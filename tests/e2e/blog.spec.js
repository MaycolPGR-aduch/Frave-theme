import { expect, test } from '@playwright/test';
import { collectPageErrors, expectNoPhpErrors } from './helpers.js';

/** Address of the blog, from the main menu (it appears once there are published posts). */
async function blogUrl(page) {
  await page.goto('/');
  const link = page.locator('#primary-navigation .menu-list > li > a', { hasText: 'Blog' });
  await expect(link).toHaveCount(1);
  return link.getAttribute('href');
}

/** Structured data of the page, as one list of graph items. */
async function schemaItems(page) {
  const blocks = await page.locator('script[type="application/ld+json"]').allTextContents();
  return blocks.flatMap((text) => {
    const data = JSON.parse(text);
    return data['@graph'] ?? [data];
  });
}

test.describe('blog', () => {
  test('el blog destaca el último artículo y ofrece los temas', async ({ page }) => {
    const errors = collectPageErrors(page);
    await page.goto(await blogUrl(page));
    await expectNoPhpErrors(page);

    await expect(page.locator('.breadcrumb [aria-current="page"]')).toHaveText('Blog');
    await expect(page.locator('.blog-featured .post-tile--featured')).toHaveCount(1);
    await expect(page.locator('.blog-grid .post-tile').first()).toBeVisible();
    // WordPress's sample post stays out.
    await expect(page.locator('.post-tile__title', { hasText: /Hello world|Hola mundo/ })).toHaveCount(0);

    const topics = page.locator('.blog-topics a');
    await expect(topics.first()).toHaveAttribute('aria-current', 'page');
    await topics.nth(1).click();
    await expect(page.locator('.page-hero h1')).toHaveText((await page.locator('.breadcrumb [aria-current="page"]').textContent()).trim());
    await expect(page.locator('.blog-grid .post-tile').first()).toBeVisible();
    expect(errors).toEqual([]);
  });

  test('un artículo tiene migas de pan, compartir, relacionados y datos estructurados', async ({ page }) => {
    const errors = collectPageErrors(page);
    await page.goto(await blogUrl(page));
    await page.locator('.post-tile .card__link').first().click();
    await expectNoPhpErrors(page);

    const crumbs = page.locator('.breadcrumb li');
    expect(await crumbs.count()).toBeGreaterThanOrEqual(3);
    await expect(crumbs.first()).toHaveText('Inicio');
    await expect(page.locator('.page-hero__meta')).toContainText('min de lectura');

    const share = page.locator('.share');
    await expect(share.locator('a[href^="https://wa.me/?text="]')).toBeVisible();
    await expect(share.locator('a[href*="linkedin.com/sharing"]')).toBeVisible();
    await expect(share.locator('a[href*="facebook.com/sharer"]')).toBeVisible();
    const href = await share.locator('a[href*="linkedin.com/sharing"]').getAttribute('href');
    expect(decodeURIComponent(href)).toContain(page.url());

    await expect(page.locator('.article-related .post-tile').first()).toBeVisible();

    const items = await schemaItems(page);
    const article = items.find((item) => item['@type'] === 'BlogPosting');
    expect(article?.headline).toBe((await page.locator('.page-hero h1').textContent()).trim());
    expect(items.find((item) => item['@type'] === 'BreadcrumbList')?.itemListElement.length).toBe(await crumbs.count());
    await expect(page.locator('meta[property="og:type"]')).toHaveAttribute('content', 'article');
    expect(errors).toEqual([]);
  });

  test('«Copiar enlace» copia la dirección del artículo', async ({ page, context, browserName }) => {
    test.skip(browserName !== 'chromium', 'Permisos del portapapeles solo en Chromium.');
    await context.grantPermissions(['clipboard-read', 'clipboard-write']);
    await page.goto(await blogUrl(page));
    await page.locator('.post-tile .card__link').first().click();
    const copy = page.locator('[data-share-copy] button');
    test.skip(!(await copy.isVisible()), 'El portapapeles necesita una dirección segura (localhost o https).');
    await copy.click();
    await expect(page.locator('[data-share-status]')).toHaveText('Enlace copiado');
    expect(await page.evaluate(() => navigator.clipboard.readText())).toBe(page.url());
  });

  test('la búsqueda no se indexa y los listados tienen dirección canónica', async ({ page }) => {
    await page.goto('/?s=fragancia');
    await expect(page.locator('meta[name="robots"]')).toHaveAttribute('content', /noindex/);
    await page.goto(await blogUrl(page));
    await expect(page.locator('link[rel="canonical"]')).toHaveCount(1);
  });
});
