# AcelleMail KB — Deploy & Update

> Deploy/update the Knowledge Base Laravel site to production.

## Quick Deploy

```bash
# From vbrand workspace root
rsync -avz --rsync-path="sudo rsync" \
  --exclude='.git/' --exclude='vendor/' --exclude='node_modules/' \
  --exclude='.env' --exclude='.DS_Store' --exclude='database/database.sqlite' \
  --exclude='storage/logs/*' --exclude='storage/framework/cache/*' \
  --exclude='storage/framework/sessions/*' --exclude='storage/framework/views/*' \
  kb/ brandnew:/var/www/acelle-kb/

ssh brandnew "cd /var/www/acelle-kb && \
  sudo -u vbrandwww composer install --no-dev --optimize-autoloader && \
  sudo -u vbrandwww php artisan config:cache && \
  sudo -u vbrandwww php artisan route:cache && \
  sudo -u vbrandwww php artisan view:cache && \
  sudo chown -R vbrandwww:vbrand /var/www/acelle-kb"
```

## Deploy Views Only (fast)

```bash
rsync -avz --rsync-path="sudo rsync" \
  kb/resources/views/ brandnew:/var/www/acelle-kb/resources/views/

ssh brandnew "cd /var/www/acelle-kb && \
  sudo -u vbrandwww php artisan view:cache && \
  sudo chown -R vbrandwww:vbrand /var/www/acelle-kb/resources"
```

## Re-seed Database

```bash
ssh brandnew "cd /var/www/acelle-kb && \
  sudo -u vbrandwww php artisan migrate:fresh --seed --force && \
  sudo -u vbrandwww php artisan config:cache"
```

## Verify

```bash
curl -sI https://knowledge.acellemail.com
curl -s https://knowledge.acellemail.com | grep '<title>'
curl -sI https://knowledge.acellemail.com/articles/creating-your-first-email-campaign-in-acellemail
curl -sI https://knowledge.acellemail.com/category/email-marketing
curl -sI https://knowledge.acellemail.com/search?q=smtp
```

## Server Info

| Key | Value |
|-----|-------|
| SSH | `ssh brandnew` |
| Path | `/var/www/acelle-kb/` |
| Web Root | `/var/www/acelle-kb/public/` |
| URL | https://knowledge.acellemail.com |
| Owner | `vbrandwww:vbrand` |
| DB | SQLite at `database/database.sqlite` |
