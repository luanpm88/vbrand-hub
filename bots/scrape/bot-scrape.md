# Bot: Scrape Shop

Master scraper — hỗ trợ nhiều nguồn, tất cả output vào cùng `shops/` format.

## Self-learn rule (BẮT BUỘC sau mỗi scrape)
1. Scrape pattern mới (DOM structure thay đổi, anti-bot) → update bot file + scraper script
2. Price format mới → update `import-to-woocommerce.js` `toIntPrice()`
3. Gotcha về source (Lazada API thay đổi, Shopee blocked) → update `scrape-lazada-shop.md` / `scrape-shopee-shop.md`
4. **KHÔNG để kiến thức chết trong context window** — ghi ra file trong cùng commit

## Usage

```bash
# Scrape từ Lazada (URL)
bots/scrape/bot-scrape.md --type=lazada --url=https://www.lazada.vn/shop/nike-flagship-store/

# Scrape từ Shopee (HTML file — user copy từ DevTools)
bots/scrape/bot-scrape.md --type=shopee --shop=logitech.official.store --html=/tmp/shopee.html

# Sau khi scrape xong → import vào WooCommerce
bots/scrape/bot-import-woo.md --site=https://nike.b-teka.com --shop=nike-flagship-store --clean
```

---

## Layers

```
User
  │
  ▼
bot-scrape.md          ← entry point, chọn type
  ├── type=lazada  →  scrape-lazada-shop.md  →  scripts/scrape-lazada-shop.js
  └── type=shopee  →  scrape-shopee-shop.md  →  scripts/scrape-shopee-shop.js
  │
  ▼
shops/<SHOP_NAME>/     ← standard format (xem bên dưới)
  │
  ▼
bot-import-woo.md      ← import vào WooCommerce, đọc bất kỳ shop/ nào
  └── scripts/import-to-woocommerce.js
```

---

## Standard Shop Data Format

Tất cả scrapers (lazada, shopee, tương lai: tiktok, sendo...) phải output **cùng format** này.

### `shops/<SHOP_NAME>/`

```
shops/<SHOP_NAME>/
├── shop-info.json          ← thông tin shop (chuẩn)
├── categories.json         ← danh mục [{ "name": "..." }]
├── products.json           ← danh sách sản phẩm (chuẩn)
├── products.md             ← bảng markdown tổng hợp
├── scrape-summary.json     ← metadata (thời gian, source, params)
├── full.html               ← (shopee only) HTML gốc để re-parse
└── images/
    ├── shop/
    │   └── avatar.jpg      ← logo shop
    └── products/
        └── <ID>.jpg
```

### `shop-info.json` (standard)

```json
{
  "name": "Nike Flagship Store",
  "slug": "nike-flagship-store",
  "logo": "https://cdn.lazada.vn/...",       ← main image URL
  "localLogo": "images/shop/avatar.jpg",      ← local path
  "url": "https://...",
  "shopId": "4767314",
  "sellerId": "200712880444",                 ← optional
  "source": "lazada"                          ← "lazada" | "shopee" | ...
}
```

### `products.json` (standard — mỗi item)

```json
{
  "id": "3312048385",
  "name": "Áo thun Nike Sportswear",
  "price": 659000,               ← regular price (VND integer)
  "salePrice": 550000,           ← sale price nếu có discount, null nếu không
  "sold": 41,                    ← số lượng đã bán (bất kỳ period nào)
  "rating": 4.8,                 ← 0-5
  "sku": "ABC-123",              ← optional
  "inStock": true,               ← optional
  "image": "https://...",        ← main image URL
  "images": ["https://..."],     ← tất cả images
  "url": "https://...",          ← URL gốc trên platform
  "localImage": "images/products/3312048385.jpg",
  "source": "lazada",            ← "lazada" | "shopee" | ...

  // Platform-specific (optional, giữ nguyên để reference)
  // Lazada: priceRaw, originalPriceRaw, soldLastMonth, reviews, brandId, ...
  // Shopee: shopId, priceMin, priceMax, currency, description, ...
}
```

---

## Thêm scraper mới

Khi có nguồn mới (ví dụ: TikTok Shop, Sendo):

1. Tạo `scripts/scrape-tiktok-shop.js` — scrape + output standard format
2. Tạo `scrape-tiktok-shop.md` — doc riêng cho TikTok
3. Update `bot-scrape.md` này — thêm `--type=tiktok`
4. Import bot (`bot-import-woo.md`) **không cần thay đổi** — đọc standard format

---

## Scripts

```bash
# Run scraper directly
cd /private/tmp
node /Users/luan/apps/vbrand/bots/scrape/scripts/scrape-lazada-shop.js <url> [options]
node /Users/luan/apps/vbrand/bots/scrape/scripts/scrape-shopee-shop.js <shop> --html <file> [options]

# Import (after scrape)
node /Users/luan/apps/vbrand/bots/scrape/scripts/import-to-woocommerce.js <site_url> <shop_dir> [--clean]
```

## Prerequisites

```bash
cd /private/tmp
npm install puppeteer-core puppeteer-extra puppeteer-extra-plugin-stealth
```
