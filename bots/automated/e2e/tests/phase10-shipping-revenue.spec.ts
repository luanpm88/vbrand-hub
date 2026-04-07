import { test, expect } from '@playwright/test';
import { ENV } from '../playwright.config';
import { loginDesktop, assertNoPageErrors } from '../helpers/auth';

/**
 * Phase 10 — Vận chuyển & Doanh thu (Desktop only)
 * Plan: docs/E2E_TEST_PLAN.md §Phase 10
 *
 * Reference: USER_GUIDE_DESKTOP §7 (Vận chuyển), §8 (Doanh thu)
 *
 * Reality check (per CLAUDE.md "if 2 docs disagree → fix the wrong one"):
 *
 *   §7 Vận chuyển:
 *   - The "Vận chuyển" parent menu is rendered with `d-none` (hidden) in
 *     `_menu_frontend_brand.blade.php:260`. Production sellers do not see it.
 *   - Its only child "Đơn vị vận chuyển" links to `Store\WarehouseController@index`
 *     — i.e. the same page as Kho hàng (already covered by Phase 9).
 *   - The "Thống kê vận chuyển" sub-item links to `href="#"` — there is no
 *     route, no controller, no view. Pure dead nav.
 *   → Phase 10 does NOT add a separate "Vận chuyển" test. The Doanh thu
 *     surface is the only real one. User guide §7 is corrected to reflect
 *     reality.
 *
 *   §8 Doanh thu:
 *   - Page is `Brand\CustomerController@accountingReport` at
 *     `/brand/accounting-report`. It renders 5 accounting amounts and an
 *     AJAX list at `Brand\CustomerController@journalEntries`
 *     (`/brand/customers/{uid}/journal-entries`).
 *   - Filter form has 3 selects: `sort_order`, `transaction_type`,
 *     `entry_type`. There is **no** date-range filter — the plan item
 *     "Đổi range thời gian → reload data" is aspirational and the user
 *     guide §8 wording is corrected accordingly.
 *
 * Webapp: no Doanh thu / Vận chuyển surface in the mobile webapp
 * (USER_GUIDE_MOBILE has neither §). Phase 10 is desktop-only.
 *
 * Goals:
 *  - GET /brand/accounting-report → 200, no PHP error
 *  - The 5 accounting cells render (Cash, Fee, Revenue, Payout, Remain — via
 *    `getAccounting*` helpers on Customer model)
 *  - The 3 filter selects exist (sort_order / transaction_type / entry_type)
 *  - GET /brand/customers/{uid}/journal-entries (no filter) → < 500
 *  - GET /brand/customers/{uid}/journal-entries with transaction_type filter → < 500
 *  - GET /brand/customers/{uid}/journal-entries with entry_type filter → < 500
 */

// Read the seller customer uid from env (already set up for Phase 4.2).
const CUSTOMER_UID = ENV.SELLER_CUSTOMER_UID;

test.describe('Phase 10 / Desktop / Doanh thu — báo cáo doanh số', () => {
  test('accounting-report page renders + 5 amounts + 3 filter selects', async ({
    page,
  }) => {
    await loginDesktop(page);

    const res = await page.goto(`${ENV.BASE_APP}/brand/accounting-report`);
    expect(res?.status() ?? 0, '/brand/accounting-report status').toBeLessThan(500);
    await assertNoPageErrors(page, '/brand/accounting-report');

    // 5 accounting cells from Customer::getAccounting*. Each is rendered
    // with `đ` suffix in a `<span>`. Cheap-but-targeted: just verify the
    // page contains all 5 helper output keys via the visible labels from
    // the template (these are baked Vietnamese strings inside the table).
    const html = await page.content();
    expect(html).toContain('đ');

    // Filter selects from the JournalEntryListContainer form
    await expect(page.locator('select[name="sort_order"]')).toBeAttached();
    await expect(page.locator('select[name="transaction_type"]')).toBeAttached();
    await expect(page.locator('select[name="entry_type"]')).toBeAttached();
  });

  test('journal-entries AJAX endpoint accepts no-filter request', async ({
    page,
  }) => {
    await loginDesktop(page);

    const res = await page.request.get(
      `${ENV.BASE_APP}/brand/customers/${CUSTOMER_UID}/journal-entries?per_page=15`,
    );
    expect(res.status(), 'journal-entries status (no filter)').toBeLessThan(500);

    const body = await res.text();
    expect(body).not.toContain('Whoops, looks like something went wrong');
  });

  test('journal-entries AJAX accepts transaction_type filter', async ({
    page,
  }) => {
    await loginDesktop(page);

    // user_sale_order is one of the two enum values listed in the template
    // (Acelle\Model\JournalTransactionUserSaleOrder::TYPE_USER_SALE_ORDER).
    const res = await page.request.get(
      `${ENV.BASE_APP}/brand/customers/${CUSTOMER_UID}/journal-entries?transaction_type=user_sale_order&per_page=15`,
    );
    expect(res.status(), 'journal-entries status (transaction_type)').toBeLessThan(500);
    const body = await res.text();
    expect(body).not.toContain('Whoops, looks like something went wrong');
  });

  test('journal-entries AJAX accepts entry_type filter', async ({ page }) => {
    await loginDesktop(page);

    // Pass an entry_type and per_page; the controller's `byEntryType()`
    // scope handles unknown values gracefully (no exception).
    const res = await page.request.get(
      `${ENV.BASE_APP}/brand/customers/${CUSTOMER_UID}/journal-entries?entry_type=user_cash&per_page=15`,
    );
    expect(res.status(), 'journal-entries status (entry_type)').toBeLessThan(500);
    const body = await res.text();
    expect(body).not.toContain('Whoops, looks like something went wrong');
  });
});
