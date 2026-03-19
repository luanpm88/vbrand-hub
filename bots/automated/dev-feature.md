# Bot: Dev Feature — Implement Feature + Tests + Docs

Lấy feature issue từ vbrand-hub, implement đầy đủ theo design doc, viết tests, update docs, deploy.

**Khác với do-one-task.md:** Bot này dành riêng cho `type:feature` — đọc design doc kỹ, implement từng bước, viết tests ngay trong cùng session, commit từng component.

## Cách dùng

```
bots/automated/dev-feature.md
bots/automated/dev-feature.md issue 42
bots/automated/dev-feature.md issue 42 --no-deploy
```

## Prerequisites

- `gh` CLI đã cài và authenticated
- Repo hub: `luanpm88/vbrand-hub`

## Quy tắc

- Đọc design doc TRƯỚC khi code — không assume, không sáng tạo architecture
- Implement theo đúng thứ tự trong Implementation Checklist của design doc
- Commit NGAY sau khi xong mỗi component — không gộp
- Viết tests TRONG cùng session — không để sau
- Update docs sau khi implement xong

---

## Flow: 14 bước

### Bước 0: Check resume

```bash
gh issue list --repo luanpm88/vbrand-hub --label "vbrand,status:in-progress" --state open --limit 1 --json number,title
```

Nếu có → hỏi user: resume / skip / reset

### Bước 1: Fetch issue

Nếu user chỉ định `issue N`:
```bash
gh issue view N --repo luanpm88/vbrand-hub --json number,title,labels,body,comments
```

Nếu không:
```bash
gh issue list --repo luanpm88/vbrand-hub --label "vbrand,status:new,type:feature" --state open --limit 1 --json number,title,labels,body --jq '.[0]'
```

Nếu không có → báo "Không có feature mới." và DỪNG.

### Bước 2: Claim

```bash
gh issue edit N --repo luanpm88/vbrand-hub --remove-label "status:new" --add-label "status:in-progress,type:feature"
gh issue comment N --repo luanpm88/vbrand-hub --body "🤖 Dev Feature bot started at $(date '+%Y-%m-%d %H:%M:%S')"
```

### Bước 3: Đọc design docs

1. Luôn đọc `docs/VBRAND_SYSTEM_DOCUMENTATION.md` — tổng quan, patterns
2. Đọc design doc cụ thể trong `docs/rfq/`:

| Feature liên quan | Design doc |
|---|---|
| Super Buyer webapp | `docs/rfq/SUPER_BUYER_DESIGN.md` |
| RFQ backend + UI | `docs/rfq/RFQ_DESIGN.md` |
| RFQ mobile app | `docs/rfq/RFQ_MOBILE_DESIGN.md` |
| Import Request | `docs/rfq/IMPORT_REQUEST_DESIGN.md` |

3. Xác định **Implementation Checklist** trong design doc
4. Xác định **Implementation Order** khuyến nghị
5. Xác định **components** cần thay đổi

### Bước 4: Kiểm tra dependencies

Trước khi implement, verify dependencies:

| Đang implement | Cần có trước |
|---|---|
| Super Buyer webapp (checkout) | `order/add` endpoint trên vbrandsync phải implement |
| RFQ UI (seller webapp/admin) | RFQ backend: vbrandsync plugin + Laravel Order model + OrderDTO |
| RFQ mobile | RFQ backend + Laravel API routes |
| Import Request | Migration đã run |

Nếu dependency chưa có → implement dependency trước, hoặc note + hỏi user.

### Bước 5: Implement vbrandsync plugin (nếu cần)

Path: `site/wp-content/plugins/vbrandsync/`

Đọc code hiện tại → implement theo design doc → verify PHP syntax.

**Sau khi implement:**
```bash
cd /Users/luan/apps/vbrand/site/wp-content/plugins/vbrandsync
git add <specific-files>
git commit -m "feat: <mô tả> [vbrandsync] (implements #N)"
git push origin main
```

### Bước 6: Implement Laravel app (nếu cần)

Path: `app/`

Thứ tự implement:
1. Model + method mới (Wordpress/Order.php, Model/*)
2. Config (config/order_statuses.php, v.v.)
3. DTO (app/DTOs/*)
4. Routes (routes/brand_webapp.php, brand_api_v1.php, brand_superbuyer.php)
5. Middleware (app/Http/Middleware/)
6. Controllers (app/Http/Controllers/Brand/*)
7. Views (resources/views/*)

**Sau khi implement mỗi logical group (ví dụ: backend vs views):**
```bash
cd /Users/luan/apps/vbrand/app
git add <specific-files>
git commit -m "feat: <mô tả> [app] (implements #N)"
git push origin brand
```

### Bước 7: Implement mobile (nếu cần)

Path: `mobile/`

Follow TypeScript types trong design doc. Backward compatible — kiểm tra `is_rfq`, `order_type` trước khi render RFQ UI.

```bash
cd /Users/luan/apps/vbrand/mobile
git add <specific-files>
git commit -m "feat: <mô tả> [mobile] (implements #N)"
git push origin main
```

### Bước 8: Viết Unit Tests (Pest)

Path: `app/tests/Unit/<FeatureName>Test.php`

Test cases:
- Model relationships hoạt động đúng
- Business logic methods (e.g. `approveRfq()`, `isActive()`)
- Status/state transitions
- Scopes

```php
<?php
use Acelle\Model\SuperBuyer;

it('has active status by default', function () {
    $buyer = new SuperBuyer(['status' => 'active']);
    expect($buyer->isActive())->toBeTrue();
});

it('returns false for inactive status', function () {
    $buyer = new SuperBuyer(['status' => 'inactive']);
    expect($buyer->isActive())->toBeFalse();
});
```

```bash
cd /Users/luan/apps/vbrand/app
git add tests/Unit/<FeatureName>Test.php
git commit -m "test: unit tests for <feature> (implements #N)"
git push origin brand
```

### Bước 9: Viết Feature Tests (Pest HTTP)

Path: `app/tests/Feature/<FeatureName>Test.php`

Test cases:
- Route tồn tại và trả đúng HTTP status
- Auth protection (redirect 302 nếu chưa đăng nhập)
- JSON response structure cho API routes
- Validation rules

```php
<?php
it('redirects unauthenticated users', function () {
    $response = $this->get('/brand/super-buyer/mobile/shops');
    $response->assertRedirect('/brand/super-buyer/mobile/login');
});

it('returns shops list as JSON for API', function () {
    $user = User::factory()->create();
    $user->superBuyer()->create(['status' => 'active', 'name' => 'Test']);

    $response = $this->actingAs($user, 'api')
        ->getJson('/api/v1/brand/super-buyer/shops');

    $response->assertOk()
        ->assertJsonStructure(['status', 'data', 'meta']);
});
```

```bash
cd /Users/luan/apps/vbrand/app
git add tests/Feature/<FeatureName>Test.php
git commit -m "test: feature/HTTP tests for <feature> (implements #N)"
git push origin brand
```

### Bước 10: Viết Dusk Browser Tests

Path: `app/tests/Browser/<Area>/<FeatureName>Test.php`

**Area mapping:**
- `Webapp/` — seller webapp (brand/webapp)
- `SuperBuyer/` — Super Buyer pages
- `Admin/` — admin panel

**Helpers có sẵn trong DuskTestCase:**
- `loginAsCustomer($browser)` — login as seller
- `assertNoPageErrors($browser, 'Page Name')` — no PHP errors
- `assertNoAjaxErrors($browser, '#selector', 'Context')` — no AJAX errors

**Cần thêm vào DuskTestCase khi implement Super Buyer:**
```php
protected function loginAsSuperBuyer(Browser $browser): Browser
{
    $user = \Acelle\Model\User::whereHas('superBuyer', function ($q) {
        $q->where('status', 'active');
    })->first();

    if (!$user) {
        $this->fail('No Super Buyer user found for Dusk testing');
    }

    return $browser->loginAs($user);
}
```

**Pattern chuẩn:**
```php
public function test_shops_page_loads(): void
{
    $this->browse(function (Browser $browser) {
        $this->loginAsSuperBuyer($browser);

        $browser->visit('/brand/super-buyer/mobile/shops')
            ->waitFor('.animate-fade-in', 30)
            ->assertSee('Cửa hàng');

        $this->assertNoPageErrors($browser, 'SuperBuyer Shops');
    });
}

public function test_shops_ajax_list_loads(): void
{
    $this->browse(function (Browser $browser) {
        $this->loginAsSuperBuyer($browser);

        $browser->visit('/brand/super-buyer/mobile/shops')
            ->waitFor('.animate-fade-in', 30)
            ->waitUntilMissing('[x-show="isLoading"]', 60)
            ->pause(2000);

        $this->assertNoAjaxErrors($browser, '#shop-list, [id*="shop"]', 'Shops AJAX');
        $this->assertNoPageErrors($browser, 'SuperBuyer Shops After AJAX');
    });
}
```

**Smoke test pattern** (test tất cả pages của 1 feature):
```php
private static array $pages = [
    'Shops'    => '/brand/super-buyer/mobile/shops',
    'Orders'   => '/brand/super-buyer/mobile/orders',
    'Profile'  => '/brand/super-buyer/mobile/profile',
];
```

```bash
cd /Users/luan/apps/vbrand/app
git add tests/Browser/<Area>/<FeatureName>Test.php
git commit -m "test: Dusk browser tests for <feature> (implements #N)"
git push origin brand
```

### Bước 11: Update docs

Sau khi implement xong, update `docs/VBRAND_SYSTEM_DOCUMENTATION.md`:
- Thêm routes mới vào bảng route reference
- Xóa note "chưa implement" nếu có
- Update API contracts nếu thay đổi
- Thêm phần test commands nếu mới

```bash
# docs/ là thư mục tách, commit vào app repo hoặc standalone tùy context
cd /Users/luan/apps/vbrand/app
git add ../../docs/VBRAND_SYSTEM_DOCUMENTATION.md
git commit -m "docs: update documentation after <feature> implementation (implements #N)"
git push origin brand
```

### Bước 12: Deploy

Giống do-one-task.md bước 7. Thứ tự:
1. vbrandsync + themes → rsync → migrate
2. app → SSH git pull → migrate

```bash
# App deploy:
ssh vbrand@18.141.199.175 "
cd /home/vbrand/app
git pull origin brand
php composer.phar install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache && php artisan cache:clear && php artisan view:clear && php artisan route:clear
"
```

Với `--no-deploy`: skip bước này, chỉ commit + push.

### Bước 13: Update GitHub issue

```bash
gh issue edit N --repo luanpm88/vbrand-hub --remove-label "status:in-progress" --add-label "status:deployed"
gh issue comment N --repo luanpm88/vbrand-hub --body "$(cat <<'EOF'
🤖 Feature implemented and deployed!

**Design doc:** docs/rfq/<design>.md
**Components changed:** <list>

**Tests added:**
- `tests/Unit/<Name>Test.php` — unit tests
- `tests/Feature/<Name>Test.php` — HTTP tests
- `tests/Browser/<Area>/<Name>Test.php` — browser tests

**Commits:**
- [vbrandsync] `<hash>` — <message>
- [app backend] `<hash>` — <message>
- [app views] `<hash>` — <message>
- [app tests] `<hash>` — <message>

**Deployed to:** app ✓ / sites ✓
**Verified:** ✓

**Report:** bots/automated/reports/task-N.md
EOF
)"
```

### Bước 14: Tạo report

File: `bots/automated/reports/task-N.md`

```markdown
# Task #N — <Title>

## Info
- **Issue:** https://github.com/luanpm88/vbrand-hub/issues/N
- **Type:** feature
- **Design doc:** docs/rfq/<design>.md
- **Status:** SUCCESS
- **Started:** <timestamp>
- **Completed:** <timestamp>

## Description
<copy from issue>

## Design Doc Implementation Checklist
- [x] <item 1>
- [x] <item 2>
- [ ] <deferred item> — reason

## Files Changed
- `<path>` — <description>

## Tests Added
- `tests/Unit/<Name>Test.php` — <N tests>
- `tests/Feature/<Name>Test.php` — <N tests>
- `tests/Browser/<Area>/<Name>Test.php` — <N tests>

## Commits
- **<repo>:** `<hash>` — "<message>"

## Deployment
- App: deployed ✓ / N/A
- Sites: ✓ / N/A
- Mobile: pushed, EAS build needed / N/A

## Revert
git revert <hash>
```

---

## Error handling

Nếu bất kỳ bước nào fail:

```bash
gh issue edit N --repo luanpm88/vbrand-hub --remove-label "status:in-progress" --add-label "status:failed"
gh issue comment N --repo luanpm88/vbrand-hub --body "🤖 Feature implementation failed. Reason: <error>. Needs human review."
```
