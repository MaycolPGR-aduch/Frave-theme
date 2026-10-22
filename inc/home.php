<?php
/**
 * Home page: default copy, editable through the ACF group "Inicio Frave" (acf-json),
 * helpers for highlighted words and the hero video.
 *
 * The defaults below are the single source of the home copy: templates fall back to them
 * and ACF shows them as default values in the editor.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Screens that get the portrait hero video and its still (phones). */
const FRAVE_HERO_PORTRAIT_QUERY = '(max-width: 39.99rem)';

/**
 * Default copy of every home field. Wrap a word in *asterisks* to highlight it; in the hero
 * title, *a|b|c* are words that take turns in the same place (frave_hero_title_html()).
 */
function frave_home_defaults(): array {
	return array(
		'frave_home_hero_eyebrow'     => __( 'Fragancias · Sabores · Envases', 'frave' ),
		'frave_home_hero_title'       => __( 'Fragancias y sabores que *conectan con|distinguen a|elevan|dan vida a* tu marca', 'frave' ),
		'frave_home_hero_text'        => __( 'Te conectamos con fabricantes internacionales de fragancias y sabores, con asesoría técnica para cada producto.', 'frave' ),
		'frave_home_hero_facts'       => __( "+500 referencias\nAsesoría técnica\nFabricantes internacionales", 'frave' ),
		'frave_home_hero_primary'     => __( 'Solicitar asesoría', 'frave' ),
		'frave_home_hero_secondary'   => __( 'Ver el catálogo', 'frave' ),

		'frave_home_lines_eyebrow'    => __( 'Nuestras líneas', 'frave' ),
		'frave_home_lines_title'      => __( 'Tres líneas para dar vida a *tus productos*', 'frave' ),
		'frave_home_lines_text'       => __( 'Conoce cada línea y su portafolio, o cuéntanos tu proyecto y te orientamos.', 'frave' ),
		'frave_home_line_sabores'     => __( 'Saborización para bebidas, alimentos, lácteos y salud bucal.', 'frave' ),
		'frave_home_line_fragancias'  => __( 'Más de 500 referencias para perfumería, cosmética, cuidado del hogar y ambientación.', 'frave' ),
		'frave_home_line_envases'     => __( 'Frascos, válvulas y accesorios para perfumería, cosmética y cuidado personal.', 'frave' ),

		'frave_home_steps_eyebrow'    => __( 'Cómo trabajamos', 'frave' ),
		'frave_home_steps_title'      => __( 'Tu proyecto, *paso a paso*', 'frave' ),
		'frave_home_steps_text'       => __( 'Te acompañamos desde la primera consulta hasta que los insumos llegan a tu taller o planta.', 'frave' ),
		'frave_home_step_1_title'     => __( 'Conversamos', 'frave' ),
		'frave_home_step_1_text'      => __( 'Nos cuentas qué estás creando, para quién y en qué cantidades.', 'frave' ),
		'frave_home_step_2_title'     => __( 'Proponemos', 'frave' ),
		'frave_home_step_2_text'      => __( 'Te recomendamos sabores, fragancias o envases del catálogo y preparamos tu cotización.', 'frave' ),
		'frave_home_step_3_title'     => __( 'Entregamos', 'frave' ),
		'frave_home_step_3_text'      => __( 'Coordinamos presentaciones, plazos y despacho para que tu producción avance.', 'frave' ),

		'frave_home_featured_eyebrow' => __( 'Selección Frave', 'frave' ),
		'frave_home_featured_title'   => __( 'Productos *destacados*', 'frave' ),

		'frave_home_reasons_eyebrow'  => __( 'Por qué Frave', 'frave' ),
		'frave_home_reasons_title'    => __( 'Razones para *elegirnos*', 'frave' ),
		'frave_home_reasons_text'     => __( 'Lo que nos convierte en el socio comercial de las marcas que buscan diferenciarse.', 'frave' ),
		'frave_home_reason_1_icon'    => 'lab',
		'frave_home_reason_1_title'   => __( 'Asesoría técnica especializada', 'frave' ),
		'frave_home_reason_1_text'    => __( 'Te acompañamos en el desarrollo de tus productos con conocimiento técnico de la industria.', 'frave' ),
		'frave_home_reason_2_icon'    => 'link',
		'frave_home_reason_2_title'   => __( 'Fabricantes internacionales', 'frave' ),
		'frave_home_reason_2_text'    => __( 'Te conectamos con fabricantes de fragancias y sabores de reconocido prestigio.', 'frave' ),
		'frave_home_reason_3_icon'    => 'fragrance',
		'frave_home_reason_3_title'   => __( 'Más de 500 referencias', 'frave' ),
		'frave_home_reason_3_text'    => __( 'Un portafolio de fragancias y esencias hidrosolubles para diversas industrias.', 'frave' ),
		'frave_home_reason_4_icon'    => 'users',
		'frave_home_reason_4_title'   => __( 'Relaciones de largo plazo', 'frave' ),
		'frave_home_reason_4_text'    => __( 'Basadas en el respaldo, la confianza y la eficiencia operativa.', 'frave' ),

		'frave_home_catalog_eyebrow'  => __( 'Catálogo', 'frave' ),
		'frave_home_catalog_title'    => __( 'Encuentra lo que *tu producto* necesita', 'frave' ),
		'frave_home_catalog_text'     => __( 'Busca por nombre o explora por línea, familia olfativa y presentación.', 'frave' ),

		'frave_home_posts_eyebrow'    => __( 'Tendencias', 'frave' ),
		'frave_home_posts_title'      => __( 'Ideas para *tu próxima creación*', 'frave' ),

		'frave_home_location_eyebrow' => __( 'Dónde estamos', 'frave' ),
		'frave_home_location_title'   => __( 'Visítanos o *escríbenos*', 'frave' ),
		'frave_home_location_text'    => __( 'Te atendemos en nuestro local y por todos nuestros canales.', 'frave' ),
	);
}

/** A home field from ACF, or its default. */
function frave_home_text( string $name ): string {
	$defaults = frave_home_defaults();
	return (string) frave_field( $name, $defaults[ $name ] ?? '' );
}

/** Show the defaults in the ACF editor too, so the copy lives in one place. */
function frave_home_acf_defaults( array $field ): array {
	$defaults = frave_home_defaults();
	if ( isset( $defaults[ $field['name'] ] ) && '' === (string) ( $field['default_value'] ?? '' ) ) {
		$field['default_value'] = $defaults[ $field['name'] ];
	}
	return $field;
}
add_filter( 'acf/load_field', 'frave_home_acf_defaults' );

/**
 * Escape a title and turn *words* into the highlight: bold elsewhere; in hero titles
 * ('accent') the brand orange in bold, and 'pill' for an orange pill. Line breaks become <br>.
 */
function frave_emphasis( string $text, string $style = 'strong' ): string {
	$html               = nl2br( esc_html( $text ), false );
	$tags               = array(
		'accent' => array( '<span class="accent-word">', '</span>' ),
		'pill'   => array( '<span class="pill">', '</span>' ),
	);
	list( $open, $end ) = $tags[ $style ] ?? array( '<strong>', '</strong>' );
	return (string) preg_replace( '/\*([^*]+)\*/u', $open . '$1' . $end, $html );
}

/**
 * The hero title. *a|b|c* marks words that take turns in the same place: the first one is
 * the one screen readers read and the one shown without motion. The title breaks before the
 * word that leads into them and after them («Fragancias y sabores / que conectan con /
 * tu marca»), so they always close a line and a shorter word leaves no gap. Without «|»,
 * the *word* is highlighted in place, as in the other heroes.
 */
function frave_hero_title_html( string $text ): string {
	if ( 1 !== preg_match( '/^(.*?)\*([^*]*\|[^*]*)\*(.*)$/su', $text, $parts ) ) {
		return frave_emphasis( $text, 'accent' );
	}
	$words = array_values( array_filter( array_map( 'trim', explode( '|', $parts[2] ) ), 'strlen' ) );
	if ( ! $words ) {
		return frave_emphasis( str_replace( '|', '', $text ), 'accent' );
	}
	$before = trim( (string) preg_replace( '/\s+/u', ' ', frave_plain( $parts[1] ) ) );
	$after  = trim( (string) preg_replace( '/\s+/u', ' ', frave_plain( $parts[3] ) ) );
	$cut    = strrpos( $before, ' ' );
	$head   = false === $cut ? '' : substr( $before, 0, $cut );
	$lead   = false === $cut ? $before : substr( $before, $cut + 1 );

	$items = '';
	foreach ( $words as $index => $word ) {
		$items .= sprintf( '<span class="hero-rotator__word%s">%s</span>', 0 === $index ? ' is-active' : '', esc_html( $word ) );
	}
	$visual  = '' !== $head ? esc_html( $head ) . '<br>' : '';
	$visual .= '' !== $lead ? esc_html( $lead ) . ' ' : '';
	$visual .= '<span class="hero-rotator" data-hero-rotator>' . $items . '</span>';
	$visual .= '' !== $after ? '<br>' . esc_html( $after ) : '';

	return sprintf(
		'<span class="screen-reader-text">%1$s</span><span class="home-hero__title-text" aria-hidden="true">%2$s</span>',
		esc_html( trim( $before . ' ' . $words[0] . ' ' . $after ) ),
		$visual
	);
}

/** Text without the highlight markers, for attributes and plain contexts. */
function frave_plain( string $text ): string {
	return str_replace( '*', '', $text );
}

/**
 * Hero video: the files chosen in ACF, or the theme's defaults (assets/media). With the
 * defaults, phones get a portrait version too (FRAVE_HERO_PORTRAIT_QUERY): the same fruit
 * fall in the lower half of a 9:16 frame, made from the landscape video.
 *
 * @return array{sources: array<int, array{src: string, type: string}>, poster: string, portrait: array{sources: array<int, array{src: string, type: string}>, poster: string}|null}
 */
function frave_home_hero_media(): array {
	$video  = frave_field( 'frave_home_hero_video' );
	$poster = frave_field( 'frave_home_hero_poster' );

	$video_url  = is_array( $video ) ? (string) ( $video['url'] ?? '' ) : ( is_numeric( $video ) ? (string) wp_get_attachment_url( (int) $video ) : '' );
	$poster_url = is_array( $poster ) ? (string) ( $poster['url'] ?? '' ) : ( is_numeric( $poster ) ? (string) wp_get_attachment_image_url( (int) $poster, 'full' ) : '' );

	if ( '' !== $video_url ) {
		$sources = array(
			array(
				'src'  => $video_url,
				'type' => 'video/mp4',
			),
		);
	} else {
		$sources = array(
			array(
				'src'  => frave_asset_url( 'assets/media/hero-frutas-v2.webm' ),
				'type' => 'video/webm',
			),
			array(
				'src'  => frave_asset_url( 'assets/media/hero-frutas-v2.mp4' ),
				'type' => 'video/mp4',
			),
		);
	}

	$portrait = '' === $video_url ? array(
		'sources' => array(
			array(
				'src'  => frave_asset_url( 'assets/media/hero-frutas-v2-movil.webm' ),
				'type' => 'video/webm',
			),
			array(
				'src'  => frave_asset_url( 'assets/media/hero-frutas-v2-movil.mp4' ),
				'type' => 'video/mp4',
			),
		),
		'poster'  => frave_asset_url( 'assets/media/hero-frutas-v2-movil-poster.jpg' ),
	) : null;

	return array(
		'sources'  => $sources,
		'poster'   => '' !== $poster_url ? $poster_url : frave_asset_url( 'assets/media/hero-frutas-v2-poster.jpg' ),
		'portrait' => $portrait,
	);
}

/**
 * The hero poster is the largest element at first paint: fetch it early on the home page,
 * only the one each screen shows.
 */
function frave_home_preload_poster( array $resources ): array {
	if ( ! is_front_page() ) {
		return $resources;
	}
	$media = frave_home_hero_media();
	$still = array(
		'href'          => $media['poster'],
		'as'            => 'image',
		'fetchpriority' => 'high',
	);
	if ( $media['portrait'] ) {
		$resources[]    = array(
			'href'          => $media['portrait']['poster'],
			'as'            => 'image',
			'fetchpriority' => 'high',
			'media'         => FRAVE_HERO_PORTRAIT_QUERY,
		);
		$still['media'] = '(min-width: 40rem)';
	}
	$resources[] = $still;
	return $resources;
}
add_filter( 'wp_preload_resources', 'frave_home_preload_poster' );

/**
 * The three product lines with their link (line page, else category, else catalog), image
 * (category image, else the theme's) and description, so the home page always presents
 * the business.
 *
 * @return array<int, array{slug: string, name: string, text: string, url: string, has_page: bool, image_id: int, image: string, icon: string}>
 */
function frave_home_lines(): array {
	$lines   = array();
	$catalog = class_exists( 'WooCommerce' ) ? frave_catalog_shop_url() : home_url( '/' );
	foreach ( frave_product_lines() as $slug => $name ) {
		$term    = frave_line_term( $slug );
		$link    = frave_line_url( $slug );
		$lines[] = array(
			'slug'     => $slug,
			'name'     => $term ? $term->name : $name,
			'text'     => frave_home_text( 'frave_home_line_' . $slug ),
			'url'      => '' !== $link ? $link : $catalog,
			'has_page' => null !== frave_line_page( $slug ),
			'image_id' => $term ? (int) get_term_meta( $term->term_id, 'thumbnail_id', true ) : 0,
			'image'    => frave_line_card_image( $slug ),
			'icon'     => frave_product_line_icon( $slug ),
		);
	}
	return $lines;
}

/** Icons editors can choose for the "Razones para elegirnos" cards. */
function frave_home_reason_icons(): array {
	return array(
		'chat'      => __( 'Conversación', 'frave' ),
		'link'      => __( 'Alianza', 'frave' ),
		'target'    => __( 'Objetivo', 'frave' ),
		'bulb'      => __( 'Idea', 'frave' ),
		'sparkle'   => __( 'Destello', 'frave' ),
		'quality'   => __( 'Calidad', 'frave' ),
		'clock'     => __( 'Reloj', 'frave' ),
		'lab'       => __( 'Laboratorio', 'frave' ),
		'truck'     => __( 'Despacho', 'frave' ),
		'users'     => __( 'Personas', 'frave' ),
		'leaf'      => __( 'Natural', 'frave' ),
		'flavor'    => __( 'Sabores', 'frave' ),
		'fragrance' => __( 'Fragancias', 'frave' ),
		'packaging' => __( 'Envases', 'frave' ),
	);
}

/** Choices of the ACF icon fields come from the list above. */
function frave_home_acf_icon_choices( array $field ): array {
	$field['choices'] = frave_home_reason_icons();
	return $field;
}
foreach ( array( 1, 2, 3, 4 ) as $frave_reason ) {
	add_filter( 'acf/load_field/name=frave_home_reason_' . $frave_reason . '_icon', 'frave_home_acf_icon_choices' );
}
unset( $frave_reason );
