<?php
/** Featured top-level WooCommerce categories. */
if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

$categories = get_terms( array(
	'taxonomy'   => 'product_cat',
	'hide_empty' => true,
	'parent'     => 0,
	'number'     => 4,
	'orderby'    => 'menu_order',
	'order'      => 'ASC',
) );
if ( is_wp_error( $categories ) || empty( $categories ) ) {
	return;
}
?>
<section class="section category-section" aria-labelledby="category-heading">
	<div class="container">
		<div class="section-heading">
			<div>
				<p class="eyebrow"><?php esc_html_e( 'EXPLORA FRAVE', 'frave' ); ?></p>
				<h2 id="category-heading"><?php esc_html_e( 'Encuentra lo que imaginas', 'frave' ); ?></h2>
			</div>
			<a class="text-link" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'Ver toda la tienda', 'frave' ); ?><span aria-hidden="true">&rarr;</span></a>
		</div>
		<div class="category-grid">
			<?php foreach ( $categories as $category ) : ?>
				<?php get_template_part( 'template-parts/cards/category', null, array( 'category' => $category ) ); ?>
			<?php endforeach; ?>
		</div>
	</div>
</section>
