import { expect, test } from '@playwright/test';

/** Submit a form skipping the browser's own validation, to exercise the server-side checks. */
async function submitWithoutBrowserValidation(form, buttonName) {
  await form.evaluate((element, name) => {
    element.noValidate = true;
    element.requestSubmit(element.querySelector(`[name="${name}"]`));
  }, buttonName);
}

test.describe('Libro de Reclamaciones', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('/');
    const link = page.locator('a.claims-book-link');
    test.skip((await link.count()) === 0, 'El Libro de Reclamaciones no está configurado en este sitio.');
    await page.goto(await link.getAttribute('href'));
  });

  test('valida en el servidor y conserva lo escrito', async ({ page }) => {
    const form = page.locator('.frave-claims__form');
    await page.fill('#frave-claim-detail', 'Detalle que debe conservarse.');
    await page.fill('#frave-claim-doc-number', '123');
    await page.waitForTimeout(3500); // The anti-spam time trap rejects faster submissions.
    await submitWithoutBrowserValidation(form, 'frave_claim_submit');

    const summary = page.locator('.frave-claims__notice--error');
    await expect(summary).toContainText('El DNI debe tener 8 dígitos.');
    await expect(summary).toContainText('Ingresa tus nombres.');
    await expect(page.locator('#frave-claim-detail')).toHaveValue('Detalle que debe conservarse.');
  });

  test('registra una hoja y muestra la confirmación con su número', async ({ page }) => {
    await page.fill('#frave-claim-first-name', 'Prueba');
    await page.fill('#frave-claim-last-name', 'Automatizada');
    await page.selectOption('#frave-claim-doc-type', 'DNI');
    await page.fill('#frave-claim-doc-number', '12345678');
    await page.fill('#frave-claim-address', 'Av. de Prueba 123, Lima');
    await page.fill('#frave-claim-phone', '999999999');
    await page.fill('#frave-claim-email', 'e2e@example.com');
    await page.fill('#frave-claim-good-description', 'Producto de prueba automatizada');
    await page.fill('#frave-claim-detail', 'Detalle de prueba automatizada.');
    await page.fill('#frave-claim-request', 'Solicitud de prueba.');
    await page.check('#frave-claim-privacy');
    await page.waitForTimeout(3500);
    await page.locator('[name="frave_claim_submit"]').click();

    const confirmation = page.locator('.frave-claims__notice--success');
    await expect(confirmation).toContainText(/hoja N\.º \d{6}-\d{4}/);
    await expect(page.locator('.frave-claim-sheet')).toContainText('DNI 12345678');
  });
});

test.describe('formulario de contacto', () => {
  test('envía un mensaje válido', async ({ page }) => {
    const response = await page.goto('/?pagename=contacto');
    const form = page.locator('.frave-contact form');
    test.skip(!response.ok() || (await form.count()) === 0, 'La página de contacto no está publicada en este sitio.');

    await page.fill('#frave-contact-name', 'Prueba Automatizada');
    await page.fill('#frave-contact-email', 'e2e@example.com');
    await page.selectOption('#frave-contact-subject', 'productos');
    await page.fill('#frave-contact-message', 'Mensaje de prueba automatizada.');
    await page.check('#frave-contact-privacy');
    await page.waitForTimeout(3500);
    await page.locator('[name="frave_contact_submit"]').click();

    await expect(page.locator('.frave-contact .frave-claims__notice--success')).toContainText('Mensaje enviado');
  });
});
