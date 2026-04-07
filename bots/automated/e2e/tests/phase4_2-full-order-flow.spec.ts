import { test, expect, Page } from '@playwright/test';
import { ENV } from '../playwright.config';
import {
  loginDesktop,
  loginWebapp,
  loginAdmin,
  assertNoPageErrors,
} from '../helpers/auth';
import {
  csrfHeaders,
  anyProductId,
  customerAddToCart,
  customerCheckout,
  findOrderById,
  forceDeleteOrder,
} from '../helpers/api';

/**
 * Phase 4.2 — Full E2E order flow (customer → seller → admin)
 * Plan: docs/E2E_TEST_PLAN.md §Phase 4.2
 *
 * Reference:
 *  - SALES_HANDOVER §1 (Đơn hàng + Thanh toán + Vận chuyển)
 *  - USER_GUIDE_DESKTOP §5.4 (Cửa hàng → Đơn hàng)
 *  - USER_GUIDE_MOBILE  §3 (Đơn hàng)
 *  - CLAUDE.md §"Site standardization" (COD-only + vBrand Express-only)
 *
 * Goals:
 *  - Validate the entire chain: customer places an order on the storefront →
 *    seller (desktop + webapp) sees it → admin (sgconnect admin) sees it →
 *    seller walks the order through the 4 status transitions to completion →
 *    admin still sees it correctly.
 *  - Cover the cancel edge case from both seller side and admin side.
 *
 * Why this matters:
 *  - Phase 4 tested the status transitions in isolation by seeding orders
 *    directly via the WP REST seed helper. Phase 4.2 verifies the same chain
 *    end-to-end starting from a real customer checkout, which is the only
 *    surface that proves payment + shipping standardization is real.
 *  - Phase 4.2 is the first phase that drives the admin surface — adding the
 *    `loginAdmin` helper and `SELLER_CUSTOMER_UID` env var.
 */

// ---------- helpers ----------

/**
 * POST a desktop seller order action — id in body, NOT in URL params.
 * (Same convention as phase4-orders.spec.ts.)
 */
async function postDesktopAction(page: Page, orderId: number, action: string): Promise<number> {
  const headers = await csrfHeaders(page);
  const res = await page.request.fetch(
    `${ENV.BASE_APP}/store/orders/${orderId}/${action}`,
    { method: 'POST', headers, form: { id: String(orderId) } },
  );
  return res.status();
}

/**
 * POST a webapp seller order action — id in URL.
 */
async function postWebappAction(page: Page, orderId: number, action: string): Promise<number> {
  const headers = await csrfHeaders(page);
  const res = await page.request.fetch(
    `${ENV.BASE_APP}/brand/mobile/orders/${orderId}/${action}`,
    { method: 'POST', headers },
  );
  return res.status();
}

/**
 * POST an admin order action — admin routes are scoped by `customer_uid`,
 * id is in the body (same convention as desktop seller routes).
 */
async function postAdminAction(page: Page, orderId: number, action: string): Promise<number> {
  const headers = await csrfHeaders(page);
  const res = await page.request.fetch(
    `${ENV.BASE_APP}/admin/store/${ENV.SELLER_CUSTOMER_UID}/orders/${orderId}/${action}`,
    { method: 'POST', headers, form: { id: String(orderId) } },
  );
  return res.status();
}

/**
 * Place a real customer order via the storefront Store API. Returns the new
 * order id (status will land in `processing` or `ordered` depending on whether
 * the vbrandsync hooks have converted it).
 */
async function placeCustomerOrder(page: Page): Promise<number> {
  const productId = await anyProductId(page);
  await customerAddToCart(page, productId, 1);
  return customerCheckout(page);
}

/**
 * Some sellers' orders land in `processing` (default WC for COD) right after
 * checkout. The vBrand workflow expects `ordered` as the start state — call
 * the brand-app `confirm` endpoint (URI_SET_ORDERED) once to normalise the
 * status before walking the rest of the flow. No-op if already `ordered`.
 */
async function normaliseToOrdered(page: Page, orderId: number): Promise<void> {
  const wp = await findOrderById(page, orderId);
  if (!wp) throw new Error(`normaliseToOrdered: order ${orderId} not findable`);
  if (wp.status === 'ordered') return;
  // The desktop "confirm" endpoint hits set-ordered. Safe to call from any
  // pre-packaging state.
  await postDesktopAction(page, orderId, 'confirm');
}

// ---------- Test 1: full happy-path with admin verification ----------

test.describe('Phase 4.2 / Full flow / customer → seller → admin → completed', () => {
  let createdId: number | null = null;

  test.afterEach(async ({ page }) => {
    if (createdId != null) {
      await forceDeleteOrder(page, createdId);
      createdId = null;
    }
  });

  test('customer places order → all 3 surfaces see it → seller walks 4-step → completed', async ({
    page,
  }) => {
    // -------- 1. Customer (storefront) places order --------
    createdId = await placeCustomerOrder(page);
    expect(createdId).toBeGreaterThan(0);

    let wp = await findOrderById(page, createdId);
    expect(wp, `customer order ${createdId} should be in WP`).not.toBeNull();
    // Real storefront COD checkout lands in `processing` (default WC) or
    // `ordered` (if vbrandsync converted it). Both are acceptable starting
    // states.
    expect(['ordered', 'processing']).toContain(wp!.status);

    // -------- 2. Seller (desktop) sees the order --------
    await loginDesktop(page);
    let res = await page.goto(`${ENV.BASE_APP}/store/orders`);
    expect(res?.status() ?? 0, '/store/orders').toBeLessThan(500);
    await assertNoPageErrors(page, '/store/orders');
    // The order list is rendered via AJAX from /store/orders/list. Hit it
    // directly so the assertion is independent of the JS list rendering.
    const sellerListRes = await page.request.fetch(
      `${ENV.BASE_APP}/store/orders/list?perPage=50&page=1`,
    );
    const sellerHtml = await sellerListRes.text();
    expect(sellerHtml, 'desktop seller list should mention the order').toContain(String(createdId));

    // -------- 3. Seller (webapp) sees the order --------
    await loginWebapp(page);
    res = await page.goto(`${ENV.BASE_APP}/brand/mobile/orders`);
    expect(res?.status() ?? 0).toBeLessThan(500);
    await assertNoPageErrors(page, '/brand/mobile/orders');
    const webappListRes = await page.request.fetch(
      `${ENV.BASE_APP}/brand/mobile/orders/list?perPage=50&page=1`,
    );
    const webappHtml = await webappListRes.text();
    expect(webappHtml, 'webapp seller list should mention the order').toContain(String(createdId));

    // -------- 4. Admin sees the order in admin surface --------
    await loginAdmin(page);
    res = await page.goto(
      `${ENV.BASE_APP}/admin/brand/${ENV.SELLER_CUSTOMER_UID}/orders`,
    );
    expect(res?.status() ?? 0, '/admin/brand/{uid}/orders').toBeLessThan(500);
    await assertNoPageErrors(page, '/admin/brand/{uid}/orders');
    const adminListRes = await page.request.fetch(
      `${ENV.BASE_APP}/admin/brand/${ENV.SELLER_CUSTOMER_UID}/orders/list?perPage=50&page=1`,
    );
    const adminHtml = await adminListRes.text();
    expect(adminHtml, 'admin order list should mention the order').toContain(String(createdId));

    // -------- 5. Seller walks the 4-step happy-path workflow (desktop) --------
    await loginDesktop(page);
    await normaliseToOrdered(page, createdId);
    expect((await findOrderById(page, createdId))!.status).toBe('ordered');

    expect(await postDesktopAction(page, createdId, 'seller-confirm')).toBeLessThan(400);
    expect((await findOrderById(page, createdId))!.status).toBe('packaging');

    expect(await postDesktopAction(page, createdId, 'set-packaged')).toBeLessThan(400);
    expect((await findOrderById(page, createdId))!.status).toBe('ready_for_pickup');

    expect(await postDesktopAction(page, createdId, 'set-delivering')).toBeLessThan(400);
    expect((await findOrderById(page, createdId))!.status).toBe('delivering');

    expect(await postDesktopAction(page, createdId, 'complete')).toBeLessThan(400);
    expect((await findOrderById(page, createdId))!.status).toBe('completed');

    // -------- 6. Admin can still see the now-completed order --------
    await loginAdmin(page);
    const adminAfterRes = await page.request.fetch(
      `${ENV.BASE_APP}/admin/brand/${ENV.SELLER_CUSTOMER_UID}/orders/list?perPage=50&page=1`,
    );
    const adminAfterHtml = await adminAfterRes.text();
    expect(adminAfterHtml, 'admin should still see the completed order').toContain(
      String(createdId),
    );
  });
});

// ---------- Test 2: customer → seller cancel (edge case) ----------

test.describe('Phase 4.2 / Edge case / seller cancels a customer order', () => {
  let createdId: number | null = null;

  test.afterEach(async ({ page }) => {
    if (createdId != null) {
      await forceDeleteOrder(page, createdId);
      createdId = null;
    }
  });

  test('customer places order → seller cancels via webapp → status seller_cancelled', async ({
    page,
  }) => {
    createdId = await placeCustomerOrder(page);
    expect((await findOrderById(page, createdId))!).not.toBeNull();

    await loginWebapp(page);
    expect(await postWebappAction(page, createdId, 'seller-cancel')).toBeLessThan(400);
    expect((await findOrderById(page, createdId))!.status).toBe('seller_cancelled');

    // Admin should still see the cancelled order in their full list (we don't
    // filter by status because the admin list doesn't necessarily expose a
    // status query param — listing all and grepping the order id is enough
    // to prove visibility).
    await loginAdmin(page);
    const res = await page.request.fetch(
      `${ENV.BASE_APP}/admin/brand/${ENV.SELLER_CUSTOMER_UID}/orders/list?perPage=50&page=1`,
    );
    expect(await res.text(), 'admin should see the cancelled order').toContain(String(createdId));
  });
});

// ---------- Test 3: admin intervention cancel ----------

test.describe('Phase 4.2 / Edge case / admin cancels an order on behalf of seller', () => {
  let createdId: number | null = null;

  test.afterEach(async ({ page }) => {
    if (createdId != null) {
      await forceDeleteOrder(page, createdId);
      createdId = null;
    }
  });

  test('customer places order → admin cancels via admin route → status seller_cancelled', async ({
    page,
  }) => {
    createdId = await placeCustomerOrder(page);
    expect((await findOrderById(page, createdId))!).not.toBeNull();

    await loginAdmin(page);
    expect(await postAdminAction(page, createdId, 'seller-cancel')).toBeLessThan(400);
    expect((await findOrderById(page, createdId))!.status).toBe('seller_cancelled');
  });
});
