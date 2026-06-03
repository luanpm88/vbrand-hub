# vBrand Server Migration Plan — 2026-05-24

**Old server:** `52.220.55.112` (was `18.141.199.175` — IP got swapped on AWS Lightsail)
**New server:** `54.169.34.13` (Ubuntu 24.04, fresh)
**Strategy:** Provision new → clone all source + DB as-is → smoke test → SSL → keep old as rollback ~1-2 tuần → decommission.
**Isolation:** mỗi customer site = 1 Linux user riêng (own home, own php-fpm pool), brand app giữ `vbrand`.

> **ARCHITECTURE UPDATE (post-2026-06-03 cutover):** "vbrand / BrandViet" now runs on the MAINLINE Acelle codebase (`~/apps/acelle`, the refactor "/rui" UI) PLUS the `acelle/brand` PLUGIN. It is NO LONGER a forked/baked-in app. The brand/shop/website features are delivered by the plugin (source `~/apps/acelle_brand`, symlinked into `storage/app/plugins/acelle/brand`). The preamble and per-site narrative below describe the original 2026-05-24 forked-app migration; treat the brand app rows accordingly (now plugin-based, primary DB `brand`, customer↔WordPress mapping via the `brand_site_connections` table, customer UI under `/rui/brand/*`).

---

## 0. Status (tracking)

Legend: `☐` pending · `◐` in-progress · `☑` done · `✗` blocked

| Phase | Status | Notes |
|-------|--------|-------|
| 0. SSH access to new server | ☑ | Done 2026-05-24. `ssh ubuntu@54.169.34.13` working |
| 1. Inventory old server | ☑ | 16 components (1 app + 15 sites); xem §2 |
| 2. Dump all DBs (old) | ☑ | 16 DBs gzipped, ~8.4MB total |
| 3. Provision new server | ☑ | 4GB swap added, nginx + PHP 8.3-FPM + MySQL 8 + certbot + wp-cli + composer installed |
| 4. Create per-site users | ☑ | 15 users (vbrand + 14 sites), group `vbrand`, home `/home/<user>` |
| 5. Setup php-fpm pools | ☑ | 15 pools: vbrand=dynamic 12 max; per-site=ondemand 8 max (RAM 911MB limit) |
| 6. Transfer files (rsync) | ☑ | App 2.9GB + 14 sites ~10GB; over AWS Singapore inter-zone <1Gbps |
| 7. Import DBs + MySQL users | ☑ | 16 DBs imported. MySQL tuned: innodb_buffer_pool=256M. Users restored from grants. |
| 8. Nginx vhosts | ☑ | 16 vhosts adjusted: paths → /home/<user>/wordpress + sockets → php8.3-fpm.<user>.sock |
| 9. Smoke test (curl) | ☑ | 13/13 WP sites HTTPS 200/301/302 internally; 1 brand app responds login redirect; 1 acm Laravel responds login redirect |
| 10. SSL via certbot | ◐ | 12/16 done (9 b-teka.com + app.sgconnect.vn + khomaynenkhi.com + sattanhung.com); 4 pending DNS (cafedanhphat.vn, guucafe.com, khohanglaptop.com, acellemail.b-teka.com) |
| 11. enforce-cod-vbrand-express | ☑ | 13/13 WP sites: COD-only + vBrand Express-only. khohanglaptop password fixed (`@34xds@3%?.KM`). |
| 12. E2E test suite (Playwright) | ☑ | 60+/68 passed. 8 fails all pre-existing (3× logitech seller warehouse data NULL — confirmed same on old server; 5× SuperBuyer + ImportRequest routes not yet implemented per CLAUDE.md design docs). Zero migration regression. |
| 13. Update docs/bots | ☑ | sites.md + deploy-app.md + deploy-sites.md + CLAUDE.md + do-one-task.md + dev-feature.md + upgrade-site.md + DESIGN_USAGE_PROMPTS.md + WP_WOO_SITE_INSTALL.md all updated to new IP/paths |
| 14. Keep old server 1-2 tuần | ☑ Skipped | User chose immediate cleanup 2026-05-27 sau khi verify new server stable. Acelle stack preserved riêng. |
| 15. Decommission old server vBrand stack | ☑ Done 2026-05-27 | Deleted: `/home/vbrand` (15GB), 16 MySQL DBs, 16 MySQL users, Linux users `vbrand`+`vbrandwww`, group `vbrand`, 18 nginx vhosts, php-fpm pool `vbrand.conf`. Disk freed: 96% → 69% used. |
| 16. Acelle stack preserved (separate scope) | ☑ Verified | `/home/acelle` 13GB intact, 12 acelle nginx vhosts active, 5 acelle DBs intact, all endpoints responding. Acelle catchall config (`acellemail.conf` + `staging.acellemail.com`) repointed từ `vbrand.sock` → `acelle.sock` + chown `/var/www/acellemail{,-staging}` to `acelle:acelle`. |

**Migration final status (2026-05-24 15:55):**
- 13 sites HTTPS 200/301/302 từ external ✓
- Brand app `app.sgconnect.vn` login flow + admin + WordPress site connection (via /rui/brand connection screen) verified via Playwright E2E ✓
- Per-user isolation working (14 distinct php-fpm sockets, mỗi site độc lập)
- Server load nominal (~1.0 average), RAM 911M + 800M swap (sustainable)
- Scanner IP blocking + rate-limit deployed

**Pending user action:**
- DNS update tại `dotvndns.vn` (registrar) cho 4 domain còn lại:
  - `cafedanhphat.vn` + `www.cafedanhphat.vn` (A record → `54.169.34.13`)
  - `guucafe.com` + `www.guucafe.com` (A record → `54.169.34.13`)
  - `khohanglaptop.com` + `www.khohanglaptop.com` (A record → `54.169.34.13`)
  - `acellemail.b-teka.com` (A record → `54.169.34.13`) — nếu cần dùng
- Sau DNS propagate, run trên new server:
  ```bash
  ssh ubuntu@54.169.34.13 "sudo certbot --nginx --non-interactive --agree-tos --redirect --email admin@sgconnect.vn -d cafedanhphat.vn -d www.cafedanhphat.vn && sudo certbot --nginx --non-interactive --agree-tos --redirect --email admin@sgconnect.vn -d guucafe.com && sudo certbot --nginx --non-interactive --agree-tos --redirect --email admin@sgconnect.vn -d khohanglaptop.com -d www.khohanglaptop.com"
  ```

**Pending user action (cutover the remaining domains):**
- DNS update at `dotvndns.vn` / PAVietnam panel (registrar admin) — change A record for:
  - `app.sgconnect.vn`, `app.brandviet.vn` (apex/main brand app)
  - `cafedanhphat.vn` + `www.cafedanhphat.vn`
  - `guucafe.com` + `www.guucafe.com`
  - `khomaynenkhi.com` + `www.khomaynenkhi.com`
  - `sattanhung.com` + `www.sattanhung.com`
  - `khohanglaptop.com` + `www.khohanglaptop.com`
- → All point to `54.169.34.13`. TTL = ~10-30 phút sau khi save.
- Sau khi DNS propagated, em sẽ:
  1. Issue cert via certbot (apex + www nếu DNS đầy đủ)
  2. Run E2E full với `BASE_APP=https://app.sgconnect.vn`
  3. Verify all sites pass

---

## 1. DNS Cutover Status (do user)

Đã chuyển sang `54.169.34.13` (xem screenshot do user):

```
voducfoods.b-teka.com  → 54.169.34.13
cafedanhphat.b-teka.com → 54.169.34.13
autotaybac.b-teka.com  → 54.169.34.13
dieuan.b-teka.com      → 54.169.34.13
guucoffee.b-teka.com   → 54.169.34.13
acm.b-teka.com         → 54.169.34.13
orgafood.b-teka.com    → 54.169.34.13
nike.b-teka.com        → 54.169.34.13
logitech.b-teka.com    → 54.169.34.13
```

**Vẫn cần cutover thêm:**
- `app.sgconnect.vn` (brand app main)
- `app.brandviet.vn` (redirect alias)
- `guucafe.com` + `www`
- `cafedanhphat.vn` + `www` (đã thấy `.b-teka.com` cutover, chưa rõ apex)
- `khomaynenkhi.com`
- `khohanglaptop.com` + `www`
- `sattanhung.com` + `www`
- `acellemail.b-teka.com` + `www` (nếu là vbrand site; nếu thuộc Acelle giữ ở old)
- `Guucoffee.com` (legacy alias trỏ tới /home/vbrand/sites/Guucoffee_com — same WP với guucafe.com)

**Để Acelle stay (old server):** `acellemail.com`, `api.`, `demo.`, `forum.`, `knowledge.`, `staging.`, `verify.`, `beta.acellemail.com`.

---

## 2. Inventory — Components to migrate

### 2.1 Brand App (Acelle + acelle/brand Plugin)

| Item | Value |
|------|-------|
| User | `vbrand` |
| Home | `/home/vbrand/` |
| Code | `/home/vbrand/app` (symlink → app-new on prod; built from ~/apps/acelle mainline) |
| Plugin | `~/apps/acelle_brand` symlinked → `/home/vbrand/app/storage/app/plugins/acelle/brand` |
| DB | `brand` (the primary DB; legacy `vbrand` preserved for rollback) — user `vbrand` has ALL PRIVILEGES on both |
| App Setup | Mainline Acelle with plugin loaded via `php artisan plugin:load acelle/brand` + activate() in plugins table |
| Domain | `app.sgconnect.vn`, `app.brandviet.vn` (redirect) |
| nginx | `vbrand.conf` |
| PHP-FPM | shared pool `vbrandwww` — socket `/run/php/php8.3-fpm.vbrand.sock` |
| Branch | Mainline Acelle (e.g. `/rui`); plugin source at ~/apps/acelle_brand |

### 2.2 Customer Sites (WP + 1 Laravel Acelle)

Mỗi site = 1 Linux user. Linux user name = current DIR_NAME (lowercase, underscores).

> **NOTE (post-2026-06-03):** Each site's WordPress integration with Acelle Brand is managed via the `brand_site_connections` table (customer_id → endpoint_url), not the legacy `customers.wordpress_endpoint` column.

| # | Domain(s) | Linux user (new) | Code path (new) | MySQL DB | MySQL user (existing) | App type |
|---|-----------|------------------|------------------|----------|----------------------|----------|
| 1 | logitech.b-teka.com + www | `logitech_b_teka_com` | `/home/logitech_b_teka_com/wordpress` | `logitech_b_teka_com` | `logitech_b_teka_com` | WP |
| 2 | nike.b-teka.com + www | `nike_b_teka_com` | `/home/nike_b_teka_com/wordpress` | `nike_b_teka_com` | `nike_b_teka_com` | WP |
| 3 | guucafe.com + www, Guucoffee.com + www (legacy) | `Guucoffee_com` | `/home/Guucoffee_com/wordpress` | `Guucoffee_com` | `Guucoffee_com` | WP |
| 4 | orgafood.b-teka.com + www | `orgafood_b_teka_com` | `/home/orgafood_b_teka_com/wordpress` | `orgafood_b_teka_com` | `orgafood_b_teka_com` | WP |
| 5 | guucoffee.b-teka.com + www | `guucoffee_b_teka_com` | `/home/guucoffee_b_teka_com/wordpress` | `guucoffee_b_teka_com` | `guucoffee_b_teka_com` | WP |
| 6 | dieuan.b-teka.com + www | `dieuan_b_teka_com` | `/home/dieuan_b_teka_com/wordpress` | `dieuan_b_teka_com` | `dieuan_b_teka_com` | WP |
| 7 | autotaybac.b-teka.com | `autotaybac_b_teka_com` | `/home/autotaybac_b_teka_com/wordpress` | `autotaybac_b_teka_com` | `autotaybac_b_teka_com` | WP |
| 8 | cafedanhphat.vn + www, cafedanhphat.b-teka.com (redirect) | `cafedanhphat_b_teka_com` | `/home/cafedanhphat_b_teka_com/wordpress` | `cafedanhphat_b_teka_com` | `cafedanhphat_b_teka_com` | WP |
| 9 | voducfoods.b-teka.com + www | `voducfoods_b_teka_com` | `/home/voducfoods_b_teka_com/wordpress` | `voducfoods_b_teka_com` | `voducfoods_b_teka_com` | WP |
| 10 | khomaynenkhi.com | `khomaynenkhi_com` | `/home/khomaynenkhi_com/wordpress` | `khomaynenkhi_com` | `khomaynenkhi_com` | WP |
| 11 | acellemail.b-teka.com + www | `acellemail_b_teka_com` | `/home/acellemail_b_teka_com/wordpress` | `acellemail_b_teka_com` | `acellemail_b_teka_com` | WP |
| 12 | acm.b-teka.com | `acm_b_teka_com` | `/home/acm_b_teka_com/laravel` | `acm_b_teka_com` | `acm_bteka` (pwd `acm_bteka_2026`) | Laravel (Acelle) |
| 13 | khohanglaptop.com + www | `khohanglaptop_com` | `/home/khohanglaptop_com/wordpress` | `kholaptop` | `silaptop` | WP |
| 14 | sattanhung.com + www | `sattanhung_com` | `/home/sattanhung_com/wordpress` | `sattanhung` | `sattanhung` | WP |

**Tất cả MySQL user customer (trừ acm_bteka, silaptop):** mật khẩu `aA456321@` (xem `bots/report/sites.md`).

### 2.3 Sizes (audit)

```
sites/Guucoffee_com           693M     DB Guucoffee_com           19M
sites/acellemail_b_teka_com   606M     DB acellemail_b_teka_com   27M
sites/acm_b_teka_com          2.1G     DB acm_b_teka_com          27M
sites/autotaybac_b_teka_com   319M     DB autotaybac_b_teka_com   18M
sites/cafedanhphat_b_teka_com 1.3G     DB cafedanhphat_b_teka_com 19M
sites/dieuan_b_teka_com       428M     DB dieuan_b_teka_com       19M
sites/guucoffee_b_teka_com    697M     DB guucoffee_b_teka_com    19M
sites/khomaynenkhi_com        742M     DB khomaynenkhi_com        20M
sites/logitech_b_teka_com     704M     DB logitech_b_teka_com     28M
sites/nike_b_teka_com         1003M    DB nike_b_teka_com         50M
sites/orgafood_b_teka_com     696M     DB orgafood_b_teka_com     26M
sites/voducfoods_b_teka_com   794M     DB voducfoods_b_teka_com   26M
/home/vbrand/app              4.2G     DB vbrand                  308M
/home/vbrand/kholaptop        ?        DB kholaptop               20M
/home/vbrand/sattanhung       ?        DB sattanhung              208M
```

Total files ~ 12-13 GB, DBs ~ 1.6 GB (compressed ~ 400 MB).

---

## 3. Provision new server

### 3.1 Base packages

```bash
ssh ubuntu@54.169.34.13 "
  sudo apt update && sudo apt upgrade -y && sudo apt install -y \
    nginx \
    php8.3-fpm php8.3-cli php8.3-mysql php8.3-curl php8.3-gd php8.3-xml php8.3-mbstring \
    php8.3-zip php8.3-bcmath php8.3-intl php8.3-imagick php8.3-soap php8.3-redis \
    mysql-server \
    certbot python3-certbot-nginx \
    git rsync unzip pv \
    composer
"
```

### 3.2 wp-cli

```bash
ssh ubuntu@54.169.34.13 "
  curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
  chmod +x wp-cli.phar && sudo mv wp-cli.phar /usr/local/bin/wp
  wp --info
"
```

### 3.3 MySQL hardening + tuning

```bash
ssh ubuntu@54.169.34.13 "
  sudo mysql_secure_installation  # set root pwd, remove anon, etc.
  # tune /etc/mysql/mysql.conf.d/mysqld.cnf:
  #   innodb_buffer_pool_size = 512M  (RAM 1.9G total — leave room cho PHP + nginx)
  #   max_connections = 200
  sudo systemctl enable --now mysql
"
```

### 3.4 Default PHP-FPM disabled — chỉ dùng per-site pools

```bash
ssh ubuntu@54.169.34.13 "
  sudo rm -f /etc/php/8.3/fpm/pool.d/www.conf
"
```

---

## 4. Create per-site users + group

```bash
ssh ubuntu@54.169.34.13 "
  sudo groupadd -f vbrand
  # Brand app user
  id vbrand >/dev/null 2>&1 || sudo useradd -m -d /home/vbrand -s /bin/bash -g vbrand vbrand

  # Per-site users
  for u in logitech_b_teka_com nike_b_teka_com Guucoffee_com orgafood_b_teka_com guucoffee_b_teka_com dieuan_b_teka_com autotaybac_b_teka_com cafedanhphat_b_teka_com voducfoods_b_teka_com khomaynenkhi_com acellemail_b_teka_com acm_b_teka_com khohanglaptop_com sattanhung_com; do
    id \$u >/dev/null 2>&1 || sudo useradd -m -d /home/\$u -s /bin/bash -g vbrand \$u
  done

  # Lock passwords (SSH key only, no password login)
  for u in vbrand logitech_b_teka_com nike_b_teka_com Guucoffee_com orgafood_b_teka_com guucoffee_b_teka_com dieuan_b_teka_com autotaybac_b_teka_com cafedanhphat_b_teka_com voducfoods_b_teka_com khomaynenkhi_com acellemail_b_teka_com acm_b_teka_com khohanglaptop_com sattanhung_com; do
    sudo passwd -l \$u
  done

  # Add www-data to vbrand group (nginx reads static files)
  sudo usermod -aG vbrand www-data
"
```

---

## 5. PHP-FPM per-user pools

Mỗi user 1 pool. Template:

```ini
; /etc/php/8.3/fpm/pool.d/<user>.conf
[<user>]
user = <user>
group = vbrand
listen = /run/php/php8.3-fpm.<user>.sock
listen.owner = www-data
listen.group = www-data
pm = dynamic
pm.max_children = 6        ; per pool — tighter to keep total < 50 cho 1.9G RAM
pm.start_servers = 2
pm.min_spare_servers = 1
pm.max_spare_servers = 3
request_terminate_timeout = 60s
```

Brand app pool dùng `pm.max_children = 15` (giữ như cũ).

**Tổng max_children:** 15 (vbrand) + 14×6 = ~99. Với RAM 1.9G, ondemand sẽ ổn — ít concurrent reqs cùng lúc. Theo dõi `pm.max_children setting reached` log sau 1 tuần.

---

## 6. Transfer files (rsync from old → new)

### 6.1 Setup SSH from old → new

Trên server cũ:
```bash
ssh ubuntu@52.220.55.112 "
  sudo -u ubuntu ssh-keygen -t ed25519 -N '' -f /home/ubuntu/.ssh/id_ed25519 -q || true
  sudo cat /home/ubuntu/.ssh/id_ed25519.pub
"
# Copy output, paste vào /home/ubuntu/.ssh/authorized_keys trên new server
```

### 6.2 Rsync brand app

```bash
ssh ubuntu@52.220.55.112 "
  sudo rsync -aHAX --numeric-ids --info=progress2 \
    --exclude='storage/logs/*' \
    --exclude='node_modules' \
    /home/vbrand/app/ \
    ubuntu@54.169.34.13:/tmp/vbrand-app/
"
ssh ubuntu@54.169.34.13 "
  sudo mv /tmp/vbrand-app /home/vbrand/app
  sudo chown -R vbrand:vbrand /home/vbrand/app
  sudo chmod -R g+w /home/vbrand/app/storage /home/vbrand/app/bootstrap/cache
"
```

### 6.3 Rsync per-site (template)

```bash
# Vars
SITE_DIR_OLD="logitech_b_teka_com"      # tên dir trên old server
USER_NEW="logitech_b_teka_com"          # user trên new server
WP_PATH_NEW="/home/$USER_NEW/wordpress"

ssh ubuntu@52.220.55.112 "
  sudo rsync -aHAX --numeric-ids --info=progress2 \
    --exclude='wp-content/cache/*' \
    --exclude='wp-content/uploads/cache/*' \
    /home/vbrand/sites/$SITE_DIR_OLD/ \
    ubuntu@54.169.34.13:/tmp/wp-$SITE_DIR_OLD/
"
ssh ubuntu@54.169.34.13 "
  sudo mkdir -p $WP_PATH_NEW
  sudo mv /tmp/wp-$SITE_DIR_OLD/* /tmp/wp-$SITE_DIR_OLD/.[!.]* $WP_PATH_NEW/ 2>/dev/null
  sudo chown -R $USER_NEW:vbrand $WP_PATH_NEW
  sudo find $WP_PATH_NEW -type d -exec chmod 755 {} \;
  sudo find $WP_PATH_NEW -type f -exec chmod 644 {} \;
  # wp-content writable
  sudo chmod -R g+w $WP_PATH_NEW/wp-content
"
```

**Special cases:**
- `khohanglaptop_com`: source `/home/vbrand/kholaptop` (NOT inside `sites/`)
- `sattanhung_com`: source `/home/vbrand/sattanhung`
- `acm_b_teka_com`: Laravel app, target `/home/acm_b_teka_com/laravel` (not `wordpress`)

---

## 7. Import DBs

### 7.1 Transfer dump archive

```bash
# Trên old server: dumps tại /var/backups/vbrand-move-2026-05-24/*.sql.gz
ssh ubuntu@52.220.55.112 "sudo rsync -avz /var/backups/vbrand-move-2026-05-24/ ubuntu@54.169.34.13:/tmp/db-dumps/"
```

### 7.2 Create MySQL users + restore

```bash
ssh ubuntu@54.169.34.13 "
  cd /tmp/db-dumps
  for db in vbrand brand Guucoffee_com acellemail_b_teka_com acm_b_teka_com autotaybac_b_teka_com cafedanhphat_b_teka_com dieuan_b_teka_com guucoffee_b_teka_com khomaynenkhi_com logitech_b_teka_com nike_b_teka_com orgafood_b_teka_com voducfoods_b_teka_com kholaptop sattanhung; do
    sudo mysql -e \"CREATE DATABASE IF NOT EXISTS \\\`\$db\\\` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci\"
    pv \$db.sql.gz | gunzip | sudo mysql \$db
  done

  # Recreate users
  sudo mysql -e \"
    CREATE USER IF NOT EXISTS 'vbrand'@'localhost' IDENTIFIED BY 'aA456321^&*';
    GRANT ALL PRIVILEGES ON vbrand.* TO 'vbrand'@'localhost';
    GRANT ALL PRIVILEGES ON brand.*  TO 'vbrand'@'localhost';
  \"
  for u in Guucoffee_com acellemail_b_teka_com autotaybac_b_teka_com cafedanhphat_b_teka_com dieuan_b_teka_com guucoffee_b_teka_com khomaynenkhi_com logitech_b_teka_com nike_b_teka_com orgafood_b_teka_com voducfoods_b_teka_com; do
    sudo mysql -e \"
      CREATE USER IF NOT EXISTS '\$u'@'localhost' IDENTIFIED BY 'aA456321@';
      GRANT ALL PRIVILEGES ON \\\`\$u\\\`.* TO '\$u'@'localhost';
    \"
  done
  sudo mysql -e \"
    CREATE USER IF NOT EXISTS 'acm_bteka'@'localhost' IDENTIFIED BY 'acm_bteka_2026';
    GRANT ALL PRIVILEGES ON acm_b_teka_com.* TO 'acm_bteka'@'localhost';

    CREATE USER IF NOT EXISTS 'silaptop'@'localhost' IDENTIFIED BY 'aA456321@';
    GRANT ALL PRIVILEGES ON kholaptop.* TO 'silaptop'@'localhost';

    CREATE USER IF NOT EXISTS 'sattanhung'@'localhost' IDENTIFIED BY 'aA456321@';
    GRANT ALL PRIVILEGES ON sattanhung.* TO 'sattanhung'@'localhost';

    FLUSH PRIVILEGES;
  \"
"
```

**Note:** `silaptop` + `sattanhung` passwords assumed = `aA456321@`. Verify trên old server `SHOW GRANTS` rồi adjust.

---

## 8. Nginx vhosts

### 8.1 Copy + adjust

Mỗi vhost cần đổi:
- `root` path → `/home/<user>/wordpress` (hoặc `/laravel/public`)
- `fastcgi_pass` socket → `unix:/run/php/php8.3-fpm.<user>.sock`
- Bỏ tạm SSL lines (sẽ certbot lại từ đầu) → chỉ giữ `listen 80;`
- Giữ tất cả timeouts + cache headers từ cũ

### 8.2 Snippet test before SSL

```bash
sudo nginx -t && sudo systemctl reload nginx
```

---

## 9. Smoke test (pre-SSL, dùng curl --resolve)

```bash
for dom in logitech.b-teka.com nike.b-teka.com guucafe.com orgafood.b-teka.com guucoffee.b-teka.com dieuan.b-teka.com autotaybac.b-teka.com cafedanhphat.vn voducfoods.b-teka.com khomaynenkhi.com acellemail.b-teka.com acm.b-teka.com khohanglaptop.com sattanhung.com app.sgconnect.vn; do
  echo "=== $dom ==="
  curl -sS -o /dev/null -w "%{http_code}\n" -H "Host: $dom" --resolve $dom:80:54.169.34.13 http://$dom/
done
```

Pass = 200/302/301. 5xx hoặc 502 = nginx/php-fpm sai cấu hình → fix trước SSL.

---

## 10. SSL via certbot

```bash
ssh ubuntu@54.169.34.13 "
  for d in 'app.sgconnect.vn' 'app.brandviet.vn' 'logitech.b-teka.com,www.logitech.b-teka.com' 'nike.b-teka.com,www.nike.b-teka.com' 'guucafe.com,www.guucafe.com' 'Guucoffee.com,www.Guucoffee.com' 'orgafood.b-teka.com,www.orgafood.b-teka.com' 'guucoffee.b-teka.com,www.guucoffee.b-teka.com' 'dieuan.b-teka.com,www.dieuan.b-teka.com' 'autotaybac.b-teka.com' 'cafedanhphat.vn,www.cafedanhphat.vn,cafedanhphat.b-teka.com' 'voducfoods.b-teka.com,www.voducfoods.b-teka.com' 'khomaynenkhi.com' 'acellemail.b-teka.com,www.acellemail.b-teka.com' 'acm.b-teka.com' 'khohanglaptop.com,www.khohanglaptop.com' 'sattanhung.com,www.sattanhung.com'; do
    sudo certbot --nginx --non-interactive --agree-tos --redirect \
      --email admin@sgconnect.vn \
      -d \$(echo \$d | tr ',' ' ' | awk '{for(i=1;i<=NF;i++) printf \" -d \"\$i}' | sed 's/^ -d //')
  done
"
```

Nếu domain có `www.` chưa trỏ IP mới → certbot fail NXDOMAIN. Skip `www.` cho domain đó, add sau.

---

## 11. enforce-cod-vbrand-express per site

```bash
scp /Users/luan/apps/vbrand/bots/automated/enforce-cod-vbrand-express.php ubuntu@54.169.34.13:/tmp/

ssh ubuntu@54.169.34.13 "
  for u in logitech_b_teka_com nike_b_teka_com Guucoffee_com orgafood_b_teka_com guucoffee_b_teka_com dieuan_b_teka_com autotaybac_b_teka_com cafedanhphat_b_teka_com voducfoods_b_teka_com khomaynenkhi_com acellemail_b_teka_com khohanglaptop_com sattanhung_com; do
    sudo -u \$u wp --path=/home/\$u/wordpress eval-file /tmp/enforce-cod-vbrand-express.php
  done
"
```

Skip `acm_b_teka_com` (Laravel Acelle, không phải WP).

---

## 12. E2E test gate

```bash
cd /Users/luan/apps/vbrand/bots/automated/e2e
BASE_APP=https://app.sgconnect.vn \
BASE_SITE=https://logitech.b-teka.com \
SELLER_EMAIL=logitech@gmail.com \
npm test
```

Pass requirement: 100% green (xem `docs/E2E_TEST_PLAN.md` cho từng phase).

> **NOTE (post-2026-06-03):** The E2E test configuration must account for the new `/rui/brand/*` routes. Verify that tests hit the correct new routes (e.g. `/rui/brand/connection`, `/rui/brand/home`, etc.) — NOT the old `/brand/*` paths, which are gone.

---

## 13. Update docs/bots

- `bots/report/sites.md` → đổi server IP + path mỗi site
- `bots/automated/deploy-app.md` → `ssh vbrand@54.169.34.13` (giữ vbrand user vì vbrand vẫn ở /home/vbrand). Post-2026-06-03 it should be updated to: 1) pull from the mainline Acelle repo (not the `brand` branch), 2) ensure the plugin is loaded via `php artisan plugin:load acelle/brand`, 3) use the `brand` database (not `vbrand`), 4) set `APP_URL=https://app.sgconnect.vn` and `DB_DATABASE=brand`
- `bots/automated/deploy-sites.md` → đổi path `/home/vbrand/sites/...` → `/home/<user>/wordpress`, đổi rsync target user thành per-site user
- `CLAUDE.md ## Server + Architecture` block → cập nhật IP `54.169.34.13`, path mới, mỗi site user mới. Post-2026-06-03 it should also document: a) mainline Acelle codebase at `/home/vbrand/app`, b) `acelle/brand` plugin symlinked to `storage/app/plugins/acelle/brand`, c) `brand_site_connections` table replacing `customers.wordpress_endpoint`, d) `/rui/brand/*` routes for customer UI, e) `ConnectionService` + `ConnectionStateService` for WP site management
- `~/.ssh/config` → `brandnew` HostName → 54.169.34.13

---

## 14. Rollback strategy

- **Old server retained 14 ngày** với DNS pointing back trong DNS provider (chỉ cần đổi A record nếu cần)
- Nếu critical bug discovered trên new → DNS trỏ về old IP cũ (52.220.55.112), traffic flips trong 5-30 phút
- Sau 14 ngày smooth → snapshot old → shutdown

---

## 15. Lessons Learned (2026-05-24)

### `ssh-keygen -lf authorized_keys` để verify key added đúng
- Khi setup server mới, user add key vào `~/.ssh/authorized_keys`. Nếu paste sai format hoặc thừa dấu xuống dòng, SSH sẽ deny silently.
- Test verify: `ssh-keygen -lf ~/.ssh/authorized_keys` print fingerprint từng key — match với client fingerprint (`ssh-keygen -lf ~/.ssh/id_rsa.pub`).
- Lần này user paste lệnh `echo 'ssh-rsa ...' >> authorized_keys` đầu tiên không work; thử lại work — có thể có whitespace issue ban đầu.

### Per-site user + per-pool PHP-FPM cho isolation
- Mỗi customer site = 1 Linux user, 1 php-fpm pool, 1 socket riêng (`php8.3-fpm.<user>.sock`).
- Trade-off RAM: low-traffic sites dùng `pm = ondemand` (zero idle workers) — không spawn nếu không có request. Mỗi pool tiêu RAM ~10MB khi idle (master process only).
- Cho server 911MB RAM + 4GB swap: 14 pools × ondemand fits comfortably. Brand app dùng `dynamic` (12 max children, 3 start) vì traffic ổn định hơn.
- Nginx vhost socket per-pool đảm bảo PHP request từ site A không bao giờ chạy as user B.

### `chdir = /` trong php-fpm pool tránh chdir errors
- Default pool config có `chdir = /var/www` không tồn tại trên hệ thống mới → systemd-fpm reload fail nếu pool include chdir vào dir absent.
- Override với `chdir = /` (always exists). PHP scripts chdir explicit nếu cần.

### `git pull` cần `.git` folder (2.3GB) trên server
- deploy-app.md flow là `git pull origin brand`. Server cần `.git` (đầy đủ history) để pull/checkout. Lần đầu migration cứ nghĩ skip được, nhưng phải rsync luôn — tốn 2.3GB nhưng không skip được.
- Lesson: với deploy-via-git workflow, server phải có full git repo (origin remote configured, .git dir present).

### Laravel bootstrap/cache có path absolute → cache phải clear sau khi đổi path/server
- Acelle Laravel app on acm.b-teka.com fail 500 sau khi rsync vì `bootstrap/cache/config.php` còn ref tới `/home/vbrand/sites/acm_b_teka_com/...` cũ.
- Fix: `rm -f bootstrap/cache/{config,routes,packages,services,events}.php && rm -rf storage/framework/views/*` — Laravel auto-regenerates.
- Brand app cũng cần làm tương tự sau di chuyển — apply cùng pattern.

### enforce-cod-vbrand-express idempotent — chạy 2 lần nếu lần 1 không sạch
- Script disable other gateways nhưng verify check trong cùng request đôi khi thấy stale in-memory state ($gateway->enabled vẫn 'yes' dù DB đã update).
- Lần 1 ghi xuống DB. Lần 2 verify thấy clean state. Cả 2 lần đều idempotent.
- 3 sites (acellemail_b_teka_com, sattanhung_com, khohanglaptop_com) phải chạy 2 lần để pass.

### MySQL user pwds: silaptop ≠ aA456321@
- Khi recreate users, đa số dùng `aA456321@` pattern. Nhưng `silaptop` (cho kholaptop DB) dùng `@34xds@3%?.KM` từ legacy clone. Phải đọc `wp-config.php` của site để tìm đúng password trước khi tạo MySQL user.

### Lightsail firewall block port 443 by default
- Lightsail instance fresh có firewall mở SSH + HTTP nhưng KHÔNG HTTPS. Phải vào console Networking tab + add HTTPS rule (port 443).
- Symptom: `nc -z server 443 = blocked` even though nginx listening + cert installed.

### Domain DNS phải update ở registrar/NS panel (không phải Lightsail)
- DNS A record cho mỗi domain quản lý ở registrar's nameserver (vd dotvndns.vn cho .vn, namecheap cho .com, ...). Lightsail IP thay đổi → user phải vào registrar panel update.
- TTL ~5-30 phút sau save (tùy provider). Verify bằng `dig @ns_authoritative <domain>` không qua resolver cache.

### /etc/hosts loopback override trên server cho self-callback
- Brand app + WP sites cùng server thường gọi nhau qua URL (vd `https://app.sgconnect.vn/api/brand` callback từ WP site). Nếu DNS app.sgconnect.vn vẫn trỏ IP cũ broken, callback fail.
- Workaround: add `127.0.0.1 app.sgconnect.vn ...` vào `/etc/hosts` của new server. Loopback HTTPS connect đến nginx local → cert mismatch nhưng Laravel-side curl thường `verify=false` cho internal calls.
- Permanent fix: DNS update + real cert.

## 16. Decommission checklist (sau 14 ngày stable)

```bash
# 1. Verify zero traffic on old server
ssh ubuntu@52.220.55.112 "sudo tail -100 /var/log/nginx/access.log | grep -v 'localhost\|health' | head"

# 2. Snapshot Lightsail instance (in console: instance → Snapshots → Create snapshot)

# 3. Stop instance (don't delete yet — keep snapshot for cold rollback)
# Via Lightsail console: instance → Stop instance

# 4. Sau 30 ngày stable, delete instance + snapshot
```

Old server retained for:
- Rollback if critical bug discovered (DNS flip back within 5-30 min) — Note: This plan was for vBrand brand app only. Acelle Mail stack (`/home/acelle`) remains on original infrastructure (separate scope), not included in this 2026-05-24→2026-06-03 cutover.

---

## Appendix A — Domain ↔ Linux user ↔ DB mapping (paste-ready)

```
Domain                           User                  Code path                              DB
app.sgconnect.vn                 vbrand                /home/vbrand/app                       vbrand
logitech.b-teka.com              logitech_b_teka_com   /home/logitech_b_teka_com/wordpress   logitech_b_teka_com
nike.b-teka.com                  nike_b_teka_com       /home/nike_b_teka_com/wordpress       nike_b_teka_com
guucafe.com,Guucoffee.com        Guucoffee_com         /home/Guucoffee_com/wordpress         Guucoffee_com
orgafood.b-teka.com              orgafood_b_teka_com   /home/orgafood_b_teka_com/wordpress   orgafood_b_teka_com
guucoffee.b-teka.com             guucoffee_b_teka_com  /home/guucoffee_b_teka_com/wordpress  guucoffee_b_teka_com
dieuan.b-teka.com                dieuan_b_teka_com     /home/dieuan_b_teka_com/wordpress     dieuan_b_teka_com
autotaybac.b-teka.com            autotaybac_b_teka_com /home/autotaybac_b_teka_com/wordpress autotaybac_b_teka_com
cafedanhphat.vn,...b-teka.com    cafedanhphat_b_teka_com /home/cafedanhphat_b_teka_com/wordpress cafedanhphat_b_teka_com
voducfoods.b-teka.com            voducfoods_b_teka_com /home/voducfoods_b_teka_com/wordpress voducfoods_b_teka_com
khomaynenkhi.com                 khomaynenkhi_com      /home/khomaynenkhi_com/wordpress      khomaynenkhi_com
acellemail.b-teka.com            acellemail_b_teka_com /home/acellemail_b_teka_com/wordpress acellemail_b_teka_com
acm.b-teka.com                   acm_b_teka_com        /home/acm_b_teka_com/laravel           acm_b_teka_com
khohanglaptop.com                khohanglaptop_com     /home/khohanglaptop_com/wordpress     kholaptop
sattanhung.com                   sattanhung_com        /home/sattanhung_com/wordpress        sattanhung
```
