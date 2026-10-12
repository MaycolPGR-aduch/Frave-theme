import { expect, test } from '@playwright/test';
import { collectPageErrors, expectNoPhpErrors } from './helpers.js';

// Line pages come from bin/crear-lineas.php (the CI seed runs it): /sabores/, /fragancias/, /envases/.
const FRAGANCIAS = '/?pagename=fragancias';

test.describe('páginas de línea', () => {
  test('la página de Fragancias presenta la línea y su portafolio', async ({ page }) => {
    const errors = collectPageErrors(page);
    await page.goto(FRAGANCIAS);
    await expectNoPhpErrors(page);

    await expect(page.locator('.line-hero h1')).toContainText('aromas');
    await expect(page.locator('.line-hero__media img')).toBeVisible();
    // The figure counts up, so its heading carries the full text for screen readers.
    await expect(page.locator('.line-highlight h3').first()).toHaveText(/\+500\s+referencias/);

    const groups = page.locator('#portafolio .line-group');
    await expect(groups).toHaveCount(4);
    await expect(groups.first().locator('h3')).toHaveText('Perfumería');
    await expect(groups.first().locator('.line-chip')).toHaveCount(3);

    // A chip links to the catalog only when its subcategory has products.
    for (const chip of await page.locator('.line-chip--link').all()) {
      await expect(chip).toHaveAttribute('href', /perfumeria|cuidado|ambientacion|aerosoles|difusores|marketing|cocina/);
    }

    await expect(page.locator('.line-applications .ticker')).toHaveAttribute('aria-hidden', 'true');
    await expect(page.locator('.line-applications__list li').first()).toBeVisible();
    // The page closes with its own call to action instead of the footer's.
    await expect(page.locator('.line-cta')).toBeVisible();
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
    const link = page.locator('.page-hero__line-link');
    await expect(link).toContainText('Conoce la línea Fragancias');
    await link.click();
    await expect(page.locator('.line-hero h1')).toContainText('aromas');
  });

  test('el menú agrupa las líneas', async ({ page }) => {
    await page.goto('/');
    const toggle = page.locator('[data-primary-navigation] [data-submenu-toggle]', { hasText: 'Líneas' });
    test.skip((await toggle.count()) === 0, 'Este sitio tiene un menú propio en Apariencia → Menús.');
    await toggle.click();
    const submenu = toggle.locator('xpath=ancestor::li[1]/ul[contains(@class, "sub-menu")]');
    await expect(submenu.getByRole('link')).toHaveText([/Sabores/, /Fragancias/, /Envases/]);
  });
});
