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

/** Default copy of every home field. Wrap a word in *asterisks* to highlight it. */
function frave_home_defaults(): array {
	return array(
		'frave_home_hero_eyebrow'     => __( 'Sabores · Fragancias · Envases', 'frave' ),
		'frave_home_hero_title'       => __( 'Sabores y fragancias que *conectan* con tu marca', 'frave' ),
		'frave_home_hero_text'        => __( 'Conectamos a las marcas de perfumería, cosmética, cuidado personal y del hogar con fabricantes internacionales de fragancias y sabores, con asesoría técnica especializada.', 'frave' ),
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
		'frave_home_reasons_text'     => __( 'Lo que nos piden las marcas que confían en nosotros.', 'frave' ),
		'frave_home_reason_1_icon'    => 'chat',
		'frave_home_reason_1_title'   => __( 'Asesoría cercana', 'frave' ),
		'frave_home_reason_1_text'    => __( 'Te orientamos para elegir lo que mejor funciona en tu producto.', 'frave' ),
		'frave_home_reason_2_icon'    => 'sparkle',
		'frave_home_reason_2_title'   => __( 'Todo en un lugar', 'frave' ),
		'frave_home_reason_2_text'    => __( 'Sabores, fragancias y envases con un solo proveedor.', 'frave' ),
		'frave_home_reason_3_icon'    => 'quality',
		'frave_home_reason_3_title'   => __( 'Calidad cuidada', 'frave' ),
		'frave_home_reason_3_text'    => __( 'Insumos seleccionados para que tu producto rinda como esperas.', 'frave' ),
		'frave_home_reason_4_icon'    => 'clock',
		'frave_home_reason_4_title'   => __( 'Respuesta ágil', 'frave' ),
		'frave_home_reason_4_text'    => __( 'Atendemos tus consultas y cotizaciones con rapidez.', 'frave' ),

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
 * Escape a title and turn *words* into the highlight: a pill in the hero, bold elsewhere.
 * Line breaks become <br>.
 */
function frave_emphasis( string $text, string $style = 'strong' ): string {
	$html = nl2br( esc_html( $text ), false );
	$open = 'pill' === $style ? '<span class="pill">' : '<strong>';
	$end  = 'pill' === $style ? '</span>' : '</strong>';
	return (string) preg_replace( '/\*([^*]+)\*/u', $open . '$1' . $end, $html );
}

/** Text without the highlight markers, for attributes and plain contexts. */
function frave_plain( string $text ): string {
	return str_replace( '*', '', $text );
}

/**
 * Hero video: the files chosen in ACF, or the theme's defaults (assets/media).
 *
 * @return array{sources: array<int, array{src: string, type: string}>, poster: string}
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
				'src'  => frave_asset_url( 'assets/media/hero-frutas.webm' ),
				'type' => 'video/webm',
			),
			array(
				'src'  => frave_asset_url( 'assets/media/hero-frutas.mp4' ),
				'type' => 'video/mp4',
			),
		);
	}

	return array(
		'sources' => $sources,
		'poster'  => '' !== $poster_url ? $poster_url : frave_asset_url( 'assets/media/hero-frutas-poster.jpg' ),
	);
}

/** The hero poster is the largest element at first paint: fetch it early on the home page. */
function frave_home_preload_poster( array $resources ): array {
	if ( is_front_page() ) {
		$resources[] = array(
			'href'          => frave_home_hero_media()['poster'],
			'as'            => 'image',
			'fetchpriority' => 'high',
		);
	}
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
