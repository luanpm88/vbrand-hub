# vBrand — E2E Test Plan (Playwright)

> Phụ thuộc: `docs/SALES_HANDOVER.md`, `docs/USER_GUIDE_DESKTOP.md`, `docs/USER_GUIDE_MOBILE.md`
> Mục tiêu: Đảm bảo **mọi tính năng** được mô tả trong các tài liệu sales/user-guide đều hoạt động đúng — chạy local trước, sau đó staging/prod.
> Stack: **Playwright + TypeScript**, chạy trong `bots/automated/e2e/`.
> Phạm vi: **Tất cả platform trừ mobile app (Expo)** — chỉ test web (Desktop dashboard, Mobile webapp, WP storefront).

---

## Targets

| Env | Brand app (Laravel) | Storefront (WP/Woo) |
|-----|---------------------|---------------------|
| **local** *(default)* | http://brand.test | http://brand-site.test |
| staging | https://app.sgconnect.vn | https://*.b-teka.com |
| prod | https://app.sgconnect.vn | (per-customer domain) |

Cấu hình qua env vars: `BASE_APP`, `BASE_SITE`, `SELLER_EMAIL`, `SELLER_PASSWORD`, `ADMIN_EMAIL`, `ADMIN_PASSWORD`.

### Test accounts (local mặc định)

| Role | Email | Password |
|------|-------|----------|
| Seller (customer) | `admin@acm.com` | `123456` |
| Admin | `admin@sgconnect.vn` | `aA456321@` |

> Trên prod password seller demo là `123456` cho 4 site demo (Logitech, Nike, GuuCoffee, Orgafood).

---

## Quy ước phases

Mỗi phase = 1 file `phaseN-***.spec.ts` trong `bots/automated/e2e/tests/`.
Mỗi phase **độc lập** — chạy được riêng. Có thể giao cho AI khác làm tiếp khi resume.

Trạng thái: ☐ pending | ◐ in-progress | ☑ done

```bash
# Run 1 phase
cd bots/automated/e2e
npx playwright test tests/phase1-auth-smoke.spec.ts

# Run all
npx playwright test
```

---

## Phase 1 — Auth & Smoke ☑

> Mục tiêu: Login được + tất cả route chính 200 OK, không có PHP error.
> Spec: `bots/automated/e2e/tests/phase1-auth-smoke.spec.ts` — **48/48 pass** (desktop + mobile projects).

**Desktop dashboard (`/login`):**
- ☑ GET `/login` → 200, có form
- ☑ Login sai password → ở lại `/login`
- ☑ Login đúng → redirect vào dashboard (no PHP error)
- ☑ Smoke từng route chính (13 routes — full menu coverage):
  - ☑ Trang chính `/brand`
  - ☑ Tên miền `/brand/domain`
  - ☑ Website / Giao diện `/brand/website-templates`
  - ☑ **Cấu hình nội dung** `/brand/website/theme/options` *(fixed null-schema bug — see WebsiteController@themeOptions)*
  - ☑ Khách hàng / Liên hệ `/brand/contacts`
  - ☑ Cửa hàng / Sản phẩm `/store/products`
  - ☑ Các danh mục `/store/categories`
  - ☑ Thuộc tính `/store/attributes`
  - ☑ Đơn hàng `/store/orders`
  - ☑ **Kho hàng / Vận chuyển** `/store/warehouse` *(menu "Vận chuyển → Đơn vị vận chuyển" trỏ vào WarehouseController@index — share page với Kho hàng)*
  - ☑ Store dashboard `/store/dashboard`
  - ☑ Bài viết / Blog `/website/articles`
  - ☑ **Doanh thu bán hàng** `/brand/accounting-report` *(CustomerController@accountingReport)*
- ☑ Logout → quay lại `/login`

**Mobile webapp (`/brand/mobile/login`):**
- ☑ GET login page → 200
- ☑ Login sai → ở lại login
- ☑ Login đúng → vào dashboard webapp
- ☑ 4 tab chính:
  - ☑ Dashboard `/brand/mobile`
  - ☑ Sản phẩm `/brand/mobile/products`
  - ☑ Đơn hàng `/brand/mobile/orders`
  - ☑ Tài khoản `/brand/mobile/profile`

**Storefront (`brand-site.test`):**
- ☑ GET `/` → 200, có title
- ☑ `/shop/` → < 500

---

## Phase 2 — Sản phẩm (CRUD) ☑

> Reference: USER_GUIDE_DESKTOP §5.1, USER_GUIDE_MOBILE §2
> Spec: `bots/automated/e2e/tests/phase2-products.spec.ts` — **8/8 pass** (desktop + mobile projects).

**Desktop:**
- ☑ Vào /store/products/create
- ☑ Thêm sản phẩm mới (title + price)
- ☑ Sản phẩm vừa tạo xuất hiện trong WP (verified via `vbrandsync/v1/product/list?keyword=`)
- ☑ Vào /store/products/{id}/edit
- ☑ Sửa title → save → reload → title mới persist
- ☑ Xóa qua DELETE /store/products/delete (id=)
- ☑ Verify gone

**Mobile webapp:**
- ☑ Tab Sản phẩm `/brand/mobile/products` render + có search input
- ☑ Open `/brand/mobile/products/create`
- ☑ Tạo sản phẩm (title + price) → AJAX POST → redirect /edit
- ☑ Sản phẩm xuất hiện trong WP
- ☑ Open `/brand/mobile/products/{id}/edit` → đổi title → submit qua Alpine `submitForm` (dispatch submit event programmatically)
- ☑ Verify round-trip
- ☑ POST `/brand/mobile/products/{id}/delete` → verify gone

**Storefront sync (cross-platform):**
- ☑ Sản phẩm tạo trên dashboard hiện ngay trên WP REST (`vbrandsync/v1/product/list`)
- ☑ Sản phẩm xóa thì biến mất

> **Phase 2 fixes (deploy required):**
> - **vbrandsync** [plugin.php:17-33](site/wp-content/plugins/vbrandsync/plugin.php) — `vbrandsync_getResponse` cached app/kernel statically. Trước đây 2nd call trong cùng request crash với `Call to a member function make() on true` vì `require_once` trả `true`. Đây là root cause khiến mọi product/order/category POST handler die khi theme đã load Laravel trước đó.
> - **app** [Wordpress/Product.php fillParams](app/app/Wordpress/Product.php#L154) — accept aliases `content`/`sale_price`/`categories` (webapp form) ngoài `description`/`discount_price`/`category_ids` (desktop form). Trước đây mobile webapp silently drop description/sale_price/categories khi save.

---

## Phase 3 — Danh mục & Thuộc tính (Desktop only) ☑

> Reference: USER_GUIDE_DESKTOP §5.2, §5.3
> Spec: `bots/automated/e2e/tests/phase3-categories-attrs.spec.ts` — **4/4 pass** (desktop + mobile projects).

**Danh mục:**
- ☑ Vào `/store/categories/create`
- ☑ Thêm danh mục mới (name + description)
- ☑ Verify trong WP qua `vbrandsync/v1/category/list`
- ☑ Sửa danh mục (`/store/categories/{id}/edit`) → round-trip
- ☑ Xóa qua DELETE `/store/categories/delete-selected` (`ids[0]=`)
- ☑ Verify gone

**Thuộc tính:**
- ☑ Vào `/store/attributes/create`
- ☑ Tạo thuộc tính (name + description + 3 values: S, M, L) — values[] inject vào form qua page.evaluate (mô phỏng JS row builder)
- ☑ Verify trong WP qua `vbrandsync/v1/attribute/list` — values khớp ['L','M','S']
- ☑ Sửa thuộc tính (description) — name read-only on edit (đúng — WP attribute slug không rename được)
- ☑ Xóa qua DELETE `/store/attributes/delete-selected` → verify gone

> ⚠️ Skip: "Gán danh mục + thuộc tính cho 1 sản phẩm test" — sẽ làm trong Phase 4 (đơn hàng) hoặc Phase tích hợp riêng. Các CRUD cơ bản cho cả 2 entity đã xanh.

> **Phase 3 fixes (deploy required):**
> - **app** [resources/views/store/attributes/_form.blade.php:5](app/resources/views/store/attributes/_form.blade.php#L5) — `<input readonly>` luôn áp dụng cho cả create + edit. Trên create form thì user không gõ được name → form fail validation. Fix: chỉ readonly khi `$attribute->id` đã tồn tại (edit mode).

---

## Phase 4 — Đơn hàng & Order Statuses ☑

> Reference: USER_GUIDE_DESKTOP §5.4, USER_GUIDE_MOBILE §3, docs/ORDER_STATUSES.md
> Spec: `bots/automated/e2e/tests/phase4-orders.spec.ts` — **12/12 pass** (desktop + mobile projects).

**Setup (helpers/api.ts):**
- ☑ `seedOrder()` — POST `vbrandsync/v1/order/add` → status `ordered`
- ☑ `findOrderById()` — GET `vbrandsync/v1/order/find/{id}`
- ☑ `forceDeleteOrder()` — POST `vbrandsync/v1/order/delete/{id}` (vbrandsync handler implemented as part of this phase)

**Desktop:**
- ☑ `/store/orders` list page renders, no PHP error
- ☑ Seeded order findable in WP
- ☑ 4-step happy-path workflow (`ordered → packaging → ready_for_pickup → delivering → completed`):
  - ☑ Xác nhận đơn (`/store/orders/{id}/seller-confirm`)
  - ☑ Đóng gói (`/store/orders/{id}/set-packaged`)
  - ☑ Đang giao (`/store/orders/{id}/set-delivering`)
  - ☑ Hoàn thành (`/store/orders/{id}/complete`)
- ☑ Hủy đơn (`/store/orders/{id}/seller-cancel`) → `seller_cancelled`

**Mobile webapp:**
- ☑ `/brand/mobile/orders` list page renders, no PHP error
- ☑ Same 4-step happy-path workflow via webapp routes (`/brand/mobile/orders/{id}/...`)
- ☑ Hủy đơn via webapp route → `seller_cancelled`

> ⚠️ **Skipped (not wired end-to-end in production):**
> - **"Đã giao" (set-delivered) intermediate step** — user guides previously listed it as a 5th step but it never worked: vbrandsync `Order` model has no `setDelivered()`, brand-app `Acelle\Wordpress\Order` has no `setDelivered()`, `OrderStatusCatalog::actionUrls['store']['set-delivered']` points at the non-existent `Store\OrdersController@setComplated`, and there is no `wc-delivered` post status registered in `vbrandsync/plugin.php`. The 4 visible buttons in the actual UI go straight from `delivering` → `completed`. **Fix applied:** updated `USER_GUIDE_DESKTOP.md` and `USER_GUIDE_MOBILE.md` to the actual 4-step workflow. The dead `Đã giao` plumbing is left in place pending a separate decision on whether to implement or remove.
> - **Refund / Báo mất hàng / Lịch sử trạng thái UI** — endpoints exist but require complex preconditions (a paid order, then refund flow). Will revisit if covered in a later integration phase.

> **Phase 4 fixes (deploy required):**
> - **vbrandsync** [wordpress/api/order.php](site/wp-content/plugins/vbrandsync/wordpress/api/order.php) — `vbrandsync_ajax_order_delete` was an empty function and only registered at `/order/delete` (no id). Brand-app actually calls `/order/delete/{id}`. Implemented the handler (resolves id from path or body, force-deletes the WC order) and registered both URLs. Without this, brand-app delete silently fails and orphan WP orders accumulate.
> - **docs** — `USER_GUIDE_DESKTOP.md` §5.4 and `USER_GUIDE_MOBILE.md` §3 corrected from 5-step to actual 4-step workflow with status names in parentheses.

---

## Phase 4.1 — Storefront customer checkout (COD + vBrand Express) ☑

> Reference: SALES_HANDOVER §1, CLAUDE.md §"Site standardization"
> Spec: `bots/automated/e2e/tests/phase4_1-storefront-checkout.spec.ts` — **8/8 pass** (4 tests × 2 projects).
> Setup: `bots/automated/enforce-cod-vbrand-express.php` (already applied to local + 5 prod sites).

- ☑ `/cart/` page renders, no PHP error
- ☑ `/checkout/` page renders, no PHP error
- ☑ With a product in cart, WC Store API `/cart` endpoint advertises **only `cod`** in `payment_methods`
- ☑ With a product in cart, WC Store API `/cart` endpoint advertises **only `vbrand_shipping_method`** ("vBrand Express") in shipping rates on every package
- ☑ Customer adds product → WC Store API checkout → order id returned → order findable in WP with billing info intact

> **Phase 4.1 fixes (deploy required):**
> - **vbrandsync** [wordpress/payment.php](site/wp-content/plugins/vbrandsync/wordpress/payment.php#L34) — `BaoKimVN` constructor read `$this->settings['title']/['description']/['merchant_id']/['redirect_page_id']` directly without `??` defaults. When the enforce script (or any admin tool) wrote a partial settings array, the constructor crashed on every WP request — including all REST API endpoints. Defensive `?? ''` defaults added.
> - **bots** [bots/automated/enforce-cod-vbrand-express.php](bots/automated/enforce-cod-vbrand-express.php) — new wp-cli script that idempotently enforces COD-only payment + vBrand Express-only shipping. Already run on local + all 5 prod sites.
> - **bots** [bots/automated/deploy-sites.md](bots/automated/deploy-sites.md) — new step 5.5 calls the enforce script after every plugin sync.
> - **docs** [WP_WOO_SITE_INSTALL.md](docs/WP_WOO_SITE_INSTALL.md) — appended a "BẮT BUỘC sau khi install" section pointing at the script.
> - **CLAUDE.md** — new top-level §"Site standardization" with the rule + how to apply.

---

## Phase 4.2 — Full E2E order flow (customer → seller → admin) ☑

> Reference: SALES_HANDOVER §1, USER_GUIDE_DESKTOP §5.4, USER_GUIDE_MOBILE §3
> Spec: `bots/automated/e2e/tests/phase4_2-full-order-flow.spec.ts` — **6/6 pass** (3 tests × 2 projects).

**Test 1 — Full happy-path:**
- ☑ Customer places order via storefront (Store API: add-to-cart + checkout COD)
- ☑ Seller (desktop) `/store/orders` list contains the order id
- ☑ Seller (webapp) `/brand/mobile/orders` list contains the order id
- ☑ Admin `/admin/brand/{customer_uid}/orders` list contains the order id
- ☑ Seller walks 4-step workflow on desktop: `ordered → packaging → ready_for_pickup → delivering → completed` (with `normaliseToOrdered` helper to convert WC's default `processing` → `ordered` if needed)
- ☑ Admin still sees the now-completed order

**Test 2 — Seller cancel edge case:**
- ☑ Customer places order
- ☑ Seller cancels via webapp `/brand/mobile/orders/{id}/seller-cancel`
- ☑ Status → `seller_cancelled`
- ☑ Admin sees the cancelled order in the admin list

**Test 3 — Admin intervention cancel:**
- ☑ Customer places order
- ☑ Admin cancels via `/admin/store/{customer_uid}/orders/{id}/seller-cancel`
- ☑ Status → `seller_cancelled`

> **New helpers added in this phase:**
> - `helpers/auth.ts` `loginAdmin()` + `loginVia()` (skip-when-already-authenticated) + `forceLogout()`
> - `helpers/api.ts` `wcStoreNonce`, `customerAddToCart`, `customerCheckout` (WC Store API)
> - `playwright.config.ts` `SELLER_CUSTOMER_UID` env var (default `679906f87e366` for local `admin@acm.com`; on prod override to the seller's `customers.uid`, e.g. `69b617af33263` for logitech)

---

## Phase 5 — Storefront checkout (COD) ☑ (covered by Phase 4.1 + 4.2)

> **Phase 5 đã được Phase 4.1 + 4.2 cover hoàn toàn** — không cần spec riêng.

- ☑ Cart + checkout pages render → Phase 4.1
- ☑ Add to cart → Phase 4.1 + 4.2
- ☑ COD payment + vBrand Express shipping = options duy nhất → Phase 4.1
- ☑ Place order → order id returned → Phase 4.1
- ☑ Order findable in WP với billing info → Phase 4.1
- ☑ Order xuất hiện trên seller desktop, seller webapp, admin → Phase 4.2 Test 1

> Customer auto-create: WC checkout không tạo customer record bên brand-app
> (chỉ tạo `_billing_*` meta trên order). Brand-app's "Khách hàng" tab quản
> lý `Brand\Contact` table riêng. Cover trong Phase 6.

---

## Phase 6 — Khách hàng (Desktop) ☑

> Reference: USER_GUIDE_DESKTOP §4
> Spec: `bots/automated/e2e/tests/phase6-contacts.spec.ts` — **4/4 pass** (2 tests × 2 projects).

- ☑ `/brand/contacts` list page renders, no PHP error
- ☑ Create contact via `/brand/contacts/create` form (first_name, last_name, email, phone, address_1, country_id=228 VN, city, state)
- ☑ Find by email via `/brand/contacts/list?keyword=`
- ☑ Edit contact (`/brand/contacts/{id}/edit`) — change phone → round-trip
- ☑ Delete via `DELETE /brand/contacts/delete` (id in body)
- ☑ Verify gone from list

> ⚠️ **Multitenancy concern (not fixed):** `Acelle\Model\Contact` has no `customer_id` column at all — the contacts table is GLOBAL across all sellers. `Brand\ContactController::index` does `Contact::search($keyword)` with no scope. Two sellers logging into the brand app see the same contact list. This is the existing production behavior; left untouched pending a design decision. Tests use unique emails to avoid concurrent-run collisions.

> **Khách hàng "auto-created from checkout" (originally listed in Phase 5):** WC checkout creates `_billing_*` order meta, NOT a `Brand\Contact` row. The dashboard's "Khách hàng" tab is a separate CRM-style list manually maintained by the seller. The two are unrelated tables.

> **New helpers added in this phase:**
> - `helpers/api.ts` `findContactIdByEmail()`, `forceDeleteContact()`
> - Pattern: replace cascading-AJAX `<select>` elements with hidden `<input>`s before submit (avoids driving the country/state/city dropdown chooser JS — same approach as Phase 3 attribute values[]).

---

## Phase 7 — Website: Theme & Cấu hình nội dung ☑

> Reference: USER_GUIDE_DESKTOP §3, USER_GUIDE_MOBILE §4
> Spec: `bots/automated/e2e/tests/phase7-website.spec.ts` — **10/10 pass** (5 tests × 2 projects).

**Desktop:**
- ☑ `/brand/website-templates` list page renders, no PHP error
- ☑ WP exposes >1 theme via `vbrandsync/v1/theme/list` (verified ≥2 themes + exactly 1 active)
- ☑ POST `/brand/website-templates/set-active/{theme}` flips the active flag in WP
- ☑ `/brand/website/theme/options` (Cấu hình nội dung) page renders
- ☑ POST `/brand/website/theme/options` with empty payload → status < 500 (no-op round-trip; theme builder schema may be empty for some themes — Phase 1 fixed the null-schema crash)

**Mobile webapp:**
- ☑ `/brand/mobile/templates` list page renders, no PHP error
- ☑ POST `/brand/mobile/templates/{theme}/activate` flips the active flag in WP

**Snapshot/restore strategy:** each theme-switching test snapshots the current active theme in `let originalThemeId` and restores it in `afterEach`, so a green Phase 7 leaves the site on the same theme it started.

> ⚠️ **Skipped (deeper UI testing not in scope):**
> - Editing specific theme builder fields (banner text, menu items, footer) — depends on the active theme exposing a `themeGetMeta()` schema. Local active theme (AcelleMail) has no schema. The render + save round-trip is verified but per-field editing is left for theme-specific Dusk tests.
> - "Xem trang" → opens storefront in new tab — this is a `target="_blank"` link with no app-side state to verify; covered indirectly by Phase 1 storefront smoke.

> **New helpers:** `helpers/api.ts` `listThemes()`, `activeTheme()` — both query `vbrandsync/v1/theme/list` (returns object keyed by theme name, normalised to a `WPTheme[]` array).

### Phase 7.1 — Website extras (leftover items) ☑

> Spec: `bots/automated/e2e/tests/phase7_1-website-extras.spec.ts` — **9/10 pass + 1 intentional skip** (5 tests × 2 projects, mobile project skips the desktop-sidebar-only test).

The original Phase 7 plan listed sub-items the main spec did not test individually. Phase 7.1 closes them:

**Desktop:**
- ☑ Templates list page renders one card per theme — thumbnail (`<img>`) + "Đang chọn" label on the active theme + "Kích hoạt" form on each inactive theme. The original "4 themes (logitech / orgafood / dreamcafe / nikezero)" assertion was prod-specific; replaced with `themes ≥ 1` + "active count == 1" + "Kích hoạt count == themes - 1" (matches actual local theme set).
- ☑ Preview — there is **no separate "Preview" button** on the templates page (verified by reading `brand/website_templates/index.blade.php`). The "preview" referenced in the original plan is the iframe inside Cấu hình nội dung (`brand/website/themeOptions.blade.php` line 471: `<iframe id="previewFrame" src="{previewUrl}?vb_builder=1">`). Phase 7.1 verifies the iframe is attached and the src points at the storefront.
- ☑ Activate template → covered by main Phase 7 (set-active flips active flag in WP).
- ☑ Cấu hình nội dung page renders → covered by main Phase 7 + iframe assertion above.
- ☑ Vào Website → Xem trang của bạn → desktop sidebar `<a target="_blank" href="{site_url}">` is asserted (skipped on mobile project — sidebar dropdown not rendered in iPhone 14 Pro viewport).
- ⚠️ **Sửa Thông tin chung / Trang chủ / Menu / Giới thiệu / Footer + Verify storefront có thay đổi:** depends on the active WP theme exposing a `themeGetMeta()` builder schema. Local active theme (AcelleMail) returns no schema → after Phase 1's null-fix normalisation `schema['sessions']` and `schema['options']` are empty arrays, so there are no fields to fill and no storefront content tied to those fields. Per-field editing is left for theme-specific Dusk tests against a builder-aware theme. The schema-aware machinery itself (page renders, save round-trip, iframe) IS verified.

**Mobile webapp:**
- ☑ Templates list renders thumbnails + "Xem website" header link with `target="_blank"`.
- ⚠️ Preview template — no preview surface on the webapp templates page either (only Activate). Same situation as desktop.
- ☑ Activate template → covered by main Phase 7.
- ☑ Tài khoản → Xem website → `webapp/profile/index.blade.php:72` `<a target="_blank">Xem website</a>` link asserted.

---

## Phase 8 — Tên miền ☑

> Reference: USER_GUIDE_DESKTOP §2
> Spec: `bots/automated/e2e/tests/phase8-domain.spec.ts` — **6/6 pass** (3 tests × 2 projects).

- ☑ Vào Tên miền (`/brand/domain`) → list page renders, no PHP error, AJAX `/brand/domain/list` < 500
- ☑ "Đăng ký tên miền mới" CTA (link, not dialog) → click → `/brand/domain/check` form renders (input `#domainname` + Check button)
- ☑ Search 1 dummy domain → GET `/brand/domain/checkdomain?domain=...` returns a result fragment (with auto-skip if upstream `whois.net.vn` is unreachable from test env — third-party dependency, not a vbrand bug)
- ☑ Skip thanh toán thật — `Brand\DomainController@buy` requires a real `customer->assignDomainPlan()` → invoice → checkout flow that touches CheckoutController; out of scope for E2E

> ⚠️ **Webapp:** there is no Tên miền surface in the mobile webapp (USER_GUIDE_MOBILE has no §Tên miền — domain management is desktop-only). Phase 8 is desktop-only; mobile project still runs the same specs as a smoke (no viewport-specific assertions).

> **Notes (no fixes required):**
> - `Brand\DomainController@checkDomain` calls `Domain::checkDomain($domain)` which does `file_get_contents('https://www.whois.net.vn/whois.php?domain=...')` synchronously and the controller compares the raw response against integer `1`. Practical effect: result is **always** the "available" branch unless whois.net.vn returns literally `1`. Left as-is — out of scope, but worth flagging in a future hardening pass.
> - The list AJAX returns an empty list HTML when the user has no domains (the case on local for `admin@acm.com`). That is the correct behavior.

---

## Phase 9 — Kho hàng ☑

> Reference: USER_GUIDE_DESKTOP §6
> Spec: `bots/automated/e2e/tests/phase9-warehouse.spec.ts` — **4/4 pass** (2 tests × 2 projects).

- ☑ Vào `/store/warehouse` → auto-redirect tới `/store/warehouse/{id}/edit` (no PHP error)
- ☑ Form **Thông tin kho hàng** render với 3 required fields (`contact_name`, `contact_phone`, `address`)
- ☑ PATCH `/store/warehouse/{id}` round-trip `contact_name` end-to-end (snapshot + restore original)

> ⚠️ **Skipped (not implemented in production):**
> - **Xuất nhập tồn / Nhập hàng / Xuất hàng / Thống kê sản phẩm** — none of these are wired. `Store\WarehouseController` only implements `index` (redirect), `edit`, `update`. The other resource methods (`list`, `create`, `store`, `show`, `delete`, `destroy`) are empty stubs. There are no routes or models for stock movements, import/export slips, or inventory reports.
> - **Fix applied:** [docs/USER_GUIDE_DESKTOP.md §6](docs/USER_GUIDE_DESKTOP.md) corrected to reflect actual capability — Kho hàng is the seller's single warehouse contact-info form (địa chỉ lấy hàng), nothing more.

> **Webapp:** no Kho hàng surface in the mobile webapp (USER_GUIDE_MOBILE has no §Kho hàng — desktop-only feature). Phase 9 is desktop-only; mobile project still runs the same specs as a smoke.

---

## Phase 10 — Vận chuyển & Doanh thu ☑

> Reference: USER_GUIDE_DESKTOP §7, §8
> Spec: `bots/automated/e2e/tests/phase10-shipping-revenue.spec.ts` — **8/8 pass** (4 tests × 2 projects).

**Doanh thu (§8):**
- ☑ `/brand/accounting-report` page renders, no PHP error
- ☑ 3 filter selects (`sort_order`, `transaction_type`, `entry_type`) attached to the form
- ☑ AJAX `/brand/customers/{uid}/journal-entries` (no filter) → < 500
- ☑ AJAX with `transaction_type=user_sale_order` → < 500
- ☑ AJAX with `entry_type=user_cash` → < 500

**Vận chuyển (§7):** ⚠️ **Skipped (not implemented):**
- The "Vận chuyển" parent menu in `_menu_frontend_brand.blade.php:260` is hidden via `d-none`. Production sellers do not see it.
- Its only working sub-item "Đơn vị vận chuyển" links to `Store\WarehouseController@index` (i.e. the same Kho hàng page already covered by Phase 9).
- The "Thống kê vận chuyển" sub-item links to `href="#"` — no route, no controller, no view.
- **No date-range filter** on the Doanh thu page either — the plan item "Đổi range thời gian → reload data" was aspirational. Filters are sort/transaction_type/entry_type only.
- **Fix applied:** [USER_GUIDE_DESKTOP §7](docs/USER_GUIDE_DESKTOP.md) now describes Vận chuyển as "vBrand Express là đơn vị mặc định, cấu hình điểm lấy hàng trong Kho hàng" (no separate Vận chuyển page). [USER_GUIDE_DESKTOP §8](docs/USER_GUIDE_DESKTOP.md) now lists the 5 actual accounting metrics + 3 actual filters; the date-range claim was removed.

> **Webapp:** no Vận chuyển / Doanh thu surface in the mobile webapp. Phase 10 is desktop-only; mobile project still runs the same specs as a smoke.

---

## Phase 11 — Tài khoản & đổi mật khẩu ☑

> Reference: USER_GUIDE_DESKTOP (header), USER_GUIDE_MOBILE §5
> Spec: `bots/automated/e2e/tests/phase11-account.spec.ts` — **6/6 pass** (3 tests × 2 projects).

**Desktop (`/account/profile`):**
- ☑ GET renders form, no PHP error
- ☑ POST round-trips a `first_name` change end-to-end (snapshot + restore in afterEach)

**Webapp profile (`/brand/mobile/profile` + `/edit`):**
- ☑ Index page renders, no PHP error, has "Thông tin cá nhân" + "Đổi mật khẩu" links
- ☑ Edit form renders with `first_name` / `last_name` / `phone` inputs
- ☑ POST `/brand/mobile/profile/update` (JSON) returns success message + value persists on reload (snapshot + restore in afterEach)

**Webapp password (`/brand/mobile/profile/password`):**
- ☑ Form renders with `current_password` / `new_password` / `new_password_confirmation` inputs
- ☑ POST changes password to a 12-char temp value → success JSON + a fresh browser context can authenticate with the new password
- ☑ afterEach restores the original `123456` password via the **desktop** `/account/profile` endpoint (which has no min-length on the password field — see note below). Verified via `Hash::check` after a full mobile-project run.

> ⚠️ **User guide vs reality:** the original plan said "Đổi mật khẩu (đổi rồi đổi lại 123456)" — but `Brand\Webapp\ProfileController@updatePassword` enforces `new_password: required|min:8` ([app/Http/Controllers/Brand/Webapp/ProfileController.php:73](app/Http/Controllers/Brand/Webapp/ProfileController.php#L73)). Local seller password is 6 chars (`123456`), so the webapp endpoint can never restore it. Phase 11 routes the restore through the desktop `AccountController@profile` endpoint which has no min-length validation — that's the only way to revert without a direct DB write. The user guides do not promise being able to set a 6-char password, so no doc fix needed; the plan wording was the only thing wrong.

> **Webapp Tab Tài khoản → Xem website link:** already covered by Phase 7.1 (mobile webapp profile Xem website test).

---

## Phase 12 — RFQ flow ☑

> Reference: docs/rfq/RFQ_DESIGN.md, SALES_HANDOVER §1 (RFQ)
> Spec: `bots/automated/e2e/tests/phase12-rfq.spec.ts` — **6/6 pass** (3 tests × 2 projects).

**WP REST seed + meta verification:**
- ☑ `vbrandsync /order/add` with `order_type=rfq` lands an order in `rfq_pending` with `_rfq_unit_price`, `_rfq_line_total = unit × qty`, `_rfq_status = rfq_pending`, `_rfq_original_total > 0`

**Webapp (`/brand/mobile/orders/...`):**
- ☑ Filter `?status=rfq-pending` returns the RFQ (per-row "RFQ" chip rendered from `webapp/orders/_list.blade.php:96`)
- ☑ Order show page renders the "Yêu cầu báo giá" pricing card
- ☑ POST `/{id}/approve-rfq` returns `{status:'success'}` + WP `_rfq_status = rfq_approved` + `total = rfq_unit_price × quantity` + `status = packaging`

**Desktop (`/store/orders/...`):**
- ☑ Filter `?status=rfq-pending` includes the seeded RFQ in the rendered list HTML
- ☑ POST `/{id}/approve-rfq` returns `{success: 'Đã duyệt RFQ thành công!'}` + same WP-side state changes verified

> ⚠️ **Skipped (not implemented in production):**
> - **Storefront RFQ creation** — the WP storefront has no RFQ creation UI. RFQ orders originate from the Super Buyer flow ([docs/rfq/SUPER_BUYER_DESIGN.md](docs/rfq/SUPER_BUYER_DESIGN.md)). Phase 12 seeds RFQs via the same `vbrandsync /order/add` endpoint that Super Buyer checkout uses.
> - **Webapp seller "RFQ count" badge** — `Brand\Webapp\OrderController@index` only computes `ordersCount / completedCount / failedCount`. There is no `rfqCount` and no header badge surfacing it.
> - **Reject RFQ** — there is no reject endpoint in any controller. `approveRfq` is the only RFQ action; the order stays `rfq_pending` until either approved or seller-cancelled via the standard cancel path.

> **Phase 12 fixes (deploy required):**
> - **app** [app/Support/OrderStatusCatalog.php:137-150](app/Support/OrderStatusCatalog.php#L137) — `prefixed()` was doing `str_replace('_', '-', $normalized)` so `rfq_pending` became `wc-rfq-pending`. WC custom statuses are registered with **underscores** ([site/wp-content/plugins/vbrandsync/plugin.php:339](site/wp-content/plugins/vbrandsync/plugin.php#L339) and friends — `wc-rfq_pending`, `wc-ready_for_pickup`, `wc-seller_cancelled`, etc.). The hyphen variant matched zero orders, silently breaking every multi-word status filter end-to-end. Fix: drop the `str_replace`. Affects webapp, desktop, brand-api, and admin order list filters.
> - **app** [app/resources/views/store/orders/list.blade.php](app/resources/views/store/orders/list.blade.php) — the desktop orders list partial was structurally broken by commit `0a22389ce5` ("graceful WordPress connection error handling"). The edit accidentally replaced the inner `@foreach($orders as $order) @php` with a stray `<script>` tag, dropped the foreach entirely, and left an orphan `@endforeach` + duplicate empty-state block. Result: any visit to `/store/orders/list` (the AJAX partial used by the desktop orders index) crashed with a Blade syntax error. Phase 4 desktop tests dodged this because they hit the per-id action endpoints, not the list partial. Fix: restore the inner `@if(!$ordersIsEmpty) @foreach @php ... @endphp <div>...</div> @endforeach @endif` wrapper from the pre-broken commit `d970d53e73`, remove the duplicate empty-state and orphan `@endforeach`.
> - **app/db migration** — `2026_03_19_120000_add_rfq_v1_fields_to_super_buyer_orders_table` was Pending on local. Without it, both `Webapp\OrderController@approveRfq` and `Store\OrdersController@approveRfq` crash on the `super_buyer_orders` `update(...rfq_unit_price...)` call (`SQLSTATE[42S22]: Column not found`). Run `php artisan migrate` after deploy.

> **New helpers:** [helpers/api.ts](bots/automated/e2e/helpers/api.ts) `seedRfqOrder()` — POST to vbrandsync `/order/add` with `order_type=rfq` and a buyer-proposed `rfq_unit_price`.

---

## Phase 13 — Super Buyer ☑

> Reference: docs/rfq/SUPER_BUYER_DESIGN.md
> Spec: `bots/automated/e2e/tests/phase13-super-buyer.spec.ts` — **6/6 pass** (3 tests × 2 projects).
>
> The route group is fully implemented under `routes/brand_superbuyer.php` with controllers in `app/Http/Controllers/Brand/SuperBuyer/` and views in `resources/views/superbuyer/`. The plan note "có thể skip nếu chưa có UI" is now stale — UI is shipped.

**Local fixture:** `admin@acm.com` is both a seller AND an active super buyer (`super_buyers.id=1`, `status=active`). The seller customer (`uid=679906f87e366`) is the only customer with a WordPress endpoint on local, so it's the only "shop" the super buyer can browse.

**Auth + smoke:**
- ☑ GET `/brand/super-buyer/mobile/login` → 200 (orange theme — `meta theme-color="#EA580C"`, `bg-brand-600`)
- ☑ POST login as `admin@acm.com` → redirect to `/brand/super-buyer/mobile/` dashboard
- ☑ Dashboard, `/shops`, `/orders`, `/profile` all render < 500 with no PHP errors
- ☑ `/shops/list` partial includes the seller's customer uid
- ☑ `/shops/{uid}/products` page + `/shops/{uid}/products/list` partial both < 500 (resolves WP via `WordpressConnectionFacade::setWordpress($customer->wordpress())` per request)

**Checkout + cancel (cross-shop / SuperBuyerOrder lifecycle):**
- ☑ GET `/shops/{uid}/checkout/{productId}` form renders with hidden `shop_uid` + `product_id`
- ☑ POST `/checkout/submit` (`order_type=normal`, qty=1, billing fields) → `{status:'success', redirect:/orders/{id}}`
- ☑ The new SuperBuyerOrder is findable via `/orders/{id}` show + appears in `/orders/list` partial HTML
- ☑ POST `/orders/{id}/cancel` returns `{status:'success'}`; the WC order (resolved via `WordpressConnectionFacade::setWordpress($sbOrder->customer->wordpress())` then `Order::find($sbOrder->wc_order_id)->sellerCancel()`) is force-deleted in afterEach via `forceDeleteOrder`

> ⚠️ **Skipped (single-shop local fixture):**
> - **Multi-shop browsing assertions** — only one customer has a wordpress_endpoint on local, so cross-shop browsing collapses to one shop entry. The shop list + per-shop product list paths are verified, but multi-shop comparisons would need a fixture with ≥2 connected customers we don't have on local.
> - **RFQ checkout via super buyer** — covered indirectly by Phase 12 (which uses the same `vbrandsync /order/add` endpoint with `order_type=rfq` that the super buyer checkout calls). The plain `order_type=normal` path is what Phase 13 verifies end-to-end.

> **Notes:**
> - "Switch WP connection" — Super Buyer is **not session-scoped** to one shop. Each shop URL prefix carries `{shopUid}` and `WordpressConnectionFacade::setWordpress()` is set per request from that uid (see `ShopController` / `ProductController` / `CheckoutController` / `OrderController`). There is no "switch shop" toggle to test — the URL itself selects the shop.
> - The cancel endpoint uses `OrderStatusCatalog::resolveActions(...)` to look up an allowed `seller-cancel` action for the current `(base_status, rfq_status)` pair, returns 422 if cancel isn't allowed at the current state. We test the happy path (newly-placed `ordered` → cancellable → `seller_cancelled`).

---

## Phase 14 — Import Request ☑

> Reference: docs/rfq/IMPORT_REQUEST_DESIGN.md, USER_GUIDE_MOBILE §6
> Spec: `bots/automated/e2e/tests/phase14-import-request.spec.ts` — **4/4 pass** (2 tests × 2 projects).

**Webapp seller (`/brand/mobile/import-requests/...`):**
- ☑ Index page renders, no PHP error
- ☑ POST `/store` (`platform=shopee`, `shop_url=...`) → `{status:'success'}` JSON
- ☑ List partial includes the shop_url + "Mới" status badge
- ☑ uid extracted from list HTML via the `editRequest('uid')` / `deleteRequest('uid')` Alpine callbacks (the `_list` partial does NOT render `/edit` URLs — uses Alpine modal callbacks instead)

**Admin (`/admin/brand/import-requests/...`):**
- ☑ Index page renders, no PHP error
- ☑ List partial contains the new request row
- ☑ POST `/{uid}/update` (status=processing, imported_count=0) → < 400
- ☑ POST `/{uid}/update` (status=completed, imported_count=5) → < 400

**Cross-surface status sync:**
- ☑ Seller create → admin sees in list → admin → processing → seller list shows "Đang xử lý"
- ☑ Admin → completed → seller list shows "Hoàn thành"

**Cleanup strategy:** the seller-side delete endpoint only allows deletion while `status=new` (`Brand\Webapp\ImportRequestController@delete:65` `->where('status', ImportRequest::STATUS_NEW)`). As soon as the admin moves the request to `processing`, the seller can no longer remove it. Phase 14 cleans up via the **admin** delete endpoint in afterEach (admin delete has no status guard) using a fresh browser context so cleanup never tangles with the test's own cookies.

> **Notes:**
> - `admin@acm.com` is both seller AND admin on local (same as Phase 4.2). The cross-surface test uses 2 separate browser contexts so the seller and admin sessions don't interfere.
> - The webapp index page is at `/brand/mobile/import-requests` (not `/brand/mobile/products?action=import` as the user guide §6 might suggest — the form is its own surface).

---

## Phase 15 — Admin panel ☐

> Reference: SALES_HANDOVER §6.A (admin tổng)

- ☐ Login admin (`admin@sgconnect.vn`)
- ☐ Smoke các trang chính của admin (Customer / Site / Plan / Template / Order / Accounting)
- ☐ Approve 1 import request (link Phase 14)

---

## Phase 16 — Blog ☐

> Reference: SALES_HANDOVER §1 (Blog)

- ☐ Tạo bài blog (title + content)
- ☐ Tạo chuyên mục
- ☐ Edit / delete bài
- ☐ Verify hiển thị trên storefront

---

## Phase 17 — KB / Knowledge Base ☐

- ☐ Smoke admin KB CRUD (đã có Feature tests, chỉ smoke E2E)

---

## Cách giao tiếp với AI khác (resume)

Mỗi lần work xong 1 phase:
1. Sửa file này: đổi `☐` → `☑` trên các check đã pass
2. Đổi heading phase: `☐` → `☑` khi tất cả check trong phase done
3. Commit message: `test(e2e): phase N — <summary>`
4. AI tiếp theo đọc file này → biết phase nào còn ☐ → làm tiếp

## Convention

- Tất cả selector ưu tiên: `data-testid` > role/text > CSS class
- Khi page chưa có `data-testid`, dùng text Vietnamese từ user guide (vd: `getByRole('button', { name: 'Lưu' })`)
- Mỗi test phải **độc lập** — fixture tự cleanup (xóa product/order vừa tạo)
- Chạy trên 2 viewport: desktop 1280×800 và mobile 430×932 (iPhone 14 Pro)
- Screenshot on failure (mặc định Playwright)
