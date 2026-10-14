# SoftGlam Product Requirements Document

**Status:** Draft 1

**Product:** SoftGlam

**Product type:** Category-flexible direct-to-consumer e-commerce platform

## 1. Purpose

SoftGlam must let customers discover, evaluate, purchase, receive, and return physical products while enabling staff to operate the business without routine developer assistance. The initial system must support jewellery and beauty through configurable catalog attributes, variants, and content without hard-coding category fields into shared commerce flows.

### 1.1 Approved launch assortment

The first public catalog is a curated mid-premium assortment of 24 products for Pakistan:

- 14 jewellery products: 4 earrings, 4 necklaces, 3 bracelets, and 3 rings
- 10 beauty products: 5 makeup and 5 skincare products
- Mixed sourcing: SoftGlam-owned products and selected external brands
- Variants do not count as separate products in the 24-product target
- Fragrance, body care, hair care, eyewear, and general fashion accessories are outside the initial launch assortment

Representative jewellery coverage:

- Earrings: studs, hoops, drops, and one statement style
- Necklaces: chain, pendant, choker/short necklace, and adjustable-length style
- Bracelets: chain, cuff/bangle, and adjustable style
- Rings: fixed-size, adjustable, and statement style

Representative beauty coverage:

- Makeup: lip color, lip treatment/gloss, complexion product, cheek product, and eye product
- Skincare: cleanser, serum/treatment, moisturizer, sunscreen/day protection, and mask or targeted treatment

The named product mix is a planning baseline. Final sellable products require supplier, margin, safety, photography, content, and stock approval.

### 1.2 Product-type taxonomy

Top-level navigation categories and product types are distinct:

- `Jewellery`
  - `Earrings`
  - `Necklaces`
  - `Bracelets`
  - `Rings`
- `Beauty`
  - `Makeup`
  - `Skincare`

Categories organize customer navigation. Product types define required attributes and validation. Collections such as New Arrivals, Gifts, Everyday Edit, or seasonal campaigns merchandise products without changing their product type.

Shared product fields include brand, title, slug, subtitle, description, highlights, care/use instructions, warnings, country of origin, media, SEO, publication state, and related products. Every sellable variant has its own SKU, price, inventory, status, and optional media assignment.

### 1.3 Jewellery attributes and variants

Shared jewellery attributes:

- Jewellery type and style
- Base material
- Finish/plating and color
- Stone/gem type where applicable
- Dimensions and weight
- Closure type where applicable
- Hypoallergenic or nickel-free claim only when substantiated
- Water/tarnish resistance claim only when substantiated
- Care instructions
- Packaging and warranty information

Variant-driving options may include ring size, bracelet/necklace length, material, finish, color, or pack quantity. A choice is a true variant whenever SKU, price, inventory, barcode, weight, media, or availability differs.

### 1.4 Beauty attributes and variants

Shared makeup attributes:

- Makeup subtype, format, finish, coverage, shade name/code, and color family where applicable
- Net volume or weight
- Ingredient list and highlighted ingredients
- Skin type/concern suitability where supportable
- Application instructions, warnings, and country of origin
- Cruelty-free, vegan, halal, dermatological, SPF, or similar claims only when substantiated
- Period-after-opening and manufacturer expiry guidance where provided

Shared skincare attributes:

- Skincare subtype, format, skin types, concerns, routine step, and usage frequency
- Net volume or weight
- Full ingredient list, highlighted active ingredients, and stated concentrations only when substantiated
- Application instructions, patch-test guidance, warnings, and country of origin
- Period-after-opening and manufacturer expiry guidance where provided

Variant-driving options may include shade, volume, pack size, formulation, or finish. Shade variants require variant-specific swatches and may require variant-specific media. Products may be listed separately only when their content, imagery, positioning, or discoverability is materially different; separate listings must not be used to bypass variant inventory integrity.

### 1.5 Accepted launch inventory limitation

Beauty batch/lot and expiry dates will be controlled manually outside SoftGlam at launch. SoftGlam stores product-level shelf-life, period-after-opening, and expiry guidance but does not initially allocate stock by received batch.

This is an explicitly accepted operational risk with these mandatory controls:

- Staff records supplier, batch/lot, received quantity, received date, and expiry date in an owned inventory register.
- Staff checks the register and physical product before dispatch and follows earliest-expiry-first handling.
- Expired, damaged, recalled, or questionable stock is physically quarantined and removed from sellable inventory immediately.
- Supplier invoices and recall contact details remain traceable.
- The process has a named owner and is included in launch training and periodic reconciliation.
- Basic in-system lot and expiry tracking must be reconsidered before catalog growth, multiple receipts with materially different expiry dates, additional stock locations, or any scale where the manual register becomes unreliable.

### 1.6 Target customer strategy

SoftGlam initially serves women aged 18–40 in Karachi, with women aged 25–40 as the primary segment and women aged 18–24 as the secondary segment. The controlled Karachi pilot validates assortment, trust, checkout, and fulfillment before broader geographic expansion.

The primary customer need is everyday self-care: dependable jewellery and beauty products that make daily routines and personal style feel considered without requiring luxury-level effort or specialist knowledge. Product authenticity is the principal trust barrier. Google Search is the expected initial discovery channel. Gifting is outside the launch customer strategy and may be reconsidered after self-purchase behavior is understood.

Age and gender guide initial merchandising and research; they must not be used to deny otherwise eligible customers, infer sensitive traits, or create inaccessible experiences.

#### Primary segment — considered everyday buyer

**Profile:** A Karachi-based woman aged 25–40, commonly a working professional or independently purchasing household member, who values quality, convenience, and products appropriate for repeat everyday use.

**Jobs to be done:**

- Find an authentic product that fits an everyday beauty routine or personal style.
- Understand materials, ingredients, shade/size, care, and expected result without researching across multiple sources.
- Compare a small, credible selection rather than sort through an uncurated marketplace.
- Complete an order quickly with a trusted payment method and realistic Karachi delivery expectation.
- Receive responsive support if the product, delivery, or order is not as expected.

**Needs and decision factors:**

- Clear brand and authenticity evidence
- Complete ingredients, materials, dimensions, usage, warnings, and care information
- Accurate product photography, shade/finish representation, and variant availability
- Visible PKR pricing, delivery estimate, payment methods, and return eligibility
- Credible reviews and policies without exaggerated claims
- Fast mobile pages and a short guest checkout
- Reliable order confirmation, tracking, and support access

**Likely barriers to validate:**

- Concern about counterfeit external-brand beauty products
- Doubt that jewellery finish, scale, or material matches photography
- Uncertainty about beauty suitability or shade accuracy
- Concern about damaged, expired, opened, or tampered products
- Reluctance to prepay before SoftGlam has established trust
- Limited patience for registration, unclear delivery charges, or slow mobile pages

#### Secondary segment — emerging routine explorer

**Profile:** A Karachi-based woman aged 18–24, commonly a student or early-career shopper, who is more price-sensitive and interested in discovering polished products that fit an evolving routine and style.

**Jobs to be done:**

- Discover a manageable set of products without marketplace overload.
- Understand what a product does, how to use or style it, and whether it is worth the price.
- Compare variants and prices clearly on mobile.
- Save products and return later before committing.
- Purchase with COD or another familiar method without being forced to create an account.

**Needs and decision factors:**

- Clear value explanation rather than discount-only messaging
- Educational product content and straightforward terminology
- Search-friendly product titles and problem/benefit language
- Wishlist, recently viewed items, and shareable product URLs
- Transparent total cost before checkout completion
- Mobile-first navigation, search, filters, and checkout

**Likely barriers to validate:**

- Greater sensitivity to product and delivery price
- Lower familiarity with ingredients, materials, sizing, or routines
- Difficulty judging value from premium presentation alone
- Abandonment when payment, account, or checkout steps feel risky or lengthy

#### Shared experience implications

- The storefront leads with product credibility and everyday usefulness, not artificial urgency or constant discounting.
- Search landing pages, category copy, titles, metadata, and structured data support high-intent Google discovery.
- Product pages explain authenticity, sourcing, ingredients/materials, use/care, size/shade/finish, condition, and packaging in consistent sections.
- External-brand products identify the brand and sourcing assurance without making unverifiable authorization claims.
- SoftGlam-owned products clearly identify SoftGlam as the brand and substantiate material, ingredient, and safety claims.
- Real product imagery is prioritized; color-altering filters and misleading retouching are prohibited.
- Guest checkout, COD plus online payment, complete costs, and Karachi delivery estimates are visible before commitment.
- Support, cancellation, return, and problem-resolution paths are written in direct language and remain easy to find.
- The visual system remains bright, calm, and premium while maintaining strong contrast, readable text, and obvious actions.
- Launch merchandising prioritizes self-purchase and daily routines; gift finders, recipient flows, and gift packaging are deferred.

#### Non-primary audiences and exclusions

SoftGlam does not initially optimize assortment or campaigns for children, professional makeup artists, salons, bulk/wholesale buyers, clinical skincare treatment, luxury collectors, or gift-first shoppers. These customers may purchase eligible products, but their specialized workflows are outside launch scope. The service must not present cosmetics as medical diagnosis or treatment.

#### Pilot research and validation

The profiles are strategic hypotheses, not claims about all Pakistani women. During the controlled Karachi pilot, the team must combine consent-aware analytics with customer interviews and support/order observations.

Validate:

- Search terms and landing-page relevance
- Which authenticity evidence changes purchase confidence
- Product information customers cannot find or understand
- Beauty shade/suitability and jewellery size/material concerns
- COD versus online-payment preference and reasons
- Delivery-price and delivery-time sensitivity
- Checkout abandonment reasons
- Cancellation, return, refusal, and support-contact reasons
- Differences between the primary and secondary segment

Minimum pilot research:

- Moderated usability sessions with participants from both segments before public launch
- Post-purchase feedback from delivered orders
- Review of no-result searches, product-to-cart, checkout completion, payment method, support contacts, and return-to-origin outcomes
- No profile expansion or personalization based on sensitive inferred traits

The segment strategy must be reviewed after the controlled pilot and before nationwide expansion. Evidence may change messaging, assortment, policies, or segment priority without weakening accessibility, privacy, or commerce integrity.

### 1.7 Brand positioning and voice

#### Brand identity

- **Canonical name:** SoftGlam
- **Pronunciation:** “soft glam”
- **Launch tagline:** “Your everyday glow.”
- **Personality:** A calm and bright friend
- **Positioning territory:** Everyday confidence
- **Market posture:** Curated mid-premium, never inaccessible luxury or discount marketplace

Use `SoftGlam` in prose, metadata, legal/customer communication, and product interfaces. A future wordmark may use custom letterforms or capitalization, but accessible text and the canonical written name remain `SoftGlam`.

The tagline is an emotional brand line, not a factual product claim. Use it selectively on the homepage, brand story, packaging, and campaigns. Do not place it in payment, error, legal, safety, refund, or support-resolution messages where direct language is required.

#### Positioning statement

For women in Karachi who want dependable beauty and jewellery for daily life, SoftGlam is a curated beauty and jewellery store that makes everyday confidence easier through a focused assortment, honest product information, clear pricing, and reliable service. Unlike crowded marketplaces or image-only social sellers, SoftGlam helps customers understand what they are buying and what to expect before and after purchase.

This is an internal strategic statement, not homepage copy.

#### Customer promise

SoftGlam selects with care, explains honestly, prices clearly, and follows through reliably.

The promise has four parts:

1. **Selected with care:** A focused assortment with a reason to exist; not unlimited products for the appearance of scale.
2. **Explained honestly:** Useful ingredients, materials, dimensions, shades, usage, care, warnings, sourcing, and limitations.
3. **Priced clearly:** PKR pricing, discounts, delivery charges, and final totals without avoidable surprises.
4. **Supported reliably:** Accurate order states, realistic delivery expectations, accessible support, and fair policy application.

#### Brand pillars

1. **Everyday confidence** — Products should help customers feel polished and comfortable in ordinary routines, not imply that they need fixing.
2. **Credible curation** — Selection is demonstrated through product quality, usefulness, and information rather than unsupported claims of exclusivity.
3. **Clarity builds trust** — Plain product facts, authentic imagery, transparent costs, and direct policies take priority over decorative language.
4. **Bright ease** — The experience feels optimistic and friendly while remaining calm, readable, and efficient.
5. **Care after checkout** — Confirmation, delivery, support, cancellation, and refund experiences are part of the brand, not operational afterthoughts.

#### Personality boundaries

SoftGlam is:

- Warm, not overly familiar
- Bright, not loud
- Confident, not absolute
- Helpful, not patronizing
- Premium, not precious
- Concise, not cold
- Optimistic, not dismissive
- Feminine where appropriate, not exclusionary or stereotyped

SoftGlam does not use shame, fear, insecurity, age anxiety, colorism, medical anxiety, or social pressure to sell products. It does not suggest a customer is incomplete, unattractive, unhygienic, or unsuccessful without a purchase.

#### Voice principles

1. **Lead with the useful point.** State what the customer needs before adding personality.
2. **Use everyday language.** Prefer familiar words, short sentences, and specific nouns and verbs.
3. **Be warm through care, not filler.** Helpful detail and respectful recovery are more valuable than excessive enthusiasm.
4. **Make confidence evidence-based.** Explain the material, ingredient, source, measurement, policy, or process behind a statement.
5. **Match the moment.** Discovery can be expressive; checkout, safety, payment, and support must be direct.
6. **Never hide a limitation.** Stock, shade variation, delivery uncertainty, exclusions, and product limitations are stated clearly.

#### Language mechanics

- Use English-first copy at launch and familiar Pakistani commerce terms where they improve comprehension.
- Use sentence case for headings, buttons, navigation, and labels unless the approved visual system requires a restrained display exception.
- Address the customer as “you”; use “we” only when SoftGlam is taking responsibility or explaining an action.
- Use contractions in friendly editorial and support copy when they remain clear.
- Prefer active voice and concrete verbs: “We received your order,” not “Your order has been received by us.”
- Use `PKR` in data, receipts, and technical contexts; customer-facing price presentation may use `Rs` or `PKR` once the design system standard is approved.
- Use exclamation marks rarely and never stack punctuation.
- Do not use emojis in core product, checkout, account, legal, error, or support interfaces.
- Avoid unexplained beauty jargon; define specialist terms when they affect selection or safe use.
- Do not use gendered greetings such as “Hey girl” in transactional or account communication.

#### Messaging hierarchy

Brand and campaign communication should follow this order:

1. Customer benefit or everyday use
2. Product/category relevance
3. Evidence and differentiating detail
4. Price, availability, and delivery information
5. Clear action

Product pages follow a stricter order:

1. Product identity and verified brand
2. Price, variant, and availability
3. Customer-relevant purpose and result without overclaiming
4. Ingredients/materials, dimensions, usage, care, warnings, and sourcing evidence
5. Delivery, payment, return eligibility, and support
6. Reviews and related discovery

#### Authenticity and sourcing language

- Use “authentic,” “original,” “authorized,” “official,” “certified,” or similar terms only when documentary evidence supports the exact claim.
- External-brand products identify the supplier/source assurance that SoftGlam can prove; supplier confidentiality does not justify a stronger public claim.
- SoftGlam-owned products say “by SoftGlam” and distinguish brand ownership from manufacturing origin.
- Product imagery must represent the sold item. Disclose material shade, screen, lighting, handcrafted, or batch variation where relevant.
- Never invent customer counts, ratings, scarcity, awards, comparison prices, expert approval, or bestseller status.

#### Beauty and jewellery claims

- Do not make medical, therapeutic, disease, guaranteed-result, or permanent-result claims unless legally permitted and supported by appropriate evidence.
- Avoid absolute language such as “safe for everyone,” “chemical-free,” “non-toxic,” “zero irritation,” “100% effective,” or “no side effects.”
- SPF, hypoallergenic, dermatologist-tested, vegan, cruelty-free, halal, nickel-free, waterproof, tarnish-resistant, and similar claims require documented substantiation.
- Beauty results that vary by person must use accurate qualifying context without turning every sentence into defensive legal copy.
- Jewellery material, plating thickness, stone, purity, resistance, and warranty statements must match supplier/manufacturer evidence.
- Reviews and creator content must not be edited or presented in a way that converts personal experience into a guaranteed result.

#### Voice by context

| Context | Voice | Requirement |
|---|---|---|
| Homepage/campaign | Bright, concise, expressive | Connect everyday confidence to a specific category or edit |
| Search/category | Plain, useful, scannable | Use terms customers actually search; avoid abstract headings alone |
| Product detail | Friendly expert | Prioritize evidence, selection help, use, care, and limitations |
| Cart/checkout | Calm and direct | Show cost, stock, delivery, payment, and required action clearly |
| Confirmation/tracking | Reassuring and factual | State what happened, current status, and next expected step |
| Validation/error | Neutral and actionable | Explain the problem without blame and provide recovery |
| Support | Human and accountable | Acknowledge impact, state ownership, action, and follow-up |
| Refund/cancellation | Precise and respectful | State amount, method, status, timing, and policy basis |
| Safety/legal | Unembellished | Accuracy and comprehension override brand personality |

#### Copy examples

Preferred:

- “Everyday beauty and jewellery, selected with care.”
- “Your everyday glow.”
- “Choose your shade to see its price and availability.”
- “Only 2 left in this finish.” when backed by current sellable inventory
- “Delivery to Karachi is calculated at checkout.”
- “We couldn’t confirm your payment. You haven’t been charged by SoftGlam. Check the payment status before trying again.” when technically accurate
- “This shade is currently unavailable. Choose another shade or save it for later.”
- “We’re sorry this arrived damaged. We’ll review the photos and update you within the stated support window.”

Avoid:

- “Fix your flaws.”
- “You need this.”
- “Guaranteed glow.”
- “Luxury you deserve” without a useful differentiator
- “Best quality” or “Pakistan’s most trusted” without defensible evidence
- “Hurry!!!” or unverified countdowns
- “Perfect for everyone.”
- “Payment failed” when the provider result is unknown or pending
- Cute or promotional language in safety, refund, or complaint resolution

#### SEO and acquisition implications

Google Search is the expected initial acquisition channel, so discoverability must not depend on poetic campaign copy. Page titles, headings, product names, descriptions, alt text, and metadata use accurate category, product type, brand, shade/material, concern/use, and location/delivery language where relevant. Editorial headlines may add emotion, but each page still states plainly what it contains.

#### Governance and validation

- Product claims require an evidence owner and source before publication.
- Legal, safety, payment, refund, and policy copy requires designated review appropriate to the risk.
- Reusable interface terms are maintained centrally to avoid contradictory statuses or promises.
- Customer support uses approved templates as a starting point but adapts with accurate case facts.
- Pilot research tests tagline comprehension, trust language, authenticity evidence, product terminology, and whether the voice feels helpful rather than childish or vague.
- The tagline and campaign language may evolve after pilot evidence; the promise of care, honesty, clarity, and reliable follow-through does not change without an explicit positioning decision.

### 1.8 Catalog taxonomy and attribute vocabulary

#### Taxonomy model

The catalog uses five distinct concepts:

1. **Category:** Customer-facing hierarchical navigation. A product may appear in multiple categories.
2. **Product type:** The single schema assigned to a product; it determines attributes, conditional requirements, and validation.
3. **Attribute:** Structured product information shared by all variants, such as material or skin type.
4. **Option:** A customer-selectable axis that creates sellable variants, such as shade or finish.
5. **Collection:** A merchandising group such as New Arrivals or Everyday Edit; it does not change product validation.

Tags support internal organization or controlled discovery but must not replace categories, product types, attributes, or collections. Public filters must not be generated from uncontrolled free-form tags.

Launch navigation:

- `jewellery` — Jewellery
  - `earrings` — Earrings
  - `necklaces` — Necklaces
  - `bracelets` — Bracelets
  - `rings` — Rings
- `beauty` — Beauty
  - `makeup` — Makeup
  - `skincare` — Skincare

Launch product types:

- `earrings`
- `necklaces`
- `bracelets`
- `rings`
- `makeup`
- `skincare`

A product has exactly one product type. Navigation categories may reuse the same labels at launch, but category identity and product-type identity remain separate so merchandising can evolve without changing product schemas.

#### Vocabulary governance

- Attribute, option, and allowed-value codes use immutable lowercase `snake_case` identifiers.
- Customer-facing labels and descriptions may change without changing codes or historical meaning.
- Selectable values use stable codes and controlled labels; filterable values are never arbitrary free text.
- Codes are not reused after retirement.
- `Unknown`, `not provided`, `none`, and `not applicable` have different meanings and must not be collapsed into one value.
- Empty required values block publication. Conditional values become required only when their documented condition applies.
- Removing or changing a value already used by products requires migration or retirement, not silent deletion.
- Staff can extend controlled values only with the relevant catalog permission and an audit event.
- Synonyms support search but do not create duplicate canonical values.
- Customer-visible claims require the evidence rules in section 1.7.

Supported attribute data types:

| Type | Use |
|---|---|
| Short text | Concise factual value that is not filterable |
| Long text | Ingredients, care, directions, or warnings |
| Integer | Whole-number counts |
| Decimal | Measured factual value; never money |
| Boolean | Explicit yes/no fact; absence is not false |
| Date | Manufacture, expiry, or publication-related date where applicable |
| Single select | One controlled value |
| Multi-select | Several controlled values |
| Measurement | Decimal value plus canonical unit and dimension |
| Color swatch | Controlled color family plus display value/reference |
| Reference | Link to a managed brand or related catalog entity |

Money, SKU, barcode, inventory, media, and publication state are first-class fields, not configurable attributes.

#### Canonical units

Metric is canonical:

- Linear dimensions: millimetres (`mm`) internally; centimetres (`cm`) may be displayed for larger dimensions
- Weight: grams (`g`); kilograms only for shipping display where appropriate
- Liquid volume: millilitres (`ml`)
- Jewellery purity: fineness or karat value as a controlled factual field, not a measurement conversion
- Ring fit: adjustable at launch, with minimum/maximum inner diameter or circumference in millimetres when reliably provided

Supplier units are converted during catalog entry. The original supplier specification may be retained as evidence, but customer-facing values derive from canonical data. Conversion must not imply greater precision than the source.

#### Shared first-class product fields

Required to publish unless marked conditional:

| Field | Rule |
|---|---|
| `brand_id` | Managed brand record; SoftGlam is explicit for owned products |
| `product_type_id` | Exactly one active launch product type |
| `title` | Specific, non-promotional product identity |
| `slug` | Unique, stable public identifier with redirect on change |
| `short_description` | Concise customer use/value summary |
| `description` | Accurate detail without unsupported claims |
| `status` | Draft, active, or archived; publication is separately controlled |
| `categories` | At least one valid customer category |
| `media` | Required launch media set with alt text |
| `country_of_origin` | Required when known/declared on product or supplier evidence; omission requires review |
| `care_or_directions` | Product-type-appropriate care or usage |
| `warnings` | Required when supplier/manufacturer provides warnings; explicit none-reviewed state otherwise |
| `seo_title` and `seo_description` | May default from approved product content but remain editable |

Every product has at least one variant, including products with no customer-selectable options. Every variant owns SKU, barcode when available, price, compare-at price, cost with restricted access, weight, dimensions where needed for shipping, status, inventory, and optional variant media.

#### Shared jewellery vocabulary

Applies to earrings, necklaces, bracelets, and rings:

| Code | Type | Requirement | Filter/search behavior |
|---|---|---|---|
| `jewellery_tier` | Single select | Required: `fashion`, `fine` | Filterable |
| `style` | Multi-select | Required from product-type values | Filterable, searchable |
| `base_material` | Single select | Required | Filterable, searchable |
| `material_color` | Single select | Required | Filterable |
| `finish` | Single select | Required | Filterable, searchable |
| `plating_material` | Single select | Required when plated | Filterable |
| `stone_presence` | Boolean | Required | Filterable |
| `stone_type` | Multi-select | Required when stones are present | Filterable, searchable |
| `stone_origin` | Single select | Required for fine jewellery stones when known: `natural`, `lab_grown`, `simulated`, `not_applicable` | Filterable for fine jewellery |
| `stone_treatment` | Short text | Required when treatment is declared or relevant | Display only |
| `item_weight_g` | Measurement | Required when reliably available | Display only |
| `water_resistance_claim` | Single select | Optional, evidence required | Display only |
| `tarnish_resistance_claim` | Single select | Optional, evidence required | Display only |
| `hypoallergenic_claim` | Boolean | Optional, evidence required | Filter only after sufficient coverage |
| `nickel_free_claim` | Boolean | Optional, evidence required | Filter only after sufficient coverage |
| `warranty_summary` | Short text | Conditional when warranty exists | Searchable |

Initial controlled material values:

- Fashion: `stainless_steel`, `sterling_silver`, `brass`, `copper`, `titanium`, `resin`, `mixed_material`, `other_disclosed`
- Fine: `gold`, `platinum`, `sterling_silver`, `palladium`, `other_precious_disclosed`
- Plating: `gold_plated`, `gold_vermeil`, `rose_gold_plated`, `silver_plated`, `rhodium_plated`, `pvd_coated`, `unplated`, `other_disclosed`
- Material colors: `yellow_gold`, `rose_gold`, `silver`, `black`, `multicolor`, `other`
- Finish: `polished`, `matte`, `brushed`, `hammered`, `textured`, `mixed_finish`

“Alloy” or “mixed material” is not sufficient when more specific supplier evidence exists. Purity must never be inferred from color or marketing name.

Fine jewellery conditional fields:

| Code | Type | Rule |
|---|---|---|
| `precious_metal` | Single select | Required for `fine` tier |
| `metal_purity` | Single select | Required when purity is claimed; controlled values such as `925_silver`, `9k_gold`, `14k_gold`, `18k_gold`, `22k_gold`, `24k_gold`, `950_platinum` |
| `hallmark` | Short text | Required when a hallmark claim is made |
| `certificate_type` | Single select | Required when certification is advertised |
| `certificate_reference` | Short text | Restricted/display policy defined before publication |
| `precious_metal_weight_g` | Measurement | Required only when advertised or needed for pricing evidence |

Fine-jewellery claims require documentary evidence. A product cannot be published as fine jewellery merely because its price or appearance suggests it.

#### Earrings vocabulary

| Code | Type | Requirement |
|---|---|---|
| `earring_style` | Single select | Required: `stud`, `hoop`, `huggie`, `drop`, `dangle`, `ear_cuff`, `statement`, `other_disclosed` |
| `closure_type` | Single select | Required: `push_back`, `screw_back`, `lever_back`, `latch_back`, `hook`, `hinged`, `clip_on`, `none`, `other_disclosed` |
| `sold_as` | Single select | Required: `pair`, `single` |
| `drop_length_mm` | Measurement | Required for drop/dangle styles when measurable |
| `outer_diameter_mm` | Measurement | Required for hoop/huggie styles when measurable |
| `post_material` | Single select | Required when different from base material |

#### Necklaces vocabulary

| Code | Type | Requirement |
|---|---|---|
| `necklace_style` | Single select | Required: `chain`, `pendant`, `choker`, `layered`, `station`, `statement`, `other_disclosed` |
| `chain_type` | Single select | Conditional: `cable`, `curb`, `box`, `snake`, `rope`, `figaro`, `bead`, `other_disclosed`, `not_applicable` |
| `length_mm` | Measurement | Required |
| `extension_length_mm` | Measurement | Optional/conditional |
| `closure_type` | Single select | Required |
| `pendant_dimensions_mm` | Measurement set | Required when pendant is present and measurable |

Customer-facing display may show necklace length in centimetres while retaining millimetres canonically.

#### Bracelets vocabulary

| Code | Type | Requirement |
|---|---|---|
| `bracelet_style` | Single select | Required: `chain`, `bangle`, `cuff`, `tennis`, `charm`, `beaded`, `wrap`, `other_disclosed` |
| `length_mm` | Measurement | Required for closed/chain styles |
| `inner_diameter_mm` | Measurement | Required for bangle/cuff when measurable |
| `extension_length_mm` | Measurement | Optional/conditional |
| `closure_type` | Single select | Required; `none` allowed for applicable styles |
| `fit_type` | Single select | Required: `fixed`, `adjustable`, `stretch` |

#### Rings vocabulary

Only adjustable rings are included at launch.

| Code | Type | Requirement |
|---|---|---|
| `ring_style` | Single select | Required: `band`, `signet`, `solitaire`, `cluster`, `stacking`, `statement`, `other_disclosed` |
| `fit_type` | Single select | Required and fixed to `adjustable` for launch products |
| `min_inner_diameter_mm` | Measurement | Required when reliably provided |
| `max_inner_diameter_mm` | Measurement | Required when reliably provided |
| `band_width_mm` | Measurement | Required when measurable |
| `setting_type` | Single select | Conditional when stones are set |

Fixed ring-size variants are blocked from publication until a later decision adds a sizing standard, conversion guidance, fit validation, and return-policy treatment.

#### Shared makeup vocabulary

| Code | Type | Requirement | Filter/search behavior |
|---|---|---|---|
| `makeup_subtype` | Single select | Required | Filterable, searchable |
| `format` | Single select | Required | Filterable |
| `finish` | Single select | Required where applicable | Filterable, searchable |
| `coverage` | Single select | Conditional for complexion/color products | Filterable |
| `color_family` | Multi-select | Conditional for shade products | Filterable, searchable |
| `skin_types` | Multi-select | Optional; evidence/source required | Filterable after sufficient coverage |
| `skin_concerns` | Multi-select | Optional; evidence/source required | Filterable after sufficient coverage |
| `ingredients_label_text` | Long text | Complete current manufacturer/package list required | Display, not filterable |
| `net_weight_g` | Measurement | One of weight/volume required |
| `net_volume_ml` | Measurement | One of weight/volume required |
| `period_after_opening_months` | Integer | Required when package provides it | Display only |
| `usage_directions` | Long text | Required | Searchable |
| `warnings` | Long text | Required when provided | Display only |

Initial makeup subtype values:

- `lip_color`
- `lip_gloss_or_treatment`
- `foundation_or_skin_tint`
- `concealer`
- `blush`
- `highlighter`
- `bronzer_or_contour`
- `eyeshadow`
- `eyeliner`
- `mascara`
- `brow_product`
- `setting_product`
- `other_disclosed`

Initial finish values include `natural`, `matte`, `satin`, `dewy`, `glossy`, `shimmer`, `metallic`, and `other_disclosed`; only relevant values appear for a subtype. Initial coverage values are `sheer`, `light`, `medium`, `full`, `buildable`, and `not_applicable`.

Shade is normally a variant option, not a product attribute. Every shade option requires:

- Stable shade code
- Customer-facing shade name
- Controlled color family
- Accessible text description
- Swatch color/reference
- Variant-specific SKU and inventory
- Variant-specific media when the appearance materially differs

A digital swatch is guidance, not a guarantee of identical appearance across screens or skin tones.

#### Shared skincare vocabulary

| Code | Type | Requirement | Filter/search behavior |
|---|---|---|---|
| `skincare_subtype` | Single select | Required | Filterable, searchable |
| `format` | Single select | Required | Filterable |
| `routine_step` | Single select | Required | Filterable, searchable |
| `skin_types` | Multi-select | Required only when supported by manufacturer evidence | Filterable |
| `skin_concerns` | Multi-select | Required only when supported by manufacturer evidence | Filterable, searchable |
| `usage_time` | Multi-select | Required: `morning`, `evening`, `as_needed` as applicable | Filterable |
| `usage_frequency` | Short text | Required | Display only |
| `ingredients_label_text` | Long text | Complete current manufacturer/package list required | Display, not filterable |
| `net_weight_g` | Measurement | One of weight/volume required |
| `net_volume_ml` | Measurement | One of weight/volume required |
| `period_after_opening_months` | Integer | Required when package provides it | Display only |
| `usage_directions` | Long text | Required | Searchable |
| `patch_test_guidance` | Long text | Required when provided/relevant | Display only |
| `warnings` | Long text | Required when provided | Display only |

Initial skincare subtype values:

- `cleanser`
- `toner_or_essence`
- `serum_or_treatment`
- `moisturizer`
- `sunscreen`
- `mask`
- `exfoliant`
- `eye_care`
- `spot_treatment`
- `lip_care`
- `other_disclosed`

Initial controlled skin-type values are `normal`, `dry`, `oily`, `combination`, and `sensitive`. Initial concern values may include `dryness`, `oiliness`, `dullness_appearance`, `uneven_tone_appearance`, `texture`, `blemish_prone`, `appearance_of_pores`, and `appearance_of_fine_lines`; final labels must remain cosmetic and evidence-based. A concern value does not represent a medical diagnosis.

Ingredient handling at launch:

- Store the complete ingredient list as text exactly from current manufacturer/package evidence, preserving order and meaningful punctuation.
- Do not rely on an ingredient-label image as the only accessible source.
- Do not automatically parse the text into structured ingredients or infer actives, concentrations, exclusions, allergens, suitability, or claims.
- Highlighted ingredients may appear in editorial content only when supported, but are not launch filters.
- Staff records the evidence source and review date outside customer-visible copy.
- A changed formulation requires content review; old order snapshots remain unchanged.

#### Variant option vocabulary

Allowed launch option codes:

- `shade`
- `finish`
- `material`
- `material_color`
- `length`
- `volume`
- `weight`
- `pack_quantity`
- `formulation`

Option labels are product-specific but option codes are controlled. Do not create synonymous axes such as `colour`, `color`, and `tone` for the same concept.

Variant rules:

- A product with no selectable options still has one default variant.
- A selectable combination maps to exactly one variant.
- Duplicate option combinations and duplicate SKUs are prohibited.
- A value becomes a variant option when it changes SKU, barcode, price, inventory, shipping data, availability, or materially distinct media.
- Product-level attributes describe all variants; facts that differ by variant belong on the variant or option value.
- Unavailable combinations are not selectable as purchasable variants.
- Archived variants remain available to historical order snapshots but cannot be purchased.
- Option order is consistent by product type and intentionally configured for customer comprehension.
- Variant titles are generated consistently from product title and selected option labels but may not replace stable SKU identity.

#### Search and filter policy

Launch searchable content:

- Product title, brand, product type, category, approved keywords, SKU for appropriate contexts, short description, style/subtype, canonical material, finish, stone, color family, skin concern, and routine step
- Synonyms such as `ear studs`/`stud earrings`, `chain`/`necklace` where they improve recall without changing canonical values

Launch filters:

- Shared: category, brand, availability, price
- Jewellery: product type, jewellery tier, style, base material, material color, finish, stone type
- Makeup: subtype, format, finish, coverage, color family
- Skincare: subtype, format, routine step, skin type, supported skin concern, usage time

A filter is shown only when active published products provide meaningful choices. Optional claim filters such as hypoallergenic or vegan remain hidden until coverage, evidence quality, and customer usefulness justify them. Ingredient text is not filterable at launch.

Search and filters expose only published, active products and sellable/public attributes. Internal evidence, cost, supplier detail, staff notes, and restricted identifiers are never indexed publicly.

#### Publication validation

All products must pass shared field, media, category, variant, SKU, pricing, inventory-policy, SEO, and evidence validation. Product-type rules additionally require:

- Jewellery: tier, style, material, color, finish, measurements where applicable, stone details when present, and conditional fine-jewellery evidence
- Makeup: subtype, format, relevant finish/coverage, net quantity, complete current label ingredients, directions, warnings, and complete shade metadata for shade variants
- Skincare: subtype, format, routine step, supported suitability/concern data, net quantity, complete current label ingredients, directions, and warnings
- Rings: adjustable fit; fixed-size rings are rejected at launch

Validation errors identify the missing or conflicting field and how to resolve it. Staff cannot bypass a hard publication requirement by hiding the field from the storefront.

#### Taxonomy change control

- Launch codes and meanings are version-controlled through migrations/seed data or an equivalently reviewed configuration process.
- Administrative editing controls labels, ordering, allowed extension points, and assignments without permitting unsafe code mutation.
- New product types require attribute mapping, publication rules, storefront display, filters/search review, sample product acceptance, and migration impact review.
- Attribute promotion from display-only to filterable requires data coverage and backfill review.
- Merging or splitting controlled values requires product migration, URL/filter compatibility consideration, and analytics continuity.
- Order items preserve the customer-visible product, variant, option, and material/shade/size snapshots at purchase time.

### 1.9 Shipping zones, rates, and delivery promises

#### Launch service scope

The controlled pilot accepts delivery addresses within Karachi, Sindh, Pakistan. Nationwide and international delivery are unavailable at launch. The storefront may be browsed from any location, but checkout prevents unsupported destination selection.

Launch shipping configuration:

| Setting | Approved value |
|---|---|
| Zone code | `karachi_pilot` |
| Country | Pakistan (`PK`) |
| Province | Sindh |
| City | Karachi |
| Method | Standard Karachi delivery |
| Method code | `karachi_standard` |
| Customer price | Rs 300 |
| Free-shipping threshold | Discounted merchandise subtotal of Rs 5,000 or more |
| Delivery target | 1–2 SoftGlam business days after order confirmation |
| SoftGlam business days | Monday–Friday, excluding declared public/operational holidays |
| Same-day dispatch promise | None |
| Fulfillment origin | One Karachi location; exact address is private operational data |
| Courier operation | Manually selected and booked by staff |

All money rules use integer minor units and PKR internally. Customer-facing `Rs` formatting does not change the stored currency or value.

#### Shipping charge calculation

- The standard shipping charge is Rs 300 for an eligible Karachi order below the free-shipping threshold.
- Standard shipping is free when the merchandise subtotal is at least Rs 5,000 after product- and order-level discounts and before shipping.
- Taxes, tips, gift cards, store credit purchases, cancelled items, and the shipping charge itself do not contribute to the threshold.
- If a cart drops below the threshold after a discount, quantity change, stock change, cancellation before fulfillment, or checkout revalidation, the customer must review the recalculated shipping charge before commitment.
- A free-shipping promotion may override the base charge only when its documented eligibility and combination rules pass.
- The final shipping amount, method, threshold basis, discount allocation, currency, and destination summary are snapshotted on checkout/order records.
- Staff cannot add an unapproved remote-area, fuel, handling, or courier surcharge after checkout.
- Staff may waive or refund shipping with an authorized reason and audit event; they cannot silently increase it.

The Rs 300 rate and Rs 5,000 threshold are launch decisions subject to courier quote and margin validation before public launch. A required commercial review may change the configured values through an explicit decision; it must not silently change confirmed orders.

#### Parcel eligibility

The launch rate applies to the approved launch assortment packed as a standard small parcel from the single Karachi location. Before a future product can be published, its packaged weight/dimensions and shipping eligibility must be known when it exceeds configured standard-parcel limits.

- The customer is not charged a weight surcharge for launch-eligible products.
- Products requiring special handling, dangerous-goods treatment, temperature control, unusually high declared value, or non-standard parcel dimensions are blocked from launch shipping until a suitable method and price are approved.
- Beauty products must be sealed and packed to reduce leakage, breakage, heat exposure, contamination, and tampering risk.
- Jewellery must be packed to reduce movement, tangling, scratching, crushing, and loss of small items.
- Shipping packaging must not make unsupported premium, sustainability, tamper-proof, or temperature-protection claims.

#### Address capture and validation

Required delivery fields:

- Recipient full name
- Pakistani mobile number
- Address line 1
- Area/locality
- City, fixed to or validated as Karachi for the pilot
- Province, fixed to Sindh
- Country, fixed to Pakistan
- Delivery instructions, optional and length-limited
- Address line 2, landmark, and postal code where available

Rules:

- Address fields are normalized for comparison and courier entry without replacing the customer-visible snapshot.
- A postal code is not the sole source of Karachi eligibility because coverage and usage may be inconsistent.
- A valid city label does not prove courier serviceability; staff still reviews the booking result.
- Customers must not enter passwords, payment data, identity-document numbers, or other unnecessary sensitive data in delivery instructions.
- Staff may correct formatting or obvious spelling with traceability but must contact the customer before materially changing the destination.
- The order preserves the exact approved delivery-address snapshot even if the customer later edits the address book.

#### Serviceability exception

“All Karachi” means SoftGlam accepts Karachi delivery addresses during the pilot; it does not mean every courier serves every building or locality without exception.

If the selected courier rejects or cannot reasonably serve an address after checkout:

1. Keep the order in a visible `address_exception`/hold state; do not mark it dispatched.
2. Contact the customer through an approved support channel and explain the issue without blaming them.
3. Offer an alternative Karachi address or an approved pickup/collection option only if one operationally exists.
4. Do not add a surcharge or downgrade the order without explicit customer agreement.
5. If no acceptable alternative exists within the stated response window, cancel the affected order and issue a full refund to the original online payment method; COD orders are cancelled without collection.
6. Release or reconcile reserved inventory transactionally.
7. Record the courier, reason, attempts, customer decision, actor, timestamps, cancellation/refund reference, and audit event.

The customer-facing message must distinguish an address-serviceability issue from an invalid address, payment failure, or customer cancellation.

#### Delivery estimate

The launch target is 1–2 SoftGlam business days after confirmation:

- Online-paid orders are confirmed only after the trusted backend verifies the provider result.
- COD orders are confirmed only after the configured verification process succeeds.
- Pending, held, payment-review, COD-verification, address-exception, or fraud-review time is outside the delivery estimate until the order becomes confirmed; the customer must see the current state rather than an inaccurate countdown.
- Monday–Friday are business days. Saturdays, Sundays, and declared holidays do not consume the target window, although a courier may deliver on those days.
- SoftGlam makes no same-day dispatch or delivery promise at launch.
- A Friday confirmation normally produces a Monday–Tuesday target when neither day is a holiday.
- The checkout and confirmation experience presents an estimated date/range computed from the confirmation state and business calendar where possible.

The estimate is a service target, not an unconditional guarantee. Weather, civil disruption, courier capacity, inaccessible premises, incorrect contact/address data, and other documented exceptions may cause delay. SoftGlam must communicate a known material delay promptly and provide a useful next step; generic disclaimers do not replace active support.

#### Manual courier selection and booking

Authorized fulfillment staff:

1. Review confirmed payment/COD state, address, contact, parcel eligibility, stock allocation, and order notes.
2. Select an active configured courier/service based on actual coverage and operational suitability, not customer identity or arbitrary preference.
3. Book shipment outside or through the available courier tool.
4. Record courier, service, tracking number, tracking URL/reference, booking timestamp, parcel count, and staff actor.
5. Validate that the tracking reference is associated with the correct order before dispatch.
6. Pack and hand over the parcel, then record dispatch/handover time.
7. Trigger the shipment notification only after a real tracking reference or approved manual tracking state exists.
8. Update delivery, failed-attempt, exception, and return-to-origin events from reliable courier evidence or authorized manual confirmation.

Tracking references must be unique where the courier guarantees uniqueness. Correction or replacement preserves history and audit context; it does not overwrite evidence silently.

#### Failed delivery and one-retry policy

When the first delivery attempt fails:

- Record the attempt time, courier status, and normalized reason.
- Contact the customer when action or clarification is required.
- Permit one coordinated reattempt when the parcel remains with the courier and the address/order is still eligible.
- Confirm corrected contact instructions or an eligible alternative Karachi address before requesting a material destination change.
- Do not charge an additional retry fee at launch.
- Do not create duplicate fulfillment or inventory movements for a retry.

After the permitted retry fails, or when the courier will not retry, move the shipment to a visible failed/return-to-origin path. Do not mark the order delivered or automatically restock goods while they remain in transit. On physical return, staff inspects the items before restocking, write-off, refund, or COD-failure closure according to the applicable policy.

Normalized failure reasons include:

- Customer unavailable
- Customer requested reschedule
- Customer refused
- Incomplete/incorrect address
- Unreachable phone
- Access restriction
- Courier capacity/operational issue
- Damaged/lost in transit
- Serviceability exception
- Other documented reason

#### Customer communication

Before order submission, show:

- Karachi-only pilot availability
- Rs 300 standard shipping
- Free shipping at a discounted merchandise subtotal of Rs 5,000 or more
- Current eligibility and final shipping charge
- 1–2 business-day target after confirmation
- Monday–Friday business-day definition or an accessible policy link
- No unsupported same-day claim

After submission, show/send:

- Order and payment/COD verification status
- Delivery address summary
- Shipping method and charged amount
- Confirmation date and estimated range when confirmed
- Courier and tracking after booking
- Delay, failed-attempt, address-exception, cancellation, and refund updates when applicable
- A clear support path

Do not describe an order as “shipped,” “out for delivery,” or “delivered” until the corresponding trusted fulfillment event exists.

#### Admin configuration and governance

Authorized staff can manage zone activation, method availability, base rate, free-shipping threshold, business calendar, displayed estimate, courier definitions, and customer messaging. Changes require validation and audit history.

- Configuration changes affect new checkout calculations, not confirmed-order snapshots.
- Deactivating the Karachi method prevents new selection but does not erase existing orders.
- Checkout recalculates server-side immediately before order/payment creation.
- The system records which rule/configuration version produced the rate.
- Preview/testing is required before an active rate or eligibility change is published.
- Shipping reports separate shipping charged, shipping waived, courier cost when recorded, free-shipping usage, delivery time, failures, retries, address exceptions, and return-to-origin outcomes.

#### Pilot review triggers

Review the rate, threshold, estimate, and coverage after meaningful pilot volume and before nationwide expansion. Evidence includes:

- Actual courier charge and packaging cost per delivered/failed order
- Gross margin impact of paid and free shipping
- Percentage of orders qualifying for free shipping
- Delivery time distribution, not only averages
- Serviceability exceptions by area
- First-attempt and retry success
- Return-to-origin and refusal reasons
- Customer contacts and abandonment related to price or delivery time
- Differences between COD and prepaid delivery outcomes

Nationwide expansion requires separately approved zones, rates, remote-area coverage, estimates, courier capability, COD treatment, return-to-origin economics, and customer messaging. Karachi rules must not be copied to other cities without evidence.

### 1.10 Cash on delivery policy

#### Launch configuration

| Setting | Approved value |
|---|---|
| Payment method | Cash on delivery (`cod`) |
| Eligible destination | Approved Karachi pilot shipping zone only |
| Maximum final payable total | Rs 15,000 |
| Additional COD fee | None |
| Verification method | Manual phone call to the recipient/customer |
| Verification attempts | Two documented attempts |
| Pending verification window | 24 hours, subject to fair contact-opportunity rules |
| Pending stock treatment | Hard reservation for the verification window |
| First customer-caused COD failure in rolling 12 months | Future COD requires manual review |
| Second customer-caused COD failure in rolling 12 months | Prepaid-only for 90 days |

The maximum applies to the final payable order total after discounts, shipping, and tax. An order above Rs 15,000 cannot select COD and must use an available online payment method. Staff cannot split one customer checkout into multiple COD orders to bypass the limit.

There is no additional COD handling fee at launch. The approved shipping charge or free-shipping rule applies exactly as it does to an otherwise equivalent prepaid order. Courier collection cost remains an internal business cost unless a future reviewed policy changes customer pricing before checkout.

#### Eligibility

COD is available to guest and registered customers only when all of the following are true:

- The destination qualifies for the active Karachi pilot method.
- The final payable total is Rs 15,000 or less.
- Every cart item permits COD and standard launch shipping.
- A valid Pakistani mobile number and complete delivery address are provided.
- The customer accepts that confirmation requires a phone call.
- The customer, account, normalized phone, and relevant delivery history are not currently prepaid-only under the approved refusal policy.
- No conflicting active COD order or material risk condition requires review.
- Stock can be reserved for the verification window.

COD availability is calculated by the trusted backend and revalidated immediately before order creation. Hiding or disabling the browser control is not sufficient enforcement.

Products may become COD-ineligible only through an approved product/shipping rule, such as a future high-value, made-to-order, personalized, restricted, or non-standard item. Staff must not deny COD arbitrarily based on customer identity, neighborhood stereotypes, language, age, gender, or other unrelated characteristics.

#### Checkout and pending state

When an eligible customer submits a COD checkout:

1. Recalculate product prices, discounts, shipping, tax, eligibility, and final payable total.
2. Create one idempotent order/payment attempt in `pending_cod_verification` state.
3. Reserve the required inventory atomically for up to 24 hours.
4. Show that the order is received but not yet confirmed.
5. Place it in the staff verification queue.
6. Send an acknowledgement containing order reference, total due on delivery, address summary, verification requirement, and cancellation/expiry explanation.

A pending COD order does not start the 1–2 business-day delivery target and must not be represented as confirmed, processing, packed, or shipped.

The pending reservation releases automatically when verification expires, the customer declines, staff rejects with a valid reason, or the order is otherwise cancelled. Expiry/release jobs are idempotent and cannot release stock already committed to a confirmed order.

#### Fair 24-hour verification window

The order expires 24 hours after submission only when SoftGlam has provided a fair opportunity to verify it.

- Staff makes up to two call attempts at meaningfully different times and records each outcome.
- Calls occur only during published/configured verification hours.
- Orders submitted outside those hours, on weekends, or on declared holidays must not expire before two reasonable attempts can occur during operating periods.
- A customer who contacts SoftGlam through an approved channel may request a call-back within the active window.
- Staff does not repeatedly call after successful verification, explicit decline, or a request to stop.
- Call recordings are prohibited unless a separately approved lawful consent process exists; the launch workflow records outcomes and notes, not audio.

The exact verification hours remain an operational setting that must be approved and published before controlled launch. Until configured, staff cannot rely on automatic expiry alone as proof that fair contact was attempted.

#### Verification script and decision

Staff verifies only information needed to fulfill the order:

- Recipient/customer name
- Order reference where appropriate
- Ordered item/variant summary without exposing sensitive internal data
- Final amount payable to the courier
- Delivery area/address confirmation
- Phone availability and useful delivery instructions
- Customer intent to accept the parcel

Staff must not request a password, OTP for another service, complete card details, CVV, account credentials, or unnecessary identity-document information.

Allowed verification outcomes:

- `verified` — customer confirms order, total, and destination
- `callback_requested` — still pending within the valid window
- `no_answer` — attempt recorded; another attempt may remain
- `unreachable` — number unavailable/invalid after appropriate attempt
- `customer_declined` — customer explicitly cancels before dispatch
- `address_change_required` — hold for an eligible corrected address
- `duplicate` — confirmed duplicate after staff review
- `staff_rejected` — approved reason required
- `expired` — fair verification opportunity ended without confirmation

On `verified`, transactionally convert reserved stock to the confirmed-order allocation, transition the COD payment/order states, record the actor and timestamp, and start the shipping estimate. On a non-confirming terminal outcome, cancel safely, release stock, and notify the customer with the accurate reason and next step.

#### Duplicate and abuse controls

- Idempotency prevents repeated checkout submission from creating duplicate orders or reservations.
- An existing materially identical pending COD order for the same customer/phone/address/cart should be recovered or surfaced rather than duplicated.
- Multiple distinct pending COD orders sharing a phone, account, or address may enter manual review; similarity alone does not prove abuse.
- Phone numbers are normalized for matching, but a shared household/address must not automatically inherit another person’s restriction without review.
- Staff cannot create hidden customer risk labels. Reasons use approved codes, factual evidence, expiry, and audit history.
- Automated denial based on inferred wealth, gender, ethnicity, language, neighborhood reputation, device price, or unrelated personal traits is prohibited.
- Rate limits and abuse monitoring protect checkout without disclosing internal thresholds to attackers.

#### COD lifecycle and reconciliation

Recommended COD payment states:

- `pending_verification`
- `confirmed_collectable`
- `out_for_collection`
- `collected_pending_reconciliation`
- `reconciled`
- `collection_failed`
- `cancelled`

Rules:

- Confirmation does not mean cash has been collected.
- Dispatch transitions the payable amount to out-for-collection without marking it paid.
- Courier/customer delivery evidence may mark collection reported, but finance reconciliation confirms the remittance.
- `reconciled` requires matching order, courier reference, amount, currency, and remittance evidence.
- Partial, short, duplicate, missing, or mismatched remittances remain visible exceptions.
- The amount due is the confirmed order total. Staff or courier must not request an unrecorded surcharge, tip, or replacement amount.
- Any courier cash/change expectation must be communicated before delivery according to selected courier capability; SoftGlam must not promise that a courier carries change without confirmation.

COD revenue reporting distinguishes delivered/collection-reported amounts from reconciled cash. Financial records cannot treat an undelivered or unreconciled COD order as successfully collected revenue.

#### Customer-caused failure classification

A COD failure counts toward the refusal policy only after staff review establishes that the customer caused an avoidable fulfillment loss after confirmation, such as:

- Explicit refusal of an accurate, undamaged order without an approved policy reason
- Repeated unavailability after the coordinated delivery attempt and permitted retry
- Materially false address/contact information that the customer does not correct
- Abandonment after confirming the order and agreeing to delivery arrangements

The following do not automatically count:

- Cancellation before dispatch
- SoftGlam stock, picking, packing, or content error
- Wrong product/variant sent
- Damaged, opened, leaking, contaminated, or visibly tampered parcel
- Courier loss, damage, capacity failure, misconduct, or unapproved surcharge
- Serviceability exception
- Material delivery delay beyond the communicated estimate where cancellation/refusal is reasonable
- Legitimate policy, safety, authenticity, or support dispute
- Event outside the customer’s reasonable control

Staff records evidence, normalized reason, courier events, contact attempts, customer explanation, reviewer, and decision. A courier status alone does not conclusively prove customer fault.

#### Escalating refusal policy

Within a rolling 12-month window:

1. **First confirmed customer-caused COD failure:** Record the event. A future COD order first enters a risk-review hold; authorized staff must review the prior event and explicitly approve COD eligibility before the ordinary verification call can confirm it.
2. **Second confirmed customer-caused COD failure:** Disable COD for 90 days. The customer may still purchase with an available prepaid method.
3. **After 90 days:** COD may be restored after staff review; restoration is not automatic when unresolved fraud, unpaid balance, or active dispute evidence exists.
4. **Further failures:** Require explicit owner/authorized-manager review. Restrictions remain proportionate, time-bound where possible, and evidence-based.

The rolling window uses the reviewed failure-event date. Overturned events no longer count. Staff cannot convert a first event directly into a second event or extend a restriction merely to avoid review.

#### Review, correction, and reinstatement

- Customers can contact support to ask why COD is unavailable and request factual review without receiving sensitive anti-abuse implementation details.
- Authorized staff can uphold, overturn, shorten, or extend a restriction only with an approved reason and audit event.
- Incorrect phone/address association, courier error, duplicate event, or staff error must be corrected promptly.
- A restriction on a guest checkout is linked cautiously using normalized contact/order evidence; it must not create an irreversible hidden profile.
- Restoring COD does not erase historical operational events, but reporting distinguishes overturned events.
- Restrictions do not affect access to paid orders, support, refunds, privacy rights, or unrelated account functionality.

#### Return-to-origin handling

A refused or failed COD parcel is not restocked while in transit.

1. Mark the fulfillment failed/return-to-origin from reliable evidence.
2. Preserve the order, COD, fulfillment, and inventory states separately.
3. On physical receipt, inspect identity, quantity, seals, leakage, contamination, damage, and saleability.
4. Restock only saleable units through an explicit inventory movement; quarantine or write off unsafe/damaged beauty and compromised jewellery packaging as appropriate.
5. Record courier/packaging cost and the reviewed customer-failure classification.
6. Close COD collection as failed; do not create a customer refund when no customer money was collected.
7. If money was collected despite failed delivery/return, route it through an explicit refund/reconciliation exception rather than assuming no payment.

#### Customer communication

Before COD selection/submission, state:

- COD is available for eligible Karachi orders up to Rs 15,000.
- No additional COD handling fee applies.
- SoftGlam will call to verify the order.
- The order is not confirmed and the delivery estimate does not begin until verification succeeds.
- The final amount due on delivery.
- Repeated avoidable refusal may limit future COD access.

After submission, communicate each meaningful state: pending verification, verified/confirmed, callback needed, expiring/expired, cancelled, dispatched, delivery attempt, retry, delivered/collection reported, return-to-origin, and support review where applicable.

Messages must not shame, threaten, or publicly expose a customer. Staff must not imply criminal conduct merely because a call was missed or delivery failed.

#### Administration, audit, and reporting

Admin capabilities require explicit permissions for COD verification, rejection, failure classification, restriction review, reinstatement, and reconciliation.

Audit events cover:

- Eligibility result and relevant rule version
- Verification attempts/outcomes and staff actor
- Confirmation, rejection, expiry, and inventory release
- Delivery failure evidence and classification
- Restriction creation, expiry, override, and reinstatement
- Collection report and financial reconciliation

Operational reports include:

- COD selection and verification rates
- Time to verification and expiry rate
- Verification outcomes and cancellation reasons
- COD versus prepaid conversion and delivery success
- First-attempt, retry, refusal, and return-to-origin rates
- Restrictions created, overturned, expired, and reinstated
- Amount out for collection, reported collected, reconciled, and exceptional
- Courier/packaging cost of failed COD orders

Pilot review must determine whether the Rs 15,000 limit, no-fee policy, manual verification, 24-hour reservation, and refusal escalation remain commercially and operationally justified.

### 1.11 Cancellation, return, exchange, and refund policy

#### Policy principles

- This policy is subject to applicable Pakistani consumer law and any non-waivable customer rights. Legal review is required before publication.
- Customer-facing eligibility is determined from the policy version accepted for the order, product rules, fulfillment state, delivery evidence, issue type, and prior remedies—not from current catalog content.
- No-change-of-mind is the launch default for both jewellery and beauty.
- Wrong, missing, damaged, defective, expired, leaking, opened/tampered-on-arrival, counterfeit/misrepresented, or materially not-as-described goods remain reviewable.
- Visible delivery issues should be reported within 48 hours of recorded receipt. The window does not erase rights for latent defects, warranty issues, or problems that could not reasonably be discovered within 48 hours.
- SoftGlam pays approved return/replacement transport for seller-, product-, packaging-, or courier-attributable issues.
- An approved customer may choose replacement or refund where stock, safety, law, and operational feasibility permit.
- Operational exchange is modeled as a return/remedy plus a linked replacement order; original financial and inventory records are not rewritten.
- Decisions are evidence-based, consistent, auditable, and communicated with a specific reason and review path.

#### Customer cancellation before dispatch

A customer may request cancellation of all or eligible unfulfilled items until the parcel is handed to the courier and the fulfillment is marked dispatched from trusted evidence.

- Pending COD verification may be cancelled immediately without payment collection; reservations are released idempotently.
- Confirmed but not dispatched COD orders may be cancelled and inventory released.
- Prepaid orders cancelled before dispatch receive the refundable amount through the original payment method, subject to provider state and processing.
- Authorization should be voided rather than captured/refunded when the selected provider and current payment state safely permit it.
- Packed status alone does not remove cancellation eligibility if courier handover has not occurred, although staff may need to stop/reverse packing safely.
- Once any affected quantity is dispatched, ordinary self-service cancellation is unavailable for that quantity; support evaluates delivery interception or a policy-eligible issue without mislabeling it as pre-dispatch cancellation.
- Staff cannot backdate dispatch to avoid a valid cancellation.

Partial cancellation:

- Locks and validates remaining unfulfilled quantities.
- Releases only the affected inventory allocation/reservation.
- Reverses allocated item price, discount, tax, and adjustments deterministically.
- Recalculates shipping for the remaining order under the snapshotted policy. If customer-requested partial cancellation reduces the discounted merchandise subtotal below Rs 5,000 while remaining items still ship, the Rs 300 shipping charge may apply; the customer must review the resulting refund/amount before confirming cancellation.
- Never cancels or refunds more quantity than remains eligible.

Full pre-dispatch cancellation removes the unconsumed shipping charge. When SoftGlam cancels because it cannot fulfill, the customer receives a full reversal/refund of the affected amount, including applicable shipping, without a cancellation fee.

#### No-change-of-mind rule

Launch products are not returnable or exchangeable merely because the customer:

- Changed their mind
- No longer wants or needs the item
- Preferred another shade, finish, style, size, or product after receiving the ordered selection
- Disliked an accurately represented scent, texture, color, finish, scale, fit, or subjective result
- Found a lower price elsewhere
- Ordered by mistake but did not cancel before dispatch

This rule must be visible before purchase and near product-specific return information. It must not be used to reject a wrong item, inaccurate description, unsupported claim, defect, safety concern, legal right, or substantiated authenticity issue.

Because launch rings are adjustable only, fixed-size fit returns remain out of scope. A future fixed-size jewellery launch requires a separately approved fit and return policy.

#### Eligible issue reasons

Reviewable reasons include:

- Wrong product or variant
- Missing item or quantity
- Extra item requiring disposition instructions
- Product damaged in transit
- Beauty product leaking, broken, opened, used, contaminated, unsealed, or visibly tampered on arrival
- Product expired on arrival or inconsistent with approved shelf-life controls
- Jewellery broken, missing components, materially scratched/tarnished on arrival, or otherwise defective
- Product materially differs from approved listing facts or imagery beyond disclosed variation
- Substantiated counterfeit, authenticity, purity, material, ingredient, or sourcing concern
- Product or batch recall
- Latent manufacturing defect discovered during reasonable intended use
- Warranty-covered failure
- Courier/packaging issue that materially affects product safety or usability

Normal wear, misuse, failure to follow clear care/directions, unauthorized repair/alteration, accidental post-delivery damage, and disclosed natural/handmade/color-screen variation are not automatically eligible. Staff must establish facts rather than use these labels as blanket rejection reasons.

#### Reporting window

For visible wrong, missing, damaged, leaking, expired, opened, contaminated, or tampered-on-arrival issues, the customer should submit a request within 48 hours of the trusted delivery timestamp.

- If courier delivery time is missing or disputed, staff establishes a reasonable receipt time from available evidence.
- A request received after 48 hours is not automatically deleted; staff checks latent defect, warranty, accessibility, outage, emergency, delayed handover, and applicable-law considerations.
- A system or support outage extends the practical reporting opportunity.
- Staff cannot shorten the window through delayed notification or inaccurate delivery marking.
- The accepted policy version and deadline are visible from order detail where practical.

#### Request information and evidence

The request captures:

- Order and affected item/quantity
- Issue reason
- Customer description
- Preferred eligible remedy
- Date/time discovered
- Photos appropriate to the issue, packaging, shipping label, seal, batch/expiry, and product where reasonably available
- Video optionally supplied by the customer
- Safe contact details and pickup/return constraints

An unboxing video is never mandatory and absence alone is not a rejection reason. Evidence requirements must be proportionate: do not require a customer to handle, open, apply, taste, smell closely, power, or otherwise interact with a potentially unsafe or contaminated product.

SoftGlam may request additional specific evidence once when the initial request is insufficient, explaining why it is needed. Requests must not seek passwords, payment credentials, unrelated identity documents, or unnecessary personal data.

#### Initial review

SoftGlam targets an initial decision within two business days after receiving a complete request.

Possible review outcomes:

- `more_information_required`
- `approved_no_return_required`
- `approved_return_required`
- `approved_replacement`
- `approved_refund`
- `rejected_with_reason`
- `escalated_safety_authenticity`
- `withdrawn_by_customer`

A request involving safety, contamination, expiry, recall, or authenticity may require immediate product quarantine or catalog/inventory hold beyond the individual order. Customer resolution should not be unnecessarily delayed while a broader supplier investigation continues when existing evidence already supports a remedy.

#### Return authorization and transport

For an approved seller/product/courier-attributable return:

- SoftGlam arranges pickup/return or reimburses a pre-approved reasonable transport method.
- The customer is not charged a restocking, processing, return-label, or retry fee.
- Instructions specify what to return, safe packaging, prohibited handling, deadline, courier/pickup method, and authorization reference.
- Customers must not return leaking, contaminated, hazardous, recalled, or otherwise unsafe products until SoftGlam provides safe instructions.
- SoftGlam may resolve low-value, unsafe, or clearly evidenced cases without physical return, with disposition instructions that comply with safety and law.
- An unauthorized parcel return does not automatically void rights, but may require investigation and does not permit an untracked refund.

The return authorization has a reasonable customer action deadline stated in the approval. Extensions are available for documented courier, accessibility, emergency, or SoftGlam-caused delay. Ownership of in-transit return risk follows the approved return method and applicable law.

#### Receipt and inspection

Returned goods remain in `in_transit` until physically received. Receipt does not automatically mean restock or refund approval.

SoftGlam targets inspection within two business days after physical receipt. Inspection records:

- Product and variant identity
- Returned quantity
- Seal/package state
- Batch/expiry where applicable
- Damage, leakage, contamination, wear, missing parts, and evidence consistency
- Whether the approved reason is confirmed, inconclusive, or contradicted
- Inventory disposition: restock, quarantine, supplier return, repair where appropriate, or write-off
- Inspector, timestamp, notes, and media where proportionate

Opened/returned beauty is not restocked merely because it appears unused. Beauty safety and seal integrity take priority over inventory recovery. Jewellery is restocked only when identity, condition, components, hygiene/handling, packaging, and saleability pass inspection. Inventory disposition is explicit and separate from the customer’s financial remedy.

An adverse inspection does not silently close the request. The customer receives the finding, evidence summary appropriate for disclosure, policy basis, and review/escalation path.

#### Remedy selection

For an approved issue, the customer may choose where operationally and legally possible:

- Replacement with the same product/variant
- Linked replacement with another explicitly agreed variant when value treatment is documented
- Monetary refund
- Voluntary store credit

Replacement rules:

- The original affected quantity is processed through the return/remedy record.
- A linked replacement order preserves zero/additional/refunded value adjustments explicitly; it does not rewrite the original order.
- Same-item replacement has no additional product or standard shipping charge for an approved seller-attributable issue.
- Replacement stock is reserved only after approval and cannot cause overselling.
- If replacement becomes unavailable, the customer can select a monetary refund rather than being forced to wait or accept store credit.
- The replacement has its own fulfillment, tracking, notification, and delivery evidence.

Store credit is optional. The customer’s informed selection is recorded, including value, currency, expiry if legally permitted, and any usage restrictions. Staff cannot present store credit as the only available refund when a monetary remedy is required or approved.

#### Refund calculation

Refunds use immutable order snapshots and deterministic allocation, not current catalog prices.

Refundable components may include:

- Affected item amount actually paid
- Allocated item-level and order-level discounts
- Allocated tax
- Original shipping according to the rule below
- Prior refunds, pending refunds, cancellations, and replacement adjustments

Rules:

- Successful plus pending refunds cannot exceed the captured/collected refundable amount.
- A full order cancellation before dispatch refunds all paid order components, including shipping.
- When SoftGlam-caused issues make the entire delivered order eligible for refund/replacement, refund the original shipping charge.
- For a partial item issue where other correctly delivered items remain kept, original shipping is normally not refunded; an authorized staff member may make a customer-favorable exception with reason and audit.
- Return/replacement shipping for approved seller-attributable issues is paid by SoftGlam and is not deducted from the customer refund.
- Free shipping has zero customer-paid shipping to refund.
- Refund allocation remains stable for reporting and later partial refunds.
- COD orders with no collected cash do not create a monetary refund; any collected/reconciled amount follows the COD refund workflow.

Before execution, staff sees the calculation breakdown and remaining refundable amount. Manual corrections require permission, reason, and immutable adjustment records.

#### Refund method

Prepaid orders:

- Refund to the original payment method wherever supported and lawful.
- If the provider cannot return funds to the original method, use an approved alternative with customer consent and reconciliation evidence.
- Provider reference, amount, status, failure, and settlement expectation are recorded.

COD orders with confirmed collection:

- Offer monetary refund by supported bank transfer or approved mobile wallet.
- Offer store credit only as a voluntary alternative selected by the customer.
- Collect the minimum transfer details through a protected workflow with restricted access; do not request credentials, PINs, OTPs, or unrelated documents.
- Verify account/wallet details proportionately before transfer and provide a customer-safe reference.
- Never mark the refund complete only because staff created an internal request; completion requires transfer/provider evidence.

Refund processing targets:

- Initiate an approved refund within two SoftGlam business days after the remedy becomes final or required inspection completes.
- Communicate the expected provider/bank/wallet settlement range separately; do not represent initiation as receipt.
- Monitor pending/failed transfers and notify the customer of material delay or required correction.
- Store credit, when voluntarily chosen, is issued only once and reconciled against the refund amount.

#### Cancellation and return state models

Cancellation request states:

- `requested`
- `approved`
- `rejected`
- `processing_reversal`
- `completed`
- `failed`

Return request states:

- `requested`
- `information_required`
- `under_review`
- `approved`
- `rejected`
- `awaiting_return`
- `in_transit`
- `received`
- `inspecting`
- `remedy_approved`
- `replacement_processing`
- `refund_processing`
- `resolved`
- `closed`

Refund states remain separate from request/order states:

- `requested`
- `pending_provider_or_transfer`
- `succeeded`
- `failed`
- `cancelled`

Transitions are validated server-side and cannot regress from stale provider, courier, or staff events. Every material transition stores actor/source, timestamp, reason, correlation/reference, and customer-notification state.

#### Admin permissions and audit

Separate permissions control:

- Approving/rejecting cancellation
- Approving/rejecting return
- Recording receipt/inspection
- Selecting inventory disposition
- Creating replacement orders
- Initiating refund
- Overriding calculation/policy within authorized bounds
- Marking offline COD refund transfer complete
- Viewing protected bank/wallet details

Sensitive actions may require step-up verification. No staff member should be able to invent a refund, exceed the remaining refundable amount, and mark it paid without independent provider/transfer evidence and auditability.

Audit records cover request data, evidence references, eligibility result, policy version, decision, reason, calculations, inventory movements, replacement link, refund references/status, overrides, communications, and actors. Audit views redact protected transfer information.

#### Customer communication

Before purchase:

- State clearly that launch products are not returnable for change of mind.
- Present product/category-specific issue eligibility, 48-hour visible-issue reporting guidance, and legal-right qualification.
- Explain cancellation availability before dispatch.

During a request:

- Confirm receipt and whether more information is needed.
- Show affected item/quantity, current state, expected review step, and support path.
- Provide approved safe return instructions and reference.
- Communicate inspection outcome and remedy options.
- Distinguish refund initiation from settlement.
- Explain rejection specifically and provide a review path.

Messages must not blame the customer, imply fraud without a reviewed finding, or use “no returns” as a substitute for investigating seller-attributable issues.

#### Metrics and policy review

Track:

- Cancellation rate and point in lifecycle
- Issue rate by product, variant, supplier, batch where manually available, courier, and reason
- Requests inside/outside 48 hours and outcomes
- Review and inspection time
- Approval, rejection, replacement, refund, and voluntary-credit rates
- Refund value, shipping refunded, return/replacement transport cost, and write-off
- Repeat defects, leakage, tampering, expiry, authenticity, and listing-accuracy issues
- Customer contacts/escalations and overturned decisions

The strict no-change-of-mind policy, 48-hour visible-issue window, remedy preferences, transport cost, and resolution targets must be reviewed after pilot evidence. Policy changes apply prospectively with versioning and do not remove rights attached to prior orders.

### 1.12 Pakistan tax and invoice behavior

#### Current status and launch blocker

As of 14 October 2026, SoftGlam is not yet operating through a registered Pakistani business entity and holds neither an NTN nor an STRN. Therefore:

- No production sales-tax rate, exemption, product classification, withholding treatment, invoice format, or FBR integration scope is approved yet.
- SoftGlam must not charge, collect, label, or imply collection of sales tax until registration status and applicable obligations are confirmed by a qualified Pakistani tax/accounting professional.
- SoftGlam must not display a fabricated NTN, STRN, FBR invoice number, QR code, registration status, or tax amount.
- Public production ordering remains blocked until business identity, registrations, tax treatment, invoicing obligations, record retention, and electronic-invoicing applicability are documented and approved.
- Development and staging may use clearly marked synthetic tax profiles and test documents that cannot be mistaken for legal invoices.

`FOUND-008` approves the product and architecture behavior around an adviser-approved compliance profile; it does not approve a tax rate or replace legal/accounting advice.

#### Official context reviewed

The planning review considered current official FBR material available on 14 October 2026:

- FBR Digital Invoicing FAQs state that a structured electronic invoice is distinct from a scan/PDF, discuss mandatory integration for applicable registered persons, identify licensed integrators/PRAL, describe debit/credit notes for invoice corrections, and state that digital invoices are issued at the statutory time of supply.
- FBR Finance Act 2025–26 explanatory material describes e-commerce treatment for digitally ordered goods and collection/withholding roles involving payment intermediaries and couriers for online and COD settlement.
- FBR rules and circulars describe reporting and withholding obligations for relevant online marketplaces, payment intermediaries, and couriers.

Authoritative law, rules, notifications, current FBR implementation guidance, and professional advice prevail over this product document. Official references reviewed:

- `https://fbr.gov.pk/faqs/173967/173969`
- `https://download1.fbr.gov.pk/Docs/202584118361586CircularNO02of2025-26SalesTax&FederalExcise.pdf`
- `https://download1.fbr.gov.pk/SROs/2025858833282SRO1429DATED04-08-2025.pdf`

Requirements must be revalidated immediately before implementation and launch because tax law, notifications, rates, classifications, thresholds, and integration requirements can change.

#### Approved customer price presentation

SoftGlam uses tax-inclusive consumer pricing whenever an adviser-approved profile determines that tax applies.

- Product cards and product pages display the final customer-facing unit price inclusive of applicable product tax.
- Cart and checkout do not add a surprise product-tax amount above the advertised price.
- Order review may show an included-tax breakdown when legally accurate and supported by the active tax profile.
- The final payable total remains merchandise after discounts plus shipping and any other lawfully approved charge; included tax is a component of those amounts, not an extra unannounced amount.
- If a product or shipping charge is exempt, zero-rated, outside scope, or treated differently, the customer-facing wording follows the approved classification and must not casually label every zero amount “tax free.”
- Tax-inclusive display does not permit SoftGlam to absorb or invent tax silently; configured gross prices, net/tax components, margins, and accounting treatment must reconcile.
- `Rs` may be used for customer display; invoices and stored financial data identify PKR explicitly where required.

Until a registered/adviser-approved production profile exists, storefront prices are ordinary advertised selling prices and customer documents must not claim that a sales-tax amount was collected.

#### Compliance profile

The backend supports one active, versioned production tax profile with effective dates. It contains only adviser-approved configuration:

- Legal business name and trading name
- Business type and registered address
- NTN and STRN where applicable
- Registration/electronic-invoicing status
- Tax jurisdiction and effective period
- Product tax classes and applicable sale/supply type
- HS/product classification where required
- Rate or approved exempt/zero/out-of-scope treatment
- Shipping, discount, return, advance-receipt, and refund treatment
- Time-of-supply/invoice-trigger mapping for prepaid, COD, cancellation, and return flows
- FBR/licensed-integrator/PRAL configuration where applicable
- Required invoice fields, number sources, QR/signature data, and document labels
- Rounding, allocation, reporting, record-retention, and correction rules
- Payment-intermediary and courier withholding/reconciliation treatment
- Reviewer, evidence references, approval date, effective date, and superseded version

A profile can be `draft`, `approved_for_staging`, `active_production`, `suspended`, or `retired`. Only an effective `active_production` profile permits production tax calculation and legally represented invoices. Activation and suspension require owner-authorized action, step-up verification, and audit history.

Tax changes use prospective effective dates. They do not rewrite confirmed orders, issued documents, prior returns, or historical reports.

#### Product and shipping tax classification

Each sellable variant inherits or explicitly receives an adviser-approved tax class. A class may include classification code, HS code where needed, sale type, treatment, rate basis, and effective period.

- Jewellery and beauty are not assumed to share the same classification or treatment.
- Externally branded and SoftGlam-owned products are not assumed to share treatment merely because they use the same product type.
- The same HS code does not automatically prove identical sale/purchase type or tax treatment.
- Shipping is classified separately from goods where required.
- A discount changes consideration/allocation according to the active profile; it does not change classification arbitrarily.
- A product cannot publish to production if its required tax class is missing, expired, conflicting, or outside the active profile.
- Staff without tax-configuration permission cannot select arbitrary rates or override treatment during order entry.

Catalog labels such as “tax included,” “exempt,” or “zero-rated” derive from the active profile and are never free-form marketing claims.

#### Trusted tax calculation

The Laravel backend calculates tax during cart estimation and recalculates it transactionally before checkout/order confirmation.

- All monetary amounts use integer minor units and ISO currency `PKR`; floating-point math is prohibited.
- Rates use exact configured decimal/basis-point representations suitable for the approved rule.
- Tax-inclusive component extraction follows the approved statutory/accounting method.
- Rounding occurs at the approved level—line, invoice, or other required basis—and is documented in the profile.
- Rounding remainders are allocated deterministically so line components equal order/document totals.
- Item discounts, order discounts, shipping discounts, returns, cancellations, and refunds allocate consistently across affected lines.
- Client-supplied tax, net, rate, or classification values are ignored.
- The calculation stores rule/profile version, class, rate/treatment, gross, net/taxable value, tax component, discount allocation, shipping treatment, rounding, and evidence references needed for audit.

Cart tax remains an estimate until the trusted confirmation/invoice event. If price, discount, destination, classification, rate, shipping, or effective profile changes before commitment, the customer reviews the new final total.

#### Order tax snapshot

Every confirmed order preserves immutable tax context sufficient to reproduce its financial result without consulting mutable catalog/configuration rows:

- Seller identity/profile version
- Customer/buyer fields required for that document type
- Supply/payment method and relevant dates
- Product/variant description and classification snapshot
- Quantity and unit gross price
- Discount and adjustment allocation
- Tax treatment and rate
- Net/taxable value and tax component
- Shipping treatment
- Gross line/order totals and rounding
- Currency
- Invoice trigger/status and external references

Historical order display uses this snapshot. Later registration, rate, address, product, customer, or profile changes do not alter it.

#### Document types

SoftGlam distinguishes:

- **Order acknowledgement:** Confirms receipt of an order/checkout attempt; not automatically a tax invoice or proof of payment.
- **Payment receipt:** Records a verified payment/collection event; not automatically a tax invoice unless the approved regime/document satisfies that role.
- **Commercial receipt:** Customer document used when legally appropriate without falsely representing sales-tax registration.
- **Tax invoice/e-invoice:** Issued only under an adviser-approved registered profile and applicable FBR requirements.
- **Advance-receipt invoice:** Supported when the approved time-of-supply rule requires invoicing on advance/prepayment.
- **Credit note:** Corrects/reduces a previously issued invoice for approved cancellation, return, refund, or value change.
- **Debit note:** Corrects/increases value/tax only when lawfully applicable and never as a way to add a hidden post-checkout charge.
- **Refund confirmation:** Records refund method, amount, status, and reference; does not replace a required credit note.

The UI and emails use the correct document name. A PDF rendering of an internal receipt is not described as an FBR electronic invoice unless it was generated/reported in the approved structured process and includes required verification data.

#### Invoice timing

The exact legal time-of-supply and invoice trigger must be approved by the Pakistani adviser and encoded per payment method.

The system must be capable of representing:

- Online payment/advance received before dispatch
- COD payment collected at delivery
- Partial fulfillment or partial supply where permitted
- Cancellation before/after invoice issuance
- Failed or reversed payment
- Return and refund after invoice issuance
- Replacement order linked to a return

Order confirmation, dispatch, delivery, payment collection, payment reconciliation, invoice issuance, and document transmission are separate events. The system does not issue or backdate a tax invoice merely because an order exists.

#### Numbering and external verification

Internal order numbers, internal document UUIDs, local document sequence, provider references, and FBR/external invoice numbers are separate identifiers.

- Document numbers are unique, immutable after issuance, and generated atomically.
- No number is silently reused after failure, cancellation, or void.
- Sequence gaps are retained/explained rather than concealed.
- If FBR/integrator returns a unique invoice number, QR code, digital signature, or verification data, preserve it with transmission status and response evidence.
- Customer documents display only identifiers required/useful for verification; internal secrets and unrestricted integration payloads remain private.
- Duplicate requests return/recover the existing document result instead of issuing a second invoice.

The final numbering format remains pending adviser and integration approval. Do not hard-code a cosmetic `INV-...` format as the legal sequence before that review.

#### Required customer document content

Subject to the active profile and document type, support:

- Legal seller/trading identity and address
- NTN/STRN only when valid and applicable
- Document label, unique number, issue date/time, and supply date/time where required
- Order reference
- Customer name/address and identifiers only where legally required
- Product/variant description, SKU/reference where appropriate, quantity, and unit price
- Gross/net/taxable value, discount, tax rate/treatment/component, shipping, and total
- Currency and payment method/status
- Credit/debit note reference to original invoice
- Required FBR/integrator number, QR, signature, or verification method
- Support/contact and concise correction information

Do not require CNIC or other identity information from an ordinary consumer unless current law/adviser guidance specifically requires it for the transaction/document. Collect the minimum required data and protect it according to privacy controls.

#### Delivery and access

Approved customer choice: documents are available through both email and secure account/order access.

- Authenticated customers access documents from the authorized order detail.
- Guest customers use the secure limited order-access mechanism; knowledge of a sequential order number alone is insufficient.
- Email may contain an approved attachment or secure link according to privacy/security review.
- Documents are generated server-side from immutable snapshots and issuance records.
- Revoked/superseded renderings remain traceable; corrected documents link to the original rather than replacing history silently.
- Accessibility applies to HTML/document presentation; a PDF alone is not the only accessible representation of essential order totals.
- Email delivery failure does not invalidate an otherwise lawfully issued document; failure is retried and visible to staff.

#### Cancellation, return, and correction documents

- Before invoice issuance, an eligible cancellation may cancel the pending invoice event without inventing a credit note when the approved profile permits.
- After invoice issuance, cancellation, return, refund, or value correction generates the required linked credit/debit note rather than editing/deleting the invoice.
- Corrections reference original document, affected lines, quantity, value, tax allocation, reason, actor, date, and external submission status.
- Partial refunds generate proportional, deterministic components and cannot exceed remaining refundable/tax amounts.
- Replacement orders have their own document lifecycle and reference the remedy relationship.
- Failed external transmission remains an operational exception; staff cannot mark legal integration complete without a successful response or approved outage procedure.

#### Payment-intermediary and courier withholding

Online-payment and COD settlement may involve statutory withholding, reporting, or deduction by a payment intermediary or courier. This is modeled separately from customer-facing order tax.

- Record gross customer amount, provider/courier fees, each withholding/deduction type, remitted net amount, statement period, external reference, and reconciliation status.
- Do not reduce the customer’s paid/collected order amount merely because the intermediary remits less after withholding/fees.
- Do not display an intermediary’s settlement withholding as an extra customer tax unless current approved law requires customer-facing treatment.
- Reconcile COD collection, online settlement, withholding certificates/statements, tax documents, refunds, chargebacks, and bank deposits without collapsing them into one status.
- Rates and reporting mappings require adviser approval and effective dating.

#### Records, security, and audit

- Retain invoices, notes, order snapshots, calculations, profile versions, external requests/responses, acknowledgements, settlement/withholding records, and corrections for the legally approved retention period.
- The technical design must support at least the currently advised period and legal hold; the final duration requires professional confirmation.
- Tax documents and integration logs are append-only/immutable except for explicit status progression and linked corrections.
- Secrets, private keys, integrator credentials, and unrestricted payloads never appear in customer documents, browser code, ordinary admin settings, or logs.
- Structured logs redact unnecessary customer/tax identifiers while retaining correlation IDs.
- Access to tax settings, documents, exports, and protected identifiers is permission-controlled and audited.
- Activation, rate/classification change, document issue, cancellation/note, resend, export, and manual reconciliation create audit events.

#### Failure and outage handling

The system supports explicit states such as:

- `not_required`
- `pending_issue`
- `pending_transmission`
- `issued`
- `transmission_failed`
- `accepted_external`
- `rejected_external`
- `corrected`
- `cancelled_by_note`

External FBR/integrator downtime must not lead to duplicate invoices, invented acceptance, lost sequence/evidence, or silent tax omission. The approved outage procedure determines whether supply can proceed, what temporary document is issued, retry timing, and customer follow-up. This procedure must be confirmed before launch if integration applies.

#### Required professional decisions before production

A qualified Pakistani tax/accounting professional must confirm and document:

1. Legal entity/business registration path and required launch registrations.
2. NTN, STRN, and sales-tax registration applicability/effective dates.
3. Federal/provincial jurisdiction and treatment relevant to sold goods, shipping, and business activities.
4. Jewellery and beauty classifications, HS codes where applicable, sale types, rates, exemptions, and third-schedule/retail-price treatment where relevant.
5. Whether and how FBR electronic invoicing integration applies; licensed integrator/PRAL process and testing.
6. Time-of-supply and invoice trigger for online prepayment, COD, partial fulfillment, cancellation, return, replacement, and advance receipt.
7. Mandatory invoice, receipt, credit/debit note fields and numbering.
8. Courier/payment-intermediary withholding, income-tax/digital-transaction obligations, certificates, returns, and reconciliation.
9. Price-marking, record-retention, buyer-identity, and reporting obligations.
10. Whether any beauty/jewellery-specific duties, levies, import, or labeling rules affect pricing/documents.
11. Treatment of discounts, free shipping, shipping charges, refunds, bad debt, failed delivery, and store credit.
12. Required accounting chart/mappings and filing ownership.

Approval evidence is recorded as a dated decision with adviser identity/engagement reference, reviewed sources, configuration values, effective date, and owner sign-off. Secrets and unnecessary personal adviser data are not stored in the product configuration.

#### Launch acceptance gate

Production commerce cannot launch until:

- Business identity and registrations are complete or a professional explicitly confirms the lawful alternative.
- Active tax profile and product/shipping classifications are approved.
- Price display and checkout totals reconcile with accounting examples.
- Required invoice/e-invoice integration passes sandbox/acceptance tests.
- Prepaid, COD, discount, free shipping, cancellation, full/partial refund, replacement, credit/debit note, rounding, and outage scenarios pass.
- Email and secure account/guest document access pass authorization and accessibility review.
- Settlement and withholding reconciliation is demonstrated with synthetic data.
- Record retention, backup, export, audit, permissions, and incident ownership are approved.

### 1.13 Online payment provider and supported methods

#### Provider decision

- **Primary integration target:** Safepay
- **Commercial/onboarding fallback:** PayFast
- **Launch integration count:** One live online gateway; no automatic multi-gateway routing/failover
- **Checkout mode:** Safepay-hosted redirect
- **Currency:** PKR only
- **Launch card scope:** Pakistan-issued cards supported and approved for the merchant account
- **Wallet scope:** Safepay-supported mobile wallets explicitly enabled in the merchant contract and verified in sandbox/acceptance testing
- **Saved payment methods:** None
- **Subscriptions/recurring payments:** None
- **Online-payment surcharge:** None unless a future legally/commercially reviewed decision changes displayed checkout pricing before commitment
- **Pending online-payment inventory reservation:** 30 minutes
- **Refund path:** Tested Safepay API for full and partial refunds
- **Provider outage fallback:** Offer COD only when the order independently satisfies COD policy; otherwise preserve the cart and ask the customer to retry later

Safepay is an integration target, not yet an active contracted provider. SoftGlam cannot accept live online payments until legal/tax registration, merchant underwriting, bank settlement setup, commercial terms, enabled methods, security credentials, webhook behavior, refund capability, reconciliation exports/API, and production acceptance are complete.

PayFast is not integrated simultaneously at launch. It becomes the replacement provider candidate if Safepay onboarding, contract terms, method coverage, refund behavior, reliability, compliance, or support fails an approval gate. Switching providers uses the payment adapter boundary and a new reviewed implementation/acceptance process; it is not a runtime toggle that bypasses testing.

#### Official provider context reviewed

The decision used public official provider material available on 14 October 2026:

- Safepay describes hosted and embedded checkout, API integration, dashboard reconciliation/refunds/reports, payment links, and Raast availability.
- Safepay’s services agreement places merchant responsibility on applicable business taxes and permits required withholding/reporting.
- PayFast publicly describes hosted checkout/API, cards, bank accounts, mobile wallets, Raast, refunds/reporting, sandbox onboarding, published fee categories, and a standard T+2/3 working-day settlement cycle.
- JazzCash publishes sandbox/API material for payment, status update, authorization/capture/void/refund operations, with method-specific refund limitations requiring careful validation.
- Easypaisa describes hosted checkout, APIs/SDKs, wallet/OTC methods, merchant reporting, reversals, and settlement portal capabilities.

References reviewed:

- `https://safepay.pk/checkout`
- `https://safepay.pk/payments`
- `https://safepay.pk/legal/content/services-agreement`
- `https://safepay.pk/blog/content/safepay-raast`
- `https://gopayfast.com/docs/`
- `https://gopayfast.com/faqs/`
- `https://gopayfast.com/pricing-payfast/`
- `https://payments.jazzcash.com.pk/SandboxDocumentation/ApiReferences.html`
- `https://easypaisa.com.pk/online-payment-gateway/`

Public marketing pages do not replace a signed merchant contract, current API documentation, underwriting decision, or end-to-end tests. Exact Safepay wallets, card schemes, fees, taxes/withholding, reserves, payout timing, refund limits, dispute terms, and support SLAs remain unapproved until onboarding.

#### Launch payment methods

Customer-facing online methods appear only when Safepay confirms they are active for SoftGlam:

1. **Pakistan-issued payment cards** — provider-hosted entry and authentication; exact schemes follow the approved merchant account.
2. **Mobile wallets** — only wallet brands and flows contractually enabled and successfully tested for the merchant.

Do not display generic JazzCash/Easypaisa or scheme logos merely because Safepay can support them for another merchant. Method names, logos, limits, customer fields, authentication, refundability, and settlement must come from the active capability configuration.

Raast and bank-account payment are not launch requirements. The adapter and product language must not imply support. They may be evaluated later through an explicit scope decision.

#### Hosted-checkout security boundary

- Card numbers, expiry, CVV, wallet PINs, wallet OTPs, bank credentials, and provider authentication secrets never enter SoftGlam forms, Laravel requests, databases, analytics, session replay, support tools, or logs.
- SoftGlam sends only the provider-required amount, PKR currency, safe customer/contact data, return/cancel URLs, internal reference, and permitted metadata from the trusted server.
- Hosted payment URLs/sessions are created server-side and bound to one checkout/payment attempt, expected amount/currency, and expiration.
- Redirect destinations use configured allowlisted HTTPS URLs; customer-provided redirect URLs are prohibited.
- Provider SDK/scripts, if needed before redirect, are loaded only from approved sources under an appropriate content-security policy.
- No saved-card/tokenization or recurring-payment consent appears at launch.
- Hosted checkout reduces but does not eliminate PCI/security obligations; the final integration follows provider/acquirer requirements and receives security review.

#### Payment initiation flow

1. Customer selects online payment after shipping and contact/address data are valid.
2. Laravel recalculates active product prices, discounts, tax, shipping, inventory, and final PKR total.
3. Laravel creates/reuses an idempotent checkout, pending order reference where required, and one active payment attempt.
4. Inventory is atomically reserved for 30 minutes.
5. Laravel creates the Safepay hosted session using an idempotency key/internal reference and stores the provider session/reference without secret leakage.
6. Browser redirects to the exact provider-hosted URL.
7. Customer completes or abandons provider authentication.
8. Browser returns to a neutral pending/result page, which queries SoftGlam’s trusted status.
9. A signed/authenticated provider notification and/or trusted server retrieval establishes authoritative payment state.
10. On verified success, transactionally confirm the payment/order, commit inventory, close the checkout, start the delivery estimate, and queue one confirmation.
11. On verified failure/cancellation/expiry, release or retain reservation according to the active attempt/window and show a safe retry path.

The return/success URL is never proof of payment. Browser parameters, screenshots, customer statements, email receipts, or frontend state cannot mark an order paid.

#### Thirty-minute reservation

- The reservation expires 30 minutes after the active hosted payment attempt begins, using server time.
- Checkout shows a clear approximate expiration without claiming the provider session itself expires at exactly the same instant unless verified.
- An idempotent scheduled job releases expired reservations that are not attached to a verified successful payment.
- Expiry, webhook, status retrieval, and success processing lock the relevant records so a late event cannot both release and commit the same stock.
- A payment reported successful after reservation release enters a payment/inventory exception; the system does not silently oversell or ask the customer to pay again.
- Resolution may allocate still-available stock, cancel/refund the payment, or contact the customer under an authorized workflow.
- Extending a reservation requires an explicit supported rule and cannot occur indefinitely from browser refreshes.

#### Retry and unknown outcomes

- Only one active online payment attempt is presented for a checkout at a time.
- A retry creates a new attempt/reference linked to the same checkout/order context after the prior attempt is terminal or checked through trusted provider status.
- Refresh, back-button, network retry, and repeated submit reuse/recover the idempotent result rather than creating duplicate sessions or charges.
- If outcome is unknown, show “We’re checking your payment” and reconcile before offering another payment attempt.
- Never tell the customer to pay again merely because the redirect failed.
- If two attempts unexpectedly succeed, flag a duplicate-payment exception and refund the duplicate through the approved flow; create only one order/inventory sale effect.
- Switching from an unresolved online attempt to COD requires trusted resolution or expiry; it cannot produce both a collectible COD order and a captured online payment unnoticed.

#### Provider notification and verification

Implementation must follow Safepay’s current contracted documentation; field/header names are not guessed in requirements.

- Preserve the raw request body when required for signature/authentication verification.
- Verify signature/authentication, timestamp/freshness where provided, environment/account, and expected event type before processing.
- Reject invalid notifications safely without leaking verification details.
- Deduplicate using the provider’s immutable event ID or the strongest documented equivalent plus an internal uniqueness rule.
- Match provider session/transaction to internal attempt and verify PKR currency and exact expected amount.
- Store provider event/reference, normalized type/status, received/processed timestamps, verification result, safe payload metadata, and processing outcome.
- Encrypt/restrict raw payload retention where required; logs contain no credentials or payment instrument data.
- Process valid events idempotently and tolerate duplicates and out-of-order delivery through allowed state transitions.
- Use server-to-server status retrieval/reconciliation when notification delivery is delayed, missing, or ambiguous.

Provider event receipt and business processing are separate: a valid event can still enter exception when amount, currency, order, attempt, or state does not match.

#### Internal payment states

Provider-specific statuses map through the adapter into stable internal states:

- `created`
- `pending_customer_action`
- `processing`
- `authorized` where actually supported/used
- `captured`
- `failed`
- `cancelled`
- `expired`
- `partially_refunded`
- `refunded`
- `chargeback_or_dispute`
- `exception`

Do not expose raw provider codes as the sole customer message. Preserve raw code/reference for authorized support while mapping to a safe state and actionable message.

Launch defaults to the provider’s approved one-time purchase flow. Authorization/capture separation is not assumed; if Safepay requires/improves a different capture model, it needs contract confirmation, inventory/state design review, and test evidence before activation.

#### Refund requirements

Live launch requires Safepay API support, enabled for SoftGlam and verified in sandbox/production acceptance, for:

- Full refunds
- Partial refunds
- Multiple partial refunds up to the remaining refundable amount, if the contract/API supports them
- Idempotent request/recovery behavior or a safe internal idempotency boundary
- Provider refund reference and status retrieval/notification
- Clear eligibility windows and settlement dependencies
- Failure and pending-state visibility

If multiple partial refunds are not supported, the product/refund policy must be reconciled before launch; staff cannot bypass accounting integrity with undocumented transfers. A provider dashboard is not the normal launch refund path under the approved decision. Any emergency manual provider action requires an incident procedure, dual review where appropriate, immediate SoftGlam reconciliation, reference capture, and audit event; it cannot be used as routine functionality.

Refund commands:

- Authenticate and authorize staff.
- Lock payment/refund records.
- Recalculate remaining refundable amount from immutable allocations and pending/successful refunds.
- Send integer minor-unit PKR amount and idempotency/reference data.
- Record pending before/atomically with external initiation according to failure-safe design.
- Treat timeout/unknown response as pending reconciliation, not safe failure/retry.
- Mark success only from trusted provider evidence.
- Notify the customer of initiation and expected settlement separately from receipt.

#### Provider outage behavior

When Safepay session creation or availability fails:

- Do not redirect to an invalid/stale URL or expose raw provider errors.
- Mark online payment temporarily unavailable with a factual message.
- Offer COD only if the current order independently satisfies all COD eligibility, value, restriction, and verification rules.
- If COD is ineligible or the customer declines it, preserve the cart/checkout where safe and offer retry later.
- Do not silently move the customer to COD.
- Do not runtime-route to PayFast at launch.
- Monitor and alert on elevated creation failures, notification lag, status-retrieval failure, refund failure, and reconciliation mismatch.

#### Settlement and reconciliation

Payment capture and merchant settlement are separate.

For every provider transaction/settlement, support:

- Gross customer amount
- Currency
- Provider transaction and settlement references
- Payment method/channel without sensitive instrument data
- Captured, refunded, disputed, and chargeback amounts
- Provider fee and applicable tax
- Statutory withholding/deduction
- Net settled amount
- Expected/actual settlement date
- Bank reference
- Reconciliation status and exception reason

Automated or staff-assisted reconciliation compares SoftGlam payments/refunds with Safepay transaction reports/API, settlement reports, withholding evidence, and bank deposits. Differences remain visible; staff cannot force-match without reason and audit.

Reconciliation must detect:

- Successful provider payment with no confirmed order
- Confirmed prepaid order without verified provider capture
- Amount/currency mismatch
- Duplicate capture
- Missing/delayed notification
- Refund mismatch
- Fee/withholding variance
- Settlement missing/short/duplicated
- Chargeback/dispute

#### Disputes and chargebacks

- Provider dispute/chargeback events never delete the order or rewrite captured history.
- Record amount, currency, reason/category, deadline, evidence status, provider reference, provisional/final outcome, fee, and actor.
- Restrict evidence exports to authorized staff and minimize customer data.
- Order/payment reporting distinguishes captured sales, refunds, and disputes.
- Inventory is not automatically restocked because of a chargeback.
- Customer account/COD access is not automatically punished without reviewed evidence and policy authority.

Detailed dispute response remains an operational procedure to approve with the merchant contract.

#### Customer communication

Before redirect:

- Show final PKR amount, shipping, included tax treatment where approved, selected method, and provider-hosted transition.
- Identify Safepay accurately without implying SoftGlam receives card/wallet credentials.
- Explain that the order is confirmed only after payment verification.

After return:

- `Pending`: “We’re checking your payment. Don’t pay again yet.”
- `Successful`: show confirmed order only after trusted verification.
- `Declined/failed`: state safe provider outcome and permit retry/other eligible method.
- `Cancelled`: distinguish customer/provider cancellation from a declined payment.
- `Unknown/delayed`: preserve order context and provide status/support path.

Do not expose raw decline details that create security/privacy risk. Do not say the customer was charged/refunded unless trusted records support the statement.

#### Merchant onboarding and acceptance gates

Safepay must pass all gates before production activation:

1. SoftGlam legal business, NTN/tax status, settlement bank account, website/policies, and KYC/underwriting documents are accepted.
2. Signed commercial terms confirm fees, taxes/withholding, reserves/holds, settlement cycle, refund/dispute fees, data processing, termination, and support/escalation.
3. PKR, Pakistan-issued cards, and named mobile wallets are enabled for the merchant.
4. Hosted checkout works on representative mobile/desktop browsers and accessible customer flows.
5. Test credentials, production credential process, IP/domain/URL allowlisting, and secret rotation are documented.
6. Notification signature/authentication, event identity, retry behavior, ordering, and status retrieval are verified from current provider docs and tests.
7. Full, partial, repeated partial, pending, failed, and duplicate refund behavior satisfies SoftGlam policy.
8. Settlement/report/API data supports transaction, fee, tax/withholding, refund, dispute, and bank reconciliation.
9. Success, decline, cancellation, timeout, abandoned redirect, duplicate submission, delayed/missing notification, out-of-order event, provider outage, and late success after stock expiry pass.
10. Incident contacts, status communication, escalation SLA, and account recovery are owned.
11. Security, privacy, logging, retention, webhook rate limits, and PCI responsibilities are reviewed.
12. Production smoke testing uses controlled real transactions/refunds and synthetic customer data where permitted.

Failure of a mandatory gate keeps online payment disabled. COD may support launch only if the business deliberately approves a COD-first release and every other legal/operational gate passes.

#### Provider decision review

Review Safepay after onboarding and after meaningful production volume using:

- Authorization/payment success by method and failure category
- Checkout redirect/return completion
- Notification latency and delivery gaps
- Refund success and time
- Settlement timeliness and reconciliation exceptions
- Effective fee/tax/withholding cost
- Chargeback/dispute rate
- Support response and incident quality
- Customer contacts and abandonment by method

Changing to PayFast or adding another provider requires a new contract, data/security review, adapter implementation, method capability mapping, migration/fallback plan, and full acceptance suite. Existing Safepay attempts, refunds, disputes, and settlements remain handled through their original provider adapter.

### 1.14 Transactional email provider and notification policy

#### Provider decision

- **Primary provider:** Resend
- **Replacement candidate:** Postmark
- **Launch scope:** Transactional email only
- **Integration:** Laravel mail transport/API with queued application jobs
- **Template source of truth:** Version-controlled application code
- **Intended From identity:** `SoftGlam <hello@softglam.pk>`
- **Intended Reply-To:** Monitored `support@softglam.pk`
- **Engagement tracking:** Open and click tracking disabled
- **Operational events:** Accepted/sent, delivered, delayed/deferred where available, bounced, complained, rejected/failed
- **Invoice/receipt delivery:** Email summary with a secure authorized document/order link; no routine PDF attachment
- **Outage behavior:** Queue and bounded retry; no automatic second-provider failover at launch

The exact domain/address values remain pending domain ownership, DNS, support mailbox, and provider verification. Production must not send as `softglam.pk` until SoftGlam controls the domain and authentication passes. Development uses provider-approved test identities and non-customer addresses.

Resend is not yet an active production provider. Account approval, plan/capacity, domain authentication, secrets, webhook verification, suppression behavior, privacy/security review, and end-to-end acceptance remain launch gates.

Postmark is a replacement candidate, not a simultaneous runtime fallback. A provider switch requires domain/setup review, adapter/transport configuration, webhook mapping, suppression migration, deliverability warmup where appropriate, and acceptance tests. Existing Resend message/event history remains attributed to Resend.

#### Official provider context reviewed

Official public material available on 14 October 2026 indicates:

- Resend provides REST API, SMTP relay, Laravel guidance, verified domains, SPF/DKIM/DMARC support, signed webhooks, API-key permissions, and transactional plans.
- Resend’s Laravel documentation describes a mail driver and webhook signature verification using a signing secret.
- Postmark provides transactional message streams, Laravel/Symfony transport options, event webhooks, templates, sandbox/developer capability, and paid plans.
- Brevo provides transactional API/SMTP, PHP SDKs, delivery/bounce/complaint webhooks, and marketing capabilities that are unnecessary for launch.
- Amazon SES provides low-cost API/SMTP sending and detailed event publishing but requires additional AWS/IAM/event-routing and deliverability operations.

References reviewed:

- `https://resend.com/pricing`
- `https://resend.com/docs/send-with-laravel`
- `https://resend.com/docs/explore-sending-features`
- `https://postmarkapp.com/pricing`
- `https://postmarkapp.com/email-webhooks`
- `https://postmarkapp.com/blog/how-to-send-transactional-emails-with-laravel-php-framework`
- `https://www.brevo.com/products/transactional-email/`
- `https://aws.amazon.com/ses/pricing/`
- `https://docs.aws.amazon.com/ses/latest/dg/monitor-using-event-publishing.html`

Provider capabilities, limits, retention, pricing, and terms may change. Current contract/docs and tests prevail during implementation.

#### Transactional-only boundary

Launch email exists to complete or explain a customer/account/operational transaction. It does not include newsletters, promotions, abandoned-cart campaigns, cross-sell broadcasts, birthday messages, or lifecycle marketing.

- Newsletter signup may collect valid consent if separately designed, but launch transactional infrastructure does not send campaigns to that list.
- A purchase, account, support contact, or transactional email does not create marketing consent.
- Transactional templates cannot hide promotional campaigns inside essential messages.
- Product recommendations and discount banners are excluded from authentication, payment, refund, safety, and complaint messages.
- Marketing email later requires separate consent, preference, unsubscribe, audience, sender-stream/domain reputation, content, suppression, analytics, and legal review.

#### Launch notification catalog

P0 authentication/security:

- Email verification
- Password reset
- Password/security-sensitive account-change notice where appropriate
- Staff invitation and privileged access/security notice

P0 commerce:

- COD order received/pending verification
- COD verified/confirmed
- COD expired, declined, or cancelled
- Online payment/order confirmed
- Payment pending/exception follow-up when customer action or reassurance is required
- Order cancellation and payment reversal/refund initiation
- Shipment dispatched/tracking
- Delivery exception, address exception, failed attempt, and retry action
- Delivered/completed where reliable evidence exists
- Refund initiated, failed/action required, and completed
- Tax/commercial document available through secure link where applicable

P1 operational maturity:

- Return request received
- More information required
- Return approved/rejected
- Return instructions
- Item received/inspection outcome
- Replacement created/dispatched
- Return/refund resolution
- Back-in-stock and abandoned-checkout messages remain separate consent/product decisions and are not transactional launch defaults.

Each notification has a stable event code and versioned template. One domain event may produce at most the intended notification effect through an idempotency key. Templates do not infer status from mutable product rows; they render from an immutable/minimized event and order snapshot.

#### Sender identity and replies

Intended launch identity:

- From display: `SoftGlam`
- From address: `hello@softglam.pk`
- Reply-To: `support@softglam.pk`

Rules:

- `support@softglam.pk` must be a real monitored mailbox/workflow with named ownership, response hours, access control, and escalation; it cannot silently discard replies.
- Automatic replies/loops are detected and do not create infinite notification/support cycles.
- Sender identity remains consistent; do not use customer-supplied From addresses.
- Security-sensitive messages may use a distinct purpose sender only through a later approved decision with the same authenticated organizational domain.
- A customer reply is support correspondence and follows retention/privacy rules; it is not automatically parsed into trusted order commands.
- Staff must verify identity/authorization before acting on cancellation, address, refund, or account-security instructions received by email.

#### Domain authentication and deliverability

Before production:

- Verify ownership of the final sending domain in Resend.
- Publish provider-approved SPF and DKIM records without creating invalid duplicate SPF policies.
- Publish DMARC with monitored rollout and an owned aggregate-report destination; strengthen enforcement only after legitimate senders align.
- Configure custom return-path/bounce handling where supported and approved.
- Ensure MX/support-mailbox configuration does not conflict with sending records.
- Separate production from development/staging identities or domains so tests cannot affect customers/reputation.
- Prohibit sending from consumer mailbox domains or unauthenticated cPanel/PHP mail.
- Test common inbox providers, mobile rendering, plain text, spam placement indicators, and link/domain reputation.

A dedicated IP is not appropriate for the low-volume pilot unless provider evidence later justifies it; poor warmup can reduce deliverability.

#### Application-owned templates

Laravel application code is the launch source of truth for subject, HTML, plain text, event schema, localization-ready strings, and template version.

- Template changes use pull-request review, automated rendering/content tests, staging previews, and deployment history.
- Provider-dashboard templates cannot change production message meaning independently.
- Admin users may preview approved templates and send controlled test messages to allowlisted internal addresses; launch admin does not edit arbitrary HTML/content.
- Templates share accessible layout/components but keep event-specific content explicit.
- Every HTML message has a meaningful plain-text alternative.
- Essential information is text, not image-only.
- Images have appropriate alt behavior and do not block comprehension when disabled.
- Color contrast, heading order, link names, mobile reflow, and font fallback meet accessibility standards.
- Subject/preheader are factual and avoid deceptive urgency, all caps, excessive punctuation, or sensitive detail.

#### Content and privacy minimization

Email is not a secure storage vault. Include only what helps the recipient understand and act:

- Customer-safe order reference
- Current factual state
- Minimal item/amount/address summary appropriate to the message
- Expected next step/timeframe
- Secure support/document/order link

Do not include:

- Passwords, reset tokens in visible text, OTPs not designed for that specific secure flow, card/wallet/bank credentials, CVV, or provider secrets
- Full protected bank/wallet refund details
- Internal fraud/risk scores, staff notes, supplier cost, or audit data
- Full unnecessary address/phone in subject/preheader
- Raw provider/courier errors or unrestricted payloads
- Sensitive return evidence as attachments

Security links use random, single-purpose, expiring, server-validated tokens. Store token hashes where practical; do not log raw tokens. Account-authenticated links still enforce resource ownership. Guest links grant only the minimum scoped access and can be revoked/rotated after suspected exposure.

#### Secure document links

Invoice, receipt, credit/debit note, and refund emails contain a concise summary and secure link rather than a routine PDF attachment.

- Authenticated customers land on authorized order/document access.
- Guests use a cryptographically random limited token, not a guessable order number.
- The link resolves to the current authorized immutable document record and preserves superseded/correction relationships.
- Link expiry/re-authentication behavior balances privacy with legally required document access; expiry does not delete the retained document.
- Forwarded email must not expose unrelated account/orders.
- Click tracking is disabled so the provider does not rewrite or collect engagement for sensitive links.

#### Queue and idempotency

Business transactions commit before notification jobs run. Email success/failure never rolls back a valid order, payment, inventory, refund, or security state.

- Create a `notification_delivery` record with event code, recipient, template/version, provider, idempotency key, status, attempts, and safe references.
- Enforce uniqueness for event/recipient/purpose so job retries do not send duplicate confirmations.
- Queue only after the originating database transaction commits.
- Render from a validated snapshot; missing required data fails visibly instead of sending misleading content.
- Send through a least-privilege production API key stored only in protected server configuration.
- Record the Resend message ID and accepted timestamp without storing unnecessary provider payload data.
- Job timeouts/unknown API results enter reconciliation before creating a new send attempt where duplicate delivery is possible.

Password-reset and email-verification request endpoints use enumeration-safe responses and rate limits. Resending invalidates/rotates tokens according to the security design without revealing whether an address exists.

#### Retry policy

Retry only transient send failures such as provider timeout, rate limit, or documented temporary server error.

Recommended launch schedule, configurable and jittered:

1. Initial attempt
2. Approximately 1 minute
3. Approximately 5 minutes
4. Approximately 30 minutes
5. Approximately 2 hours
6. Approximately 6 hours

Stop when:

- Provider accepts the message
- Failure is permanent
- Token/message purpose is no longer valid
- Recipient is suppressed for a relevant hard bounce/complaint
- Maximum attempts/age is reached

“Accepted” means provider accepted the send request, not delivered or read. Webhook events update delivery status separately. Exhausted critical notifications create an owned admin alert/queue with a safe manual recovery action; staff cannot mark delivered manually without evidence.

#### Webhook and delivery events

Use Resend’s current signed webhook specification and Laravel integration guidance; do not guess headers or signature algorithms.

- HTTPS endpoint is outside browser CSRF semantics but verifies provider signature before processing.
- Preserve raw body when required.
- Deduplicate event IDs/equivalents.
- Match provider message ID to one notification delivery.
- Normalize supported events into stable internal states.
- Tolerate duplicate and out-of-order events.
- Retain safe event metadata and timestamps; redact content/secrets.
- Return provider-appropriate success only after durable acceptance/queueing of valid events.
- Invalid events are rejected/logged safely and monitored without exposing verification detail.

Internal delivery states:

- `queued`
- `sending`
- `accepted`
- `delivered`
- `delayed`
- `soft_bounced`
- `hard_bounced`
- `complained`
- `rejected`
- `failed`
- `suppressed`
- `cancelled`

Open/click tracking is disabled and cannot be used to infer that a customer read, accepted, or acted on a message. Delivery also does not prove the correct human read it.

#### Bounce, complaint, and suppression handling

- Hard bounce/invalid recipient suppresses ordinary sending to that address and creates a visible customer/contact-quality issue.
- Spam complaint suppresses non-essential future email immediately and triggers review; do not keep retrying.
- Soft bounce/delay uses bounded provider/application retry guidance without indefinite loops.
- Suppression applies with purpose awareness and legal/security review; it does not silently prevent all account recovery forever without an alternate resolution path.
- A customer may correct an address through an authenticated/verified flow; staff cannot unsuppress arbitrary addresses without evidence and permission.
- Suppression history records provider, reason, source event, date, review, and override.
- Do not automatically transfer one address’s suppression or complaint to unrelated household members.

The application checks local suppression before send and reconciles provider suppression state during incidents/migration.

#### Provider outage and fallback

When Resend is unavailable:

- Valid business/security actions continue when safe; notification jobs remain queued.
- Show in-app confirmation/state so the customer is not told a transaction failed merely because email did.
- Retry transient failures with the bounded schedule.
- Alert staff on elevated send failures, queue age, webhook lag, bounce/complaint spikes, or exhausted critical messages.
- Expose failed notification state and safe resend action to authorized staff.
- Do not automatically send through Postmark/SMTP at launch; dual-provider failover creates duplicate, suppression, authentication, and reputation risks.
- Staff must not send transactional customer data from personal email accounts.

For time-sensitive security links, an outage may require the customer to request a new message after service recovery. The system must not extend a token beyond approved security limits merely to match an email retry.

#### Message-specific correctness

- Order confirmation sends only after COD verification or verified online payment/approved pay-later state.
- A pending checkout acknowledgement cannot use “confirmed” language.
- Shipment email sends only after trusted courier/tracking/handover data exists.
- Delivered email sends only from reliable delivery evidence.
- Refund email distinguishes requested, initiated, failed, and completed; initiation is not settlement.
- Payment unknown/delayed email tells the customer not to pay again until status is checked.
- Tax/receipt email uses the legally correct document label and secure link under section 1.12.
- Cancellation/return decisions state affected quantities, reason/policy basis, amount/method/status where applicable, and review path.
- Templates use order/event snapshots rather than current product titles, prices, addresses, or mutable policies.

#### Monitoring and reporting

Operational dashboard/alerts cover:

- Queue depth and oldest job age
- Accepted/delivered/delayed/failed rates by message type
- Hard/soft bounce and complaint rate
- Provider API/webhook error and latency
- Exhausted critical notifications
- Duplicate-prevention events
- Suppression count and overrides
- Template/version performance for delivery only, not opens/clicks
- Domain authentication/DMARC issues
- Monthly volume versus provider plan/daily capacity

Do not optimize essential-message content around engagement surveillance. Customer support and successful business outcomes—not opens—measure notification usefulness.

#### Resend onboarding and acceptance gates

1. Own final domain and monitored support mailbox.
2. Create production Resend account with owner-controlled MFA and recovery.
3. Select a plan/capacity that safely exceeds expected peak transactional volume; do not rely on a free daily limit without operational approval.
4. Verify sending domain and pass SPF, DKIM, DMARC, From, Reply-To, and return-path checks.
5. Create least-privilege, separately rotated development/staging/production credentials.
6. Configure and verify signed delivery webhooks.
7. Confirm data processing, retention, subprocessors/regions, terms, support, suspension, and export/deletion behavior.
8. Test every P0 template in HTML/plain text, mobile/desktop, common inboxes, screen-reader/keyboard-safe links, and images-disabled mode.
9. Test idempotency, duplicate job, API timeout, rate limit, delayed delivery, hard/soft bounce, complaint, invalid recipient, suppression, webhook duplicate/out-of-order, and provider outage.
10. Verify security-link expiry/revocation, guest scope, account ownership, redaction, and no click rewriting/tracking.
11. Validate queue worker/cron reliability on selected cPanel hosting and alert ownership.
12. Run controlled production smoke messages only to approved internal/test recipients before customer activation.

Failure of a gate keeps production transactional email disabled and blocks launch flows that require verified email/account recovery. A manual personal-email workaround is not acceptable.

#### Provider review and migration

Review after pilot volume using delivery rate, delays, bounces, complaints, queue incidents, support quality, capacity/cost, domain reputation, and customer contact failures.

Migration to Postmark or another provider requires:

- New account/domain authentication and deliverability review
- Adapter/transport and webhook event mapping
- Credential/security/privacy/contract review
- Suppression export/import or safe equivalent
- Message-ID/provider-history preservation
- Staged traffic transition and duplicate prevention
- Rollback plan and full acceptance suite

Template source remains in application code, reducing content drift during provider migration.

## 2. Goals

- Deliver a fast, trustworthy, mobile-first shopping experience.
- Allow non-technical staff to control catalog, inventory, orders, promotions, pages, and settings.
- Maintain accurate order, payment, and stock records.
- Make product taxonomy and variant attributes configurable for new categories.
- Provide secure access, traceable admin actions, reliable notifications, and actionable reporting.
- Establish a platform that can later support multiple regions, currencies, languages, and fulfillment locations.

## 3. Non-goals for MVP

- Building an in-house card processor, courier network, ERP, or accounting system
- Marketplace with third-party sellers
- Native iOS/Android apps
- Advanced AI recommendations, subscriptions, loyalty tiers, or multi-warehouse routing
- Full visual page builder; MVP may use configurable predefined content blocks

## 4. Users and Roles

### Customer roles

- **Visitor:** Browse, search, manage a local cart, and check out as guest where enabled.
- **Customer:** Manage profile, addresses, wishlist, orders, reviews, and return requests.

### Staff roles

- **Owner:** Full business and access control.
- **Administrator:** Broad operational access excluding ownership-sensitive controls.
- **Catalog manager:** Products, categories, collections, media, pricing, and inventory.
- **Order manager:** Orders, fulfillment, cancellations, returns, and approved refunds.
- **Content/marketing manager:** Pages, navigation, campaigns, discounts, and SEO.
- **Support agent:** Customer/order lookup, notes, and permitted service actions.
- **Analyst:** Read-only reports and exports.

Permissions must be granular and enforced server-side, not only hidden in the UI.

## 5. Core Customer Journeys

1. Discover products through navigation, collections, search, filters, and campaigns.
2. Evaluate a product using photos, video, price, variants, specifications, availability, delivery information, policies, and reviews.
3. Add an available variant to cart and apply an eligible promotion.
4. Check out with contact, shipping address, shipping method, billing information, and external payment.
5. Receive confirmation and follow order/fulfillment status.
6. Request cancellation, return, exchange, or support according to policy.

## 6. Storefront Requirements

### Global experience

- Responsive header, navigation, search, country/currency display where applicable, account, wishlist, and cart
- Footer with support, policies, social links, newsletter, contact, and business details
- Accessible keyboard navigation, visible focus, semantic structure, sufficient contrast, and descriptive media text
- Loading, empty, offline, not-found, out-of-stock, validation, and server-error states

### Home and merchandising

- Admin-configurable hero, announcement bar, category tiles, featured collections/products, editorial blocks, reviews, and newsletter section
- Scheduled publishing for campaign content is desirable after MVP

### Catalog and search

- Categories and nested categories
- Manual and rule-based collections
- Search by product name, SKU/keywords, category, and configured searchable attributes
- Sorting by relevance, newest, price, popularity, and featured order
- Filtering by availability, price, category, and category-specific attributes such as material, color, size, frame shape, or lens type
- Pagination or cursor-based infinite loading with crawlable URLs

### Product detail

- Product name, subtitle, media gallery, pricing, discount display, tax note, variant selectors, stock state, quantity, and add-to-cart
- Configurable attributes and specifications rather than hard-coded jewellery or beauty fields
- Delivery estimate, shipping/return summary, product care, size/fit guide, authenticity/warranty where applicable
- Related products, recently viewed items, and moderated reviews
- Structured data and canonical metadata

### Cart and checkout

- Cart item variant details, quantity updates, removal, subtotal, discounts, estimated shipping/tax, and final total
- Server-side revalidation of prices, discounts, inventory, shipping, and tax before payment
- Guest and authenticated checkout
- Address validation where available; separate billing address option
- Payment handled by a compliant gateway through hosted fields or redirect
- Idempotent order/payment creation and clear recovery from declined, cancelled, or interrupted payment
- Confirmation page and notification only after verified payment outcome or approved pay-later method

### Customer account

- Registration, login, logout, email verification, password reset, and supported social login if selected
- Profile and address book
- Order history, order detail, invoice/receipt, shipment tracking, and eligible cancellation
- Return/exchange request with reason, items, quantity, evidence, and status
- Wishlist and review submission for eligible purchasers
- Privacy actions: data export/request and account deletion request, subject to legal retention

## 7. Admin Dashboard Requirements

### Dashboard

- Revenue, orders, average order value, units sold, refunds, conversion inputs where available, low stock, and pending operational tasks
- Date filters and comparisons; metrics must state timezone, currency, and inclusion rules

### Catalog

- Draft/active/archived products
- Product type, vendor/brand, description, SEO, tags, categories, collections, media, specifications, options, variants, SKU, barcode, cost, price, compare-at price, weight, and dimensions
- Configurable attribute definitions with data types, allowed values, filter/search flags, and product-type assignment
- Bulk edit, CSV import/export, duplicate, preview, validation, and media ordering

### Inventory

- Stock on hand, reserved, available, adjustment reason, adjustment history, and low-stock threshold
- Prevent overselling by default; owner-configurable backorder/preorder behavior later
- Inventory reservation during checkout with expiration or atomic decrement at confirmed order, based on selected payment flow

### Orders and fulfillment

- Search/filter orders; timeline of status, payment, fulfillment, notifications, and staff actions
- Verify payment, capture where supported, fulfill partially/fully, add tracking, print packing data, cancel, refund within permission, and resend notification
- Immutable financial records; corrections use explicit refund/adjustment records

### Returns and exchanges

- Review request, approve/reject, provide instructions/label, receive/inspect items, restock or write off, refund, and close
- Return policy eligibility calculated from delivery date and product rules

### Customers and support

- Customer profile, addresses, orders, lifetime value, notes, tags/segments, consent, and support history
- Protect sensitive personal data and log privileged access/actions

### Marketing and content

- Percentage/fixed/free-shipping promotions with eligibility, schedule, limits, minimums, usage tracking, and combinability rules
- Collections, featured sorting, menus, announcement bars, banners, modular home sections, pages, FAQs, and SEO metadata
- Review moderation and response

### Settings and governance

- Business identity, locations, currencies, timezone, tax, shipping zones/rates, payment provider, order numbering, policies, notification templates, and integrations
- Staff invitation, role assignment, deactivation, session/security controls, and audit logs
- Secrets must be configured outside browser-accessible settings and stored in deployment secret management

## 8. Order Lifecycle

Default state model:

- **Order:** `pending_payment → confirmed → processing → fulfilled → completed`
- Alternate terminal/interruption states: `cancelled`, `partially_refunded`, `refunded`
- **Payment:** `pending → authorized → captured` or `failed/cancelled/refunded/partially_refunded`
- **Fulfillment:** `unfulfilled → partial → fulfilled → delivered` with `returned` as applicable
- **Return:** `requested → approved/rejected → in_transit → received → inspected → refunded/exchanged → closed`

Transitions must be validated server-side. Payment webhooks are authoritative and idempotent.

## 9. Reporting and Analytics

- Sales by date, product, category, channel, and promotion
- Orders by status; payment failures; refunds and returns
- Inventory valuation inputs, sell-through, low stock, and out-of-stock products
- Customer acquisition inputs, repeat purchase, and cohort data when identity/consent permits
- Funnel events: product view, search, add to cart, begin checkout, payment result, purchase, return request
- CSV export must respect staff permissions and protect personal data

## 10. Non-functional Requirements

- **Performance:** Optimize Core Web Vitals; cache public catalog safely; use responsive images and pagination.
- **Availability:** Graceful failures for search, payment, email, and carrier dependencies; retry safe asynchronous work.
- **Security:** No direct browser database access, server-enforced least privilege and ownership checks, admin MFA, secure webhook verification, rate limits, validation, audit logs, and no raw card storage.
- **Privacy:** Consent and retention controls appropriate to launch markets; minimize personal data.
- **Accessibility:** Target WCAG 2.2 AA for key shopping and admin workflows.
- **SEO:** Server-renderable metadata, canonical URLs, sitemap, robots controls, redirects, and Product/Breadcrumb structured data.
- **Maintainability:** Versioned database migrations, typed APIs, automated tests, observability, and separate development/staging/production environments.

## 11. Success Metrics

- Purchase conversion rate and checkout completion rate
- Search success/no-result rate and product-to-cart rate
- Payment success rate and webhook reconciliation exceptions
- On-time fulfillment and median fulfillment time
- Cancellation, return, refund, and support-contact rates
- Inventory mismatch and oversell incidents
- Core Web Vitals pass rate and application error rate
- Percentage of daily operations completed without developer involvement

## 12. MVP Acceptance Summary

MVP is ready when staff can publish a configurable product with variants, receive a verified paid order, fulfill it with tracking, notify the customer, handle cancellation/refund within policy, update content and promotions, reconcile stock, review audit history, and complete these tasks under correctly enforced permissions.

## 13. Open Product Decisions

- Pakistani legal entity, NTN/STRN applicability, adviser-approved tax classes/rates, invoice timing, FBR integration, withholding, retention, and filing ownership
- Safepay merchant onboarding/contract, named wallet availability, supported Pakistan-issued card schemes, authorization/capture model, fees/withholding, settlement timing, API refund constraints, and acceptance evidence
- Karachi courier selection, verified coverage, actual charge, COD remittance terms, pickup schedule, and operational contacts
- Final brand domain ownership, monitored support mailbox, Resend account/plan, DNS authentication, privacy/data-processing review, and notification acceptance evidence
- Privacy/consent/legal-page approval and support/incident ownership
- Review launch details; gift cards, loyalty, social login, and gifting remain deferred unless separately approved
