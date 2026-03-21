# Bot: Create & Review Theme

Tạo WordPress + WooCommerce theme cho vBrand platform từ 1 URL tham khảo. Hoặc review/fix theme hiện tại.

## Usage

```bash
# Tạo theme mới từ URL
bots/automated/create-theme-from-url.md <theme-name> <url>
bots/automated/create-theme-from-url.md nikezero https://www.nike.com/vn/

# Review theme hiện tại (chụp hình, audit, fix)
bots/automated/create-theme-from-url.md review <theme-name>

# Fix issue cụ thể trong theme
bots/automated/create-theme-from-url.md fix <theme-name> <mô tả issue>
```

## Output

- Theme hoàn chỉnh tại `/site/wp-content/themes/<theme-name>/`
- Design folder với screenshots + audit history tại `<theme>/design/`
- Schema-driven: MỌI text, image, section đều configurable
- Git commit

---

# SELF-IMPROVE RULE — ĐỌC TRƯỚC KHI LÀM GÌ

**Sau mỗi lần build theme, bot PHẢI tự cập nhật file này với:**
1. Mọi lỗi mới gặp → thêm vào `KNOWN PITFALLS` bên dưới
2. Pattern mới hiệu quả → thêm vào section phù hợp
3. Checklist mới → thêm vào Audit Checklist

**Khi làm theme MỚI:**
- Reference latest theme (hiện tại: `dreamcafe`) để học hỏi patterns
- KHÔNG clone/copy code từ theme cũ — build from scratch chuẩn WP + WooCommerce
- Đọc latest theme's `design/HISTORY.md` để tránh lặp lỗi cũ

**Latest theme:** `dreamcafe` (built 2026-03-21, coffee shop, mauve palette)
→ Xem `/site/wp-content/themes/dreamcafe/` để tham khảo patterns

---

# KNOWN PITFALLS — LỖI ĐÃ GẶP, ĐỪNG LẶP LẠI

## 🔴 CRITICAL: woocommerce.php trong theme root phá hỏng WC template routing

**Triệu chứng:** `woocommerce/archive-product.php` không bao giờ được load, dù file tồn tại và `locate_template()` trả về đúng path. Shop page vẫn render WC default (`<ul class="products columns-3">` rỗng).

**Root cause:** Nếu có file `woocommerce.php` trong theme root, WC ưu tiên nó cho MỌI WC page trước khi check subfolder `woocommerce/`. File này gọi `woocommerce_content()` → render WC default loop thay vì template của mình.

**Fix:** **ĐỪNG tạo `woocommerce.php` trong theme root.** Không cần thiết. Với `add_theme_support('woocommerce')`, WC tự route đúng:
- `woocommerce/archive-product.php` → shop archive
- `woocommerce/single-product.php` → single product
- `page.php` → cart, checkout, account (WC block pages)

**File structure ĐÚNG:** Không có `woocommerce.php` ở root. Chỉ có thư mục `woocommerce/`.

---

## 🔴 CRITICAL: WC 10.6.1+ `woocommerce_product_loop()` luôn trả về true

**Triệu chứng:** `woocommerce_product_loop()` trả về `true` dù không có sản phẩm nào → `woocommerce_product_loop_start()` render `<ul class="products">` rỗng.

**Root cause:** Trong WC 10.6.1+:
```php
function woocommerce_product_loop() {
    return have_posts() || 'products' !== woocommerce_get_loop_display_mode();
}
```
Nếu display mode là `'subcategories'` hoặc `'both'`, function trả về `true` kể cả khi `have_posts()` = false.

**Fix:** Không dùng WC loop trong `archive-product.php`. Dùng `wc_get_products()` trực tiếp:
```php
$products = wc_get_products(['status' => 'publish', 'limit' => $per_page, 'page' => $paged, ...]);
foreach ($products as $product): // render card inline
```
Xem `dreamcafe/woocommerce/archive-product.php` để tham khảo full implementation.

---

## 🟡 MAJOR: content-product.php dùng `<div>` thay vì `<li>`

**Triệu chứng:** Product cards không hiển thị đúng trong grid, layout bị vỡ.

**Root cause:** WC product loop wrap cards trong `<ul class="products">`. HTML chuẩn yêu cầu `<ul>` chỉ chứa `<li>`. Nếu dùng `<div>`, browser tự sửa DOM → layout CSS sai.

**Fix:** `content-product.php` phải dùng `<li>` làm root element:
```php
<li <?php post_class('dc-wc-product-card'); ?>>
    ...
</li>
```

**Note:** Nếu dùng `wc_get_products()` trực tiếp trong `archive-product.php` (như pitfall trên), thì không cần lo việc này — render inline dùng `<div>` bình thường.

---

## 🟡 MAJOR: Mobile shop grid vẫn 2-col dù đã có responsive CSS

**Triệu chứng:** Shop grid ở 375px vẫn hiện 2 cột, nhưng CSS đã có breakpoint 640px.

**Root cause:** Rule `grid-template-columns: repeat(2, 1fr)` được set tại `960px` breakpoint, nhưng `640px` breakpoint không explicitly override nó về `1fr`. CSS cascade giữ giá trị từ `960px`.

**Fix:** Tại `640px` breakpoint, PHẢI explicitly set shop grid về 1 cột:
```css
@media (max-width: 640px) {
    .dc-shop-product-grid { grid-template-columns: 1fr !important; }
}
```

---

## 🟡 MAJOR: WC blockified templates bypass PHP template hierarchy

**Triệu chứng:** WC 9.0+ có "blockified" templates. Một số trang (cart, checkout, account) được render bởi Gutenberg blocks, không phải PHP templates.

**Fix:** KHÔNG cố tạo PHP templates cho cart/checkout/account. Thay vào đó:
- `page.php` detect và render full-width container cho WC block pages:
```php
$is_wc_block_page = false;
if (function_exists('is_cart') && is_cart()) $is_wc_block_page = true;
if (function_exists('is_checkout') && is_checkout()) $is_wc_block_page = true;
if (function_exists('is_account_page') && is_account_page()) $is_wc_block_page = true;
```
- Style WC block components trong CSS: `.wc-block-cart`, `.wc-block-checkout`, `.wp-block-woocommerce-*`

---

## 🟠 MINOR: Puppeteer NODE_PATH khi chạy từ /tmp

**Triệu chứng:** `require('puppeteer')` fail nếu không chỉ định `NODE_PATH`.

**Fix:**
```bash
cd /tmp && NODE_PATH=/tmp/node_modules WP_URL="http://brand-site.test" node <script> <outdir>
```

---

## 🟡 MAJOR: Single product — quantity input không có nút +/−, Add to Cart không cùng hàng

**Triệu chứng:** Quantity chỉ là input số thuần, không có nút tăng/giảm. Add to Cart nằm trên dòng riêng trông rời rạc.

**Fix — 3 bước:**

**1. Override `woocommerce/global/quantity-input.php`** — thêm nút +/− xung quanh input:
```php
<div class="quantity dc-qty">
    <button type="button" class="dc-qty__btn dc-qty__btn--minus" aria-label="...">
        <!-- SVG minus icon -->
    </button>
    <input type="number" ... />
    <button type="button" class="dc-qty__btn dc-qty__btn--plus" aria-label="...">
        <!-- SVG plus icon -->
    </button>
</div>
```

**2. CSS** — stepper dạng pill (border bao ngoài, nút hai đầu), add-to-cart form flex row:
```css
/* Form add-to-cart: quantity + button cùng hàng */
.dc-single-product__summary .cart {
    display: flex !important;
    align-items: center !important;
    gap: 12px !important;
    flex-wrap: wrap;
}

/* Quantity stepper */
.dc-qty {
    display: flex !important;
    align-items: center !important;
    border: 1px solid var(--dc-border) !important;
    border-radius: var(--dc-radius) !important;
    overflow: hidden;
    height: 48px;
}
.dc-qty__btn { /* hover → background primary */ }
.dc-qty input[type="number"] {
    border: none !important;
    border-left: 1px solid var(--dc-border) !important;
    border-right: 1px solid var(--dc-border) !important;
    border-radius: 0 !important;
    -moz-appearance: textfield !important;
}
.dc-qty input::-webkit-outer-spin-button,
.dc-qty input::-webkit-inner-spin-button { -webkit-appearance: none; }
```

**3. JS trong footer.php** (trước `wp_footer()`) — xử lý click +/−:
```js
document.addEventListener('click', function(e) {
    var btn = e.target.closest('.dc-qty__btn');
    if (!btn) return;
    var input = btn.closest('.dc-qty').querySelector('input[type="number"]');
    var val = parseInt(input.value, 10) || 1;
    if (btn.classList.contains('dc-qty__btn--minus')) {
        if (!isNaN(parseInt(input.min)) && val - 1 < parseInt(input.min)) return;
        input.value = val - 1;
    } else {
        input.value = val + 1;
    }
    input.dispatchEvent(new Event('change', { bubbles: true }));
});
```

Xem `dreamcafe/woocommerce/global/quantity-input.php` + `dreamcafe/footer.php` để tham khảo full code.

---

## 🟠 MINOR: Quên copy audit cuối thành `final/`

**Triệu chứng:** Không có thư mục `design/versions/final/` — khó biết bản nào là chính thức.

**Fix:** Sau audit round cuối, LUÔN copy:
```bash
cp -r design/versions/audit_N design/versions/final
```

---

# MODE 1: Tạo Theme Mới

## PHASE 0: Đọc latest theme để học hỏi

Trước khi build, đọc:
- `/site/wp-content/themes/dreamcafe/design/HISTORY.md` — design decisions, known issues
- `/site/wp-content/themes/dreamcafe/functions.php` — WC hooks pattern
- `/site/wp-content/themes/dreamcafe/woocommerce/archive-product.php` — shop template pattern

**Mục tiêu:** Học patterns, tránh pitfalls. KHÔNG copy code — viết mới từ đầu với style của theme mới.

## PHASE 1: Research — Chụp & Phân tích website gốc

### 1.1 Chụp screenshots website gốc

```
Dùng Puppeteer headless browser:
1. Chụp trang chủ (full page + viewport 1440x900)
2. Tìm navigation links (about, contact, products, help...)
3. Chụp MỌI trang quan trọng
4. Lưu vào: <theme>/design/original_site_screenshots/
```

### 1.2 Download images từ website

```
Download images phù hợp:
- Hero/banner images → assets/images/hero/
- Featured section images → assets/images/featured/
- Category images → assets/images/categories/
- About page images → assets/images/about/
- Menu/product images → assets/images/menu/
- Contact images → assets/images/contact/
- Icons → assets/images/icons/

CHÚ Ý: Không download trademark logos. Dùng generic SVG thay thế.
```

### 1.3 Phân tích design

Từ screenshots gốc, note:
- Color palette → CSS custom properties
- Typography (font family, weights, sizes)
- Spacing system (padding, gap, margin patterns)
- Button styles (border-radius, padding, variants)
- Layout patterns (grid columns, container width)
- Special effects (transitions, hover, animations)

## PHASE 2: Build Theme

### 2.1 Theme structure

```
<theme-name>/
├── style.css                  # Theme header + ALL CSS
├── screenshot.png             # Theme preview (1200x900)
├── functions.php              # Theme support, WooCommerce, contact form
├── schema.php                 # Full schema config (TRUNG TÂM)
├── header.php                 # Top bar + sticky nav + search/cart + mobile menu
├── footer.php                 # Multi-column links + copyright + JS
├── index.php                  # Default template
├── page.php                   # Generic page (detect WC block pages!)
├── single.php                 # Single post
├── page-homepage.php          # Template Name: Homepage
├── page-aboutus.php           # Template Name: About Us
├── page-contact.php           # Template Name: Contact
│
│   ⚠️  KHÔNG có woocommerce.php ở root! (xem KNOWN PITFALLS)
│
├── woocommerce/
│   ├── archive-product.php    # Shop page — dùng wc_get_products() trực tiếp
│   ├── single-product.php     # Product detail wrapper
│   ├── content-product.php    # Product card in WC loop (dùng <li>!)
│   └── content-single-product.php  # Product detail layout
├── assets/images/             # Downloaded + fallback images
│   ├── hero/
│   ├── featured/
│   ├── categories/
│   ├── about/
│   ├── menu/
│   ├── contact/
│   └── icons/
└── design/                    # Design assets & audit history
    ├── HISTORY.md             # Full build history + design decisions
    ├── original_site_screenshots/
    ├── versions/
    │   ├── audit_1/           # screenshots
    │   ├── audit_2/
    │   └── final/             # Copy của audit tốt nhất
    └── scripts/
        └── screenshot-all.js  # Puppeteer screenshot script
```

### 2.2 Schema — TRUNG TÂM, KHÔNG CHỪA STATIC CONTENT

**Nguyên tắc:** Mọi text, image, boolean toggle, link hiển thị trên front-end PHẢI qua schema.

Required sessions & options: xem SCHEMA REFERENCE bên dưới.

### 2.3 CSS — Best Practices

```
- CSS custom properties cho colors, fonts, spacing
- BEM naming: .{prefix}-{block}__{element}--{modifier}
- Prefix 2 chữ từ theme name (dc- cho dreamcafe, nz- cho nikezero)
- Pure CSS, không framework
- Smooth transitions (0.2s-0.3s)
```

**BẮT BUỘC trong CSS:**

| Rule | Lý do |
|------|-------|
| `appearance: none` + custom SVG chevron cho ALL selects | Native dropdown inconsistent across OS |
| `transform: translateX(100%)` cho mobile nav | `right: -100%` gây bleed trên fullpage screenshot |
| `object-fit: contain` + padding cho product images | Products có white bg, `cover` crops |
| `!important` trên WC button overrides | WC blocks inject inline styles |
| `:root` override `--wp--preset--color--accent` | Prevent WC purple default |
| Hide webkit number spin: `-moz-appearance: textfield` | Native spinner ugly |
| `page.php` detect `is_cart()`/`is_checkout()` → full-width | Block cart/checkout cần wide container |
| Shop grid explicitly 1-col tại 640px breakpoint | Cascade từ 960px giữ 2-col nếu không override |

**Required CSS sections (27):**
1. Reset & base
2. Layout (container, gutter)
3. Top bar
4. Header/Nav (sticky)
5. Promo banner
6. Hero section
7. Buttons (primary, outline, white)
8. Section titles & navigation
9. About section
10. Popular menu / featured cards
11. Services cards
12. Product carousel (horizontal scroll)
13. Category carousel
14. Testimonials
15. Spotlight grid
16. Footer
17. Page hero (shop, about, contact banners)
18. Shop controls (filter pills, sort dropdown)
19. WooCommerce shop grid + product cards
20. WooCommerce single product
21. **WooCommerce Block Cart**
22. **WooCommerce Block Checkout**
23. **Global WC button overrides** (`.button.alt`, `.wp-element-button`, disabled)
24. Mobile nav + overlay
25. Responsive — 1200px
26. Responsive — 960px
27. Responsive — 640px (shop grid PHẢI về 1-col!)
28. WordPress Admin Bar offset

### 2.4 PHP Templates — Key Patterns

**Theme data loading (dùng ở MỌI template):**
```php
$td = function_exists('vbrand_load_theme_data') ? vbrand_load_theme_data() : null;
$g = function($key, $default = '') use ($td) { return $td ? $td->get($key, $default) : $default; };
```

**Image fallback:**
```php
$img = !empty($item['image']) ? $item['image'] : $theme_url . '/assets/images/hero/hero-1.jpg';
```

**Menu rendering:**
```php
foreach ($menus as $menu):
    if (empty($menu['show'])) continue;
    if ($menu['type'] === 'shop') {
        $link = wc_get_page_permalink('shop');
    } else {
        $page = vbrand_getOrCreatePageByTemplate($menu['type'], $menu['title']);
        $link = get_permalink($page->ID);
    }
endforeach;
```

**page.php MUST detect WC block pages:**
```php
$is_wc_block_page = false;
if (function_exists('is_cart') && is_cart()) $is_wc_block_page = true;
if (function_exists('is_checkout') && is_checkout()) $is_wc_block_page = true;
if (function_exists('is_account_page') && is_account_page()) $is_wc_block_page = true;
// → use full-width container for WC block pages
```

**archive-product.php — dùng wc_get_products() trực tiếp (không dùng WC loop):**
```php
$paged    = (int) (get_query_var('paged') ?: get_query_var('page') ?: 1);
$per_page = (int) apply_filters('loop_shop_per_page', 12);
$sort_param = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : get_option('woocommerce_default_catalog_orderby', 'menu_order');

$args = ['status' => 'publish', 'limit' => $per_page, 'page' => $paged, 'return' => 'objects'];
// apply sort to $args...
$products = wc_get_products($args);

// Pagination: count separately
$count_args = array_merge($args, ['limit' => -1, 'return' => 'ids']);
$total = count(wc_get_products($count_args));
```

**functions.php must include:**
- Theme support (title-tag, post-thumbnails, woocommerce, wc-product-gallery-*)
- Style enqueue
- Remove WC default wrapper/breadcrumb/sidebar actions
- Products per page + columns filters
- Contact form handler
- Auto page setup from menu schema
- **KHÔNG register `woocommerce.php` anywhere**

## PHASE 3: Pixel-Perfect Audit Loop

**Đây là phase QUAN TRỌNG NHẤT. Không skip.**

```
┌─────────────────────────────────────────────┐
│                 AUDIT LOOP                   │
│                                              │
│  1. Chụp screenshots (design/versions/       │
│     audit_N/)                                │
│  2. Xem TỪNG screenshot như designer PRO     │
│     KHÓ TÍNH                                │
│  3. Liệt kê MỌI issue → issues.md           │
│  4. Fix ALL issues                           │
│  5. Nếu còn issue → repeat (max 5 rounds)   │
│  6. Done → cp -r audit_N/ final/            │
│                                              │
│  Chất lượng output = chất lượng audit        │
└─────────────────────────────────────────────┘
```

### Audit Checklist — PHẢI check TẤT CẢ:

| # | Area | Check |
|---|------|-------|
| 1 | **All selects** | Custom chevron, no native dropdown arrow |
| 2 | **All buttons** | Styled (bg, color, border-radius, hover), no WC default purple |
| 3 | **Cart page** | Block cart styled: button, totals, product names, quantity |
| 4 | **Checkout page** | Block checkout: order summary, place order btn, shipping radio, inputs |
| 5 | **Product images** | `contain` for white-bg, consistent aspect ratio, hover effect |
| 6 | **Text overflow** | No text cut off, truncated cleanly with `...` where needed |
| 7 | **Spacing** | Consistent padding/margin, no cramped or oversized gaps |
| 8 | **Typography** | Font weight, size, line-height consistent per level |
| 9 | **Mobile nav** | Hidden on desktop, opens cleanly, no screenshot bleed |
| 10 | **Responsive** | Desktop 1440 + Mobile 375 both look good |
| 11 | **Color consistency** | Same colors throughout, no mismatched grays |
| 12 | **Hover states** | All links, buttons, cards have smooth transition |
| 13 | **Form inputs** | All inputs same height, border, radius, focus state |
| 14 | **Schema coverage** | NO static/hardcoded text on any page |
| 15 | **Image fallbacks** | All images fall back to theme assets gracefully |
| 16 | **WC notices** | Info/success/error messages styled |
| 17 | **Footer** | Multi-column, copyright, links all from schema |
| 18 | **Admin bar** | Sticky header offset for logged-in users |
| 19 | **Shop grid mobile** | 1-col at 375px, NOT 2-col (common regression!) |
| 20 | **Shop products loading** | Products actually rendering (not empty `<ul>`) |

### Screenshot Script

Script sống trong theme: `design/scripts/screenshot-all.js`

```bash
# Chạy từ /tmp (có puppeteer):
cd /tmp && NODE_PATH=/tmp/node_modules WP_URL="http://brand-site.test" node <theme-path>/design/scripts/screenshot-all.js <output-dir>

# Auto-increment audit number:
cd /tmp && NODE_PATH=/tmp/node_modules WP_URL="http://brand-site.test" node <theme-path>/design/scripts/screenshot-all.js
```

### Issues File Format

Mỗi audit round tạo `issues.md`:

```markdown
# Audit N — [Title]
## Date: YYYY-MM-DD
## Issues Found (X total)
### CRITICAL
1. [issue description]
### MAJOR
2. [issue description]
### MINOR
3. [issue description]
## Status: [FIXED → See audit_N+1 | ALL CLEAR]
```

## PHASE 4: Finalize

1. Copy audit cuối thành final: `cp -r design/versions/audit_N design/versions/final`
2. Generate `screenshot.png` (1200x900) cho WP admin
3. Update `design/HISTORY.md` với full build history
4. **Git commit — BẮT BUỘC, KHÔNG SKIP:**
   ```bash
   cd /Users/luan/apps/vbrand/site/wp-content/themes
   git add <theme-name>/
   git commit -m "feat: add <theme-name> theme — <style> inspired"
   ```
5. **Cập nhật bot này** (`bots/automated/create-theme-from-url.md`):
   - Thêm mọi lỗi mới vào KNOWN PITFALLS
   - Cập nhật "Latest theme" ở đầu file
   - Thêm pattern mới vào section phù hợp
   - Commit bot update: `cd /Users/luan/apps/vbrand && git add bots/ && git commit -m "docs: update create-theme bot — <theme-name> lessons"`

---

# MODE 2: Review Theme

```bash
bots/automated/create-theme-from-url.md review <theme-name>
```

1. Chạy screenshot script → `design/versions/audit_N/`
2. Xem screenshots, audit theo checklist (20 items trên)
3. Tạo `issues.md` với tất cả issues
4. Fix all issues
5. Repeat audit loop đến khi clean
6. `cp -r audit_N/ final/`
7. Update `design/HISTORY.md`
8. Commit

---

# MODE 3: Fix Issue

```bash
bots/automated/create-theme-from-url.md fix <theme-name> <mô tả>
```

1. Đọc mô tả issue
2. Fix trong CSS/PHP
3. Chụp screenshot verify
4. Lưu vào `design/versions/` (new audit round)
5. Commit

---

# SCHEMA REFERENCE

## Sessions
```php
'sessions' => [
    ['name' => 'general', 'title' => 'GENERAL'],
    ['name' => 'menu', 'title' => 'MENU'],
    ['name' => 'home', 'title' => 'HOME'],
    ['name' => 'about-us', 'title' => 'ABOUT US'],
    ['name' => 'contact', 'title' => 'CONTACT'],
],
```

## Required Options

**General:** site_name, logo, favicon, site_description, phone, email, address, topbar_links (list), promo_show, promo_text, promo_link_text, promo_link_url, footer_col_N_title x3, footer_col_N x3 (list), footer_locale, footer_copyright_links (list)

**Menu:** menus (list) [{show, title, type}]

**Home:** hero_slides (list), featured_show, featured_title, featured_cards (list), products_section_show, products_section_title, products_section_count, categories_show, categories_title, sport_categories (list), spotlight_show, spotlight_title, spotlight_subtitle

**About Us:** about_us_show, about_hero_image, about_hero_title, about_hero_subtitle, about_hero_btn_text, about_hero_btn_url, about_sections (list), about_stats_show, about_stats (list)

**Contact:** contact_title, contact_search_placeholder, faq_show, faq_title, faq_subtitle, faq_categories (list), contact_methods_title, contact_chat_label, contact_chat_hours, contact_chat_days, contact_phone_label, contact_phone_1, contact_phone_2, contact_store_label, contact_address, contact_email, contact_form_show, contact_form_title, contact_form_subjects (list)

## Field Types
text, textarea, image, boolean, number, select, list (has `max` + `schema` sub-fields)

---

# ERROR HANDLING

- URL không truy cập → báo user, abort
- Puppeteer chưa cài → `npm install puppeteer` tại /tmp
- Download hình fail → gradient/placeholder fallback
- Theme đã tồn tại → hỏi confirm overwrite
- Coming Soon mode → auto login trước khi chụp
- Shop products không hiển thị → kiểm tra ngay: có `woocommerce.php` ở root không? Xóa nó đi.
