"use client";

import { useEffect, useState } from "react";
import Link from "next/link";
import { useParams } from "next/navigation";
import { ApiError, cancelMyOrder, getOrder, type Order } from "@/lib/api";
import { formatPkr } from "@/lib/money";

const STATUS_LABELS: Record<string, string> = {
  pending_verification: "Pending confirmation",
  confirmed: "Confirmed",
  fulfilled: "Fulfilled",
  cancelled: "Cancelled",
};

export default function OrderPage() {
  const { orderNumber } = useParams<{ orderNumber: string }>();
  const [order, setOrder] = useState<Order | null>(null);
  const [notFound, setNotFound] = useState(false);
  const [cancelError, setCancelError] = useState<string | null>(null);
  const [cancelling, setCancelling] = useState(false);

  useEffect(() => {
    getOrder(orderNumber)
      .then((r) => setOrder(r.data))
      .catch((e) => {
        if (e instanceof ApiError && e.status === 404) setNotFound(true);
      });
  }, [orderNumber]);

  if (notFound) {
    return <main id="main" className="shell"><p>We couldn&apos;t find that order. <Link href="/">Back to shop</Link></p></main>;
  }
  if (!order) return <main id="main" className="shell"><p>Loading your order…</p></main>;

  return (
    <main id="main" className="shell">
      <h1>Thank you for your order</h1>
      <p>
        Order <strong>{order.order_number}</strong> — {STATUS_LABELS[order.status] ?? order.status}
      </p>
      {order.payment_method === "cod" && order.status === "pending_verification" && (
        <p>We&apos;ll call {order.contact_phone} within 24 hours to confirm your order.</p>
      )}
      {order.status === "pending_verification" && (
        <p>
          <button
            onClick={async () => {
              setCancelling(true);
              setCancelError(null);
              try {
                const r = await cancelMyOrder(order.order_number);
                setOrder(r.data);
              } catch {
                setCancelError("This order can no longer be cancelled. Please contact support.");
              }
              setCancelling(false);
            }}
            disabled={cancelling}
          >
            {cancelling ? "Cancelling…" : "Cancel this order"}
          </button>
          {cancelError && <span role="alert"> {cancelError}</span>}
        </p>
      )}
      <h2>Items</h2>
      <ul>
        {order.items.map((item) => (
          <li key={item.id}>
            {item.product_title}{item.variant_title ? ` — ${item.variant_title}` : ""} × {item.quantity} —{" "}
            {formatPkr(item.line_total.amount)}
          </li>
        ))}
      </ul>
      <p>
        Subtotal {formatPkr(order.totals.subtotal_amount)} · Shipping{" "}
        {order.totals.shipping_amount === 0 ? "Free" : formatPkr(order.totals.shipping_amount)} ·{" "}
        <strong>Total {formatPkr(order.totals.total_amount)}</strong>
      </p>
      <h2>Delivery to</h2>
      <p>
        {order.shipping_address.recipient_name}<br />
        {order.shipping_address.line1}
        {order.shipping_address.line2 ? <><br />{order.shipping_address.line2}</> : null}
        {order.shipping_address.area ? <>, {order.shipping_address.area}</> : null}<br />
        {order.shipping_address.city}
        {order.shipping_address.postal_code ? ` ${order.shipping_address.postal_code}` : ""}<br />
        {order.shipping_address.phone}
      </p>
      <Link className="text-link" href="/">Continue shopping</Link>
    </main>
  );
}
