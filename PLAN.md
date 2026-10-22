# SoftGlam Delivery Tracker

**Status:** Planning

**Product:** Pakistan-first curated premium multi-category commerce

**Currency:** PKR

**Current release:** R0 — Product and engineering foundation

**Launch target:** R6 — Public MVP

This is the canonical execution tracker. Product intent belongs in `PRD.md`, detailed behavior in `FSD.md`, architecture in `TRD.md`, and end-to-end journeys in `APPFLOW.md`.

## 1. Product North Star

**Vision:** Make SoftGlam the most trusted and delightful Pakistani destination for discovering and purchasing curated lifestyle products.

**Customer promise:** Beautifully selected products, presented honestly, priced clearly, and delivered reliably.

**North-star metric:** Successfully delivered orders per active purchasing customer. A successful order is fulfilled, delivered, and not cancelled, returned, or refunded during the applicable measurement window.

Supporting measures:

- Product-to-cart, cart-to-checkout, and checkout completion rates
- Search success and no-result rates
- Online payment success and COD verification rates
- On-time dispatch, delivery success, and return-to-origin rates
- Repeat purchase, return, refund, and support-contact rates
- Inventory discrepancies, oversells, webhook exceptions, and notification failures
- Core Web Vitals pass rate, API error rate, uptime, and backup restoration success

## 2. Confirmed Product Decisions

| Area | Decision |
|---|---|
| Launch market | Pakistan |
| Currency and language | PKR, English-first |
| Positioning | Curated premium |
| Catalog | Multi-category, configurable product types and attributes |
| Checkout | Guest and registered customer |
| Payments | Cash on delivery plus online payments |
| Inventory | One fulfillment location at launch |
| Fulfillment | Manual courier booking and tracking at launch |
| Frontend | Next.js on Vercel |
| Backend | Laravel REST API on capable cPanel hosting |
| Database | MySQL 8 or compatible MariaDB |
| Architecture | Modular monolith |
| Visual direction | Bright white surfaces, cool neutrals, sky-blue actions, restrained warm accents |

## 3. Status and Tracking Rules

Allowed feature statuses:

- `Not started` — scope exists but is not ready for implementation
- `Ready` — requirements, dependencies, and acceptance criteria are resolved
- `In progress` — active implementation
- `Blocked` — cannot proceed; the blocker must be recorded
- `In review` — implementation complete and under code/product review
- `Staging` — deployed for acceptance testing
- `Complete` — all definition-of-done checks pass
- `Deferred` — explicitly removed from the active release scope

A feature is not complete when only its UI exists. It is complete after applicable backend behavior, authorization, validation, failure states, tests, accessibility, analytics, audit events, staging acceptance, and documentation are finished.

## 4. Current Progress

| Release | Status | Feature groups complete |
|---|---|---:|
| R0 Foundation | In progress | 0/4 |
| R1 Catalog alpha | Not started | 0/5 |
| R2 Cart and accounts | Not started | 0/4 |
| R3 Checkout and COD beta | Not started | 0/5 |
| R4 Online payments | Not started | 0/3 |
| R5 Fulfillment and controlled launch | Not started | 0/4 |
| R6 Public MVP | Not started | 0/6 |
| R7 Operational maturity | Not started | 0/5 |
| R8 Growth | Deferred | 0/5 |
| R9 Scale and expansion | Deferred | 0/5 |

### Active

- [x] `FOUND-001` Finalize launch categories and representative product set — **Complete**
- [x] `FOUND-002` Define target customer profiles — **Complete**
- [x] `FOUND-003` Approve positioning, brand voice, and customer promise — **Complete**
- [x] `FOUND-004` Define product taxonomy and attribute vocabulary — **Complete**
- [x] `FOUND-005` Define shipping zones, rates, remote-area behavior, and delivery promises — **Complete**
- [x] `FOUND-006` Define COD eligibility, verification, value limits, and refusal policy — **Complete**
- [x] `FOUND-007` Define cancellation, return, exchange, and refund policies — **Complete**
- [x] `FOUND-008` Define Pakistan tax and invoice behavior — **Complete**
- [x] `FOUND-009` Select online payment provider and supported methods — **Complete**
- [x] `FOUND-010` Select transactional email provider — **Complete**
- [ ] `DS-001` Approve color tokens and contrast rules — **Not started**
- [ ] `HOST-001` Validate the prospective cPanel package — **Blocked: hosting not purchased**
- [ ] `COMPLY-001` Complete Pakistani business/tax registration and professional tax/invoice approval — **Blocked: entity, NTN, and STRN status unresolved**
- [ ] `PAYPROV-001` Complete Safepay merchant onboarding and payment acceptance gates — **Blocked: legal entity, contract, methods, credentials, and sandbox approval unresolved**
- [ ] `EMAIL-001` Complete domain ownership, support mailbox, Resend setup, authentication, and notification acceptance gates — **Blocked: final domain and provider account unresolved**

### Implementation status

- Laravel 13 API exists under `backend/` with Sanctum, UUID users/tokens, versioned `/api/v1` routing, request correlation IDs, and a tested health endpoint.
- Catalog foundation includes UUID brands, product types, attribute definitions, hierarchical categories, products, variants, assignments/values, deterministic launch taxonomy seeds, and public published-product endpoints.
- Inventory foundation includes a seeded Karachi location, per-variant balances, safe availability, atomic manual adjustments, required actor/reason attribution, and immutable movement history.
- Customer authentication includes registration, login, logout, current-user access, secure Sanctum cookie sessions, normalized credentials, and throttling.
- Root `npm run verify` runs frontend lint/typecheck/build and backend formatting/tests successfully.
- Local and test backend use SQLite for zero-setup development; production remains MySQL/MariaDB and requires dedicated compatibility testing.

### Accepted risks

- `RISK-001` Beauty batch/lot and expiry allocation is managed in an external owned register at launch. Mandatory handling controls and upgrade triggers are defined in `PRD.md` section 1.5. Reassess before catalog growth, materially different received expiries, or multiple inventory locations.

## 5. Standard Feature Delivery Checklist

Use this checklist in the implementing issue or pull request for every feature:

- [ ] Product scope and exclusions approved
- [ ] User roles and permissions identified
- [ ] UX covers desktop, mobile, loading, empty, validation, and failure states
- [ ] API contract and data ownership defined
- [ ] Database migration and rollback reviewed, if applicable
- [ ] Backend behavior and server authorization implemented
- [ ] Frontend/admin behavior implemented
- [ ] Audit and analytics events implemented, if applicable
- [ ] Unit, integration, and end-to-end coverage passes
- [ ] Accessibility and responsive behavior reviewed
- [ ] Security and privacy review passes
- [ ] Staging acceptance passes
- [ ] Operational documentation is current

## 6. Release Plan

## R0 — Product and Engineering Foundation

**Objective:** Resolve business ambiguity and establish the product, design, architecture, and delivery standards required for safe implementation.

### Product and policy

- [x] `FOUND-001` Finalize launch categories and representative product set — **Complete**
- [x] `FOUND-002` Define target customer profiles — **Complete**
- [x] `FOUND-003` Approve positioning, brand voice, and customer promise — **Complete**
- [x] `FOUND-004` Define product taxonomy and attribute vocabulary — **Complete**
- [x] `FOUND-005` Define shipping zones, rates, remote-area behavior, and delivery promises — **Complete**
- [x] `FOUND-006` Define COD eligibility, verification, value limits, and refusal policy — **Complete**
- [x] `FOUND-007` Define cancellation, return, exchange, and refund policies — **Complete**
- [x] `FOUND-008` Define Pakistan tax and invoice behavior — **Complete**
- [x] `FOUND-009` Select online payment provider and supported methods — **Complete**
- [x] `FOUND-010` Select transactional email provider — **Complete**
- [ ] `FOUND-011` Define customer support and incident ownership — **Not started**
- [ ] `FOUND-012` Approve legal content requirements and data-retention policy — **Not started**

### Design system

- [ ] `DS-001` Approve color tokens and contrast rules — **Not started**
- [ ] `DS-002` Approve typography and PKR price formatting — **Not started**
- [ ] `DS-003` Define spacing, grids, radii, elevation, and responsive breakpoints — **Not started**
- [ ] `DS-004` Define buttons, links, icons, badges, and status indicators — **Not started**
- [ ] `DS-005` Define forms, validation, dialogs, drawers, and notifications — **Not started**
- [ ] `DS-006` Define product cards, media, pricing, and merchandising components — **Not started**
- [ ] `DS-007` Define admin tables, filters, forms, and destructive-action patterns — **Not started**
- [ ] `DS-008` Define loading, empty, offline, not-found, and error states — **Not started**
- [ ] `DS-009` Define accessibility and reduced-motion standards — **Not started**
- [ ] `DS-010` Approve photography, image ratios, alt text, and content-copy guides — **Not started**

### Product design

- [ ] `UX-001` Design home, category, collection, and search flows — **Not started**
- [ ] `UX-002` Design product detail, cart, and checkout flows — **Not started**
- [ ] `UX-003` Design order confirmation, tracking, and customer account flows — **Not started**
- [ ] `UX-004` Design admin product, inventory, order, and settings flows — **Not started**
- [ ] `UX-005` Test representative mobile and desktop prototypes — **Not started**

### Engineering foundation

- [ ] `ENG-001` Create Laravel modular-monolith application structure — **In progress: Laravel 13 backend scaffolded**
- [ ] `ENG-002` Establish local Next.js, Laravel, and MySQL development workflow — **In progress: zero-setup SQLite local/test workflow active; MySQL validation pending**
- [ ] `ENG-003` Define API, validation, error, pagination, and idempotency conventions — **In progress: versioned API and health contract active**
- [ ] `ENG-004` Define migration, UUID, UTC timestamp, money, and naming conventions — **In progress: UUID identity/token schema active**
- [ ] `ENG-005` Configure secrets and environment management — **In progress: examples separated; deployment stores pending**
- [x] `ENG-006` Configure structured logging and correlation IDs — **Complete**
- [ ] `ENG-007` Establish frontend and backend test infrastructure — **In progress: PHPUnit backend active; frontend tests pending**
- [ ] `ENG-008` Establish lint, type, test, migration, and build CI checks — **In progress: local aggregate verification passes; CI workflow pending**
- [ ] `ENG-009` Define development, staging, and production deployment workflow — **Not started**
- [ ] `ENG-010` Define backup, restoration, monitoring, alerting, and rollback strategy — **Not started**
- [ ] `HOST-001` Validate PHP, MySQL, SSH, Composer, cron, queue, HTTPS, backup, and log support on cPanel — **Blocked**

**Exit gate:** Taxonomy supports every launch category; representative designs are approved; provider and policy decisions are recorded; environments and CI work; hosting capability is confirmed.

## R1 — Browsable Catalog Alpha

**Objective:** Staff can publish a valid product without developer assistance, and customers can discover it through a responsive, indexable storefront.

### Staff identity and access

- [ ] `IAM-001` User, profile, staff, role, and permission data model — **Not started**
- [ ] `IAM-002` Staff login, logout, secure sessions, and throttling — **Not started**
- [ ] `IAM-003` Server-side role and permission enforcement — **Not started**
- [ ] `IAM-004` Staff invitation, activation, deactivation, and last-owner protection — **Not started**
- [ ] `IAM-005` Owner/admin MFA and authentication audit events — **Not started**

### Configurable catalog

- [ ] `CAT-001` Product types and typed attribute definitions — **In progress: UUID schema, models, and launch product-type seeds implemented**
- [ ] `CAT-002` Product-type attribute assignment, validation, and ordering — **In progress: assignment schema and ordering implemented**
- [ ] `CAT-003` Brands, hierarchical categories, and manual collections — **In progress: brand/category schema and launch hierarchy seeds implemented; collections pending**
- [ ] `CAT-004` Product draft, description, tags, SEO, and publication state — **In progress: draft/active/archive foundation and public publication scope implemented**
- [ ] `CAT-005` Product options, values, variants, SKU, barcode, price, cost, weight, and dimensions — **In progress: variant/SKU/PKR price/weight foundation implemented; options and dimensions pending**
- [ ] `CAT-006` Product media upload, validation, alt text, assignment, and ordering — **Not started**
- [ ] `CAT-007` Related products, preview, publish, unpublish, archive, and duplicate — **Not started**
- [ ] `CAT-008` Publish validation, unique slugs/SKUs, redirects, and audit events — **Not started**

### Single-location inventory

- [x] `INV-001` Location, inventory item, and low-stock threshold model — **Complete**
- [x] `INV-002` On-hand, reserved, and available stock calculations — **Complete**
- [x] `INV-003` Atomic manual adjustment with required reason and actor — **Complete**
- [x] `INV-004` Append-only inventory movement history — **Complete**
- [ ] `INV-005` Low-stock and inventory reconciliation views — **In progress: low-stock calculation implemented; admin view/reconciliation pending**

### Public catalog and search

- [ ] `WEB-001` Announcement bar, responsive header, navigation, search entry, and footer — **Not started**
- [ ] `WEB-002` Configurable homepage sections — **Not started**
- [ ] `WEB-003` Category and collection pages — **Not started**
- [ ] `WEB-004` Product cards, sorting, filters, mobile filter drawer, and pagination — **Not started**
- [ ] `WEB-005` Product detail, gallery, variants, stock, specifications, delivery, and related products — **Not started**
- [ ] `WEB-006` Product/category/attribute search and no-result recovery — **Not started**
- [ ] `WEB-007` Informational pages and shared loading, empty, error, and not-found states — **Not started**

### SEO and platform

- [ ] `SEO-001` Metadata, canonical URLs, robots controls, sitemap, and redirects — **Not started**
- [ ] `SEO-002` Product, Offer, WebSite, and Breadcrumb structured data — **Not started**
- [ ] `OPS-001` Public/private media storage and authorized private downloads — **Not started**
- [ ] `OPS-002` API validation, safe errors, authorization, rate limits, and audit foundation — **Not started**
- [ ] `OPS-003` Baseline application monitoring and error reporting — **Not started**

**Exit gate:** Authorized staff can create and publish a representative product with variants, media, price, and stock; a visitor can find and evaluate it on mobile and desktop.

## R2 — Cart and Customer Accounts

**Objective:** Visitors can build a persistent cart, and customers can securely manage identity, profile, addresses, and wishlist.

### Customer authentication

- [ ] `AUTH-001` Registration and email verification — **In progress: backend registration and verification complete; frontend pages pending**
- [ ] `AUTH-002` Login, logout, intended-destination return, and secure cookie sessions — **In progress: backend session flow complete; frontend return flow pending**
- [ ] `AUTH-003` Enumeration-safe password recovery — **In progress: backend complete; frontend pages pending**
- [ ] `AUTH-004` Authentication throttling and security events — **In progress: endpoint throttling complete; security events pending**

### Customer profile

- [ ] `ACC-001` Account overview and profile editing — **Not started**
- [ ] `ACC-002` Address create, edit, delete, validation, and default selection — **In progress: backend complete; frontend pending**
- [ ] `ACC-003` Ownership authorization and privacy-request foundation — **In progress: per-user address scoping enforced; privacy requests pending**

### Cart

- [ ] `CART-001` Anonymous cart and secure persistent cart token — **In progress: backend complete; frontend pending**
- [ ] `CART-002` Add, update quantity, remove, and save for later — **In progress: backend complete except save-for-later; frontend pending**
- [ ] `CART-003` Variant, price, quantity, and stock revalidation — **In progress: add/update stock checks done; checkout revalidation pending**
- [ ] `CART-004` Customer cart and safe anonymous-cart merge — **In progress: merge on login/register done; frontend pending**
- [ ] `CART-005` Cart summary, estimated shipping, expiration, and recovery states — **In progress: subtotal done; shipping estimate/expiration pending**

### Wishlist

- [ ] `WISH-001` Local anonymous wishlist — **Not started**
- [ ] `WISH-002` Customer wishlist, add/remove, and move to cart — **Not started**
- [ ] `WISH-003` Wishlist merge after login — **Not started**

**Exit gate:** A visitor can build a cart, register or sign in, merge safely, manage addresses/wishlist, and access only their own data.

## R3 — Checkout and COD Beta

**Objective:** A guest or customer can place one valid COD order, with trusted totals, reserved stock, immutable snapshots, and operational visibility.

### Shipping configuration

- [ ] `SHIP-001` Karachi pilot zone and supported destination enforcement — **Not started**
- [ ] `SHIP-002` Manual shipping methods, flat rates, thresholds, and delivery estimates — **Not started**
- [ ] `SHIP-003` Remote-area restrictions and configurable courier definitions — **Not started**

### Checkout

- [ ] `CHK-001` Expiring checkout session and server-authoritative totals — **In progress: server-authoritative totals done; session expiry pending**
- [ ] `CHK-002` Guest and authenticated contact/address flow with Pakistani phone validation — **In progress: guest/auth contact+address done; strict PK phone format pending**
- [ ] `CHK-003` Saved address, shipping method, payment method, and billing choice — **In progress: saved/inline address done; billing choice pending**
- [ ] `CHK-004` Atomic stock reservation and reservation expiry — **In progress: atomic reservation done; expiry/release pending**
- [ ] `CHK-005` Final review, terms acceptance, and changed-price/stock recovery — **In progress: stock revalidation done; terms/price-change UX pending**
- [ ] `CHK-006` Idempotent submission and duplicate-order prevention — **In progress: cart-scoped idempotency done; frontend pending**
- [ ] `CHK-007` Optional post-purchase account creation for guests — **Not started**

### COD

- [ ] `COD-001` COD payment state model and availability rules — **In progress: pending_verification state and placement done**
- [ ] `COD-002` Maximum-value and shipping-zone restrictions — **In progress: Rs 15,000 cap done; zone restrictions pending**
- [ ] `COD-003` Admin verification queue, notes, confirmation, and rejection — **In progress: staff queue + confirm/decline done; admin UI pending**
- [ ] `COD-004` Cancellation/refusal reasons and COD operational analytics — **Not started**

### Orders

- [ ] `ORD-001` Customer-safe order number and order state machine — **In progress: pending_verification→confirmed→fulfilled + cancel enforced via staff endpoints**
- [ ] `ORD-002` Immutable product, variant, price, adjustment, and address snapshots — **In progress: item/price/address snapshots done**
- [ ] `ORD-003` Order timeline and audit events — **Not started**
- [ ] `ORD-004` Customer order history and order detail — **In progress: backend list/detail + guest lookup done; frontend pending**
- [ ] `ORD-005` Secure guest confirmation and order access — **Not started**
- [ ] `ORD-006` Customer/admin cancellation eligibility and atomic inventory release — **In progress: admin cancel releases reserved stock; customer cancel pending**

### Notifications

- [ ] `NOTIF-001` Transactional email provider and queue — **Not started**
- [ ] `NOTIF-002` Order confirmation and cancellation templates — **Not started**
- [ ] `NOTIF-003` Bounded retry, failure visibility, and notification history — **Not started**

**Exit gate:** Duplicate submission cannot create duplicate orders or oversell stock; customers and staff see an accurate COD order state; confirmation notifications are recoverable.

## R4 — Online Payments and Financial Integrity

**Objective:** Customers can pay online safely, and provider events, refunds, and reconciliation remain idempotent and auditable.

### Payment flow

- [ ] `PAY-001` Provider adapter and sandbox integration — **Not started**
- [ ] `PAY-002` Idempotent payment-session creation and provider handoff — **Not started**
- [ ] `PAY-003` Pending, success, declined, cancelled, and safe retry experiences — **Not started**
- [ ] `PAY-004` Payment state machine and authoritative order transitions — **Not started**

### Webhooks and reconciliation

- [ ] `PAY-005` Raw-body signature verification and stale-event policy — **Not started**
- [ ] `PAY-006` Provider-event deduplication and out-of-order handling — **Not started**
- [ ] `PAY-007` Amount, currency, payment, and order matching — **Not started**
- [ ] `PAY-008` Scheduled reconciliation and payment-exception queue — **Not started**

### Refunds

- [ ] `PAY-009` Authorized full and partial refund commands — **Not started**
- [ ] `PAY-010` Deterministic line/adjustment allocation and over-refund prevention — **Not started**
- [ ] `PAY-011` Pending/failed/success states, provider evidence, audit, and notifications — **Not started**

**Exit gate:** Repeated, delayed, or out-of-order requests/events cannot duplicate charges, orders, stock movements, refunds, or notifications.

## R5 — Fulfillment and Controlled Launch

**Objective:** Staff can operate test orders from confirmation through delivery without developer intervention.

### Fulfillment

- [ ] `FUL-001` Fulfillment queue and pick-and-pack workflow — **Not started**
- [ ] `FUL-002` Full and partial fulfillment with remaining-quantity validation — **Not started**
- [ ] `FUL-003` Manual courier, tracking number, tracking URL, and dispatch date — **Not started**
- [ ] `FUL-004` Delivered, failed-delivery, and return-to-origin states — **Not started**
- [ ] `FUL-005` Printable packing document and fulfillment audit events — **Not started**

### Customer experience

- [ ] `FUL-006` Customer tracking and fulfillment timeline — **Not started**
- [ ] `FUL-007` Dispatch, delivery, and delivery-exception notifications — **Not started**

### Operations

- [ ] `OPS-004` Pending verification/fulfillment, low-stock, and exception dashboard — **Not started**
- [ ] `OPS-005` Queue and scheduled-job monitoring — **Not started**
- [ ] `OPS-006` Internal support and order-escalation process — **Not started**

### Controlled launch

- [ ] `LAUNCH-001` Production-like catalog and synthetic order validation — **Not started**
- [ ] `LAUNCH-002` Internal staff training and acceptance — **Not started**
- [ ] `LAUNCH-003` Invited-customer pilot with restricted catalog/geography — **Not started**

**Exit gate:** The internal team fulfills realistic online and COD orders through successful delivery and handles known exceptions without database edits.

## R6 — Public MVP

**Objective:** Complete launch-critical merchandising, promotions, content, analytics, governance, and production readiness.

### Discounts

- [ ] `DISC-001` Percentage, fixed-amount, and free-shipping discounts — **Not started**
- [ ] `DISC-002` Codes and automatic discounts — **Not started**
- [ ] `DISC-003` Schedule, currency, minimum, and product/category/collection eligibility — **Not started**
- [ ] `DISC-004` Total/per-customer limits and combination rules — **Not started**
- [ ] `DISC-005` Atomic redemption and deterministic refund allocation — **Not started**

### Content and merchandising

- [ ] `CMS-001` Homepage section enable, order, edit, preview, and publish — **Not started**
- [ ] `CMS-002` Navigation, announcement, banner, and featured merchandising management — **Not started**
- [ ] `CMS-003` Pages, FAQs, policies, contact content, and SEO management — **Not started**
- [ ] `CMS-004` Draft protection, publication, cache invalidation, and sitemap updates — **Not started**

### Reviews

- [ ] `REV-001` Verified-purchase eligibility and one-review-per-item rule — **Not started**
- [ ] `REV-002` Review submission and customer review history — **Not started**
- [ ] `REV-003` Moderation, staff response, audit history, and approved rating aggregate — **Not started**

### Analytics and reporting

- [ ] `AN-001` Consent-aware view, search, cart, checkout, purchase, refund, and return events — **Not started**
- [ ] `AN-002` Purchase-event deduplication — **Not started**
- [ ] `REP-001` Sales, orders, products, payments, COD, discounts, and inventory reports — **Not started**
- [ ] `REP-002` Permission-aware CSV export for launch-sized datasets — **Not started**

### Governance

- [ ] `GOV-001` Business, timezone, order-numbering, shipping, return, and notification settings — **Not started**
- [ ] `GOV-002` Staff/role management and read-only audit-log viewer — **Not started**
- [ ] `GOV-003` Customer privacy export/deletion request workflow — **Not started**

### Production readiness

- [ ] `READY-001` Security review and no unresolved critical/high findings — **Not started**
- [ ] `READY-002` WCAG 2.2 AA review of key customer and admin flows — **Not started**
- [ ] `READY-003` Mobile Core Web Vitals and API performance validation — **Not started**
- [ ] `READY-004` Checkout, webhook, refund, permission, and inventory-concurrency E2E suite — **Not started**
- [ ] `READY-005` Production backup restoration and deployment rollback exercise — **Not started**
- [ ] `READY-006` Monitoring, alerts, incident ownership, and operational runbooks — **Not started**
- [ ] `READY-007` Legal pages, support channels, staff training, and launch checklist approval — **Not started**

**Exit gate:** Every launch gate in section 7 has documented passing evidence and staff can perform routine operations without developer assistance.

## R7 — Operational Maturity

**Objective:** Improve post-purchase operations, staff efficiency, and exception handling after launch behavior is understood.

### Returns and exchanges

- [ ] `RET-001` Policy-based return eligibility and customer request — **Not started**
- [ ] `RET-002` Private evidence upload and authorized access — **Not started**
- [ ] `RET-003` Admin approve/reject and return instructions — **Not started**
- [ ] `RET-004` Receipt, inspection, restock/write-off, refund, and closure — **Not started**
- [ ] `RET-005` Exchange as return plus replacement order — **Not started**

### Bulk operations and exports

- [ ] `BULK-001` Validated product CSV import and error report — **Not started**
- [ ] `BULK-002` Bulk catalog and inventory operations with audit — **Not started**
- [ ] `BULK-003` Asynchronous, permission-checked, expiring exports — **Not started**

### Customer operations

- [ ] `CRM-001` Customer notes, tags, value metrics, consent, and support history — **Not started**
- [ ] `CRM-002` Abandoned-checkout communication with consent controls — **Not started**
- [ ] `CRM-003` Back-in-stock notifications — **Not started**

### Operational intelligence

- [ ] `REP-003` Return, refund, customer, and promotion reports — **Not started**
- [ ] `REP-004` Delivery, COD refusal, return-to-origin, and courier reports — **Not started**
- [ ] `OPS-007` Advanced low-stock and reconciliation alerts — **Not started**

### Security and lifecycle

- [ ] `SEC-001` Staff active-session management and step-up verification — **Not started**
- [ ] `SEC-002` Scheduled retention and anonymization jobs — **Not started**

**Exit gate:** Returns, bulk work, reports, and failure queues reduce manual work without weakening financial, inventory, privacy, or audit controls.

## R8 — Retention and Growth

**Status:** Deferred until the public MVP has reliable funnel and unit-economics data.

- [ ] `GROW-001` Campaign scheduling, customer segments, and lifecycle email — **Deferred**
- [ ] `GROW-002` Recommendations, improved search ranking, synonyms, and typo tolerance — **Deferred**
- [ ] `GROW-003` Gift cards, bundles, loyalty, and referrals — **Deferred**
- [ ] `GROW-004` Controlled experimentation and conversion analysis — **Deferred**
- [ ] `GROW-005` Courier API adapter and S3-compatible media/CDN migration — **Deferred**

## R9 — Scale and Expansion

**Status:** Deferred until justified by demonstrated business demand.

- [ ] `SCALE-001` Urdu localization and multilingual content workflow — **Deferred**
- [ ] `SCALE-002` Multi-currency and international shipping — **Deferred**
- [ ] `SCALE-003` Multiple inventory locations and warehouse integration — **Deferred**
- [ ] `SCALE-004` Accounting/ERP, marketplace, social channel, or POS integrations — **Deferred**
- [ ] `SCALE-005` Native applications, advanced personalization, and supplier fulfillment — **Deferred**

## 7. Public Launch Gates

### Product and operations

- [ ] Launch catalog, photography, descriptions, prices, and stock are approved
- [ ] Shipping, COD, cancellation, return, refund, tax, invoice, privacy, and terms policies are approved
- [ ] Customer support channel and escalation ownership are active
- [ ] Staff complete catalog, COD, fulfillment, cancellation, refund, and incident training

### Commerce integrity

- [ ] Guest and customer checkout pass
- [ ] COD verification and cancellation pass
- [ ] Online payment success, decline, cancellation, delay, and retry pass
- [ ] Duplicate and out-of-order webhook tests pass
- [ ] Full and partial refund tests pass
- [ ] Last-unit concurrency test proves no oversell
- [ ] Order snapshots and inventory reconciliation pass

### Security and privacy

- [ ] Admin permissions are tested server-side
- [ ] Owner/admin MFA is active
- [ ] CSRF, CORS, cookies, upload validation, rate limits, and webhook signatures are reviewed
- [ ] Secrets are absent from client bundles, logs, and the repository
- [ ] Audit logs cover financial, inventory, publishing, export, settings, and access changes
- [ ] No unresolved critical/high security findings remain

### Accessibility and performance

- [ ] Key storefront and admin workflows meet the WCAG 2.2 AA target
- [ ] Keyboard, focus, labels/errors, screen-reader status, contrast, reflow, and reduced motion are tested
- [ ] Representative mobile Core Web Vitals meet current good thresholds
- [ ] Catalog pages paginate and critical API/query performance targets pass

### Reliability and deployment

- [ ] Development, staging, and production environments are isolated
- [ ] Database backup and restoration are successfully tested
- [ ] Deployment rollback is exercised
- [ ] Queue, scheduler, webhook, payment, and notification failures are monitored
- [ ] Operational alerts have named owners and tested runbooks

## 8. cPanel Acceptance Checklist

Do not approve a production host until it provides:

- [ ] Supported Laravel PHP version and required extensions
- [ ] MySQL 8 or an explicitly reviewed MariaDB version
- [ ] SSH and Composer
- [ ] Configurable web/document root pointing to Laravel `public/`
- [ ] HTTPS and secure environment-variable handling outside the web root
- [ ] Cron jobs and a reliable queue-processing strategy
- [ ] Outbound HTTPS for payment, email, and monitoring providers
- [ ] Database backups and restoration access
- [ ] Adequate memory, process, storage, database-connection, and execution limits
- [ ] Application and web-server log access
- [ ] Staging subdomain and database support

## 9. Planning and Issue Policy

- This file tracks release and feature status at product level.
- Create implementation issues only for the current or immediately upcoming release; do not create hundreds of speculative issues.
- Each issue must reference its stable feature ID and standard delivery checklist.
- Pull requests must reference an issue/feature ID and state which acceptance criteria they satisfy.
- Update this tracker in the same pull request that changes a feature status.
- A deferred feature can enter an active release only through an explicit scope decision.
- New scope must declare its release, dependencies, permissions, analytics, testing, and operational impact.
