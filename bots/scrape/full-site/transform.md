# Site Transform Bot — Rebrand Clone → New Brand

> Bot biến site clone thành site mới cho brand khác.
> Giữ nguyên layout/design, thay toàn bộ content/branding.
> Input: source site + brand info. Output: site mới sẵn sàng launch.

## Usage

```bash
# Transform site clone sang brand mới
bots/scrape/full-site/transform.md mailchimp > acellemail

# Transform với brand info file có sẵn
bots/scrape/full-site/transform.md mailchimp > newbrand --info brand-info.md

# Chỉ audit site đã transform
bots/scrape/full-site/transform.md audit acellemail

# Tiếp tục audit loop
bots/scrape/full-site/transform.md audit acellemail --continue
```

### Khi user nói ngắn gọn

| User nói | Claude làm |
|----------|-----------|
| `transform mailchimp > acellemail` | Chạy full transform flow |
| `biến mailchimp thành brand X` | Hỏi info → transform |
| `rebrand mailchimp cho Y` | Hỏi info → transform |
| `audit acellemail` | Screenshot + review |
| `tiếp tục audit` | Audit loop tiếp |

---

## Phase 0: Gather Brand Info (HỎI USER)

**QUAN TRỌNG:** Hỏi user TẤT CẢ thông tin cần thiết TRƯỚC khi bắt đầu transform.

### Câu hỏi bắt buộc

```
1. TÊN BRAND: _______
   (tên hiển thị trên site, ví dụ: "AcelleMail")

2. MÔ TẢ NGẮN: _______
   (1 câu mô tả sản phẩm, ví dụ: "Self-hosted email marketing platform")

3. COLOR PALETTE:
   - Primary: #_______ (accent chính)
   - CTA: #_______ (buttons, links)
   - Background: #_______ (page bg)
   - Text: #_______ (body text)
   → Hoặc: "giữ nguyên màu gốc" / "đổi sang blue professional" / "dark theme"

4. LOGO FILES:
   - Logo dark (cho header): _______
   - Logo light (cho footer): _______
   → Nếu chưa có: tạm dùng text logo, user thay sau

5. KEY LINKS:
   - Buy/CTA URL: _______
   - Demo URL: _______
   - Docs URL: _______
   - Support email: _______

6. PRICING (nếu có trang pricing):
   - Bao nhiêu plans? Tên + giá + features chính
   - One-time hay subscription?

7. PRODUCT FEATURES:
   - 5-10 key features (1 câu mỗi feature)
   - Unique selling points vs competitors

8. INTEGRATIONS/PARTNERS (nếu relevant):
   - Danh sách services/tools tích hợp
   - Grouped by category

9. SOCIAL LINKS: _______
   (facebook, twitter/x, linkedin, youtube, etc.)

10. LEGAL INFO:
    - Company name cho copyright: _______
    - Year founded: _______
```

### Câu hỏi tùy chọn

```
- Font preference? (giữ font gốc / đổi sang ______)
- Tone of voice? (professional / casual / technical / friendly)
- Target audience? (developers / businesses / agencies / consumers)
- Competitor mentions OK? (ví dụ: "save $12K vs Mailchimp")
```

**Sau khi có đủ info → lưu vào `{NEW_BRAND}/{NEW_BRAND}.md` → bắt đầu transform.**

---

## Phase 1: Copy Source Site

**QUAN TRỌNG: cp -f, KHÔNG rename/move source.**

```bash
# Copy toàn bộ source sang folder mới
cp -rf bots/scrape/full-site/sites/{SOURCE}/ bots/scrape/full-site/sites/{NEW_BRAND}/

# Init git riêng cho site mới
cd bots/scrape/full-site/sites/{NEW_BRAND}
git init

# Đảm bảo .gitignore
cat > .gitignore << 'EOF'
node_modules/
design/versions/
design/reference/
.DS_Store
EOF

# Commit bản gốc (để có thể diff/revert)
git add -A
git commit -m "Initial copy from {SOURCE} clone"
```

**Verify:** Source site vẫn nguyên, site mới là bản copy độc lập.

---

## Phase 2: CSS Color System

**File:** `css/style.css` → chỉ sửa `:root` variables

```css
/* Map old values → new values */
:root {
  --{prefix}-primary:     OLD → NEW;
  --{prefix}-cta:         OLD → NEW;
  --{prefix}-cta-hover:   OLD → NEW;
  --{prefix}-bg:          OLD → NEW;
  --{prefix}-text:        OLD → NEW;
  --{prefix}-light-bg:    OLD → NEW;
  --{prefix}-border:      OLD → NEW;
}
```

**KHÔNG đổi tên CSS variables** — chỉ đổi giá trị. Đổi tên sẽ break hàng ngàn dòng CSS.

**Cũng fix:**
- Hardcoded colors trong inline styles của PHP files (grep `#OLD_HEX`)
- SVG fill/stroke colors trong PHP files
- `meta theme-color` trong `_header.php`
- CSS shadow `rgba()` values nếu dùng old color

```bash
# Tìm tất cả hardcoded colors cần đổi
grep -rn "#OLD_HEX" *.php css/style.css
```

---

## Phase 3: Header & Footer

### `_header.php`

Checklist:
- [ ] Remove/replace top bar nếu specific to source brand (ví dụ: "Intuit" bar)
- [ ] Update `<title>` và `<meta description>`
- [ ] Update `<meta theme-color>`
- [ ] Replace logo `<img src>` → new logo file
- [ ] Update promo banner text + link
- [ ] Update CTA buttons text + href ("Buy Now" → new URL)
- [ ] Update "Login" / "Demo" link → new URL
- [ ] Update mobile nav links (same as desktop)
- [ ] Update Google Fonts `<link>` nếu đổi font

### `_footer.php`

Checklist:
- [ ] Update footer CTA heading + text
- [ ] Update footer logo
- [ ] Update 5-column link text + hrefs
- [ ] Remove brand-specific sections (ví dụ: "Mailchimp Presents")
- [ ] Update social links
- [ ] Update copyright: `© YEAR–{CURRENT_YEAR} {BRAND}. All Rights Reserved.`
- [ ] Update legal links
- [ ] Remove irrelevant badges (app store links, etc.)

---

## Phase 4: Page-by-Page Content Rewrite

### Workflow cho mỗi page:

1. **Đọc page gốc** — hiểu structure/sections
2. **Map content** — source concept → new brand equivalent
3. **Rewrite text** — headings, paragraphs, CTAs, list items
4. **Update links** — internal (.php) giữ nguyên, external → new URLs
5. **Update image alt text** — match new content
6. **Fix review/trust badges** — match new brand's review platform

### Content mapping table (fill in per project):

| Source Concept | → New Brand Equivalent |
|----------------|----------------------|
| "Start Free Trial" | → "{NEW CTA TEXT}" |
| "{SOURCE} plans" | → "{BRAND} plans" |
| Competitor comparison | → New competitors |
| Trust badges (G2, Capterra) | → New review platform |
| Customer logos | → New customer/partner logos |
| Case study quotes | → New testimonials |
| Stats (users, revenue) | → New stats |

### Pages thường cần rewrite nhiều nhất:
1. **Homepage** — hero, stats, feature overview, CTA
2. **Pricing** — plans, comparison table, FAQ
3. **About** — company story, team, philosophy
4. **Contact** — address, email, support options

### Pages thường chỉ cần text swap:
5. **Features** — feature names/descriptions
6. **Integrations** — integration list + logos
7. **Security** — security/compliance messaging
8. **Help** — help topics + doc links

### Chạy rewrite song song

Dùng multiple agents để rewrite nhiều pages cùng lúc:
- Agent 1: index.php + pricing.php (heavy rewrite)
- Agent 2: about.php + contact.php (narrative rewrite)
- Agent 3: features.php + email-marketing.php + automation.php
- Agent 4: integrations.php + security.php + help.php

---

## Phase 5: Fix Logos & Badges

### Integration/Partner Logos

Source site thường dùng logos của các brands khác (Shopify, Salesforce, etc.).
New brand cần logos phù hợp với SẢN PHẨM MỚI.

**KHÔNG dùng logo sai brand** (ví dụ: Shopify icon cho Amazon SES).

**Giải pháp:** Thay `<img>` bằng inline SVG letter badges:

```html
<svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
  <rect width="40" height="40" rx="8" fill="#FF9900"/>
  <text x="20" y="26" text-anchor="middle" fill="white"
        font-family="Inter,sans-serif" font-weight="700" font-size="18">S</text>
</svg>
```

Cho 32px version: width/height=32, rx=6, y=22, font-size=14.

**Giữ nguyên** logos mà vẫn đúng brand (ví dụ: Stripe, WordPress, WooCommerce nếu relevant).

### Customer/Trust Logos

Source site có customer logos (Spotify, Gap, etc.) → thay bằng:
- Text spans nếu chưa có logo mới: `<span style="font-weight:700;color:#64748B;">Brand Name</span>`
- Hoặc SVG letter badges
- User thay bằng logo thật sau

### Review Badges

Source review badges (Capterra, G2, TrustRadius) → thay bằng badges phù hợp:
- CodeCanyon/Envato (cho products bán trên Envato)
- Product Hunt, GitHub Stars (cho open source)
- App Store ratings (cho mobile apps)
- Hoặc text-based badges

---

## Phase 6: Handle Images

### QUAN TRỌNG: KHÔNG dùng DALL-E API / AI gen tự động

**Lý do:** AI-generated images trông ảo, text bị méo, phá layout gốc. Phải dùng ChatGPT web (upload hình gốc làm reference) để giữ đúng style.

### Bước 1: Phân loại tất cả hình

Mở TỪNG hình trong `images/` và phân loại:

| Priority | Tiêu chí | Action |
|----------|---------|--------|
| **P0** | Có logo/tên source brand RÕ RÀNG (text "Mailchimp", logo monkey, "Intuit") | Thay gradient placeholder trong code + gen lại qua ChatGPT web |
| **P1** | Có UI source brand (icon, color scheme đặc trưng, brand fonts) nhưng KHÔNG có text brand name | Gen lại qua ChatGPT web (upload gốc + prompt đổi màu/icon) |
| **P2** | Generic (photos người, office, illustrations, icons không branding) | **Giữ nguyên** |
| **P3** | Solid color placeholders (chỉ là 1 màu) | Giữ hoặc gen UI screenshot nếu cần |

### Bước 2: Xử lý P0 (branding rõ ràng)

Trong code PHP, thay `<img>` bằng gradient placeholder:

```html
<div style="width:100%; aspect-ratio:16/9;
     background:linear-gradient(135deg, #PRIMARY, #LIGHTER);
     border-radius:12px; display:flex; align-items:center;
     justify-content:center; color:white;
     font-family:var(--font-serif); font-size:24px;">
  Placeholder Text
</div>
```

### Bước 3: Tạo IMAGE_REPLACEMENT_GUIDE.md

**BẮT BUỘC** sau khi transform xong, tạo file `design/IMAGE_REPLACEMENT_GUIDE.md` liệt kê:

1. **Tất cả hình P0 + P1** cần gen lại
2. **Prompt cụ thể** cho từng hình để user paste vào ChatGPT web
3. **Phân loại P2 + P3** để user biết hình nào giữ được

Format cho mỗi hình cần gen:

```markdown
### {N}. `images/{path}/{filename}`
**Hiện tại:** Mô tả ngắn hình gốc (brand nào, style gì, vấn đề gì)
**Prompt:**
> Upload hình này. Recreate with same composition and style but:
> - Change color scheme from {OLD_COLORS} to {NEW_COLORS}
> - Remove {SOURCE_BRAND} branding/icons
> - Theme: {NEW_BRAND_DESCRIPTION}
> - Keep same dimensions and layout
```

### Bước 4: User gen hình qua ChatGPT web

Hướng dẫn user:
1. Mở [chatgpt.com](https://chatgpt.com)
2. Upload hình gốc từ folder images/
3. Paste prompt từ IMAGE_REPLACEMENT_GUIDE.md
4. Download hình mới → overwrite file cũ (giữ đúng filename)
5. Chạy `node design/screenshot-all.js` để audit

### Lưu ý quan trọng về hình

- **KHÔNG xóa hình gốc** trước khi có hình thay thế — gradient placeholder xấu hơn hình gốc
- **Giữ đúng filename** khi thay hình — code PHP reference theo filename
- **Giữ đúng aspect ratio** — layout phụ thuộc tỷ lệ hình
- **Hình gốc source vẫn nằm trong git** — có thể restore bất cứ lúc nào

---

## Phase 7: Audit Loop

### Quy trình audit:

```bash
# Chạy PHP server
php -S localhost:8888 &

# Screenshot tất cả pages
node design/screenshot-all.js

# Review từng screenshot
```

### Audit checklist:

- [ ] **Branding:** Không còn tên/logo source brand
- [ ] **Colors:** Đúng palette mới
- [ ] **Links:** Tất cả external links → new URLs
- [ ] **Images:** Không có hình chứa branding source
- [ ] **Logos:** Integration/partner logos đúng tên
- [ ] **Review badges:** Đúng platform
- [ ] **Text:** Không còn tên source brand (trừ competitive mentions có chủ đích)
- [ ] **Footer:** Copyright, legal links cập nhật
- [ ] **Mobile:** Layout responsive, menu hoạt động
- [ ] **Layout:** Không bị vỡ do placeholder thay hình

### Grep audit:

```bash
# Kiểm tra không còn source brand name
grep -ri "{SOURCE_BRAND}" *.php _*.php
grep -ri "{SOURCE_COMPANY}" *.php _*.php  # ví dụ: "Intuit"

# Kiểm tra image references đúng
grep -rn "img src" *.php | grep -v "logo_dark\|logo_light\|social/\|icons/"
```

### Audit loop:

```
Audit N   → Screenshot → Review → Tìm issues
Audit N+1 → Fix issues → Re-screenshot → Review
Audit N+2 → Fix remaining → Re-screenshot
...
Audit FINAL → Clean, no issues → Ready to launch
```

Mỗi audit lưu: `design/versions/audit_N/`

---

## Phase 8: Finalize

### Commit site mới:

```bash
cd bots/scrape/full-site/sites/{NEW_BRAND}
git add -A
git commit -m "Transform complete: {SOURCE} → {NEW_BRAND}"
```

### Update case notes:

Cập nhật `{NEW_BRAND}.md` với:
- Brand info đã dùng
- Color mapping (old → new)
- Image status (replaced / placeholder / keep original)
- Audit history
- Lessons learned
- Prompt list cho ChatGPT image generation

### Checklist cuối:

- [ ] Tất cả pages load không lỗi
- [ ] Grep audit clean (không còn source brand name ngoài ý muốn)
- [ ] Integration logos đúng
- [ ] Review badges đúng platform
- [ ] Footer copyright/legal cập nhật
- [ ] Mobile responsive OK
- [ ] FAQ accordion hoạt động
- [ ] Case notes `{NEW_BRAND}.md` cập nhật đầy đủ
- [ ] Git commit với message rõ ràng

---

## Lessons Learned (từ AcelleMail transform)

1. **KHÔNG dùng DALL-E gen images** — quá ảo, text méo, phá layout. Giữ hình gốc + user gen qua ChatGPT web
2. **Logo integrations phải đúng brand** — Shopify icon cho Amazon SES = sai. Dùng SVG letter badges
3. **KHÔNG đổi tên CSS variables** — chỉ đổi giá trị. `--mc-*` → giữ prefix, đổi hex
4. **cp -f source, KHÔNG rename** — giữ source nguyên, site mới là bản copy độc lập
5. **Hình có branding source → gradient placeholder** — user thay sau qua ChatGPT
6. **Review badges phải match brand** — Capterra/G2 cho Mailchimp ≠ Envato cho CodeCanyon product
7. **Customer logos phải match product** — Spotify/Gap dùng Mailchimp ≠ Amazon SES/SendGrid dùng AcelleMail
8. **Grep audit sau mỗi round** — dễ sót tên brand cũ trong text, alt text, image filenames
9. **Chạy pages rewrite song song** — dùng multiple agents, tiết kiệm thời gian
10. **Init git riêng cho site mới** — track changes, dễ revert

---

## File Map

```
bots/scrape/full-site/
├── scraper.md              ← Clone website bot
├── transform.md            ← THIS FILE — transform bot
└── sites/
    ├── mailchimp/          ← Source clone (giữ nguyên)
    ├── acellemail/         ← Transformed from mailchimp
    ├── {source}/           ← Other source clones
    └── {new-brand}/        ← Transformed sites
```
