> 📦 **DESIGN ARCHIVE — never shipped.** This was a design for the legacy fork and is OUT OF SCOPE for the `acelle/brand` plugin (see plugin PLAN.md decision D12). Kept as a design reference only.

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
    - Có `_rfq_unit_price` = đơn giá buyer đề xuất cho SKU duy nhất
    - Có `_rfq_line_total` = `rfq_unit_price x quantity`
  - Có `_rfq_status` = `'rfq_pending'` → `'rfq_approved'`
  - Status ban đầu là `wc-rfq_pending` (custom WC status)
- Khi seller duyệt RFQ → giữ line item subtotal gốc, set line item total = `rfq_unit_price x quantity`, order status chuyển thành `packaging` (giống flow seller confirm thường)
- Sau khi duyệt RFQ, `order total` là **giá vận hành hiện tại** của đơn; giá trước duyệt phải lấy từ snapshot riêng, không lấy lại từ `total`
- Super Buyer có thể hủy RFQ khi còn ở `rfq_pending`; sau khi seller duyệt thì order quay về buyer cancellation policy chung trước giao vận
- RFQ v1 chỉ hỗ trợ **1 SKU / order**. Quantity > 1 vẫn được phép.

### Actors
| Actor | Hành động |
|-------|-----------|
| Super Buyer | Tạo đơn RFQ, nhập `rfq_unit_price`, xem trạng thái |
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
                    (line item total → rfq_unit_price x quantity)
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

> **CRITICAL:** Khi duyệt RFQ, hệ thống **giữ `line item subtotal` gốc** và chỉ set `line item total = rfq_unit_price x quantity` rồi mới `calculate_totals()`. Điều này đảm bảo `$order->total` phản ánh đúng giá RFQ sau khi duyệt mà vẫn giữ được giá gốc trước RFQ.

### 2.1 Pricing Semantics Decision

Đây là quyết định chuẩn để tránh một field mang hai ý nghĩa khác nhau:

- `order.total` = **giá thực tế hiện tại** của đơn tại thời điểm đang xem. Với RFQ đã duyệt, field này phải bằng tổng đã negotiated sau approval.
- `rfq_unit_price` = **đơn giá RFQ buyer đề xuất / seller đã duyệt** cho SKU duy nhất của RFQ order.
- `rfq_line_total` = **tổng RFQ hiển thị** = `rfq_unit_price x quantity`.
- `original_total_before_rfq` = **snapshot bất biến** của tổng đơn trước khi approve RFQ. Chỉ dùng cho UI lịch sử, so sánh chênh lệch, audit trail.

Rule bắt buộc:

- Trước duyệt (`rfq_pending`): UI hiển thị `Giá gốc đơn hàng` và `Tổng RFQ đề xuất`.
- Sau duyệt (`rfq_approved` hoặc base status `packaging` trở đi): UI hiển thị `Giá trước duyệt RFQ` và `Giá đơn hàng hiện tại`.
- Có thể hiển thị thêm `rfq_unit_price x quantity` như explanatory row cho buyer/seller để tránh nhầm giữa đơn giá và tổng RFQ.

RFQ v1 chốt hẳn theo line duy nhất, nên không cần support multi-SKU RFQ trong phase này.

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
| `_rfq_unit_price` | float | e.g. `150000` | Đơn giá buyer đề xuất (chỉ khi RFQ) |
| `_rfq_line_total` | float | e.g. `300000` | Tổng RFQ = `rfq_unit_price x quantity` |
| `_rfq_status` | string | `'rfq_pending'` \| `'rfq_approved'` \| `null` | Trạng thái RFQ |
| `_rfq_original_total` | float | e.g. `220000` | Snapshot tổng đơn trước khi approve RFQ |
| `_rfq_approved_total` | float | e.g. `150000` | Snapshot tổng đơn ngay sau khi approve RFQ |
| `_rfq_approved_at` | datetime string | ISO datetime \| `null` | Thời điểm seller duyệt RFQ |
| `_super_buyer_id` | int | e.g. `1` | ID Super Buyer trên Laravel (cross-reference) |

> Để tương thích ngược, code có thể mirror `_rfq_price` = `_rfq_unit_price` trong giai đoạn chuyển đổi. Tuy nhiên semantic chuẩn mới là `_rfq_unit_price`.

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
 *                      order_type(normal|rfq), rfq_unit_price, super_buyer_id
 * @return self
 */
public static function createOrder($params)
{
    $order = wc_create_order();
     $quantity = intval($params['quantity'] ?? 1);

    // Add product
    $product = wc_get_product($params['product_id']);
    if (!$product) {
        throw new \Exception('Product not found: ' . $params['product_id']);
    }
    $order->add_product($product, $quantity);

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
        $rfqUnitPrice = floatval($params['rfq_unit_price']);
        $order->update_meta_data('_rfq_unit_price', $rfqUnitPrice);
        $order->update_meta_data('_rfq_line_total', $rfqUnitPrice * $quantity);
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
    if ($orderType === 'rfq') {
        $order->update_meta_data('_rfq_original_total', floatval($order->get_total()));
    }
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
 * Duyệt RFQ: giữ line subtotal gốc, set line total theo rfq_unit_price x quantity, chuyển status → packaging
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

    $rfqUnitPrice = floatval($wcOrder->get_meta('_rfq_unit_price'));

    $items = array_values($wcOrder->get_items());
    if (count($items) !== 1) {
        throw new \Exception('RFQ v1 only supports exactly one SKU per order');
    }

    foreach ($items as $item) {
        $originalLineSubtotal = (float) $item->get_subtotal();
        $approvedLineTotal = $rfqUnitPrice * $item->get_quantity();

        if (!$wcOrder->get_meta('_rfq_original_total')) {
            $wcOrder->update_meta_data('_rfq_original_total', floatval($wcOrder->get_total()));
        }

        $wcOrder->update_meta_data('_rfq_original_line_subtotal', $originalLineSubtotal);
        $wcOrder->update_meta_data('_rfq_line_total', $approvedLineTotal);

        $item->set_subtotal($originalLineSubtotal);
        $item->set_total($approvedLineTotal);
        $item->save();
    }

    // Recalculate & update status
    $wcOrder->calculate_totals();
    $wcOrder->update_meta_data('_rfq_status', 'rfq_approved');
    $wcOrder->update_meta_data('_rfq_approved_total', floatval($wcOrder->get_total()));
    $wcOrder->update_meta_data('_rfq_approved_at', current_time('mysql'));
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
public $rfq_original_total;
public $rfq_approved_total;
public $rfq_approved_at;
public $super_buyer_id;
```

Thêm vào `mappingWcOrder()` (sau phần mapping hiện có):
```php
// RFQ meta
$this->order_type = $od->get_meta('_order_type') ?: 'normal';
$this->rfq_price = $od->get_meta('_rfq_price') ?: null;
$this->rfq_status = $od->get_meta('_rfq_status') ?: null;
$this->rfq_original_total = $od->get_meta('_rfq_original_total') ?: null;
$this->rfq_approved_total = $od->get_meta('_rfq_approved_total') ?: null;
$this->rfq_approved_at = $od->get_meta('_rfq_approved_at') ?: null;
$this->super_buyer_id = $od->get_meta('_super_buyer_id') ?: null;
```

Thêm vào `getAttributes()` return array:
```php
'order_type' => $this->order_type,
'rfq_price' => $this->rfq_price,
'rfq_status' => $this->rfq_status,
'rfq_original_total' => $this->rfq_original_total,
'rfq_approved_total' => $this->rfq_approved_total,
'rfq_approved_at' => $this->rfq_approved_at,
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
public $rfq_original_total;
public $rfq_approved_total;
public $rfq_approved_at;
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
$this->rfq_original_total = $apiData['rfq_original_total'] ?? null;
$this->rfq_approved_total = $apiData['rfq_approved_total'] ?? null;
$this->rfq_approved_at = $apiData['rfq_approved_at'] ?? null;
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
'rfq_original_total' => $order->rfq_original_total ?? null,
'rfq_approved_total' => $order->rfq_approved_total ?? null,
'rfq_approved_at'    => $order->rfq_approved_at ?? null,
'pricing_summary'    => static::pricingSummary($order),
```

**Extend `detail()` — same fields** (detail extends summary nên tự có, nhưng double check).

**Add canonical helper:**
```php
protected static function pricingSummary($order)
{
    $isRfq = ($order->order_type ?? 'normal') === 'rfq';

    if (!$isRfq) {
        return [
            'mode' => 'normal',
            'current_total' => $order->total ?? null,
        ];
    }

    $isPending = ($order->rfq_status ?? null) === 'rfq_pending';
    $original = $order->rfq_original_total ?: $order->total;
    $approved = $order->rfq_approved_total ?: $order->total;
    $proposed = $order->rfq_price ?? null;

    return [
        'mode' => $isPending ? 'rfq_pending_compare' : 'rfq_approved_history',
        'current_total' => $order->total ?? null,
        'original_total_before_rfq' => $isPending ? $order->total ?? null : $original,
        'proposed_rfq_total' => $proposed,
        'approved_rfq_total' => $isPending ? null : $approved,
        'delta_amount' => $proposed !== null ? max(0, (float) $original - (float) $proposed) : null,
        'delta_direction' => $proposed !== null && (float) $original >= (float) $proposed ? 'down' : 'up',
        'labels' => $isPending
            ? [
                'primary' => 'Giá gốc đơn hàng',
                'secondary' => 'Giá đề xuất RFQ',
                'delta' => 'Chênh lệch',
            ]
            : [
                'primary' => 'Giá trước duyệt RFQ',
                'secondary' => 'Giá đơn hàng hiện tại',
                'delta' => 'Mức giảm RFQ',
            ],
    ];
}
```

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
            @php
                $summary = $order->pricing_summary ?? [];
                $mode = $summary['mode'] ?? 'normal';
                $primary = $summary['labels']['primary'] ?? 'Giá gốc đơn hàng';
                $secondary = $summary['labels']['secondary'] ?? 'Giá đề xuất RFQ';
                $deltaLabel = $summary['labels']['delta'] ?? 'Chênh lệch';
                $primaryValue = $mode === 'rfq_approved_history'
                    ? ($summary['original_total_before_rfq'] ?? $order->rfq_original_total ?? null)
                    : ($summary['original_total_before_rfq'] ?? $order->total ?? null);
                $secondaryValue = $mode === 'rfq_approved_history'
                    ? ($summary['current_total'] ?? $order->total ?? null)
                    : ($summary['proposed_rfq_total'] ?? $order->rfq_price ?? null);
                $delta = $summary['delta_amount'] ?? null;
            @endphp
            <div class="flex justify-between text-xs">
                <span class="text-orange-700">{{ $primary }}</span>
                <span class="text-orange-900 font-bold text-sm">
                    {{ number_format((float)($primaryValue ?? 0)) }}₫
                </span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-orange-700">{{ $secondary }}</span>
                <span class="text-orange-900">
                    {{ number_format((float)($secondaryValue ?? 0)) }}₫
                </span>
            </div>
            @if($delta !== null)
                <div class="flex justify-between text-xs border-t border-orange-200 pt-2 mt-2">
                    <span class="text-orange-700">{{ $deltaLabel }}</span>
                    <span class="font-bold text-red-600">
                        -{{ number_format(abs((float) $delta)) }}₫
                    </span>
                </div>
            @endif
        </div>
    </div>
</div>
@endif
```

UI rules dùng chung cho mọi surface:

- `rfq_pending_compare`: hiển thị `Giá gốc đơn hàng` + `Giá đề xuất RFQ` + `Chênh lệch`.
- `rfq_approved_history`: hiển thị `Giá trước duyệt RFQ` + `Giá đơn hàng hiện tại` + `Mức giảm RFQ`.
- `normal`: không hiển thị RFQ card.

Best practice cho list view:

- Surface chung như seller orders, admin orders, store orders: dùng 2-line summary compact cho RFQ orders thay vì thêm một cột global cho mọi order.
- Surface chuyên RFQ hoặc audit table có thể thêm cột riêng `Giá trước RFQ`, nhưng component canonical vẫn nên lấy từ cùng pricing summary contract.

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
        order_type, rfq_price, rfq_status,
        rfq_original_total, rfq_approved_total, rfq_approved_at,
        pricing_summary, super_buyer_id }
```

### 7.2 Approve RFQ

**Laravel → WP (`POST order/approve-rfq/{id}`):**
```
Request: (no body needed)

Response (updated order):
  { ...same as above, with rfq_status='rfq_approved', status='packaging',
    total=(recalculated with rfq_price),
    rfq_original_total=(snapshot before approval),
    rfq_approved_total=(snapshot after approval),
    pricing_summary.mode='rfq_approved_history' }
```

### 7.3 Order List/Detail — Extended Fields

Tất cả order responses giờ có thêm:
```json
{
    "order_type": "rfq",
    "is_rfq": true,
    "rfq_price": 150000,
    "rfq_status": "rfq_pending",
    "rfq_original_total": null,
    "rfq_approved_total": null,
    "rfq_approved_at": null,
    "pricing_summary": {
        "mode": "rfq_pending_compare",
        "current_total": 220000,
        "original_total_before_rfq": 220000,
        "proposed_rfq_total": 150000,
        "approved_rfq_total": null,
        "delta_amount": 70000,
        "delta_direction": "down",
        "labels": {
            "primary": "Giá gốc đơn hàng",
            "secondary": "Giá đề xuất RFQ",
            "delta": "Chênh lệch"
        }
    }
}
```

### 7.4 Canonical Contract Rules

- `pricing_summary.mode = normal`: non-RFQ order, chỉ cần `current_total`.
- `pricing_summary.mode = rfq_pending_compare`: so sánh trước duyệt; `current_total` và `original_total_before_rfq` cùng meaning tại thời điểm chưa duyệt.
- `pricing_summary.mode = rfq_approved_history`: historical mode; UI phải dùng `original_total_before_rfq` làm giá cũ và `current_total` làm giá hiện tại.
- Mobile, webapp, store/admin và Super Buyer không tự suy luận label từ `status` nếu đã có `pricing_summary`; chỉ dùng contract này để render.

---

## 8. Implementation Checklist

### Critical Files to Modify

| # | File | Change |
|---|------|--------|
| 1 | `site/wp-content/plugins/vbrandsync/plugin.php` | Register `wc-rfq_pending` custom status |
| 2 | `site/wp-content/plugins/vbrandsync/wordpress/api/order.php` | Implement `order/add` + add `order/approve-rfq/{id}` |
| 3 | `site/wp-content/plugins/vbrandsync/app/Wordpress/Models/Order.php` | Add `createOrder()`, `approveRfq()`, snapshot RFQ totals, extend `mappingWcOrder()` + `getAttributes()` |
| 4 | `app/app/Wordpress/Order.php` | Add RFQ properties, snapshot fields, extend `mapping()` + `add()`, add `approveRfq()` |
| 5 | `app/config/order_statuses.php` | Add `rfq_pending` entry |
| 6 | `app/app/DTOs/OrderDTO.php` | Add RFQ fields + canonical `pricing_summary` to `summary()` + `detail()` |
| 7 | `app/routes/brand_webapp.php` | Add `approve-rfq` route |
| 8 | `app/routes/brand_api_v1.php` | Add `approve-rfq` route |
| 9 | `app/app/Http/Controllers/Brand/Webapp/OrderController.php` | Add `approveRfq()` |
| 10 | `app/app/Http/Controllers/Brand/Api/OrderController.php` | Add `approveRfq()` |
| 11 | `app/resources/views/webapp/orders/_list.blade.php` | RFQ badge + compact pricing summary |
| 12 | `app/resources/views/webapp/orders/show.blade.php` | RFQ pricing summary card driven by `pricing_summary` |
| 13 | `app/resources/views/webapp/orders/index.blade.php` | RFQ filter tab |
| 14 | `app/resources/views/webapp/components/_status_badge.blade.php` | RFQ color mapping |
| 15 | `mobile/src/types/index.ts` | Add RFQ snapshot fields + `pricing_summary` type |
| 16 | `mobile/src/screens/orders/OrderDetailScreen.tsx` | Render lifecycle-aware RFQ pricing summary, not `total` as original |
| 17 | `mobile/src/components/OrderCard.tsx` | Compact RFQ pricing summary row for approved history when needed |
| 18 | `app/resources/views/store/orders/list.blade.php` | Compact RFQ pricing summary |
| 19 | `app/resources/views/admin/brand/orders/list.blade.php` | Compact RFQ pricing summary |
| 20 | `app/resources/views/superbuyer/orders/show.blade.php` | Same detail pricing semantics as seller/mobile |

### Implementation Order
1. WP plugin: register status → implement `createOrder()` → implement `approveRfq()` with `_rfq_original_total` snapshot → expose snapshot fields via API → deploy sites
2. Laravel: extend Order wrapper → DTO → canonical `pricing_summary` helper → routes/controllers
3. UI: views/screens update to consume `pricing_summary` instead of inferring from `total`
4. Verification: test pending vs approved lifecycle on seller, mobile, admin, Super Buyer

### Verification
1. Curl test: `POST {wp_endpoint}/order/add` with `order_type=rfq` → verify order created with correct meta
2. Curl test: `POST {wp_endpoint}/order/approve-rfq/{id}` → verify `total` changed, `rfq_original_total` preserved, `rfq_approved_total` filled
3. Webapp: seller order detail trước approve → thấy `Giá gốc đơn hàng` vs `Giá đề xuất RFQ`
4. Webapp/mobile/admin/superbuyer sau approve → thấy `Giá trước duyệt RFQ` vs `Giá đơn hàng hiện tại`
5. API: mọi clients nhận cùng `pricing_summary.mode` và cùng field names
6. Generic non-RFQ orders không đổi UI và không có pricing summary block RFQ

---

*Related: [SUPER_BUYER_DESIGN.md](SUPER_BUYER_DESIGN.md) | [IMPORT_REQUEST_DESIGN.md](IMPORT_REQUEST_DESIGN.md) | [VBRAND_SYSTEM_DOCUMENTATION.md](VBRAND_SYSTEM_DOCUMENTATION.md)*
