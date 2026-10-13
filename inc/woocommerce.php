<?php
/** WooCommerce wrappers and catalog defaults. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Catalog mode (plugin frave-peru, WooCommerce → Ajustes → Productos): products are shown
 * without online sales. The plugin removes prices and purchases; the theme drops the
 * shopping interface (account, cart, mini-cart) and leads to contact instead.
 */
function frave_catalog_mode(): bool {
	return class_exists( 'WooCommerce' ) && function_exists( 'frave_peru_catalog_mode' ) && frave_peru_catalog_mode();
}

/** The shopping interface: header account and cart, mini-cart and cart fragments. */
function frave_shop_enabled(): bool {
	return class_exists( 'WooCommerce' ) && ! frave_catalog_mode();
}

function frave_catalog_mode_body_class( array $classes ): array {
	if ( frave_catalog_mode() ) {
		$classes[] = 'frave-catalog-mode';
	}
	return $classes;
}
add_filter( 'body_class', 'frave_catalog_mode_body_class' );

function frave_woocommerce_setup(): void {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
	add_action( 'woocommerce_before_main_content', 'frave_woocommerce_wrapper_start', 10 );
	add_action( 'woocommerce_after_main_content', 'frave_woocommerce_wrapper_end', 10 );

	// Catalog pages print their title, description and breadcrumb in the page hero.
	if ( frave_is_catalog_archive() ) {
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
		remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
		remove_action( 'woocommerce_archive_description', 'woocommerce_product_archive_description', 10 );
		add_filter( 'woocommerce_show_page_title', '__return_false' );
	}
}
add_action( 'wp', 'frave_woocommerce_setup' );

/** Shop, product categories, attributes and product searches. */
function frave_is_catalog_archive(): bool {
	return class_exists( 'WooCommerce' ) && ( is_shop() || is_product_taxonomy() || ( is_search() && 'product' === get_query_var( 'post_type' ) ) );
}

/** Page hero of catalog pages: breadcrumb, title, description and product search. */
function frave_catalog_hero(): void {
	if ( is_search() ) {
		$eyebrow = __( 'BÚSQUEDA', 'frave' );
		$lead    = '';
		$meta    = '';
	} elseif ( is_product_taxonomy() ) {
		$term    = get_queried_object();
		$parent  = $term instanceof WP_Term && $term->parent ? get_term( $term->parent, $term->taxonomy ) : null;
		$eyebrow = $parent instanceof WP_Term ? $parent->name : __( 'CATÁLOGO', 'frave' );
		$lead    = $term instanceof WP_Term ? term_description( $term ) : '';
		$meta    = $term instanceof WP_Term ? frave_catalog_line_link( $term ) : '';
	} else {
		$meta    = '';
		$shop    = get_post( wc_get_page_id( 'shop' ) );
		$eyebrow = __( 'NUESTROS PRODUCTOS', 'frave' );
		$lead    = $shop instanceof WP_Post && '' !== trim( $shop->post_content ) ? $shop->post_content : __( 'Explora nuestras líneas de sabores, fragancias y envases, y solicita una cotización de lo que necesites.', 'frave' );
	}
	get_template_part(
		'template-parts/components/page-hero',
		null,
		array(
			'eyebrow'    => $eyebrow,
			'title'      => woocommerce_page_title( false ),
			'lead'       => $lead,
			'meta'       => $meta,
			'breadcrumb' => true,
			'search'     => true,
		)
	);
}

/** On a product line's category or subcategory, a link to the line page; '' otherwise. */
function frave_catalog_line_link( WP_Term $term ): string {
	if ( 'product_cat' !== $term->taxonomy ) {
		return '';
	}
	$ancestors = get_ancestors( $term->term_id, 'product_cat', 'taxonomy' );
	$top       = $ancestors ? get_term( (int) end( $ancestors ), 'product_cat' ) : $term;
	$page      = $top instanceof WP_Term ? frave_line_page( $top->slug ) : null;
	if ( ! $page ) {
		return '';
	}
	return sprintf(
		'<a class="page-hero__line-link" href="%1$s">%2$s <span aria-hidden="true">→</span></a>',
		esc_url( get_permalink( $page ) ),
		/* translators: %s: product line name. */
		esc_html( sprintf( __( 'Conoce la línea %s', 'frave' ), $top->name ) )
	);
}

function frave_woocommerce_wrapper_start(): void {
	?>
	<main id="primary" class="site-main frave-woocommerce<?php echo frave_is_catalog_archive() ? ' frave-woocommerce--catalog' : ''; ?>">
		<?php
		if ( frave_is_catalog_archive() ) {
			frave_catalog_hero();
			echo '<div class="section catalog-section">';
		}
		?>
		<div class="container">
	<?php
}

function frave_woocommerce_wrapper_end(): void {
	?>
		</div>
		<?php
		if ( frave_is_catalog_archive() ) {
			echo '</div>';
		}
		?>
	</main>
	<?php
}

/** Keep catalog cards aligned with the three-column design on desktop. */
function frave_loop_columns(): int {
	return 3;
}
add_filter( 'loop_shop_columns', 'frave_loop_columns' );

/**
 * WooCommerce skips its own button skin (grey background, purple .alt) when the body has
 * this class, which it normally adds for block themes that style buttons. The theme styles
 * every WooCommerce button, so it opts out the same way instead of fighting the
 * unlayered rules with !important.
 */
function frave_woocommerce_button_styles_class( array $classes ): array {
	if ( class_exists( 'WooCommerce' ) && ! in_array( 'woocommerce-block-theme-has-button-styles', $classes, true ) ) {
		$classes[] = 'woocommerce-block-theme-has-button-styles';
	}
	return $classes;
}
add_filter( 'body_class', 'frave_woocommerce_button_styles_class' );

/**
 * Refresh the header count and mini-cart on every page. With full-page caching the
 * HTML may hold another visitor's cart; fragments replace it from the visitor's session
 * (one request per session, then cached in sessionStorage).
 */
function frave_enqueue_cart_fragments(): void {
	if ( frave_shop_enabled() ) {
		wp_enqueue_script( 'wc-cart-fragments' );
	}
}
add_action( 'wp_enqueue_scripts', 'frave_enqueue_cart_fragments', 20 );

/** The drawer duplicates the cart and checkout pages, so it is left out there. */
function frave_show_mini_cart(): bool {
	return frave_shop_enabled() && ! is_cart() && ! is_checkout();
}

/** Keep the custom header cart link in sync with WooCommerce AJAX fragments. */
function frave_cart_link_fragment( array $fragments ): array {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return $fragments;
	}

	ob_start();
	get_template_part( 'template-parts/components/cart-link' );
	$cart_link = (string) ob_get_clean();

	if ( '' !== trim( $cart_link ) ) {
		$fragments['a.header-action--cart'] = $cart_link;
	}

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'frave_cart_link_fragment' );

/**
 * The product tabs already name their panel ("Descripción", "Información adicional"),
 * so drop the repeated heading WooCommerce prints inside each panel.
 */
add_filter( 'woocommerce_product_description_heading', '__return_empty_string' );
add_filter( 'woocommerce_product_additional_information_heading', '__return_empty_string' );

/**
 * In catalog mode product cards end with "Ver detalles" where WooCommerce printed the
 * add-to-cart button; the whole card already links to the product, so the button is
 * hidden from assistive technology to avoid announcing the same link twice.
 */
function frave_catalog_mode_loop_button(): void {
	if ( ! frave_catalog_mode() ) {
		return;
	}
	printf(
		'<a href="%1$s" class="button product-card__details" tabindex="-1" aria-hidden="true">%2$s<span aria-hidden="true">&rarr;</span></a>',
		esc_url( get_permalink() ),
		esc_html__( 'Ver detalles', 'frave' )
	);
}
add_action( 'woocommerce_after_shop_loop_item', 'frave_catalog_mode_loop_button', 10 );

/** Product cards: the image sits in a frame (for the hover zoom), with the category above the title. */
function frave_product_card_media_open(): void {
	echo '<div class="product-card__media">';
}
add_action( 'woocommerce_before_shop_loop_item_title', 'frave_product_card_media_open', 9 );

function frave_product_card_media_close(): void {
	echo '</div>';
}
add_action( 'woocommerce_before_shop_loop_item_title', 'frave_product_card_media_close', 11 );

function frave_product_card_category(): void {
	global $product;
	if ( ! $product instanceof WC_Product ) {
		return;
	}
	$default = (int) get_option( 'default_product_cat' );
	foreach ( (array) get_the_terms( $product->get_id(), 'product_cat' ) as $term ) {
		if ( $term instanceof WP_Term && $default !== $term->term_id ) {
			echo '<span class="product-card__category">' . esc_html( $term->name ) . '</span>';
			return;
		}
	}
}
add_action( 'woocommerce_shop_loop_item_title', 'frave_product_card_category', 5 );

/** The breadcrumb landmark is announced in Spanish, like the blog's («Migas de pan»). */
function frave_woocommerce_breadcrumb_label( array $defaults ): array {
	$defaults['wrap_before'] = str_replace( 'aria-label="Breadcrumb"', 'aria-label="' . esc_attr__( 'Migas de pan', 'frave' ) . '"', $defaults['wrap_before'] );
	return $defaults;
}
add_filter( 'woocommerce_breadcrumb_defaults', 'frave_woocommerce_breadcrumb_label' );
