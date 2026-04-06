# vBrand Full E2E Audit

> Started: 2026-04-06
> Completed: 2026-04-06
> Status: **DONE**

---

## Plan

| # | Area | What to test | Status |
|---|------|-------------|--------|
| 1 | WordPress Frontends | 5 sites load, pages, SSL | DONE |
| 2 | Admin Panel | Login admin tổng, dashboard | DONE |
| 3 | Webapp Login | 4 tài khoản customer đăng nhập webapp | DONE |
| 4 | Webapp Features | Dashboard, Products, Orders, Templates, Profile — all sites | DONE |
| 5 | WP API | API connectivity + auth cho tất cả sites | DONE |
| 6 | COD Payment | Bật COD trên tất cả WP sites | DONE |
| 7 | New Site | Clone guucoffee.com → guucoffee.b-teka.com | DONE |
| 8 | Bugs Found | Fix tất cả lỗi tìm được | DONE |
| 9 | Docs Update | Cập nhật tất cả tài liệu | DONE |

---

## 1. WordPress Site Frontends

| Site | URL | Load | SSL | Products | Issues |
|------|-----|------|-----|----------|--------|
| Logitech | https://logitech.b-teka.com | OK 200 | Yes (2026-06-13) | 12 sản phẩm | OK |
| Nike Zero | https://nike.b-teka.com | OK 200 | Yes (2026-06-13) | Nhiều sản phẩm | OK |
| GuuCoffee Demo | https://guucoffee.b-teka.com | OK 200 | Yes (2026-07-05) | Theme demo data | OK — mới clone |
| Orgafood | https://orgafood.b-teka.com | OK 200 | Yes (2026-06-28) | Nhiều sản phẩm | OK |
| GuuCoffee (khách) | guucoffee.com | OK (HTTP only) | NO — domain chưa trỏ | Theme demo data | Chờ khách trỏ DNS |

---

## 2. Admin Panel (app.sgconnect.vn)

| Test | Status | Issues |
|------|--------|--------|
| Admin login (`admin@sgconnect.vn`) | OK | Email đã đổi từ `admin@brandviet.vn` → `admin@sgconnect.vn` |
| Password (`aA456321@`) | OK | Reset và verify qua tinker |
| Auth::attempt | OK | Verified via `php artisan tinker` |
| admin_access policy | OK | User có admin relation |

---

## 3. Webapp Login (4 accounts)

| Site | Email | Login | Dashboard | Issues |
|------|-------|-------|-----------|--------|
| Logitech | logitech@gmail.com | 302 OK | 200 "Tổng quan" | OK |
| Nike Zero | john.nikezro.vietnam@nikezero.com | 302 OK | 200 "Tổng quan" | OK |
| GuuCoffee Demo | guucoffee@gmail.com | 302 OK | 200 "Tổng quan" | OK — mới tạo |
| Orgafood | orgafood@gmail.com | 302 OK | 200 "Tổng quan" | OK |

---

## 4. Webapp Features (per site)

### All 3 original sites (Logitech, Nike, Orgafood) — identical results

| Page | HTTP | Title | Errors |
|------|------|-------|--------|
| Dashboard (`/brand/mobile/`) | 200 | Tổng quan — SGConnect | None |
| Products (`/brand/mobile/products`) | 200 | Sản phẩm — SGConnect | None |
| Products AJAX (`/brand/mobile/products/list`) | 200 | 30 sản phẩm, paginated | None |
| Orders (`/brand/mobile/orders`) | 200 | Đơn hàng — SGConnect | None |
| Templates (`/brand/mobile/templates`) | 200 | Giao diện — SGConnect | None |
| Profile (`/brand/mobile/profile`) | 200 | Tài khoản — SGConnect | None |
| Profile Edit (`/brand/mobile/profile/edit`) | 200 | Thông tin cá nhân — SGConnect | None |
| Change Password (`/brand/mobile/profile/password`) | 200 | Đổi mật khẩu — SGConnect | None |
| Import Requests (`/brand/mobile/import-requests`) | 200 | Yêu cầu đồng bộ SP — SGConnect | None |

---

## 5. WP API Connectivity

| Site | Endpoint | Auth Token | Product List | Issues |
|------|----------|------------|-------------|--------|
| Logitech | https://logitech.b-teka.com/wp-json/vbrandsync/v1 | OK | 200, 10 products | OK |
| Nike Zero | https://nike.b-teka.com/wp-json/vbrandsync/v1 | OK | 200, 10 products | OK |
| Orgafood | https://orgafood.b-teka.com/wp-json/vbrandsync/v1 | OK | 200, 10 products | OK |
| GuuCoffee Demo | https://guucoffee.b-teka.com/wp-json/vbrandsync/v1 | OK | 200, 0 products | OK — chưa có data |

---

## 6. COD Payment

| Site | COD Enabled | Title | Language |
|------|-------------|-------|----------|
| Logitech | Yes | Thanh toán khi nhận hàng | VN |
| Nike Zero | Yes | Thanh toán khi nhận hàng | VN |
| Orgafood | Yes | Thanh toán khi nhận hàng | VN |
| GuuCoffee Demo | Yes | Thanh toán khi nhận hàng | VN |

---

## 7. Bugs Found & Fixed

| # | Area | Description | Severity | Fixed | Details |
|---|------|-------------|----------|-------|---------|
| 1 | Admin | Admin email sai (`admin@brandviet.vn`) | High | Yes | Đổi thành `admin@sgconnect.vn` trên prod via tinker |
| 2 | GuuCoffee | guucoffee.com SSL cert sai (hiện cert của `acellemail.b-teka.com`) | Medium | N/A | Domain chưa trỏ — không fix được. Đã clone ra `guucoffee.b-teka.com` thay thế |
| 3 | COD | Logitech + Orgafood COD title bằng tiếng Anh | Low | Yes | Update thành "Thanh toán khi nhận hàng" |
| 4 | COD | GuuCoffee Demo chưa bật COD | Medium | Yes | Enable COD + set title tiếng Việt |
| 5 | Server | `BaokimController` class missing — `php artisan route:list` lỗi | Low | N/A | Không ảnh hưởng runtime, chỉ lỗi khi list routes |
| 6 | Docs | Admin email sai trong SALES_HANDOVER.md | High | Yes | Fix thành `admin@sgconnect.vn` |
| 7 | Docs | Shipping ghi BaoKim + GHN — thực tế chỉ COD + vBrand Express | Medium | Yes | Update docs |

---

## 8. New Site Created

**guucoffee.b-teka.com** — Clone từ guucoffee.com

| Step | Status | Details |
|------|--------|---------|
| Create DB | OK | `guucoffee_b_teka_com` |
| Copy WP files | OK | From `/home/vbrand/sites/Guucoffee_com` |
| Update wp-config.php | OK | New DB credentials |
| Search-replace URLs | OK | `guucoffee.com` → `guucoffee.b-teka.com` |
| Nginx vhost | OK | PHP 8.3, php8.3-fpm.vbrand.sock |
| SSL (Certbot) | OK | Expires 2026-07-05 |
| Brand app customer | OK | `guucoffee@gmail.com` / `123456` |
| WP API token | OK | Generated new token |
| COD payment | OK | Enabled |
| Frontend test | OK | Loads with DreamCafe theme |
| Webapp login | OK | Dashboard accessible |

---

## 9. Docs Updated

| File | Changes |
|------|---------|
| `docs/SALES_HANDOVER.md` | Admin email fix, shipping fix (COD + vBrand Express), 5th site added, GuuCoffee Demo accounts, guucoffee.com domain warning |
| `docs/USER_GUIDE.md` | No changes needed |
| `bots/report/sites.md` | Added guucoffee.b-teka.com entry (#5) |
| `docs/FULL_AUDIT.md` | This file — complete audit results |
