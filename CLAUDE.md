# vBrand — Claude Instructions

## Đọc trước khi làm bất cứ việc gì

Đọc `docs/VBRAND_SYSTEM_DOCUMENTATION.md` trước khi code — chứa toàn bộ design, architecture, patterns, API contracts.

## Quy tắc bắt buộc

1. **Luôn đọc docs trước** — không assume, không đoán
2. **Sau khi code xong** — update `docs/VBRAND_SYSTEM_DOCUMENTATION.md` nếu có thay đổi design/API/pattern
3. **Hỏi nếu không chắc** — hơn là code sai rồi sửa
4. **Minimal changes** — chỉ fix/adjust đúng yêu cầu, không refactor code xung quanh
5. **Follow existing patterns** — đọc code hiện tại trước, viết theo cùng style

## CLAUDE là owner của docs/ và bots/

`docs/**` và `bots/**` là **source of truth** của project — CLAUDE chịu trách nhiệm giữ chúng đúng và đầy đủ. Đây không phải là "tài liệu phụ", đây là **bộ nhớ dài hạn** của AI cho project này.

### Self-learn & discovery

- **Mỗi lần discover điều gì mới** (route lạ, bug ẩn, pattern khác thường, gotcha về local/server, credential, schema DB, lesson learned từ task) → **ghi ngay** vào file phù hợp trong `docs/` hoặc `bots/`. Không để kiến thức chết trong context window của 1 conversation.
- **Mỗi lần phát hiện docs sai/cũ/thiếu** → fix luôn trong cùng commit. Không để stale.
- **Mỗi lần fix bug có root cause đáng nhớ** → update `## Lessons Learned` trong CLAUDE.md hoặc thêm note vào doc liên quan.
- **Mỗi lần tìm ra route/controller/helper mới** mà chưa được document → bổ sung vào `docs/VBRAND_SYSTEM_DOCUMENTATION.md` hoặc relevant doc.

### Khi nào update docs/bots

| Trigger | Update file nào |
|---------|----------------|
| Đổi design/API/pattern | `docs/VBRAND_SYSTEM_DOCUMENTATION.md` + relevant design doc trong `docs/rfq/` |
| Thêm tính năng mới | Design doc + USER_GUIDE_DESKTOP/MOBILE nếu user-facing + SALES_HANDOVER nếu sales-facing |
| Fix bug có lesson | `CLAUDE.md ## Lessons Learned` + comment ở chỗ fix |
| Tìm ra workflow/command hữu ích | `bots/automated/<bot>.md` hoặc tạo bot mới |
| Discover route/schema chưa biết | `docs/VBRAND_SYSTEM_DOCUMENTATION.md` + nếu liên quan tới test → `docs/E2E_TEST_PLAN.md` |
| Hoàn thành 1 phase E2E | Mark ☑ trong `docs/E2E_TEST_PLAN.md` + ghi lessons vào CLAUDE.md nếu có |
| Tìm ra credential/setup mới cho local/staging/prod | `CLAUDE.md ## Server` hoặc relevant env section |

### Nguyên tắc

- **Docs là code** — review, commit, deploy như code
- **Không trùng lặp** — nếu thông tin đã có ở 1 file, link tới chứ đừng copy
- **Vietnamese OK** trong docs, code/comment giữ English
- **Luôn cụ thể** — `app/Http/Controllers/Brand/HomeController.php:42` chứ không phải "controller home"
- **Nếu thấy 2 docs nói khác nhau** → tìm ra đúng → fix file sai → ghi nhận trong commit message

## Project Structure

```
/app     → Laravel 12 (PHP) — brand app, admin, webapp, API
/site    → WordPress + WooCommerce — customer-facing sites
  /wp-content/plugins/vbrandsync/  → sync plugin (Laravel micro-app trong WP)
  /wp-content/themes/              → WP themes (logitech, vbrand-developer, ...)
/mobile  → React Native + Expo (TypeScript) — seller mobile app
/docs    → System documentation
/bots    → Automated task bots (xem phần Bots bên dưới)
```

### New Features (chưa implement — chỉ có design docs + migrations)

```
/app/app/Model/SuperBuyer.php          → Eloquent model (table: super_buyers)
/app/app/Model/SuperBuyerOrder.php     → Eloquent model (table: super_buyer_orders)
/app/app/Model/ImportRequest.php       → Eloquent model (table: import_requests)
```

**Design docs** (đọc trước khi implement, tất cả trong `docs/rfq/`):
- `docs/rfq/SUPER_BUYER_DESIGN.md` — Super Buyer webapp architecture, auth, WP connection switching, checkout flow, order management, API contracts
- `docs/rfq/RFQ_DESIGN.md` — RFQ order type, WooCommerce integration, approve flow, cross-platform UI (seller webapp + admin + API)
- `docs/rfq/RFQ_MOBILE_DESIGN.md` — RFQ seller mobile app update, TypeScript types, UI components, implementation checklist (7/8 done)
- `docs/rfq/IMPORT_REQUEST_DESIGN.md` — Import product request, seller/admin CRUD, status flow

**Super Buyer routes** (chưa tạo): `/brand/super-buyer/mobile/*` — orange theme, login riêng

## Git Remotes & Branches

| Component | Local path | Git remote | Branch | Deploy |
|-----------|-----------|------------|--------|--------|
| app | `/Users/luan/apps/vbrand/app` | `origin` (louisitvn/acellemail) | `brand` | SSH git pull |
| vbrandsync | `/Users/luan/apps/vbrand/site/wp-content/plugins/vbrandsync` | `origin` (luanpm88/vbrandsync) | `main` | rsync |
| themes | `/Users/luan/apps/vbrand/site/wp-content/themes` | `origin` (luanpm88/vbrand-themes) | `main` | rsync |
| mobile | `/Users/luan/apps/vbrand/mobile` | `origin` (luanpm88/vbrand-mobile) | `main` | EAS build (manual) |

Mỗi component là 1 git repo riêng → commit/push riêng.

## Site standardization (BẮT BUỘC cho tất cả vBrand sites)

**Quy tắc duy nhất:**
- **COD là payment method DUY NHẤT.** Mọi gateway khác (BACS, cheque, BaoKimVN, Stripe, ...) phải `enabled=no`.
- **vBrand Express là shipping method DUY NHẤT.** Phải attach vào "Rest of the world" zone (id=0). Mọi method khác phải xóa khỏi mọi zone.
- COD title hiển thị tiếng Việt: `Thanh toán khi nhận hàng`.

**Áp dụng tự động:**
- Script: `bots/automated/enforce-cod-vbrand-express.php` — idempotent, chạy được trên site đã setup hoặc fresh.
- Local: `wp eval-file bots/automated/enforce-cod-vbrand-express.php`
- Prod 1 site: `ssh vbrand@server "wp --path=/home/vbrand/sites/<dir> eval-file /tmp/enforce-cod-vbrand-express.php"`
- Prod tất cả: scp script lên `/tmp/`, loop qua DIR_NAME trong `bots/report/sites.md`.
- **`deploy-sites.md` phải chạy script này** sau khi sync plugin (xem bot file).
- **Bất kỳ bot nào tạo site mới** (clone WP, install fresh) **phải gọi script này** ở bước cuối — nếu không, checkout sẽ hiện BACS/cheque/BaoKimVN và customer chọn nhầm.

**Kiểm tra runtime:**
- E2E `phase4.1-storefront-checkout.spec.ts` verify từ phía customer (storefront → cart → checkout): chỉ thấy COD + vBrand Express.
- Manual: `wp eval 'foreach (WC()->payment_gateways->payment_gateways() as $id => $g) echo "$id:".$g->enabled."\n";'`

## Server

- **Production:** `18.141.199.175`
- **SSH:** `vbrand@` (app ops, rsync), `ubuntu@` (sudo)
- **App path:** `/home/vbrand/app` (branch: `brand`)
- **Sites path:** `/home/vbrand/sites/*/` — mỗi site = 1 WP instance
- **Sites registry:** `bots/report/sites.md` — danh sách tất cả sites
- **PHP-FPM socket:** `/var/run/php/php8.3-fpm.vbrand.sock` (dùng cho nginx)
- **Admin account:** `admin@sgconnect.vn` / `aA456321@`

## Lessons Learned (từ audit 2026-04-06)

### Clone WP site (không phải tạo mới từ 0)
Khi cần clone 1 WP site sang domain mới:
1. `mysqldump` DB cũ → `mysql` DB mới
2. `cp -r` thư mục WP
3. Sửa `wp-config.php` (DB_NAME, DB_USER, DB_PASSWORD)
4. `wp search-replace 'old-url' 'new-url' --all-tables` (chạy 2 lần: http→https rồi domain→domain)
5. Copy nginx config từ site đang chạy, sed đổi domain/path — **KHÔNG viết từ heredoc** (dễ lỗi escape `$`)
6. SSL: `certbot --nginx -d domain --non-interactive --agree-tos`
7. Sau SSL: `wp option update siteurl/home` thành https
8. Tạo customer trên brand app: Customer → User (user.customer_id = customer.id)
9. Generate API token trên WP: `wp eval` với `update_option('vbrandsync_api_token', ...)`

### Nginx config
- **LUÔN copy từ site đang chạy** (`cp + sed`) thay vì viết heredoc qua SSH — tránh lỗi escape `$uri`, `$args`
- PHP-FPM sock: `php8.3-fpm.vbrand.sock` (KHÔNG phải `php8.2-fpm.sock`)

### Brand app user-customer relationship
- Table `customers` KHÔNG có `user_id` — thay vào đó `users.customer_id` trỏ tới `customers.id`
- Tạo customer trước → lấy customer.id → set vào user.customer_id

### Admin login
- Admin email prod: `admin@sgconnect.vn` (đã đổi từ `admin@brandviet.vn`)
- Login route: `/login` (cùng route cho cả admin + customer)
- Sau login, HomeController check `config('app.brand')` → redirect `Brand\HomeController@index`
- Admin access policy: `UserPolicy@admin_access` check `!is_null($user->admin)`

### WooCommerce COD
- Bật COD: `update_option('woocommerce_cod_settings', array('enabled'=>'yes', ...))`
- Nên set title tiếng Việt: "Thanh toán khi nhận hàng"

### Sales docs & PDF workflow
- 3 tài liệu sales: `SALES_HANDOVER.md`, `USER_GUIDE_MOBILE.md`, `USER_GUIDE_DESKTOP.md`
- User Guide tách riêng Mobile (webapp `/brand/mobile/`) vs Desktop (full app `app.sgconnect.vn/login`) — Desktop có nhiều tính năng hơn (danh mục, thuộc tính, kho, vận chuyển, doanh thu, cấu hình nội dung)
- PDF gen: `npx md-to-pdf <file>.md` — cần có chromium, chạy trong `docs/`
- Drive versioning: luôn giữ **v1** — khi update thì đè file v1 luôn, KHÔNG tăng version number
- `docs/drive_shared/` chỉ chứa 3 file: `SALES_HANDOVER_v1.pdf`, `USER_GUIDE_MOBILE_v1.pdf`, `USER_GUIDE_DESKTOP_v1.pdf`
- rclone sync đè lên Google Drive — file cũ tự bị replace

### vbrandsync `require_once` returns true on 2nd call
- `plugin.php` `vbrandsync_getResponse()` was doing `$app = require_once 'bootstrap/app.php'`. PHP semantics: `require_once` returns the file's value **only on first include**, then returns `bool(true)` on subsequent calls. Result: any 2nd caller in the same WP request crashed with `Call to a member function make() on true`.
- This is hit on EVERY product/order/category create or update because `theme.php → vbrand_load_theme_data()` autoloads Laravel via the same function before the REST handler runs.
- Fix: cache `$app` and `$beemail_kernel` in static vars on first call, reuse for the rest of the WP request lifecycle. See `site/wp-content/plugins/vbrandsync/plugin.php`.
- Discovered by E2E Phase 2 (product create flow).

### Webapp form field names diverge from desktop
- Desktop product form posts `description` / `discount_price` / `category_ids[]`
- Mobile webapp product form posts `content` / `sale_price` / `categories[]`
- `Acelle\Wordpress\Product::fillParams` originally only accepted the desktop names → mobile webapp silently dropped description/sale price/categories on save.
- Fix: accept both names with `?? alias` in fillParams. Don't rename forms — both are user-visible and the controller is the right place to normalize.

### Gateway constructor crashes if settings array is partial
- `wordpress/payment.php` `BaoKimVN` constructor read `$this->settings['title']/['description']/['merchant_id']/['redirect_page_id']` directly without `??` defaults.
- The BaoKimVN gateway is registered on every WP request via the `woocommerce_payment_gateways` filter, so its constructor runs on every request — including REST API endpoints.
- The `enforce-cod-vbrand-express.php` script wrote `{enabled: no}` into the BaoKimVN settings option, blowing away the other keys → constructor crashed → REST API died globally.
- Fix: defensive `?? ''` defaults in the constructor + the enforce script now seeds defaults from `$gateway->get_form_fields()` instead of writing a partial array.
- **Lesson:** any class that's instantiated on every WP request must read its own option with null-coalescing. Never trust that a settings option contains all the keys init_form_fields would have populated.
- Discovered while writing E2E Phase 4.1 (storefront checkout standardization).

### Login helpers must skip when already authenticated
- `loginDesktop`, `loginWebapp`, `loginAdmin` originally always navigated to `/login` and tried to fill the email input. But once a browser context is authenticated, hitting `/login` redirects to the dashboard → no email input → `locator.fill` times out.
- Phase 4.2 (customer → seller → admin chain in one test) hit this immediately when calling `loginWebapp` after `loginDesktop` against the same user.
- Fix: shared `loginVia()` checks if the page redirected away from the login URL after the initial `goto`. If yes → already authenticated, no-op. Added `forceLogout()` for the rare case a test wants to switch users mid-flight.
- **Lesson:** any "login" helper called more than once per test must be idempotent. Same applies to seeding fixtures — always check current state first.

### vbrandsync `order/delete` was a no-op
- `vbrandsync_ajax_order_delete()` was an empty function body. Only the `/order/delete` (no id) route was registered. But brand-app `Acelle\Wordpress\Order::URI_DELETE = 'order/delete/{id}'` calls a different URL entirely.
- Result: every "delete order" call from the brand-app silently 404'd (or hit the empty handler), orphan WC orders accumulated, and there was no way to clean up after E2E.
- Fix: implemented the handler (force `wc_get_order($id)->delete(true)`) and registered both `/order/delete` (legacy, id in body) and `/order/delete/(?P<id>\d+)` (path, what brand-app actually calls). See `site/wp-content/plugins/vbrandsync/wordpress/api/order.php`.
- Discovered by E2E Phase 4 cleanup.

### Order workflow has 4 steps, not 5 — user guides were aspirational
- `USER_GUIDE_DESKTOP §5.4` and `USER_GUIDE_MOBILE §3` listed 5 status transitions: Xác nhận → Đóng gói → Đang giao → **Đã giao** → Hoàn thành.
- The "Đã giao" step is **broken end-to-end and never worked**:
  1. `vbrandsync` `Order` model has no `setDelivered()` method and no `STATUS_DELIVERED` const
  2. `vbrandsync/plugin.php` does not register `wc-delivered` as a custom WC post status (the others — packaging, ready_for_pickup, delivering, etc — are all registered)
  3. brand-app `Acelle\Wordpress\Order` has no `setDelivered()` either
  4. `OrderStatusCatalog::actionUrls['store']['set-delivered']` points at `Store\OrdersController@setComplated` — a method that does not exist (the route is registered as `complated` typo too)
  5. No "Đã giao" button is rendered in any current store/orders or webapp/orders blade
- Production sellers go straight from `delivering` → `completed` (the 4-step workflow). Per CLAUDE rule "if 2 docs disagree → find the right one → fix the wrong one", the user guides are wrong; the app is right. **Fixed by updating both user guides** to the 4-step workflow with status names in parens.
- The dead `setComplated` plumbing in `OrderStatusCatalog`, `routes/brand.php`, and the webapp `setDelivered` controller method is left in place pending a separate decision on whether to delete it or implement Đã giao properly.
- Lesson: when adding "expected" features to user guides, make sure the wire goes all the way through — controller, model, REST handler, custom WC status registration. A broken intermediate step is invisible until somebody actually clicks the button.

### redirect()->action() with resource routes needs the resource param name
- `Brand\ArticleCategoryController@store` was doing `redirect()->action('@edit', ['category' => $id])`. The category was successfully created in WP, but the redirect crashed with `UrlGenerationException: Missing parameter article_category` because the resource route registered the URL as `/website/article-category/{article_category}/edit`. Production sellers got a 500 every time they created a blog category, even though the category was actually created.
- Fix: pass `'article_category' => $category->id` instead of `'category'`. The param key must match the resource binding name (Laravel infers it from the resource segment).
- Discovered by E2E Phase 16 (blog category create test).
- **Lesson:** when calling `redirect()->action()` for a controller that's bound via `Route::resource(...)`, the param key in the args array must match the resource segment name (singularized + snake_cased), NOT a friendly alias. If a singular param like `category` gets resolved to `article_category`, the resource binding takes precedence over any explicit GET route registered earlier.

### OrderStatusCatalog::prefixed silently broke every multi-word status filter
- `OrderStatusCatalog::prefixed()` was doing `'wc-' . str_replace('_', '-', $normalized)`. WC custom statuses are registered with **underscores** in the vbrandsync plugin (`wc-rfq_pending`, `wc-ready_for_pickup`, `wc-seller_cancelled`, etc — see `site/wp-content/plugins/vbrandsync/plugin.php` `register_post_status` calls). The hyphen variant matched zero orders, so every multi-word status filter (RFQ-pending tab, ready-for-pickup tab, seller-cancelled tab) was silently empty on webapp / desktop / brand-api / admin order lists.
- Fix: drop the `str_replace` in `app/Support/OrderStatusCatalog.php` `prefixed()`.
- Discovered by E2E Phase 12 (RFQ filter test). Phase 4 didn't catch it because the order workflow tests hit per-id action endpoints, not the status-filter list.
- **Lesson:** when designing a status normalization layer, the format must round-trip exactly to whatever the storage layer (here: WC `register_post_status`) actually uses. A "prettifying" `_` → `-` translation in the prefix function ≠ a renaming of the underlying status. Always test the filter end-to-end against a fixture in that status.

### resources/views/store/orders/list.blade.php was structurally broken
- Commit `0a22389ce5` ("graceful WordPress connection error handling") accidentally replaced the inner `@foreach($orders as $order) @php` block with a stray `<script>` tag, dropped the foreach entirely, and left an orphan `@endforeach` plus a duplicate empty-state. Result: every visit to `/store/orders/list` (the AJAX partial used by the desktop orders index) crashed with a Blade syntax error.
- Phase 4 desktop tests dodged this because they hit per-id action endpoints, not the list partial. Phase 12 surfaced it because the desktop RFQ filter test hits `/store/orders/list?status=rfq-pending`.
- Fix: restore the `@if(!$ordersIsEmpty) @foreach @php ... @endphp <div>...</div> @endforeach @endif` wrapper from the pre-broken commit `d970d53e73`. Drop the duplicate empty-state.
- **Lesson:** Blade compile errors don't surface until the view actually renders. A view that's never hit on the happy path can stay broken indefinitely. When refactoring a partial, run a smoke test that hits the AJAX endpoint before considering the change done.

### Attribute create form name input is readonly
- `resources/views/store/attributes/_form.blade.php` is shared by create + edit. The `name` input was hardcoded `<input readonly>`, which is correct for edit (WP attribute slugs cannot be renamed) but blocks create entirely — user can't type a name → form fails `name required` validation.
- Fix: only apply `readonly` when `$attribute->id` exists (edit mode). One-line `@if(!empty($attribute->id)) readonly @endif`.
- Discovered by E2E Phase 3 (attribute CRUD).
- Lesson: shared `_form.blade.php` between create + edit is convenient but easy to break — when adding a "feels read-only" attribute, always check if it should be edit-only.

### Theme builder schema có thể rỗng
- `Brand\WebsiteController@themeOptions` gọi `$customer->wordpress()->themeGetMeta()` → 1 số WP theme local trả về object không có key `sessions`/`options` → view `themeOptions.blade.php` crash với "Trying to access array offset on null"
- Fix: controller phải normalize `$schema['sessions'] ?? []` và `$schema['options'] ?? []` trước khi pass vào view
- Phát hiện qua E2E Phase 1 — đây chính là lý do E2E test cần chạy với seller có WP connection thật (`admin@acm.com` local)

### WooCommerce Coming Soon mode hides every shop page on fresh sites
- WC 8.x ships with `woocommerce_coming_soon=yes` ON by default on every fresh install. While it's on, `/shop/`, the configured shop page (vd `/thuc-don/`), AND every product detail page get replaced server-side with the WC "Great things are on the horizon" placeholder. The product import API still succeeds and the store API still returns products — only the customer-facing storefront is blanked out, so it looks like the import failed even though it didn't.
- Affected guucoffee.b-teka.com after the duc-anh-coffee import: 26 products in DB, REST API returned them, but `/thuc-don/` rendered the placeholder. Took longer than it should have to diagnose.
- Fixes (defense in depth):
  1. **Preventive:** `bots/automated/enforce-cod-vbrand-express.php` now also writes `woocommerce_coming_soon=no` + `woocommerce_store_pages_only=no`. Idempotent. Every bot that creates a new site already calls this script as the last step → bug cannot recur on new sites.
  2. **Detective:** `bots/scrape/scripts/import-to-woocommerce.js` post-import sanity check fetches the homepage and warns loudly if the "Great things are on the horizon" placeholder is detected, with the exact `wp option update` commands to fix.
- **Lesson:** API-level "import succeeded" verification is not enough — always do at least one HTTP fetch of the public storefront after import. Coming-soon mode is invisible to REST checks. Any future site-creation bot must enforce coming-soon=no, not just COD/shipping.

### Lazada DOM-fallback price string is parsed as decimal by WC, losing 1000×
- When the Lazada API params can't be captured (small shops without Mall pagination), `scripts/scrape-lazada-shop.js` falls back to DOM scraping. DOM-fallback `products.json` has `price` as a localized string like `"₫ 450.000"` instead of an integer.
- `scripts/import-to-woocommerce.js` was passing `p.price` straight through to `/import/product`. WooCommerce parses `"₫ 450.000"` with `.` as decimal separator (not VND thousand separator) → stores `450` VND instead of `450,000` VND. Every DOM-fallback Lazada import had prices 1000× too small.
- API-mode scrape was unaffected because it sets `priceRaw` (number) and we used `p.price || p.priceRaw`, but `p.price` short-circuits to the string first.
- Fix: `toIntPrice()` helper in `import-to-woocommerce.js` strips all non-digits (`/[^\d]/g`) before sending. Handles both number and string inputs.
- **Lesson:** when a scraper has 2 modes (API vs DOM fallback) the downstream consumer must normalize. Don't trust that "price" means the same type across modes. Even better: scraper itself should always emit a numeric `priceRaw` regardless of mode.

### curl test webapp login (không cần browser)
- Phải lấy session cookie trước (`GET /brand/mobile/login` → extract `Set-Cookie`)
- Extract CSRF token từ HTML (`grep _token`)
- POST login KHÔNG follow redirect (`-w "%{http_code}" -o /dev/null`)
- Sau đó GET dashboard riêng với session cookie
- Nếu follow redirect từ POST 302 → curl POST lại URL mới → 405 Method Not Allowed

## Architecture (3-layer API chain)

```
Mobile App / Webapp → Laravel API → WordPress REST API (vbrandsync plugin) → WooCommerce
```

- Laravel app là trung tâm — quản lý tất cả qua API tới WordPress
- vbrandsync plugin expose REST endpoints cho Laravel gọi (products, orders, attributes, themes, ...)
- Mobile app gọi Laravel API (không gọi WP trực tiếp)

---

## Automated Bots System

Hệ thống tự động hóa fix bug + implement feature + tests + deploy.
Issues/features được track trên GitHub repo **`luanpm88/vbrand-hub`**.

### Cách hoạt động

```
User report bug/feature → GitHub Issues [vbrand, status:new] trên luanpm88/vbrand-hub
        ↓
  Bug/adjustment → do-one-task.md     Feature mới → dev-feature.md
   ├── fetch + claim task               ├── fetch + claim task
   ├── đọc docs + code → fix            ├── đọc design doc (docs/rfq/)
   ├── commit + push (từng repo)        ├── implement step-by-step
   ├── auto deploy                      ├── commit từng component ngay
   ├── verify production                ├── viết Unit + Feature + Dusk tests
   ├── update issue → deployed          ├── update docs
   └── tạo report                       ├── deploy + verify
                                        ├── update issue → deployed
                                        └── tạo report
```

### Bot files

| Bot | File | Mô tả |
|-----|------|--------|
| Do One Task | `bots/automated/do-one-task.md` | Fix bug/adjustment từ issue — auto deploy |
| Dev Feature | `bots/automated/dev-feature.md` | Implement feature với tests + docs — bot mới |
| Write Tests | `bots/automated/write-tests.md` | Viết Unit/Feature/Dusk tests cho 1 area |
| Deploy App | `bots/automated/deploy-app.md` | Deploy brand Laravel app lên server |
| Deploy Sites | `bots/automated/deploy-sites.md` | Sync themes + vbrandsync plugin lên WP sites |
| Deploy Mobile | `bots/automated/deploy-mobile.md` | Commit + push mobile (không build) |
| Design Doc | `bots/automated/DESIGN_USAGE_PROMPTS.md` | Architecture + usage guide chi tiết |
| Scrape (master) | `bots/scrape/bot-scrape.md` | Entry point — dispatch sang lazada/shopee, standard format |
| Import WooCommerce | `bots/scrape/bot-import-woo.md` | Import `shops/` data vào WooCommerce (any source) |
| Scrape Lazada | `bots/scrape/scrape-lazada-shop.md` | Scrape Lazada Mall shop → `shops/` standard format |
| Scrape Shopee | `bots/scrape/scrape-shopee-shop.md` | Scrape Shopee shop qua HTML mode (copy từ DevTools) |
| Clone Full Site | `bots/scrape/full-site/scraper.md` | Clone/copy website thành standalone PHP site |
| Sync Drive | `bots/sync-drive.md` | Sync `docs/drive_shared/` lên Google Drive (`luanpm88:vBrand_Shared/SGCONNECT/`) |

### Usage — Cách gọi bots

```bash
# Fix bug/adjustment mới nhất
bots/automated/do-one-task.md
bots/automated/do-one-task.md issue 42

# Implement feature mới nhất (với tests + docs)
bots/automated/dev-feature.md
bots/automated/dev-feature.md issue 42
bots/automated/dev-feature.md issue 42 --no-deploy  # implement only, no deploy

# Viết tests cho 1 area
bots/automated/write-tests.md rfq
bots/automated/write-tests.md super-buyer
bots/automated/write-tests.md import-request

# Fix tất cả tasks đang chờ (loop)
# User nói: "loop do-one-task" → lặp cho đến hết status:new

# Xem danh sách tasks
bots/automated/do-one-task.md list
bots/automated/do-one-task.md list all

# Deploy riêng (không cần issue)
bots/automated/deploy-app.md
bots/automated/deploy-sites.md sync all
bots/automated/deploy-sites.md sync nike.b-teka.com
```

### Khi user nói ngắn gọn

User có thể nói ngắn — Claude phải tự hiểu và chạy đúng bot:

| User nói | Claude làm |
|----------|-----------|
| `do-one-task` hoặc `fix task mới` | Chạy `do-one-task.md` |
| `fix issue 26` hoặc `sửa issue 26` | Chạy `do-one-task.md issue 26` |
| `dev feature` hoặc `implement feature` | Chạy `dev-feature.md` |
| `implement issue 42` hoặc `làm issue 42` | Chạy `dev-feature.md issue 42` |
| `write tests rfq` hoặc `viết tests super-buyer` | Chạy `write-tests.md <area>` |
| `sửa hết tasks` hoặc `loop do-one-task` | Lặp `do-one-task.md` cho đến hết `status:new` |
| `deploy app` | Chạy `deploy-app.md` |
| `deploy sites` | Chạy `deploy-sites.md sync all` |
| `list tasks` hoặc `xem tasks` | Chạy `do-one-task.md list` |
| `bug mới nhất ...` + context | Chạy `do-one-task.md` |
| `scrape lazada <url>` | Chạy `bot-scrape.md --type=lazada --url=...` |
| `scrape shopee <shop>` | Hướng dẫn copy HTML → chạy `bot-scrape.md --type=shopee` |
| `import <shop> vào <site>` | Chạy `bot-import-woo.md import <site> shops/<shop>/ --clean` |
| `scrape + import <url> vào <site>` | Scrape (bot-scrape) rồi import (bot-import-woo) |
| `clone site mailchimp.com` | Chạy `scraper.md clone https://mailchimp.com` |
| `clone site giống X` | Tìm site phù hợp → chạy `scraper.md clone` |
| `update mailchimp fix menu` | Chạy `scraper.md update mailchimp` |
| `audit mailchimp` | Screenshot + review site mailchimp |
| `export sales handoff` hoặc `xuất pdf sales` | Gen 3 PDF (xem quy trình bên dưới) → copy vào `docs/drive_shared/` (tăng version) |
| `sync drive` hoặc `đẩy lên drive` | Chạy `rclone sync docs/drive_shared/ luanpm88:vBrand_Shared/SGCONNECT/ --progress` |
| `export sales handoff và sync drive` | Gen 3 PDF + copy drive_shared + rclone sync — full pipeline |

### Quy trình release 3 PDF sales

Khi user nói `export sales handoff` hoặc tương tự, chạy **đúng 4 bước** sau:

```bash
# 1. Gen 3 PDF
cd docs
npx md-to-pdf SALES_HANDOVER.md
npx md-to-pdf USER_GUIDE_MOBILE.md
npx md-to-pdf USER_GUIDE_DESKTOP.md

# 2. Copy vào drive_shared/ (luôn đè v1 — KHÔNG tăng version)
cp docs/SALES_HANDOVER.pdf docs/drive_shared/SALES_HANDOVER_v1.pdf
cp docs/USER_GUIDE_MOBILE.pdf docs/drive_shared/USER_GUIDE_MOBILE_v1.pdf
cp docs/USER_GUIDE_DESKTOP.pdf docs/drive_shared/USER_GUIDE_DESKTOP_v1.pdf

# 3. Sync lên Google Drive
rclone sync docs/drive_shared/ luanpm88:vBrand_Shared/SGCONNECT/ --progress

# 4. (Nếu user yêu cầu) Commit + push
```

**3 file PDF:**
| File MD | PDF output | Nội dung |
|---------|-----------|----------|
| `docs/SALES_HANDOVER.md` | `SALES_HANDOVER_v1.pdf` | Tài liệu bàn giao sales |
| `docs/USER_GUIDE_MOBILE.md` | `USER_GUIDE_MOBILE_v1.pdf` | Hướng dẫn Webapp (điện thoại) |
| `docs/USER_GUIDE_DESKTOP.md` | `USER_GUIDE_DESKTOP_v1.pdf` | Hướng dẫn Desktop (máy tính) |

**Google Drive:** `luanpm88:vBrand_Shared/SGCONNECT/` (rclone remote `luanpm88`)

### Label conventions

**Tạo issue chỉ cần:** label `vbrand` + `status:new` + mô tả — bot tự phân loại type + component.

**Type labels** (bot gán, commit prefix):
- `type:bug` → `fix:` | `type:adjustment` → `adjust:` | `type:feature` → `feat:`
- `type:style` → `style:` | `type:perf` → `perf:`

**Component labels** (bot gán): `component:app`, `component:vbrandsync`, `component:themes`, `component:mobile`

**Status labels** (bot quản lý): `status:new` → `status:in-progress` → `status:deployed` / `status:failed`

### Reports

Mỗi task tạo report: `bots/automated/reports/task-N.md` — chứa root cause, changes, commits, deploy status, revert command.

---

## Testing

### Stack

| Type | Framework | Path | Run command |
|------|-----------|------|-------------|
| Unit | Pest PHP | `app/tests/Unit/` | `./vendor/bin/pest tests/Unit/` |
| Feature/HTTP | Pest PHP | `app/tests/Feature/` | `./vendor/bin/pest tests/Feature/` |
| Browser (headless) | Laravel Dusk | `app/tests/Browser/` | `php artisan dusk` |
| Browser (visible) | Laravel Dusk | `app/tests/Browser/` | `DUSK_HEADLESS_DISABLED=true php artisan dusk` |
| **E2E (cross-platform)** | **Playwright + TS** | `bots/automated/e2e/tests/` | `cd bots/automated/e2e && npm test` |

### E2E Playwright suite (mọi web platform trừ mobile app)

**Mục tiêu:** verify mọi tính năng mô tả trong `docs/SALES_HANDOVER.md` + `docs/USER_GUIDE_DESKTOP.md` + `docs/USER_GUIDE_MOBILE.md` đều chạy đúng — local trước, sau đó staging/prod.

**Plan (single source of truth):** [`docs/E2E_TEST_PLAN.md`](docs/E2E_TEST_PLAN.md) — checklist 17 phases, dùng `☐ pending / ◐ in-progress / ☑ done` để track tiến độ.

**Project layout:**
```
bots/automated/e2e/
├── package.json              # @playwright/test
├── playwright.config.ts      # 2 projects: desktop 1280×800, mobile iPhone 14 Pro
├── helpers/auth.ts           # loginDesktop, loginWebapp, assertNoPageErrors
├── tests/phase1-auth-smoke.spec.ts   # ☑ done
├── tests/phase2-products.spec.ts     # ☐ todo
└── README.md
```

**Targets (env vars, default = local):**
| Var | Default |
|-----|---------|
| `BASE_APP` | `http://brand.test` |
| `BASE_SITE` | `http://brand-site.test` |
| `SELLER_EMAIL` | `admin@acm.com` (chỉ user này có WP connection local → `brand-site.test`) |
| `SELLER_PASSWORD` | `123456` |
| `ADMIN_EMAIL` | `admin@sgconnect.vn` |
| `ADMIN_PASSWORD` | `aA456321@` |

**Khi user nói "continue test" / "tiếp tục test e2e" / "làm phase tiếp":**
1. Đọc `docs/E2E_TEST_PLAN.md`, tìm phase đầu tiên còn `☐` (hoặc `◐`)
2. Đọc reference docs phase đó nói tới (vd `USER_GUIDE_DESKTOP §5.1`)
3. Tạo file `bots/automated/e2e/tests/phaseN-<name>.spec.ts`
4. Follow conventions: `data-testid` > role/text > CSS, dùng helpers `loginDesktop`/`loginWebapp`/`assertNoPageErrors`, mỗi test self-cleanup
5. Chạy `cd bots/automated/e2e && npx playwright test tests/phaseN-*.spec.ts --project=desktop` rồi `--project=mobile`
6. Fix tới khi xanh hết:
   - Nếu test sai → fix test
   - **Nếu app/site có bug thật → fix luôn** (minimal change), commit riêng `fix:` cho component bị ảnh hưởng, ghi root cause + line number
7. **Auto-deploy** sau khi xanh hết phase (KHÔNG cần hỏi user — đây là explicit policy):
   - Có fix trong `app/` → chạy `bots/automated/deploy-app.md`
   - Có fix trong `site/wp-content/{plugins,themes}/` → chạy `bots/automated/deploy-sites.md sync all`
   - Sau deploy, re-run phase đó với `BASE_APP=https://app.sgconnect.vn` để verify production xanh
8. Update `docs/E2E_TEST_PLAN.md`: đổi `☐` → `☑` cho từng check + heading phase. Ghi rõ bug nào đã fix + commit hash
9. Update `## Lessons Learned` trong CLAUDE.md nếu bug có root cause đáng nhớ (vd: WP theme schema null, missing route, broken middleware)
10. Commit: `test(e2e): phase N — <summary>` (gồm spec + plan + lessons)

**Khi user nói "run e2e" / "test e2e":**
- Default chạy local: `cd bots/automated/e2e && npm test`
- Chạy phase cụ thể: `npx playwright test tests/phaseN-*.spec.ts`
- Staging: `BASE_APP=https://app.sgconnect.vn BASE_SITE=https://logitech.b-teka.com SELLER_EMAIL=logitech@gmail.com npm test`

**Setup local (one-time):**
```bash
cd bots/automated/e2e && npm install && npx playwright install chromium webkit
# Đảm bảo seller test có WP connection (chỉ admin@acm.com mặc định):
cd /Users/luan/apps/vbrand/app && php artisan tinker --execute='
$u=\Acelle\Model\User::where("email","admin@acm.com")->first();
$u->password=bcrypt("123456"); $u->save(); echo "ok";'
```

**Convention quan trọng:**
- Mỗi test phải **độc lập** + tự cleanup fixture (xóa product/order vừa tạo trong same test)
- Selector ưu tiên `data-testid` > Vietnamese text từ user guide > CSS class
- 2 viewports: desktop 1280×800 + mobile 430×932 (iPhone 14 Pro) — match Dusk
- Khi route chưa biết, grep `routes/web.php` + `routes/brand.php` (KHÔNG `php artisan route:list` — broken bởi BaokimController)

### DuskTestCase helpers (`app/tests/DuskTestCase.php`)

- `loginAsCustomer($browser)` — login as seller (user với customer relationship)
- `loginAsSuperBuyer($browser)` — login as Super Buyer (**cần thêm khi implement Super Buyer**)
- `assertNoPageErrors($browser, 'Name')` — kiểm tra không có PHP error trong page source
- `assertNoAjaxErrors($browser, '#selector', 'Context')` — kiểm tra không có error trong AJAX response

**Viewport:** iPhone 14 Pro (430×932) — tất cả Dusk tests chạy ở mobile viewport

### Test structure cho new features

```
tests/
├── Unit/
│   ├── RfqOrderTest.php          ← RFQ business logic
│   ├── SuperBuyerTest.php        ← SuperBuyer/SuperBuyerOrder models
│   └── ImportRequestTest.php    ← ImportRequest model + statuses
├── Feature/
│   ├── RfqApiTest.php            ← approve-rfq routes + auth
│   ├── SuperBuyerRouteTest.php   ← Super Buyer routes + auth
│   └── ImportRequestApiTest.php ← Import Request CRUD API
└── Browser/
    ├── Webapp/
    │   └── RfqOrdersTest.php     ← RFQ badge, filter tab, approve flow
    ├── SuperBuyer/
    │   ├── SuperBuyerSmokeTest.php ← All Super Buyer pages
    │   └── CheckoutTest.php      ← Checkout + order creation flow
    └── Admin/
        └── ImportRequestTest.php ← Admin manage import requests
```

### Khi implement feature mới → viết tests ngay

Xem `bots/automated/write-tests.md` để biết patterns và commands.

---

## Workflow chung

1. Nhận yêu cầu từ user (hoặc GitHub Issue từ `luanpm88/vbrand-hub`)
2. Đọc `docs/VBRAND_SYSTEM_DOCUMENTATION.md` + design doc liên quan (`docs/rfq/`)
3. Đọc code hiện tại, hiểu patterns
4. Code — minimal, follow patterns
5. Viết tests (Unit + Feature + Dusk) ngay trong cùng session
6. Commit + push từng component riêng
7. Deploy nếu cần
8. Update `docs/VBRAND_SYSTEM_DOCUMENTATION.md` nếu có thay đổi design/API
