import Link from "next/link";
import { listProducts } from "@/lib/api";
import { formatPkr } from "@/lib/money";

export default async function SearchPage({ searchParams }: { searchParams: Promise<{ q?: string }> }) {
  const { q } = await searchParams;
  const products = q ? await listProducts({ q }).then((r) => r.data).catch(() => []) : [];

  return (
    <main id="main" className="shell">
      <h1>Search</h1>
      <form action="/search" method="get">
        <input name="q" defaultValue={q ?? ""} placeholder="Search products…" required minLength={2} />
        <button className="text-link" type="submit">Search</button>
      </form>
      {q && products.length === 0 && <p>No products match “{q}”.</p>}
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
    </main>
  );
}
