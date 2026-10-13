import { expect, test } from '@playwright/test';
import { SHOP, STORE_PAGES, collectPageErrors, expectNoPhpErrors, isCatalogMode } from './helpers.js';

/** Catalog mode (frave-peru): products are shown without prices or online sales. */
test.describe('modo catálogo', () => {
  test.beforeEach(async ({ page }) => {
    test.skip(!(await isCatalogMode(page)), 'El modo catálogo está desactivado en este sitio.');
  });

  test('el catálogo no muestra precios ni compra, y ofrece ver detalles', async ({ page }) => {
    const errors = collectPageErrors(page);
    await page.goto(SHOP);
    await expectNoPhpErrors(page);

    const cards = page.locator('ul.products li.product');
    await expect(cards.first()).toBeVisible();
    await expect(page.locator('ul.products .price')).toHaveCount(0);
    await expect(page.locator('ul.products .add_to_cart_button')).toHaveCount(0);
    await expect(page.locator('ul.products span.onsale')).toHaveCount(0);
    await expect(page.locator('ul.products a.product-card__details')).toHaveCount(await cards.count());

    const orderby = await page.locator('select.orderby option').evaluateAll((options) => options.map((option) => option.value));
    expect(orderby).not.toContain('price');
    expect(orderby).not.toContain('price-desc');
    await expect(page.locator('#catalog-filters .catalog-filter__title', { hasText: 'Precio' })).toHaveCount(0);
    expect(errors).toEqual([]);
  });

  test('los filtros y el orden por precio de la URL se descartan', async ({ page }) => {
    await page.goto(`${SHOP}&min_price=10&max_price=20&orderby=price`);
    await expect(page).not.toHaveURL(/min_price|max_price|orderby=price/);
    await expect(page.locator('ul.products li.product').first()).toBeVisible();
  });

  test('carrito, checkout y cuenta llevan al catálogo', async ({ page }) => {
    for (const path of STORE_PAGES) {
      await page.goto(path);
      await expect(page, path).toHaveURL(/post_type=product|\/catalogo\/?$/);
    }
  });

  test('la cabecera no tiene cuenta ni carrito, y la API pública de la tienda está cerrada', async ({ page, request }) => {
    await page.goto('/');
    await expect(page.locator('.header-action--cart')).toHaveCount(0);
    await expect(page.locator('.header-action--account')).toHaveCount(0);
    await expect(page.locator('#mini-cart')).toHaveCount(0);

    const response = await request.get('/?rest_route=/wc/store/v1/products');
    expect(response.status()).toBe(404);
  });

  test('la ficha invita a cotizar y el formulario llega con el producto', async ({ page }) => {
    await page.goto(SHOP);
    const card = page.locator('ul.products li.product').first();
    const name = (await card.locator('.woocommerce-loop-product__title').textContent()).trim();
    await card.locator('a.woocommerce-LoopProduct-link').click();

    const summary = page.locator('.product .summary');
    await expect(summary.locator('.price')).toHaveCount(0);
    await expect(summary.locator('form.cart')).toHaveCount(0);
    await expect(page.locator('script[type="application/ld+json"]', { hasText: '"offers"' })).toHaveCount(0);
    await expect(page.locator('#tab-title-reviews')).toHaveCount(0);

    const inquiry = page.locator('.product-inquiry');
    await expect(inquiry.getByRole('heading', { name: '¿Te interesa este producto?' })).toBeVisible();
    const quote = inquiry.locator('a.product-inquiry__quote');
    test.skip((await quote.count()) === 0, 'La página de contacto no está publicada en este sitio.');

    await quote.click();
    await expect(page).toHaveURL(/producto=\d+/);
    await expect(page.locator('#frave-contact-subject-cotizacion')).toBeChecked();
    await expect(page.locator('#frave-contact-message')).toHaveValue(new RegExp(name.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')));
    await expect(page.locator('#frave-contact-order-number')).toHaveCount(0);
  });
});
