import { expect, test } from '@playwright/test';
import { collectPageErrors, expectNoPhpErrors } from './helpers.js';

// bin/crear-paginas-empresa.php (run by the CI seed) creates /nosotros/ and /experticia/.
const ABOUT = '/?pagename=nosotros';
const EXPERTISE = '/?pagename=experticia';

test.describe('páginas de empresa', () => {
  test('Nosotros presenta la empresa, su esencia y sus creencias', async ({ page }) => {
    const errors = collectPageErrors(page);
    await page.goto(ABOUT);
    await expectNoPhpErrors(page);

    await expect(page.locator('.company-hero h1')).toContainText('diferenciarse');
    await expect(page.locator('.company-essence blockquote')).toContainText('Conectamos conocimiento, confianza e innovación');
    const beliefs = page.locator('.company-belief');
    await expect(beliefs).toHaveCount(4);
    await expect(beliefs.locator('.company-belief__behavior')).toHaveCount(4);
    await expect(beliefs.first()).toContainText('Comportamiento:');
    await expect(page.locator('.cta-panel')).toBeVisible();
    await expect(page.locator('.site-footer__cta')).toHaveCount(0);
    expect(errors).toEqual([]);
  });

  test('Experticia muestra la nube de palabras del Perú con su lista legible', async ({ page }) => {
    const errors = collectPageErrors(page);
    await page.goto(EXPERTISE);
    await expectNoPhpErrors(page);

    await expect(page.locator('.company-hero h1')).toContainText('marca');
    const cloud = page.locator('.word-cloud__svg');
    await expect(cloud).toBeVisible();
    await expect(cloud).toHaveAttribute('aria-hidden', 'true');
    const words = await cloud.locator('text').count();
    expect(words).toBeGreaterThan(40);
    // Screen readers get every word from the list instead of the drawing.
    await expect(page.locator('.word-cloud ul.screen-reader-text li')).toHaveCount(words);
    await expect(page.locator('.highlight h3').first()).toHaveText(/\+500\s+referencias/);
    expect(errors).toEqual([]);
  });

  test('«Cómo trabajamos» abre un paso a la vez y cada paso tiene su dirección', async ({ page }) => {
    await page.goto(EXPERTISE);
    const tabs = page.locator('[data-method-tab]');
    await expect(tabs).toHaveCount(4);
    await expect(tabs.first()).toHaveAttribute('aria-expanded', 'true');
    await expect(page.locator('#paso-proponemos')).toBeHidden();

    await page.getByRole('button', { name: /Proponemos/ }).click();
    await expect(page.getByRole('button', { name: /Proponemos/ })).toHaveAttribute('aria-expanded', 'true');
    await expect(page.locator('#paso-proponemos')).toBeVisible();
    await expect(page.locator('#paso-escuchamos')).toBeHidden();
    await expect(page).toHaveURL(/#paso-proponemos$/);

    await page.goto(`${EXPERTISE}#paso-asesoramos`);
    await expect(page.getByRole('button', { name: /Asesoramos/ })).toHaveAttribute('aria-expanded', 'true');
    await expect(page.locator('#paso-asesoramos')).toBeVisible();
  });

  test('la portada y el menú llevan a Nosotros y Experticia', async ({ page }) => {
    await page.goto('/');
    await page.locator('.home-reasons').getByRole('link', { name: 'Conoce nuestra experticia' }).click();
    await expect(page.locator('.company-hero h1')).toContainText('marca');

    const lines = page.locator('[data-primary-navigation] [data-submenu-toggle]', { hasText: 'Líneas' });
    test.skip((await lines.count()) === 0, 'Este sitio tiene un menú propio en Apariencia → Menús.');
    const nav = page.locator('[data-primary-navigation]');
    await expect(nav.getByRole('link', { name: 'Nosotros', exact: true })).toBeVisible();
    await expect(nav.getByRole('link', { name: 'Experticia', exact: true })).toBeVisible();
  });
});
