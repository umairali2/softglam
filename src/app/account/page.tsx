"use client";

import { useCallback, useEffect, useState } from "react";
import Link from "next/link";
import { useRouter } from "next/navigation";
import {
  ApiError,
  createAddress,
  deleteAddress,
  getUser,
  listAddresses,
  listMyOrders,
  logout,
  type AuthUser,
  type CustomerAddress,
  type Order,
} from "@/lib/api";
import { formatPkr } from "@/lib/money";

const STATUS_LABELS: Record<string, string> = {
  pending_verification: "Pending confirmation",
  confirmed: "Confirmed",
  fulfilled: "Fulfilled",
  cancelled: "Cancelled",
};

export default function AccountPage() {
  const router = useRouter();
  const [user, setUser] = useState<AuthUser | null>(null);
  const [orders, setOrders] = useState<Order[]>([]);
  const [addresses, setAddresses] = useState<CustomerAddress[]>([]);
  const [newAddress, setNewAddress] = useState({
    recipient_name: "", phone: "", line1: "", line2: "", area: "", city: "Karachi", postal_code: "", is_default: false,
  });
  const [addressError, setAddressError] = useState<string | null>(null);

  useEffect(() => {
    getUser()
      .then((r) => setUser(r.data))
      .catch((e) => {
        if (e instanceof ApiError && e.status === 401) router.push("/login");
      });
    listMyOrders().then((r) => setOrders(r.data)).catch(() => undefined);
    listAddresses().then((r) => setAddresses(r.data)).catch(() => undefined);
  }, [router]);

  const signOut = useCallback(async () => {
    await logout().catch(() => undefined);
    router.push("/");
  }, [router]);

  async function addAddress(e: React.FormEvent) {
    e.preventDefault();
    setAddressError(null);
    try {
      const r = await createAddress({ ...newAddress, line2: newAddress.line2 || null, area: newAddress.area || null, postal_code: newAddress.postal_code || null });
      setAddresses((a) => [...a, r.data]);
      setNewAddress({ recipient_name: "", phone: "", line1: "", line2: "", area: "", city: "Karachi", postal_code: "", is_default: false });
    } catch (err) {
      setAddressError(err instanceof ApiError ? err.message : "Could not save the address.");
    }
  }

  async function removeAddress(id: string) {
    await deleteAddress(id).catch(() => undefined);
    setAddresses((a) => a.filter((x) => x.id !== id));
  }

  if (!user) return <main id="main" className="shell"><p>Loading your account…</p></main>;

  return (
    <main id="main" className="shell">
      <h1>Hi, {user.name}</h1>
      <p>{user.email}</p>
      {!user.email_verified_at && <p role="alert">Your email isn&apos;t verified yet — check your inbox for the verification link.</p>}
      <h2>Your orders</h2>
      {orders.length === 0 ? (
        <p>No orders yet. <Link href="/">Start shopping</Link></p>
      ) : (
        <ul>
          {orders.map((order) => (
            <li key={order.id}>
              <Link href={`/orders/${order.order_number}`}>{order.order_number}</Link> —{" "}
              {STATUS_LABELS[order.status] ?? order.status} — {formatPkr(order.totals.total_amount)}
            </li>
          ))}
        </ul>
      )}
      <h2>Saved addresses</h2>
      {addresses.length === 0 ? <p>No saved addresses yet.</p> : (
        <ul>
          {addresses.map((a) => (
            <li key={a.id}>
              {a.recipient_name} — {a.line1}{a.area ? `, ${a.area}` : ""}, {a.city} {a.is_default ? "(default)" : ""}{" "}
              <button onClick={() => removeAddress(a.id)}>Remove</button>
            </li>
          ))}
        </ul>
      )}
      <form onSubmit={addAddress}>
        <h3>Add an address</h3>
        <input style={{ display: "block", marginBottom: "0.5rem" }} required placeholder="Recipient name" value={newAddress.recipient_name} onChange={(e) => setNewAddress((a) => ({ ...a, recipient_name: e.target.value }))} />
        <input style={{ display: "block", marginBottom: "0.5rem" }} required placeholder="Phone" value={newAddress.phone} onChange={(e) => setNewAddress((a) => ({ ...a, phone: e.target.value }))} />
        <input style={{ display: "block", marginBottom: "0.5rem" }} required placeholder="Address line 1" value={newAddress.line1} onChange={(e) => setNewAddress((a) => ({ ...a, line1: e.target.value }))} />
        <input style={{ display: "block", marginBottom: "0.5rem" }} placeholder="Line 2 (optional)" value={newAddress.line2} onChange={(e) => setNewAddress((a) => ({ ...a, line2: e.target.value }))} />
        <input style={{ display: "block", marginBottom: "0.5rem" }} placeholder="Area (optional)" value={newAddress.area} onChange={(e) => setNewAddress((a) => ({ ...a, area: e.target.value }))} />
        <input style={{ display: "block", marginBottom: "0.5rem" }} required placeholder="City" value={newAddress.city} onChange={(e) => setNewAddress((a) => ({ ...a, city: e.target.value }))} />
        <label style={{ display: "block", marginBottom: "0.5rem" }}>
          <input type="checkbox" checked={newAddress.is_default} onChange={(e) => setNewAddress((a) => ({ ...a, is_default: e.target.checked }))} /> Default address
        </label>
        {addressError && <p role="alert">{addressError}</p>}
        <button className="text-link" type="submit">Save address</button>
      </form>
      <button className="text-link" onClick={signOut}>Sign out</button>
    </main>
  );
}
