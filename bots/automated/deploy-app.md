# Bot: Deploy Brand App

Deploy the acelle app + **both plugins** (`acelle/brand` **and** `acelle/messenger`)
to the BrandViet production server (https://app.sgconnect.vn).

`app.sgconnect.vn` officially runs acelle mainline + `acelle/brand` + `acelle/messenger`
(messenger went live on prod 2026-06-11). A normal "deploy brand prod" updates BOTH
plugins.

## Cách dùng

**Standard recurring deploy = the plugins (what changes day-to-day):**

```bash
bash ~/apps/vbrand/bots/automated/deploy-plugins.sh            # both plugins (default)
bash ~/apps/vbrand/bots/automated/deploy-plugins.sh messenger  # one plugin only
bash ~/apps/vbrand/bots/automated/deploy-plugins.sh brand
```

`deploy-plugins.sh` is idempotent and safe to re-run: per plugin it backs up the live
dir, rsyncs the full plugin source (`--delete`, prod's `vendor/` preserved), then
register → `vendor:publish` → `migrate --force` → activate, and finally
`config:cache` + verify (`artisan --version`, plugins active, `/login` 200,
`/rui/messenger/inbox` 302). Rollback a plugin:
`ssh vbrand@54.169.34.13 'cd /home/vbrand/app-new/storage/app/plugins/acelle && rm -rf <name> && mv <name>.bak-* <name>'`.

The full acelle host-app rebuild (below) is rarely needed — only for host (non-plugin)
code changes. Most prod work ships through the plugins.

## SSH accounts

- `vbrand@54.169.34.13` — deploy operations (server migration 2026-05-24; old IP `18.141.199.175` retired)
- Production app path: `/home/vbrand/app` (symlink → /home/vbrand/app-new; legacy real dir at /home/vbrand/app-legacy)
- Plugins on prod: `/home/vbrand/app-new/storage/app/plugins/acelle/{brand,messenger}`
- Branch: `main` (acelle mainline); plugin sources: `~/apps/acelle_brand`, `~/apps/acelle_messenger`

## ⚠️ Deploy the WHOLE plugin, never single files

2026-06-09 incident: rsyncing ONE messenger `ServiceProvider.php` across a version
skew fataled `php artisan` (a class existed locally but not on prod). Always deploy the
entire plugin so code + classes + migrations stay internally consistent — that's exactly
what `deploy-plugins.sh` does. If a plugin adds a NEW composer dependency, install it on
prod's plugin `vendor/` first (the rsync preserves prod `vendor/`).

## Messenger platform-app keys (Meta / Zalo)

The admin platform-app credentials live in the `messenger_settings` table, **encrypted
with the instance APP_KEY** — so you cannot copy ciphertext between instances. Transfer
DECRYPTED values and let the target re-encrypt: read locally via
`PlatformAppSettingsService` accessors, then on prod call `$svc->set(...)` /
`$svc->setBool(...)` (see the one-off `/tmp` tinker pattern used on 2026-06-11). The
Meta App ID/secret/config-id/verify-token + Zalo App ID/secret + feature flags +
`messenger_platform_mode_availability` grid were seeded this way (no DB seeder). After
moving keys, the admin must re-point the Meta/Zalo webhook callback URLs at
`https://app.sgconnect.vn/...` and re-attest in the vendor dashboards.

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
