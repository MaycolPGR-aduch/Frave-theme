import { expect, test } from '@playwright/test';
import { collectPageErrors, expectNoPhpErrors, isCatalogMode } from './helpers.js';

test('la portada carga sin errores y con los destacados como tarjetas', async ({ page }) => {
  const errors = collectPageErrors(page);
  await page.goto('/');

  await expect(page.locator('.home-hero h1')).toBeVisible();
  await expectNoPhpErrors(page);

  const titles = page.locator('.featured-section .woocommerce-loop-product__title');
  if (await titles.count()) {
    // Unstyled cards fall back to the 2rem+ global heading size.
    const size = await titles.first().evaluate((el) => parseFloat(getComputedStyle(el).fontSize));
    expect(size).toBeLessThan(24);
    if (await isCatalogMode(page)) {
      // Outlined "Ver detalles" instead of the dark add-to-cart button.
      const details = page.locator('.featured-section ul.products a.product-card__details').first();
      await expect(details).toHaveCSS('background-color', 'rgba(0, 0, 0, 0)');
      await expect(details).toHaveCSS('color', 'rgb(32, 35, 41)');
    } else {
      const button = page.locator('.featured-section ul.products .button').first();
      await expect(button).toHaveCSS('background-color', 'rgb(32, 35, 41)');
    }
  }

  expect(errors).toEqual([]);
});

test('el pie enlaza al Libro de Reclamaciones y los textos legales publicados', async ({ page }) => {
  await page.goto('/');
  const claims = page.locator('a.claims-book-link');
  await expect(claims).toBeVisible();
  await expect(page.locator('.legal-menu a', { hasText: 'Libro de Reclamaciones' })).toHaveCount(1);
});

test('las páginas de error responden 404 con el diseño del tema', async ({ page }) => {
  const response = await page.goto('/?p=999999999');
  expect(response.status()).toBe(404);
  await expect(page.locator('main .empty-state h1')).toBeVisible();
});

test.describe('portada', () => {
  test('el hero presenta el título, las dos acciones y el vídeo con su imagen fija', async ({ page }) => {
    await page.goto('/');
    const hero = page.locator('.home-hero');
    // Screen readers get one stable sentence; the alternating words are hidden from them.
    await expect(hero.locator('h1 .screen-reader-text')).toHaveText('Fragancias y sabores que conectan con tu marca');
    await expect(hero.locator('h1 [aria-hidden="true"] .hero-rotator__word')).toHaveCount(4);
    await expect(hero.locator('.home-hero__facts li')).toHaveCount(3);
    await expect(hero.locator('a.btn--dark')).toHaveAttribute('href', /post_type=product|\/catalogo\//);

    const video = hero.locator('video[data-hero-video]');
    await expect(video).toHaveAttribute('poster', /\.(jpe?g|webp)/);
    await expect(video).toHaveAttribute('aria-hidden', 'true');
    expect(await video.locator('source').count()).toBeGreaterThan(0);
  });

  test('las palabras del título se alternan y el botón también las pausa', async ({ page }) => {
    await page.goto('/');
    const active = page.locator('.hero-rotator__word.is-active');
    await expect(active).toHaveText('conectan con');
    await expect(active).toHaveText('distinguen a', { timeout: 6000 });
    await expect(page.locator('.home-hero')).toHaveClass(/is-ambient/);

    const toggle = page.locator('[data-hero-toggle]');
    await expect(toggle).toHaveAttribute('aria-label', 'Pausar la animación');
    await toggle.click();
    await expect(page.locator('.home-hero')).toHaveClass(/is-paused/);
    const word = await active.textContent();
    await page.waitForTimeout(3200);
    await expect(active).toHaveText(word);
    await toggle.click();
    await expect(toggle).toHaveAttribute('aria-label', 'Pausar la animación');
  });

  test('la animación se puede pausar y el botón cambia según su estado', async ({ page }) => {
    await page.goto('/');
    const toggle = page.locator('[data-hero-toggle]');
    // Headless browsers may refuse to play video; then the poster stays and there is nothing to pause.
    try {
      await expect(toggle).toBeVisible({ timeout: 8000 });
    } catch {
      test.skip(true, 'El navegador no reprodujo el vídeo.');
    }
    await expect(toggle).toHaveAttribute('aria-label', 'Pausar la animación');
    await toggle.click();
    await expect(toggle).toHaveAttribute('aria-label', /Reproducir|Ver de nuevo/);
    expect(await page.locator('[data-hero-video]').evaluate((video) => video.paused)).toBe(true);
  });

  test('con movimiento reducido el vídeo no se reproduce y queda la imagen fija', async ({ browser }) => {
    const context = await browser.newContext({ reducedMotion: 'reduce' });
    const page = await context.newPage();
    await page.goto('/');
    await page.waitForTimeout(1500);
    const state = await page.locator('[data-hero-video]').evaluate((video) => ({ paused: video.paused, time: video.currentTime }));
    expect(state).toEqual({ paused: true, time: 0 });
    await expect(page.locator('[data-hero-toggle]')).toBeHidden();
    // The title stays on its first word and the waves stay still.
    await page.waitForTimeout(2000);
    await expect(page.locator('.hero-rotator__word.is-active')).toHaveText('conectan con');
    await expect(page.locator('.home-hero')).not.toHaveClass(/is-ambient/);
    await context.close();
  });

  test('las tres líneas y los pasos de trabajo están presentes', async ({ page }) => {
    await page.goto('/');
    const lines = page.locator('.home-lines .line-card');
    await expect(lines).toHaveCount(3);
    await expect(lines.locator('a.card__link')).toHaveText([/Fragancias/, /Sabores/, /Envases/]);
    await expect(page.locator('.home-steps .home-step')).toHaveCount(3);
  });

  test('las secciones finales comparten el fondo beige', async ({ page }) => {
    await page.goto('/');
    const closing = page.locator('.home-closing');
    test.skip((await closing.count()) === 0, 'Sin artículos ni dirección no hay secciones finales.');
    await expect(closing).toHaveCSS('background-image', /fondo-beige/);
  });

  test('el mapa solo se carga al pedirlo', async ({ page }) => {
    await page.goto('/');
    const map = page.locator('[data-map]');
    test.skip((await map.count()) === 0, 'Sin dirección configurada no hay sección de ubicación.');
    await expect(map.locator('iframe')).toHaveCount(0);
    await page.route(/google\.com\/maps/, (route) => route.fulfill({ status: 200, contentType: 'text/html', body: '<p>Mapa</p>' }));
    await map.locator('[data-map-load]').click();
    await expect(map.locator('iframe')).toHaveAttribute('src', /google\.com\/maps/);
  });
});
