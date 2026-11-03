<?php
/**
 * The catalog page (/catalogo/) as a summary of everything Frave offers, before the
 * products: the hero gains shortcuts, then a bar of anchors (wide screens),
 * the index of the lines (every category and type, with its collections and products),
 * the collections, the perfumery tops, «¿Qué fabricas?» (applications by industry),
 * «Busca por aroma» (olfactory families and featured notes), the products with their
 * filters and a closing call to action. Built from what the site already has, so it fills
 * itself as categories, collections, references and products are added.
 *
 * Only on the first page of the catalog without a search or filters; a filtered or
 * searched catalog keeps the plain listing.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** The catalog page itself: no search, filters or later pages. */
function frave_is_catalog_overview(): bool {
	return function_exists( 'is_shop' ) && is_shop() && ! is_search() && ! is_paged() && 0 === frave_catalog_active_filter_count();
}

/**
 * The product lines: the top-level categories with a line page (Fragancias, Sabores,
 * Envases), or, while none has one, every top-level category but WooCommerce's
 * «Sin categorizar». Other top-level categories are not lines.
 *
 * @return WP_Term[]
 */
function frave_catalog_lines(): array {
	$lines = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'parent'     => 0,
			'hide_empty' => false,
			'exclude'    => array( (int) get_option( 'default_product_cat' ) ),
		)
	);
	if ( is_wp_error( $lines ) ) {
		return array();
	}
	$with_page = array_values( array_filter( $lines, static fn( WP_Term $line ): bool => null !== frave_line_page( $line->slug ) ) );
	return $with_page ? $with_page : $lines;
}

/**
 * Children of a category, in the catalog order.
 *
 * @return WP_Term[]
 */
function frave_catalog_children( WP_Term $term ): array {
	$children = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'parent'     => $term->term_id,
			'hide_empty' => false,
		)
	);
	return is_wp_error( $children ) ? array() : $children;
}

/**
 * What the hero adds on the catalog page: shortcuts (escaped HTML). No figures: Frave's
 * range is open-ended, so the site never counts lines, categories, collections or notes.
 */
function frave_catalog_hero_extra(): string {
	$links = array();
	foreach ( frave_catalog_lines() as $line ) {
		$url = frave_line_url( $line->slug );
		$url = '' !== $url ? $url : frave_term_detail_url( $line );
		if ( '' !== $url ) {
			$links[] = array( $line->name, $url );
		}
	}
	if ( function_exists( 'frave_collections' ) && frave_collections() ) {
		$links[] = array( __( 'Colecciones', 'frave' ), '#catalogo-colecciones' );
	}
	if ( function_exists( 'frave_top_categories' ) && frave_top_categories() ) {
		$links[] = array( __( 'Top de perfumería', 'frave' ), '#catalogo-top' );
	}
	$links[] = array( __( 'Por aroma', 'frave' ), '#catalogo-aromas' );

	ob_start();
	?>
	<nav class="catalog-shortcuts" aria-label="<?php esc_attr_e( 'Accesos del catálogo', 'frave' ); ?>">
		<?php foreach ( $links as list( $frave_label, $frave_url ) ) : ?>
			<a class="catalog-shortcuts__link" href="<?php echo esc_url( $frave_url ); ?>"><?php echo esc_html( $frave_label ); ?></a>
		<?php endforeach; ?>
	</nav>
	<?php
	return (string) ob_get_clean();
}

/** The summary before the products (priority 0, before the filter layout opens). */
function frave_catalog_overview(): void {
	if ( ! frave_is_catalog_overview() ) {
		return;
	}
	get_template_part( 'template-parts/catalog/overview' );
}
add_action( 'woocommerce_before_shop_loop', 'frave_catalog_overview', 0 );
add_action( 'woocommerce_no_products_found', 'frave_catalog_overview', 0 );

/** «¿No encuentras lo que buscas?» after the products, instead of the footer's call to action. */
function frave_catalog_closing(): void {
	if ( frave_is_catalog_overview() ) {
		get_template_part( 'template-parts/catalog/closing' );
	}
}
add_action( 'woocommerce_after_main_content', 'frave_catalog_closing', 5 );

function frave_catalog_hide_footer_cta( bool $show ): bool {
	return frave_is_catalog_overview() ? false : $show;
}
add_filter( 'frave_show_footer_cta', 'frave_catalog_hide_footer_cta' );
