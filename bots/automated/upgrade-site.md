# Bot: Upgrade Old Site

Nâng cấp website cũ/xấu/lỗi thời thành site chuyên nghiệp trên vBrand platform. Giữ nguyên 100% nội dung, sản phẩm, hình ảnh từ site cũ — chỉ thay đổi thiết kế.

**Khác với `create-theme-from-url.md`:**
- Site cũ là **content source** (không phải design source)
- Design hoàn toàn mới do Claude tạo (không copy từ URL)
- Có phase scrape content + phase viết marketing copy
- Dùng gpt-image-1 API để gen hero/banner images
- Reuse `import-to-woocommerce.js` để import products

## Usage

```bash
# Upgrade site cũ → theme mới (local only)
bots/automated/upgrade-site.md <theme-name> <old-site-url>
bots/automated/upgrade-site.md dieu-an https://vancongnghiepdieuan.com.vn/

# Upgrade + deploy production
bots/automated/upgrade-site.md <theme-name> <old-site-url> --deploy <domain>
bots/automated/upgrade-site.md dieu-an https://vancongnghiepdieuan.com.vn/ --deploy dieuan.b-teka.com

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
- (Optional) Production site live + products imported

---

# SELF-IMPROVE RULE — ĐỌC TRƯỚC KHI LÀM GÌ

**Sau mỗi lần upgrade site, bot PHẢI tự cập nhật:**
1. Mọi lỗi mới gặp → thêm vào `KNOWN PITFALLS` bên dưới
2. Pattern mới hiệu quả → thêm vào section phù hợp
3. Checklist mới → thêm vào Audit Checklist
4. Update `docs/SALES_HANDOVER.md` — thêm site mới vào demo sites table + FAQ count
5. Update `bots/report/sites.md` — thêm site registry entry
6. Update `Latest theme` reference ở trên nếu theme mới tốt hơn
7. Regenerate 3 PDFs (`npx md-to-pdf`) + sync Google Drive (`rclone sync`)
8. **KHÔNG để kiến thức chết trong context window** — ghi ra file trong cùng commit

**Khi upgrade site:**
- Reference latest theme để học patterns
- KHÔNG clone/copy code từ theme cũ — build from scratch
- Đọc latest theme's `design/HISTORY.md` để tránh lặp lỗi cũ
- Đọc `create-theme-from-url.md` KNOWN PITFALLS — tất cả đều áp dụng

**Latest theme:** `cafedanhphat` (built 2026-04-28, cafe distribution B2B+B2C, warm coffee brown + đỏ accent)
→ Xem `/site/wp-content/themes/cafedanhphat/` — copy from dreamcafe approach (saved 3-4 hours)

**Previous themes:** `autotaybac` (2026-04-15, auto detailing, dark navy + amber), `dieu-an` (2026-04-15, industrial valves, blue + orange), `sattanhung` (2026-04-10, decorative ironwork, warm orange)

---

# KNOWN PITFALLS — FULL LIST (từ dieu-an + sattanhung + create-theme)

## 🔴 CRITICAL

### CSS class names PHẢI match giữa PHP và style.css
**Bài học dieu-an:** Agent build CSS (style.css) và PHP templates riêng biệt → class names khác nhau hoàn toàn (vd CSS: `.da-trust` nhưng PHP dùng `.da-trust-bar`). Kết quả: toàn bộ homepage sections không render đúng.
**Fix:** Sau khi build xong, PHẢI chạy audit script so sánh tất cả `class="da-*"` trong PHP files vs CSS definitions. Dùng command:
```bash
# Extract PHP classes
grep -oE 'da-[a-zA-Z0-9_-]+' *.php woocommerce/*.php | awk -F: '{print $2}' | sort -u > /tmp/php.txt
# Extract CSS classes  
grep -oE '\.da-[a-zA-Z0-9_-]+' style.css | sed 's/^\.//' | sort -u > /tmp/css.txt
# Find mismatches
comm -23 /tmp/php.txt /tmp/css.txt
```
Nếu có mismatch → FIX PHP to match CSS (không đổi CSS).

### KHÔNG tạo `woocommerce.php` trong theme root
WC sẽ dùng file này thay vì `archive-product.php` → full theme bị override.

### Dùng `wc_get_products()` trực tiếp, KHÔNG dùng WC loop
WC loop phụ thuộc vào query vars, dễ bị conflict. `wc_get_products()` explicit, predictable.

### `.da-product-card__image` PHẢI có `display: block`
**Bài học dieu-an:** `<a>` tag mặc định `display: inline`. Khi có `aspect-ratio: 1`, inline element KHÔNG constrain height đúng → image chiếm gần hết card height → card `overflow: hidden` clip toàn bộ text body → product name + price + button hoàn toàn invisible.
**Fix:** LUÔN thêm `display: block` cho `.da-product-card__image`.

### Vietnamese diacritics PHẢI có trong MỌI default text
**Bài học dieu-an:** Agent viết schema.php defaults KHÔNG có dấu tiếng Việt ("Dieu An" thay vì "Diệu An", "San Pham" thay vì "Sản Phẩm"). Kết quả: toàn bộ site hiện text không dấu.
**Fix:** 
1. Trong prompt cho agent, PHẢI cung cấp bảng text mẫu với dấu đầy đủ
2. Sau khi build, grep check: `grep -c "[àáảãạ]" schema.php` — nếu = 0 → chắc chắn thiếu dấu
3. Fix TOÀN BỘ PHP files (schema + templates) — không chỉ schema

### KHÔNG double-nest CSS grid classes trên section + inner div
**Bài học autotaybac:** `<section class="atb-categories">` chứa `<div class="atb-categories">` → CSS `.atb-categories { display: grid; grid-template-columns: repeat(3,1fr) }` apply 2 lần → inner grid bị constrain thành 1/3 width → cards render cực nhỏ. Tương tự xảy ra với `.atb-blog`, `.atb-partners`, `.atb-about-preview`.
**Fix:** Section wrapper dùng generic class (`atb-section`), chỉ inner div mới dùng grid class. LUÔN grep check sau khi build:
```bash
# Tìm double-nesting
for cls in categories blog partners about-preview; do
  count=$(grep -c "atb-${cls}" page-homepage.php)
  if [ "$count" -gt 1 ]; then echo "⚠️  Double-nest: atb-${cls} appears $count times"; fi
done
```

### Service/category cards PHẢI có overlay div trong PHP
**Bài học autotaybac:** CSS `.atb-categories__card-overlay` defines gradient overlay cho text readability, nhưng PHP template không render `<div class="atb-categories__card-overlay"></div>` → text trắng trên ảnh sáng không đọc được.
**Fix:** LUÔN thêm overlay div giữa image div và content div trong card structure.

### Blog page PHẢI set `page_for_posts` trong WP
**Bài học autotaybac:** Tạo blog page template nhưng không set `wp option update page_for_posts <ID>` → `/tin-tuc/` hiện "Chưa có bài viết nào" dù có posts.
**Fix:** Sau khi tạo site, PHẢI:
```bash
BLOG_ID=$(wp post create --post_type=page --post_status=publish --post_title='Tin Tức' --post_name='tin-tuc' --porcelain)
wp option update page_for_posts $BLOG_ID
```

### Blog posts PHẢI có featured image — ALL posts
**Bài học autotaybac:** 3/4 bài blog có ảnh từ original, 1 bài thiếu → homepage blog grid bị lệch 1 card không hình.
**Fix:** Nếu original không có ảnh cho 1 bài → scrape ảnh từ bài content (`curl page | grep img src`) hoặc dùng hero image làm fallback. KHÔNG để post nào thiếu featured image.

### Mobile nav KHÔNG duplicate text khi có logo image
**Bài học autotaybac:** Mobile nav always rendered `<span class="logo-text">Site Name</span>` bên cạnh `<img>` logo → hiện "Auto TÂY BẮC Auto Tây Bắc" duplicate.
**Fix:** Dùng `if/else` — chỉ show text span khi KHÔNG có logo image:
```php
<?php if ($logo): ?>
  <img src="..." alt="...">
<?php else: ?>
  <span class="logo-text"><?php echo $site_name; ?></span>
<?php endif; ?>
```

## 🟡 MAJOR

### WP page template assignment — PHẢI dùng `template_for_type()` 
**Bài học dieu-an:** Header.php gọi `vbrand_getOrCreatePageByTemplate('about')` nhưng page meta có `_wp_page_template = 'page-aboutus.php'` → không match → page trống.
**Fix:** PHẢI có function `{theme}_template_for_type()` trong functions.php:
```php
function dieuan_template_for_type($type) {
    $map = [
        'homepage' => 'page-homepage.php',
        'about'    => 'page-aboutus.php',
        'contact'  => 'page-contact.php',
        'shop'     => 'page-homepage.php',
    ];
    return $map[$type] ?? 'page.php';
}
```
Header.php PHẢI gọi qua mapping: `$tpl = dieuan_template_for_type($menuType);`

### WooCommerce shop page ID conflict với front page
**Bài học dieu-an:** Auto-setup function set `woocommerce_shop_page_id` = same page as `page_on_front` → WC hijack homepage thành shop page.
**Fix:** PHẢI verify `page_on_front != woocommerce_shop_page_id` sau auto-setup. Tạo 2 pages riêng: "Trang Chủ" (homepage template) + "Sản Phẩm" (shop, page.php template).

### `Template Name:` header BẮT BUỘC trong mỗi page template
```php
<?php
/**
 * Template Name: Homepage
 */
```
Thiếu → WP không nhận template → page dùng page.php generic.

### Product images từ site cũ — download + fallback strategy
**Bài học dieu-an:** Nhiều image URLs từ site cũ bị 404 (Vietnamese characters in URL, old server down).
**Strategy:**
1. Download trực tiếp từ old site
2. Fallback: Wayback Machine (`web.archive.org/web/*/URL`)
3. Remaining: dùng hero image làm placeholder
4. Set `menu_order = 0` cho products có image, `100` cho products không có → products có ảnh hiện trước
```bash
# Sort products: with image first
wp eval 'foreach(wc_get_products(["limit"=>-1]) as $p) { 
    $p->set_menu_order($p->get_image_id() ? 0 : 100); 
    $p->save(); 
}'
```

### Import products — KHÔNG chạy import 2 lần liên tiếp
**Bài học dieu-an:** `import-to-woocommerce.js` KHÔNG update existing — luôn tạo mới → chạy 2 lần = 2x products.
**Fix:** Luôn dùng `--clean` nếu muốn reimport. Sequence đúng:
```bash
# Round 1: clean + with images (some will fail)
node import-to-woocommerce.js <url> <dir> --clean
# KHÔNG chạy round 2 — sẽ duplicate
# Thay vào đó: dùng WP CLI script để import images cho products đã tạo
```

### Content fallbacks trong PHP PHẢI match industry
**Bài học dieu-an:** Agent viết fallback text về "thực phẩm dinh dưỡng" (food/nutrition) cho công ty van công nghiệp (industrial valves). Trust bar có "500+ sản phẩm" cho company chỉ có 40 sản phẩm.
**Fix:** PHẢI cung cấp industry-specific content trong prompt. Check ALL fallback text:
```bash
grep -n "default.*'" page-homepage.php | head -20  # review every default
```

### Mobile shop grid phải explicit 1-col tại 640px
### WC block pages (cart, checkout) — detect trong page.php
### Quantity input cần custom +/− buttons

## 🟠 MINOR

### Puppeteer NODE_PATH khi chạy từ /tmp
```bash
cd /tmp && npm install puppeteer
NODE_PATH=/tmp/node_modules node script.js
```

### gpt-image-1 thay vì dall-e-3
**Bài học dieu-an:** DALL-E 3 deprecated, dùng `gpt-image-1` model. API khác:
```javascript
const response = await client.images.generate({
    model: "gpt-image-1",
    prompt: "...",
    size: "1536x1024",  // NOT 1792x1024
    quality: "high",     // NOT "hd"
});
// Response: response.data[0].b64_json (NOT .url)
const buffer = Buffer.from(response.data[0].b64_json, 'base64');
fs.writeFileSync(outputPath, buffer);
```
Cost: ~$0.05-0.19 per image. Budget ~$1 total.

### OpenAI billing limits
**Bài học dieu-an:** Free tier hoặc low balance → chỉ gen được 2-3 images trước khi bị rate limited.
**Fix:** Check balance trước. Nếu limited → gen hero-1 + about-2 first (2 ảnh quan trọng nhất). Còn lại dùng fallback.

### Logo từ site cũ — LUÔN download + dùng trên header trắng
**Bài học dieu-an:** Header mặc định dùng SVG placeholder → user muốn logo gốc.
**Fix:** 
1. Phase 1: download logo: `curl -sL -o assets/images/logo.png "<old-site>/images/logo.png"`
2. header.php: `$logo = $g('logo', get_template_directory_uri() . '/assets/images/logo.png');`

### Floating Action Buttons (Zalo/Phone/Map) — STANDARD cho mọi vBrand site
**Bài học dieu-an:** User yêu cầu thêm FABs nhấp nháy bên phải.
**Implementation:** Thêm vào footer.php trước `wp_footer()`:
- Phone button (orange, pulse animation)
- Zalo button (blue, pulse animation, link: `https://zalo.me/<phone>`)
- Map button (green, pulse animation, link: Google Maps)
- CSS inline trong footer (không cần thêm vào style.css)
- Hover expand hiện label
- Mobile responsive (smaller at 768px)
Schema-driven: `$phone`, `$g('zalo', $phone)`, `$g('map_url', 'https://maps.google.com/?q=...')`

### Partner section — dùng real brand names, KHÔNG "Partner 1 Partner 2"
**Bài học dieu-an:** Placeholder text "Partner 1-6" hiện trên prod → xấu.
**Fix:** Research actual brands/manufacturers that the company distributes. Dùng styled initials badge khi chưa có logo.

### vbrandsync storage permissions trên production
**Bài học dieu-an:** Fresh rsync → storage/logs + storage/framework/sessions permission denied → 500.
**Fix:** Sau rsync plugin, LUÔN chạy:
```bash
ssh vbrand@server "cd /path/wp-content/plugins/vbrandsync && \
    mkdir -p storage/logs storage/framework/sessions storage/framework/views storage/framework/cache && \
    chmod -R 775 storage bootstrap/cache"
```

### Copy audit cuối thành `final/`
### Products.json price=0 cho B2B sites (all "Liên Hệ")

---

# PHASE 0: Đọc latest theme + learn patterns

Trước khi bắt đầu:
1. Đọc `/site/wp-content/themes/autotaybac/` — latest theme, service business, nhiều page templates
2. Đọc `/site/wp-content/themes/autotaybac/functions.php` — `autotaybac_template_for_type()` + `autotaybac_activate()`
3. Đọc `/site/wp-content/themes/autotaybac/header.php` — menu resolution + mobile nav (no duplicate text)
4. Đọc `/site/wp-content/themes/autotaybac/page-homepage.php` — no double-nesting pattern
5. Đọc `bots/automated/create-theme-from-url.md` — full KNOWN PITFALLS + SCHEMA REFERENCE
6. Đọc `/site/wp-content/themes/dieu-an/` — previous theme, product shop, reference cho WC integration

---

# PHASE 1: Scrape Old Site → `<theme>/original/`

## 1.1 Screenshot ALL pages

Dùng Puppeteer headless browser:
1. Chụp TỪNG trang (full page scroll) — desktop 1440px + mobile 375px
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

**QUAN TRỌNG:** `products.json` từ scrape thường rất bẩn (navigation elements, broken prices). PHẢI tạo `products-clean.json` riêng với:
- Loại bỏ non-product entries (Google Maps errors, nav links)
- Normalize prices (VND string → integer, hoặc 0 cho "Liên Hệ")
- Map image URLs đúng
- Vietnamese diacritics đầy đủ trong product names
- Copy `products-clean.json → products.json` trước khi import

## 1.3 Download ALL images

```
original/images/
├── logo.png           # Company logo — LUÔN download
└── site-*.jpg         # All site images (products + banners)
```

**Logo detection:**
```bash
# Common paths to try:
curl -sL -o logo.png "<site>/images/logo.png"
curl -sL -o logo.png "<site>/img/logo.png"  
curl -sL -o logo.png "<site>/assets/images/logo.png"
# Hoặc extract từ <img> tag trong header
```

## 1.4 Write analysis.md

Nội dung:
- Content inventory (tất cả text blocks, organized by page)
- Image inventory (tất cả images với quality notes: kích thước, chất lượng)
- Missing content (những gì site hiện đại cần mà site cũ thiếu)
- Product data quality assessment
- Menu/navigation structure
- Company info: tên, MST, phone, email, address, social links
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
| Sắt mỹ thuật | Warm orange + dark charcoal |

## 2.2 Content Writing (`plan/content.md`)

Viết TOÀN BỘ marketing copy dựa trên info scrape được. **BẮT BUỘC tiếng Việt đầy đủ dấu.**

Sections:
- Hero slide taglines (3 slides, mỗi slide: badge + title + subtitle + 2 buttons)
- Trust bar stats (4 items: con số thực tế từ analysis)
- Giới thiệu công ty (~300 words, mở rộng từ info hiện có)
- Mô tả TỪNG danh mục sản phẩm (2-3 câu professional)
- "Tại sao chọn [Company]" (4-5 USPs)
- FAQ (5-6 câu hỏi phổ biến, chia theo category: Sản Phẩm + Đặt Hàng)
- Footer description
- CTA texts (phone number cụ thể trong button)
- Partner/brand list (tên thương hiệu thật mà company phân phối)
- About page: Câu chuyện + Cam kết + Tầm nhìn/Sứ mệnh + Stats + Cơ sở
- Contact: 3 cards (Hotline/Email/Địa chỉ) + Form + FAQ

**Nguyên tắc viết:**
- Dựa trên facts từ site cũ, KHÔNG bịa đặt
- Stats phải thực tế (đừng viết "500+ sản phẩm" khi chỉ có 40)
- Mở rộng, chuyên nghiệp hóa ngôn ngữ
- Tiếng Việt chuẩn, không lỗi chính tả, đầy đủ dấu
- Tone: chuyên gia, đáng tin cậy, không quá bán hàng
- Phone/email/address phải chính xác từ site cũ

## 2.3 Image Plan (`plan/image-plan.md`)

Danh sách images cần gen qua gpt-image-1:
| Image | Size | Prompt | Placement |
|-------|------|--------|-----------|
| hero-1 | 1536x1024 | "..." | Hero slide 1 |
| about-2 | 1024x1024 | "..." | About page story |

**Rules:**
- Dùng `gpt-image-1` (KHÔNG phải dall-e-3)
- KHÔNG gen product images — giữ nguyên từ site cũ
- Luôn thêm "no text, no logos, no words, no letters, photorealistic" vào prompt
- Size: 1536x1024 (landscape hero) hoặc 1024x1024 (square)
- Max ~6-8 images (~$0.50-1.50 total)
- Priority order: hero-1 > about-2 > hero-3 > rest (nếu bị billing limit)

## 2.4 Sitemap (`plan/sitemap.md`)

Page structure + module mapping:
```
Homepage (page-homepage.php, Template Name: Homepage)
├── Hero slider (3 slides)
├── Trust bar (4 stats)
├── Featured categories (6 cards)
├── Product carousel (8 products, sorted by popularity)
├── About preview (text + image)
├── CTA banner (with phone number)
├── Blog section (latest 3 posts)
├── Partners/Brands strip (real brand names)
└── Newsletter CTA

About (page-aboutus.php, Template Name: About Us)
├── Page hero (breadcrumb)
├── Company story (2 sections with images)
├── Vision/Mission (2 cards)
├── Stats (4 numbers)
├── Facilities (address cards)
└── CTA banner

Contact (page-contact.php, Template Name: Contact)
├── Page hero (breadcrumb)
├── 3 Contact cards (phone, email, address)
├── Contact form (name, email, phone, subject, message)
└── FAQ accordion (grouped by category)

Shop → WooCommerce archive-product.php
├── Page hero (breadcrumb)
├── Category pills (horizontal scroll)
├── Sort dropdown + count
├── Product grid (3-col desktop, 1-col mobile)
└── Pagination

Product → WooCommerce single-product.php
Cart → WC block page
Checkout → WC block page
```

---

# PHASE 2.5: Generate gpt-image-1 Images (Optional)

Script: `plan/scripts/generate-images.js`

```javascript
const OpenAI = require('openai');
const fs = require('fs');
const path = require('path');

const client = new OpenAI({ apiKey: process.env.OPENAI_API_KEY });

async function generateImage(prompt, size, outputPath) {
    console.log(`Generating: ${path.basename(outputPath)}...`);
    const response = await client.images.generate({
        model: "gpt-image-1",
        prompt: prompt + " — no text, no logos, no words, no letters, photorealistic",
        n: 1,
        size: size,      // "1536x1024" or "1024x1024"
        quality: "high",  // NOT "hd"
    });
    // gpt-image-1 returns b64_json, NOT url
    const buffer = Buffer.from(response.data[0].b64_json, 'base64');
    fs.writeFileSync(outputPath, buffer);
    console.log(`Saved: ${outputPath} (${(buffer.length/1024).toFixed(0)} KB)`);
}
```

Chạy:
```bash
OPENAI_API_KEY=sk-... node plan/scripts/generate-images.js
# Nếu billing limit → chạy lại với --only flag cho từng image
```

---

# PHASE 3: Build Theme

### Theme structure
```
<theme-name>/
├── style.css                  # ALL CSS, prefix từ design-plan
├── screenshot.png             # 1200x900
├── functions.php              # ~110 lines, PHẢI có template_for_type + activate
├── schema.php                 # ~900+ lines, TOÀN BỘ text từ plan/content.md, CÓ DẤU
├── header.php                 # Topbar + sticky nav + mobile hamburger + logo
├── footer.php                 # Multi-column + JS + Floating Action Buttons
├── index.php, page.php, single.php
├── page-homepage.php          # Template Name: Homepage
├── page-aboutus.php           # Template Name: About Us
├── page-contact.php           # Template Name: Contact
├── woocommerce/
│   ├── archive-product.php    # Custom grid with wc_get_products()
│   ├── single-product.php
│   ├── content-single-product.php
│   └── global/quantity-input.php
├── assets/images/             # Logo + DALL-E images
│   ├── logo.png               # FROM OLD SITE
│   └── hero/, about/          # Generated images
├── original/                  # Phase 1 output
├── plan/                      # Phase 2 output
└── design/                    # Phase 4/5 audit
    ├── scripts/screenshot-all.js
    ├── versions/audit_N/
    ├── versions/final/
    └── HISTORY.md
```

### Key patterns:

**functions.php — REQUIRED sections:**
```php
// 1. Theme support (title-tag, woocommerce, post-thumbnails, nav-menu)
// 2. Enqueue styles + Google Fonts
// 3. Widget areas
// 4. template_for_type() mapping ← CRITICAL
// 5. Auto-setup pages (run once on first load) ← CRITICAL
// 6. WooCommerce hooks (loop_shop_per_page, columns, remove wrappers)
// 7. Contact form handler
```

**header.php — menu resolution pattern:**
```php
$tpl = function_exists('{theme}_template_for_type') 
    ? {theme}_template_for_type($menuType) 
    : $menuType;
$page = vbrand_getOrCreatePageByTemplate($tpl, $menu['title']);
```

**footer.php — MUST include:**
1. 4-column footer (about, products, info, contact)
2. Hero slider JS
3. Quantity stepper JS
4. FAQ accordion JS
5. Promo dismiss JS
6. **Floating Action Buttons** (Phone/Zalo/Map) with pulse animation

**style.css — CRITICAL rules:**
```css
.{prefix}-product-card__image {
    display: block;       /* ← CRITICAL: prevents text clipping */
    aspect-ratio: 1;
    overflow: hidden;
}
```

**schema.php guidelines:**
- EVERY default text MUST have Vietnamese diacritics
- PROVIDE exact text in agent prompt, don't let agent guess
- After build, verify: `grep -c "[àáảãạ]" schema.php` must be > 0

### CSS ↔ PHP class audit (RUN AFTER BUILD):
```bash
cd /path/to/theme
grep -oE '{prefix}-[a-zA-Z0-9_-]+' *.php woocommerce/*.php | awk -F: '{print $2}' | sort -u > /tmp/php.txt
grep -oE '\.{prefix}-[a-zA-Z0-9_-]+' style.css | sed 's/^\.//' | sort -u > /tmp/css.txt
echo "=== PHP classes NOT in CSS ===" && comm -23 /tmp/php.txt /tmp/css.txt
```
If ANY mismatch → fix PHP to match CSS names.

---

# PHASE 4: Import Products

## 4.1 Clean import data

```bash
# Verify products.json is clean
python3 -c "import json; d=json.load(open('products.json')); print(f'Products: {len(d)}'); print(d[0])"
```

Check for:
- Non-product entries (Google Maps, navigation)
- Garbage prices ("45Đ" from unrelated elements)
- Missing Vietnamese diacritics in names
- Image URLs — test a few: `curl -sL -o /dev/null -w "%{http_code}" "<url>"`

## 4.2 Import to local WooCommerce

```bash
# Activate theme first
cd /path/site && wp theme activate <theme-name>

# Import with images (some may fail — that's OK)
node bots/scrape/scripts/import-to-woocommerce.js http://brand-site.test <theme>/original/ --clean

# NEVER run import again without --clean (duplicates!)
```

## 4.3 Import remaining images via WP CLI

For products where image URL failed during import:
```php
// WP eval-file script: download + attach images
$products = wc_get_products(['limit' => -1]);
foreach ($products as $p) {
    if ($p->get_image_id()) continue; // already has image
    // Try download from old site URL...
    // Fallback: Wayback Machine
}
```

## 4.4 Sort products (images first)

```bash
wp eval 'foreach(wc_get_products(["limit"=>-1]) as $p) { 
    $p->set_menu_order($p->get_image_id() ? 0 : 100); 
    $p->save(); 
}'
```

---

# PHASE 5: Audit Loop

Screenshot script → `design/versions/audit_N/` → checklist → fix → repeat.

### Screenshot script
Located at `design/scripts/screenshot-all.js`. Takes desktop + mobile screenshots of:
- Homepage, About, Contact, Shop, Cart, Checkout
- Plus alt URLs (fallback paths like `/?post_type=product`)

```bash
cd /tmp && npm install puppeteer  # one-time
NODE_PATH=/tmp/node_modules WP_URL="http://brand-site.test" node <theme>/design/scripts/screenshot-all.js
```

### Full Audit Checklist (25 items):

| # | Area | Check |
|---|------|-------|
| 1 | **Homepage hero** | Renders, correct text (industry-specific), image loads |
| 2 | **Trust bar** | 4 stats, realistic numbers |
| 3 | **Category cards** | All show, correct titles + subtitles |
| 4 | **Product carousel** | Products with images show, names visible, buttons work |
| 5 | **About preview** | Correct company description |
| 6 | **CTA banner** | Phone number visible, correct text |
| 7 | **Partners** | Real brand names, not "Partner 1" |
| 8 | **Newsletter** | Form renders, button styled |
| 9 | **Footer** | 4 columns, all links, correct contact info |
| 10 | **Floating buttons** | Phone/Zalo/Map visible, pulse animation |
| 11 | **Header** | Logo from old site, nav links work, sticky on scroll |
| 12 | **About page** | Full content: story, vision/mission, stats, facilities |
| 13 | **Contact page** | 3 cards, form, FAQ accordion |
| 14 | **Shop page** | Product grid 3-col, names + prices visible, category pills |
| 15 | **Product detail** | Image, title, description, add to cart |
| 16 | **Cart** | WC blocks styled with theme colors |
| 17 | **Mobile nav** | Hamburger opens, links work, close works |
| 18 | **Mobile layout** | All sections stack properly, no overflow |
| 19 | **Vietnamese text** | All diacritics present, no mojibake, no ASCII-only |
| 20 | **Product images** | Real images from old site (not all hero fallback) |
| 21 | **Menu links** | All 4 menu items navigate to correct pages |
| 22 | **Page templates** | Each page uses correct Template Name |
| 23 | **Front page** | Shows homepage template, NOT shop |
| 24 | **Product text** | Product names, category, buttons visible (not clipped) |
| 25 | **Promo bar** | Orange/accent topbar with CTA, dismissable |

---

# PHASE 6: Deploy to Production (Optional)

Nếu user cung cấp domain (vd `dieuan.b-teka.com`):

## 6.1 Pre-flight
```bash
dig +short <domain>  # Must be 18.141.199.175
ssh vbrand@18.141.199.175 "ls /home/vbrand/sites/<dir_name>"  # Must not exist
```

## 6.2 Create site (follow `bots/vbrand_new_prod_site.md`)
Steps 1-16: MySQL → WP → WC → rsync → nginx → SSL → brand app → verify

## 6.3 Post-deploy REQUIRED steps
```bash
# 1. Fix vbrandsync permissions
ssh vbrand@server "cd /path/plugins/vbrandsync && \
    mkdir -p storage/logs storage/framework/{sessions,views,cache} && \
    chmod -R 775 storage bootstrap/cache"

# 2. Fix page templates (auto-setup often gets this wrong)
ssh vbrand@server "cd /path && \
    wp post meta update <homepage_id> _wp_page_template page-homepage.php && \
    wp post meta update <shop_id> _wp_page_template page.php && \
    wp option update page_on_front <homepage_id> && \
    wp option update woocommerce_shop_page_id <shop_id>"

# 3. Import products
node import-to-woocommerce.js https://<domain> <theme>/original/ --clean --skip-images
# Then import images via WP CLI script (see Phase 4.3)

# 4. Sort products (images first)
ssh vbrand@server "cd /path && wp eval '...menu_order...'"

# 5. Enforce COD + vBrand Express
scp enforce-cod-vbrand-express.php vbrand@server:/tmp/
ssh vbrand@server "wp --path=/path eval-file /tmp/enforce-cod-vbrand-express.php"

# 6. Verify
curl -sL -o /dev/null -w "%{http_code}" https://<domain>/  # 200
```

## 6.4 Update registry
Add entry to `bots/report/sites.md`

---

# PHASE 7: Finalize

1. `cp -r design/versions/audit_N design/versions/final`
2. Write `design/HISTORY.md` (design system, pages, build timeline, known issues)
3. Git commit theme:
   ```bash
   cd /Users/luan/apps/vbrand/site/wp-content/themes
   git add <theme-name>/
   git commit -m "feat: add <theme-name> theme — upgraded from <old-url>"
   ```
4. **Cập nhật bot này** (`upgrade-site.md`) với lessons learned
5. Update `Latest theme:` at top of this file

---

# SCHEMA REFERENCE

Xem `create-theme-from-url.md` — SCHEMA REFERENCE section. Áp dụng y hệt.

**Required sessions:** general, menu, home, about-us, contact
**Required options:** site_name, logo, favicon, phone, phone_2, email, address, address_warehouse, menus, hero_slides, trust_stats, sport_categories, products_section, about_preview, cta, partners, newsletter, about_hero, about_sections, about_vision, about_mission, about_stats, contact_title, contact_form_title, faq, footer columns, copyright_links, promo, topbar_links, map_url, zalo

---

# ERROR HANDLING

- Old site không truy cập → báo user, abort
- Old site encoding lạ → detect + convert UTF-8
- Product images quá nhỏ → contain + padding + ghi note
- gpt-image-1 API fail → gradient placeholder fallback
- gpt-image-1 billing limit → gen top 2 priority images only
- Products import fail → retry without images, then WP CLI for images
- Theme đã tồn tại → hỏi confirm overwrite
- CSS ↔ PHP class mismatch → fix PHP to match CSS
- Front page shows shop → check woocommerce_shop_page_id != page_on_front
- Page content empty → check _wp_page_template meta + Template Name header
- Product text invisible → check display:block on image container
- vbrandsync 500 → check storage/logs permissions

---

# WORKFLOW SUMMARY

```
┌──────────────────────────────────────────────────────────────┐
│  PHASE 0: Read latest theme (dieu-an) + learn patterns       │
│  PHASE 1: Scrape old site → original/                        │
│  PHASE 2: Design plan + Content → plan/ ← USER REVIEW       │
│  PHASE 2.5: gpt-image-1 images (optional, ~$1)              │
│  PHASE 3: Build theme + CSS↔PHP class audit                  │
│  PHASE 4: Import products + images + sort                    │
│  PHASE 5: Audit loop (screenshot → checklist → fix → repeat) │
│  PHASE 6: Deploy to production (optional)                    │
│  PHASE 7: Finalize + commit + update this bot                │
└──────────────────────────────────────────────────────────────┘
```

# BUILD LOG

## dieu-an (2026-04-15) — vancongnghiepdieuan.com.vn
- **Company:** Công ty TNHH TM DV XNK Diệu An — Van Công Nghiệp
- **Palette:** Blue #1B4D89 + Orange #E8792B, prefix `da-`
- **Products:** 67 products, 14 categories, 24 with real images
- **gpt-image-1:** 3/8 images generated (billing limit)
- **Audit rounds:** 7 (CSS mapping, Vietnamese diacritics, product text visibility, content fix, partners, FABs)
- **Production:** dieuan.b-teka.com (SSL expires 2026-07-14)
- **Key discoveries:** CSS↔PHP class mismatch, display:block on card image, template_for_type mapping, WC shop page conflict, product ordering by image, FABs standard
- **Duration:** ~4 hours total (scrape → build → audit → deploy)

## cafedanhphat (2026-04-28) — cafedanhphat.vn → cafedanhphat.b-teka.com
- **Company:** Công ty TNHH Danh An Phát Đạt — Cà phê phân phối Đà Nẵng / miền Trung
- **Approach:** Copy + customize dreamcafe theme (KHÔNG build from scratch)
- **Palette:** giữ dreamcafe warm coffee/cream + accent đỏ #c8201f, prefix `cdp-`
- **Products:** 12 sản phẩm (8 từ site cũ + 4 chế thêm dòng hạt/hòa tan), 4 categories, all 12 with images
- **Blog:** 6 bài (từ 14 bài site cũ — chọn bài có thumb + content chất lượng)
- **gpt-image-1:** SKIPPED — dùng real coffee shop photos từ original/images/site/
- **Audit rounds:** 3 (audit_1: English text in PHP fallbacks → audit_2: fixed + FABs → final: blog imported)
- **Production:** cafedanhphat.b-teka.com (SSL expires 2026-07-27)
- **Key discoveries:** Copy theme + customize là 3-4x nhanh hơn build from scratch. Audit English placeholder trong PHP templates riêng biệt với schema defaults.
- **Duration:** ~2.5 hours total (scrape → copy → schema → deploy → import → audit → fix)
