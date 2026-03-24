# AcelleMail — Image Replacement Guide

> Danh sách tất cả hình cần thay, phân loại theo mức độ ưu tiên.
> Dùng ChatGPT web: upload hình gốc + paste prompt → download hình mới → thay file.

## Cách dùng

1. Mở [chatgpt.com](https://chatgpt.com)
2. Upload hình gốc (từ folder `images/` tương ứng)
3. Paste prompt bên dưới
4. Download hình gen ra → overwrite file cũ (giữ đúng filename)
5. Chạy `node design/screenshot-all.js` để audit

---

## P0: PHẢI THAY (có branding Mailchimp/Intuit rõ ràng)

### 1. `images/features/switch-brands.png`
**Hiện tại:** Logo "INTUIT mailchimp" khổng lồ + Klaviyo, HubSpot, Constant Contact
**Đã xử lý:** Thay bằng gradient placeholder trong code (không hiển thị trên site)
**Nếu muốn hình thật:**
> Upload hình này. Recreate with same composition: show migration flow from other email platforms (Mailchimp, Sendinblue, ActiveCampaign) converging into AcelleMail. Use blue color scheme (#2563EB). Remove all Intuit/Mailchimp branding. Clean, professional style. Same dimensions.

### 2. `images/features/pricing-hero.png`
**Hiện tại:** Chữ "mailchimp" trên nền vàng, 2 người ngồi làm việc
**Đã xử lý:** Thay bằng gradient placeholder trong code
**Nếu muốn hình thật:**
> Upload hình này. Recreate with same composition and style: two people working at a desk, professional setting. Change yellow background to blue (#4A90D9). Remove all Mailchimp text/branding. Keep the professional, casual work atmosphere. Same dimensions.

### 3. `images/about/intuit-family.png`
**Hiện tại:** Logo Intuit + TurboTax + CreditKarma + QuickBooks + Mailchimp
**Đã xử lý:** Không dùng trên site nữa (đã remove section Intuit family)
**Action:** Có thể xóa file này.

### 4. `images/hero/home-hero-alt.png`
**Hiện tại:** Solid yellow (#FFE01B) — màu Mailchimp
**Action:** Đổi thành solid blue hoặc gen hình mới
> Upload hình này. This is a solid color background. Create a similar solid color image but in blue (#4A90D9) instead of yellow. Same dimensions (1200x800).

---

## P1: NÊN THAY (có UI Mailchimp nhưng không có text "Mailchimp")

### 5. `images/hero/home-hero.png`
**Hiện tại:** Mailchimp email builder UI — hiển thị brand "Tandu", Canva, Wix logos
**Prompt:**
> Upload hình này. Recreate with same composition: email marketing platform UI showing an email being designed. Change the color scheme to blue (#2563EB primary, #4A90D9 accent). Remove Canva/Wix logos. Show a generic email builder with drag-and-drop interface. Professional, clean UI style. Same dimensions.

### 6. `images/hero/automation-hero.png`
**Hiện tại:** Laptop showing Mailchimp automation flow UI (yellow/teal accents, Mailchimp monkey icon)
**Prompt:**
> Upload hình này. Recreate with same composition: laptop on desk showing email automation workflow builder. Change the UI colors from yellow/teal to blue (#2563EB). Remove the monkey/chimp icon. Keep the flow diagram with nodes and connections visible. Professional photography style. Same dimensions.

### 7. `images/features/automation-flows.png`
**Hiện tại:** Mailchimp automation flow UI (monkey icon visible, yellow/teal theme)
**Prompt:**
> Upload hình này. Recreate with same layout: marketing automation flow diagram with triggers, conditions, delays, and email send actions. Change color scheme to blue (#2563EB). Remove any monkey/chimp icons. Keep the professional flowchart style. Same dimensions.

### 8. `images/features/email-templates.png`
**Hiện tại:** Email templates grid showing "Assembly" brand, dark/warm theme
**Prompt:**
> Upload hình này. Recreate with same composition: grid of email templates showing different designs. Change the dark/warm color scheme to a clean blue/white theme. Keep showing multiple email template previews. Professional email marketing tool UI. Same dimensions.

### 9. `images/features/campaign-manager.png`
**Hiện tại:** Email campaign scheduler showing "Assembly" brand, yellow accent
**Prompt:**
> Upload hình này. Recreate with same composition: email campaign scheduler/calendar interface. Change yellow accent to blue (#2563EB). Remove "Assembly" brand. Show a clean campaign management UI. Same dimensions.

### 10. `images/features/email-sms.png`
**Hiện tại:** SMS + email UI with Shopify bag icon, green accents
**Prompt:**
> Upload hình này. Recreate with same composition: email campaign interface showing a promotional email being composed. Change green/Shopify theme to blue (#2563EB). Remove Shopify bag icon. Show email builder + preview side by side. Same dimensions.

### 11. `images/features/automations-ecom.png`
**Hiện tại:** Shopify popup + automation flow (green Shopify bag, "Kaylin Pickles" brand)
**Prompt:**
> Upload hình này. Recreate with same composition: email automation flow triggered by subscriber action. Remove Shopify branding and green colors. Change to blue (#2563EB) theme. Show: trigger event → condition → send email flow. Same dimensions.

### 12. `images/features/segmentation.png`
**Hiện tại:** Laptop showing "Predictive Segmentation" UI (Mailchimp teal accent, monkey icon)
**Prompt:**
> Upload hình này. Recreate with same composition: laptop showing audience segmentation interface with likelihood segments. Change teal/yellow to blue (#2563EB). Remove monkey icon. Keep the clean data visualization style. Same dimensions.

### 13. `images/features/predictive.png`
**Hiện tại:** "Conversion insights" dashboard (yellow Mailchimp accent, blue background)
**Prompt:**
> Upload hình này. Recreate with same composition: conversion insights dashboard showing customer analytics, bar chart, and "Send email" CTA. Keep the blue background but change yellow accent to white/light blue. Remove Mailchimp monkey icon. Same dimensions.

### 14. `images/features/content-studio.png`
**Hiện tại:** Laptop showing "Content Studio" with "Tandu" brand photos
**Prompt:**
> Upload hình này. Recreate with same composition: laptop showing a media/content library interface with uploaded images in a grid. Change teal accent to blue (#2563EB). Remove "Tandu" brand. Show generic professional photos. Same dimensions.

### 15. `images/features/landing-pages.png`
**Hiện tại:** Laptop showing "Landing Page Builder" with "Tandu" brand (Mailchimp teal accent)
**Prompt:**
> Upload hình này. Recreate with same composition: laptop showing a landing page builder with style controls. Change teal/yellow to blue (#2563EB). Remove "Tandu" brand. Show a generic landing page being edited. Same dimensions.

### 16. `images/features/websites.png`
**Hiện tại:** "FLEXSTART" website builder UI (orange/teal theme)
**Prompt:**
> Upload hình này. Recreate with same composition: website builder showing a live website with section style editor. Change orange/teal to blue (#2563EB). Remove "FLEXSTART" brand. Show a professional website being built. Same dimensions.

### 17. `images/features/integrations-auto.png`
**Hiện tại:** Email + Shopify automation flow (Shopify bag icon, orange/green)
**Prompt:**
> Upload hình này. Recreate with same composition: email automation triggered by customer behavior, showing email preview + flow diagram. Remove Shopify icon, change orange to blue (#2563EB). Keep the clean flowchart style. Same dimensions.

### 18. `images/features/whats-new.png`
**Hiện tại:** Woman in colorful shirt, "The Latest" badge (Mailchimp yellow)
**Prompt:**
> Upload hình này. Recreate with same composition: professional woman smiling, holding a tablet/laptop, in an office setting. Change the yellow badge to blue (#2563EB). Modern, friendly, professional photography. Same dimensions.

---

## P2: TÙY CHỌN (generic, có thể giữ nguyên)

Các hình sau KHÔNG có branding Mailchimp rõ ràng, có thể giữ nguyên hoặc thay nếu muốn.

| File | Hiện tại | Giữ/Thay |
|------|---------|----------|
| `images/hero/about-hero.jpg` | Team photo (3 people, blue jackets) | Giữ OK |
| `images/hero/help-hero.png` | Black & white illustration (bird + person) | Giữ OK — artistic style |
| `images/features/case-study.png` | Photo 2 women (no branding) | Giữ OK |
| `images/features/customer-journey.png` | Simple flow diagram (no branding) | Giữ OK |
| `images/features/onboarding.png` | Woman on phone at desk | Giữ OK |
| `images/features/experts.png` | Office/coworking space photo | Giữ OK |
| `images/features/customer-success.png` | Hands on table, meeting | Giữ OK |
| `images/about/office.png` | Office building exterior (night) | Giữ OK |
| `images/about/newsroom.jpg` | Small photo (generic) | Giữ OK |
| `images/about/why-acellemail.jpg` | Small photo (generic) | Giữ OK |
| `images/about/whats-new.png` | Small thumbnail | Giữ OK |
| `images/help/contact-support.png` | Pencil illustration (B&W) | Giữ OK — artistic |
| `images/help/expert-help.png` | Characters illustration (B&W) | Giữ OK — artistic |

---

## P3: BLANK/PLACEHOLDER (solid colors — tùy ý)

Các hình này chỉ là solid colors, có thể giữ hoặc thay bằng hình thật:

| File | Color | Action |
|------|-------|--------|
| `images/features/ab-testing.png` | Solid light teal (#D5F0EE) | Giữ hoặc gen A/B test UI |
| `images/features/social-posting.png` | Solid cream (#F0EAD6) | Giữ hoặc gen social UI |
| `images/features/surveys.png` | Solid lavender (#D8D4EA) | Giữ hoặc gen survey UI |
| `images/features/analytics.png` | Solid light green (#DEECD5) | Giữ hoặc gen analytics UI |

---

## Tổng kết

| Priority | Số hình | Mô tả |
|----------|---------|-------|
| **P0** | 4 | Branding Mailchimp/Intuit rõ — 2 đã placeholder, 1 đã remove, 1 solid color |
| **P1** | 14 | UI Mailchimp (monkey icon, yellow/teal, Shopify) — nên gen lại |
| **P2** | 13 | Generic photos/illustrations — giữ nguyên OK |
| **P3** | 4 | Solid colors — tùy ý |
| **Total** | 35 | |

**Recommend:** Gen lại P0 (4) + P1 (14) = **18 hình** qua ChatGPT web.
