import { test, expect } from '@playwright/test';
import { ENV } from '../playwright.config';
import {
  loginWebapp,
  loginAdmin,
  forceLogout,
  assertNoPageErrors,
} from '../helpers/auth';
import { csrfHeaders } from '../helpers/api';

/**
 * Phase 14 — Import Request flow
 * Plan: docs/E2E_TEST_PLAN.md §Phase 14
 * Reference: docs/rfq/IMPORT_REQUEST_DESIGN.md, USER_GUIDE_MOBILE §6
 *
 * Surfaces:
 *   Seller webapp:
 *     GET  /brand/mobile/import-requests           (page)
 *     GET  /brand/mobile/import-requests/list      (partial)
 *     POST /brand/mobile/import-requests/store     (JSON)
 *     POST /brand/mobile/import-requests/{uid}/update (JSON; only allowed while status=new)
 *     POST /brand/mobile/import-requests/{uid}/delete (JSON; only allowed while status=new)
 *   Admin:
 *     GET  /admin/brand/import-requests            (page)
 *     GET  /admin/brand/import-requests/list       (list partial)
 *     GET  /admin/brand/import-requests/{uid}/edit (form)
 *     POST /admin/brand/import-requests/{uid}/update (form-redirect)
 *     POST /admin/brand/import-requests/{uid}/delete (JSON)
 *
 * Status flow: new → processing → completed (admin drives this).
 *
 * Local fixture: `admin@acm.com` is both a seller (with a customer record)
 * AND an admin (Phase 4.2 already established this on local). So one
 * browser context can drive the seller side and a second context drives
 * the admin side without needing a separate user.
 *
 * Cleanup strategy: the seller-side delete endpoint only allows deletion
 * while the request is still in `status=new`. As soon as the admin moves
 * it to `processing`, the seller can no longer remove it. So Phase 14
 * cleans up via the **admin** delete endpoint at the end of each test
 * (admin delete has no status guard).
 */

const SHOP_URL = `https://shopee.vn/e2e-phase14-${Date.now()}`;
const SELLER_BASE = `${ENV.BASE_APP}/brand/mobile/import-requests`;
const ADMIN_BASE = `${ENV.BASE_APP}/admin/brand/import-requests`;

test.describe('Phase 14 / Webapp seller / Import request CRUD', () => {
  test.use({ viewport: { width: 430, height: 932 } });

  let createdUid: string | null = null;

  test.afterEach(async ({ browser }) => {
    if (!createdUid) return;
    // Use a fresh context to admin-delete (avoid interfering with the
    // test's own cookies / mid-flight state).
    const ctx = await browser.newContext();
    const page = await ctx.newPage();
    try {
      await loginAdmin(page);
      const headers = await csrfHeaders(page);
      await page.request
        .post(`${ADMIN_BASE}/${createdUid}/delete`, { headers })
        .catch(() => {});
    } finally {
      await ctx.close();
    }
    createdUid = null;
  });

  test('seller creates request → list contains it with status=new', async ({ page }) => {
    await loginWebapp(page);

    // Index page renders
    const idxRes = await page.goto(SELLER_BASE);
    expect(idxRes?.status() ?? 0).toBeLessThan(500);
    await assertNoPageErrors(page, '/brand/mobile/import-requests');

    // POST store via JSON
    const headers = await csrfHeaders(page);
    const storeRes = await page.request.post(`${SELLER_BASE}/store`, {
      headers,
      form: { platform: 'shopee', shop_url: SHOP_URL },
    });
    expect(storeRes.status(), 'webapp store status').toBeLessThan(400);
    const json = await storeRes.json();
    expect(json.status).toBe('success');

    // The list partial should now contain the shop_url + status badge "Mới"
    const listRes = await page.request.get(`${SELLER_BASE}/list`);
    expect(listRes.status()).toBeLessThan(500);
    const listHtml = await listRes.text();
    expect(listHtml).toContain(SHOP_URL);
    expect(listHtml).toContain('Mới');

    // Capture the uid from the list HTML for the cleanup hook. The list
    // partial renders /edit links containing the uid.
    // The webapp _list partial embeds the uid via Alpine
    // `editRequest('uid')` / `deleteRequest('uid')` callbacks (no
    // /edit links). Match the first one.
    const m = listHtml.match(/(?:editRequest|deleteRequest)\(['"]([^'"]+)['"]\)/);
    expect(m, 'list HTML should contain an editRequest/deleteRequest callback').toBeTruthy();
    createdUid = m![1];
  });
});

test.describe('Phase 14 / Admin / Process request → seller sees status update', () => {
  test.use({ viewport: { width: 1280, height: 800 } });

  let createdUid: string | null = null;

  test.afterEach(async ({ browser }) => {
    if (!createdUid) return;
    const ctx = await browser.newContext();
    const page = await ctx.newPage();
    try {
      await loginAdmin(page);
      const headers = await csrfHeaders(page);
      await page.request
        .post(`${ADMIN_BASE}/${createdUid}/delete`, { headers })
        .catch(() => {});
    } finally {
      await ctx.close();
    }
    createdUid = null;
  });

  test('full lifecycle: seller create → admin processing → completed → seller sees update', async ({
    browser,
  }) => {
    // ---- Seller side: create the request ----
    const sellerCtx = await browser.newContext();
    const sellerPage = await sellerCtx.newPage();
    await loginWebapp(sellerPage);

    const sellerCsrf = await csrfHeaders(sellerPage);
    const storeRes = await sellerPage.request.post(`${SELLER_BASE}/store`, {
      headers: sellerCsrf,
      form: { platform: 'lazada', shop_url: SHOP_URL },
    });
    expect(storeRes.status()).toBeLessThan(400);

    const sellerListBefore = await sellerPage.request.get(`${SELLER_BASE}/list`);
    const beforeHtml = await sellerListBefore.text();
    const m = beforeHtml.match(/(?:editRequest|deleteRequest)\(['"]([^'"]+)['"]\)/);
    expect(m, 'list HTML should contain an editRequest/deleteRequest callback').toBeTruthy();
    createdUid = m![1];

    // ---- Admin side: list + edit + update to processing ----
    const adminCtx = await browser.newContext();
    const adminPage = await adminCtx.newPage();
    // admin@acm.com is both seller + admin on local. We must hit the
    // admin login flow (same /login route, but with the admin email).
    // forceLogout first so we're not reusing seller cookies.
    await forceLogout(adminPage);
    await loginAdmin(adminPage);

    // Admin index renders
    const adminIdx = await adminPage.goto(ADMIN_BASE);
    expect(adminIdx?.status() ?? 0).toBeLessThan(500);
    await assertNoPageErrors(adminPage, '/admin/brand/import-requests');

    // Admin list partial contains the new request
    const adminListRes = await adminPage.request.get(`${ADMIN_BASE}/list`);
    expect(adminListRes.status()).toBeLessThan(500);
    const adminListHtml = await adminListRes.text();
    expect(adminListHtml).toContain(SHOP_URL);

    // Admin updates to processing
    const adminCsrf = await csrfHeaders(adminPage);
    const updateRes1 = await adminPage.request.post(
      `${ADMIN_BASE}/${createdUid}/update`,
      {
        headers: adminCsrf,
        form: { status: 'processing', imported_count: '0', notes: '' },
      },
    );
    expect(updateRes1.status(), 'admin update→processing status').toBeLessThan(400);

    // ---- Seller sees status update to "Đang xử lý" ----
    const sellerListAfter1 = await sellerPage.request.get(`${SELLER_BASE}/list`);
    const after1Html = await sellerListAfter1.text();
    expect(after1Html).toContain(SHOP_URL);
    expect(after1Html).toContain('Đang xử lý');

    // ---- Admin updates to completed ----
    const updateRes2 = await adminPage.request.post(
      `${ADMIN_BASE}/${createdUid}/update`,
      {
        headers: adminCsrf,
        form: { status: 'completed', imported_count: '5', notes: 'imported via e2e' },
      },
    );
    expect(updateRes2.status(), 'admin update→completed status').toBeLessThan(400);

    // ---- Seller sees status update to "Hoàn thành" ----
    const sellerListAfter2 = await sellerPage.request.get(`${SELLER_BASE}/list`);
    const after2Html = await sellerListAfter2.text();
    expect(after2Html).toContain(SHOP_URL);
    expect(after2Html).toContain('Hoàn thành');

    await sellerCtx.close();
    await adminCtx.close();
  });
});
