<?php
/**
 * Global product attributes for the three lines, so the catalog filters work: each one
 * becomes a filter section on the catalog and category pages whenever a product in view
 * uses it. Suggested values are created too; the import adds any other value it finds.
 *
 * From the site folder, with the theme's clone (bin/ is not deployed with the theme):
 *   wp eval-file ~/repos/frave-theme/bin/crear-atributos.php
 *
 * Safe to run again: existing attributes and values are kept.
 */

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	exit( 'Run with WP-CLI only.' );
}
if ( ! function_exists( 'wc_create_attribute' ) ) {
	WP_CLI::error( 'WooCommerce no está activo.' );
}

/**
 * Attribute slug (without pa_) => [ name, suggested values ]. Filter order follows this list.
 *
 * @return array<string, array{0: string, 1: string[]}>
 */
function frave_attributes_list(): array {
	return array(
		'presentacion'     => array( 'Presentación', array() ),
		'familia-olfativa' => array( 'Familia olfativa', array( 'Cítrica', 'Floral', 'Frutal', 'Amaderada', 'Oriental', 'Aromática', 'Gourmand', 'Fresca', 'Especiada' ) ),
		'perfil-de-sabor'  => array( 'Perfil de sabor', array( 'Frutal', 'Cítrico', 'Lácteo', 'Dulce', 'Tostado', 'Especiado', 'Salado', 'Mentolado' ) ),
		'aplicacion'       => array( 'Aplicación', array( 'Perfumería fina', 'Body splash', 'Cremas y lociones', 'Jabones', 'Shampoo y acondicionador', 'Detergentes y suavizantes', 'Limpiadores', 'Velas', 'Difusores', 'Ambientadores', 'Bebidas', 'Lácteos', 'Confitería', 'Panadería', 'Snacks', 'Cuidado bucal' ) ),
		'solubilidad'      => array( 'Solubilidad', array( 'Hidrosoluble', 'Liposoluble' ) ),
		'capacidad'        => array( 'Capacidad', array() ),
		'material'         => array( 'Material', array( 'Vidrio', 'PET', 'Polipropileno', 'Aluminio' ) ),
		'cierre'           => array( 'Cierre', array( 'Válvula atomizadora', 'Bomba dosificadora', 'Tapa rosca', 'Tapa a presión', 'Gotero' ) ),
	);
}

$frave_existing = array();
foreach ( wc_get_attribute_taxonomies() as $frave_taxonomy ) {
	$frave_existing[ $frave_taxonomy->attribute_name ] = (int) $frave_taxonomy->attribute_id;
}

foreach ( frave_attributes_list() as $frave_slug => list( $frave_name, $frave_values ) ) {
	if ( isset( $frave_existing[ $frave_slug ] ) ) {
		WP_CLI::log( "El atributo «{$frave_name}» ya existe." );
	} else {
		$frave_id = wc_create_attribute(
			array(
				'name'         => $frave_name,
				'slug'         => $frave_slug,
				'type'         => 'select',
				'order_by'     => 'menu_order',
				'has_archives' => false,
			)
		);
		if ( is_wp_error( $frave_id ) ) {
			WP_CLI::warning( "No se pudo crear «{$frave_name}»: " . $frave_id->get_error_message() );
			continue;
		}
		WP_CLI::log( "Atributo creado: {$frave_name}" );
	}

	// The taxonomy of a new attribute is registered on the next request; register it now.
	$frave_taxonomy = wc_attribute_taxonomy_name( $frave_slug );
	if ( ! taxonomy_exists( $frave_taxonomy ) ) {
		register_taxonomy( $frave_taxonomy, 'product', array( 'hierarchical' => false ) );
	}
	foreach ( $frave_values as $frave_order => $frave_value ) {
		if ( term_exists( $frave_value, $frave_taxonomy ) ) {
			continue;
		}
		$frave_term = wp_insert_term( $frave_value, $frave_taxonomy );
		if ( ! is_wp_error( $frave_term ) ) {
			update_term_meta( (int) $frave_term['term_id'], 'order', $frave_order );
		}
	}
}
// Sizes already created (by an earlier import) get their small-to-large order (theme, inc/product-page.php).
if ( function_exists( 'frave_measure_order' ) ) {
	foreach ( frave_measure_attributes() as $frave_taxonomy ) {
		$frave_terms = taxonomy_exists( $frave_taxonomy ) ? get_terms(
			array(
				'taxonomy'   => $frave_taxonomy,
				'hide_empty' => false,
			)
		) : array();
		foreach ( is_wp_error( $frave_terms ) ? array() : $frave_terms as $frave_term ) {
			update_term_meta( $frave_term->term_id, 'order', frave_measure_order( $frave_term->name ) );
		}
	}
}
WP_CLI::success( 'Atributos listos.' );
