# vBrand — E2E Test Plan (Playwright)

> Phụ thuộc: `docs/SALES_HANDOVER.md`, `docs/USER_GUIDE_DESKTOP.md`, `docs/USER_GUIDE_MOBILE.md`
> Mục tiêu: Đảm bảo **mọi tính năng** được mô tả trong các tài liệu sales/user-guide đều hoạt động đúng — chạy local trước, sau đó staging/prod.
> Stack: **Playwright + TypeScript**, chạy trong `bots/automated/e2e/`.
> Phạm vi: **Tất cả platform trừ mobile app (Expo)** — chỉ test web (Desktop dashboard, Mobile webapp, WP storefront).

---

## Targets

| Env | Brand app (Laravel) | Storefront (WP/Woo) |
|-----|---------------------|---------------------|
| **local** *(default)* | http://brand.test | http://brand-site.test |
| staging | https://app.sgconnect.vn | https://*.b-teka.com |
| prod | https://app.sgconnect.vn | (per-customer domain) |

Cấu hình qua env vars: `BASE_APP`, `BASE_SITE`, `SELLER_EMAIL`, `SELLER_PASSWORD`, `ADMIN_EMAIL`, `ADMIN_PASSWORD`.

### Test accounts (local mặc định)

| Role | Email | Password |
|------|-------|----------|
| Seller (customer) | `admin@acm.com` | `123456` |
| Admin | `admin@sgconnect.vn` | `aA456321@` |

> Trên prod password seller demo là `123456` cho 4 site demo (Logitech, Nike, GuuCoffee, Orgafood).

---

## Quy ước phases

Mỗi phase = 1 file `phaseN-***.spec.ts` trong `bots/automated/e2e/tests/`.
Mỗi phase **độc lập** — chạy được riêng. Có thể giao cho AI khác làm tiếp khi resume.

Trạng thái: ☐ pending | ◐ in-progress | ☑ done

```bash
# Run 1 phase
cd bots/automated/e2e
npx playwright test tests/phase1-auth-smoke.spec.ts

# Run all
npx playwright test
```

---

## Phase 1 — Auth & Smoke ☑

> Mục tiêu: Login được + tất cả route chính 200 OK, không có PHP error.
> Spec: `bots/automated/e2e/tests/phase1-auth-smoke.spec.ts` — **48/48 pass** (desktop + mobile projects).

**Desktop dashboard (`/login`):**
- ☑ GET `/login` → 200, có form
- ☑ Login sai password → ở lại `/login`
- ☑ Login đúng → redirect vào dashboard (no PHP error)
- ☑ Smoke từng route chính (13 routes — full menu coverage):
  - ☑ Trang chính `/brand`
  - ☑ Tên miền `/brand/domain`
  - ☑ Website / Giao diện `/brand/website-templates`
  - ☑ **Cấu hình nội dung** `/brand/website/theme/options` *(fixed null-schema bug — see WebsiteController@themeOptions)*
  - ☑ Khách hàng / Liên hệ `/brand/contacts`
  - ☑ Cửa hàng / Sản phẩm `/store/products`
  - ☑ Các danh mục `/store/categories`
  - ☑ Thuộc tính `/store/attributes`
  - ☑ Đơn hàng `/store/orders`
  - ☑ **Kho hàng / Vận chuyển** `/store/warehouse` *(menu "Vận chuyển → Đơn vị vận chuyển" trỏ vào WarehouseController@index — share page với Kho hàng)*
  - ☑ Store dashboard `/store/dashboard`
  - ☑ Bài viết / Blog `/website/articles`
  - ☑ **Doanh thu bán hàng** `/brand/accounting-report` *(CustomerController@accountingReport)*
- ☑ Logout → quay lại `/login`

**Mobile webapp (`/brand/mobile/login`):**
- ☑ GET login page → 200
- ☑ Login sai → ở lại login
- ☑ Login đúng → vào dashboard webapp
- ☑ 4 tab chính:
  - ☑ Dashboard `/brand/mobile`
  - ☑ Sản phẩm `/brand/mobile/products`
  - ☑ Đơn hàng `/brand/mobile/orders`
  - ☑ Tài khoản `/brand/mobile/profile`

**Storefront (`brand-site.test`):**
- ☑ GET `/` → 200, có title
- ☑ `/shop/` → < 500

---

## Phase 2 — Sản phẩm (CRUD) ☑

> Reference: USER_GUIDE_DESKTOP §5.1, USER_GUIDE_MOBILE §2
> Spec: `bots/automated/e2e/tests/phase2-products.spec.ts` — **8/8 pass** (desktop + mobile projects).

**Desktop:**
- ☑ Vào /store/products/create
- ☑ Thêm sản phẩm mới (title + price)
- ☑ Sản phẩm vừa tạo xuất hiện trong WP (verified via `vbrandsync/v1/product/list?keyword=`)
- ☑ Vào /store/products/{id}/edit
- ☑ Sửa title → save → reload → title mới persist
- ☑ Xóa qua DELETE /store/products/delete (id=)
- ☑ Verify gone

**Mobile webapp:**
- ☑ Tab Sản phẩm `/brand/mobile/products` render + có search input
- ☑ Open `/brand/mobile/products/create`
- ☑ Tạo sản phẩm (title + price) → AJAX POST → redirect /edit
- ☑ Sản phẩm xuất hiện trong WP
- ☑ Open `/brand/mobile/products/{id}/edit` → đổi title → submit qua Alpine `submitForm` (dispatch submit event programmatically)
- ☑ Verify round-trip
- ☑ POST `/brand/mobile/products/{id}/delete` → verify gone

**Storefront sync (cross-platform):**
- ☑ Sản phẩm tạo trên dashboard hiện ngay trên WP REST (`vbrandsync/v1/product/list`)
- ☑ Sản phẩm xóa thì biến mất

> **Phase 2 fixes (deploy required):**
> - **vbrandsync** [plugin.php:17-33](site/wp-content/plugins/vbrandsync/plugin.php) — `vbrandsync_getResponse` cached app/kernel statically. Trước đây 2nd call trong cùng request crash với `Call to a member function make() on true` vì `require_once` trả `true`. Đây là root cause khiến mọi product/order/category POST handler die khi theme đã load Laravel trước đó.
> - **app** [Wordpress/Product.php fillParams](app/app/Wordpress/Product.php#L154) — accept aliases `content`/`sale_price`/`categories` (webapp form) ngoài `description`/`discount_price`/`category_ids` (desktop form). Trước đây mobile webapp silently drop description/sale_price/categories khi save.

---

## Phase 3 — Danh mục & Thuộc tính (Desktop only) ☑

> Reference: USER_GUIDE_DESKTOP §5.2, §5.3
> Spec: `bots/automated/e2e/tests/phase3-categories-attrs.spec.ts` — **4/4 pass** (desktop + mobile projects).

**Danh mục:**
- ☑ Vào `/store/categories/create`
- ☑ Thêm danh mục mới (name + description)
- ☑ Verify trong WP qua `vbrandsync/v1/category/list`
- ☑ Sửa danh mục (`/store/categories/{id}/edit`) → round-trip
- ☑ Xóa qua DELETE `/store/categories/delete-selected` (`ids[0]=`)
- ☑ Verify gone

**Thuộc tính:**
- ☑ Vào `/store/attributes/create`
- ☑ Tạo thuộc tính (name + description + 3 values: S, M, L) — values[] inject vào form qua page.evaluate (mô phỏng JS row builder)
- ☑ Verify trong WP qua `vbrandsync/v1/attribute/list` — values khớp ['L','M','S']
- ☑ Sửa thuộc tính (description) — name read-only on edit (đúng — WP attribute slug không rename được)
- ☑ Xóa qua DELETE `/store/attributes/delete-selected` → verify gone

> ⚠️ Skip: "Gán danh mục + thuộc tính cho 1 sản phẩm test" — sẽ làm trong Phase 4 (đơn hàng) hoặc Phase tích hợp riêng. Các CRUD cơ bản cho cả 2 entity đã xanh.

> **Phase 3 fixes (deploy required):**
> - **app** [resources/views/store/attributes/_form.blade.php:5](app/resources/views/store/attributes/_form.blade.php#L5) — `<input readonly>` luôn áp dụng cho cả create + edit. Trên create form thì user không gõ được name → form fail validation. Fix: chỉ readonly khi `$attribute->id` đã tồn tại (edit mode).

---

## Phase 4 — Đơn hàng & Order Statuses ☐

> Reference: USER_GUIDE_DESKTOP §5.4, USER_GUIDE_MOBILE §3, docs/ORDER_STATUSES.md

**Setup (test fixture):**
- ☐ Tạo 1 đơn hàng test (qua API hoặc storefront checkout COD)

**Desktop:**
- ☐ Vào Cửa hàng → Đơn hàng → đơn xuất hiện
- ☐ Lọc theo từng tab status (Tất cả / Mới / Đang giao / Hoàn thành / ...)
- ☐ Xem chi tiết đơn → có sản phẩm, khách hàng, payment, shipping
- ☐ Workflow chuyển trạng thái đầy đủ:
  - ☐ Xác nhận đơn → status đổi
  - ☐ Đóng gói → status đổi
  - ☐ Đang giao → status đổi
  - ☐ Đã giao → status đổi
  - ☐ Hoàn thành → status đổi
- ☐ Hủy đơn (đơn khác)
- ☐ Hoàn tiền
- ☐ Báo mất hàng
- ☐ Lịch sử trạng thái hiển thị đầy đủ

**Mobile webapp:**
- ☐ Tab Đơn hàng hiển thị tổng đơn / hoàn thành / thất bại
- ☐ Filter các tab status hoạt động
- ☐ Xem detail đơn
- ☐ Thực hiện workflow chuyển trạng thái 5 bước
- ☐ Hủy / Hoàn tiền / Báo mất hàng

---

## Phase 5 — Storefront checkout (COD) ☐

> Reference: SALES_HANDOVER §1 (COD Production)

- ☐ Mở `brand-site.test`
- ☐ Vào trang shop
- ☐ Add to cart 1 sản phẩm
- ☐ Vào cart → đúng item, đúng giá
- ☐ Checkout → điền name/phone/address/email
- ☐ Chọn COD
- ☐ Place order → trang thank-you
- ☐ Đơn vừa tạo xuất hiện trên dashboard seller
- ☐ Customer mới được tạo tự động (Phase 6 link)

---

## Phase 6 — Khách hàng (Desktop) ☐

> Reference: USER_GUIDE_DESKTOP §4

- ☐ Vào Khách hàng
- ☐ List hiển thị
- ☐ Thêm khách hàng thủ công (name/email/phone/address)
- ☐ Khách hàng từ Phase 5 (auto từ checkout) xuất hiện
- ☐ Sửa khách hàng
- ☐ Xóa khách hàng

---

## Phase 7 — Website: Theme & Cấu hình nội dung ☐

> Reference: USER_GUIDE_DESKTOP §3, USER_GUIDE_MOBILE §4

**Desktop:**
- ☐ Vào Website → Giao diện
- ☐ List template hiển thị (4 themes: logitech, orgafood, dreamcafe, nikezero)
- ☐ Preview 1 template
- ☐ Activate template → success message + theme đổi trên storefront
- ☐ Vào Website → Cấu hình nội dung
- ☐ Sửa Thông tin chung (tên shop, mô tả) → Lưu → reload vẫn còn
- ☐ Sửa Trang chủ (banner)
- ☐ Sửa Menu
- ☐ Sửa Giới thiệu
- ☐ Sửa Footer
- ☐ Vào Website → Xem trang → mở storefront
- ☐ Verify storefront có thay đổi vừa lưu

**Mobile webapp:**
- ☐ Dashboard → Giao diện → list template
- ☐ Preview template
- ☐ Activate template
- ☐ Tài khoản → Xem website

---

## Phase 8 — Tên miền ☐

> Reference: USER_GUIDE_DESKTOP §2

- ☐ Vào Tên miền → list domain
- ☐ Mở dialog "Đăng ký tên miền"
- ☐ Search 1 domain (dummy) → kết quả check
- ☐ (Skip thanh toán thật trên local — chỉ verify UI flow)

---

## Phase 9 — Kho hàng ☐

> Reference: USER_GUIDE_DESKTOP §6

- ☐ Vào Kho hàng
- ☐ Thông tin kho hàng
- ☐ Xuất nhập tồn
- ☐ Nhập hàng — tạo phiếu nhập 1 sản phẩm
- ☐ Xuất hàng — tạo phiếu xuất
- ☐ Thống kê sản phẩm hiển thị

---

## Phase 10 — Vận chuyển & Doanh thu ☐

> Reference: USER_GUIDE_DESKTOP §7, §8

- ☐ Vào Vận chuyển → Đơn vị vận chuyển
- ☐ Thống kê vận chuyển hiển thị
- ☐ Vào Doanh thu → báo cáo doanh số hiển thị
- ☐ Đổi range thời gian → reload data

---

## Phase 11 — Tài khoản & đổi mật khẩu ☐

> Reference: USER_GUIDE_DESKTOP (header), USER_GUIDE_MOBILE §5

**Desktop:**
- ☐ Mở profile dropdown
- ☐ Sửa thông tin cá nhân (họ, tên, sđt) → Lưu → reload còn

**Mobile:**
- ☐ Tab Tài khoản
- ☐ Chỉnh sửa thông tin
- ☐ Đổi mật khẩu (đổi rồi đổi lại 123456)
- ☐ Xem website mở tab mới

---

## Phase 12 — RFQ flow ☐

> Reference: docs/rfq/RFQ_DESIGN.md, SALES_HANDOVER §1 (RFQ)

- ☐ Storefront: tạo RFQ (gửi yêu cầu báo giá)
- ☐ Webapp seller: badge RFQ count
- ☐ Filter tab RFQ trên Đơn hàng webapp
- ☐ Approve RFQ → đổi sang đơn thường
- ☐ Reject RFQ
- ☐ Desktop: tương tự

---

## Phase 13 — Super Buyer ☐

> Reference: docs/rfq/SUPER_BUYER_DESIGN.md
> **Lưu ý:** route `/brand/super-buyer/mobile/*` chưa fully implemented — phase này có thể skip nếu chưa có UI.

- ☐ GET `/brand/super-buyer/mobile/login` → 200 (nếu route đã đăng ký)
- ☐ Login Super Buyer (orange theme)
- ☐ Switch WP connection
- ☐ Browse products from multiple shops
- ☐ Checkout cross-shop
- ☐ Order management

---

## Phase 14 — Import Request ☐

> Reference: docs/rfq/IMPORT_REQUEST_DESIGN.md, USER_GUIDE_MOBILE §6

- ☐ Webapp: Sản phẩm → Import → form
- ☐ Submit yêu cầu (link Shopee/Lazada dummy)
- ☐ Trạng thái Mới hiển thị
- ☐ Admin: process request → Đang xử lý → Hoàn thành
- ☐ Webapp seller thấy status update

---

## Phase 15 — Admin panel ☐

> Reference: SALES_HANDOVER §6.A (admin tổng)

- ☐ Login admin (`admin@sgconnect.vn`)
- ☐ Smoke các trang chính của admin (Customer / Site / Plan / Template / Order / Accounting)
- ☐ Approve 1 import request (link Phase 14)

---

## Phase 16 — Blog ☐

> Reference: SALES_HANDOVER §1 (Blog)

- ☐ Tạo bài blog (title + content)
- ☐ Tạo chuyên mục
- ☐ Edit / delete bài
- ☐ Verify hiển thị trên storefront

---

## Phase 17 — KB / Knowledge Base ☐

- ☐ Smoke admin KB CRUD (đã có Feature tests, chỉ smoke E2E)

---

## Cách giao tiếp với AI khác (resume)

Mỗi lần work xong 1 phase:
1. Sửa file này: đổi `☐` → `☑` trên các check đã pass
2. Đổi heading phase: `☐` → `☑` khi tất cả check trong phase done
3. Commit message: `test(e2e): phase N — <summary>`
4. AI tiếp theo đọc file này → biết phase nào còn ☐ → làm tiếp

## Convention

- Tất cả selector ưu tiên: `data-testid` > role/text > CSS class
- Khi page chưa có `data-testid`, dùng text Vietnamese từ user guide (vd: `getByRole('button', { name: 'Lưu' })`)
- Mỗi test phải **độc lập** — fixture tự cleanup (xóa product/order vừa tạo)
- Chạy trên 2 viewport: desktop 1280×800 và mobile 430×932 (iPhone 14 Pro)
- Screenshot on failure (mặc định Playwright)
