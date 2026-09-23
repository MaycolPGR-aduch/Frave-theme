<?php
/** Product search bar. */
if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}
?>
<div class="search-strip">
	<div class="container">
		<form role="search" method="get" class="product-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label class="screen-reader-text" for="frave-product-search"><?php esc_html_e( 'Buscar productos', 'frave' ); ?></label>
			<input id="frave-product-search" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Busca fragancias, esencias e insumos', 'frave' ); ?>">
			<input type="hidden" name="post_type" value="product">
			<button class="product-search__submit" type="submit">
				<span><?php esc_html_e( 'Buscar', 'frave' ); ?></span>
				<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="10.8" cy="10.8" r="6.4"></circle><path d="m16 16 4.2 4.2"></path></svg>
			</button>
		</form>
	</div>
</div>
