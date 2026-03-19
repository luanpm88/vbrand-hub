# Bot: Deploy Sites — Themes + vBrandSync Plugin

Sync vbrandsync plugin + themes từ local lên tất cả production WordPress sites.

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
- `WP_PATH` = `/home/vbrand/sites/${DIR_NAME}`

## SSH accounts

- `vbrand@18.141.199.175` — rsync, wp-cli

## Nguồn local

- Plugin: `/Users/luan/apps/vbrand/site/wp-content/plugins/vbrandsync/`
- Themes: `/Users/luan/apps/vbrand/site/wp-content/themes/`

## Flow: sync 1 site

### Bước 1: Verify site tồn tại

```bash
ssh vbrand@18.141.199.175 "test -d /home/vbrand/sites/${DIR_NAME}/wp-content && echo 'OK' || echo 'FAIL'"
```

Nếu FAIL → báo user và skip site này.

### Bước 2: Sync plugin vbrandsync

```bash
rsync -avz --delete /Users/luan/apps/vbrand/site/wp-content/plugins/vbrandsync/ vbrand@18.141.199.175:/home/vbrand/sites/${DIR_NAME}/wp-content/plugins/vbrandsync/
```

### Bước 3: Sync themes

```bash
rsync -avz --delete /Users/luan/apps/vbrand/site/wp-content/themes/ vbrand@18.141.199.175:/home/vbrand/sites/${DIR_NAME}/wp-content/themes/
```

### Bước 4: Cấu hình .env + install + migrate

```bash
ssh vbrand@18.141.199.175 "
cd /home/vbrand/sites/${DIR_NAME}/wp-content/plugins/vbrandsync
sed -i 's/^DB_DATABASE=.*/DB_DATABASE=${DIR_NAME}/' .env
sed -i 's/^DB_USERNAME=.*/DB_USERNAME=${DIR_NAME}/' .env
sed -i 's/^DB_PASSWORD=.*/DB_PASSWORD=aA456321@/' .env
sed -i 's/^DB_HOST=.*/DB_HOST=127.0.0.1/' .env
sed -i 's/^DB_PREFIX=.*/DB_PREFIX=wp_vbs_/' .env
chmod -R 775 storage bootstrap/cache
php composer.phar install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:clear
php artisan cache:clear
php artisan view:clear
"
```

### Bước 5: Activate plugin

```bash
ssh vbrand@18.141.199.175 "
cd /home/vbrand/sites/${DIR_NAME}
wp plugin activate vbrandsync 2>/dev/null || echo 'Plugin already active'
"
```

### Bước 6: Verify

```bash
ssh vbrand@18.141.199.175 "
cd /home/vbrand/sites/${DIR_NAME}
echo '=== Active Plugins ==='
wp plugin list --status=active --format=table
echo '=== Themes ==='
wp theme list --format=table
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
