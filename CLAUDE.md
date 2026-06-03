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

After 2026-06-03 cutover, local workspace structure:
```
~/apps/acelle/          → mainline acelle codebase (/rui UI, brand plugin loaded)
~/apps/acelle_brand/    → brand plugin source (symlinked → acelle/storage/app/plugins/acelle/brand)
~/apps/vbrand/          → LEGACY — only kept for rollback reference (do not edit)
~/apps/vbrand_sites/    → WordPress customer sites (WooCommerce + vbrandsync plugin)
```

Old layout (pre-cutover, retired — kept here for historical reference of the legacy fork):
```
/acelle  → Laravel 12 (mainline acelle codebase, ~/apps/acelle)
  /storage/app/plugins/acelle/brand  → brand plugin (symlink from ~/apps/acelle_brand)
  /resources/views/rui/brand/*       → brand customer UI (/rui/brand/home, connection, etc.)
/wp-sites → WordPress + WooCommerce (customer sites)
  /wp-content/plugins/vbrandsync/    → sync plugin (unchanged, talks to acelle/brand)
  /wp-content/themes/                → WP themes (logitech, vbrand-developer, ...)
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
| acelle (mainline) | `/Users/luan/apps/acelle` | `origin` (upstream acelle repo) | `develop` or release branch | deploy as app-new → symlink |
| brand plugin | `/Users/luan/apps/acelle_brand` | `origin` (brand plugin repo) | relevant branch | rsync into storage/app/plugins/acelle/brand |
| vbrandsync | `/Users/luan/apps/vbrand/site/wp-content/plugins/vbrandsync` | `origin` (luanpm88/vbrandsync) | `main` | rsync |
| themes | `/Users/luan/apps/vbrand/site/wp-content/themes` | `origin` (luanpm88/vbrand-themes) | `main` | rsync |
| mobile | `/Users/luan/apps/vbrand/mobile` | `origin` (luanpm88/vbrand-mobile) | `main` | EAS build (manual) |
| ~~kb~~ | ~~`/Users/luan/apps/vbrand/kb`~~ | **Consolidated 2026-05-09** into `acellemail/landing/` (subfolder `acellemail.com/kb/*`). Standalone `kb/` folder removed; remote repo `luanpm88/acelle-knowledge-base` archived. KB content lives in `acellemail/landing/database/seeders/Article*Seeder.php`. See [`acellemail/landing/docs/SEO_PLAN_KB.md`](acellemail/landing/docs/SEO_PLAN_KB.md). | — | — |

Mỗi component là 1 git repo riêng → commit/push riêng.

## Site standardization (BẮT BUỘC cho tất cả vBrand sites)

**Quy tắc duy nhất:**
- **COD là payment method DUY NHẤT.** Mọi gateway khác (BACS, cheque, BaoKimVN, Stripe, ...) phải `enabled=no`.
- **vBrand Express là shipping method DUY NHẤT.** Phải attach vào "Rest of the world" zone (id=0). Mọi method khác phải xóa khỏi mọi zone.
- COD title hiển thị tiếng Việt: `Thanh toán khi nhận hàng`.

**Áp dụng tự động:**
- Script: `bots/automated/enforce-cod-vbrand-express.php` — idempotent, chạy được trên site đã setup hoặc fresh.
- Local: `wp eval-file bots/automated/enforce-cod-vbrand-express.php`
- Prod 1 site: `ssh vbrand@server "wp --path=/home/<DIR_NAME>/wordpress eval-file /tmp/enforce-cod-vbrand-express.php"`
- Prod tất cả: scp script lên `/tmp/`, loop qua DIR_NAME trong `bots/report/sites.md`.
- **`deploy-sites.md` phải chạy script này** sau khi sync plugin (xem bot file).
- **Bất kỳ bot nào tạo site mới** (clone WP, install fresh) **phải gọi script này** ở bước cuối — nếu không, checkout sẽ hiện BACS/cheque/BaoKimVN và customer chọn nhầm.

**Kiểm tra runtime:**
- E2E `phase4.1-storefront-checkout.spec.ts` verify từ phía customer (storefront → cart → checkout): chỉ thấy COD + vBrand Express.
- Manual: `wp eval 'foreach (WC()->payment_gateways->payment_gateways() as $id => $g) echo "$id:".$g->enabled."\n";'`

## Server

- **Production:** `54.169.34.13` (Lightsail Singapore, Ubuntu 24.04.4 LTS, 1 vCPU / 911 MiB RAM + 4 GiB swap / 38 GB disk)
- **Migrated 2026-05-24** từ `52.220.55.112` (old) — see [`docs/SERVER_MOVE_PLAN.md`](docs/SERVER_MOVE_PLAN.md)
- **Old server:** `52.220.55.112` (trước là `18.141.199.175` — đổi IP trên AWS Lightsail). Giữ chạy ~14 ngày làm rollback. Acelle Mail stack (`/home/acelle`) chưa di chuyển — separate decision.
- **SSH:** `ubuntu@` (sudo, system ops, deploy-sites), `vbrand@` (brand app deploy — symlink swap, no git pull). Customer WP sites: mỗi site = 1 Linux user riêng (group `vbrand`); `vbrand` đọc được nhưng **không ghi** được vào `/home/<DIR_NAME>/wordpress` → WP write-ops chạy qua `ubuntu@` + `sudo -u <DIR_NAME>` (per-site users không có SSH key trực tiếp).
- **App path:** `/home/vbrand/app` → symlink to `/home/vbrand/app-new` (acelle mainline release). Legacy `/home/vbrand/app-legacy` preserved for rollback. Owner `vbrand:vbrand`, php-fpm pool `vbrand` = `/run/php/php8.3-fpm.vbrand.sock`
- **DB:** after 2026-06-03 cutover, the app uses the `brand` database. Legacy DB `vbrand` preserved for rollback. (The 'vbrand' mysql user has ALL PRIVILEGES on both `vbrand` and `brand`; it CANNOT create new databases). Rollback: switch app symlink + restore DB from backup.
- **Sites path:** `/home/<DIR_NAME>/wordpress` — mỗi customer = 1 Linux user riêng (group `vbrand`), 1 php-fpm pool socket `/run/php/php8.3-fpm.<DIR_NAME>.sock`
- **Sites registry:** `bots/report/sites.md` — danh sách đầy đủ (14 sites + brand app)
- **Stack:** nginx 1.24 + PHP 8.3.6-FPM + MySQL 8.0 + certbot (Let's Encrypt auto-renew). MySQL tuned: `innodb_buffer_pool_size=256M`, `max_connections=100`.
- **Pool mode:** brand app `dynamic` (max 8 workers, 2 spare). Per-site `ondemand` (max 4 workers, idle timeout 60s) — fits 14 sites in 911 MB.
- **Security:** `iptables` drop scanner IPs (45.88.138.44 blocked). nginx rate-limit hostile paths (`/.env`, `/.git`, ...). `request_terminate_timeout=60s` mỗi pool.
- **Admin account:** `admin@sgconnect.vn` / `aA456321@`
- **App updates (after 2026-06-03 cutover):** vbrand runs on acelle mainline + brand plugin. Refer user to `acelle/CLAUDE.md` for mainline app updates; `acelle_brand/CLAUDE.md` for plugin updates. Deploy app: build acelle release alongside, set `.env` (`DB_DATABASE=brand`, `APP_URL=https://app.sgconnect.vn`, keep legacy `APP_KEY`/mail), `composer install --no-dev`, `migrate:fresh` + seed `DatabaseInit` + `TemplateSeeder`, then `mv app app-legacy && ln -sfn app-new app` (no sudo needed — nginx `SCRIPT_FILENAME=$realpath_root` + opcache revalidate_freq=2). Rollback: `rm /home/vbrand/app && mv /home/vbrand/app-legacy /home/vbrand/app`.

**NOTE (2026-06-03 after cutover):** All sites use `/home/<DIR_NAME>/wordpress/` (mỗi customer 1 Linux user). Old server (52.220.55.112) had a different structure — that setup is now obsolete.

## Lessons Learned (từ audit 2026-04-06)

### Clone WP site (không phải tạo mới từ 0)
Khi cần clone 1 WP site sang domain mới:
1. `mysqldump` DB cũ → `mysql` DB mới
2. `cp -r` thư mục WP
3. Sửa `wp-config.php` (DB_NAME, DB_USER, DB_PASSWORD)
4. **Sửa `wp-content/plugins/vbrandsync/.env`** (DB_DATABASE, DB_USERNAME, DB_PASSWORD) — vbrandsync là Laravel micro-app riêng, có `.env` độc lập với wp-config. Nếu skip, mọi REST endpoint vbrandsync sẽ 500.
5. `wp search-replace 'old-url' 'new-url' --skip-columns=guid --all-tables` (chạy 2-3 lần: https://old → http://new, http://old → http://new, rồi domain-only — vì siteurl chưa SSL)
6. **Set tạm `wp option update siteurl/home http://newdomain`** trước khi cài SSL — certbot cần HTTP để pass challenge
7. Copy nginx config từ site đang chạy, sed đổi domain/path — **KHÔNG viết từ heredoc** (dễ lỗi escape `$`). Initial config dùng `listen 80` only — certbot sẽ tự thêm 443 + redirect.
8. SSL: `certbot --nginx -d domain --non-interactive --agree-tos --email <email> --redirect`. **Bỏ `-d www.<domain>`** nếu DNS www chưa trỏ — sẽ fail NXDOMAIN, kill cả cert. Add www sau khi DNS sẵn sàng.
9. Sau SSL: `wp option update siteurl/home https://...` + `wp search-replace http://new https://new --skip-columns=guid --all-tables`
10. **Reset vbrandsync settings cho customer mới** — DB clone copy luôn `wp_vbs_settings.vbrand_token` của customer cũ. Phải tạo customer mới trên brand app rồi `Setting::set('vbrand_token', $newApiToken)` qua tinker bên trong vbrandsync plugin.
11. Tạo customer trên brand app: `App\Services\AccountManagement\AccountProvisioningService::createCustomer(...)` → create brand_site_connection via /rui/brand connection screen using `ConnectionService::connect($customer, $endpoint, $auth_meta)` (không set customer->wordpress_endpoint — column đã remove).
12. Run `enforce-cod-vbrand-express.php` (COD only + vBrand Express + coming-soon=no) — bắt buộc
13. **Set `show_on_front=page`** trước khi public — fresh DB clones default `posts` → homepage hiện blog index thay vì template page. Kèm `wp option update page_on_front <id>` (page id của Trang Chủ).
14. **Dọn duplicate pages**: khi activate theme mới, vbrandsync auto tạo lại page mới với slug `-2` (Trang Chủ-2 etc) — keep originals (id 10/11/12/13), delete duplicates, set `_wp_page_template` đúng.
15. Update `bots/report/sites.md` registry — thêm entry mới full credentials

### PHP-FPM `vbrandwww` pool capacity (2026-05-10)
- Pool ban đầu: `pm.max_children=5`, không có `request_terminate_timeout`. Khi 1 outbound HTTP call từ WP/vbrandsync hang (e.g. brand-app slow, DNS chậm), worker block trên `poll()` syscall vô thời hạn → 5 worker exhausted → cả 9 sites trên server timeout.
- FPM log warn `server reached pm.max_children setting (5), consider raising it` xuất hiện đều đặn vài giờ/lần trong ngày 2026-05-10. Sau khi add site #9 (voducfoods), tần suất tăng đến mức blocking.
- Fix: `/etc/php/8.3/fpm/pool.d/vbrand.conf`:
  ```
  pm.max_children = 15      # was 5
  pm.start_servers = 4      # was 2
  pm.min_spare_servers = 2  # was 1
  pm.max_spare_servers = 6  # was 3
  request_terminate_timeout = 60s   # NEW — kill workers hung > 60s
  ```
  Backup giữ tại `vbrand.conf.bak-2026-05-10`. `sudo systemctl restart php8.3-fpm` để apply.
- **Lesson:** mỗi lần thêm site mới vào `/home/<DIR_NAME>/wordpress` → tạo php-fpm pool riêng `/etc/php/8.3/fpm/pool.d/<DIR_NAME>.conf` (mode `ondemand`, `pm.max_children=4`, `request_terminate_timeout=60s`). Tổng tất cả pools không vượt ~60 workers trên server 911 MB RAM (sau migration 2026-05-24). Brand app pool giữ `dynamic` (max 8). Server cũ 1.9 GB cho 15 max — config khác.
- **Lesson:** WP/vbrandsync gọi outbound HTTP (brand-app webhook, font CDN, ...) ở init time. Nếu không có `request_terminate_timeout`, 1 endpoint chậm = downtime toàn server. Luôn set timeout ≤ 60s cho mọi pool serving WP.

### Clone WP site + đổi theme (rebrand mạnh)
Khi clone 1 site sang brand mới (ví dụ orgafood → voducfoods):
- **Theme options keyed by theme name** trong `wp_vbs_settings` JSON (key = `wp_get_theme()->get('Name')`). Nếu theme mới có `Theme Name` khác → customizer values từ theme cũ KHÔNG load. Hai lựa chọn:
  1. **Đè theme name + dựa vào schema defaults** (đã update qua sed) — đơn giản nhất khi schema đã chứa Vietnamese defaults
  2. **Migrate JSON key**: `UPDATE wp_vbs_settings SET value = REPLACE(value, '"OldName":', '"NewName":') WHERE name='theme.options';`
- **Copy theme local trước, rồi rsync** — KHÔNG copy theme trên server. Local là source-of-truth (commit vào `vbrand-themes` repo).
- Bulk rename CSS prefix với sed cẩn thận: `--of-` → `--vd-` cho CSS vars, `\.of-` / `"of-` / `'of-` / ` of-` cho class names. Sau đó audit lại `grep -rE "of-|--of-|orgafood"` để soi sót.
- **Brand strings cần đổi cả uppercase**: e.g. `ORGAFOOD20` (coupon code), `OrgaFood` (display name). Chạy `grep -rinE "orga[a-z]*food|ORGAFOOD"` cuối cùng.
- **SVG logo**: viết tay vào `assets/images/logo/logo.svg` (full wordmark + icon) + `logo-mark.svg` (icon-only / favicon). Set qua `ThemeData::updateThemeOptions(['logo' => get_template_directory_uri() . '/assets/images/logo/logo.svg', 'favicon' => ...])`.
- **WP duplicate pages khi đổi theme**: vbrandsync's `vbrand_setfrontPageByTemplate` tự tạo page mới khi activate theme — nếu page cùng template đã tồn tại trong DB clone từ theme cũ, sẽ ra 2 bản (Trang chủ, Trang chủ-2, ...). Acceptable cho demo, có thể dọn sau bằng `wp post delete` nếu cần.

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

### AcelleMail partner backlink exchange — full workflow (2026-04-15)
- Khi đối tác muốn trao đổi backlink (họ link tới acellemail.com, mình link lại):
  1. **Scrape site đối tác** bằng Playwright/WebFetch — lấy logo SVG, company name, tagline, services. Không tự bịa content.
  2. **Tạo design doc** `acellemail/landing/docs/<PARTNER>.md` — plan toàn bộ trước khi code.
  3. **Integrations page** (`integrations.blade.php`): thêm pill nav + section `id="<slug>"` với Partner badge + logo + 4-card grid. Dùng gradient SVG vector icons thay vì letter squares để phân biệt với section khác.
  4. **Footer** (`footer.blade.php`): thêm link vào Partners column.
  5. **E2E** (`footer.spec.ts`): update partner list (exact DOM order).
  6. **LANDING.md**: update lessons learned trong cùng commit.
  7. **Audit visual** (Playwright screenshot desktop + mobile) trước khi deploy.
  8. **Deploy views-only** → E2E prod verify.
- Pattern reference: IPWarmup (letter squares, flat color) vs Mobile Message (vector icons, gradient bg, logo header) — cùng structure nhưng khác visual.
- **Lesson:** luôn download logo về local (`public/images/integrations/`) thay vì hotlink — external URL có thể chết.

### Copy + customize existing theme thay vì build from scratch (cafedanhphat 2026-04-28)
- Khi user yêu cầu "copy theme X ra theme Y rồi customize cho client Z", workflow nhanh hơn nhiều so với build from scratch:
  1. `cp -r theme-X theme-Y` → rename CSS prefix (`sed`), function names, theme metadata trong style.css header + functions.php
  2. Scrape client site cho content + images (puppeteer + cheerio)
  3. Batch Edit schema.php defaults với content client (giữ structure, đổi text)
  4. Audit English placeholder text trong page templates (page-aboutus.php, page-contact.php, archive-product.php, header.php) — dreamcafe legacy có nhiều English defaults trong PHP fallback
  5. Hero font-size cần giảm cho Vietnamese title (60px → 56px + accent 0.85em với `display:block`)
- Time saved: ~3-4 giờ so với build CSS + templates from scratch
- **Lesson:** Khi copy theme, sau khi grep replace prefix CSS xong, vẫn phải audit English text hardcoded trong PHP templates — schema defaults + PHP fallback defaults là 2 nguồn khác nhau, schema customize không cover hết.
- Reference: `/site/wp-content/themes/cafedanhphat/design/HISTORY.md`

### WooCommerce Store API scraper (2026-05-20 — khomaynenkhi launch)
- Khi nguồn import là WooCommerce site có **WC Store API public** (`/wp-json/wc/store/v1/products` + `/products/categories`) → KHÔNG cần Lazada/Shopee scraper (puppeteer + stealth + browser session). Pure HTTP fetch là đủ.
- New scraper: `bots/scrape/scripts/scrape-woocommerce-store.js` — output cùng standard format như Lazada/Shopee scraper, drop-in cho `import-to-woocommerce.js`.
- Detection: `curl -s https://target.com/wp-json/wc/store/v1/products?per_page=1` → 200 + JSON product array = OK. (404/401 = bị disable, fallback sang scraper khác.)
- Speed: 67 sản phẩm + 67 ảnh + 25 categories trong ~5s (vs ~2-3 phút với headless browser).
- Output mới: thêm field `images[]` (tất cả ảnh), `slug`, `short_description`, `in_stock` — import script hiện chỉ dùng primary image.
- **Lesson:** Trước khi reach cho headless scraper, luôn thử WC Store API endpoint — 60% các site B2B Vietnamese đều có nó open. Tiết kiệm 99% thời gian + 0 risk bị block IP.

### Khi import B2B contact-for-price products vào WooCommerce → add-to-cart silently fails (2026-05-22)
- Ductrico → khomaynenkhi import: all products có `price=""` (B2B liên hệ). WC's `WC_Product::is_purchasable()` requires `'' !== $this->get_price()` → returns false for empty price → submitting the cart form just reloads the product page với 0 cookies set, 0 error messages. UX hoàn toàn câm.
- Cart page mới (fresh sites) dùng WC block-based cart (`<!-- wp:woocommerce/cart -->`) — render qua React, ignore theme overrides, show English "Your cart is currently empty!" + auto cross-sell "New in store" block không cách nào style được.
- Fix in `khomaynenkhi/functions.php`:
  1. **`woocommerce_is_purchasable` filter** — return true for any published + in-stock product regardless of price (cart still works, COD-only flow turns into "request quote")
  2. **`woocommerce_empty_price_html` + `woocommerce_get_price_html`** filters — show "Liên hệ" when price ≤ 0 instead of blank/Free
  3. **`init` hook to rewrite cart/checkout page content** from `[wp:woocommerce/cart]` → `[woocommerce_cart]` shortcode (and same for checkout) → WC falls back to classic templates → theme's `woocommerce/cart/cart-empty.php` override takes over
  4. **`woocommerce_add_to_cart_redirect`** → redirect to cart page sau add-to-cart (user gets visual confirmation, not silent stay)
  5. **`gettext` + `ngettext` filters** mapping common WC English strings to Vietnamese ("Your cart is currently empty", "View cart", "Cart totals", "Proceed to checkout", etc.) — covers both `__()` and `_n()` (the success notice "%s has been added to your cart" uses `_n` for plural)
- Template overrides: `woocommerce/cart/cart-empty.php` với SVG icon + Vietnamese copy + 2 CTAs ("Xem sản phẩm" + "Liên hệ tư vấn"); single product `woocommerce/content-single-product.php` redesign với 2-column layout (sticky gallery + summary), feature checklist, "Yêu cầu báo giá" secondary CTA, tab-based description/specs/reviews.
- **Lessons:**
  1. Sau khi WC import từ source có empty price, **luôn test add-to-cart end-to-end** (curl POST → check `Set-Cookie: woocommerce_cart_hash`) — `is_purchasable` filter false fails silently không log.
  2. Fresh WC site (8.x+) dùng cart/checkout blocks by default → kill theme override khả năng. Filter trên `init` để rewrite về shortcode là path duy nhất giữ control của theme.
  3. `_n()` strings (plural-aware) cần filter `ngettext` riêng, không match qua `gettext`. WC notice "%s has been added to your cart" là `_n`, không phải `__`.
  4. Khi user phàn nàn "button không work + page xấu", debug **end-to-end qua curl** (open page → POST form → check cookies + redirect) trước khi đoán nguyên nhân. HTML response code 200 không có nghĩa là form work — phải xem cookies + final URL.

### rsync of vbrandsync plugin can leak local dev provider into prod cache → every page 500 (2026-05-22)
- Rsync push of `site/wp-content/plugins/vbrandsync/` from local to server includes `bootstrap/cache/packages.php` + `services.php`. Local repo was at some point booted with dev deps installed → cache references `NunoMaduro\Collision\Adapters\Laravel\CollisionServiceProvider`. Server `vendor/` is `composer install --no-dev` → class doesn't exist.
- On next request, `vbrandsync_getResponse()` boots Laravel, kernel handle tries to register providers, fails on missing Collision class → bootstrap aborts BEFORE `DatabaseServiceProvider::boot()` runs → Eloquent's connection resolver stays null. Every subsequent `Model::where(...)` call throws `Call to a member function connection() on null`.
- Symptom: storefront 500 on every product/category/archive page (header.php calls `vbrand_load_theme_data()` → `Setting::get('theme.options')` → boom). REST `/wp-json/vbrandsync/v1/*` endpoints might still work because they call `vbrandsync_getResponse('/')` themselves and the static `$app` caches across that call.
- Stack hint to recognize this: error message `Class "NunoMaduro\Collision\..." not found` in `storage/logs/laravel.log` immediately followed by `Call to a member function connection() on null` for every subsequent page render.
- Fix (immediate): `sudo rm bootstrap/cache/{packages,services}.php` on the affected server, then `sudo systemctl reload php8.3-fpm`. Laravel regenerates the cache from the actual installed `vendor/` on next boot — clean state.
- Fix (durable): `bots/automated/deploy-sites.md` `rsync` now excludes `.env`, `vendor/`, `storage/logs/*`, **and `bootstrap/cache/packages.php` + `services.php`**. Plus a post-rsync `grep + rm` step that deletes the cache if it references the Collision provider.
- **Lesson:** `bootstrap/cache/` is environment-specific output, not source-of-truth. Treat it like `vendor/` — never sync from a dev machine to prod. The `.gitignore` already excludes it from commits; rsync needs the same protection.

### Importer was silently dropping gallery + categories + slug + description images (2026-05-22)
- Khi user phàn nàn "nội dung sản phẩm chưa được copy qua WP" và share screenshot product page chỉ có title + 1 ảnh, **đừng giả định** scraper sai. Verify chain end-to-end:
  1. Check scraped JSON: `python3 -c "import json; [print(p['name'], len(p['description']), len(p['images']), p['categories']) for p in json.load(open('shops/X/products.json'))]"` — scraped data có thể đầy đủ.
  2. Check imported WP: `curl https://site.com/wp-json/wc/store/v1/products?slug=X` — so sánh description/images/categories field với scraped.
  3. Gap thường ở **importer** chứ không phải scraper. `import-to-woocommerce.js` historically chỉ pass `title/description/image_url/price` — bỏ rơi `images[]`, `categories[]`, `slug`, `short_description`, `in_stock`.
- Concrete bugs found in ductrico → khomaynenkhi import:
  - `productData` không có `image_urls[]` → mỗi product chỉ 1 thumbnail thay vì gallery 2-4 ảnh
  - `categoryMap` được build ở step 4 nhưng never referenced khi build per-product data → product `categories: []` trên storefront, no breadcrumb
  - `slug` không được pass → WP auto-generate từ title → URL drift khỏi source canonical slug
  - Description HTML chứa `<img src="http://source.com/...">` → hotlinked vĩnh viễn; nếu source xuống = ảnh chết + SEO juice rò sang source
- Fix (full pipeline):
  - `import-to-woocommerce.js`: build `galleryUrls` từ `p.images[]` (normalize + dedupe), build `categoryIds` từ `categoryMap[name]`, pass `slug` / `short_description` / `in_stock` / `image_urls[]` / `category_ids[]`
  - `vbrandsync/wordpress/api/import.php` `vbrandsync_api_import_product()`:
    * Set `post_name` từ `slug` qua `wp_update_post()` sau khi `$product->save()` (Product model dùng `wp_insert_post` không support `post_name`)
    * Set `post_excerpt` từ `short_description`
    * Sau khi save, download remaining `image_urls` không trùng `image_url` → `insertImageAsAttachment` → append vào `_product_image_gallery` meta
    * **Sideload description images:** regex `<img src="https?://...">` trong description, filter external (host ≠ `home_url`), download + attach to product + rewrite URL inline qua `strtr($desc, $rewriteMap)` + `wp_update_post(post_content)`
    * `set_stock_status` respect `in_stock === '0'` thay vì hardcoded `instock`
- **Lessons:**
  1. Khi 2 endpoints (importer + endpoint) cùng implement contract: viết test scenario mỗi field flow end-to-end. Khi nâng cấp scraper output (thêm `images[]`, `slug`, ...), audit luôn importer + endpoint có consume hay không.
  2. Hotlinked images trong description = silent SEO/availability liability. Sideload luôn ngay khi import — không để "fix sau".
  3. `Product::save()` (vbrandsync Product model) **không support `post_name`**. Phải `wp_update_post(['ID' => $id, 'post_name' => $slug])` sau khi save.
  4. Khi user share screenshot UI, **tìm dữ liệu trong DB trước**: API response cho biết WP có gì → so với source → biết gap ở đâu. Không chỉ trust screenshot.
  5. **WordPress auto-slashes `$_POST`** via `wp_magic_quotes()` (legacy magic_quotes_gpc compat). Khi field chứa HTML với quotes (e.g. `<img src="...">`), `$_POST['description']` arrives as `<img src=\"...\">`. Regex like `src=["\']([^"\']+)` doesn't match the `\"` form. **Always `wp_unslash($_POST[key])` before regex/string ops.** `wp_insert_post()` and `wp_update_post()` expect slashed input (they internally unslash before DB write), so if you've already `wp_unslash`ed, the round-trip still stores the correct value because update_post will re-slash. Diagnostic: add `bin2hex(substr($desc, 0, 80))` to a debug stamp — look for `5c22` (`\"`) to confirm slashing.

### `import-to-woocommerce.js` `SITE_URL` typo bug (2026-05-20)
- Line 273 dùng `SITE_URL` (undefined) thay vì `siteUrl` → crash ở Step 6 "Storefront sanity check" sau khi import xong. Products vẫn được import thành công nhưng coming-soon check không chạy. Lỗi `Storefront check skipped: SITE_URL is not defined` ở cuối log.
- Fix: line 273 đổi sang `siteUrl`.
- **Lesson:** Khi tách step verification ra cuối script, dùng cùng tên biến với phần đầu — đừng nhầm UPPER_SNAKE vs camelCase.

### `woocommerce_shop_page_id` bị orphan sau clone + theme activate (2026-05-20)
- Khi clone DB + activate theme mới, vbrandsync auto tạo page mới cho mỗi menu type=shop → set `woocommerce_shop_page_id` = ID của page mới đó. Nếu sau đó page bị delete (vd cleanup duplicates), `wc_get_page_permalink('shop')` fall back về home URL.
- Triệu chứng: click menu "Sản Phẩm" trên storefront → ra trang chủ thay vì shop archive. /shop/ vẫn 200 (vì WP route resolution), nhưng menu link sai.
- Fix: `wp option update woocommerce_shop_page_id <id>` trỏ tới page có slug Vietnamese (vd `/san-pham/`) + xóa `_wp_page_template` meta để WC tự render archive thay vì page-homepage.php template.
- **Lesson:** sau khi cleanup duplicate pages, audit lại `woocommerce_shop_page_id`, `woocommerce_cart_page_id`, `woocommerce_checkout_page_id`, `woocommerce_myaccount_page_id`. Bất kỳ ID nào trỏ tới deleted page sẽ làm storefront break im lặng.

### SVG illustrations thay placeholder duplicate banner (2026-05-20)
- Khi 1 page có nhiều section dùng cùng 1 image fallback (vd page-aboutus.php `$img = !empty($section['image']) ? $section['image'] : $theme_url . '/assets/images/hero/hero-1.png';`), về visual cảm giác lặp lại, mất max-effort vibe.
- Fix: cycle through different SVG defaults theo `$i` index: `$defaultImages = ['story.svg', 'quality.svg', 'service.svg']; $img = $defaultImages[$i] ?? $fallback;`.
- Custom SVG illustrations cho B2B industrial site (compressor industry):
  * About sections: 1 hero scene + 3 narrative scenes (story / quality / service)
  * Categories: 1 SVG per category card (screw / piston / oil-free / dryer / tank-filter / parts)
  * Brand logos: 1 SVG wordmark per brand (Jaguar / Atlas Copco / Hitachi etc.) — text-only wordmark trong brand color, KHÔNG dùng official logo files (copyright)
- Time investment: ~15 phút per illustration × 14 illustrations = 3.5h, nhưng kết quả top-notch + 0 image rights issues + scale infinitely.
- **Lesson:** Khi user phàn nàn "hình bị lặp banner" thay vì download stock photos hoặc tìm royalty-free, vẽ thẳng SVG illustration cho từng section. Vector → 1 file vài KB → responsive perfect → reuse được.

### `show_on_front` mặc định `posts` trên fresh DB clones (2026-05-20)
- Sau khi clone DB từ site khác (`wp db export` → `mysql import` → search-replace), `show_on_front` có thể là `"posts"` ngay cả khi `page_on_front` đã set đúng. Result: homepage hiện blog index (latest posts) thay vì template page-homepage.
- Triệu chứng: homepage HTML ngắn (~460 dòng), không có `kmnk-hero` section, chỉ có `kmnk-page-hero--small` (page template fallback).
- Fix: `wp option update show_on_front page` — bắt buộc sau khi clone. Đã thêm vào "Clone WP site" checklist (bước 13).
- **Lesson:** `page_on_front=<id>` ≠ "hiện page này". Phải pair với `show_on_front=page`.

### Theme PHP fallback defaults vs schema defaults (2026-05-20)
- Khi clone theme A → B (dieu-an → khomaynenkhi), audit phải cover **3 nơi** chứa brand strings, không phải chỉ 1:
  1. `schema.php` — defaults dùng khi user mở Theme Customizer (DB lưu vào `wp_vbs_settings.theme.options`)
  2. **`page-*.php` PHP fallback** trong `$g('key', [<<default here>>])` — defaults dùng khi DB **chưa có** giá trị (fresh site, hoặc khi key chưa được customize)
  3. Hardcoded HTML/text inline trong templates
- Lesson kỳ trước (cafedanhphat) đã catch (3). Lesson kỳ này: (2) là class riêng — nếu chỉ update schema.php mà bỏ qua PHP fallback, fresh sites sẽ hiện stale defaults ngay (vì DB chưa lưu gì).
- Workflow: sau `sed` rename CSS prefix → `grep -nE "\\\$g\(" page-*.php` → audit từng fallback array với content brand mới.
- **Lesson:** Mỗi `$g('key', [<<default>>])` là 1 fallback. Schema chỉ kick in khi user click Save trong Customizer. PHP fallback là first render. Audit cả 2.

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
- Mobile app gọi Laravel API (không gọi WP trực tiếp) — api.sgconnect.vn route vào /rui/brand/* của acelle mainline + brand plugin

---

## AcelleMail landing (`acellemail/landing/`)

**Single source of truth:** [`acellemail/landing/docs/LANDING.md`](acellemail/landing/docs/LANDING.md) — đọc TRƯỚC khi làm bất cứ task nào liên quan tới `acellemail.com`. Bao gồm: layout, server config, E2E gate, deploy workflow, design system, pages, lessons learned, self-learn rule.

**Tóm tắt cho session ngoài:**
- Laravel 12 site, static pages, no DB. Repo riêng: `git@github.com:luanpm88/acellemail-landing.git` (branch `develop`).
- Mọi change → E2E gate (`acellemail/landing/tests/e2e/`, Playwright, 42 tests) → rsync → re-verify prod.
- Discovery nào mới về landing → update `LANDING.md` trong cùng commit (xem `## 9. Self-learn rule` trong file đó).

**Marketing operations:** [`acellemail/landing/docs/marketing/`](acellemail/landing/docs/marketing/) — toàn bộ kế hoạch marketing (paid ads, SEO ad-side, content, lead capture, direct sales infra cho 2026-07-01 deadline). Persona [`Marketer.bot`](acellemail/landing/docs/marketing/Marketer.bot) phải đọc TRƯỚC mỗi session marketing-related. Mỗi session phải append entry vào [`marketing/LESSONS_LEARNED.md`](acellemail/landing/docs/marketing/LESSONS_LEARNED.md). North Star: combined revenue (CodeCanyon + Direct) = 2-3× current trong 6-12 tháng.

**Khi user nói ngắn:**
| User nói | Claude làm |
|----------|-----------|
| `test acellemail` / `e2e acellemail` | Start artisan serve + `cd acellemail/landing/tests/e2e && npm test` |
| `deploy acellemail` | Theo §4 trong `LANDING.md`: E2E gate → rsync → optimize → re-run E2E prod |
| `seo loop` / `tiếp seo` / `next seo wave` | Đọc `acellemail/landing/docs/SEO_PLAN.md ## Wave loop runner` → chạy wave kế tiếp trong `acellemail/landing/docs/SEO_PLAN.md ## Wave Progress` (E2E gate → deploy → verify → mark ☑ → commit + push). Auto cho 🟢; STOP & ask cho 🟡 / 🟥 |
| `seo loop status` | Print Wave Progress table only, không thay đổi gì |
| `maintain` / `MAINTENANCE.md run` / `health check` / `audit acellemail` / `kiểm tra prod` / `top notch check` | Đọc `acellemail/landing/docs/MAINTENANCE.md` → chạy `cd acellemail/landing && ./docs/scripts/maintain/run.sh standard` (~5 min: 8 phases — health/SEO/perf/content/tests/deps against prod). Surface report + fix any new findings + update "Known issues" table in MAINTENANCE.md |
| `maintain quick` / `daily check` | `./acellemail/landing/docs/scripts/maintain/run.sh quick` — 30s smoke (health + SEO), cron-friendly |
| `maintain deep` | `./acellemail/landing/docs/scripts/maintain/run.sh deep` — standard + Lighthouse + link integrity (~20 min) |
| `maintain full` | `./acellemail/landing/docs/scripts/maintain/run.sh full` — deep + SSH server-side via brandnew (~30 min) |
| `maintain server` | `./acellemail/landing/docs/scripts/maintain/run.sh server` — SSH-only checks (disk, logs, certbot, nginx, php-fpm) |

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

Khi user nói `export sales handoff` hoặc tương tự, chạy **đúng 5 bước** sau:

```bash
# 1. Bump "Cập nhật: <today>" line trong cả 3 MD (BẮT BUỘC trước khi gen PDF)
#    - SALES_HANDOVER.md / USER_GUIDE_MOBILE.md / USER_GUIDE_DESKTOP.md
#    - Mỗi file có 1 dòng `> Cập nhật: YYYY-MM-DD` ngay sau `# <title>`
#    - Update sang ngày hôm nay TRƯỚC khi gen PDF (để PDF có ngày đúng)

# 2. Gen 3 PDF (cần Chrome — set PUPPETEER_EXECUTABLE_PATH nếu .cache/puppeteer rỗng)
cd docs
PUPPETEER_EXECUTABLE_PATH="/Applications/Google Chrome.app/Contents/MacOS/Google Chrome" \
  npx md-to-pdf SALES_HANDOVER.md USER_GUIDE_MOBILE.md USER_GUIDE_DESKTOP.md

# 3. Copy vào drive_shared/ (luôn đè v1 — KHÔNG tăng version số trong filename)
cp SALES_HANDOVER.pdf drive_shared/SALES_HANDOVER_v1.pdf
cp USER_GUIDE_MOBILE.pdf drive_shared/USER_GUIDE_MOBILE_v1.pdf
cp USER_GUIDE_DESKTOP.pdf drive_shared/USER_GUIDE_DESKTOP_v1.pdf

# 4. Sync lên Google Drive
rclone sync drive_shared/ luanpm88:vBrand_Shared/SGCONNECT/ --progress

# 5. (Nếu user yêu cầu) Commit + push
```

**Quan trọng:** filename giữ `_v1` cố định, nhưng "version" thực sự nằm ở dòng `> Cập nhật: <date>` trong từng MD. Mỗi lần regen → bump date trong cả 3 MD trước, không skip.

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
