<?php
/**
 * Top de perfumería: the perfumes that lead the market in each perfumery subcategory, shown
 * as inspiration («what you can create with our essences»), never as products for sale.
 * Each reference is an entry of the admin section «Top de perfumería» (post type
 * frave_referencia, not public) with:
 * - its name (title), house, subcategory (a product category), audience (Hombre, Mujer or
 *   Unisex), olfactory family (inc/fragrance-families.php) and notes by level;
 * - «Por qué es tendencia» (excerpt) and its rank (Orden, the page order);
 * - its picture: the featured image, or assets/media/top/{slug}.webp (1080 × 1080) and
 *   {slug}-540.webp, or meanwhile its family's picture.
 *
 * Shown right after the hero of its subcategory's page (tabs per audience when there is
 * more than one, podium for the first three) and as a band on the Fragancias line and the
 * Perfumería solution. «Quiero crear este perfil» opens the contact form with the profile
 * written (?perfil=). bin/crear-top-perfumeria.php creates the first references.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const FRAVE_TOP_POST_TYPE = 'frave_referencia';

/** Audiences in their order on the page. @return array<string, string> */
function frave_top_audiences(): array {
	return array(
		'hombre' => __( 'Hombre', 'frave' ),
		'mujer'  => __( 'Mujer', 'frave' ),
		'unisex' => __( 'Unisex', 'frave' ),
	);
}

/** Note levels of a reference. @return array<string, string> */
function frave_top_levels(): array {
	return array(
		'top'   => __( 'Salida', 'frave' ),
		'heart' => __( 'Corazón', 'frave' ),
		'base'  => __( 'Fondo', 'frave' ),
	);
}

function frave_top_register(): void {
	register_post_type(
		FRAVE_TOP_POST_TYPE,
		array(
			'labels'          => array(
				'name'               => __( 'Top de perfumería', 'frave' ),
				'singular_name'      => __( 'Referencia', 'frave' ),
				'menu_name'          => __( 'Top perfumería', 'frave' ),
				'add_new'            => __( 'Añadir referencia', 'frave' ),
				'add_new_item'       => __( 'Añadir referencia', 'frave' ),
				'edit_item'          => __( 'Editar referencia', 'frave' ),
				'new_item'           => __( 'Nueva referencia', 'frave' ),
				'search_items'       => __( 'Buscar referencias', 'frave' ),
				'not_found'          => __( 'No hay referencias.', 'frave' ),
				'not_found_in_trash' => __( 'No hay referencias en la papelera.', 'frave' ),
				'featured_image'     => __( 'Imagen del perfil', 'frave' ),
				'set_featured_image' => __( 'Elegir imagen del perfil', 'frave' ),
			),
			'description'     => __( 'Perfumes que marcan tendencia, mostrados como inspiración en las subcategorías de perfumería.', 'frave' ),
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'show_in_rest'    => false,
			'menu_position'   => 26,
			'menu_icon'       => 'dashicons-star-filled',
			'hierarchical'    => false,
			'supports'        => array( 'title', 'excerpt', 'thumbnail', 'page-attributes' ),
			'capability_type' => 'post',
			'map_meta_cap'    => true,
		)
	);
}
add_action( 'init', 'frave_top_register' );

/* ---------- Data ---------- */

/**
 * Published references of a category, in their order.
 *
 * @return WP_Post[]
 */
function frave_top_entries( WP_Term $category ): array {
	return get_posts(
		array(
			'post_type'        => FRAVE_TOP_POST_TYPE,
			'post_status'      => 'publish',
			'numberposts'      => 30,
			'orderby'          => array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			),
			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- a few dozen references at most.
			'meta_query'       => array(
				array(
					'key'   => '_frave_top_category',
					'value' => (int) $category->term_id,
				),
			),
			'suppress_filters' => false,
		)
	);
}

/**
 * The references grouped by audience, in the audiences' order.
 *
 * @param WP_Post[] $entries References.
 * @return array<string, WP_Post[]>
 */
function frave_top_groups( array $entries ): array {
	$groups = array_fill_keys( array_keys( frave_top_audiences() ), array() );
	foreach ( $entries as $entry ) {
		$audience = (string) get_post_meta( $entry->ID, '_frave_top_audience', true );
		$groups[ isset( $groups[ $audience ] ) ? $audience : 'unisex' ][] = $entry;
	}
	return array_filter( $groups );
}

/**
 * Categories with published references, in the catalog order. With $within, only those
 * that are one of them or below one of them.
 *
 * @param WP_Term[]|null $within Product categories.
 * @return WP_Term[]
 */
function frave_top_categories( ?array $within = null ): array {
	$ids        = array_unique(
		array_map(
			'intval',
			get_posts(
				array(
					'post_type'   => FRAVE_TOP_POST_TYPE,
					'post_status' => 'publish',
					'numberposts' => 100,
					'fields'      => 'ids',
				)
			)
		)
	);
	$categories = array_filter( array_map( static fn( int $id ): int => (int) get_post_meta( $id, '_frave_top_category', true ), $ids ) );
	if ( ! $categories ) {
		return array();
	}
	$terms = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'include'    => array_values( array_unique( $categories ) ),
			'hide_empty' => false,
		)
	);
	if ( is_wp_error( $terms ) ) {
		return array();
	}
	if ( null === $within ) {
		return $terms;
	}
	return array_values(
		array_filter(
			$terms,
			static function ( WP_Term $term ) use ( $within ): bool {
				foreach ( $within as $parent ) {
					if ( $parent->term_id === $term->term_id || term_is_ancestor_of( $parent, $term, 'product_cat' ) ) {
						return true;
					}
				}
				return false;
			}
		)
	);
}

/**
 * What the page shows of a reference.
 *
 * @return array{name: string, house: string, audience: string, family: string, family_name: string, family_tone: string, notes: array<string, string[]>, reason: string, slug: string}
 */
function frave_top_entry( WP_Post $entry ): array {
	$families = frave_fragrance_families();
	$family   = (string) get_post_meta( $entry->ID, '_frave_top_family', true );
	$notes    = array();
	foreach ( array_keys( frave_top_levels() ) as $level ) {
		$list = array_filter( array_map( 'trim', explode( ',', (string) get_post_meta( $entry->ID, '_frave_top_notes_' . $level, true ) ) ) );
		if ( $list ) {
			$notes[ $level ] = array_values( $list );
		}
	}
	return array(
		'name'        => get_the_title( $entry ),
		'house'       => (string) get_post_meta( $entry->ID, '_frave_top_house', true ),
		'audience'    => (string) get_post_meta( $entry->ID, '_frave_top_audience', true ),
		'family'      => isset( $families[ $family ] ) ? $family : '',
		'family_name' => $families[ $family ]['name'] ?? '',
		'family_tone' => $families[ $family ]['tone'] ?? '#f1e6d8',
		'notes'       => $notes,
		'reason'      => has_excerpt( $entry ) ? get_the_excerpt( $entry ) : '',
		'slug'        => $entry->post_name,
	);
}

/** The reference's picture: its featured image, its theme picture or its family's; '' without any. */
function frave_top_image( WP_Post $entry, string $sizes, array $attr = array() ): string {
	$attr += array(
		'alt'     => '',
		'loading' => 'lazy',
		'sizes'   => $sizes,
	);
	if ( has_post_thumbnail( $entry ) ) {
		return (string) get_the_post_thumbnail( $entry, 'large', $attr );
	}
	$base = 'assets/media/top/' . $entry->post_name;
	if ( file_exists( FRAVE_THEME_DIR . '/' . $base . '-540.webp' ) ) {
		return frave_theme_image( $base, 1080, 1080, 540, $attr );
	}
	$family = frave_fragrance_family_image_base( (string) get_post_meta( $entry->ID, '_frave_top_family', true ) );
	return '' !== $family ? frave_theme_image( $family, 1080, 1350, 640, $attr ) : '';
}

/** «Quiero crear este perfil»: the contact form with the profile written, or WhatsApp. */
function frave_top_quote_link( WP_Post $entry, WP_Term $category ): ?array {
	$link = frave_line_quote_link( $category );
	if ( $link && empty( $link['external'] ) ) {
		$link['url'] = add_query_arg( 'perfil', $entry->post_name, $link['url'] );
		return $link;
	}
	$data     = frave_top_entry( $entry );
	$whatsapp = frave_whatsapp_url( frave_top_message( $data['name'], $data['house'] ) );
	return '' !== $whatsapp ? array(
		'url'      => $whatsapp,
		'external' => true,
	) : null;
}

function frave_top_message( string $name, string $house ): string {
	$reference = '' !== $house ? $name . ' (' . $house . ')' : $name;
	/* translators: %s: perfume and house used as a market reference. */
	return sprintf( __( 'Hola, quisiera desarrollar una fragancia con un perfil similar a %s. ¿Qué esencias me recomiendan?', 'frave' ), $reference );
}

/** The contact form opened from «Quiero crear este perfil» (?perfil=slug) names the profile. */
function frave_top_contact_prefill( array $values ): array {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- display only.
	$slug = isset( $_GET['perfil'] ) ? sanitize_title( wp_unslash( $_GET['perfil'] ) ) : '';
	if ( '' === $slug ) {
		return $values;
	}
	$entry = get_page_by_path( $slug, OBJECT, FRAVE_TOP_POST_TYPE );
	if ( ! $entry instanceof WP_Post || 'publish' !== $entry->post_status ) {
		return $values;
	}
	$data              = frave_top_entry( $entry );
	$values['message'] = frave_top_message( $data['name'], $data['house'] ) . "\n\n";
	return $values;
}
add_filter( 'frave_peru_contact_prefill', 'frave_top_contact_prefill' );

/* ---------- Where it appears ---------- */

/** The top of a perfumery subcategory, right after its hero (before types and collections). */
function frave_detail_top(): void {
	if ( is_paged() || frave_catalog_active_filter_count() > 0 ) {
		return;
	}
	$term = frave_detail_term();
	if ( ! $term ) {
		return;
	}
	get_template_part( 'template-parts/components/top-perfumery', null, array( 'category' => $term ) );
}
add_action( 'woocommerce_before_shop_loop', 'frave_detail_top', -1 );
add_action( 'woocommerce_no_products_found', 'frave_detail_top', -1 );

/* ---------- Admin ---------- */

function frave_top_meta_box(): void {
	add_meta_box( 'frave-top-data', __( 'Datos del perfume', 'frave' ), 'frave_top_render_meta_box', FRAVE_TOP_POST_TYPE, 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'frave_top_meta_box' );

function frave_top_render_meta_box( WP_Post $post ): void {
	wp_nonce_field( 'frave_top_save', 'frave_top_nonce' );
	$value    = static fn( string $key ): string => (string) get_post_meta( $post->ID, '_frave_top_' . $key, true );
	$category = wp_dropdown_categories(
		array(
			'taxonomy'          => 'product_cat',
			'name'              => 'frave_top_category',
			'id'                => 'frave-top-category',
			'hierarchical'      => true,
			'hide_empty'        => false,
			'selected'          => (int) $value( 'category' ),
			'show_option_none'  => __( '— Elige la subcategoría —', 'frave' ),
			'option_none_value' => 0,
			'echo'              => false,
		)
	);
	?>
	<p class="description"><?php esc_html_e( 'El top se muestra como inspiración (lo que se puede crear con nuestras esencias), no como un producto en venta. El título es el nombre del perfume; el orden del top es el campo «Orden» del panel Atributos (1 = primer puesto); «Por qué es tendencia» es el extracto.', 'frave' ); ?></p>
	<table class="form-table" role="presentation">
		<tr>
			<th scope="row"><label for="frave-top-house"><?php esc_html_e( 'Casa', 'frave' ); ?></label></th>
			<td><input type="text" class="regular-text" id="frave-top-house" name="frave_top_house" value="<?php echo esc_attr( $value( 'house' ) ); ?>" placeholder="Paco Rabanne"></td>
		</tr>
		<tr>
			<th scope="row"><label for="frave-top-category"><?php esc_html_e( 'Subcategoría', 'frave' ); ?></label></th>
			<td><?php echo $category; // phpcs:ignore WordPress.Security.EscapeOutput -- built by wp_dropdown_categories(). ?><p class="description"><?php esc_html_e( 'El top aparece en la página de esta subcategoría.', 'frave' ); ?></p></td>
		</tr>
		<tr>
			<th scope="row"><label for="frave-top-audience"><?php esc_html_e( 'Para', 'frave' ); ?></label></th>
			<td>
				<select id="frave-top-audience" name="frave_top_audience">
					<?php foreach ( frave_top_audiences() as $key => $label ) : ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $value( 'audience' ), $key ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="frave-top-family"><?php esc_html_e( 'Familia olfativa', 'frave' ); ?></label></th>
			<td>
				<select id="frave-top-family" name="frave_top_family">
					<option value=""><?php esc_html_e( '— Elige la familia —', 'frave' ); ?></option>
					<?php foreach ( frave_fragrance_families() as $key => $family ) : ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $value( 'family' ), $key ); ?>><?php echo esc_html( $family['name'] ); ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<?php foreach ( frave_top_levels() as $level => $label ) : ?>
			<tr>
				<?php /* translators: %s: note level (Salida, Corazón, Fondo). */ ?>
				<th scope="row"><label for="frave-top-notes-<?php echo esc_attr( $level ); ?>"><?php echo esc_html( sprintf( __( 'Notas de %s', 'frave' ), mb_strtolower( $label ) ) ); ?></label></th>
				<td><input type="text" class="large-text" id="frave-top-notes-<?php echo esc_attr( $level ); ?>" name="frave_top_notes_<?php echo esc_attr( $level ); ?>" value="<?php echo esc_attr( $value( 'notes_' . $level ) ); ?>" placeholder="<?php esc_attr_e( 'Separadas por comas', 'frave' ); ?>"></td>
			</tr>
		<?php endforeach; ?>
	</table>
	<?php
}

function frave_top_save( int $post_id ): void {
	if ( ! isset( $_POST['frave_top_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['frave_top_nonce'] ) ), 'frave_top_save' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$text = static fn( string $key ): string => isset( $_POST[ $key ] ) ? sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) : '';
	update_post_meta( $post_id, '_frave_top_house', $text( 'frave_top_house' ) );
	update_post_meta( $post_id, '_frave_top_category', isset( $_POST['frave_top_category'] ) ? absint( wp_unslash( $_POST['frave_top_category'] ) ) : 0 );
	$audience = $text( 'frave_top_audience' );
	update_post_meta( $post_id, '_frave_top_audience', isset( frave_top_audiences()[ $audience ] ) ? $audience : 'unisex' );
	$family = $text( 'frave_top_family' );
	update_post_meta( $post_id, '_frave_top_family', isset( frave_fragrance_families()[ $family ] ) ? $family : '' );
	foreach ( array_keys( frave_top_levels() ) as $level ) {
		update_post_meta( $post_id, '_frave_top_notes_' . $level, $text( 'frave_top_notes_' . $level ) );
	}
}
add_action( 'save_post_' . FRAVE_TOP_POST_TYPE, 'frave_top_save' );

/** The excerpt box is «Por qué es tendencia». */
function frave_top_excerpt_label( string $translation, string $text, string $domain ): string {
	if ( 'default' === $domain && 'Excerpt' === $text && FRAVE_TOP_POST_TYPE === get_post_type() ) {
		return __( 'Por qué es tendencia (una frase, opcional)', 'frave' );
	}
	return $translation;
}
add_filter( 'gettext', 'frave_top_excerpt_label', 10, 3 );

/** The list shows the rank and the data, ordered by rank. */
function frave_top_columns( array $columns ): array {
	return array(
		'cb'           => $columns['cb'] ?? '',
		'frave_rank'   => __( 'Orden', 'frave' ),
		'title'        => __( 'Perfume', 'frave' ),
		'frave_house'  => __( 'Casa', 'frave' ),
		'frave_cat'    => __( 'Subcategoría', 'frave' ),
		'frave_for'    => __( 'Para', 'frave' ),
		'frave_family' => __( 'Familia', 'frave' ),
		'date'         => $columns['date'] ?? __( 'Fecha', 'frave' ),
	);
}
add_filter( 'manage_' . FRAVE_TOP_POST_TYPE . '_posts_columns', 'frave_top_columns' );

function frave_top_column( string $column, int $post_id ): void {
	$data = frave_top_entry( get_post( $post_id ) );
	switch ( $column ) {
		case 'frave_rank':
			echo esc_html( (string) get_post_field( 'menu_order', $post_id ) );
			break;
		case 'frave_house':
			echo esc_html( $data['house'] );
			break;
		case 'frave_cat':
			$term = get_term( (int) get_post_meta( $post_id, '_frave_top_category', true ), 'product_cat' );
			echo $term instanceof WP_Term ? esc_html( $term->name ) : '—';
			break;
		case 'frave_for':
			echo esc_html( frave_top_audiences()[ $data['audience'] ] ?? '—' );
			break;
		case 'frave_family':
			echo esc_html( '' !== $data['family_name'] ? $data['family_name'] : '—' );
			break;
	}
}
add_action( 'manage_' . FRAVE_TOP_POST_TYPE . '_posts_custom_column', 'frave_top_column', 10, 2 );

function frave_top_admin_order( WP_Query $query ): void {
	if ( is_admin() && $query->is_main_query() && FRAVE_TOP_POST_TYPE === $query->get( 'post_type' ) && ! $query->get( 'orderby' ) ) {
		$query->set(
			'orderby',
			array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			)
		);
	}
}
add_action( 'pre_get_posts', 'frave_top_admin_order' );
