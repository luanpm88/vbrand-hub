# AcelleMail KB — Usage Guide

## Local Development

```bash
cd kb
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve --port=8090
```

Visit http://localhost:8090

## Admin Panel

**URL:** `/login`
**Credentials:** `admin@acellemail.com` / `123456@`

### Admin Features
- **Articles**: Create, edit, delete, preview. EasyMDE markdown editor with toolbar + HTML source mode.
- **Categories**: CRUD with color picker, group assignment, sort order.
- **Tags**: CRUD with article count display.
- **Dark/Light mode**: Toggle in top-right corner. Persists via localStorage.

## Deployment

### Quick Deploy
```bash
rsync -avz --rsync-path="sudo rsync" \
  --exclude='.git/' --exclude='vendor/' --exclude='node_modules/' \
  --exclude='.env' --exclude='.DS_Store' --exclude='database/database.sqlite' \
  --exclude='storage/logs/*' --exclude='storage/framework/cache/*' \
  --exclude='storage/framework/sessions/*' --exclude='storage/framework/views/*' \
  kb/ brandnew:/var/www/acelle-kb/

ssh brandnew "cd /var/www/acelle-kb && \
  sudo rm -f bootstrap/cache/services.php bootstrap/cache/packages.php && \
  sudo -u vbrandwww composer install --no-dev --optimize-autoloader && \
  sudo -u vbrandwww php artisan migrate --force && \
  sudo -u vbrandwww php artisan config:cache && \
  sudo -u vbrandwww php artisan route:cache && \
  sudo -u vbrandwww php artisan view:cache && \
  sudo chown -R vbrandwww:vbrand /var/www/acelle-kb"
```

### Re-seed (destructive)
```bash
ssh brandnew "cd /var/www/acelle-kb && \
  sudo -u vbrandwww php artisan migrate:fresh --seed --force"
```

### Deploy Views Only (fast)
```bash
rsync -avz --rsync-path="sudo rsync" \
  kb/resources/views/ brandnew:/var/www/acelle-kb/resources/views/
ssh brandnew "cd /var/www/acelle-kb && sudo -u vbrandwww php artisan view:cache"
```

## Running Tests
```bash
php artisan test
# or specific
php artisan test --filter=ArticleCrudTest
```

## Server Info

| Key | Value |
|-----|-------|
| Production URL | https://knowledge.acellemail.com |
| Admin URL | https://knowledge.acellemail.com/login |
| Server Path | /var/www/acelle-kb/ |
| SSH | ssh brandnew |
| Owner | vbrandwww:vbrand |
