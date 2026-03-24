# Mailchimp Clone — Case Notes

> Standalone PHP clone of mailchimp.com
> Created: March 2026 | Audits: 7 rounds

## Quick Start

```bash
cd bots/scrape/full-site/sites/mailchimp
php -S localhost:8888

# Screenshot audit
node design/screenshot-all.js
```

---

## Design System

### Colors
| Token | Value | Usage |
|-------|-------|-------|
| `--mc-yellow` | `#FFE01B` | Primary brand, promo banners, hero accents |
| `--mc-black` | `#241C15` | Text, dark sections, footer bg |
| `--mc-teal` | `#007C89` | CTAs, links, hover states |
| `--mc-cream` | `#FEFDF5` | Page background |
| `--mc-white` | `#FFFFFF` | Cards, clean sections |
| `--mc-gray` | `#6B6B6B` | Secondary text |
| `--mc-light-gray` | `#F6F1EB` | Subtle backgrounds, hover |
| `--mc-border` | `#E5E0DA` | Borders, dividers |

### Typography
| Element | Font | Weight | Notes |
|---------|------|--------|-------|
| Headings | Source Serif 4 | 300 (light) | Serif, elegant, large sizes |
| Body | Inter | 400-600 | Sans-serif, clean |
| Nav | Inter | 500 (medium) | 14px |
| Buttons | Inter | 600 (semibold) | Uppercase not used |

### Google Fonts URL
```
https://fonts.googleapis.com/css2?family=Source+Serif+4:ital,opsz,wght@0,8..60,200..900;1,8..60,200..900&family=Inter:wght@300;400;500;600;700&display=swap
```

### Layout
- Container max-width: `1200px`
- Spacing scale: 4/8/16/24/32/48/64/96px
- Border radius: 4/8/12/24/999px (pill)
- Breakpoint: `768px` (desktop/mobile)

---

## Site Structure

### Pages (10 total)

| Page | File | $current_page | Description |
|------|------|---------------|-------------|
| Home | `index.php` | `home` | Hero + features overview + case study + integrations + trust |
| All Features | `features.php` | `features` | 6 feature categories + integration strip + trust |
| Email Marketing | `email-marketing.php` | `email-marketing` | Email-specific features + case study + FAQ |
| Automations | `automation.php` | `automation` | Automation flows + templates + FAQ |
| Integrations | `integrations.php` | `integrations` | Categorized integration directory |
| Pricing | `pricing.php` | `pricing` | 4 plan cards + comparison table + FAQ |
| Security | `security.php` | `security` | Certifications + features + FAQ |
| About | `about.php` | `about` | Hero image + founder story + culture |
| Help | `help.php` | `help` | Search + guides + topic cards + expert section |
| Contact | `contact.php` | `contact` | Form + mailing info + Intuit family |

### Shared Components

| File | Contains |
|------|----------|
| `_header.php` | Intuit bar, promo banner, main nav (10 items), mobile nav overlay |
| `_footer.php` | Footer CTA, 5-column links, Mailchimp Presents, social icons, app store, legal bar |
| `css/style.css` | All styles (~4100 lines), numbered sections 1-60+ |
| `js/script.js` | Mobile nav, sticky header, FAQ accordion, scroll reveal |

### Navigation

Desktop nav shows all 10 pages directly (no dropdowns, no chevrons):
```
Home | Features | Email | Automation | Integrations | Pricing | Security | About | Help | Contact
```

Mobile nav: hamburger menu with 9 links + CTA buttons.

### Active State Logic
```php
$current_page === 'home' ? ' mc-header__nav-link--active' : ''
```
Each page sets `$current_page` at line 1, header checks with `===`.

---

## CSS Architecture

### Section Organization

```
Sections 1-10:   Reset, base, typography, layout, buttons, cards
Sections 11-20:  Intuit bar, promo banner, header, mobile nav, hero, features
Sections 21-30:  Logo strip, case study, testimonials, integrations, pricing
Sections 31-40:  Compare table, CTA sections, trust section, FAQ, footer
Sections 41-50:  Page-specific (email-marketing, automation, security, etc.)
Sections 51-60:  Overrides, responsive, appended fixes
```

### BEM Naming Pattern
```
.mc-{component}
.mc-{component}__{element}
.mc-{component}--{modifier}
```

### Important CSS Notes

- Later sections override earlier ones (cascade priority)
- FAQ uses `.is-open` class (NOT `.mc-faq__item--open`)
- SVG chevron icons rotate with `transform: rotate(180deg)` on `.is-open`
- Button reset needed for FAQ: `background: none; border: none;`
- Mobile breakpoint: `@media (max-width: 768px)`

---

## JavaScript Components

### FAQ Accordion
- Selector: `.mc-faq__item` + `.mc-faq__question`
- Toggle class: `.is-open` on `.mc-faq__item`
- Single-open mode (clicking one closes others)
- Handled by shared `script.js` — do NOT add inline scripts in pages

### Mobile Navigation
- Hamburger: `#hamburgerBtn` → toggles `.is-open` on `#mobileNav`
- Close: `#mobileNavClose` + Escape key
- Body scroll locked when open

### Sticky Header
- `#mainHeader` gets `.mc-header--scrolled` class after 10px scroll
- Adds subtle shadow

### Scroll Reveal
- Elements with `.mc-reveal` class
- IntersectionObserver adds `.is-visible`
- Threshold: 10%, rootMargin: -40px bottom

---

## Image Assets

### Real Images (downloaded)
```
images/
├── logo-wordmark.png          ← Mailchimp logo
├── home-hero.png              ← Homepage hero composite
├── automation-hero.png        ← Automation page hero
├── help-hero.png              ← Help page hero
├── about-hero.jpg             ← About page hero (team photo)
├── features/
│   ├── automation-flows.png   ← Real Mailchimp screenshot
│   ├── predictive.png         ← AI/predictive features
│   ├── campaign-manager.png   ← Campaign dashboard
│   ├── email-sms.png          ← Email + SMS
│   ├── segmentation.png       ← Audience segmentation
│   ├── email-templates.png    ← Template gallery
│   ├── customer-journey.png   ← Journey builder
│   ├── content-studio.png     ← Content tools
│   ├── landing-pages.png      ← Landing page builder
│   └── websites.png           ← Website builder
├── integrations/
│   ├── shopify.png, woocommerce.png, quickbooks.png
│   ├── hubspot.png, stripe.png, instagram.png
│   ├── salesforce.png, wordpress.png, zapier.png
│   └── google-ads.png, google-analytics.png, typeform.png
├── brands/
│   ├── spotify.png, subway.png, canva.png, gap.png
│   └── ... (trust/client logos)
├── social/
│   └── facebook.svg, twitter.svg, instagram.svg, linkedin.svg, youtube.svg, pinterest.svg
└── icons/
    └── mcp.svg, ios.svg, android.svg, gdpr.svg
```

### Image Sources Used
1. Direct download from mailchimp.com CDN
2. Companieslogo.com for brand/integration logos
3. Simple Icons CDN for SVG social icons
4. Some feature screenshots from Mailchimp CDN (`eep.io/images/...`)

### Known Issue
- Integration logos were accidentally overwritten by a failed download agent once. Keep backups or verify after bulk image operations.

---

## Audit History

| Audit | Focus | Issues Found |
|-------|-------|-------------|
| 1-2 | Initial build + CSS fixes | Logo sizes, card heights, feature image spacing |
| 3 | Image quality | 6 placeholder images replaced with real screenshots |
| 4 | Integration logos | 9 logos accidentally overwritten → re-downloaded from brand CDNs |
| 5 | Link updates (header/footer) | Navigation links updated to real pages |
| 6 | Full link audit + FAQ fix | 150+ href="#" mapped to real pages, FAQ accordion CSS fixed |
| 7 | Menu restructure | Changed from 4 grouped items to 10 direct page links, removed chevrons |

---

## Lessons Learned

1. **Download real images first** — placeholders ruin the visual quality of the clone
2. **Don't run multiple image-download agents on same folder** — they can overwrite each other
3. **FAQ: use shared script.js only** — inline scripts cause class name conflicts
4. **SVG elements can't have CSS ::before/::after** — use transform for icon animation instead
5. **CSS cascade matters** — later sections override earlier ones, use this for fixes
6. **10 nav items fit at 1440px** with tight padding (6px 10px, gap 2px, font 14px)
7. **Always verify after bulk edits** — grep for `href="#"` counts to confirm
8. **Audit screenshots after every change round** — visual regression is easy to miss
