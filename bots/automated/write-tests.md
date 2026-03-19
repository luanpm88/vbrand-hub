# Bot: Write Tests — Viết tests cho feature/area

Viết Pest unit tests, Pest feature/HTTP tests, và Dusk browser tests cho 1 area đã implement.

Dùng bot này khi:
- Feature đã implement nhưng chưa có tests
- Cần mở rộng test coverage
- Sau khi fix bug → thêm regression test

## Cách dùng

```
bots/automated/write-tests.md <area>
```

**Area values:**
```
bots/automated/write-tests.md rfq              → RFQ orders (seller webapp + API)
bots/automated/write-tests.md super-buyer      → Super Buyer webapp
bots/automated/write-tests.md import-request   → Import Request feature
bots/automated/write-tests.md seller-webapp    → Toàn bộ seller webapp (smoke tests)
bots/automated/write-tests.md mobile-rfq       → RFQ trên mobile app (Dusk không test được RN — viết unit/type tests)
```

## Test types

| Type | Framework | Path | Dùng khi |
|------|-----------|------|----------|
| Unit | Pest | `tests/Unit/` | Test model methods, business logic, không cần HTTP |
| Feature/HTTP | Pest | `tests/Feature/` | Test routes, auth, API responses, validation |
| Browser | Dusk | `tests/Browser/` | Test UI load, AJAX, headless Chrome |

## Run commands

```bash
# Run tất cả tests (không Dusk):
cd /Users/luan/apps/vbrand/app
./vendor/bin/pest

# Run Dusk (headless, cần app đang chạy):
php artisan dusk

# Run visible (để debug UI):
DUSK_HEADLESS_DISABLED=true php artisan dusk

# Run 1 test file:
php artisan dusk --filter SuperBuyerSmokeTest
./vendor/bin/pest tests/Unit/RfqOrderTest.php

# Run 1 test group:
./vendor/bin/pest --group rfq
php artisan dusk tests/Browser/SuperBuyer/
```

---

## Flow: Viết tests cho 1 area

### Bước 1: Đọc code đã implement

Trước khi viết test, đọc:
- Design doc tương ứng trong `docs/rfq/`
- Controller(s) liên quan
- Model(s) liên quan
- Routes

### Bước 2: Viết Unit Tests

**Path:** `app/tests/Unit/<FeatureName>Test.php`

**Patterns phổ biến:**
```php
<?php

use Acelle\Model\SuperBuyer;
use Acelle\Model\SuperBuyerOrder;
use Acelle\Model\ImportRequest;

// Group tests với describe():
describe('SuperBuyer', function () {
    it('isActive() returns true for active status', function () {
        $buyer = new SuperBuyer(['status' => 'active']);
        expect($buyer->isActive())->toBeTrue();
    });

    it('isActive() returns false for inactive', function () {
        $buyer = new SuperBuyer(['status' => 'inactive']);
        expect($buyer->isActive())->toBeFalse();
    });
});

describe('SuperBuyerOrder', function () {
    it('isRfq() returns true for rfq type', function () {
        $order = new SuperBuyerOrder(['order_type' => 'rfq']);
        expect($order->isRfq())->toBeTrue();
    });

    it('isRfq() returns false for normal type', function () {
        $order = new SuperBuyerOrder(['order_type' => 'normal']);
        expect($order->isRfq())->toBeFalse();
    });
});

describe('ImportRequest', function () {
    it('has correct status constants', function () {
        expect(ImportRequest::STATUS_NEW)->toBe('new');
        expect(ImportRequest::STATUS_PROCESSING)->toBe('processing');
        expect(ImportRequest::STATUS_COMPLETED)->toBe('completed');
        expect(ImportRequest::STATUS_FAILED)->toBe('failed');
    });

    it('statusOptions() returns all 4 statuses', function () {
        $options = ImportRequest::statusOptions();
        expect($options)->toHaveKeys(['new', 'processing', 'completed', 'failed']);
    });
});
```

### Bước 3: Viết Feature Tests (HTTP)

**Path:** `app/tests/Feature/<FeatureName>Test.php`

**Patterns phổ biến:**
```php
<?php

use Acelle\Model\User;
use Acelle\Model\Customer;

// Auth protection:
it('redirects unauthenticated to super buyer login', function () {
    $response = $this->get('/brand/super-buyer/mobile/shops');
    $response->assertRedirect('/brand/super-buyer/mobile/login');
});

// Auth check — regular seller cannot access super buyer:
it('redirects seller (non-super-buyer) to super buyer login', function () {
    $user = User::whereHas('customer')->first();
    $response = $this->actingAs($user)->get('/brand/super-buyer/mobile/shops');
    $response->assertRedirect('/brand/super-buyer/mobile/login');
});

// JSON API response structure:
it('shops API returns correct structure', function () {
    $user = User::whereHas('superBuyer')->first();
    if (!$user) {
        test()->skip('No Super Buyer user in DB');
    }

    $response = $this->actingAs($user, 'api')
        ->getJson('/api/v1/brand/super-buyer/shops');

    $response->assertOk()
        ->assertJsonStructure([
            'status',
            'data' => [['uid', 'name', 'status', 'has_wordpress']],
            'meta' => ['total', 'page', 'per_page', 'page_count'],
        ]);
});

// Import Request CRUD:
it('seller can create import request', function () {
    $user = User::whereHas('customer')->first();

    $response = $this->actingAs($user)
        ->post('/brand/mobile/import-requests/store', [
            'platform' => 'shopee',
            'shop_url' => 'https://shopee.vn/test_shop',
        ]);

    $response->assertOk()
        ->assertJson(['status' => 'success']);
});

it('seller cannot edit import request in processing status', function () {
    $user = User::whereHas('customer')->first();
    $importRequest = \Acelle\Model\ImportRequest::factory()->create([
        'customer_id' => $user->customer->id,
        'status' => 'processing',
    ]);

    $response = $this->actingAs($user)
        ->post("/brand/mobile/import-requests/{$importRequest->uid}/update", [
            'platform' => 'lazada',
            'shop_url' => 'https://lazada.vn/test',
        ]);

    $response->assertStatus(404); // firstOrFail với status=new → 404
});
```

### Bước 4: Viết Dusk Browser Tests

**Path:** `app/tests/Browser/<Area>/<FeatureName>Test.php`

**Areas:**
- `Webapp/` → seller webapp (`/brand/mobile/*`)
- `SuperBuyer/` → Super Buyer webapp (`/brand/super-buyer/mobile/*`)
- `Admin/` → admin panel (`/admin/brand/*`)

**Smoke test template:**
```php
<?php

namespace Tests\Browser\SuperBuyer;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SuperBuyerSmokeTest extends DuskTestCase
{
    private static array $pages = [
        'Dashboard'  => '/brand/super-buyer/mobile',
        'Shops'      => '/brand/super-buyer/mobile/shops',
        'Orders'     => '/brand/super-buyer/mobile/orders',
        'Profile'    => '/brand/super-buyer/mobile/profile',

        // AJAX endpoints:
        'Shops List (AJAX)'  => '/brand/super-buyer/mobile/shops/list?page=1',
        'Orders List (AJAX)' => '/brand/super-buyer/mobile/orders/list?page=1',
    ];

    public function test_all_super_buyer_pages_load_without_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsSuperBuyer($browser);

            $failures = [];

            foreach (self::$pages as $name => $url) {
                try {
                    $browser->visit($url)->pause(1500);
                    $source = $browser->driver->getPageSource();

                    $errorPatterns = [
                        'Spatie\\LaravelIgnition', 'Whoops!', 'ErrorException',
                        'Stack trace:', 'Undefined property', 'Undefined variable',
                        'number_format(): Argument', 'Call to undefined method',
                    ];

                    foreach ($errorPatterns as $pattern) {
                        if (str_contains($source, $pattern)) {
                            $failures[] = "[{$name}] Error: '{$pattern}' at {$url}";
                            $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $name);
                            $browser->screenshot("error_superbuyer_{$safeName}");
                        }
                    }
                } catch (\Exception $e) {
                    $failures[] = "[{$name}] Exception: " . $e->getMessage();
                }
            }

            if (!empty($failures)) {
                $this->fail("Super Buyer smoke test failures:\n" . implode("\n", $failures));
            }
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

            $this->assertNoAjaxErrors($browser, '[id*="shop"], .shop-list', 'SuperBuyer Shops AJAX');
            $this->assertNoPageErrors($browser, 'SuperBuyer Shops');
        });
    }

    public function test_orders_ajax_list_loads(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsSuperBuyer($browser);

            $browser->visit('/brand/super-buyer/mobile/orders')
                ->waitFor('.animate-fade-in', 30)
                ->waitUntilMissing('[x-show="isLoading"]', 60)
                ->pause(2000);

            $this->assertNoPageErrors($browser, 'SuperBuyer Orders');
        });
    }
}
```

**RFQ-specific browser tests:**
```php
class RfqOrdersTest extends DuskTestCase
{
    public function test_rfq_badge_shows_in_order_list(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsCustomer($browser);

            $browser->visit('/brand/mobile/orders/list?page=1&perPage=20')
                ->pause(2000);

            // Không assert badge tồn tại (có thể không có RFQ orders trong test DB)
            // Chỉ assert không có PHP errors
            $this->assertNoPageErrors($browser, 'Orders List with RFQ support');
        });
    }

    public function test_rfq_filter_tab_exists(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsCustomer($browser);

            $browser->visit('/brand/mobile/orders')
                ->waitFor('.animate-fade-in', 30)
                ->pause(1000);

            // Assert RFQ filter tab tồn tại
            $hasRfqTab = $browser->driver->executeScript(
                "return document.body.textContent.includes('RFQ') ||
                        document.body.textContent.includes('Chờ duyệt RFQ');"
            );

            $this->assertTrue($hasRfqTab, 'RFQ filter tab not found in orders page');
            $this->assertNoPageErrors($browser, 'Orders with RFQ Filter');
        });
    }

    public function test_approve_rfq_endpoint_exists(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsCustomer($browser);

            // POST to approve-rfq with fake ID — should return 404 (not 500/405)
            $result = $browser->driver->executeScript("
                return fetch('/brand/mobile/orders/99999/approve-rfq', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]')?.content || '',
                        'Accept': 'application/json',
                    }
                }).then(r => r.status).catch(e => 'error');
            ");

            // 404 = route exists but order not found (expected)
            // 405 = route not registered (FAIL)
            // 500 = server error (FAIL)
            $this->assertNotEquals(405, $result, 'approve-rfq route not registered (405 Method Not Allowed)');
            $this->assertNotEquals(500, $result, 'approve-rfq route returned 500 Server Error');
        });
    }
}
```

### Bước 5: Chạy tests + fix

```bash
cd /Users/luan/apps/vbrand/app

# Unit + Feature tests:
./vendor/bin/pest tests/Unit/<FeatureName>Test.php
./vendor/bin/pest tests/Feature/<FeatureName>Test.php

# Dusk (cần app chạy local):
php artisan dusk tests/Browser/<Area>/<FeatureName>Test.php

# Visible mode để debug:
DUSK_HEADLESS_DISABLED=true php artisan dusk tests/Browser/<Area>/<FeatureName>Test.php --filter test_name
```

Nếu test fail:
- Đọc error message
- Fix code hoặc fix test (nếu test assert sai)
- Không commit tests đang fail

### Bước 6: Commit tests

```bash
cd /Users/luan/apps/vbrand/app

git add tests/Unit/<Name>Test.php tests/Feature/<Name>Test.php tests/Browser/<Area>/<Name>Test.php
git commit -m "test: add tests for <area> feature"
git push origin brand
```

---

## DuskTestCase helpers cần thêm khi implement Super Buyer

Thêm vào `app/tests/DuskTestCase.php`:

```php
/**
 * Login as Super Buyer for testing.
 */
protected function loginAsSuperBuyer(Browser $browser): Browser
{
    $user = \Acelle\Model\User::whereHas('superBuyer', function ($q) {
        $q->where('status', 'active');
    })->first();

    if (!$user) {
        $this->fail('No Super Buyer user found in database. Create one via tinker:
            $user = User::find(1);
            $user->superBuyer()->create(["name" => "Test Buyer", "status" => "active"]);
        ');
    }

    return $browser->loginAs($user);
}
```

---

## Test coverage target

| Area | Unit | Feature | Browser |
|------|------|---------|---------|
| RFQ (backend) | `RfqOrderTest.php` | `RfqApiTest.php` | `RfqOrdersTest.php` |
| Super Buyer | `SuperBuyerTest.php` | `SuperBuyerRouteTest.php` | `SuperBuyerSmokeTest.php`, `CheckoutTest.php` |
| Import Request | `ImportRequestTest.php` | `ImportRequestApiTest.php` | `ImportRequestTest.php` |
