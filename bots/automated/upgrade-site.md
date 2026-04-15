# Bot: Upgrade Old Site

Nâng cấp website cũ/xấu/lỗi thời thành site chuyên nghiệp trên vBrand platform. Giữ nguyên 100% nội dung, sản phẩm, hình ảnh từ site cũ — chỉ thay đổi thiết kế.

**Khác với `create-theme-from-url.md`:**
- Site cũ là **content source** (không phải design source)
- Design hoàn toàn mới do Claude tạo (không copy từ URL)
- Có phase scrape content + phase viết marketing copy
- Có thể dùng DALL-E 3 API để gen hero/banner images
- Reuse `import-to-woocommerce.js` để import products

## Usage

```bash
# Upgrade site cũ → theme mới
bots/automated/upgrade-site.md <theme-name> <old-site-url>
bots/automated/upgrade-site.md dieu-an https://vancongnghiepdieuan.com.vn/

# Review theme đã build
bots/automated/upgrade-site.md review <theme-name>
```

## Output

- Theme hoàn chỉnh tại `/site/wp-content/themes/<theme-name>/`
- Scraped content tại `<theme>/original/` (screenshots, products, images, analysis)
- Design plan tại `<theme>/plan/` (design-plan, content, image-plan, sitemap)
- Design audit tại `<theme>/design/` (versions/audit_N, final)
- Schema-driven: MỌI text, image, section đều configurable
- Git commit

---

# SELF-IMPROVE RULE — ĐỌC TRƯỚC KHI LÀM GÌ

**Sau mỗi lần upgrade site, bot PHẢI tự cập nhật file này với:**
1. Mọi lỗi mới gặp → thêm vào `KNOWN PITFALLS` bên dưới
2. Pattern mới hiệu quả → thêm vào section phù hợp
3. Checklist mới → thêm vào Audit Checklist

**Khi upgrade site:**
- Reference latest theme (hiện tại: `sattanhung`) để học patterns
- KHÔNG clone/copy code từ theme cũ — build from scratch
- Đọc latest theme's `design/HISTORY.md` để tránh lặp lỗi cũ
- Đọc `create-theme-from-url.md` KNOWN PITFALLS — tất cả đều áp dụng

**Latest theme:** `sattanhung` (built 2026-04-10, decorative ironwork, warm orange palette)
→ Xem `/site/wp-content/themes/sattanhung/` để tham khảo patterns

---

# KNOWN PITFALLS — INHERIT TỪ create-theme-from-url.md + THÊM MỚI

## Inherited (đọc `create-theme-from-url.md` để xem chi tiết):
- 🔴 CRITICAL: KHÔNG tạo `woocommerce.php` trong theme root
- 🔴 CRITICAL: Dùng `wc_get_products()` trực tiếp, KHÔNG dùng WC loop
- 🟡 MAJOR: `content-product.php` phải dùng `<li>` (nếu dùng WC loop)
- 🟡 MAJOR: Mobile shop grid phải explicit 1-col tại 640px
- 🟡 MAJOR: WC block pages (cart, checkout) — detect trong `page.php`
- 🟡 MAJOR: Quantity input cần custom +/− buttons
- 🟠 MINOR: Puppeteer NODE_PATH khi chạy từ /tmp
- 🟠 MINOR: Copy audit cuối thành `final/`

## Mới cho upgrade-site:

### 🟡 MAJOR: Product images từ site cũ thường rất nhỏ/mờ
**Triệu chứng:** Images 100x100px hoặc watermarked, trông tệ trên theme mới.
**Fix:** `object-fit: contain` + padding + background nhạt. KHÔNG stretch. Nếu quá tệ → placeholder gradient + ghi note cho user thay ảnh sau.

### 🟡 MAJOR: Old site encoding — charset mismatch
**Triệu chứng:** Text tiếng Việt bị mojibake khi scrape.
**Fix:** Detect charset từ `<meta charset>` hoặc `Content-Type` header. Convert sang UTF-8 nếu cần.

### 🟡 MAJOR: DALL-E images phải photorealistic, KHÔNG có text
**Triệu chứng:** DALL-E gen text trên ảnh → text bị garbled/sai chính tả.
**Fix:** Luôn thêm "no text, no logos, no words, no letters" vào prompt. Text overlay bằng CSS.

### 🟠 MINOR: Old site URL structure không chuẩn
**Triệu chứng:** `index.php?page=about` thay vì `/about/`. Links trong content trỏ tới URL cũ.
**Fix:** Không giữ link structure cũ — chỉ scrape content, links mới theo WP permalink.

---

# PHASE 0: Đọc latest theme + learn patterns

Trước khi bắt đầu:
1. Đọc `/site/wp-content/themes/sattanhung/design/HISTORY.md`
2. Đọc `/site/wp-content/themes/sattanhung/functions.php`
3. Đọc `/site/wp-content/themes/sattanhung/woocommerce/archive-product.php`
4. Đọc `bots/automated/create-theme-from-url.md` — full KNOWN PITFALLS + SCHEMA REFERENCE

---

# PHASE 1: Scrape Old Site → `<theme>/original/`

## 1.1 Screenshot ALL pages

Dùng Puppeteer headless browser:
1. Chụp TỪNG trang (full page scroll)
2. Navigate tất cả links trong menu/sidebar
3. Chụp tất cả category pages
4. Chụp sample product pages
5. Lưu vào: `<theme>/original/screenshots/`

## 1.2 Extract ALL content

Scraper script tại `<theme>/original/scripts/scrape-site.js`:

Output files:
```
original/
├── shop-info.json     # Company info (standard format)
│   {name, tagline, phones[], emails[], addresses[], taxId, logo}
├── categories.json    # Product categories
│   [{id, name, slug, parent, productCount}]
├── products.json      # All products (compatible với import-to-woocommerce.js)
│   [{name, price, salePrice, image, images[], category, description, specifications}]
├── content.json       # All text content by page
│   {homepage: {...}, about: {...}, contact: {...}, ...}
└── analysis.md        # Site analysis document
```

## 1.3 Download ALL images

```
original/images/
├── logo.png           # Company logo
└── products/          # All product images
    ├── 1.jpg
    ├── 2.jpg
    └── ...
```

## 1.4 Write analysis.md

Nội dung:
- Content inventory (tất cả text blocks, organized by page)
- Image inventory (tất cả images với quality notes: kích thước, chất lượng)
- Missing content (những gì site hiện đại cần mà site cũ thiếu)
- Product data quality assessment
- Menu/navigation structure
- Đề xuất cải tiến

---

# PHASE 2: Design Plan + Content Writing → `<theme>/plan/`

**Đây là review checkpoint — user review trước khi build.**

## 2.1 Design Plan (`plan/design-plan.md`)

Nội dung:
- **Color palette** — chọn dựa trên ngành nghề + logo colors. CSS custom properties.
- **Typography** — Google Fonts, sizes, weights
- **CSS prefix** — 2 chữ cái từ theme name
- **Homepage sections** (order + mô tả từng section)
- **About page sections**
- **Contact page sections**
- **Shop page layout**
- **Mobile strategy**

**Nguyên tắc chọn palette:**
| Ngành | Palette gợi ý |
|-------|---------------|
| Công nghiệp / B2B | Deep blue + safety orange |
| F&B / Cafe | Warm brown + cream |
| Thời trang | Black + accent |
| Organic / Healthy | Green + earth tones |
| Tech / Gadgets | Dark + electric blue |

## 2.2 Content Writing (`plan/content.md`)

Viết TOÀN BỘ marketing copy dựa trên info scrape được:
- Hero slide taglines (3)
- Giới thiệu công ty (~300 words, mở rộng từ info hiện có)
- Mô tả TỪNG danh mục sản phẩm (2-3 câu professional)
- "Tại sao chọn [Company]" (4-5 USPs)
- FAQ (5-6 câu hỏi phổ biến)
- Footer description
- Meta descriptions
- CTA texts

**Nguyên tắc viết:**
- Dựa trên facts từ site cũ, KHÔNG bịa đặt
- Mở rộng, chuyên nghiệp hóa ngôn ngữ
- Tiếng Việt chuẩn, không lỗi chính tả
- Tone: chuyên gia, đáng tin cậy, không quá bán hàng

## 2.3 Image Plan (`plan/image-plan.md`)

Danh sách images cần gen qua DALL-E 3:
| Image | Size | Prompt | Placement |
|-------|------|--------|-----------|
| ... | 1792x1024 | "..." | Hero slide 1 |

**Rules:**
- DALL-E chỉ gen hero/banner/about images — KHÔNG gen product images
- Product images giữ nguyên từ site cũ
- Luôn thêm "no text, no logos, no words" vào prompt
- Size: 1792x1024 (landscape hero) hoặc 1024x1024 (square)
- Max ~6-8 images ($0.50-0.80 total)

## 2.4 Sitemap (`plan/sitemap.md`)

Page structure + module mapping:
```
Homepage
├── Hero slider (3 slides)
├── Trust bar (stats)
├── Featured categories (grid)
├── Product carousel
├── About preview
├── CTA banner
├── Blog section
├── Partners
└── Subscription CTA

About
├── Page hero
├── Company story
├── Mission/Vision
├── Stats
├── Facilities
└── CTA

Contact
├── Page hero
├── Contact cards
├── Map
├── Form
└── FAQ

Shop → WooCommerce archive
Product → WooCommerce single
Cart → WC block
Checkout → WC block
```

---

# PHASE 2.5: Generate DALL-E Images (Optional)

Script: `plan/scripts/generate-images.js`

```javascript
const OpenAI = require('openai');
const fs = require('fs');
const path = require('path');
const https = require('https');

const client = new OpenAI({ apiKey: process.env.OPENAI_API_KEY });

async function generateImage(prompt, size, outputPath) {
    console.log(`Generating: ${path.basename(outputPath)}...`);
    const response = await client.images.generate({
        model: "dall-e-3",
        prompt: prompt + " — no text, no logos, no words, no letters, photorealistic",
        n: 1,
        size: size,
        quality: "hd",
    });
    const url = response.data[0].url;
    // Download image
    const file = fs.createWriteStream(outputPath);
    https.get(url, (res) => res.pipe(file));
    console.log(`Saved: ${outputPath}`);
}

// Read image-plan.json and generate each image
// ...
```

Chạy:
```bash
OPENAI_API_KEY=sk-... node plan/scripts/generate-images.js
```

---

# PHASE 3: Build Theme

**Same structure as `create-theme-from-url.md` Phase 2 nhưng design từ scratch.**

### Theme structure
```
<theme-name>/
├── style.css                  # ALL CSS, prefix từ design-plan
├── screenshot.png             # 1200x900
├── functions.php              # ~110 lines
├── schema.php                 # ~900+ lines, content từ plan/content.md
├── header.php                 # Topbar + sticky nav + mobile hamburger
├── footer.php                 # Multi-column + JS
├── index.php, page.php, single.php
├── page-homepage.php          # Sections từ plan/sitemap.md
├── page-aboutus.php
├── page-contact.php
├── woocommerce/
│   ├── archive-product.php
│   ├── single-product.php
│   ├── content-single-product.php
│   └── global/quantity-input.php
├── assets/images/             # DALL-E + migrated images
├── original/                  # Phase 1 output
├── plan/                      # Phase 2 output
└── design/                    # Phase 4 audit
```

### Key patterns (xem `create-theme-from-url.md` để biết chi tiết):
- Schema-driven: MỌI text, image, section qua schema.php
- CSS: custom properties, BEM, 27+ sections, responsive
- PHP: `$td = vbrand_load_theme_data(); $g = function(...)`
- WC: `wc_get_products()` direct, NO `woocommerce.php` at root
- functions.php: theme support, WC hooks, contact form, auto page setup

---

# PHASE 4: Import Products

Reuse existing infrastructure:

```bash
# Copy scraped data to standard format
# products.json đã compatible từ Phase 1

# Import vào WooCommerce site
node bots/scrape/scripts/import-to-woocommerce.js <site-url> <theme>/original/ --clean
```

Verify:
- Tất cả categories đã import
- Tất cả products với images
- Product images hiển thị đúng trên theme mới

---

# PHASE 5: Audit Loop

**Same as `create-theme-from-url.md` Phase 3.**

Screenshot script → `design/versions/audit_N/` → 20-point checklist → fix → repeat (max 5).

### Thêm checklist cho upgrade-site:

| # | Area | Check |
|---|------|-------|
| 21 | **Old product images** | Render OK dù nhỏ/mờ (contain + padding, không stretch) |
| 22 | **Vietnamese content** | Không bị mojibake, đúng dấu, đẹp |
| 23 | **DALL-E images** | Photorealistic, không có text garbled |
| 24 | **Company info** | Đúng tên, SĐT, email, địa chỉ từ site cũ |
| 25 | **All old content migrated** | Không thiếu sản phẩm, category, hay thông tin nào |

### Audit checklist gốc (20 items — xem `create-theme-from-url.md`):
1-20: selects, buttons, cart, checkout, product images, text overflow, spacing, typography, mobile nav, responsive, colors, hover, forms, schema, fallbacks, WC notices, footer, admin bar, shop grid mobile, shop products loading.

---

# PHASE 6: Finalize

1. `cp -r design/versions/audit_N design/versions/final`
2. Generate `screenshot.png` (1200x900)
3. Write `design/HISTORY.md`
4. Git commit:
   ```bash
   cd /Users/luan/apps/vbrand/site/wp-content/themes
   git add <theme-name>/
   git commit -m "feat: add <theme-name> theme — upgraded from <old-url>"
   ```
5. Chạy `enforce-cod-vbrand-express.php` trên site mới
6. **Cập nhật bot này** với lessons learned
7. Cập nhật `create-theme-from-url.md` "Latest theme" nếu cần

---

# SCHEMA REFERENCE

Xem `create-theme-from-url.md` — SCHEMA REFERENCE section. Áp dụng y hệt.

**Required sessions:** general, menu, home, about-us, contact
**Required options:** site_name, logo, favicon, phone, email, address, menus, hero_slides, featured, categories, products, about, contact, footer, etc.

---

# ERROR HANDLING

- Old site không truy cập → báo user, abort
- Old site encoding lạ → detect + convert UTF-8
- Product images quá nhỏ → contain + padding + ghi note
- DALL-E API fail → gradient placeholder fallback
- Products import fail → retry, check format compatibility
- Theme đã tồn tại → hỏi confirm overwrite

---

# WORKFLOW SUMMARY

```
┌─────────────────────────────────────────────────────────┐
│  PHASE 0: Read latest theme + learn patterns            │
│  PHASE 1: Scrape old site → original/                   │
│  PHASE 2: Design plan + Content → plan/ ← USER REVIEW  │
│  PHASE 2.5: DALL-E images (optional)                    │
│  PHASE 3: Build theme                                   │
│  PHASE 4: Import products                               │
│  PHASE 5: Audit loop (max 5 rounds)                     │
│  PHASE 6: Finalize + commit                             │
└─────────────────────────────────────────────────────────┘
```
