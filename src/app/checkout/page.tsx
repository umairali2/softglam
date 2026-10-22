"use client";

import { useEffect, useState } from "react";
import Link from "next/link";
import { useRouter } from "next/navigation";
import { ApiError, checkoutCod, getCart, getUser, listAddresses, type Cart, type CustomerAddress } from "@/lib/api";
import { formatPkr } from "@/lib/money";

const input = { width: "100%", marginBottom: "0.75rem", padding: "0.5rem" } as const;

export default function CheckoutPage() {
  const router = useRouter();
  const [cart, setCart] = useState<Cart | null>(null);
  const [pending, setPending] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [fieldErrors, setFieldErrors] = useState<Record<string, string[]>>({});
  const [addresses, setAddresses] = useState<CustomerAddress[]>([]);
  const [addressId, setAddressId] = useState<string | null>(null);
  const [form, setForm] = useState({
    contact_email: "",
    contact_phone: "",
    recipient_name: "",
    phone: "",
    line1: "",
    line2: "",
    area: "",
    city: "Karachi",
    postal_code: "",
  });

  useEffect(() => {
    getCart().then((r) => setCart(r.data)).catch(() => setError("Your cart could not be loaded."));
    getUser()
      .then(async (r) => {
        setForm((f) => ({ ...f, contact_email: f.contact_email || r.data.email }));
        const list = await listAddresses().catch(() => null);
        if (list?.data.length) {
          setAddresses(list.data);
          const def = list.data.find((a) => a.is_default) ?? list.data[0];
          setAddressId(def.id);
        }
      })
      .catch(() => undefined); // guest checkout
  }, []);

  const set = (key: keyof typeof form) => (e: React.ChangeEvent<HTMLInputElement>) =>
    setForm((f) => ({ ...f, [key]: e.target.value }));

  const fieldError = (key: string) => fieldErrors[key]?.[0];

  async function submit(e: React.FormEvent) {
    e.preventDefault();
    setPending(true);
    setError(null);
    setFieldErrors({});
    try {
      const r = await checkoutCod({
        contact_email: form.contact_email,
        contact_phone: form.contact_phone,
        address_id: addressId ?? undefined,
        address: addressId ? undefined : {
          recipient_name: form.recipient_name,
          phone: form.phone,
          line1: form.line1,
          line2: form.line2 || undefined,
          area: form.area || undefined,
          city: form.city,
          postal_code: form.postal_code || undefined,
        },
      });
      router.push(`/orders/${r.data.order_number}`);
    } catch (err) {
      if (err instanceof ApiError) {
        setError(err.message);
        setFieldErrors(err.errors ?? {});
      } else {
        setError("Checkout failed. Please try again.");
      }
      setPending(false);
    }
  }

  if (!cart) return <main id="main" className="shell"><p>Loading checkout…</p></main>;
  if (cart.items.length === 0) {
    return <main id="main" className="shell"><p>Your bag is empty. <Link href="/">Continue shopping</Link></p></main>;
  }

  const shipping = cart.totals.subtotal_amount >= 500000 ? 0 : 30000;

  return (
    <main id="main" className="shell">
      <h1>Checkout</h1>
      <form onSubmit={submit}>
        <fieldset>
          <legend>Contact</legend>
          <input style={input} type="email" required placeholder="Email" value={form.contact_email} onChange={set("contact_email")} />
          {fieldError("contact_email") && <p role="alert">{fieldError("contact_email")}</p>}
          <input style={input} type="tel" required placeholder="Mobile number" value={form.contact_phone} onChange={set("contact_phone")} />
          {fieldError("contact_phone") && <p role="alert">{fieldError("contact_phone")}</p>}
        </fieldset>
        <fieldset>
          <legend>Delivery address</legend>
          {addresses.length > 0 && (
            <>
              {addresses.map((a) => (
                <label key={a.id} style={{ display: "block", marginBottom: "0.5rem" }}>
                  <input
                    type="radio"
                    name="saved_address"
                    checked={addressId === a.id}
                    onChange={() => setAddressId(a.id)}
                  />{" "}
                  {a.recipient_name} — {a.line1}, {a.city} {a.is_default ? "(default)" : ""}
                </label>
              ))}
              <label style={{ display: "block", marginBottom: "0.5rem" }}>
                <input
                  type="radio"
                  name="saved_address"
                  checked={addressId === null}
                  onChange={() => setAddressId(null)}
                />{" "}
                Use a different address
              </label>
            </>
          )}
          {addressId === null && (
            <>
              <input style={input} required placeholder="Recipient name" value={form.recipient_name} onChange={set("recipient_name")} />
              {fieldError("address.recipient_name") && <p role="alert">{fieldError("address.recipient_name")}</p>}
              <input style={input} required placeholder="Recipient phone" value={form.phone} onChange={set("phone")} />
              <input style={input} required placeholder="Address line 1" value={form.line1} onChange={set("line1")} />
              {fieldError("address.line1") && <p role="alert">{fieldError("address.line1")}</p>}
              <input style={input} placeholder="Address line 2 (optional)" value={form.line2} onChange={set("line2")} />
              <input style={input} placeholder="Area / neighbourhood (optional)" value={form.area} onChange={set("area")} />
              <input style={input} required placeholder="City" value={form.city} onChange={set("city")} />
              {fieldError("address.city") && <p role="alert">{fieldError("address.city")}</p>}
              <input style={input} placeholder="Postal code (optional)" value={form.postal_code} onChange={set("postal_code")} />
            </>
          )}
        </fieldset>
        <fieldset>
          <legend>Payment</legend>
          <p><strong>Cash on delivery</strong> — pay the courier when your order arrives.</p>
          <p>We&apos;ll call to confirm your order within 24 hours. Online payment is coming soon.</p>
        </fieldset>
        <p>
          Subtotal: {formatPkr(cart.totals.subtotal_amount)} · Shipping: {shipping === 0 ? "Free" : formatPkr(shipping)} ·{" "}
          <strong>Total: {formatPkr(cart.totals.subtotal_amount + shipping)}</strong>
        </p>
        {error && <p role="alert">{error}</p>}
        <button className="text-link" type="submit" disabled={pending}>
          {pending ? "Placing order…" : "Place COD order"}
        </button>
      </form>
    </main>
  );
}
