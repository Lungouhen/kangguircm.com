# Commercial CMS Delivery Roadmap

The product remains a secure, single-site RCM marketing CMS. Commercial capabilities are delivered as bounded modules that follow existing controllers, Eloquent models, Blade views, permissions, widget contracts, audits, queues, and encrypted storage.

## Phase 1 — Lead operations

- No-code form builder and form widget
- Encrypted submissions, inbox, exports, and notification handoff
- CAPTCHA provider abstraction
- Legal policy/version and consent linkage
- Cookie consent and script categories

## Phase 2 — Structured RCM content

- Services, Specialties, Case Studies, Testimonials, FAQs, Team, Locations, Payers, Integrations
- Relationships, ordering, publishing, SEO, and dynamic builder widgets

## Phase 3 — Marketing integrations

- Provider-allowlisted GA4, GTM, Search Console, Meta and LinkedIn configuration
- Consent-gated loading, conversion events, CRM webhooks, double opt-in newsletter and scheduling links

## Phase 4 — Operations

- Redirect/404 manager
- Media folders, usage tracking and image derivatives
- Backup/restore, cache/maintenance tools, public search and health reporting

## Delivery rules

1. No arbitrary uploaded PHP or unreviewed JavaScript.
2. Secrets remain environment/secrets-manager values.
3. PHI is prohibited in public forms and external alerts.
4. Every state-changing admin action is authorized, validated and audited.
5. New modules include migrations, tests, admin navigation, public rendering and failure states.
6. Existing content remains backward compatible.
7. Features are not called complete until PHP migrations/tests and browser workflows pass.
