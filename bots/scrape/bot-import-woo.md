# Bot: Import Shop vào WooCommerce

Import data từ `shops/<SHOP>/` vào WooCommerce site qua vbrandsync REST API.
Đọc **standard format** — hoạt động với data từ bất kỳ scraper nào (lazada, shopee, ...).

## Usage

```bash
# Clean + import toàn bộ
bots/scrape/bot-import-woo.md import <site_url> <shop_dir> --clean

# Import thử 20 sản phẩm đầu
bots/scrape/bot-import-woo.md import <site_url> <shop_dir> --clean --limit 20

# Import không xóa data cũ
bots/scrape/bot-import-woo.md import <site_url> <shop_dir>

# Dry run (xem trước, không import)
bots/scrape/bot-import-woo.md import <site_url> <shop_dir> --dry-run

# Import nhanh không hình (test API)
bots/scrape/bot-import-woo.md import <site_url> <shop_dir> --clean --skip-images
```

## Ví dụ thực tế

```bash
# Import Nike (Lazada) vào production
bots/scrape/bot-import-woo.md import https://nike.b-teka.com shops/nike-flagship-store/ --clean

# Import Logitech (Shopee) vào local
bots/scrape/bot-import-woo.md import http://brand-site.test shops/logitech_official_store/ --clean

# Test local trước, rồi production
bots/scrape/bot-import-woo.md import http://brand-site.test shops/nike-flagship-store/ --clean --limit 20
bots/scrape/bot-import-woo.md import https://nike.b-teka.com shops/nike-flagship-store/ --clean
```

## Options

| Flag | Mô tả |
|------|--------|
| `--clean` | Xóa tất cả products/categories/media trước khi import |
| `--limit <n>` | Giới hạn số sản phẩm import |
| `--skip-images` | Bỏ qua import hình (nhanh hơn cho testing) |
| `--dry-run` | Chỉ hiển thị, không import thực tế |

## Flow

```
1. Kiểm tra kết nối API (GET /import/status)
2. [--clean] Xóa tất cả products + categories + media
3. Import shop-info (name, logo, shopId, sellerId, source)
4. Import categories
5. Import products (đọc standard fields: price, salePrice, sold, ...)
6. Verify (GET /import/status → kiểm tra số lượng)
```

## Input Format (standard)

Đọc từ `shops/<SHOP>/`:
- `shop-info.json` — `name`, `logo`/`avatar`, `shopId`, `sellerId`, `url`
- `categories.json` — `[{ name, ... }]`
- `products.json` — standard format (xem `bot-scrape.md`)

**Backward compat:** Cũng đọc được Lazada-specific fields (`originalPriceRaw`, `priceRaw`, `soldLastMonth`).

## vbrandsync REST API

| Method | Endpoint | Mô tả |
|--------|----------|--------|
| `GET` | `/import/status` | Đếm products, categories hiện tại |
| `POST` | `/import/clean` | Xóa tất cả products, categories, media |
| `POST` | `/import/shop-info` | Update shop info |
| `POST` | `/import/category` | Import hoặc tìm 1 category |
| `POST` | `/import/product` | Import 1 sản phẩm |

### Product fields gửi lên API

| Field | Source | Mô tả |
|-------|--------|--------|
| `title` | `p.name` | Tên sản phẩm |
| `price` | `p.price` | Giá gốc (VND) |
| `discount_price` | `p.salePrice` | Giá sale (nếu có) |
| `sold_count` | `p.sold` | Số lượng đã bán |
| `rating` | `p.rating` | Rating 0-5 |
| `sku` | `p.sku` | SKU (optional) |
| `image_url` | `p.image` | URL hình chính |
| `product_id` | `p.id` | ID trên platform gốc |
| `source` | `p.source` | "lazada" / "shopee" / ... |
| `product_url` | `p.url` | URL gốc |

## Run command

```bash
cd /Users/luan/apps/vbrand/bots/scrape
node scripts/import-to-woocommerce.js <site_url> <shop_dir> [options]
```

## Troubleshooting

### Site unreachable / API error

```bash
# Kiểm tra site local có chạy không
curl http://brand-site.test/wp-json/vbrandsync/v1/import/status

# Kiểm tra plugin active
ssh vbrand@18.141.199.175 "cd /home/vbrand/sites/nike_b_teka_com && wp plugin list --status=active"
```

### 0 products sau import

```bash
# WooCommerce coming soon mode
wp option update woocommerce_coming_soon no
wp option update woocommerce_store_pages_only no
wp cache flush
```

### Import thành công nhưng shop page trống

```bash
# Kiểm tra shop page ID
wp option get woocommerce_shop_page_id
wp cache flush && wp transient delete --all && wp rewrite flush
```
