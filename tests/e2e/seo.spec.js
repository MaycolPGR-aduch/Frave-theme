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

  test('las categorías y colecciones están en el mapa del sitio aunque no tengan productos', async ({ request }) => {
    const index = await (await request.get('/?sitemap=index')).text();
    expect(index).toContain('product_cat');
    expect(index).toContain('pa_coleccion');
    const categories = await (await request.get('/?sitemap=taxonomies&sitemap-subtype=product_cat')).text();
    expect(categories).toContain('perfumeria-arabe');
    expect(categories).not.toMatch(/sin-categorizar|uncategorized/);
    const collections = await (await request.get('/?sitemap=taxonomies&sitemap-subtype=pa_coleccion')).text();
    expect(collections).toContain('lavavajillas');
  });

  test('los títulos dicen lo que ofrece cada página y la portada conserva el suyo', async ({ page }) => {
    const titles = [
      ['/?pagename=fragancias', /^Fragancias para perfumería, cosmética y hogar – /],
      ['/?product_cat=cuidado-textil', /^Fragancias para detergentes y suavizantes – /],
      ['/?pa_coleccion=romero', /^Colección Romero: /],
    ];
    for (const [path, title] of titles) {
      await page.goto(path);
      await expect(page, path).toHaveTitle(title);
    }
    await page.goto('/');
    await expect(page).toHaveTitle(/^Frave/);
  });

  test('una descripción corta se reemplaza por la escrita para buscadores', async ({ page }) => {
    await page.goto('/?product_cat=valvulas');
    const description = await page.locator('meta[name="description"]').getAttribute('content');
    expect(description?.length).toBeGreaterThan(110);
  });

  test('la categoría de una línea cede su lugar en Google a la página de la línea', async ({ page, request }) => {
    await page.goto('/?product_cat=fragancias');
    await expect(page.locator('meta[name="robots"]')).toHaveAttribute('content', /noindex/);
    const categories = await (await request.get('/?sitemap=taxonomies&sitemap-subtype=product_cat')).text();
    expect(categories).not.toMatch(/(product_cat=|categoria-producto\/)fragancias\/?<\/loc>/);
    await page.goto('/?product_cat=perfumeria-arabe');
    await expect(page.locator('meta[name="robots"]')).not.toHaveAttribute('content', /noindex/);
  });

  test('en modo catálogo el carrito y la cuenta no están en el mapa del sitio', async ({ page, request }) => {
    test.skip(!(await isCatalogMode(page)), 'Con la tienda activa esas páginas sí se usan.');
    const pages = await (await request.get('/?sitemap=posts&sitemap-subtype=page')).text();
    expect(pages).not.toMatch(/carrito|finalizar-compra|mi-cuenta/);
  });
});
