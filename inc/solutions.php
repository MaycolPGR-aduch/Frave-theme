<?php
/**
 * Solutions by industry: a hub page (template "Soluciones", /soluciones/) and one page per
 * industry (template "Solución", /soluciones/<slug>/) that gathers the subcategories of
 * every line that industry uses. The industry is the page's slug.
 *
 * The defaults below build on the copy agreed with Frave's management (October 2026);
 * each text can be replaced per page in the ACF group "Solución Frave". Wrap a word in
 * *asterisks* to highlight it. bin/crear-soluciones.php creates the pages.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const FRAVE_TEMPLATE_SOLUTIONS = 'page-templates/soluciones.php';
const FRAVE_TEMPLATE_SOLUTION  = 'page-templates/solucion.php';

/** Industries, keyed by page slug, in menu order. */
function frave_solutions(): array {
	return array(
		'perfumeria'                        => array(
			'title'        => __( 'Perfumería', 'frave' ),
			'summary'      => __( 'Fragancias de diseñador, árabes y de nicho, con los frascos y válvulas para presentarlas.', 'frave' ),
			'hero_title'   => __( 'Perfumería con *carácter* propio', 'frave' ),
			'lead'         => __( 'Fragancias de diseñador, árabes y exclusivas, y los envases para presentarlas: lo que una marca de perfumería necesita en un solo aliado.', 'frave' ),
			'intro_title'  => __( 'Cada fragancia transmite una historia y *despierta emociones*.', 'frave' ),
			'intro_text'   => __( "La perfumería de diseñador fusiona la moda con el arte de crear fragancias; la árabe combina tradición e intensidad con ingredientes como oud, ámbar, almizcle y rosa; y la de nicho apuesta por composiciones originales y materias primas selectas.\n\nTe acompañamos a elegir el estilo que mejor representa a tu marca y a presentarlo con frascos de vidrio y válvulas a la altura de tu fragancia.", 'frave' ),
			'applications' => __( "Perfumería femenina y masculina\nFragancias EDP y EDT\nFragancias exclusivas y de autor\nBody splash\nLanzamientos especiales\nEdiciones de colección", 'frave' ),
			'cta_title'    => __( '¿Preparas el lanzamiento de *tu perfume*?', 'frave' ),
			'cta_text'     => __( 'Cuéntanos el estilo de tu marca y te proponemos fragancias y envases.', 'frave' ),
			'terms'        => array( 'perfumeria', 'frascos-de-vidrio-perfumeria', 'valvulas' ),
			'icon'         => 'fragrance',
			'image'        => 'assets/media/soluciones/perfumeria.webp',
		),
		'cosmetica-y-cuidado-personal'      => array(
			'title'        => __( 'Cosmética y cuidado personal', 'frave' ),
			'summary'      => __( 'Fragancias para cremas, lociones, jabones y cuidado del cabello, sabores para el cuidado bucal y envases.', 'frave' ),
			'hero_title'   => __( 'Cuidado personal que *se siente*', 'frave' ),
			'lead'         => __( 'Fragancias que aportan identidad a productos de uso diario, sabores para el cuidado bucal y envases que acompañan tu fórmula.', 'frave' ),
			'intro_title'  => __( 'Fragancias diseñadas para *integrarse* en tu fórmula.', 'frave' ),
			'intro_text'   => __( "Las fragancias para el cuidado personal aportan identidad y valor sensorial a productos de uso diario. Se integran armoniosamente en cremas, lociones, jabones líquidos y body splash, y combinan atractivo olfativo, compatibilidad con las formulaciones y calidad.\n\nCompletamos tu propuesta con sabores para el cuidado bucal y envases para cosmética y cuidado personal.", 'frave' ),
			'applications' => __( "Cremas y lociones\nJabones líquidos\nBody splash\nCuidado del cabello\nCuidado de la piel\nCuidado del bebé\nProtección solar\nCuidado bucal", 'frave' ),
			'cta_title'    => __( '¿Desarrollas una línea de *cuidado personal*?', 'frave' ),
			'cta_text'     => __( 'Te ayudamos a elegir fragancias compatibles con tu formulación.', 'frave' ),
			'terms'        => array( 'fragancias-cosmeticas', 'cuidado-bucal', 'envases' ),
			'icon'         => 'leaf',
			'image'        => 'assets/media/soluciones/cosmetica-y-cuidado-personal.webp',
		),
		'cuidado-del-hogar'                 => array(
			'title'        => __( 'Cuidado del hogar', 'frave' ),
			'summary'      => __( 'Fragancias para detergentes, suavizantes, lavavajillas y limpiadores multiusos.', 'frave' ),
			'hero_title'   => __( 'Limpieza que *se percibe*', 'frave' ),
			'lead'         => __( 'Fragancias para detergentes, suavizantes, lavavajillas y limpiadores que refuerzan la percepción de higiene y bienestar.', 'frave' ),
			'intro_title'  => __( 'Las tareas cotidianas, convertidas en una experiencia de *frescura*.', 'frave' ),
			'intro_text'   => __( "Las fragancias para el cuidado del hogar aportan aromas agradables y persistentes que refuerzan la percepción de higiene y bienestar.\n\nLas desarrollamos para detergentes, suavizantes, lavavajillas y limpiadores multiusos, adaptándolas a las necesidades técnicas de cada formulación y aplicación.", 'frave' ),
			'applications' => __( "Detergentes\nSuavizantes\nAcondicionadores textiles\nLavavajillas\nLimpiadores multiusos\nLimpieza de superficies\nDesinfectantes\nNeutralizadores de olores", 'frave' ),
			'cta_title'    => __( '¿Buscas la fragancia para *tu limpiador*?', 'frave' ),
			'cta_text'     => __( 'Cuéntanos tu producto y su base, y te proponemos alternativas.', 'frave' ),
			'terms'        => array( 'fragancias-hogar' ),
			'icon'         => 'sparkle',
			'image'        => 'assets/media/soluciones/cuidado-del-hogar.webp',
		),
		'ambientacion-y-marketing-olfativo' => array(
			'title'        => __( 'Ambientación y marketing olfativo', 'frave' ),
			'summary'      => __( 'Aromas para difusores, velas, vehículos y espacios comerciales, con frascos para difusores.', 'frave' ),
			'hero_title'   => __( 'Espacios con *personalidad*', 'frave' ),
			'lead'         => __( 'Aromas para difusores, velas, reeds, automóviles y ambientadores, y marketing olfativo para que tu marca también se recuerde por su aroma.', 'frave' ),
			'intro_title'  => __( 'Transformamos cada espacio en una *experiencia inolvidable*.', 'frave' ),
			'intro_text'   => __( "Fragancias que inspiran bienestar, frescura y emociones, con aromas versátiles para difusores, velas, reeds, automóviles y ambientadores.\n\nCreamos ambientes únicos, envolventes y llenos de personalidad, y te acompañamos en el marketing olfativo de tus espacios comerciales.", 'frave' ),
			'applications' => __( "Difusores de varitas\nVelas con fragancia\nAmbientadores sólidos\nAerosoles y sprays\nAromatizadores de autos\nMarketing olfativo\nSistemas de difusión", 'frave' ),
			'cta_title'    => __( '¿Quieres que tu marca *se recuerde por su aroma*?', 'frave' ),
			'cta_text'     => __( 'Conversemos sobre tus espacios y el formato de difusión que necesitas.', 'frave' ),
			'terms'        => array( 'ambientacion', 'frascos-para-difusores' ),
			'icon'         => 'pin',
			'image'        => 'assets/media/soluciones/ambientacion-y-marketing-olfativo.webp',
		),
		'alimentos-y-bebidas'               => array(
			'title'        => __( 'Alimentos y bebidas', 'frave' ),
			'summary'      => __( 'Sabores para bebidas, lácteos, confitería, panadería y productos salados.', 'frave' ),
			'hero_title'   => __( 'El sabor que *distingue* tu producto', 'frave' ),
			'lead'         => __( 'Soluciones integrales de saborización para bebidas, lácteos, confitería, panadería y salados, con el respaldo de fabricantes internacionales.', 'frave' ),
			'intro_title'  => __( 'Una experiencia gastronómica superior empieza en el *sabor*.', 'frave' ),
			'intro_text'   => __( "La creación de los sabores se basa en la combinación precisa de componentes aromáticos que se integran de manera armónica con el gusto y la textura.\n\nPriorizamos el conocimiento del mercado y las preferencias de los consumidores, con la innovación en insumos clave, el desarrollo de conceptos y el suministro sostenible de nuestros proveedores.", 'frave' ),
			'applications' => __( "Bebidas\nJugos y néctares\nYogures y lácteos\nHelados\nConfitería\nPanadería y panetones\nSnacks y salados\nCafé y té", 'frave' ),
			'cta_title'    => __( '¿Desarrollas un *nuevo sabor*?', 'frave' ),
			'cta_text'     => __( 'Cuéntanos sobre tu producto y te recomendamos alternativas de nuestro portafolio.', 'frave' ),
			'terms'        => array( 'bebidas-y-lacteos', 'confiteria-y-panaderia', 'salados' ),
			'icon'         => 'flavor',
			'image'        => 'assets/media/soluciones/alimentos-y-bebidas.webp',
		),
	);
}

/** Industry a solution page presents: its slug, when it is a known industry. */
function frave_solution_slug( ?WP_Post $page = null ): string {
	$page = $page ? $page : get_post();
	return $page instanceof WP_Post && isset( frave_solutions()[ $page->post_name ] ) ? $page->post_name : '';
}

/** A text of the current solution page: the page's ACF field, or the default. */
function frave_solution_text( string $key, string $slug ): string {
	$value = frave_field( 'frave_solution_' . $key );
	if ( is_string( $value ) && '' !== trim( $value ) ) {
		return $value;
	}
	return (string) ( frave_solutions()[ $slug ][ $key ] ?? '' );
}

/**
 * Published solution pages, keyed by industry, in the order of frave_solutions().
 *
 * @return array<string, WP_Post>
 */
function frave_solution_pages(): array {
	static $pages = null;
	if ( null !== $pages ) {
		return $pages;
	}
	$found = array();
	foreach ( get_pages(
		array(
			'meta_key'     => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery -- small, cached query on pages.
			'meta_value'   => FRAVE_TEMPLATE_SOLUTION, // phpcs:ignore WordPress.DB.SlowDBQuery
			'post_status'  => 'publish',
			// Industry pages hang from the hub, which this query does not return.
			'hierarchical' => false,
		)
	) as $page ) {
		$slug = frave_solution_slug( $page );
		if ( '' !== $slug ) {
			$found[ $slug ] = $page;
		}
	}
	$pages = array_intersect_key( array_replace( frave_solutions(), $found ), $found );
	return $pages;
}

/** The hub page (template "Soluciones"), or ''. */
function frave_solutions_url(): string {
	$page = frave_page_with_template( FRAVE_TEMPLATE_SOLUTIONS );
	return $page ? (string) get_permalink( $page ) : '';
}

/**
 * Product categories an industry uses, in order, skipping those not created yet.
 *
 * @return WP_Term[]
 */
function frave_solution_terms( string $slug ): array {
	$terms = array();
	foreach ( frave_solutions()[ $slug ]['terms'] ?? array() as $term_slug ) {
		$term = frave_line_term( $term_slug );
		if ( $term ) {
			$terms[] = $term;
		}
	}
	return $terms;
}

/** Name of the product line a category belongs to (its top-level ancestor). */
function frave_term_line_name( WP_Term $term ): string {
	$ancestors = get_ancestors( $term->term_id, 'product_cat', 'taxonomy' );
	$top       = $ancestors ? get_term( (int) end( $ancestors ), 'product_cat' ) : $term;
	return $top instanceof WP_Term ? $top->name : '';
}

/**
 * Cards of the industries with a published page, for the hub and the closing links.
 *
 * @return array<int, array{slug: string, title: string, summary: string, icon: string, url: string}>
 */
function frave_solution_cards( string $except = '' ): array {
	$cards = array();
	foreach ( frave_solution_pages() as $slug => $page ) {
		if ( $slug === $except ) {
			continue;
		}
		$solution = frave_solutions()[ $slug ];
		$cards[]  = array(
			'slug'    => $slug,
			'title'   => get_the_title( $page ),
			'summary' => $solution['summary'],
			'icon'    => $solution['icon'],
			'url'     => (string) get_permalink( $page ),
		);
	}
	return $cards;
}

/** These pages close with their own call to action instead of the footer's. */
function frave_solutions_hide_footer_cta( bool $show ): bool {
	return is_page_template( array( FRAVE_TEMPLATE_SOLUTIONS, FRAVE_TEMPLATE_SOLUTION ) ) ? false : $show;
}
add_filter( 'frave_show_footer_cta', 'frave_solutions_hide_footer_cta' );

/** The defaults as placeholders in the page's ACF fields. */
function frave_solution_acf_placeholder( array $field ): array {
	// By now ACF has turned 'name' into the input name (acf[field_…]); '_name' keeps the field name.
	$name = (string) ( $field['_name'] ?? $field['name'] );
	if ( ! is_admin() || ! str_starts_with( $name, 'frave_solution_' ) ) {
		return $field;
	}
	$page = get_post();
	$slug = $page instanceof WP_Post ? frave_solution_slug( $page ) : '';
	$key  = substr( $name, strlen( 'frave_solution_' ) );
	$copy = frave_solutions()[ $slug ][ $key ] ?? '';
	if ( is_string( $copy ) && '' !== $copy ) {
		$field['placeholder'] = $copy;
	}
	return $field;
}
add_filter( 'acf/prepare_field', 'frave_solution_acf_placeholder' );
