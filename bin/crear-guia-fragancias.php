<?php
/**
 * Fragrance guide pages, under the Fragancias line page when it exists:
 * - /fragancias/familias-olfativas/ (template "Familias olfativas", inc/fragrance-families.php)
 * - /fragancias/piramide-olfativa/ (template "Pirámide olfativa", inc/fragrance-pyramid.php)
 * Their copy comes from the theme.
 *
 * From the site folder (cd ~/domains/frave.pe/public_html), with the theme's clone
 * (bin/ is not deployed with the theme):
 *   wp eval-file ~/repos/frave-theme/bin/crear-guia-fragancias.php
 *
 * Safe to run again: a page that already uses its template is left as it is.
 */

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	exit( 'Run with WP-CLI only.' );
}

$frave_guide_pages = array(
	'familias-olfativas' => array( 'Familias olfativas', 'page-templates/familias-olfativas.php' ),
	'piramide-olfativa'  => array( 'Pirámide olfativa', 'page-templates/piramide-olfativa.php' ),
);

$frave_parent = frave_line_page( 'fragancias' );
$frave_order  = 0;
foreach ( $frave_guide_pages as $frave_slug => list( $frave_title, $frave_template ) ) {
	++$frave_order;
	$frave_existing = frave_page_with_template( $frave_template );
	if ( $frave_existing ) {
		WP_CLI::log( "La página «{$frave_existing->post_title}» ya existe: " . get_permalink( $frave_existing ) );
		continue;
	}

	$frave_path = ( $frave_parent ? get_page_uri( $frave_parent ) . '/' : '' ) . $frave_slug;
	if ( get_page_by_path( $frave_path ) instanceof WP_Post ) {
		WP_CLI::warning( "/{$frave_path}/ ya existe con otra plantilla; asígnale «{$frave_title}» desde el editor." );
		continue;
	}

	$frave_id = wp_insert_post(
		array(
			'post_type'   => 'page',
			'post_status' => 'publish',
			'post_title'  => $frave_title,
			'post_name'   => $frave_slug,
			'post_parent' => $frave_parent ? $frave_parent->ID : 0,
			'menu_order'  => $frave_order,
		),
		true
	);
	if ( is_wp_error( $frave_id ) ) {
		WP_CLI::warning( "No se pudo crear «{$frave_title}»: " . $frave_id->get_error_message() );
		continue;
	}
	update_post_meta( $frave_id, '_wp_page_template', $frave_template );
	WP_CLI::log( "Página creada: {$frave_title} (" . get_permalink( $frave_id ) . ')' );
}
WP_CLI::success( 'Guía de fragancias lista.' );
