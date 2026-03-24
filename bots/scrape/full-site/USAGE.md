# Full-Site Scraper Bot — Usage Guide

> Clone any website into a standalone PHP site. Screenshot audit until pixel-perfect.

---

## Quick Reference

| Action | Prompt |
|--------|--------|
| Clone new site | `clone site https://mailchimp.com` |
| Clone with idea | `clone site giống stripe cho fintech brand` |
| Update existing | `update mailchimp fix pricing layout` |
| Audit screenshots | `audit mailchimp` |
| Run local server | `cd bots/scrape/full-site/sites/mailchimp && php -S localhost:8888` |

---

## 1. Clone a New Site

### Basic — from URL

```
clone site https://mailchimp.com
```

```
clone site https://stripe.com vào folder stripe
```

**What happens:**
1. Bot analyzes site: design system (colors, fonts, layout), page structure, assets
2. Creates folder: `bots/scrape/full-site/sites/{name}/`
3. Builds all pages as standalone PHP (header/footer shared includes)
4. Downloads real images (logos, heroes, features, brand logos)
5. Creates single CSS file with design tokens + BEM components
6. Creates JS (mobile nav, accordion, scroll effects)
7. Runs PHP server + takes Puppeteer screenshots
8. Reviews screenshots, fixes issues, re-screenshots
9. Writes `{name}.md` case notes

### With idea — bot finds the right site

```
clone site cho thương hiệu thời trang, giống Nike
```

```
tạo site SaaS dashboard giống linear.app hoặc notion.so
```

```
clone 1 site e-commerce đẹp cho brand mỹ phẩm
```

**What happens:**
1. Bot suggests 2-3 reference sites matching the idea
2. You pick one
3. Clone flow starts

### With reference screenshots

If the site blocks bots or needs login:

```
clone site từ screenshots trong /tmp/reference/
```

Chuẩn bị trước:
1. Mở site trong browser
2. Full-page screenshot mỗi page (Cmd+Shift+P → "Capture full size screenshot")
3. Lưu vào 1 folder
4. Bot phân tích screenshots thay vì crawl trực tiếp

---

## 2. Update Existing Site

### Fix specific issue

```
update mailchimp fix FAQ accordion không mở được
```

```
update mailchimp đổi menu thành flat links, bỏ dropdown
```

```
update mailchimp thêm page blog.php
```

### Redesign section

```
update mailchimp redesign pricing page, thêm toggle monthly/yearly
```

```
update mailchimp đổi hero section thành full-width video background
```

### Update links/navigation

```
update mailchimp cập nhật tất cả link nội bộ cho hợp lý
```

### Add new page

```
update mailchimp thêm page careers.php với team photos
```

**What happens:**
1. Bot reads `{name}.md` case notes to understand context
2. Reads current code
3. Makes changes
4. Starts PHP server (if not running)
5. Takes audit screenshots
6. Reviews, fixes if needed
7. Updates `{name}.md` if design/structure changed

---

## 3. Audit & Screenshots

### Full audit

```
audit mailchimp
```

**What happens:**
1. Starts PHP server on port 8888
2. Runs `node design/screenshot-all.js` — captures all pages at desktop (1440x900) + mobile (375x812)
3. Saves to `design/versions/audit_N/`
4. Reviews every screenshot for: layout, images, links, responsive, interactions
5. Reports issues found

### Quick screenshot only

```
screenshot mailchimp
```

Takes screenshots without reviewing.

### Compare audits

```
so sánh audit 5 và audit 7 của mailchimp
```

### Audit specific page

```
audit mailchimp chỉ pricing page
```

---

## 4. Run Locally

```bash
# Start server
cd bots/scrape/full-site/sites/mailchimp
php -S localhost:8888

# Open in browser
open http://localhost:8888

# Take screenshots (from site folder)
node design/screenshot-all.js

# Take specific audit number
node design/screenshot-all.js 8
```

---

## 5. Example Workflows

### A. Clone from scratch (full flow)

```
User: clone site mailchimp.com

Bot:
  Phase 1 — Research (5-10 min)
    ├── Capture/analyze reference screenshots
    ├── Extract: colors #FFE01B, #241C15, #007C89, fonts Source Serif 4 + Inter
    ├── Map 10 pages: home, features, pricing, automation, email, integrations, security, about, help, contact
    └── Identify 50+ images to download

  Phase 2 — Build Foundation
    ├── Create folder structure
    ├── Write CSS design tokens + base styles
    ├── Create _header.php, _footer.php, script.js
    └── Setup screenshot script

  Phase 3 — Build Pages (parallel agents)
    ├── Homepage: hero + features + case study + integrations + trust logos
    ├── Features: 6 categories, integration strip
    ├── Pricing: 4 plan cards, comparison table, FAQ
    ├── ... (all 10 pages)
    └── Download all real images

  Phase 4 — Audit Loop
    ├── Audit 1: initial screenshots → find layout issues
    ├── Audit 2: fix CSS → find image issues
    ├── Audit 3: fix images → find link issues
    ├── Audit 4: fix links + FAQ JS
    └── Audit N: final polish

  Phase 5 — Finalize
    ├── Write {name}.md case notes
    └── All pages working, all links functional
```

### B. Quick update

```
User: update mailchimp bỏ dropdown menu, thêm tất cả page lên nav

Bot:
  1. Read mailchimp.md → understand current nav structure
  2. Edit _header.php → replace 4 grouped items with 10 direct links
  3. Remove SVG chevron arrows
  4. Adjust CSS gap/padding for 10 items to fit
  5. Screenshot audit → verify
  Done in 2-3 minutes
```

### C. Add features to existing site

```
User: update mailchimp thêm dark mode toggle

Bot:
  1. Read mailchimp.md → understand CSS architecture
  2. Add CSS variables for dark theme
  3. Add toggle button in header
  4. Add JS to switch themes + save preference
  5. Screenshot audit both modes
```

---

## 6. File Structure

```
bots/scrape/full-site/
├── scraper.md              ← Bot instructions (how to clone)
├── USAGE.md                ← THIS FILE (how to use)
└── sites/
    ├── mailchimp/          ← Cloned site
    │   ├── mailchimp.md    ← Case notes (design tokens, pages, lessons)
    │   ├── index.php       ← Homepage
    │   ├── _header.php     ← Shared header
    │   ├── _footer.php     ← Shared footer
    │   ├── *.php           ← Content pages
    │   ├── css/style.css   ← All styles
    │   ├── js/script.js    ← All interactions
    │   ├── images/         ← All assets
    │   └── design/
    │       ├── screenshot-all.js
    │       ├── reference/      ← Original site screenshots
    │       └── versions/
    │           ├── audit_1/    ← First screenshots
    │           └── audit_N/    ← Latest screenshots
    ├── stripe/             ← Another cloned site (future)
    └── ...
```

---

## 7. Tips

**Image quality matters** — Always download real images. Placeholder colors or broken images make the clone look amateur. Sources: site CDN, companieslogo.com, Simple Icons.

**One CSS file** — Keep everything in `css/style.css` with numbered sections. Easier to debug and no import chains.

**Audit often** — Screenshot after every major change. Visual regressions are hard to spot in code.

**FAQ gotcha** — Use shared `script.js` for FAQ accordion. Never add inline `<script>` in pages — causes class name conflicts.

**Nav for testing** — List all pages directly in nav (no dropdowns). Makes it easy to click through and test everything.

**Mobile first check** — Always review mobile screenshots. Most layout issues show up on 375px width.

---

## 8. Dependencies

```bash
# PHP (built into macOS)
php -S localhost:8888

# Puppeteer (for screenshots)
# Install in any site folder that has design/screenshot-all.js:
cd bots/scrape/full-site/sites/mailchimp
npm install puppeteer
```
