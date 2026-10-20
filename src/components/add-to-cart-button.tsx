"use client";

import { useState } from "react";
import { useRouter } from "next/navigation";
import { addCartItem, ApiError } from "@/lib/api";

export function AddToCartButton({ variantId, disabled }: { variantId: string; disabled?: boolean }) {
  const [pending, setPending] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const router = useRouter();

  async function handleAdd() {
    setPending(true);
    setError(null);
    try {
      await addCartItem(variantId, 1);
      router.push("/cart");
    } catch (e) {
      setError(e instanceof ApiError ? e.message : "Could not add this item. Please try again.");
      setPending(false);
    }
  }

  return (
    <div>
      <button className="text-link" onClick={handleAdd} disabled={pending || disabled}>
        {pending ? "Adding…" : disabled ? "Out of stock" : "Add to bag"}
      </button>
      {error && <p role="alert">{error}</p>}
    </div>
  );
}
