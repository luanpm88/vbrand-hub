# AcelleMail Landing — Audit Bot

> Screenshot all pages (desktop + mobile) from the Laravel landing site for visual QA.
> Saves to `acellemail/design/versions/audit_N/`

## Usage

```
acellemail/bots/audit.md
acellemail/bots/audit.md review    # screenshot + review issues
```

## What it does

1. Auto-detect next audit number (audit_14 → audit_15, etc.)
2. Screenshot all 10 pages at 2 viewports:
   - **Desktop**: 1440×10000 (full page)
   - **Mobile**: 430×10000 (iPhone 14 Pro width, full page)
3. Save to `acellemail/design/versions/audit_N/`
4. If `review` mode: read each screenshot and list UI issues

## Pages

| Route | URL |
|-------|-----|
| home | https://acellemail.com |
| features | https://acellemail.com/features |
| pricing | https://acellemail.com/pricing |
| email-marketing | https://acellemail.com/email-marketing |
| automation | https://acellemail.com/automation |
| integrations | https://acellemail.com/integrations |
| security | https://acellemail.com/security |
| about | https://acellemail.com/about |
| help | https://acellemail.com/help |
| contact | https://acellemail.com/contact |

## Steps

### 1. Determine audit number

```bash
# Find highest existing audit_N and increment
LATEST=$(ls -d acellemail/design/versions/audit_* 2>/dev/null | sort -t_ -k2 -n | tail -1 | grep -o '[0-9]*$')
NEXT=$((LATEST + 1))
DIR="acellemail/design/versions/audit_${NEXT}"
mkdir -p "$DIR"
echo "Creating audit_${NEXT}"
```

### 2. Screenshot all pages

```bash
CHROME="/Applications/Google Chrome.app/Contents/MacOS/Google Chrome"
BASE="https://acellemail.com"
PAGES="home features pricing email-marketing automation integrations security about help contact"

for page in $PAGES; do
  url="$BASE/$page"
  if [ "$page" = "home" ]; then url="$BASE"; fi

  "$CHROME" --headless --disable-gpu \
    --screenshot="$DIR/${page}_desktop.png" \
    --window-size=1440,10000 "$url" 2>/dev/null

  "$CHROME" --headless --disable-gpu \
    --screenshot="$DIR/${page}_mobile.png" \
    --window-size=430,10000 "$url" 2>/dev/null

  echo "✓ $page"
done

echo "Total: $(ls $DIR/*.png | wc -l) screenshots in audit_${NEXT}"
```

### 3. Review (optional)

If `review` mode requested:
- Read each screenshot image
- Check for: broken layouts, missing images, text overflow, color inconsistencies, spacing issues, mobile responsiveness
- List issues found with severity (P0/P1/P2)
- Create `$DIR/REVIEW.md` with findings

## Local testing

To audit from local dev server instead of production:

```bash
# Start Laravel dev server
cd acellemail/landing && php artisan serve --port=8088 &

# Then use BASE="http://127.0.0.1:8088" instead
```

## File structure

```
acellemail/design/versions/
├── audit_1/          ← earliest
├── ...
├── audit_14/         ← current (Laravel migration)
│   ├── home_desktop.png
│   ├── home_mobile.png
│   ├── features_desktop.png
│   ├── features_mobile.png
│   ├── ...
│   └── contact_mobile.png
└── audit_N/          ← next audit
```
