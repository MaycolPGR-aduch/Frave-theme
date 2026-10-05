import { expect, test } from '@playwright/test';
import { SHOP, addFirstSimpleProduct, collectPageErrors } from './helpers.js';

test.describe('mini-carrito', () => {
  test('se abre al añadir por AJAX y permite quitar el producto', async ({ page }) => {
    const errors = collectPageErrors(page);
    await addFirstSimpleProduct(page);

    const drawer = page.locator('#mini-cart');
    await expect(drawer.locator('[data-mini-cart-status]')).toHaveText('Producto añadido al carrito.');
    await expect(drawer.locator('.woocommerce-mini-cart-item')).toHaveCount(1);
    await expect(page.locator('.cart-count')).toHaveText('1');
    await expect(drawer.locator('[data-mini-cart-close]')).toBeFocused();

    // Focus returns to the control that opened the drawer: here, the add-to-cart button.
    await page.keyboard.press('Escape');
    await expect(drawer).not.toHaveClass(/is-open/);
    await expect(page.locator('ul.products a.ajax_add_to_cart').first()).toBeFocused();

    const cartLink = page.locator('a.header-action--cart');
    await cartLink.click();
    await expect(drawer).toHaveClass(/is-open/);
    await page.keyboard.press('Escape');
    await expect(cartLink).toBeFocused();

    await cartLink.click();
    await expect(drawer).toHaveClass(/is-open/);
    await drawer.locator('a.remove').click();
    await expect(drawer.locator('.woocommerce-mini-cart__empty-message')).toBeVisible();
    await expect(page.locator('.cart-count')).toHaveText('0');
    expect(errors).toEqual([]);
  });

  test('un producto variable se añade desde su ficha y el cajón aparece abierto', async ({ page }) => {
    await page.goto(SHOP);
    const card = page.locator('ul.products li.product', { has: page.locator('a.product_type_variable') }).first();
    await card.locator('a.woocommerce-LoopProduct-link').click();

    const selects = page.locator('form.variations_form select');
    const count = await selects.count();
    for (let i = 0; i < count; i += 1) {
      const value = await selects.nth(i).locator('option:not([value=""])').first().getAttribute('value');
      await selects.nth(i).selectOption(value);
    }
    const add = page.locator('form.variations_form .single_add_to_cart_button');
    await expect(add).not.toHaveClass(/disabled/);
    await add.click();

    await expect(page.locator('#mini-cart')).toHaveClass(/is-open/);
    await expect(page.locator('#mini-cart .woocommerce-mini-cart-item')).toHaveCount(1);
  });
});
