import Link from "next/link";
import { ArrowIcon, SparkIcon } from "@/components/icons";
import { collections } from "@/lib/catalog";
import { listProducts, type ApiProduct } from "@/lib/api";
import { formatPkr } from "@/lib/money";

const toneFor = (product: ApiProduct, index: number) => {
  const category = product.categories[0]?.slug;
  if (category === "jewellery") return "warm";
  if (category === "beauty") return "cool";
  return ["sand", "pearl", "clay", "ink"][index % 4];
};
import { siteConfig } from "@/lib/site";

const jsonLd = {
  "@context": "https://schema.org",
  "@type": "WebSite",
  name: siteConfig.name,
  url: siteConfig.url,
  description: siteConfig.description,
  potentialAction: {
    "@type": "SearchAction",
    target: `${siteConfig.url}/search?q={search_term_string}`,
    "query-input": "required name=search_term_string",
  },
};

export default async function Home() {
  const products = await listProducts()
    .then((response) => response.data)
    .catch(() => [] as ApiProduct[]);

  return <main id="main">
    <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(jsonLd) }} />
    <section className="hero">
      <div className="hero-copy">
        <p className="eyebrow"><SparkIcon width="15" height="15" /> The new collection</p>
        <h1>Objects for<br /><em>every day.</em></h1>
        <p className="hero-description">Considered pieces that feel personal from the first wear—and become more yours with time.</p>
        <Link className="text-link" href="#new">Explore the collection <ArrowIcon /></Link>
      </div>
      <div className="hero-art" role="img" aria-label="Sculptural gold jewellery arranged on a warm stone surface">
        <div className="hero-orbit orbit-one" />
        <div className="hero-orbit orbit-two" />
        <div className="hero-stone" />
        <span className="art-note">Form, feeling<br />and fine detail.</span>
      </div>
    </section>

    <section className="intro shell" aria-labelledby="intro-heading">
      <p className="eyebrow">The Softglam point of view</p>
      <h2 id="intro-heading">Less noise. More <em>you.</em></h2>
      <p>We choose objects for how they feel, function and endure—not for a passing moment. Easy to wear, hard to forget.</p>
    </section>

    <section className="collections shell" aria-labelledby="collections-heading">
      <div className="section-heading">
        <div><p className="eyebrow">Find your detail</p><h2 id="collections-heading">Shop by collection</h2></div>
        <Link className="text-link desktop-link" href="#new">View all <ArrowIcon /></Link>
      </div>
      <div className="collection-grid">
        {collections.map((collection, index) => <article className="collection-card" id={collection.id} key={collection.name}>
          <div className={`collection-art collection-${collection.tone}`}>
            <span className="collection-number">0{index + 1}</span>
            <div className="collection-object" />
          </div>
          <h3>{collection.name}</h3>
          <p>{collection.description}</p>
          <Link href={`/categories/${collection.id}`} aria-label={`Shop ${collection.name}`}>Discover <ArrowIcon /></Link>
        </article>)}
      </div>
    </section>

    <section className="new-arrivals shell" id="new" aria-labelledby="new-heading">
      <div className="section-heading">
        <div><p className="eyebrow">Just arrived</p><h2 id="new-heading">New, and noteworthy</h2></div>
        <Link className="text-link desktop-link" href="#new">Shop new arrivals <ArrowIcon /></Link>
      </div>
      <div className="product-grid">
        {products.length === 0 && <p>New pieces are on their way. Please check back soon.</p>}
        {products.map((product, index) => {
          const variant = product.variants.find((v) => v.is_default) ?? product.variants[0];
          return <article className="product-card" key={product.id}>
            <Link href={`/products/${product.slug}`} aria-label={`View ${product.title}`}>
              <div className={`product-art product-${toneFor(product, index)}`}>
                {variant?.availability?.status === "out_of_stock" && <span className="badge">Sold out</span>}
                <span className={`product-object product-object-${(index % 4) + 1}`} />
                <span className="quick-add">Quick view</span>
              </div>
              <div className="product-info">
                <div><h3>{product.title}</h3><p>{product.categories[0]?.name ?? product.brand?.name}</p></div>
                {variant && <span>{formatPkr(variant.price.amount)}</span>}
              </div>
            </Link>
          </article>;
        })}
      </div>
    </section>

    <section className="story" id="story" aria-labelledby="story-heading">
      <div className="story-art"><div className="story-object" /><span>Made to stay</span></div>
      <div className="story-copy">
        <p className="eyebrow">Our approach</p>
        <h2 id="story-heading">Good design should feel <em>effortless.</em></h2>
        <p>Softglam is a study in considered simplicity. We look for honest materials, thoughtful proportions and the small details that make an object a pleasure to use.</p>
        <p>Because the things you reach for every day should be the things you love most.</p>
        <Link className="text-link" href="#">Read our story <ArrowIcon /></Link>
      </div>
    </section>

    <section className="values shell" aria-label="Our commitments">
      <div><span>01</span><h3>Considered design</h3><p>Purposeful details, never decoration for decoration&apos;s sake.</p></div>
      <div><span>02</span><h3>Materials that matter</h3><p>Selected for their feel, function and ability to wear beautifully.</p></div>
      <div><span>03</span><h3>Here for the long run</h3><p>Care guidance and thoughtful support, before and after you choose.</p></div>
    </section>
  </main>;
}
