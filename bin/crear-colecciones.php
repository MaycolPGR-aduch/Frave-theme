<?php
/**
 * Collections (inc/collections.php): the WooCommerce attribute «Colección» with archives,
 * the first collections of the Fragancias line with their subcategory and description, and
 * the hub page /colecciones/ (template «Colecciones»).
 *
 * From the site folder (cd ~/domains/frave.pe/public_html), with the theme's clone, then
 * refresh the addresses so /coleccion/… works:
 *   wp eval-file ~/repos/frave-theme/bin/crear-colecciones.php
 *   wp rewrite flush
 *
 * Safe to run again: existing collections keep their name, description and subcategory.
 * The aromas (products) are assigned to a collection in each product, attribute «Colección».
 */

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	exit( 'Run with WP-CLI only.' );
}

// slug => [ name, subcategory slug, description ].
$frave_collections_list = array(
	'shampoo-y-acondicionador'       => array( 'Shampoo y acondicionador', 'cuidado-del-cabello', 'Fragancias florales, cítricas y aromáticas para shampoos y acondicionadores: perfuman durante el lavado y acompañan al cabello después. Flor rosada, flor blanca, lima y lavanda.' ),
	'mascarillas-y-cremas-de-peinar' => array( 'Mascarillas y cremas de peinar', 'cuidado-del-cabello', 'Fragancias para mascarillas, cremas de peinar y tratamientos capilares, pensadas para integrarse en emulsiones: jazmín, argán, aloe y flores rosadas.' ),
	'romero'                         => array( 'Romero', 'cuidado-del-cabello', 'Fragancia herbal y fresca de romero para shampoo, acondicionador, cremas de peinar y perfume capilar: una misma firma aromática para toda una línea capilar.' ),
	'pitahaya-e-hibiscus'            => array( 'Pitahaya e Hibiscus', 'cuidado-del-cabello', 'Fragancia frutal y floral de pitahaya e hibisco para shampoo, acondicionador, cremas de peinar y perfume capilar, con un carácter tropical y luminoso.' ),
	'mascotas'                       => array( 'Mascotas', 'cuidado-de-mascotas', 'Fragancias suaves para shampoos, colonias y productos de higiene para mascotas: algodón fresco, talco, florales ligeros, avena, lavanda y frutales.' ),
	'suavizantes-y-microcapsulas'    => array( 'Suavizantes y microcápsulas', 'cuidado-textil', 'Fragancias para suavizantes y microcápsulas que refuerzan la sensación de ropa limpia y perduran en las prendas después del lavado.' ),
	'detergentes'                    => array( 'Detergentes', 'cuidado-textil', 'Fragancias para detergentes líquidos que transmiten limpieza desde que se abre el envase hasta la ropa seca.' ),
	'limpiadores-de-piso'            => array( 'Limpiadores de piso', 'cuidado-de-superficies', 'Fragancias para limpiadores y desinfectantes multiusos de piso: frutales, florales y aromáticas que dejan el ambiente perfumado después de limpiar.' ),
	'aromas-para-ambientacion'       => array( 'Aromas para ambientación', 'ambientacion', 'Fragancias para difusores de varitas, sprays, aerosoles y velas: brisa cítrica, vainilla, algodón fresco, lavanda, sándalo y cedro.' ),
);

/* ---------- The attribute «Colección», with its own pages ---------- */

$frave_attribute = null;
foreach ( wc_get_attribute_taxonomies() as $frave_taxonomy ) {
	if ( 'coleccion' === $frave_taxonomy->attribute_name ) {
		$frave_attribute = $frave_taxonomy;
	}
}
if ( $frave_attribute ) {
	if ( ! $frave_attribute->attribute_public ) {
		wc_update_attribute(
			(int) $frave_attribute->attribute_id,
			array(
				'name'         => $frave_attribute->attribute_label,
				'slug'         => 'coleccion',
				'type'         => $frave_attribute->attribute_type,
				'order_by'     => 'menu_order',
				'has_archives' => true,
			)
		);
		WP_CLI::log( 'El atributo «Colección» ahora tiene páginas propias.' );
	} else {
		WP_CLI::log( 'El atributo «Colección» ya existe.' );
	}
} else {
	$frave_id = wc_create_attribute(
		array(
			'name'         => 'Colección',
			'slug'         => 'coleccion',
			'type'         => 'select',
			'order_by'     => 'menu_order',
			'has_archives' => true,
		)
	);
	if ( is_wp_error( $frave_id ) ) {
		WP_CLI::error( 'No se pudo crear el atributo «Colección»: ' . $frave_id->get_error_message() );
	}
	WP_CLI::log( 'Atributo creado: Colección' );
}
// The taxonomy of a new attribute is registered on the next request; register it now.
if ( ! taxonomy_exists( 'pa_coleccion' ) ) {
	register_taxonomy(
		'pa_coleccion',
		'product',
		array(
			'hierarchical' => false,
			'public'       => true,
			'query_var'    => true,
			'rewrite'      => array( 'slug' => 'coleccion' ),
		)
	);
}

/* ---------- The collections ---------- */

$frave_order = 0;
foreach ( $frave_collections_list as $frave_slug => list( $frave_name, $frave_category_slug, $frave_description ) ) {
	$frave_category = get_term_by( 'slug', $frave_category_slug, 'product_cat' );
	$frave_term     = get_term_by( 'slug', $frave_slug, 'pa_coleccion' );
	if ( $frave_term instanceof WP_Term ) {
		WP_CLI::log( "La colección «{$frave_term->name}» ya existe." );
		$frave_term_id = (int) $frave_term->term_id;
	} else {
		$frave_new = wp_insert_term(
			$frave_name,
			'pa_coleccion',
			array(
				'slug'        => $frave_slug,
				'description' => $frave_description,
			)
		);
		if ( is_wp_error( $frave_new ) ) {
			WP_CLI::warning( "No se pudo crear «{$frave_name}»: " . $frave_new->get_error_message() );
			continue;
		}
		$frave_term_id = (int) $frave_new['term_id'];
		update_term_meta( $frave_term_id, 'order', $frave_order );
		WP_CLI::log( "Colección creada: {$frave_name}" );
	}
	++$frave_order;
	if ( ! get_term_meta( $frave_term_id, 'frave_category', true ) ) {
		if ( $frave_category instanceof WP_Term ) {
			update_term_meta( $frave_term_id, 'frave_category', (int) $frave_category->term_id );
		} else {
			WP_CLI::warning( "Falta la subcategoría «{$frave_category_slug}» para «{$frave_name}» (ejecuta antes bin/crear-lineas.php)." );
		}
	}
}

/* ---------- The hub /colecciones/ ---------- */

$frave_hub = get_page_by_path( 'colecciones' );
if ( $frave_hub instanceof WP_Post ) {
	WP_CLI::log( 'La página «Colecciones» ya existe.' );
} else {
	$frave_hub_id = wp_insert_post(
		array(
			'post_type'   => 'page',
			'post_status' => 'publish',
			'post_title'  => 'Colecciones',
			'post_name'   => 'colecciones',
		),
		true
	);
	if ( is_wp_error( $frave_hub_id ) ) {
		WP_CLI::warning( 'No se pudo crear la página «Colecciones»: ' . $frave_hub_id->get_error_message() );
	} else {
		update_post_meta( $frave_hub_id, '_wp_page_template', 'page-templates/colecciones.php' );
		WP_CLI::log( 'Página creada: Colecciones (' . get_permalink( $frave_hub_id ) . ')' );
	}
}

WP_CLI::success( 'Colecciones listas. Ejecuta «wp rewrite flush» para activar sus direcciones.' );
