import { expect, test } from '@playwright/test';
import { collectPageErrors, expectNoPhpErrors } from './helpers.js';

// Notes of a perfumery subcategory (inc/fragrance-notes.php): those of its published top
// references (bin/crear-top-perfumeria.php, run by the CI seed) and the style's hallmarks.
test.describe('notas de la perfumería', () => {
  test('la perfumería árabe muestra su pirámide de notas con los sellos del estilo', async ({ page }) => {
    const errors = collectPageErrors(page);
    await page.goto('/?product_cat=perfumeria-arabe');
    await expectNoPhpErrors(page);
    const notes = page.locator('#ingredientes');
    await expect(notes.locator('h2')).toHaveText('Las notas de la perfumería árabe');
    await expect(notes.locator('.notes-tier__label')).toContainText(['Salida', 'Corazón', 'Fondo']);

    // The base starts with the hallmarks, in the style's order; the card shows the first one.
    const base = notes.locator('.notes-tier--base .note-chip__name');
    await expect(base.first()).toHaveText('Oud');
    await expect(notes.locator('[data-card-name]')).toHaveText('Oud');
    await expect(notes.locator('[data-card-seal]')).toBeVisible();
    await expect(notes.locator('[data-card-perfumes-wrap]')).toBeHidden();

    // A note of the top references names the perfumes that carry it.
    await notes.locator('.note-chip', { hasText: 'Café' }).click();
    await expect(notes.locator('[data-card-name]')).toHaveText('Café');
    await expect(notes.locator('[data-card-perfumes]')).toContainText('Khamrah Qahwa');
    await expect(notes.locator('[data-card-seal]')).toBeHidden();
    await expect(notes.locator('.note-chip', { hasText: 'Café' })).toHaveAttribute('aria-pressed', 'true');
    expect(errors).toEqual([]);
  });

  test('«Sellos del estilo» deja solo los sellos y la ficha pasa a uno de ellos', async ({ page }) => {
    await page.goto('/?product_cat=perfumeria-arabe');
    const notes = page.locator('#ingredientes');
    await notes.locator('.note-chip', { hasText: 'Café' }).click();
    await notes.locator('[data-notes-filter="seals"]').click();
    await expect(notes.locator('[data-notes-filter="seals"]')).toHaveAttribute('aria-pressed', 'true');
    await expect(notes.locator('.note-chip', { hasText: 'Café' })).toBeHidden();
    await expect(notes.locator('.note-chip:visible')).toHaveCount(7);
    await expect(notes.locator('[data-card-seal]')).toBeVisible();

    await notes.locator('[data-notes-filter="all"]').click();
    await expect(notes.locator('.note-chip', { hasText: 'Café' })).toBeVisible();
  });

  test('cada estilo tiene sus notas; las categorías sin top ni sellos no muestran la sección', async ({ page }) => {
    await page.goto('/?product_cat=perfumeria-disenador');
    await expect(page.locator('#ingredientes .note-chip', { hasText: 'Notas marinas' })).toBeVisible();
    await expect(page.locator('#ingredientes .note-icon').first()).toBeVisible();

    await page.goto('/?product_cat=cuidado-del-cabello');
    await expect(page.locator('#ingredientes')).toHaveCount(0);
  });
});
