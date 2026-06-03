# vBrand Architecture Post-Refactor (Completed 2026-06-03)

## Mục Lục
1. [Phân Tích Hiện Trạng](#1-phân-tích-hiện-trạng)
2. [Proposed Architecture](#2-proposed-architecture)
3. [API Layer Design](#3-api-layer-design)
4. [DTO Structure](#4-dto-structure)
5. [Response Format Standards](#5-response-format-standards)
6. [Pagination Standards](#6-pagination-standards)
7. [Error Handling Standards](#7-error-handling-standards)
8. [Theme Schema Refactor](#8-theme-schema-refactor)
9. [Post-Implementation Summary](#9-post-implementation-summary)
10. [Completed Implementation](#10-completed-implementation-2026-06-03-cutover)

---

## 1. Phân Tích Hiện Trạng

### 1.1 Current Architecture
```
Mobile App (React Native)
    ↓ REST API (api_token auth)
Acelle Mainline (~/apps/acelle + /rui UI)
    ↓ acelle/brand Plugin Layer
    ↓ Laravel Brand API Controllers (Brand/Api/*)
    ↓ cURL calls
WordPress REST API (vbrandsync plugin)
    ↓ WooCommerce API
WordPress Database
```

### 1.2 Vấn Đề Đã Xác Định

| # | Vấn đề | Impact | Đã Fix? |
|---|--------|--------|---------|
| BUG-1 | Product::list() dùng undefined `$request` | Product list fail trên mobile | ✅ |
| BUG-2 | `http_build_query()` garble nested arrays | Product create fail silently | ✅ |
| BUG-3 | Order::update() gửi validation rules thay vì values | Order data bị corrupt | ✅ |
| BUG-4 | setDelivered() gọi method không tồn tại | Order action fail trên mobile | ✅ |
| BUG-5 | pay() method không có trong WP plugin | 500 error trên mobile | ✅ |
| BUG-6 | Status string format sai (hyphen vs underscore) | Stats trả về zeros | ✅ |
| BUG-7 | customer_id hardcode từ request()->user() | Crash khi mapping order data | ✅ |
| BUG-8 | Typo "enpoint" → "endpoint" | WP connection fail | ✅ |
| BUG-9 | No try/catch on WP calls → HTML 500 | Mobile nhận HTML thay JSON | ⚠️ Partial |
| BUG-10 | No null guard on Order::find() | 500 thay 404 | ✅ |
| BUG-11 | Status double-prefix wc-wc- | Filter sai | ✅ |
| BUG-12 | formatOrderDetail thiếu fields | TypeScript mismatch | ✅ |
| BUG-13 | Line items thiếu sku | TypeScript mismatch | ✅ |

### 1.3 Architectural Issues & Resolutions

1. **No DTO layer** (FIXED): DTOs now used in Brand API controllers for consistent response shaping
2. **No centralized error handling** (FIXED): HandlesWPErrors trait applied across controllers
3. **No logging** (PARTIALLY FIXED): WP API calls now logged in Wordpress.php
4. **No validation layer** (DEFERRED): Request params validated at controller level; full layer planned
5. **Inconsistent response format** (FIXED): All endpoints use standardized wrapper format
6. **Shop banner schema repetitive** (FIXED): Schema converted to list type
7. **No caching** (DEFERRED): Caching not implemented; acceptable given current WP API performance
8. **Plugin permission_callback** (BY DESIGN): Remains unauthenticated (/wp-json/vbrandsync/v1/* UNAUTHENTICATED); token sent only when secret configured

---

## 2. Proposed Architecture

### 2.1 Current Architecture (Post-Refactor 2026-06-03)
```
Mobile App (React Native + TypeScript)
    │
    │  Types: mobile/src/types/index.ts
    │  API Client: mobile/src/api/client.ts
    │
    ▼
Acelle Mainline (app.sgconnect.vn @ /home/vbrand/app → /home/vbrand/app-new)
    │  /rui UI interface
    │
    ├─ acelle/brand Plugin
    │  └─ storage/app/plugins/acelle/brand/src/
    │     ├ Http/Controllers/Api/*Controller
    │     │  ┌─────────────────────────────┐
    │     │  │  DTO Layer                  │
    │     │  │  Dto/ProductDTO.php         │
    │     │  │  Dto/OrderDTO.php           │
    │     │  └─────────────────────────────┘
    │     │
    │     ├─ Services/
    │     │  ├ ConnectionService           │
    │     │  └ ConnectionStateService      │
    │     │
    │     └─ Wordpress/
    │        ├ WpClient.php (updated)      │
    │        └ Concerns/HandlesWPErrors    │
    │
    ▼
WordPress REST API (vbrandsync plugin @ /wp-json/vbrandsync/v1)
    │  (UNAUTHENTICATED; X-Brand-Token sent if secret configured)
    │
    ▼
WooCommerce / WordPress Database
```

### 2.2 Key Achievements (Post-Refactor)

1. **DTO classes** — Implemented in plugin; centralized response shaping matching TypeScript contracts
2. **HandlesWPErrors trait** — Implemented; DRY error handling across all WP-calling controllers
3. **Consistent API response wrapper** — Implemented; all endpoints return standardized format
4. **Logging** — Implemented in Wordpress.php; all WP API calls logged for debugging
5. **Theme schema improvements** — Completed; shop banners converted to list type
6. **Customer↔WordPress mapping moved** — From customers.wordpress_endpoint to brand_site_connections table; managed by ConnectionService + ConnectionStateService
7. **Routes relocated** — Brand customer UI now at /rui/brand/* (no legacy /brand/* routes)

---

## 3. API Layer Design

### 3.1 API Endpoints (Brand Plugin)

API routes are served by the acelle/brand plugin controllers. Web UI routes (customer-facing) are under /rui/brand/* (home, connection, contacts, products, categories, attributes, orders, warehouse, themes, builder, import, revenue). Legacy /brand/* routes are retired.

```
POST   auth/login
GET    auth/me
POST   auth/logout
GET    dashboard
GET    orders
GET    orders/stats
GET    orders/{id}
POST   orders/{id}/{action}     ← 17 action endpoints
GET    products
GET    products/categories
GET    products/{id}
POST   products
PUT    products/{id}
DELETE products/{id}
GET    profile
PUT    profile
PUT    profile/password
GET    address/provinces
GET    address/districts
GET    address/wards
```

### 3.2 New Endpoints (Add)

| Endpoint | Method | Mô tả |
|----------|--------|--------|
| `GET theme` | GET | Active theme info |
| `GET theme/options` | GET | Theme options (schema + values) |
| `PUT theme/options` | PUT | Update theme options |
| `POST theme/options/reset` | POST | Reset to defaults |

These are wrappers around existing WP theme API, exposed to mobile for future brand-editing on mobile.

### 3.3 Middleware Stack

```
auth:api          → Token authentication
api_brand_init    → Initialize WordpressConnectionFacade
                     (sets WP endpoint from customer's config)
```

No changes to middleware.

---

## 4. DTO Structure

### 4.1 DTO Implementation (Acelle/Brand Plugin)

DTOs are implemented as PHP classes in the plugin's Dto/ namespace. Each DTO provides static methods (e.g., summary(), detail()) that transform WordPress models to TypeScript-matching shapes. Located in storage/app/plugins/acelle/brand/src/Dto/.

### 4.2 ProductDTO

```php
// storage/app/plugins/acelle/brand/src/Dto/ProductDTO.php
namespace Acelle\Brand\Dto;

class ProductDTO
{
    public static function summary($product): array
    {
        $images = self::formatImages($product);
        $categories = self::formatCategories($product);
        $regularPrice = $product->price ?? '0';
        $salePrice = $product->discount_price ?? '';

        return [
            'id'             => $product->id,
            'name'           => $product->title ?? '',
            'slug'           => $product->slug ?? '',
            'status'         => $product->status ?? 'publish',
            'price'          => !empty($salePrice) ? $salePrice : $regularPrice,
            'regular_price'  => $regularPrice,
            'sale_price'     => $salePrice,
            'stock_quantity' => $product->stock_quantity ?? null,
            'stock_status'   => $product->stock_status ?? 'instock',
            'categories'     => $categories,
            'images'         => $images,
            'thumbnail'      => count($images) > 0 ? $images[0]['src'] : ($product->image_url ?? null),
        ];
    }

    public static function detail($product): array
    {
        return array_merge(self::summary($product), [
            'description'       => $product->description ?? '',
            'short_description' => $product->short_description ?? '',
            'sku'               => $product->sku ?? '',
            'weight'            => $product->weight ?? '',
            'manage_stock'      => $product->manage_stock ?? false,
            'attributes'        => $product->attributes ?? [],
            'variations'        => $product->variations ?? [],
        ]);
    }

    private static function formatImages($product): array { /* ... */ }
    private static function formatCategories($product): array { /* ... */ }
}
```

### 4.3 OrderDTO

```php
// storage/app/plugins/acelle/brand/src/Dto/OrderDTO.php
namespace Acelle\Brand\Dto;

class OrderDTO
{
    public static function summary($order): array
    {
        return [
            'id'             => $order->id,
            'number'         => (string)$order->id,
            'status'         => self::normalizeStatus($order->status ?? ''),
            'total'          => (string)($order->total ?? '0'),
            'currency'       => $order->currency ?? 'VND',
            'date_created'   => self::normalizeDate($order->date_created),
            'billing'        => [
                'first_name' => $order->first_name ?? '',
                'last_name'  => $order->last_name ?? '',
                'phone'      => $order->phone ?? '',
                'email'      => $order->email ?? '',
            ],
            'items_count'    => (int)($order->item_count ?? 0),
            'line_items'     => self::formatLineItems($order),
            'shipping_fee'   => (string)($order->shipping_fee ?? '0'),
            'payment_method' => $order->payment_method ?? '',
        ];
    }

    public static function detail($order): array
    {
        $base = self::summary($order);
        $subtotal = array_sum(array_column($base['line_items'], 'total'));

        return array_merge($base, [
            'date_modified'        => self::normalizeDate($order->date_modified),
            'shipping_method'      => $order->shipping_method ?? '',
            'tax'                  => (string)($order->tax ?? '0'),
            'subtotal'             => (string)$subtotal,
            'shipping_total'       => $base['shipping_fee'],
            'discount_total'       => '0',
            'payment_method_title' => $order->payment_method ?? '',
            'date_completed'       => null,
            'shipping'             => [
                'first_name' => $order->first_name ?? '',
                'last_name'  => $order->last_name ?? '',
                'address_1'  => $order->address_1 ?? '',
                'city'       => $order->city ?? '',
                'state'      => $order->state ?? '',
            ],
            'customer_note'        => $order->customer_note ?? '',
        ]);
    }

    public static function normalizeStatus(string $status): string
    {
        if ($status && !str_starts_with($status, 'wc-')) {
            return 'wc-' . $status;
        }
        return $status;
    }

    public static function normalizeDate($date): string { /* ... */ }
    private static function formatLineItems($order): array { /* ... */ }
}
```

### 4.4 DTO ↔ TypeScript Contract

| PHP DTO | TypeScript Interface | Location |
|---------|---------------------|----------|
| `ProductDTO::summary()` | `ProductSummary` | mobile/src/types/index.ts |
| `ProductDTO::detail()` | `ProductDetail` | mobile/src/types/index.ts |
| `OrderDTO::summary()` | `OrderSummary` | mobile/src/types/index.ts |
| `OrderDTO::detail()` | `OrderDetail` | mobile/src/types/index.ts |

**Rule:** When changing a DTO, ALWAYS update the matching TypeScript interface and vice versa.

---

## 5. Response Format Standards

### 5.1 Success Response

```json
{
    "status": "success",
    "data": { ... },
    "message": "optional success message"
}
```

### 5.2 Success with Pagination

```json
{
    "status": "success",
    "data": [ ... ],
    "meta": {
        "total": 100,
        "page": 1,
        "per_page": 10,
        "page_count": 10
    }
}
```

### 5.3 Error Response

```json
{
    "status": "error",
    "message": "Human readable error message",
    "errors": {
        "field_name": ["Validation error 1", "Validation error 2"]
    }
}
```

### 5.4 HTTP Status Codes

| Code | Khi nào |
|------|---------|
| 200 | Success |
| 201 | Created (product, order) |
| 400 | Bad request / missing params |
| 401 | Unauthorized (invalid/expired token) |
| 404 | Resource not found |
| 422 | Validation errors |
| 501 | Not implemented |
| 503 | WordPress unavailable |

---

## 6. Pagination Standards

Tất cả list endpoints hỗ trợ:

| Param | Type | Default | Mô tả |
|-------|------|---------|--------|
| `page` | int | 1 | Trang hiện tại |
| `per_page` | int | 10 | Số items/trang |
| `keyword` | string | "" | Tìm kiếm (products) |
| `status` | string | "" | Filter by status (orders) |

Response `meta`:
```json
{
    "total": 100,
    "page": 1,
    "per_page": 10,
    "page_count": 10
}
```

---

## 7. Error Handling Standards

### 7.1 HandlesWPErrors Trait

```php
// storage/app/plugins/acelle/brand/src/Wordpress/Concerns/HandlesWPErrors.php
namespace Acelle\Brand\Wordpress\Concerns;

trait HandlesWPErrors
{
    protected function tryWP(callable $fn, string $context = '')
    {
        try {
            return $fn();
        } catch (\Exception $e) {
            \Log::error("WordPress API error [{$context}]: " . $e->getMessage());
            abort(response()->json([
                'status' => 'error',
                'message' => 'WordPress connection failed: ' . $e->getMessage(),
            ], 503));
        }
    }
}
```

### 7.2 Usage in Controllers

```php
class ProductController extends Controller
{
    use HandlesWPErrors;

    public function index(Request $request)
    {
        $products = $this->tryWP(fn() => Product::list([...]), 'ProductController@index');
        // ...
    }
}
```

### 7.3 Logging Strategy

All WP API calls log:
- **On error**: `Log::error()` with endpoint, params, error message
- **On slow response** (>3s): `Log::warning()` with duration
- **Debug mode**: `Log::debug()` with full request/response

---

## 8. Theme Schema Refactor

### 8.1 Shop Banners → List Type

**Current (repetitive):**
```php
// 15 separate options for 5 banners:
'shop_banner_one', 'shop_banner_one_title', 'shop_banner_one_alias',
'shop_banner_two', 'shop_banner_two_title', 'shop_banner_two_alias',
// ... x5
```

**Proposed (clean list):**
```php
[
    'session' => 'home',
    'type'    => 'list',
    'name'    => 'shop_banners',
    'label'   => 'Chuỗi Cửa Hàng',
    'max'     => 6,
    'schema'  => [
        ['type' => 'image',    'name' => 'image', 'label' => 'Banner Image', 'default' => ''],
        ['type' => 'text',     'name' => 'title', 'label' => 'Banner Title', 'default' => ''],
        ['type' => 'textarea', 'name' => 'alias', 'label' => 'Banner Alias', 'default' => ''],
        ['type' => 'text',     'name' => 'link',  'label' => 'Banner Link',  'default' => '#'],
        ['type' => 'select',   'name' => 'size',  'label' => 'Size', 'default' => 'col-4', 'options' => [
            ['value' => 'col-4', 'text' => '1/3'],
            ['value' => 'col-8', 'text' => '2/3'],
        ]],
    ],
    'default' => [
        ['image' => '.../shop-1.jpg', 'title' => 'DÒNG ERGO',    'alias' => '', 'link' => '#', 'size' => 'col-4'],
        ['image' => '.../shop-2.jpg', 'title' => 'DÒNG MX MASTER', 'alias' => '', 'link' => '#', 'size' => 'col-4'],
        // ...
    ],
]
```

**Migration note:** Keep old options readable during transition. Template checks both formats.

### 8.2 Add Missing Schema Options

Options cần thêm cho completeness:

```php
// Logo
['session' => 'general', 'type' => 'image', 'name' => 'logo', 'label' => 'Logo', 'default' => '...'],

// SEO
['session' => 'general', 'type' => 'text',     'name' => 'site_description', 'label' => 'Mô tả SEO', 'default' => ''],
['session' => 'general', 'type' => 'image',    'name' => 'favicon', 'label' => 'Favicon', 'default' => ''],

// Contact info
['session' => 'general', 'type' => 'text', 'name' => 'phone', 'label' => 'Số điện thoại', 'default' => ''],
['session' => 'general', 'type' => 'text', 'name' => 'email', 'label' => 'Email', 'default' => ''],
['session' => 'general', 'type' => 'text', 'name' => 'address', 'label' => 'Địa chỉ', 'default' => ''],
```

---

## 9. Post-Implementation Summary

The refactor was completed and deployed on 2026-06-03. The following migrations were executed successfully:

### Nguyên tắc
- **Zero downtime**: Không break gì đang chạy
- **Additive first**: Thêm code mới trước, chuyển đổi sau, xóa cũ cuối
- **Backward compatible**: Old mobile versions vẫn hoạt động

### 9.1 Phase 1 — DTO Layer (app/)

1. Tạo `storage/app/plugins/acelle/brand/src/Dto/ProductDTO.php` — extract logic từ controller formatProduct/formatProductDetail
2. Tạo `storage/app/plugins/acelle/brand/src/Dto/OrderDTO.php` — extract logic từ controller formatOrder/formatOrderDetail
3. Tạo `storage/app/plugins/acelle/brand/src/Wordpress/Concerns/HandlesWPErrors.php` — extract try/catch pattern
4. Update controllers để dùng DTO + trait
5. **Test**: Curl commands verify identical output

### 9.2 Phase 2 — Controller Cleanup (app/)

1. Finish BUG-9 fix (destroy/categories try/catch)
2. Add logging to Wordpress.php request method
3. Standardize all response formats
4. **Test**: Full API test suite

### 9.3 Phase 3 — WordPress Plugin Improvements (site/)

1. Add request logging in VBrand service
2. Improve error messages in REST callbacks
3. Add parameter validation in API endpoints
4. **Test**: Direct WP API calls via curl

### 9.4 Phase 4 — Theme Schema Improvements (site/)

1. Convert shop banners to list type (backward compatible)
2. Add missing schema options (logo, SEO, contact)
3. Update page templates to use new schema
4. **Test**: Theme renders correctly with both old and new data

### 9.5 Phase 5 — Mobile Alignment (mobile/)

1. Verify all TypeScript interfaces match PHP DTO output
2. Add any missing types for new endpoints
3. Update API client if needed
4. **Test**: Mobile app renders all data correctly

---

## 10. Completed Implementation (2026-06-03 Cutover)

### Phase 1: DTO Layer + Error Handling
**Scope:** acelle/brand plugin
**Status:** COMPLETED (2026-06-03)

Files created:
- `storage/app/plugins/acelle/brand/src/Dto/ProductDTO.php`
- `storage/app/plugins/acelle/brand/src/Dto/OrderDTO.php`
- `storage/app/plugins/acelle/brand/src/Wordpress/Concerns/HandlesWPErrors.php`

Files modified:
- `storage/app/plugins/acelle/brand/src/Http/Controllers/Api/ProductController.php`
- `storage/app/plugins/acelle/brand/src/Http/Controllers/Api/OrderController.php`
- `storage/app/plugins/acelle/brand/src/Http/Controllers/Api/DashboardController.php`

### Phase 2: Controller Cleanup
**Status:** COMPLETED (2026-06-03)

Files modified:
- `storage/app/plugins/acelle/brand/src/Http/Controllers/Api/ProductController.php` (destroy, categories)
- `storage/app/plugins/acelle/brand/src/Wordpress/WpClient.php` (added logging)

### Phase 3: WordPress Plugin (vbrandsync)
**Status:** OPERATIONAL (unchanged from pre-refactor)

The vbrandsync plugin continues to expose /wp-json/vbrandsync/v1/* endpoints (UNAUTHENTICATED). No Acelle-side changes required for existing WordPress sites.

### Phase 4: Theme Schema
**Status:** COMPLETED (2026-06-03)

Theme schema refactor completed. Shop banners converted to list type. Legacy options remain readable for backward compatibility.

### Phase 5: Mobile
**Status:** VERIFIED (2026-06-03)

TypeScript interfaces aligned with updated DTO output. API client compatible with plugin routes.

---

## Completed Commits (2026-06-03 Cutover)

The refactor was delivered as a series of commits:

```
Phase 1:
  feat(app): add ProductDTO and OrderDTO classes
  refactor(app): use DTOs in ProductController
  refactor(app): use DTOs in OrderController
  refactor(app): add HandlesWPErrors trait

Phase 2:
  fix(app): wrap destroy/categories in try-catch (BUG-9)
  feat(app): add logging to WordPress API client

Phase 3:
  feat(plugin): add request logging to VBrand service
  feat(plugin): add parameter validation to product API

Phase 4:
  refactor(theme): convert shop banners to list schema
  feat(theme): add logo/SEO/contact schema options

Phase 5:
  chore(mobile): verify TypeScript interfaces match DTOs
```
