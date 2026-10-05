import { expect, test } from '@playwright/test';
import { SHOP, collectPageErrors, expectNoPhpErrors } from './helpers.js';

test.describe('filtros del catálogo', () => {
  test('el panel muestra las secciones y filtra por atributo', async ({ page }) => {
    const errors = collectPageErrors(page);
    await page.goto(SHOP);
    await expectNoPhpErrors(page);

    const panel = page.locator('#catalog-filters');
    await expect(panel.locator('.catalog-filter__title', { hasText: 'Categorías' })).toBeVisible();
    await expect(panel.locator('.catalog-filter__title', { hasText: 'Precio' })).toBeVisible();

    const total = await page.locator('ul.products li.product').count();
    const option = panel.locator('.catalog-filter--attribute a.catalog-option').first();
    await expect(option).toBeVisible();
    const label = (await option.locator('.catalog-option__label').textContent()).trim();
    await option.click();

    await expect(page).toHaveURL(/filter_[a-z0-9-]+=/);
    await expect(page.locator('.catalog-chip', { hasText: label })).toBeVisible();
    await expect(panel.locator('a.catalog-option.is-selected', { hasText: label })).toBeVisible();
    expect(await page.locator('ul.products li.product').count()).toBeLessThanOrEqual(total);
    await expect(page.locator('meta[name="robots"]')).toHaveAttribute('content', /noindex/);

    await page.locator('.catalog-chip', { hasText: label }).click();
    await expect(page).not.toHaveURL(/filter_[a-z0-9-]+=/);
    await expect(page.locator('.catalog-chip')).toHaveCount(0);
    expect(errors).toEqual([]);
  });

  test('solo disponibles oculta los productos agotados', async ({ page }) => {
    await page.goto(`${SHOP}&filter_stock_status=instock`);
    await expect(page.locator('.catalog-chip', { hasText: 'Solo disponibles' })).toBeVisible();
    await expect(page.locator('ul.products li.product.outofstock')).toHaveCount(0);
  });

  test('un rango de precio invertido se corrige con una redirección', async ({ page }) => {
    await page.goto(`${SHOP}&min_price=50&max_price=20`);
    await expect(page).toHaveURL(/min_price=20/);
    await expect(page).toHaveURL(/max_price=50/);
    await expect(page.locator('.catalog-chip', { hasText: 'Precio' })).toBeVisible();
  });

  test('una combinación sin resultados ofrece quitar los filtros', async ({ page }) => {
    await page.goto(`${SHOP}&min_price=999990&max_price=999999`);
    await expect(page.locator('.catalog-no-results a')).toBeVisible();
    await page.locator('.catalog-no-results a').click();
    await expect(page.locator('ul.products li.product').first()).toBeVisible();
  });
});
