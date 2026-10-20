"use client";

import { Suspense } from "react";
import Link from "next/link";
import { useSearchParams } from "next/navigation";

function Status() {
  const status = useSearchParams().get("status");
  if (status === "verified") {
    return <p>Your email is verified. You&apos;re all set — <Link href="/account">go to your account</Link>.</p>;
  }
  if (status === "invalid") {
    return <p>That verification link is invalid or has already been used. Request a new link from your account.</p>;
  }
  return <p>Checking your verification link…</p>;
}

export default function VerifyEmailPage() {
  return (
    <main id="main" className="shell">
      <h1>Email verification</h1>
      <Suspense fallback={<p>Loading…</p>}>
        <Status />
      </Suspense>
    </main>
  );
}
