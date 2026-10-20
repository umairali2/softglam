"use client";

import { useCallback, useEffect, useState } from "react";
import Link from "next/link";
import { getCart, removeCartItem, updateCartItem, type Cart } from "@/lib/api";
import { formatPkr } from "@/lib/money";

export default function CartPage() {
  const [cart, setCart] = useState<Cart | null>(null);
  const [error, setError] = useState<string | null>(null);

  const refresh = useCallback(() => {
    getCart().then((r) => setCart(r.data)).catch(() => setError("Your cart could not be loaded."));
  }, []);

  useEffect(refresh, [refresh]);

  async function setQuantity(itemId: string, quantity: number) {
    try {
      const r = await updateCartItem(itemId, quantity);
      setCart(r.data);
    } catch {
      setError("That quantity is not available.");
      refresh();
    }
  }

  async function remove(itemId: string) {
    await removeCartItem(itemId);
    refresh();
  }

  if (error && !cart) return <main id="main" className="shell"><p role="alert">{error}</p></main>;
  if (!cart) return <main id="main" className="shell"><p>Loading your bag…</p></main>;

  return (
    <main id="main" className="shell">
      <h1>Your bag</h1>
      {cart.items.length === 0 ? (
        <p>Your bag is empty. <Link href="/">Continue shopping</Link></p>
      ) : (
        <>
          <ul>
            {cart.items.map((item) => (
              <li key={item.id}>
                <Link href={`/products/${item.variant.product.slug}`}>
                  {item.variant.product.title}{item.variant.title ? ` — ${item.variant.title}` : ""}
                </Link>
                <span> {formatPkr(item.unit_price.amount)} </span>
                <button onClick={() => setQuantity(item.id, item.quantity - 1)} disabled={item.quantity <= 1} aria-label="Decrease quantity">−</button>
                <span>{item.quantity}</span>
                <button onClick={() => setQuantity(item.id, item.quantity + 1)} aria-label="Increase quantity">+</button>
                <span> {formatPkr(item.line_total.amount)}</span>
                <button onClick={() => remove(item.id)}>Remove</button>
              </li>
            ))}
          </ul>
          {error && <p role="alert">{error}</p>}
          <p>Subtotal: <strong>{formatPkr(cart.totals.subtotal_amount)}</strong></p>
          <p>Shipping is calculated at checkout — free on orders over Rs 5,000.</p>
          <Link className="text-link" href="/checkout">Checkout</Link>
        </>
      )}
    </main>
  );
}
