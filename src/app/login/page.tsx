"use client";

import { useState } from "react";
import Link from "next/link";
import { useRouter } from "next/navigation";
import { ApiError, login } from "@/lib/api";

const input = { width: "100%", marginBottom: "0.75rem", padding: "0.5rem" } as const;

export default function LoginPage() {
  const router = useRouter();
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [pending, setPending] = useState(false);
  const [error, setError] = useState<string | null>(null);

  async function submit(e: React.FormEvent) {
    e.preventDefault();
    setPending(true);
    setError(null);
    try {
      await login({ email, password });
      router.push("/account");
    } catch (err) {
      setError(err instanceof ApiError ? "The provided credentials are incorrect." : "Sign-in failed. Please try again.");
      setPending(false);
    }
  }

  return (
    <main id="main" className="shell">
      <h1>Sign in</h1>
      <form onSubmit={submit} style={{ maxWidth: "24rem" }}>
        <input style={input} type="email" required placeholder="Email" value={email} onChange={(e) => setEmail(e.target.value)} />
        <input style={input} type="password" required placeholder="Password" value={password} onChange={(e) => setPassword(e.target.value)} />
        {error && <p role="alert">{error}</p>}
        <button className="text-link" type="submit" disabled={pending}>{pending ? "Signing in…" : "Sign in"}</button>
      </form>
      <p><Link href="/forgot-password">Forgot your password?</Link></p>
      <p>New to SoftGlam? <Link href="/register">Create an account</Link></p>
    </main>
  );
}
