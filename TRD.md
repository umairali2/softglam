# SoftGlam Technical Requirements Document

**Status:** Draft 2

**Frontend:** Next.js 16 on Vercel

**Backend:** Laravel REST API on cPanel

**Database:** MySQL 8 or reviewed MariaDB equivalent

**Architecture:** Modular monolith

## 1. Technical Objectives

- Provide a secure, maintainable, category-agnostic commerce architecture.
- Keep public storefront reads fast while preserving authoritative server-side pricing, inventory, and order logic.
- Use Laravel and MySQL capabilities without exposing privileged operations, database access, or service credentials to clients.
- Keep the backend a modular monolith with explicit domain boundaries rather than premature microservices.
- Make payments, webhooks, stock updates, and refunds idempotent and auditable.

## 2. Recommended Architecture

### Web application

- **Storefront:** Next.js 16 with TypeScript, deployed to Vercel.
- **Admin:** A protected route group in the same Next.js application for MVP; separation remains possible if future release or security boundaries justify it.
- **UI:** Shared design tokens and accessible components; responsive images and forms with server validation.
- **Trust boundary:** Browser and Next.js UI checks improve experience but never replace Laravel authorization.

### Laravel application

- **API:** Versioned REST endpoints with typed contracts, request validation, stable error codes, and controlled resource serialization.
- **Domains:** Identity, access control, catalog, inventory, cart, pricing, discounts, checkout, orders, payments, fulfillment, returns, customers, reviews, content, notifications, reporting, and audit.
- **Auth:** Laravel Sanctum with secure `HttpOnly` cookie sessions when the frontend and API share a parent domain.
- **Authorization:** Middleware, policies, gates, and domain-level permission checks; database access is never exposed directly to browsers.
- **Storage:** Laravel filesystem with public product media and private protected evidence; S3-compatible storage can replace the initial disk through the same abstraction.
- **Queues:** Laravel queued jobs for email, image processing, reports, exports, and provider reconciliation.
- **Scheduler:** Laravel Scheduler invoked by cPanel cron for reservation expiry, retries, reconciliation, cleanup, and lifecycle work.

### Database and hosting

- **MySQL:** Source of truth for catalog, customers, inventory, carts, orders, payments, and operations.
- **Transactions and locks:** Required for inventory, checkout, payment, refund, and other concurrency-sensitive commands.
- **Deployment:** Laravel API and database on a validated cPanel package; Next.js remains on Vercel.
- **Environments:** Separate development, staging, and production applications, databases, credentials, and provider environments.
- **Realtime:** Not required for core correctness; admin views may poll until a justified realtime provider is selected.

### External services

- Safepay hosted checkout as the primary PKR online-payment integration target; PayFast is the commercial fallback if onboarding/acceptance fails
- Resend for queued transactional email with application-owned templates and signed delivery webhooks; Postmark is the replacement candidate; optional SMS/WhatsApp requires later approval
- Carrier/rate/tracking provider or manual fulfillment adapter
- Analytics and error monitoring with privacy controls
- FBR electronic-invoicing licensed integrator or PRAL where the approved registered profile requires it

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
- `tax_profiles`, `tax_classes`, `tax_rates`, and effective-dated product/shipping assignments
- `tax_calculations`, `financial_documents`, `financial_document_lines`, `document_transmissions`, and linked credit/debit notes
- `settlement_deductions` for payment-intermediary/courier fees and withholding reconciliation

Cart totals shown to clients are estimates. A trusted checkout command recalculates active price, eligibility, shipping, tax, and inventory. Production checkout requires an effective owner-authorized compliance profile; tax and issued financial documents preserve immutable profile, classification, calculation, rounding, numbering, and external-transmission snapshots.

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

### Public reads

Public, published catalog/content data is served through Laravel API resources or trusted Next.js server requests. Queries and serializers must select only required public fields and must not expose costs, internal notes, supplier data, drafts, or unpublished items.

### Trusted commands

The following must run in the Laravel application:

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

- Laravel Sanctum provides sessions; secure `HttpOnly`, `Secure`, appropriately scoped cookie-based sessions are required when the frontend and API share a parent domain.
- Email verification and password recovery use enumeration-safe messaging; CAPTCHA/rate limits protect abuse-prone endpoints.
- MFA is required for owners and administrators and recommended for all staff.
- Never derive staff permission from editable profile data or email domain alone.
- Laravel middleware, policies, gates, query scoping, and domain checks default to deny and explicitly allow ownership, publication, or permitted staff access.
- Database credentials, application keys, and provider secrets exist only in trusted server environments and never in browser bundles.
- Step-up verification should be considered for refunds, role changes, exports, and sensitive settings.

## 6. Storage Design

- `catalog-public`: optimized public product media; authenticated staff write only
- `review-public` or moderated private-to-public flow
- `return-private`: customer-owner and authorized staff access only
- Validate MIME type, size, extension, and image dimensions; randomize object paths.
- Generate image variants and strip unnecessary metadata where supported.
- Store the object path/reference in MySQL, not expiring signed URLs.

## 7. Payment Design

1. Trusted server recalculates checkout and reserves/decrements stock according to policy.
2. Server creates provider session/payment intent with idempotency key and internal checkout reference.
3. Client completes payment using provider-hosted UI.
4. Verified signed webhook is recorded once by provider event ID.
5. A transaction updates payment/order state and inventory; asynchronous notifications follow.
6. Reconciliation job finds stale/pending or mismatched records.

The success redirect is not proof of payment. Raw card data, CVV, or unrestricted provider secrets must never reach SoftGlam databases or logs.

## 8. State and Concurrency Requirements

- Enforce allowed order/payment/fulfillment/return transitions in trusted code or database functions.
- Use row locking, constraints, and atomic operations to prevent overselling and duplicate refunds.
- Unique constraints should cover product slug, SKU where applicable, discount code, order number, webhook event ID, and idempotency keys.
- Financial and inventory ledgers should be append-only; derived balances may be cached but reconcilable.

## 9. Search, SEO, and Performance

- Begin with indexed MySQL full-text search and normalized searchable fields if catalog size and relevance requirements permit; adopt a dedicated search service only when justified.
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
- Use separate development, staging, and production applications, databases, credentials, storage, and provider environments.

## 11. Migrations, Backups, and Data Lifecycle

- All schema, constraints, indexes, database routines, and seed/reference data changes use versioned Laravel migrations or seeders committed to source control.
- Do not make untracked production schema edits.
- Enable platform backups appropriate to business recovery targets and periodically test restoration.
- Define retention/anonymization rules for carts, logs, exports, return evidence, and personal data.
- Preserve order/financial records where legally required while honoring eligible privacy requests.

## 12. Testing Strategy

- **Unit:** pricing, discount eligibility, tax/shipping rules, state transitions, permissions
- **Database:** constraints, indexes, ownership/permission query boundaries, atomic inventory behavior, and migration tests
- **Integration:** payment and webhook idempotency, refund, email, storage, carrier adapter
- **End-to-end:** browse-to-purchase, declined payment, duplicate webhook, cancellation, fulfillment, return/refund, and staff permissions
- **Non-functional:** accessibility, performance budgets, security scanning, rate limits, concurrency/oversell, backup restore

Use provider sandbox environments. Production-like tests must use synthetic customers and non-sensitive data.

## 13. Deployment and CI/CD

- Pull request checks: formatting, lint, type checking, unit/integration tests, migration validation, and build
- Preview environment for web changes; staging validation before production
- Backward-compatible schema changes for zero/low-downtime deployment
- Deployment records, controlled environment variables, and rollback/runbook procedures
- Secrets in Vercel and cPanel protected environment configuration, never committed to source control

## 14. Initial Service Targets

These are launch targets to refine with business needs:

- p75 storefront Core Web Vitals meet current “good” thresholds
- p95 cached catalog read under 500 ms at the application boundary
- p95 trusted non-provider command under 1.5 seconds
- 99.9% monthly target availability excluding declared third-party outages
- No unresolved critical/high security findings at launch
- Recovery point and recovery time objectives documented and tested before production

## 15. Technical Decisions Still Required

- Final cPanel package and its PHP, MySQL/MariaDB, cron, queue, backup, logging, and resource capabilities
- Package manager and frontend/backend test libraries
- Safepay merchant contract/onboarding, named wallet/card capability, authorization/capture model, fees/withholding, settlement, API refunds, and acceptance evidence; PayFast remains the fallback candidate
- Tax/FBR, analytics, monitoring, and future carrier providers
- Final domain ownership plus Resend account/plan, SPF/DKIM/DMARC, support mailbox, webhook, data-processing, queue-capacity, and acceptance evidence
- Provider compatibility with the approved 30-minute online-payment reservation and late-success recovery policy
- Search implementation and expected catalog/order volume
- Pakistan-specific compliance, tax/invoice, consent, retention, and data residency requirements
- Recovery objectives and operational alert ownership
