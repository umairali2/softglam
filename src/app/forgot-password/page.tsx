"use client";

import { useState } from "react";
import Link from "next/link";
import { forgotPassword } from "@/lib/api";

const input = { width: "100%", marginBottom: "0.75rem", padding: "0.5rem" } as const;

export default function ForgotPasswordPage() {
  const [email, setEmail] = useState("");
  const [sent, setSent] = useState(false);
  const [pending, setPending] = useState(false);

  async function submit(e: React.FormEvent) {
    e.preventDefault();
    setPending(true);
    await forgotPassword(email).catch(() => undefined);
    setSent(true);
    setPending(false);
  }

  return (
    <main id="main" className="shell">
      <h1>Reset your password</h1>
      {sent ? (
        <p>If an account exists for that email, a reset link is on its way.</p>
      ) : (
        <form onSubmit={submit} style={{ maxWidth: "24rem" }}>
          <input style={input} type="email" required placeholder="Email" value={email} onChange={(e) => setEmail(e.target.value)} />
          <button className="text-link" type="submit" disabled={pending}>{pending ? "Sending…" : "Send reset link"}</button>
        </form>
      )}
      <p><Link href="/login">Back to sign in</Link></p>
    </main>
  );
}
