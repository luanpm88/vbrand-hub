# Bot: Do One Task — Auto Fix + Deploy

Lấy 1 task từ GitHub Issues, phân loại, fix, commit, push, deploy tự động, tạo report.

## Cách dùng

```
bots/automated/do-one-task.md
bots/automated/do-one-task.md issue 42
bots/automated/do-one-task.md list
bots/automated/do-one-task.md list all
```

## Input

- Không có argument → lấy task `vbrand,status:new` cũ nhất
- `issue N` → fix issue số N cụ thể
- `list` → liệt kê 10 task mới nhất
- `list all` → liệt kê tất cả task

## Prerequisites

- `gh` CLI đã cài và authenticated
- GitHub repo hub: `luanpm88/vbrand-hub`

## Quy tắc

- Đọc `docs/VBRAND_SYSTEM_DOCUMENTATION.md` trước khi code (theo CLAUDE.md)
- Minimal changes — chỉ fix/adjust, không refactor code xung quanh
- Follow existing patterns và conventions
- Mỗi repo commit riêng

## Label conventions

**Tạo issue chỉ cần:** `vbrand` + `status:new` — mô tả lỗi/yêu cầu, không cần gì khác.
**Bot chỉ xử lý issue có label `vbrand`** — bỏ qua issues khác trong repo.

| Label | Ý nghĩa | Ai gán |
|---|---|---|
| `vbrand` | Issue thuộc hệ thống vBrand | người tạo |
| `status:new` | Mới tạo, chưa xử lý | người tạo |
| `status:in-progress` | Đang xử lý | bot |
| `status:deployed` | Đã deploy lên production | bot |
| `status:failed` | Xử lý thất bại, cần review | bot |

### Type labels (bot tự phân loại từ nội dung issue)

| Label | Ý nghĩa | Commit prefix |
|---|---|---|
| `type:bug` | Lỗi, crash, sai logic | `fix:` |
| `type:adjustment` | Chỉnh sửa nhỏ, thay đổi text/config | `adjust:` |
| `type:feature` | Tính năng mới | `feat:` |
| `type:style` | Thay đổi giao diện, CSS, layout | `style:` |
| `type:perf` | Cải thiện performance | `perf:` |

### Component labels (bot tự phân loại từ nội dung issue)

| Label | Target | Local path |
|---|---|---|
| `component:app` | Laravel brand app | `/Users/luan/apps/vbrand/app` |
| `component:vbrandsync` | vbrandsync plugin | `/site/wp-content/plugins/vbrandsync/` |
| `component:themes` | WordPress themes | `/site/wp-content/themes/` |
| `component:mobile` | React Native app | `/Users/luan/apps/vbrand/mobile` |

## Component → Repo mapping

| Component | Local path | Git remote | Branch | Deploy method |
|---|---|---|---|---|
| app | `/Users/luan/apps/vbrand/app` | `origin` (louisitvn/acellemail) | `brand` | SSH git pull |
| vbrandsync | `/Users/luan/apps/vbrand/site/wp-content/plugins/vbrandsync` | `origin` (luanpm88/vbrandsync) | `main` | rsync |
| themes | `/Users/luan/apps/vbrand/site/wp-content/themes/` | `origin` (luanpm88/vbrand-themes) | `main` | rsync |
| mobile | `/Users/luan/apps/vbrand/mobile` | `origin` (luanpm88/vbrand-mobile) | `main` | EAS build (manual) |

## SSH accounts

- `vbrand@18.141.199.175` — app operations, rsync, wp-cli
- `ubuntu@18.141.199.175` — sudo operations (nếu cần)

## Flow: list

### Lệnh `list`

```bash
gh issue list --repo luanpm88/vbrand-hub --label "vbrand,status:new" --state open --limit 10 --json number,title,labels,createdAt
```

### Lệnh `list all`

```bash
gh issue list --repo luanpm88/vbrand-hub --label "vbrand" --state open --json number,title,labels,createdAt
```

Output dạng bảng:

```
# | Title | Type | Component | Status | Created
42 | Lỗi sync sản phẩm | bug | app | new | 2026-03-15
43 | Đổi màu nút checkout | style | themes | new | 2026-03-16
```

## Flow: Fix 1 task (11 bước)

### Bước 0: Check resume

Kiểm tra có issue `status:in-progress` từ lần trước không:

```bash
gh issue list --repo luanpm88/vbrand-hub --label "vbrand,status:in-progress" --state open --limit 1 --json number,title
```

Nếu có → hỏi user:
```
⚠️ Issue #N "Title" đang in-progress từ lần trước.
1. resume — tiếp tục fix issue này
2. skip — bỏ qua, lấy task mới
3. reset — reset về status:new, lấy task mới
```

### Bước 1: Fetch task

Nếu user chỉ định `issue N`:

```bash
gh issue view N --repo luanpm88/vbrand-hub --json number,title,labels,body,comments
```

Nếu không chỉ định:

```bash
gh issue list --repo luanpm88/vbrand-hub --label "vbrand,status:new" --state open --limit 1 --json number,title,labels,body --jq '.[0]'
```

Nếu không có task → báo "Không có task mới." và DỪNG.

### Bước 2: Claim task

```bash
gh issue edit N --repo luanpm88/vbrand-hub --remove-label "status:new" --add-label "status:in-progress"
gh issue comment N --repo luanpm88/vbrand-hub --body "🤖 Bot picked up this task at $(date '+%Y-%m-%d %H:%M:%S')"
```

### Bước 3: Phân loại type + component

Bot tự phân loại dựa trên nội dung issue (người report KHÔNG cần gán):

**Type — đọc title + body, xác định loại:**
- Crash, lỗi, sai logic, exception → `type:bug`
- Thay đổi text, config, giá trị, settings → `type:adjustment`
- Tính năng mới, thêm chức năng → `type:feature`
- Thay đổi giao diện, CSS, layout, responsive → `type:style`
- Chậm, tối ưu, cache → `type:perf`

**Component — đọc title + body, xác định target:**
- Laravel, API, brand app, admin panel, backend → `component:app`
- Sync, WooCommerce plugin, vbrandsync, đồng bộ → `component:vbrandsync`
- Giao diện WP, theme, CSS, layout, banner, checkout page → `component:themes`
- Mobile app, React Native, Expo, Android, iOS → `component:mobile`

Gán labels:
```bash
gh issue edit N --repo luanpm88/vbrand-hub --add-label "type:bug,component:app"
```

Nếu không rõ → đọc code, suy luận. Nếu vẫn không xác định → comment hỏi và DỪNG.
Có thể ảnh hưởng nhiều component → gán nhiều labels, fix từng cái, commit riêng.

### Bước 4: Đọc code + hiểu vấn đề

- Đọc issue description và comments
- Đọc `docs/VBRAND_SYSTEM_DOCUMENTATION.md` (theo CLAUDE.md)
- Explore code liên quan
- Xác định root cause trước khi sửa

### Bước 5: Fix / Adjust / Implement

- Minimal changes, focused
- Follow existing patterns
- KHÔNG thay đổi code không liên quan

### Bước 6: Commit + Push

Commit prefix theo type:
- `type:bug` → `fix: <mô tả> (fixes #N)`
- `type:adjustment` → `adjust: <mô tả> (fixes #N)`
- `type:feature` → `feat: <mô tả> (fixes #N)`
- `type:style` → `style: <mô tả> (fixes #N)`
- `type:perf` → `perf: <mô tả> (fixes #N)`

Với mỗi component đã thay đổi:

**app:**
```bash
cd /Users/luan/apps/vbrand/app
git add <specific-files>
git commit -m "<prefix>: <mô tả> (fixes #N)"
git push origin brand
```

**vbrandsync:**
```bash
cd /Users/luan/apps/vbrand/site/wp-content/plugins/vbrandsync
git add <specific-files>
git commit -m "<prefix>: <mô tả> (fixes #N)"
git push origin main
```

**themes:**
```bash
cd /Users/luan/apps/vbrand/site/wp-content/themes
git add <specific-files>
git commit -m "<prefix>: <mô tả> (fixes #N)"
git push origin main
```
> Deploy vẫn qua rsync (không phải git pull trên server).

**mobile:**
```bash
cd /Users/luan/apps/vbrand/mobile
git add <specific-files>
git commit -m "<prefix>: <mô tả> (fixes #N)"
git push origin main
```

### Bước 7: Auto Deploy

Tự động deploy dựa trên component đã thay đổi. KHÔNG hỏi confirm.

**Nếu component:app đã thay đổi → Deploy brand app:**

```bash
ssh vbrand@18.141.199.175 "
cd /home/vbrand/app
git pull origin brand
php composer.phar install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
"
```

**Nếu component:vbrandsync hoặc component:themes đã thay đổi → Deploy sites:**

Đọc `bots/report/sites.md`, lấy danh sách sites. Với mỗi site (DIR_NAME, DOMAIN):

```bash
# Sync plugin
rsync -avz --delete /Users/luan/apps/vbrand/site/wp-content/plugins/vbrandsync/ vbrand@18.141.199.175:/home/vbrand/sites/${DIR_NAME}/wp-content/plugins/vbrandsync/

# Sync themes
rsync -avz --delete /Users/luan/apps/vbrand/site/wp-content/themes/ vbrand@18.141.199.175:/home/vbrand/sites/${DIR_NAME}/wp-content/themes/

# Fix .env + install + migrate
ssh vbrand@18.141.199.175 "
cd /home/vbrand/sites/${DIR_NAME}/wp-content/plugins/vbrandsync
sed -i 's/^DB_DATABASE=.*/DB_DATABASE=${DIR_NAME}/' .env
sed -i 's/^DB_USERNAME=.*/DB_USERNAME=${DIR_NAME}/' .env
sed -i 's/^DB_PASSWORD=.*/DB_PASSWORD=aA456321@/' .env
sed -i 's/^DB_HOST=.*/DB_HOST=127.0.0.1/' .env
sed -i 's/^DB_PREFIX=.*/DB_PREFIX=wp_vbs_/' .env
chmod -R 775 storage bootstrap/cache
php composer.phar install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:clear
php artisan cache:clear
php artisan view:clear
"
```

**Nếu component:mobile → Push only, skip deploy:**

```
⚠️ Mobile fix đã commit + push. Cần EAS build riêng để deploy:
  cd /Users/luan/apps/vbrand/mobile
  eas build --platform all
```

### Bước 8: Verify production

Sau khi deploy, verify trên prod server để đảm bảo code đã up-to-date:

**Nếu component:app đã deploy:**
```bash
ssh vbrand@18.141.199.175 "cd /home/vbrand/app && echo '=== App ===' && git log --oneline -1 && git status --short"
```
So sánh commit hash trên server phải match local. Nếu khác → báo lỗi.

**Nếu component:vbrandsync/themes đã deploy (rsync):**
```bash
for DIR_NAME in <danh_sách_sites>; do
  echo "=== ${DIR_NAME} ==="
  ssh vbrand@18.141.199.175 "
    echo 'Plugin:' && ls -la /home/vbrand/sites/${DIR_NAME}/wp-content/plugins/vbrandsync/app/Wordpress/Models/ | tail -3
    echo 'Theme:' && ls -la /home/vbrand/sites/${DIR_NAME}/wp-content/themes/logitech/css/ | tail -3
    echo 'Active:' && cd /home/vbrand/sites/${DIR_NAME} && wp plugin list --status=active --name=vbrandsync --format=csv 2>/dev/null
  "
done
```

**Output verify:**
```
✅ Production verified!
- App: <hash> matches local ✓
- logitech.b-teka.com: plugin ✓, themes ✓
- nike.b-teka.com: plugin ✓, themes ✓
```

Nếu verify fail → đánh dấu trong report, KHÔNG update issue thành `status:deployed`.

### Bước 9: Update issue

```bash
gh issue edit N --repo luanpm88/vbrand-hub --remove-label "status:in-progress" --add-label "status:deployed"
gh issue comment N --repo luanpm88/vbrand-hub --body "$(cat <<'EOF'
🤖 Task completed and deployed!

**Type:** <type>
**Component:** <component>
**Commit:** `<hash>` — <message>
**Files changed:**
- path/to/file1
- path/to/file2

**Root cause / Description:** <brief>
**Fix / Changes:** <brief>

**Deployed to:**
- Brand app ✓ (hoặc N/A)
- Sites: logitech.b-teka.com ✓, nike.b-teka.com ✓ (hoặc N/A)

**Verified on production:** ✓

**Report:** bots/automated/reports/task-N.md
EOF
)"
```

### Bước 10: Tạo report

Tạo file `bots/automated/reports/task-N.md` theo format:

```markdown
# Task #N — <Title>

## Info
- **Issue:** https://github.com/luanpm88/vbrand-hub/issues/N
- **Status:** SUCCESS
- **Type:** <bug / adjustment / feature / style / perf>
- **Component:** <component>
- **Started:** <timestamp>
- **Completed:** <timestamp>

## Description
<copy từ issue body>

## Root Cause / Analysis
<giải thích nguyên nhân hoặc phân tích yêu cầu>

## Changes Applied
<mô tả thay đổi>

## Files Changed
- `path/to/file1.php` — <mô tả thay đổi>
- `path/to/file2.php` — <mô tả thay đổi>

## Commits
- **<repo>:** `<hash>` — "<message>" (pushed to <remote>/<branch>)

## Deployment
- Brand app: deployed ✓ / N/A
- Sites synced: logitech.b-teka.com ✓, nike.b-teka.com ✓ / N/A
- Mobile: pushed, needs EAS build / N/A

## Production Verify
- App: <hash> matches ✓
- Sites: plugin ✓, themes ✓

## Revert (nếu cần)
git revert <hash>
```

### Bước 11: Error handling

Nếu bất kỳ bước nào fail:

```bash
gh issue edit N --repo luanpm88/vbrand-hub --remove-label "status:in-progress" --add-label "status:failed"
gh issue comment N --repo luanpm88/vbrand-hub --body "🤖 Task failed. Reason: <error details>. Needs human review."
```

Vẫn tạo report với status FAILED và chi tiết lỗi.

### Output

```
✅ Task #N completed and deployed!
- Issue: <title>
- Type: <type>
- Component: <component>
- Commit: <hash>
- Deployed: app ✓ / sites ✓
- Verified: ✓
- Report: bots/automated/reports/task-N.md
```

Hoặc nếu fail:

```
❌ Task #N failed!
- Issue: <title>
- Error: <error details>
- Report: bots/automated/reports/task-N.md
- Issue marked as status:failed on GitHub
```
