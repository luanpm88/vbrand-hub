> ⚠️ **SUPERSEDED — not a source of truth.** This describes the retired forked vBrand app (`~/apps/vbrand/app`). The current source of truth for WordPress/WooCommerce + vbrandsync site setup is **`~/apps/acelle_brand/docs/guide/WP_SITE_SETUP.md`**. Kept for historical reference only.

# WordPress + WooCommerce Local Site Setup Guide

Hướng dẫn từng bước tạo một WordPress + WooCommerce site local cho vBrand.
Copy-paste từng bước là xong. Thay đổi các biến ở đầu cho phù hợp site mới.

> **App architecture (post 2026-06-03 cutover):** vBrand/BrandViet giờ chạy trên
> codebase **mainline acelle** (`~/apps/acelle`, UI refactor `/rui`) cộng plugin
> `acelle/brand` (source `~/apps/acelle_brand`, symlink vào
> `storage/app/plugins/acelle/brand`). App forked cũ (`~/apps/vbrand/app`) đã
> **RETIRED**. Guide này chỉ lo phần dựng **WordPress+Woo site standalone** —
> process tạo site KHÔNG đổi. Việc gắn site vào tài khoản customer giờ làm qua
> bảng `brand_site_connections` ở màn `/rui/brand` (xem cuối guide), KHÔNG còn
> nằm trong thư mục app.

---

## Biến cần thay đổi cho mỗi site

| Biến | Ví dụ site 1 | Ví dụ site 2 |
|------|--------------|--------------|
| `SITE_DIR` | `site` | `site2` |
| `SITE_DOMAIN` | `brand-site.test` | `brand-site2.test` |
| `DB_NAME` | `brandsite` | `brandsite2` |
| `SITE_TITLE` | `Brand Site` | `Brand Site 2` |

```bash
# === ĐẶT BIẾN Ở ĐÂY - THAY ĐỔI CHO MỖI SITE MỚI ===
SITE_DIR="site"
#
# Tip (macOS): tránh dùng đuôi `.local` vì mDNS/Bonjour có thể làm DNS lookup chậm / timeout ngẫu nhiên.
# Prefer `.test` (RFC 6761) hoặc `.localhost`.
SITE_DOMAIN="brand-site.test"
DB_NAME="brandsite"
SITE_TITLE="Brand Site"
ADMIN_USER="admin"
ADMIN_PASS="admin"
ADMIN_EMAIL="admin@${SITE_DOMAIN}"
# Thư mục cha chứa WordPress site files (standalone — KHÔNG phải app dir).
# App vBrand/BrandViet giờ chạy từ ~/apps/acelle + plugin acelle/brand;
# WordPress site là cài đặt độc lập, không nằm trong cây thư mục app nữa.
SITES_ROOT="$HOME/apps/brand-sites"
DB_USER="root"
DB_PASS="123456"
DB_HOST="127.0.0.1"
NGINX_CONF="/opt/homebrew/etc/nginx/servers/apps"
# vbrandsync được deploy độc lập trên TỪNG WordPress+Woo site tại
# wp-content/plugins/vbrandsync. Không có một location trung tâm quản lý nó.
# Nếu copy từ một template, set VBRANDSYNC_SRC trỏ tới repo/site nguồn thực tế
# (vd: copy từ một site đã có: <site-dir>/wp-content/plugins/vbrandsync).
VBRANDSYNC_SRC="$HOME/apps/brand-sites/site/wp-content/plugins/vbrandsync"
```

---

## Prerequisites (chỉ cần làm 1 lần)

```bash
# WP-CLI
brew install wp-cli

# Kiểm tra
wp --version
# WP-CLI 2.12.0

# nginx + PHP-FPM phải đang chạy
brew services list | grep -E 'nginx|php'
```

---

## Step 1: Download WordPress

```bash
mkdir -p "${SITES_ROOT}/${SITE_DIR}"
cd "${SITES_ROOT}/${SITE_DIR}"

wp core download --locale=en_US
```

Kết quả: WordPress+Woo site được tạo như một cài đặt **standalone** (local hoặc
remote) — files nằm trong `${SITES_ROOT}/${SITE_DIR}/`. App Acelle sẽ kết nối tới
site này sau bằng cách tạo một row `brand_site_connection` trỏ tới endpoint
`/wp-json/vbrandsync/v1` của site (qua màn `/rui/brand`), KHÔNG còn dùng cột
legacy `customers.wordpress_endpoint` và KHÔNG quản lý qua cây thư mục app.

---

## Step 2: Thêm domain vào /etc/hosts

```bash
# Kiểm tra xem đã có chưa
grep "${SITE_DOMAIN}" /etc/hosts

# Nếu chưa có, thêm vào
echo "127.0.0.1   ${SITE_DOMAIN}" | sudo tee -a /etc/hosts
```

Verify:
```bash
ping -c 1 "${SITE_DOMAIN}"
# Phải trả về 127.0.0.1
```

---

## Step 3: Tạo MySQL database

```bash
mysql -u"${DB_USER}" -p"${DB_PASS}" -h"${DB_HOST}" -e "
  DROP DATABASE IF EXISTS ${DB_NAME};
  CREATE DATABASE ${DB_NAME} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
"
```

Verify:
```bash
mysql -u"${DB_USER}" -p"${DB_PASS}" -h"${DB_HOST}" -e "SHOW DATABASES;" | grep "${DB_NAME}"
```

---

## Step 4: Thêm nginx virtual host

Thêm block sau vào cuối file `${NGINX_CONF}`:

```bash
cat >> "${NGINX_CONF}" << NGINX_EOF

server {
    listen 80;
    server_name ${SITE_DOMAIN};
    root ${SITES_ROOT}/${SITE_DIR};

    index index.php index.html;

    charset utf-8;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    location ~ \.php\$ {
        include fastcgi_params;
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        fastcgi_index index.php;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    client_max_body_size 64M;
}
NGINX_EOF
```

Test và restart nginx:
```bash
nginx -t && brew services restart nginx
```

---

## Step 5: Tạo wp-config.php

```bash
cd "${SITES_ROOT}/${SITE_DIR}"

wp config create \
  --dbname="${DB_NAME}" \
  --dbuser="${DB_USER}" \
  --dbpass="${DB_PASS}" \
  --dbhost="${DB_HOST}"
```

---

## Step 6: Cài đặt WordPress

```bash
cd "${SITES_ROOT}/${SITE_DIR}"

wp core install \
  --url="http://${SITE_DOMAIN}" \
  --title="${SITE_TITLE}" \
  --admin_user="${ADMIN_USER}" \
  --admin_password="${ADMIN_PASS}" \
  --admin_email="${ADMIN_EMAIL}" \
  --skip-email
```

Verify:
```bash
curl -sI "http://${SITE_DOMAIN}" | head -5
# HTTP/1.1 200 OK
```

---

## Step 7: Cài vbrandsync plugin

```bash
cd "${SITES_ROOT}/${SITE_DIR}"

cp -r "${VBRANDSYNC_SRC}" wp-content/plugins/vbrandsync
wp plugin activate vbrandsync
```

---

## Step 8: Cài WooCommerce

```bash
cd "${SITES_ROOT}/${SITE_DIR}"

wp plugin install woocommerce --activate
```

---

## Step 9: Cấu hình Permalinks

```bash
cd "${SITES_ROOT}/${SITE_DIR}"

wp rewrite structure '/%postname%/' --hard
```

---

## Step 10: Verify toàn bộ

```bash
cd "${SITES_ROOT}/${SITE_DIR}"

# Check HTTP
curl -sI "http://${SITE_DOMAIN}" | head -3

# Check plugins
wp plugin list --status=active --format=table

# Check WP admin
echo "Admin URL: http://${SITE_DOMAIN}/wp-admin/"
echo "Login: ${ADMIN_USER} / ${ADMIN_PASS}"
```

---

## Quick Script: Tạo site mới (all-in-one)

Copy script dưới đây, thay đổi 4 biến đầu, chạy 1 phát là xong:

```bash
#!/bin/bash
set -e

# ============================================
# THAY ĐỔI CÁC BIẾN NÀY CHO SITE MỚI
# ============================================
SITE_DIR="site2"
SITE_DOMAIN="brand-site2.test"
DB_NAME="brandsite2"
SITE_TITLE="Brand Site 2"

# ============================================
# KHÔNG CẦN THAY ĐỔI PHẦN DƯỚI
# ============================================
ADMIN_USER="admin"
ADMIN_PASS="admin"
ADMIN_EMAIL="admin@${SITE_DOMAIN}"
# Thư mục cha chứa WordPress site files (standalone — KHÔNG phải app dir).
SITES_ROOT="$HOME/apps/brand-sites"
DB_USER="root"
DB_PASS="123456"
DB_HOST="127.0.0.1"
NGINX_CONF="/opt/homebrew/etc/nginx/servers/apps"
# vbrandsync deploy độc lập trên từng site; trỏ tới repo/site nguồn thực tế.
VBRANDSYNC_SRC="$HOME/apps/brand-sites/site/wp-content/plugins/vbrandsync"

echo "==> Creating WordPress site: ${SITE_DOMAIN}"
echo "    Directory: ${SITES_ROOT}/${SITE_DIR}"
echo "    Database:  ${DB_NAME}"
echo ""

# 1. Download WordPress
echo "[1/9] Downloading WordPress..."
mkdir -p "${SITES_ROOT}/${SITE_DIR}"
cd "${SITES_ROOT}/${SITE_DIR}"
wp core download --locale=en_US

# 2. /etc/hosts
echo "[2/9] Adding ${SITE_DOMAIN} to /etc/hosts..."
if ! grep -q "${SITE_DOMAIN}" /etc/hosts; then
    echo "127.0.0.1   ${SITE_DOMAIN}" | sudo tee -a /etc/hosts
    echo "    Added."
else
    echo "    Already exists, skipping."
fi

# 3. MySQL database
echo "[3/9] Creating database ${DB_NAME}..."
mysql -u"${DB_USER}" -p"${DB_PASS}" -h"${DB_HOST}" -e "
  DROP DATABASE IF EXISTS ${DB_NAME};
  CREATE DATABASE ${DB_NAME} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
"

# 4. nginx virtual host
echo "[4/9] Adding nginx virtual host..."
cat >> "${NGINX_CONF}" << NGINX_EOF

server {
    listen 80;
    server_name ${SITE_DOMAIN};
    root ${SITES_ROOT}/${SITE_DIR};

    index index.php index.html;
    charset utf-8;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    location ~ \.php\$ {
        include fastcgi_params;
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        fastcgi_index index.php;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    client_max_body_size 64M;
}
NGINX_EOF

nginx -t && brew services restart nginx

# 5. wp-config.php
echo "[5/9] Creating wp-config.php..."
cd "${SITES_ROOT}/${SITE_DIR}"
wp config create \
  --dbname="${DB_NAME}" \
  --dbuser="${DB_USER}" \
  --dbpass="${DB_PASS}" \
  --dbhost="${DB_HOST}"

# 6. Install WordPress
echo "[6/9] Installing WordPress..."
wp core install \
  --url="http://${SITE_DOMAIN}" \
  --title="${SITE_TITLE}" \
  --admin_user="${ADMIN_USER}" \
  --admin_password="${ADMIN_PASS}" \
  --admin_email="${ADMIN_EMAIL}" \
  --skip-email

# 7. vbrandsync plugin
echo "[7/9] Installing vbrandsync plugin..."
cp -r "${VBRANDSYNC_SRC}" wp-content/plugins/vbrandsync
wp plugin activate vbrandsync

# 8. WooCommerce
echo "[8/9] Installing WooCommerce..."
wp plugin install woocommerce --activate

# 9. Permalinks
echo "[9/9] Setting permalinks..."
wp rewrite structure '/%postname%/' --hard

echo ""
echo "============================================"
echo "  DONE! Site created successfully."
echo "============================================"
echo "  URL:    http://${SITE_DOMAIN}"
echo "  Admin:  http://${SITE_DOMAIN}/wp-admin/"
echo "  Login:  ${ADMIN_USER} / ${ADMIN_PASS}"
echo "  DB:     ${DB_NAME}"
echo "  Dir:    ${SITES_ROOT}/${SITE_DIR}"
echo "============================================"
```

---

## Existing Sites

| Site | Domain | Directory | Database | Status |
|------|--------|-----------|----------|--------|
| Site 1 | `brand-site.test` | `site/` | `brandsite` | Active |

---

## BẮT BUỘC sau khi install — Standardize payment + shipping

Mọi vBrand site (mới install hoặc clone) **phải** chạy script enforcement để có
đúng 1 payment (COD) + 1 shipping (vBrand Express). Xem CLAUDE.md §"Site
standardization".

```bash
# Local
cd /path/to/wp-root && wp eval-file /Users/luan/apps/vbrand/bots/automated/enforce-cod-vbrand-express.php

# Server
scp /Users/luan/apps/vbrand/bots/automated/enforce-cod-vbrand-express.php vbrand@54.169.34.13:/tmp/enforce-cod-vbrand-express.php
ssh vbrand@54.169.34.13 "wp --path=/home/<DIR_NAME>/wordpress eval-file /tmp/enforce-cod-vbrand-express.php"
```

Output kết thúc bằng `OK — site is COD-only + vBrand Express-only` mới được bàn giao.

---

## Gắn site vào tài khoản Acelle (connection)

Sau khi site WordPress+Woo đã chạy (các bước trên), nối nó với một customer
trong app Acelle. **Không** còn dùng cột legacy `customers.wordpress_endpoint`.

1. **Provision WordPress+Woo site** — process ở trên (vbrandsync expose
   `/wp-json/vbrandsync/v1`, UNAUTHENTICATED).
2. **Tạo customer account Acelle** — admin "create customer", hoặc
   `App\Services\AccountManagement\AccountProvisioningService::createCustomer` /
   `createInstallAccount`.
3. **Tạo `brand_site_connection`** — vào màn `/rui/brand` (connection screen),
   trỏ customer tới endpoint `/wp-json/vbrandsync/v1` của site họ. Mỗi customer
   một row trong bảng plugin `brand_site_connections`
   (`customer_id`, `endpoint_url`, `auth_meta` JSON `{secret}`, `tls_verify`,
   `status`, `last_checked_at`, `last_error`).

Lifecycle do `Acelle\Brand\Services\ConnectionService` (connect / clientFor /
getOrNull) + `ConnectionStateService` (state configured/connected cho
sidebar/dashboard) quản lý. `Acelle\Brand\Wordpress\WpClient` nói chuyện với
site **không đổi** — chỉ gửi header `X-Brand-Token` khi có set `secret`
(hardening tùy chọn).

---

## Troubleshooting

### nginx -t fails
- Kiểm tra syntax trong `${NGINX_CONF}`
- Đảm bảo không duplicate `server_name`

### 502 Bad Gateway
- PHP-FPM không chạy: `brew services restart php`
- Kiểm tra: `lsof -i :9000`

### WP-CLI lỗi database
- MySQL không chạy: `brew services restart mysql`
- Sai password: kiểm tra `wp-config.php`

### Site trả về 404
- Kiểm tra `root` path trong nginx config
- Chạy lại: `wp rewrite flush --hard`

### Plugin activation fails
- Kiểm tra source path: `ls "${VBRANDSYNC_SRC}"`
- Nếu không có, copy từ `brandsite/wp-content/plugins/vbrandsync`
