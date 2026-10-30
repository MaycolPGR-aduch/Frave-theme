import { expect, test } from '@playwright/test';
import { SHOP } from './helpers.js';

test.describe('móvil', () => {
  test('el menú se abre, retiene el foco y se cierra con Escape', async ({ page }) => {
    await page.goto('/');
    const toggle = page.locator('[data-menu-toggle]');
    await toggle.click();
    await expect(toggle).toHaveAttribute('aria-expanded', 'true');
    await expect(page.locator('[data-primary-navigation] a').first()).toBeFocused();

    await page.keyboard.press('Escape');
    await expect(toggle).toHaveAttribute('aria-expanded', 'false');
    await expect(toggle).toBeFocused();
  });

  test('los submenús se despliegan como acordeón dentro del menú', async ({ page }) => {
    await page.goto('/');
    const submenuToggle = page.locator('[data-primary-navigation] [data-submenu-toggle]').first();
    test.skip((await submenuToggle.count()) === 0, 'El menú de este sitio no tiene submenús.');

    await page.locator('[data-menu-toggle]').click();
    const submenu = submenuToggle.locator('xpath=ancestor::li[1]/ul[contains(@class, "sub-menu")]');
    await expect(submenu).toBeHidden();
    await submenuToggle.click();
    await expect(submenuToggle).toHaveAttribute('aria-expanded', 'true');
    await expect(submenu).toBeVisible();
    await submenuToggle.click();
    await expect(submenu).toBeHidden();
  });

  test('los filtros se abren como panel lateral modal', async ({ page }) => {
    await page.goto(SHOP);
    const button = page.locator('.catalog-toolbar__filters');
    const panel = page.locator('#catalog-filters');
    await expect(panel).toBeHidden();

    await button.click();
    await expect(panel).toBeVisible();
    await expect(panel).toHaveAttribute('role', 'dialog');
    await expect(panel.locator('.catalog-filters__close')).toBeFocused();

    await page.keyboard.press('Escape');
    await expect(panel).toBeHidden();
    await expect(button).toBeFocused();
  });

  test('los pasos de «Cómo trabajamos» son un acordeón', async ({ page }) => {
    await page.goto('/?pagename=servicios');
    const second = page.getByRole('button', { name: /Proponemos/ });
    await expect(second).toHaveAttribute('aria-expanded', 'false');
    await second.click();
    await expect(second).toHaveAttribute('aria-expanded', 'true');
    // On phones the panel opens right under its button.
    const button = await second.boundingBox();
    const panel = await page.locator('#paso-proponemos').boundingBox();
    expect(panel.y).toBeGreaterThan(button.y);
    expect(panel.y - (button.y + button.height)).toBeLessThan(40);
  });

  test('el chat de WhatsApp cabe en la pantalla', async ({ page }) => {
    await page.goto('/');
    const opener = page.locator('[data-wa-chat-open]');
    test.skip((await opener.count()) === 0, 'Sin número de WhatsApp configurado.');
    await opener.click();
    const chat = page.locator('[data-wa-chat]');
    await expect(chat).toBeVisible();
    const box = await chat.boundingBox();
    const viewport = page.viewportSize();
    expect(box.x).toBeGreaterThanOrEqual(0);
    expect(box.x + box.width).toBeLessThanOrEqual(viewport.width);
    expect(box.y).toBeGreaterThanOrEqual(0);
  });

  test('la cabecera de una categoría muestra su imagen bajo el título', async ({ page }) => {
    await page.goto('/?product_cat=perfumeria-disenador');
    const media = page.locator('.page-hero__media');
    await expect(media).toBeVisible();
    const title = await page.locator('.page-hero h1').boundingBox();
    const image = await media.boundingBox();
    const lead = await page.locator('.page-hero__lead').boundingBox();
    expect(image.y).toBeGreaterThan(title.y + title.height - 1);
    expect(lead.y).toBeGreaterThan(image.y + image.height - 1);
  });

  test('la primera pantalla muestra el texto, los botones lado a lado y la imagen de la cabecera', async ({ page }) => {
    for (const url of ['/?pagename=soluciones', '/?pagename=nosotros', '/?pagename=fragancias', '/?pagename=soluciones/perfumeria']) {
      await page.goto(url);
      const hero = page.locator('.company-hero, .line-hero').first();
      const viewport = page.viewportSize();
      // The copy and its buttons fit, and the picture already shows (the store's header,
      // with cart and account, is taller: the picture may continue below the fold).
      const row = await hero.locator('.btn-row').boundingBox();
      expect(row.y + row.height, url).toBeLessThanOrEqual(viewport.height);
      const picture = await hero.locator('.hero-visual, .word-cloud, .line-hero__media').first().boundingBox();
      expect(picture.y, url).toBeLessThan(viewport.height - 120);
      const buttons = hero.locator('.btn-row .btn');
      if ((await buttons.count()) > 1) {
        const [first, second] = [await buttons.nth(0).boundingBox(), await buttons.nth(1).boundingBox()];
        expect(Math.abs(first.y - second.y), url).toBeLessThan(2);
      }
    }
  });

  test('una descripción larga muestra «Leer más» y se despliega', async ({ page }) => {
    await page.goto('/?product_cat=perfumeria-disenador');
    const more = page.locator('.page-hero .clamp-toggle');
    await expect(more).toBeVisible();
    await expect(more).toHaveText('Leer más');
    await more.click();
    await expect(more).toHaveText('Leer menos');
    await expect(more).toHaveAttribute('aria-expanded', 'true');
  });

  test('la página no se desborda horizontalmente', async ({ page }) => {
    for (const path of ['/', SHOP, '/?pagename=contacto', '/?pagename=fragancias', '/?pagename=nosotros', '/?pagename=servicios', '/?pagename=soluciones', '/?pagename=soluciones/perfumeria']) {
      await page.goto(path);
      const overflow = await page.evaluate(() => document.documentElement.scrollWidth - window.innerWidth);
      expect(overflow, path).toBeLessThanOrEqual(0);
    }
  });
});

test.describe('móvil: redes sociales', () => {
  test('el menú termina con los logos de las redes', async ({ page }) => {
    await page.goto('/');
    test.skip((await page.locator('.social-links--footer .social-link').count()) === 0, 'Este sitio no tiene redes configuradas.');
    await expect(page.locator('.social-links--header')).toBeHidden();
    await page.locator('[data-menu-toggle]').click();
    const links = page.locator('.social-links--menu .social-link');
    await expect(links.first()).toBeVisible();
    await expect(page.locator('.social-links--menu .social-links__list')).toHaveAccessibleName('Síguenos');
  });
});
