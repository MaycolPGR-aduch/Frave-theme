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
    await expect(men.locator('.top-card__name')).toHaveText(['Invictus', 'Stronger With You Intense', 'Le Male Elixir']);
    await expect(men.locator('.top-card--1 .top-card__notes dt')).toHaveText(['Salida', 'Corazón', 'Fondo']);
    // Each perfume has its own picture (assets/media/top/{slug}).
    await expect(men.locator('.top-card--1 img')).toHaveAttribute('src', /media\/top\/invictus/);
    // Drafts (still to confirm) do not show.
    await expect(top).not.toContainText('Born in Roma');
    await expect(top.locator('#top-panel-mujer')).toBeHidden();

    await top.getByRole('tab', { name: 'Mujer' }).click();
    await expect(top.locator('#top-panel-mujer .top-card__name').first()).toHaveText('N°5');
    await expect(men).toBeHidden();
    await expect(top.locator('.top-perfumery__note')).toContainText('referencia del mercado');
    expect(errors).toEqual([]);
  });

  test('con un solo público no hay pestañas', async ({ page }) => {
    await page.goto('/?product_cat=perfumeria-exclusiva');
    const top = page.locator('#top-perfumeria');
    await expect(top.getByRole('tab')).toHaveCount(0);
    await expect(top.locator('.top-card__name')).toHaveText(['Imagination', 'Ombré Leather', 'Baccarat Rouge 540']);
  });

  test('«Quiero crear este perfil» abre el formulario con el perfil escrito', async ({ page }) => {
    await page.goto(DESIGNER);
    const cta = page.locator('#top-panel-hombre .top-card--1 .top-card__cta');
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
