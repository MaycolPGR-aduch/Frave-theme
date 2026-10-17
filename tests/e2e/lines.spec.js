import { expect, test } from '@playwright/test';
import { collectPageErrors, expectNoPhpErrors } from './helpers.js';

// Line pages come from bin/crear-lineas.php (the CI seed runs it): /sabores/, /fragancias/, /envases/.
const FRAGANCIAS = '/?pagename=fragancias';

/** The image has been downloaded and decoded (lazy images load once scrolled into view). */
async function expectLoaded(image) {
  await image.scrollIntoViewIfNeeded();
  await expect.poll(() => image.evaluate((img) => img.complete && img.naturalWidth > 0)).toBe(true);
}

test.describe('páginas de línea', () => {
  test('la página de Fragancias presenta la línea y su portafolio', async ({ page }) => {
    const errors = collectPageErrors(page);
    await page.goto(FRAGANCIAS);
    await expectNoPhpErrors(page);

    await expect(page.locator('.line-hero h1')).toContainText('aromas');
    await expect(page.locator('.line-hero__media img')).toBeVisible();
    // The figure counts up, so its heading carries the full text for screen readers.
    await expect(page.locator('.highlight h3').first()).toHaveText(/\+500\s+referencias/);

    const groups = page.locator('#portafolio .line-group');
    await expect(groups).toHaveCount(4);
    await expect(groups.first().locator('h3')).toHaveText('Perfumería');
    await expect(groups.first().locator('.type-link')).toHaveCount(3);

    // Without an image chosen in the admin, every category shows the theme's own.
    for (const group of await groups.all()) {
      await expectLoaded(group.locator('.line-group__media img').first());
    }
    await expect(page.locator('#portafolio .type-link__thumb img')).toHaveCount(13);
    await expectLoaded(page.locator('#portafolio .type-link__thumb img').first());

    // Every type leads to its subcategory's detail page, with or without products.
    for (const link of await page.locator('#portafolio .type-link').all()) {
      await expect(link).toHaveAttribute('href', /perfumeria|cuidado|ambientacion|aerosoles|difusores|marketing|cocina/);
    }
    await expect(groups.first().locator('.line-group__uses')).toContainText('Perfumería');

    await expect(page.locator('.line-applications .ticker')).toHaveAttribute('aria-hidden', 'true');
    await expect(page.locator('.line-applications__list li').first()).toBeVisible();
    // The page closes with its own call to action instead of the footer's.
    await expect(page.locator('.cta-panel')).toBeVisible();
    await expect(page.locator('.site-footer__cta')).toHaveCount(0);
    expect(errors).toEqual([]);
  });

  test('«Solicitar cotización» abre el formulario con la línea escrita', async ({ page }) => {
    await page.goto(FRAGANCIAS);
    const quote = page.locator('.line-hero .btn--primary');
    test.skip((await quote.getAttribute('target')) === '_blank', 'Sin página de contacto: la cotización va por WhatsApp.');
    await quote.click();
    await expect(page).toHaveURL(/linea=fragancias/);
    await expect(page.locator('#frave-contact-message')).toHaveValue(/línea Fragancias/);
  });

  test('cotizar una subcategoría nombra la subcategoría y su línea', async ({ page }) => {
    await page.goto(FRAGANCIAS);
    const quote = page.locator('#portafolio .line-group').first().getByRole('link', { name: /Cotizar/ });
    test.skip((await quote.getAttribute('target')) === '_blank', 'Sin página de contacto: la cotización va por WhatsApp.');
    await quote.click();
    await expect(page).toHaveURL(/linea=perfumeria/);
    await expect(page.locator('#frave-contact-message')).toHaveValue(/Perfumería \(Fragancias\)/);
  });

  test('Sabores y Envases también tienen su página', async ({ page }) => {
    for (const [slug, title] of [['sabores', 'recuerdan'], ['envases', 'presentan']]) {
      await page.goto(`/?pagename=${slug}`);
      await expectNoPhpErrors(page);
      await expect(page.locator('.line-hero h1')).toContainText(title);
      await expect(page.locator('#portafolio .line-group').first()).toBeVisible();
    }
  });

  test('Sabores muestra su galería de inspiración', async ({ page }) => {
    await page.goto('/?pagename=sabores');
    const tiles = page.locator('.line-gallery .gallery-tile');
    await expect(tiles).toHaveCount(13);
    await expect(tiles.first().locator('figcaption')).toHaveText('Fresa');
    await expectLoaded(tiles.first().locator('img'));
    // The other lines have no gallery.
    await page.goto(FRAGANCIAS);
    await expect(page.locator('.line-gallery')).toHaveCount(0);
  });

  test('la portada lleva de cada línea a su página', async ({ page }) => {
    await page.goto('/');
    const card = page.locator('.home-lines .line-card--fragancias');
    await expect(card.locator('img')).toBeVisible();
    await card.getByRole('link', { name: 'Fragancias' }).click();
    await expect(page.locator('.line-hero h1')).toContainText('aromas');
  });

  test('la categoría del catálogo enlaza con su página de línea', async ({ page }) => {
    await page.goto('/?product_cat=perfumeria-arabe');
    await expectNoPhpErrors(page);
    // On wide screens the hero shows the category's image beside the text.
    await page.setViewportSize({ width: 1280, height: 800 });
    await expectLoaded(page.locator('.page-hero__media img'));
    const link = page.locator('.page-hero__line-link');
    await expect(link).toContainText('Conoce la línea Fragancias');
    await link.click();
    await expect(page.locator('.line-hero h1')).toContainText('aromas');
  });

  test('al señalar un tipo, la imagen de la tarjeta cambia a la suya', async ({ page }) => {
    await page.goto(FRAGANCIAS);
    const card = page.locator('#portafolio .line-group').first();
    const arabic = card.locator('.type-link', { hasText: 'Perfumería árabe' });
    await arabic.scrollIntoViewIfNeeded();
    await arabic.hover();
    const layer = card.locator('.line-group__layer.is-active');
    await expect(layer).toHaveCount(1);
    await expect.poll(() => layer.evaluate((img) => img.complete && img.naturalWidth > 0)).toBe(true);
    await expect(layer).toHaveAttribute('src', /perfumeria-arabe/);
    await expect(card.locator('[data-swap-caption]')).toHaveText('Perfumería árabe');
    // Leaving the card brings its own picture back.
    await page.mouse.move(5, 5);
    await expect(card.locator('.line-group__layer.is-active')).toHaveCount(0);
    await expect(card.locator('[data-swap-caption]')).toBeHidden();
  });

  test('cada subcategoría tiene su página de detalle, unida a su línea y a sus soluciones', async ({ page }) => {
    await page.goto(FRAGANCIAS);
    await page.locator('#portafolio .line-group').first().getByRole('link', { name: /Ver detalle/ }).click();
    await expect(page.locator('.page-hero h1')).toHaveText('Perfumería');
    await expect(page.locator('.page-hero__line-link')).toContainText('Fragancias');
    await expect(page.locator('.page-hero__solutions a')).toHaveText(['Perfumería']);
    // Its types as cards, each with its own detail page.
    await expect(page.locator('.category-types .line-group')).toHaveCount(3);
    await page.locator('.category-types .line-group__title a', { hasText: 'Perfumería de diseñador' }).click();
    await expect(page.locator('.page-hero h1')).toHaveText('Perfumería de diseñador');
  });

  test('sin productos, la página de detalle invita a cotizar en lugar de mostrar una lista vacía', async ({ page }) => {
    await page.goto('/?product_cat=perfumeria-disenador');
    await expectNoPhpErrors(page);
    const empty = page.locator('.category-empty');
    test.skip((await empty.count()) === 0, 'Esta subcategoría ya tiene productos.');
    await expect(empty.locator('h2')).toContainText('Perfumería de diseñador');
    await expect(page.locator('.catalog-filters')).toHaveCount(0);
    await expect(page.locator('.page-hero__actions a')).toContainText('Solicitar cotización');
  });

  test('el menú agrupa las líneas', async ({ page }) => {
    await page.goto('/');
    const toggle = page.locator('[data-primary-navigation] [data-submenu-toggle]', { hasText: 'Líneas' });
    test.skip((await toggle.count()) === 0, 'Este sitio tiene un menú propio en Apariencia → Menús.');
    await toggle.click();
    const submenu = toggle.locator('xpath=ancestor::li[1]/ul[contains(@class, "sub-menu")]');
    await expect(submenu.getByRole('link')).toHaveText([/Fragancias/, /Sabores/, /Envases/]);
  });
});
