# AcelleMail Landing Site — Case Notes

> Standalone PHP landing page for AcelleMail (self-hosted email marketing platform)
> Based on: Mailchimp clone layout | Rebranded: March 2026 | Audits: 7 (clone) + ongoing (rebrand)

## Quick Start

```bash
cd bots/scrape/full-site/sites/acellemail
php -S localhost:8888

# Screenshot audit
node design/screenshot-all.js

# Generate images with DALL-E
OPENAI_API_KEY=sk-xxx node design/generate-images.js
```

---

## Design System

### Colors (Blue Professional Palette)
| Token | Value | Usage |
|-------|-------|-------|
| `--mc-yellow` | `#4A90D9` | Primary blue accent, promo banners, hero |
| `--mc-black` | `#1E293B` | Text, dark sections, footer bg |
| `--mc-teal` | `#2563EB` | CTAs, links, hover states |
| `--mc-teal-hover` | `#1D4ED8` | CTA hover state |
| `--mc-cream` | `#F8FAFC` | Page background |
| `--mc-white` | `#FFFFFF` | Cards, clean sections |
| `--mc-gray` | `#64748B` | Secondary text |
| `--mc-light-gray` | `#F1F5F9` | Subtle backgrounds, hover |
| `--mc-border` | `#E2E8F0` | Borders, dividers |

Note: CSS variable names kept as `--mc-*` from the original clone to avoid breaking 4100+ lines of CSS.

### Typography
| Element | Font | Weight | Notes |
|---------|------|--------|-------|
| Headings | Source Serif 4 | 300 (light) | Serif, elegant, large sizes |
| Body | Inter | 400-600 | Sans-serif, clean |
| Nav | Inter | 500 (medium) | 14px |
| Buttons | Inter | 600 (semibold) | Uppercase not used |

### Layout
- Container max-width: `1200px`
- Spacing scale: 4/8/16/24/32/48/64/96px
- Border radius: 4/8/12/24/999px (pill)
- Breakpoint: `768px` (desktop/mobile)

---

## AcelleMail Product Info

### Key Links
- **Buy:** https://codecanyon.net/item/acelle-email-marketing-web-application/17796082
- **Demo:** https://demo.acellemail.com
- **Docs:** https://acellemail.com
- **Support:** support@acellemail.com

### Pricing (CodeCanyon)
| License | Price | Use Case |
|---------|-------|----------|
| Regular | $64 | Single domain, full source code, 6 months support |
| Extended | $199 | SaaS framework, charge end-users, full source code |
| Installation | $49 | Professional setup on your server |
| Annual Support | $149/yr | Priority tickets, migration, custom config |

### Key Features
- Drag & drop email builder with 100+ templates
- Marketing automation (triggers, journeys, scheduled)
- List management (import/export, segmentation, double opt-in)
- Email verification (built-in)
- Open/click/bounce tracking
- GDPR compliance tools
- Full source code (PHP/Laravel)
- Works with: Amazon SES, SendGrid, SparkPost, Elastic Email, Mailgun, any SMTP

---

## Site Structure

### Pages (10 total)

| Page | File | $current_page | Description |
|------|------|---------------|-------------|
| Home | `index.php` | `home` | Hero + features overview + stats + sending services |
| All Features | `features.php` | `features` | Feature categories + integration strip |
| Email Marketing | `email-marketing.php` | `email-marketing` | Email builder features + cost savings + FAQ |
| Automation | `automation.php` | `automation` | Automation flows + templates + FAQ |
| Integrations | `integrations.php` | `integrations` | Sending services + payment gateways + API |
| Pricing | `pricing.php` | `pricing` | 4 license cards + comparison + FAQ |
| Security | `security.php` | `security` | Self-hosted security + GDPR + FAQ |
| About | `about.php` | `about` | AcelleMail story + philosophy + commitment |
| Help | `help.php` | `help` | Documentation + guides + support options |
| Contact | `contact.php` | `contact` | Form + support options + CodeCanyon forum |

### Shared Components

| File | Contains |
|------|----------|
| `_header.php` | Promo banner, main nav (10 items), mobile nav, Buy Now / Try Demo CTAs |
| `_footer.php` | Footer CTA, 5-column links, AcelleMail tagline, social icons, legal bar |
| `css/style.css` | All styles (~4100 lines), numbered sections 1-60+ |
| `js/script.js` | Mobile nav, sticky header, FAQ accordion, scroll reveal |

### Navigation
Desktop nav: 10 direct links (no dropdowns):
```
Home | Features | Email | Automation | Integrations | Pricing | Security | About | Help | Contact
```

### Key External Links
- "Buy Now" CTA → CodeCanyon listing
- "Try Demo" → demo.acellemail.com
- Documentation → acellemail.com
- Support → CodeCanyon comments

---

## CSS Architecture

Same as original clone — numbered sections with BEM naming using `mc-` prefix.

### Important Notes
- CSS variable names are `--mc-*` (kept from clone, not renamed)
- FAQ uses `.is-open` class (NOT `.mc-faq__item--open`)
- No inline scripts in pages — use shared `script.js` only
- Mobile breakpoint: `@media (max-width: 768px)`

---

## Image Generation

### DALL-E Script
`design/generate-images.js` — generates all images via OpenAI DALL-E 3 API.

```bash
OPENAI_API_KEY=sk-xxx node design/generate-images.js --dry-run  # preview
OPENAI_API_KEY=sk-xxx node design/generate-images.js            # all images
OPENAI_API_KEY=sk-xxx node design/generate-images.js --only hero # category only
```

Categories: `hero` (5), `features` (20+), `about` (4), `help` (2)

### Logo Files
- `images/logo_dark.svg` — dark logo for light backgrounds (header)
- `images/logo_light.svg` — light logo for dark backgrounds (footer)

### Images NOT requiring generation
- Social icons (`images/social/`) — generic SVGs, keep as-is
- Generic icons (`images/icons/gdpr.svg`, etc.) — keep as-is
- Integration logos where applicable (Stripe, WordPress, etc.)

---

## Audit History

| Audit | Focus | Notes |
|-------|-------|-------|
| 1-7 | Original Mailchimp clone | Layout, images, links, FAQ, nav restructure |
| 8+ | AcelleMail rebrand | Content rewrite, color change, image generation |

---

## Lessons Learned (from clone phase)

1. **FAQ: use shared script.js only** — inline scripts cause class name conflicts
2. **SVG elements can't have CSS ::before/::after** — use transform for icon animation
3. **CSS cascade matters** — later sections override earlier ones
4. **10 nav items fit at 1440px** with tight padding (6px 10px, gap 2px, font 14px)
5. **Audit screenshots after every change round** — visual regression is easy to miss
6. **Keep CSS variable names stable** — renaming `--mc-*` to `--ac-*` would break 4100 lines
