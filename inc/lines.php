<?php
/**
 * Line pages (Sabores, Fragancias, Envases): a page with the template "Línea" presents one
 * product category before its products. The category is the one chosen in the page's ACF
 * group "Línea Frave", or the category with the page's slug.
 *
 * The defaults below are the line copy agreed with Frave's management (October 2026); each
 * text can be replaced per page in ACF. Wrap a word in *asterisks* to highlight it.
 * Subcategories, their descriptions and order come from WooCommerce (bin/crear-lineas.php
 * creates the agreed tree).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const FRAVE_TEMPLATE_LINE = 'page-templates/linea.php';

/** Default copy of each line, keyed by category slug. */
function frave_line_defaults(): array {
	return array(
		'sabores'    => array(
			'eyebrow'            => __( 'Línea Sabores', 'frave' ),
			'title'              => __( 'Sabores que *se recuerdan*', 'frave' ),
			'lead'               => __( 'Soluciones integrales de saborización para bebidas, alimentos, lácteos y salud bucal, con el respaldo de fabricantes internacionales.', 'frave' ),
			'intro_title'        => __( 'Un gran sabor nace de la combinación precisa de *componentes aromáticos*.', 'frave' ),
			'intro_text'         => __( "Al integrarse de manera armónica con el gusto y la textura, se logra una experiencia gastronómica superior, capaz de dar un toque distintivo a una gran variedad de comidas y bebidas cotidianas.\n\nPriorizamos el conocimiento del mercado y las preferencias de los consumidores, y nos apoyamos en las fortalezas de nuestros proveedores.", 'frave' ),
			'highlight_1_title'  => __( 'Innovación en insumos clave', 'frave' ),
			'highlight_1_text'   => __( 'Componentes aromáticos de fabricantes internacionales de reconocido prestigio.', 'frave' ),
			'highlight_2_title'  => __( 'Desarrollo de conceptos', 'frave' ),
			'highlight_2_text'   => __( 'Te acompañamos a definir el perfil de sabor que tu producto necesita.', 'frave' ),
			'highlight_3_title'  => __( 'Suministro sostenible', 'frave' ),
			'highlight_3_text'   => __( 'Abastecimiento constante para que tu producción avance.', 'frave' ),
			'portfolio_title'    => __( 'Sabores para *cada industria*', 'frave' ),
			'portfolio_text'     => __( 'Explora nuestras categorías de aromas y encuentra el punto de partida para tu producto.', 'frave' ),
			'applications_title' => __( 'Perfiles que dan carácter a *tus recetas*', 'frave' ),
			'applications_text'  => __( 'Algunos de los sabores y aplicaciones con los que acompañamos a la industria de alimentos y bebidas.', 'frave' ),
			'applications'       => __( "Manzana\nMaracuyá\nFresa\nVainilla\nPanetón\nCafé\nTé\nBebidas\nProductos lácteos\nConfitería\nPanadería\nSalados\nCuidado bucal", 'frave' ),
			'cta_title'          => __( '¿Desarrollas un *nuevo sabor*?', 'frave' ),
			'cta_text'           => __( 'Cuéntanos sobre tu producto y te recomendamos las alternativas de nuestro portafolio.', 'frave' ),
			'icons'              => array( 'lab', 'sparkle', 'leaf' ),
			'tone'               => '#ead6c4',
		),
		'fragancias' => array(
			'eyebrow'            => __( 'Línea Fragancias', 'frave' ),
			'title'              => __( 'El lenguaje de los *aromas*', 'frave' ),
			'lead'               => __( 'Más de 500 referencias de fragancias y esencias hidrosolubles para perfumería fina, cosmética, cuidado personal, aseo e higiene y marketing olfativo.', 'frave' ),
			'intro_title'        => __( 'El olfato conecta a las personas con *emociones, recuerdos y experiencias*.', 'frave' ),
			'intro_text'         => __( "Los aromas son un lenguaje sensorial universal que trasciende culturas, cuenta historias y genera vínculos significativos.\n\nOfrecemos alternativas versátiles e innovadoras, alineadas con las tendencias del mercado y los requerimientos de cada cliente, con asesoría técnica especializada para el desarrollo de tus productos.", 'frave' ),
			'highlight_1_title'  => __( '+500 referencias', 'frave' ),
			'highlight_1_text'   => __( 'De fragancias y esencias hidrosolubles para diversas industrias.', 'frave' ),
			'highlight_2_title'  => __( 'Asesoría técnica', 'frave' ),
			'highlight_2_text'   => __( 'Especializada en el desarrollo de tus productos.', 'frave' ),
			'highlight_3_title'  => __( 'Tendencias del mercado', 'frave' ),
			'highlight_3_text'   => __( 'Alternativas versátiles e innovadoras para cada cliente.', 'frave' ),
			'portfolio_title'    => __( 'Un portafolio para *cada aplicación*', 'frave' ),
			'portfolio_text'     => __( 'De la perfumería fina al cuidado del hogar y la ambientación de espacios.', 'frave' ),
			'applications_title' => __( 'Fragancias presentes en *el día a día*', 'frave' ),
			'applications_text'  => __( 'Productos en los que nuestras fragancias aportan identidad y valor sensorial.', 'frave' ),
			'applications'       => __( "Perfumería femenina y masculina\nFragancias EDP y EDT\nCremas y lociones\nJabones líquidos\nBody splash\nCuidado del cabello\nDetergentes y suavizantes\nLavavajillas\nLimpiadores multiusos\nVelas\nDifusores y reeds\nAmbientadores\nAutomóviles\nMarketing olfativo", 'frave' ),
			'cta_title'          => __( '¿Buscas la fragancia para *tu próximo producto*?', 'frave' ),
			'cta_text'           => __( 'Te ayudamos a elegir entre más de 500 referencias según tu aplicación y tu público.', 'frave' ),
			'icons'              => array( 'fragrance', 'lab', 'sparkle' ),
			'tone'               => '#e5d7cc',
		),
		'envases'    => array(
			'eyebrow'            => __( 'Línea Envases', 'frave' ),
			'title'              => __( 'Envases que *presentan* tu marca', 'frave' ),
			'lead'               => __( 'Frascos, válvulas y accesorios para perfumería, cosmética y cuidado personal, con alternativas de personalización.', 'frave' ),
			'intro_title'        => __( 'La presentación también *diferencia* a tu producto.', 'frave' ),
			'intro_text'         => __( "Impulsamos la presentación y diferenciación de tus productos con una amplia variedad de envases y accesorios que combinan estética, innovación y calidad.\n\nNuestro portafolio incluye soluciones para perfumería, cosmética y cuidado personal, con diseños versátiles que se adaptan a las tendencias del mercado y fortalecen la identidad de tu marca.", 'frave' ),
			'highlight_1_title'  => __( 'Variedad de formatos', 'frave' ),
			'highlight_1_text'   => __( 'Frascos de vidrio, frascos para difusores y válvulas.', 'frave' ),
			'highlight_2_title'  => __( 'Personalización', 'frave' ),
			'highlight_2_text'   => __( 'Alternativas para que el envase refleje tu marca.', 'frave' ),
			'highlight_3_title'  => __( 'Estética y calidad', 'frave' ),
			'highlight_3_text'   => __( 'Diseños que acompañan las tendencias del mercado.', 'frave' ),
			'portfolio_title'    => __( 'Todo para *presentar* tu producto', 'frave' ),
			'portfolio_text'     => __( 'Elige el envase y los accesorios que mejor acompañan tu fórmula.', 'frave' ),
			'applications_title' => __( 'Envases para *cada fórmula*', 'frave' ),
			'applications_text'  => __( 'Presentaciones para las industrias que atendemos.', 'frave' ),
			'applications'       => __( "Perfumería fina\nBody splash\nCosmética\nCuidado personal\nDifusores de varitas\nAmbientación\nLanzamientos especiales\nEdiciones de colección", 'frave' ),
			'cta_title'          => __( '¿Necesitas envases para *tu lanzamiento*?', 'frave' ),
			'cta_text'           => __( 'Cuéntanos sobre tu fórmula y la presentación que imaginas; te proponemos opciones.', 'frave' ),
			'icons'              => array( 'packaging', 'sparkle', 'quality' ),
			'tone'               => '#ddc9b6',
		),
	);
}

/** Copy for a category without its own defaults: its name and description. */
function frave_line_generic_defaults( string $name, string $description ): array {
	return array(
		'eyebrow'            => __( 'Línea', 'frave' ),
		'title'              => $name,
		'lead'               => $description,
		'intro_title'        => '',
		'intro_text'         => '',
		'portfolio_title'    => __( 'Nuestro *portafolio*', 'frave' ),
		'portfolio_text'     => '',
		'applications_title' => '',
		'applications_text'  => '',
		'applications'       => '',
		/* translators: %s: product line name. */
		'cta_title'          => sprintf( __( '¿Te interesa nuestra línea de %s?', 'frave' ), $name ),
		'cta_text'           => __( 'Cuéntanos sobre tu proyecto y te preparamos una cotización.', 'frave' ),
		'icons'              => array( 'sparkle', 'quality', 'chat' ),
		'tone'               => '#e9dccd',
	);
}

/** Category slug a line page presents: the ACF choice, or the page's own slug. */
function frave_line_slug( ?WP_Post $page = null ): string {
	$page = $page ? $page : get_post();
	if ( ! $page instanceof WP_Post ) {
		return '';
	}
	$term_id = function_exists( 'get_field' ) ? (int) get_field( 'frave_line_category', $page->ID ) : 0;
	$term    = $term_id ? get_term( $term_id, 'product_cat' ) : null;
	return $term instanceof WP_Term ? $term->slug : $page->post_name;
}

function frave_line_term( string $slug ): ?WP_Term {
	if ( '' === $slug || ! taxonomy_exists( 'product_cat' ) ) {
		return null;
	}
	$term = get_term_by( 'slug', $slug, 'product_cat' );
	return $term instanceof WP_Term ? $term : null;
}

/** Defaults of the line, for a known line or any category. */
function frave_line_copy( string $slug ): array {
	$defaults = frave_line_defaults();
	if ( isset( $defaults[ $slug ] ) ) {
		return $defaults[ $slug ];
	}
	$term = frave_line_term( $slug );
	return frave_line_generic_defaults(
		$term ? $term->name : get_the_title(),
		$term ? wp_strip_all_tags( $term->description ) : ''
	);
}

/** A text of the current line page: the page's ACF field, or the line default. */
function frave_line_text( string $key, string $slug ): string {
	$value = frave_field( 'frave_line_' . $key );
	if ( is_string( $value ) && '' !== trim( $value ) ) {
		return $value;
	}
	$copy = frave_line_copy( $slug );
	return is_string( $copy[ $key ] ?? null ) ? $copy[ $key ] : '';
}

/** Published line page of a category, or null. */
function frave_line_page( string $slug ): ?WP_Post {
	static $pages = null;
	if ( null === $pages ) {
		$pages = array();
		$found = get_pages(
			array(
				'meta_key'    => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery -- small, cached query on pages.
				'meta_value'  => FRAVE_TEMPLATE_LINE, // phpcs:ignore WordPress.DB.SlowDBQuery
				'post_status' => 'publish',
			)
		);
		foreach ( $found as $page ) {
			$pages[ frave_line_slug( $page ) ] = $page;
		}
	}
	return $pages[ $slug ] ?? null;
}

/** Products in a category, its subcategories included (WooCommerce keeps that count). */
function frave_term_product_count( WP_Term $term ): int {
	$count = get_term_meta( $term->term_id, 'product_count_product_cat', true );
	return '' === $count ? (int) $term->count : (int) $count;
}

/**
 * Where a product line leads: its page, or its category while that has products.
 * '' when neither exists, so no link ends on an empty page.
 */
function frave_line_url( string $slug ): string {
	$page = frave_line_page( $slug );
	if ( $page ) {
		return (string) get_permalink( $page );
	}
	$term = frave_line_term( $slug );
	if ( $term && frave_term_product_count( $term ) > 0 ) {
		$link = get_term_link( $term );
		return is_wp_error( $link ) ? '' : $link;
	}
	return '';
}

/** Category link while it has products, otherwise ''. */
function frave_term_products_url( WP_Term $term ): string {
	if ( frave_term_product_count( $term ) < 1 ) {
		return '';
	}
	$link = get_term_link( $term );
	return is_wp_error( $link ) ? '' : $link;
}

/**
 * Quote request about a line or one of its subcategories: the contact form with the topic
 * filled in (?linea=), or WhatsApp while the contact page is not published.
 *
 * @return array{url: string, external: bool}|null
 */
function frave_line_quote_link( WP_Term $term ): ?array {
	$contact = frave_contact_page_url();
	if ( $contact ) {
		return array(
			'url'      => add_query_arg( 'linea', $term->slug, $contact ),
			'external' => false,
		);
	}
	/* translators: %s: product line or subcategory name. */
	$whatsapp = frave_whatsapp_url( sprintf( __( 'Hola, quisiera información y una cotización de %s.', 'frave' ), $term->name ) );
	return $whatsapp ? array(
		'url'      => $whatsapp,
		'external' => true,
	) : null;
}

/**
 * Hero images of a line: the page's ACF images or the theme's (assets/media/lineas).
 *
 * @return array{desktop: string, mobile: string, desktop_size: int[], mobile_size: int[]}
 */
function frave_line_images( string $slug ): array {
	$images = array(
		'desktop'      => '',
		'mobile'       => '',
		'desktop_size' => array( 1672, 941 ),
		'mobile_size'  => array( 1122, 1402 ),
	);
	foreach ( array( 'desktop', 'mobile' ) as $format ) {
		$id = (int) frave_field( 'frave_line_image_' . $format, 0 );
		if ( $id ) {
			$source = wp_get_attachment_image_src( $id, 'full' );
			if ( $source ) {
				$images[ $format ]           = $source[0];
				$images[ $format . '_size' ] = array( (int) $source[1], (int) $source[2] );
			}
		}
	}
	$files = array(
		'desktop' => 'escritorio',
		'mobile'  => 'movil',
	);
	foreach ( $files as $format => $suffix ) {
		if ( '' === $images[ $format ] && frave_line_has_theme_image( $slug ) ) {
			$images[ $format ] = frave_asset_url( "assets/media/lineas/{$slug}-{$suffix}.webp" );
		}
	}
	return $images;
}

/** The theme ships images for the three agreed lines. */
function frave_line_has_theme_image( string $slug ): bool {
	return isset( frave_line_defaults()[ $slug ] );
}

/** Card image of a line (theme default), or ''. */
function frave_line_card_image( string $slug ): string {
	return frave_line_has_theme_image( $slug ) ? frave_asset_url( "assets/media/lineas/{$slug}-tarjeta.webp" ) : '';
}

/**
 * Image of a product category: the one chosen in Productos → Categorías or, failing that,
 * the theme's. The theme ships one per category of the agreed tree
 * (assets/media/categorias/{slug}.webp, 1200 × 800, with a 600 px version) and uses the
 * line's hero image for the three lines. '' when there is none.
 *
 * @param array<string, string> $attr alt, loading, class… (alt defaults to '').
 */
function frave_term_image( WP_Term $term, string $sizes, array $attr = array() ): string {
	$attr += array(
		'alt'     => '',
		'loading' => 'lazy',
		'sizes'   => $sizes,
	);
	$id    = (int) get_term_meta( $term->term_id, 'thumbnail_id', true );
	if ( $id ) {
		return (string) wp_get_attachment_image( $id, 'frave-editorial', false, $attr );
	}
	$html = frave_theme_image( 'assets/media/categorias/' . $term->slug, 1200, 800, 600, $attr );
	if ( '' === $html && 0 === $term->parent && frave_line_has_theme_image( $term->slug ) ) {
		$html = sprintf(
			'<img src="%1$s" width="1672" height="941" alt="%2$s" loading="%3$s" decoding="async">',
			esc_url( frave_asset_url( "assets/media/lineas/{$term->slug}-escritorio.webp" ) ),
			esc_attr( $attr['alt'] ),
			esc_attr( $attr['loading'] )
		);
	}
	return $html;
}

/**
 * A category's image as a hidden layer of a card's picture (term-cards.js swaps it in
 * when the visitor points at that category). Its src and srcset wait in data- attributes:
 * the script loads them only when the pointer or the focus reaches the card. '' when the
 * category has no image.
 */
function frave_term_image_layer( WP_Term $term, string $sizes, int $index ): string {
	$html = frave_term_image(
		$term,
		$sizes,
		array(
			'class'           => 'line-group__layer',
			'data-swap-layer' => (string) $index,
			'loading'         => 'lazy',
		)
	);
	return '' === $html ? '' : (string) preg_replace( '/ (src|srcset)="/', ' data-$1="', $html );
}

/**
 * Small square image of a product category for chips: the thumbnail of the image chosen
 * in the admin, or the theme's 120 px version. '' when there is none.
 */
function frave_term_thumb( WP_Term $term ): string {
	$id = (int) get_term_meta( $term->term_id, 'thumbnail_id', true );
	if ( $id ) {
		return (string) wp_get_attachment_image(
			$id,
			'thumbnail',
			false,
			array(
				'alt'     => '',
				'loading' => 'lazy',
			)
		);
	}
	$path = 'assets/media/categorias/' . $term->slug . '-mini.webp';
	if ( ! file_exists( FRAVE_THEME_DIR . '/' . $path ) ) {
		return '';
	}
	return sprintf( '<img src="%s" width="120" height="120" alt="" loading="lazy" decoding="async">', esc_url( frave_asset_url( $path ) ) );
}

/**
 * Inspiration gallery of a line: image slug (assets/media/{line}/{slug}.webp, 800 × 800
 * with a 400 px version) => caption. Only Sabores has one.
 *
 * @return array<string, string>
 */
function frave_line_gallery( string $slug ): array {
	if ( 'sabores' !== $slug ) {
		return array();
	}
	return array(
		'fresa'             => __( 'Fresa', 'frave' ),
		'maracuya'          => __( 'Maracuyá', 'frave' ),
		'manzana'           => __( 'Manzana', 'frave' ),
		'vainilla'          => __( 'Vainilla', 'frave' ),
		'cafe'              => __( 'Café', 'frave' ),
		'te'                => __( 'Té', 'frave' ),
		'paneton'           => __( 'Panetón', 'frave' ),
		'panaderia'         => __( 'Panadería', 'frave' ),
		'confiteria'        => __( 'Confitería', 'frave' ),
		'bebidas'           => __( 'Bebidas', 'frave' ),
		'productos-lacteos' => __( 'Productos lácteos', 'frave' ),
		'salados'           => __( 'Salados', 'frave' ),
		'cuidado-bucal'     => __( 'Cuidado bucal', 'frave' ),
	);
}

/**
 * Subcategories of a line in WooCommerce's order, each with its own subcategories.
 *
 * @return array<int, array{term: WP_Term, children: WP_Term[]}>
 */
function frave_line_groups( WP_Term $line ): array {
	$groups = array();
	$terms  = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'parent'     => $line->term_id,
			'hide_empty' => false,
		)
	);
	if ( is_wp_error( $terms ) ) {
		return $groups;
	}
	foreach ( $terms as $term ) {
		$children = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'parent'     => $term->term_id,
				'hide_empty' => false,
			)
		);
		$groups[] = array(
			'term'     => $term,
			'children' => is_wp_error( $children ) ? array() : $children,
		);
	}
	return $groups;
}

/** Non-empty lines of a textarea; a lone "-" counts as empty, so editors can hide a list. */
function frave_lines_of( string $text ): array {
	return array_values( array_filter( array_map( 'trim', preg_split( '/\R/u', $text ) ), static fn( $line ) => '' !== $line && '-' !== $line ) );
}

/**
 * A highlight title that starts with a figure ("+500 referencias") becomes a figure and
 * its label; otherwise null.
 *
 * @return array{prefix: string, number: int, suffix: string, label: string}|null
 */
function frave_split_figure( string $title ): ?array {
	if ( ! preg_match( '/^(\+?)(\d{1,6})(\+?|%)\s+(.+)$/u', trim( $title ), $match ) ) {
		return null;
	}
	return array(
		'prefix' => $match[1],
		'number' => (int) $match[2],
		'suffix' => $match[3],
		'label'  => $match[4],
	);
}

/** The other lines that have somewhere to go, for the closing cross-links. */
function frave_other_lines( string $current ): array {
	$others = array();
	foreach ( frave_product_lines() as $slug => $name ) {
		$url = $slug === $current ? '' : frave_line_url( $slug );
		if ( '' === $url ) {
			continue;
		}
		$term     = frave_line_term( $slug );
		$others[] = array(
			'slug'  => $slug,
			'name'  => $term ? $term->name : $name,
			'text'  => frave_home_text( 'frave_home_line_' . $slug ),
			'url'   => $url,
			'image' => frave_line_card_image( $slug ),
		);
	}
	return $others;
}

/** The page's own closing call to action replaces the footer's on line pages. */
function frave_line_hide_footer_cta( bool $show ): bool {
	return is_page_template( FRAVE_TEMPLATE_LINE ) ? false : $show;
}
add_filter( 'frave_show_footer_cta', 'frave_line_hide_footer_cta' );

/** The hero image is the largest element at first paint: fetch it early. */
function frave_line_preload_image( array $resources ): array {
	if ( ! is_page_template( FRAVE_TEMPLATE_LINE ) ) {
		return $resources;
	}
	$images = frave_line_images( frave_line_slug() );
	if ( '' !== $images['desktop'] ) {
		$resources[] = array(
			'href'          => $images['desktop'],
			'as'            => 'image',
			'media'         => '(min-width: 64rem)',
			'fetchpriority' => 'high',
		);
	}
	if ( '' !== $images['mobile'] ) {
		$resources[] = array(
			'href'          => $images['mobile'],
			'as'            => 'image',
			'media'         => '(max-width: 63.999rem)',
			'fetchpriority' => 'high',
		);
	}
	return $resources;
}
add_filter( 'wp_preload_resources', 'frave_line_preload_image' );

/**
 * The line defaults as placeholders in the page's ACF fields, so editors see what shows
 * when a field is empty.
 */
function frave_line_acf_placeholder( array $field ): array {
	// By now ACF has turned 'name' into the input name (acf[field_…]); '_name' keeps the field name.
	$name = (string) ( $field['_name'] ?? $field['name'] );
	if ( ! is_admin() || ! str_starts_with( $name, 'frave_line_' ) ) {
		return $field;
	}
	$page = get_post();
	if ( ! $page instanceof WP_Post || 'page' !== $page->post_type ) {
		return $field;
	}
	$key  = substr( $name, strlen( 'frave_line_' ) );
	$copy = frave_line_copy( frave_line_slug( $page ) );
	if ( is_string( $copy[ $key ] ?? null ) && '' !== $copy[ $key ] ) {
		$field['placeholder'] = $copy[ $key ];
	}
	return $field;
}
add_filter( 'acf/prepare_field', 'frave_line_acf_placeholder' );
