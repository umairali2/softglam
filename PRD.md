# Softglam Product Requirements Document

**Status:** Draft 1  
**Product:** Softglam  
**Product type:** Category-flexible direct-to-consumer e-commerce platform

## 1. Purpose

Softglam must let customers discover, evaluate, purchase, receive, and return physical products while enabling staff to operate the business without routine developer assistance. The initial system must be capable of selling jewelry, eyewear, or another product category through configurable catalog attributes and content.

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
- Configurable attributes and specifications rather than hard-coded jewelry/eyewear fields
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
- **Security:** RLS on exposed tables, least privilege, admin MFA, secure webhook verification, rate limits, validation, audit logs, and no raw card storage.
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

- Launch geography, legal/privacy requirements, tax treatment, currency, and language
- Gateway, payment methods, authorization/capture behavior, and refund constraints
- Shipping providers, delivery SLA, free-shipping logic, and remote-area rules
- Single vs. multi-location inventory and stock reservation strategy
- Return/exchange windows and product exclusions
- Guest checkout, reviews, gift cards, loyalty, and social login scope
