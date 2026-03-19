# vBrand Automated Task System

## Architecture

```
Sales/Users report issue (chỉ cần mô tả)
        ↓
  GitHub Issues (luanpm88/vbrand-hub)
   [vbrand, status:new]
        ↓
  do-one-task.md
   ├── fetch task (gh issue)
   ├── claim (status:in-progress)
   ├── auto classify (type + component)
   ├── read code + fix/adjust/implement
   ├── commit + push (all repos)
   ├── auto deploy
   │    ├── component:app → deploy-app (SSH git pull)
   │    ├── component:vbrandsync/themes → deploy-sites (rsync)
   │    └── component:mobile → push only (EAS build manual)
   ├── verify production
   ├── update issue (status:deployed)
   └── create report (reports/task-N.md)
```

## Bots

| Bot | File | Mô tả |
|-----|------|--------|
| Do One Task | `bots/automated/do-one-task.md` | Lấy 1 task, phân loại, fix, deploy, report |
| Deploy App | `bots/automated/deploy-app.md` | Deploy brand Laravel app lên server |
| Deploy Sites | `bots/automated/deploy-sites.md` | Sync themes + vbrandsync plugin lên WP sites |
| Deploy Mobile | `bots/automated/deploy-mobile.md` | Commit + push mobile (không build) |
| Scrape & Import | `bots/scrape/scrape-lazada-shop.md` | Scrape Lazada shop → Import vào WooCommerce |

## Usage Prompts

### Lấy task mới nhất, fix + deploy
```
bots/automated/do-one-task.md
```

### Fix 1 issue cụ thể
```
bots/automated/do-one-task.md issue 42
```

### Xem danh sách task
```
bots/automated/do-one-task.md list
bots/automated/do-one-task.md list all
```

### Deploy brand app (standalone)
```
bots/automated/deploy-app.md
```

### Deploy sites (standalone)
```
bots/automated/deploy-sites.md sync all
bots/automated/deploy-sites.md sync nike.b-teka.com
```

### Deploy mobile (standalone — commit + push)
```
bots/automated/deploy-mobile.md
```

### Scrape & Import Lazada shop

```
# Scrape shop từ Lazada
bots/scrape/scrape-lazada-shop.md scrape https://www.lazada.vn/shop/nike-flagship-store/

# Import vào WooCommerce (clean + import tất cả)
bots/scrape/scrape-lazada-shop.md import https://nike.b-teka.com shops/nike-flagship-store/ --clean

# Scrape + Import full flow
bots/scrape/scrape-lazada-shop.md scrape https://www.lazada.vn/shop/nike-flagship-store/
bots/scrape/scrape-lazada-shop.md import https://nike.b-teka.com shops/nike-flagship-store/ --clean

# Test local trước
bots/scrape/scrape-lazada-shop.md import http://brand-site.test shops/nike-flagship-store/ --clean --limit 20
```

Xem chi tiết: `bots/scrape/scrape-lazada-shop.md`

## Cách tạo issue

Chỉ cần mô tả. Bot tự phân loại type (bug/adjustment/feature/style/perf) và component.

```bash
gh issue create --repo luanpm88/vbrand-hub \
  --title "Trang checkout bị trắng" \
  --label "vbrand,status:new" \
  --body "Mô tả vấn đề, cách tái hiện"
```

Hoặc tạo trên GitHub web: https://github.com/luanpm88/vbrand-hub/issues/new
(gán label `vbrand` + `status:new`)

## Label Conventions

### Khi tạo issue (chỉ cần 2 labels)
| Label | Ý nghĩa |
|-------|---------|
| `vbrand` | Issue thuộc hệ thống vBrand (phân biệt issues khác trong repo) |
| `status:new` | Mới tạo, chưa xử lý |

### Type labels (bot tự phân loại)
| Label | Ý nghĩa | Commit prefix |
|-------|---------|---------------|
| `type:bug` | Lỗi, crash, sai logic | `fix:` |
| `type:adjustment` | Chỉnh sửa nhỏ, text/config | `adjust:` |
| `type:feature` | Tính năng mới | `feat:` |
| `type:style` | Giao diện, CSS, layout | `style:` |
| `type:perf` | Cải thiện performance | `perf:` |

### Component labels (bot tự phân loại)
| Label | Target | Local path |
|-------|--------|------------|
| `component:app` | Laravel brand app | `/Users/luan/apps/vbrand/app` |
| `component:vbrandsync` | vbrandsync plugin | `/site/wp-content/plugins/vbrandsync/` |
| `component:themes` | WordPress themes | `/site/wp-content/themes/` |
| `component:mobile` | React Native app | `/Users/luan/apps/vbrand/mobile` |

### Status labels (bot tự quản lý)
| Label | Ý nghĩa |
|-------|---------|
| `status:in-progress` | Bot đang xử lý |
| `status:deployed` | Đã deploy production |
| `status:failed` | Thất bại, cần review |

## Report Format

Mỗi task tạo 1 file: `bots/automated/reports/task-N.md`

```markdown
# Task #N — Title

## Info
- Issue: https://github.com/luanpm88/vbrand-hub/issues/N
- Status: SUCCESS / FAILED
- Type: bug / adjustment / feature / style / perf
- Component: app / vbrandsync / themes / mobile
- Started: 2026-03-15 14:30:00
- Completed: 2026-03-15 14:45:00

## Description
...

## Root Cause / Analysis
...

## Changes Applied
...

## Files Changed
- path/to/file — mô tả

## Commits
- repo: hash — "message" (pushed to remote/branch)

## Deployment
- Brand app: deployed ✓ / N/A
- Sites: site1 ✓, site2 ✓ / N/A

## Production Verify
- App: hash matches ✓
- Sites: plugin ✓, themes ✓

## Revert (nếu cần)
git revert <hash>
```

## Setup (1 lần)

### 1. Install gh CLI
```bash
brew install gh
gh auth login
```

### 2. Tạo labels trên GitHub
```bash
gh label create "vbrand" --color "1d76db" --repo luanpm88/vbrand-hub
gh label create "component:app" --color "0075ca" --repo luanpm88/vbrand-hub
gh label create "component:vbrandsync" --color "008672" --repo luanpm88/vbrand-hub
gh label create "component:themes" --color "e4e669" --repo luanpm88/vbrand-hub
gh label create "component:mobile" --color "d876e3" --repo luanpm88/vbrand-hub
gh label create "status:new" --color "fbca04" --repo luanpm88/vbrand-hub
gh label create "status:in-progress" --color "f9d0c4" --repo luanpm88/vbrand-hub
gh label create "status:deployed" --color "5319e7" --repo luanpm88/vbrand-hub
gh label create "status:failed" --color "d93f0b" --repo luanpm88/vbrand-hub
gh label create "type:bug" --color "ee0701" --repo luanpm88/vbrand-hub
gh label create "type:adjustment" --color "fbca04" --repo luanpm88/vbrand-hub
gh label create "type:feature" --color "0e8a16" --repo luanpm88/vbrand-hub
gh label create "type:style" --color "c5def5" --repo luanpm88/vbrand-hub
gh label create "type:perf" --color "bfdadc" --repo luanpm88/vbrand-hub
```

## Safety Notes

- **Revert:** mỗi report ghi commit hash → `git revert` nếu sai
- **Resume:** task dở dang (status:in-progress) → bot hỏi Resume/Skip/Reset
- **Multi-component:** 1 issue có thể ảnh hưởng nhiều component → commit riêng từng repo
- **Push to cloud:** tất cả repos đều push lên GitHub
- **Verify prod:** sau deploy luôn verify trên prod server
- **Mobile:** push lên remote nhưng KHÔNG deploy (cần EAS build riêng)

## Git Remotes

| Component | Remote | Branch |
|-----------|--------|--------|
| app | `origin` (louisitvn/acellemail) | `brand` |
| vbrandsync | `origin` (luanpm88/vbrandsync) | `main` |
| themes | `origin` (luanpm88/vbrand-themes) | `main` |
| mobile | `origin` (luanpm88/vbrand-mobile) | `main` |

> Themes + vbrandsync deploy qua rsync (không git pull trên server). Push chỉ để lưu cloud.

## Server Info

- Production: `18.141.199.175`
- SSH: `vbrand@` (app ops), `ubuntu@` (sudo)
- Brand app: `/home/vbrand/app` (branch: `brand`)
- WP sites: `/home/vbrand/sites/*/`
- Sites registry: `bots/report/sites.md`
