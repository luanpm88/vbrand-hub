# Scrape WooCommerce Store API

Bot này scrape mọi WooCommerce site có **WC Store API public** — không cần headless browser, không cần copy HTML, không cần login.

## Khi dùng

Khi nguồn import là 1 WooCommerce site Vietnamese B2B điển hình (`*.vn`, `*.com.vn`, `*.com`) — phần lớn đều có Store API public mặc định.

Trước khi scrape, **test detection 1 lần**:

```bash
curl -s "https://target.com/wp-json/wc/store/v1/products?per_page=1" | head -2
```

- `[{...}]` JSON array → OK, dùng bot này
- `404` / `401 Unauthorized` / `not enabled` → fallback sang `scrape-lazada-shop.md` hoặc `scrape-shopee-shop.md`

## Usage

```bash
cd /private/tmp
node /Users/luan/apps/vbrand/bots/scrape/scripts/scrape-woocommerce-store.js <site_url> [options]
```

**Options:**

| Flag | Default | Mô tả |
|------|---------|-------|
| `--slug <name>` | hostname | Tên folder output (`bots/scrape/shops/<slug>/`) |
| `--limit <n>` | 0 (all) | Giới hạn số products |
| `--per-page <n>` | 50 (max 100) | Items per pagination request |
| `--no-images` | false | Skip image downloads (fast mode) |
| `--concurrency <n>` | 5 | Parallel image downloads |

## Example: ductrico → khomaynenkhi.com (2026-05-20)

```bash
# Step 1: scrape (5s)
cd /private/tmp
node /Users/luan/apps/vbrand/bots/scrape/scripts/scrape-woocommerce-store.js https://ductrico.com --slug ductrico
# → 67 products + 24 categories + 67 images

# Step 2: import to target site
cd /Users/luan/apps/vbrand/bots/scrape
node scripts/import-to-woocommerce.js https://khomaynenkhi.com shops/ductrico/ --clean
# → 67 imported, 0 failed
```

## Output

Standard format (xem `bot-scrape.md` cho schema đầy đủ):

```
shops/<slug>/
├── shop-info.json       # { name, description, url, source: "woocommerce", scrapedAt }
├── categories.json      # [ { id, name, slug, parent, count, description, image } ]
├── products.json        # [ { id, name, slug, sku, description, price, salePrice, image, images[], categories[], rating, in_stock, url, source } ]
└── images/products/<id>.jpg
```

## Why pure HTTP (no browser)

WC Store API là endpoint public chuẩn của WooCommerce 7+ — không cần auth, không có rate limit đáng kể. Pagination qua headers `x-wp-total` / `x-wp-totalpages`. So với Lazada/Shopee:

- ✅ 100× nhanh hơn (no browser warmup)
- ✅ 0 risk bị block IP (single HTTP client, normal UA)
- ✅ Không phụ thuộc CSS selectors (data structure stable)
- ❌ Chỉ dùng được với WC site (không Lazada / Shopee / Sendo / Tiki)

## Lưu ý: B2B sites với price=0 ("Liên hệ")

Nhiều Vietnamese industrial B2B sites set `price = 0` cho mọi product và hiển thị "Liên hệ" thay vì giá. Bot vẫn import bình thường — WooCommerce sẽ render empty price area trên storefront, customer phải gọi/inbox.

Nếu muốn override sang real prices, dùng `--limit 0 --no-images`, edit `products.json` thủ công, rồi import.

---

**Created:** 2026-05-20 (khomaynenkhi launch — first WC Store API scrape)
**Maintained by:** Claude / vBrand bot ops
