<?php
/** Featured products, managed in WooCommerce (the star in the product list). */

if ( ! defined( 'ABSPATH' ) || ! class_exists( 'WooCommerce' ) ) {
	return;
}

// Respect catalog visibility and the "hide out of stock items" setting, as the shop does.
$frave_query_args = array(
	'status'     => 'publish',
	'limit'      => 4,
	'visibility' => 'catalog',
);
if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
	$frave_query_args['stock_status'] = 'instock';
}

$frave_products = wc_get_products(
	array_merge(
		$frave_query_args,
		array(
			'featured' => true,
			'orderby'  => array(
				'menu_order' => 'ASC',
				'date'       => 'DESC',
			),
		)
	)
);
if ( empty( $frave_products ) ) {
	$frave_products = wc_get_products(
		array_merge(
			$frave_query_args,
			array(
				'orderby' => 'date',
				'order'   => 'DESC',
			)
		)
	);
}
if ( empty( $frave_products ) ) {
	return;
}
?>
<section class="section featured-section" aria-labelledby="featured-heading">
	<div class="container">
		<div class="section-head section-head--split" data-reveal="up">
			<p class="eyebrow"><?php echo esc_html( frave_home_text( 'frave_home_featured_eyebrow' ) ); ?></p>
			<h2 id="featured-heading"><?php echo frave_emphasis( frave_home_text( 'frave_home_featured_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
			<a class="link-arrow section-head__action" href="<?php echo esc_url( frave_catalog_shop_url() ); ?>"><?php esc_html_e( 'Ver todos los productos', 'frave' ); ?><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></a>
		</div>
		<?php // Product cards are styled under .woocommerce, as WooCommerce's own [products] shortcode wraps them. ?>
		<div class="woocommerce">
			<ul class="products frave-products" data-reveal-group>
				<?php
				$frave_original_product = $GLOBALS['product'] ?? null;
				foreach ( $frave_products as $frave_featured ) {
					$frave_post = get_post( $frave_featured->get_id() );
					if ( ! $frave_post instanceof WP_Post ) {
						continue;
					}
					// WooCommerce's product template reads the global post and product; restored after the loop.
					$GLOBALS['post']    = $frave_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
					$GLOBALS['product'] = $frave_featured;
					setup_postdata( $frave_post );
					wc_get_template_part( 'content', 'product' );
				}
				wp_reset_postdata();
				$GLOBALS['product'] = $frave_original_product;
				?>
			</ul>
		</div>
	</div>
</section>
