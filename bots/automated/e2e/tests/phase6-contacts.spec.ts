import { test, expect } from '@playwright/test';
import { ENV } from '../playwright.config';
import { loginDesktop, assertNoPageErrors } from '../helpers/auth';
import {
  uniqueName,
  csrfHeaders,
  findContactIdByEmail,
  forceDeleteContact,
} from '../helpers/api';

/**
 * Phase 6 — Khách hàng (Desktop)
 * Plan: docs/E2E_TEST_PLAN.md §Phase 6
 *
 * Reference: USER_GUIDE_DESKTOP §4 (Khách hàng — Quản lý liên hệ)
 *
 * The "Khách hàng" tab in the desktop sidebar is wired to
 * `Brand\ContactController` (table: contacts). This is the seller's
 * private CRM-style contact list — distinct from WC customers (which are
 * created automatically by storefront checkout via WC's billing meta).
 *
 * Goals:
 *  - List page renders with no PHP error (covered by Phase 1 too — re-asserted)
 *  - Create a contact via the create form (POST to /brand/contacts/store)
 *  - Verify it appears in /brand/contacts/list
 *  - Edit the contact (change phone) → round-trip
 *  - Delete the contact → verify gone
 *
 * Reliability strategy:
 *  - Each test uses a unique email so concurrent runs don't collide
 *  - Each test cleans up via `forceDeleteContact` in afterEach
 *  - Form submissions go through the real UI (page.goto + click Save) for
 *    create + edit because the controller does session flash messages and
 *    redirects — driving the form proves the whole stack works
 */

// `country_id = 228` = Vietnam in the local + prod DB. The Contact validator
// requires country_id, so this is hard-coded for VN. Override per-env via the
// helper if running against a different country.
const VN_COUNTRY_ID = '228';

test.describe('Phase 6 / Desktop / Khách hàng', () => {
  let createdId: number | null = null;
  const email = `${uniqueName('e2e-contact')}@test.local`;

  test.afterEach(async ({ page }) => {
    if (createdId != null) {
      await forceDeleteContact(page, createdId);
      createdId = null;
    }
  });

  test('list page renders without PHP error', async ({ page }) => {
    await loginDesktop(page);
    const res = await page.goto(`${ENV.BASE_APP}/brand/contacts`);
    expect(res?.status() ?? 0).toBeLessThan(500);
    await assertNoPageErrors(page, '/brand/contacts');
  });

  test('create → list → edit → delete', async ({ page }) => {
    await loginDesktop(page);

    // --- Create via the form ---
    await page.goto(`${ENV.BASE_APP}/brand/contacts/create`);
    await assertNoPageErrors(page, '/brand/contacts/create');

    await page.locator('input[name="first_name"]').fill('E2E');
    await page.locator('input[name="last_name"]').fill('Contact');
    await page.locator('input[name="email"]').fill(email);
    await page.locator('input[name="phone"]').fill('0900000010');
    await page.locator('input[name="address_1"]').fill('123 Test St');

    // The country/city/state are <select> elements populated by AJAX after
    // a country is chosen. Driving the cascading dropdowns is brittle, so
    // remove the existing selects and inject hidden inputs with the same
    // names — Laravel reads `$request->all()` from the form data and doesn't
    // care about the input element type.
    await page.evaluate((countryId) => {
      const form = document.querySelector('form#ContactCreate') as HTMLFormElement;
      const replaceField = (name: string, value: string) => {
        form.querySelectorAll(`[name="${name}"]`).forEach((el) => el.remove());
        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = name;
        hidden.value = value;
        form.appendChild(hidden);
      };
      replaceField('country_id', countryId);
      replaceField('city', 'Ho Chi Minh');
      replaceField('state', '79');
    }, VN_COUNTRY_ID);

    await Promise.all([
      page.waitForURL(/\/brand\/contacts(\?|$)/, { timeout: 30_000 }),
      page.locator('form#ContactCreate button[type="submit"]').first().click(),
    ]);
    await assertNoPageErrors(page, 'after contact create');

    // --- Find by email in the list endpoint ---
    const foundId = await findContactIdByEmail(page, email);
    expect(foundId, `contact "${email}" should appear in the list`).not.toBeNull();
    createdId = foundId!;

    // --- Edit: open the edit page, change phone, save ---
    await page.goto(`${ENV.BASE_APP}/brand/contacts/${createdId}/edit`);
    await assertNoPageErrors(page, `/brand/contacts/${createdId}/edit`);
    await expect(page.locator('input[name="email"]')).toHaveValue(email);

    const newPhone = '0900000099';
    await page.locator('input[name="phone"]').fill(newPhone);
    // Same strategy as create: replace the country/city/state selects with
    // hidden inputs so they're guaranteed to round-trip on submit.
    await page.evaluate((countryId) => {
      const form = document.querySelector('form#sendingserverCreate') as HTMLFormElement;
      const replaceField = (name: string, value: string) => {
        form.querySelectorAll(`[name="${name}"]`).forEach((el) => el.remove());
        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = name;
        hidden.value = value;
        form.appendChild(hidden);
      };
      replaceField('country_id', countryId);
      replaceField('city', 'Ho Chi Minh');
      replaceField('state', '79');
    }, VN_COUNTRY_ID);

    await Promise.all([
      page.waitForURL(/\/brand\/contacts(\?|$)/, { timeout: 30_000 }),
      page.locator('form button[type="submit"]').first().click(),
    ]);
    await assertNoPageErrors(page, 'after contact update');

    // Round-trip: open the edit page again and verify the phone persisted
    await page.goto(`${ENV.BASE_APP}/brand/contacts/${createdId}/edit`);
    await expect(page.locator('input[name="phone"]')).toHaveValue(newPhone);

    // --- Delete via the brand-app DELETE endpoint ---
    const headers = await csrfHeaders(page);
    const delRes = await page.request.fetch(`${ENV.BASE_APP}/brand/contacts/delete`, {
      method: 'DELETE',
      headers,
      form: { id: String(createdId) },
    });
    expect(delRes.status(), 'DELETE response').toBeLessThan(400);

    // Verify gone
    const stillFound = await findContactIdByEmail(page, email);
    expect(stillFound, 'contact should be gone after delete').toBeNull();
    createdId = null;
  });
});
