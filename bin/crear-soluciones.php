<?php
/**
 * Solutions by industry: the hub /soluciones/ (template "Soluciones") and one page per
 * industry under it (template "Solución"), with the slugs inc/solutions.php knows.
 *
 * From the site folder, with the theme's clone (bin/ is not deployed with the theme):
 *   wp eval-file ~/repos/frave-theme/bin/crear-soluciones.php
 *
 * Safe to run again: existing pages are left as they are.
 */

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	exit( 'Run with WP-CLI only.' );
}

$frave_industries = array(
	'perfumeria'                        => 'Perfumería',
	'cosmetica-y-cuidado-personal'      => 'Cosmética y cuidado personal',
	'cuidado-del-hogar'                 => 'Cuidado del hogar',
	'ambientacion-y-marketing-olfativo' => 'Ambientación y marketing olfativo',
	'alimentos-y-bebidas'               => 'Alimentos y bebidas',
);

/** Published page at $path, created with $template when missing. Returns its ID or 0. */
function frave_solutions_page( string $path, string $title, string $template, int $parent_id, int $order ): int {
	$page = get_page_by_path( $path );
	if ( $page instanceof WP_Post ) {
		if ( get_page_template_slug( $page ) !== $template ) {
			WP_CLI::warning( "/{$path}/ ya existe con otra plantilla; no se cambia." );
		} else {
			WP_CLI::log( "La página «{$page->post_title}» ya existe." );
		}
		return (int) $page->ID;
	}
	$id = wp_insert_post(
		array(
			'post_type'   => 'page',
			'post_status' => 'publish',
			'post_title'  => $title,
			'post_name'   => basename( $path ),
			'post_parent' => $parent_id,
			'menu_order'  => $order,
		),
		true
	);
	if ( is_wp_error( $id ) ) {
		WP_CLI::warning( "No se pudo crear «{$title}»: " . $id->get_error_message() );
		return 0;
	}
	update_post_meta( $id, '_wp_page_template', $template );
	WP_CLI::log( "Página creada: {$title} (/{$path}/)" );
	return (int) $id;
}

$frave_hub = frave_solutions_page( 'soluciones', 'Soluciones', 'page-templates/soluciones.php', 0, 0 );
if ( ! $frave_hub ) {
	WP_CLI::error( 'Sin la página Soluciones no se crean las de cada industria.' );
}
$frave_order = 0;
foreach ( $frave_industries as $frave_slug => $frave_title ) {
	frave_solutions_page( 'soluciones/' . $frave_slug, $frave_title, 'page-templates/solucion.php', $frave_hub, ++$frave_order );
}
WP_CLI::success( 'Soluciones listas.' );
