# Deploy lên prod app.sgconnect.vn — quy trình chuẩn

> Đúc kết từ đợt deploy 2026-06-12 (messenger + brand + vbrandsync + host UI).
> Đọc TRƯỚC khi đẩy bất cứ thứ gì lên server này.

## Server

| | |
|---|---|
| Host | `app.sgconnect.vn` = `54.169.34.13` (AWS Lightsail) |
| SSH | `ssh vbrand@54.169.34.13` (key mặc định, user `vbrand`) |
| App host | `/home/vbrand/app` (→ `app-new`; `app-legacy` là bản rollback cutover) |
| DB | MySQL local, database `brand` |
| ⚠ KHÔNG phải git repo | App dir deploy bằng **rsync từ working tree local**, không có `.git`. Mọi diff phải so bằng `rsync -rcn --itemize-changes`. |
| WP shops | `/home/{site}/wordpress/` — 13 site (b-teka.com demo subdomains + shop thật: Guucoffee_com, khohanglaptop_com, khomaynenkhi_com, sattanhung_com, auriuscrm…) |

## Nguồn local (source of truth)

| Thành phần | Local path | Prod path |
|---|---|---|
| Host app | `~/apps/acelle` | `/home/vbrand/app` |
| Plugin messenger | `~/apps/acelle_messenger` | `/home/vbrand/app/storage/app/plugins/acelle/messenger` |
| Plugin brand | `~/apps/acelle_brand` | `/home/vbrand/app/storage/app/plugins/acelle/brand` |
| vbrandsync (WP plugin) | `~/apps/acelle_brand/vbrandsync` | `/home/{site}/wordpress/wp-content/plugins/vbrandsync` (×13 site) |

## Quy trình deploy (theo thứ tự)

### 0. Backup (LUÔN LUÔN, trước mọi bước)

```sh
# Plugin: copy nguyên dir với suffix ngày
ssh vbrand@54.169.34.13 "cp -a /home/vbrand/app/storage/app/plugins/acelle/messenger \
  /home/vbrand/app/storage/app/plugins/acelle/messenger.bak-$(date +%Y%m%d)"

# Host: tar các dir sẽ bị thay
ssh vbrand@54.169.34.13 "cd /home/vbrand/app && tar -czf /home/vbrand/host-backup-$(date +%Y%m%d).tar.gz \
  app public/refactor resources/views/refactor routes config database/migrations"
```

### 1. Plugin (messenger / brand)

```sh
rsync -rc --delete --exclude=.git --exclude=node_modules \
  ~/apps/acelle_messenger/ vbrand@54.169.34.13:/home/vbrand/app/storage/app/plugins/acelle/messenger/

rsync -rc --delete --exclude=.git --exclude=node_modules \
  ~/apps/acelle_brand/ vbrand@54.169.34.13:/home/vbrand/app/storage/app/plugins/acelle/brand/
```

### 2. Host app — ⚠ BẮT BUỘC đi cùng bước 1

**Bài học 2026-06-12:** chỉ deploy plugin mà quên host → views plugin mới chạy trên
CSS/JS/blade-components host cũ → **vỡ style toàn inbox** (icon Zalo/IG phóng to,
mũi tên khổng lồ, ô xám). Plugin views phụ thuộc host: `mc-brand-icon.blade.php`,
`public/refactor/css/{components,variables}.css`, `public/refactor/js/ConvThread*.js`,
layouts (`$cssVer`). Host và plugin PHẢI cùng phiên bản working tree.

```sh
cd ~/apps/acelle
for d in app public/refactor resources/views/refactor resources/lang routes config database/migrations; do
  rsync -rc $d/ vbrand@54.169.34.13:/home/vbrand/app/$d/
done
```

Không đụng: `.env`, `storage/`, `bootstrap/cache/`, `vendor/`, `public/plugins/`
(publish lại ở bước 4). Nếu composer.json đổi → ssh vào chạy `composer install` riêng.

### 3. Migrate + publish + clear (mọi lần deploy)

```sh
ssh vbrand@54.169.34.13 "cd /home/vbrand/app && \
  php artisan migrate --force && \
  php artisan vendor:publish --provider='Acelle\\Brand\\ServiceProvider' --force && \
  php artisan vendor:publish --provider='Acelle\\Messenger\\ServiceProvider' --force && \
  php artisan view:clear && php artisan config:clear && php artisan route:clear && php artisan cache:clear"
```

### 4. vbrandsync → TOÀN BỘ 13 WP sites

Đẩy 1 lần lên `/tmp` của server rồi loop server-side (giữ nguyên `storage/` từng site):

```sh
rsync -rc --exclude=storage --exclude=node_modules --exclude=shopee_shop_test \
  --exclude='plugin.php.bak*' --delete \
  ~/apps/acelle_brand/vbrandsync/ vbrand@54.169.34.13:/tmp/vbrandsync-latest/

ssh vbrand@54.169.34.13 'for d in /home/*/wordpress/wp-content/plugins/vbrandsync; do
  site=$(echo $d | cut -d/ -f3)
  cp -a "$d" "$(dirname $d)/../vbrandsync.bak-$(date +%Y%m%d)" 2>/dev/null
  rsync -rc --exclude=storage /tmp/vbrandsync-latest/ "$d/" && echo "OK $site" || echo "FAIL $site"
done'
```

Dir plugin các site thuộc owner `{site_user}:vbrand` với group-write → user `vbrand` ghi được trực tiếp.

### 5. Verify (Playwright headless từ local)

```sh
# Mẫu script: /tmp/verify-logitech-inbox.cjs (login → rail connected → thread rows → screenshots)
NODE_PATH=~/apps/acelle/node_modules node /tmp/verify-logitech-inbox.cjs
```

Checklist: login về `/rui/brand` · inbox rail không kênh nào `--off` · icon brand
(FB/Zalo/IG) hiển thị compact đúng · Sales BOX chips có số đơn + doanh thu ·
filter `?channels=zalo_oa` ra badges media.

## Cấu hình instance đặc thù (SGConnect = BrandViet-style)

- `.env`: `BRAND_ENABLED=true` → middleware `BrandDefaultLanding` (plugin brand)
  redirect `/` **và** `/dashboard` → `/rui/brand`. Autologin
  (`/autologin/{api_token}`) của host hard-redirect về `refactor.dashboard`
  nên middleware phải bắt cả path `dashboard` (fix 2026-06-12).
- Brand mode per-customer: `setBrandSetting($customer, 'mode', 'messenger')`
  → sidebar ưu tiên inbox/lead/CRM.
- Messenger platform keys đã cấu hình trong bảng `messenger_settings`
  (meta.app_id/app_secret, zalo.app_id/app_secret, features.events.enabled).
- Agreement gate (`/rui/brand` terms modal) chỉ bật khi admin soạn template
  (`Setting brand_agreement_template`) — đang TRỐNG → không gate ai.

## Demo data (tài khoản demo cho khách)

- Customer #6 `logitech@gmail.com` / `Logitech@2026` — shop `logitech.b-teka.com`.
- Autologin demo: `https://app.sgconnect.vn/autologin/{api_token}` (token trong bảng users).
- Seeder: `php artisan messenger:seed-demo --customer-id=6 --leads=600 --subscribers=180`
  (đã mở rộng: 6 kênh gồm `zalo_oa` full 11 media-type + `instagram`; stamp
  `credential_id` round-robin FB Pages thật). Thêm:
  `messenger:seed-demo-cross-channel-report` + `messenger:seed-demo-cross-channel-subscribers`.
- Setup credentials + mail list: script mẫu `/tmp/setup-logitech-messenger.php`
  (FB ×2 keys thật từ `~/apps/acelle/.facebook_creds.json`, Zalo BYO demo-verified —
  ⚠ Zalo cred giả KHÔNG dùng mode `connect`, `ZaloTokenRefreshJob` sẽ churn).
- Orders demo: tạo qua brand bridge `OrderService::create(WpClient, OrderData)`
  (cần `product_id` thật trên WP), chuyển trạng thái qua các endpoint
  `order/set-packaging|set-packaged|set-delivering|set-completed/{id}`.

## Rollback

```sh
# Plugin:  xoá dir hiện tại, đổi tên .bak-YYYYMMDD lại
# Host:    tar -xzf /home/vbrand/host-backup-YYYYMMDD.tar.gz -C /home/vbrand/app
# Cutover gốc (cùng lắm): rm /home/vbrand/app && mv app-legacy app
```
