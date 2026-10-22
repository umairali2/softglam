"use client";

import { useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import { addToWishlist, listWishlist, removeFromWishlist } from "@/lib/api";

export function WishlistButton({ productId }: { productId: string }) {
  const router = useRouter();
  const [saved, setSaved] = useState<boolean | null>(null);

  useEffect(() => {
    listWishlist()
      .then((r) => setSaved(r.data.some((p) => p.id === productId)))
      .catch(() => setSaved(false));
  }, [productId]);

  const toggle = async () => {
    try {
      if (saved) {
        await removeFromWishlist(productId);
        setSaved(false);
      } else {
        await addToWishlist(productId);
        setSaved(true);
      }
    } catch {
      router.push("/login");
    }
  };

  if (saved === null) return null;

  return (
    <button onClick={toggle} aria-pressed={saved}>
      {saved ? "♥ Saved" : "♡ Save for later"}
    </button>
  );
}
