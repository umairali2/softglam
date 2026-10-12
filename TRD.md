# Softglam Technical Requirements Document

**Status:** Draft 1  
**Backend platform:** Supabase

## 1. Technical Objectives

- Provide a secure, maintainable, category-agnostic commerce architecture.
- Keep public storefront reads fast while preserving authoritative server-side pricing, inventory, and order logic.
- Use Supabase capabilities without exposing privileged operations or service credentials to clients.
- Make payments, webhooks, stock updates, and refunds idempotent and auditable.

## 2. Recommended Architecture

### Web applications

- **Storefront:** A server-rendering-capable TypeScript framework such as Next.js, deployed to a compatible managed platform.
- **Admin:** A protected route group in the same application for MVP, or a separate application later if release/security boundaries require it.
- **UI:** Shared design tokens and accessible components; responsive images and forms with server validation.

The final framework and hosting choice must be confirmed before implementation. The architecture must not depend on framework-only client checks for authorization.

### Supabase services

- **Postgres:** Source of truth for catalog, customers, inventory, carts, orders, and operations
- **Auth:** Customer and staff identity; staff role assignments stored separately from user-controlled metadata
- **Storage:** Product media, review media, and restricted return evidence with distinct buckets/policies
- **Row Level Security:** Required on all schemas/tables accessible through Supabase APIs
- **Edge Functions or trusted server runtime:** Checkout, payment sessions, webhooks, refunds, privileged admin commands, email triggers, and exports
- **Realtime:** Optional for admin order/inventory updates; not required for core correctness
- **Scheduled jobs:** Expire reservations, retry notifications, reconcile payments, refresh aggregates, and clean temporary records

### External services

- PCI-compliant payment gateway
- Transactional email provider; optional SMS/WhatsApp provider
- Carrier/rate/tracking provider or manual fulfillment adapter
- Analytics and error monitoring with privacy controls

Adapters should isolate providers so they can be replaced without rewriting order logic.

## 3. Domain Model

All primary keys should use UUIDs unless a clear operational reason requires another type. Store timestamps in UTC and display using configured business/customer timezone.

### Identity and access

- `profiles`: user identity extension and customer preferences
- `staff_members`: links auth users to staff status
- `roles`, `permissions`, `role_permissions`, `staff_roles`: granular RBAC
- `audit_logs`: actor, action, entity, before/after summary, request metadata, timestamp

### Catalog

- `product_types`: category-specific schema grouping
- `attribute_definitions`: code, label, data type, validation, allowed values, filter/search flags
- `product_type_attributes`: product type assignment and ordering
- `products`: status, title, slug, description, brand, SEO, publication data
- `product_attribute_values`: configurable non-variant specifications
- `product_options`, `product_option_values`: variant axes such as size/color/material
- `product_variants`: SKU, barcode, prices, physical data, status
- `variant_option_values`: variant combinations
- `categories`, `category_products`: hierarchical navigation assignment
- `collections`, `collection_products`: manual/rule-derived merchandising
- `product_media`: storage path, type, alt text, ordering
- `related_products`: manual relationships

Prices should use integer minor units plus ISO currency code, never floating-point values.

### Inventory

- `locations`: fulfillment/stock locations
- `inventory_items`: variant-location balances and thresholds
- `inventory_reservations`: checkout/order reservation with expiry
- `inventory_movements`: append-only adjustments, reservations, releases, sales, returns, and reason/reference

Available stock is derived consistently from on-hand and active reservations. Inventory-changing commands must lock relevant rows or use atomic database functions.

### Cart, pricing, and checkout

- `carts`: user or anonymous token, currency, status, expiry
- `cart_items`: variant, quantity, captured display context
- `discounts`, `discount_codes`, `discount_rules`, `discount_redemptions`
- `checkout_sessions`: pricing snapshot, customer/contact, addresses, status, expiry
- `shipping_zones`, `shipping_methods`, `shipping_rates`
- `tax_rules` or an integration reference

Cart totals shown to clients are estimates. A trusted checkout command recalculates active price, eligibility, shipping, tax, and inventory.

### Orders and payments

- `orders`: human-readable number, customer/contact, totals, currency, statuses, snapshots, timestamps
- `order_items`: immutable product/variant name, SKU, attributes, unit price, tax, discount, quantity
- `order_addresses`: immutable billing/shipping snapshots
- `order_adjustments`: discounts, shipping, tax, manual corrections with reasons
- `payments`: provider reference, amount, currency, status, idempotency key
- `payment_events`: verified provider webhook event IDs and payload metadata
- `refunds`, `refund_items`: provider reference, reason, amounts, status
- `fulfillments`, `fulfillment_items`, `tracking_events`

Do not rely on mutable catalog rows to display historical orders.

### Post-purchase and content

- `return_requests`, `return_items`, `return_events`
- `reviews`, `review_media`, `review_votes`
- `wishlists`, `wishlist_items`
- `pages`, `page_sections`, `navigation_menus`, `navigation_items`, `faqs`
- `notification_templates`, `notification_deliveries`
- `customer_notes`, `customer_tags`, `consents`

## 4. API and Command Boundaries

### Safe direct reads

Public, published catalog/content data may be read through views or RPCs protected by RLS. Public views should expose only required fields and must not expose costs, internal notes, supplier data, or unpublished items.

### Trusted commands

The following must run in a trusted server or Edge Function:

- Final price/tax/shipping calculation
- Checkout creation and stock reservation
- Payment session creation and capture/refund
- Payment/carrier webhook processing
- Order state transitions and cancellation
- Inventory adjustments and bulk operations
- Staff invitation/role changes
- Customer data exports and sensitive reports

Commands must authenticate the actor, authorize permissions, validate input, execute transactionally where possible, and record audit data.

### API conventions

- Typed request/response contracts and schema validation
- Cursor pagination for large lists
- Idempotency keys for checkout, payment, refund, fulfillment, and webhook operations
- Stable machine-readable error codes plus safe user messages
- Request/correlation IDs propagated to logs and provider metadata

## 5. Authentication and Authorization

- Supabase Auth for sessions; secure cookie-based server sessions preferred for SSR applications.
- Email verification and password recovery; CAPTCHA/rate limits on abuse-prone endpoints.
- MFA required for owners and administrators and recommended for all staff.
- Never derive staff permission from editable client metadata or email domain alone.
- RLS policies default to deny and explicitly allow ownership/publication/permitted staff access.
- Service-role key only in trusted runtimes; never use `SUPABASE_SERVICE_ROLE_KEY` in browser bundles.
- Step-up verification should be considered for refunds, role changes, exports, and sensitive settings.

## 6. Storage Design

- `catalog-public`: optimized public product media; authenticated staff write only
- `review-public` or moderated private-to-public flow
- `return-private`: customer-owner and authorized staff access only
- Validate MIME type, size, extension, and image dimensions; randomize object paths.
- Generate image variants and strip unnecessary metadata where supported.
- Store object path/reference in Postgres, not expiring signed URLs.

## 7. Payment Design

1. Trusted server recalculates checkout and reserves/decrements stock according to policy.
2. Server creates provider session/payment intent with idempotency key and internal checkout reference.
3. Client completes payment using provider-hosted UI.
4. Verified signed webhook is recorded once by provider event ID.
5. A transaction updates payment/order state and inventory; asynchronous notifications follow.
6. Reconciliation job finds stale/pending or mismatched records.

The success redirect is not proof of payment. Raw card data, CVV, or unrestricted provider secrets must never reach Supabase tables or logs.

## 8. State and Concurrency Requirements

- Enforce allowed order/payment/fulfillment/return transitions in trusted code or database functions.
- Use row locking, constraints, and atomic operations to prevent overselling and duplicate refunds.
- Unique constraints should cover product slug, SKU where applicable, discount code, order number, webhook event ID, and idempotency keys.
- Financial and inventory ledgers should be append-only; derived balances may be cached but reconcilable.

## 9. Search, SEO, and Performance

- Begin with indexed Postgres full-text/trigram search if catalog size and relevance requirements permit; adopt a dedicated search service only when justified.
- Index publication status, slugs, category joins, SKU, order number, normalized email, statuses, timestamps, and common admin filters.
- Avoid N+1 queries; use purpose-built views/RPCs for listing cards and admin reports.
- Cache only public catalog/content; purge or revalidate on publish, price, or stock events.
- Generate sitemap(s), canonical metadata, robots policy, Product/Offer/Breadcrumb JSON-LD, and redirects.
- Use CDN-delivered responsive media and lazy loading below the fold.

## 10. Observability and Operations

- Structured logs with correlation ID; redact tokens, payment data, passwords, and unnecessary PII.
- Monitor frontend/server errors, function failure rate, webhook failures, payment discrepancies, notification failures, database utilization, and low stock.
- Alerts must route to an owned operational channel with documented severity and response.
- Maintain dashboards for checkout/payment success, webhook lag, failed jobs, and order exceptions.
- Use separate development, staging, and production Supabase projects.

## 11. Migrations, Backups, and Data Lifecycle

- All schema, RLS, functions, and seed/reference data changes use versioned migrations committed to source control.
- Do not make untracked production schema edits.
- Enable platform backups appropriate to business recovery targets and periodically test restoration.
- Define retention/anonymization rules for carts, logs, exports, return evidence, and personal data.
- Preserve order/financial records where legally required while honoring eligible privacy requests.

## 12. Testing Strategy

- **Unit:** pricing, discount eligibility, tax/shipping rules, state transitions, permissions
- **Database:** constraints, RLS policies by role, atomic inventory behavior, migration tests
- **Integration:** payment and webhook idempotency, refund, email, storage, carrier adapter
- **End-to-end:** browse-to-purchase, declined payment, duplicate webhook, cancellation, fulfillment, return/refund, and staff permissions
- **Non-functional:** accessibility, performance budgets, security scanning, rate limits, concurrency/oversell, backup restore

Use provider sandbox environments. Production-like tests must use synthetic customers and non-sensitive data.

## 13. Deployment and CI/CD

- Pull request checks: formatting, lint, type checking, unit/integration tests, migration validation, and build
- Preview environment for web changes; staging validation before production
- Backward-compatible schema changes for zero/low-downtime deployment
- Deployment records, controlled environment variables, and rollback/runbook procedures
- Secrets in hosting/Supabase secret management, never committed to source control

## 14. Initial Service Targets

These are launch targets to refine with business needs:

- p75 storefront Core Web Vitals meet current “good” thresholds
- p95 cached catalog read under 500 ms at the application boundary
- p95 trusted non-provider command under 1.5 seconds
- 99.9% monthly target availability excluding declared third-party outages
- No unresolved critical/high security findings at launch
- Recovery point and recovery time objectives documented and tested before production

## 15. Technical Decisions Still Required

- Frontend framework, hosting, package manager, and test suite
- Payment, tax, email, analytics, monitoring, and carrier providers
- Stock reservation duration and payment authorization/capture policy
- Search implementation and expected catalog/order volume
- Launch-region compliance and data residency needs
- Recovery objectives and operational alert ownership
