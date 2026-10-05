import { expect, test } from '@playwright/test';
import { addFirstSimpleProduct, checkoutUrl, fillBilling, waitForCheckoutIdle } from './helpers.js';

test.describe('checkout con comprobante de Perú', () => {
  test.beforeEach(async ({ page }) => {
    await addFirstSimpleProduct(page);
    await page.goto(await checkoutUrl(page));
    await waitForCheckoutIdle(page);
  });

  test('muestra solo los campos del comprobante elegido', async ({ page }) => {
    await expect(page.locator('#billing_id_number_field')).toBeVisible();
    await expect(page.locator('#billing_ruc_field')).toBeHidden();

    await page.selectOption('#billing_voucher_type', 'FACTURA');
    await expect(page.locator('#billing_ruc_field')).toBeVisible();
    await expect(page.locator('#billing_id_number_field')).toBeHidden();
  });

  test('rechaza un RUC inválido y una razón social vacía', async ({ page }) => {
    await fillBilling(page);
    await page.selectOption('#billing_voucher_type', 'FACTURA');
    await page.fill('#billing_ruc', '20131312956');
    await waitForCheckoutIdle(page);
    await page.locator('#payment_method_cod').check();
    await page.locator('#place_order').click();

    const errors = page.locator('ul.woocommerce-error');
    await expect(errors).toContainText('El RUC no es válido');
    await expect(errors).toContainText('razón social');
  });

  test('crea un pedido con boleta y DNI y lo muestra en la confirmación', async ({ page }) => {
    await fillBilling(page);
    await page.selectOption('#billing_voucher_type', 'BOLETA');
    await page.selectOption('#billing_id_type', 'DNI');
    await page.fill('#billing_id_number', '12345678');
    await waitForCheckoutIdle(page);
    await page.locator('#payment_method_cod').check();
    await page.locator('#place_order').click();

    await expect(page).toHaveURL(/order-received/, { timeout: 30_000 });
    await expect(page.locator('.frave-peru-document', { hasText: 'Comprobante' })).toContainText('Boleta');
    await expect(page.locator('.frave-peru-document', { hasText: 'DNI' })).toContainText('12345678');
  });
});
