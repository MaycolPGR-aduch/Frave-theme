import { expect, test } from '@playwright/test';

test.describe('navegación de escritorio', () => {
  test('un submenú se abre con su botón, se cierra con Escape y devuelve el foco', async ({ page }) => {
    await page.goto('/');
    const toggle = page.locator('[data-primary-navigation] [data-submenu-toggle]').first();
    test.skip((await toggle.count()) === 0, 'El menú de este sitio no tiene submenús.');

    const submenu = toggle.locator('xpath=ancestor::li[1]/ul[contains(@class, "sub-menu")]');
    await toggle.focus();
    await page.keyboard.press('Enter');
    await expect(toggle).toHaveAttribute('aria-expanded', 'true');
    await expect(submenu).toBeVisible();
    await expect(submenu.locator('a').first()).toBeVisible();

    await page.keyboard.press('Escape');
    await expect(toggle).toHaveAttribute('aria-expanded', 'false');
    await expect(toggle).toBeFocused();
    await expect(submenu).toBeHidden();
  });

  test('un clic fuera del menú cierra el submenú', async ({ page }) => {
    await page.goto('/');
    const toggle = page.locator('[data-primary-navigation] [data-submenu-toggle]').first();
    test.skip((await toggle.count()) === 0, 'El menú de este sitio no tiene submenús.');

    await toggle.click();
    await expect(toggle).toHaveAttribute('aria-expanded', 'true');
    await page.locator('main').click({ position: { x: 5, y: 5 } });
    await expect(toggle).toHaveAttribute('aria-expanded', 'false');
  });
});
