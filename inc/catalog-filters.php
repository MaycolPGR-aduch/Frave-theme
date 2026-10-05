<?php
/**
 * Catalog filters for the shop, product categories, attribute archives and product search.
 *
 * Filters use WooCommerce's own query parameters, so WooCommerce runs the queries:
 * - filter_{attribute} + query_type_{attribute}: global attributes (Productos → Atributos),
 *   rendered with WooCommerce's layered nav widget, which counts products for the current
 *   filters and hides empty terms. New attributes appear automatically.
 * - min_price / max_price, rating_filter and orderby.
 * Two filters WooCommerce's classic catalog lacks are added here:
 * - filter_stock_status=instock (same name as WooCommerce's block filters): only in stock.
 * - on_sale=1: only products on sale.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function frave_is_filterable_catalog(): bool {
	return class_exists( 'WooCommerce' ) && ( is_shop() || is_product_taxonomy() );
}

/* ---------- Custom filters: in stock and on sale ---------- */

function frave_catalog_in_stock_only(): bool {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only catalog filter.
	return isset( $_GET['filter_stock_status'] ) && 'instock' === sanitize_key( wp_unslash( $_GET['filter_stock_status'] ) );
}

function frave_catalog_on_sale_only(): bool {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only catalog filter.
	return isset( $_GET['on_sale'] ) && '1' === sanitize_key( wp_unslash( $_GET['on_sale'] ) );
}

/** The stock filter is pointless when the store already hides out-of-stock products. */
function frave_catalog_offers_stock_filter(): bool {
	return 'yes' !== get_option( 'woocommerce_hide_out_of_stock_items' );
}

/** IDs of products on sale (parents included for variations); WooCommerce caches the list. */
function frave_catalog_sale_ids(): array {
	return array_map( 'absint', wc_get_product_ids_on_sale() );
}

function frave_catalog_filter_query( WP_Query $query ): void {
	if ( frave_catalog_in_stock_only() ) {
		// Same taxonomy WooCommerce uses for its "hide out of stock" setting.
		$tax_query   = (array) $query->get( 'tax_query' );
		$tax_query[] = array(
			'taxonomy' => 'product_visibility',
			'field'    => 'name',
			'terms'    => array( 'outofstock' ),
			'operator' => 'NOT IN',
		);
		$query->set( 'tax_query', $tax_query );
	}

	if ( frave_catalog_on_sale_only() ) {
		$ids     = frave_catalog_sale_ids();
		$current = array_filter( (array) $query->get( 'post__in' ) );
		$ids     = $current ? array_values( array_intersect( $current, $ids ) ) : $ids;
		$query->set( 'post__in', $ids ? $ids : array( 0 ) );
	}
}
add_action( 'woocommerce_product_query', 'frave_catalog_filter_query' );

/**
 * Price bounds of the current request, adjusted like WooCommerce's own price filter when
 * prices are stored without tax but displayed with it.
 *
 * @return array{min: float, max: float}|null
 */
function frave_catalog_price_bounds(): ?array {
	$args = frave_catalog_active_args();
	if ( ! isset( $args['min_price'] ) && ! isset( $args['max_price'] ) ) {
		return null;
	}
	$min = isset( $args['min_price'] ) ? (float) $args['min_price'] : 0.0;
	$max = isset( $args['max_price'] ) ? (float) $args['max_price'] : (float) PHP_INT_MAX;

	if ( wc_tax_enabled() && 'incl' === get_option( 'woocommerce_tax_display_shop' ) && ! wc_prices_include_tax() ) {
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- WooCommerce's own hook, applied as its price filter does.
		$rates = WC_Tax::get_rates( apply_filters( 'woocommerce_price_filter_widget_tax_class', '' ) );
		if ( $rates ) {
			$min -= WC_Tax::get_tax_total( WC_Tax::calc_inclusive_tax( $min, $rates ) );
			$max -= WC_Tax::get_tax_total( WC_Tax::calc_inclusive_tax( $max, $rates ) );
		}
	}
	return array(
		'min' => $min,
		'max' => $max,
	);
}

/**
 * Attribute counts start from the main tax query, so the stock filter is already included.
 * WooCommerce leaves out the price filter and the sale restriction is ours: add both, or
 * counts would promise products that the listing then hides.
 */
function frave_catalog_filter_counts_query( array $query ): array {
	global $wpdb;
	if ( frave_catalog_on_sale_only() ) {
		$ids             = frave_catalog_sale_ids();
		$query['where'] .= $ids ? " AND {$wpdb->posts}.ID IN (" . implode( ',', $ids ) . ')' : ' AND 1=0';
	}
	$price = frave_catalog_price_bounds();
	if ( $price ) {
		// Same overlap test as WC_Query::price_filter_post_clauses().
		$query['where'] .= $wpdb->prepare(
			" AND {$wpdb->posts}.ID IN ( SELECT product_id FROM {$wpdb->wc_product_meta_lookup} WHERE NOT ( %f < min_price OR %f > max_price ) )",
			$price['max'],
			$price['min']
		);
	}
	return $query;
}
add_filter( 'woocommerce_get_filtered_term_product_counts_query', 'frave_catalog_filter_counts_query' );

/* ---------- URLs ---------- */

/** Query args of the current catalog view that other filter links must keep. */
function frave_catalog_active_args(): array {
	$args = array();
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only catalog filter.
	foreach ( wp_unslash( $_GET ) as $key => $value ) {
		if ( ! is_string( $key ) || ! is_scalar( $value ) || '' === (string) $value ) {
			continue;
		}
		if ( preg_match( '/^(filter_|query_type_)[a-z0-9_-]+$/', $key ) || in_array( $key, array( 'min_price', 'max_price', 'rating_filter', 'orderby', 'on_sale' ), true ) ) {
			$args[ $key ] = wc_clean( (string) $value );
		}
	}
	return $args;
}

/** Number of filters the visitor applied (sorting is not a filter). */
function frave_catalog_active_filter_count(): int {
	return count( frave_catalog_active_filters() );
}

/** The current archive without filters, sorting or pagination; keeps a product search. */
function frave_catalog_base_url(): string {
	if ( is_search() ) {
		return add_query_arg(
			array(
				's'         => rawurlencode( htmlspecialchars_decode( get_search_query() ) ),
				'post_type' => 'product',
			),
			home_url( '/' )
		);
	}
	if ( is_product_taxonomy() ) {
		$link = get_term_link( get_queried_object() );
		return is_wp_error( $link ) ? frave_catalog_shop_url() : $link;
	}
	return frave_catalog_shop_url();
}

/**
 * Canonical shop archive URL: /tienda/ with pretty permalinks, ?post_type=product with plain
 * ones (the shop page's ?page_id= URL redirects there and would cost an extra request).
 */
function frave_catalog_shop_url(): string {
	$link = get_post_type_archive_link( 'product' );
	return $link ? $link : wc_get_page_permalink( 'shop' );
}

/**
 * Current catalog URL with $changes applied; a null value removes that argument.
 * Pagination is dropped because a new filter changes the result pages.
 */
function frave_catalog_url( array $changes = array() ): string {
	$args = array_merge( frave_catalog_active_args(), $changes );
	$args = array_filter( $args, static fn( $value ) => null !== $value && '' !== $value );
	return add_query_arg( array_map( 'rawurlencode', $args ), frave_catalog_base_url() );
}

/** Keep our filters in the links built by WooCommerce's widgets (attributes, rating). */
function frave_catalog_widget_url( string $link ): string {
	if ( frave_catalog_in_stock_only() ) {
		$link = add_query_arg( 'filter_stock_status', 'instock', $link );
	}
	if ( frave_catalog_on_sale_only() ) {
		$link = add_query_arg( 'on_sale', '1', $link );
	}
	return $link;
}
add_filter( 'woocommerce_widget_get_current_page_url', 'frave_catalog_widget_url' );

/**
 * Empty or inverted price bounds (e.g. a price form sent without JavaScript) would make
 * WooCommerce filter by 0; redirect to a clean URL instead.
 */
function frave_catalog_normalize_price_args(): void {
	if ( ! frave_is_filterable_catalog() ) {
		return;
	}
	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only catalog filter.
	$min = isset( $_GET['min_price'] ) ? trim( sanitize_text_field( wp_unslash( $_GET['min_price'] ) ) ) : null;
	$max = isset( $_GET['max_price'] ) ? trim( sanitize_text_field( wp_unslash( $_GET['max_price'] ) ) ) : null;
	// phpcs:enable
	if ( null === $min && null === $max ) {
		return;
	}

	$clean = static fn( $value ) => null === $value || '' === $value || ! is_numeric( $value ) ? null : (string) max( 0, (float) $value );
	$new   = array(
		'min_price' => $clean( $min ),
		'max_price' => $clean( $max ),
	);
	if ( null !== $new['min_price'] && null !== $new['max_price'] && (float) $new['min_price'] > (float) $new['max_price'] ) {
		$new = array(
			'min_price' => $new['max_price'],
			'max_price' => $new['min_price'],
		);
	}

	if ( $new['min_price'] !== $min || $new['max_price'] !== $max ) {
		wp_safe_redirect( frave_catalog_url( $new ) );
		exit;
	}
}
add_action( 'template_redirect', 'frave_catalog_normalize_price_args', 5 );

/** Filtered listings are near-duplicates of the catalog: let crawlers follow, not index. */
function frave_catalog_robots( array $robots ): array {
	if ( frave_is_filterable_catalog() && frave_catalog_active_filter_count() > 0 ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}
	return $robots;
}
add_filter( 'wp_robots', 'frave_catalog_robots' );

/* ---------- Data for the panel ---------- */

/**
 * Price range of the products in the current archive, ignoring the price filter itself.
 * Same query as WooCommerce's price filter widget, which keeps it protected.
 *
 * @return array{min: float, max: float}|null
 */
function frave_catalog_price_range(): ?array {
	global $wpdb;

	$main_query = WC()->query->get_main_query();
	if ( ! $main_query ) {
		return null;
	}
	$args       = $main_query->query_vars;
	$tax_query  = isset( $args['tax_query'] ) ? (array) $args['tax_query'] : array();
	$meta_query = isset( $args['meta_query'] ) ? (array) $args['meta_query'] : array();

	if ( ! is_post_type_archive( 'product' ) && ! empty( $args['taxonomy'] ) && ! empty( $args['term'] ) ) {
		$tax_query[] = WC()->query->get_main_tax_query();
	}
	foreach ( $meta_query + $tax_query as $key => $clause ) {
		if ( ! empty( $clause['price_filter'] ) || ! empty( $clause['rating_filter'] ) ) {
			unset( $meta_query[ $key ] );
		}
	}

	$meta_sql   = ( new WP_Meta_Query( $meta_query ) )->get_sql( 'post', $wpdb->posts, 'ID' );
	$tax_sql    = ( new WP_Tax_Query( $tax_query ) )->get_sql( $wpdb->posts, 'ID' );
	$search     = WC_Query::get_main_search_query_sql();
	$search_sql = $search ? ' AND ' . $search : '';
	$sale_sql   = '';
	if ( frave_catalog_on_sale_only() ) {
		$ids      = frave_catalog_sale_ids();
		$sale_sql = $ids ? " AND {$wpdb->posts}.ID IN (" . implode( ',', $ids ) . ')' : ' AND 1=0';
	}

	// phpcs:disable WordPress.DB.PreparedSQL, WordPress.DB.DirectDatabaseQuery -- built from WP_Tax_Query/WP_Meta_Query SQL, as in WooCommerce.
	$row = $wpdb->get_row(
		"SELECT MIN( min_price ) AS min_price, MAX( max_price ) AS max_price
		FROM {$wpdb->wc_product_meta_lookup}
		WHERE product_id IN (
			SELECT ID FROM {$wpdb->posts} {$tax_sql['join']} {$meta_sql['join']}
			WHERE {$wpdb->posts}.post_type = 'product' AND {$wpdb->posts}.post_status = 'publish'
			{$tax_sql['where']} {$meta_sql['where']} {$search_sql} {$sale_sql}
		)"
	);
	// phpcs:enable

	if ( ! $row || null === $row->min_price ) {
		return null;
	}
	return array(
		'min' => floor( (float) $row->min_price ),
		'max' => ceil( (float) $row->max_price ),
	);
}

/**
 * Category tree for the panel: top-level categories, with the children of the current
 * category and of its ancestors expanded.
 *
 * @return array<int, array{term: WP_Term, current: bool, children: array}>
 */
function frave_catalog_category_tree( int $parent_id = 0 ): array {
	$current   = is_product_category() ? get_queried_object() : null;
	$current   = $current instanceof WP_Term ? $current : null;
	$ancestors = $current ? array_merge( array( $current->term_id ), get_ancestors( $current->term_id, 'product_cat', 'taxonomy' ) ) : array();

	$terms = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'parent'     => $parent_id,
			'hide_empty' => true,
			'exclude'    => array( absint( get_option( 'default_product_cat' ) ) ),
			'orderby'    => 'menu_order',
			'order'      => 'ASC',
		)
	);
	if ( is_wp_error( $terms ) ) {
		return array();
	}

	$tree = array();
	foreach ( $terms as $term ) {
		$tree[] = array(
			'term'     => $term,
			'current'  => $current && $current->term_id === $term->term_id,
			'children' => in_array( $term->term_id, $ancestors, true ) ? frave_catalog_category_tree( $term->term_id ) : array(),
		);
	}
	return $tree;
}

/**
 * Active filters as removable chips.
 *
 * @return array<int, array{label: string, url: string}>
 */
function frave_catalog_active_filters(): array {
	$filters = array();

	foreach ( WC_Query::get_layered_nav_chosen_attributes() as $taxonomy => $data ) {
		$slug      = wc_attribute_taxonomy_slug( $taxonomy );
		$attribute = wc_attribute_label( $taxonomy );
		foreach ( $data['terms'] as $term_slug ) {
			$term = get_term_by( 'slug', $term_slug, $taxonomy );
			if ( ! $term ) {
				continue;
			}
			$remaining = array_diff( $data['terms'], array( $term_slug ) );
			$filters[] = array(
				'label' => $attribute . ': ' . $term->name,
				'url'   => frave_catalog_url(
					array(
						'filter_' . $slug     => $remaining ? implode( ',', $remaining ) : null,
						'query_type_' . $slug => count( $remaining ) > 1 && 'or' === $data['query_type'] ? 'or' : null,
					)
				),
			);
		}
	}

	$args = frave_catalog_active_args();
	if ( isset( $args['min_price'] ) || isset( $args['max_price'] ) ) {
		$price = static fn( $value ) => html_entity_decode( wp_strip_all_tags( wc_price( (float) $value, array( 'decimals' => 0 ) ) ) );
		if ( isset( $args['min_price'], $args['max_price'] ) ) {
			/* translators: 1: minimum price, 2: maximum price. */
			$label = sprintf( __( 'Precio: %1$s – %2$s', 'frave' ), $price( $args['min_price'] ), $price( $args['max_price'] ) );
		} elseif ( isset( $args['min_price'] ) ) {
			/* translators: %s: minimum price. */
			$label = sprintf( __( 'Precio: desde %s', 'frave' ), $price( $args['min_price'] ) );
		} else {
			/* translators: %s: maximum price. */
			$label = sprintf( __( 'Precio: hasta %s', 'frave' ), $price( $args['max_price'] ) );
		}
		$filters[] = array(
			'label' => $label,
			'url'   => frave_catalog_url(
				array(
					'min_price' => null,
					'max_price' => null,
				)
			),
		);
	}

	if ( isset( $args['rating_filter'] ) ) {
		$ratings = array_filter( array_map( 'absint', explode( ',', $args['rating_filter'] ) ) );
		foreach ( $ratings as $rating ) {
			$remaining = array_diff( $ratings, array( $rating ) );
			$filters[] = array(
				/* translators: %d: number of stars. */
				'label' => sprintf( _n( 'Valoración: %d estrella', 'Valoración: %d estrellas', $rating, 'frave' ), $rating ),
				'url'   => frave_catalog_url( array( 'rating_filter' => $remaining ? implode( ',', $remaining ) : null ) ),
			);
		}
	}

	if ( frave_catalog_in_stock_only() ) {
		$filters[] = array(
			'label' => __( 'Solo disponibles', 'frave' ),
			'url'   => frave_catalog_url( array( 'filter_stock_status' => null ) ),
		);
	}
	if ( frave_catalog_on_sale_only() ) {
		$filters[] = array(
			'label' => __( 'En oferta', 'frave' ),
			'url'   => frave_catalog_url( array( 'on_sale' => null ) ),
		);
	}

	return $filters;
}

/** URL without any filter; keeps sorting and the product search. */
function frave_catalog_clear_url(): string {
	$args = frave_catalog_active_args();
	return isset( $args['orderby'] ) ? add_query_arg( 'orderby', rawurlencode( $args['orderby'] ), frave_catalog_base_url() ) : frave_catalog_base_url();
}

/**
 * Attributes offered as filters: every global attribute, in WooCommerce's order.
 * Filter to exclude or reorder, e.g. to hide an attribute that is only informative.
 *
 * @return array<int, object> Attribute taxonomies as returned by wc_get_attribute_taxonomies().
 */
function frave_catalog_filter_attributes(): array {
	return (array) apply_filters( 'frave_catalog_filter_attributes', array_values( wc_get_attribute_taxonomies() ) );
}

/**
 * Attribute option as a checkbox-like link: WooCommerce builds the URL and count; the
 * markup adds a visible state and a screen reader label for the selected option.
 */
function frave_catalog_layered_nav_term_html( string $term_html, $term, $link, $count ): string {
	if ( ! $term instanceof WP_Term || ! frave_is_filterable_catalog() ) {
		return $term_html;
	}
	$chosen   = WC_Query::get_layered_nav_chosen_attributes();
	$selected = in_array( $term->slug, $chosen[ $term->taxonomy ]['terms'] ?? array(), true );

	return frave_catalog_option_html( $term->name, $link ? (string) $link : '', (int) $count, $selected );
}
add_filter( 'woocommerce_layered_nav_term_html', 'frave_catalog_layered_nav_term_html', 10, 4 );

function frave_catalog_option_html( string $label, string $url, ?int $count, bool $selected ): string {
	$inner = '<span class="catalog-option__box" aria-hidden="true"></span>'
		. '<span class="catalog-option__label">' . esc_html( $label ) . '</span>';
	if ( null !== $count ) {
		$inner .= sprintf(
			'<span class="catalog-option__count">%s<span class="screen-reader-text"> %s</span></span>',
			esc_html( number_format_i18n( $count ) ),
			esc_html( _n( 'producto', 'productos', $count, 'frave' ) )
		);
	}
	if ( $selected ) {
		$inner .= '<span class="screen-reader-text">' . esc_html__( '(filtro activo, pulsa para quitarlo)', 'frave' ) . '</span>';
	}

	$class = 'catalog-option' . ( $selected ? ' is-selected' : '' );
	if ( '' === $url ) {
		return '<span class="' . esc_attr( $class . ' is-disabled' ) . '">' . $inner . '</span>';
	}
	return '<a class="' . esc_attr( $class ) . '" rel="nofollow" href="' . esc_url( $url ) . '">' . $inner . '</a>';
}

/** Nested category links; the current category is marked with aria-current. */
function frave_catalog_render_category_tree( array $tree ): void {
	if ( ! $tree ) {
		return;
	}
	echo '<ul class="catalog-categories">';
	foreach ( $tree as $node ) {
		$term = $node['term'];
		$link = get_term_link( $term );
		if ( is_wp_error( $link ) ) {
			continue;
		}
		printf(
			'<li><a class="catalog-categories__link%1$s" href="%2$s"%3$s><span>%4$s</span><span class="catalog-categories__count">%5$s<span class="screen-reader-text"> %6$s</span></span></a>',
			$node['current'] ? ' is-current' : '',
			esc_url( $link ),
			$node['current'] ? ' aria-current="page"' : '',
			esc_html( $term->name ),
			esc_html( number_format_i18n( $term->count ) ),
			esc_html( _n( 'producto', 'productos', $term->count, 'frave' ) )
		);
		frave_catalog_render_category_tree( $node['children'] );
		echo '</li>';
	}
	echo '</ul>';
}

/* ---------- Layout ---------- */

/** Result count and sorting move into the theme's toolbar, next to the filter button. */
function frave_catalog_move_toolbar_items(): void {
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
}
add_action( 'wp', 'frave_catalog_move_toolbar_items' );

function frave_catalog_toolbar(): void {
	if ( frave_is_filterable_catalog() ) {
		get_template_part( 'template-parts/catalog/toolbar' );
	}
}
add_action( 'woocommerce_before_shop_loop', 'frave_catalog_toolbar', 20 );
add_action( 'woocommerce_no_products_found', 'frave_catalog_toolbar', 2 );

/** Wrappers for the widgets, so every filter is a collapsible section. */
function frave_catalog_widget_args( string $id ): array {
	return array(
		'before_widget' => '<details class="catalog-filter catalog-filter--' . esc_attr( $id ) . ' %s" open>',
		'after_widget'  => '</div></details>',
		'before_title'  => '<summary class="catalog-filter__title">',
		'after_title'   => '</summary><div class="catalog-filter__content">',
	);
}

/** Open the two-column layout and print the filter panel before the product loop. */
function frave_catalog_layout_start(): void {
	if ( ! frave_is_filterable_catalog() ) {
		return;
	}
	echo '<div class="catalog-layout">';
	get_template_part( 'template-parts/catalog/filters' );
	echo '<div class="catalog-layout__main">';
}

function frave_catalog_layout_end(): void {
	if ( frave_is_filterable_catalog() ) {
		echo '</div></div>';
	}
}

/** Offer a way out when the filters leave no products. */
function frave_catalog_no_results_actions(): void {
	if ( frave_is_filterable_catalog() && frave_catalog_active_filter_count() > 0 ) {
		echo '<p class="catalog-no-results">';
		frave_button( __( 'Quitar todos los filtros', 'frave' ), frave_catalog_clear_url(), 'button button--secondary' );
		echo '</p>';
	}
}

add_action( 'woocommerce_before_shop_loop', 'frave_catalog_layout_start', 1 );
add_action( 'woocommerce_after_shop_loop', 'frave_catalog_layout_end', 99 );
add_action( 'woocommerce_no_products_found', 'frave_catalog_layout_start', 1 );
add_action( 'woocommerce_no_products_found', 'frave_catalog_no_results_actions', 20 );
add_action( 'woocommerce_no_products_found', 'frave_catalog_layout_end', 99 );

/** Active filter chips, under the result count and sorting. */
function frave_catalog_active_filters_bar(): void {
	if ( frave_is_filterable_catalog() ) {
		get_template_part( 'template-parts/catalog/active-filters' );
	}
}
add_action( 'woocommerce_before_shop_loop', 'frave_catalog_active_filters_bar', 35 );
add_action( 'woocommerce_no_products_found', 'frave_catalog_active_filters_bar', 5 );
