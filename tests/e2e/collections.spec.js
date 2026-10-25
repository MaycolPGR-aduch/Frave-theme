import { expect, test } from '@playwright/test';
import { collectPageErrors, expectNoPhpErrors } from './helpers.js';

// bin/crear-colecciones.php (run by the CI seed) creates the attribute «Colección», its nine
// first collections (without aromas yet) and the hub /colecciones/.
const HUB = '/?pagename=colecciones';
const HAIR = '/?product_cat=cuidado-del-cabello';
const ROSEMARY = '/?pa_coleccion=romero';

test.describe('colecciones de fragancias', () => {
  test('el centro de colecciones las agrupa por subcategoría', async ({ page }) => {
    const errors = collectPageErrors(page);
    await page.goto(HUB);
    await expectNoPhpErrors(page);
    await expect(page.locator('.page-hero h1')).toHaveText('Colecciones');

    await expect(page.locator('.collections-hub .collection-card')).toHaveCount(9);
    await expect(page.locator('.collections-hub .collections h2')).toHaveText([
      'Cuidado del cabello', 'Cuidado de mascotas', 'Cuidado textil', 'Cuidado de superficies', 'Ambientación',
    ]);
    const hair = page.locator('#colecciones-cuidado-del-cabello');
    await expect(hair.locator('.collection-card__title')).toHaveText([
      'Shampoo y acondicionador', 'Mascarillas y cremas de peinar', 'Romero', 'Pitahaya e Hibiscus',
    ]);
    // Without aromas yet, each card offers them on request; the group links to its subcategory.
    await expect(hair.locator('.collection-card__meta').first()).toContainText('Aromas a pedido');
    await hair.getByRole('link', { name: 'Ver cuidado del cabello' }).click();
    await expect(page.locator('.page-hero h1')).toHaveText('Cuidado del cabello');
    expect(errors).toEqual([]);
  });

  test('la subcategoría muestra sus colecciones y cada tarjeta lleva a la suya', async ({ page }) => {
    await page.goto(HAIR);
    const section = page.locator('#colecciones');
    await expect(section.locator('h2')).toHaveText('Colecciones de Cuidado del cabello');
    await expect(section.locator('.collection-card')).toHaveCount(4);
    // On its own subcategory's page the card does not repeat the subcategory.
    await expect(section.locator('.collection-card__eyebrow').first()).toHaveText('Colección');

    // The whole card is the link: a click on its picture lands on the name's stretched link.
    await section.locator('.collection-card', { hasText: 'Romero' }).locator('.collection-card__media').click({ force: true });
    await expect(page.locator('.page-hero h1')).toHaveText('Romero');
  });

  test('la página de una colección sin aromas invita a cotizar', async ({ page }) => {
    const errors = collectPageErrors(page);
    await page.goto(ROSEMARY);
    await expectNoPhpErrors(page);
    await expect(page.locator('.page-hero h1')).toHaveText('Romero');
    await expect(page.locator('.page-hero .eyebrow')).toContainText('Colección · Cuidado del cabello');
    await expect(page.locator('.woocommerce-breadcrumb a').nth(1)).toHaveText('Colecciones');
    // Its own picture, not its subcategory's.
    await expect(page.locator('.page-hero img')).toHaveAttribute('src', /colecciones\/romero/);
    await expect(page.locator('.page-hero__line-link')).toContainText('Ver cuidado del cabello');
    await expect(page.locator('.page-hero__solutions a')).toHaveText(['Cosmética y cuidado personal']);

    const empty = page.locator('.category-empty');
    await expect(empty.locator('h2')).toHaveText('Estamos preparando los aromas de Romero');
    await expect(empty.locator('[data-frave-whatsapp="collection"]')).toHaveAttribute('href', /Romero/);
    await expect(page.locator('.catalog-filters')).toHaveCount(0);
    expect(errors).toEqual([]);
  });

  test('las bandas de Fragancias y las soluciones nombran sus colecciones', async ({ page }) => {
    await page.goto('/?pagename=fragancias');
    const cosmetics = page.locator('#portafolio .line-group').filter({ hasText: 'Fragancias cosméticas' });
    await expect(cosmetics.locator('.line-group__collections a')).toHaveText([
      'Shampoo y acondicionador', 'Mascarillas y cremas de peinar', 'Romero', 'Pitahaya e Hibiscus', 'Mascotas',
    ]);

    await page.goto('/?pagename=soluciones/cosmetica-y-cuidado-personal');
    const section = page.locator('#colecciones');
    await expect(section.locator('h2')).toContainText('Colecciones para');
    await expect(section.locator('.collection-card')).toHaveCount(5);
    await expect(section.locator('.collection-card__eyebrow').first()).toHaveText('Cuidado del cabello');
    await section.getByRole('link', { name: 'Ver todas las colecciones' }).click();
    await expect(page.locator('.page-hero h1')).toHaveText('Colecciones');
  });
});
