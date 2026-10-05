import { expect, test } from '@playwright/test';
import { SHOP } from './helpers.js';

test.describe('móvil', () => {
  test('el menú se abre, retiene el foco y se cierra con Escape', async ({ page }) => {
    await page.goto('/');
    const toggle = page.locator('[data-menu-toggle]');
    await toggle.click();
    await expect(toggle).toHaveAttribute('aria-expanded', 'true');
    await expect(page.locator('[data-primary-navigation] a').first()).toBeFocused();

    await page.keyboard.press('Escape');
    await expect(toggle).toHaveAttribute('aria-expanded', 'false');
    await expect(toggle).toBeFocused();
  });

  test('los filtros se abren como panel lateral modal', async ({ page }) => {
    await page.goto(SHOP);
    const button = page.locator('.catalog-toolbar__filters');
    const panel = page.locator('#catalog-filters');
    await expect(panel).toBeHidden();

    await button.click();
    await expect(panel).toBeVisible();
    await expect(panel).toHaveAttribute('role', 'dialog');
    await expect(panel.locator('.catalog-filters__close')).toBeFocused();

    await page.keyboard.press('Escape');
    await expect(panel).toBeHidden();
    await expect(button).toBeFocused();
  });

  test('la página no se desborda horizontalmente', async ({ page }) => {
    for (const path of ['/', SHOP]) {
      await page.goto(path);
      const overflow = await page.evaluate(() => document.documentElement.scrollWidth - window.innerWidth);
      expect(overflow, path).toBeLessThanOrEqual(0);
    }
  });
});
