import { expect, test } from '@playwright/test';
import { collectPageErrors, expectNoPhpErrors } from './helpers.js';

test('la portada carga sin errores y con los destacados como tarjetas', async ({ page }) => {
  const errors = collectPageErrors(page);
  await page.goto('/');

  await expect(page.locator('.hero h1')).toBeVisible();
  await expectNoPhpErrors(page);

  const titles = page.locator('.featured-section .woocommerce-loop-product__title');
  if (await titles.count()) {
    // Unstyled cards fall back to the 2rem+ global heading size.
    const size = await titles.first().evaluate((el) => parseFloat(getComputedStyle(el).fontSize));
    expect(size).toBeLessThan(24);
    const button = page.locator('.featured-section ul.products .button').first();
    await expect(button).toHaveCSS('background-color', 'rgb(32, 35, 41)');
  }

  expect(errors).toEqual([]);
});

test('el pie enlaza al Libro de Reclamaciones y los textos legales publicados', async ({ page }) => {
  await page.goto('/');
  const claims = page.locator('a.claims-book-link');
  await expect(claims).toBeVisible();
  await expect(page.locator('.legal-menu a', { hasText: 'Libro de Reclamaciones' })).toHaveCount(1);
});

test('las páginas de error responden 404 con el diseño del tema', async ({ page }) => {
  const response = await page.goto('/?p=999999999');
  expect(response.status()).toBe(404);
  await expect(page.locator('main .empty-state h1')).toBeVisible();
});
