> ⚠️ **LEGACY / HISTORICAL — not a source of truth.** This is the AcelleMail product marketing plan — MISPLACED here; it is not about vBrand/brand. The current brand system is the `acelle/brand` plugin — see `~/apps/acelle_brand/docs/`.

# AcelleMail Marketing Plan

> Source of truth cho tất cả marketing activities: SEO, social channels, content strategy.

## Brand Info

| Key | Value |
|-----|-------|
| Product | AcelleMail — Self-Hosted Email Marketing Platform |
| Website | https://acellemail.com |
| Knowledge Base | https://knowledge.acellemail.com |
| Demo | https://demo.acellemail.com |
| CodeCanyon | https://codecanyon.net/item/acelle-email-marketing-web-application/17796082 |
| Price | $64 (Regular), Extended license available |
| Downloads | 50,000+ |
| Rating | 4.6 stars (1,200+ reviews) |
| Since | 2016 |
| Stack | Laravel/PHP, self-hosted |
| Target Audience | Developers, agencies, businesses wanting email marketing without SaaS fees |
| Competitors | Mailchimp, SendGrid, Sendinblue/Brevo, ActiveCampaign, ClickFunnels, Mautic, Constant Contact |
| USP | Self-hosted, full source code, one-time fee, no recurring costs, any SMTP |

## Channels (TBD)

| Channel | URL | Status | Notes |
|---------|-----|--------|-------|
| Website | https://acellemail.com | Active | Landing site (Laravel) |
| YouTube | — | Planned | Tutorials, demos, comparisons |
| X (Twitter) | — | Planned | Product updates, tips, community |
| LinkedIn | — | Planned | B2B reach, case studies |
| GitHub | — | Planned | Open source presence, community |
| Product Hunt | — | Planned | Launch/relaunch |
| Reddit | — | Planned | r/selfhosted, r/emailmarketing |

---

# SEO Implementation Plan — Landing Site

## Current State (2026-03-29)

- 10 pages: home, features, email-marketing, automation, integrations, pricing, security, about, help, contact
- Title tags: generic (e.g., "Features | AcelleMail")
- Meta description: 1 default dùng chung cho tất cả trang
- Missing: OG tags, Twitter Cards, canonical URLs, JSON-LD, sitemap, lazy loading

## Phase 1: Layout Meta Infrastructure

**File:** `acellemail/landing/resources/views/layouts/app.blade.php`

Thêm vào `<head>`:
- Canonical URL (`@yield`)
- Open Graph tags (og:type, og:site_name, og:title, og:description, og:url, og:image, og:locale)
- Twitter Card tags
- Robots meta
- `@stack('jsonld')` slot

## Phase 2: Per-Page SEO

| Page | Title | Meta Description | Target Keyword |
|------|-------|------------------|----------------|
| `/` | AcelleMail — Self-Hosted Email Marketing Platform \| No Monthly Fees | Self-hosted email marketing with full source code. Send unlimited emails via Amazon SES, SendGrid, or any SMTP. One-time $64 license. 50,000+ downloads. | self-hosted email marketing platform |
| `/features` | Email Marketing Features — Builder, Automation & Analytics \| AcelleMail | Explore AcelleMail features: drag & drop email builder, marketing automation, A/B testing, list segmentation, analytics, and 100+ templates. Self-hosted. | email marketing features |
| `/email-marketing` | Self-Hosted Email Marketing Software — Send Unlimited Emails \| AcelleMail | Send unlimited email campaigns from your own server. Drag & drop builder, 100+ templates, real-time analytics. No per-subscriber fees. Full data ownership. | self-hosted email marketing software |
| `/automation` | Marketing Automation — Trigger-Based Emails & Workflows \| AcelleMail | Build automated email workflows with triggers, delays, and conditions. Welcome series, drip campaigns, and customer journeys — all self-hosted. | email marketing automation |
| `/integrations` | Integrations — Amazon SES, SendGrid, Mailgun, Stripe & More \| AcelleMail | Connect AcelleMail to Amazon SES, SendGrid, SparkPost, Mailgun, Postmark. Accept payments via Stripe, PayPal, Braintree. REST API for custom integrations. | email marketing integrations SMTP |
| `/pricing` | Pricing — $64 One-Time License, No Monthly Fees \| AcelleMail | AcelleMail starts at $64 — one-time payment, lifetime updates, full source code. No monthly fees, no per-subscriber charges. Compare Regular vs Extended. | email marketing software pricing |
| `/security` | Security & GDPR Compliance — Your Data on Your Server \| AcelleMail | AcelleMail is self-hosted: subscriber data never leaves your server. Full GDPR compliance, encryption at rest, role-based access, and audit logging. | GDPR compliant email marketing |
| `/about` | About AcelleMail — The Story Behind 50,000+ Downloads | AcelleMail is a Laravel-based email marketing platform trusted by 50,000+ businesses. Our mission: make professional email marketing accessible to everyone. | about acellemail |
| `/help` | Help Center — Documentation, Guides & Support \| AcelleMail | Get help with AcelleMail: installation guides, configuration docs, API reference, video tutorials, and community forum. Everything you need to get started. | acellemail documentation help |
| `/contact` | Contact AcelleMail — Support, Sales & Partnership Inquiries | Get in touch with AcelleMail for pre-sales questions, technical support, partnership inquiries, or custom solutions. We typically respond within 24 hours. | contact acellemail support |

## Phase 3: JSON-LD Structured Data

| Schema | Pages | Purpose |
|--------|-------|---------|
| Organization | Home, About, Contact | Brand entity cho Google Knowledge Panel |
| SoftwareApplication | Home | Rich snippet: rating, price, category |
| WebSite + SearchAction | Home | Sitelinks search box |
| BreadcrumbList | All except Home | Breadcrumb trail trong SERP |
| FAQPage | Pricing, Automation, Email Marketing, Security | FAQ rich snippets |
| Product + Offer | Pricing | Price display trong SERP |

## Phase 4: Technical SEO

- XML Sitemap (`/sitemap.xml`) — 10 URLs with priority + changefreq
- robots.txt — add Sitemap directive
- Canonical URLs — prevent duplicate content

## Phase 5: Performance SEO

- `loading="lazy"` cho ~80 images (trừ hero)
- `fetchpriority="high"` cho hero images
- Target: Lighthouse SEO 95+

## Phase 6: OG Share Image

- `public/images/og/og-default.png` (1200x630px)
- Branded: logo + tagline + gradient background

---

# SEO Implementation — Knowledge Base (knowledge.acellemail.com)

## Overview

KB site (Laravel 13) — ~60 articles, 18 categories, 74 tags. Đóng vai trò quan trọng cho:
- **Long-tail SEO**: articles target "how to..." queries
- **Competitor capture**: "Mailchimp alternative", "migrate from ActiveCampaign", etc.
- **Authority building**: tutorials, guides tạo topical authority cho email marketing

## Implemented

### Layout Meta Infrastructure
- OG tags, Twitter Cards, canonical URLs, robots meta, `@stack('jsonld')` trong `layouts/kb.blade.php`

### Dynamic Per-Page SEO
| Page Type | Title Pattern | OG | Canonical |
|-----------|---------------|-----|-----------|
| Home | "AcelleMail Knowledge Base — Email Marketing Tutorials, Guides & How-To" | Custom | Auto |
| Article | "{meta_title or title} — AcelleMail KB" | Dynamic from article | Explicit route |
| Category | "{name} — Email Marketing Guides \| AcelleMail KB" | Dynamic | Explicit route |
| Tag | "{name} — Email Marketing Articles \| AcelleMail KB" | Dynamic | Explicit route |
| Search | noindex, follow (prevent thin content indexing) | — | — |

### JSON-LD Structured Data
| Schema | Pages |
|--------|-------|
| Article (headline, author, datePublished, publisher) | Every article |
| BreadcrumbList | Articles, categories, tags |
| WebSite + SearchAction | Home |
| Organization | Home |

### Technical SEO
- robots.txt: Disallow /admin/, /login, /register + Sitemap directive
- Sitemap: Already existed — dynamic XML with caching

### Competitor Capture Strategy
- Footer "Compare" section on BOTH landing + KB sites
- Links: "Mailchimp Alternative", "SendGrid Alternative", "ActiveCampaign Alternative", "ClickFunnels Alternative", "Sendinblue Alternative"
- All link to KB category: migration-comparison
- **Next step**: Write dedicated comparison articles per competitor

## Content Roadmap (Future)

### Priority Comparison Articles (Target competitor keywords)
1. "AcelleMail vs Mailchimp: Self-Hosted Email Marketing Comparison"
2. "AcelleMail vs ActiveCampaign: Features, Pricing & Data Ownership"
3. "AcelleMail vs SendGrid: Sending Service vs Full Platform"
4. "AcelleMail vs ClickFunnels: Email Marketing Without the SaaS Lock-in"
5. "AcelleMail vs Sendinblue (Brevo): Cost Comparison for 100K Subscribers"
6. "AcelleMail vs Constant Contact: Why Self-Hosted Wins"
7. "Best Mailchimp Alternatives for Self-Hosted Email Marketing (2026)"
8. "How to Migrate from Mailchimp to AcelleMail — Step by Step"
9. "Email Marketing Cost Calculator: SaaS vs Self-Hosted"

### Target Keywords per Competitor
| Competitor | Target Keywords |
|------------|----------------|
| Mailchimp | mailchimp alternative, mailchimp self-hosted, migrate from mailchimp, mailchimp pricing too expensive |
| ActiveCampaign | activecampaign alternative, activecampaign open source alternative |
| SendGrid | sendgrid alternative email marketing, sendgrid vs self-hosted |
| ClickFunnels | clickfunnels email alternative, clickfunnels too expensive |
| Sendinblue/Brevo | sendinblue alternative, brevo self-hosted alternative |
| Constant Contact | constant contact alternative, constant contact cheaper option |
| Mautic | mautic vs acellemail, self-hosted email marketing comparison |

---

## Status — Landing Site

- [x] Plan created (2026-03-29)
- [x] Phase 1: Layout meta infrastructure (2026-03-29)
- [x] Phase 2: Per-page SEO sections (2026-03-29)
- [x] Phase 3: JSON-LD structured data (2026-03-29)
- [x] Phase 4: XML Sitemap + robots.txt (2026-03-29)
- [x] Phase 5: Performance SEO — lazy loading (2026-03-29)
- [x] Phase 6: OG share image — SVG (2026-03-29)
- [x] Deployed to production (2026-03-30)
- [x] Footer "Compare" section added (2026-03-30)

## Status — Knowledge Base

- [x] Layout meta infrastructure — OG, Twitter, canonical, JSON-LD (2026-03-30)
- [x] Per-page dynamic SEO — titles, descriptions, OG for all page types (2026-03-30)
- [x] JSON-LD — Article, BreadcrumbList, WebSite, Organization (2026-03-30)
- [x] robots.txt — Disallow admin + Sitemap directive (2026-03-30)
- [x] Footer "Compare" section with competitor links (2026-03-30)
- [x] Deployed to production (2026-03-30)
- [ ] Comparison articles per competitor (content roadmap)
