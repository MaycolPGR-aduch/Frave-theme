<?php
/** Product search form: WordPress search limited to products, rendered by WooCommerce. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form role="search" method="get" class="product-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="frave-product-search"><?php esc_html_e( 'Buscar productos', 'frave' ); ?></label>
	<input id="frave-product-search" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Busca sabores, fragancias y envases', 'frave' ); ?>">
	<input type="hidden" name="post_type" value="product">
	<button class="product-search__submit" type="submit">
		<span><?php esc_html_e( 'Buscar', 'frave' ); ?></span>
		<?php echo frave_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
	</button>
</form>
