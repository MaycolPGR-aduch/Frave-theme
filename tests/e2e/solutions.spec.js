import { expect, test } from '@playwright/test';
import { collectPageErrors, expectNoPhpErrors } from './helpers.js';

// bin/crear-soluciones.php (run by the CI seed) creates /soluciones/ and one page per industry.
const HUB = '/?pagename=soluciones';
const PERFUMERY = '/?pagename=soluciones/perfumeria';

test.describe('soluciones por industria', () => {
  test('la página de Soluciones presenta las cinco industrias', async ({ page }) => {
    await page.goto(HUB);
    await expectNoPhpErrors(page);
    await expect(page.locator('.solution-hero h1')).toContainText('cada industria');
    const cards = page.locator('#industrias .solution-card');
    await expect(cards).toHaveCount(5);
    await cards.filter({ hasText: 'Perfumería' }).getByRole('link').click();
    await expect(page.locator('.solution-hero h1')).toContainText('carácter');
  });

  test('las industrias son paneles: el señalado se abre con su resumen y sus líneas', async ({ page }) => {
    await page.setViewportSize({ width: 1440, height: 900 });
    await page.goto(HUB);
    const panels = page.locator('#industrias .industry-panel');
    await expect(panels.first()).toHaveClass(/is-open/);
    const home = panels.filter({ hasText: 'Cuidado del hogar' });
    await home.hover();
    await expect(home).toHaveClass(/is-open/);
    await expect(panels.first()).not.toHaveClass(/is-open/);
    await expect(home.locator('.industry-panel__lines li')).toHaveText(['Fragancias']);
    // Once it has grown, the open panel is the widest.
    await expect.poll(async () => (await home.boundingBox()).width / (await panels.first().boundingBox()).width).toBeGreaterThan(2);
  });

  test('en el celular un toque abre la industria y el segundo lleva a su página', async ({ browser }) => {
    const context = await browser.newContext({ viewport: { width: 390, height: 844 }, hasTouch: true, isMobile: true });
    const page = await context.newPage();
    await page.goto(HUB);
    const home = page.locator('#industrias .industry-panel').filter({ hasText: 'Cuidado del hogar' });
    await home.scrollIntoViewIfNeeded();
    const url = page.url();
    await home.tap();
    await expect(home).toHaveClass(/is-open/);
    expect(page.url()).toBe(url);
    await home.tap();
    await expect(page.locator('.solution-hero h1')).toContainText('Limpieza');
    await context.close();
  });

  test('una industria reúne lo que ofrece cada línea y su cotización', async ({ page }) => {
    const errors = collectPageErrors(page);
    await page.goto(PERFUMERY);
    await expectNoPhpErrors(page);

    const offer = page.locator('#oferta .line-group');
    await expect(offer).toHaveCount(3);
    // Each card says which line it comes from.
    await expect(offer.first().locator('.line-group__number')).toContainText('FRAGANCIAS');
    await expect(offer.nth(1).locator('.line-group__number')).toContainText('ENVASES');
    await expect(page.locator('.line-applications__list li').first()).toBeVisible();
    await expect(page.locator('.solution-list .solution-card')).toHaveCount(4);
    await expect(page.locator('.site-footer__cta')).toHaveCount(0);

    const quote = page.locator('.solution-hero .btn--primary');
    test.skip((await quote.getAttribute('target')) === '_blank', 'Sin página de contacto: la cotización va por WhatsApp.');
    await quote.click();
    await expect(page).toHaveURL(/linea=perfumeria/);
    await expect(page.locator('#frave-contact-message')).toHaveValue(/Perfumería \(Fragancias\)/);
    expect(errors).toEqual([]);
  });

  test('el formulario de contacto pide empresa, ciudad y línea, con el consentimiento de la Ley 29733', async ({ page }) => {
    await page.goto('/?pagename=contacto&linea=perfumeria-arabe');
    await expect(page.locator('#frave-contact-company')).toBeVisible();
    await expect(page.locator('#frave-contact-city')).toBeVisible();
    // The line comes from the link: Perfumería árabe belongs to Fragancias.
    await expect(page.locator('#frave-contact-line-fragancias')).toBeChecked();
    await expect(page.locator('label[for="frave-contact-privacy"]')).toContainText('Ley N.° 29733');
    await expect(page.locator('.frave-contact__rights')).toContainText('rectificarlos');
  });
});

test.describe('chat de WhatsApp guiado', () => {
  test('arma el mensaje de una cotización y se cierra con Escape', async ({ page }) => {
    await page.goto('/');
    const opener = page.locator('[data-wa-chat-open]');
    test.skip((await opener.count()) === 0, 'Sin número de WhatsApp configurado.');

    await opener.click();
    const chat = page.getByRole('dialog', { name: 'Frave por WhatsApp' });
    await expect(chat).toBeVisible();
    await expect(opener).toHaveAttribute('aria-expanded', 'true');

    await chat.getByRole('button', { name: 'Solicitar una cotización' }).click();
    await chat.getByLabel('Tu nombre (opcional)').fill('Ana');
    await chat.getByLabel('Línea de interés').selectOption('Fragancias');
    await chat.getByLabel('Detalles (opcional)').fill('Para una línea de body splash.');
    const send = chat.getByRole('link', { name: /Continuar en WhatsApp/ });
    const href = decodeURIComponent((await send.getAttribute('href')) ?? '');
    expect(href).toContain('wa.me/');
    expect(href).toContain('Hola, soy Ana, quisiera solicitar una cotización de la línea Fragancias.');
    expect(href).toContain('Para una línea de body splash.');

    await chat.getByRole('button', { name: 'Atrás' }).click();
    await expect(chat.getByRole('button', { name: 'Solicitar una cotización' })).toBeFocused();
    await page.keyboard.press('Escape');
    await expect(chat).toBeHidden();
    await expect(opener).toBeFocused();
  });
});
