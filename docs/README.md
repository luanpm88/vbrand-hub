# vBrand — Documentation (post-cutover map)

> **2026-06-03 cutover.** The brand / shop / website features no longer run on
> the forked vBrand Laravel app. They run on the **Acelle mainline + the
> `acelle/brand` plugin**. The legacy app at **`~/apps/vbrand/app` is frozen
> (rollback-only) — do NOT use it or its docs as a reference.**

## Where the source of truth lives now

**All brand-feature docs (architecture, orders, products, themes, express
shipping, super-buyer, warehouse, revenue, the customer UI, the vbrandsync REST
contract, E2E) live in the plugin:** `~/apps/acelle_brand/docs/`
([start at its README](../../acelle_brand/docs/README.md)).

This `vbrand/docs/` folder keeps **only** two things current, plus a legacy
archive. We **don't duplicate** plugin docs here — superseded files carry a
banner pointing at their plugin source of truth.

### ✅ Current (maintained here — no plugin equivalent)
| File | Scope |
|---|---|
| `SERVER_MOVE_PLAN.md` | Prod infrastructure / ops — servers, per-site users, php-fpm pools, server move + rollback. |
| `SALES_HANDOVER.md` | Sales / business — feature list, onboarding, demo sites (platform-level, backend-agnostic). |

### ↪︎ Superseded by the plugin (kept for history; see each file's banner)
| Legacy file | Current source of truth |
|---|---|
| `ORDER_STATUSES.md` | `acelle_brand/docs/guide/ORDER_STATUSES.md` |
| `E2E_TEST_PLAN.md` | `acelle_brand/docs/guide/E2E_TEST_PLAN.md` |
| `THEME_BUILDER.md`, `THEME_DESIGN.md` | `acelle_brand/docs/THEME_EDITOR.md` + `guide/THEMES.md` |
| `WP_WOO_SITE_INSTALL.md` | `acelle_brand/docs/guide/WP_SITE_SETUP.md` |
| `VBRAND_SYSTEM_DOCUMENTATION.md` | `acelle_brand/docs/` (PLAN / DESIGN / guide/SYSTEM_OVERVIEW) |
| `USER_GUIDE_DESKTOP.md`, `USER_GUIDE_MOBILE.md` | `acelle_brand/docs/guide/USER_GUIDE_CUSTOMER.md` |
| `rfq/SUPER_BUYER_DESIGN.md` | `acelle_brand/docs/guide/SUPER_BUYER.md` |

### 🗄️ Historical / archive (not a source of truth)
- `ARCHITECTURE_REFACTOR.md` — the 2026-06-03 migration log (forked app → plugin). Useful record.
- `FULL_AUDIT.md` — a 2026-04-06 pre-cutover smoke-test snapshot.
- `rfq/RFQ_DESIGN.md`, `rfq/RFQ_MOBILE_DESIGN.md`, `rfq/IMPORT_REQUEST_DESIGN.md` — **design archive**, never shipped, explicitly **out of plugin scope** (plugin PLAN.md decision D12).
- `marketing/PLAN.md` — **misplaced**: it's the AcelleMail product plan, not vBrand.

## The one-line rule
If it's about **how the brand plugin works** → `acelle_brand/docs/`. If it's about
the **servers it runs on** or the **sales story** → here. Never copy between the
two — link.
