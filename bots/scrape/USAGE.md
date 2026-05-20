# Scrape Bots — Hướng dẫn sử dụng

Hệ thống 2 layer: **Scrape** (lấy data) → **Import** (đẩy vào WooCommerce).

---

## Kiến trúc

```
bot-scrape.md          ← entry point — chọn nguồn (lazada / shopee / woocommerce / ...)
  ├── scrape-lazada-shop.md          →  scripts/scrape-lazada-shop.js
  ├── scrape-shopee-shop.md          →  scripts/scrape-shopee-shop.js
  └── scrape-woocommerce-store.md    →  scripts/scrape-woocommerce-store.js  (NEW)
         │
         ▼
  shops/<SHOP_NAME>/   ← standard format (xem bot-scrape.md)
         │
         ▼
bot-import-woo.md      ← import vào WooCommerce (đọc bất kỳ shops/ nào)
  └── scripts/import-to-woocommerce.js
```

---

## Ví dụ thực tế

### 1. Scrape + Import Nike từ Lazada

```bash
# Bước 1: Scrape
cd /private/tmp
node /Users/luan/apps/vbrand/bots/scrape/scripts/scrape-lazada-shop.js https://www.lazada.vn/shop/nike-flagship-store/
# → output: bots/scrape/shops/nike-flagship-store/ (741 products)

# Bước 2: Import local (test trước)
cd /Users/luan/apps/vbrand/bots/scrape
node scripts/import-to-woocommerce.js http://brand-site.test shops/nike-flagship-store/ --clean --limit 20

# Bước 3: Import production
node scripts/import-to-woocommerce.js https://nike.b-teka.com shops/nike-flagship-store/ --clean
```

### 2. Scrape + Import Logitech từ Shopee

```bash
# Bước 1: User copy HTML từ Chrome
# - Mở https://shopee.vn/logitech.official.store, scroll đến cuối
# - F12 → Elements → right-click <html> → Copy → Copy outerHTML
# - pbpaste > /tmp/shopee-logitech.html

# Bước 2: Scrape
cd /private/tmp
node /Users/luan/apps/vbrand/bots/scrape/scripts/scrape-shopee-shop.js logitech.official.store --html /tmp/shopee-logitech.html
# → output: bots/scrape/shops/logitech_official_store/ (61 products)

# Bước 3: Import
cd /Users/luan/apps/vbrand/bots/scrape
node scripts/import-to-woocommerce.js https://logitech.b-teka.com shops/logitech_official_store/ --clean
```

### 3. Re-import từ data đã có (không scrape lại)

```bash
# Data đã có trong shops/ → import thẳng vào site khác
cd /Users/luan/apps/vbrand/bots/scrape
node scripts/import-to-woocommerce.js https://logitech.b-teka.com shops/logitech_official_store/ --clean
```

### 4. Test nhanh không hình

```bash
node scripts/import-to-woocommerce.js http://brand-site.test shops/nike-flagship-store/ --clean --skip-images --limit 10
```

---

## Shops đã scrape

| Shop | Source | Sản phẩm | Ngày | Site |
|------|--------|----------|------|------|
| nike-flagship-store | Lazada | 741 | 2026-03-17 | nike.b-teka.com |
| logitech_official_store | Shopee | 61 | 2026-03-22 | logitech.b-teka.com |
| ductrico | WooCommerce Store API | 67 | 2026-05-20 | khomaynenkhi.com |

---

## Commands cheat sheet

```bash
# === SCRAPE ===

# Lazada (full URL)
cd /private/tmp && node /path/to/scrape-lazada-shop.js <lazada_url>

# Shopee (HTML file — user copy từ DevTools)
cd /private/tmp && node /path/to/scrape-shopee-shop.js <shop_name> --html <file.html>

# WooCommerce Store API (pure HTTP — no browser) — works on any WC site with public Store API
cd /private/tmp && node /Users/luan/apps/vbrand/bots/scrape/scripts/scrape-woocommerce-store.js <site_url>
# Detect first: curl -s <site>/wp-json/wc/store/v1/products?per_page=1 → 200 = OK

# === IMPORT ===

# Local test (clean + import tất cả)
cd bots/scrape && node scripts/import-to-woocommerce.js http://brand-site.test shops/<SHOP>/ --clean

# Production
cd bots/scrape && node scripts/import-to-woocommerce.js https://<domain> shops/<SHOP>/ --clean

# Giới hạn số sản phẩm
... --clean --limit 20

# Bỏ qua hình (nhanh)
... --skip-images

# Xem trước, không import
... --dry-run

# === VERIFY ===

curl https://<domain>/wp-json/vbrandsync/v1/import/status
```

---

## Standard Product Format

Tất cả scrapers output cùng format. Xem chi tiết: [bot-scrape.md](bot-scrape.md)

```json
{
  "id": "3312048385",
  "name": "Tên sản phẩm",
  "price": 659000,
  "salePrice": 550000,
  "sold": 41,
  "rating": 4.8,
  "image": "https://...",
  "localImage": "images/products/3312048385.jpg",
  "url": "https://...",
  "source": "lazada"
}
```

---

## Bot docs

| File | Mô tả |
|------|--------|
| [bot-scrape.md](bot-scrape.md) | Master scrape — kiến trúc + standard format |
| [bot-import-woo.md](bot-import-woo.md) | Import vào WooCommerce |
| [scrape-lazada-shop.md](scrape-lazada-shop.md) | Chi tiết scrape Lazada |
| [scrape-shopee-shop.md](scrape-shopee-shop.md) | Chi tiết scrape Shopee (HTML mode) |
