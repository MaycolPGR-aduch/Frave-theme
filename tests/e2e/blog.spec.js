import { expect, test } from '@playwright/test';
import { collectPageErrors, expectNoPhpErrors } from './helpers.js';

/**
 * Address of the blog, from the home page: «Ver todos los artículos» of the latest posts,
 * which does not depend on the menu (a menu set in Apariencia → Menús may leave it out).
 */
async function blogUrl(page) {
  await page.goto('/');
  const link = page.locator('.home-posts .section-head__action');
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

  test('el blog intercala la guía de fragancias entre los artículos', async ({ page }) => {
    await page.goto(await blogUrl(page));
    const promo = page.locator('.blog-grid .blog-promo');
    await expect(promo).toHaveCount(1);
    await expect(promo.locator('a')).toHaveText(['Familias olfativas', 'Pirámide olfativa']);
    // After the second card of the grid.
    expect(await page.locator('.blog-grid > li').nth(2).locator('.blog-promo').count()).toBe(1);
  });

  test('un artículo largo tiene índice, consejo, tabla, asesoría, firma y enlaces a su línea', async ({ page }) => {
    const errors = collectPageErrors(page);
    await page.goto(await blogUrl(page));
    await page.locator('.post-tile .card__link', { hasText: 'Propiedades organolépticas' }).click();
    await expectNoPhpErrors(page);

    const index = page.locator('.article__body .page-index');
    await expect(index.locator('.page-index__title')).toHaveText('En este artículo');
    const first = index.locator('a').first();
    const anchor = (await first.getAttribute('href')).slice(1);
    await expect(page.locator(`.article__body h2[id="${anchor}"]`)).toHaveText((await first.textContent()).trim());

    await expect(page.locator('.article__body .is-style-frave-tip')).toContainText('Consejo:');
    await expect(page.locator('.article__body .wp-block-table th').first()).toHaveText('Sentido');
    // The quote box sits between two sections, never before the first one.
    const quote = page.locator('.article__body .article-quote');
    await expect(quote).toHaveCount(1);
    await expect(quote.locator('a[data-frave-whatsapp="article"]')).toBeVisible();
    expect(await quote.evaluate((box) => [...box.parentElement.children].slice(0, [...box.parentElement.children].indexOf(box)).some((el) => el.matches('h2[id]')))).toBe(true);
    await expect(quote.locator('+ h2')).toHaveCount(1);
    await expect(page.locator('.article-author')).toContainText('Equipo técnico Frave');
    await expect(page.locator('.article-product .article-product__links a')).toContainText(['Línea Sabores']);
    expect(errors).toEqual([]);
  });

  test('un artículo de una colección la enlaza en «Para tu producto»', async ({ page }) => {
    await page.goto(await blogUrl(page));
    await page.locator('.post-tile .card__link', { hasText: 'Colección Lavavajillas' }).click();
    const product = page.locator('.article-product');
    await expect(product.locator('.article-product__links a')).toContainText(['Línea Fragancias', 'Familias olfativas', 'Pirámide olfativa']);
    await expect(product.locator('.collection-card')).toHaveCount(1);
    await expect(product.locator('.collection-card')).toContainText('Lavavajillas');
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

  test('con el menú automático, «Blog» aparece cuando hay artículos', async ({ page }) => {
    const url = await blogUrl(page);
    const fallback = await page.locator('#primary-navigation .menu-list > li > a[href]').evaluateAll((links) => links.some((a) => a.textContent.trim() === 'Servicios'));
    test.skip(!fallback, 'Este sitio usa un menú propio de Apariencia → Menús.');
    await expect(page.locator(`#primary-navigation .menu-list > li > a[href="${url}"]`)).toHaveText('Blog');
  });

  test('la búsqueda no se indexa y los listados tienen dirección canónica', async ({ page }) => {
    await page.goto('/?s=fragancia');
    await expect(page.locator('meta[name="robots"]')).toHaveAttribute('content', /noindex/);
    await page.goto(await blogUrl(page));
    await expect(page.locator('link[rel="canonical"]')).toHaveCount(1);
  });
});
