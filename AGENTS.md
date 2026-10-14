# SoftGlam Agent Rules

This file guides AI coding work on SoftGlam. It combines a **Ponytail-style lazy senior engineer** mindset with the **non-negotiable** requirements from the TRD and FSD. When in doubt, prefer less code — but never simplify away security, financial integrity, auditability, or customer trust.

## Project Context

- **Frontend:** Next.js 16 (TypeScript), deployed to a Next-compatible host (likely Vercel).
- **Backend (approved direction):** Laravel REST API + MySQL/MariaDB, deployed to cPanel.
- **Auth:** Laravel Sanctum; prefer secure `HttpOnly` cookie sessions when frontend and API share a parent domain.
- **Storage:** Laravel filesystem first, S3-compatible later.
- **Queues/scheduler:** Laravel queues + cPanel cron.
- **Backend location:** `backend/` (Laravel 13, PHP 8.3+).
- **Local backend database:** SQLite for zero-setup development/tests; production target remains MySQL 8 or reviewed MariaDB.

## Verification Commands

- Full repository: `npm run verify`
- Frontend: `npm run lint && npm run typecheck && npm run build`
- Backend: `composer --working-dir=backend verify`
- Targeted backend test: `cd backend && php artisan test --compact path/to/Test.php`

## Laziness Ladder (Ponytail)

Before writing code, climb down this ladder:

1. **Does this need to exist at all?** If the request is speculative, skip it and say so in one line.
2. **Already in this codebase?** Reuse helpers, utilities, types, patterns, or domain modules already here.
3. **Does the standard library/framework cover it?** Use Laravel/PHP stdlib, Eloquent, Next.js built-ins, or React/TypeScript native features.
4. **Does an already-installed dependency cover it?** Use it. Do not add a new dependency for something a few lines can do.
5. **Can it be one line or one small method?** Write that.
6. **Only then:** write the minimum code that correctly solves the problem.

Defaults:

- Deletion over addition; boring over clever.
- No unrequested abstractions: no interface with one implementation, no factory for one product, no config for a value that never changes.
- No boilerplate or scaffolding "for later." Later can scaffold for itself.
- Fewest files possible. Shortest working diff wins — but only once the problem is understood.
- When deliberately simplifying past a known ceiling (global lock, O(n²) scan, naive heuristic), mark it with a `ponytail:` comment naming the ceiling and the upgrade path.

## Non-Negotiable Guardrails

These override the laziness ladder. They are hard requirements from the TRD/FSD and must not be skipped, simplified, or delegated to the UI.

### Security and Authorization

- **All admin authorization is enforced server-side.** UI hiding is not enough.
- Staff permissions are granular and checked on every read/write operation.
- Never derive staff permission from client-editable metadata or email domain.
- Service credentials, provider secrets, and `SUPABASE_SERVICE_ROLE_KEY`-equivalent keys must never reach client bundles or logs.
- Abuse-prone endpoints have rate limits and CAPTCHA where appropriate.
- Staff MFA is required for owners/admins.
- Sensitive operations (refunds, role changes, exports, settings) may require step-up verification.

### Money

- Store and calculate all monetary values as **integer minor units** with an ISO currency code.
- Never use floating-point for money.
- Display formatting is the only place decimals appear.

### Inventory

- Available stock is derived from on-hand minus active reservations.
- Inventory-changing commands must lock rows or use atomic database operations.
- Concurrent checkouts must not oversell the last unit.
- Manual adjustments require reason, actor, and append-only movement records.
- Inventory movements are append-only and reconcilable.

### Orders and Snapshots

- Orders preserve immutable snapshots of product, variant, price, tax, discount, and address data.
- Do not rely on mutable catalog rows to render historical orders.
- Order state transitions are enforced in trusted code; transitions cannot regress due to out-of-order events.
- Order numbers are unique and customer-safe.

### Payments, Refunds, and Webhooks

- Checkout totals are recalculated in trusted server code, not accepted from the client.
- Use idempotency keys for checkout, payment, refund, fulfillment, and webhook operations.
- Payment webhooks must be signature-verified and deduplicated by provider event ID.
- Success/cancel redirects are not proof of payment.
- Sum of successful and pending refunds cannot exceed the captured refundable amount.
- Refunds record provider response, reason, actor, amount allocation, and status.
- Never log or store raw card data, CVV, or unrestricted provider secrets.

### Audit and Logging

- Create audit events for authentication/security changes, catalog publication, price/inventory changes, order actions, refunds, returns, exports, settings, and staff access management.
- Audit logs include actor, action, entity, before/after summary, request metadata, and timestamp.
- Structured logs include correlation IDs and redact tokens, payment data, passwords, and unnecessary PII.

### Storage and Media

- Public product images start on the public disk; serve sensitive files through authorized controllers.
- Validate MIME type, size, extension, and image dimensions.
- Randomize object paths; never use original filenames.
- Store the object path/reference in the database, not expiring signed URLs.

### API and Data Conventions

- Primary keys use UUIDs unless there is a clear operational reason not to.
- Timestamps are stored in UTC; display uses configured business/customer timezone.
- Use typed request/response contracts and schema validation.
- Cursor pagination for large lists.
- Stable machine-readable error codes plus safe user messages.
- Unique constraints cover SKU, product slug, discount code, order number, webhook event ID, and idempotency keys.

### Testing and Quality

- Write unit tests for pricing, discount eligibility, tax/shipping rules, state transitions, and permissions.
- Write integration tests for payment/webhook idempotency, refund flow, email, and storage.
- Test database constraints, atomic inventory behavior, and migration correctness.
- E2E tests must cover browse-to-purchase, declined payment, duplicate webhook, cancellation, fulfillment, return/refund, and staff permissions.
- Run lint, type checks, and tests before considering a change complete.

### Deployment and Secrets

- Secrets live in deployment secret stores; never commit them to source control.
- Database schema changes use versioned migrations.
- Pull request checks include formatting, lint, type checking, unit/integration tests, migration validation, and build.
- Use separate development, staging, and production environments.

## How to Handle Ambiguity

- If a requirement is unclear, first search the codebase and these documents.
- If still unclear, ship the lazy/safe default and ask the user in the same response: "Did X; Y covers it. Need full X? Say so."
- Never stall waiting for an answer when a safe default exists.

## Decision Authority

You may decide:

- Code style, naming, file placement, and small implementation details.
- Whether to reuse an existing pattern or create a minimal new one.
- Which existing test pattern to follow.

Stop and ask the user before:

- Adding a new major dependency.
- Changing the approved backend architecture (Laravel/MySQL).
- Removing or weakening any guardrail listed above.
- Introducing a design pattern that affects multiple domains.
- Committing secrets or modifying security policies.

## Quick Reference

- **Lazy:** reuse, stdlib, one line, delete code.
- **Not lazy:** auth, money, inventory locking, order snapshots, webhook verification, audit logging, idempotency, refunds, secrets, tests for those.
