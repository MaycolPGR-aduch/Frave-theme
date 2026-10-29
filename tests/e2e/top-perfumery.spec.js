import { expect, test } from '@playwright/test';
import { collectPageErrors, expectNoPhpErrors } from './helpers.js';

// bin/crear-top-perfumeria.php (run by the CI seed) publishes the confirmed references and
// leaves the ones Frave still has to confirm as drafts (Born in Roma Uomo, Romance…).
const DESIGNER = '/?product_cat=perfumeria-disenador';

test.describe('top de perfumería', () => {
  test('la subcategoría muestra su top con una pestaña por público y el podio', async ({ page }) => {
    const errors = collectPageErrors(page);
    await page.goto(DESIGNER);
    await expectNoPhpErrors(page);
    const top = page.locator('#top-perfumeria');
    await expect(top.locator('h2')).toHaveText('Los perfumes que marcan tendencia');
    await expect(top.getByRole('tab')).toHaveText(['Hombre', 'Mujer']);

    const men = top.locator('#top-panel-hombre');
    await expect(men.locator('.top-item__name')).toHaveText(['Invictus', 'Stronger With You Intense', 'Le Male Elixir']);
    // The first panel starts open, with its notes and its own picture (assets/media/top/{slug}).
    const first = men.locator('.top-item').first();
    await expect(first.locator('.top-item__toggle')).toHaveAttribute('aria-expanded', 'true');
    await expect(first.locator('.top-item__notes dt')).toHaveText(['Salida', 'Corazón', 'Fondo']);
    await expect(first.locator('.top-item__media img')).toHaveAttribute('src', /media\/top\/invictus/);
    await expect(men.locator('.top-item').nth(1).locator('.top-item__cta')).not.toBeInViewport();
    // Drafts (still to confirm) do not show.
    await expect(top).not.toContainText('Born in Roma');
    await expect(top.locator('#top-panel-mujer')).toBeHidden();

    await top.getByRole('tab', { name: 'Mujer' }).click();
    await expect(top.locator('#top-panel-mujer .top-item__name').first()).toHaveText('N°5');
    await expect(men).toBeHidden();
    await expect(top.locator('.top-perfumery__note')).toContainText('referencia del mercado');
    expect(errors).toEqual([]);
  });

  test('con un solo público no hay pestañas', async ({ page }) => {
    await page.goto('/?product_cat=perfumeria-exclusiva');
    const top = page.locator('#top-perfumeria');
    await expect(top.getByRole('tab')).toHaveCount(0);
    await expect(top.locator('.top-item__name')).toHaveText(['Imagination', 'Ombré Leather', 'Baccarat Rouge 540']);
  });

  test('un panel se abre a la vez: con el ratón al señalarlo y en el celular con un toque', async ({ page }) => {
    await page.setViewportSize({ width: 1440, height: 900 });
    await page.goto(DESIGNER);
    const items = page.locator('#top-panel-hombre .top-item');
    await items.nth(2).hover();
    await expect(items.nth(2)).toHaveClass(/is-open/);
    await expect(items.first()).not.toHaveClass(/is-open/);
    await expect(items.nth(2).locator('.top-item__toggle')).toHaveAttribute('aria-expanded', 'true');
    // Closed panels keep their links out of the keyboard's way.
    await expect(items.first().locator('.top-item__panel')).toHaveJSProperty('inert', true);

    await page.setViewportSize({ width: 390, height: 844 });
    await page.reload();
    await items.nth(1).locator('.top-item__toggle').click();
    await expect(items.nth(1)).toHaveClass(/is-open/);
    await expect(items.nth(1).locator('.top-item__cta')).toBeVisible();
    // A second tap closes it.
    await items.nth(1).locator('.top-item__toggle').click();
    await expect(items.nth(1).locator('.top-item__toggle')).toHaveAttribute('aria-expanded', 'false');
  });

  test('«Quiero crear este perfil» abre el formulario con el perfil escrito', async ({ page }) => {
    await page.goto(DESIGNER);
    const cta = page.locator('#top-panel-hombre .top-item').first().locator('.top-item__cta');
    test.skip((await cta.getAttribute('target')) === '_blank', 'Sin página de contacto: el perfil va por WhatsApp.');
    await cta.click();
    await expect(page.locator('#frave-contact-message')).toHaveValue(/perfil similar a Invictus \(Paco Rabanne\)/);
  });

  test('Fragancias y Perfumería presentan los tops; Sabores no', async ({ page }) => {
    await page.goto('/?pagename=fragancias');
    const band = page.locator('.top-band');
    await expect(band.locator('.top-band__title')).toHaveText(['Perfumería de diseñador', 'Perfumería árabe', 'Perfumería exclusiva (nicho)']);
    await expect(band.locator('.top-band__card').first()).toContainText('Invictus');
    await expect(band.locator('.top-band__card').first()).toContainText('N°5');
    // One audience: its first three.
    await expect(band.locator('.top-band__card').last().locator('strong')).toHaveText(['Imagination', 'Ombré Leather', 'Baccarat Rouge 540']);

    await band.locator('.top-band__card').first().getByRole('link', { name: /Ver el top/ }).click();
    await expect(page).toHaveURL(/#top-perfumeria$/);
    await expect(page.locator('#top-perfumeria')).toBeVisible();

    await page.goto('/?pagename=soluciones/perfumeria');
    await expect(page.locator('.top-band .top-band__card')).toHaveCount(3);

    await page.goto('/?pagename=sabores');
    await expect(page.locator('.top-band')).toHaveCount(0);
  });
});
