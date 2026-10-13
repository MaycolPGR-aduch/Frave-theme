<?php
/**
 * Product page: the line it belongs to above the title, the product data (presentations,
 * olfactory family, application…) in view instead of a tab, the quote request, and the
 * description as a section after the summary. Related products close the page.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The product's line and most specific category, for the eyebrow above the title.
 *
 * @return array{line: ?WP_Term, leaf: ?WP_Term}
 */
function frave_product_line_terms( WC_Product $product ): array {
	$terms = get_the_terms( $product->get_id(), 'product_cat' );
	$terms = is_array( $terms ) ? $terms : array();
	$leaf  = null;
	$depth = -1;
	foreach ( $terms as $term ) {
		$level = count( get_ancestors( $term->term_id, 'product_cat', 'taxonomy' ) );
		if ( $level > $depth ) {
			$depth = $level;
			$leaf  = $term;
		}
	}
	$line = null;
	if ( $leaf ) {
		$ancestors = get_ancestors( $leaf->term_id, 'product_cat', 'taxonomy' );
		$top       = $ancestors ? get_term( (int) end( $ancestors ), 'product_cat' ) : $leaf;
		$line      = $top instanceof WP_Term ? $top : null;
	}
	return array(
		'line' => $line,
		'leaf' => $leaf && $line && $leaf->term_id !== $line->term_id ? $leaf : null,
	);
}

/** "FRAGANCIAS · PERFUMERÍA ÁRABE" above the title, linking to the line page and the category. */
function frave_product_eyebrow(): void {
	global $product;
	if ( ! $product instanceof WC_Product ) {
		return;
	}
	$terms = frave_product_line_terms( $product );
	if ( ! $terms['line'] ) {
		return;
	}
	$line_url = frave_line_url( $terms['line']->slug );
	echo '<p class="eyebrow product-eyebrow">';
	printf( '<a href="%1$s">%2$s</a>', esc_url( '' !== $line_url ? $line_url : (string) get_term_link( $terms['line'] ) ), esc_html( $terms['line']->name ) );
	if ( $terms['leaf'] ) {
		$leaf_link = get_term_link( $terms['leaf'] );
		printf( ' <span aria-hidden="true">·</span> <a href="%1$s">%2$s</a>', esc_url( is_wp_error( $leaf_link ) ? '' : $leaf_link ), esc_html( $terms['leaf']->name ) );
	}
	echo '</p>';
}

/**
 * Visible product attributes as label and values, e.g. Presentación: 250 g · 500 g · 1 kg.
 *
 * @return array<int, array{label: string, values: string[]}>
 */
function frave_product_specs( WC_Product $product ): array {
	$specs = array();
	foreach ( $product->get_attributes() as $attribute ) {
		if ( ! $attribute->get_visible() && ! $attribute->get_variation() ) {
			continue;
		}
		if ( $attribute->is_taxonomy() ) {
			// In the attribute's order: sizes from small to large (250 g, 500 g, 1 kg), the rest as sorted in WooCommerce.
			$terms = $attribute->get_terms();
			$terms = is_array( $terms ) ? array_filter( $terms ) : array();
			usort( $terms, static fn( $a, $b ) => array( (int) get_term_meta( $a->term_id, 'order', true ), $a->name ) <=> array( (int) get_term_meta( $b->term_id, 'order', true ), $b->name ) );
			$values = wp_list_pluck( $terms, 'name' );
		} else {
			$values = $attribute->get_options();
		}
		$values = array_values( array_filter( array_map( 'trim', (array) $values ) ) );
		if ( $values ) {
			$specs[] = array(
				'label'  => wc_attribute_label( $attribute->get_name(), $product ),
				'values' => $values,
			);
		}
	}
	return $specs;
}

function frave_product_specs_list(): void {
	global $product;
	if ( ! $product instanceof WC_Product ) {
		return;
	}
	$specs = frave_product_specs( $product );
	if ( ! $specs ) {
		return;
	}
	echo '<dl class="product-specs">';
	foreach ( $specs as $spec ) {
		echo '<div class="product-specs__row">';
		printf( '<dt>%s</dt>', esc_html( $spec['label'] ) );
		echo '<dd><ul class="product-specs__values">';
		foreach ( $spec['values'] as $value ) {
			printf( '<li>%s</li>', esc_html( $value ) );
		}
		echo '</ul></dd></div>';
	}
	echo '</dl>';
}

/** Code (SKU) under the quote request; the categories are already in the eyebrow. */
function frave_product_code(): void {
	global $product;
	if ( $product instanceof WC_Product && '' !== $product->get_sku() ) {
		printf( '<p class="product-code">%1$s <span>%2$s</span></p>', esc_html__( 'Código:', 'frave' ), esc_html( $product->get_sku() ) );
	}
}

/** The description as its own section after the summary, without tabs. */
function frave_product_description(): void {
	global $product;
	if ( ! $product instanceof WC_Product || '' === trim( $product->get_description() ) ) {
		return;
	}
	?>
	<section class="product-description" aria-labelledby="product-description-title">
		<h2 id="product-description-title"><?php esc_html_e( 'Detalles del producto', 'frave' ); ?></h2>
		<div class="product-description__content entry-content"><?php echo wp_kses_post( wpautop( do_shortcode( $product->get_description() ) ) ); ?></div>
	</section>
	<?php
}

/**
 * The data now shows next to the title and the description has its own section; the tabs
 * stay only for reviews when the store sells online.
 */
function frave_product_tabs( array $tabs ): array {
	unset( $tabs['description'], $tabs['additional_information'] );
	return $tabs;
}

function frave_related_products_heading(): string {
	return __( 'También te puede interesar', 'frave' );
}

/** Attributes whose values are sizes (bin/crear-atributos.php). */
function frave_measure_attributes(): array {
	return array( 'pa_presentacion', 'pa_capacidad' );
}

/**
 * Sort key of a size such as "250 g", "1 kg", "50 ml" or "1,5 L": its amount in milligrams or
 * millilitres × 1000, so 250 g comes before 1 kg. Values without a known unit go last.
 */
function frave_measure_order( string $value ): int {
	$units = array(
		'mg' => 1,
		'g'  => 1000,
		'kg' => 1000000,
		'ml' => 1000,
		'cc' => 1000,
		'l'  => 1000000,
		'lt' => 1000000,
		'oz' => 29574,
	);
	if ( ! preg_match( '/^\s*(\d+(?:[.,]\d+)?)\s*(mg|kg|g|ml|cc|lt|l|oz)\b/i', $value, $match ) ) {
		return 2000000000;
	}
	$amount = (float) str_replace( ',', '.', $match[1] );
	return (int) min( 1999999999, round( $amount * $units[ strtolower( $match[2] ) ] ) );
}

/**
 * New sizes get their sort order right away (WooCommerce sorts these attributes by their
 * order), so the catalog filters and the product page list them from small to large.
 */
function frave_measure_term_order( int $term_id, int $tt_id, string $taxonomy ): void {
	if ( in_array( $taxonomy, frave_measure_attributes(), true ) ) {
		$term = get_term( $term_id, $taxonomy );
		if ( $term instanceof WP_Term ) {
			update_term_meta( $term_id, 'order', frave_measure_order( $term->name ) );
		}
	}
}
add_action( 'created_term', 'frave_measure_term_order', 10, 3 );
add_action( 'edited_term', 'frave_measure_term_order', 10, 3 );

function frave_product_page_setup(): void {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	add_action( 'woocommerce_single_product_summary', 'frave_product_eyebrow', 3 );
	add_action( 'woocommerce_single_product_summary', 'frave_product_specs_list', 25 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
	add_action( 'woocommerce_single_product_summary', 'frave_product_code', 40 );
	add_action( 'woocommerce_after_single_product_summary', 'frave_product_description', 5 );
	add_filter( 'woocommerce_product_tabs', 'frave_product_tabs', 98 );
	add_filter( 'woocommerce_product_related_products_heading', 'frave_related_products_heading' );
}
add_action( 'after_setup_theme', 'frave_product_page_setup', 20 );
