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

# MODE 1: Tạo Theme Mới

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
- Category images → assets/images/sport/
- About page images → assets/images/hero/ (reuse)
- Contact/help images → assets/images/contact/
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
├── woocommerce.php            # WooCommerce wrapper (fallback)
├── woocommerce/
│   ├── archive-product.php    # Shop page
│   ├── single-product.php     # Product detail wrapper
│   ├── content-product.php    # Product card in loop
│   └── content-single-product.php  # Product detail layout
├── assets/images/             # Downloaded + fallback images
│   ├── hero/
│   ├── featured/
│   ├── sport/
│   ├── contact/
│   └── icons/
└── design/                    # Design assets & audit history
    ├── HISTORY.md             # Full build history
    ├── original_site_screenshots/
    ├── versions/
    │   ├── audit_1/           # screenshots/ + issues.md
    │   ├── audit_2/
    │   └── final/
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
- Prefix 2 chữ từ theme name (nz- cho nikezero, ap- cho adidas-prime)
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

**Required CSS sections (22):**
1. Reset & base
2. Layout (container, gutter)
3. Top bar
4. Header/Nav (sticky)
5. Promo banner
6. Hero section
7. Buttons (primary, outline, white)
8. Section titles & navigation
9. Featured cards
10. Product carousel
11. Category carousel
12. Spotlight grid
13. Footer
14. About page
15. Contact/Help page + form
16. WooCommerce shop (product grid, filter pills, sort, pagination)
17. WooCommerce single product (gallery + summary, tabs, variations)
18. **WooCommerce Block Cart** (items, totals, proceed button, coupon)
19. **WooCommerce Block Checkout** (inputs, order summary, place order, shipping, payment)
20. **Global WC button overrides** (`.button.alt`, `.wp-element-button`, disabled state)
21. Mobile nav overlay
22. Responsive breakpoints (640, 960, 1200)

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

**functions.php must include:**
- Theme support (title-tag, post-thumbnails, woocommerce, wc-product-gallery-*)
- Style enqueue
- Remove WC default wrapper/breadcrumb/sidebar
- Products per page filter
- Contact form handler
- Auto page setup from menu schema

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
│  6. Done → copy to design/versions/final/    │
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

### Screenshot Script

Script sống trong theme: `design/scripts/screenshot-all.js`

```bash
# Chạy từ thư mục có puppeteer (hoặc /tmp):
cd /tmp && node <theme-path>/design/scripts/screenshot-all.js <output-dir>

# Auto-increment audit number:
cd /tmp && node <theme-path>/design/scripts/screenshot-all.js
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

1. Generate `screenshot.png` (1200x900) cho WP admin
2. Update `design/HISTORY.md` với full build history
3. `git add <theme-name>/`
4. `git commit -m "feat: add <theme-name> theme — <style> inspired"`

---

# MODE 2: Review Theme

```bash
bots/automated/create-theme-from-url.md review <theme-name>
```

1. Chạy screenshot script → `design/versions/audit_N/`
2. Xem screenshots, audit theo checklist (18 items trên)
3. Tạo `issues.md` với tất cả issues
4. Fix all issues
5. Repeat audit loop đến khi clean
6. Update `design/HISTORY.md`
7. Commit

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
