# Softglam Application Flows

**Status:** Draft 1

## 1. Application Map

### Storefront

- `/` Home
- `/search` Search results
- `/categories/[slug]` Category
- `/collections/[slug]` Collection/campaign
- `/products/[slug]` Product detail
- `/cart` Cart
- `/checkout` Checkout
- `/checkout/payment` Payment handoff/embedded provider step
- `/order/confirmation/[reference]` Confirmation with protected access
- `/account/*` Profile, addresses, orders, wishlist, reviews, returns
- `/pages/[slug]` About, contact, FAQs, shipping, returns, privacy, terms

### Admin

- `/admin` Business overview
- `/admin/products`, `/categories`, `/collections`, `/inventory`
- `/admin/orders`, `/returns`, `/customers`
- `/admin/discounts`, `/reviews`, `/content`
- `/admin/reports`
- `/admin/settings/*` Business, shipping, tax, payments, notifications, users/roles, integrations, audit

Exact routes may change with framework conventions; the capabilities and access boundaries remain required.

## 2. Customer Discovery Flow

```mermaid
flowchart TD
  A[Landing page] --> B{Discovery method}
  B -->|Navigation| C[Category or collection]
  B -->|Search| D[Search results]
  B -->|Campaign| E[Campaign collection]
  C --> F[Filter and sort]
  D --> F
  E --> F
  F --> G[Product detail]
  G --> H{Variant available?}
  H -->|No| I[Show unavailable and alternatives]
  H -->|Yes| J[Select options and quantity]
  J --> K[Add to cart]
  K --> L{Continue shopping?}
  L -->|Yes| B
  L -->|No| M[Cart]
```

Key behavior:

- Filters are generated from configured product attributes.
- Product URLs remain stable and indexable.
- Add-to-cart rechecks purchasability and gives a clear recovery message if availability changed.

## 3. Checkout and Payment Flow

```mermaid
flowchart TD
  A[Cart] --> B[Proceed to checkout]
  B --> C{Signed in?}
  C -->|No| D[Guest or sign in]
  C -->|Yes| E[Contact and address]
  D --> E
  E --> F[Select shipping]
  F --> G[Trusted server recalculates totals]
  G --> H{Valid stock, price, promotion?}
  H -->|No| I[Explain changes and return to review]
  H -->|Yes| J[Create checkout and payment session]
  J --> K[Gateway payment UI]
  K --> L{Gateway result}
  L -->|Declined/cancelled| M[Retry or select another method]
  L -->|Pending| N[Pending confirmation screen]
  L -->|Client success| O[Wait for verified server status]
  O --> P[Signed webhook processed idempotently]
  N --> P
  P --> Q{Payment confirmed?}
  Q -->|No| M
  Q -->|Yes| R[Confirm order and inventory]
  R --> S[Send confirmation]
  S --> T[Order confirmation page]
```

Rules:

- A browser redirect never directly marks an order paid.
- Duplicate requests/webhooks produce one payment/order effect.
- If cart data changes, customer must review the new final total before payment.

## 4. Authentication Flow

```mermaid
flowchart TD
  A[Login or registration] --> B{Action}
  B -->|Register| C[Create Supabase Auth user]
  C --> D[Verify email if required]
  D --> E[Create/load profile]
  B -->|Login| F[Authenticate]
  F --> G{MFA required?}
  G -->|Yes| H[Complete MFA]
  G -->|No| E
  H --> E
  B -->|Forgot password| I[Send recovery link]
  I --> J[Set new password]
  J --> F
  E --> K[Merge eligible anonymous cart]
  K --> L[Continue intended action]
```

Staff login always routes through protected admin authorization. A valid customer session alone grants no admin access.

## 5. Order Fulfillment Flow

```mermaid
flowchart TD
  A[Confirmed order] --> B[Admin order queue]
  B --> C[Review payment, address, fraud flags, stock]
  C --> D{Can fulfill?}
  D -->|No| E[Hold/contact/cancel according to policy]
  D -->|Yes| F[Pick and pack]
  F --> G[Create full or partial fulfillment]
  G --> H[Add carrier and tracking]
  H --> I[Mark fulfilled]
  I --> J[Notify customer]
  J --> K[Tracking updates]
  K --> L[Delivered]
  L --> M[Completed after policy period]
```

Every status change is timestamped and attributed. Partial fulfillment tracks quantities per order item.

## 6. Customer Cancellation Flow

```mermaid
flowchart TD
  A[Customer opens order] --> B{Cancellation eligible?}
  B -->|No| C[Show reason and support/return path]
  B -->|Yes| D[Customer confirms cancellation]
  D --> E[Trusted command locks order]
  E --> F{Still unfulfilled and cancellable?}
  F -->|No| C
  F -->|Yes| G[Cancel order/items]
  G --> H[Release/restock inventory]
  H --> I[Void authorization or initiate refund]
  I --> J[Record timeline and notify customer]
```

## 7. Return and Refund Flow

```mermaid
flowchart TD
  A[Delivered order] --> B[Select return items and reason]
  B --> C[Upload evidence if required]
  C --> D{Policy eligible?}
  D -->|No| E[Explain and offer support]
  D -->|Yes| F[Create return request]
  F --> G[Admin reviews]
  G --> H{Decision}
  H -->|Reject| I[Reason and notification]
  H -->|Approve| J[Instructions or return label]
  J --> K[Item received]
  K --> L[Inspection]
  L --> M{Disposition}
  M -->|Restock| N[Inventory movement: return]
  M -->|Write off| O[Inventory movement: damaged]
  N --> P[Refund or exchange]
  O --> P
  P --> Q[Gateway result recorded]
  Q --> R[Close and notify]
```

Refund amount is computed from refundable paid amounts, prior refunds, discounts, shipping/tax policy, and returned quantity—not current catalog price.

## 8. Admin Product Publishing Flow

```mermaid
flowchart TD
  A[Create draft] --> B[Choose product type]
  B --> C[Enter content and configurable attributes]
  C --> D[Create options and variants]
  D --> E[Set SKU, price, inventory, physical data]
  E --> F[Upload and order media]
  F --> G[Assign categories and collections]
  G --> H[Set SEO and preview]
  H --> I{Validation passes?}
  I -->|No| J[Show actionable errors]
  J --> C
  I -->|Yes| K[Publish now or schedule]
  K --> L[Invalidate cache and update search/sitemap]
```

Publishing requires at least one sellable variant, valid pricing, required attributes, and required media/content according to business configuration.

## 9. Inventory Adjustment Flow

```mermaid
flowchart TD
  A[Open inventory] --> B[Select variant/location]
  B --> C[Enter delta or counted quantity]
  C --> D[Select reason and optional note]
  D --> E[Permission and validation]
  E --> F[Atomic inventory movement]
  F --> G[Recalculate availability]
  G --> H{Below threshold?}
  H -->|Yes| I[Low-stock alert]
  H -->|No| J[Complete]
  I --> J
```

Balances are never silently overwritten; each adjustment creates a traceable movement.

## 10. Discount Creation and Evaluation Flow

Admin defines discount type, eligibility, products/collections, customer scope, dates, minimums, total/per-customer usage, and combination rules. At cart and checkout:

1. Normalize the supplied code.
2. Find active rule in the checkout currency/timezone.
3. Validate customer, item, minimum, schedule, and usage eligibility.
4. Apply using deterministic priority/combination rules.
5. Revalidate at final checkout and record redemption atomically with the order.

## 11. Support and Exception Flows

- **Payment succeeded but webhook delayed:** show pending state; reconciliation retrieves provider status; never ask customer to pay again without checking.
- **Webhook repeated/out of order:** store provider event ID and process safely; state machine rejects invalid regressions.
- **Stock changed during checkout:** recalculate atomically, release invalid reservation, and return customer to review.
- **Email failed:** order remains valid; notification is queued for retry and visible to staff.
- **Carrier unavailable:** permit authorized manual rate/tracking workflow if configured.
- **Product unpublished after purchase:** historical order remains readable from immutable snapshots.
- **Admin lacks permission:** deny on server, log relevant attempt, and show a safe access message.
