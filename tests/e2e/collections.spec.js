import { expect, test } from '@playwright/test';
import { collectPageErrors, expectNoPhpErrors } from './helpers.js';

// bin/crear-colecciones.php (run by the CI seed) creates the attribute «Colección», its ten
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

    await expect(page.locator('.collections-hub .collection-card')).toHaveCount(10);
    await expect(page.locator('.collections-hub .collections h2')).toHaveText([
      'Cuidado del cabello', 'Cuidado de mascotas', 'Cuidado textil', 'Cuidado de superficies', 'Cocina y lavavajillas', 'Ambientación',
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

  test('la página de una colección presenta sus ingredientes, otras colecciones y la cotización', async ({ page }) => {
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

    // Its ingredient, with its family; a single one has no pyramid.
    await expect(page.locator('.note-card__name')).toHaveText(['Romero']);
    await expect(page.locator('.note-card__family')).toHaveText(['Aromática']);
    await expect(page.locator('.profile-tier')).toHaveCount(0);
    await expect(page.locator('.profile-families .family-chip')).toHaveText(['Aromática']);

    // Without aromas yet: the other collections of its subcategory and the quote panel, no filters.
    await expect(page.locator('#otras-colecciones .collection-card')).toHaveCount(3);
    await expect(page.locator('.cta-panel--bare h2')).toHaveText('¿Te interesa la colección Romero?');
    await expect(page.locator('.cta-panel--bare [data-frave-whatsapp]')).toHaveAttribute('href', /Romero/);
    await expect(page.locator('.catalog-filters')).toHaveCount(0);
    await expect(page.locator('.site-footer__cta')).toHaveCount(0);
    expect(errors).toEqual([]);
  });

  test('los ingredientes se dan vuelta y se ordenan en la pirámide olfativa', async ({ page }) => {
    await page.goto('/?pa_coleccion=lavavajillas');
    const cards = page.locator('.note-card');
    await expect(cards.locator('.note-card__name')).toHaveText(['Limón', 'Lima', 'Manzana', 'Uva', 'Tutti frutti', 'Avena', 'Sandía']);
    // The back of a card: its level, description and family in the guide.
    await expect(cards.first().locator('.note-card__level')).toHaveText('Nota de salida');
    await expect(cards.first().locator('.note-card__link')).toHaveAttribute('href', /#familia-citrica$/);
    await cards.first().focus();
    await expect(cards.first().locator('.note-card__inner')).toHaveCSS('transform', /matrix3d/);

    const tiers = page.locator('.profile-tier');
    await expect(tiers).toHaveCount(3);
    await expect(tiers.first()).toContainText('Salida');
    await expect(tiers.first().locator('li')).toContainText(['Limón', 'Lima', 'Uva', 'Sandía']);
    await expect(tiers.last().locator('li')).toHaveText(['Avena']);
    await expect(page.locator('.profile-families .family-chip')).toHaveText(['Cítrica', 'Frutal', 'Gourmand']);
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

  test('la colección Lavavajillas está en su subcategoría y en Cuidado del hogar', async ({ page }) => {
    await page.goto('/?product_cat=cocina-y-lavavajillas');
    const section = page.locator('#colecciones');
    await expect(section.locator('.collection-card__title')).toHaveText(['Lavavajillas']);
    await section.locator('.collection-card', { hasText: 'Lavavajillas' }).locator('.collection-card__media').click({ force: true });
    await expect(page.locator('.page-hero h1')).toHaveText('Lavavajillas');
    await expect(page.locator('.page-hero .eyebrow')).toContainText('Colección · Cocina y lavavajillas');
    await expect(page.locator('.page-hero')).toContainText('chicle-uva (tutti frutti)');

    await page.goto('/?pagename=soluciones/cuidado-del-hogar');
    await expect(page.locator('#colecciones .collection-card__title')).toContainText(['Lavavajillas']);
  });
});
