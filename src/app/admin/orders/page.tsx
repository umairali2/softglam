"use client";

import { useCallback, useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import {
  ApiError,
  cancelOrder,
  confirmOrder,
  declineOrder,
  fulfillOrder,
  getUser,
  listAdminOrders,
  type Order,
} from "@/lib/api";
import { formatPkr } from "@/lib/money";

const input = { marginRight: "0.5rem", padding: "0.25rem" } as const;

export default function AdminOrdersPage() {
  const router = useRouter();
  const [ready, setReady] = useState(false);
  const [orders, setOrders] = useState<Order[]>([]);
  const [error, setError] = useState<string | null>(null);
  const [reason, setReason] = useState<Record<string, string>>({});
  const [tracking, setTracking] = useState<Record<string, { courier_name: string; tracking_number: string }>>({});

  const refresh = useCallback(() => {
    listAdminOrders()
      .then((r) => setOrders(r.data.filter((o) => o.status === "pending_verification" || o.status === "confirmed")))
      .catch(() => setError("Could not load the verification queue."));
  }, []);

  useEffect(() => {
    getUser()
      .then((r) => {
        if (!r.data.is_staff) router.push("/");
        else setReady(true);
      })
      .catch(() => router.push("/login"));
  }, [router]);

  useEffect(() => {
    if (ready) refresh();
  }, [ready, refresh]);

  async function act(fn: () => Promise<unknown>, fallback: string) {
    setError(null);
    try {
      await fn();
      refresh();
    } catch (e) {
      setError(e instanceof ApiError ? e.message : fallback);
    }
  }

  if (!ready) return <main id="main" className="shell"><p>Loading…</p></main>;

  return (
    <main id="main" className="shell">
      <h1>COD order queue</h1>
      <p>Call each customer, then confirm or decline. Confirmed orders can be fulfilled with courier/tracking.</p>
      {error && <p role="alert">{error}</p>}
      {orders.length === 0 ? (
        <p>No orders awaiting verification.</p>
      ) : (
        <ul>
          {orders.map((order) => (
            <li key={order.id} style={{ marginBottom: "1.5rem", borderBottom: "1px solid #eee", paddingBottom: "1rem" }}>
              <p>
                <strong>{order.order_number}</strong> [{order.status}] — {formatPkr(order.totals.total_amount)} —{" "}
                {order.contact_email}, {order.contact_phone}
              </p>
              <p>
                {order.shipping_address.recipient_name}, {order.shipping_address.line1},{" "}
                {order.shipping_address.city} · {order.shipping_address.phone}
              </p>
              <ul>
                {order.items.map((item) => (
                  <li key={item.id}>{item.product_title} × {item.quantity}</li>
                ))}
              </ul>
              {order.status === "pending_verification" && (
                <div>
                  <button onClick={() => act(() => confirmOrder(order.id), "Confirm failed.")}>Confirm</button>
                  <input
                    style={input}
                    placeholder="Decline / cancel reason"
                    value={reason[order.id] ?? ""}
                    onChange={(e) => setReason((r) => ({ ...r, [order.id]: e.target.value }))}
                  />
                  <button onClick={() => act(() => declineOrder(order.id, reason[order.id] ?? ""), "Decline failed.")}>
                    Decline
                  </button>
                </div>
              )}
              {order.status === "confirmed" && (
                <div style={{ marginTop: "0.5rem" }}>
                  <input
                    style={input}
                    placeholder="Courier"
                    value={tracking[order.id]?.courier_name ?? ""}
                    onChange={(e) => setTracking((t) => ({ ...t, [order.id]: { ...t[order.id], courier_name: e.target.value, tracking_number: t[order.id]?.tracking_number ?? "" } }))}
                  />
                  <input
                    style={input}
                    placeholder="Tracking #"
                    value={tracking[order.id]?.tracking_number ?? ""}
                    onChange={(e) => setTracking((t) => ({ ...t, [order.id]: { ...t[order.id], courier_name: t[order.id]?.courier_name ?? "", tracking_number: e.target.value } }))}
                  />
                  <button onClick={() => act(() => fulfillOrder(order.id, tracking[order.id] ?? {}), "Fulfill failed.")}>
                    Fulfill
                  </button>
                  <button onClick={() => act(() => cancelOrder(order.id, reason[order.id] ?? ""), "Cancel failed.")}>
                    Cancel
                  </button>
                </div>
              )}
            </li>
          ))}
        </ul>
      )}
    </main>
  );
}
