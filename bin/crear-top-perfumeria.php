<?php
/**
 * The first references of the perfumery tops (inc/top-perfumery.php), in «Top perfumería».
 * Perfumes Frave still has to confirm (which one it is, its subcategory or its notes) are
 * created as drafts; they appear on the site once someone checks them and publishes them.
 *
 * From the site folder (cd ~/domains/frave.pe/public_html), with the theme's clone:
 *   wp eval-file ~/repos/frave-theme/bin/crear-top-perfumeria.php
 *
 * Safe to run again: existing references (by slug, in any status) are left as they are.
 */

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	exit( 'Run with WP-CLI only.' );
}

// Subcategory => audience => [ name, house, family, top notes, heart notes, base notes, why it leads (or ''), publish ].
$frave_top_list = array(
	'perfumeria-disenador' => array(
		'hombre' => array(
			array( 'Invictus', 'Paco Rabanne', 'fresca', 'Pomelo, notas marinas, mandarina', 'Hoja de laurel, jazmín', 'Ámbar gris, madera de guayaco, musgo de roble, pachulí', 'Fresco, marino y energético: uno de los perfiles masculinos más pedidos.', true ),
			array( 'Stronger With You Intense', 'Emporio Armani', 'gourmand', 'Pimienta rosa, enebro, mandarina', 'Toffee, canela, lavanda', 'Vainilla, haba tonka, ámbar', '', true ),
			array( 'Le Male Elixir', 'Jean Paul Gaultier', 'oriental', 'Lavanda, menta', 'Vainilla, benjuí', 'Miel, haba tonka, tabaco', '', true ),
			array( 'Born in Roma Uomo', 'Valentino', 'amaderada', 'Hoja de violeta, sal mineral', 'Salvia', 'Vetiver ahumado', '', false ),
		),
		'mujer'  => array(
			array( 'N°5', 'Chanel', 'floral', 'Aldehídos, ylang-ylang, neroli, bergamota', 'Rosa, jazmín, iris', 'Sándalo, vainilla, vetiver', 'El floral aldehídico clásico: elegante y reconocible en todo el mundo.', true ),
			array( 'Cloud', 'Ariana Grande', 'gourmand', 'Lavanda, pera, bergamota', 'Crema batida, praliné, coco', 'Almizcle, maderas cremosas', '', true ),
			array( 'Romance', 'Ralph Lauren', 'floral', 'Rosa, jengibre, manzanilla', 'Lirio, violeta', 'Almizcle, pachulí, musgo de roble', '', false ),
		),
	),
	'perfumeria-exclusiva' => array(
		'unisex' => array(
			array( 'Imagination', 'Louis Vuitton', 'citrica', 'Cidra, bergamota, naranja', 'Jengibre, canela, neroli', 'Té negro, ambroxan, madera de guayaco, olíbano', 'Cítrico y especiado con té negro: luminoso y elegante.', true ),
			array( 'Ombré Leather', 'Tom Ford', 'amaderada', 'Cardamomo', 'Cuero, jazmín sambac', 'Ámbar, musgo, pachulí', '', true ),
			array( 'Baccarat Rouge 540', 'Maison Francis Kurkdjian', 'oriental', 'Azafrán, jazmín', 'Madera de ámbar, ámbar gris', 'Resina de abeto, cedro', '', true ),
			array( 'Erba Pura', 'Xerjoff', 'frutal', 'Naranja de Sicilia, limón, bergamota', 'Frutas', 'Almizcle blanco, ámbar, vainilla', '', false ),
		),
	),
	'perfumeria-arabe'     => array(
		'hombre' => array(
			array( 'Khamrah Qahwa', 'Lattafa', 'gourmand', 'Canela, cardamomo, jengibre', 'Praliné, frutos secos confitados, flores blancas', 'Vainilla, haba tonka, café arábica, benjuí', 'Café, especias y dulzor: el gourmand árabe del momento.', true ),
			array( '9PM', 'Afnan', 'oriental', 'Manzana, canela, lavanda, bergamota', 'Azahar, lirio de los valles', 'Vainilla, haba tonka, ámbar, pachulí', '', true ),
			array( 'Mandarin Sky', '', 'citrica', 'Mandarina', '', 'Ámbar', '', false ),
			array( 'Asad', 'Lattafa', 'especiada', 'Pimienta negra, tabaco, piña', 'Pachulí, café, iris', 'Vainilla, ámbar, benjuí, ládano', '', true ),
			array( 'Asad Bourbon', 'Lattafa', 'oriental', 'Pimienta rosa, lavanda', 'Vainilla bourbon', 'Ámbar, maderas', '', false ),
		),
		'mujer'  => array(
			array( 'Yara', 'Lattafa', 'gourmand', 'Orquídea, heliotropo, mandarina', 'Acorde gourmand, frutas tropicales', 'Vainilla, almizcle, sándalo', 'Cremoso, dulce y floral: un éxito de la perfumería árabe femenina.', true ),
			array( 'Yara Candy', 'Lattafa', 'gourmand', 'Frutos rojos, mandarina', 'Caramelo', 'Vainilla, almizcle', '', false ),
		),
	),
);

$frave_created = 0;
foreach ( $frave_top_list as $frave_category_slug => $frave_groups ) {
	$frave_category = get_term_by( 'slug', $frave_category_slug, 'product_cat' );
	if ( ! $frave_category instanceof WP_Term ) {
		WP_CLI::warning( "Falta la subcategoría «{$frave_category_slug}» (ejecuta antes bin/crear-lineas.php)." );
		continue;
	}
	foreach ( $frave_groups as $frave_audience => $frave_entries ) {
		foreach ( $frave_entries as $frave_index => $frave_entry ) {
			list( $frave_name, $frave_house, $frave_family, $frave_top, $frave_heart, $frave_base, $frave_reason, $frave_publish ) = $frave_entry;
			$frave_slug = sanitize_title( $frave_name . ( 'Romance' === $frave_name ? '-ralph-lauren' : '' ) );
			if ( get_page_by_path( $frave_slug, OBJECT, 'frave_referencia' ) instanceof WP_Post ) {
				WP_CLI::log( "La referencia «{$frave_name}» ya existe." );
				continue;
			}
			$frave_id = wp_insert_post(
				array(
					'post_type'    => 'frave_referencia',
					'post_status'  => $frave_publish ? 'publish' : 'draft',
					'post_title'   => $frave_name,
					'post_name'    => $frave_slug,
					'post_excerpt' => $frave_reason,
					'menu_order'   => $frave_index + 1,
					'meta_input'   => array(
						'_frave_top_house'       => $frave_house,
						'_frave_top_category'    => (int) $frave_category->term_id,
						'_frave_top_audience'    => $frave_audience,
						'_frave_top_family'      => $frave_family,
						'_frave_top_notes_top'   => $frave_top,
						'_frave_top_notes_heart' => $frave_heart,
						'_frave_top_notes_base'  => $frave_base,
					),
				),
				true
			);
			if ( is_wp_error( $frave_id ) ) {
				WP_CLI::warning( "No se pudo crear «{$frave_name}»: " . $frave_id->get_error_message() );
				continue;
			}
			++$frave_created;
			WP_CLI::log( ( $frave_publish ? 'Publicada' : 'Borrador (por confirmar)' ) . ": {$frave_name}" );
		}
	}
}

WP_CLI::success( "Top de perfumería listo: {$frave_created} referencias nuevas. Revisa los borradores en «Top perfumería» y publícalos cuando Frave los confirme." );
