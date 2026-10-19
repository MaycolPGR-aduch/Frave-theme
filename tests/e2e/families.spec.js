import { expect, test } from '@playwright/test';
import { collectPageErrors, expectNoPhpErrors } from './helpers.js';

// bin/crear-familias-olfativas.php (run by the CI seed) creates the guide under /fragancias/.
const GUIDE = '/?pagename=fragancias/familias-olfativas';

test.describe('guía de familias olfativas', () => {
  test('presenta las nueve familias con su índice y las familias por producto', async ({ page }) => {
    const errors = collectPageErrors(page);
    await page.goto(GUIDE);
    await expectNoPhpErrors(page);
    await expect(page.locator('.solution-hero h1')).toContainText('Familias olfativas');

    const families = page.locator('.family');
    await expect(families).toHaveCount(9);
    await expect(families.locator('.family__title')).toHaveText([
      'Cítrica', 'Floral', 'Frutal', 'Amaderada', 'Oriental', 'Aromática', 'Gourmand', 'Fresca', 'Especiada',
    ]);
    // Every family names its notes and the products it suits.
    await expect(page.locator('#familia-citrica .family__notes li')).toHaveCount(6);
    await expect(page.locator('#familia-citrica .family__uses li').first()).toBeVisible();

    // The index jumps to a family.
    await page.getByRole('navigation', { name: 'Familias olfativas' }).getByRole('link', { name: 'Gourmand' }).click();
    await expect(page).toHaveURL(/#familia-gourmand$/);
    await expect(page.locator('#familia-gourmand .family__title')).toBeInViewport();

    const rows = page.locator('.families-matrix__row');
    await expect(rows).toHaveCount(9);
    await expect(rows.filter({ hasText: 'Detergentes y suavizantes' }).locator('.family-chip').first()).toHaveAttribute('href', '#familia-fresca');
    await expect(page.locator('.site-footer__cta')).toHaveCount(0);
    expect(errors).toEqual([]);
  });

  test('lleva al catálogo de la familia que tiene productos y cotiza las demás', async ({ page }) => {
    await page.goto(GUIDE);
    const floral = page.locator('#familia-floral').getByRole('link', { name: 'Ver fragancias florales' });
    test.skip(!process.env.CI && (await floral.count()) === 0, 'Sin fragancias florales en este catálogo (el seed del CI tiene una).');
    // The seed's citrus product is an essence, not a fragrance: that family has no catalog link.
    await expect(page.locator('#familia-citrica').getByRole('link', { name: /Ver fragancias/ })).toHaveCount(0);
    await expect(floral).toHaveAttribute('href', /filter_familia-olfativa=floral/);
    await floral.click();
    await expect(page.locator('ul.products')).toContainText('[E2E] Fragancia floral');

    await page.goto(GUIDE);
    const quote = page.locator('#familia-citrica').getByRole('link', { name: 'Cotizar fragancias cítricas' });
    test.skip((await quote.getAttribute('target')) === '_blank', 'Sin página de contacto: la cotización va por WhatsApp.');
    await quote.click();
    await expect(page).toHaveURL(/linea=fragancias/);
  });

  test('la línea Fragancias invita a la guía; Sabores no', async ({ page }) => {
    await page.goto('/?pagename=fragancias');
    const invitation = page.locator('.line-families');
    await expect(invitation.locator('.family-chip')).toHaveCount(9);
    await invitation.getByRole('link', { name: 'Ver la guía' }).click();
    await expect(page.locator('.solution-hero h1')).toContainText('Familias olfativas');

    await page.goto('/?pagename=sabores');
    await expect(page.locator('.line-families')).toHaveCount(0);
  });
});

test.describe('rueda de familias olfativas', () => {
  test('en Perfumería gira hacia la familia elegida y muestra su ficha', async ({ page }) => {
    const errors = collectPageErrors(page);
    await page.goto('/?pagename=soluciones/perfumeria');
    await expectNoPhpErrors(page);
    const wheel = page.locator('.family-wheel');
    const tabs = wheel.getByRole('tab');
    await expect(tabs).toHaveCount(9);
    await expect(tabs.first()).toHaveAttribute('aria-selected', 'true');
    await expect(wheel.getByRole('tabpanel')).toHaveCount(1);
    await expect(wheel.getByRole('tabpanel')).toContainText('Cítrica');

    const dial = wheel.locator('[data-wheel-dial]');
    const before = await dial.evaluate((element) => element.style.getPropertyValue('--wheel-rotation'));
    await wheel.getByRole('tab', { name: 'Gourmand' }).click();
    await expect(wheel.getByRole('tabpanel')).toContainText('En perfumería:');
    await expect(wheel.getByRole('tabpanel')).toContainText('Vainilla');
    await expect(wheel.locator('[data-wheel-name]')).toHaveText('Gourmand');
    expect(await dial.evaluate((element) => element.style.getPropertyValue('--wheel-rotation'))).not.toBe(before);

    // Arrow keys move between families, as in any tab list.
    await page.keyboard.press('ArrowRight');
    await expect(wheel.getByRole('tab', { name: 'Fresca' })).toBeFocused();
    await expect(wheel.getByRole('tabpanel')).toContainText('Brisa marina');
    await expect(wheel.getByRole('tabpanel').getByRole('link', { name: 'Más sobre la familia Fresca' })).toHaveAttribute('href', /#familia-fresca$/);
    expect(errors).toEqual([]);
  });

  test('las otras industrias no la muestran', async ({ page }) => {
    await page.goto('/?pagename=soluciones/cuidado-del-hogar');
    await expect(page.locator('.solution-hero h1')).toBeVisible();
    await expect(page.locator('.family-wheel')).toHaveCount(0);
  });
});
