# Bot #2: vBrand Sync Production Site

Deploy acelle release (+ plugin `acelle/brand`) lên prod single-instance, và provision customer WordPress sites external.

## Cách dùng

```
bots/vbrand_sync_prod_site.md deploy
bots/vbrand_sync_prod_site.md provision logitech.b-teka.com
```

## Input

- **deploy** — build + symlink-swap acelle release mới lên prod
- **provision {DOMAIN}** — provision 1 customer WordPress site external + tạo brand_site_connection

## Sites registry

File `bots/report/sites.md` chứa danh sách customer sites. Đọc file này để:
- Tra cứu DOMAIN ↔ customer
- Verify site đã có brand_site_connection chưa

Mapping customer ↔ WordPress endpoint thực tế nằm ở bảng `brand_site_connections` (`customer_id` → `endpoint_url`), không phải directory trên prod.

## Quy tắc xử lý biến

Từ DOMAIN, tính ra:
- `DOMAIN` = domain user nhập (ví dụ: `logitech.b-teka.com`)
- `DIR_NAME` = thay `.` và `-` thành `_` (ví dụ: `logitech_b_teka_com`)

Prod deployment giờ là **single acelle instance** tại `/home/vbrand/app/public` (symlink → `/home/vbrand/app-new`), không còn multi-site layout. Customer WordPress sites là external, được quản lý qua bảng `brand_site_connections` (customer_id → endpoint_url) — không sync vào directory nào trên prod server.

## SSH accounts

- `vbrand@54.169.34.13` — dùng cho composer/artisan deploy + symlink swap. File tự động thuộc `vbrand:vbrand`, không cần chown/chmod. (sudo cần password — cutover được thiết kế reload-free, không cần sudo.)

## Nguồn local

Brand features giờ đến từ plugin `acelle/brand` — source `~/apps/acelle_brand` (symlink vào `storage/app/plugins/acelle/brand`). Themes của brand builder là một phần của plugin, **không** sync từ legacy `vbrand/site` local app.

- Plugin acelle/brand: `~/apps/acelle_brand/` → `storage/app/plugins/acelle/brand`
- Themes: nằm trong plugin (không sync riêng)
- Customer WordPress sites: chạy plugin `vbrandsync` external tại WordPress endpoint của chính họ (`/wp-json/vbrandsync/v1/*`), **không** sync từ `vbrand/site` local.

## Flow: deploy prod (single acelle instance)

Prod giờ là một acelle release duy nhất, không còn per-site directory. Deploy là build release mới song song rồi symlink swap (reload-free, không cần sudo).

### Bước 1: Build release + composer install (vbrand@)

```bash
ssh vbrand@54.169.34.13 "
cd /home/vbrand/app-new
composer install --no-dev --optimize-autoloader
"
```

### Bước 2: Cấu hình .env (vbrand@)

App dùng database `brand`. Giữ nguyên `APP_KEY`/mail từ legacy.

```bash
ssh vbrand@54.169.34.13 "
cd /home/vbrand/app-new
sed -i 's#^DB_DATABASE=.*#DB_DATABASE=brand#' .env
sed -i 's#^APP_URL=.*#APP_URL=https://app.sgconnect.vn#' .env
"
```

### Bước 3: Migrate + seed (vbrand@)

```bash
ssh vbrand@54.169.34.13 "
cd /home/vbrand/app-new
php artisan migrate:fresh --force
php artisan db:seed --class=DatabaseInit --force
php artisan db:seed --class=TemplateSeeder --force
"
```

### Bước 4: Load + activate plugin acelle/brand (vbrand@)

```bash
ssh vbrand@54.169.34.13 "
cd /home/vbrand/app-new
php artisan plugin:load acelle/brand
"
```

Activate plugin (row `status='active'` trong bảng `plugins`).

### Bước 5: Symlink swap (vbrand@)

```bash
ssh vbrand@54.169.34.13 "
cd /home/vbrand
mv app app-legacy && ln -sfn app-new app
"
```

Hoạt động vì nginx set `SCRIPT_FILENAME=\$realpath_root...` và `opcache.validate_timestamps=On` (`revalidate_freq=2`).

**Rollback:** `rm /home/vbrand/app && mv /home/vbrand/app-legacy /home/vbrand/app`.

### Output

```
✅ Deploy prod xong!
- Release: /home/vbrand/app -> app-new
- DB: brand
- Plugin acelle/brand: active ✓
- Legacy preserved: /home/vbrand/app-legacy (rollback)
```

## Flow: provision customer site

Tạo site mới cho customer (the new normal). Không tạo directory trên prod.

1. **Provision WordPress + Woo external** (process unchanged) — plugin `vbrandsync` expose `/wp-json/vbrandsync/v1/*`.
2. **Tạo acelle customer account** — admin "create customer" hoặc `App\Services\AccountManagement\AccountProvisioningService::createCustomer` / `createInstallAccount`.
3. **Tạo brand_site_connection** trỏ customer tới endpoint `/wp-json/vbrandsync/v1` của site họ — qua màn hình connection ở `/rui/brand`, **không** dùng cột legacy `customers.wordpress_endpoint`.

Mapping customer ↔ WordPress sống ở bảng `brand_site_connections` (cột: `customer_id`, `endpoint_url`, `auth_meta` JSON `{secret}`, `tls_verify`, `status`, `last_checked_at`, `last_error`) — một row mỗi customer. Lifecycle do `Acelle\Brand\Services\ConnectionService` (connect/clientFor/getOrNull) + `ConnectionStateService` (configured/connected state cho sidebar/dashboard) quản lý. `Acelle\Brand\Wordpress\WpClient` gọi WP endpoint, gửi `X-Brand-Token` chỉ khi có secret (optional hardening).

**Validate customer site:** kiểm tra bảng `brand_site_connections` xem customer đã có `endpoint_url` chưa. Nếu chưa → báo user: "Customer not yet connected to a WordPress site. Provision WordPress externally and create a brand_site_connection entry via /rui/brand connection screen."

```
✅ Provision xong ${DOMAIN}!
- WordPress + Woo: provisioned external ✓
- Acelle customer account: created ✓
- brand_site_connection: endpoint /wp-json/vbrandsync/v1 ✓
```
