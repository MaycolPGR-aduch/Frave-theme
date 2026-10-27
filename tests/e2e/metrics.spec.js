import { expect, test } from '@playwright/test';

// Contact measurement of the frave-peru plugin (includes/metrics.php): clicks are sent with
// navigator.sendBeacon to frave-peru/v1/evento, without cookies.
const EVENT = /frave-peru(\/|%2F)v1(\/|%2F)evento/;

/** The events the page sends, with the status the site answered. */
async function captureEvents(page) {
  const events = [];
  await page.route(EVENT, async (route) => {
    const response = await route.fetch();
    events.push({ ...JSON.parse(route.request().postData() || '{}'), status: response.status() });
    await route.fulfill({ response });
  });
  return events;
}

test.describe('medición de contactos', () => {
  test('un clic en WhatsApp se cuenta con su lugar y su página, sin cookies', async ({ page, context }) => {
    await context.route(/wa\.me|whatsapp\.com/, (route) => route.abort());
    const events = await captureEvents(page);
    await page.goto('/?pagename=fragancias');
    const whatsapp = page.locator('[data-frave-whatsapp="floating"]');
    test.skip((await whatsapp.count()) === 0, 'Sin número de WhatsApp configurado.');

    await whatsapp.click();
    await expect.poll(() => events.length).toBe(1);
    expect(events[0]).toMatchObject({ type: 'whatsapp', source: 'floating', status: 204 });
    expect(events[0].page).toContain('fragancias');
    expect(await context.cookies()).toEqual([]);
  });

  test('un clic en «Solicitar cotización» cuenta como cotización', async ({ page }) => {
    const events = await captureEvents(page);
    await page.goto('/?pagename=fragancias');
    const quote = page.locator('main a[href*="linea="]').first();
    test.skip((await quote.count()) === 0, 'Sin página de contacto: la cotización va por WhatsApp.');
    // Stay on the page to read the event (it is sent before leaving anyway).
    await page.evaluate(() => document.addEventListener('click', (event) => event.preventDefault()));
    await quote.click();
    await expect.poll(() => events.length).toBe(1);
    expect(events[0]).toMatchObject({ type: 'quote', source: 'main', status: 204 });
  });

  test('la ruta solo acepta clics de los tipos conocidos', async ({ request }) => {
    const route = '/?rest_route=/frave-peru/v1/evento';
    expect((await request.post(route, { data: JSON.stringify({ type: 'form' }) })).status()).toBe(400);
    expect((await request.post(route, { data: JSON.stringify({ type: 'visita' }) })).status()).toBe(400);
  });
});
