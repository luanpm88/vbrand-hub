# Full-Site Scraper Bot — Clone Any Website

> Bot tự động clone/copy website thành standalone PHP site.
> Input: URL + ý tưởng. Output: folder site hoàn chỉnh chạy `php -S localhost:8888`.

## Usage

```bash
# Clone site mới
bots/scrape/full-site/scraper.md clone https://mailchimp.com

# Update site đã clone
bots/scrape/full-site/scraper.md update mailchimp "fix pricing page layout"

# Audit lại site
bots/scrape/full-site/scraper.md audit mailchimp

# Chỉ screenshot
bots/scrape/full-site/scraper.md screenshot mailchimp
```

### Khi user nói ngắn gọn

| User nói | Claude làm |
|----------|-----------|
| `clone site mailchimp.com` | Chạy clone flow |
| `clone https://example.com` | Chạy clone flow |
| `update mailchimp fix menu` | Chạy update flow cho site mailchimp |
| `audit mailchimp` | Screenshot + review |
| `clone site giống mailchimp cho brand X` | Clone + customize |

---

## Output Structure

```
bots/scrape/full-site/sites/{SITE_NAME}/
├── {SITE_NAME}.md          ← Case-specific notes (design tokens, structure, lessons)
├── index.php               ← Homepage
├── _header.php             ← Shared header (nav, promo banner)
├── _footer.php             ← Shared footer (links, social, legal)
├── {page}.php              ← Content pages
├── css/
│   └── style.css           ← Single CSS file, all styles
├── js/
│   └── script.js           ← Single JS file (mobile nav, accordion, scroll effects)
├── images/
│   ├── logo-wordmark.png
│   ├── hero/               ← Hero images per page
│   ├── features/           ← Feature screenshots
│   ├── integrations/       ← Integration/partner logos
│   ├── brands/             ← Trust/client logos
│   ├── social/             ← Social media icons
│   ├── icons/              ← UI icons (app store, gdpr, etc.)
│   └── {section}/          ← Other section-specific images
└── design/
    ├── screenshot-all.js   ← Puppeteer screenshot script
    ├── reference/           ← Original site screenshots (target)
    └── versions/
        ├── audit_1/        ← First round screenshots
        ├── audit_2/        ← After fixes
        └── audit_N/        ← Latest
```

---

## Phase 1: Research & Analysis

### 1.1 Capture Reference Screenshots

Trước khi code, chụp reference screenshots của site gốc.

```bash
# Dùng Puppeteer hoặc browser DevTools
# Chụp full-page screenshots của tất cả pages chính
# Lưu vào: design/reference/
```

Nếu không tự chụp được (site block bots), yêu cầu user:
- Mở site trong browser
- Cmd+Shift+P → "Capture full size screenshot"
- Hoặc dùng extension GoFullPage
- Lưu screenshots vào `design/reference/`

### 1.2 Analyze Design System

Từ reference screenshots + inspect site gốc, extract:

**Colors:**
```
Primary:     #______
Secondary:   #______
Accent:      #______
Background:  #______
Text:        #______
Border:      #______
```

**Typography:**
```
Heading font: ______ (weight, style)
Body font:    ______ (weight, style)
```

**Layout:**
```
Max-width:    ______px
Grid:         ______ columns
Breakpoints:  desktop ______px, mobile ______px
```

**Components:** Buttons, cards, forms, nav patterns, spacing system

### 1.3 Map Site Structure

Liệt kê tất cả pages cần clone:

```
Page Name        | URL Path          | Priority
-----------------|-------------------|----------
Home             | /                 | Must
Features         | /features         | Must
Pricing          | /pricing          | Must
About            | /about            | Nice
Contact          | /contact          | Nice
...
```

Ưu tiên: Homepage > Product pages > Pricing > Support pages

### 1.4 Identify & Source Assets

**Images cần tìm:**
- Logo (wordmark + icon)
- Hero images (mỗi page)
- Feature screenshots/illustrations
- Brand/trust logos
- Integration partner logos
- Social media icons
- App store badges

**Nguồn images:**
1. Download trực tiếp từ site gốc (inspect → copy image URL)
2. CDN của site (thường có pattern: cdn.site.com/images/...)
3. Brand asset pages (nhiều company có public brand resources)
4. Companieslogo.com, Simple Icons cho brand logos
5. Tự tạo placeholder nếu không tìm được (solid color + text)

**Quan trọng:** Download images thật, KHÔNG dùng placeholder colors. Placeholder phá hỏng visual quality.

---

## Phase 2: Build Foundation

### 2.1 Setup Project

```bash
mkdir -p bots/scrape/full-site/sites/{SITE_NAME}/{css,js,images,design/reference,design/versions}
```

### 2.2 Create Design Tokens (CSS Variables)

```css
:root {
  /* Colors */
  --primary:     #______;
  --secondary:   #______;
  --accent:      #______;
  --bg:          #______;
  --text:        #______;
  --border:      #______;

  /* Typography */
  --font-heading: '______', serif;
  --font-body:    '______', sans-serif;

  /* Spacing */
  --space-xs:  4px;
  --space-sm:  8px;
  --space-md:  16px;
  --space-lg:  24px;
  --space-xl:  32px;
  --space-2xl: 48px;
  --space-3xl: 64px;

  /* Layout */
  --container:  1200px;
  --radius-sm:  4px;
  --radius-md:  8px;
  --radius-lg:  16px;

  /* Transitions */
  --transition-fast: 0.15s ease;
  --transition-base: 0.3s ease;
}
```

### 2.3 CSS Architecture

Single file `css/style.css` with numbered sections:

```css
/* ==========================================================================
   1. RESET & BASE
   2. TYPOGRAPHY
   3. LAYOUT (container, grid)
   4. BUTTONS
   5. CARDS
   ...
   N. INTUIT BAR / TOP BAR
   N+1. PROMO BANNER
   N+2. HEADER / NAV
   N+3. MOBILE NAV
   N+4. FOOTER
   N+5. PAGE-SPECIFIC: HOME
   N+6. PAGE-SPECIFIC: FEATURES
   ...
   LAST. RESPONSIVE / MOBILE OVERRIDES
   ========================================================================== */
```

**BEM naming** with site prefix: `.mc-header`, `.mc-card`, `.mc-btn`

### 2.4 Create Shared Components

**`_header.php`:**
```php
<?php
if (!isset($current_page)) $current_page = '';
if (!isset($base_path)) $base_path = '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Site Title</title>
  <link rel="stylesheet" href="<?php echo $base_path; ?>css/style.css">
</head>
<body>

<!-- Top bar, promo banner, header nav -->
<!-- $current_page controls active state highlighting -->

<main>
```

**`_footer.php`:**
```php
</main>
<!-- Footer CTA, footer columns, social links, legal bar -->
<script src="<?php echo $base_path; ?>js/script.js"></script>
</body>
</html>
```

**`js/script.js`:**
- Mobile hamburger menu (open/close)
- Sticky header shadow on scroll
- FAQ accordion (`.is-open` class toggle)
- Scroll reveal (IntersectionObserver)

### 2.5 Page Template

```php
<?php $current_page = 'page-name'; ?>
<?php include '_header.php'; ?>

<!-- Page content sections -->

<?php include '_footer.php'; ?>
```

---

## Phase 3: Build Pages

### Workflow per page:

1. **Study reference** — screenshot gốc của page đó
2. **Identify sections** — hero, features, testimonials, CTA, FAQ...
3. **Write HTML** — semantic, BEM classes
4. **Write CSS** — append new sections to style.css
5. **Source images** — download real images cho page
6. **Test locally** — `php -S localhost:8888`

### Section Patterns (common across sites):

| Section | Class Pattern | Notes |
|---------|--------------|-------|
| Hero | `.{prefix}-hero` | Full-width, image/gradient bg |
| Feature Row | `.{prefix}-feature-row` | Image + text, alternating sides |
| Card Grid | `.{prefix}-card-grid` | 2-4 columns, responsive |
| Logo Strip | `.{prefix}-logo-strip` | Brand trust logos, grayscale |
| Testimonial | `.{prefix}-testimonial` | Quote + author + photo |
| CTA Band | `.{prefix}-cta-band` | Full-width colored bg, centered |
| FAQ | `.{prefix}-faq` | Accordion with `.is-open` state |
| Stats | `.{prefix}-stats` | 3-4 big numbers in a row |
| Pricing Table | `.{prefix}-pricing` | Cards with featured highlight |
| Comparison | `.{prefix}-compare` | Table with checkmarks |

### Navigation

Menu chính nên list tất cả pages đã clone — mỗi item link trực tiếp, không dropdown:

```php
<nav>
  <a href="index.php" class="<?php echo $current_page === 'home' ? 'active' : ''; ?>">Home</a>
  <a href="features.php" class="<?php echo $current_page === 'features' ? 'active' : ''; ?>">Features</a>
  <!-- ... all pages ... -->
</nav>
```

### Internal Links

**Tất cả `href="#"` phải được map tới page thật:**
- "Start Free Trial", "Get Started" → pricing.php
- "Learn More" → page phù hợp với context
- "Contact Us" → contact.php
- External links (social, legal, app store) → giữ `#`

---

## Phase 4: Screenshot & Audit

### 4.1 Screenshot Script

Tạo `design/screenshot-all.js`:

```javascript
const puppeteer = require('puppeteer');
const path = require('path');
const fs = require('fs');

const BASE_URL = 'http://localhost:8888';
const PAGES = [
    { name: 'home', path: '/' },
    { name: 'features', path: '/features.php' },
    // ... all pages
];

const VIEWPORTS = [
    { name: 'desktop', width: 1440, height: 900 },
    { name: 'mobile', width: 375, height: 812 },
];

(async () => {
    const versionsDir = path.join(__dirname, 'versions');
    let auditNum = process.argv[2];
    if (!auditNum) {
        const existing = fs.readdirSync(versionsDir).filter(d => d.startsWith('audit_'));
        auditNum = existing.length + 1;
    }

    const outDir = path.join(versionsDir, `audit_${auditNum}`);
    fs.mkdirSync(outDir, { recursive: true });

    const browser = await puppeteer.launch({ headless: 'new' });

    for (const vp of VIEWPORTS) {
        for (const pg of PAGES) {
            const page = await browser.newPage();
            await page.setViewport({ width: vp.width, height: vp.height });
            await page.goto(`${BASE_URL}${pg.path}`, { waitUntil: 'networkidle0', timeout: 15000 });
            await new Promise(r => setTimeout(r, 500));
            await page.screenshot({ path: path.join(outDir, `${pg.name}_${vp.name}.png`), fullPage: true });
            await page.close();
        }
    }

    await browser.close();
})();
```

### 4.2 Run Audit

```bash
# Start PHP server
php -S localhost:8888 &

# Take screenshots
node design/screenshot-all.js

# Review screenshots visually
# Compare with design/reference/ originals
```

### 4.3 Audit Checklist

Review mỗi screenshot cho:

- [ ] **Layout** — sections đúng thứ tự, spacing hợp lý
- [ ] **Typography** — font đúng, size hierarchy rõ ràng
- [ ] **Colors** — match design tokens, không có default browser colors
- [ ] **Images** — real images hiển thị, không placeholder/broken
- [ ] **Navigation** — menu hiện đúng, active state highlight
- [ ] **Buttons/CTAs** — visible, đúng màu, text rõ ràng
- [ ] **Footer** — columns aligned, social icons hiện
- [ ] **Mobile** — responsive, không overflow, menu hamburger hoạt động
- [ ] **Links** — tất cả internal links point tới real pages
- [ ] **JS** — accordion/toggle hoạt động, scroll effects smooth

### 4.4 Fix & Re-audit

```
Audit 1 → Initial build → Screenshot → Find issues
Audit 2 → Fix CSS/layout issues → Re-screenshot
Audit 3 → Fix images/assets → Re-screenshot
Audit 4 → Fix links/interactions → Re-screenshot
...
Audit N → Final polish → Done
```

Mỗi audit round lưu screenshots riêng: `design/versions/audit_N/`

---

## Phase 5: Polish & Finalize

### 5.1 Final Checks

- [ ] Tất cả pages load không lỗi PHP
- [ ] Tất cả images hiện (không 404)
- [ ] FAQ accordion click mở/đóng
- [ ] Mobile hamburger menu hoạt động
- [ ] Tất cả internal links navigate đúng page
- [ ] Active state menu highlight đúng
- [ ] No horizontal scroll on mobile
- [ ] Footer consistent across all pages

### 5.2 Write Case Notes

Tạo `{SITE_NAME}.md` trong folder site với:
- Design tokens (colors, fonts)
- Site structure + page list
- Lessons learned / known issues
- Image sources used
- Special patterns/components

---

## Update Flow

Khi user muốn update site đã clone:

```
1. Đọc {SITE_NAME}.md để hiểu context
2. Đọc code hiện tại
3. Thực hiện changes
4. Chạy PHP server: php -S localhost:8888
5. Screenshot audit: node design/screenshot-all.js
6. Review screenshots
7. Fix nếu cần → re-screenshot
8. Update {SITE_NAME}.md nếu có thay đổi quan trọng
```

---

## Advanced: Auto-Find Site Ideas

Khi user nói "clone site giống X cho brand Y":

1. **Analyze brand Y** — industry, target audience, tone
2. **Search for reference sites** — tìm top sites trong cùng industry
3. **Suggest 2-3 options** với reasoning
4. **User chọn** → bắt đầu clone flow

Ví dụ: "clone site cho thương hiệu thời trang" → suggest Nike.com, Zara.com, Uniqlo.com layouts

---

## Dependencies

```bash
# Puppeteer (for screenshots)
npm install puppeteer

# PHP CLI (for local server)
php -S localhost:8888
```

---

## File Map

```
bots/scrape/full-site/
├── scraper.md              ← THIS FILE — bot instructions
└── sites/
    ├── mailchimp/          ← Cloned Mailchimp site
    │   ├── mailchimp.md    ← Case notes
    │   ├── *.php           ← Pages
    │   ├── css/style.css
    │   ├── js/script.js
    │   ├── images/
    │   └── design/
    ├── {next-site}/        ← Future clones
    └── ...
```
