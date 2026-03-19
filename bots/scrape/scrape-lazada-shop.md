# Bot: Scrape & Import Lazada Mall Shop

Scrape toàn bộ shop từ Lazada Mall → Import vào WooCommerce site qua vbrandsync REST API.
2 bước: **Scrape** (lấy data) → **Import** (đẩy vào WooCommerce).

---

## Quick Start — Full Flow

```bash
# 1. Scrape shop Lazada
bots/scrape/scrape-lazada-shop.md scrape https://www.lazada.vn/shop/nike-flagship-store/

# 2. Import vào WooCommerce site (clean trước, import hết)
bots/scrape/scrape-lazada-shop.md import https://nike.b-teka.com shops/nike-flagship-store/ --clean

# Hoặc import vào local
bots/scrape/scrape-lazada-shop.md import http://brand-site.test shops/nike-flagship-store/ --clean
```

---

## STEP 1: Scrape

### Usage

```bash
# Scrape toàn bộ shop (shop info + categories + ALL products + images)
bots/scrape/scrape-lazada-shop.md scrape <lazada_shop_url>

# Chỉ scrape shop info
bots/scrape/scrape-lazada-shop.md scrape <url> --shop-info

# Chỉ scrape products (giới hạn 20)
bots/scrape/scrape-lazada-shop.md scrape <url> --products --limit 20
```

### Scrape Options

| Flag | Mô tả |
|------|--------|
| `--all` | Scrape mọi thứ (default) |
| `--shop-info` | Chỉ scrape thông tin shop |
| `--products` | Chỉ scrape sản phẩm |
| `--categories` | Chỉ scrape danh mục |
| `--limit <n>` | Giới hạn số sản phẩm |
| `--output <dir>` | Thư mục output (default: `bots/scrape/shops/<shop-slug>/`) |

### Scrape Output

```
bots/scrape/shops/<shop-slug>/
├── shop-info.json          # Tên, logo, followers, rating, shopId, sellerId
├── categories.json         # Danh mục shop
├── products.json           # TẤT CẢ sản phẩm + data phong phú
├── products.md             # Bảng tổng hợp markdown
├── scrape-summary.json     # Metadata scrape (thời gian, params)
├── images/
│   ├── shop/logo.jpg       # Logo shop
│   └── products/           # Hình sản phẩm (720x720)
│       ├── <product_id>.jpg
│       └── ...
└── screenshots/
    └── shop-page.png       # Screenshot trang shop
```

### Product Data Structure

```json
{
  "id": "3312048385",
  "name": "Áo thun ba lỗ Nam Nike Sportswear Men's Tank - WHITE",
  "nameRaw": "[VOUCHER 25%] Áo thun ba lỗ Nam...",
  "url": "https://www.lazada.vn/products/pdp-i3312048385-s16153256899.html",
  "image": "https://filebroker-cdn.lazada.vn/kf/...",
  "price": "₫ 659,000",
  "priceRaw": 659000,
  "originalPrice": "₫ 799,000",
  "originalPriceRaw": 799000,
  "rating": 4.8,
  "reviews": 154,
  "soldLastMonth": 41,
  "sku": "3312048385_VNAMZ-16153256899",
  "brandId": "1894",
  "categoryId": "7545",
  "inStock": true,
  "freeShipping": false,
  "localImage": "images/products/3312048385.jpg"
}
```

---

## STEP 2: Import vào WooCommerce

### Usage

```bash
# Import tất cả (clean + import)
bots/scrape/scrape-lazada-shop.md import <site_url> <shop_dir> --clean

# Import chỉ 50 sản phẩm đầu (test)
bots/scrape/scrape-lazada-shop.md import <site_url> <shop_dir> --clean --limit 50

# Import không clean (giữ data cũ)
bots/scrape/scrape-lazada-shop.md import <site_url> <shop_dir>

# Dry run (xem trước)
bots/scrape/scrape-lazada-shop.md import <site_url> <shop_dir> --dry-run

# Import nhanh không hình (test API)
bots/scrape/scrape-lazada-shop.md import <site_url> <shop_dir> --clean --skip-images
```

### Import Options

| Flag | Mô tả |
|------|--------|
| `--clean` | Xóa tất cả products/categories/media trước khi import |
| `--limit <n>` | Giới hạn số sản phẩm import |
| `--skip-images` | Bỏ qua import hình (nhanh hơn cho testing) |
| `--dry-run` | Chỉ hiển thị, không import thực tế |

### Import Flow

```
1. Kiểm tra kết nối API (GET /import/status)
2. [--clean] Xóa tất cả products + categories + media
3. Import shop info (name, logo, shopId, sellerId)
4. Import categories
5. Import products (từng sản phẩm, kèm hình ảnh từ Lazada CDN)
6. Verify (GET /import/status → kiểm tra số lượng)
```

### vbrandsync Import REST API

| Method | Endpoint | Mô tả |
|--------|----------|--------|
| `POST` | `/import/clean` | Xóa tất cả products, categories, media |
| `POST` | `/import/product` | Import 1 sản phẩm (title, price, image_url, lazada_id...) |
| `POST` | `/import/category` | Import hoặc tìm 1 category |
| `POST` | `/import/shop-info` | Update shop info (name, logo, shopId...) |
| `GET` | `/import/status` | Đếm products, categories hiện tại |

---

## Ví dụ thực tế

### Ví dụ 1: Scrape + Import Nike vào production

```bash
# Bước 1: Scrape Nike Flagship Store từ Lazada
bots/scrape/scrape-lazada-shop.md scrape https://www.lazada.vn/shop/nike-flagship-store/
# → Output: bots/scrape/shops/nike-flagship-store/ (741 products)

# Bước 2: Import vào Nike production site
bots/scrape/scrape-lazada-shop.md import https://nike.b-teka.com shops/nike-flagship-store/ --clean
# → 741 products imported, shop info updated
```

### Ví dụ 2: Scrape shop mới + test local trước

```bash
# Scrape shop Adidas
bots/scrape/scrape-lazada-shop.md scrape https://www.lazada.vn/shop/adidas-official-store/

# Test local với 20 sản phẩm đầu
bots/scrape/scrape-lazada-shop.md import http://brand-site.test shops/adidas-official-store/ --clean --limit 20

# Confirm OK → import full lên production
bots/scrape/scrape-lazada-shop.md import https://adidas.b-teka.com shops/adidas-official-store/ --clean
```

### Ví dụ 3: Import nhanh không hình (test API)

```bash
bots/scrape/scrape-lazada-shop.md import http://brand-site.test shops/nike-flagship-store/ --clean --skip-images --limit 5
```

---

## Hướng dẫn cho Claude

### Khi user nói ngắn gọn

| User nói | Claude làm |
|----------|-----------|
| `scrape lazada <url>` | Chạy scrape script |
| `import <shop> vào <site>` | Chạy import script |
| `scrape + import <url> vào <site>` | Scrape rồi import |
| `clean + import lại` | Import với `--clean` |
| `test local trước` | Import vào `http://brand-site.test` |

### Flow cho Claude

1. **Scrape:** Copy script ra `/private/tmp/` → chạy → verify hình/data
2. **Import:** Chạy import script với site URL → verify status
3. **Review:** Mở site, check products, images, prices
4. **Deploy plugin nếu cần:** Commit vbrandsync → rsync tới sites → clear cache

---

## Technical Details

### Scrape — Cách hoạt động

```
1. Launch headless Puppeteer + Stealth plugin
2. Load shop page → capture API params (shopId, sellerId, campaignId, promotionTag)
3. Scrape shop info từ DOM (name, logo, followers, rating)
4. Scrape categories từ DOM links
5. Gọi internal API paginate tất cả sản phẩm:
   GET /shop/site/api/shop/campaignTppProducts/query
     ?shopId=...&sellerId=...&campaignId=...&promotionTag=...
     &offset=0,1,2...&limit=30&sourceType=pc&type=3
6. Download hình sản phẩm (720x720 resolution)
7. Lưu JSON + Markdown + images
```

**Tại sao dùng API thay vì DOM scraping?**
- API trả về data phong phú (giá, rating, reviews, SKU, sold count)
- Không bị CAPTCHA (product detail pages bị reCAPTCHA block)
- Nhanh hơn nhiều (không visit từng trang sản phẩm)
- Lấy được TOÀN BỘ sản phẩm (DOM chỉ hiển thị 1 phần)

### Prerequisites

```bash
cd /private/tmp
npm install puppeteer puppeteer-extra puppeteer-extra-plugin-stealth
```

### Scripts

| Script | Mô tả |
|--------|--------|
| `bots/scrape/scripts/scrape-lazada-shop.js` | Scrape Lazada shop |
| `bots/scrape/scripts/import-to-woocommerce.js` | Import vào WooCommerce |

Chạy:
```bash
cd /private/tmp && node /Users/luan/apps/vbrand/bots/scrape/scripts/scrape-lazada-shop.js <url> [options]
cd /Users/luan/apps/vbrand/bots/scrape && node scripts/import-to-woocommerce.js <site_url> <shop_dir> [options]
```

### Fallback

Nếu API params không capture được, scraper tự fallback sang DOM scraping (ít data hơn).

---

## Troubleshooting

### Shop page trống sau import

**Nguyên nhân 1:** WooCommerce Coming Soon mode đang bật
```bash
# Kiểm tra
wp option get woocommerce_coming_soon
# Tắt
wp option update woocommerce_coming_soon no
wp option update woocommerce_store_pages_only no
wp cache flush
```

**Nguyên nhân 2:** Duplicate shop pages (slug conflict)
```bash
# Kiểm tra
wp db query "SELECT ID, post_title, post_name FROM wp_posts WHERE post_name LIKE 'shop%' AND post_type='page'"
# Nếu có 2 page "Shop" (shop + shop-2), xóa cái thừa và đảm bảo woocommerce_shop_page_id đúng
wp option get woocommerce_shop_page_id
```

**Nguyên nhân 3:** Cache cũ
```bash
wp cache flush && wp transient delete --all && wp rewrite flush
```

---

## Test Results

**Nike Flagship Store** (2026-03-17):
- Scrape: 741 sản phẩm, 741/741 có giá + SKU + hình, ~3 phút
- Import local (brand-site.test): 741/741 thành công, 0 failed ✓
- Import prod (nike.b-teka.com): 741/741 thành công, 0 failed ✓
- Giá: ₫309,000 — ₫6,179,000
- 614/741 có rating, 608/741 có reviews
- Frontend verified: shop page + product detail page working on both sites ✓
- Plugin committed (effef53), pushed, deployed via rsync ✓
