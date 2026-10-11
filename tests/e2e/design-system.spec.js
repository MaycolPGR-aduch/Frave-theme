import { expect, test } from '@playwright/test';
import { SHOP, collectPageErrors, expectNoPhpErrors } from './helpers.js';

test.describe('sistema visual', () => {
  test('la cabecera flota fija y se eleva al hacer scroll', async ({ page }) => {
    await page.goto(SHOP);
    const header = page.locator('[data-site-header]');
    await expect(header).not.toHaveClass(/is-scrolled/);

    await page.evaluate(() => window.scrollTo({ top: 900, behavior: 'instant' }));
    await expect(header).toHaveClass(/is-scrolled/);
    const top = await page.locator('.site-header__inner').evaluate((element) => element.getBoundingClientRect().top);
    expect(top).toBeGreaterThanOrEqual(0);
    expect(top).toBeLessThan(48);
  });

  test('el catálogo abre con la banda: migas, título y buscador', async ({ page }) => {
    const errors = collectPageErrors(page);
    await page.goto(SHOP);
    await expectNoPhpErrors(page);

    const hero = page.locator('.page-hero');
    await expect(hero.locator('h1')).toBeVisible();
    await expect(hero.locator('.woocommerce-breadcrumb')).toBeVisible();
    await expect(hero.locator('form.product-search input[type="search"]')).toBeVisible();
    await expect(page.locator('.search-strip')).toHaveCount(0);
    // The hero starts at the top of the page, behind the floating header.
    expect(await hero.evaluate((element) => element.getBoundingClientRect().top)).toBeLessThanOrEqual(1);
    expect(errors).toEqual([]);
  });

  test('las páginas interiores usan la banda con su título', async ({ page }) => {
    const response = await page.goto('/?pagename=contacto');
    test.skip(!response.ok(), 'La página de contacto no está publicada en este sitio.');
    await expect(page.locator('.page-hero h1')).toBeVisible();
    await expect(page.locator('.page-hero .eyebrow')).toBeVisible();
  });

  test('el pie invita a contactar con el botón primario', async ({ page }) => {
    await page.goto('/');
    const cta = page.locator('.site-footer__cta');
    test.skip((await cta.count()) === 0, 'Sin página de contacto ni WhatsApp no hay llamada a la acción.');
    const button = cta.locator('a.btn--primary');
    await expect(button).toBeVisible();
    await expect(button).toHaveCSS('background-color', 'rgb(196, 59, 0)');
    await expect(button).toHaveCSS('color', 'rgb(255, 255, 255)');
  });

  test('la guía del sistema visual no se muestra a los visitantes', async ({ page }) => {
    await page.goto('/?sistema-visual');
    await expect(page.locator('.style-guide')).toHaveCount(0);
  });
});
