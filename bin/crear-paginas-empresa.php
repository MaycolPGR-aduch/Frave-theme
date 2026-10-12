<?php
/**
 * Company pages: /nosotros/ (template "Nosotros") and /experticia/ (template "Experticia").
 * Their copy comes from the theme (inc/company.php) until it is edited in ACF.
 *
 * From the site folder, with the theme's clone (bin/ is not deployed with the theme):
 *   wp eval-file ~/repos/frave-theme/bin/crear-paginas-empresa.php
 *
 * Safe to run again. An existing page with the default template gets the company
 * template; a page with another template is left as it is.
 */

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	exit( 'Run with WP-CLI only.' );
}

$frave_company_pages = array(
	'nosotros'   => array( 'Nosotros', 'page-templates/nosotros.php' ),
	'experticia' => array( 'Experticia', 'page-templates/experticia.php' ),
);

foreach ( $frave_company_pages as $frave_slug => list( $frave_title, $frave_template ) ) {
	$frave_page = get_page_by_path( $frave_slug );
	if ( $frave_page instanceof WP_Post ) {
		$frave_current = get_page_template_slug( $frave_page );
		if ( $frave_template === $frave_current ) {
			WP_CLI::log( "La página «{$frave_page->post_title}» ya existe." );
		} elseif ( '' === $frave_current || 'default' === $frave_current ) {
			update_post_meta( $frave_page->ID, '_wp_page_template', $frave_template );
			WP_CLI::log( "La página «{$frave_page->post_title}» ahora usa la plantilla «{$frave_title}»." );
		} else {
			WP_CLI::warning( "La página /{$frave_slug}/ usa otra plantilla ({$frave_current}); no se cambia." );
		}
		if ( 'publish' !== $frave_page->post_status ) {
			WP_CLI::warning( "La página /{$frave_slug}/ no está publicada." );
		}
		continue;
	}
	$frave_id = wp_insert_post(
		array(
			'post_type'   => 'page',
			'post_status' => 'publish',
			'post_title'  => $frave_title,
			'post_name'   => $frave_slug,
		),
		true
	);
	if ( is_wp_error( $frave_id ) ) {
		WP_CLI::warning( "No se pudo crear «{$frave_title}»: " . $frave_id->get_error_message() );
		continue;
	}
	update_post_meta( $frave_id, '_wp_page_template', $frave_template );
	WP_CLI::log( "Página creada: {$frave_title} (/{$frave_slug}/)" );
}
WP_CLI::success( 'Páginas de empresa listas.' );
