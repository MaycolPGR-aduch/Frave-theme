<?php
/**
 * Guide of olfactory families: /fragancias/familias-olfativas/ (template "Familias
 * olfativas"), under the Fragancias line page when it exists. Its copy comes from the
 * theme (inc/fragrance-families.php).
 *
 * From the site folder, with the theme's clone (bin/ is not deployed with the theme):
 *   wp eval-file ~/repos/frave-theme/bin/crear-familias-olfativas.php
 *
 * Safe to run again: an existing guide is left as it is.
 */

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	exit( 'Run with WP-CLI only.' );
}

$frave_template = 'page-templates/familias-olfativas.php';
$frave_existing = frave_page_with_template( $frave_template );
if ( $frave_existing ) {
	WP_CLI::success( "La guía ya existe: {$frave_existing->post_title} (" . get_permalink( $frave_existing ) . ')' );
	return;
}

$frave_parent = frave_line_page( 'fragancias' );
$frave_path   = ( $frave_parent ? get_page_uri( $frave_parent ) . '/' : '' ) . 'familias-olfativas';
if ( get_page_by_path( $frave_path ) instanceof WP_Post ) {
	WP_CLI::error( "/{$frave_path}/ ya existe con otra plantilla; asígnale «Familias olfativas» desde el editor." );
}

$frave_id = wp_insert_post(
	array(
		'post_type'   => 'page',
		'post_status' => 'publish',
		'post_title'  => 'Familias olfativas',
		'post_name'   => 'familias-olfativas',
		'post_parent' => $frave_parent ? $frave_parent->ID : 0,
	),
	true
);
if ( is_wp_error( $frave_id ) ) {
	WP_CLI::error( 'No se pudo crear la guía: ' . $frave_id->get_error_message() );
}
update_post_meta( $frave_id, '_wp_page_template', $frave_template );
WP_CLI::success( 'Guía creada: ' . get_permalink( $frave_id ) );
