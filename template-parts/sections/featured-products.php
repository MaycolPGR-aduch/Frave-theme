<?php
/** Featured products, managed in WooCommerce. */
if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

// Respect catalog visibility and the "hide out of stock items" setting, as the shop does.
$query_args = array(
	'status'     => 'publish',
	'limit'      => 4,
	'visibility' => 'catalog',
);
if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
	$query_args['stock_status'] = 'instock';
}

$products = wc_get_products( array_merge( $query_args, array(
	'featured' => true,
	'orderby'  => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
) ) );

if ( empty( $products ) ) {
	$products = wc_get_products( array_merge( $query_args, array(
		'orderby' => 'date',
		'order'   => 'DESC',
	) ) );
}
if ( empty( $products ) ) {
	return;
}
?>
<section class="section featured-section" aria-labelledby="featured-heading">
	<div class="container">
		<div class="section-heading" data-reveal="up">
			<div>
				<p class="eyebrow"><?php esc_html_e( 'SELECCIÓN FRAVE', 'frave' ); ?></p>
				<h2 id="featured-heading"><?php esc_html_e( 'Inspírate para crear', 'frave' ); ?></h2>
			</div>
			<a class="text-link" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'Ver todos los productos', 'frave' ); ?><span aria-hidden="true">&rarr;</span></a>
		</div>
		<ul class="products frave-products" data-reveal-group>
			<?php
			$original_product = isset( $GLOBALS['product'] ) ? $GLOBALS['product'] : null;
			foreach ( $products as $featured_product ) :
				$featured_post = get_post( $featured_product->get_id() );
				if ( ! $featured_post instanceof WP_Post ) {
					continue;
				}
				$GLOBALS['post']    = $featured_post;
				$GLOBALS['product'] = $featured_product;
				setup_postdata( $featured_post );
				wc_get_template_part( 'content', 'product' );
			endforeach;
			wp_reset_postdata();
			$GLOBALS['product'] = $original_product;
			?>
		</ul>
	</div>
</section>
