<?php
/** Product search bar under the header (store mode, and product pages in catalog mode). */
if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}
?>
<div class="search-strip">
	<div class="container">
		<?php get_template_part( 'template-parts/components/product-search-form' ); ?>
	</div>
</div>
