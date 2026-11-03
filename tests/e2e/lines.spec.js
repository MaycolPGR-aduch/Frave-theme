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

  test('Sabores desliza sus aplicaciones y presenta sus propiedades organolépticas', async ({ page }) => {
    await page.goto('/?pagename=sabores');
    const band = page.locator('.line-applications');
    await expect(band.locator('h2')).toHaveText('Propiedades que dan carácter a tus recetas');
    // The sliding words keep the applications; the cards are the properties.
    await expect(band.locator('.ticker')).toContainText('Panetón');
    const cards = band.locator('.line-applications__list li');
    await expect(cards.first()).toHaveText('Color y aspecto');
    await expect(cards.filter({ hasText: 'Panetón' })).toHaveCount(0);
    // With the article published, each card opens its section.
    const links = band.locator('.line-applications__list a');
    if (await links.count()) {
      await expect(links.first()).toHaveAttribute('href', /#vista-color-y-aspecto$/);
    }
  });

  test('Sabores explica sus propiedades organolépticas por sentido', async ({ page }) => {
    await page.goto('/?pagename=sabores');
    const senses = page.locator('.line-senses');
    await expect(senses.locator('h2')).toContainText('todos los sentidos');
    await expect(senses.locator('.line-sense__sense')).toHaveText(['Vista', 'Olfato', 'Gusto', 'Tacto y oído']);
    // With the article published, each card opens its section.
    const links = senses.locator('.line-sense a');
    if (await links.count()) {
      await expect(links.nth(2)).toHaveAttribute('href', /#gusto-los-sabores-basicos$/);
    }
    // The section never spills sideways, even with the cards sliding on phones. Only the
    // section: with the shop active the header itself overflows between 1024 and 1280 px.
    expect(await senses.evaluate((section) => section.scrollWidth <= section.clientWidth)).toBe(true);

    await page.goto('/?pagename=fragancias');
    await expect(page.locator('.line-senses')).toHaveCount(0);
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

  test('en escritorio cada categoría es una banda: la imagen sigue a la vista mientras se recorren los tipos', async ({ page }) => {
    await page.setViewportSize({ width: 1440, height: 900 });
    await page.goto(FRAGANCIAS);
    const bands = page.locator('#portafolio .line-group--band');
    await expect(bands).toHaveCount(4);
    // The bands span the screen and alternate sides.
    const first = await bands.first().boundingBox();
    expect(Math.round(first.width)).toBeGreaterThanOrEqual(1420);
    const media = bands.nth(1).locator('.line-group__visual');
    const text = bands.nth(1).locator('.line-group__body');
    expect((await media.boundingBox()).x).toBeGreaterThan((await text.boundingBox()).x);

    // Pointing at the last type of the longest band, its picture is on screen.
    const last = bands.nth(3).locator('.type-link').last();
    await last.scrollIntoViewIfNeeded();
    await last.hover();
    await expect(bands.nth(3).locator('.line-group__layer.is-active')).toHaveCount(1);
    await expect(bands.nth(3).locator('.line-group__visual')).toBeInViewport({ ratio: 0.8 });
  });

  test('en el celular la imagen de la banda pasa sola por los tipos y se puede pausar', async ({ browser }) => {
    const context = await browser.newContext({ viewport: { width: 390, height: 844 } });
    const page = await context.newPage();
    await page.goto(FRAGANCIAS);
    const band = page.locator('#portafolio .line-group--band').first();
    await band.locator('.line-group__visual').scrollIntoViewIfNeeded();
    await expect(band).toHaveClass(/is-cycling/);
    await expect(band.locator('[data-swap-caption]')).toHaveText('Perfumería de diseñador', { timeout: 6000 });
    await expect(band.locator('.type-link.is-active')).toHaveText(/Perfumería de diseñador/);

    const toggle = band.locator('[data-swap-toggle]');
    await toggle.click();
    await expect(toggle).toHaveAttribute('aria-label', 'Reproducir las imágenes');
    const caption = await band.locator('[data-swap-caption]').textContent();
    await page.waitForTimeout(3000);
    await expect(band.locator('[data-swap-caption]')).toHaveText(caption);
    await context.close();
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

  test('sin productos, la página de detalle no muestra avisos ni una lista vacía', async ({ page }) => {
    await page.goto('/?product_cat=perfumeria-disenador');
    await expectNoPhpErrors(page);
    test.skip((await page.locator('ul.products li').count()) > 0, 'Esta subcategoría ya tiene productos.');
    // The site is finished: no «Estamos preparando…» and no «No se encontraron productos».
    await expect(page.locator('main')).not.toContainText('Estamos preparando');
    await expect(page.locator('.woocommerce-info')).toHaveCount(0);
    await expect(page.locator('.catalog-filters')).toHaveCount(0);
    await expect(page.locator('.page-hero__actions a')).toContainText('Solicitar cotización');

    await page.goto('/?pa_coleccion=romero');
    await expect(page.locator('main')).not.toContainText('Estamos preparando');
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
