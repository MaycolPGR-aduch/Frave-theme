import { expect, test } from '@playwright/test';

// Background photographs under a veil (template-parts/components/section-bg.php).
test.describe('imágenes de fondo de secciones', () => {
  test('las secciones elegidas llevan su foto decorativa y las demás no', async ({ page }) => {
    const sections = [
      ['/?pagename=nosotros', '.company-essence', 'nosotros-esencia'],
      ['/', '.home-catalog__panel', 'catalogo'],
      ['/?pagename=fragancias', '.line-applications', 'aplicaciones-fragancias'],
      ['/?pagename=fragancias', '.line-families', 'textura-luz-sombra'],
    ];
    for (const [url, selector, image] of sections) {
      await page.goto(url);
      const background = page.locator(`${selector} .section-bg`);
      await expect(background, `${selector} en ${url}`).toHaveAttribute('aria-hidden', 'true');
      await expect(background.locator('img')).toHaveAttribute('src', new RegExp(`fondos/${image}[.]webp`));
      await expect(background.locator('img')).toHaveAttribute('loading', 'lazy');
    }
    // The industries reuse the applications band without a photograph.
    await page.goto('/?pagename=soluciones/perfumeria');
    await expect(page.locator('.line-applications .section-bg')).toHaveCount(0);
    // «Razones para elegirnos» stays white: its panels already carry photographs.
    await page.goto('/');
    await expect(page.locator('.home-reasons .section-bg')).toHaveCount(0);
    await expect(page.locator('.home-reasons')).not.toHaveClass(/section--surface/);
  });
});
