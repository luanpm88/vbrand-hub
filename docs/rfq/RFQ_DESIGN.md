# vBrand RFQ (Request For Quotation) - Complete Technical Documentation

## Mục Lục
1. [Tổng Quan](#1-tổng-quan)
2. [RFQ Status Flow](#2-rfq-status-flow)
3. [WooCommerce Integration](#3-woocommerce-integration)
4. [vbrandsync Plugin Changes](#4-vbrandsync-plugin-changes)
5. [Laravel App Changes](#5-laravel-app-changes)
6. [Cross-Platform UI](#6-cross-platform-ui)
7. [API Contracts](#7-api-contracts)
8. [Implementation Checklist](#8-implementation-checklist)

---

## 1. Tổng Quan

### RFQ là gì?
RFQ (Request For Quotation) là loại đơn hàng đặc biệt trong đó **Super Buyer đề xuất giá mua thấp hơn giá gốc** của sản phẩm. Seller xem xét và quyết định duyệt hoặc không.

### Concept
- RFQ là **WooCommerce order** giống order thường, chỉ khác:
  - Có `_order_type = 'rfq'` (thay vì `'normal'`)
  - Có `_rfq_price` = giá đề xuất từ buyer
  - Có `_rfq_status` = `'rfq_pending'` → `'rfq_approved'`
  - Status ban đầu là `wc-rfq_pending` (custom WC status)
- Khi seller duyệt RFQ → giá line item đổi thành `rfq_price`, order status chuyển thành `packaging` (giống flow seller confirm thường)
- **Không có nút Cancel** cho RFQ — seller chỉ cần không duyệt

### Actors
| Actor | Hành động |
|-------|-----------|
| Super Buyer | Tạo đơn RFQ, nhập `rfq_price`, xem trạng thái |
| Seller (Customer) | Xem đơn RFQ, thấy giá đề xuất vs giá gốc vs lời/lỗ, duyệt hoặc bỏ qua |
| Admin | Xem đơn RFQ, không có action (chỉ view) |

---

## 2. RFQ Status Flow

```
                        Super Buyer tạo đơn RFQ
                                │
                                ▼
                    ┌───────────────────────┐
                    │    rfq_pending        │
                    │ "Chờ người bán duyệt" │
                    └───────────┬───────────┘
                                │
                    Seller nhấn "Duyệt RFQ"
                    (line item price → rfq_price)
                    (recalculate totals)
                                │
                                ▼
                    ┌───────────────────────┐
                    │     packaging         │
                    │  "Đã xác nhận đơn"    │
                    │ (rfq_status=approved) │
                    └───────────┬───────────┘
                                │
                    Tiếp tục flow thường:
                    packaging → ready_for_pickup → delivering → completed
```

> **CRITICAL:** Khi duyệt RFQ, hệ thống **ĐỔI GIÁ line item** thành `rfq_price` rồi mới `calculate_totals()`. Điều này đảm bảo `$order->total` phản ánh đúng giá RFQ sau khi duyệt.

---

## 3. WooCommerce Integration

### 3.1 Custom Order Status

Đăng ký `wc-rfq_pending` trong vbrandsync plugin, **follow existing pattern** từ `wc-ordered`, `wc-packaging`, etc.

File: `site/wp-content/plugins/vbrandsync/plugin.php`

```php
// Đăng ký sau các status hiện có (line ~324)
register_post_status('wc-rfq_pending', array(
    'label'                     => _x('Chờ duyệt RFQ', 'Order status', 'textdomain'),
    'public'                    => true,
    'exclude_from_search'       => false,
    'show_in_admin_all_list'    => true,
    'show_in_admin_status_list' => true,
    'label_count'               => _n_noop(
        'RFQ Pending <span class="count">(%s)</span>',
        'RFQ Pending <span class="count">(%s)</span>',
        'textdomain'
    )
));

// Thêm vào filter add_custom_order_status_to_wc (line ~342)
$order_statuses['wc-rfq_pending'] = _x('Chờ duyệt RFQ', 'Order status', 'textdomain');
```

### 3.2 Order Meta Fields

Mỗi order trên WooCommerce lưu thêm meta:

| Meta Key | Type | Values | Mô tả |
|----------|------|--------|--------|
| `_order_type` | string | `'normal'` \| `'rfq'` | Loại đơn hàng |
| `_rfq_price` | float | e.g. `150000` | Giá đề xuất từ buyer (chỉ khi RFQ) |
| `_rfq_status` | string | `'rfq_pending'` \| `'rfq_approved'` \| `null` | Trạng thái RFQ |
| `_super_buyer_id` | int | e.g. `1` | ID Super Buyer trên Laravel (cross-reference) |

Sử dụng WooCommerce API:
```php
$order->update_meta_data('_order_type', 'rfq');
$order->get_meta('_order_type');  // returns 'rfq'
```

---

## 4. vbrandsync Plugin Changes

### 4.1 Implement `order/add` Endpoint (hiện đang EMPTY)

File: `site/wp-content/plugins/vbrandsync/wordpress/api/order.php` (line 38-40)

**Hiện tại:**
```php
function vbrandsync_ajax_order_add() {
    // EMPTY - chưa implement
}
```

**Implement:**
```php
function vbrandsync_ajax_order_add() {
    vbrandsync_getResponse('/');

    $params = $_POST;
    $order = \App\Wordpress\Models\Order::createOrder($params);

    return $order->getAttributes();
}
```

### 4.2 Add `createOrder()` to WP Order Model

File: `site/wp-content/plugins/vbrandsync/app/Wordpress/Models/Order.php`

```php
/**
 * Tạo WooCommerce order mới (cho Super Buyer checkout)
 *
 * @param array $params Keys: product_id, quantity, first_name, last_name,
 *                      phone, email, address_1, city, state,
 *                      order_type(normal|rfq), rfq_price, super_buyer_id
 * @return self
 */
public static function createOrder($params)
{
    $order = wc_create_order();

    // Add product
    $product = wc_get_product($params['product_id']);
    if (!$product) {
        throw new \Exception('Product not found: ' . $params['product_id']);
    }
    $order->add_product($product, intval($params['quantity'] ?? 1));

    // Billing info
    $order->set_billing_first_name($params['first_name'] ?? '');
    $order->set_billing_last_name($params['last_name'] ?? '');
    $order->set_billing_phone($params['phone'] ?? '');
    $order->set_billing_email($params['email'] ?? '');
    $order->set_billing_address_1($params['address_1'] ?? '');
    $order->set_billing_city($params['city'] ?? '');
    $order->set_billing_state($params['state'] ?? '');

    // Order type + RFQ meta
    $orderType = $params['order_type'] ?? 'normal';
    $order->update_meta_data('_order_type', $orderType);

    if ($orderType === 'rfq') {
        $order->update_meta_data('_rfq_price', floatval($params['rfq_price']));
        $order->update_meta_data('_rfq_status', 'rfq_pending');
        $order->set_status('rfq_pending');
    } else {
        $order->set_status('ordered');
    }

    // Super Buyer reference
    if (!empty($params['super_buyer_id'])) {
        $order->update_meta_data('_super_buyer_id', intval($params['super_buyer_id']));
    }

    $order->calculate_totals();
    $order->save();

    // Return as model instance
    $self = new self();
    $self->mappingWcOrder($order);
    return $self;
}
```

### 4.3 Add `approveRfq()` to WP Order Model

```php
/**
 * Duyệt RFQ: đổi giá line item → rfq_price, chuyển status → packaging
 *
 * @return self
 */
public function approveRfq()
{
    $wcOrder = wc_get_order($this->id);
    if (!$wcOrder) {
        throw new \Exception('Order not found: ' . $this->id);
    }

    $rfqStatus = $wcOrder->get_meta('_rfq_status');
    if ($rfqStatus !== 'rfq_pending') {
        throw new \Exception('Order is not in rfq_pending status');
    }

    $rfqPrice = floatval($wcOrder->get_meta('_rfq_price'));

    // Đổi giá line item thành rfq_price
    foreach ($wcOrder->get_items() as $item) {
        $item->set_subtotal($rfqPrice * $item->get_quantity());
        $item->set_total($rfqPrice * $item->get_quantity());
        $item->save();
    }

    // Recalculate & update status
    $wcOrder->calculate_totals();
    $wcOrder->update_meta_data('_rfq_status', 'rfq_approved');
    $wcOrder->update_status('packaging');
    $wcOrder->save();

    $this->mappingWcOrder($wcOrder);
    return $this;
}
```

### 4.4 New REST Endpoint: `order/approve-rfq/{id}`

File: `site/wp-content/plugins/vbrandsync/wordpress/api/order.php`

```php
// order approve-rfq
function vbrandsync_api_order_approve_rfq($request) {
    vbrandsync_getResponse('/');

    $id = $request['id'];
    $order = \App\Wordpress\Models\Order::find($id);

    if (!$order) {
        return new WP_Error('not_found', 'Order not found', ['status' => 404]);
    }

    $order->approveRfq();

    return $order->getAttributes();
}
add_action('rest_api_init', function () {
    register_rest_route('vbrandsync/v1', '/order/approve-rfq/(?P<id>\d+)', array(
        'methods'  => 'POST',
        'callback' => 'vbrandsync_api_order_approve_rfq',
        'permission_callback' => '__return_true',
    ));
});
```

### 4.5 Extend `mappingWcOrder()` with RFQ Meta

File: `site/wp-content/plugins/vbrandsync/app/Wordpress/Models/Order.php`

Thêm properties vào class:
```php
public $order_type;
public $rfq_price;
public $rfq_status;
public $super_buyer_id;
```

Thêm vào `mappingWcOrder()` (sau phần mapping hiện có):
```php
// RFQ meta
$this->order_type = $od->get_meta('_order_type') ?: 'normal';
$this->rfq_price = $od->get_meta('_rfq_price') ?: null;
$this->rfq_status = $od->get_meta('_rfq_status') ?: null;
$this->super_buyer_id = $od->get_meta('_super_buyer_id') ?: null;
```

Thêm vào `getAttributes()` return array:
```php
'order_type' => $this->order_type,
'rfq_price' => $this->rfq_price,
'rfq_status' => $this->rfq_status,
'super_buyer_id' => $this->super_buyer_id,
```

---

## 5. Laravel App Changes

### 5.1 Order Model Wrapper

File: `app/app/Wordpress/Order.php`

**Thêm properties:**
```php
public $order_type;
public $rfq_price;
public $rfq_status;
public $super_buyer_id;
```

**Thêm URI constant:**
```php
const URI_APPROVE_RFQ = 'order/approve-rfq/{id}';
```

**Extend `mapping()` method** — thêm sau các field hiện có:
```php
$this->order_type = $apiData['order_type'] ?? 'normal';
$this->rfq_price = $apiData['rfq_price'] ?? null;
$this->rfq_status = $apiData['rfq_status'] ?? null;
$this->super_buyer_id = $apiData['super_buyer_id'] ?? null;
```

**Extend `add()` method** — thêm fields vào POST data:
```php
'order_type' => $this->order_type ?? 'normal',
'rfq_price' => $this->rfq_price ?? null,
'super_buyer_id' => $this->super_buyer_id ?? null,
```

**Thêm `approveRfq()` method:**
```php
public function approveRfq()
{
    $uri = str_replace('{id}', $this->id, self::URI_APPROVE_RFQ);
    $response = self::wordpress()->request('POST', $uri);
    $this->mapping($response);
    return $this;
}
```

### 5.2 Order Status Config

File: `app/config/order_statuses.php`

Thêm entry mới (đặt trước `'checkout-draft'`):

```php
'rfq_pending' => [
    'label' => 'Chờ duyệt RFQ',
    'description' => 'Đơn RFQ — Người mua muốn mua với giá đề xuất. Duyệt để đồng ý bán.',
    'actions' => [
        [
            'action' => 'approve-rfq',
            'label' => 'Duyệt RFQ',
            'icon' => 'thumb_up',
            'color' => 'orange',
            'type' => 'default',
        ],
    ],
],
```

### 5.3 OrderDTO

File: `app/app/DTOs/OrderDTO.php`

**Extend `summary()` — thêm sau `'payment_method'`:**
```php
'order_type' => $order->order_type ?? 'normal',
'is_rfq'     => ($order->order_type ?? 'normal') === 'rfq',
'rfq_price'  => $order->rfq_price ?? null,
'rfq_status' => $order->rfq_status ?? null,
```

**Extend `detail()` — same fields** (detail extends summary nên tự có, nhưng double check).

### 5.4 Routes

**Webapp** — file: `app/routes/brand_webapp.php`, thêm trong auth group:
```php
Route::post('orders/{id}/approve-rfq', 'Brand\Webapp\OrderController@approveRfq')
    ->name('webapp.orders.approveRfq');
```

**API** — file: `app/routes/brand_api_v1.php`, thêm trong auth group:
```php
Route::post('orders/{id}/approve-rfq', 'Brand\Api\OrderController@approveRfq');
```

### 5.5 Webapp OrderController

File: `app/app/Http/Controllers/Brand/Webapp/OrderController.php`

```php
public function approveRfq(Request $request, $id)
{
    $order = Order::find($id);

    if (!$order) {
        return response()->json(['status' => 'error', 'message' => 'Không tìm thấy đơn hàng'], 404);
    }

    $order->approveRfq();

    // Update tracking table nếu có
    \Acelle\Model\SuperBuyerOrder::where('customer_id', $request->user()->customer->id)
        ->where('wc_order_id', $id)
        ->update(['rfq_status' => 'rfq_approved', 'status' => 'packaging']);

    return response()->json([
        'status' => 'success',
        'message' => 'Đã duyệt RFQ thành công'
    ]);
}
```

### 5.6 API OrderController

File: `app/app/Http/Controllers/Brand/Api/OrderController.php`

```php
public function approveRfq(Request $request, $id)
{
    $order = $this->findOrFail(fn() => Order::find($id), 'Order');

    return $this->tryWP(function () use ($order, $request) {
        $order->approveRfq();

        \Acelle\Model\SuperBuyerOrder::where('customer_id', $request->user()->customer->id)
            ->where('wc_order_id', $order->id)
            ->update(['rfq_status' => 'rfq_approved', 'status' => 'packaging']);

        return response()->json([
            'status' => 'success',
            'message' => 'Đã duyệt RFQ thành công',
            'data' => OrderDTO::detail($order),
        ]);
    }, 'approve RFQ');
}
```

---

## 6. Cross-Platform UI

### 6.1 RFQ Badge (Order List — cả 3 platforms)

Hiển thị trong order list khi `order_type === 'rfq'`:

```html
{{-- Webapp: app/resources/views/webapp/orders/_list.blade.php --}}
{{-- Thêm sau status badge --}}
@if(($order->order_type ?? 'normal') === 'rfq')
    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-orange-50 text-orange-700 border border-orange-200">
        <span class="material-icons-round text-[10px]">request_quote</span>
        RFQ {{ number_format((float)($order->rfq_price ?? 0)) }}₫
    </span>
@endif
```

**Mobile APP (API response)** — `OrderDTO::summary()` trả về `is_rfq`, `rfq_price` → mobile app render badge tương ứng.

**Admin** — tương tự webapp, thêm badge + column `rfq_price` trong admin order list.

### 6.2 RFQ Info Card (Order Detail — seller view)

```html
{{-- Webapp: app/resources/views/webapp/orders/show.blade.php --}}
{{-- Thêm sau status banner --}}
@if(($order->order_type ?? 'normal') === 'rfq')
<div class="px-4 mb-4">
    <div class="bg-orange-50 border border-orange-200 rounded-2xl p-4">
        <div class="flex items-center gap-2 mb-3">
            <span class="material-icons-round text-orange-600 text-[20px]">request_quote</span>
            <span class="text-sm font-bold text-orange-900">Yêu cầu báo giá (RFQ)</span>
        </div>
        <div class="space-y-2">
            <div class="flex justify-between text-xs">
                <span class="text-orange-700">Giá đề xuất</span>
                <span class="text-orange-900 font-bold text-sm">
                    {{ number_format((float)($order->rfq_price ?? 0)) }}₫
                </span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-orange-700">Giá gốc sản phẩm</span>
                <span class="text-orange-900">
                    {{ number_format((float)($order->total ?? 0)) }}₫
                </span>
            </div>
            @php
                $diff = ($order->total ?? 0) - ($order->rfq_price ?? 0);
            @endphp
            <div class="flex justify-between text-xs border-t border-orange-200 pt-2 mt-2">
                <span class="text-orange-700">Chênh lệch</span>
                <span class="font-bold {{ $diff >= 0 ? 'text-red-600' : 'text-emerald-600' }}">
                    {{ $diff >= 0 ? '-' : '+' }}{{ number_format(abs($diff)) }}₫
                    ({{ $diff >= 0 ? 'lỗ' : 'lời' }})
                </span>
            </div>
        </div>
    </div>
</div>
@endif
```

### 6.3 Status Filter Tab

Thêm `rfq_pending` vào status filter tabs trong order list:

```php
// Webapp: app/resources/views/webapp/orders/index.blade.php
// Thêm vào $filterTabs array
['value' => 'wc-rfq_pending', 'label' => 'RFQ', 'icon' => 'request_quote'],
```

### 6.4 Status Badge Color

```php
// Webapp: app/resources/views/webapp/components/_status_badge.blade.php
// Thêm vào $colorMap
'wc-rfq_pending' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-700', 'dot' => 'bg-orange-500'],
```

### 6.5 Action Route Map

```php
// Webapp: app/resources/views/webapp/orders/_list.blade.php
// Thêm vào $actionRouteMap
'approve-rfq' => route('webapp.orders.approveRfq', ['id' => $order->id]),
```

---

## 7. API Contracts

### 7.1 Create Order (Super Buyer → Laravel → WP)

**Laravel → WP (`POST order/add`):**
```
Request:
  product_id:      int (required)
  quantity:        int (default: 1)
  first_name:      string (required)
  last_name:       string
  phone:           string (required)
  email:           string
  address_1:       string
  city:            string
  state:           string
  order_type:      'normal' | 'rfq' (default: 'normal')
  rfq_price:       float (required if order_type='rfq')
  super_buyer_id:  int

Response (same as order/find):
  { id, customer_id, first_name, last_name, email, phone, total, currency,
    status, date_created, date_modified, item_count, payment_method,
    shipping_method, order_items, shipping_fee, tax,
    order_type, rfq_price, rfq_status, super_buyer_id }
```

### 7.2 Approve RFQ

**Laravel → WP (`POST order/approve-rfq/{id}`):**
```
Request: (no body needed)

Response (updated order):
  { ...same as above, with rfq_status='rfq_approved', status='packaging',
    total=(recalculated with rfq_price) }
```

### 7.3 Order List/Detail — Extended Fields

Tất cả order responses giờ có thêm:
```json
{
    "order_type": "rfq",
    "is_rfq": true,
    "rfq_price": 150000,
    "rfq_status": "rfq_pending"
}
```

---

## 8. Implementation Checklist

### Critical Files to Modify

| # | File | Change |
|---|------|--------|
| 1 | `site/wp-content/plugins/vbrandsync/plugin.php` | Register `wc-rfq_pending` custom status |
| 2 | `site/wp-content/plugins/vbrandsync/wordpress/api/order.php` | Implement `order/add` + add `order/approve-rfq/{id}` |
| 3 | `site/wp-content/plugins/vbrandsync/app/Wordpress/Models/Order.php` | Add `createOrder()`, `approveRfq()`, extend `mappingWcOrder()` + `getAttributes()` |
| 4 | `app/app/Wordpress/Order.php` | Add RFQ properties, extend `mapping()` + `add()`, add `approveRfq()` |
| 5 | `app/config/order_statuses.php` | Add `rfq_pending` entry |
| 6 | `app/app/DTOs/OrderDTO.php` | Add RFQ fields to `summary()` + `detail()` |
| 7 | `app/routes/brand_webapp.php` | Add `approve-rfq` route |
| 8 | `app/routes/brand_api_v1.php` | Add `approve-rfq` route |
| 9 | `app/app/Http/Controllers/Brand/Webapp/OrderController.php` | Add `approveRfq()` |
| 10 | `app/app/Http/Controllers/Brand/Api/OrderController.php` | Add `approveRfq()` |
| 11 | `app/resources/views/webapp/orders/_list.blade.php` | RFQ badge |
| 12 | `app/resources/views/webapp/orders/show.blade.php` | RFQ info card |
| 13 | `app/resources/views/webapp/orders/index.blade.php` | RFQ filter tab |
| 14 | `app/resources/views/webapp/components/_status_badge.blade.php` | RFQ color mapping |

### Implementation Order
1. WP plugin: register status → implement `createOrder()` → implement `approveRfq()` → deploy sites
2. Laravel: extend Order model → config → DTO → routes → controllers
3. UI: views update (badge, info card, filter, action)

### Verification
1. Curl test: `POST {wp_endpoint}/order/add` with `order_type=rfq` → verify order created with correct meta
2. Curl test: `POST {wp_endpoint}/order/approve-rfq/{id}` → verify price changed + status updated
3. Webapp: seller order list → see RFQ badge → click → see info card → approve → verify flow
4. API: mobile app → same flow via JSON API
5. Admin: order list → see RFQ badge + filter

---

*Related: [SUPER_BUYER_DESIGN.md](SUPER_BUYER_DESIGN.md) | [IMPORT_REQUEST_DESIGN.md](IMPORT_REQUEST_DESIGN.md) | [VBRAND_SYSTEM_DOCUMENTATION.md](VBRAND_SYSTEM_DOCUMENTATION.md)*
