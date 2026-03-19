# Bot: Deploy Brand App

Deploy Laravel brand app lên production server.

## Cách dùng

```
bots/automated/deploy-app.md
```

## SSH accounts

- `vbrand@18.141.199.175` — deploy operations
- Production app path: `/home/vbrand/app`
- Branch: `brand`

## Flow

### Bước 1: Pre-flight check

So sánh local vs server:

```bash
# Local
cd /Users/luan/apps/vbrand/app
echo "=== Local (branch: $(git branch --show-current)) ==="
git log --oneline -3
echo "=== Unpushed commits ==="
git log origin/brand..brand --oneline 2>/dev/null || echo "None"
```

```bash
# Server
ssh vbrand@18.141.199.175 "cd /home/vbrand/app && echo '=== Server ===' && git log --oneline -1 && echo '=== Status ===' && git status --short"
```

Nếu có unpushed commits → cảnh báo: "Có commits chưa push. Push trước: `cd /Users/luan/apps/vbrand/app && git push origin brand`"

### Bước 2: Deploy

```bash
ssh vbrand@18.141.199.175 "
cd /home/vbrand/app
git pull origin brand
php composer.phar install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan cache:clear
php artisan view:clear
php artisan route:clear
"
```

> ⚠️ **Quan trọng — Cache rules:**
> - Luôn dùng `config:cache` (KHÔNG dùng `config:clear`) — vì `routes/web.php` dùng `config('app.brand')` để conditionally load brand routes. Nếu config không được cache thì brand routes sẽ không load → 404 toàn bộ `/brand/*`
> - Luôn dùng `route:clear` (KHÔNG dùng `route:cache`) — vì `BaokimController` không tồn tại trên prod, khiến `route:cache` fail

### Bước 3: Verify

```bash
ssh vbrand@18.141.199.175 "
cd /home/vbrand/app
echo '=== Current commit ==='
git log --oneline -1
echo '=== App version ==='
php artisan --version
"
```

### Output

```
✅ Brand app deployed!
- Server: vbrand@18.141.199.175:/home/vbrand/app
- Branch: brand
- Commit: <hash> — <message>
- Deployed at: <timestamp>
```
