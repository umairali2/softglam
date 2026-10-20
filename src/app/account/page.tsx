"use client";

import { useCallback, useEffect, useState } from "react";
import Link from "next/link";
import { useRouter } from "next/navigation";
import { ApiError, getUser, listMyOrders, logout, type AuthUser, type Order } from "@/lib/api";
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

  useEffect(() => {
    getUser()
      .then((r) => setUser(r.data))
      .catch((e) => {
        if (e instanceof ApiError && e.status === 401) router.push("/login");
      });
    listMyOrders().then((r) => setOrders(r.data)).catch(() => undefined);
  }, [router]);

  const signOut = useCallback(async () => {
    await logout().catch(() => undefined);
    router.push("/");
  }, [router]);

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
      <button className="text-link" onClick={signOut}>Sign out</button>
    </main>
  );
}
