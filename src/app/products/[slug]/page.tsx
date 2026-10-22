import Link from "next/link";
import { notFound } from "next/navigation";
import { getProduct } from "@/lib/api";
import { formatPkr } from "@/lib/money";
import { AddToCartButton } from "@/components/add-to-cart-button";
import { WishlistButton } from "@/components/wishlist-button";

export default async function ProductPage({ params }: { params: Promise<{ slug: string }> }) {
  const { slug } = await params;
  const product = await getProduct(slug)
    .then((response) => response.data)
    .catch(() => null);

  if (!product) notFound();

  const variant = product.variants.find((v) => v.is_default) ?? product.variants[0];
  const outOfStock = variant?.availability?.status === "out_of_stock";

  return (
    <main id="main" className="shell">
      <nav aria-label="Breadcrumb">
        <Link href="/">Home</Link> /{" "}
        {product.categories[0] ? (
          <Link href={`/categories/${product.categories[0].slug}`}>{product.categories[0].name}</Link>
        ) : "Shop"}
      </nav>
      {product.images?.[0] && (
        // eslint-disable-next-line @next/next/no-img-element
        <img
          src={product.images[0].url}
          alt={product.images[0].alt ?? product.title}
          style={{ maxWidth: "420px", borderRadius: "8px" }}
        />
      )}
      <section>
        <h1>{product.title}</h1>
        {product.subtitle && <p>{product.subtitle}</p>}
        {variant && (
          <div>
            <p>
              <strong>{formatPkr(variant.price.amount)}</strong>
              {variant.price.compare_at_amount && (
                <> <s>{formatPkr(variant.price.compare_at_amount)}</s></>
              )}
            </p>
            <AddToCartButton variantId={variant.id} disabled={outOfStock} /> <WishlistButton productId={product.id} />
          </div>
        )}
        {product.short_description && <p>{product.short_description}</p>}
      </section>
    </main>
  );
}
