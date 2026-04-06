# vBrand System - Complete Technical Documentation

## Mục Lục
1. [Tổng Quan Hệ Thống](#1-tổng-quan-hệ-thống)
2. [Kiến Trúc Tổng Thể](#2-kiến-trúc-tổng-thể)
3. [Laravel App (`/app`)](#3-laravel-app-app)
4. [WordPress Plugin VBrandSync (`/brandsite`)](#4-wordpress-plugin-vbrandsync-brandsite)
5. [API Communication: App ↔ WordPress](#5-api-communication-app--wordpress)
6. [Theme/Template System](#6-themetemplate-system)
7. [Order Management & Status Flow](#7-order-management--status-flow)
8. [Product Management](#8-product-management)
9. [Payment Integration (BaoKim)](#9-payment-integration-baokim)
10. [Shipping Integration](#10-shipping-integration)
11. [Authentication & Authorization](#11-authentication--authorization)
12. [Database & Data Flow](#12-database--data-flow)
13. [Feature Reference by Controller](#13-feature-reference-by-controller)
14. [API Endpoint Reference](#14-api-endpoint-reference)
15. [Prompt Guide: How to Work with This Codebase](#15-prompt-guide-how-to-work-with-this-codebase)
16. [Browser Testing (Laravel Dusk)](#16-browser-testing-laravel-dusk)
17. [Architecture Refactor (2026-03)](#17-architecture-refactor-2026-03)
18. [Super Buyer System](#18-super-buyer-system)
19. [RFQ (Request For Quotation)](#19-rfq-request-for-quotation)
20. [Import Product Request](#20-import-product-request)

---

## 1. Tổng Quan Hệ Thống

vBrand là một **SaaS e-commerce platform** cho phép mỗi customer quản lý website bán hàng riêng dựa trên WordPress + WooCommerce thông qua một Laravel dashboard trung tâm.

### Concept Chính
- **1 Customer = 1 WordPress site** (brandsite)
- Customer **không cần biết WordPress/WooCommerce** - toàn bộ quản lý qua Laravel panel
- Tất cả data (products, orders, theme settings) được **sync qua REST API** giữa Laravel và WordPress
- Hệ thống **template schema** cho phép customer customize website mà không cần code

### Hai Thành Phần Chính
| Component | Path | Technology | Role |
|-----------|------|------------|------|
| **App** (Dashboard) | `/app` | Laravel 12 | Customer panel, Admin panel, API gateway |
| **BrandSite** | `/brandsite` | WordPress + WooCommerce | Customer's storefront website |

---

## 2. Kiến Trúc Tổng Thể

```
┌─────────────────────────────────────────────┐
│              LARAVEL APP (/app)              │
│                                              │
│  ┌──────────┐  ┌──────────┐  ┌───────────┐  │
│  │ Customer  │  │  Admin   │  │  Public   │  │
│  │  Panel    │  │  Panel   │  │   API     │  │
│  │ (brand/*) │  │(admin/*) │  │ (api/*)   │  │
│  └─────┬─────┘  └─────┬────┘  └─────┬─────┘  │
│        │              │              │         │
│  ┌─────▼──────────────▼──────────────▼──────┐ │
│  │        Acelle\Wordpress\* Models         │ │
│  │   (Product, Order, Article, Category)    │ │
│  │        Acelle\Wordpress\Wordpress        │ │
│  └─────────────────┬────────────────────────┘ │
└────────────────────┼──────────────────────────┘
                     │ REST API (cURL)
                     │ wordpress_endpoint column
                     ▼
┌─────────────────────────────────────────────┐
│          WORDPRESS SITE (/brandsite)         │
│                                              │
│  ┌──────────────────────────────────────┐   │
│  │   Plugin: vbrandsync                  │   │
│  │   REST API: /wp-json/vbrandsync/v1/*  │   │
│  │                                       │   │
│  │  ┌─────────┐ ┌────────┐ ┌─────────┐  │   │
│  │  │Product  │ │ Order  │ │ Theme   │  │   │
│  │  │API      │ │ API    │ │ API     │  │   │
│  │  └────┬────┘ └───┬────┘ └────┬────┘  │   │
│  │       │          │           │        │   │
│  │  ┌────▼──────────▼───────────▼─────┐  │   │
│  │  │  WooCommerce / WordPress Core   │  │   │
│  │  │  (wp_posts, wc_orders, etc.)    │  │   │
│  │  └─────────────────────────────────┘  │   │
│  └──────────────────────────────────────┘   │
│                                              │
│  ┌──────────────────────────────────────┐   │
│  │   Themes: e.g. logitech/             │   │
│  │   - schema.php (template config)     │   │
│  │   - page-homepage.php                │   │
│  │   - page-aboutus.php                 │   │
│  │   - page-news.php, etc.              │   │
│  └──────────────────────────────────────┘   │
└─────────────────────────────────────────────┘
```

### Connection Flow
```
App (Laravel)                        BrandSite (WordPress)
     │                                      │
     │  customers.wordpress_endpoint ──────►│ REST API base URL
     │                                      │
     │◄──── settings.vbrand_endpoint ───────│ Laravel API base URL
     │◄──── settings.vbrand_token ──────────│ API authentication token
     │                                      │
     │  Acelle\Wordpress\Wordpress->request()│
     │  ────── cURL (GET/POST) ────────────►│ /wp-json/vbrandsync/v1/*
     │                                      │
     │  App\Services\VBrand->request()      │
     │◄────── cURL (GET/POST) ─────────────│ api/brand/* endpoints
```

---

## 3. Laravel App (`/app`)

### 3.1 Namespace & Framework
- **Namespace**: `Acelle\*`
- **Framework**: Laravel 12
- **Main routes file**: `routes/brand.php`

### 3.2 Key Directories
```
app/
├── app/
│   ├── Http/Controllers/
│   │   ├── Brand/          # Customer panel controllers (9 files)
│   │   ├── Store/          # Store management (orders, attributes)
│   │   ├── Admin/Brand/    # Admin panel controllers (7 files)
│   │   ├── Admin/Store/    # Admin store management
│   │   └── Api/Brand/      # Public API controllers
│   ├── Model/              # 165+ Eloquent models (Laravel DB)
│   ├── Wordpress/          # WordPress API wrapper classes
│   │   ├── Wordpress.php   # Main API client
│   │   ├── Product.php     # Product API wrapper
│   │   ├── Order.php       # Order API wrapper
│   │   ├── Article.php     # Article API wrapper
│   │   ├── Customer.php    # WP Customer (Category) wrapper
│   │   ├── OrderItem.php   # Order item wrapper
│   │   └── Scrapers/       # External data scrapers (Shopee)
│   └── Library/
│       └── Facades/WordpressConnectionFacade
├── routes/
│   └── brand.php           # ALL brand-related routes
├── config/
│   ├── constants.php       # Accounting constants
│   ├── permissions.php     # Permission definitions
│   └── roles.php           # Role definitions
└── resources/views/
    ├── brand/              # Customer panel views
    ├── store/              # Store management views
    └── admin/              # Admin panel views
```

### 3.3 Customer Model (`Acelle\Model\Customer`)
File: `app/app/Model/Customer.php` (~1800 lines)

**Key DB columns:**
- `wordpress_endpoint` — URL endpoint của WordPress site (e.g. `https://brandsite.com/wp-json/vbrandsync/v1`)
- `uid` — Unique identifier
- `status` — `active` | `inactive`

**Key methods:**
```php
// Lấy WordPress connection instance
$customer->wordpress()  // returns Acelle\Wordpress\Wordpress instance

// Template management
$customer->getWordPressTemplates()
$customer->wpSetThemeActive($theme)

// Create new resources
$customer->newProduct()
$customer->newOrder()
$customer->newDomain()
$customer->newWebsiteTemplate()

// File management
$customer->uploadWebsiteFile($file)  // Upload files for theme customization

// Subscription & Plan
$customer->getCurrentActiveSubscription()
$customer->getOrderFeeByAmount($amount)

// Balance & Accounting
$customer->getBalance()
$customer->balanceDeposit($amount)
$customer->balanceWithdraw($amount)
```

**Relationships:**
```php
$customer->users()          // hasMany User (login accounts)
$customer->orders()         // hasMany Order
$customer->websites()       // hasMany Website
$customer->domains()        // belongsToMany Domain
$customer->websiteTemplates() // belongsToMany WebsiteTemplate
$customer->subscriptions()  // hasMany Subscription
$customer->balances()       // hasMany Balance
$customer->invoices()       // hasMany Invoice
$customer->warehouses()     // hasMany Warehouse
$customer->roles()          // hasMany Role
```

### 3.4 WordPress Wrapper Classes (`Acelle\Wordpress\*`)

Đây là **proxy classes** - không lưu data trực tiếp vào DB của Laravel. Thay vào đó, gọi REST API sang WordPress.

#### `Acelle\Wordpress\Wordpress` (Main API Client)
File: `app/app/Wordpress/Wordpress.php`

```php
class Wordpress {
    public $endpoint;  // WordPress REST API base URL

    // URI Constants
    const URI_THEME_LIST = 'theme/list';
    const URI_THEME_ACTIVATE = 'theme/activate';
    const URI_THEME_GET_OPTIONS = 'theme/options/get';
    const URI_THEME_UPDATE_OPTIONS = 'theme/options/update';
    const URI_THEME_META = 'theme/meta';
    const URI_THEME_RESET_OPTIONS = 'theme/options/reset';
    const URI_SETTINGS_UPDATE_API = 'settings/update/api';
    const URI_PAGE_URL = 'page/url';

    // Core request method - all API calls go through here
    public function request($method, $uri, $params=[], $debug=false);

    // Theme operations
    public function themeList();                       // GET all themes
    public function themeActivate($theme);             // POST activate a theme
    public function themeGetOptions();                 // GET current theme options
    public function themeUpdateOptions($options);      // POST update theme options
    public function themeUpdateOption($name, $value);  // Update single option
    public function themeGetMeta();                    // GET theme schema metadata
    public function themeOptionsReset();               // POST reset theme to defaults
    public function getPageUrl();                      // GET site URL
    public function updateBrandAPI($endpoint, $token); // POST update API connection
}
```

**Connection failure handling:**
- Nếu `customers.wordpress_endpoint` chưa được cấu hình hoặc WordPress site không phản hồi, `Wordpress::request()` sẽ ném `Acelle\Exceptions\WordpressConnectionException`.
- Mobile webapp (`/brand/mobile/*`) render thông báo thân thiện cho customer thay vì show stack trace/exception page.
- Desktop brand app (`/brand/*`) dùng `Customer::getWordPressConnectionState()` để tránh gọi trực tiếp WordPress trong layout/menu chung.
- Desktop AJAX GET cho các list trong `/brand/*` trả về HTML cảnh báo ngay trong vùng list; desktop mutation requests vẫn trả JSON lỗi để frontend xử lý đúng.
- AJAX/JSON actions trả về HTTP `503` với thông điệp tiếng Việt để frontend hiển thị rõ ràng.

#### `Acelle\Wordpress\Product`
File: `app/app/Wordpress/Product.php`

```php
class Product {
    // Properties
    public $id, $title, $description, $price, $discount_price;
    public $image_url, $image_urls, $category_ids;
    public $lazada_id, $source, $categories;
    public $attributes, $variations;

    // URI Constants (→ WordPress REST API)
    const URI_LIST = 'product/list';
    const URI_COUNT = 'product/count';
    const URI_ADD = 'product/add';
    const URI_FIND = 'product/find/{id}';
    const URI_UPDATE = 'product/update/{id}';
    const URI_DELETE = 'product/delete/{id}';
    const URI_UPLOAD_IMAGES = 'product/{id}/upload-images';
    const URI_DELETE_IMAGES = 'product/{id}/delete-images';
    const URI_UPLOAD_IMAGE_FROM_URL = 'product/{id}/upload-image-from-url';

    // Methods
    static function list($options);        // List products with pagination/filter
    static function count();               // Count products
    static function find($id);             // Find by ID
    static function where($options);       // Query with conditions
    function save();                       // Create or update
    function fillParams($params);          // Fill from request params
    function saveFromParams($params);      // Validate + save + upload images
    function uploadImages($images);        // Upload images via API
    function deleteImages($urls);          // Delete images via API
    function uploadImageFromUrl($url);     // Upload from URL
}
```

#### `Acelle\Wordpress\Order`
File: `app/app/Wordpress/Order.php`

```php
class Order {
    // Properties
    public $id, $customer_id, $first_name, $last_name, $email, $phone;
    public $total, $currency, $status, $date_created, $date_modified;
    public $item_count, $payment_method, $shipping_method, $order_items;
    public $shipping_fee, $tax, $baokim_order_id, $baokim_mrc_order_id;

    // Status Constants
    const STATUS_ORDERED = 'ordered';
    const STATUS_PACKAGING = 'packaging';
    const STATUS_READY_FOR_PICKUP = 'ready_for_pickup';
    const STATUS_DELIVERING = 'delivering';
    const STATUS_DONE = 'completed';
    const STATUS_CANCELLED_BY_SELLER = 'seller_cancelled';
    const STATUS_REFUNDED = 'refunded';
    const STATUS_LOST = 'lost';
    const STATUS_DISPUTED = 'disputed';
    const STATUS_DISPUTED_REFUNDED = 'disputed_refunded';
    const STATUS_LOST_REFUNDED = 'lost_refunded';
    const STATUS_CANCELLED_BY_SYSTEM = 'cancelled_system';
    const STATUS_SYSTEM_REFUNDED = 'system_refunded';

    // CRUD
    static function list($options);    // List with pagination/filter
    static function count($options);   // Count orders
    static function find($id);         // Find by ID
    function add();                    // Create order
    function update();                 // Update order
    function delete();                 // Delete order

    // Status Transitions (all via API to WordPress)
    function confirm();                // → ordered
    function sellerComfirm();          // → packaging
    function setPackaged();            // → ready_for_pickup
    function setDelivering();          // → delivering
    function setComplete();            // → completed
    function deliver();                // → completed

    // Cancel & Refund
    function sellerCancel();           // → seller_cancelled
    function refund();                 // → refunded
    function reportLostProduct();      // → lost
    function lostRefund();             // → lost_refunded
    function disputedReport();         // → disputed
    function disputedRefund();         // → disputed_refunded
    function systemCancel();           // → cancelled_system
    function cancelledBySystemRefund(); // → system_refunded

    // Payment
    function checkBaoKimPaymentStatus();

    // Accounting
    function getCustomer();
    function getPlanFee();
    function getAllFee();
    function getCustomerRevenueAmmount();
}
```

#### `Acelle\Wordpress\Article`
```php
class Article {
    const URI_ARTICLE_LIST = 'article/list';
    const URI_ARTICLE_ADD = 'article/add';
    const URI_ARTICLE_FIND = 'article/find/{id}';
    const URI_ARTICLE_UPDATE = 'article/update/{id}';

    function all();
    function add($data);
    function find($id);
    function update($id, $data);
}
```

---

## 4. WordPress Plugin VBrandSync (`/brandsite`)

### 4.1 Plugin Entry Point
File: `brandsite/wp-content/plugins/vbrandsync/plugin.php`

Plugin này là **cầu nối** giữa Laravel app và WordPress. Nó:
1. Đăng ký **REST API endpoints** tại `/wp-json/vbrandsync/v1/*`
2. Đăng ký **custom WooCommerce order statuses**
3. Tích hợp **Laravel framework** bên trong WordPress (embedded Laravel)
4. Cung cấp **theme data loading** functions
5. Tích hợp **payment** (BaoKim) và **shipping** (GHN, vBrand Express)

### 4.2 Embedded Laravel
VBrandSync plugin **embed** một Laravel application bên trong WordPress:
```php
function vbrandsync_getResponse($path=null) {
    require __DIR__.'/vendor/autoload.php';
    $app = require_once __DIR__.'/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle(App\Wordpress\LaravelRequest::capture($path));
    return $response;
}
```

Điều này cho phép sử dụng **Eloquent models** (`App\Models\Setting`, `App\Library\ThemeData`) và **Laravel services** bên trong WordPress.

### 4.3 Plugin Structure
```
vbrandsync/
├── plugin.php              # Main entry: menus, order statuses, includes
├── wordpress/
│   ├── api.php             # Includes all API endpoint files
│   ├── api/
│   │   ├── product.php     # Product CRUD REST endpoints
│   │   ├── order.php       # Order management REST endpoints
│   │   ├── theme.php       # Theme management REST endpoints
│   │   ├── category.php    # Product category endpoints
│   │   ├── attribute.php   # Product attribute endpoints
│   │   ├── article.php     # Blog article endpoints
│   │   ├── article-category.php  # Blog category endpoints
│   │   ├── settings.php    # API connection settings
│   │   └── orderitems.php  # Order items
│   ├── theme.php           # Theme helper functions
│   ├── shipping.php        # Shipping method includes
│   ├── shipping/
│   │   ├── ghn.php         # GiaoHangNhanh integration
│   │   ├── vbrand_express.php  # vBrand Express shipping
│   │   └── custom_fields.php   # Checkout custom fields (Province/District/Ward)
│   └── payment.php         # BaoKim payment gateway (WC_Payment_Gateway)
├── app/                    # Embedded Laravel application
│   ├── Services/VBrand.php         # HTTP client to call Laravel app
│   ├── Library/ThemeData.php       # Theme options manager
│   ├── Library/Baokim.php          # BaoKim payment client
│   ├── Library/PostItem.php        # Post/product creation helper
│   ├── Models/Setting.php          # Key-value settings (DB)
│   ├── Wordpress/Models/           # WooCommerce model wrappers
│   │   ├── Product.php             # WC Product wrapper
│   │   ├── Order.php               # WC Order wrapper
│   │   ├── OrderItem.php           # WC Order Item wrapper
│   │   ├── Category.php            # WC Product Category wrapper
│   │   ├── Attribute.php           # WC Product Attribute wrapper
│   │   ├── Value.php               # Attribute Value wrapper
│   │   ├── Variation.php           # Product Variation wrapper
│   │   └── ArticleCategory.php     # WP Post Category wrapper
│   └── Wordpress/Scrapers/Shopee.php  # Shopee data scraper
└── bootstrap/app.php       # Laravel bootstrap
```

### 4.4 Custom WooCommerce Order Statuses
Registered in `plugin.php`:

| WC Status | Label (VN) | Description |
|-----------|-----------|-------------|
| `wc-ordered` | Đã đặt hàng | Order confirmed by buyer |
| `wc-packaging` | Đang đóng gói | Seller is packaging |
| `wc-ready_for_pickup` | Chờ lấy hàng | Ready for shipping pickup |
| `wc-delivering` | Đang giao hàng | In delivery |
| `wc-seller_cancelled` | Hủy bởi người bán | Cancelled by seller |
| `wc-refunded` | Đã hoàn phí | Refunded |
| `wc-lost` | Sản phẩm bị thất lạc | Product lost in delivery |
| `wc-disputed` | Không nhận được hàng | Buyer didn't receive |
| `wc-lost_refunded` | Đã hoàn phí (Mất hàng) | Refunded for lost product |
| `wc-disputed_refunded` | Đã hoàn phí (Khách báo không nhận) | Refunded for dispute |
| `wc-cancelled_system` | Hủy bởi system | System cancelled |
| `wc-system_refunded` | Đã hoàn phí (hủy bởi admin) | System refund |

Auto-status on checkout: New orders → `wc-ordered` (via `woocommerce_checkout_order_processed` hook)

### 4.5 Key WordPress Models

#### `App\Wordpress\Models\Product` (WP side)
File: `vbrandsync/app/Wordpress/Models/Product.php`

Wraps WooCommerce product operations using native WP/WC functions:
```php
class Product {
    public $id, $title, $description, $price, $discount_price;
    public $image_url, $image_urls, $images, $category_ids;
    public $source, $lazada_id, $categories;
    public $attributes, $variations;

    // Uses: wp_insert_post(), wp_update_post(), wp_delete_post()
    //       wc_get_product(), WC_Product_Variable
    //       update_post_meta() for price, source, lazada_id
    //       wp_set_object_terms() for categories
    function save();              // Create/update WC product
    static function find($id);    // Find by post ID
    static function list($opts);  // WP_Query with filters
    static function count();      // Count products
    function delete();            // wp_delete_post()
    function uploadImages();      // Handle file uploads
    function uploadImageFromUrl(); // Download and attach image
    function setAsFeatured();      // Mark as featured product
    function mapFromWPPostId($id); // Map WC product → object properties
    function getVariations();      // Get WC_Product_Variable variations
    function update_product_variations($variations); // Manage variations
}
```

#### `App\Wordpress\Models\Order` (WP side)
File: `vbrandsync/app/Wordpress/Models/Order.php`

Wraps WooCommerce order operations:
```php
class Order {
    // Uses: wc_get_order(), wc_get_orders()
    function mappingWcOrder($od);   // Map WC_Order → object
    static function list($opts);    // List orders with pagination
    static function find($id);      // Find by order ID
    static function count($opts);   // Count orders

    // Status changes: all use $od->update_status()
    function setOrdered();          // → ordered
    function setPackaging();        // → packaging
    function setPackaged();         // → ready_for_pickup
    function setDelivering();       // → delivering
    function setCompleted();        // → completed
    function setDone();             // → completed
    function sellerCancel();        // → seller_cancelled
    function setRefunded();         // → refunded
    function setLost();             // → lost
    function setDisputed();         // → disputed
    function setDisputedRefunded(); // → disputed_refunded
    function setLostRefunded();     // → lost_refunded
    function setCancelledBySystem(); // → cancelled_system
    function setSystemRefunded();   // → system_refunded

    // Payment
    function checkBaoKimPaymentStatus(); // Check via BaoKim API
}
```

### 4.6 VBrand Service (WP → Laravel communication)
File: `vbrandsync/app/Services/VBrand.php`

Khi WordPress cần gọi ngược về Laravel app:
```php
class VBrand {
    public $endpoint;  // Laravel API URL (from settings.vbrand_endpoint)

    function request($method, $url, $params=[]);  // cURL HTTP client
    function run($endpoint, $startAt, $endAt, $callback);  // Batch product import
    function getProvinces();                    // Get provinces from Laravel
    function getGHNShippingPrice($district, $ward);  // Shipping cost via Laravel
    function getVbrandExpressShippingPrice($district); // vBrand Express shipping
}
```

Settings used (stored in `settings` table):
- `vbrand_endpoint` — Laravel app API URL
- `vbrand_token` — API authentication token

### 4.7 Theme Helper Functions
File: `vbrandsync/wordpress/theme.php`

```php
// Load theme data for current active theme
function vbrand_load_theme_data();  // returns App\Library\ThemeData

// Page management for theme templates
function vbrand_createPageWithTemplate($template, $title);
function vbrand_getPageByTemplate($template);
function vbrand_getOrCreatePageByTemplate($template, $title);
function vbrand_setfrontPageByTemplate($template);
```

---

## 5. API Communication: App ↔ WordPress

### 5.1 App → WordPress (Laravel gọi sang WordPress)

**Connection setup:**
1. Admin sets `customer.wordpress_endpoint` = WordPress REST API URL
2. `$customer->wordpress()` returns `Acelle\Wordpress\Wordpress` instance

**Flow:**
```
Controller (Brand/Store)
    → Acelle\Wordpress\{Product|Order|Article}::method()
        → Acelle\Wordpress\Wordpress::request($method, $uri, $params)
            → cURL to: {wordpress_endpoint}/{uri}
                → /wp-json/vbrandsync/v1/{uri}
```

**Example: List products**
```php
// In Laravel controller
$products = Acelle\Wordpress\Product::list(['per_page' => 10, 'page' => 1]);
// → calls: GET {wordpress_endpoint}/product/list?per_page=10&page=1
// → hits: /wp-json/vbrandsync/v1/product/list on WordPress
// → WordPress executes: App\Wordpress\Models\Product::list($_GET)
// → Returns WC products via WP_Query
```

### 5.2 WordPress → App (WordPress gọi ngược về Laravel)

**Connection setup:**
1. Laravel calls `$customer->wordpress()->updateBrandAPI($endpoint, $token)`
2. WordPress stores `vbrand_endpoint` + `vbrand_token` in `settings` table

**Flow:**
```
WordPress shipping/payment code
    → App\Services\VBrand::method()
        → cURL to: {vbrand_endpoint}/{path}?api_token={vbrand_token}
            → Laravel API endpoints (auth:api middleware)
```

**Use cases:**
- Get shipping prices (GHN, vBrand Express)
- Get provinces/districts/wards for address forms
- Product sync from external sources

### 5.3 Complete API Endpoint Map

#### WordPress REST API (called by Laravel)
Base: `/wp-json/vbrandsync/v1`

| Method | Endpoint | Handler | Purpose |
|--------|----------|---------|---------|
| GET | `/theme/list` | `vbrandsync_ajax_theme_list` | List all WP themes |
| POST | `/theme/activate` | `vbrandsync_ajax_theme_activate` | Activate a theme |
| GET | `/theme/options/get` | `vbrandsync_ajax_get_theme_options` | Get theme options |
| POST | `/theme/options/update` | `vbrandsync_ajax_update_theme_options` | Update theme options |
| GET | `/theme/meta` | `vbrandsync_ajax_get_theme_meta` | Get theme schema |
| POST | `/theme/options/reset` | `vbrandsync_ajax_reset_theme_meta` | Reset theme |
| GET | `/page/url` | `vbrandsync_ajax_get_page_url` | Get site home URL |
| POST | `/settings/update/api` | `vbrandsync_ajax_settings_update_api` | Update API connection |
| GET | `/product/list` | `vbrandsync_ajax_product_list` | List products |
| GET | `/product/count` | `vbrandsync_ajax_product_count` | Count products |
| POST | `/product/add` | `vbrandsync_ajax_product_add` | Add product |
| GET | `/product/find/{id}` | `vbrandsync_api_product_find` | Find product |
| GET | `/product/show/{id}` | `vbrandsync_ajax_product_show` | Show product |
| POST | `/product/update/{id}` | `vbrandsync_ajax_product_update` | Update product |
| POST | `/product/delete/{id}` | `vbrandsync_ajax_product_delete` | Delete product |
| POST | `/product/{id}/upload-images` | `vbrandsync_api_product_upload_images` | Upload images |
| POST | `/product/{id}/delete-images` | `vbrandsync_api_product_delete_images` | Delete images |
| POST | `/product/{id}/upload-image-from-url` | `vbrandsync_api_product_upload_image_from_url` | Upload from URL |
| GET | `/order/list` | `vbrandsync_api_order_list` | List orders |
| GET | `/order/count` | `vbrandsync_api_order_count` | Count orders |
| GET | `/order/find/{id}` | `vbrandsync_api_order_find` | Find order |
| POST | `/order/set-ordered/{id}` | `vbrandsync_api_order_setOrdered` | Set ordered |
| POST | `/order/set-packaging/{id}` | `vbrandsync_api_order_setPackaging` | Set packaging |
| POST | `/order/set-packaged/{id}` | `vbrandsync_api_order_setPackaged` | Set packaged |
| POST | `/order/set-delivering/{id}` | `vbrandsync_api_order_setDelivering` | Set delivering |
| POST | `/order/set-delivered/{id}` | `vbrandsync_api_order_setDelivered` | Set delivered |
| POST | `/order/set-done/{id}` | `vbrandsync_api_order_setDone` | Set done |
| POST | `/order/set-completed/{id}` | `vbrandsync_api_order_set_completed` | Set completed |
| POST | `/order/set-pending/{id}` | `vbrandsync_api_order_set_pending` | Set pending |
| POST | `/order/set-cancelled-by-seller/{id}` | `vbrandsync_api_order_sellerCancel` | Seller cancel |
| POST | `/order/set-refunded/{id}` | `vbrandsync_api_order_setRefunded` | Refund |
| POST | `/order/set-lost/{id}` | `vbrandsync_api_order_setLost` | Mark lost |
| POST | `/order/set-disputed/{id}` | `vbrandsync_api_order_setDisputed` | Mark disputed |
| POST | `/order/set-lost-refunded/{id}` | `vbrandsync_api_order_setLostRefunded` | Lost + refund |
| POST | `/order/set-disputed-refunded/{id}` | `vbrandsync_api_order_setDisputedRefunded` | Disputed + refund |
| POST | `/order/set-cancelled-by-system/{id}` | `vbrandsync_api_order_setCancelledBySystem` | System cancel |
| POST | `/order/set-system-refunded/{id}` | `vbrandsync_api_order_setSystemRefunded` | System refund |
| POST | `/order/check-baokim-payment/{id}` | `vbrandsync_api_order_checkBaoKimPaymentStatus` | Check payment |

#### Laravel API (called by WordPress)
Base: `api/brand` (defined in `routes/brand.php`)

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `api/brand` | API endpoint info |
| GET | `api/brand/address/provinces` | Vietnamese provinces list |
| GET | `api/brand/address/districts` | Districts by province |
| GET | `api/brand/address/wards` | Wards by district |
| GET | `api/brand/get-price` | Get shipping price (public) |
| GET | `api/brand/get-warehouse` | Get warehouse info (auth) |
| GET | `api/brand/get-ghn-price` | GHN shipping price (auth) |
| GET | `api/brand/get-vbrand-express-price` | vBrand Express price (auth) |

---

## 6. Theme/Template System

### 6.1 Overview
vBrand có hệ thống **template schema** cho phép customer customize hoàn toàn nội dung website mà không cần code. Mỗi WordPress theme có một file `schema.php` định nghĩa toàn bộ cấu trúc dữ liệu có thể customize.

### 6.2 Schema Architecture

#### Schema File
Location: `brandsite/wp-content/themes/{theme_name}/schema.php`

Cấu trúc:
```php
return [
    'sessions' => [
        // Nhóm các options theo tab/section
        ['name' => 'general', 'title' => 'CHUNG'],
        ['name' => 'menu', 'title' => 'MENU'],
        ['name' => 'home', 'title' => 'HOME'],
        ['name' => 'about-us', 'title' => 'ABOUT US'],
    ],

    'options' => [
        // Mỗi option là 1 field có thể customize
        [
            'session' => 'general',   // Thuộc tab nào
            'type' => 'text',         // Loại input
            'name' => 'site_name',    // Key lưu trữ
            'label' => 'Tên website', // Label hiển thị
            'default' => 'vBrand',    // Giá trị mặc định
        ],
        // ...
    ],
];
```

#### Supported Field Types
| Type | Description | Example |
|------|-------------|---------|
| `text` | Text input | Site name, menu title, URL |
| `textarea` | Multi-line text | Description, short alias |
| `boolean` | Toggle on/off | Show/hide module |
| `select` | Dropdown select | Menu type, tab type |
| `image` | Image upload | Banner, logo, slider images |
| `list` | Repeatable group | Sliders, menus, footer links |

#### List Type (Nested Schema)
`list` type cho phép tạo **repeatable groups** với sub-schema riêng:
```php
[
    'type' => 'list',
    'name' => 'slider',
    'label' => 'Slider',
    'max' => 10,                 // Max items
    'schema' => [                // Sub-fields for each item
        ['type' => 'image', 'name' => 'anh', 'label' => 'Ảnh'],
        ['type' => 'text', 'name' => 'title', 'label' => 'Tiêu đề'],
    ],
    'default' => [...]           // Default items
]
```

### 6.3 Data Storage & Retrieval

#### Storage
Theme options được lưu trong **vbrandsync's `settings` table** (WordPress side):
```
name: 'theme.options'
value: JSON string of {
    "Theme Name 1": { "site_name": "...", "slider": [...] },
    "Theme Name 2": { ... }
}
```

#### ThemeData Class
File: `vbrandsync/app/Library/ThemeData.php`

```php
class ThemeData {
    function getAllThemeOptions();     // Get all themes' options
    function getThemeOptions();       // Get current theme's options
    function get($name, $default);    // Get single option value
    function set($name, $value);      // Set single option value
    function getThemeMeta();          // Load schema.php from active theme
    function getMetaOption($name);    // Find option definition in schema
    function updateThemeOptions($opts); // Merge update theme options
    function resetThemeOptions();     // Reset current theme options
}
```

### 6.4 Theme Options Flow

#### Reading (WordPress theme files)
```php
// In any theme template (e.g. page-homepage.php)
$themeData = vbrand_load_theme_data();
$siteName = $themeData->get('site_name');
$sliders = $themeData->get('slider');
$menus = $themeData->get('menus');
```

#### Writing (Laravel app → WordPress)
```php
// 1. Customer opens theme options page
$themeOptions = $customer->wordpress()->themeGetOptions();
$schema = $customer->wordpress()->themeGetMeta();

// 2. Customer submits form with changes
// 3. WebsiteController::fillSchema() processes nested form data
$themeOptions = $this->fillSchema($customer, $themeOptions, $schema['options'], $request->all());

// 4. Send updated options to WordPress
$customer->wordpress()->themeUpdateOptions($themeOptions);
```

#### `fillSchema()` Processing Logic
File: `app/app/Http/Controllers/Brand/WebsiteController.php`

Handles nested form data recursively:
- **text/textarea/boolean/select**: Direct value assignment
- **image**: Upload file via `$customer->uploadWebsiteFile()`, store URL
- **list**: Recursively process each item's sub-schema

### 6.5 Menu System
Menus are defined in schema as a `list` type with page template linking:
```php
// Schema defines available page types
'options' => [
    ['value' => 'page-homepage.php', 'text' => 'Home'],
    ['value' => 'page-aboutus.php', 'text' => 'About Us'],
    ['value' => 'shop', 'text' => 'Shop'],
    ['value' => 'page-news.php', 'text' => 'News'],
    ['value' => 'page-contact', 'text' => 'Contact Us'],
]
```

WordPress pages are **auto-created** with matching templates via:
```php
vbrand_getOrCreatePageByTemplate('page-homepage.php', 'Trang chủ');
```

### 6.6 Example Theme: Logitech
Location: `brandsite/wp-content/themes/logitech/`

Schema sections:
- **General**: Site name, footer widgets (4 groups), social icons, copyright links
- **Menu**: Navigation menu items (up to 10)
- **Home**: Slider (main + banner), news section, product tabs, shop banners, shopping banner group
- **About Us**: Toggle visibility
- **Partner**: Partner section

---

## 7. Order Management & Status Flow

### 7.1 Order Status Flow Diagram

```
                    ┌──────────────┐
     New checkout → │ checkout-draft│
                    └──────┬───────┘
                           │ woocommerce_checkout_order_processed
                           ▼
                    ┌──────────────┐
                    │   ordered    │ ← Đã đặt hàng
                    └──────┬───────┘
                           │ sellerComfirm()
                           ▼
                    ┌──────────────┐
                    │  packaging   │ ← Đang đóng gói
                    └──────┬───────┘
                           │ setPackaged()
                           ▼
                    ┌──────────────────┐
                    │ ready_for_pickup │ ← Chờ lấy hàng
                    └──────┬───────────┘
                           │ setDelivering()
                           ▼
                    ┌──────────────┐
                    │  delivering  │ ← Đang giao hàng
                    └──────┬───────┘
                           │ setComplete() / deliver()
                           ▼
                    ┌──────────────┐
                    │  completed   │ ← Hoàn thành
                    └──────────────┘

    ===== CANCEL & REFUND FLOWS =====

    Any active status:
        │ sellerCancel() ──────→ seller_cancelled
        │ systemCancel() ──────→ cancelled_system
        │                           │ cancelledBySystemRefund()
        │                           └──→ system_refunded

    After delivery:
        │ reportLostProduct() ─→ lost
        │                         │ lostRefund()
        │                         └──→ lost_refunded
        │
        │ disputedReport() ────→ disputed
        │                         │ disputedRefund()
        │                         └──→ disputed_refunded
        │
        │ refund() ────────────→ refunded
```

### 7.2 Order Processing By Role

| Action | Customer Panel | Admin Panel |
|--------|---------------|-------------|
| View orders | `store/orders` | `admin/brand/{uid}/orders` |
| Confirm (→ ordered) | `store/orders/{id}/confirm` | `admin/store/{uid}/orders/{id}/confirm` |
| Seller confirm (→ packaging) | `store/orders/{id}/seller-confirm` | `admin/store/{uid}/orders/{id}/seller-confirm` |
| Set packaged | `store/orders/{id}/set-packaged` | `admin/store/{uid}/orders/{id}/set-packaged` |
| Set delivering | `store/orders/{id}/set-delivering` | `admin/store/{uid}/orders/{id}/set-delivering` |
| Set delivered | `store/orders/{id}/deliveed` | `admin/store/{uid}/orders/{id}/deliveed` |
| Complete | `store/orders/{id}/complete` | `admin/store/{uid}/orders/{id}/complete` |
| Seller cancel | `store/orders/{id}/seller-cancel` | `admin/store/{uid}/orders/{id}/seller-cancel` |
| Refund | `store/orders/{id}/refund` | `admin/store/{uid}/orders/{id}/refund` |
| System cancel | `store/orders/{id}/system-cancel` | `admin/store/{uid}/orders/{id}/system-cancel` |
| Check payment | `store/orders/{id}/payment/check` | `admin/store/{uid}/orders/{id}/payment/check` |

### 7.2.1 Shared Status Catalog

- Shared runtime catalog: `app/app/Support/OrderStatusCatalog.php`
- Base status/action definitions: `app/config/order_statuses.php`
- DTO mapping for API responses: `app/app/DTOs/OrderDTO.php`
- Mobile order filters are delivered by `GET /api/v1/brand/orders/stats` in the `filters` field
- Webapp, store, admin, and super buyer order UIs should read labels, descriptions, actions, filters, and progress semantics from the shared catalog instead of hardcoding per-screen arrays
- Filter metadata may represent a business stage backed by multiple raw statuses. Current example: `Đã đặt hàng` expands to both `ordered` and `processing` so all surfaces show the same items under the same tab.
- Super Buyer uses the same shared catalog, with buyer-specific policy layered in one place:
    - display status is resolved from base `status` plus `rfq_status`, not by blindly preferring RFQ overlay forever
    - buyer-facing status description also comes from the shared catalog
    - buyer actions only expose what buyer can actually do on that surface

### 7.3 Order Accounting
When an order is completed, accounting entries are recorded:
- `ACC_USER_CASH` — User cash account
- `ACC_USER_TAX` — Tax amount
- `ACC_USER_PLAN_FEE` — Plan-based order fee
- `ACC_USER_SHIPPING_FEE` — Shipping fee
- `ACC_USER_REVENUE` — Net revenue for customer

Revenue calculation:
```php
$revenue = $order->total - $order->tax - $order->getPlanFee() - $order->shipping_fee
```

---

## 8. Product Management

### 8.1 Product Data Flow
```
Customer Panel (Laravel)                    WordPress/WooCommerce
        │                                           │
        │  Create/Edit Form                         │
        │  ──POST──→ Acelle\Wordpress\Product       │
        │            ::saveFromParams()              │
        │            → validate                      │
        │            → save() ──cURL POST──────────→ │
        │                      /product/add          │ wp_insert_post()
        │                      /product/update/{id}  │ wp_update_post()
        │                                            │ update_post_meta()
        │            → uploadImages() ──cURL POST──→ │
        │                      /product/{id}/upload  │ wp_handle_sideload()
        │                                            │
        │  List/Search                               │
        │  ──GET───→ Product::list()                 │
        │            ──cURL GET──────────────────→   │ WP_Query
        │            /product/list?per_page=10       │
        │            ←──JSON response───────────     │
        │            → mapping() → collection        │
```

### 8.2 Product Properties
| Property | WP Storage | Type |
|----------|-----------|------|
| `title` | `post_title` | string |
| `description` | `post_content` | string |
| `permalink` | `get_permalink(product_id)` | string \| null |
| `price` | `_price` / `_regular_price` meta | number |
| `discount_price` | `_price` meta (when has discount) | number |
| `image_url` | Post thumbnail | string (URL) |
| `image_urls` | Gallery images | array (URLs) |
| `category_ids` | `product_cat` taxonomy | array (int) |
| `source` | `source` meta | string (shopee/lazada/woo/vbrand) |
| `lazada_id` | `lazada_id` meta | string |
| `attributes` | WC Product Attributes | array |
| `variations` | WC Product Variations | array |

`App\Wordpress\Models\Product::mapFromWPPostId()` map thêm `permalink` để Laravel app có thể hiển thị hoặc copy đúng public WordPress URL của sản phẩm mà không phải tự dựng link từ slug.

### 8.3 Product Variations
Products support WooCommerce variable products with:
- **Attributes**: Named attributes (e.g. size, color) with options
- **Variations**: Specific attribute combinations with individual prices

---

## 9. Payment Integration (BaoKim)

### 9.1 Architecture
BaoKim is integrated at **WordPress side** as a WooCommerce Payment Gateway.

File: `vbrandsync/wordpress/payment.php` — `WC_xBaoKimVN` class
File: `vbrandsync/app/Library/Baokim.php` — API client

### 9.2 Payment Flow
```
1. Customer checkout on WordPress store
2. WC_xBaoKimVN::generate_BaoKimVN_form()
   → Creates BaoKim order via API
   → Saves baokim_order_id and baokim_mrc_order_id to WC order meta
   → Returns payment URL redirect

3. Customer pays on BaoKim
4. BaoKim webhooks → BaokimController::webhooks()
   OR
   Manual check: order/check-baokim-payment/{id}
   → Baokim::checkOrder() API call
   → If stat == 'c' (completed) → set order status to 'ordered'

5. Success redirect → BaokimController::success()
```

### 9.3 BaoKim API Operations
```php
class Baokim {
    static function initialize();              // Create from env vars
    function createOrder($params);             // Create payment order
    function cancelOrder($orderId);            // Cancel order
    function checkOrder($orderId, $mrcOrderId); // Check payment status
}
```

---

## 10. Shipping Integration

### 10.1 GiaoHangNhanh (GHN)
File: `vbrandsync/wordpress/shipping/ghn.php`

WooCommerce shipping method that calculates shipping via GHN API through Laravel:
```
WordPress checkout → WC shipping calculation
    → VBrand::getGHNShippingPrice($districtId, $wardCode)
        → cURL to Laravel: api/brand/get-ghn-price?api_token=...
            → Laravel: ShippingController::getGHNPrice()
                → GHN API
```

### 10.2 vBrand Express
File: `vbrandsync/wordpress/shipping/vbrand_express.php`

Custom shipping method with pricing via Laravel:
```
WordPress checkout → WC shipping calculation
    → VBrand::getVbrandExpressShippingPrice($district)
        → cURL to Laravel: api/brand/get-vbrand-express-price?api_token=...
```

### 10.3 Custom Checkout Fields
File: `vbrandsync/wordpress/shipping/custom_fields.php`

Adds Vietnamese address fields to WooCommerce checkout:
- Province (Tỉnh/Thành phố)
- District (Quận/Huyện)
- Ward (Phường/Xã)

These use AJAX to load cascading dropdowns via VBrand service.

---

## 11. Authentication & Authorization

### 11.1 Laravel App Authentication
- **Web login**: Standard Laravel auth with middleware `['not_installed', 'auth', 'frontend']`
- **API auth**: `api_token` column on `users` table, middleware `auth:api`
- **1 Customer → Many Users**: Each user belongs to a customer

### 11.2 Customer-User Relationship
```php
// User logs in → gets customer context
$customer = $request->user()->customer;

// All WordPress operations use customer's endpoint
$customer->wordpress()->request(...)
```

### 11.3 Admin Access
- Admin routes: middleware `['not_installed', 'auth', 'backend']`
- Admin can "login as" customer: `admin/brand/customers/login-as/{uid}`
- Admin can "one click login": `admin/brand/customers/{uid}/one-click-login`

### 11.4 WordPress API Authentication
Currently, WordPress REST API endpoints use `'permission_callback' => '__return_true'` (public access). Security relies on the WordPress site being accessible only from the Laravel app.

### 11.5 WordPress → Laravel API Auth
Uses `api_token` query parameter: `$data['api_token'] = Setting::get('vbrand_token')`

---

## 12. Database & Data Flow

### 12.1 Laravel Database (Main App)
Key tables:
- `customers` — Customer accounts (with `wordpress_endpoint`)
- `users` — Login accounts (with `api_token`, belongs to customer)
- `orders` — Local order records
- `products` — Local product records
- `subscriptions` — Customer plan subscriptions
- `invoices` — Billing invoices
- `balances` — Customer account balances
- `transactions` — Financial transactions
- `domains` — Custom domains
- `website_templates` — Available templates
- `warehouses` — Shipping warehouses
- `contacts` — Customer contacts
- `roles` / `permissions` — Authorization
- `provinces`, `districts`, `wards` — Vietnamese geography

### 12.2 WordPress Database (Per site)
Standard WP + WooCommerce tables plus:
- `settings` table (from vbrandsync migrations) — Key-value store for:
  - `vbrand_endpoint` — Laravel API URL
  - `vbrand_token` — API auth token
  - `theme.options` — JSON blob of all theme customizations
  - `last_sync_at` — Last data sync timestamp

WooCommerce data (in standard WP tables):
- Products: `wp_posts` (type=product) + `wp_postmeta`
- Orders: `wp_posts` (type=shop_order) + WC order items
- Categories: `wp_terms` + `wp_term_taxonomy` (taxonomy=product_cat)
- Custom order statuses: Registered via `register_post_status()`

### 12.3 Data Ownership
| Data Type | Primary Storage | Managed From |
|-----------|----------------|--------------|
| Customer accounts | Laravel DB | Admin panel |
| User accounts | Laravel DB | Admin/Customer panel |
| Products | WordPress DB | Customer panel (via API) |
| Orders | WordPress DB | Customer panel (via API) |
| Theme options | WordPress DB (settings) | Customer panel (via API) |
| Subscriptions/Plans | Laravel DB | Admin panel |
| Invoices/Payments | Laravel DB | Both panels |
| Contacts | Laravel DB | Customer panel |
| Articles/Blog | WordPress DB | Customer panel (via API) |
| Domains | Laravel DB | Admin panel |
| Geography data | Laravel DB | System (seeded) |

---

## 13. Feature Reference by Controller

### 13.1 Customer Panel Controllers

#### `Brand\HomeController`
- `index()` — Dashboard homepage
- `hostings()` — Hosting management
- `templates()` — Template gallery
- `invoice()` — Invoice viewing

#### `Brand\WebsiteController`
- `themeOptions()` — View/edit theme customization (schema-based form)
- `themeOptionsReset()` — Reset theme to defaults
- `ImportDemo_form()` / `ImportDemo()` — Import demo site from Shopee

#### `Brand\WebsiteTemplateController`
- `index()` / `list()` — Browse available templates
- `buy()` — Purchase a template (creates invoice)
- `wpSetActive()` — Activate template on WordPress site

#### `Brand\ContactController`
- Full CRUD for customer contacts
- Bulk operations (delete many, activate many)
- Search and filtering

#### `Brand\ArticleController`
- Blog article CRUD via WordPress API
- Category association

#### `Brand\ArticleCategoryController`
- Blog category CRUD
- Status management

#### `Brand\CustomerController`
- `accountingReport()` — Revenue and fee reports
- `journalEntries()` — Financial transaction log

#### `Brand\DomainController`
- Domain registration (via GoDaddy API)
- Domain availability checking
- Domain purchase with plan assignment
- Domain listing and search

#### `Brand\AddressController`
- `districts()` — Get districts by province
- `wards()` — Get wards by district

#### `Store\OrdersController` (~50 methods)
- Order listing with status filtering and pagination
- Order creation with contact management
- Order editing with product selection
- Print order functionality
- **All order status transitions** (confirm, package, deliver, complete, cancel, refund, etc.)
- BaoKim payment status checking

#### `Store\AttributeController`
- Product attribute CRUD
- Bulk operations

### 13.2 Mobile Webapp Controllers (`Brand\Webapp\*`)

Routes prefix: `brand/mobile`, Views: `resources/views/webapp/`

#### `Brand\Webapp\AuthController`
- `showLogin()` — Mobile-optimized login page (guest route, no auth required)
- `login()` — Handle login POST (redirects to `webapp.dashboard` on success)
- `logout()` — Logout and redirect to mobile login

**Auth redirect**: `Authenticate` middleware detects `brand/mobile*` requests and redirects unauthenticated users to `brand/mobile/login` instead of desktop `/login`.

#### `Brand\Webapp\DashboardController`
- `index()` — Dashboard with stats (orders, products, theme), recent orders

#### `Brand\Webapp\ProductController`
- `index()` / `list()` — Product listing with search (AJAX list)
- `create()` / `store()` — Create product
- `edit()` / `update()` — Edit product
- `delete()` / `deleteSelected()` — Delete product(s)

#### `Brand\Webapp\OrderController`
- `index()` / `list()` — Order listing with status filter (AJAX list)
- `create()` — Create draft order
- `show()` — Order detail with progress tracker, actions
- `edit()` / `update()` — Edit order
- `delete()` — Delete order
- 17 status action methods (confirm, package, deliver, complete, cancel, refund, etc.)

#### `Brand\Webapp\ProfileController`
- `index()` — Profile overview (account info, store info, logout)
- `edit()` / `update()` — Edit personal info (name, phone)
- `password()` / `updatePassword()` — Change password

### 13.3 Admin Panel Controllers

#### `Admin\Brand\CustomerController`
- Full customer CRUD with image upload
- Customer search/filtering
- Enable/disable/delete customers
- Plan assignment
- One-click login (impersonation)
- Balance tracking with date filtering
- Accounting reports and journal entries
- Sub-account management
- Growth chart analytics

#### `Admin\Brand\OrderController`
- View customer orders
- All order status management (mirrors Store\OrdersController)
- Requires customer_uid context

#### `Admin\Brand\PlanController`
- Plan CRUD with billing cycles
- Sending limits configuration
- Plan visibility toggle
- Plan copying

#### `Admin\Brand\WebsiteTemplateController`
- Template CRUD
- Bulk operations

#### `Admin\Brand\DomainController`
- Domain management
- Bulk operations

#### `Admin\Brand\HostingController`
- Hosting management
- Bulk operations

#### `Admin\Brand\PayoutController`
- Create payouts for customers

#### `Admin\Store\WordPressController`
- `connect()` — Set customer's WordPress endpoint URL

---

## 14. API Endpoint Reference

### 14.1 Mobile Webapp Routes (brand_webapp.php)

#### Guest Routes — Middleware: `not_installed`
```
GET    brand/mobile/login                  → Brand\Webapp\AuthController@showLogin
POST   brand/mobile/login                  → Brand\Webapp\AuthController@login
```

#### Authenticated Routes — Middleware: `not_installed, auth, frontend`
```
GET    brand/mobile/                       → Brand\Webapp\DashboardController@index
POST   brand/mobile/logout                 → Brand\Webapp\AuthController@logout

# Profile
GET    brand/mobile/profile               → Brand\Webapp\ProfileController@index
GET    brand/mobile/profile/edit           → Brand\Webapp\ProfileController@edit
POST   brand/mobile/profile/update         → Brand\Webapp\ProfileController@update
GET    brand/mobile/profile/password       → Brand\Webapp\ProfileController@password
POST   brand/mobile/profile/password       → Brand\Webapp\ProfileController@updatePassword

# Products (CRUD)
GET    brand/mobile/products              → Brand\Webapp\ProductController@index
GET    brand/mobile/products/list          → Brand\Webapp\ProductController@list (AJAX)
GET    brand/mobile/products/create        → Brand\Webapp\ProductController@create
POST   brand/mobile/products/store         → Brand\Webapp\ProductController@store
GET    brand/mobile/products/{id}/edit     → Brand\Webapp\ProductController@edit
POST   brand/mobile/products/{id}/update   → Brand\Webapp\ProductController@update
POST   brand/mobile/products/{id}/delete   → Brand\Webapp\ProductController@delete

# Orders (CRUD + 17 status actions)
GET    brand/mobile/orders                → Brand\Webapp\OrderController@index
GET    brand/mobile/orders/list            → Brand\Webapp\OrderController@list (AJAX)
GET    brand/mobile/orders/{id}            → Brand\Webapp\OrderController@show
GET    brand/mobile/orders/{id}/edit       → Brand\Webapp\OrderController@edit
POST   brand/mobile/orders/{id}/update     → Brand\Webapp\OrderController@update
POST   brand/mobile/orders/{id}/delete     → Brand\Webapp\OrderController@delete
POST   brand/mobile/orders/{id}/confirm    → ... (all status actions same as store routes)
```

**Auth flow**: `Authenticate` middleware redirects `brand/mobile*` requests to `brand/mobile/login` (not `/login`).
`RedirectMobileBrowser` middleware on `GET brand/` redirects mobile User-Agents to `brand/mobile/`.

### 14.2 Laravel Routes (brand.php)

#### Frontend (Customer) Routes — Middleware: `auth, frontend`
```
GET    brand/                              → Brand\HomeController@index
GET    brand/hostings                      → Brand\HomeController@hostings
GET    brand/templates                     → Brand\HomeController@templates
GET    brand/invoice                       → Brand\HomeController@invoice

# Media
GET    brand/media                         → MediaController@index
POST   brand/media                         → MediaController@store
DELETE brand/media/{id}                    → MediaController@destroy

# Domain
POST   brand/domain/{domain}/buy           → Brand\DomainController@buy
GET    brand/domain/check                  → Brand\DomainController@checkform
GET    brand/domain/checkdomain            → Brand\DomainController@checkDomain
POST   brand/domain/register               → Brand\DomainController@registerDomain
POST   brand/domain/doregister             → Brand\DomainController@doRegister
GET    brand/domain/list                   → Brand\DomainController@list
GET    brand/domain                        → Brand\DomainController@index

# BaoKim
GET    baokim/checkout                     → BaokimController@checkout
GET    baokim                              → BaokimController@index

# Website Templates
POST   brand/website-templates/set-active/{theme} → Brand\WebsiteTemplateController@wpSetActive
GET    brand/website-templates             → Brand\WebsiteTemplateController@index
POST   admin/website-templates/{uid}/buy   → Brand\WebsiteTemplateController@buy
GET    brand/website-templates/list        → Brand\WebsiteTemplateController@list
GET    brand/import-demo                   → Brand\WebsiteController@ImportDemo_form
POST   brand/import-demo                   → Brand\WebsiteController@ImportDemo

# Contacts (CRUD)
DELETE brand/contacts/delete-selected      → Brand\ContactController@deleteSelected
DELETE brand/contacts/delete               → Brand\ContactController@delete
PATCH  brand/contacts                      → Brand\ContactController@updateStatus
PUT    brand/contacts                      → Brand\ContactController@multiltask
GET    brand/contacts/list                 → Brand\ContactController@list
RESOURCE brand/contacts                    → Brand\ContactController (index/create/store/show/edit/update/destroy)

# Theme Options
GET|POST brand/website/theme/options/reset → Brand\WebsiteController@themeOptionsReset
GET|POST brand/website/theme/options       → Brand\WebsiteController@themeOptions

# Articles (CRUD)
GET    website/articles                    → Brand\ArticleController@index
GET    website/articles/list               → Brand\ArticleController@list
GET    website/articles/create             → Brand\ArticleController@create
POST   website/articles/store              → Brand\ArticleController@store
GET    website/articles/{id}/edit          → Brand\ArticleController@edit
POST   website/articles/{id}/update        → Brand\ArticleController@update
POST   website/articles/{id}/delete        → Brand\ArticleController@delete

# Article Categories
GET    website/article-category            → Brand\ArticleCategoryController@index
GET    website/article-category/list       → Brand\ArticleCategoryController@list
# ... full CRUD

# Accounting
GET    brand/accounting-report             → Brand\CustomerController@accountingReport
GET    brand/customers/{uid}/journal-entries → Brand\CustomerController@journalEntries

# Attributes
GET    store/attribute/collection          → Store\AttributeController@collection
GET    store/attribute/list                → Store\AttributeController@list
RESOURCE store/attribute                   → Store\AttributeController

# Orders (Complete workflow)
POST   store/orders/{id}/confirm           → Store\OrdersController@confirm
POST   store/orders/{id}/seller-confirm    → Store\OrdersController@sellerComfirm
POST   store/orders/{id}/set-packaged      → Store\OrdersController@setPackaged
POST   store/orders/{id}/set-delivering    → Store\OrdersController@setDelivering
POST   store/orders/{id}/deliveed          → Store\OrdersController@setDelivered
POST   store/orders/{id}/complete          → Store\OrdersController@setComplete
POST   store/orders/{id}/pay               → Store\OrdersController@pay
POST   store/orders/{id}/seller-cancel     → Store\OrdersController@sellerCancel
POST   store/orders/{id}/refund            → Store\OrdersController@refund
POST   store/orders/{id}/report-lost-product → Store\OrdersController@reportLostProduct
POST   store/orders/{id}/lost-refund       → Store\OrdersController@lostRefund
POST   store/orders/{id}/disputed-report   → Store\OrdersController@disputedReport
POST   store/orders/{id}/disputed-refund   → Store\OrdersController@disputedRefund
POST   store/orders/{id}/system-cancel     → Store\OrdersController@systemCancel
POST   store/orders/{id}/system-cancel/refund → Store\OrdersController@cancelledBySystemRefund
POST   store/orders/{id}/payment/check     → Store\OrdersController@checkBaoKimPaymentStatus
POST   store/orders/{id}/delete            → Store\OrdersController@delete
```

#### Backend (Admin) Routes — Middleware: `auth, backend`
```
# Customer Management
RESOURCE admin/brand/customers             → Admin\Brand\CustomerController
GET    admin/brand/customers/login-as/{uid} → loginAs
GET    admin/brand/customers/{uid}/one-click-login → oneClickLogin
POST   admin/brand/customers/{uid}/assign-plan → assignPlan
GET    admin/brand/customers/{id}/balance  → balance
GET    admin/brand/customers/{uid}/su-account → subAccount

# Plans
RESOURCE admin/brand/plans                 → Admin\Brand\PlanController
# ... full CRUD + billing cycle + sending limit

# Domains, Hostings, Website Templates
RESOURCE admin/brand/domain                → Admin\Brand\DomainController
RESOURCE admin/brand/hosting               → Admin\Brand\HostingController
RESOURCE admin/brand/websitetemplates      → Admin\Brand\WebsiteTemplateController

# WordPress Connect
GET|POST store/wordpress/connect/{customer_uid} → Admin\Store\WordPressController@connect

# Admin Order Management (mirrors customer routes with customer_uid)
POST   admin/store/{customer_uid}/orders/{id}/confirm → Admin\Brand\OrderController@confirm
# ... all order status routes with customer_uid prefix

# Payout
GET|POST admin/brand/payout/{customer_uid}/add → Admin\Brand\PayoutController@add
```

#### Public API Routes
```
GET    api/brand                           → Api\Brand\ApiController@endpoint
GET    api/brand/address/provinces         → Api\Brand\AddressController@provinces
GET    api/brand/address/districts         → Brand\AddressController@districts
GET    api/brand/address/wards             → Brand\AddressController@wards
GET    api/brand/get-price                 → Api\Brand\ShippingController@getPrice

# Authenticated (auth:api)
GET    api/brand/get-warehouse             → Api\Brand\ShippingController@getWarehouse
GET    api/brand/get-ghn-price             → Api\Brand\ShippingController@getGHNPrice
GET    api/brand/get-vbrand-express-price  → Api\Brand\ShippingController@getVbrandExpressPrice
```

#### Mobile App API Routes — `api/v1/brand` — Middleware: `auth:api, api_brand_init`

> **CRITICAL**: API routes MUST include `api_brand_init` middleware.
> This middleware (`ApiBrandInit.php`) initializes `WordpressConnectionFacade::setWordpress()`
> which is required for ALL `Product::list()`, `Order::list()` etc. calls.
> Without it, the WordPress/WooCommerce connection is null and all data returns empty.
>
> The webapp uses the `frontend` middleware which does the same thing but also handles
> web redirects (not suitable for JSON APIs). `api_brand_init` is the API equivalent.

```
# Route file: routes/brand_api_v1.php
# Controllers: app/Http/Controllers/Brand/Api/*

# Public (no auth)
POST   api/v1/brand/auth/login             → AuthController@login

# Protected (auth:api + api_brand_init)
GET    api/v1/brand/auth/me                → AuthController@me
POST   api/v1/brand/auth/logout            → AuthController@logout
GET    api/v1/brand/dashboard              → DashboardController@index
#      Returns: stats (order/product counts), active_theme (id, name, thumbnail), recent_orders, store

# Orders
GET    api/v1/brand/orders                 → OrderController@index
GET    api/v1/brand/orders/stats           → OrderController@stats
GET    api/v1/brand/orders/{id}            → OrderController@show
POST   api/v1/brand/orders/{id}/confirm    → OrderController@confirm
POST   api/v1/brand/orders/{id}/seller-confirm → OrderController@sellerConfirm
POST   api/v1/brand/orders/{id}/deliver    → OrderController@deliver
POST   api/v1/brand/orders/{id}/set-packaged → OrderController@setPackaged
POST   api/v1/brand/orders/{id}/set-delivering → OrderController@setDelivering
POST   api/v1/brand/orders/{id}/set-delivered → OrderController@setDelivered
POST   api/v1/brand/orders/{id}/complete   → OrderController@setComplete
POST   api/v1/brand/orders/{id}/pay        → OrderController@pay
POST   api/v1/brand/orders/{id}/seller-cancel → OrderController@sellerCancel
POST   api/v1/brand/orders/{id}/refund     → OrderController@refund

# Products
GET    api/v1/brand/products               → ProductController@index
GET    api/v1/brand/products/categories    → ProductController@categories
GET    api/v1/brand/products/{id}          → ProductController@show
POST   api/v1/brand/products               → ProductController@store
PUT    api/v1/brand/products/{id}          → ProductController@update
DELETE api/v1/brand/products/{id}          → ProductController@destroy

`ProductDTO::summary()` và `ProductDTO::detail()` trả thêm field `permalink` cho từng sản phẩm. `brand/mobile/products` dùng field này để copy public product link trực tiếp từ WordPress site.

# Profile
GET    api/v1/brand/profile                → ProfileController@show
PUT    api/v1/brand/profile                → ProfileController@update
PUT    api/v1/brand/profile/password       → ProfileController@updatePassword

# Address
GET    api/v1/brand/address/provinces      → AddressController@provinces
GET    api/v1/brand/address/districts      → AddressController@districts
GET    api/v1/brand/address/wards          → AddressController@wards
```

---

## 15. Prompt Guide: How to Work with This Codebase

### 15.1 Adding a New Feature to Customer Panel
```
1. Define route in: app/routes/brand.php (frontend group)
2. Create/edit controller in: app/app/Http/Controllers/Brand/
3. If involves WordPress data:
   a. Create API wrapper in: app/app/Wordpress/{Entity}.php
   b. Create REST endpoint in: brandsite/wp-content/plugins/vbrandsync/wordpress/api/{entity}.php
   c. Create WP model wrapper in: brandsite/wp-content/plugins/vbrandsync/app/Wordpress/Models/{Entity}.php
4. Create views in: app/resources/views/brand/
```

### 15.2 Adding a New Theme Customization Option
```
1. Edit theme schema: brandsite/wp-content/themes/{theme}/schema.php
   - Add to 'options' array with appropriate session, type, name, label, default
2. Use in theme template: $themeData->get('your_option_name')
3. The Laravel form automatically generates based on schema
   - WebsiteController::fillSchema() handles all field types
```

### 15.3 Adding a New Order Status
```
1. WordPress plugin.php:
   - register_post_status('wc-new_status', [...])
   - Add to add_custom_order_status_to_wc() filter
2. WordPress Order model (vbrandsync/app/Wordpress/Models/Order.php):
   - Add const STATUS_NEW = 'new_status'
   - Add setNewStatus() method
3. WordPress API (vbrandsync/wordpress/api/order.php):
   - Register REST route: order/set-new-status/{id}
4. Laravel Order wrapper (app/app/Wordpress/Order.php):
   - Add const URI_SET_NEW_STATUS = 'order/set-new-status/{id}'
   - Add const STATUS_NEW = 'new_status'
   - Add setNewStatus() method that calls API
5. Laravel Controller (Store/OrdersController.php or Admin/Brand/OrderController.php):
   - Add controller method
6. Route in brand.php:
   - Add POST route
```

### 15.4 Adding a New Product Feature
```
1. WordPress Product model (vbrandsync/app/Wordpress/Models/Product.php):
   - Add property, update save(), mapFromWPPostId(), fillParams()
2. WordPress API (vbrandsync/wordpress/api/product.php):
   - Update relevant endpoint handlers
3. Laravel Product wrapper (app/app/Wordpress/Product.php):
   - Add property, update mapping(), fillParams(), saveFromParams()
4. Update views as needed
```

### 15.5 Connecting a New Customer to WordPress
```
1. Admin: admin/brand/customers → create customer
2. Admin: store/wordpress/connect/{customer_uid}
   - Set customer.wordpress_endpoint = "https://site.com/wp-json/vbrandsync/v1"
3. WordPress side: Install vbrandsync plugin
4. Via API or manually: Set vbrand_endpoint and vbrand_token in WordPress settings
   - Or: $customer->wordpress()->updateBrandAPI($laravelEndpoint, $apiToken)
```

### 15.6 Key File Locations Quick Reference
```
Routes:           app/routes/brand.php        (webapp)
                  app/routes/brand_webapp.php  (mobile webapp)
                  app/routes/brand_api_v1.php  (mobile app API)
Customer Model:   app/app/Model/Customer.php
WP API Client:    app/app/Wordpress/Wordpress.php
WP Product:       app/app/Wordpress/Product.php
WP Order:         app/app/Wordpress/Order.php
WP Article:       app/app/Wordpress/Article.php

Middleware:        app/app/Http/Middleware/Frontend.php      (webapp - sets WP connection)
                  app/app/Http/Middleware/ApiBrandInit.php   (API - sets WP connection)

Plugin Entry:     brandsite/wp-content/plugins/vbrandsync/plugin.php
Plugin APIs:      brandsite/wp-content/plugins/vbrandsync/wordpress/api/*.php
Plugin Models:    brandsite/wp-content/plugins/vbrandsync/app/Wordpress/Models/*.php
Theme Data:       brandsite/wp-content/plugins/vbrandsync/app/Library/ThemeData.php
VBrand Service:   brandsite/wp-content/plugins/vbrandsync/app/Services/VBrand.php
Settings Model:   brandsite/wp-content/plugins/vbrandsync/app/Models/Setting.php
Theme Schema:     brandsite/wp-content/themes/{theme}/schema.php
Payment:          brandsite/wp-content/plugins/vbrandsync/wordpress/payment.php
Shipping:         brandsite/wp-content/plugins/vbrandsync/wordpress/shipping/*.php
```

### 15.7 Common Patterns

#### CRITICAL: WordPress Connection Initialization
```
Any route that calls Product::list(), Order::list(), or any Wordpress model method
MUST first initialize WordpressConnectionFacade::setWordpress().

- Webapp routes: handled by `frontend` middleware (web redirects on error)
- API routes:    handled by `api_brand_init` middleware (JSON errors)

If products/orders return EMPTY from API but work in webapp:
→ Check that `api_brand_init` middleware is in the route group.
→ File: app/app/Http/Middleware/ApiBrandInit.php
→ Registered as alias `api_brand_init` in bootstrap/app.php
```

#### CRITICAL: vbrandsync API Property Names vs WooCommerce REST API
```
The vbrandsync WordPress plugin exposes its OWN REST API with DIFFERENT field names
than the standard WooCommerce REST API. The Laravel Wordpress models (Product, Order,
OrderItem) map from vbrandsync field names, NOT WooCommerce field names.

PRODUCT MODEL (app/app/Wordpress/Product.php):
  vbrandsync API → Laravel Model      (NOT WooCommerce REST API)
  ─────────────────────────────────────────────────────────
  title          → $product->title     (NOT $product->name)
  price          → $product->price     (regular price, often EMPTY)
  discount_price → $product->discount_price  (actual selling price)
  image_url      → $product->image_url       (single thumbnail)
  image_urls     → $product->image_urls      (array of image URLs)
  categories     → $product->categories      (NOT $product->categories[].name)
  
  PRICE GOTCHA: The vbrandsync API often puts the display price in `discount_price`
  and leaves `price` empty. Use discount_price as fallback:
    $displayPrice = !empty($product->discount_price) ? $product->discount_price : $product->price;

ORDER MODEL (app/app/Wordpress/Order.php):
  vbrandsync API → Laravel Model       (NOT WooCommerce REST API)
  ─────────────────────────────────────────────────────────
  first_name     → $order->first_name  (NOT $order->billing->first_name)
  last_name      → $order->last_name   (NOT $order->billing->last_name)
  phone          → $order->phone       (NOT $order->billing->phone)
  email          → $order->email       (NOT $order->billing->email)
  order_items    → $order->order_items (NOT $order->line_items)
  item_count     → $order->item_count  (NOT count($order->line_items))
  shipping_fee   → $order->shipping_fee
  
  Properties are FLAT (not nested in billing/shipping objects).

ORDER ITEM MODEL (app/app/Wordpress/OrderItem.php):
  vbrandsync API → Laravel Model
  ─────────────────────────────────────────────────────────
  id             → $item->id
  order_id       → $item->order_id
  product_id     → $item->product_id
  name           → $item->name          (product name in the order)
  quantity       → $item->quantity
  total          → $item->total
  
  NOTE: OrderItem does NOT have a `price` field. Derive unit price from total/quantity.
  NOTE: OrderItem does NOT have `image_url`. Product images must be fetched separately.

API CONTROLLERS (app/app/Http/Controllers/Brand/Api/):
  ProductController.php - formatProduct() maps model → JSON for mobile app
  OrderController.php   - formatOrder() maps model → JSON for mobile app
  
  These formatters translate vbrandsync model properties into WooCommerce-like
  JSON structure that the mobile app expects (price, regular_price, sale_price,
  billing.first_name, line_items, etc.)
```

#### WordPress API Call Pattern (Laravel side)
```php
// All Wordpress wrapper classes use the same pattern:
public static function wordpress() {
    return WordpressConnectionFacade::getWordpress();
}

// CRUD operations:
$data = self::wordpress()->request('GET', self::URI_LIST, $params);
$data = self::wordpress()->request('POST', $uri, $params);
```

#### Theme Thumbnail Source
```
WordPress theme thumbnails come from: {theme_directory}/screenshot.png
This is the standard WordPress convention.

Flow:
1. Laravel calls: $customer->wordpress()->themeList()
   → Wordpress.php → GET wp-json/vbrandsync/v1/theme/list
2. WP plugin (vbrandsync/wordpress/api/theme.php) returns:
   {
     id: theme.stylesheet,
     name: theme_key,
     thumbUrl: get_template_directory_uri() . '/screenshot.png',
     description: theme.description,
     active: (wp_get_theme()->get('Name') == key)
   }
3. Displayed in:
   - Main app dashboard: <img src="{{ $wordPressTemplate->thumbUrl }}">
   - Webapp dashboard: <img src="{{ $activeTheme->thumbUrl }}">
   - Mobile API: dashboard response → active_theme.thumbnail
   - Mobile app: HomeScreen.tsx → Image source from active_theme.thumbnail
```

#### Schema-Based Form Pattern
```php
// Schema defines structure → form auto-generates
// Options stored as JSON → ThemeData reads/writes
// fillSchema() handles nested form processing recursively
```

#### CRITICAL: wc_get_orders() vs WP_Query Parameters
```
Products use WP_Query directly (post_type='product'), so standard WP params work:
  - posts_per_page, paged, s, meta_query, tax_query ✅

Orders use wc_get_orders() (WC_Order_Query), which has DIFFERENT params:
  - limit    (NOT posts_per_page)
  - page     (NOT paged)
  - status   (array of statuses)
  - type     ('shop_order')

Using posts_per_page/paged with wc_get_orders() silently fails — returns empty.
This is especially true with WooCommerce HPOS (High-Performance Order Storage).

File: site/wp-content/plugins/vbrandsync/app/Wordpress/Models/Order.php
  count() — uses 'limit' => -1  ✅ (works)
  list()  — must use 'limit' and 'page' (NOT posts_per_page/paged)

File: site/wp-content/plugins/vbrandsync/app/Wordpress/Models/Product.php
  count() — uses WP_Query with posts_per_page => -1  ✅
  list()  — uses WP_Query with posts_per_page/paged  ✅ (products are WP posts)
```

#### WooCommerce Status & Date Serialization
```
PROBLEM 1: WooCommerce get_status() returns status WITHOUT 'wc-' prefix
  - $od->get_status() returns "processing", NOT "wc-processing"
  - Mobile StatusBadge.tsx STATUS_MAP expects 'wc-processing', 'wc-completed', etc.
  - Without normalization: falls through to default → [missing "en.processing" translation]

FIX: Prepend 'wc-' in API controllers (OrderController, DashboardController):
  if (!str_starts_with($status, 'wc-')) { $status = 'wc-' . $status; }

PROBLEM 2: WooCommerce get_date_created() returns WC_DateTime object
  - JSON serializes as: {"date":"2026-03-14 08:16:18.000000","timezone_type":1,"timezone":"+00:00"}
  - Mobile new Date(object) → Invalid Date

FIX: Extract date string in API controllers:
  if (is_object($dateCreated) && isset($dateCreated->date)) { $dateCreated = $dateCreated->date; }
  elseif ($dateCreated instanceof \DateTimeInterface) { $dateCreated = $dateCreated->format('Y-m-d H:i:s'); }

Files fixed:
  - app/Http/Controllers/Brand/Api/OrderController.php (formatOrder, formatOrderDetail)
  - app/Http/Controllers/Brand/Api/DashboardController.php (recent_orders)
  - resources/views/webapp/orders/_list.blade.php (status + date via Carbon)
  - resources/views/webapp/orders/show.blade.php (@php block at top of content)
```

#### Order Model Property Mapping (Laravel ↔ Webapp)
```
The Laravel Order model uses FLAT properties, NOT nested objects:
  $order->first_name   (NOT $order->billing->first_name)
  $order->last_name    (NOT $order->billing->last_name)
  $order->email        (NOT $order->billing->email)
  $order->phone        (NOT $order->billing->phone)
  $order->order_items  (NOT $order->line_items)
  $order->shipping_fee (NOT $order->shipping_total)
  $order->total        ✅
  $order->payment_method (NOT $order->payment_method_title)

Properties that DON'T exist on the model:
  - $order->billing (no nested billing object)
  - $order->shipping (no nested shipping object)
  - $order->subtotal (compute as total - shipping_fee - tax)
  - $order->discount_total
  - $order->customer_note
  - $order->line_items (use order_items)
```

#### Order Status Change Pattern
```php
// Laravel: Controller receives request
$order = Order::find($request->id);
$order->statusMethod(); // e.g. confirm(), sellerComfirm()

// Order wrapper calls WordPress API
$uri = str_replace('{id}', $this->id, self::URI_SET_STATUS);
self::wordpress()->request('POST', $uri, ['id' => $this->id]);

// WordPress REST endpoint receives call
$order = \App\Wordpress\Models\Order::find($data['id']);
$order->setStatus(); // Uses wc_get_order()->update_status()
```

---

## 16. Browser Testing (Laravel Dusk)

Automated browser testing for the Mobile Webapp (`/brand/mobile/`) using Laravel Dusk with headless Chrome.

### 16.1 Overview

Dusk tests visit every webapp page in a real browser, checking for:
- PHP errors (ErrorException, TypeError, ParseError, FatalError)
- Laravel error pages (Ignition, Whoops)
- Blade template errors (`number_format()`, `htmlspecialchars()`, undefined properties/variables)
- JavaScript console errors
- AJAX-loaded content errors (product/order list partials)

### 16.2 Setup

```bash
# Already installed. If starting fresh:
composer require laravel/dusk --dev
php artisan dusk:install

# ChromeDriver must match your Chrome version
php artisan dusk:chrome-driver  # auto-detect
# or specify: php artisan dusk:chrome-driver 145
```

**Environment**: `.env.dusk.local` is used automatically when running `artisan dusk`. Key settings:
- `APP_URL=http://127.0.0.1:8000` — local dev server URL
- `APP_BRAND=true` — enables brand/mobile routes
- `DUSK_TEST_EMAIL` / `DUSK_TEST_PASSWORD` — test user credentials

### 16.3 Running Tests

```bash
# 1. Start the dev server (separate terminal)
php artisan serve --port=8000

# 2. Run all webapp browser tests
php artisan dusk --filter=Webapp

# 3. Run specific test suites
php artisan dusk --filter=WebappSmokeTest          # Full page crawl (all pages)
php artisan dusk --filter=ProductsTest              # Products page tests
php artisan dusk --filter=OrdersTest                # Orders page tests
php artisan dusk --filter=DashboardTest             # Dashboard tests

# 4. Run single test
php artisan dusk --filter=test_all_webapp_pages_load_without_errors

# 5. Run with visible browser (debugging)
DUSK_HEADLESS_DISABLED=true php artisan dusk --filter=WebappSmokeTest

# 6. Using the helper script
./dusk-test.sh smoke         # Run smoke tests
./dusk-test.sh products      # Run product tests
./dusk-test.sh --headed      # Visible browser
./dusk-test.sh --serve       # Auto-start dev server
```

### 16.4 Test Structure

```
tests/
├── DuskTestCase.php                    # Base class (mobile viewport, auth, error helpers)
├── Browser/
│   ├── Webapp/
│   │   ├── WebappSmokeTest.php         # Main: crawls ALL pages, checks errors
│   │   ├── DashboardTest.php           # Dashboard specific tests
│   │   ├── ProductsTest.php            # Products + AJAX list tests
│   │   └── OrdersTest.php             # Orders + AJAX list tests
│   └── Pages/Webapp/
│       ├── DashboardPage.php           # Page object
│       ├── ProductsPage.php            # Page object
│       └── OrdersPage.php             # Page object
```

### 16.5 Key Test Capabilities

**DuskTestCase** base class provides:
- `loginAsCustomer($browser)` — authenticates via Dusk session (no form needed)
- `assertNoPageErrors($browser, $name)` — checks page HTML for 7+ error patterns
- `assertNoAjaxErrors($browser, $selector, $context)` — checks AJAX-loaded content
- Mobile viewport: 430×932 (iPhone 14 Pro)
- `pageLoadTimeout(120)` — handles slow WooCommerce API responses
- `--disable-hang-monitor` Chrome flag — prevents renderer crash on slow pages

**WebappSmokeTest** (most important):
- Visits 8 pages in one test: Dashboard, Products, Product Create, Orders, + 4 AJAX endpoints
- Checks 13 error patterns per page
- Takes screenshots on failure (saved to `tests/Browser/screenshots/`)
- Separate tests for: AJAX lifecycle, order details, product edit, price validation, JS errors

### 16.6 Adding Tests for New Pages

```php
// In WebappSmokeTest.php, add to $pages array:
private static array $pages = [
    // ... existing pages ...
    'My New Page' => '/brand/mobile/my-new-page',
];

// For a focused test, create a new file:
// tests/Browser/Webapp/MyNewPageTest.php
class MyNewPageTest extends DuskTestCase
{
    public function test_page_loads_without_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsCustomer($browser);
            $browser->visit('/brand/mobile/my-new-page')
                ->pause(2000);
            $this->assertNoPageErrors($browser, 'My New Page');
        });
    }
}
```

### 16.7 WooCommerce Price Type Issue

WooCommerce API returns prices as **strings** (e.g., `"150000"` not `150000`). All `number_format()` calls in Blade templates must cast to float:

```php
// ❌ Wrong — crashes with string prices
{{ number_format($product->price) }}

// ✅ Correct — cast to float first
{{ number_format((float) $product->price) }}
```

The `test_all_price_displays_are_numeric` test in WebappSmokeTest verifies all `₫` amounts render as proper numbers.

### 16.8 Troubleshooting

| Issue | Solution |
|-------|---------|
| ChromeDriver version mismatch | `php artisan dusk:chrome-driver` (auto-detect) |
| "Unable to receive message from renderer" | `--disable-hang-monitor` flag (already configured) |
| Page timeout after 30s | `pageLoadTimeout(120)` (already configured) |
| TTY mode warning | Normal when redirecting output, doesn't affect test |
| "Failed to connect to localhost:9515" | ChromeDriver not running: start manually or let `prepare()` handle it |
| Tests not found | Use `php artisan dusk`, not `./vendor/bin/pest` for Browser tests |

### 16.9 Known Issues

- **Order Create page** (`/brand/mobile/orders/create`): Skipped — references `Acelle\Model\Order` which doesn't exist (pre-existing issue in `Customer::newOrder()`)
- **Slow tests**: WooCommerce API calls make each page take 20-50 seconds; full smoke suite runs ~3-4 minutes

---

## 17. Architecture Refactor (2026-03)

### 17.1 DTO Layer

Response shaping is now centralized in DTO classes instead of inline in controllers:

| DTO | File | Matches TypeScript |
|-----|------|-------------------|
| `ProductDTO::summary()` | `app/DTOs/ProductDTO.php` | `ProductSummary` |
| `ProductDTO::detail()` | `app/DTOs/ProductDTO.php` | `ProductDetail` |
| `OrderDTO::summary()` | `app/DTOs/OrderDTO.php` | `OrderSummary` |
| `OrderDTO::detail()` | `app/DTOs/OrderDTO.php` | `OrderDetail` |

### 17.2 Error Handling

All WP-calling controllers use `HandlesWPErrors` trait (`app/Wordpress/Concerns/HandlesWPErrors.php`):
- `tryWP(callable, context)` — Execute with try/catch + logging, returns 503 JSON on failure
- `findOrFail(callable, resourceName)` — Find resource or abort 404 JSON

### 17.3 API Logging

`Wordpress.php::request()` now logs:
- **Errors**: `Log::error("WP API cURL error [{method} {uri}]...")`
- **Slow requests** (>3s): `Log::warning("WP API slow [{method} {uri}]...")`
- Includes timing (ms) and HTTP status codes
- **Connection issues**: missing endpoint / no response now throw `WordpressConnectionException` for customer-friendly handling

### 17.4 Bug Fixes Applied

| Bug | Fix | File |
|-----|-----|------|
| BUG-1 | Removed undefined `$request` in Product::list() | `Wordpress/Product.php` |
| BUG-2 | Removed http_build_query() on POST body | `Wordpress/Product.php` |
| BUG-3 | Send actual values not validation rules in Order::update() | `Wordpress/Order.php` |
| BUG-4 | setDelivered() calls setComplete() | `OrderController.php` |
| BUG-5 | pay() returns 501 (not implemented) | `OrderController.php` |
| BUG-6 | Use STATUS_* constants with wc- prefix | `OrderController.php` |
| BUG-7 | customer_id from API data with fallback | `Wordpress/Order.php` |
| BUG-8 | Fixed typo "enpoint" → "endpoint" | `Wordpress/Wordpress.php` |
| BUG-9 | All WP calls wrapped in try/catch via HandlesWPErrors trait | All API controllers |
| BUG-10 | Null guard via findOrFail() | `OrderController.php` |
| BUG-11 | Status from mobile passed through as-is | `OrderController.php` |
| BUG-12 | formatOrderDetail includes all OrderDetail fields | `OrderDTO.php` |
| BUG-13 | Line items include sku field | `OrderDTO.php` |

### 17.5 Product Model Additions

New properties in `Wordpress/Product.php`:
- `slug`, `sku`, `status`, `stock_quantity`, `stock_status`
- `short_description`, `weight`, `manage_stock`

### 17.6 Theme Schema Changes

Schema (`site/wp-content/themes/logitech/schema.php`) improvements:
- **New general options**: `logo`, `favicon`, `site_description`, `phone`, `email`, `address`
- **Shop banners refactored**: 15 individual options → single `shop_banners` list type
- **Backward compatible**: page-homepage.php falls back to old format if new data not present

### 17.7 Related Documentation

| Document | Content |
|----------|---------|
| `docs/THEME_DESIGN.md` | Complete theme system documentation — schema types, ThemeData engine, data flow, developer guides |
| `docs/ARCHITECTURE_REFACTOR.md` | Full refactor plan — DTO design, API standards, migration strategy, implementation roadmap |
| `docs/API_TEST.sh` | Curl test script for all API endpoints |
| `docs/rfq/SUPER_BUYER_DESIGN.md` | Super Buyer architecture — auth, WP connection switching, checkout flow, order management, API contracts |
| `docs/rfq/RFQ_DESIGN.md` | RFQ feature — WooCommerce integration, approve flow, UI components, API contracts |
| `docs/rfq/IMPORT_REQUEST_DESIGN.md` | Import Product Request — seller/admin CRUD, status flow, API contracts |
| `docs/rfq/RFQ_MOBILE_DESIGN.md` | RFQ mobile app — TypeScript types, UI components, dark mode, implementation checklist |

### 17.8 TypeScript Interface Updates

`mobile/src/types/index.ts` updated:
- `OrderSummary`: Added optional `line_items`, `shipping_fee`, `payment_method`, `billing.email`
- `OrderDetail`: Added optional `shipping_method`, `tax`

---

## 18. Super Buyer System

> **Chi tiết đầy đủ**: xem [SUPER_BUYER_DESIGN.md](rfq/SUPER_BUYER_DESIGN.md)

### 18.1 Concept

Super Buyer là vai trò đặc biệt — **1 buyer duy nhất** có thể browse tất cả shops, xem sản phẩm, tạo đơn hàng (Normal + RFQ) trên bất kỳ shop nào.

- Webapp riêng tại `/brand/super-buyer/mobile/*` với **theme cam (orange)**
- **WP connection switch per-request** theo shop đang browse (không có connection cố định)
- Orders tracked trên **cả 2 nơi**: WooCommerce (order thật) + Laravel DB (`super_buyer_orders` table)
- Order list/detail/filter/action của Super Buyer phải đọc từ `OrderStatusCatalog` để tránh drift giữa tabs, badge, mô tả trạng thái, và quyền thao tác

### 18.2 Database

| Table | Mô tả |
|-------|--------|
| `super_buyers` | id, user_id (FK→users), name, phone, company_name, status |
| `super_buyer_orders` | id, uid, super_buyer_id, customer_id, wc_order_id, order_type, quantity, rfq_unit_price, rfq_line_total, rfq_price, rfq_original_total, rfq_approved_total, rfq_status, total, status — UNIQUE(customer_id, wc_order_id) |

**Models:** `Acelle\Model\SuperBuyer`, `Acelle\Model\SuperBuyerOrder`

**Operational backfill:** nếu có RFQ rows cũ trước RFQ v1, chạy `php artisan super-buyer:backfill-rfq` để hydrate `quantity`, `rfq_unit_price`, `rfq_line_total`, `rfq_original_total`, `rfq_approved_total` từ Woo order hiện tại. Có thể preview bằng `php artisan super-buyer:backfill-rfq --dry-run`.

### 18.2.1 Buyer-facing status and cancellation policy

- Super Buyer display status is resolved centrally from `status` + `rfq_status`:
    - `rfq_pending` stays visible while the RFQ is still waiting for seller response
    - `rfq_approved` is only shown while the order is still before shipment handoff (`ordered`, `processing`, `packaging`)
    - from `ready_for_pickup` onward, the UI falls back to the base lifecycle status so the buyer sees the real fulfillment stage
- Super Buyer filters support both `status` and `rfq_status` chips from the shared catalog
- Current buyer cancel policy:
    - allowed: `pending`, `ordered`, `processing`, `packaging`, `rfq_pending`
    - blocked: `ready_for_pickup`, `delivering`, `completed`, cancelled/refunded/incident statuses
- Buyer-facing copy is status-aware:
    - `rfq_pending` => “Hủy yêu cầu báo giá”
    - `pending` => cancel copy explains the order is still waiting for payment/confirmation
    - `ordered` / `processing` => cancel copy explains the shop has not handed over to shipping yet
    - `packaging` / `rfq_approved` => cancel copy explains buyer can still cancel only before shipment handoff

### 18.3 Authentication

- Login riêng: `POST /brand/super-buyer/mobile/login`
- Admin switch link: `/brand/super-buyer/mobile/login-as/{id}`
- Middleware `SuperBuyerInit`: verify `auth()->user()->superBuyer` exists + active, share `$superBuyer` to views

### 18.4 Architecture — WP Connection Switching

```
SuperBuyer browse shop X
    → Controller set WordpressConnectionFacade cho customer X
    → Product::list() gọi WP API của shop X
    → Response về Laravel → render view
```

Pattern giống `Admin\Brand\OrderController` — set facade per customer trước khi gọi WP models.

### 18.5 Key Routes

| Method | URI | Controller | Mô tả |
|--------|-----|-----------|--------|
| GET | `super-buyer/mobile/login` | AuthController@showLogin | Login page |
| POST | `super-buyer/mobile/login` | AuthController@login | Login action |
| GET | `super-buyer/mobile/shops` | ShopController@index | List all shops |
| GET | `super-buyer/mobile/shops/{id}/products` | ProductController@index | Products of a shop |
| GET | `super-buyer/mobile/shops/{id}/products/{pid}` | ProductController@show | Product detail |
| POST | `super-buyer/mobile/checkout` | OrderController@store | Create order (Normal/RFQ) |
| GET | `super-buyer/mobile/orders` | OrderController@index | My orders |
| GET | `super-buyer/mobile/orders/{id}` | OrderController@show | Order detail |

### 18.6 Checkout Flow

Simple checkout: name, SĐT, address, quantity + chọn Normal/RFQ.

```
SuperBuyer submit checkout form
    → Laravel OrderController@store
    → Set WP connection cho target customer
    → Order::add() → POST order/add tới WP
    → WP createOrder() → wc_create_order() + meta
    → Save SuperBuyerOrder tracking record (Laravel DB)
    → Redirect to order detail
```

---

## 19. RFQ (Request For Quotation)

> **Chi tiết đầy đủ**: xem [RFQ_DESIGN.md](rfq/RFQ_DESIGN.md)

### 19.1 Concept

RFQ là loại đơn hàng đặc biệt — **Super Buyer đề xuất giá mua thấp hơn giá gốc**. Seller xem xét và duyệt hoặc bỏ qua.

- RFQ = WooCommerce order với `_order_type = 'rfq'`, `_rfq_unit_price`, `_rfq_line_total`, `_rfq_status`
- Status ban đầu: `wc-rfq_pending` (custom WC status)
- Super Buyer có thể hủy RFQ khi còn ở `rfq_pending`; sau khi seller duyệt thì follow buyer cancellation policy chung của Super Buyer surface
- RFQ v1 chỉ hỗ trợ đúng 1 SKU trên mỗi order; quantity > 1 vẫn hợp lệ

### 19.2 Status Flow

```
Super Buyer tạo đơn RFQ
        │
        ▼
    rfq_pending  →  Seller nhấn "Duyệt RFQ"  →  packaging  →  flow thường
                     (line total → rfq_unit_price x quantity)
                     (recalculate totals)
```

> **CRITICAL:** Khi duyệt RFQ, hệ thống giữ `line item subtotal` gốc, chỉ đổi `line item total` thành `rfq_unit_price x quantity` rồi mới `calculate_totals()`. Cách này giữ được giá gốc và làm RFQ discount hiện rõ trong WooCommerce.

### 19.2.1 Pricing Decision

- `order.total` luôn là giá vận hành hiện tại của đơn.
- Với RFQ đã duyệt, `order.total` phải phản ánh giá negotiated sau approve, không phải giá gốc trước RFQ.
- `rfq_unit_price` là đơn giá buyer đề xuất cho SKU duy nhất của RFQ order.
- `rfq_line_total = rfq_unit_price x quantity` là tổng RFQ hiển thị ra mọi UI.
- Giá trước duyệt phải được lưu ở snapshot riêng, recommended key: `_rfq_original_total`.
- UI lifecycle:
    - `rfq_pending`: `Giá gốc đơn hàng` / `Tổng RFQ đề xuất`
    - `rfq_approved` và `packaging+`: `Giá trước duyệt RFQ` / `Giá đơn hàng hiện tại`
- Không dùng lại `order.total` làm “giá gốc” sau approval.

### 19.3 WooCommerce Meta

| Meta Key | Values | Mô tả |
|----------|--------|--------|
| `_order_type` | `'normal'` \| `'rfq'` | Loại đơn hàng |
| `_rfq_unit_price` | float | Đơn giá RFQ buyer nhập cho SKU duy nhất |
| `_rfq_line_total` | float | Tổng RFQ = `rfq_unit_price x quantity` |
| `_rfq_status` | `'rfq_pending'` \| `'rfq_approved'` | Trạng thái RFQ |
| `_rfq_original_total` | float | Snapshot tổng đơn trước approve RFQ |
| `_rfq_approved_total` | float | Snapshot tổng đơn ngay sau approve RFQ |
| `_rfq_approved_at` | datetime | Thời điểm approve RFQ |
| `_super_buyer_id` | int | ID Super Buyer trên Laravel |

### 19.4 Key Endpoints

| Layer | Endpoint | Mô tả |
|-------|----------|--------|
| WP | `POST order/add` | Tạo order (normal + rfq) — **hiện đang EMPTY, cần implement** |
| WP | `POST order/approve-rfq/{id}` | Duyệt RFQ — đổi giá + chuyển status |
| Laravel Webapp | `POST orders/{id}/approve-rfq` | Webapp route cho seller |
| Laravel API | `POST orders/{id}/approve-rfq` | API route cho mobile |

### 19.5 Config

`config/order_statuses.php` thêm entry `'rfq_pending'` với action `'approve-rfq'` (icon: thumb_up, color: orange).

### 19.6 UI Components

- **RFQ Badge**: orange badge `RFQ` + pricing summary hiển thị `giá gốc → tổng RFQ`
- **RFQ Pricing Summary**: lifecycle-aware block dùng cùng một contract trên webapp, admin, store, Super Buyer và mobile
- **Filter Tab**: tab `RFQ` trong order list filter
- **Status Badge**: orange cho `wc-rfq_pending`

### 19.7 Canonical API/UI Contract

Order DTO/API nên expose thêm các field sau cho RFQ:

- `rfq_original_total`
- `rfq_approved_total`
- `rfq_approved_at`
- `rfq_unit_price`
- `pricing_summary`

`pricing_summary` là contract dùng chung cho mọi surface:

- `mode = normal`
- `mode = rfq_pending_compare`
- `mode = rfq_approved_history`

Mỗi mode trả đủ `current_total`, `rfq_unit_price`, `quantity`, historical snapshot cần thiết, delta và labels để client render mà không phải tự suy luận từ `total`.

---

## 20. Import Product Request

> **Chi tiết đầy đủ**: xem [IMPORT_REQUEST_DESIGN.md](rfq/IMPORT_REQUEST_DESIGN.md)

### 20.1 Concept

Seller yêu cầu đồng bộ sản phẩm từ Shopee/Lazada vào WooCommerce. Admin manually xử lý (scrape + import).

### 20.2 Database

| Table | Columns |
|-------|---------|
| `import_requests` | id, uid, customer_id, platform, shop_url, status, imported_count, notes, timestamps |

**Model:** `Acelle\Model\ImportRequest` — status: `new` → `processing` → `completed` / `failed`

### 20.3 Status Flow

```
Seller tạo request (status: new)
    → Admin xem + bắt đầu xử lý (status: processing)
        → Admin import xong (status: completed, imported_count: N)
        → Hoặc thất bại (status: failed, notes: lý do)
```

### 20.4 Routes

**Seller (Webapp + API):**
| Method | URI | Mô tả |
|--------|-----|--------|
| GET | `import-requests` | List requests |
| POST | `import-requests` | Create request |
| PUT | `import-requests/{uid}` | Update request |
| DELETE | `import-requests/{uid}` | Delete request |

**Admin:**
| Method | URI | Mô tả |
|--------|-----|--------|
| GET | `admin/import-requests` | List all requests |
| PUT | `admin/import-requests/{uid}/status` | Update status + notes |

### 20.5 Priority

Phase sau — implement sau khi Super Buyer + RFQ hoàn thành.
