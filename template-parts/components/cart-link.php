<?php
/** Accessible cart link, reused in the site header and WooCommerce fragments. */

if ( ! defined( 'ABSPATH' ) || ! class_exists( 'WooCommerce' ) ) {
	exit;
}

$cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
$cart_label = sprintf(
	_n( 'Carrito, %d producto', 'Carrito, %d productos', $cart_count, 'frave' ),
	$cart_count
);
?>
<a class="header-action header-action--cart" href="<?php echo esc_url( wc_get_cart_url() ); ?>" aria-label="<?php echo esc_attr( $cart_label ); ?>">
	<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M3.5 4h2l2.1 11.2h10.8l2-8H6.2"></path><circle cx="9.5" cy="19" r="1"></circle><circle cx="17.2" cy="19" r="1"></circle></svg>
	<span class="cart-count" aria-hidden="true"><?php echo esc_html( number_format_i18n( $cart_count ) ); ?></span>
</a>
