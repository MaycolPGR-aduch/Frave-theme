import { expect } from '@playwright/test';

/** Shop archive: works with plain and pretty permalinks. */
export const SHOP = '/?post_type=product';

/** Collect uncaught JavaScript errors of a page. */
export function collectPageErrors(page) {
  const errors = [];
  page.on('pageerror', (error) => errors.push(error.message));
  return errors;
}

/** PHP errors printed in the page (WP_DEBUG_DISPLAY). */
export async function expectNoPhpErrors(page) {
  await expect(page.locator('body')).not.toContainText(/(Fatal error|Parse error|Warning|Notice|Deprecated):\s/);
}

/** Add the first simple product of the shop through WooCommerce's AJAX button. */
export async function addFirstSimpleProduct(page) {
  await page.goto(SHOP);
  const button = page.locator('ul.products a.ajax_add_to_cart').first();
  await expect(button).toBeVisible();
  await button.click();
  await expect(page.locator('#mini-cart')).toHaveClass(/is-open/);
}

/** Checkout URL taken from the mini-cart, so the test does not depend on page IDs. */
export async function checkoutUrl(page) {
  return page.locator('#mini-cart a.checkout').getAttribute('href');
}

/** Fill the billing fields that every order needs (Peru, guest). */
export async function fillBilling(page) {
  await page.fill('#billing_first_name', 'Prueba');
  await page.fill('#billing_last_name', 'Automatizada');
  await page.evaluate(() => {
    const $ = window.jQuery;
    $('#billing_country').val('PE').trigger('change');
    $('#billing_state').val('LIM').trigger('change');
  });
  await page.fill('#billing_address_1', 'Av. de Prueba 123');
  await page.fill('#billing_city', 'Lima');
  if (await page.locator('#billing_postcode').isVisible()) await page.fill('#billing_postcode', '15001');
  await page.fill('#billing_phone', '999999999');
  await page.fill('#billing_email', 'e2e@example.com');
}

/** Wait for WooCommerce's checkout to finish refreshing the order review. */
export async function waitForCheckoutIdle(page) {
  await expect(page.locator('.woocommerce-checkout-review-order-table')).toBeVisible();
  await expect(page.locator('form.checkout .blockUI')).toHaveCount(0);
}
