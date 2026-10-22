"use client";

import Link from "next/link";
import { useEffect, useState } from "react";
import { listWishlist, type ApiProduct } from "@/lib/api";
import { formatPkr } from "@/lib/money";

export default function WishlistPage() {
  const [products, setProducts] = useState<ApiProduct[] | null>(null);
  const [guest, setGuest] = useState(false);

  useEffect(() => {
    listWishlist()
      .then((r) => setProducts(r.data))
      .catch(() => setGuest(true));
  }, []);

  if (guest) {
    return <main id="main" className="shell"><p><Link href="/login">Sign in</Link> to see your saved items.</p></main>;
  }

  return (
    <main id="main" className="shell">
      <h1>Saved items</h1>
      {products === null ? <p>Loading…</p> : products.length === 0 ? (
        <p>Nothing saved yet. <Link href="/">Browse the shop</Link></p>
      ) : (
        <ul>
          {products.map((product) => {
            const variant = product.variants.find((v) => v.is_default) ?? product.variants[0];
            return (
              <li key={product.id}>
                <Link href={`/products/${product.slug}`}>
                  {product.title} — {variant ? formatPkr(variant.price.amount) : ""}
                </Link>
              </li>
            );
          })}
        </ul>
      )}
    </main>
  );
}
