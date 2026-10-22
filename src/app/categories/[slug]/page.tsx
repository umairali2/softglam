import Link from "next/link";
import { listProducts } from "@/lib/api";
import { formatPkr } from "@/lib/money";

export default async function CategoryPage({ params }: { params: Promise<{ slug: string }> }) {
  const { slug } = await params;
  const products = await listProducts(slug).then((r) => r.data).catch(() => []);
  const title = products[0]?.categories.find((c) => c.slug === slug)?.name ?? slug;

  return (
    <main id="main" className="shell">
      <h1>{title}</h1>
      {products.length === 0 ? (
        <p>Nothing here yet. <Link href="/">Back to shop</Link></p>
      ) : (
        <ul>
          {products.map((product) => {
            const variant = product.variants.find((v) => v.is_default) ?? product.variants[0];
            return (
              <li key={product.id}>
                <Link href={`/products/${product.slug}`}>
                  {product.title} — {variant ? formatPkr(variant.price.amount) : ""}
                  {variant?.availability?.status === "out_of_stock" ? " (sold out)" : ""}
                </Link>
              </li>
            );
          })}
        </ul>
      )}
    </main>
  );
}
