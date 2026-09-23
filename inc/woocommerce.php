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
