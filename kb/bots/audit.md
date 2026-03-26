# AcelleMail KB — Audit Bot

> Screenshot all KB pages (desktop + mobile) for visual QA.

## Usage

```bash
kb/bots/audit.md                # Screenshot all pages
kb/bots/audit.md review         # Screenshot + review issues
```

## Pages to Audit

```bash
CHROME="/Applications/Google Chrome.app/Contents/MacOS/Google Chrome"
BASE="https://knowledge.acellemail.com"
DIR="kb/design/audit_$(date +%Y%m%d_%H%M)"
mkdir -p "$DIR"

# Home
"$CHROME" --headless --disable-gpu --screenshot="$DIR/home_desktop.png" --window-size=1440,5000 "$BASE" 2>/dev/null
"$CHROME" --headless --disable-gpu --screenshot="$DIR/home_mobile.png" --window-size=430,5000 "$BASE" 2>/dev/null

# Article detail
"$CHROME" --headless --disable-gpu --screenshot="$DIR/article_desktop.png" --window-size=1440,5000 "$BASE/articles/creating-your-first-email-campaign-in-acellemail" 2>/dev/null
"$CHROME" --headless --disable-gpu --screenshot="$DIR/article_mobile.png" --window-size=430,5000 "$BASE/articles/creating-your-first-email-campaign-in-acellemail" 2>/dev/null

# Category
"$CHROME" --headless --disable-gpu --screenshot="$DIR/category_desktop.png" --window-size=1440,3000 "$BASE/category/email-marketing" 2>/dev/null

# Search
"$CHROME" --headless --disable-gpu --screenshot="$DIR/search_desktop.png" --window-size=1440,3000 "$BASE/search?q=smtp" 2>/dev/null

# Tag
"$CHROME" --headless --disable-gpu --screenshot="$DIR/tag_desktop.png" --window-size=1440,3000 "$BASE/tag/spf" 2>/dev/null

# Admin
"$CHROME" --headless --disable-gpu --screenshot="$DIR/admin_desktop.png" --window-size=1440,2000 "$BASE/admin/articles" 2>/dev/null

echo "Screenshots saved to $DIR"
```

## Review Checklist

- [ ] Logo renders correctly (KB icon + AcelleMail wordmark)
- [ ] Categories dropdown shows grouped items (no emojis)
- [ ] Category cards show color dots (no emojis)
- [ ] Pagination arrows are small (not giant SVGs)
- [ ] Article detail: TOC sidebar renders, code blocks highlighted
- [ ] Search returns results
- [ ] All internal links work (no 404s)
- [ ] Links to acellemail.com work
- [ ] Responsive on mobile
- [ ] Footer renders correctly
