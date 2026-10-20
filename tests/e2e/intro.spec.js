import { expect, test } from '@playwright/test';
import { collectPageErrors } from './helpers.js';

// The intro skips automated browsers (navigator.webdriver): these tests pretend to be a visitor.
test.beforeEach(async ({ context }) => {
  await context.addInitScript(() => Object.defineProperty(Navigator.prototype, 'webdriver', { get: () => false }));
});

test.describe('introducción de entrada', () => {
  test('la primera página muestra el logotipo sobre naranja y luego se levanta', async ({ page }) => {
    const errors = collectPageErrors(page);
    await page.goto('/', { waitUntil: 'commit' });
    const intro = page.locator('[data-site-intro]');
    await expect(intro).toBeVisible();
    await expect(intro).toHaveAttribute('aria-hidden', 'true');
    await expect(intro.locator('.site-intro__logo')).toHaveClass(/is-loaded/);
    // It never stays more than 3.5 s (plus the 0.8 s curtain).
    await expect(intro).toBeHidden({ timeout: 6000 });
    await expect(page.locator('html')).not.toHaveClass(/site-intro-on/);

    // The next pages of the visit open directly.
    await page.goto('/?pagename=fragancias');
    await expect(intro).toBeHidden();
    expect(errors).toEqual([]);
  });

  test('un clic la levanta antes', async ({ page }) => {
    await page.goto('/', { waitUntil: 'commit' });
    const intro = page.locator('[data-site-intro]');
    await expect(intro).toBeVisible();
    await intro.click();
    await expect(intro).toBeHidden({ timeout: 3000 });
  });
});

test('la cabecera muestra solo las letras del logotipo', async ({ page }) => {
  await page.goto('/');
  const logo = page.locator('.site-branding__logo');
  await expect(logo).toHaveAttribute('src', /frave-wordmark\.webp/);
  await expect(logo).toHaveAttribute('alt', /\S/);
});
