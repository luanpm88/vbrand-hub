# vBrand — Claude Instructions

## Đọc trước khi làm bất cứ việc gì

Đọc `docs/VBRAND_SYSTEM_DOCUMENTATION.md` trước khi code — chứa toàn bộ design, architecture, patterns, API contracts.

## Quy tắc bắt buộc

1. **Luôn đọc docs trước** — không assume, không đoán
2. **Sau khi code xong** — update `docs/VBRAND_SYSTEM_DOCUMENTATION.md` nếu có thay đổi design/API/pattern
3. **Hỏi nếu không chắc** — hơn là code sai rồi sửa
4. **Minimal changes** — chỉ fix/adjust đúng yêu cầu, không refactor code xung quanh
5. **Follow existing patterns** — đọc code hiện tại trước, viết theo cùng style

## Project Structure

```
/app     → Laravel 12 (PHP) — brand app, admin, webapp, API
/site    → WordPress + WooCommerce — customer-facing sites
  /wp-content/plugins/vbrandsync/  → sync plugin (Laravel micro-app trong WP)
  /wp-content/themes/              → WP themes (logitech, vbrand-developer, ...)
/mobile  → React Native + Expo (TypeScript) — seller mobile app
/docs    → System documentation
/bots    → Automated task bots (xem phần Bots bên dưới)
```

### New Features (chưa implement — chỉ có design docs + migrations)

```
/app/app/Model/SuperBuyer.php          → Eloquent model (table: super_buyers)
/app/app/Model/SuperBuyerOrder.php     → Eloquent model (table: super_buyer_orders)
/app/app/Model/ImportRequest.php       → Eloquent model (table: import_requests)
```

**Design docs** (đọc trước khi implement, tất cả trong `docs/rfq/`):
- `docs/rfq/SUPER_BUYER_DESIGN.md` — Super Buyer webapp architecture, auth, WP connection switching, checkout flow, order management, API contracts
- `docs/rfq/RFQ_DESIGN.md` — RFQ order type, WooCommerce integration, approve flow, cross-platform UI (seller webapp + admin + API)
- `docs/rfq/RFQ_MOBILE_DESIGN.md` — RFQ seller mobile app update, TypeScript types, UI components, implementation checklist (7/8 done)
- `docs/rfq/IMPORT_REQUEST_DESIGN.md` — Import product request, seller/admin CRUD, status flow

**Super Buyer routes** (chưa tạo): `/brand/super-buyer/mobile/*` — orange theme, login riêng

## Git Remotes & Branches

| Component | Local path | Git remote | Branch | Deploy |
|-----------|-----------|------------|--------|--------|
| app | `/Users/luan/apps/vbrand/app` | `origin` (louisitvn/acellemail) | `brand` | SSH git pull |
| vbrandsync | `/Users/luan/apps/vbrand/site/wp-content/plugins/vbrandsync` | `origin` (luanpm88/vbrandsync) | `main` | rsync |
| themes | `/Users/luan/apps/vbrand/site/wp-content/themes` | `origin` (luanpm88/vbrand-themes) | `main` | rsync |
| mobile | `/Users/luan/apps/vbrand/mobile` | `origin` (luanpm88/vbrand-mobile) | `main` | EAS build (manual) |

Mỗi component là 1 git repo riêng → commit/push riêng.

## Server

- **Production:** `18.141.199.175`
- **SSH:** `vbrand@` (app ops, rsync), `ubuntu@` (sudo)
- **App path:** `/home/vbrand/app` (branch: `brand`)
- **Sites path:** `/home/vbrand/sites/*/` — mỗi site = 1 WP instance
- **Sites registry:** `bots/report/sites.md` — danh sách tất cả sites

## Architecture (3-layer API chain)

```
Mobile App / Webapp → Laravel API → WordPress REST API (vbrandsync plugin) → WooCommerce
```

- Laravel app là trung tâm — quản lý tất cả qua API tới WordPress
- vbrandsync plugin expose REST endpoints cho Laravel gọi (products, orders, attributes, themes, ...)
- Mobile app gọi Laravel API (không gọi WP trực tiếp)

---

## Automated Bots System

Hệ thống tự động hóa fix bug + implement feature + tests + deploy.
Issues/features được track trên GitHub repo **`luanpm88/vbrand-hub`**.

### Cách hoạt động

```
User report bug/feature → GitHub Issues [vbrand, status:new] trên luanpm88/vbrand-hub
        ↓
  Bug/adjustment → do-one-task.md     Feature mới → dev-feature.md
   ├── fetch + claim task               ├── fetch + claim task
   ├── đọc docs + code → fix            ├── đọc design doc (docs/rfq/)
   ├── commit + push (từng repo)        ├── implement step-by-step
   ├── auto deploy                      ├── commit từng component ngay
   ├── verify production                ├── viết Unit + Feature + Dusk tests
   ├── update issue → deployed          ├── update docs
   └── tạo report                       ├── deploy + verify
                                        ├── update issue → deployed
                                        └── tạo report
```

### Bot files

| Bot | File | Mô tả |
|-----|------|--------|
| Do One Task | `bots/automated/do-one-task.md` | Fix bug/adjustment từ issue — auto deploy |
| Dev Feature | `bots/automated/dev-feature.md` | Implement feature với tests + docs — bot mới |
| Write Tests | `bots/automated/write-tests.md` | Viết Unit/Feature/Dusk tests cho 1 area |
| Deploy App | `bots/automated/deploy-app.md` | Deploy brand Laravel app lên server |
| Deploy Sites | `bots/automated/deploy-sites.md` | Sync themes + vbrandsync plugin lên WP sites |
| Deploy Mobile | `bots/automated/deploy-mobile.md` | Commit + push mobile (không build) |
| Design Doc | `bots/automated/DESIGN_USAGE_PROMPTS.md` | Architecture + usage guide chi tiết |
| Scrape & Import | `bots/scrape/scrape-lazada-shop.md` | Scrape Lazada Mall shop → Import vào WooCommerce |

### Usage — Cách gọi bots

```bash
# Fix bug/adjustment mới nhất
bots/automated/do-one-task.md
bots/automated/do-one-task.md issue 42

# Implement feature mới nhất (với tests + docs)
bots/automated/dev-feature.md
bots/automated/dev-feature.md issue 42
bots/automated/dev-feature.md issue 42 --no-deploy  # implement only, no deploy

# Viết tests cho 1 area
bots/automated/write-tests.md rfq
bots/automated/write-tests.md super-buyer
bots/automated/write-tests.md import-request

# Fix tất cả tasks đang chờ (loop)
# User nói: "loop do-one-task" → lặp cho đến hết status:new

# Xem danh sách tasks
bots/automated/do-one-task.md list
bots/automated/do-one-task.md list all

# Deploy riêng (không cần issue)
bots/automated/deploy-app.md
bots/automated/deploy-sites.md sync all
bots/automated/deploy-sites.md sync nike.b-teka.com
```

### Khi user nói ngắn gọn

User có thể nói ngắn — Claude phải tự hiểu và chạy đúng bot:

| User nói | Claude làm |
|----------|-----------|
| `do-one-task` hoặc `fix task mới` | Chạy `do-one-task.md` |
| `fix issue 26` hoặc `sửa issue 26` | Chạy `do-one-task.md issue 26` |
| `dev feature` hoặc `implement feature` | Chạy `dev-feature.md` |
| `implement issue 42` hoặc `làm issue 42` | Chạy `dev-feature.md issue 42` |
| `write tests rfq` hoặc `viết tests super-buyer` | Chạy `write-tests.md <area>` |
| `sửa hết tasks` hoặc `loop do-one-task` | Lặp `do-one-task.md` cho đến hết `status:new` |
| `deploy app` | Chạy `deploy-app.md` |
| `deploy sites` | Chạy `deploy-sites.md sync all` |
| `list tasks` hoặc `xem tasks` | Chạy `do-one-task.md list` |
| `bug mới nhất ...` + context | Chạy `do-one-task.md` |
| `scrape lazada <url>` | Chạy `scrape-lazada-shop.md scrape <url>` |
| `import <shop> vào <site>` | Chạy `scrape-lazada-shop.md import <site> <shop_dir> --clean` |
| `scrape + import <url> vào <site>` | Scrape rồi import |

### Label conventions

**Tạo issue chỉ cần:** label `vbrand` + `status:new` + mô tả — bot tự phân loại type + component.

**Type labels** (bot gán, commit prefix):
- `type:bug` → `fix:` | `type:adjustment` → `adjust:` | `type:feature` → `feat:`
- `type:style` → `style:` | `type:perf` → `perf:`

**Component labels** (bot gán): `component:app`, `component:vbrandsync`, `component:themes`, `component:mobile`

**Status labels** (bot quản lý): `status:new` → `status:in-progress` → `status:deployed` / `status:failed`

### Reports

Mỗi task tạo report: `bots/automated/reports/task-N.md` — chứa root cause, changes, commits, deploy status, revert command.

---

## Testing

### Stack

| Type | Framework | Path | Run command |
|------|-----------|------|-------------|
| Unit | Pest PHP | `app/tests/Unit/` | `./vendor/bin/pest tests/Unit/` |
| Feature/HTTP | Pest PHP | `app/tests/Feature/` | `./vendor/bin/pest tests/Feature/` |
| Browser (headless) | Laravel Dusk | `app/tests/Browser/` | `php artisan dusk` |
| Browser (visible) | Laravel Dusk | `app/tests/Browser/` | `DUSK_HEADLESS_DISABLED=true php artisan dusk` |

### DuskTestCase helpers (`app/tests/DuskTestCase.php`)

- `loginAsCustomer($browser)` — login as seller (user với customer relationship)
- `loginAsSuperBuyer($browser)` — login as Super Buyer (**cần thêm khi implement Super Buyer**)
- `assertNoPageErrors($browser, 'Name')` — kiểm tra không có PHP error trong page source
- `assertNoAjaxErrors($browser, '#selector', 'Context')` — kiểm tra không có error trong AJAX response

**Viewport:** iPhone 14 Pro (430×932) — tất cả Dusk tests chạy ở mobile viewport

### Test structure cho new features

```
tests/
├── Unit/
│   ├── RfqOrderTest.php          ← RFQ business logic
│   ├── SuperBuyerTest.php        ← SuperBuyer/SuperBuyerOrder models
│   └── ImportRequestTest.php    ← ImportRequest model + statuses
├── Feature/
│   ├── RfqApiTest.php            ← approve-rfq routes + auth
│   ├── SuperBuyerRouteTest.php   ← Super Buyer routes + auth
│   └── ImportRequestApiTest.php ← Import Request CRUD API
└── Browser/
    ├── Webapp/
    │   └── RfqOrdersTest.php     ← RFQ badge, filter tab, approve flow
    ├── SuperBuyer/
    │   ├── SuperBuyerSmokeTest.php ← All Super Buyer pages
    │   └── CheckoutTest.php      ← Checkout + order creation flow
    └── Admin/
        └── ImportRequestTest.php ← Admin manage import requests
```

### Khi implement feature mới → viết tests ngay

Xem `bots/automated/write-tests.md` để biết patterns và commands.

---

## Workflow chung

1. Nhận yêu cầu từ user (hoặc GitHub Issue từ `luanpm88/vbrand-hub`)
2. Đọc `docs/VBRAND_SYSTEM_DOCUMENTATION.md` + design doc liên quan (`docs/rfq/`)
3. Đọc code hiện tại, hiểu patterns
4. Code — minimal, follow patterns
5. Viết tests (Unit + Feature + Dusk) ngay trong cùng session
6. Commit + push từng component riêng
7. Deploy nếu cần
8. Update `docs/VBRAND_SYSTEM_DOCUMENTATION.md` nếu có thay đổi design/API
