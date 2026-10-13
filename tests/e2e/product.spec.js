import { expect, test } from '@playwright/test';
import { SHOP, collectPageErrors, expectNoPhpErrors } from './helpers.js';

/** Open the first product of a category that has attributes (Fragancias in CI and locally). */
async function openFragrance(page) {
  await page.goto('/?product_cat=fragancias');
  const card = page.locator('ul.products li.product').first();
  await expect(card).toBeVisible();
  await card.locator('a.woocommerce-LoopProduct-link').first().click();
  await expect(page.locator('div.product .product_title')).toBeVisible();
}

test.describe('ficha de producto', () => {
  test('muestra la línea, los datos del producto y la descripción sin pestañas', async ({ page }) => {
    const errors = collectPageErrors(page);
    await openFragrance(page);
    await expectNoPhpErrors(page);

    const eyebrow = page.locator('.product-eyebrow');
    await expect(eyebrow).toContainText('Fragancias');
    await expect(eyebrow.getByRole('link').first()).toHaveAttribute('href', /.+/);

    // The attributes are in view, as label and values, not hidden in a tab.
    const specs = page.locator('.product-specs');
    await expect(specs).toBeVisible();
    await expect(specs.locator('dt').first()).toBeVisible();
    await expect(specs.locator('.product-specs__values li').first()).toBeVisible();
    await expect(page.locator('.woocommerce-tabs #tab-additional_information')).toHaveCount(0);
    await expect(page.locator('.woocommerce-tabs #tab-description')).toHaveCount(0);
    expect(errors).toEqual([]);
  });

  test('las medidas se ordenan de menor a mayor', async ({ page }) => {
    await page.goto(SHOP);
    const links = await page.locator('ul.products li.product a.woocommerce-LoopProduct-link').evaluateAll((items) => items.map((a) => a.href));
    let checked = 0;
    for (const link of links) {
      await page.goto(link);
      const row = page.locator('.product-specs__row', { has: page.locator('dt', { hasText: /Presentación|Capacidad/ }) });
      if ((await row.count()) === 0) continue;
      const values = await row.locator('li').allTextContents();
      const amounts = values.map((value) => {
        const match = value.trim().match(/^(\d+(?:[.,]\d+)?)\s*(mg|kg|g|ml|l)\b/i);
        if (!match) return Number.POSITIVE_INFINITY;
        const factor = { mg: 0.001, g: 1, kg: 1000, ml: 1, l: 1000 }[match[2].toLowerCase()];
        return parseFloat(match[1].replace(',', '.')) * factor;
      });
      expect(amounts, values.join(', ')).toEqual([...amounts].sort((a, b) => a - b));
      checked += 1;
    }
    test.skip(checked === 0, 'Ningún producto tiene presentaciones o capacidades.');
  });
});

test.describe('categorías del catálogo', () => {
  test('una línea ofrece sus subcategorías con productos como accesos rápidos', async ({ page }) => {
    await page.goto('/?product_cat=fragancias');
    const chips = page.locator('.catalog-subcategories a');
    await expect(chips.first()).toBeVisible();
    await chips.first().click();
    await expect(page.locator('ul.products li.product').first()).toBeVisible();
  });

  test('los filtros siguen el orden de búsqueda: familia antes que presentación', async ({ page }) => {
    await page.goto('/?product_cat=fragancias');
    const titles = await page.locator('#catalog-filters .catalog-filter__title').allTextContents();
    const family = titles.findIndex((title) => title.includes('Familia olfativa'));
    const size = titles.findIndex((title) => title.includes('Presentación'));
    test.skip(family < 0 || size < 0, 'Esta vista no usa esos atributos.');
    expect(family).toBeLessThan(size);
  });
});
