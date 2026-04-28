# vBrand — Tài liệu bàn giao Sales

> Cập nhật: 2026-04-15

---

## FAQ — Trả lời nhanh cho Sales

| # | Câu hỏi | Trả lời ngắn | Chi tiết |
|---|---------|--------------|----------|
| 1 | **Sản phẩm đã có gì, hoàn thiện đến đâu?** | Platform hoàn thiện 100%, đang chạy production với 4 site thật. 12 nhóm tính năng: website, sản phẩm, đơn hàng, thanh toán (COD), vận chuyển (GHN), blog, tên miền, kế toán, mobile webapp, template gallery, RFQ, Super Buyer. | [Mục 1](#1-sản-phẩm-vbrand-là-gì-đã-có-gì) |
| 2 | **Quy trình build cho khách gồm gì, phát sinh thêm gì?** | 5 bước: (1) Sales thu thập info → (2) Dev tạo site 10-15 phút → (3) Dev chỉnh giao diện 1-2h → (4) Nhập sản phẩm → (5) Sales bàn giao. Phát sinh thường gặp: khách muốn giao diện riêng → cần link website mẫu để clone 100% (+1-2 ngày), import nhiều sản phẩm, đăng ký domain. | [Mục 2](#2-quy-trình-build-site-cho-khách) |
| 3 | **Cần bổ sung design, content, người thu thập info không?** | Không cần designer, không cần content writer. Chỉ cần **1 Sales biết dùng webapp** là đủ triển khai. Sales thu thập info là khâu quan trọng nhất. | [Mục 3](#3-phân-công-công-việc--sales-vs-team-dev) |
| 4 | **Khách thiếu thông tin thì xử lý sao?** | Chỉ cần tối thiểu **tên + email** là đủ tạo site demo. Chưa có logo → dùng text, chưa có domain → dùng miễn phí `*.b-teka.com`, chưa có sản phẩm → import từ Shopee hoặc để trống. Tạo demo nhanh → khách tự bổ sung sau. | [Mục 4](#4-checklist-thu-thập-thông-tin-từ-khách) |
| 5 | **Có mẫu demo hoàn chỉnh gửi khách chưa?** | **Có.** 7 site đang chạy production thật, gửi link cho khách xem ngay. | [Mục 5](#5-demo-sites-hiện-có) |
| 6 | **Đang có bao nhiêu mẫu?** | **7 site demo** + **6 theme** phủ các ngành: công nghệ, thời trang, F&B, thực phẩm, công nghiệp, ô tô/dịch vụ. | [Mục 5](#5-demo-sites-hiện-có) + [Mục 7](#7-giao-diện-có-sẵn-6-themes) |
| 7 | **Mẫu nào thuận tiện nhất để chào khách?** | Gửi demo **theo ngành khách** (F&B → GuuCoffee, Organic → Orgafood, Tech → Logitech, Thời trang → Nike). Chưa rõ ngành → gửi cả 4. Chiến lược tốt nhất: gửi link demo + nói "tạo site riêng cho anh/chị trong 15 phút". | [Mục 8](#8-mẫu-nào-tốt-nhất-để-đi-chào-khách) |

---

## 1. Sản phẩm vBrand là gì? Đã có gì?

**vBrand** là nền tảng SaaS giúp khách hàng sở hữu **website bán hàng riêng** (WordPress + WooCommerce) và quản lý mọi thứ qua **1 dashboard duy nhất** — không cần biết code hay WordPress.

### Tính năng đã hoàn thiện & đang chạy production

| Nhóm | Tính năng | Trạng thái |
|------|-----------|------------|
| **Website** | Chọn theme, tùy chỉnh giao diện (logo, banner, menu, màu sắc...) qua form kéo thả | Production |
| **Sản phẩm** | Thêm/sửa/xóa sản phẩm, ảnh, biến thể, thuộc tính | Production |
| **Đơn hàng** | 13 trạng thái đơn (đặt hàng → đóng gói → giao → hoàn thành...), quản lý đầy đủ | Production |
| **Thanh toán** | COD (thanh toán khi nhận hàng) | Production |
| **Vận chuyển** | vBrand Express (vận chuyển nội bộ) | Production |
| **Blog** | Viết bài, quản lý chuyên mục | Production |
| **Tên miền** | Kiểm tra & đăng ký tên miền qua GoDaddy | Production |
| **Kế toán** | Báo cáo doanh thu, bút toán, hóa đơn | Production |
| **Mobile** | Giao diện mobile webapp tối ưu cho seller (sản phẩm, đơn hàng, dashboard) | Production |
| **Template Gallery** | Kho giao diện cho khách chọn & kích hoạt | Production |
| **RFQ** | Khách hàng gửi yêu cầu báo giá, seller duyệt/từ chối | Production |
| **Super Buyer** | Người mua đặc biệt mua hàng trên nhiều shop cùng lúc | Production |

### Mức độ hoàn thiện

- **Core platform:** 100% — đang chạy production với khách thật
- **Thanh toán + vận chuyển:** 100% — COD + vBrand Express hoạt động
- **Mobile webapp:** 100% — đầy đủ chức năng seller
- **Tạo site mới:** 100% tự động — từ 0 đến site hoàn chỉnh trong ~10 phút
- **Theme system:** 6 theme có sẵn, thêm theme mới mất 1-2 ngày (khách gửi link website mẫu → dev clone 100%)
- **Nâng cấp site cũ:** Chuyển đổi website cũ/lỗi thời sang vBrand — giữ nguyên sản phẩm + hình ảnh, thiết kế hoàn toàn mới

---

## 2. Quy trình build site cho khách

### Bước 1 — Thu thập thông tin khách `>>> Sales làm`

Sales thu thập **6 thông tin tối thiểu** từ khách (xem chi tiết mục 4):

1. Tên công ty / thương hiệu
2. Domain muốn dùng
3. Sản phẩm mẫu (link Shopee / Lazada / website cá nhân)
4. Link giao diện mẫu (website khách thích)
5. Email liên hệ
6. Số điện thoại

Gửi đầy đủ về cho **team dev** để tiến hành bước 2.

### Bước 2 — Tạo site `>>> Team dev làm`

Team dev chạy hệ thống **tự động**: tạo database → cài WordPress → cài WooCommerce → kích hoạt theme → SSL → kết nối dashboard.

**Thời gian:** 10-15 phút. Xong sẽ gửi lại cho Sales: link site + tài khoản đăng nhập.

### Bước 3 — Tùy chỉnh giao diện `>>> Team dev làm`

Team dev setup theo yêu cầu khách:
- Đổi logo, banner, thông tin liên hệ
- Chỉnh màu sắc, menu, footer theo link giao diện mẫu khách gửi
- Cấu hình thanh toán, vận chuyển nếu cần

**Thời gian:** 1-2 giờ.

### Bước 4 — Nhập sản phẩm

| Cách | Ai làm | Khi nào |
|------|--------|---------|
| **Thủ công** — thêm từng sản phẩm qua webapp | **Sales tự làm** | Khách có ít sản phẩm (<20) |
| **Tự động** — scrape từ Shopee/Lazada | **Team dev làm** | Khách có link shop Shopee/Lazada |

> Sales có thể tự thêm sản phẩm thủ công qua webapp mà không cần dev (xem User Guide đính kèm).

### Bước 5 — Bàn giao cho khách `>>> Sales làm`

Sales gửi cho khách **4 thứ:**

1. **Link webapp:** `https://app.sgconnect.vn/brand/mobile/login`
2. **Link desktop:** `https://app.sgconnect.vn/login`
3. **Tài khoản:** email + mật khẩu (team dev cung cấp)
4. **Hướng dẫn sử dụng:** đính kèm 2 file:
   - [`USER_GUIDE_MOBILE.pdf`](USER_GUIDE_MOBILE.md) — hướng dẫn Webapp (điện thoại)
   - [`USER_GUIDE_DESKTOP.pdf`](USER_GUIDE_DESKTOP.md) — hướng dẫn Desktop (máy tính, đầy đủ hơn)

> Ưu tiên gửi **link webapp** trước — khách mở trên điện thoại dùng ngay được.
> Desktop có thêm: cấu hình nội dung, danh mục, thuộc tính, khách hàng, kho hàng, doanh thu.

### Thường phát sinh thêm

| Phát sinh | Ai xử lý | Giải pháp |
|-----------|----------|-----------|
| Khách muốn giao diện khác hoàn toàn | Team dev | Sales **phải lấy link website mẫu** từ khách → dev clone 100% giao diện đó (1-2 ngày). Không nhận yêu cầu mô tả chung chung, bắt buộc có link cụ thể. |
| Khách có nhiều sản phẩm (100+) | Team dev | Dùng tool import tự động từ Shopee/Lazada |
| Khách muốn tên miền riêng | Team dev | Đăng ký + trỏ DNS |
| Khách cần tính năng đặc thù | Team dev | Đánh giá & báo giá riêng |
| Khách muốn sửa sản phẩm/giá | Sales hoặc khách tự làm | Dùng webapp theo User Guide |

---

## 3. Phân công công việc — Sales vs Team Dev

| Công việc | Ai làm | Ghi chú |
|-----------|--------|---------|
| Thu thập thông tin khách (6 mục) | **Sales** | Khâu quan trọng nhất — càng đủ info càng nhanh |
| Tạo site + setup giao diện | **Team dev** | Tự động, 1-2 giờ |
| Nhập sản phẩm thủ công (<20 SP) | **Sales tự làm được** | Qua webapp, xem User Guide |
| Import sản phẩm tự động (Shopee/Lazada) | **Team dev** | Sales gửi link shop → dev scrape |
| Bàn giao + hướng dẫn khách | **Sales** | Gửi link webapp + User Guide |
| Theme mới hoàn toàn | **Team dev** | Sales lấy **link website mẫu** từ khách → dev clone 100%. Bắt buộc có link, không nhận mô tả chung chung. 1-2 ngày. |
| Tên miền riêng | **Team dev** | Đăng ký + trỏ DNS |

**Kết luận:** Sales chỉ cần **thu thập thông tin + bàn giao**. Phần kỹ thuật team dev lo hết.

### Lưu ý quan trọng — Quy trình feedback & yêu cầu mới

| Tình huống | Xử lý |
|------------|--------|
| Cần chỉnh sửa / feedback **trong scope hiện tại** (sửa giao diện, đổi ảnh, chỉnh sản phẩm, fix lỗi...) | Sales gửi **trực tiếp cho team dev** → dev xử lý luôn |
| Cần **thêm chức năng mới ngoài scope** (tính năng chưa có, yêu cầu đặc thù, tùy chỉnh phức tạp...) | Sales phải **confirm với PO Nghị trước** → PO duyệt → mới chuyển team dev làm |

> **Quy tắc:** Trong scope → gửi dev. Ngoài scope → qua PO Nghị duyệt trước. Không tự ý commit thêm tính năng ngoài scope.

---

## 4. Checklist thu thập thông tin từ khách

### 6 thông tin tối thiểu cần lấy (để làm demo)

| # | Thông tin | Bắt buộc | Ví dụ |
|---|-----------|----------|-------|
| 1 | **Tên công ty / thương hiệu** | **Bắt buộc** | GuuCoffee, Orgafood, Nike Zero VN |
| 2 | **Domain muốn dùng** | **Bắt buộc** | `guucafe.com` hoặc dùng miễn phí `thuonghieu.b-teka.com` |
| 3 | **Sản phẩm mẫu** — link Shopee / Lazada / website cá nhân | **Bắt buộc** | Link shop Shopee để import sản phẩm tự động |
| 4 | **Link giao diện mẫu** — website khách muốn clone giống | **Bắt buộc** | Link cụ thể (VD: `https://nike.com`). Dev sẽ clone 100% giao diện từ link này. **Không nhận mô tả chung chung** — phải có link. |
| 5 | **Email liên hệ** | **Bắt buộc** | Dùng làm tài khoản đăng nhập |
| 6 | **Số điện thoại** | **Bắt buộc** | Hiển thị trên site + liên lạc |

### Thông tin bổ sung (càng có càng tốt, thiếu vẫn làm demo được)

- Logo (file PNG/SVG)
- Ảnh banner / ảnh sản phẩm chất lượng cao
- Slogan / mô tả ngắn về thương hiệu
- Địa chỉ cửa hàng / văn phòng
- Link fanpage Facebook / Zalo OA / Instagram
- Bảng giá sản phẩm (nếu chưa có trên Shopee/Lazada)

### Khách cung cấp thiếu thì xử lý thế nào?

| Thiếu gì | Xử lý |
|----------|--------|
| Chưa có logo | Dùng text logo mặc định, khách gửi sau thì update |
| Chưa có sản phẩm | Import tự động từ Shopee/Lazada nếu có link, hoặc tạo site trống |
| Chưa có tên miền | Dùng miễn phí `thuonghieu.b-teka.com`, nâng cấp domain riêng sau |
| Chưa chọn giao diện | Gửi 4 demo link cho khách chọn, hoặc khách gửi link website mẫu bất kỳ → dev clone 100% |
| Thiếu mô tả/ảnh | Lấy từ Shopee/Lazada, hoặc tạo site với thông tin cơ bản, bổ sung dần |

**Chiến lược:** Có đủ 6 thông tin trên → tạo site demo trong 15 phút → khách thấy kết quả → chủ động cung cấp thêm phần còn lại.

---

## 5. Demo sites hiện có

### 6 site đang chạy production

| # | Site | Link | Theme | Ngành |
|---|------|------|-------|-------|
| 1 | **Logitech** | https://logitech.b-teka.com | logitech | Công nghệ / Phụ kiện |
| 2 | **Nike Zero Vietnam** | https://nike.b-teka.com | nikezero | Thời trang / Giày dép |
| 3 | **GuuCoffee Demo** | https://guucoffee.b-teka.com | dreamcafe | Cafe / F&B |
| 4 | **Orgafood** | https://orgafood.b-teka.com | orgafood | Thực phẩm sạch / Organic |
| 5 | **GuuCoffee** *(khách thật)* | https://guucafe.com | dreamcafe | Cafe / F&B |
| 6 | **Diệu An** | https://dieuan.b-teka.com | dieu-an | Công nghiệp / Van & Phụ kiện |
| 7 | **Auto Tây Bắc** | https://autotaybac.b-teka.com | autotaybac | Ô tô / Chăm sóc xe |
| 8 | **Cà phê Danh Phát** | https://cafedanhphat.b-teka.com | cafedanhphat | Cafe phân phối / B2B |

> Tất cả 8 site đều chạy HTTPS, sẵn sàng demo.

---

## 6. Thông tin đăng nhập (bàn giao đầy đủ)

### A. Admin tổng — Quản lý toàn bộ hệ thống

| Thông tin | Giá trị |
|-----------|---------|
| **Link admin** | https://app.sgconnect.vn/admin |
| **Email** | `admin@sgconnect.vn` |
| **Mật khẩu** | `aA456321@` |

> Đây là tài khoản admin tổng — quản lý tất cả khách hàng, site, gói dịch vụ, template, đơn hàng, kế toán.

### B. Dashboard khách hàng — Mỗi site 1 tài khoản

**Link đăng nhập:** https://app.sgconnect.vn/login

| Site | Email | Mật khẩu |
|------|-------|-----------|
| Logitech | `logitech@gmail.com` | `123456` |
| Nike Zero | `john.nikezro.vietnam@nikezero.com` | `123456` |
| GuuCoffee Demo | `guucoffee@gmail.com` | `123456` |
| Orgafood | `orgafood@gmail.com` | `123456` |
| Diệu An | `dieuan@gmail.com` | `123456` |
| Auto Tây Bắc | `autotaybac@gmail.com` | `123456` |
| Cà phê Danh Phát | `cafedanhanphat@gmail.com` | `123456` |

> Đăng nhập → vào dashboard seller quản lý site: sản phẩm, đơn hàng, giao diện, blog...

---

## 7. Giao diện có sẵn (7 themes)

| Theme | Phù hợp cho | Demo |
|-------|-------------|------|
| **logitech** | Công nghệ, điện tử, phụ kiện | https://logitech.b-teka.com |
| **orgafood** | Thực phẩm, organic, nông sản | https://orgafood.b-teka.com |
| **dreamcafe** | Cafe, nhà hàng, F&B | https://guucoffee.b-teka.com |
| **cafedanhphat** | Cafe phân phối, B2B + B2C, đại lý | https://cafedanhphat.b-teka.com |
| **nikezero** | Thời trang, giày dép, thể thao | https://nike.b-teka.com |
| **dieu-an** | Công nghiệp, B2B, van & phụ kiện | https://dieuan.b-teka.com |
| **autotaybac** | Ô tô, dịch vụ, chăm sóc xe | https://autotaybac.b-teka.com |

---

## 8. Mẫu nào tốt nhất để đi chào khách?

### Gợi ý: Gửi 2-3 link demo theo ngành của khách

| Khách ngành | Gửi demo |
|-------------|----------|
| F&B / Cafe / Nhà hàng | https://guucoffee.b-teka.com |
| Thực phẩm / Organic | https://orgafood.b-teka.com |
| Công nghệ / Phụ kiện | https://logitech.b-teka.com |
| Thời trang / Giày dép | https://nike.b-teka.com |
| Công nghiệp / B2B / Vật liệu | https://dieuan.b-teka.com |
| Ô tô / Dịch vụ / Chăm sóc xe | https://autotaybac.b-teka.com |
| Chung / Chưa rõ ngành | Gửi cả 6 link để khách chọn |

### Cách tiếp cận hiệu quả nhất

1. **Gửi link demo trước** — để khách thấy ngay kết quả thực tế
2. **Hỏi thông tin cơ bản** — tên thương hiệu + email + ngành hàng
3. **Tạo site demo riêng cho khách trong 15 phút** — gây ấn tượng mạnh
4. **Hướng dẫn khách vào dashboard** — cho khách tự trải nghiệm
5. **Chốt deal** — khi khách đã thấy site của chính mình

> **Tip:** Không cần gửi tài liệu dài. Gửi link demo + nói "em tạo thử site riêng cho anh/chị trong 15 phút" là đủ thuyết phục.

---

## Tóm tắt nhanh

- **Sản phẩm:** Hoàn thiện, đang chạy production với 7 site thật
- **Tạo site mới:** 10-15 phút, tự động hoàn toàn
- **Nâng cấp site cũ:** Chuyển đổi website lỗi thời sang vBrand, giữ nguyên sản phẩm + hình ảnh
- **Cần từ khách:** Tối thiểu 6 thông tin (tên, domain, sản phẩm mẫu, giao diện mẫu, email, SĐT)
- **Demo:** 5 site demo (b-teka.com) + 5 theme sẵn, gửi link cho khách xem ngay
- **Nhân sự triển khai:** 1 người sales biết dùng dashboard là đủ
- **Chiến lược chào hàng:** Gửi demo → tạo site riêng 15 phút → chốt

---

## Phụ lục — WordPress Admin các site demo

> Thông tin kỹ thuật, chỉ dùng khi cần truy cập trực tiếp WordPress của từng site demo.

| Site | Link WP Admin | User | Mật khẩu |
|------|---------------|------|-----------|
| Logitech | https://logitech.b-teka.com/wp-admin | `admin` | `aA456321@` |
| Nike Zero | https://nike.b-teka.com/wp-admin | `admin` | `aA456321@` |
| GuuCoffee Demo | https://guucoffee.b-teka.com/wp-admin | `admin` | `aA456321@` |
| Orgafood | https://orgafood.b-teka.com/wp-admin | `admin` | `aA456321@` |
| Diệu An | https://dieuan.b-teka.com/wp-admin | `admin` | `aA456321@` |
| Auto Tây Bắc | https://autotaybac.b-teka.com/wp-admin | `admin` | `aA456321@` |
