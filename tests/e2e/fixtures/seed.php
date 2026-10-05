<?php
/**
 * E2E fixture: a minimal, self-contained Frave store for the Playwright tests.
 *
 * Runs in the disposable wp-env site of the CI (see .github/workflows/ci.yml):
 *   npx wp-env run cli wp eval-file wp-content/themes/frave/tests/e2e/fixtures/seed.php
 * It is idempotent, but it changes store settings and creates [E2E] content:
 * never run it on a real store.
 */

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	exit( 'Run with WP-CLI only.' );
}

/* ---------- Store settings ---------- */

$settings = array(
	'blogname'                                 => 'Frave E2E',
	'timezone_string'                          => 'America/Lima',
	'woocommerce_coming_soon'                  => 'no',
	'woocommerce_store_pages_only'             => 'no',
	'woocommerce_currency'                     => 'PEN',
	'woocommerce_default_country'              => 'PE:LIM',
	'woocommerce_store_address'                => 'Av. de Prueba 123',
	'woocommerce_store_city'                   => 'Lima',
	'woocommerce_store_postcode'               => '15001',
	'woocommerce_default_customer_address'     => 'base',
	'woocommerce_calc_taxes'                   => 'no',
	'woocommerce_enable_guest_checkout'        => 'yes',
	'woocommerce_enable_ajax_add_to_cart'      => 'yes',
	'woocommerce_cart_redirect_after_add'      => 'no',
	'woocommerce_hide_out_of_stock_items'      => 'no',
	'woocommerce_cod_settings'                 => array(
		'enabled'            => 'yes',
		'title'              => 'Contra entrega',
		'description'        => 'Pago al recibir el pedido (prueba).',
		'instructions'       => '',
		'enable_for_methods' => array(),
		'enable_for_virtual' => 'yes',
	),
);
foreach ( $settings as $option => $value ) {
	update_option( $option, $value );
}

global $wp_rewrite;
$wp_rewrite->set_permalink_structure( '/%postname%/' );

/* ---------- WooCommerce pages, classic shortcodes ---------- */

WC_Install::create_pages();
$shortcodes = array(
	'cart'      => '[woocommerce_cart]',
	'checkout'  => '[woocommerce_checkout]',
	'myaccount' => '[woocommerce_my_account]',
);
foreach ( $shortcodes as $page => $shortcode ) {
	$page_id = wc_get_page_id( $page );
	if ( $page_id > 0 ) {
		wp_update_post(
			array(
				'ID'           => $page_id,
				'post_content' => $shortcode,
				'post_status'  => 'publish',
			)
		);
	}
}

/* ---------- Shipping: flat rate for Peru ---------- */

$zone_exists = false;
foreach ( WC_Shipping_Zones::get_zones() as $zone_data ) {
	$zone_exists = $zone_exists || 'Perú E2E' === $zone_data['zone_name'];
}
if ( ! $zone_exists ) {
	$zone = new WC_Shipping_Zone();
	$zone->set_zone_name( 'Perú E2E' );
	$zone->add_location( 'PE', 'country' );
	$zone->save();
	$instance_id = $zone->add_shipping_method( 'flat_rate' );
	update_option(
		'woocommerce_flat_rate_' . $instance_id . '_settings',
		array(
			'title'      => 'Envío E2E',
			'cost'       => '10',
			'tax_status' => 'none',
		)
	);
}

/* ---------- Catalog ---------- */

/** Category by slug, created if missing. */
function frave_e2e_category( string $name, string $slug, int $parent_id = 0 ): int {
	$term = get_term_by( 'slug', $slug, 'product_cat' );
	if ( ! $term ) {
		$created = wp_insert_term( $name, 'product_cat', array( 'slug' => $slug, 'parent' => $parent_id ) );
		return is_wp_error( $created ) ? 0 : (int) $created['term_id'];
	}
	return (int) $term->term_id;
}

/**
 * Global attribute with its terms, registered for this request.
 *
 * @return array{id: int, taxonomy: string, terms: array<string, int>}
 */
function frave_e2e_attribute( string $slug, string $label, array $names ): array {
	$attribute_id = wc_attribute_taxonomy_id_by_name( $slug );
	if ( ! $attribute_id ) {
		$attribute_id = wc_create_attribute(
			array(
				'name'     => $label,
				'slug'     => $slug,
				'type'     => 'select',
				'order_by' => 'menu_order',
			)
		);
	}
	$taxonomy = wc_attribute_taxonomy_name( $slug );
	if ( ! taxonomy_exists( $taxonomy ) ) {
		register_taxonomy( $taxonomy, array( 'product' ), array( 'hierarchical' => false ) );
	}
	$terms = array();
	foreach ( $names as $name ) {
		$term = get_term_by( 'name', $name, $taxonomy );
		if ( ! $term ) {
			$created = wp_insert_term( $name, $taxonomy );
			$term    = get_term( $created['term_id'], $taxonomy );
		}
		$terms[ $name ] = (int) $term->term_id;
	}
	return array(
		'id'       => (int) $attribute_id,
		'taxonomy' => $taxonomy,
		'terms'    => $terms,
	);
}

function frave_e2e_product_attribute( array $attribute, array $term_names, bool $variation = false ): WC_Product_Attribute {
	$item = new WC_Product_Attribute();
	$item->set_id( $attribute['id'] );
	$item->set_name( $attribute['taxonomy'] );
	$item->set_options( array_map( static fn( $name ) => $attribute['terms'][ $name ], $term_names ) );
	$item->set_visible( true );
	$item->set_variation( $variation );
	return $item;
}

/** Product by SKU, created as the given class if missing. */
function frave_e2e_product( string $sku, string $class_name ): WC_Product {
	$product_id = wc_get_product_id_by_sku( $sku );
	$product    = $product_id ? wc_get_product( $product_id ) : new $class_name();
	$product->set_sku( $sku );
	return $product;
}

$essences  = frave_e2e_category( 'Esencias', 'esencias' );
$citrus    = frave_e2e_category( 'Esencias cítricas', 'esencias-citricas', $essences );
$fragrance = frave_e2e_category( 'Fragancias', 'fragancias' );
$family    = frave_e2e_attribute( 'familia-olfativa', 'Familia olfativa', array( 'Cítrica', 'Floral', 'Amaderada' ) );
$size      = frave_e2e_attribute( 'presentacion', 'Presentación', array( '30 ml', '50 ml' ) );

$simple_products = array(
	'E2E-CIT' => array( '[E2E] Esencia cítrica', '35', '', 'instock', array( $essences, $citrus ), 'Cítrica', '30 ml' ),
	'E2E-FLO' => array( '[E2E] Fragancia floral', '42', '35.70', 'instock', array( $fragrance ), 'Floral', '50 ml' ),
	'E2E-AMA' => array( '[E2E] Acorde amaderado agotado', '48', '', 'outofstock', array( $essences ), 'Amaderada', '30 ml' ),
);
foreach ( $simple_products as $sku => list( $name, $regular, $sale, $stock, $categories, $family_term, $size_term ) ) {
	$product = frave_e2e_product( $sku, WC_Product_Simple::class );
	$product->set_name( $name );
	$product->set_status( 'publish' );
	$product->set_regular_price( $regular );
	$product->set_sale_price( $sale );
	$product->set_manage_stock( 'instock' === $stock );
	$product->set_stock_quantity( 'instock' === $stock ? 20 : null );
	$product->set_stock_status( $stock );
	$product->set_category_ids( $categories );
	$product->set_featured( true );
	$product->set_short_description( 'Producto de prueba automatizada.' );
	$product->set_attributes(
		array(
			frave_e2e_product_attribute( $family, array( $family_term ) ),
			frave_e2e_product_attribute( $size, array( $size_term ) ),
		)
	);
	$product->save();
}

$variable = frave_e2e_product( 'E2E-VAR', WC_Product_Variable::class );
$variable->set_name( '[E2E] Base variable' );
$variable->set_status( 'publish' );
$variable->set_category_ids( array( $essences ) );
$variable->set_featured( true );
$variable->set_attributes( array( frave_e2e_product_attribute( $size, array( '30 ml', '50 ml' ), true ) ) );
$variable_id = $variable->save();
$term_slugs  = array(
	'30 ml' => get_term( $size['terms']['30 ml'] )->slug,
	'50 ml' => get_term( $size['terms']['50 ml'] )->slug,
);
foreach ( array( '30 ml' => '28', '50 ml' => '42' ) as $term_name => $price ) {
	$sku          = 'E2E-VAR-' . (int) $term_name;
	$variation_id = wc_get_product_id_by_sku( $sku );
	$variation    = $variation_id ? wc_get_product( $variation_id ) : new WC_Product_Variation();
	$variation->set_parent_id( $variable_id );
	$variation->set_sku( $sku );
	$variation->set_attributes( array( $size['taxonomy'] => $term_slugs[ $term_name ] ) );
	$variation->set_regular_price( $price );
	$variation->set_manage_stock( true );
	$variation->set_stock_quantity( 20 );
	$variation->set_status( 'publish' );
	$variation->save();
}
WC_Product_Variable::sync( $variable_id );

/* ---------- Pages ---------- */

/** Published page by slug, created or updated. */
function frave_e2e_page( string $title, string $slug, string $content, string $template = '' ): int {
	$page = get_page_by_path( $slug );
	$data = array(
		'post_type'    => 'page',
		'post_title'   => $title,
		'post_name'    => $slug,
		'post_content' => $content,
		'post_status'  => 'publish',
	);
	if ( $page ) {
		$data['ID'] = $page->ID;
	}
	$page_id = (int) wp_insert_post( wp_slash( $data ) );
	if ( $template ) {
		update_post_meta( $page_id, '_wp_page_template', $template );
	}
	return $page_id;
}

$home = frave_e2e_page( 'Inicio', 'inicio', '' );
update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $home );

$claims_page = frave_e2e_page( 'Libro de Reclamaciones', 'libro-de-reclamaciones', '[frave_libro_reclamaciones]' );
frave_e2e_page( 'Contacto', 'contacto', '<!-- wp:paragraph --><p>Escríbenos.</p><!-- /wp:paragraph -->', 'page-templates/contacto.php' );

update_option(
	'frave_peru_claims_settings',
	array(
		'business_name' => '[E2E] Frave S.A.C.',
		'ruc'           => '20000000001',
		'address'       => '[E2E] Av. de Prueba 123, Lima',
		'page_id'       => $claims_page,
		'notify_email'  => '',
	)
);

flush_rewrite_rules();
wc_delete_product_transients();
WP_CLI::success( 'Tienda E2E lista.' );
