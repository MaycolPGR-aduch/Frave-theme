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

/** Refresh the custom header count after classic cart updates, only on the cart page. */
function frave_enqueue_cart_fragments(): void {
	if ( class_exists( 'WooCommerce' ) && is_cart() ) {
		wp_enqueue_script( 'wc-cart-fragments' );
	}
}
add_action( 'wp_enqueue_scripts', 'frave_enqueue_cart_fragments', 20 );

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
