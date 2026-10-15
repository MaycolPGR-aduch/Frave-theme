<?php
/**
 * Detail page of a product category: the catalog page of every line, category and
 * subcategory is where line pages and solution pages meet. Its hero names the line and the
 * industries that use the category and offers a quote; a category with subcategories
 * presents them as cards; and while a category has no products, an invitation to request
 * a quote replaces the empty listing and its filters.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** The product category of the current catalog page, or null. */
function frave_detail_term(): ?WP_Term {
	if ( ! function_exists( 'is_product_category' ) || ! is_product_category() ) {
		return null;
	}
	$term = get_queried_object();
	return $term instanceof WP_Term ? $term : null;
}

/** Address of a category's detail page, with or without products. */
function frave_term_detail_url( WP_Term $term ): string {
	$link = get_term_link( $term );
	return is_wp_error( $link ) ? '' : $link;
}

/**
 * Published solution pages that use a category: those listing it, one of its ancestors
 * (Perfumería de diseñador is part of Perfumería) or one of its descendants (a line is
 * used by every industry that uses one of its categories).
 *
 * @return array<int, array{title: string, url: string}>
 */
function frave_term_solutions( WP_Term $term ): array {
	$family    = array_merge( array( $term->term_id ), array_map( 'intval', get_ancestors( $term->term_id, 'product_cat', 'taxonomy' ) ) );
	$solutions = array();
	foreach ( frave_solution_pages() as $slug => $page ) {
		foreach ( frave_solution_terms( $slug ) as $used ) {
			if ( in_array( $used->term_id, $family, true ) || term_is_ancestor_of( $term, $used, 'product_cat' ) ) {
				$solutions[] = array(
					'title' => get_the_title( $page ),
					'url'   => (string) get_permalink( $page ),
				);
				break;
			}
		}
	}
	return $solutions;
}

/** «Se usa en: Perfumería, Cosmética…» as links to the solution pages, or ''. */
function frave_term_solutions_html( WP_Term $term, string $class_name ): string {
	$links = array_map(
		static fn( array $solution ): string => sprintf( '<a href="%s">%s</a>', esc_url( $solution['url'] ), esc_html( $solution['title'] ) ),
		frave_term_solutions( $term )
	);
	if ( ! $links ) {
		return '';
	}
	return sprintf(
		'<span class="%1$s">%2$s %3$s</span>',
		esc_attr( $class_name ),
		esc_html__( 'Se usa en:', 'frave' ),
		implode( ', ', $links )
	);
}

/** A category with no products in it or below it: its page invites to request a quote. */
function frave_detail_is_empty( WP_Term $term ): bool {
	return frave_term_product_count( $term ) < 1;
}

/** The hero's buttons: request a quote about the category, by form or WhatsApp. */
function frave_detail_hero_actions( WP_Term $term ): string {
	$quote = frave_line_quote_link( $term );
	if ( ! $quote ) {
		return '';
	}
	return sprintf(
		'<a class="btn btn--primary" href="%1$s"%2$s><span>%3$s</span>%4$s<span class="btn__icon">%5$s</span></a>',
		esc_url( $quote['url'] ),
		$quote['external'] ? ' target="_blank" rel="noopener"' : '',
		esc_html__( 'Solicitar cotización', 'frave' ),
		$quote['external'] ? '<span class="screen-reader-text"> ' . esc_html__( '(se abre en una nueva pestaña)', 'frave' ) . '</span>' : '',
		frave_icon( 'arrow-right' )
	);
}

/**
 * The category's subcategories as cards with their image, description, types and quote,
 * above the products (or the invitation to quote). Not on filtered or later pages.
 */
function frave_detail_types(): void {
	$term = frave_detail_term();
	if ( ! $term || is_paged() || frave_catalog_active_filter_count() > 0 ) {
		return;
	}
	$children = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'parent'     => $term->term_id,
			'hide_empty' => false,
		)
	);
	if ( is_wp_error( $children ) || ! $children ) {
		return;
	}
	?>
	<section class="category-types" aria-labelledby="category-types-title">
		<div class="section-head">
			<p class="eyebrow"><?php esc_html_e( 'Tipos', 'frave' ); ?></p>
			<h2 id="category-types-title"><?php echo esc_html( sprintf( /* translators: %s: category name. */ __( 'Explora %s', 'frave' ), $term->name ) ); ?></h2>
		</div>
		<ol class="line-groups category-types__list" data-reveal-group>
			<?php
			foreach ( array_values( $children ) as $frave_index => $frave_child ) {
				get_template_part(
					'template-parts/components/term-card',
					null,
					array(
						'term'   => $frave_child,
						'number' => $frave_index + 1,
					)
				);
			}
			?>
		</ol>
	</section>
	<?php
}
// Before the filter layout opens (priority 1), so the cards span the full width. Not in
// woocommerce_archive_description: the theme hides WooCommerce's header, its page hero replaces it.
add_action( 'woocommerce_before_shop_loop', 'frave_detail_types', 0 );
add_action( 'woocommerce_no_products_found', 'frave_detail_types', 0 );

/** While a category has no products: no empty listing, but an invitation to request a quote. */
function frave_detail_setup_empty(): void {
	$term = frave_detail_term();
	if ( $term && frave_detail_is_empty( $term ) ) {
		remove_action( 'woocommerce_no_products_found', 'wc_no_products_found' );
		add_action( 'woocommerce_no_products_found', 'frave_detail_empty_state' );
	}
}
add_action( 'wp', 'frave_detail_setup_empty' );

function frave_detail_empty_state(): void {
	$term = frave_detail_term();
	if ( ! $term ) {
		return;
	}
	$quote    = frave_line_quote_link( $term );
	$whatsapp = frave_whatsapp_url( sprintf( /* translators: %s: category name. */ __( 'Hola, quisiera información y una cotización de %s.', 'frave' ), $term->name ) );
	?>
	<div class="category-empty card">
		<span class="icon-tile icon-tile--lg"><?php echo frave_icon( 'chat' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
		<div class="category-empty__copy">
			<h2><?php echo esc_html( sprintf( /* translators: %s: category name. */ __( 'Estamos preparando los productos de %s', 'frave' ), $term->name ) ); ?></h2>
			<p><?php echo esc_html( sprintf( /* translators: %s: category name. */ __( 'Cuéntanos qué necesitas y te enviamos opciones de %s con sus características.', 'frave' ), mb_strtolower( $term->name ) ) ); ?></p>
		</div>
		<div class="category-empty__actions">
			<?php if ( $quote && ! $quote['external'] ) : ?>
				<?php frave_button( __( 'Solicitar cotización', 'frave' ), $quote['url'] ); ?>
			<?php endif; ?>
			<?php if ( $whatsapp ) : ?>
				<a class="btn btn--whatsapp" href="<?php echo esc_url( $whatsapp ); ?>" target="_blank" rel="noopener" data-frave-whatsapp="category">
					<span><?php esc_html_e( 'Escríbenos por WhatsApp', 'frave' ); ?></span>
					<span class="screen-reader-text"><?php esc_html_e( '(se abre en una nueva pestaña)', 'frave' ); ?></span>
					<span class="btn__icon"><?php echo frave_whatsapp_icon(); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
				</a>
			<?php endif; ?>
		</div>
	</div>
	<?php
}
