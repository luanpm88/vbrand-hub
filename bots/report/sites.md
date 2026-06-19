# vBrand Production Sites

Danh sách tất cả WordPress sites đã deploy trên server `54.169.34.13` (Lightsail Singapore, Ubuntu 24.04.4 LTS, 1 vCPU / 911 MiB RAM + 4 GiB swap / 38 GB disk).

**Server migration (2026-05-24):** moved from `18.141.199.175` → `54.169.34.13`. Mỗi site = 1 Linux user riêng (isolated home + isolated php-fpm pool socket). Xem [`docs/SERVER_MOVE_PLAN.md`](../../docs/SERVER_MOVE_PLAN.md).

**Server stack:**
- nginx 1.24 (Ubuntu)
- PHP 8.3.6 FPM — pool per user: socket `/run/php/php8.3-fpm.<user>.sock`
- MySQL 8.0.45 — innodb_buffer_pool=256M, max_connections=100, max_allowed_packet=64M
- Let's Encrypt SSL via certbot (auto-renew)
- Swap: 4 GiB (`/swapfile`, vm.swappiness=10)
- iptables firewall: scanner IPs blocked + nginx-level dot-file rate-limit

**Convention (per-site Linux user):**
| Item | Value |
|------|-------|
| Linux user | DIR_NAME (1:1 mapping) |
| Group | `vbrand` (for www-data read + cross-site shared dirs) |
| Home | `/home/<DIR_NAME>/` |
| WP root | `/home/<DIR_NAME>/wordpress/` (or `/laravel/` cho acm) |
| Owner | `<DIR_NAME>:vbrand` |
| FPM socket | `/run/php/php8.3-fpm.<DIR_NAME>.sock` |
| FPM pool config | `/etc/php/8.3/fpm/pool.d/<DIR_NAME>.conf` |
| PM mode | `ondemand`, max_children=4 (per-site) / vbrand `dynamic`, max_children=8 |

**Old server (52.220.55.112, previously 18.141.199.175):** giữ chạy ~14 ngày làm rollback. Acelle Mail stack (`/home/acelle`, ~12 GB) vẫn ở old server — KHÔNG di chuyển trong migration này. Decommission sau khi new server stable.

---

## 1. logitech.b-teka.com
- DIR_NAME: logitech_b_teka_com
- DB_NAME: logitech_b_teka_com
- DB_USER: logitech_b_teka_com
- DB_PASS: aA456321@
- WP Admin: admin
- WP Pass: aA456321@
- WP Path: /home/logitech_b_teka_com/wordpress
- Linux user: `logitech_b_teka_com` (group `vbrand`)
- PHP-FPM socket: `/run/php/php8.3-fpm.logitech_b_teka_com.sock`
- WP API: https://logitech.b-teka.com/wp-json/vbrandsync/v1
- Brand Endpoint: https://app.sgconnect.vn/api/brand
- Brand Token: gozN9aURtXN6FZEwjGkOrTlSorYWPlA10d8iSvdY15PsKt9dkY87enxfpiAh
- Customer Name: Logitech
- Customer Email: logitech@gmail.com
- Customer Password: Logitech@2026
- First Name: Admin
- Last Name: Shop
- Phone:
- Theme: logitech
- Timezone: Asia/Ho_Chi_Minh
- SSL: Yes (issued 2026-05-24 — expires 2026-08-22, auto-renew via certbot.timer)
- Created: 2026-03-15

## 2. nike.b-teka.com
- DIR_NAME: nike_b_teka_com
- DB_NAME: nike_b_teka_com
- DB_USER: nike_b_teka_com
- DB_PASS: aA456321@
- WP Admin: admin
- WP Pass: aA456321@
- WP Path: /home/nike_b_teka_com/wordpress
- Linux user: `nike_b_teka_com` (group `vbrand`)
- PHP-FPM socket: `/run/php/php8.3-fpm.nike_b_teka_com.sock`
- WP API: https://nike.b-teka.com/wp-json/vbrandsync/v1
- Brand Endpoint: https://app.sgconnect.vn/api/brand
- Brand Token: 2v52Jz4FylpVdYp6fKqLFueLVwpJhAiRNx7GR6r4CbgFc0uI038r2v9qRLC2
- Customer Name: Nike Zero Vietnam
- Customer Email: john.nikezro.vietnam@nikezero.com
- Customer Password: Nike@2026
- First Name: John
- Last Name: Nguyễn
- Phone:
- Theme: logitech
- Timezone: Asia/Ho_Chi_Minh
- SSL: Yes (issued 2026-05-24 — expires 2026-08-22, auto-renew via certbot.timer)
- Created: 2026-03-15

---

## 3. guucafe.com (formerly Guucoffee.com)
- DIR_NAME: Guucoffee_com
- DB_NAME: Guucoffee_com
- DB_USER: Guucoffee_com
- DB_PASS: aA456321@
- WP Admin: admin
- WP Pass: aA456321@
- WP Path: /home/Guucoffee_com/wordpress
- Linux user: `Guucoffee_com` (group `vbrand`)
- PHP-FPM socket: `/run/php/php8.3-fpm.Guucoffee_com.sock`
- WP API: https://guucafe.com/wp-json/vbrandsync/v1
- Brand Endpoint: https://app.sgconnect.vn/api/brand
- Brand Token: BZPSmpdy1vSUlF77OaFlqa2ytgdgR9mdoOKvg7lsD1Tg86IelHFi4kXSHfLQ
- Customer Name: GuuCoffee
- Customer Email: marketingmientrung@gmail.com
- Customer Password: Aa456321$
- First Name: Anh
- Last Name: Linh
- Phone: 0888 755 468
- Address: Tổ 47, An Khê, TP. Đà Nẵng
- Theme: dreamcafe
- Timezone: Asia/Ho_Chi_Minh
- SSL: Yes — re-issued on new server 2026-06-08 (expires 2026-09-06, auto-renew via certbot.timer). DNS trỏ về 54.169.34.13 + certbot 2026-06-08; trước đó HTTPS rơi vào default vhost → bounce app login (đã fix). apex only (www.guucafe.com chưa có A record).
- Created: 2026-03-22
- Domain changed: 2026-04-15 (guucoffee.com → guucafe.com)
- Contact updated: 2026-05-20 (phone/email/address synced to theme.options + admin_email + WC store)

## 4. orgafood.b-teka.com
- DIR_NAME: orgafood_b_teka_com
- DB_NAME: orgafood_b_teka_com
- DB_USER: orgafood_b_teka_com
- DB_PASS: aA456321@
- WP Admin: admin
- WP Pass: aA456321@
- WP Path: /home/orgafood_b_teka_com/wordpress
- Linux user: `orgafood_b_teka_com` (group `vbrand`)
- PHP-FPM socket: `/run/php/php8.3-fpm.orgafood_b_teka_com.sock`
- WP API: https://orgafood.b-teka.com/wp-json/vbrandsync/v1
- Brand Endpoint: https://app.sgconnect.vn/api/brand
- Brand Token: OUoMvcdmAorpMW5H7xtHdlLJ1DSIz3H6jrGPIWKPajnrpleNOyKKuAS6USxr
- Customer Name: Orgafood
- Customer Email: orgafood@gmail.com
- Customer Password: Orgafood@2026
- First Name: Admin
- Last Name: Shop
- Phone:
- Theme: orgafood
- Timezone: Asia/Ho_Chi_Minh
- SSL: Yes (issued 2026-05-24 — expires 2026-08-22, auto-renew via certbot.timer)
- Created: 2026-03-30

## 5. guucoffee.b-teka.com
- DIR_NAME: guucoffee_b_teka_com
- DB_NAME: guucoffee_b_teka_com
- DB_USER: guucoffee_b_teka_com
- DB_PASS: aA456321@
- WP Admin: admin
- WP Pass: aA456321@
- WP Path: /home/guucoffee_b_teka_com/wordpress
- Linux user: `guucoffee_b_teka_com` (group `vbrand`)
- PHP-FPM socket: `/run/php/php8.3-fpm.guucoffee_b_teka_com.sock`
- WP API: https://guucoffee.b-teka.com/wp-json/vbrandsync/v1
- Brand Endpoint: https://app.sgconnect.vn/api/brand
- Brand Token: da709526b662d99eed7166daf07273f2a41a6f45713047843cb68291fdfe
- Customer Name: GuuCoffee Demo
- Customer Email: guucoffee@gmail.com
- Customer Password: Guucoffee@2026
- First Name: Anh
- Last Name: Linh
- Phone:
- Theme: dreamcafe
- Timezone: Asia/Ho_Chi_Minh
- SSL: Yes (issued 2026-05-24 — expires 2026-08-22, auto-renew via certbot.timer)
- Created: 2026-04-06
- Note: Clone từ guucoffee.com (site #3) — dùng làm demo, domain b-teka.com

## 6. dieuan.b-teka.com
- DIR_NAME: dieuan_b_teka_com
- DB_NAME: dieuan_b_teka_com
- DB_USER: dieuan_b_teka_com
- DB_PASS: aA456321@
- WP Admin: admin
- WP Pass: aA456321@
- WP Path: /home/dieuan_b_teka_com/wordpress
- Linux user: `dieuan_b_teka_com` (group `vbrand`)
- PHP-FPM socket: `/run/php/php8.3-fpm.dieuan_b_teka_com.sock`
- WP API: https://dieuan.b-teka.com/wp-json/vbrandsync/v1
- Brand Endpoint: https://app.sgconnect.vn/api/brand
- Brand Token: 72499529ad5115fa36ec4faf7b0758cfc7b3654be993263945f5be276659
- Customer Name: Diệu An
- Customer Email: dieuan@gmail.com
- Customer Password: Dieuan@2026
- Theme: dieu-an
- Timezone: Asia/Ho_Chi_Minh
- SSL: Yes (issued 2026-05-24 — expires 2026-08-22, auto-renew via certbot.timer)
- Created: 2026-04-15
- Note: Van công nghiệp — upgraded from vancongnghiepdieuan.com.vn via upgrade-site.md bot

## 7. autotaybac.b-teka.com
- DIR_NAME: autotaybac_b_teka_com
- DB_NAME: autotaybac_b_teka_com
- DB_USER: autotaybac_b_teka_com
- DB_PASS: aA456321@
- WP Admin: admin
- WP Pass: aA456321@
- WP Path: /home/autotaybac_b_teka_com/wordpress
- Linux user: `autotaybac_b_teka_com` (group `vbrand`)
- PHP-FPM socket: `/run/php/php8.3-fpm.autotaybac_b_teka_com.sock`
- WP API: https://autotaybac.b-teka.com/wp-json/vbrandsync/v1
- Brand Endpoint: https://app.sgconnect.vn/api/brand
- Brand Token: 065fa78ae9bffc55dff2fbdd22f9d7512a67e2e4f6bb25314529bb345f57
- Customer Name: Auto Tây Bắc
- Customer Email: autotaybac@gmail.com
- Customer Password: Autotaybac@2026
- Theme: autotaybac
- Timezone: Asia/Ho_Chi_Minh
- SSL: Yes (issued 2026-05-24 — expires 2026-08-22, auto-renew via certbot.timer)
- Created: 2026-04-15
- Note: Chăm Sóc & Làm Đẹp Xe Toàn Diện
## 8. cafedanhphat.vn
- DIR_NAME: cafedanhphat_b_teka_com
- DB_NAME: cafedanhphat_b_teka_com
- DB_USER: cafedanhphat_b_teka_com
- DB_PASS: aA456321@
- WP Admin: admin
- WP Pass: aA456321@
- WP Path: /home/cafedanhphat_b_teka_com/wordpress
- Linux user: `cafedanhphat_b_teka_com` (group `vbrand`)
- PHP-FPM socket: `/run/php/php8.3-fpm.cafedanhphat_b_teka_com.sock`
- WP API: https://cafedanhphat.vn/wp-json/vbrandsync/v1
- Brand Endpoint: https://app.sgconnect.vn/api/brand
- Brand Token: Lu4I6W6O7H3WMwE8cSGf2RtHJYjsPMDNfr6E59yvVyM0mQw1RtJvFRtDBo9v
- Customer Name: Cà phê Danh Phát
- Customer Email: cafedanhanphat@gmail.com
- Customer Password: Cafedanhphat@2026
- First Name: Anh
- Last Name: Phát
- Phone: 0772 62 64 68
- Theme: cafedanhphat
- Timezone: Asia/Ho_Chi_Minh
- SSL: Yes — re-issued on new server 2026-06-08 (expires 2026-09-06, auto-renew via certbot.timer) — covers cafedanhphat.vn + www.cafedanhphat.vn. DNS (apex+www) trỏ về 54.169.34.13 + certbot 2026-06-08; trước đó HTTPS rơi vào default vhost → bounce app login (đã fix).
- Created: 2026-04-28
- Domain switched: 2026-04-28 (cafedanhphat.b-teka.com → cafedanhphat.vn — official domain)
- Aliases: cafedanhphat.b-teka.com → 301 redirect to cafedanhphat.vn (legacy)
- Note: Cà phê phân phối Đà Nẵng & miền Trung — upgraded from old cafedanhphat.vn HTML site, copied dreamcafe theme + customized

## 9. voducfoods.b-teka.com
- DIR_NAME: voducfoods_b_teka_com
- DB_NAME: voducfoods_b_teka_com
- DB_USER: voducfoods_b_teka_com
- DB_PASS: aA456321@
- WP Admin: admin
- WP Pass: aA456321@
- WP Path: /home/voducfoods_b_teka_com/wordpress
- Linux user: `voducfoods_b_teka_com` (group `vbrand`)
- PHP-FPM socket: `/run/php/php8.3-fpm.voducfoods_b_teka_com.sock`
- WP API: https://voducfoods.b-teka.com/wp-json/vbrandsync/v1
- Brand Endpoint: https://app.sgconnect.vn/api/brand
- Brand Token: LjhNYZk6wCjg1QoSicYvgJ0vwAWuIuLyu7dE1MkfYVtNsSsyLuDhWmLQM06T
- Customer Name: Võ Đức FOOD
- Customer Email: voducfoods@gmail.com
- Customer Password: Voduc@2026
- First Name: Admin
- Last Name: Shop
- Phone:
- Theme: voducfoods
- Site title: "Võ Đức — Vịt Quay Da Giòn"
- Site tagline: "Giòn · Ngon · Chất — Sỉ & Lẻ + Đào tạo nghề"
- Hotline: 0379 901 887 — Email: voducquay@gmail.com — Khu vực: Đồng Nai
- Brand model (dual revenue): (1) Sỉ & Lẻ vịt quay/heo quay/gà nướng (2) Đào tạo nghề — cam kết học viên thành nghề
- Đặc sản: Vịt quay da giòn / Vịt quay truyền thống / Heo quay / Gà nướng mắm nhĩ
- Logo: custom JPG badge mascot (vịt cosplay võ thuật + Pickelhaube helmet) tại `assets/images/logo/VoDuc_Logo.jpg` — provided by client 2026-05-10
- Brand banners (reference only, KHÔNG dùng trên web): `assets/images/banner.jpg` + `banner_info.jpg` — lưu để giữ ý tưởng nội dung + bảng màu
- Palette: lacquer red (#B5251D) + gold (#F4D44A) + roasted brown (#4A2C1F) + cream (#FFF8EC) — match banner 100%
- Timezone: Asia/Ho_Chi_Minh
- SSL: Yes (issued 2026-05-24 — expires 2026-08-22, auto-renew via certbot.timer) — apex only (www CNAME chưa trỏ)
- Created: 2026-05-10
- Cloned from: orgafood.b-teka.com (mysqldump + cp -r WP folder + wp search-replace + new theme `voducfoods` cloned từ `orgafood`)
- Note: F&B đặc sản — Vịt da giòn / vịt nướng — kế thừa 38 products từ orgafood (cần sau này import lại products thật của Võ Đức). Theme name "Võ Đức FOOD" giữ trong style.css, brand display dùng "Võ Đức — Vịt Da Giòn".

## 10. khomaynenkhi.com
- DIR_NAME: khomaynenkhi_com
- DB_NAME: khomaynenkhi_com
- DB_USER: khomaynenkhi_com
- DB_PASS: aA456321@
- WP Admin: admin
- WP Pass: aA456321@
- WP Path: /home/khomaynenkhi_com/wordpress
- Linux user: `khomaynenkhi_com` (group `vbrand`)
- PHP-FPM socket: `/run/php/php8.3-fpm.khomaynenkhi_com.sock`
- WP API: https://khomaynenkhi.com/wp-json/vbrandsync/v1
- Brand Endpoint: https://app.sgconnect.vn/api/brand
- Brand Token: 6FiEPK0CwveFIMsrcJ7M3U4YKTXEjbYGtzDm4qKdOeEHBZfRQbGekhMSdgQg
- Customer ID: 17
- Customer Name: Kho Máy Nén Khí
- Customer Email: khomaynenkhi@gmail.com
- Customer Password: Khomaynenkhi@2026
- First Name: Admin
- Last Name: Shop
- Phone:
- Theme: khomaynenkhi
- Site title: "Kho Máy Nén Khí"
- Site tagline: "Thiết bị nén khí công nghiệp — Trục vít / Piston / Sấy khí / Phụ tùng"
- Hotline: 0901 234 567 — Email: contact@khomaynenkhi.com — Khu vực: TP.HCM + Bình Dương
- Brand model: B2B máy nén khí công nghiệp đa thương hiệu (Jaguar, Atlas Copco, Hanbell, Sullair, Fusheng, Hitachi, Kobelco) — nhà máy / xưởng cơ khí / dệt may / thực phẩm / dược phẩm / hóa chất
- Categories: Máy Nén Khí Trục Vít / Piston / Không Dầu / Máy Sấy Khí / Bình Chứa / Lọc Khí / Phụ Tùng / Dầu Bôi Trơn
- Palette: industrial blue `#1B4D89` + accent orange `#E8792B` — inherited from dieu-an base
- Logo: custom SVG `assets/images/logo.svg` — air-tank icon + KHO MÁY NÉN KHÍ wordmark (gradient blue + orange accent dot)
- Timezone: Asia/Ho_Chi_Minh
- SSL: Yes (issued 2026-05-24 — expires 2026-08-22, auto-renew via certbot.timer) — apex only (www CNAME chưa trỏ)
- Created: 2026-05-20
- Cloned from: dieuan.b-teka.com (wp db export + rsync WP files + theme `dieu-an` → `khomaynenkhi` với sed prefix `da-` → `kmnk-`)
- Products: 67 sản phẩm scrape từ ductrico.com qua **bot mới `scrape-woocommerce-store.js`** (WooCommerce Store API, không cần browser) — máy nén khí Jaguar trục vít / piston / không dầu + máy sấy khí + bình chứa + phụ tùng đa hãng. Categories: 24.
- Pricing: "Liên hệ" (price=0, B2B inquiry) — match nguồn ductrico.com
- Note: Site đầu tiên dùng new WC Store API scraper. Server PHP-FPM `pm.max_children` hiện 15 — đủ cho 10 sites; theo dõi nếu tăng tải.

## 11. acellemail.b-teka.com
- DIR_NAME: acellemail_b_teka_com
- DB_NAME: acellemail_b_teka_com
- DB_USER: acellemail_b_teka_com
- DB_PASS: aA456321@
- WP Admin: admin
- WP Pass: aA456321@
- WP Path: /home/acellemail_b_teka_com/wordpress
- Linux user: `acellemail_b_teka_com` (group `vbrand`)
- PHP-FPM socket: `/run/php/php8.3-fpm.acellemail_b_teka_com.sock`
- WP API: https://acellemail.b-teka.com/wp-json/vbrandsync/v1
- Brand Endpoint: https://app.sgconnect.vn/api/brand
- Theme: (inherited from previous setup)
- Timezone: Asia/Ho_Chi_Minh
- SSL: ⏳ DNS chưa trỏ — chờ user. Cert sẽ issue sau khi `acellemail.b-teka.com` resolve về `54.169.34.13`.
- Created: pre-migration (date unknown — discovered trong migration audit)
- Note: vBrand stack demo site cho Acelle Mail. Có thể là demo site nội bộ.

## 12. acm.b-teka.com  (Acelle License Manager — Laravel, NOT WordPress)
- DIR_NAME: acm_b_teka_com
- DB_NAME: acm_b_teka_com
- DB_USER: `acm_bteka` (NOT acm_b_teka_com — exception)
- DB_PASS: `acm_bteka_2026` (NOT aA456321@ — exception)
- App Path: /home/acm_b_teka_com/laravel
- Linux user: `acm_b_teka_com` (group `vbrand`)
- PHP-FPM socket: `/run/php/php8.3-fpm.acm_b_teka_com.sock`
- App URL: https://acm.b-teka.com
- APP_KEY: `base64:HqqNO41Fft15c/VR5DdH3WtAWyyUbEnnBakYF1r+A0M=` (sensitive — Laravel encryption key)
- Stack: Laravel (Acelle license manager — separate from acelle.com main app)
- Theme: n/a (Laravel — not WP)
- Timezone: Asia/Ho_Chi_Minh
- SSL: Yes (issued 2026-05-24 — expires 2026-08-22, auto-renew via certbot.timer)
- Created: pre-migration
- Note: KHÔNG chạy enforce-cod-vbrand-express (Laravel, không phải WC). Sau migration phải clear `bootstrap/cache/{config,routes,packages,services,events}.php` để regenerate path-absolute caches.

## 13. khohanglaptop.com  (Legacy WP — separate stack from /home/vbrand/sites/)
- DIR_NAME: khohanglaptop_com
- DB_NAME: `kholaptop` (NOT khohanglaptop_com — legacy name)
- DB_USER: `silaptop` (NOT khohanglaptop_com — legacy name)
- DB_PASS: `@34xds@3%?.KM` (NOT aA456321@ — legacy password)
- WP Admin: admin
- WP Pass: (legacy, check site)
- WP Path: /home/khohanglaptop_com/wordpress
- Linux user: `khohanglaptop_com` (group `vbrand`)
- PHP-FPM socket: `/run/php/php8.3-fpm.khohanglaptop_com.sock`
- WP API: https://khohanglaptop.com/wp-json/vbrandsync/v1
- Brand Endpoint: https://app.sgconnect.vn/api/brand
- Theme: (legacy logitech clone)
- Timezone: Asia/Ho_Chi_Minh
- SSL: ⏳ DNS chưa trỏ — chờ user. Cert sẽ issue sau khi `khohanglaptop.com` resolve về `54.169.34.13`.
- Created: pre-migration (was at `/home/vbrand/kholaptop` on old server)
- Note: Source pre-migration path was `/home/vbrand/kholaptop` (NOT inside `sites/`). Original clone từ logitech.dump (xem file `logitech.dump` ở wp root).

## 14. sattanhung.com
- DIR_NAME: sattanhung_com
- DB_NAME: `sattanhung` (NOT sattanhung_com — legacy name)
- DB_USER: `sattanhung` (matches DB name)
- DB_PASS: aA456321@
- WP Admin: admin (email logitech@brandviet.vn — legacy clone)
- WP Pass: aA456321@ (reset 2026-06-08 — was legacy/unknown, now standardized)
- WP Path: /home/sattanhung_com/wordpress
- Linux user: `sattanhung_com` (group `vbrand`)
- PHP-FPM socket: `/run/php/php8.3-fpm.sattanhung_com.sock`
- WP API: https://sattanhung.com/wp-json/vbrandsync/v1
- Brand App: https://app.sgconnect.vn (acelle mainline + plugin acelle/brand; connection screen /rui/brand/connection)
- Connection: brand_site_connections (customer_id=18, endpoint_url=https://sattanhung.com/wp-json/vbrandsync/v1, status=connected, X-Brand-Token shared secret) — connected 2026-06-08
- Brand Token (X-Brand-Token shared secret): knvg2S6KnvtkHQVkLNjys7np9uWdgOx6lSnS9K4j
- Customer ID: 18
- Customer Name: Sắt Tân Hưng
- Customer Email: sattanhung@gmail.com
- Customer Password: Sattanhung@2026
- First Name: Admin
- Last Name: Shop
- Theme: (legacy logitech clone)
- Timezone: Asia/Ho_Chi_Minh
- SSL: Yes (issued 2026-05-24 — expires 2026-08-22, auto-renew via certbot.timer) — covers `sattanhung.com` + `www.sattanhung.com`
- Created: pre-migration (was at `/home/vbrand/sattanhung` on old server)
- Connected to brand app: 2026-06-08 (customer 18 created 05:33, brand_site_connection wired via ConnectionService::connect; loopback /etc/hosts → connect-time BRAND_ALLOW_PRIVATE_ENDPOINTS override, operational path unaffected)
- Note: Source pre-migration path was `/home/vbrand/sattanhung` (NOT inside `sites/`). Originally clone từ logitech.dump.

---

## Server-wide accounts

| Component | Account | Path | DB | Notes |
|-----------|---------|------|----|----|
| Brand app (Laravel) | Linux `vbrand`, MySQL `vbrand` (pwd `aA456321^&*`) | `/home/vbrand/app` | `vbrand` (main, 308 MB) + `brand` (ads, 31 MB) | App URL `https://app.sgconnect.vn`. Branch `brand`. |
| SSH access | `ubuntu@54.169.34.13` | sudo capable | — | Use for deploy + nginx + system ops |
| SSH access | `vbrand@54.169.34.13` | `/home/vbrand` only | — | Use for git pull / brand app deploy (xem `bots/automated/deploy-app.md`) |

## Pending DNS cutover (users phải update)

| Domain | Current IP | Target IP | Status |
|--------|-----------|-----------|--------|
| `cafedanhphat.vn` + `www.cafedanhphat.vn` | 18.141.199.175 | 54.169.34.13 | ✅ done 2026-06-08 (DNS apex+www trỏ + SSL issued) |
| `guucafe.com` | 18.141.199.175 | 54.169.34.13 | ✅ done 2026-06-08 (DNS trỏ + SSL issued) |
| `www.guucafe.com` | NX | 54.169.34.13 (CNAME apex hoặc A) | ⏳ chờ (apex đã work) |
| `Guucoffee.com` (legacy) | NX | (optional) | ⏳ ngừng dùng |
| `app.brandviet.vn` (redirect alias) | 18.141.199.175 | 54.169.34.13 | ⏳ chờ |
| `khohanglaptop.com` + `www.` | NX | 54.169.34.13 | ⏳ chờ |
| `acellemail.b-teka.com` + `www.` | NX | 54.169.34.13 | ⏳ chờ |
| `www.khomaynenkhi.com` | NX | 54.169.34.13 (CNAME apex) | ⏳ chờ (apex đã work) |
| `www.<site>.b-teka.com` cho 7 b-teka subdomains | NX | 54.169.34.13 (CNAME apex) | ⏳ chờ (apex đã work) |

Sau khi DNS resolve về `54.169.34.13`, run:
```bash
ssh ubuntu@54.169.34.13 "sudo certbot --nginx --non-interactive --agree-tos --redirect --email admin@sgconnect.vn -d <domain>"
```
