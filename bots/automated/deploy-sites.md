# Bot: Deploy Sites — Themes + vBrandSync Plugin

Sync vbrandsync plugin + themes from local to production WordPress sites used by acelle-brand customers. (Note: customer account provisioning is now via the acelle Admin UI or AccountProvisioningService; this bot syncs only the WordPress-side plugin + themes artifacts.)

## Self-learn rule (BẮT BUỘC sau mỗi deploy)
1. Permission/storage issue mới → update bước chmod/mkdir trong flow
2. Plugin conflict hoặc PHP error → thêm vào `CLAUDE.md ## Lessons Learned`
3. Thêm site mới → update `bots/report/sites.md` + `docs/SALES_HANDOVER.md`
4. **KHÔNG để kiến thức chết trong context window** — ghi ra file trong cùng commit

## Cách dùng

```
bots/automated/deploy-sites.md sync all
bots/automated/deploy-sites.md sync logitech.b-teka.com
```

## Input

- `sync all` — sync tất cả sites trong `bots/report/sites.md`
- `sync {DOMAIN}` — sync 1 site cụ thể

## Quy tắc xử lý biến

Từ DOMAIN, tính ra:
- `DIR_NAME` = thay `.` và `-` thành `_`
- `WP_PATH` = `/home/${DIR_NAME}/wordpress`

## SSH accounts

- `vbrand@54.169.34.13` — rsync, wp-cli

## Nguồn local

- Plugin: `/Users/luan/apps/vbrand/site/wp-content/plugins/vbrandsync/`
- Themes: `/Users/luan/apps/vbrand/site/wp-content/themes/`

## Flow: sync 1 site

> **Post-migration (2026-05-24) convention:** each CUSTOMER WordPress site = 1 Linux user (`<DIR_NAME>`), 1 php-fpm pool socket `/run/php/php8.3-fpm.<DIR_NAME>.sock`, code at `/home/<DIR_NAME>/wordpress/`. Deploy bot uses `ssh ubuntu@server` + `sudo` to chown files back to site user after rsync (vì local user `ubuntu` push lên thì files mặc định `ubuntu:ubuntu` — phải normalize về `<DIR_NAME>:vbrand`). (Note: the main acelle-brand app at app.sgconnect.vn uses a different deployment model — see CLAUDE.md.)

### Bước 1: Verify site tồn tại

```bash
ssh ubuntu@54.169.34.13 "sudo test -d /home/${DIR_NAME}/wordpress/wp-content && echo 'OK' || echo 'FAIL'"
```

Nếu FAIL → báo user và skip site này.

### Bước 2: Sync plugin vbrandsync (qua /tmp staging → sudo move + chown)

```bash
# CRITICAL excludes:
# - .env: per-site DB config, must not be overwritten
# - vendor/: production install only (no dev deps); composer install runs separately
# - storage/logs/*: per-site logs (don't copy local logs to server)
# - bootstrap/cache/*: cache files reference dev-only providers (vd CollisionServiceProvider)
#   on local but server vendor/ doesn't have them → bootstrap fails with
#   "Class \"NunoMaduro\\Collision\\Adapters\\Laravel\\CollisionServiceProvider\" not found"
#   → Eloquent connection() returns null → every storefront page 500s.
#   Laravel auto-regenerates these on first request when missing.

# Step 2a — rsync to /tmp staging (ubuntu user can write /tmp directly, no sudo)
rsync -avz --delete \
  --exclude '.env' \
  --exclude 'vendor/' \
  --exclude 'storage/logs/*' \
  --exclude 'bootstrap/cache/packages.php' \
  --exclude 'bootstrap/cache/services.php' \
  /Users/luan/apps/vbrand/site/wp-content/plugins/vbrandsync/ \
  ubuntu@54.169.34.13:/tmp/deploy-${DIR_NAME}-plugin/

# Step 2b — sudo rsync staging → site, chown to site user, cleanup
ssh ubuntu@54.169.34.13 "
  sudo rsync -a --delete --exclude '.env' \
    /tmp/deploy-${DIR_NAME}-plugin/ \
    /home/${DIR_NAME}/wordpress/wp-content/plugins/vbrandsync/
  sudo chown -R ${DIR_NAME}:vbrand /home/${DIR_NAME}/wordpress/wp-content/plugins/vbrandsync
  rm -rf /tmp/deploy-${DIR_NAME}-plugin
"

# Step 2c — regenerate cache if it still references dev-only providers:
ssh ubuntu@54.169.34.13 "
  if sudo grep -q 'Collision\\\\\\\\Adapters\\\\\\\\Laravel' /home/${DIR_NAME}/wordpress/wp-content/plugins/vbrandsync/bootstrap/cache/packages.php 2>/dev/null; then
    sudo rm -f /home/${DIR_NAME}/wordpress/wp-content/plugins/vbrandsync/bootstrap/cache/packages.php
    sudo rm -f /home/${DIR_NAME}/wordpress/wp-content/plugins/vbrandsync/bootstrap/cache/services.php
    echo 'Stale cache removed — Laravel will regenerate on next request'
  fi
"
```

### Bước 3: Sync themes (same staging pattern)

```bash
rsync -avz --delete /Users/luan/apps/vbrand/site/wp-content/themes/ \
  ubuntu@54.169.34.13:/tmp/deploy-${DIR_NAME}-themes/

ssh ubuntu@54.169.34.13 "
  sudo rsync -a --delete \
    /tmp/deploy-${DIR_NAME}-themes/ \
    /home/${DIR_NAME}/wordpress/wp-content/themes/
  sudo chown -R ${DIR_NAME}:vbrand /home/${DIR_NAME}/wordpress/wp-content/themes
  rm -rf /tmp/deploy-${DIR_NAME}-themes
"
```

### Bước 4: Cấu hình .env + install + migrate (chạy as site user) — ⚠️ LEGACY ONLY

> ⚠️ **DEPRECATED under the new acelle-brand model.** The vbrandsync plugin on customer WordPress sites is now a STATELESS API consumer (no .env, no migrations). Configuration is managed by the acelle-brand core app (`app.sgconnect.vn`, database `brand`), NOT by individual WordPress site plugins. This step SHOULD BE REMOVED, or treated as legacy-only for sites still running the old self-bootstrapping plugin. If the plugin still requires a per-site .env, that is an ANTI-PATTERN and should be refactored into pure WordPress plugin code without a Laravel bootstrap.
>
> ⚠️ **`php artisan migrate --force` is incompatible with the new stateless model.** vbrandsync running Laravel migrations per-site is technical debt. If migrations are still needed, they MUST move to the acelle core app and run once against the shared `brand` database — not per-site.

```bash
# LEGACY ONLY — skip for sites on the new stateless plugin model.
ssh ubuntu@54.169.34.13 "
  sudo -u ${DIR_NAME} bash -c '
    cd /home/${DIR_NAME}/wordpress/wp-content/plugins/vbrandsync
    sed -i \"s/^DB_DATABASE=.*/DB_DATABASE=${DIR_NAME}/\" .env
    sed -i \"s/^DB_USERNAME=.*/DB_USERNAME=${DIR_NAME}/\" .env
    sed -i \"s/^DB_PASSWORD=.*/DB_PASSWORD=aA456321@/\" .env
    sed -i \"s/^DB_HOST=.*/DB_HOST=127.0.0.1/\" .env
    sed -i \"s/^DB_PREFIX=.*/DB_PREFIX=wp_vbs_/\" .env
    chmod -R 775 storage bootstrap/cache 2>/dev/null || true
    php composer.phar install --no-dev --optimize-autoloader 2>&1 | tail -3
    # ⚠️ REMOVE/SKIP: migrations must move to the acelle core app (shared brand DB).
    php artisan migrate --force
    php artisan config:clear
    php artisan cache:clear
    php artisan view:clear
  '
"
```

> ⚠️ `silaptop` (DB user khohanglaptop) password là `@34xds@3%?.KM`, không phải `aA456321@`. Per CLAUDE.md \"Lessons Learned — MySQL user pwds\".
> ⚠️ The main acelle-brand app runs at `app.sgconnect.vn`, NOT as individual per-site Laravel instances. Do NOT run deploy-sites.md against `app.sgconnect.vn`. This bot syncs only customer WordPress sites (like `logitech.b-teka.com`, `nike.b-teka.com`, etc.), not the acelle core.

### Bước 5: Activate plugin

```bash
ssh ubuntu@54.169.34.13 "
  sudo -u ${DIR_NAME} wp --path=/home/${DIR_NAME}/wordpress plugin activate vbrandsync 2>/dev/null || echo 'Plugin already active'
"
```

### Bước 5.5: Enforce COD-only + vBrand Express-only — ⚠️ DEPRECATED

> ⚠️ **This step is DEPRECATED.** Site standardization (payment methods, shipping config) is now managed via the acelle-brand Admin UI when the customer connection is created — not via per-site WP-CLI scripts. Remove this per-site WP-CLI configuration step; payment/shipping setup is now handled by the acelle core app during customer onboarding.

<details>
<summary>Legacy WP-CLI enforcement (kept for reference only)</summary>

**BẮT BUỘC (legacy)** — vBrand sites phải có đúng 1 payment (COD) + 1 shipping (vBrand Express).
Xem CLAUDE.md §"Site standardization".

```bash
# Copy script lên server (1 lần) — sau đó tất cả site dùng chung
scp /Users/luan/apps/vbrand/bots/automated/enforce-cod-vbrand-express.php ubuntu@54.169.34.13:/tmp/enforce-cod-vbrand-express.php

ssh ubuntu@54.169.34.13 "
  sudo -u ${DIR_NAME} wp --path=/home/${DIR_NAME}/wordpress eval-file /tmp/enforce-cod-vbrand-express.php
"
```

Output phải kết thúc bằng `OK — site is COD-only + vBrand Express-only`.
Nếu FAIL → re-run lần 2 (idempotent — đôi khi disable + verify cùng request thấy stale in-memory state). Nếu vẫn fail → KHÔNG bàn giao site cho customer, fix trước.

</details>

### Bước 6: Verify

```bash
ssh ubuntu@54.169.34.13 "
  sudo -u ${DIR_NAME} bash -c '
    cd /home/${DIR_NAME}/wordpress
    echo \"=== Active Plugins ===\"
    wp plugin list --status=active --format=table
    echo \"=== Themes ===\"
    wp theme list --format=table
  '
"
```

## Flow: sync all

1. Đọc `bots/report/sites.md`, lấy tất cả DOMAIN + DIR_NAME
2. Với mỗi site, chạy **Flow sync 1 site** ở trên
3. Output tổng hợp:

```
✅ Deploy sites complete! (N sites)
- logitech.b-teka.com ✓
- nike.b-teka.com ✓
- Deployed at: <timestamp>
```

## Learned Issues

### chmod: Operation not permitted (2026-03-21)
**Vấn đề:** `chmod -R 775 storage bootstrap/cache` fail vì files thuộc sở hữu `www-data`, user `vbrand` không có quyền đổi permissions.
**Fix:** Thêm `2>/dev/null || true` — permissions đã đúng sẵn từ setup ban đầu, chmod chỉ là safety check không bắt buộc.
**Rule:** Luôn dùng `|| true` cho các lệnh chmod trong deploy — không để chmod fail block toàn bộ deploy.
