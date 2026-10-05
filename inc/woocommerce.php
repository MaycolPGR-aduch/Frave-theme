<?php
/** WooCommerce wrappers and catalog defaults. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function frave_woocommerce_setup(): void {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
	add_action( 'woocommerce_before_main_content', 'frave_woocommerce_wrapper_start', 10 );
	add_action( 'woocommerce_after_main_content', 'frave_woocommerce_wrapper_end', 10 );
}
add_action( 'wp', 'frave_woocommerce_setup' );

function frave_woocommerce_wrapper_start(): void {
	?>
	<main id="primary" class="site-main frave-woocommerce">
		<div class="container">
	<?php
}

function frave_woocommerce_wrapper_end(): void {
	?>
		</div>
	</main>
	<?php
}

/** Keep catalog cards aligned with the three-column design on desktop. */
function frave_loop_columns(): int {
	return 3;
}
add_filter( 'loop_shop_columns', 'frave_loop_columns' );

/**
 * WooCommerce skips its own button skin (grey background, purple .alt) when the body has
 * this class, which it normally adds for block themes that style buttons. The theme styles
 * every WooCommerce button, so it opts out the same way instead of fighting the
 * unlayered rules with !important.
 */
function frave_woocommerce_button_styles_class( array $classes ): array {
	if ( class_exists( 'WooCommerce' ) && ! in_array( 'woocommerce-block-theme-has-button-styles', $classes, true ) ) {
		$classes[] = 'woocommerce-block-theme-has-button-styles';
	}
	return $classes;
}
add_filter( 'body_class', 'frave_woocommerce_button_styles_class' );

/**
 * Refresh the header count and mini-cart on every page. With full-page caching the
 * HTML may hold another visitor's cart; fragments replace it from the visitor's session
 * (one request per session, then cached in sessionStorage).
 */
function frave_enqueue_cart_fragments(): void {
	if ( class_exists( 'WooCommerce' ) ) {
		wp_enqueue_script( 'wc-cart-fragments' );
	}
}
add_action( 'wp_enqueue_scripts', 'frave_enqueue_cart_fragments', 20 );

/** The drawer duplicates the cart and checkout pages, so it is left out there. */
function frave_show_mini_cart(): bool {
	return class_exists( 'WooCommerce' ) && ! is_cart() && ! is_checkout();
}

/** Keep the custom header cart link in sync with WooCommerce AJAX fragments. */
function frave_cart_link_fragment( array $fragments ): array {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return $fragments;
	}

	ob_start();
	get_template_part( 'template-parts/components/cart-link' );
	$cart_link = (string) ob_get_clean();

	if ( '' !== trim( $cart_link ) ) {
		$fragments['a.header-action--cart'] = $cart_link;
	}

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'frave_cart_link_fragment' );
