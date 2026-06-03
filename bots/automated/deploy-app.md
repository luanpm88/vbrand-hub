# Bot: Deploy Brand App

Deploy acelle app with brand plugin to production server.

## Cách dùng

```
bots/automated/deploy-app.md
```

## SSH accounts

- `vbrand@54.169.34.13` — deploy operations (server migration 2026-05-24; old IP `18.141.199.175` retired)
- Production app path: `/home/vbrand/app` (symlink → /home/vbrand/app-new; legacy real dir at /home/vbrand/app-legacy)
- Branch: `main` (acelle mainline); plugin source: acelle_brand repo

## Flow

### Bước 1: Pre-flight check

So sánh local vs server:

```bash
# Local
cd /Users/luan/apps/acelle
echo "=== Local acelle (branch: $(git branch --show-current)) ==="
git log --oneline -3
echo "=== Unpushed commits ==="
git log origin/main..main --oneline 2>/dev/null || echo "None"
```

```bash
# Server
ssh vbrand@54.169.34.13 "ls -la /home/vbrand/app && echo '(symlink; see /home/vbrand/app-new for current release)'"
```

Nếu có unpushed commits → cảnh báo: "Có commits chưa push. Push trước: `cd /Users/luan/apps/acelle && git push origin main`"

### Bước 2: Deploy

Build a new release alongside the current one, prepare it, then atomically swap the symlink:

```bash
# On server: build release at /home/vbrand/app-new (or next build dir), composer install --no-dev, set .env (DB_DATABASE=brand, etc.), php artisan migrate:fresh, then: rm /home/vbrand/app && ln -sfn /home/vbrand/app-new /home/vbrand/app
```

Build and prepare release at /home/vbrand/app-new with: composer install --no-dev, php artisan migrate:fresh, php artisan db:seed --class DatabaseInit, php artisan db:seed --class TemplateSeeder, php artisan config:cache. Then symlink swap.

> **Tại sao không git pull trên server?**
> The new deployment builds the release offline (at /home/vbrand/app-new), tests it, then atomically swaps the symlink—no git pull on server, no cleanup needed.

> ⚠️ **Quan trọng — Cache rules:**
> During offline release build: use `config:cache` (opcache.validate_timestamps=On ensures revalidation), skip `route:cache` (not needed for /rui/* routes under acelle's dynamic routing). No conditional brand route loading; brand features are activated via the acelle/brand plugin status.

### Bước 3: Verify

```bash
ssh vbrand@54.169.34.13 "
ls -la /home/vbrand/app && readlink /home/vbrand/app && echo '=== Release ===' && ls -la /home/vbrand/app-new && php /home/vbrand/app/artisan --version
"
```

### Output

```
✅ Acelle app (with brand plugin) deployed!
- Server: vbrand@54.169.34.13:/home/vbrand/app (symlink → /home/vbrand/app-new)
- Mainline branch: main
- Commit: <hash> — <message>
- Deployed at: <timestamp>
```
