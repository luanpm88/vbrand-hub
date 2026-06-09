> 📦 **DESIGN ARCHIVE — never shipped.** This was a design for the legacy fork and is OUT OF SCOPE for the `acelle/brand` plugin (see plugin PLAN.md decision D12). Kept as a design reference only.

# vBrand Import Product Request - Complete Technical Documentation

## Mục Lục
1. [Tổng Quan](#1-tổng-quan)
2. [Database Design](#2-database-design)
3. [Status Flow](#3-status-flow)
4. [Seller Side (Webapp + API)](#4-seller-side-webapp--api)
5. [Admin Side](#5-admin-side)
6. [API Contracts](#6-api-contracts)
7. [Implementation Checklist](#7-implementation-checklist)

---

## 1. Tổng Quan

### Import Request là gì?
Seller có thể **yêu cầu đồng bộ sản phẩm** từ shop của họ trên các nền tảng khác (Shopee, Lazada, v.v.) vào WooCommerce. Yêu cầu được gửi lên, **admin manually xử lý** (scrape + import), rồi cập nhật trạng thái.

### Flow cơ bản
```
Seller nhập shop URL (Shopee/Lazada)
    → Tạo Import Request (status: new)
        → Admin xem + xử lý manually (status: processing)
            → Admin import xong (status: completed, imported_count: N)
```

### Actors
| Actor | Hành động |
|-------|-----------|
| Seller | Tạo request, xem danh sách, sửa/xóa request của mình, xem trạng thái |
| Admin | Xem tất cả requests, cập nhật status + imported_count, CRUD |

### Nơi lưu trữ
- **Laravel DB** (`import_requests` table) — KHÔNG lưu trên WP
- Lý do: đây là tính năng quản lý giữa seller và admin, không liên quan WooCommerce

---

## 2. Database Design

### `import_requests` Table

```sql
CREATE TABLE import_requests (
    id              BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    uid             VARCHAR(36) UNIQUE NOT NULL,
    customer_id     BIGINT UNSIGNED NOT NULL,        -- FK → customers.id
    platform        VARCHAR(50) DEFAULT 'shopee',    -- shopee | lazada | other
    shop_url        TEXT NOT NULL,                    -- URL shop trên nền tảng
    status          VARCHAR(30) DEFAULT 'new',       -- new | processing | completed | failed
    imported_count  INT DEFAULT 0,                   -- số SP đã import
    notes           TEXT NULL,                        -- ghi chú admin
    created_at      TIMESTAMP NULL,
    updated_at      TIMESTAMP NULL,
    INDEX (customer_id),
    INDEX (status)
);
```

**Migration file:** `app/database/migrations/2026_03_17_000003_create_import_requests_table.php`

### Eloquent Model

File: `app/app/Model/ImportRequest.php`

```php
namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use Acelle\Library\Traits\HasUid;

class ImportRequest extends Model
{
    use HasUid;

    protected $fillable = [
        'uid', 'customer_id', 'platform', 'shop_url',
        'status', 'imported_count', 'notes',
    ];

    // Status constants
    const STATUS_NEW = 'new';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';

    // Platform constants
    const PLATFORM_SHOPEE = 'shopee';
    const PLATFORM_LAZADA = 'lazada';
    const PLATFORM_OTHER = 'other';

    public function customer()
    {
        return $this->belongsTo('Acelle\Model\Customer');
    }

    public static function statusOptions()
    {
        return [
            self::STATUS_NEW => 'Mới',
            self::STATUS_PROCESSING => 'Đang xử lý',
            self::STATUS_COMPLETED => 'Hoàn thành',
            self::STATUS_FAILED => 'Thất bại',
        ];
    }

    public static function platformOptions()
    {
        return [
            self::PLATFORM_SHOPEE => 'Shopee',
            self::PLATFORM_LAZADA => 'Lazada',
            self::PLATFORM_OTHER => 'Khác',
        ];
    }

    // Scopes
    public function scopeOfCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeSearch($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('shop_url', 'like', "%{$keyword}%")
                  ->orWhere('platform', 'like', "%{$keyword}%");
            });
        }
        return $query;
    }
}
```

### DTO

File: `app/app/DTOs/ImportRequestDTO.php`

```php
namespace Acelle\DTOs;

class ImportRequestDTO
{
    public static function summary($request): array
    {
        return [
            'uid' => $request->uid,
            'customer_name' => $request->customer ? $request->customer->displayName() : null,
            'platform' => $request->platform,
            'shop_url' => $request->shop_url,
            'status' => $request->status,
            'status_label' => \Acelle\Model\ImportRequest::statusOptions()[$request->status] ?? $request->status,
            'imported_count' => $request->imported_count,
            'notes' => $request->notes,
            'created_at' => $request->created_at ? $request->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $request->updated_at ? $request->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
```

---

## 3. Status Flow

```
    Seller tạo yêu cầu
           │
           ▼
    ┌─────────────┐
    │     new      │ ← Seller có thể sửa/xóa
    │    (Mới)     │
    └──────┬──────┘
           │ Admin bắt đầu xử lý
           ▼
    ┌─────────────┐
    │  processing  │ ← Admin đang scrape + import
    │ (Đang xử lý)│
    └──────┬──────┘
           │
     ┌─────┴─────┐
     ▼           ▼
┌─────────┐ ┌─────────┐
│completed│ │ failed  │
│(Hoàn    │ │(Thất    │
│ thành)  │ │ bại)    │
└─────────┘ └─────────┘
```

**Status descriptions:**
| Status | Label | Mô tả | Seller action |
|--------|-------|--------|---------------|
| `new` | Mới | Yêu cầu vừa tạo, chờ admin | Sửa, Xóa |
| `processing` | Đang xử lý | Admin đang scrape/import SP | Chỉ xem |
| `completed` | Hoàn thành | Import xong | Xem số SP imported |
| `failed` | Thất bại | Không import được | Xem ghi chú admin |

---

## 4. Seller Side (Webapp + API)

### 4.1 UI Entry Point

**Nút "Đồng bộ sản phẩm"** trong product list (cả 3 platforms):

File: `app/resources/views/webapp/products/index.blade.php`

```html
{{-- Thêm button trong header, cạnh nút "Thêm sản phẩm" --}}
<a href="{{ route('webapp.importRequests') }}"
   class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
    <span class="material-icons-round text-white text-[20px]">cloud_download</span>
</a>
```

Nếu có request pending, show badge:
```html
@if($importRequestCount > 0)
    <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-orange-500 text-white text-[9px] flex items-center justify-center">
        {{ $importRequestCount }}
    </span>
@endif
```

### 4.2 Webapp Routes

Thêm vào `app/routes/brand_webapp.php` (trong auth group):

```php
// Import Requests
Route::get('import-requests', 'Brand\Webapp\ImportRequestController@index')
    ->name('webapp.importRequests');
Route::get('import-requests/list', 'Brand\Webapp\ImportRequestController@list')
    ->name('webapp.importRequests.list');
Route::post('import-requests/store', 'Brand\Webapp\ImportRequestController@store')
    ->name('webapp.importRequests.store');
Route::get('import-requests/{uid}/edit', 'Brand\Webapp\ImportRequestController@edit')
    ->name('webapp.importRequests.edit');
Route::post('import-requests/{uid}/update', 'Brand\Webapp\ImportRequestController@update')
    ->name('webapp.importRequests.update');
Route::post('import-requests/{uid}/delete', 'Brand\Webapp\ImportRequestController@delete')
    ->name('webapp.importRequests.delete');
```

### 4.3 Webapp Controller

File: `app/app/Http/Controllers/Brand/Webapp/ImportRequestController.php`

```php
namespace Acelle\Http\Controllers\Brand\Webapp;

use Acelle\Model\ImportRequest;

class ImportRequestController extends Controller
{
    public function index(Request $request)
    {
        return view('webapp.import-requests.index');
    }

    public function list(Request $request)
    {
        $customer = $request->user()->customer;

        $query = ImportRequest::ofCustomer($customer->id)
            ->orderBy('created_at', 'desc');

        if ($status = $request->input('status')) {
            $query->byStatus($status);
        }

        $requests = $query->paginate(20);

        return view('webapp.import-requests._list', compact('requests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'platform' => 'required|in:shopee,lazada,other',
            'shop_url' => 'required|url|max:1000',
        ]);

        $customer = $request->user()->customer;

        ImportRequest::create([
            'customer_id' => $customer->id,
            'platform' => $request->platform,
            'shop_url' => $request->shop_url,
            'status' => ImportRequest::STATUS_NEW,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Đã gửi yêu cầu thành công'
        ]);
    }

    public function edit(Request $request, $uid)
    {
        $customer = $request->user()->customer;
        $importRequest = ImportRequest::ofCustomer($customer->id)
            ->where('uid', $uid)
            ->firstOrFail();

        return view('webapp.import-requests._form', compact('importRequest'));
    }

    public function update(Request $request, $uid)
    {
        $request->validate([
            'platform' => 'required|in:shopee,lazada,other',
            'shop_url' => 'required|url|max:1000',
        ]);

        $customer = $request->user()->customer;
        $importRequest = ImportRequest::ofCustomer($customer->id)
            ->where('uid', $uid)
            ->where('status', ImportRequest::STATUS_NEW) // chỉ sửa được khi new
            ->firstOrFail();

        $importRequest->update([
            'platform' => $request->platform,
            'shop_url' => $request->shop_url,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Đã cập nhật yêu cầu'
        ]);
    }

    public function delete(Request $request, $uid)
    {
        $customer = $request->user()->customer;
        $importRequest = ImportRequest::ofCustomer($customer->id)
            ->where('uid', $uid)
            ->where('status', ImportRequest::STATUS_NEW) // chỉ xóa được khi new
            ->firstOrFail();

        $importRequest->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Đã xóa yêu cầu'
        ]);
    }
}
```

### 4.4 Webapp Views

```
app/resources/views/webapp/import-requests/
    index.blade.php     ← List page + create button
    _list.blade.php     ← AJAX partial (request cards)
    _form.blade.php     ← Create/edit form (modal hoặc inline)
```

**index.blade.php** — layout giống product/order list:
- Header: "Yêu cầu đồng bộ SP" + nút "Tạo yêu cầu mới"
- Filter tabs: Tất cả | Mới | Đang xử lý | Hoàn thành
- AJAX list load

**_list.blade.php** — mỗi request card:
```html
<div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
    <div class="flex items-center justify-between mb-2">
        <div class="flex items-center gap-2">
            {{-- Platform icon --}}
            <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                {{ $request->platform === 'shopee' ? 'bg-orange-50 text-orange-700' : '' }}
                {{ $request->platform === 'lazada' ? 'bg-blue-50 text-blue-700' : '' }}
                {{ $request->platform === 'other' ? 'bg-gray-50 text-gray-700' : '' }}">
                {{ ucfirst($request->platform) }}
            </span>
            {{-- Status badge --}}
            <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                {{ $request->status === 'new' ? 'bg-blue-50 text-blue-700' : '' }}
                {{ $request->status === 'processing' ? 'bg-amber-50 text-amber-700' : '' }}
                {{ $request->status === 'completed' ? 'bg-emerald-50 text-emerald-700' : '' }}
                {{ $request->status === 'failed' ? 'bg-red-50 text-red-700' : '' }}">
                {{ \Acelle\Model\ImportRequest::statusOptions()[$request->status] }}
            </span>
        </div>
        @if($request->status === 'new')
            <div class="flex gap-1">
                <button class="text-gray-400 hover:text-blue-500" title="Sửa">
                    <span class="material-icons-round text-[18px]">edit</span>
                </button>
                <button class="text-gray-400 hover:text-red-500" title="Xóa">
                    <span class="material-icons-round text-[18px]">delete</span>
                </button>
            </div>
        @endif
    </div>
    <a href="{{ $request->shop_url }}" target="_blank"
       class="text-sm text-brand-600 underline break-all">
        {{ $request->shop_url }}
    </a>
    @if($request->status === 'completed')
        <p class="text-xs text-emerald-600 mt-2">
            Đã import {{ $request->imported_count }} sản phẩm
            • {{ $request->updated_at->format('d/m/Y H:i') }}
        </p>
    @endif
    @if($request->notes)
        <p class="text-xs text-gray-500 mt-1">{{ $request->notes }}</p>
    @endif
</div>
```

**_form.blade.php** — form tạo/sửa:
```html
<form @submit.prevent="submitForm($el)">
    <div class="space-y-4">
        <div>
            <label class="text-sm font-medium text-gray-700">Nền tảng</label>
            <select name="platform" class="w-full rounded-xl border-gray-200 ...">
                <option value="shopee">Shopee</option>
                <option value="lazada">Lazada</option>
                <option value="other">Khác</option>
            </select>
        </div>
        <div>
            <label class="text-sm font-medium text-gray-700">Link shop</label>
            <input type="url" name="shop_url" placeholder="https://shopee.vn/shop/..."
                   class="w-full rounded-xl border-gray-200 ..." required>
        </div>
        <button type="submit" class="w-full py-3 bg-brand-600 text-white rounded-xl font-semibold">
            Gửi yêu cầu
        </button>
    </div>
</form>
```

### 4.5 API Routes (cho mobile app)

Thêm vào `app/routes/brand_api_v1.php`:

```php
// Import Requests
Route::get('import-requests', 'Brand\Api\ImportRequestController@index');
Route::post('import-requests', 'Brand\Api\ImportRequestController@store');
Route::get('import-requests/{uid}', 'Brand\Api\ImportRequestController@show');
Route::put('import-requests/{uid}', 'Brand\Api\ImportRequestController@update');
Route::delete('import-requests/{uid}', 'Brand\Api\ImportRequestController@destroy');
```

### 4.6 API Controller

File: `app/app/Http/Controllers/Brand/Api/ImportRequestController.php`

Follow pattern: `HandlesWPErrors` trait (nhưng không cần WP calls — toàn bộ Eloquent), return JSON.

```php
namespace Acelle\Http\Controllers\Brand\Api;

use Acelle\Model\ImportRequest;
use Acelle\DTOs\ImportRequestDTO;

class ImportRequestController extends Controller
{
    public function index(Request $request)
    {
        $customer = $request->user()->customer;

        $query = ImportRequest::ofCustomer($customer->id)
            ->orderBy('created_at', 'desc');

        if ($status = $request->input('status')) {
            $query->byStatus($status);
        }

        $requests = $query->paginate($request->input('per_page', 20));

        return response()->json([
            'status' => 'success',
            'data' => $requests->map(fn($r) => ImportRequestDTO::summary($r)),
            'meta' => [
                'total' => $requests->total(),
                'page' => $requests->currentPage(),
                'per_page' => $requests->perPage(),
                'page_count' => $requests->lastPage(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'platform' => 'required|in:shopee,lazada,other',
            'shop_url' => 'required|url|max:1000',
        ]);

        $importRequest = ImportRequest::create([
            'customer_id' => $request->user()->customer->id,
            'platform' => $request->platform,
            'shop_url' => $request->shop_url,
            'status' => ImportRequest::STATUS_NEW,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => ImportRequestDTO::summary($importRequest),
            'message' => 'Đã gửi yêu cầu thành công',
        ]);
    }

    public function show(Request $request, $uid)
    {
        $importRequest = ImportRequest::ofCustomer($request->user()->customer->id)
            ->where('uid', $uid)
            ->firstOrFail();

        return response()->json([
            'status' => 'success',
            'data' => ImportRequestDTO::summary($importRequest),
        ]);
    }

    public function update(Request $request, $uid)
    {
        $request->validate([
            'platform' => 'sometimes|in:shopee,lazada,other',
            'shop_url' => 'sometimes|url|max:1000',
        ]);

        $importRequest = ImportRequest::ofCustomer($request->user()->customer->id)
            ->where('uid', $uid)
            ->where('status', ImportRequest::STATUS_NEW)
            ->firstOrFail();

        $importRequest->update($request->only(['platform', 'shop_url']));

        return response()->json([
            'status' => 'success',
            'data' => ImportRequestDTO::summary($importRequest->fresh()),
            'message' => 'Đã cập nhật yêu cầu',
        ]);
    }

    public function destroy(Request $request, $uid)
    {
        $importRequest = ImportRequest::ofCustomer($request->user()->customer->id)
            ->where('uid', $uid)
            ->where('status', ImportRequest::STATUS_NEW)
            ->firstOrFail();

        $importRequest->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Đã xóa yêu cầu',
        ]);
    }
}
```

---

## 5. Admin Side

### 5.1 Admin Routes

Thêm vào `app/routes/brand.php` (trong admin brand group):

```php
// Import Requests
Route::get('import-requests', 'Admin\Brand\ImportRequestController@index')
    ->name('admin.brand.importRequests');
Route::get('import-requests/list', 'Admin\Brand\ImportRequestController@list')
    ->name('admin.brand.importRequests.list');
Route::get('import-requests/{uid}/edit', 'Admin\Brand\ImportRequestController@edit')
    ->name('admin.brand.importRequests.edit');
Route::post('import-requests/{uid}/update', 'Admin\Brand\ImportRequestController@update')
    ->name('admin.brand.importRequests.update');
Route::post('import-requests/{uid}/delete', 'Admin\Brand\ImportRequestController@delete')
    ->name('admin.brand.importRequests.delete');
```

### 5.2 Admin Controller

File: `app/app/Http/Controllers/Admin/Brand/ImportRequestController.php`

```php
namespace Acelle\Http\Controllers\Admin\Brand;

use Acelle\Model\ImportRequest;

class ImportRequestController extends Controller
{
    public function index()
    {
        return view('admin.brand.import-requests.index');
    }

    public function list(Request $request)
    {
        $query = ImportRequest::with('customer')
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($status = $request->input('status')) {
            $query->byStatus($status);
        }

        // Filter by customer
        if ($customerId = $request->input('customer_id')) {
            $query->ofCustomer($customerId);
        }

        // Search
        if ($keyword = $request->input('keyword')) {
            $query->search($keyword);
        }

        $requests = $query->paginate(20);

        return view('admin.brand.import-requests.list', compact('requests'));
    }

    public function edit(Request $request, $uid)
    {
        $importRequest = ImportRequest::where('uid', $uid)->firstOrFail();

        return view('admin.brand.import-requests.edit', compact('importRequest'));
    }

    public function update(Request $request, $uid)
    {
        $request->validate([
            'status' => 'required|in:new,processing,completed,failed',
            'imported_count' => 'nullable|integer|min:0',
            'notes' => 'nullable|string|max:2000',
        ]);

        $importRequest = ImportRequest::where('uid', $uid)->firstOrFail();

        $importRequest->update([
            'status' => $request->status,
            'imported_count' => $request->input('imported_count', $importRequest->imported_count),
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.brand.importRequests')
            ->with('success', 'Đã cập nhật yêu cầu');
    }

    public function delete(Request $request, $uid)
    {
        ImportRequest::where('uid', $uid)->firstOrFail()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Đã xóa yêu cầu'
        ]);
    }
}
```

### 5.3 Admin Views

```
app/resources/views/admin/brand/import-requests/
    index.blade.php     ← List page
    list.blade.php      ← AJAX partial (table rows)
    edit.blade.php      ← Edit form
```

**Admin list** — table format:
| Customer | Platform | Shop URL | Status | Imported | Ngày tạo | Actions |
|----------|----------|----------|--------|----------|----------|---------|

**Admin edit form:**
- Status dropdown: Mới | Đang xử lý | Hoàn thành | Thất bại
- Imported count: number input
- Notes: textarea
- Save button

### 5.4 Admin Menu

File: `app/resources/views/layouts/core/_menu_backend_brand.blade.php`

Thêm item mới sau "Đơn hàng":

```php
<li class="...">
    <a href="{{ route('admin.brand.importRequests') }}" class="...">
        <span class="material-icons-round">cloud_download</span>
        <span>Yêu cầu nhập SP</span>
        @if($newImportRequestCount > 0)
            <span class="badge bg-orange-500 text-white">{{ $newImportRequestCount }}</span>
        @endif
    </a>
</li>
```

---

## 6. API Contracts

### 6.1 List Import Requests (Seller)

```
GET /api/v1/brand/import-requests?status=new&page=1&per_page=20

Response:
{
    "status": "success",
    "data": [
        {
            "uid": "abc123",
            "customer_name": "Nike Vietnam",
            "platform": "shopee",
            "shop_url": "https://shopee.vn/nike_official",
            "status": "new",
            "status_label": "Mới",
            "imported_count": 0,
            "notes": null,
            "created_at": "2026-03-17 10:00:00",
            "updated_at": "2026-03-17 10:00:00"
        }
    ],
    "meta": { "total": 3, "page": 1, "per_page": 20, "page_count": 1 }
}
```

### 6.2 Create Import Request

```
POST /api/v1/brand/import-requests

Body:
{
    "platform": "shopee",
    "shop_url": "https://shopee.vn/nike_official"
}

Response:
{
    "status": "success",
    "data": { "uid": "abc123", ... },
    "message": "Đã gửi yêu cầu thành công"
}
```

### 6.3 Update Import Request (Seller — chỉ khi status=new)

```
PUT /api/v1/brand/import-requests/{uid}

Body:
{
    "platform": "lazada",
    "shop_url": "https://lazada.vn/shop/nike"
}
```

### 6.4 Delete Import Request (Seller — chỉ khi status=new)

```
DELETE /api/v1/brand/import-requests/{uid}
```

---

## 7. Implementation Checklist

### New Files

| # | File | Description |
|---|------|-------------|
| 1 | `app/database/migrations/..._create_import_requests_table.php` | Migration |
| 2 | `app/app/Model/ImportRequest.php` | Eloquent model |
| 3 | `app/app/DTOs/ImportRequestDTO.php` | DTO |
| 4 | `app/app/Http/Controllers/Brand/Webapp/ImportRequestController.php` | Seller webapp controller |
| 5 | `app/app/Http/Controllers/Brand/Api/ImportRequestController.php` | Seller API controller |
| 6 | `app/app/Http/Controllers/Admin/Brand/ImportRequestController.php` | Admin controller |
| 7 | `app/resources/views/webapp/import-requests/index.blade.php` | Seller list page |
| 8 | `app/resources/views/webapp/import-requests/_list.blade.php` | Seller list partial |
| 9 | `app/resources/views/webapp/import-requests/_form.blade.php` | Seller form |
| 10 | `app/resources/views/admin/brand/import-requests/index.blade.php` | Admin list page |
| 11 | `app/resources/views/admin/brand/import-requests/list.blade.php` | Admin list partial |
| 12 | `app/resources/views/admin/brand/import-requests/edit.blade.php` | Admin edit form |

### Existing Files to Modify

| # | File | Change |
|---|------|--------|
| 1 | `app/routes/brand_webapp.php` | Add import-request routes |
| 2 | `app/routes/brand_api_v1.php` | Add import-request API routes |
| 3 | `app/routes/brand.php` | Add admin import-request routes |
| 4 | `app/resources/views/webapp/products/index.blade.php` | Add "Đồng bộ SP" button |
| 5 | `app/resources/views/layouts/core/_menu_backend_brand.blade.php` | Add admin menu item |

### Implementation Order

1. Migration + Model + DTO (đã có sẵn nếu chạy Phase 1)
2. Seller webapp: controller + routes + views
3. Seller API: controller + routes
4. Admin: controller + routes + views + menu
5. UI: thêm button trong product list

### Verification

1. Seller webapp: tạo request → xem list → sửa → xóa
2. Seller webapp: tạo request → admin thấy → admin update status → seller thấy status mới
3. API: CRUD qua curl hoặc mobile app
4. Admin: list → filter → edit status + imported_count → save

---

*Related: [RFQ_DESIGN.md](RFQ_DESIGN.md) | [SUPER_BUYER_DESIGN.md](SUPER_BUYER_DESIGN.md) | [VBRAND_SYSTEM_DOCUMENTATION.md](VBRAND_SYSTEM_DOCUMENTATION.md)*
