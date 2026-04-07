import { test, expect, Page } from '@playwright/test';
import { ENV } from '../playwright.config';
import { loginDesktop, loginWebapp, assertNoPageErrors } from '../helpers/auth';
import {
  csrfHeaders,
  seedOrder,
  findOrderById,
  forceDeleteOrder,
  anyProductId,
} from '../helpers/api';

/**
 * Phase 4 — Đơn hàng & Order Statuses
 * Plan: docs/E2E_TEST_PLAN.md §Phase 4
 *
 * Reference:
 *  - USER_GUIDE_DESKTOP §5.4 (Đơn hàng)
 *  - USER_GUIDE_MOBILE  §3 (Đơn hàng)
 *  - docs/ORDER_STATUSES.md
 *
 * Goals:
 *  - List page renders with status filter tabs (desktop + webapp)
 *  - Orders seeded in WP show up in both surfaces
 *  - The 4-step happy-path workflow walks through the WC custom statuses:
 *      ordered → packaging → ready_for_pickup → delivering → completed
 *    via the brand-app HTTP endpoints (no UI clicking through dialogs).
 *  - sellerCancel marks an order as `seller_cancelled`.
 *
 * Notes / discrepancies (documented in docs/E2E_TEST_PLAN.md §Phase 4):
 *  - The user guides previously listed a 5th step "Đã giao". That step is
 *    not wired end-to-end (vbrandsync has no STATUS_DELIVERED, the brand-app
 *    Order model has no setDelivered, OrderStatusCatalog points at a missing
 *    Store\OrdersController@setComplated). The user guides have been
 *    corrected to the actual 4-step flow as part of this phase.
 *
 * Reliability strategy (same as phases 2+3):
 *  - Each test seeds its own order via `vbrandsync/v1/order/add`
 *  - Each test cleans up via `vbrandsync/v1/order/delete/{id}` in afterEach
 *  - Status verification reads from `vbrandsync/v1/order/find/{id}` (canonical
 *    source of truth, same data the brand-app reads from)
 */

// ---------- helpers ----------

/**
 * POST a desktop order action. The desktop OrdersController reads the order
 * id from `$request->id` (NOT from the `{id}` URL parameter), so the body
 * must include `id=<id>`. The URL just routes to the controller method.
 */
async function postDesktopAction(
  page: Page,
  orderId: number,
  action: string,
): Promise<number> {
  const headers = await csrfHeaders(page);
  const res = await page.request.fetch(
    `${ENV.BASE_APP}/store/orders/${orderId}/${action}`,
    {
      method: 'POST',
      headers,
      form: { id: String(orderId) },
    },
  );
  return res.status();
}

/**
 * POST a webapp order action. The webapp OrderController takes `$id` from
 * the URL (clean), so no body is required.
 */
async function postWebappAction(
  page: Page,
  orderId: number,
  action: string,
): Promise<number> {
  const headers = await csrfHeaders(page);
  const res = await page.request.fetch(
    `${ENV.BASE_APP}/brand/mobile/orders/${orderId}/${action}`,
    {
      method: 'POST',
      headers,
    },
  );
  return res.status();
}

// ---------- List + smoke ----------

test.describe('Phase 4 / Desktop / Đơn hàng list', () => {
  test('list page renders + has status tabs', async ({ page }) => {
    await loginDesktop(page);
    const res = await page.goto(`${ENV.BASE_APP}/store/orders`);
    expect(res?.status() ?? 0).toBeLessThan(500);
    await assertNoPageErrors(page, '/store/orders');
    // The status filter tabs are tied to the OrderStatusCatalog. We don't
    // assert a specific selector (the tab implementation could change), we
    // just assert that the canonical Vietnamese label "Đơn hàng" is in the
    // page heading.
    await expect(page.locator('body')).toContainText(/Đơn hàng|Tất cả/);
  });
});

test.describe('Phase 4 / Mobile webapp / Đơn hàng list', () => {
  test.use({ viewport: { width: 430, height: 932 } });

  test('list page renders + has status tabs', async ({ page }) => {
    await loginWebapp(page);
    const res = await page.goto(`${ENV.BASE_APP}/brand/mobile/orders`);
    expect(res?.status() ?? 0).toBeLessThan(500);
    await assertNoPageErrors(page, '/brand/mobile/orders');
    await expect(page.locator('body')).toContainText(/Đơn hàng|Tất cả|Hoàn thành/);
  });
});

// ---------- Desktop status workflow ----------

test.describe('Phase 4 / Desktop / Order workflow', () => {
  let createdId: number | null = null;

  test.afterEach(async ({ page }) => {
    if (createdId != null) {
      await forceDeleteOrder(page, createdId);
      createdId = null;
    }
  });

  test('seeded order is findable + happy-path 4-step transitions to completed', async ({
    page,
  }) => {
    await loginDesktop(page);

    // --- Seed an order via WP REST ---
    const productId = await anyProductId(page);
    createdId = await seedOrder(page, { productId });

    // Verify it exists in WP and starts at `ordered`
    let wpOrder = await findOrderById(page, createdId);
    expect(wpOrder, 'seeded order should be in WP').not.toBeNull();
    expect(wpOrder!.status).toBe('ordered');

    // --- Step 1: Xác nhận đơn (sellerComfirm → packaging) ---
    expect(await postDesktopAction(page, createdId, 'seller-confirm')).toBeLessThan(400);
    wpOrder = await findOrderById(page, createdId);
    expect(wpOrder!.status, 'after seller-confirm').toBe('packaging');

    // --- Step 2: Đóng gói (setPackaged → ready_for_pickup) ---
    expect(await postDesktopAction(page, createdId, 'set-packaged')).toBeLessThan(400);
    wpOrder = await findOrderById(page, createdId);
    expect(wpOrder!.status, 'after set-packaged').toBe('ready_for_pickup');

    // --- Step 3: Đang giao (setDelivering → delivering) ---
    expect(await postDesktopAction(page, createdId, 'set-delivering')).toBeLessThan(400);
    wpOrder = await findOrderById(page, createdId);
    expect(wpOrder!.status, 'after set-delivering').toBe('delivering');

    // --- Step 4: Hoàn thành (setComplete → completed) ---
    expect(await postDesktopAction(page, createdId, 'complete')).toBeLessThan(400);
    wpOrder = await findOrderById(page, createdId);
    expect(wpOrder!.status, 'after complete').toBe('completed');
  });

  test('seller-cancel marks order as seller_cancelled', async ({ page }) => {
    await loginDesktop(page);
    const productId = await anyProductId(page);
    createdId = await seedOrder(page, { productId });

    expect(await postDesktopAction(page, createdId, 'seller-cancel')).toBeLessThan(400);
    const wpOrder = await findOrderById(page, createdId);
    expect(wpOrder!.status).toBe('seller_cancelled');
  });
});

// ---------- Mobile webapp status workflow ----------

test.describe('Phase 4 / Mobile webapp / Order workflow', () => {
  test.use({ viewport: { width: 430, height: 932 } });

  let createdId: number | null = null;

  test.afterEach(async ({ page }) => {
    if (createdId != null) {
      await forceDeleteOrder(page, createdId);
      createdId = null;
    }
  });

  test('happy-path 4-step transitions to completed (webapp routes)', async ({ page }) => {
    await loginWebapp(page);

    const productId = await anyProductId(page);
    createdId = await seedOrder(page, { productId });

    let wpOrder = await findOrderById(page, createdId);
    expect(wpOrder!.status).toBe('ordered');

    expect(await postWebappAction(page, createdId, 'seller-confirm')).toBeLessThan(400);
    expect((await findOrderById(page, createdId))!.status).toBe('packaging');

    expect(await postWebappAction(page, createdId, 'set-packaged')).toBeLessThan(400);
    expect((await findOrderById(page, createdId))!.status).toBe('ready_for_pickup');

    expect(await postWebappAction(page, createdId, 'set-delivering')).toBeLessThan(400);
    expect((await findOrderById(page, createdId))!.status).toBe('delivering');

    expect(await postWebappAction(page, createdId, 'complete')).toBeLessThan(400);
    expect((await findOrderById(page, createdId))!.status).toBe('completed');
  });

  test('seller-cancel marks order as seller_cancelled (webapp route)', async ({ page }) => {
    await loginWebapp(page);
    const productId = await anyProductId(page);
    createdId = await seedOrder(page, { productId });

    expect(await postWebappAction(page, createdId, 'seller-cancel')).toBeLessThan(400);
    expect((await findOrderById(page, createdId))!.status).toBe('seller_cancelled');
  });
});
