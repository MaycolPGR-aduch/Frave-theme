<?php
/**
 * Mini-cart drawer. WooCommerce renders the contents and keeps them fresh through
 * the div.widget_shopping_cart_content fragment. Without JavaScript the drawer stays
 * hidden and the header cart link opens the cart page.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! frave_show_mini_cart() ) {
	return;
}

// A product was added in this request (single product form or ?add-to-cart link).
$open_on_load = did_action( 'woocommerce_add_to_cart' ) > 0;
?>
<div class="mini-cart-backdrop" data-mini-cart-backdrop hidden></div>
<div class="mini-cart" id="mini-cart" role="dialog" aria-modal="true" aria-labelledby="mini-cart-title" data-mini-cart<?php echo $open_on_load ? ' data-open-on-load' : ''; ?> data-added-message="<?php esc_attr_e( 'Producto añadido al carrito.', 'frave' ); ?>">
	<div class="mini-cart__header">
		<h2 class="mini-cart__title" id="mini-cart-title" tabindex="-1"><?php esc_html_e( 'Tu carrito', 'frave' ); ?></h2>
		<button class="mini-cart__close" type="button" data-mini-cart-close>
			<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="m6 6 12 12M18 6 6 18"></path></svg>
			<span class="screen-reader-text"><?php esc_html_e( 'Cerrar carrito', 'frave' ); ?></span>
		</button>
	</div>
	<p class="mini-cart__status" role="status" data-mini-cart-status></p>
	<div class="mini-cart__body">
		<div class="widget_shopping_cart_content"><?php woocommerce_mini_cart(); ?></div>
		<p class="mini-cart__empty-cta">
			<?php frave_button( __( 'Explorar productos', 'frave' ), wc_get_page_permalink( 'shop' ) ); ?>
		</p>
	</div>
</div>
