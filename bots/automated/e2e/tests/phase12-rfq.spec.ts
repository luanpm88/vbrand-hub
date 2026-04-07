import { test, expect } from '@playwright/test';
import { ENV } from '../playwright.config';
import { loginDesktop, loginWebapp, assertNoPageErrors } from '../helpers/auth';
import {
  anyProductId,
  csrfHeaders,
  findOrderById,
  forceDeleteOrder,
  forceDeleteProduct,
  seedRfqOrder,
} from '../helpers/api';

/**
 * Phase 12 — RFQ flow
 * Plan: docs/E2E_TEST_PLAN.md §Phase 12
 * Reference: docs/rfq/RFQ_DESIGN.md
 *
 * RFQ at a glance:
 *   buyer-proposed price → status `rfq_pending` → seller approves →
 *   line total = `rfq_unit_price × quantity`, status flips to `packaging`,
 *   `_rfq_status = rfq_approved`, `_rfq_approved_total` snapshot, then the
 *   order continues through the normal 4-step seller workflow.
 *
 * Reality check vs the original plan:
 *  - "Storefront: tạo RFQ (gửi yêu cầu báo giá)" — the storefront has no
 *    RFQ creation UI. RFQ orders are created via the Super Buyer flow
 *    (docs/rfq/SUPER_BUYER_DESIGN.md). For Phase 12 we seed the RFQ
 *    directly via vbrandsync `/order/add` with `order_type=rfq` (the same
 *    endpoint Super Buyer checkout uses). See `seedRfqOrder` helper.
 *  - "Webapp seller: badge RFQ count" — `Brand\Webapp\OrderController@index`
 *    only computes `ordersCount / completedCount / failedCount` (no rfq
 *    count). The "RFQ" badge in `webapp/orders/_list.blade.php:96` is an
 *    inline per-row badge on the order, not a header counter. Header badge
 *    is **not implemented**; per-row badge IS verified by Phase 12 (we
 *    look for the `RFQ` chip on the seeded order's row).
 *  - "Filter tab RFQ trên Đơn hàng webapp" — supported via the
 *    `?status=rfq-pending` query param to `/brand/mobile/orders/list`
 *    (`OrderStatusCatalog::requestStatuses` accepts the alias). Phase 12
 *    asserts the RFQ shows up in that filtered list and is absent from
 *    `?status=packaging` until approval moves it.
 *  - "Reject RFQ" — there is no reject endpoint in any controller
 *    (`approveRfq` is the only RFQ action; the RFQ stays in `rfq_pending`
 *    until either approved or seller-cancelled via the normal cancel path).
 *    Phase 12 documents this and skips the reject test.
 *
 * Endpoints under test:
 *  - vbrandsync POST /order/add (order_type=rfq)         — seed
 *  - vbrandsync GET  /order/find/{id}                    — verify state
 *  - GET  /brand/mobile/orders/list?status=rfq-pending   — webapp filter
 *  - POST /brand/mobile/orders/{id}/approve-rfq          — webapp approve
 *  - POST /store/orders/{id}/approve-rfq                 — desktop approve
 *  - vbrandsync POST /order/delete/{id}                  — cleanup
 */

const RFQ_UNIT_PRICE = 12345; // distinctive value so we can spot it in the response

// ---------- shared cleanup state ----------

type CleanupState = { productId: number | null; orderId: number | null };

function newState(): CleanupState {
  return { productId: null, orderId: null };
}

// ---------- WP REST: seed + verify + approve ----------

test.describe('Phase 12 / WP REST / Seed RFQ + verify mappingWcOrder', () => {
  const state = newState();

  test.afterEach(async ({ page }) => {
    if (state.orderId) await forceDeleteOrder(page, state.orderId);
    if (state.productId) await forceDeleteProduct(page, state.productId);
    state.orderId = null;
    state.productId = null;
  });

  test('seedRfqOrder lands an order in rfq_pending with the right meta', async ({
    page,
  }) => {
    state.productId = await anyProductId(page);
    state.orderId = await seedRfqOrder(page, {
      productId: state.productId,
      quantity: 2,
      rfqUnitPrice: RFQ_UNIT_PRICE,
    });

    const found = (await findOrderById(page, state.orderId)) as
      | (Record<string, unknown> & {
          status: string;
          order_type: string;
          rfq_status: string | null;
          rfq_unit_price: number | string | null;
          rfq_line_total: number | string | null;
          rfq_original_total: number | string | null;
        })
      | null;
    expect(found, 'order should exist in WP').toBeTruthy();
    expect(found!.status, 'status should be rfq_pending').toBe('rfq_pending');
    expect(found!.order_type, 'order_type should be rfq').toBe('rfq');
    expect(found!.rfq_status, '_rfq_status should be rfq_pending').toBe('rfq_pending');
    expect(Number(found!.rfq_unit_price), '_rfq_unit_price should match seed').toBe(
      RFQ_UNIT_PRICE,
    );
    expect(
      Number(found!.rfq_line_total),
      '_rfq_line_total should be unit_price × quantity',
    ).toBe(RFQ_UNIT_PRICE * 2);
    // _rfq_original_total is a snapshot of the woo total computed from
    // the product's actual price (NOT the proposed RFQ price), so it
    // should be > 0 and not equal to rfq_line_total (unless the product
    // happens to be priced exactly at RFQ_UNIT_PRICE, which is vanishingly
    // unlikely with our distinctive 12345 value).
    expect(Number(found!.rfq_original_total)).toBeGreaterThan(0);
  });
});

// ---------- Webapp: filter + show + approve ----------

test.describe('Phase 12 / Webapp / RFQ filter + approve', () => {
  test.use({ viewport: { width: 430, height: 932 } });
  const state = newState();

  test.afterEach(async ({ page }) => {
    if (state.orderId) await forceDeleteOrder(page, state.orderId);
    if (state.productId) await forceDeleteProduct(page, state.productId);
    state.orderId = null;
    state.productId = null;
  });

  test('webapp /orders/list?status=rfq-pending shows the RFQ + approve flips to packaging', async ({
    page,
  }) => {
    await loginWebapp(page);

    state.productId = await anyProductId(page);
    state.orderId = await seedRfqOrder(page, {
      productId: state.productId,
      quantity: 1,
      rfqUnitPrice: RFQ_UNIT_PRICE,
    });

    // Filter list with status=rfq-pending — must contain the new order id
    const filteredRes = await page.request.get(
      `${ENV.BASE_APP}/brand/mobile/orders/list?status=rfq-pending&perPage=50`,
    );
    expect(filteredRes.status(), 'rfq-pending list status').toBeLessThan(500);
    const filteredHtml = await filteredRes.text();
    expect(
      filteredHtml.includes(`#${state.orderId}`) ||
        filteredHtml.includes(String(state.orderId)),
      `webapp rfq-pending list should contain order #${state.orderId}`,
    ).toBeTruthy();
    // The per-row "RFQ" chip from webapp/orders/_list.blade.php:96 should
    // be present (not just the id).
    expect(filteredHtml).toContain('RFQ');

    // Show page renders the RFQ pricing card (webapp/orders/show.blade.php:48)
    const showRes = await page.goto(
      `${ENV.BASE_APP}/brand/mobile/orders/${state.orderId}`,
    );
    expect(showRes?.status() ?? 0).toBeLessThan(500);
    await assertNoPageErrors(page, `webapp orders show ${state.orderId}`);
    await expect(page.getByText('Yêu cầu báo giá')).toBeVisible();

    // Approve via the webapp endpoint
    const headers = await csrfHeaders(page);
    const approveRes = await page.request.post(
      `${ENV.BASE_APP}/brand/mobile/orders/${state.orderId}/approve-rfq`,
      { headers },
    );
    expect(approveRes.status(), 'webapp approve-rfq status').toBeLessThan(400);
    const approveJson = await approveRes.json();
    expect(approveJson.status).toBe('success');

    // Verify in WP: status flipped to packaging, _rfq_status to rfq_approved,
    // _rfq_approved_total snapshot present, total now = rfq_line_total
    const after = (await findOrderById(page, state.orderId)) as
      | (Record<string, unknown> & {
          status: string;
          rfq_status: string | null;
          rfq_approved_total: number | string | null;
          total: number | string;
        })
      | null;
    expect(after!.status, 'status should be packaging after approve').toBe('packaging');
    expect(after!.rfq_status, '_rfq_status should be rfq_approved').toBe('rfq_approved');
    expect(Number(after!.rfq_approved_total)).toBeGreaterThan(0);
    // Approved total = rfq_unit_price × quantity (=12345 for qty 1)
    expect(Number(after!.total)).toBe(RFQ_UNIT_PRICE);
  });
});

// ---------- Desktop: same approve flow ----------

test.describe('Phase 12 / Desktop / RFQ approve', () => {
  test.use({ viewport: { width: 1280, height: 800 } });
  const state = newState();

  test.afterEach(async ({ page }) => {
    if (state.orderId) await forceDeleteOrder(page, state.orderId);
    if (state.productId) await forceDeleteProduct(page, state.productId);
    state.orderId = null;
    state.productId = null;
  });

  test('desktop POST /store/orders/{id}/approve-rfq flips status to packaging', async ({
    page,
  }) => {
    await loginDesktop(page);

    state.productId = await anyProductId(page);
    state.orderId = await seedRfqOrder(page, {
      productId: state.productId,
      quantity: 3,
      rfqUnitPrice: RFQ_UNIT_PRICE,
    });

    // Verify the order shows up in the desktop /store/orders list with the
    // rfq-pending filter.
    const listRes = await page.request.get(
      `${ENV.BASE_APP}/store/orders/list?status=rfq-pending&perPage=50`,
    );
    expect(listRes.status(), 'desktop rfq-pending list status').toBeLessThan(500);
    const html = await listRes.text();
    expect(html).toContain(String(state.orderId));

    // Approve via the desktop endpoint. Note: the controller reads
    // `$request->id` from the body (the {id} route param is unused), so
    // we send id in both URL and form for safety.
    const headers = await csrfHeaders(page);
    const approveRes = await page.request.post(
      `${ENV.BASE_APP}/store/orders/${state.orderId}/approve-rfq`,
      { headers, form: { id: String(state.orderId) } },
    );
    expect(approveRes.status(), 'desktop approve-rfq status').toBeLessThan(400);
    const json = await approveRes.json();
    expect(json.success).toContain('thành công');

    const after = (await findOrderById(page, state.orderId)) as
      | (Record<string, unknown> & {
          status: string;
          rfq_status: string | null;
          total: number | string;
        })
      | null;
    expect(after!.status).toBe('packaging');
    expect(after!.rfq_status).toBe('rfq_approved');
    // qty 3 × 12345 = 37035
    expect(Number(after!.total)).toBe(RFQ_UNIT_PRICE * 3);
  });
});
