"use client";

import { useState } from "react";
import Link from "next/link";
import { useRouter } from "next/navigation";
import { ApiError, register } from "@/lib/api";

const input = { width: "100%", marginBottom: "0.75rem", padding: "0.5rem" } as const;

export default function RegisterPage() {
  const router = useRouter();
  const [form, setForm] = useState({ name: "", email: "", password: "", password_confirmation: "" });
  const [pending, setPending] = useState(false);
  const [errors, setErrors] = useState<Record<string, string[]>>({});
  const [error, setError] = useState<string | null>(null);

  const set = (key: keyof typeof form) => (e: React.ChangeEvent<HTMLInputElement>) =>
    setForm((f) => ({ ...f, [key]: e.target.value }));

  async function submit(e: React.FormEvent) {
    e.preventDefault();
    setPending(true);
    setError(null);
    setErrors({});
    try {
      await register(form);
      router.push("/account");
    } catch (err) {
      if (err instanceof ApiError) {
        setError(err.message);
        setErrors(err.errors ?? {});
      } else {
        setError("Registration failed. Please try again.");
      }
      setPending(false);
    }
  }

  const field = (key: string, props: React.InputHTMLAttributes<HTMLInputElement>) => (
    <>
      <input style={input} value={form[key as keyof typeof form]} onChange={set(key as keyof typeof form)} {...props} />
      {errors[key]?.[0] && <p role="alert">{errors[key][0]}</p>}
    </>
  );

  return (
    <main id="main" className="shell">
      <h1>Create your account</h1>
      <form onSubmit={submit} style={{ maxWidth: "24rem" }}>
        {field("name", { required: true, placeholder: "Full name" })}
        {field("email", { type: "email", required: true, placeholder: "Email" })}
        {field("password", { type: "password", required: true, placeholder: "Password (min 12 characters)" })}
        {field("password_confirmation", { type: "password", required: true, placeholder: "Confirm password" })}
        {error && <p role="alert">{error}</p>}
        <button className="text-link" type="submit" disabled={pending}>{pending ? "Creating…" : "Create account"}</button>
      </form>
      <p>Already registered? <Link href="/login">Sign in</Link></p>
    </main>
  );
}
