# Bot #1: vBrand New Production Site

Tạo WordPress + WooCommerce site mới trên production server, tạo customer trên brand app, kết nối 2 chiều brand ↔ WordPress.

> **⚠️ Kiến trúc mới (sau cutover 2026-06-03):** Brand app giờ chạy trên **acelle mainline** (UI refactor `/rui`) + **plugin `acelle/brand`** — KHÔNG còn là app fork `vbrand` baked-in nữa. `/home/vbrand/app` là **symlink → `/home/vbrand/app-new`**; app fork cũ giữ tại `/home/vbrand/app-legacy` (chỉ để rollback). App dùng DB `brand` (DB `vbrand` giữ để rollback). Mọi lệnh `tinker` chạy trong `/home/vbrand/app` (qua symlink) dùng **namespace `App\Model\*`** (số ít `Model`), KHÔNG phải `\Acelle\Model\*` của app cũ. Việc map customer ↔ WordPress giờ nằm ở **bảng plugin `brand_site_connections`** (không còn cột `customers.wordpress_endpoint`), quản lý bởi `Acelle\Brand\Services\ConnectionService`. Phía WordPress (plugin `vbrandsync`, `/wp-json/vbrandsync/v1/*`) GIỮ NGUYÊN không đổi.
>
> **⚠️ Server model (sau move 2026-05-24):** mỗi customer WP site = 1 Linux user riêng (`<DIR_NAME>`, group `vbrand`), code tại `/home/<DIR_NAME>/wordpress`. User `vbrand` **đọc được nhưng KHÔNG ghi** được vào WP site, và per-site user **không có SSH key trực tiếp**. Vì vậy: các lệnh **WP write-op** bên dưới (wp-cli, sửa file, tạo/chmod, rsync vào site) phải chạy qua **`ubuntu@54.169.34.13` + `sudo -u <DIR_NAME>`** (rồi `chown <DIR_NAME>:vbrand`) — KHÔNG dùng `vbrand@` trực tiếp như server cũ. Chỉ các lệnh **brand-app** (`php artisan tinker` trong `/home/vbrand/app`) mới giữ `vbrand@`; lệnh **sudo hệ thống** (MySQL, nginx) dùng `ubuntu@`. Xem `bots/automated/deploy-sites.md` (đã đúng model này) để tham chiếu lệnh `sudo`/`chown`.

## Cách dùng

```
bots/vbrand_new_prod_site.md tạo site logitech.b-teka.com
bots/vbrand_new_prod_site.md tạo site logitech.b-teka.com công ty "Logitech Vietnam" email huy@logitech.com
bots/vbrand_new_prod_site.md tạo site nike.b-teka.com theme logitech
bots/vbrand_new_prod_site.md Guucoffee.com email Marketingmientrung@gmail.com tên khách "Anh Linh" công ty "GuuCoffee" theme dreamcafe
```

## Input

- **DOMAIN** (bắt buộc) — domain của site (ví dụ: `logitech.b-teka.com`)
- **COMPANY_NAME** (optional) — tên công ty/shop. Mặc định: lấy phần đầu domain viết hoa (ví dụ: `logitech.b-teka.com` → `Logitech`)
- **ADMIN_EMAIL** (optional) — email quản lý. Mặc định: `{domain_slug}@gmail.com` (ví dụ: `logitech@gmail.com`)
- **USER_NAME** (optional) — tên khách hàng, dạng "Anh Linh" hoặc "Nguyễn Văn A". Bot tự tách: từ đầu = FIRST_NAME, từ cuối = LAST_NAME. Ví dụ: `"Anh Linh"` → FIRST_NAME=`Anh`, LAST_NAME=`Linh`. Nếu chỉ có 1 từ thì FIRST_NAME = từ đó, LAST_NAME = rỗng
- **FIRST_NAME** (optional) — tên admin. Mặc định: `Admin`. Bị override nếu có USER_NAME
- **LAST_NAME** (optional) — họ admin. Mặc định: `Shop`. Bị override nếu có USER_NAME
- **PHONE** (optional) — SĐT admin. Mặc định: không có
- **TIMEZONE** (optional) — timezone. Mặc định: `Asia/Ho_Chi_Minh`
- **THEME** (optional) — tên theme WordPress cần activate. Mặc định: `logitech`

## Quy tắc xử lý biến

Từ DOMAIN, tính ra các biến:
- `DOMAIN` = domain user nhập, **lowercase toàn bộ** (ví dụ: `Guucoffee.com` → `guucoffee.com`)
- `DIR_NAME` = thay `.` và `-` thành `_` (ví dụ: `logitech.com` → `logitech_com`, `logitech.b-teka.com` → `logitech_b_teka_com`)
- `DB_NAME` = giống DIR_NAME (ví dụ: `logitech_com`)
- `DB_USER` = giống DIR_NAME (ví dụ: `logitech_com`)
- `DB_PASS` = `aA456321@`
- `WP_ADMIN_USER` = `admin`
- `WP_ADMIN_PASS` = `aA456321@`
- `WP_PATH` = `/home/${DIR_NAME}/wordpress`
- `BRAND_USER_PASS` = `<Stem>@2026` — **Stem** = nhãn đầu của DOMAIN viết hoa chữ cái đầu (ví dụ `khomaynenkhi.com` → `Khomaynenkhi@2026`, `logitech.b-teka.com` → `Logitech@2026`, `cafedanhphat.vn` → `Cafedanhphat@2026`). ⛔ **TUYỆT ĐỐI KHÔNG dùng `123456`** — mật khẩu chung yếu, gây security incident 2026-06-19 (toàn bộ shop đã bị reset sang pattern này). Mỗi shop một mật khẩu riêng theo stem.
- `BRAND_APP_PATH` = `/home/vbrand/app` — **lưu ý:** sau cutover (2026-06-03) đây là **symlink** trỏ tới `/home/vbrand/app-new` (release acelle mainline + plugin `acelle/brand`). Bản fork cũ giữ lại để rollback tại `/home/vbrand/app-legacy`. DB của app là `brand` (DB cũ `vbrand` giữ để rollback).
- `BRAND_APP_ENDPOINT` = endpoint mà WP gọi ngược về Brand app. **KHÔNG còn `/api/brand` như app fork cũ.** Trong kiến trúc plugin mới, việc đẩy endpoint+secret sang WP được `ConnectionService::connect()` tự làm (xem bước 14), nên KHÔNG cần điền tay. Nếu cần external JSON API thì plugin expose `/api/v1/brand/*` (guard `auth:api`).
- `WP_API_ENDPOINT` = `https://${DOMAIN}/wp-json/vbrandsync/v1` (sau khi SSL, nếu SSL fail thì `http://`)

Biến optional:
- `COMPANY_NAME` = nếu user không nhập → lấy phần đầu domain trước dấu `.` đầu tiên, viết hoa chữ cái đầu (ví dụ: `logitech.b-teka.com` → `Logitech`)
- `ADMIN_EMAIL` = nếu user không nhập → `{phần_trước_dấu_chấm_đầu}@gmail.com` (ví dụ: `logitech@gmail.com`)
- `USER_NAME` = nếu user nhập "tên khách" hoặc "tên" → tách: từ đầu = FIRST_NAME, từ cuối = LAST_NAME
- `FIRST_NAME` = mặc định `Admin` (override bởi USER_NAME nếu có)
- `LAST_NAME` = mặc định `Shop` (override bởi USER_NAME nếu có)
- `TIMEZONE` = mặc định `Asia/Ho_Chi_Minh`
- `THEME` = mặc định `logitech`

## SSH accounts

- `vbrand@54.169.34.13` — dùng cho mọi thao tác WordPress (download, config, install, wp-cli, rsync) và brand app (tinker). File tạo ra tự động thuộc `vbrand:vbrand`.
- `ubuntu@54.169.34.13` — chỉ dùng khi cần `sudo`: tạo MySQL DB/user, tạo/enable nginx vhost, reload nginx.

## QUY TẮC AN TOÀN: KHÔNG BAO GIỜ GHI ĐÈ

**TUYỆT ĐỐI KHÔNG override/ghi đè dữ liệu đã tồn tại.** Nếu domain, email, hoặc bất kỳ resource nào đã tồn tại → PHẢI hỏi user với 3 options:

1. **skip** — bỏ qua bước này, đi tiếp bước kế
2. **resume** — kiểm tra setup đã tới đâu rồi, chạy tiếp các bước chưa hoàn thành
3. **cancel** — hủy toàn bộ, không làm gì thêm

**KHÔNG CÓ option override/ghi đè.** Nếu user muốn làm lại từ đầu, phải xóa manual trước rồi chạy bot lại.

## Các bước thực hiện

Chạy tuần tự từng bước qua SSH. Nếu bước nào lỗi thì DỪNG và báo user.

### Bước 0: Pre-flight check — Kiểm tra trùng lặp

Trước khi bắt đầu, kiểm tra xem domain/email đã tồn tại chưa:

**0a. Check domain trong registry:**

Đọc file `bots/report/sites.md`. Nếu DOMAIN đã có trong file → cảnh báo user:

```
⚠️ Domain ${DOMAIN} đã tồn tại trong registry (bots/report/sites.md)!
Chọn:
1. skip — bỏ qua, không tạo site mới
2. resume — kiểm tra setup tới đâu rồi, chạy tiếp phần còn lại
3. cancel — hủy
```

**0b. Check thư mục site trên server:**

```bash
ssh vbrand@54.169.34.13 "test -d /home/${DIR_NAME}/wordpress/wp-content && echo 'EXISTS' || echo 'NOT_FOUND'"
```

Nếu EXISTS → cảnh báo user: "Thư mục site đã tồn tại trên server."

**0c. Check email trên brand app:**

```bash
ssh vbrand@54.169.34.13 'cd /home/vbrand/app && php artisan tinker --execute="
\$user = \App\Model\User::where(\"email\", \"${ADMIN_EMAIL}\")->first();
if (\$user) {
    echo \"EXISTS\n\";
    echo \"User ID: \" . \$user->id . \"\n\";
    echo \"Email: \" . \$user->email . \"\n\";
} else {
    echo \"NOT_FOUND\n\";
}
"'
```

Nếu EXISTS → cảnh báo user: "Email ${ADMIN_EMAIL} đã tồn tại trên brand app."

**0d. Check nginx vhost:**

```bash
ssh ubuntu@54.169.34.13 "test -f /etc/nginx/sites-available/${DOMAIN} && echo 'EXISTS' || echo 'NOT_FOUND'"
```

**Nếu user chọn `resume`:**
- Tổng hợp kết quả check ở trên
- Báo user: "Đã setup tới bước N, sẽ tiếp tục từ bước N+1"
- Chạy tiếp các bước chưa hoàn thành (dùng logic check ở mỗi bước bên dưới)

**Nếu user chọn `skip`:** dừng, không làm gì.
**Nếu user chọn `cancel`:** dừng, không làm gì.

### Bước 1: Tạo MySQL database + user (ubuntu@)

**Check trước:** kiểm tra DB đã tồn tại chưa.

```bash
ssh ubuntu@54.169.34.13 "sudo mysql -e \"SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME='${DIR_NAME}';\" 2>/dev/null | grep -q '${DIR_NAME}' && echo 'DB_EXISTS' || echo 'DB_NOT_FOUND'"
```

- Nếu `DB_EXISTS` → skip bước này (log: "DB ${DIR_NAME} đã tồn tại, skip.")
- Nếu `DB_NOT_FOUND` → tạo mới:

```bash
ssh ubuntu@54.169.34.13 "sudo mysql -e \"
CREATE DATABASE IF NOT EXISTS ${DIR_NAME} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DIR_NAME}'@'localhost' IDENTIFIED BY 'aA456321@';
GRANT ALL PRIVILEGES ON ${DIR_NAME}.* TO '${DIR_NAME}'@'localhost';
FLUSH PRIVILEGES;
\""
```

### Bước 2: Download WordPress (vbrand@)

**Check trước:** kiểm tra WP đã download chưa.

```bash
ssh vbrand@54.169.34.13 "test -f /home/${DIR_NAME}/wordpress/wp-includes/version.php && echo 'WP_EXISTS' || echo 'WP_NOT_FOUND'"
```

- Nếu `WP_EXISTS` → skip bước này
- Nếu `WP_NOT_FOUND` → download:

```bash
ssh vbrand@54.169.34.13 "
mkdir -p /home/${DIR_NAME}/wordpress
cd /home/${DIR_NAME}/wordpress
wp core download --locale=en_US
"
```

### Bước 3: Tạo wp-config.php (vbrand@)

**Check trước:**

```bash
ssh vbrand@54.169.34.13 "test -f /home/${DIR_NAME}/wordpress/wp-config.php && echo 'CONFIG_EXISTS' || echo 'CONFIG_NOT_FOUND'"
```

- Nếu `CONFIG_EXISTS` → skip bước này
- Nếu `CONFIG_NOT_FOUND` → tạo:

```bash
ssh vbrand@54.169.34.13 "
cd /home/${DIR_NAME}/wordpress
wp config create \
  --dbname=${DIR_NAME} \
  --dbuser=${DIR_NAME} \
  --dbpass='aA456321@' \
  --dbhost=localhost
"
```

### Bước 4: Install WordPress (vbrand@)

**Check trước:**

```bash
ssh vbrand@54.169.34.13 "cd /home/${DIR_NAME}/wordpress && wp core is-installed 2>/dev/null && echo 'WP_INSTALLED' || echo 'WP_NOT_INSTALLED'"
```

- Nếu `WP_INSTALLED` → skip bước này
- Nếu `WP_NOT_INSTALLED` → install:

```bash
ssh vbrand@54.169.34.13 "
cd /home/${DIR_NAME}/wordpress
wp core install \
  --url='http://${DOMAIN}' \
  --title='${COMPANY_NAME}' \
  --admin_user=admin \
  --admin_password='aA456321@' \
  --admin_email='admin@${DOMAIN}' \
  --skip-email
wp config set FS_METHOD direct
"
```

**Lưu ý**: `FS_METHOD=direct` bắt buộc vì WordPress chạy dưới user `vbrand` (đã có write permission). Nếu không set, WP sẽ fallback sang FTP → crash WooCommerce logging.

### Bước 5: Install WooCommerce + permalinks (vbrand@)

**Check trước:**

```bash
ssh vbrand@54.169.34.13 "cd /home/${DIR_NAME}/wordpress && wp plugin is-installed woocommerce 2>/dev/null && echo 'WOO_EXISTS' || echo 'WOO_NOT_FOUND'"
```

- Nếu `WOO_EXISTS` → chỉ activate nếu chưa active, skip install
- Nếu `WOO_NOT_FOUND` → install + activate:

```bash
ssh vbrand@54.169.34.13 "
cd /home/${DIR_NAME}/wordpress
wp plugin install woocommerce --activate
wp rewrite structure '/%postname%/' --hard
"
```

**Sau khi install**, cấu hình WooCommerce cho Việt Nam:

```bash
ssh vbrand@54.169.34.13 "
cd /home/${DIR_NAME}/wordpress
wp option update woocommerce_default_country 'VN'
wp option update woocommerce_store_city 'Hồ Chí Minh'
wp option update woocommerce_currency 'VND'
wp option update woocommerce_currency_pos 'right_space'
wp option update woocommerce_price_decimal_sep ','
wp option update woocommerce_price_thousand_sep '.'
wp option update woocommerce_price_num_decimals '0'
"
```

**Lưu ý**: `woocommerce_default_country = VN` là bắt buộc. WC Blocks dùng option này để khởi tạo country mặc định trong checkout form — nếu không set, Blocks sẽ hiển thị địa chỉ Mỹ (US). Plugin vbrandsync đã có filter `pre_option_woocommerce_default_country` nhưng set thẳng vào DB ở đây để chắc chắn.

### Bước 6: Sync plugin vbrandsync + themes từ local (rsync → vbrand@)

rsync qua `vbrand@` — file tự động thuộc `vbrand:vbrand`, không cần chown/chmod.

**Bước này LUÔN chạy** (rsync tự handle incremental sync).

```bash
# Sync plugin vbrandsync
rsync -avz --delete /Users/luan/apps/acelle_brand/vbrandsync/ vbrand@54.169.34.13:/home/${DIR_NAME}/wordpress/wp-content/plugins/vbrandsync/

# Sync toàn bộ themes
rsync -avz --delete /Users/luan/apps/vbrand/site/wp-content/themes/ vbrand@54.169.34.13:/home/${DIR_NAME}/wordpress/wp-content/themes/
```

### Bước 7: Cấu hình .env + install + migrate vbrandsync (vbrand@)

vbrandsync là Laravel app. `.env` được rsync từ local nên DB credentials sai → phải sửa cho đúng DB của site mới.

**Bước này LUÔN chạy** (rsync ghi đè .env mỗi lần sync).

**Lưu ý DB_PREFIX**: phải set `DB_PREFIX=wp_vbs_` để CLI (tinker, artisan) hoạt động đúng. Khi chạy qua WordPress, prefix được override bởi `AppServiceProvider` (`$table_prefix . 'vbs_'`).

```bash
ssh vbrand@54.169.34.13 "
cd /home/${DIR_NAME}/wordpress/wp-content/plugins/vbrandsync
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

### Bước 8: Activate plugin vbrandsync (vbrand@)

```bash
ssh vbrand@54.169.34.13 "
cd /home/${DIR_NAME}/wordpress
wp plugin activate vbrandsync 2>/dev/null || echo 'Plugin already active'
"
```

### Bước 9: Activate theme (vbrand@)

**Check trước:**

```bash
ssh vbrand@54.169.34.13 "cd /home/${DIR_NAME}/wordpress && wp theme list --status=active --field=name"
```

- Nếu output = `${THEME}` → skip (theme đã active)
- Nếu khác → kiểm tra theme có tồn tại không rồi activate:

```bash
ssh vbrand@54.169.34.13 "cd /home/${DIR_NAME}/wordpress && wp theme is-installed ${THEME} && wp theme activate ${THEME} || echo 'Theme ${THEME} not found'"
```

Nếu theme không tồn tại → báo user, skip bước này.

### Bước 10: Tạo nginx virtual host (ubuntu@)

**Check trước:**

```bash
ssh ubuntu@54.169.34.13 "test -f /etc/nginx/sites-available/${DOMAIN} && echo 'VHOST_EXISTS' || echo 'VHOST_NOT_FOUND'"
```

- Nếu `VHOST_EXISTS` → skip bước này
- Nếu `VHOST_NOT_FOUND` → tạo:

```bash
ssh ubuntu@54.169.34.13 "sudo tee /etc/nginx/sites-available/${DOMAIN} > /dev/null << 'NGINX_EOF'
server {
    server_name ${DOMAIN} www.${DOMAIN};

    root /home/${DIR_NAME}/wordpress;
    index index.php index.html index.htm;

    access_log /var/log/nginx/${DIR_NAME}.access.log;
    error_log  /var/log/nginx/${DIR_NAME}.error.log;

    location ~ /\. {
        deny all;
    }

    location / {
        try_files \$uri \$uri/ /index.php?\$args;
    }

    location ~ \.php\$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.vbrand.sock;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~* \.(jpg|jpeg|png|gif|ico|css|js|webp|svg|woff2?|ttf|eot)\$ {
        expires 30d;
        access_log off;
    }

    fastcgi_connect_timeout 300;
    fastcgi_send_timeout 300;
    fastcgi_read_timeout 300;
    proxy_read_timeout 300;
    send_timeout 300;

    client_max_body_size 64M;

    listen 80;
}
NGINX_EOF"
```

### Bước 11: Enable site + reload nginx (ubuntu@)

**Check trước:**

```bash
ssh ubuntu@54.169.34.13 "test -L /etc/nginx/sites-enabled/${DOMAIN} && echo 'ENABLED' || echo 'NOT_ENABLED'"
```

- Nếu `ENABLED` → skip symlink, chỉ reload nginx
- Nếu `NOT_ENABLED` → enable + reload:

```bash
ssh ubuntu@54.169.34.13 "
sudo ln -sf /etc/nginx/sites-available/${DOMAIN} /etc/nginx/sites-enabled/${DOMAIN}
sudo nginx -t && sudo systemctl reload nginx
"
```

### Bước 12: Setup SSL với Certbot (ubuntu@)

**Check trước:**

```bash
ssh ubuntu@54.169.34.13 "sudo certbot certificates 2>/dev/null | grep -q '${DOMAIN}' && echo 'SSL_EXISTS' || echo 'SSL_NOT_FOUND'"
```

- Nếu `SSL_EXISTS` → skip bước này
- Nếu `SSL_NOT_FOUND` → cài SSL:

```bash
ssh ubuntu@54.169.34.13 "sudo certbot --nginx -d ${DOMAIN} --non-interactive --agree-tos --email admin@${DOMAIN}"
```

Sau khi certbot chạy xong, nó tự sửa nginx config để redirect HTTP → HTTPS và thêm SSL certificate.

**Lưu ý**: Domain phải đã trỏ DNS A record về `54.169.34.13` trước khi chạy bước này. Nếu certbot fail vì DNS chưa trỏ → báo user và skip, có thể chạy lại sau.

**Sau khi SSL thành công**, phải update WordPress URLs sang HTTPS:

```bash
ssh vbrand@54.169.34.13 "
cd /home/${DIR_NAME}/wordpress
wp option update siteurl 'https://${DOMAIN}'
wp option update home 'https://${DOMAIN}'
wp search-replace 'http://${DOMAIN}' 'https://${DOMAIN}' --skip-columns=guid
"
```

Nếu SSL bị skip (DNS chưa trỏ) → KHÔNG update URLs, giữ nguyên `http://`.

Từ bước này trở đi, dùng `https://${DOMAIN}` thay vì `http://${DOMAIN}` cho tất cả endpoint (WP API, `brand_site_connections.endpoint_url`...).

### Bước 13: Tạo customer + user trên brand app (vbrand@)

**Check trước:** kiểm tra email đã tồn tại trên brand app chưa.

WP endpoint giờ nằm ở bảng plugin `brand_site_connections` (model `Acelle\Brand\Models\BrandSiteConnection`), KHÔNG còn ở cột `customers.wordpress_endpoint`.

```bash
ssh vbrand@54.169.34.13 'cd /home/vbrand/app && php artisan tinker --execute="
\$user = \App\Model\User::where(\"email\", \"${ADMIN_EMAIL}\")->first();
if (\$user) {
    \$customer = \$user->customer;
    \$conn = \Acelle\Brand\Models\BrandSiteConnection::where(\"customer_id\", \$customer->id)->first();
    echo \"EXISTS\n\";
    echo \"Customer ID: \" . \$customer->id . \"\n\";
    echo \"Customer UID: \" . \$customer->uid . \"\n\";
    echo \"Customer Name: \" . \$customer->name . \"\n\";
    echo \"User Email: \" . \$user->email . \"\n\";
    echo \"User API Token: \" . \$user->api_token . \"\n\";
    echo \"WP Endpoint: \" . (\$conn ? \$conn->endpoint_url : \"(chưa có connection)\") . \"\n\";
    echo \"Connection Status: \" . (\$conn ? \$conn->status : \"none\") . \"\n\";
} else {
    echo \"NOT_FOUND\n\";
}
"'
```

- Nếu `EXISTS` → skip tạo customer. Nếu chưa có row trong `brand_site_connections` (hoặc `endpoint_url` chưa đúng) → tạo/cập nhật connection ở **bước 14** (đừng set cột `wordpress_endpoint` — cột đó không còn dùng). Để kiểm tra nhanh connection của customer đã tồn tại:

```bash
ssh vbrand@54.169.34.13 'cd /home/vbrand/app && php artisan tinker --execute="
\$user = \App\Model\User::where(\"email\", \"${ADMIN_EMAIL}\")->first();
\$customer = \$user->customer;
\$conn = \Acelle\Brand\Models\BrandSiteConnection::where(\"customer_id\", \$customer->id)->first();
echo \"WP Endpoint: \" . (\$conn ? \$conn->endpoint_url : \"(chưa có — tạo ở bước 14)\") . \"\n\";
echo \"API Token: \" . \$user->api_token . \"\n\";
"'
```

- Nếu `NOT_FOUND` → tạo mới:

App mainline KHÔNG còn `Customer::createCustomerWithDefaultUser()`. Tạo account qua service `App\Services\AccountManagement\AccountProvisioningService::createCustomer()` (cùng thứ tự tham số, vẫn trả về `[$validator, $customer, $user]`). KHÔNG set `wordpress_endpoint` — connection tạo riêng ở **bước 14**.

```bash
ssh vbrand@54.169.34.13 'cd /home/vbrand/app && php artisan tinker --execute="
\$svc = app(\App\Services\AccountManagement\AccountProvisioningService::class);
list(\$validator, \$customer, \$user) = \$svc->createCustomer(
    null,
    \"${COMPANY_NAME}\",
    \"${TIMEZONE}\",
    \App\Model\Language::whereCode(\"vi\")->first()->id,
    \"${ADMIN_EMAIL}\",
    \"${BRAND_USER_PASS}\",
    \"${BRAND_USER_PASS}\",
    \"${FIRST_NAME}\",
    \"${LAST_NAME}\",
    null,
    \App\Model\Role::getDefaultAdminRole()->uid
);
if (\$validator->fails()) {
    echo \"ERRORS: \" . json_encode(\$validator->errors()) . \"\n\";
    exit(1);
}
echo \"SUCCESS\n\";
echo \"Customer ID: \" . \$customer->id . \"\n\";
echo \"Customer UID: \" . \$customer->uid . \"\n\";
echo \"Customer Name: \" . \$customer->name . \"\n\";
echo \"User Email: \" . \$user->email . \"\n\";
echo \"User API Token: \" . \$user->api_token . \"\n\";
"'
```

**Lưu output**: ghi lại `api_token` để dùng ở bước 14.

### Bước 14: Tạo connection Brand ↔ WordPress (vbrand@)

Connection giờ là **một row trong bảng plugin `brand_site_connections`** (1 row / customer), quản lý bởi `Acelle\Brand\Services\ConnectionService`. Gọi `connect()` MỘT lần là đủ cho **cả 2 phía**:
1. Lưu/cập nhật row `brand_site_connections` (`endpoint_url`, `tls_verify`, sinh `secret` lưu trong `auth_meta`, `status='connected'`) — phía Acelle.
2. Tự động **push endpoint + secret sang WP** (gọi `vbrandsync` `settings/update/api`) — phía WordPress. Vì vậy KHÔNG còn phải `Setting::set('vbrand_endpoint'/'vbrand_token')` thủ công như app cũ.

Lưu ý: auth giữa 2 phía giờ là **shared secret** (`X-Brand-Token`), KHÔNG còn dùng `api_token` của user. Customer ở bước 13 được resolve qua email.

**Check trước:** kiểm tra row connection đã tồn tại + đúng endpoint chưa.

```bash
ssh vbrand@54.169.34.13 'cd /home/vbrand/app && php artisan tinker --execute="
\$customer = \App\Model\User::where(\"email\", \"${ADMIN_EMAIL}\")->first()->customer;
\$conn = \Acelle\Brand\Models\BrandSiteConnection::where(\"customer_id\", \$customer->id)->first();
if (\$conn && \$conn->endpoint_url === \"https://${DOMAIN}/wp-json/vbrandsync/v1\") {
    echo \"EXISTS\n\";
    echo \"Endpoint: \" . \$conn->endpoint_url . \"\n\";
    echo \"Status: \" . \$conn->status . \"\n\";
} else {
    echo \"NOT_FOUND\n\";
}
"'
```

- Nếu `EXISTS` → skip (connection đã cấu hình)
- Nếu `NOT_FOUND` → tạo connection qua `ConnectionService::connect()`:

```bash
ssh vbrand@54.169.34.13 'cd /home/vbrand/app && php artisan tinker --execute="
\$customer = \App\Model\User::where(\"email\", \"${ADMIN_EMAIL}\")->first()->customer;
\$svc = app(\Acelle\Brand\Services\ConnectionService::class);
\$data = new \Acelle\Brand\Dto\ConnectionData(\"https://${DOMAIN}/wp-json/vbrandsync/v1\", true, null);
\$res = \$svc->connect(\$customer, \$data);
\$conn = \$res[\"connection\"];
echo \"Connection saved!\n\";
echo \"Endpoint: \" . \$conn->endpoint_url . \"\n\";
echo \"Status: \" . \$conn->status . \"\n\";
if (! empty(\$res[\"warning\"])) { echo \"WARNING (push to WP failed, non-fatal): \" . \$res[\"warning\"] . \"\n\"; }
"'
```

> **Lưu ý:** `ConnectionData($endpoint, $tls_verify, $secret)` — truyền `secret = null` để `connect()` tự sinh secret 40-ký-tự và đẩy sang WP. Nếu phần push sang WP fail (WP chưa reachable / SSL chưa xong) thì `connect()` vẫn lưu row Acelle-side và trả `warning` (không fatal) — chạy lại bước này sau khi WP sẵn sàng để đồng bộ lại secret.
>
> Cách khác (qua UI): đăng nhập brand app `https://app.sgconnect.vn` bằng customer vừa tạo → mở `/rui/brand/connection` → nhập endpoint `https://${DOMAIN}/wp-json/vbrandsync/v1` → Save. UI gọi cùng `ConnectionService::connect()`.

### Bước 15: Verify (vbrand@)

```bash
ssh vbrand@54.169.34.13 "
cd /home/${DIR_NAME}/wordpress
echo '=== Plugin list ==='
wp plugin list --status=active --format=table
echo '=== Site URL ==='
wp option get siteurl
"
```

### Bước 16: Ghi vào sites registry

**Check trước:** đọc `bots/report/sites.md`, kiểm tra DOMAIN đã có chưa.
- Nếu đã có → skip (không ghi đè)
- Nếu chưa có → thêm entry mới vào cuối file:

Format mỗi site:

```
## N. ${DOMAIN}
- DIR_NAME: ${DIR_NAME}
- DB_NAME: ${DIR_NAME}
- DB_USER: ${DIR_NAME}
- DB_PASS: aA456321@
- WP Admin: admin
- WP Pass: aA456321@
- WP Path: /home/${DIR_NAME}/wordpress
- WP API: https://${DOMAIN}/wp-json/vbrandsync/v1
- Brand App: https://app.sgconnect.vn (acelle mainline + plugin acelle/brand; connection screen /rui/brand/connection)
- Connection: brand_site_connections (customer_id=${CUSTOMER_ID}, endpoint_url=https://${DOMAIN}/wp-json/vbrandsync/v1, X-Brand-Token shared secret)
- Brand Token: ${API_TOKEN}
- Customer Name: ${COMPANY_NAME}
- Customer Email: ${ADMIN_EMAIL}
- Customer Password: ${BRAND_USER_PASS}
- First Name: ${FIRST_NAME}
- Last Name: ${LAST_NAME}
- Phone: ${PHONE}
- Theme: ${THEME}
- Timezone: ${TIMEZONE}
- SSL: Yes/No
- Created: ${NGÀY_HÔM_NAY}
```

### Output cuối cùng

Báo user, chỉ hiển thị các bước ĐÃ THỰC SỰ CHẠY (bỏ qua bước skip):

```
✅ Site đã tạo xong!

WordPress:
- URL: https://${DOMAIN}
- Admin: https://${DOMAIN}/wp-admin/
- Login: admin / aA456321@
- Database: ${DIR_NAME} (user: ${DIR_NAME})
- Source: /home/${DIR_NAME}/wordpress

Brand App (acelle mainline + plugin acelle/brand; path /home/vbrand/app → symlink /home/vbrand/app-new):
- URL: https://app.sgconnect.vn
- Customer workspace: https://app.sgconnect.vn/rui/brand
- Customer: ${COMPANY_NAME}
- Email: ${ADMIN_EMAIL}
- Password: ${BRAND_USER_PASS}
- API Token: ${API_TOKEN}

Kết nối (lưu ở brand_site_connections, quản lý bởi ConnectionService):
- Brand → WP: https://${DOMAIN}/wp-json/vbrandsync/v1 (Acelle gọi WP, auth bằng X-Brand-Token shared secret)
- WP → Brand: endpoint + secret được ConnectionService::connect() tự push vào vbrandsync settings (không set tay)

Registry: đã ghi vào bots/report/sites.md

Bước đã skip: [liệt kê các bước đã skip vì đã tồn tại]

⚠️ Nhớ trỏ DNS A record cho ${DOMAIN} về IP 54.169.34.13
```
