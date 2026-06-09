> ⚠️ **SUPERSEDED — not a source of truth.** This describes the retired forked vBrand app (`~/apps/vbrand/app`). The current source of truth for Super Buyer is **`~/apps/acelle_brand/docs/guide/SUPER_BUYER.md`**. Kept for historical reference only.

# vBrand Super Buyer - Complete Technical Documentation

## Mục Lục
1. [Tổng Quan](#1-tổng-quan)
2. [Database Design](#2-database-design)
3. [Authentication & Middleware](#3-authentication--middleware)
4. [Architecture: WP Connection Switching](#4-architecture-wp-connection-switching)
5. [Routes](#5-routes)
6. [Controllers](#6-controllers)
7. [Views & Layout](#7-views--layout)
8. [Checkout Flow (Order Creation)](#8-checkout-flow-order-creation)
9. [Order Management](#9-order-management)
10. [API Contracts (Mobile App)](#10-api-contracts-mobile-app)
11. [Artisan Commands](#11-artisan-commands)
12. [Implementation Checklist](#12-implementation-checklist)

---

## 1. Tổng Quan

### Super Buyer là gì?
Super Buyer là vai trò đặc biệt trong hệ thống — **1 buyer duy nhất** có thể:
- Xem tất cả shops (customers có `wordpress_endpoint`)
- Browse sản phẩm từ bất kỳ shop nào
- Tạo đơn hàng (Normal hoặc RFQ) trên bất kỳ shop nào
- Quản lý đơn hàng của mình across all shops

### Concept chính
- Super Buyer có **webapp riêng** tại `/brand/super-buyer/mobile/*` với **theme cam (orange)** để phân biệt với seller (indigo)
- Super Buyer **không có 1 WP connection cố định** — connection được switch per-request theo shop đang browse
- Đơn hàng Super Buyer được track trên **cả 2 nơi**: WooCommerce (order thật) + Laravel DB (`super_buyer_orders` table) để có thể list tất cả orders across shops
- Super Buyer access qua **login riêng** hoặc **admin switch** (link từ admin panel)

### So sánh Seller vs Super Buyer

| | Seller (Customer) | Super Buyer |
|---|---|---|
| WP Connection | 1 fixed (wordpress_endpoint) | Switch per request |
| Orders | Trên 1 WP site | Across nhiều WP sites |
| Products | Quản lý SP của mình | Browse SP tất cả shops |
| Theme | Indigo | Orange |
| Route prefix | `brand/mobile` | `brand/super-buyer/mobile` |
| DB tracking | Không cần (1 WP site) | `super_buyer_orders` table |

---

## 2. Database Design

### 2.1 `super_buyers` Table

```sql
CREATE TABLE super_buyers (
    id              BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id         BIGINT UNSIGNED NOT NULL,       -- FK → users.id
    name            VARCHAR(255) NOT NULL,
    phone           VARCHAR(50) NULL,
    company_name    VARCHAR(255) NULL,
    status          VARCHAR(50) DEFAULT 'active',   -- active | inactive
    created_at      TIMESTAMP NULL,
    updated_at      TIMESTAMP NULL,
    INDEX (user_id),
    INDEX (status)
);
```

**Rationale:** Tách riêng table thay vì thêm `role` column vào `Customer` vì:
- `Customer` model (1800+ lines) coupled chặt với `wordpress_endpoint` và seller logic
- Super Buyer **không phải Customer** — không có WP site riêng
- Chỉ có 1 Super Buyer trong hệ thống, nhưng table design cho phép mở rộng sau

**Migration file:** `app/database/migrations/2026_03_17_000001_create_super_buyers_table.php`

### 2.2 `super_buyer_orders` Table

```sql
CREATE TABLE super_buyer_orders (
    id              BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    uid             VARCHAR(36) UNIQUE NOT NULL,
    super_buyer_id  BIGINT UNSIGNED NOT NULL,       -- FK → super_buyers.id
    customer_id     BIGINT UNSIGNED NOT NULL,        -- FK → customers.id (shop)
    wc_order_id     BIGINT UNSIGNED NOT NULL,        -- WooCommerce order ID
    order_type      VARCHAR(20) DEFAULT 'normal',    -- normal | rfq
    rfq_price       DECIMAL(15,2) NULL,
    rfq_status      VARCHAR(30) NULL,                -- rfq_pending | rfq_approved | null
    total           DECIMAL(15,2) NULL,
    status          VARCHAR(50) NOT NULL,            -- mirror WC order status
    created_at      TIMESTAMP NULL,
    updated_at      TIMESTAMP NULL,
    INDEX (super_buyer_id),
    UNIQUE (customer_id, wc_order_id)
);
```

**Rationale:** Cần tracking table vì:
- Orders nằm rải rác trên **nhiều WP sites khác nhau**
- Không thể query tất cả WP sites cùng lúc để list "all my orders"
- Table này cho phép Eloquent query nhanh cho order list, rồi dùng `wc_order_id` + `customer_id` để lấy detail từ WP khi cần

**Migration file:** `app/database/migrations/2026_03_17_000002_create_super_buyer_orders_table.php`

### 2.3 Eloquent Models

#### `Acelle\Model\SuperBuyer`
File: `app/app/Model/SuperBuyer.php`

```php
namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use Acelle\Library\Traits\HasUid;

class SuperBuyer extends Model
{
    use HasUid;

    protected $fillable = ['user_id', 'name', 'phone', 'company_name', 'status'];

    public function user()
    {
        return $this->belongsTo('Acelle\Model\User');
    }

    public function orders()
    {
        return $this->hasMany('Acelle\Model\SuperBuyerOrder');
    }

    public function isActive()
    {
        return $this->status === 'active';
    }
}
```

#### `Acelle\Model\SuperBuyerOrder`
File: `app/app/Model/SuperBuyerOrder.php`

```php
namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use Acelle\Library\Traits\HasUid;

class SuperBuyerOrder extends Model
{
    use HasUid;

    protected $fillable = [
        'super_buyer_id', 'customer_id', 'wc_order_id',
        'order_type', 'rfq_price', 'rfq_status', 'total', 'status',
    ];

    public function superBuyer()
    {
        return $this->belongsTo('Acelle\Model\SuperBuyer');
    }

    public function customer()
    {
        return $this->belongsTo('Acelle\Model\Customer');
    }

    public function isRfq()
    {
        return $this->order_type === 'rfq';
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByOrderType($query, $type)
    {
        return $query->where('order_type', $type);
    }
}
```

#### User Model — thêm relationship
File: `app/app/Model/User.php`

```php
// Thêm sau customer() relationship (line ~98)
public function superBuyer()
{
    return $this->hasOne('Acelle\Model\SuperBuyer');
}
```

### 2.4 New DTO

#### `Acelle\DTOs\ShopDTO`
File: `app/app/DTOs/ShopDTO.php`

```php
namespace Acelle\DTOs;

class ShopDTO
{
    public static function summary($customer): array
    {
        return [
            'uid' => $customer->uid,
            'name' => $customer->displayName(),
            'phone' => $customer->phone ?? null,
            'status' => $customer->status,
            'has_wordpress' => !empty($customer->wordpress_endpoint),
        ];
    }
}
```

---

## 3. Authentication & Middleware

### 3.1 Login riêng cho Super Buyer

Super Buyer có trang login riêng tại `/brand/super-buyer/mobile/login`. Flow:
1. User nhập email + password → `Auth::attempt()`
2. Check `$user->superBuyer` exists và `isActive()`
3. Nếu OK → redirect tới `superbuyer.dashboard`
4. Nếu không phải Super Buyer → show error "Tài khoản không có quyền Super Buyer"

**Controller:** `app/app/Http/Controllers/Brand/SuperBuyer/AuthController.php` — clone từ `Brand\Webapp\AuthController`, đổi:
- View prefix: `superbuyer.auth.*` thay `webapp.auth.*`
- Route names: `superbuyer.*` thay `webapp.*`
- Check `$user->superBuyer` thay vì `$user->customer`

### 3.2 Admin Switch

Admin có link "Xem trang Buyer →" trong admin panel → redirect tới `/brand/super-buyer/mobile/`. Vì dùng chung session auth, admin đã đăng nhập nên chỉ cần middleware check `$user->superBuyer`.

> **CRITICAL:** Cần tạo Super Buyer record cho admin user trước khi sử dụng. Tạo qua tinker hoặc seed:
> ```php
> $user = \Acelle\Model\User::find(1); // admin user
> $user->superBuyer()->create(['name' => 'Super Buyer', 'status' => 'active']);
> ```

### 3.3 Middleware: `SuperBuyerInit`

File: `app/app/Http/Middleware/SuperBuyerInit.php`

```php
namespace Acelle\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SuperBuyerInit
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user || !$user->superBuyer || !$user->superBuyer->isActive()) {
            return redirect()->route('superbuyer.login');
        }

        return $next($request);
    }
}
```

> **Key difference vs `Frontend` middleware:** `Frontend` middleware (line 90-92) gọi `WordpressConnectionFacade::setWordpress($user->customer->wordpress())`. `SuperBuyerInit` **KHÔNG set WP connection** vì Super Buyer browse nhiều shops — connection được set per-request trong controllers.

Đăng ký middleware alias trong `app/app/Http/Kernel.php`:
```php
'super_buyer' => \Acelle\Http\Middleware\SuperBuyerInit::class,
```

---

## 4. Architecture: WP Connection Switching

### 4.1 Vấn đề
Hệ thống hiện tại dùng **singleton WordPress connection per request** qua `WordpressConnectionFacade`:
- `Frontend` middleware set connection từ `$user->customer->wordpress()`
- Tất cả WordPress models (`Order`, `Product`, etc.) đọc connection từ facade
- → Mỗi request chỉ query 1 WP site duy nhất

Super Buyer cần browse **nhiều shops khác nhau** → cần switch connection.

### 4.2 Solution: Per-Request Connection Switching

Trong mỗi controller action của Super Buyer, **switch connection theo shop** trước khi gọi WP models:

```php
// Pattern chuẩn cho Super Buyer controllers
public function products(Request $request, $shopUid)
{
    // 1. Find shop (customer)
    $customer = \Acelle\Model\Customer::findByUid($shopUid);
    if (!$customer || !$customer->wordpress_endpoint) {
        abort(404, 'Shop không tồn tại');
    }

    // 2. Switch WP connection
    \Acelle\Library\Facades\WordpressConnectionFacade::setWordpress(
        $customer->wordpress()
    );

    // 3. Gọi WP models — sẽ query shop này
    $products = \Acelle\Wordpress\Product::list([
        'per_page' => $request->input('per_page', 20),
        'page' => $request->input('page', 1),
    ]);

    return view('superbuyer.products.index', compact('products', 'customer'));
}
```

> **Note:** Pattern này **giống hệt** Admin OrderController (`app/app/Http/Controllers/Admin/Brand/OrderController.php`) khi admin xem orders của một customer cụ thể. Admin cũng switch WP connection per customer.

### 4.3 Connection Classes

| Class | File | Role |
|-------|------|------|
| `Wordpress` | `app/app/Wordpress/Wordpress.php` | HTTP client, cURL wrapper tới WP REST API |
| `WordpressConnection` | `app/app/Wordpress/WordpressConnection.php` | Singleton holder: `setWordpress()` / `getWordpress()` |
| `WordpressConnectionFacade` | `app/app/Library/Facades/WordpressConnectionFacade.php` | Laravel facade → resolves to `WordpressConnection` |

```
SuperBuyer Controller
    → Customer::findByUid($shopUid)
        → $customer->wordpress()  // returns new Wordpress($endpoint)
    → WordpressConnectionFacade::setWordpress($wordpress)
    → Product::list() / Order::find()
        → Product::wordpress()  // reads from facade
            → WordpressConnectionFacade::getWordpress()
                → returns the Wordpress instance we just set
            → $wordpress->request('GET', 'product/list', ...)
                → cURL to {customer's WP endpoint}/product/list
```

---

## 5. Routes

### 5.1 Route File

File: `app/routes/brand_superbuyer.php` (FILE MỚI)

```php
<?php

/**
 * Super Buyer Webapp Routes
 * Prefix: brand/super-buyer/mobile
 */

// Guest routes (login)
Route::group([
    'namespace' => '\Acelle\Http\Controllers',
    'middleware' => ['not_installed'],
    'prefix' => 'brand/super-buyer/mobile',
], function () {
    Route::get('login', 'Brand\SuperBuyer\AuthController@showLogin')
        ->name('superbuyer.login');
    Route::post('login', 'Brand\SuperBuyer\AuthController@login')
        ->name('superbuyer.login.post');
});

// Authenticated routes
Route::group([
    'namespace' => '\Acelle\Http\Controllers',
    'middleware' => ['not_installed', 'auth', 'super_buyer'],
    'prefix' => 'brand/super-buyer/mobile',
], function () {

    // Dashboard
    Route::get('/', 'Brand\SuperBuyer\DashboardController@index')
        ->name('superbuyer.dashboard');

    // Auth
    Route::post('logout', 'Brand\SuperBuyer\AuthController@logout')
        ->name('superbuyer.logout');

    // Shops
    Route::get('shops', 'Brand\SuperBuyer\ShopController@index')
        ->name('superbuyer.shops');
    Route::get('shops/list', 'Brand\SuperBuyer\ShopController@list')
        ->name('superbuyer.shops.list');

    // Products (per shop)
    Route::get('shops/{shopUid}/products', 'Brand\SuperBuyer\ProductController@index')
        ->name('superbuyer.products');
    Route::get('shops/{shopUid}/products/list', 'Brand\SuperBuyer\ProductController@list')
        ->name('superbuyer.products.list');
    Route::get('shops/{shopUid}/products/{productId}', 'Brand\SuperBuyer\ProductController@show')
        ->name('superbuyer.products.show');

    // Checkout
    Route::get('shops/{shopUid}/checkout/{productId}', 'Brand\SuperBuyer\CheckoutController@index')
        ->name('superbuyer.checkout');
    Route::post('checkout/submit', 'Brand\SuperBuyer\CheckoutController@submit')
        ->name('superbuyer.checkout.submit');

    // Orders
    Route::get('orders', 'Brand\SuperBuyer\OrderController@index')
        ->name('superbuyer.orders');
    Route::get('orders/list', 'Brand\SuperBuyer\OrderController@list')
        ->name('superbuyer.orders.list');
    Route::get('orders/{id}', 'Brand\SuperBuyer\OrderController@show')
        ->name('superbuyer.orders.show');
    Route::post('orders/{id}/cancel', 'Brand\SuperBuyer\OrderController@cancel')
        ->name('superbuyer.orders.cancel');

    // Profile
    Route::get('profile', 'Brand\SuperBuyer\ProfileController@index')
        ->name('superbuyer.profile');
    Route::post('profile/update', 'Brand\SuperBuyer\ProfileController@update')
        ->name('superbuyer.profile.update');
});
```

### 5.2 Register Routes

File: `app/app/Providers/RouteServiceProvider.php`

Thêm vào `map()` method (follow pattern hiện có):
```php
// Thêm sau require brand_webapp.php
require base_path('routes/brand_superbuyer.php');
```

### 5.3 API Routes (cho mobile app sau này)

File: `app/routes/brand_api_v1.php` — thêm group mới:

```php
// Super Buyer API
Route::group([
    'middleware' => ['auth:api'],
    'prefix' => 'super-buyer',
], function () {
    Route::get('shops', 'Brand\SuperBuyer\Api\ShopController@index');
    Route::get('shops/{shopUid}/products', 'Brand\SuperBuyer\Api\ProductController@index');
    Route::get('shops/{shopUid}/products/{id}', 'Brand\SuperBuyer\Api\ProductController@show');
    Route::post('orders', 'Brand\SuperBuyer\Api\OrderController@store');
    Route::get('orders', 'Brand\SuperBuyer\Api\OrderController@index');
    Route::get('orders/{id}', 'Brand\SuperBuyer\Api\OrderController@show');
    Route::post('orders/{id}/cancel', 'Brand\SuperBuyer\Api\OrderController@cancel');
});
```

---

## 6. Controllers

### 6.1 Controller Directory

```
app/app/Http/Controllers/Brand/SuperBuyer/
    AuthController.php          ← Login/logout
    DashboardController.php     ← Dashboard stats
    ShopController.php          ← List shops
    ProductController.php       ← Browse shop products
    CheckoutController.php      ← Create order (Normal/RFQ)
    OrderController.php         ← List/view/cancel orders
    ProfileController.php       ← Super Buyer profile
```

### 6.2 ShopController

```php
namespace Acelle\Http\Controllers\Brand\SuperBuyer;

use Acelle\Model\Customer;
use Acelle\DTOs\ShopDTO;

class ShopController extends Controller
{
    public function index()
    {
        return view('superbuyer.shops.index');
    }

    public function list(Request $request)
    {
        $query = Customer::whereNotNull('wordpress_endpoint')
            ->where('wordpress_endpoint', '!=', '');

        // Search
        if ($keyword = $request->input('keyword')) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('phone', 'like', "%{$keyword}%");
            });
        }

        $customers = $query->orderBy('name')->paginate(20);

        return view('superbuyer.shops._list', compact('customers'));
    }
}
```

### 6.3 ProductController

```php
namespace Acelle\Http\Controllers\Brand\SuperBuyer;

use Acelle\Model\Customer;
use Acelle\Wordpress\Product;
use Acelle\Library\Facades\WordpressConnectionFacade;

class ProductController extends Controller
{
    /**
     * Switch WP connection to target shop
     */
    private function connectToShop($shopUid)
    {
        $customer = Customer::findByUid($shopUid);
        if (!$customer || !$customer->wordpress_endpoint) {
            abort(404, 'Shop không tồn tại');
        }

        WordpressConnectionFacade::setWordpress($customer->wordpress());
        return $customer;
    }

    public function index(Request $request, $shopUid)
    {
        $customer = $this->connectToShop($shopUid);
        return view('superbuyer.products.index', compact('customer'));
    }

    public function list(Request $request, $shopUid)
    {
        $customer = $this->connectToShop($shopUid);

        $products = Product::list([
            'per_page' => $request->input('per_page', 20),
            'page' => $request->input('page', 1),
            'keyword' => $request->input('keyword'),
        ]);

        return view('superbuyer.products._list', compact('products', 'customer'));
    }

    public function show(Request $request, $shopUid, $productId)
    {
        $customer = $this->connectToShop($shopUid);
        $product = Product::find($productId);

        if (!$product) {
            abort(404, 'Sản phẩm không tồn tại');
        }

        return view('superbuyer.products.show', compact('product', 'customer'));
    }
}
```

### 6.4 CheckoutController (xem chi tiết Section 8)

### 6.5 OrderController

```php
namespace Acelle\Http\Controllers\Brand\SuperBuyer;

use Acelle\Model\SuperBuyerOrder;
use Acelle\Wordpress\Order;
use Acelle\DTOs\OrderDTO;
use Acelle\Library\Facades\WordpressConnectionFacade;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        return view('superbuyer.orders.index');
    }

    public function list(Request $request)
    {
        $superBuyer = $request->user()->superBuyer;

        $query = SuperBuyerOrder::where('super_buyer_id', $superBuyer->id)
            ->with('customer')
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($status = $request->input('status')) {
            $query->byStatus($status);
        }

        // Filter by order type
        if ($type = $request->input('order_type')) {
            $query->byOrderType($type);
        }

        $orders = $query->paginate(20);

        return view('superbuyer.orders._list', compact('orders'));
    }

    public function show(Request $request, $id)
    {
        $superBuyer = $request->user()->superBuyer;
        $sbOrder = SuperBuyerOrder::where('super_buyer_id', $superBuyer->id)
            ->findOrFail($id);

        // Switch WP connection to order's shop
        $customer = $sbOrder->customer;
        WordpressConnectionFacade::setWordpress($customer->wordpress());

        // Get full order from WP
        $order = Order::find($sbOrder->wc_order_id);

        return view('superbuyer.orders.show', compact('order', 'sbOrder', 'customer'));
    }

    public function cancel(Request $request, $id)
    {
        $superBuyer = $request->user()->superBuyer;
        $sbOrder = SuperBuyerOrder::where('super_buyer_id', $superBuyer->id)
            ->findOrFail($id);

        // Switch WP connection
        WordpressConnectionFacade::setWordpress($sbOrder->customer->wordpress());

        // Cancel on WP
        $order = Order::find($sbOrder->wc_order_id);
        $order->sellerCancel(); // reuse existing cancel method

        // Update tracking
        $sbOrder->update(['status' => 'cancelled']);

        return response()->json([
            'status' => 'success',
            'message' => 'Đã hủy đơn hàng'
        ]);
    }
}
```

---

## 7. Views & Layout

### 7.1 Directory Structure

```
app/resources/views/superbuyer/
    layouts/
        app.blade.php               ← Main layout (ORANGE theme)
        _bottom_nav.blade.php       ← Bottom nav (4 tabs)
    auth/
        login.blade.php             ← Login page
    dashboard/
        index.blade.php             ← Stats + recent orders
    shops/
        index.blade.php             ← Search bar + AJAX list
        _list.blade.php             ← Shop cards partial (AJAX)
    products/
        index.blade.php             ← Product grid of 1 shop
        _list.blade.php             ← Product cards partial (AJAX)
        show.blade.php              ← Product detail + "Mua ngay"
    checkout/
        index.blade.php             ← Checkout form (simple)
    orders/
        index.blade.php             ← Order list + filters
        _list.blade.php             ← Order cards partial (AJAX)
        show.blade.php              ← Order detail
    profile/
        index.blade.php             ← Profile info
```

### 7.2 Layout — Orange Theme

File: `app/resources/views/superbuyer/layouts/app.blade.php`

Clone từ `webapp/layouts/app.blade.php`. Thay đổi chính:

**Color palette** — đổi from indigo to orange:
```javascript
// Trong Tailwind config (script tag)
brand: {
    50:  '#FFF7ED',
    100: '#FFEDD5',
    200: '#FED7AA',
    300: '#FDBA74',
    400: '#FB923C',
    500: '#F97316',
    600: '#EA580C',
    700: '#C2410C',
    800: '#9A3412',
    900: '#7C2D12',
    950: '#431407',
},
```

**Header** — đổi title và icon:
```html
<span class="material-icons-round text-brand-400">shopping_bag</span>
<span class="font-bold text-white">vBrand Buyer</span>
```

**Bottom nav items:**
```php
@php
$navItems = [
    ['route' => 'superbuyer.dashboard', 'icon' => 'dashboard', 'label' => 'Trang chủ', 'match' => 'superbuyer.dashboard'],
    ['route' => 'superbuyer.shops', 'icon' => 'store', 'label' => 'Cửa hàng', 'match' => 'superbuyer.shops'],
    ['route' => 'superbuyer.orders', 'icon' => 'receipt_long', 'label' => 'Đơn hàng', 'match' => 'superbuyer.orders'],
    ['route' => 'superbuyer.profile', 'icon' => 'person', 'label' => 'Tài khoản', 'match' => 'superbuyer.profile'],
];
@endphp
```

### 7.3 View Patterns (follow webapp)

Tất cả views follow **cùng pattern** với seller webapp:

| Pattern | Cách hoạt động |
|---------|----------------|
| AJAX list | Page load → Alpine.js `fetch()` tới `*.list` route → insert HTML partial |
| Action sheet | Bottom sheet popup cho confirm/actions |
| Toast | Flash messages qua `showToast()` |
| Loading overlay | `showLoading()` / `hideLoading()` khi gọi API |

---

## 8. Checkout Flow (Order Creation)

### 8.1 Flow tổng quan

```
Product Detail (show.blade.php)
    → Click "Mua ngay"
        → GET /brand/super-buyer/mobile/shops/{shopUid}/checkout/{productId}
            ← Render checkout form

Checkout Form (checkout/index.blade.php)
    → Nhập: qty, họ tên, SĐT, địa chỉ
    → Chọn: Đơn thường | Đơn RFQ
    → (nếu RFQ) Nhập giá đề xuất
    → Click "Đặt hàng"
        → POST /brand/super-buyer/mobile/checkout/submit
            → CheckoutController@submit
                → Switch WP connection
                → Order::add() → POST tới WP order/add
                → Save SuperBuyerOrder (Laravel DB)
                → Redirect tới order detail
```

### 8.2 CheckoutController

```php
namespace Acelle\Http\Controllers\Brand\SuperBuyer;

use Acelle\Model\Customer;
use Acelle\Model\SuperBuyerOrder;
use Acelle\Wordpress\Order;
use Acelle\Wordpress\Product;
use Acelle\Library\Facades\WordpressConnectionFacade;

class CheckoutController extends Controller
{
    public function index(Request $request, $shopUid, $productId)
    {
        $customer = Customer::findByUid($shopUid);
        if (!$customer || !$customer->wordpress_endpoint) {
            abort(404);
        }

        WordpressConnectionFacade::setWordpress($customer->wordpress());
        $product = Product::find($productId);

        if (!$product) {
            abort(404, 'Sản phẩm không tồn tại');
        }

        return view('superbuyer.checkout.index', compact('product', 'customer'));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'shop_uid' => 'required',
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'first_name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'required|string|max:500',
            'order_type' => 'required|in:normal,rfq',
            'rfq_price' => 'required_if:order_type,rfq|nullable|numeric|min:0',
        ]);

        // Find shop
        $customer = Customer::findByUid($request->shop_uid);
        if (!$customer || !$customer->wordpress_endpoint) {
            abort(404);
        }

        // Switch WP connection
        WordpressConnectionFacade::setWordpress($customer->wordpress());

        // Create order on WooCommerce
        $superBuyer = $request->user()->superBuyer;

        $order = new Order();
        $order->order_type = $request->order_type;
        $order->rfq_price = $request->rfq_price;
        $order->super_buyer_id = $superBuyer->id;

        $response = Order::wordpress()->request('POST', Order::URI_ADD, [
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'first_name' => $request->first_name,
            'last_name' => $request->input('last_name', ''),
            'phone' => $request->phone,
            'address_1' => $request->address,
            'order_type' => $request->order_type,
            'rfq_price' => $request->rfq_price,
            'super_buyer_id' => $superBuyer->id,
        ]);

        // Save tracking record in Laravel DB
        $sbOrder = SuperBuyerOrder::create([
            'super_buyer_id' => $superBuyer->id,
            'customer_id' => $customer->id,
            'wc_order_id' => $response['id'],
            'order_type' => $request->order_type,
            'rfq_price' => $request->rfq_price,
            'rfq_status' => $request->order_type === 'rfq' ? 'rfq_pending' : null,
            'total' => $response['total'] ?? 0,
            'status' => $response['status'] ?? 'ordered',
        ]);

        return redirect()->route('superbuyer.orders.show', ['id' => $sbOrder->id])
            ->with('success', 'Đặt hàng thành công!');
    }
}
```

### 8.3 Checkout View

**Key UI elements:**
- Product card: ảnh, tên, giá (from WP)
- Quantity input: `type="number"` min=1
- Thông tin giao hàng: Họ tên, SĐT, Địa chỉ
- **Order type toggle** (nổi bật):
  ```html
  <div class="grid grid-cols-2 gap-3" x-data="{ orderType: 'normal' }">
      <button @click="orderType = 'normal'"
          :class="orderType === 'normal' ? 'ring-2 ring-brand-500 bg-brand-50' : 'bg-gray-50'"
          class="p-4 rounded-xl text-center transition-all">
          <span class="material-icons-round text-2xl">shopping_cart</span>
          <div class="text-sm font-semibold mt-1">Đơn thường</div>
      </button>
      <button @click="orderType = 'rfq'"
          :class="orderType === 'rfq' ? 'ring-2 ring-orange-500 bg-orange-50' : 'bg-gray-50'"
          class="p-4 rounded-xl text-center transition-all">
          <span class="material-icons-round text-2xl">request_quote</span>
          <div class="text-sm font-semibold mt-1">Đơn RFQ</div>
      </button>
  </div>
  ```
- RFQ price input (show khi chọn RFQ):
  ```html
  <div x-show="orderType === 'rfq'" x-transition class="mt-3">
      <label class="text-xs text-gray-500">Giá đề xuất (VNĐ)</label>
      <input type="number" name="rfq_price" class="..." placeholder="Nhập giá mong muốn">
      <p class="text-xs text-gray-400 mt-1">
          Giá gốc: {{ number_format($product->price) }}₫
      </p>
  </div>
  ```
- Submit button: "Đặt hàng" (orange)

---

## 9. Order Management

### 9.1 Order List

Super Buyer order list query từ **`super_buyer_orders` table** (Eloquent) — NOT từ WP API. Lý do:
- Orders nằm trên nhiều WP sites → không thể query hết
- Tracking table có đủ info cho list view (status, total, order_type, rfq_price)
- Chỉ khi xem detail mới cần gọi WP API

**Filters:**
- Status: tất cả | ordered | rfq_pending | rfq_approved | packaging | ready_for_pickup | delivering | completed | cancelled | refunded
- Order type: tất cả | normal | rfq

**List item hiển thị:**
- Shop name (from `customer` relationship)
- Order ID (`#wc_order_id`)
- Status badge
- RFQ badge (nếu có) + rfq_price
- Total
- Created date

### 9.2 Order Detail

1. Lấy `SuperBuyerOrder` từ Laravel DB
2. Switch WP connection tới shop tương ứng
3. Gọi `Order::find($wc_order_id)` để lấy full detail từ WP
4. Render view với đầy đủ line items, billing info, status progress

### 9.3 Cancel Order

Super Buyer có thể cancel order:
1. Gọi `Order::sellerCancel()` trên WP (reuse existing method)
2. Chỉ cho phép khi order còn trước giao vận thực tế: `pending`, `ordered`, `processing`, `packaging`, `rfq_pending`
3. Update `super_buyer_orders.status = 'seller_cancelled'` và clear `rfq_status` để UI không giữ RFQ overlay sai sau khi hủy

---

## 10. API Contracts (Mobile App)

### 10.1 List Shops

```
GET /api/v1/brand/super-buyer/shops?keyword=nike&page=1&per_page=20

Response:
{
    "status": "success",
    "data": [
        { "uid": "abc123", "name": "Nike Vietnam", "phone": "0901234567", "status": "active", "has_wordpress": true }
    ],
    "meta": { "total": 15, "page": 1, "per_page": 20, "page_count": 1 }
}
```

### 10.2 Browse Products

```
GET /api/v1/brand/super-buyer/shops/{shopUid}/products?keyword=shoes&page=1&per_page=20

Response: (same format as existing GET /api/v1/brand/products, but for specific shop)
{
    "status": "success",
    "data": [ ProductDTO::summary() ... ],
    "meta": { ... }
}
```

### 10.3 Create Order

```
POST /api/v1/brand/super-buyer/orders

Body:
{
    "shop_uid": "abc123",
    "product_id": 45,
    "quantity": 2,
    "first_name": "Nguyen Van A",
    "phone": "0901234567",
    "address": "123 Nguyen Hue, Q1, HCM",
    "order_type": "rfq",
    "rfq_price": 150000
}

Response:
{
    "status": "success",
    "data": {
        "id": 1,
        "uid": "...",
        "wc_order_id": 789,
        "shop_name": "Nike Vietnam",
        "order_type": "rfq",
        "rfq_price": 150000,
        "total": 200000,
        "rfq_status": "rfq_pending",
        "status": "ordered",
        "created_at": "2026-03-17 10:00:00"
    },
    "message": "Đặt hàng thành công"
}
```

### 10.4 List Orders

```
GET /api/v1/brand/super-buyer/orders?rfq_status=rfq_pending&order_type=rfq&page=1

Response:
{
    "status": "success",
    "data": [
        {
            "id": 1,
            "uid": "...",
            "wc_order_id": 789,
            "shop_name": "Nike Vietnam",
            "shop_uid": "abc123",
            "order_type": "rfq",
            "rfq_price": 150000,
            "rfq_status": "rfq_pending",
            "total": 200000,
            "status": "ordered",
            "created_at": "2026-03-17 10:00:00"
        }
    ],
    "meta": { "total": 5, "page": 1, "per_page": 20, "page_count": 1 }
}
```

---

## 11. Artisan Commands

### `brand:set-buyer` — Tạo / kích hoạt Super Buyer

```bash
# Tạo bằng email
php artisan brand:set-buyer --user_email=buyer@example.com

# Tạo bằng user_id, đặt tên custom
php artisan brand:set-buyer --user_id=5 --name="Nguyen Van A"

# Deactivate
php artisan brand:set-buyer --user_email=buyer@example.com --deactivate
```

**Options:**

| Option | Mô tả |
|--------|-------|
| `--user_id` | ID của user trong bảng `users` |
| `--user_email` | Email của user (tìm trong bảng `users`) |
| `--name` | Tên hiển thị (mặc định lấy `user->name` hoặc email) |
| `--deactivate` | Set status = inactive thay vì active |

**Output:**
```
✓ Created Super Buyer
+----------+------------------------------------------+
| Field    | Value                                    |
+----------+------------------------------------------+
| User ID  | 5                                        |
| Email    | buyer@example.com                        |
| Name     | Nguyen Van A                             |
| Status   | active                                   |
| SB ID    | 1                                        |
| Login URL| https://yourdomain.com/brand/super-buyer/mobile/login |
+----------+------------------------------------------+
```

Command dùng `updateOrCreate` — chạy nhiều lần an toàn (idempotent).

---

## 12. Implementation Checklist

### New Files to Create

| # | File | Description |
|---|------|-------------|
| 1 | `app/database/migrations/..._create_super_buyers_table.php` | Migration |
| 2 | `app/database/migrations/..._create_super_buyer_orders_table.php` | Migration |
| 3 | `app/app/Model/SuperBuyer.php` | Eloquent model |
| 4 | `app/app/Model/SuperBuyerOrder.php` | Eloquent model |
| 5 | `app/app/DTOs/ShopDTO.php` | DTO |
| 6 | `app/app/Http/Middleware/SuperBuyerInit.php` | Middleware |
| 7 | `app/routes/brand_superbuyer.php` | Route file |
| 8 | `app/app/Http/Controllers/Brand/SuperBuyer/AuthController.php` | Controller |
| 9 | `app/app/Http/Controllers/Brand/SuperBuyer/DashboardController.php` | Controller |
| 10 | `app/app/Http/Controllers/Brand/SuperBuyer/ShopController.php` | Controller |
| 11 | `app/app/Http/Controllers/Brand/SuperBuyer/ProductController.php` | Controller |
| 12 | `app/app/Http/Controllers/Brand/SuperBuyer/CheckoutController.php` | Controller |
| 13 | `app/app/Http/Controllers/Brand/SuperBuyer/OrderController.php` | Controller |
| 14 | `app/app/Http/Controllers/Brand/SuperBuyer/ProfileController.php` | Controller |
| 15-25 | `app/resources/views/superbuyer/**/*.blade.php` | ~11 view files |

### Existing Files to Modify

| # | File | Change |
|---|------|--------|
| 1 | `app/app/Model/User.php` | Add `superBuyer()` relationship |
| 2 | `app/app/Http/Kernel.php` | Register `super_buyer` middleware alias |
| 3 | `app/app/Providers/RouteServiceProvider.php` | Register `brand_superbuyer.php` |
| 4 | `app/routes/brand_api_v1.php` | Add Super Buyer API routes |

### Implementation Order

1. **DB + Models** — migrations, models, User relationship
2. **Middleware + Routes** — SuperBuyerInit, route file, register
3. **Layout** — clone webapp layout, change to orange theme
4. **Shop browsing** — ShopController + views
5. **Product browsing** — ProductController with WP connection switching + views
6. **Checkout** — CheckoutController + view (depends on RFQ — `order/add` must be implemented first, see RFQ_DESIGN.md)
7. **Orders** — OrderController + views
8. **Auth** — AuthController + login view
9. **Dashboard + Profile** — last, simplest

### Verification

1. Login as Super Buyer → see orange-themed dashboard
2. Browse shops → see list of customers with WP
3. Click shop → see products (verify WP connection switching works)
4. Click product → see detail → "Mua ngay"
5. Checkout normal order → verify created on WP + tracked in Laravel
6. Checkout RFQ order → verify `rfq_pending` status on both sides
7. View order list → see orders from all shops with correct filters
8. Cancel order → verify status updated on both WP + Laravel
9. Admin switch → verify admin can access Super Buyer pages

---

*Related: [RFQ_DESIGN.md](RFQ_DESIGN.md) | [IMPORT_REQUEST_DESIGN.md](IMPORT_REQUEST_DESIGN.md) | [VBRAND_SYSTEM_DOCUMENTATION.md](VBRAND_SYSTEM_DOCUMENTATION.md)*
