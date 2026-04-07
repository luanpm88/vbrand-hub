# vBrand E2E (Playwright)

End-to-end tests covering **all web platforms** for vBrand:
- Desktop dashboard (`brand.test/login`)
- Mobile webapp (`brand.test/brand/mobile/login`)
- Storefront WP/Woo (`brand-site.test`)

> Mobile React Native (Expo) is **out of scope** — covered separately.

## Plan

The single source of truth for what's tested is **[docs/E2E_TEST_PLAN.md](../../../docs/E2E_TEST_PLAN.md)**.
It is a phased checklist (Phase 1 → Phase 17). Each phase has its own spec file:

```
tests/phase1-auth-smoke.spec.ts
tests/phase2-products.spec.ts          (todo)
tests/phase3-categories-attrs.spec.ts  (todo)
...
```

When a phase is done, mark its boxes ☐ → ☑ in `docs/E2E_TEST_PLAN.md` and commit.
The next AI session resumes from the first ☐ phase.

## Setup (one-time)

```bash
cd bots/automated/e2e
npm install
npx playwright install chromium
```

## Run

```bash
# Phase 1 only
npm run phase1

# Specific spec
npx playwright test tests/phase1-auth-smoke.spec.ts

# All tests
npm test

# Headed (see browser)
npm run test:headed

# HTML report after a run
npm run report
```

## Targets / env vars

Defaults are local. Override via env vars to point at staging/prod:

| Var | Default |
|-----|---------|
| `BASE_APP` | `http://brand.test` |
| `BASE_SITE` | `http://brand-site.test` |
| `SELLER_EMAIL` | `admin@acm.com` |
| `SELLER_PASSWORD` | `123456` |
| `ADMIN_EMAIL` | `admin@sgconnect.vn` |
| `ADMIN_PASSWORD` | `aA456321@` |

Example — run against staging:

```bash
BASE_APP=https://app.sgconnect.vn \
BASE_SITE=https://logitech.b-teka.com \
SELLER_EMAIL=logitech@gmail.com \
npm test
```

## Conventions

- Selectors: `data-testid` > role/text > CSS. When markup has no testid, use Vietnamese text from `USER_GUIDE_*.md`.
- Tests must be **independent** — each creates and cleans its own fixture data.
- Two viewports: desktop 1280×800 + mobile 430×932 (iPhone 14 Pro), matching the project's Dusk suite.
- Helper: `helpers/auth.ts` provides `loginDesktop`, `loginWebapp`, `assertNoPageErrors`.
