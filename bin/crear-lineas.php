<?php
/**
 * Product lines agreed with Frave's management (October 2026): the subcategories of
 * Fragancias, Sabores and Envases with their descriptions, and the line pages (template
 * "Línea") at /sabores/, /fragancias/ and /envases/.
 *
 * From the site folder, with the theme's clone (bin/ is not deployed with the theme):
 *   wp eval-file ~/repos/frave-theme/bin/crear-lineas.php
 *
 * Safe to run again: existing categories keep their name, description and order (only
 * empty descriptions are filled in) and existing pages are left as they are.
 */

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	exit( 'Run with WP-CLI only.' );
}

/**
 * Category tree: slug => [name, description, children].
 *
 * @return array<string, array{0: string, 1: string, 2?: array}>
 */
function frave_lines_tree(): array {
	return array(
		'fragancias' => array(
			'Fragancias',
			'Más de 500 referencias de fragancias y esencias hidrosolubles para perfumería, cosmética, cuidado personal, hogar y ambientación.',
			array(
				'perfumeria'            => array(
					'Perfumería',
					'Fragancias de diseñador, árabes y de nicho para la perfumería fina.',
					array(
						'perfumeria-disenador' => array( 'Perfumería de diseñador', 'La perfumería de diseñador fusiona el mundo de la moda con el arte de crear fragancias. Sus composiciones reflejan estilos, tendencias y conceptos estéticos, convirtiendo cada aroma en una extensión de la identidad y personalidad de una firma creativa. Son fragancias que combinan elegancia, versatilidad y sofisticación para acompañar diferentes estilos de vida.' ),
						'perfumeria-arabe'     => array( 'Perfumería árabe', 'La perfumería árabe es una tradición milenaria que combina cultura, historia y arte olfativo. Se caracteriza por fragancias intensas y duraderas, elaboradas con ingredientes como oud, ámbar, almizcle y rosa. Sus composiciones evolucionan con el tiempo, creando experiencias sensoriales profundas que evocan lujo, elegancia y distinción, ideales para quienes buscan aromas con personalidad.' ),
						'perfumeria-exclusiva' => array( 'Perfumería exclusiva (nicho)', 'La perfumería nicho es una expresión artística que transforma los aromas en experiencias únicas. Se distingue por sus fragancias exclusivas, composiciones originales y materias primas selectas, creadas para quienes buscan autenticidad, sofisticación y carácter. Cada creación transmite una historia, despierta emociones y celebra el arte de la perfumería más allá de lo convencional.' ),
					),
				),
				'fragancias-cosmeticas' => array(
					'Fragancias cosméticas',
					'Las fragancias para el cuidado personal aportan identidad y valor sensorial a productos cosméticos de uso diario. Diseñadas para integrarse armoniosamente en cremas, lociones, jabones líquidos y body splash, combinan atractivo olfativo, compatibilidad con las formulaciones y calidad.',
					array(
						'cuidado-del-cabello' => array( 'Cuidado del cabello', 'Fragancias para shampoos, acondicionadores, tratamientos y productos de peinado. Dan a cada producto una huella aromática que se percibe durante el lavado y acompaña al cabello después, y se integran en bases limpiadoras y emulsiones capilares.' ),
						'cuidado-de-la-piel'  => array( 'Cuidado de la piel', 'Fragancias para cremas, lociones, jabones líquidos, geles de ducha y body splash. Pensadas para integrarse en emulsiones y bases limpiadoras con una experiencia sensorial agradable y estable en el tiempo.' ),
						'cuidado-de-mascotas' => array( 'Cuidado de mascotas', 'Fragancias para shampoos, colonias y productos de higiene para mascotas. Aromas frescos y suaves que transmiten limpieza y bienestar, pensados para formulaciones dedicadas al cuidado animal.' ),
					),
				),
				'fragancias-hogar'      => array(
					'Fragancias para el cuidado del hogar',
					'Las fragancias para el cuidado del hogar transforman las tareas cotidianas en experiencias de limpieza y frescura. Desarrolladas para detergentes, suavizantes, lavavajillas y limpiadores multiusos, aportan aromas agradables y persistentes que refuerzan la percepción de higiene y bienestar.',
					array(
						'cuidado-textil'         => array( 'Cuidado textil', 'Fragancias para detergentes, suavizantes y perfumes textiles. Refuerzan la sensación de ropa limpia con aromas pensados para acompañar a las prendas después del lavado.' ),
						'cuidado-de-superficies' => array( 'Cuidado de superficies', 'Fragancias para limpiadores multiusos, desinfectantes, ceras y limpiapisos. Aportan frescura a los ambientes y refuerzan la percepción de higiene después de cada limpieza.' ),
						'cocina-y-lavavajillas'  => array( 'Cocina y lavavajillas', 'Fragancias para lavavajillas líquidos, desengrasantes y limpiadores de cocina. Notas cítricas y frescas que acompañan el poder de limpieza y dejan una sensación agradable en manos y superficies.' ),
					),
				),
				'ambientacion'          => array(
					'Ambientación',
					'Transformamos cada espacio en una experiencia inolvidable a través de fragancias que inspiran bienestar, frescura y emociones. Aromas versátiles para difusores, velas, reeds, automóviles y ambientadores, ideales para crear ambientes únicos, envolventes y llenos de personalidad.',
					array(
						'difusores-de-varitas'      => array( 'Difusores de varitas', 'Fragancias para difusores de varitas, preparadas para difundirse de forma constante y perfumar los espacios de manera continua y decorativa.' ),
						'ambientacion-de-vehiculos' => array( 'Ambientación de vehículos', 'Fragancias para ambientadores de auto: colgantes, clips de rejilla y sprays. Aromas frescos e intensos que hacen más agradable cada trayecto.' ),
						'aerosoles-y-sprays'        => array( 'Aerosoles y sprays', 'Fragancias para ambientadores en aerosol, sprays para telas y atomizadores de ambiente. Proporcionan un impacto aromático inmediato y una sensación de frescura en el espacio.' ),
						'marketing-olfativo'        => array( 'Marketing olfativo', 'Aromas para la identidad olfativa de tiendas, hoteles, oficinas y eventos. Te acompañamos a crear una firma aromática que refuerce la experiencia de tus clientes y el recuerdo de tu marca.' ),
					),
				),
			),
		),
		'sabores'    => array(
			'Sabores',
			'Soluciones de saborización para bebidas, alimentos, lácteos y salud bucal.',
			array(
				'bebidas-y-lacteos'      => array( 'Bebidas y productos lácteos', 'Sabores para bebidas, jugos, yogures y otros productos lácteos.' ),
				'confiteria-y-panaderia' => array( 'Confitería y panadería', 'Sabores para caramelos, chocolates, postres, panes y panetones.' ),
				'salados'                => array( 'Salados', 'Sabores para snacks, sazonadores y otros productos salados.' ),
				'cuidado-bucal'          => array( 'Cuidado bucal', 'Sabores para pastas dentales, enjuagues y productos de higiene bucal.' ),
			),
		),
		'envases'    => array(
			'Envases',
			'Frascos, válvulas y accesorios para perfumería, cosmética y cuidado personal.',
			array(
				'frascos-de-vidrio-perfumeria' => array( 'Frascos de vidrio para perfumería', 'Frascos de vidrio para perfumes, colonias y body splash.' ),
				'frascos-para-difusores'       => array( 'Frascos para difusores', 'Frascos para difusores de varitas y ambientadores.' ),
				'valvulas'                     => array( 'Válvulas', 'Válvulas para frascos de perfumería y atomizadores.' ),
			),
		),
	);
}

/** Create the category (or find it by slug) and fill in an empty description. */
function frave_lines_term( string $slug, string $name, string $description, int $parent_id, int $order ): int {
	$term = get_term_by( 'slug', $slug, 'product_cat' );
	if ( $term instanceof WP_Term ) {
		if ( (int) $term->parent !== $parent_id ) {
			WP_CLI::warning( "«{$term->name}» ya existe con otra categoría superior; no se mueve." );
		}
		if ( '' === trim( $term->description ) && '' !== $description ) {
			wp_update_term( $term->term_id, 'product_cat', array( 'description' => $description ) );
			WP_CLI::log( "Descripción añadida: {$term->name}" );
		}
		return (int) $term->term_id;
	}
	$created = wp_insert_term(
		$name,
		'product_cat',
		array(
			'slug'        => $slug,
			'parent'      => $parent_id,
			'description' => $description,
		)
	);
	if ( is_wp_error( $created ) ) {
		WP_CLI::warning( "No se pudo crear «{$name}»: " . $created->get_error_message() );
		return 0;
	}
	update_term_meta( (int) $created['term_id'], 'order', $order );
	WP_CLI::log( "Categoría creada: {$name}" );
	return (int) $created['term_id'];
}

function frave_lines_create_tree( array $tree, int $parent_id = 0 ): void {
	$order = 0;
	foreach ( $tree as $slug => $entry ) {
		$id = frave_lines_term( $slug, $entry[0], $entry[1], $parent_id, ++$order );
		if ( $id && ! empty( $entry[2] ) ) {
			frave_lines_create_tree( $entry[2], $id );
		}
	}
}

/** A published page per line with the template "Línea", unless the slug is taken. */
function frave_lines_create_pages(): void {
	foreach ( frave_lines_tree() as $slug => $entry ) {
		$existing = get_page_by_path( $slug );
		if ( $existing instanceof WP_Post ) {
			$template = get_page_template_slug( $existing );
			if ( 'page-templates/linea.php' === $template ) {
				WP_CLI::log( "La página «{$existing->post_title}» ya existe." );
			} else {
				WP_CLI::warning( "Ya hay una página en /{$slug}/ con otra plantilla; elige la plantilla «Línea» en el editor si debe presentar la línea." );
			}
			continue;
		}
		$page_id = wp_insert_post(
			array(
				'post_type'   => 'page',
				'post_status' => 'publish',
				'post_title'  => $entry[0],
				'post_name'   => $slug,
			),
			true
		);
		if ( is_wp_error( $page_id ) ) {
			WP_CLI::warning( "No se pudo crear la página «{$entry[0]}»: " . $page_id->get_error_message() );
			continue;
		}
		update_post_meta( $page_id, '_wp_page_template', 'page-templates/linea.php' );
		WP_CLI::log( "Página creada: {$entry[0]} (/{$slug}/)" );
	}
}

/**
 * The three lines in the site's order (Fragancias, the main line, first) wherever
 * WooCommerce lists categories: catalog, filters and the admin. Unlike the other
 * categories, their order is set on every run.
 */
function frave_lines_order_lines(): void {
	foreach ( array_keys( frave_lines_tree() ) as $index => $slug ) {
		$term = get_term_by( 'slug', $slug, 'product_cat' );
		if ( $term instanceof WP_Term && (int) get_term_meta( $term->term_id, 'order', true ) !== $index + 1 ) {
			update_term_meta( $term->term_id, 'order', $index + 1 );
			WP_CLI::log( "Orden de la línea {$term->name}: " . ( $index + 1 ) );
		}
	}
}

if ( ! taxonomy_exists( 'product_cat' ) ) {
	WP_CLI::error( 'WooCommerce no está activo.' );
}
frave_lines_create_tree( frave_lines_tree() );
frave_lines_order_lines();
frave_lines_create_pages();
WP_CLI::success( 'Líneas listas.' );
