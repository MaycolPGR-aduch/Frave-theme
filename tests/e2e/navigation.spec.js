import { expect, test } from '@playwright/test';

test.describe('navegación de escritorio', () => {
  test('el primer Tab muestra «Saltar al contenido» legible y lleva al contenido', async ({ page }) => {
    await page.goto('/');
    await page.keyboard.press('Tab');
    const skip = page.locator('.skip-link');
    await expect(skip).toBeFocused();
    await expect(skip).toBeInViewport();
    const [color, background] = await skip.evaluate((el) => [getComputedStyle(el).color, getComputedStyle(el).backgroundColor]);
    expect(color).not.toBe(background);
    expect(color).toBe('rgb(255, 255, 255)');
    await page.keyboard.press('Enter');
    await expect(page).toHaveURL(/#primary$/);
  });

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

  test('los submenús conservan su flecha en cualquier ancho de escritorio', async ({ page }) => {
    for (const width of [1024, 1280, 1440]) {
      await page.setViewportSize({ width, height: 800 });
      await page.goto('/');
      const widths = await page.locator('[data-primary-navigation] .submenu-toggle svg').evaluateAll((icons) => icons.map((icon) => icon.getBoundingClientRect().width));
      expect(Math.min(...widths), `${width}px`).toBeGreaterThanOrEqual(15);
      // On failure the message tells how much the bar overflows and what it holds.
      const bar = await page.locator('.site-header__inner').evaluate((inner) => ({
        overflow: inner.scrollWidth - inner.clientWidth,
        parts: [...inner.children].map((child) => `${child.className.split(' ')[0]} ${Math.round(child.getBoundingClientRect().width)}`).join(', '),
        items: [...inner.querySelectorAll('.menu-list > li')].map((item) => item.textContent.trim().split('\n')[0]).join(', '),
        font: document.fonts.check('700 14px Lato'),
      }));
      expect(bar.overflow, `${width}px: ${JSON.stringify(bar)}`).toBeLessThanOrEqual(0);
    }
  });

  test('las redes sociales aparecen en la cabecera y en el pie, y abren en otra pestaña', async ({ page }) => {
    await page.setViewportSize({ width: 1440, height: 900 });
    await page.goto('/');
    const footer = page.locator('.social-links--footer .social-link');
    test.skip((await footer.count()) === 0, 'Este sitio no tiene redes configuradas.');
    const header = page.locator('.social-links--header .social-link');
    await expect(header.first()).toBeVisible();
    expect(await header.count()).toBe(await footer.count());
    await expect(header.first()).toHaveAttribute('target', '_blank');
    await expect(header.first().locator('.screen-reader-text')).toContainText(/Instagram|TikTok|Facebook|LinkedIn|YouTube/);
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
