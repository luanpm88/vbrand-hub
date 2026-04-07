import { test, expect } from '@playwright/test';
import { ENV } from '../playwright.config';
import { loginDesktop, assertNoPageErrors } from '../helpers/auth';
import { csrfHeaders } from '../helpers/api';

/**
 * Phase 16 — Blog (Bài viết)
 * Plan: docs/E2E_TEST_PLAN.md §Phase 16
 * Reference: SALES_HANDOVER §1 (Blog)
 *
 * Surfaces (seller-side, desktop only — there is no webapp blog UI):
 *   GET  /website/articles                       (list page)
 *   GET  /website/articles/list                  (list partial)
 *   GET  /website/articles/create                (form)
 *   POST /website/articles/store                 (create — `post_title`, `post_content`)
 *   GET  /website/articles/{id}/edit             (edit form)
 *   POST /website/articles/{id}/update           (update — same fields)
 *   GET  /website/article-category               (categories list page)
 *   POST /website/article-category/store         (create — `name`, `description`)
 *   POST /website/article-category/delete        (delete — id in body)
 *
 * Behind the scenes everything writes to WP via vbrandsync
 * (`/wp-json/vbrandsync/v1/article/...`, `/article-category/...`).
 *
 * Storefront verification: WP `/wp-json/wp/v2/posts?slug=...` returns the
 * post if it's published.
 *
 * ⚠️ **Cleanup gap (not a Phase 16 fix):** vbrandsync has NO `article/delete`
 * endpoint and `Brand\ArticleController` has no `delete` method (the
 * `website/articles/{id}/delete` route is registered but resolves to a
 * non-existent method). Test articles cannot be removed via the standard
 * flow. Phase 16 cleans up by hitting WP REST `/wp/v2/posts/{id}?force=true`
 * via the wp-cli admin user (the local site has WP application passwords
 * disabled, so we use the JSON-API basic-auth-style cookie auth fallback —
 * which doesn't work over plain HTTP) or simply leaves the test article in
 * place with an `e2e-phase16-` prefix. Documented in the plan.
 * Categories CAN be deleted via the standard endpoint and the test does so.
 */

const BLOG_TITLE = `e2e-phase16-${Date.now()}`;
const BLOG_CONTENT = `Phase 16 generated content ${Date.now()}`;
const CATEGORY_NAME = `e2e-phase16-cat-${Date.now()}`;

test.describe('Phase 16 / Blog (desktop seller)', () => {
  test.use({ viewport: { width: 1280, height: 800 } });

  let articleId: number | null = null;
  let categoryId: number | null = null;

  test.afterEach(async ({ page }) => {
    // Categories support delete via the standard endpoint
    if (categoryId) {
      const headers = await csrfHeaders(page);
      await page.request
        .post(`${ENV.BASE_APP}/website/article-category/${categoryId}/delete`, {
          headers,
          form: { id: String(categoryId) },
        })
        .catch(() => {});
      categoryId = null;
    }
    // Articles: no delete endpoint exists in vbrandsync or in the brand-app
    // controller. We try a best-effort WP REST delete for cleanup but
    // accept that it may fail (in which case the e2e-phase16-* article
    // accumulates in WP). Documented in the plan.
    if (articleId) {
      await page.request
        .delete(`${ENV.BASE_SITE}/wp-json/wp/v2/posts/${articleId}?force=true`)
        .catch(() => {});
      articleId = null;
    }
  });

  test('list page renders + AJAX list partial < 500', async ({ page }) => {
    await loginDesktop(page);

    const idx = await page.goto(`${ENV.BASE_APP}/website/articles`);
    expect(idx?.status() ?? 0).toBeLessThan(500);
    await assertNoPageErrors(page, '/website/articles');

    const list = await page.request.get(`${ENV.BASE_APP}/website/articles/list`);
    expect(list.status()).toBeLessThan(500);
  });

  test('create category → create article → edit article → verify on storefront', async ({
    page,
  }) => {
    await loginDesktop(page);

    const headers = await csrfHeaders(page);

    // 1. Create category via standard form POST
    const catRes = await page.request.post(
      `${ENV.BASE_APP}/website/article-category/store`,
      {
        headers,
        form: { name: CATEGORY_NAME, description: 'Phase 16 e2e test category' },
      },
    );
    expect(catRes.status(), 'category store status').toBeLessThan(400);

    // Find the new category id via WP REST (vbrandsync exposes article-category/list)
    const catListRes = await page.request.get(
      `${ENV.BASE_SITE}/wp-json/vbrandsync/v1/article-category/list`,
    );
    expect(catListRes.status()).toBeLessThan(500);
    const catList = await catListRes.json();
    const categories = Array.isArray(catList) ? catList : (catList?.items ?? []);
    const cat = (categories as Array<{ id: number; name: string }>).find(
      (c) => c.name === CATEGORY_NAME,
    );
    expect(cat, `category ${CATEGORY_NAME} should exist in WP after create`).toBeTruthy();
    categoryId = cat!.id;

    // 2. Create article via standard form POST
    const articleRes = await page.request.post(
      `${ENV.BASE_APP}/website/articles/store`,
      {
        headers,
        form: { post_title: BLOG_TITLE, post_content: BLOG_CONTENT },
      },
    );
    expect(articleRes.status(), 'article store status').toBeLessThan(400);

    // 3. Verify the article appears in the brand-app list partial
    const listAfter = await page.request.get(`${ENV.BASE_APP}/website/articles/list`);
    expect(listAfter.status()).toBeLessThan(500);
    const listHtml = await listAfter.text();
    expect(listHtml, 'brand-app list should contain the new article title').toContain(
      BLOG_TITLE,
    );

    // 4. Verify the article exists on the storefront (WP REST /wp/v2/posts).
    //    The vbrandsync `article/add` handler sets `post_status=publish`
    //    so the post is queryable via the public WP REST.
    const wpPostRes = await page.request.get(
      `${ENV.BASE_SITE}/wp-json/wp/v2/posts?search=${encodeURIComponent(BLOG_TITLE)}`,
    );
    expect(wpPostRes.status()).toBeLessThan(500);
    const wpPosts = (await wpPostRes.json()) as Array<{
      id: number;
      title: { rendered: string };
      status: string;
    }>;
    const post = wpPosts.find((p) =>
      (p.title?.rendered ?? '').includes(BLOG_TITLE),
    );
    expect(post, `WP REST should return the new post for ${BLOG_TITLE}`).toBeTruthy();
    expect(post!.status).toBe('publish');
    articleId = post!.id;

    // 5. Edit the article — change title via update endpoint
    const newTitle = `${BLOG_TITLE}-edited`;
    const updateRes = await page.request.post(
      `${ENV.BASE_APP}/website/articles/${articleId}/update`,
      {
        headers,
        form: {
          id: String(articleId),
          post_title: newTitle,
          post_content: BLOG_CONTENT + ' (edited)',
        },
      },
    );
    expect(updateRes.status(), 'article update status').toBeLessThan(400);

    // Verify the new title via WP REST
    const wpPostAfter = await page.request.get(
      `${ENV.BASE_SITE}/wp-json/wp/v2/posts/${articleId}`,
    );
    expect(wpPostAfter.status()).toBeLessThan(500);
    const updated = (await wpPostAfter.json()) as { title: { rendered: string } };
    expect(updated.title.rendered).toContain(newTitle);
  });
});
