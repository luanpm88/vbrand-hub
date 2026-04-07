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

// ---------- Contacts (Khách hàng) ----------

/**
 * Find a brand-app contact by exact email by hitting the contacts list
 * endpoint with `keyword=`. Returns the contact id, or null.
 *
 * Note: Acelle\Model\Contact has no `customer_id` column at all, so the
 * contacts table is GLOBAL across all sellers. Until that's refactored,
 * tests rely on unique emails to avoid collisions between concurrent runs.
 */
export async function findContactIdByEmail(
  page: Page,
  email: string,
): Promise<number | null> {
  const res = await page.request.fetch(
    `${ENV.BASE_APP}/brand/contacts/list?keyword=${encodeURIComponent(email)}&perPage=20`,
  );
  if (!res.ok()) return null;
  const html = await res.text();
  // The list blade renders edit URLs containing the contact id.
  const m = html.match(/Brand\\?ContactController@edit[^>]*?\/brand\/contacts\/(\d+)\/edit/);
  if (m) return Number(m[1]);
  // Fallback regex — the action() helper builds /brand/contacts/{id}/edit
  const m2 = html.match(/\/brand\/contacts\/(\d+)\/edit/);
  return m2 ? Number(m2[1]) : null;
}

/** Force-delete a contact via the brand-app DELETE endpoint. */
export async function forceDeleteContact(page: Page, id: number | string) {
  const headers = await csrfHeaders(page);
  await page.request
    .fetch(`${ENV.BASE_APP}/brand/contacts/delete`, {
      method: 'DELETE',
      headers,
      form: { id: String(id) },
    })
    .catch(() => {});
}

// ---------- Themes (Website → Giao diện) ----------

export type WPTheme = {
  id: string;
  name: string;
  active: boolean;
};

/**
 * List WP themes via the vbrandsync `theme/list` endpoint. The endpoint
 * returns an OBJECT keyed by theme name (not an array) so we normalise
 * here.
 */
export async function listThemes(page: Page): Promise<WPTheme[]> {
  const res = await page.request.fetch(
    `${ENV.BASE_SITE}/wp-json/vbrandsync/v1/theme/list`,
  );
  if (!res.ok()) throw new Error(`listThemes failed ${res.status()}`);
  const body = (await res.json()) as Record<string, { id: string; name: string; active: boolean }>;
  return Object.values(body).map((t) => ({
    id: String(t.id),
    name: String(t.name),
    active: !!t.active,
  }));
}

/**
 * Find the currently active theme. Useful for snapshot/restore around a
 * theme-switching test (so the local/prod site doesn't end up on a different
 * theme than it started).
 */
export async function activeTheme(page: Page): Promise<WPTheme | null> {
  const themes = await listThemes(page);
  return themes.find((t) => t.active) ?? null;
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
 * Find a SIMPLE product id (no variations) so order/checkout tests don't trip
 * over WC's "missing variation attributes" error. Searches via the WC Store
 * API `type=simple` filter, then falls back to creating a fresh simple
 * product via vbrandsync `/product/add` if none exist.
 *
 * On local, the seeded products are mostly simple. On prod (logitech etc.)
 * everything tends to be variable, so the create-fallback path runs.
 *
 * The created product has an `e2e-` prefix so it's easy to spot + clean up.
 * Test code is responsible for deletion via `forceDeleteProduct` in afterEach
 * if it cares about cleanliness (the helper itself does NOT track ownership).
 */
export async function anyProductId(page: Page): Promise<number> {
  // 1. Prefer an existing simple product.
  const res = await page.request.fetch(
    `${ENV.BASE_SITE}/wp-json/wc/store/v1/products?type=simple&per_page=5`,
  );
  if (res.ok()) {
    const items = (await res.json()) as Array<{ id: number; type: string }>;
    const simple = items.find((p) => p.type === 'simple');
    if (simple) return Number(simple.id);
  }

  // 2. Create one via vbrandsync /product/add (no auth required — public REST).
  const created = await page.request.fetch(
    `${ENV.BASE_SITE}/wp-json/vbrandsync/v1/product/add`,
    {
      method: 'POST',
      form: {
        title: `e2e-helper-simple-${Date.now()}`,
        price: '50000',
        description: 'Simple product seeded by e2e helper',
      },
    },
  );
  if (!created.ok()) {
    throw new Error(
      `anyProductId: failed to create fallback simple product: ${created.status()} ${await created.text()}`,
    );
  }
  const body = (await created.json()) as { id: number };
  if (!body.id) {
    throw new Error(`anyProductId: created product missing id: ${JSON.stringify(body)}`);
  }
  return Number(body.id);
}

// ---------- Storefront customer checkout (WC Store API) ----------

/**
 * Fetch a fresh WC Store API nonce. The Nonce is returned in a response
 * header on every Store API request, so this is a single GET to /cart.
 */
export async function wcStoreNonce(page: Page): Promise<string> {
  const res = await page.request.fetch(
    `${ENV.BASE_SITE}/wp-json/wc/store/v1/cart`,
  );
  const nonce = res.headers()['nonce'];
  if (!nonce) throw new Error('wcStoreNonce: response missing Nonce header');
  return nonce;
}

/**
 * Add a product to the customer's cart via the WC Store API. The session
 * cookie is stored in `page.context()` so subsequent requests share it.
 * Returns the parsed cart payload after the add.
 */
export async function customerAddToCart(
  page: Page,
  productId: number | string,
  quantity = 1,
): Promise<{ items_count: number; items: Array<{ id: number; quantity: number }> }> {
  const nonce = await wcStoreNonce(page);
  const res = await page.request.fetch(
    `${ENV.BASE_SITE}/wp-json/wc/store/v1/cart/add-item`,
    {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', Nonce: nonce },
      data: { id: Number(productId), quantity },
    },
  );
  if (!res.ok()) {
    throw new Error(`customerAddToCart failed ${res.status()}: ${await res.text()}`);
  }
  return res.json();
}

export type CustomerCheckoutInput = {
  firstName?: string;
  lastName?: string;
  email?: string;
  phone?: string;
  address?: string;
  city?: string;
  postcode?: string;
  country?: string;
};

/**
 * Place an order via the WC Store API checkout endpoint. Assumes the cart
 * already has at least one item. Hard-codes COD as the only payment method
 * (which is also the only enabled gateway per CLAUDE.md "Site standardization").
 *
 * Returns the new order id.
 */
export async function customerCheckout(
  page: Page,
  input: CustomerCheckoutInput = {},
): Promise<number> {
  const nonce = await wcStoreNonce(page);
  const billing = {
    first_name: input.firstName ?? 'E2E',
    last_name: input.lastName ?? 'Customer',
    address_1: input.address ?? '123 Test St',
    address_2: '',
    city: input.city ?? 'Ho Chi Minh',
    // VN address validation requires `state` (Tỉnh/Thành phố) — WC uses
    // 2-digit province codes from the VN dataset (79 = TP. HCM).
    state: '79',
    postcode: input.postcode ?? '70000',
    country: input.country ?? 'VN',
    email: input.email ?? 'e2e-customer@test.local',
    phone: input.phone ?? '0900000001',
  };
  const res = await page.request.fetch(
    `${ENV.BASE_SITE}/wp-json/wc/store/v1/checkout`,
    {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', Nonce: nonce },
      data: {
        billing_address: billing,
        shipping_address: billing,
        payment_method: 'cod',
        payment_data: [],
        customer_note: 'placed by e2e',
      },
    },
  );
  if (!res.ok()) {
    throw new Error(`customerCheckout failed ${res.status()}: ${await res.text()}`);
  }
  const body = (await res.json()) as { order_id: number };
  if (!body.order_id) {
    throw new Error(`customerCheckout: response missing order_id: ${JSON.stringify(body)}`);
  }
  return Number(body.order_id);
}
