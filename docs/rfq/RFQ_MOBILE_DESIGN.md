# vBrand RFQ — Seller Mobile App (React Native + Expo) Design

## Mục Lục
1. [Tổng Quan](#1-tổng-quan)
2. [TypeScript Types](#2-typescript-types)
3. [Theme — RFQ Color](#3-theme--rfq-color)
4. [StatusBadge](#4-statusbadge)
5. [OrderCard — RFQ Badge](#5-ordercard--rfq-badge)
6. [OrdersScreen — Filter Tab](#6-ordersscreen--filter-tab)
7. [OrderDetailScreen — RFQ Info Card + Icon](#7-orderdetailscreen--rfq-info-card--icon)
8. [i18n — Translation Keys](#8-i18n--translation-keys)
9. [API — Không Cần Thay Đổi](#9-api--không-cần-thay-đổi)
10. [Implementation Checklist](#10-implementation-checklist)

---

## 1. Tổng Quan

### Mục đích
Document changes trong **seller mobile app** (`/mobile`) để support RFQ orders — **consistent với webapp** seller view.

### Prerequisite
Backend API phải implement trước theo `docs/RFQ_DESIGN.md`:
- vbrandsync plugin: `wc-rfq_pending` custom status, `order/approve-rfq/{id}` endpoint, RFQ meta trong `mappingWcOrder()`
- Laravel: Order model RFQ properties, `OrderDTO` với `is_rfq`/`rfq_price`/`rfq_status`, API route `orders/{id}/approve-rfq`, `order_statuses.php` config với `rfq_pending`

### Scope
- **8 files** sửa, **0 files** tạo mới, **0 screens** mới
- **0 API calls** mới — `ordersApi.action(id, 'approve-rfq')` đã hoạt động generic
- Backward compatible: `is_rfq = undefined` (falsy) → không hiện RFQ UI

### Trạng thái
7/8 files đã implement (✅). Chỉ còn `OrderDetailScreen.tsx` — RFQ Info Card + `thumb_up` icon (❌).

---

## 2. TypeScript Types

**File:** `mobile/src/types/index.ts`

### 2.1 ✅ `OrderStatus` — thêm `wc-rfq_pending` (line 87)

```typescript
export type OrderStatus =
  // ... existing ...
  | 'wc-failed'
  | 'wc-rfq_pending';
```

### 2.2 ✅ `OrderAction` — thêm `approve-rfq` (line 302)

```typescript
export type OrderAction =
  // ... existing ...
  | 'payment/check'
  | 'approve-rfq';
```

### 2.3 ✅ `OrderSummary` — thêm RFQ fields (lines 140-144)

```typescript
export interface OrderSummary {
  // ... existing fields ...
  // RFQ fields
  order_type?: 'normal' | 'rfq';
  is_rfq?: boolean;
  rfq_price?: string | number | null;
  rfq_status?: 'rfq_pending' | 'rfq_approved' | null;
}
```

`OrderDetail extends OrderSummary` → tự kế thừa, không cần sửa.

---

## 3. Theme — RFQ Color

**File:** `mobile/src/theme/index.ts`

### ✅ `rfqPending` color (lines 47, 104)

```typescript
// Colors.light
rfqPending: '#F97316',   // orange-500

// Colors.dark
rfqPending: '#FB923C',   // orange-400
```

---

## 4. StatusBadge

**File:** `mobile/src/components/StatusBadge.tsx`

### ✅ `wc-rfq_pending` vào `STATUS_MAP` (line 27)

```typescript
'wc-rfq_pending': { key: 'orders.rfqPending', colorKey: 'rfqPending' },
```

Hiển thị: orange pill badge "Chờ duyệt RFQ" / "RFQ Pending".

---

## 5. OrderCard — RFQ Badge

**File:** `mobile/src/components/OrderCard.tsx`

### ✅ Orange RFQ price badge (lines 130-143)

Sau `<StatusBadge>`, khi `order.is_rfq === true`:

```tsx
{order.is_rfq && (() => {
  const rfqColor = (colors as any).rfqPending || '#F97316';
  return (
    <View style={[styles.rfqBadge, { backgroundColor: rfqColor + '18', borderColor: rfqColor + '40' }]}>
      <Ionicons name="pricetag" size={10} color={rfqColor} />
      <Text style={[Typography.caption, { color: rfqColor, marginLeft: 3, fontWeight: '600' }]}>
        RFQ {order.rfq_price ? formatPrice(order.rfq_price) : ''}
      </Text>
    </View>
  );
})()}
```

Visual: `[#1234] [Chờ duyệt RFQ] [RFQ 150,000d]`

---

## 6. OrdersScreen — Filter Tab

**File:** `mobile/src/screens/orders/OrdersScreen.tsx`

### ✅ RFQ filter tab (line 22)

```typescript
const STATUS_FILTERS = [
  { key: '', label: 'orders.all' },
  { key: 'wc-ordered', label: 'orders.ordered' },
  { key: 'wc-rfq_pending', label: 'orders.rfqPending' },  // here
  { key: 'wc-packaging', label: 'orders.packaging' },
  // ...
];
```

Vị trí: `Tất cả > Đã đặt > [Chờ duyệt RFQ] > Đóng gói > ...`

---

## 7. OrderDetailScreen — RFQ Info Card + Icon

**File:** `mobile/src/screens/orders/OrderDetailScreen.tsx`

### 7.1 ❌ Thêm `thumb_up` vào ICON_MAP

Sau line 57:

```typescript
const ICON_MAP: Record<string, string> = {
  // ... existing ...
  account_balance: 'card',
  thumb_up: 'thumbs-up',              // THÊM
};
```

### 7.2 ❌ Import `Colors`

```typescript
import { Typography, Spacing, BorderRadius, Shadow, Colors } from '../../theme';
```

### 7.3 ❌ RFQ Info Card

Thêm sau order header card (sau line 134, trước Customer info card).

```tsx
{/* RFQ Info Card */}
{order.is_rfq && order.rfq_price != null && (() => {
  const isDark = colors === Colors.dark;
  const rfq = isDark
    ? { bg: '#431407', border: '#9A3412', icon: '#FB923C', title: '#FDBA74', text: '#FB923C', divider: '#9A3412' }
    : { bg: '#FFF7ED', border: '#FDBA74', icon: '#EA580C', title: '#9A3412', text: '#C2410C', divider: '#FDBA74' };

  const originalTotal = typeof order.total === 'string' ? parseFloat(order.total) : order.total;
  const rfqPrice = typeof order.rfq_price === 'string' ? parseFloat(order.rfq_price) : order.rfq_price!;
  const diff = originalTotal - rfqPrice;
  const isLoss = diff > 0;

  return (
    <View style={[styles.card, { backgroundColor: rfq.bg, borderColor: rfq.border }]}>
      <View style={rfqInfoStyles.header}>
        <Ionicons name="document-text-outline" size={20} color={rfq.icon} />
        <Text style={[Typography.h4, { color: rfq.title, marginLeft: 8 }]}>
          {t('orders.rfqTitle')}
        </Text>
      </View>

      <View style={rfqInfoStyles.row}>
        <Text style={[Typography.body, { color: rfq.text }]}>{t('orders.rfqProposedPrice')}</Text>
        <Text style={[Typography.h4, { color: rfq.title }]}>{formatPrice(rfqPrice)}</Text>
      </View>

      <View style={rfqInfoStyles.row}>
        <Text style={[Typography.body, { color: rfq.text }]}>{t('orders.rfqOriginalPrice')}</Text>
        <Text style={[Typography.body, { color: rfq.title }]}>{formatPrice(order.total)}</Text>
      </View>

      <View style={[rfqInfoStyles.divider, { borderTopColor: rfq.divider }]} />
      <View style={rfqInfoStyles.row}>
        <Text style={[Typography.body, { color: rfq.text }]}>{t('orders.rfqDifference')}</Text>
        <Text style={[Typography.bodySemibold, { color: isLoss ? '#DC2626' : '#16A34A' }]}>
          {isLoss ? '-' : '+'}{formatPrice(Math.abs(diff))}
          {' '}({isLoss ? t('orders.rfqLoss') : t('orders.rfqProfit')})
        </Text>
      </View>
    </View>
  );
})()}
```

### 7.4 ❌ StyleSheet cho RFQ Info Card

Thêm cuối file:

```typescript
const rfqInfoStyles = StyleSheet.create({
  header: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: Spacing.md,
  },
  row: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: Spacing.sm,
  },
  divider: {
    borderTopWidth: 1,
    marginVertical: Spacing.sm,
  },
});
```

### 7.5 Dark mode color table

| Token | Light | Dark |
|-------|-------|------|
| `bg` | `#FFF7ED` (orange-50) | `#431407` (orange-950) |
| `border` | `#FDBA74` (orange-300) | `#9A3412` (orange-800) |
| `icon` | `#EA580C` (orange-600) | `#FB923C` (orange-400) |
| `title` | `#9A3412` (orange-800) | `#FDBA74` (orange-300) |
| `text` | `#C2410C` (orange-700) | `#FB923C` (orange-400) |
| `divider` | `#FDBA74` (orange-300) | `#9A3412` (orange-800) |

### 7.6 Price difference logic

- `diff = originalTotal - rfqPrice`
- `diff > 0` → seller mất tiền → đỏ (`#DC2626`) + "(lỗ)"
- `diff <= 0` → seller lời → xanh (`#16A34A`) + "(lời)"

### 7.7 ✅ Action button — Tự hoạt động

1. API trả `status_actions: [{ action: 'approve-rfq', label: 'Duyệt RFQ', icon: 'thumb_up', color: 'orange' }]`
2. `ACTION_COLORS.orange = '#F97316'` — đã có (line 43)
3. `ICON_MAP.thumb_up = 'thumbs-up'` — cần thêm (7.1)
4. `handleAction()` gọi `ordersApi.action(id, 'approve-rfq')` → refresh → `packaging`

---

## 8. i18n — Translation Keys

**Files:** `mobile/src/i18n/vi.ts` + `en.ts`

### ✅ Đã thêm (lines 83-90 cả 2 files)

| Key | Vietnamese | English |
|-----|-----------|---------|
| `orders.rfqPending` | Chờ duyệt RFQ | RFQ Pending |
| `orders.rfqTitle` | Yêu cầu báo giá (RFQ) | Request for Quotation (RFQ) |
| `orders.rfqProposedPrice` | Giá đề xuất | Proposed Price |
| `orders.rfqOriginalPrice` | Giá gốc sản phẩm | Original Price |
| `orders.rfqDifference` | Chênh lệch | Difference |
| `orders.rfqLoss` | lỗ | loss |
| `orders.rfqProfit` | lời | profit |

---

## 9. API — Không Cần Thay Đổi

**File:** `mobile/src/api/orders.ts`

| Feature | Code | Ghi chú |
|---------|------|---------|
| List với RFQ fields | `ordersApi.list()` | TypeScript types handle |
| Filter `wc-rfq_pending` | `ordersApi.list({ status: 'wc-rfq_pending' })` | Đã pass `status` param |
| Approve RFQ | `ordersApi.action(id, 'approve-rfq')` | Generic action method |
| Stats | `ordersApi.stats()` | `Record<string, number>` — nhận key bất kỳ |

> **Backend prerequisite:** `OrderDTO` phải trả `order_type`, `is_rfq`, `rfq_price`, `rfq_status`. Config `order_statuses.php` phải có `rfq_pending` với action `approve-rfq`. Xem [RFQ_DESIGN.md](RFQ_DESIGN.md).

---

## 10. Implementation Checklist

| # | File | Change | Status |
|---|------|--------|--------|
| 1 | `mobile/src/types/index.ts` | `wc-rfq_pending` + `approve-rfq` + RFQ fields | ✅ |
| 2 | `mobile/src/theme/index.ts` | `rfqPending` color (light + dark) | ✅ |
| 3 | `mobile/src/i18n/vi.ts` | 7 RFQ translation keys | ✅ |
| 4 | `mobile/src/i18n/en.ts` | 7 RFQ translation keys | ✅ |
| 5 | `mobile/src/components/StatusBadge.tsx` | `wc-rfq_pending` mapping | ✅ |
| 6 | `mobile/src/components/OrderCard.tsx` | RFQ price badge (orange pill) | ✅ |
| 7 | `mobile/src/screens/orders/OrdersScreen.tsx` | RFQ filter tab | ✅ |
| 8 | `mobile/src/screens/orders/OrderDetailScreen.tsx` | `thumb_up` icon + RFQ Info Card | ❌ |

### Verification

1. **Type check:** `npx tsc --noEmit`
2. **Backward compat:** `is_rfq = undefined` → no RFQ UI
3. **Dark mode:** Toggle → RFQ card readable cả 2 mode
4. **E2E (sau backend deploy):**
   - Orders tab → "Chờ duyệt RFQ" filter → thấy RFQ orders
   - Order detail → RFQ Info Card (giá đề xuất / gốc / chênh lệch)
   - "Duyệt RFQ" button → confirm → order → `packaging`

### Out of scope
- Dashboard RFQ stats widget
- Push notifications cho new RFQ
- RFQ creation từ mobile (Super Buyer dùng webapp)
- Batch RFQ approval

---

*Prerequisite: [RFQ_DESIGN.md](RFQ_DESIGN.md) | Related: [SUPER_BUYER_DESIGN.md](SUPER_BUYER_DESIGN.md)*
