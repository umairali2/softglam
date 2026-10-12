# Softglam Functional Specification Document

**Status:** Draft 1  
**Scope:** MVP plus clearly marked post-MVP functions

## 1. Specification Conventions

- **Priority:** P0 = launch-critical, P1 = operational maturity, P2 = growth
- **Actors:** Visitor, Customer, Staff, Owner, System, External Provider
- Every admin function requires authenticated server-side authorization and appropriate audit logging.
- Every monetary calculation uses integer minor units and a defined currency.

## 2. Storefront Functional Requirements

### SF-01 Global navigation and layout — P0

**Actor:** Visitor/Customer

The system shall display responsive navigation, configurable menus, search entry, account entry, wishlist where enabled, cart count, announcement content, and footer links.

**Acceptance criteria**

- Published categories/collections/pages can be added, ordered, nested, and removed by authorized staff.
- Navigation supports keyboard and screen-reader operation.
- Cart count reflects the active anonymous or customer cart.
- Unpublished destinations are not exposed to public users.

### SF-02 Home page — P0

The system shall render admin-configured predefined sections including hero, category grid, featured collection/products, editorial media, reviews, and newsletter capture.

- Staff can enable, disable, reorder, and edit supported sections.
- Only published products/content appear.
- Each media item supports alternative text and responsive rendering.

### SF-03 Category and collection listing — P0

The system shall provide indexable listing pages with breadcrumbs, description, product cards, filters, sorting, and pagination.

- Filters are derived from product type/attribute configuration and active catalog data.
- URL state can represent filters and sort.
- Product cards show current price, promotion, image, and availability summary.
- Empty results provide filter reset and discovery paths.

### SF-04 Search — P0

The system shall search published products by configured fields and return relevant, filterable results.

- Search handles normalized casing and common punctuation.
- Unpublished/internal fields are excluded.
- Search query and no-result analytics are recorded without unnecessary personal data.
- Suggestions and typo tolerance are P1 unless readily supported by the chosen implementation.

### SF-05 Product detail — P0

The system shall display current product information and allow selection of a valid purchasable variant.

- Selecting option values resolves one variant or indicates an unavailable combination.
- Displayed price, media, SKU, stock message, and physical details update as configured.
- Add-to-cart is unavailable until required options are selected.
- Product includes policies, delivery summary, specifications, care/fit guidance, related items, SEO, and structured data.

### SF-06 Cart — P0

The system shall maintain an anonymous or customer cart across navigation and permit quantity update/removal.

- Server validates variant status, maximum quantity, and stock.
- Cart shows item-level and order-level discounts plus estimated totals.
- Logging in merges carts using a documented rule and never duplicates quantities beyond allowed stock.
- Cart items preserve the selected variant and display enough information to identify it.

### SF-07 Checkout — P0

The system shall collect contact, shipping address, shipping method, billing choice, and payment method through a guided checkout.

- Required fields and supported destination are validated.
- Customer can review final items and total before payment.
- Final totals are recalculated in trusted code.
- Changed price, discount, stock, shipping, or tax requires explicit review.
- Duplicate submission does not create duplicate charges or orders.

### SF-08 Payment and confirmation — P0

The system shall create payment through an approved gateway and confirm the order only from a trusted provider result.

- Signed webhooks are verified and deduplicated.
- Declined, cancelled, pending, and successful states have distinct user messages.
- Confirmation access requires an authenticated owner or a secure limited reference/token.
- Confirmation includes order reference, summary, delivery destination summary, and next steps.

### SF-09 Customer authentication — P0

The system shall support registration, login, logout, verification/recovery according to configuration, and secure session handling.

- Enumeration-safe messaging is used for recovery/login where appropriate.
- Customer sessions cannot access staff operations.
- Account pages redirect unauthenticated users while preserving intended destination.

### SF-10 Customer profile and addresses — P0

Customers shall edit profile data and manage valid shipping/billing addresses.

- Customer can create, edit, delete, and select default addresses.
- Addresses already copied to orders remain unchanged.
- Only the owning customer and authorized staff can access records.

### SF-11 Customer orders — P0

Customers shall view their orders, payment/fulfillment summary, timeline, items, totals, tracking, and support actions.

- Historical item/address/price data comes from order snapshots.
- Available actions depend on current state and policy.
- Unauthorized order identifiers disclose no order data.

### SF-12 Wishlist — P1

Customers shall add/remove products or variants and move available items to cart. Anonymous wishlist behavior may be local-only and merged after login.

### SF-13 Reviews — P1

Eligible customers shall submit one review per purchased item, subject to moderation.

- Rating, title/body, display-name choice, media, and moderation status are supported.
- Staff can approve, reject, hide, and respond with audit history.
- Aggregate ratings include only approved reviews.

### SF-14 Cancellation and returns — P1

Customers shall request eligible cancellation/return/exchange with item quantities and reasons.

- Eligibility is calculated from state, fulfillment/delivery date, return window, and product rules.
- Requests show progress and notifications.
- The system prevents requested quantities exceeding purchased less prior returned/cancelled quantities.

### SF-15 Informational pages and contact — P0

The storefront shall expose editable about, contact, FAQ, shipping, returns, privacy, and terms content.

- Required legal/policy versions and effective dates can be published.
- Contact requests include abuse protection and delivery to an owned support workflow.

## 3. Admin Functional Requirements

### AD-01 Admin authentication and authorization — P0

- Only active staff members can enter admin routes.
- Owners/admins use MFA when configured as required.
- Each read/write operation checks granular permission server-side.
- Staff invite, role change, deactivation, and sensitive access are audited.

### AD-02 Dashboard — P0

Authorized staff shall view date-filtered KPIs and operational queues.

- KPIs include gross/net sales definition, orders, AOV, units, refunds, and low stock.
- Queues include pending fulfillment, return requests, payment exceptions, and failed notifications.
- Values identify timezone and currency; unsupported cross-currency aggregation is not misleading.

### AD-03 Product type and attribute configuration — P0

Staff shall define reusable product types and typed attributes.

- Supported types include text, number, boolean, date, single-select, multi-select, and measurement where implemented.
- Staff can mark an attribute required, filterable, searchable, variant-driving, or display-only as applicable.
- Attribute codes remain stable after use; destructive changes with data dependencies are blocked or migrated.

### AD-04 Product management — P0

Staff shall create, preview, publish, unpublish, archive, duplicate, search, and filter products.

- Product stores content, product type, attributes, categories, collections, tags, SEO, and publication state.
- Publishing validates configured required data.
- Archiving does not alter historical orders.
- Slug collisions are rejected and redirects are maintained when published slugs change.

### AD-05 Variant and media management — P0

- Staff can define option values and generate/manage valid variant combinations.
- Each variant supports SKU, barcode, price, compare-at price, cost-permission restriction, weight, dimensions, status, and media assignment.
- Invalid duplicate option combinations/SKUs are rejected.
- Media can be uploaded, ordered, assigned alt text, and removed when no protected reference requires retention.

### AD-06 Category and collection management — P0

- Staff can create hierarchical categories with navigation/SEO data.
- Staff can create manual collections and order products.
- Rule-based collections and scheduled publication are P1.
- Cyclic category parent relationships are rejected.

### AD-07 Inventory management — P0

- Staff can view on-hand, reserved, available, threshold, and movement history by variant/location.
- Authorized adjustment requires quantity, reason, and optional reference/note.
- Inventory writes are atomic and append a movement.
- Low-stock/out-of-stock states update storefront behavior and alerts.

### AD-08 Order search and detail — P0

- Staff can find orders by order number, customer/contact, payment reference, tracking, date, and state subject to permissions.
- Detail shows immutable items/addresses/totals, payment, fulfillment, timeline, notes, and notifications.
- Internal notes never appear to customers unless explicitly marked as a customer communication.

### AD-09 Fulfillment — P0

- Authorized staff can create partial/full fulfillment against remaining quantities.
- Carrier, service, tracking number/URL, dispatch date, and customer notification choice are supported.
- Duplicate fulfillment beyond ordered quantity is rejected.
- Fulfillment changes inventory/order states according to configured inventory timing.

### AD-10 Cancellation and refund — P0

- Authorized staff can cancel eligible unfulfilled quantities and release/restock stock.
- Authorized staff can initiate full/partial refunds up to remaining refundable amount.
- Refund records preserve gateway response, reason, actor, amount allocation, and status.
- Failed/pending refunds remain visible for reconciliation and cannot silently mark complete.

### AD-11 Return/exchange processing — P1

- Staff can review, approve/reject, issue instructions, record receipt/inspection, choose restock/write-off, refund/exchange, and close.
- Every decision has actor, timestamp, reason, and customer notification state.
- Exchange may be implemented as return plus replacement order in MVP/P1 to preserve accounting clarity.

### AD-12 Customer management — P0

- Authorized staff can search customer records and view orders, addresses, consent, tags, notes, and value metrics.
- Sensitive fields and export actions require explicit permissions.
- Staff cannot directly set or view customer passwords.
- Account disable/privacy requests follow legal retention and audit rules.

### AD-13 Discounts and promotion rules — P0

- Staff can configure code/automatic percentage, fixed amount, and free-shipping discounts.
- Rules support schedule, currency, minimums, product/collection/customer eligibility, usage limits, and combination behavior.
- The system previews rule validity and records redemptions atomically.
- Gift cards, bundles, and buy-X-get-Y are P2 unless selected for initial scope.

### AD-14 Content, navigation, and SEO — P0

- Authorized staff can manage pages, predefined home sections, menus, banners, FAQs, and default/per-page SEO.
- Draft and published states are supported; preview does not publicly expose drafts.
- Content changes invalidate applicable cache and update sitemap/search where needed.

### AD-15 Reviews moderation — P1

Staff can filter pending/reported reviews, approve/reject/hide, and publish a staff response. Original content and moderation actions remain traceable.

### AD-16 Settings — P0

Owner-authorized staff can configure:

- Business identity, support contacts, locations, timezone, order numbering
- Currency display and tax behavior
- Shipping zones, methods, rates, thresholds, and delivery messaging
- Policy content and return windows
- Notification sender/template configuration
- Non-secret integration status and feature flags

Provider secrets are managed in protected deployment secret stores, not ordinary dashboard fields.

### AD-17 Staff, roles, and audit logs — P0

- Owner can invite/deactivate staff and assign approved roles.
- System prevents removal of the last active owner.
- Audit log supports filters for actor, action, entity, and date and is read-only to ordinary admins.
- Security-relevant and financial actions include sufficient before/after context without logging secrets.

### AD-18 Reports and exports — P1

- Reports cover sales, products, discounts, customers, returns/refunds, payments, and inventory.
- Exports are generated securely, permission checked, time-limited, and audited.
- Large exports run asynchronously and notify the requester.

## 4. System Functional Requirements

### SY-01 Notifications — P0

The system shall queue and track order confirmation, payment exception, fulfillment/tracking, cancellation, refund, return, verification, and password recovery notifications.

- Notification failure never rolls back a valid financial/order transaction.
- Retriable failures use bounded retries and become visible to staff.
- Templates render from immutable event/order data and support test-send in non-production-safe ways.

### SY-02 Payment webhook handling — P0

- Verify provider signature against raw request requirements.
- Reject invalid or stale events according to provider guidance.
- Deduplicate by provider event ID.
- Map event to internal payment safely and reject amount/currency mismatch.
- Apply valid transition transactionally and trigger follow-up work.

### SY-03 Inventory integrity — P0

- Concurrent checkouts cannot sell more than configured available stock.
- Reservations expire and release through a scheduled process.
- Confirmed sales, cancellations, returns, and manual adjustments are reconcilable through movements.
- A reconciliation report identifies negative or inconsistent balances.

### SY-04 Auditability — P0

Create audit events for authentication/security changes, catalog publication, price/inventory changes, order state actions, refunds, returns, exports, settings, and staff access management.

### SY-05 Scheduled operations — P0/P1

- P0: reservation expiry, notification retry, payment reconciliation, stale checkout cleanup
- P1: scheduled content/promotion activation, aggregate refresh, retention/anonymization, low-stock digest
- Jobs are idempotent, observable, and safe to retry.

### SY-06 Analytics events — P0

The system shall emit consistent events for view item/list, search, add/remove cart, begin checkout, shipping/payment step, purchase, refund, and return request. Purchase deduplication uses order/payment identity.

## 5. Business Rules

### Pricing

- Checkout uses currently active price in checkout currency unless a valid locked-price policy exists.
- Compare-at price must exceed selling price to display as a discount.
- Discount allocation to order lines must be deterministic for refunds.
- Staff-facing cost data is permission restricted.

### Inventory

- Default policy disallows purchase when available quantity is insufficient.
- Product active status does not override unavailable variant stock.
- Manual stock changes require reason and cannot rewrite movement history.

### Orders

- Order number is unique and customer-safe; internal UUID remains primary identity.
- Order snapshots are immutable except explicitly modeled operational fields.
- State transitions cannot regress merely due to out-of-order external events.

### Refunds

- Sum of successful and pending refunds cannot exceed captured refundable amount.
- A refund request requires permission, reason, idempotency key, and current provider/payment validation.
- Restocking is an explicit choice separate from financial refund.

### Content and catalog

- Only published, currently active content and sellable products appear publicly.
- Deleting records referenced by orders, payments, inventory movements, or audits is prohibited; archive instead.

## 6. Cross-cutting Acceptance Requirements

### Security

- All exposed Supabase tables have tested RLS.
- Service credentials and provider secrets are absent from client bundles and logs.
- Inputs are schema validated; abuse-prone endpoints are rate limited.
- Staff MFA, least privilege, signed webhook validation, and audit records are in place before launch.

### Accessibility

- Key flows meet WCAG 2.2 AA target: keyboard access, focus management, labels/errors, contrast, zoom/reflow, and screen-reader status messaging.
- Automated checks supplement manual keyboard and assistive-technology testing.

### Performance

- Images are sized/compressed responsively.
- Public pages avoid blocking unnecessary client JavaScript.
- Large admin lists paginate and filter server-side.
- Critical storefront pages meet agreed Core Web Vitals performance budgets on representative mobile devices.

### Reliability

- Retrying checkout, webhook, refund, fulfillment, and scheduled jobs is safe.
- Provider outages result in recoverable pending/error states.
- Backups, restore test, monitoring, alerts, and incident ownership exist before production.

### Privacy and compliance

- Collect only needed personal data and apply market-specific consent/retention rules.
- Card data is handled by the payment provider, not stored by Softglam.
- Privacy export/deletion workflow preserves records legally required for finance, fraud, or disputes.

## 7. MVP Release Acceptance Scenarios

1. Staff creates a new product type (for example eyewear), attributes, product, variants, media, price, and stock, then publishes it without code changes.
2. A visitor finds that product, selects an in-stock variant, completes payment once, and receives one confirmed order and notification.
3. Two customers competing for the last unit cannot both receive confirmed stock.
4. A repeated or out-of-order payment webhook does not duplicate an order, inventory movement, or notification.
5. Staff partially fulfills an order with tracking; customer sees correct remaining and fulfilled quantities.
6. Authorized staff issues a partial refund; unauthorized staff is denied; financial and audit records reconcile.
7. Staff updates homepage content, menus, promotion rules, and policy pages without deployment.
8. Customer can view only their own account/order data; public users cannot read drafts, costs, private return media, or admin data.
9. Operational staff can identify failed notifications, stale payments, low stock, and pending fulfillment from the dashboard.
10. Backup/restore, production monitoring, accessibility, performance, and launch security checks have documented passing evidence.

## 8. Deferred Functions

P2 candidates include multi-currency checkout, localization workflow, multiple warehouses, loyalty, gift cards, subscriptions, bundles, advanced recommendations, virtual try-on, POS, marketplace channels, and native mobile apps. Each requires separate product and technical scoping before implementation.
