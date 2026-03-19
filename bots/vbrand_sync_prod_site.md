# Bot #2: vBrand Sync Production Site

Sync plugin vbrandsync + themes từ local lên production site.

## Cách dùng

```
bots/vbrand_sync_prod_site.md sync logitech.b-teka.com
bots/vbrand_sync_prod_site.md sync all
```

## Input

- **sync {DOMAIN}** — sync 1 site cụ thể
- **sync all** — sync tất cả sites trong `bots/report/sites.md`

## Sites registry

File `bots/report/sites.md` chứa danh sách tất cả sites đã deploy. Đọc file này để:
- Lấy danh sách sites khi `sync all`
- Tra cứu DIR_NAME từ DOMAIN
- Verify site có tồn tại trong registry không

## Quy tắc xử lý biến

Từ DOMAIN, tính ra:
- `DOMAIN` = domain user nhập (ví dụ: `logitech.b-teka.com`)
- `DIR_NAME` = thay `.` và `-` thành `_` (ví dụ: `logitech_b_teka_com`)
- `WP_PATH` = `/home/vbrand/sites/${DIR_NAME}`

## SSH accounts

- `vbrand@18.141.199.175` — dùng cho rsync và wp-cli. File tự động thuộc `vbrand:vbrand`, không cần chown/chmod.

## Nguồn local

- Plugin vbrandsync: `/Users/luan/apps/vbrand/site/wp-content/plugins/vbrandsync/`
- Themes: `/Users/luan/apps/vbrand/site/wp-content/themes/`

## Flow: sync 1 site

### Bước 1: Verify site tồn tại trên server

```bash
ssh vbrand@18.141.199.175 "test -d /home/vbrand/sites/${DIR_NAME}/wp-content && echo 'OK' || echo 'FAIL'"
```

Nếu FAIL → báo user: "Site /home/vbrand/sites/${DIR_NAME} không tồn tại trên server. Chạy Bot #1 trước."

### Bước 2: Sync plugin vbrandsync (rsync → vbrand@)

```bash
rsync -avz --delete /Users/luan/apps/vbrand/site/wp-content/plugins/vbrandsync/ vbrand@18.141.199.175:/home/vbrand/sites/${DIR_NAME}/wp-content/plugins/vbrandsync/
```

### Bước 3: Sync toàn bộ themes (rsync → vbrand@)

```bash
rsync -avz --delete /Users/luan/apps/vbrand/site/wp-content/themes/ vbrand@18.141.199.175:/home/vbrand/sites/${DIR_NAME}/wp-content/themes/
```

### Bước 4: Cấu hình .env + install + migrate vbrandsync (vbrand@)

vbrandsync là Laravel app. rsync `--delete` ghi đè `.env` từ local → phải sửa lại DB credentials cho đúng site.

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

### Bước 5: Activate plugin (nếu chưa active) (vbrand@)

```bash
ssh vbrand@18.141.199.175 "
cd /home/vbrand/sites/${DIR_NAME}
wp plugin activate vbrandsync 2>/dev/null || echo 'Plugin already active'
"
```

### Bước 6: Verify (vbrand@)

```bash
ssh vbrand@18.141.199.175 "
cd /home/vbrand/sites/${DIR_NAME}
echo '=== Active Plugins ==='
wp plugin list --status=active --format=table
echo '=== Themes ==='
wp theme list --format=table
"
```

### Output

```
✅ Sync xong ${DOMAIN}!
- Plugin vbrandsync: synced ✓
- Themes: synced ✓
- Server path: /home/vbrand/sites/${DIR_NAME}
```

## Flow: sync all

1. Đọc `bots/report/sites.md`, lấy tất cả DOMAIN từ bảng.
2. Với mỗi DOMAIN, chạy **Flow sync 1 site** ở trên.
3. Output tổng hợp:

```
✅ Sync all xong! (N sites)
- logitech.b-teka.com ✓
- example.com ✓
- ...
```
