<?php
/**
 * Collections: groups of aromas of a product subcategory (Romero, Mascotas…). A collection
 * is a term of the WooCommerce attribute «Colección» (pa_coleccion, with archives), so it
 * has its own page and works as a catalog filter, and the data stays in WooCommerce if the
 * theme changes. Each one has:
 * - its subcategory: term meta frave_category, chosen in Productos → Atributos → Colección;
 * - its order: term meta order (the attribute's custom order);
 * - its picture: assets/media/colecciones/{slug}.webp (1536 × 1024) and {slug}-768.webp,
 *   or meanwhile its subcategory's picture.
 *
 * They appear on their subcategory's detail page, in the portfolio bands of the line, on the
 * solution pages that use their subcategory, in the hub /colecciones/ (template
 * «Colecciones») and in the catalog menu. bin/crear-colecciones.php creates the attribute,
 * the first collections and the hub; a new collection only needs a term, its subcategory
 * and its products.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const FRAVE_COLLECTION_TAXONOMY  = 'pa_coleccion';
const FRAVE_TEMPLATE_COLLECTIONS = 'page-templates/colecciones.php';

/**
 * Collections in their order. With $categories, only those of these categories or of their
 * subcategories (a line or a group shows the collections below it).
 *
 * @param WP_Term[]|null $categories Product categories.
 * @return WP_Term[]
 */
function frave_collections( ?array $categories = null ): array {
	if ( ! taxonomy_exists( FRAVE_COLLECTION_TAXONOMY ) ) {
		return array();
	}
	$terms = get_terms(
		array(
			'taxonomy'   => FRAVE_COLLECTION_TAXONOMY,
			'hide_empty' => false,
		)
	);
	if ( is_wp_error( $terms ) || ! $terms ) {
		return array();
	}
	usort( $terms, static fn( WP_Term $a, WP_Term $b ): int => (int) get_term_meta( $a->term_id, 'order', true ) <=> (int) get_term_meta( $b->term_id, 'order', true ) );
	if ( null === $categories ) {
		return $terms;
	}
	return array_values(
		array_filter(
			$terms,
			static function ( WP_Term $collection ) use ( $categories ): bool {
				$category = frave_collection_category( $collection );
				if ( ! $category ) {
					return false;
				}
				foreach ( $categories as $parent ) {
					if ( $parent->term_id === $category->term_id || term_is_ancestor_of( $parent, $category, 'product_cat' ) ) {
						return true;
					}
				}
				return false;
			}
		)
	);
}

/** The product subcategory a collection belongs to, or null. */
function frave_collection_category( WP_Term $collection ): ?WP_Term {
	$id   = (int) get_term_meta( $collection->term_id, 'frave_category', true );
	$term = $id ? get_term( $id, 'product_cat' ) : null;
	return $term instanceof WP_Term ? $term : null;
}

/** The collection of the current catalog page, or null. */
function frave_collection_term(): ?WP_Term {
	if ( ! is_tax( FRAVE_COLLECTION_TAXONOMY ) ) {
		return null;
	}
	$term = get_queried_object();
	return $term instanceof WP_Term ? $term : null;
}

/** Address of a collection's page. */
function frave_collection_url( WP_Term $collection ): string {
	$link = get_term_link( $collection );
	return is_wp_error( $link ) ? '' : $link;
}

/** The collection's picture: its own (theme asset) or its subcategory's; '' without either. */
function frave_collection_image( WP_Term $collection, string $sizes, array $attr = array() ): string {
	$base = 'assets/media/colecciones/' . $collection->slug;
	if ( file_exists( FRAVE_THEME_DIR . '/' . $base . '-768.webp' ) ) {
		return frave_theme_image( $base, 1536, 1024, 768, $attr + array( 'sizes' => $sizes ) );
	}
	$category = frave_collection_category( $collection );
	return $category ? frave_term_image( $category, $sizes, $attr ) : '';
}

/** The hub page /colecciones/, or ''. */
function frave_collections_url(): string {
	$page = frave_page_with_template( FRAVE_TEMPLATE_COLLECTIONS );
	return $page ? (string) get_permalink( $page ) : '';
}

/**
 * Entries of the header's «Catálogo» menu: the collections hub, then the fragrance guide.
 *
 * @return array<int, array{label: string, url: string, icon: string}>
 */
function frave_catalog_menu_links(): array {
	$links = array();
	$hub   = frave_collections_url();
	if ( '' !== $hub ) {
		$links[] = array(
			'label' => __( 'Colecciones', 'frave' ),
			'url'   => $hub,
			'icon'  => 'sparkle',
		);
	}
	return array_merge( $links, frave_fragrance_guide_links() );
}

/** Quote request about a collection: through its subcategory, naming the collection. */
function frave_collection_quote_link( WP_Term $collection ): ?array {
	$category = frave_collection_category( $collection );
	if ( $category ) {
		return frave_line_quote_link( $category );
	}
	return frave_company_action();
}

/* ---------- Where they appear ---------- */

/** Collections of a category's detail page, under its types (category-detail.php). */
function frave_detail_collections(): void {
	if ( is_paged() || frave_catalog_active_filter_count() > 0 ) {
		return;
	}
	$term = frave_detail_term();
	if ( ! $term ) {
		return;
	}
	$collections = frave_collections( array( $term ) );
	// The subcategory on each card only when the page groups several (a line or a group).
	$mixed = (bool) array_filter( $collections, static fn( WP_Term $collection ): bool => frave_collection_category( $collection )?->term_id !== $term->term_id );
	get_template_part(
		'template-parts/components/collections',
		null,
		array(
			'collections'   => $collections,
			/* translators: %s: category name. */
			'title'         => sprintf( __( 'Colecciones de *%s*', 'frave' ), $term->name ),
			'id'            => 'colecciones',
			'compact'       => true,
			'show_category' => $mixed,
		)
	);
}
add_action( 'woocommerce_before_shop_loop', 'frave_detail_collections', 0 );
add_action( 'woocommerce_no_products_found', 'frave_detail_collections', 0 );

/** A collection without products yet: its page invites to request a quote, without filters. */
function frave_collection_setup_empty(): void {
	$collection = frave_collection_term();
	if ( $collection && $collection->count < 1 ) {
		remove_action( 'woocommerce_no_products_found', 'wc_no_products_found' );
		add_action( 'woocommerce_no_products_found', 'frave_collection_empty_state' );
	}
}
add_action( 'wp', 'frave_collection_setup_empty' );

function frave_collection_empty_state(): void {
	$collection = frave_collection_term();
	if ( ! $collection ) {
		return;
	}
	$quote = frave_collection_quote_link( $collection );
	/* translators: %s: collection name. */
	$whatsapp = frave_whatsapp_url( sprintf( __( 'Hola, quisiera información y una cotización de la colección %s.', 'frave' ), $collection->name ) );
	?>
	<div class="category-empty card">
		<span class="icon-tile icon-tile--lg"><?php echo frave_icon( 'fragrance' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
		<div class="category-empty__copy">
			<?php /* translators: %s: collection name. */ ?>
			<h2><?php echo esc_html( sprintf( __( 'Estamos preparando los aromas de %s', 'frave' ), $collection->name ) ); ?></h2>
			<p><?php esc_html_e( 'Cuéntanos tu producto y te enviamos las fragancias de esta colección con sus características.', 'frave' ); ?></p>
		</div>
		<div class="category-empty__actions">
			<?php if ( $quote && empty( $quote['external'] ) ) : ?>
				<?php frave_button( __( 'Solicitar cotización', 'frave' ), $quote['url'] ); ?>
			<?php endif; ?>
			<?php if ( $whatsapp ) : ?>
				<a class="btn btn--whatsapp" href="<?php echo esc_url( $whatsapp ); ?>" target="_blank" rel="noopener" data-frave-whatsapp="collection">
					<span><?php esc_html_e( 'Escríbenos por WhatsApp', 'frave' ); ?></span>
					<span class="screen-reader-text"><?php esc_html_e( '(se abre en una nueva pestaña)', 'frave' ); ?></span>
					<?php echo frave_whatsapp_icon(); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
	<?php
}

/**
 * In a collection's breadcrumb, WooCommerce's attribute label («Colección del producto»,
 * without a link) becomes «Colecciones», linked to the hub.
 *
 * @param array<int, array{0: string, 1: string}> $crumbs Breadcrumb items: name and URL.
 * @return array<int, array{0: string, 1: string}>
 */
function frave_collection_breadcrumb( array $crumbs ): array {
	$taxonomy = frave_collection_term() ? get_taxonomy( FRAVE_COLLECTION_TAXONOMY ) : null;
	if ( ! $taxonomy ) {
		return $crumbs;
	}
	foreach ( $crumbs as $index => $crumb ) {
		if ( ( $crumb[0] ?? '' ) === $taxonomy->labels->name ) {
			$crumbs[ $index ] = array( __( 'Colecciones', 'frave' ), frave_collections_url() );
		}
	}
	return $crumbs;
}
add_filter( 'woocommerce_get_breadcrumb', 'frave_collection_breadcrumb' );

/** The hub closes with its own call to action instead of the footer's. */
function frave_collections_hide_footer_cta( bool $show ): bool {
	return is_page_template( FRAVE_TEMPLATE_COLLECTIONS ) ? false : $show;
}
add_filter( 'frave_show_footer_cta', 'frave_collections_hide_footer_cta' );

/* ---------- Admin: the subcategory of a collection ---------- */

/** The select of the subcategory, on the add and edit screens of a collection. */
function frave_collection_category_field( $term = null ): void {
	$current = $term instanceof WP_Term ? (int) get_term_meta( $term->term_id, 'frave_category', true ) : 0;
	$select  = wp_dropdown_categories(
		array(
			'taxonomy'          => 'product_cat',
			'name'              => 'frave_category',
			'id'                => 'frave-collection-category',
			'hierarchical'      => true,
			'hide_empty'        => false,
			'selected'          => $current,
			'show_option_none'  => __( '— Elige la subcategoría —', 'frave' ),
			'option_none_value' => 0,
			'echo'              => false,
		)
	);
	wp_nonce_field( 'frave_collection_category', 'frave_collection_category_nonce' );
	$help = __( 'La colección aparece en la página de esta subcategoría, en su línea y en las soluciones que la usan.', 'frave' );
	if ( $term instanceof WP_Term ) {
		printf(
			'<tr class="form-field"><th scope="row"><label for="frave-collection-category">%1$s</label></th><td>%2$s<p class="description">%3$s</p></td></tr>',
			esc_html__( 'Subcategoría', 'frave' ),
			$select, // phpcs:ignore WordPress.Security.EscapeOutput -- built by wp_dropdown_categories().
			esc_html( $help )
		);
		return;
	}
	printf(
		'<div class="form-field"><label for="frave-collection-category">%1$s</label>%2$s<p>%3$s</p></div>',
		esc_html__( 'Subcategoría', 'frave' ),
		$select, // phpcs:ignore WordPress.Security.EscapeOutput -- built by wp_dropdown_categories().
		esc_html( $help )
	);
}
add_action( FRAVE_COLLECTION_TAXONOMY . '_add_form_fields', 'frave_collection_category_field' );
add_action( FRAVE_COLLECTION_TAXONOMY . '_edit_form_fields', 'frave_collection_category_field' );

function frave_collection_save_category( int $term_id ): void {
	if ( ! isset( $_POST['frave_collection_category_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['frave_collection_category_nonce'] ) ), 'frave_collection_category' ) ) {
		return;
	}
	if ( ! current_user_can( 'manage_product_terms' ) ) { // phpcs:ignore WordPress.WP.Capabilities.Unknown -- WooCommerce's capability for product terms.
		return;
	}
	$category = isset( $_POST['frave_category'] ) ? absint( wp_unslash( $_POST['frave_category'] ) ) : 0;
	update_term_meta( $term_id, 'frave_category', $category );
}
add_action( 'created_' . FRAVE_COLLECTION_TAXONOMY, 'frave_collection_save_category' );
add_action( 'edited_' . FRAVE_COLLECTION_TAXONOMY, 'frave_collection_save_category' );
