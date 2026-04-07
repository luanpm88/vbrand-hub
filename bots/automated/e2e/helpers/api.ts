import { Page } from '@playwright/test';
import { ENV } from '../playwright.config';

/**
 * Shared helpers for talking to the brand-app HTTP routes and the WP REST API
 * (vbrandsync) from inside Playwright tests. All helpers use `page.request` so
 * that the browser-context cookies (auth + XSRF) are inherited automatically.
 *
 * Why these live here:
 *  - csrfHeaders, uniqueName, and the WP finders were duplicated across phase 2
 *    and phase 3. Centralising them avoids drift and lets each phase spec stay
 *    focused on the user-guide flow it covers.
 *  - The "find by X in WP" helpers are the canonical "is this thing really
 *    there" check used by every CRUD spec — WP REST is the single source of
 *    truth, same data the brand-app and webapp both read from.
 */

// ---------- Generic ----------

/** Unique fixture name with prefix + timestamp + random suffix. */
export function uniqueName(prefix: string): string {
  return `${prefix}-${Date.now()}-${Math.floor(Math.random() * 1e6)}`;
}

/**
 * Read the XSRF token from the current page's cookies and return a header map
 * suitable for Laravel POST/DELETE requests via APIRequestContext.
 */
export async function csrfHeaders(page: Page): Promise<Record<string, string>> {
  const cookies = await page.context().cookies();
  const xsrf = cookies.find((c) => c.name === 'XSRF-TOKEN');
  return {
    'X-XSRF-TOKEN': xsrf ? decodeURIComponent(xsrf.value) : '',
    'X-Requested-With': 'XMLHttpRequest',
    Accept: 'application/json',
  };
}

// ---------- WP REST finders (canonical source of truth) ----------

/**
 * Find a product by exact title via the vbrandsync product/list endpoint.
 * Returns the product id, or null if no match.
 */
export async function findProductIdByTitle(
  page: Page,
  title: string,
): Promise<number | null> {
  const res = await page.request.fetch(
    `${ENV.BASE_SITE}/wp-json/vbrandsync/v1/product/list?keyword=${encodeURIComponent(title)}`,
  );
  if (!res.ok()) return null;
  const items = (await res.json()) as Array<{ id: number; title: string }>;
  const hit = items.find((p) => p.title === title);
  return hit ? Number(hit.id) : null;
}

/** Find a category by exact name via the vbrandsync category/list endpoint. */
export async function findCategoryIdByName(
  page: Page,
  name: string,
): Promise<number | null> {
  const res = await page.request.fetch(
    `${ENV.BASE_SITE}/wp-json/vbrandsync/v1/category/list?per_page=100`,
  );
  if (!res.ok()) return null;
  const body = (await res.json()) as { items?: Array<{ id: number; name: string }> };
  const hit = (body.items ?? []).find((c) => c.name === name);
  return hit ? Number(hit.id) : null;
}

/**
 * Find an attribute by name (case-insensitive — WP normalizes attribute names
 * to lowercase). Returns id and value list.
 */
export async function findAttributeByName(
  page: Page,
  name: string,
): Promise<{ id: number; values: string[] } | null> {
  const res = await page.request.fetch(
    `${ENV.BASE_SITE}/wp-json/vbrandsync/v1/attribute/list?per_page=100`,
  );
  if (!res.ok()) return null;
  const body = (await res.json()) as {
    items?: Array<{ id: number | string; name: string; values?: string[] }>;
  };
  const lower = name.toLowerCase();
  const hit = (body.items ?? []).find((a) => a.name.toLowerCase() === lower);
  return hit ? { id: Number(hit.id), values: hit.values ?? [] } : null;
}

// ---------- Force-delete helpers (afterEach cleanup) ----------

/**
 * Force-delete a product by id via the brand-app DELETE endpoint, bypassing
 * the "are you sure?" UI confirmation. Used by afterEach to guarantee cleanup
 * even if a test mid-flight fails.
 */
export async function forceDeleteProduct(page: Page, id: number | string) {
  const headers = await csrfHeaders(page);
  await page.request
    .fetch(`${ENV.BASE_APP}/store/products/delete`, {
      method: 'DELETE',
      headers,
      form: { id: String(id) },
    })
    .catch(() => {});
}

/** Force-delete a category via the brand-app deleteSelected endpoint. */
export async function forceDeleteCategory(page: Page, id: number | string) {
  const headers = await csrfHeaders(page);
  await page.request
    .fetch(`${ENV.BASE_APP}/store/categories/delete-selected`, {
      method: 'DELETE',
      headers,
      form: { 'ids[0]': String(id) },
    })
    .catch(() => {});
}

/** Force-delete an attribute via the brand-app deleteSelected endpoint. */
export async function forceDeleteAttribute(page: Page, id: number | string) {
  const headers = await csrfHeaders(page);
  await page.request
    .fetch(`${ENV.BASE_APP}/store/attributes/delete-selected`, {
      method: 'DELETE',
      headers,
      form: { 'ids[0]': String(id) },
    })
    .catch(() => {});
}

// ---------- Orders ----------

export type SeedOrderInput = {
  productId: number | string;
  quantity?: number;
  firstName?: string;
  lastName?: string;
  phone?: string;
  email?: string;
  address?: string;
};

export type WPOrder = {
  id: number;
  status: string;
  first_name?: string;
  last_name?: string;
  phone?: string;
  email?: string;
  total?: string;
};

/**
 * Seed a Woo order via the vbrandsync `/order/add` endpoint. The seller-app
 * does not have a checkout flow we can drive from inside the dashboard, so
 * for status-transition tests we create the fixture directly in WP.
 *
 * Returns the new order id (status will be `ordered`).
 */
export async function seedOrder(
  page: Page,
  input: SeedOrderInput,
): Promise<number> {
  const res = await page.request.fetch(
    `${ENV.BASE_SITE}/wp-json/vbrandsync/v1/order/add`,
    {
      method: 'POST',
      form: {
        product_id: String(input.productId),
        quantity: String(input.quantity ?? 1),
        first_name: input.firstName ?? 'E2E',
        last_name: input.lastName ?? 'Test',
        phone: input.phone ?? '0900000000',
        email: input.email ?? 'e2e@test.local',
        address_1: input.address ?? '123 Test St',
        order_type: 'normal',
      },
    },
  );
  if (!res.ok()) {
    throw new Error(`seedOrder failed: ${res.status()} ${await res.text()}`);
  }
  const body = (await res.json()) as { id: number };
  return Number(body.id);
}

/**
 * Find an order by id via WP `/order/find/{id}`. Returns null if missing
 * (e.g. after a delete).
 */
export async function findOrderById(
  page: Page,
  id: number | string,
): Promise<WPOrder | null> {
  const res = await page.request.fetch(
    `${ENV.BASE_SITE}/wp-json/vbrandsync/v1/order/find/${id}`,
  );
  if (!res.ok()) return null;
  try {
    const body = (await res.json()) as WPOrder;
    if (!body || !body.id) return null;
    return body;
  } catch {
    return null;
  }
}

/**
 * Force-delete an order via the vbrandsync REST endpoint. Used by afterEach
 * to guarantee cleanup so test orders don't accumulate in the local WP DB.
 */
export async function forceDeleteOrder(page: Page, id: number | string) {
  await page.request
    .fetch(`${ENV.BASE_SITE}/wp-json/vbrandsync/v1/order/delete/${id}`, {
      method: 'POST',
    })
    .catch(() => {});
}

/**
 * Get the id of any existing product so order tests can seed without first
 * creating one. Returns the first product id from the WP product list.
 */
export async function anyProductId(page: Page): Promise<number> {
  const res = await page.request.fetch(
    `${ENV.BASE_SITE}/wp-json/vbrandsync/v1/product/list?per_page=1`,
  );
  if (!res.ok()) throw new Error(`anyProductId: list failed ${res.status()}`);
  const items = (await res.json()) as Array<{ id: number }>;
  if (!items.length) {
    throw new Error('anyProductId: WP has no products — seed one first');
  }
  return Number(items[0].id);
}
