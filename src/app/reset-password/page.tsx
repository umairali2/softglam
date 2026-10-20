"use client";

import { Suspense, useState } from "react";
import Link from "next/link";
import { useRouter, useSearchParams } from "next/navigation";
import { ApiError, resetPassword } from "@/lib/api";

const input = { width: "100%", marginBottom: "0.75rem", padding: "0.5rem" } as const;

function ResetForm() {
  const router = useRouter();
  const params = useSearchParams();
  const token = params.get("token") ?? "";
  const email = params.get("email") ?? "";
  const [password, setPassword] = useState("");
  const [confirm, setConfirm] = useState("");
  const [pending, setPending] = useState(false);
  const [error, setError] = useState<string | null>(null);

  async function submit(e: React.FormEvent) {
    e.preventDefault();
    setPending(true);
    setError(null);
    try {
      await resetPassword({ token, email, password, password_confirmation: confirm });
      router.push("/login");
    } catch (err) {
      setError(err instanceof ApiError ? err.message : "Reset failed. The link may have expired — request a new one.");
      setPending(false);
    }
  }

  if (!token || !email) {
    return <p>That reset link is incomplete. <Link href="/forgot-password">Request a new one</Link></p>;
  }

  return (
    <form onSubmit={submit} style={{ maxWidth: "24rem" }}>
      <input style={input} type="password" required minLength={12} placeholder="New password" value={password} onChange={(e) => setPassword(e.target.value)} />
      <input style={input} type="password" required placeholder="Confirm new password" value={confirm} onChange={(e) => setConfirm(e.target.value)} />
      {error && <p role="alert">{error}</p>}
      <button className="text-link" type="submit" disabled={pending}>{pending ? "Resetting…" : "Reset password"}</button>
    </form>
  );
}

export default function ResetPasswordPage() {
  return (
    <main id="main" className="shell">
      <h1>Choose a new password</h1>
      <Suspense fallback={<p>Loading…</p>}>
        <ResetForm />
      </Suspense>
    </main>
  );
}
