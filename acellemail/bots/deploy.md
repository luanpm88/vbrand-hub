# AcelleMail Landing — Deploy & Update

> Deploy/update the AcelleMail Laravel landing site to production server.

## Pre-deploy E2E gate (BẮT BUỘC)

**Không được rsync lên prod nếu E2E chưa xanh.** Mọi thay đổi trong
`acellemail/landing/resources/`, `routes/web.php`, hoặc `public/` (css/js/images)
phải pass toàn bộ Playwright suite ở `acellemail/docs/e2e/` trước.

```bash
# 1. Start local Laravel server
cd acellemail/landing
php artisan serve --host=127.0.0.1 --port=8765 &
SERVE_PID=$!

# 2. Run E2E (desktop + mobile)
cd ../docs/e2e
BASE_URL=http://127.0.0.1:8765 npm test

# 3. Stop local server
kill $SERVE_PID
```

Chỉ khi tất cả specs xanh mới được tiếp tục `rsync` bên dưới.

**Sau deploy** — re-run suite trên URL production để verify:

```bash
cd acellemail/docs/e2e
BASE_URL=https://acellemail.com npm test
```

Nếu prod fail → rollback (git revert + redeploy) hoặc hotfix ngay.

Lần đầu setup: `cd acellemail/docs/e2e && npm install && npx playwright install chromium`.
Xem `acellemail/docs/e2e/README.md` để biết chi tiết.

## Architecture

```
acellemail/landing/          → Laravel 12 project
├── public/                  → Web root (css/, js/, images/)
├── resources/views/         → Blade templates
│   ├── layouts/app.blade.php
│   ├── partials/            → header, footer, promo-banner, mobile-nav
│   └── pages/               → home, features, pricing, etc.
├── routes/web.php           → All page routes
└── app/Http/Controllers/    → PageController
```

## Quick Deploy

```bash
# From vbrand workspace root
rsync -avz --chmod=Du=rwx,Dgo=rx,Fu=rw,Fgo=r --rsync-path="sudo rsync" \
  --exclude='.git/' \
  --exclude='node_modules/' \
  --exclude='vendor/' \
  --exclude='bootstrap/cache/*.php' \
  --exclude='storage/logs/*' \
  --exclude='storage/framework/cache/*' \
  --exclude='.env' \
  --exclude='.DS_Store' \
  acellemail/landing/ \
  brandnew:/var/www/acellemail-landing/

# Install deps + optimize on server
ssh brandnew "cd /var/www/acellemail-landing && \
  sudo rm -f bootstrap/cache/*.php && \
  sudo find public -type d -exec chmod 755 {} + && \
  sudo find public -type f -exec chmod 644 {} + && \
  sudo -u vbrandwww composer install --no-dev --optimize-autoloader && \
  sudo -u vbrandwww php artisan config:cache && \
  sudo -u vbrandwww php artisan route:cache && \
  sudo -u vbrandwww php artisan view:cache && \
  sudo chown -R vbrandwww:vbrand /var/www/acellemail-landing"
```

## Deploy Views Only (fast)

```bash
rsync -avz --chmod=Du=rwx,Dgo=rx,Fu=rw,Fgo=r --rsync-path="sudo rsync" \
  acellemail/landing/resources/views/ \
  brandnew:/var/www/acellemail-landing/resources/views/

ssh brandnew "cd /var/www/acellemail-landing && \
  sudo -u vbrandwww php artisan view:cache && \
  sudo chown -R vbrandwww:vbrand /var/www/acellemail-landing/resources"
```

## Deploy Assets Only (CSS/JS/images)

```bash
rsync -avz --chmod=Du=rwx,Dgo=rx,Fu=rw,Fgo=r --rsync-path="sudo rsync" \
  acellemail/landing/public/css/ \
  brandnew:/var/www/acellemail-landing/public/css/

rsync -avz --chmod=Du=rwx,Dgo=rx,Fu=rw,Fgo=r --rsync-path="sudo rsync" \
  acellemail/landing/public/images/ \
  brandnew:/var/www/acellemail-landing/public/images/

ssh brandnew "sudo chown -R vbrandwww:vbrand /var/www/acellemail-landing/public"
ssh brandnew "sudo find /var/www/acellemail-landing/public -type d -exec chmod 755 {} + && sudo find /var/www/acellemail-landing/public -type f -exec chmod 644 {} +"
```

## Verify After Deploy

```bash
# Check HTTP status
curl -sI https://acellemail.com

# Check page loads
curl -s https://acellemail.com | grep '<title>'

# Check specific pages (clean URLs, no .php)
curl -sI https://acellemail.com/pricing
curl -sI https://acellemail.com/features
curl -sI https://acellemail.com/automation
```

## First-Time Setup

```bash
# 1. Upload project
rsync -avz --chmod=Du=rwx,Dgo=rx,Fu=rw,Fgo=r --rsync-path="sudo rsync" \
  --exclude='.git/' --exclude='node_modules/' --exclude='.DS_Store' \
  --exclude='bootstrap/cache/*.php' \
  acellemail/landing/ \
  brandnew:/var/www/acellemail-landing/

# 2. Install deps
ssh brandnew "cd /var/www/acellemail-landing && \
  sudo rm -f bootstrap/cache/*.php && \
  sudo find public -type d -exec chmod 755 {} + && \
  sudo find public -type f -exec chmod 644 {} + && \
  sudo -u vbrandwww composer install --no-dev --optimize-autoloader"

# 3. Setup env
ssh brandnew "cd /var/www/acellemail-landing && \
  sudo -u vbrandwww cp .env.example .env && \
  sudo -u vbrandwww php artisan key:generate"

# 4. Optimize
ssh brandnew "cd /var/www/acellemail-landing && \
  sudo -u vbrandwww php artisan config:cache && \
  sudo -u vbrandwww php artisan route:cache && \
  sudo -u vbrandwww php artisan view:cache"

# 5. Fix ownership
ssh brandnew "sudo chown -R vbrandwww:vbrand /var/www/acellemail-landing"

# 6. Update nginx to point to public/
# root /var/www/acellemail-landing/public;
```

## Nginx Config

Update `/etc/nginx/sites-enabled/acellemail.com`:
```nginx
root /var/www/acellemail-landing/public;
```

## Server Info

| Key | Value |
|-----|-------|
| SSH | `ssh brandnew` |
| Path | `/var/www/acellemail-landing/` |
| Web Root | `/var/www/acellemail-landing/public/` |
| URL | https://acellemail.com |
| URL (www) | https://www.acellemail.com |
| URL (beta) | https://beta.acellemail.com |
| Owner | `vbrandwww:vbrand` |
