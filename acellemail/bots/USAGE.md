# AcelleMail Landing — Usage Guide

> Manage the AcelleMail landing site (PHP static site cloned from Mailchimp layout).

## Local Development

```bash
cd bots/scrape/full-site/sites/acellemail

# Start local server
php -S localhost:8888

# Open in browser
open http://localhost:8888
```

## Pages

| Page | File | URL |
|------|------|-----|
| Home | `index.php` | `/` |
| Email Marketing | `email-marketing.php` | `/email-marketing.php` |
| Features | `features.php` | `/features.php` |
| Pricing | `pricing.php` | `/pricing.php` |
| Integrations | `integrations.php` | `/integrations.php` |
| Automation | `automation.php` | `/automation.php` |
| Security | `security.php` | `/security.php` |
| About | `about.php` | `/about.php` |
| Contact | `contact.php` | `/contact.php` |
| Help | `help.php` | `/help.php` |

Shared partials: `_header.php`, `_footer.php`

## File Structure

```
acellemail/
├── index.php               <- Home page
├── *.php                   <- Other pages
├── _header.php             <- Shared header (nav, logo, CSS)
├── _footer.php             <- Shared footer (links, scripts)
├── css/
│   └── style.css           <- Main stylesheet (4100+ lines)
├── js/
│   └── script.js           <- Interactions, mobile menu
├── images/
│   ├── logo_light.svg      <- White logo (header, dark bg)
│   ├── logo_dark.svg       <- Dark logo (footer, light bg)
│   ├── hero/               <- Hero images
│   ├── features/           <- Feature section images
│   └── integrations/       <- Integration logos
├── design/
│   └── IMAGE_REPLACEMENT_GUIDE.md  <- Image gen prompts
├── bots/
│   ├── deploy.md           <- Deploy instructions
│   ├── SERVER.md           <- Server config
│   └── USAGE.md            <- This file
└── acellemail.md           <- Brand content reference
```

## Deploy to Production

```bash
# Full deploy
rsync -avz \
  --exclude='.git/' \
  --exclude='design/node_modules/' \
  --exclude='design/versions/' \
  --exclude='design/reference/' \
  --exclude='bots/' \
  --exclude='.DS_Store' \
  bots/scrape/full-site/sites/acellemail/ \
  brandnew:/var/www/acellemail-landing/

ssh brandnew "sudo chown -R vbrandwww:vbrand /var/www/acellemail-landing"
```

See [deploy.md](deploy.md) for more options (single file, images only, CSS only).

## Production

| Key | Value |
|-----|-------|
| URL | https://beta.acellemail.com |
| Server | `brandnew` (18.141.199.175) |
| Path | `/var/www/acellemail-landing/` |

## Editing Content

### CSS Variables (colors)

CSS uses `--mc-*` prefix (kept from Mailchimp clone to avoid breaking 4100+ lines). Key variables in `css/style.css`:

```css
--mc-yellow: #2563EB;      /* AcelleMail blue (was Mailchimp yellow) */
--mc-dark: #1e1e2f;        /* Dark background */
--mc-text: #241c15;        /* Body text */
```

### Images

See `design/IMAGE_REPLACEMENT_GUIDE.md` for:
- P0: Branding images that need replacement (4 images)
- P1: UI screenshots to regenerate via ChatGPT web (14 images)
- P2: Generic photos — keep as-is (13 images)
- P3: Solid color placeholders — optional (4 images)

### Logo

- Light (white): `images/logo_light.svg` — used in header
- Dark (colored): `images/logo_dark.svg` — used in footer

### Fonts

- Headings: Source Serif 4 (Google Fonts)
- Body: Inter (Google Fonts)

## Bot Prompts

### Deploy after edit
```
deploy acellemail landing
```

### Update specific page
```
update acellemail index page — change hero text to "..."
```

### Add new page
```
add testimonials page to acellemail landing
```

### Replace an image
```
replace acellemail pricing hero image with [uploaded image]
```

### Audit all pages
```
audit acellemail landing — screenshot all pages desktop + mobile
```
