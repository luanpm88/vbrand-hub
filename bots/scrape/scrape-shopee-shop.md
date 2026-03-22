# Bot: Scrape Shopee Shop

Scrape toàn bộ shop từ Shopee → lấy products.json, products.md, images.
**Không import trực tiếp vào WooCommerce** (dùng import-to-woocommerce.js sau khi scrape).

---

## Quick Start

```bash
# Cách duy nhất hoạt động với Shopee: HTML mode
# 1. User mở Shopee trong Chrome (đã login), vào trang shop
# 2. Scroll đến cuối trang để load hết sản phẩm
# 3. DevTools → right-click <html> tag → Copy → Copy outerHTML
# 4. Paste vào file /tmp/shopee-shop.html (hoặc dùng "Save as")
# 5. Chạy:
node /Users/luan/apps/vbrand/bots/scrape/scripts/scrape-shopee-shop.js baseus.flagship.vn --html /tmp/shopee-shop.html
```

---

## Tại sao chỉ dùng HTML mode?

Shopee chặn tất cả automation (Puppeteer, Stealth, Cookies) — bot detection rất gắt:
- Hiển thị CAPTCHA / verify ngay cả khi có cookies
- Redirect về trang login khi detect headless browser
- Block IP sau vài request tự động

**Giải pháp:** User tự browse bằng Chrome logged-in → copy HTML → script parse.
Script không kết nối mạng trong parse mode — chỉ dùng Puppeteer để chạy DOM queries trên HTML đã copy.

---

## Usage

```bash
# HTML mode (cách chính)
node /Users/luan/apps/vbrand/bots/scrape/scripts/scrape-shopee-shop.js <shop_name> --html <file.html>

# Với output dir tùy chỉnh
node scrape-shopee-shop.js baseus --html /tmp/shopee-baseus.html --output /tmp/scrape/baseus
```

### Options

| Flag | Mô tả |
|------|--------|
| `--html <file>` | Parse từ HTML file (cách chính, recommended) |
| `--output <dir>` | Thư mục output (default: `bots/scrape/shops/<shop_name>/`) |

---

## Output Structure

```
bots/scrape/shops/<shop_name>/
├── full.html               ← copy HTML đã paste (để re-parse sau nếu cần)
├── shop-info.json          ← tên, avatar, url, shopId
├── categories.json         ← [] (không có trong HTML mode)
├── products.json           ← danh sách sản phẩm
├── products.md             ← bảng markdown tổng hợp
├── scrape-summary.json     ← metadata + selectors đã dùng
└── images/
    ├── shop/
    │   └── avatar.jpg
    └── products/
        ├── <itemId>.jpg
        └── <itemId>_1.jpg  ← hình phụ (nếu có trong JSON mode)
```

---

## Product Data Structure

```json
{
  "id": "98765432",
  "shopId": "12345678",
  "name": "Chuột không dây Logitech M330 SILENT PLUS",
  "description": "",
  "price": 450000,
  "priceMin": 450000,
  "priceMax": 450000,
  "currency": "VND",
  "stock": 0,
  "sold": 1200,
  "rating": 4.8,
  "image": "https://down-vn.img.susercontent.com/file/xxx",
  "images": [...],
  "url": "https://shopee.vn/product/12345678/98765432",
  "localImage": "images/products/98765432.jpg"
}
```

**Lưu ý — HTML mode limitations:**
- `description` → `""` (cần API riêng, không có trong listing page)
- `stock` → `0` (không hiển thị trên listing)
- `models`, `tierVariations` → không có
- `ratingCount` → `0` (chỉ có rating star)

---

## Extraction Strategies

Script thử lần lượt, dùng kết quả đầu tiên có sản phẩm:

### Strategy 1 — JSON trong `<script>` tags
Shopee đôi khi embed product data trong script:
```
"items": [{...itemid, name, price...}]
```
Nếu tìm thấy → data đầy đủ hơn (description, stock, models).

### Strategy 2 — DOM links `-i.SHOPID.ITEMID` *(hoạt động chính)*
Shopee product URL pattern: `/product-name-i.SHOPID.ITEMID`
```js
document.querySelectorAll('a[href*="-i."]')
// regex: /-i\.(\d+)\.(\d+)/  →  m[1]=shopId, m[2]=itemId
```
Walk up DOM tree tới product card → extract name, price, sold, rating, image.

### Strategy 3 — DOM links `/product/SHOPID/ITEMID`
Fallback cho alternate URL format.

---

## Hướng dẫn copy HTML từ DevTools

1. Mở Chrome, vào trang shop Shopee (đã login)
2. **Quan trọng: scroll đến hết trang** — Shopee lazy-loads sản phẩm
3. `F12` → Elements tab
4. Tìm thẻ `<html>` ở đầu DOM tree
5. Right-click → **Copy** → **Copy outerHTML**
6. Paste vào file: `pbpaste > /tmp/shopee-shop.html`
7. Hoặc: File → Save Page As → "Webpage, HTML Only"

### Kiểm tra HTML có đủ sản phẩm không

```bash
grep -c 'href.*-i\.' /tmp/shopee-shop.html
# Số links với pattern -i.SHOPID.ITEMID → ≈ số sản phẩm × vài links mỗi sản phẩm
# Nếu < 10 → HTML chưa đủ, cần scroll thêm hoặc copy lại
```

---

## Hướng dẫn cho Claude

### Khi user nói

| User nói | Claude làm |
|----------|-----------|
| `scrape shopee <shop>` | Hướng dẫn user copy HTML, rồi chạy script |
| `parse shopee html <file>` | Chạy `--html <file>` |
| `scrape + import shopee vào <site>` | Scrape HTML mode → verify products.json → import với import-to-woocommerce.js |

### Flow cho Claude

1. **Nhắc user copy HTML** — scroll đến cuối, copy outerHTML của `<html>`
2. **Chạy script** — `node scrape-shopee-shop.js <shop> --html <file>`
3. **Verify** — `cat shops/<shop>/products.json | python3 -m json.tool | head -50`
4. **Import** (nếu cần) — dùng `import-to-woocommerce.js` (cùng format Lazada)

---

## Prerequisites

```bash
cd /private/tmp
npm install puppeteer-core
# puppeteer-extra + stealth (optional, chỉ cần nếu dùng browser automation mode)
npm install puppeteer-extra puppeteer-extra-plugin-stealth
```

Script tự fallback: `puppeteer-extra` → `puppeteer` → `puppeteer-core`

---

## Troubleshooting

### 0 sản phẩm tìm được

**Nguyên nhân:** Chưa scroll đến cuối, Shopee chưa render hết cards
**Fix:** Scroll đến cuối trang trong Chrome, copy HTML lại

**Nguyên nhân:** HTML bị truncate khi paste
**Fix:** Dùng "Save as" → "Webpage, HTML Only" thay vì copy-paste thủ công

**Nguyên nhân:** Shopee đổi DOM structure
**Fix:** Mở `scrape-summary.json`, xem `selectors` field — update `page.evaluate()` trong `parseFromHtmlFile()`

### Hình không download được

**Nguyên nhân:** Image URL từ `susercontent.com` cần Referer header
**Fix:** Script đã thêm `Referer: https://shopee.vn/` header — nếu vẫn lỗi, check URL có hợp lệ không

### Tên sản phẩm bị sai/rỗng

Shopee render React → tên thường nằm trong `img.alt` attribute
Script ưu tiên `img.alt` trước, fallback sang text nodes.
Nếu `img.alt` rỗng → check `[class*="name"]` hoặc `[class*="title"]` selector trong `page.evaluate()`

---

## Learned Patterns (self-learning)

### DOM selectors đã verify hoạt động (2026-03-22, thử với logitech.official.store)

- **Product links:** `a[href*="-i."]` → regex `-i\.(\d+)\.(\d+)` — **hoạt động**
- **Product card:** `.closest('li, [class*="shopee-search-item"], [class*="col-"], [class*="item"]')`
- **Name:** `img.alt` (reliable — Shopee dùng product name làm alt text)
- **Price:** `[aria-label="promotion price"]` là span rỗng (a11y label), **không phải container**
  - Đi lên `parentElement` → tìm `span` có text khớp `/^\d[\d\.]+$/` (e.g. `"2.619.000"`)
  - Parse: `parseInt("2.619.000".replace(/\./g, ''))` → `2619000`
  - `[class*="price"]` **không hoạt động** vì Shopee dùng Tailwind utility classes
- **Sold:** Tìm `div/span` leaf node (không có children) có text chứa `"Đã bán"`
  - Format "5k+" → `5000`, "1.2k" → `1200`, "1.200" → `1200`
- **Rating:** `img[alt="rating-star"]` nextElementSibling → text `"4.9"`
  - `[class*="stars-filled"]` style.width **không hoạt động** (Shopee dùng SVG/img)
- **Image:** `img[src*="susercontent"]` không có `100x100` suffix — lấy ảnh lớn nhất

### Test results

**logitech.official.store** (2026-03-22):
- 61 sản phẩm | 61/61 có price | 61/61 có sold | 61/61 có rating
- Price: ₫259,000 — ₫3,039,000 | Sold "5k+" parse → 5000
- 61/61 hình download OK từ `down-vn.img.susercontent.com`
