import { expect, test } from '@playwright/test';
import { SHOP, collectPageErrors, expectNoPhpErrors } from './helpers.js';

// The catalog page as a summary of everything Frave offers (inc/catalog-overview.php).
test.describe('catálogo como resumen', () => {
  test('la cabecera presenta el catálogo con accesos y cifras', async ({ page }) => {
    const errors = collectPageErrors(page);
    await page.goto(SHOP);
    await expectNoPhpErrors(page);
    const hero = page.locator('.page-hero');
    await expect(hero.locator('h1')).toHaveText('Todo lo que Frave ofrece');
    await expect(hero.locator('.catalog-shortcuts a', { hasText: 'Colecciones' })).toHaveAttribute('href', '#catalogo-colecciones');
    await expect(hero.locator('.catalog-figures')).toContainText('líneas');
    await expect(hero.locator('.catalog-figures')).toContainText('categorías');
    expect(errors).toEqual([]);
  });

  test('reúne índice, colecciones, top, aplicaciones, aromas, productos y cierre', async ({ page }) => {
    await page.setViewportSize({ width: 1440, height: 900 });
    await page.goto(SHOP);

    // Anchors to every block (wide screens).
    const anchors = page.locator('.catalog-anchors a');
    await expect(anchors.first()).toHaveText('Líneas');
    await expect(anchors.last()).toHaveAttribute('href', '#productos');

    // The index: every line with all its categories and types.
    const index = page.locator('#catalogo-lineas');
    await expect(index.locator('.catalog-index__title')).toHaveText(['Fragancias', 'Sabores', 'Envases']);
    await expect(index.locator('.catalog-index__line--wide .catalog-index__title')).toHaveText('Fragancias');
    await expect(index.getByRole('link', { name: 'Perfumería árabe' })).toBeVisible();
    await expect(index.locator('.catalog-index__count').first()).toContainText('colecci');

    await expect(page.locator('#catalogo-colecciones .collection-card').first()).toBeVisible();
    await expect(page.locator('#catalogo-top .top-band__card')).toHaveCount(3);
    await expect(page.locator('#catalogo-aplicaciones .catalog-uses__item')).toHaveCount(5);
    await expect(page.locator('#catalogo-aromas .family-chip')).toHaveCount(9);
    await expect(page.locator('#catalogo-aromas .note-chip', { hasText: 'Oud' })).toHaveAttribute('href', /#ingredientes$/);

    await expect(page.locator('#productos h2')).toHaveText('Todos los productos');
    await expect(page.locator('.catalog-closing h2')).toHaveText('¿No encuentras lo que buscas?');
    // The page closes with its own call to action instead of the footer's.
    await expect(page.locator('.site-footer__cta')).toHaveCount(0);

    // An index link leads to its category.
    await index.getByRole('link', { name: 'Perfumería árabe' }).click();
    await expect(page.locator('.page-hero h1')).toHaveText('Perfumería árabe');
  });

  test('una búsqueda o un filtro dejan solo el listado', async ({ page }) => {
    await page.goto('/?s=fragancia&post_type=product');
    await expect(page.locator('#catalogo-lineas')).toHaveCount(0);
    await expect(page.locator('.catalog-closing')).toHaveCount(0);
  });
});
