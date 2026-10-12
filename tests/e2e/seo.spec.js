import { expect, test } from '@playwright/test';
import { isCatalogMode } from './helpers.js';

test.describe('metadatos y privacidad del sitio', () => {
  test('cada página tiene descripción e imagen para compartir', async ({ page }) => {
    for (const path of ['/', '/?pagename=fragancias', '/?pagename=nosotros', '/?pagename=soluciones/perfumeria']) {
      await page.goto(path);
      const description = await page.locator('meta[name="description"]').getAttribute('content');
      expect(description?.length, path).toBeGreaterThan(40);
      expect(description?.length, path).toBeLessThanOrEqual(161);
      await expect(page.locator('meta[property="og:image"]'), path).toHaveAttribute('content', /\.(jpe?g|png)$/);
      await expect(page.locator('meta[property="og:title"]'), path).toHaveAttribute('content', /.+/);
    }
  });

  test('sin icono del sitio se usa el isotipo como favicon', async ({ page }) => {
    await page.goto('/');
    const icons = page.locator('link[rel="icon"]');
    expect(await icons.count()).toBeGreaterThan(0);
  });

  test('los nombres de las cuentas no se publican', async ({ request }) => {
    const users = await request.get('/?rest_route=/wp/v2/users');
    expect(users.status()).toBe(404);
    const author = await request.get('/?author=1', { maxRedirects: 0 });
    expect(author.status()).toBe(301);
    const index = await (await request.get('/?sitemap=index')).text();
    expect(index).not.toContain('sitemap=users');
  });

  test('en modo catálogo el carrito y la cuenta no están en el mapa del sitio', async ({ page, request }) => {
    test.skip(!(await isCatalogMode(page)), 'Con la tienda activa esas páginas sí se usan.');
    const pages = await (await request.get('/?sitemap=posts&sitemap-subtype=page')).text();
    expect(pages).not.toMatch(/carrito|finalizar-compra|mi-cuenta/);
  });
});
