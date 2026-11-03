<?php
/**
 * Titles and descriptions for search engines: what each page offers, in the words people
 * search with («fragancias para detergentes», «esencias para perfumería árabe»), instead of
 * the bare page name («Cuidado textil – Frave»). The page itself keeps its heading; only the
 * <title>, og:title and, where the page's own text is short, the meta description change.
 *
 * Keys: «shop» (the catalog), «blog» (the posts page), «page:{path}», «product_cat:{slug}»,
 * «category:{slug}» and «pa_coleccion:{slug}». The home keeps its own title. A collection
 * without an entry gets «Colección {name}: fragancias para {subcategory}»; any page or post
 * can set its own title with the custom field frave_seo_title (inc/seo.php).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Title (without « – Frave») and, optionally, description of each page.
 *
 * @return array<string, array{title: string, description?: string}>
 */
function frave_seo_entries(): array {
	$entries = array(
		'shop'                                         => array(
			'title'       => __( 'Catálogo de fragancias, sabores y envases', 'frave' ),
			'description' => __( 'Catálogo de Frave: fragancias para perfumería, cosmética, hogar y ambientación, sabores para alimentos y bebidas, y envases. Cotiza por WhatsApp.', 'frave' ),
		),
		'blog'                                         => array( 'title' => __( 'Blog: guías de fragancias, sabores y envases', 'frave' ) ),

		// Pages.
		'page:fragancias'                              => array( 'title' => __( 'Fragancias para perfumería, cosmética y hogar', 'frave' ) ),
		'page:sabores'                                 => array( 'title' => __( 'Sabores y saborizantes para alimentos y bebidas', 'frave' ) ),
		'page:envases'                                 => array( 'title' => __( 'Envases: frascos de vidrio, difusores y válvulas', 'frave' ) ),
		'page:colecciones'                             => array(
			'title'       => __( 'Colecciones de fragancias por tipo de producto', 'frave' ),
			'description' => __( 'Colecciones de fragancias listas para tu producto: shampoo, cremas de peinar, mascotas, detergentes, suavizantes, limpiadores, lavavajillas y ambientación.', 'frave' ),
		),
		'page:soluciones'                              => array( 'title' => __( 'Soluciones por industria: fragancias y sabores', 'frave' ) ),
		'page:soluciones/perfumeria'                   => array( 'title' => __( 'Soluciones para perfumería: esencias y frascos', 'frave' ) ),
		'page:soluciones/cosmetica-y-cuidado-personal' => array( 'title' => __( 'Fragancias para cosmética y cuidado personal', 'frave' ) ),
		'page:soluciones/cuidado-del-hogar'            => array( 'title' => __( 'Fragancias para detergentes y limpieza del hogar', 'frave' ) ),
		'page:soluciones/ambientacion-y-marketing-olfativo' => array( 'title' => __( 'Fragancias para ambientación y marketing olfativo', 'frave' ) ),
		'page:soluciones/alimentos-y-bebidas'          => array( 'title' => __( 'Sabores para la industria de alimentos y bebidas', 'frave' ) ),
		'page:nosotros'                                => array( 'title' => __( 'Nosotros: proveedor de fragancias y sabores en Perú', 'frave' ) ),
		'page:servicios'                               => array( 'title' => __( 'Asesoría y desarrollo de fragancias y sabores', 'frave' ) ),
		'page:contacto'                                => array( 'title' => __( 'Contacto: cotiza fragancias, sabores y envases', 'frave' ) ),
		'page:preguntas-frecuentes'                    => array( 'title' => __( 'Preguntas frecuentes sobre fragancias y sabores', 'frave' ) ),
		'page:fragancias/familias-olfativas'           => array( 'title' => __( 'Familias olfativas: guía para elegir una fragancia', 'frave' ) ),
		'page:fragancias/piramide-olfativa'            => array( 'title' => __( 'Pirámide olfativa: notas de salida, corazón y fondo', 'frave' ) ),

		// Blog categories.
		'category:novedades'                           => array(
			'title'       => __( 'Novedades: lanzamientos, colecciones y noticias', 'frave' ),
			'description' => __( 'Novedades de Frave: nuevas colecciones de fragancias, lanzamientos de sabores y envases, y noticias para fabricantes y marcas en Perú.', 'frave' ),
		),
		'category:guias-tecnicas'                      => array(
			'title'       => __( 'Guías técnicas de fragancias y sabores', 'frave' ),
			'description' => __( 'Guías técnicas de Frave: cómo elegir, dosificar y aplicar fragancias, sabores y envases en perfumes, cosméticos, productos de limpieza y alimentos.', 'frave' ),
		),
		'category:tendencias'                          => array(
			'title'       => __( 'Tendencias en fragancias y sabores', 'frave' ),
			'description' => __( 'Tendencias en fragancias y sabores: lo que marca el rumbo en perfumería, cosmética, cuidado del hogar, ambientación y alimentos.', 'frave' ),
		),

		// Product categories. The lines themselves are the catalog of their line page.
		'product_cat:fragancias'                       => array( 'title' => __( 'Catálogo de fragancias', 'frave' ) ),
		'product_cat:sabores'                          => array(
			'title'       => __( 'Catálogo de sabores', 'frave' ),
			'description' => __( 'Sabores y saborizantes para bebidas, lácteos, confitería, panadería, snacks y cuidado bucal, con desarrollo a medida y asesoría técnica de Frave.', 'frave' ),
		),
		'product_cat:envases'                          => array(
			'title'       => __( 'Catálogo de envases', 'frave' ),
			'description' => __( 'Envases para perfumería y ambientación: frascos de vidrio para perfume, frascos para difusores, válvulas atomizadoras y bombas. Cotiza con Frave.', 'frave' ),
		),
		'product_cat:bebidas-y-lacteos'                => array(
			'title'       => __( 'Sabores para bebidas y productos lácteos', 'frave' ),
			'description' => __( 'Sabores para bebidas, jugos, néctares, yogures y otros lácteos: perfiles frutales, cremosos y dulces para tu fórmula. Cotiza con Frave.', 'frave' ),
		),
		'product_cat:confiteria-y-panaderia'           => array(
			'title'       => __( 'Sabores para confitería y panadería', 'frave' ),
			'description' => __( 'Sabores para caramelos, chocolates, postres, panes y panetones, pensados para resistir el horneado y llegar a la mesa con su aroma. Cotiza con Frave.', 'frave' ),
		),
		'product_cat:salados'                          => array(
			'title'       => __( 'Sabores para snacks y productos salados', 'frave' ),
			'description' => __( 'Sabores para snacks, sazonadores, salsas y otros productos salados: perfiles intensos para tu fórmula, con asesoría técnica. Cotiza con Frave.', 'frave' ),
		),
		'product_cat:cuidado-bucal'                    => array(
			'title'       => __( 'Sabores para pasta dental y cuidado bucal', 'frave' ),
			'description' => __( 'Sabores para pastas dentales, enjuagues bucales y productos de higiene bucal: perfiles mentolados y frescos para tu marca. Cotiza con Frave.', 'frave' ),
		),
		'product_cat:perfumeria'                       => array(
			'title'       => __( 'Esencias para perfumería: diseñador, árabe y nicho', 'frave' ),
			'description' => __( 'Esencias para perfumería fina: de diseñador, árabes y de nicho, con el top de tendencias y sus notas. Crea tu perfume, colonia o body splash con Frave.', 'frave' ),
		),
		'product_cat:perfumeria-disenador'             => array( 'title' => __( 'Esencias para perfumería de diseñador', 'frave' ) ),
		'product_cat:perfumeria-arabe'                 => array( 'title' => __( 'Esencias para perfumería árabe: oud y ámbar', 'frave' ) ),
		'product_cat:perfumeria-exclusiva'             => array( 'title' => __( 'Esencias para perfumería exclusiva y nicho', 'frave' ) ),
		'product_cat:fragancias-cosmeticas'            => array( 'title' => __( 'Fragancias para cosméticos y cuidado personal', 'frave' ) ),
		'product_cat:cuidado-del-cabello'              => array( 'title' => __( 'Fragancias para shampoo y cuidado del cabello', 'frave' ) ),
		'product_cat:cuidado-de-la-piel'               => array( 'title' => __( 'Fragancias para cremas y cuidado de la piel', 'frave' ) ),
		'product_cat:cuidado-de-mascotas'              => array( 'title' => __( 'Fragancias para productos de mascotas', 'frave' ) ),
		'product_cat:fragancias-hogar'                 => array( 'title' => __( 'Fragancias para productos de limpieza del hogar', 'frave' ) ),
		'product_cat:cuidado-textil'                   => array( 'title' => __( 'Fragancias para detergentes y suavizantes', 'frave' ) ),
		'product_cat:cuidado-de-superficies'           => array( 'title' => __( 'Fragancias para limpiadores y desinfectantes', 'frave' ) ),
		'product_cat:cocina-y-lavavajillas'            => array( 'title' => __( 'Fragancias para lavavajillas y limpieza de cocina', 'frave' ) ),
		'product_cat:ambientacion'                     => array( 'title' => __( 'Fragancias para ambientadores y difusores', 'frave' ) ),
		'product_cat:difusores-de-varitas'             => array( 'title' => __( 'Fragancias para difusores de varitas', 'frave' ) ),
		'product_cat:ambientacion-de-vehiculos'        => array( 'title' => __( 'Fragancias para ambientadores de auto', 'frave' ) ),
		'product_cat:aerosoles-y-sprays'               => array( 'title' => __( 'Fragancias para aerosoles y sprays ambientales', 'frave' ) ),
		'product_cat:marketing-olfativo'               => array( 'title' => __( 'Marketing olfativo: fragancias para tu marca', 'frave' ) ),
		'product_cat:frascos-de-vidrio-perfumeria'     => array(
			'title'       => __( 'Frascos de vidrio para perfume', 'frave' ),
			'description' => __( 'Frascos de vidrio para perfumes, colonias y body splash, con válvula o tapa, para tu línea de perfumería. Te asesoramos y cotizamos con Frave.', 'frave' ),
		),
		'product_cat:frascos-para-difusores'           => array(
			'title'       => __( 'Frascos para difusores de varitas', 'frave' ),
			'description' => __( 'Frascos de vidrio para difusores de varitas y ambientadores, para presentar tu línea de ambientación. Te asesoramos y cotizamos con Frave.', 'frave' ),
		),
		'product_cat:valvulas'                         => array(
			'title'       => __( 'Válvulas atomizadoras y bombas para frascos', 'frave' ),
			'description' => __( 'Válvulas atomizadoras y bombas para frascos de perfume y ambientadores. Te asesoramos para elegir el cierre adecuado y cotizamos con Frave.', 'frave' ),
		),

		// Collections.
		'pa_coleccion:shampoo-y-acondicionador'        => array( 'title' => __( 'Colección Shampoo y acondicionador: fragancias', 'frave' ) ),
		'pa_coleccion:mascarillas-y-cremas-de-peinar'  => array( 'title' => __( 'Fragancias para mascarillas y cremas de peinar', 'frave' ) ),
		'pa_coleccion:romero'                          => array( 'title' => __( 'Colección Romero: fragancia herbal para el cabello', 'frave' ) ),
		'pa_coleccion:pitahaya-e-hibiscus'             => array( 'title' => __( 'Colección Pitahaya e Hibiscus: fragancia capilar', 'frave' ) ),
		'pa_coleccion:mascotas'                        => array( 'title' => __( 'Colección Mascotas: fragancias para su higiene', 'frave' ) ),
		'pa_coleccion:suavizantes-y-microcapsulas'     => array( 'title' => __( 'Fragancias para suavizantes y microcápsulas', 'frave' ) ),
		'pa_coleccion:detergentes'                     => array( 'title' => __( 'Colección Detergentes: fragancias para la ropa', 'frave' ) ),
		'pa_coleccion:limpiadores-de-piso'             => array( 'title' => __( 'Fragancias para limpiadores de piso', 'frave' ) ),
		'pa_coleccion:aromas-para-ambientacion'        => array( 'title' => __( 'Aromas para ambientación: difusores, sprays y velas', 'frave' ) ),
		'pa_coleccion:lavavajillas'                    => array( 'title' => __( 'Colección Lavavajillas: fragancias para la cocina', 'frave' ) ),
	);

	/**
	 * Filters the search titles and descriptions.
	 *
	 * @param array<string, array{title: string, description?: string}> $entries
	 */
	return (array) apply_filters( 'frave_seo_entries', $entries );
}

/** Key of the current page in frave_seo_entries(), or ''. */
function frave_seo_entry_key(): string {
	if ( is_front_page() ) {
		return '';
	}
	if ( function_exists( 'is_shop' ) && is_shop() ) {
		return 'shop';
	}
	if ( is_home() ) {
		return 'blog';
	}
	if ( is_page() ) {
		return 'page:' . get_page_uri( get_queried_object_id() );
	}
	if ( is_category() || is_tax() ) {
		$term = get_queried_object();
		return $term instanceof WP_Term ? $term->taxonomy . ':' . $term->slug : '';
	}
	return '';
}

/**
 * Title and description of the current page, or null.
 *
 * @return array{title: string, description?: string}|null
 */
function frave_seo_entry(): ?array {
	$key = frave_seo_entry_key();
	if ( '' === $key ) {
		return null;
	}
	$entries = frave_seo_entries();
	if ( isset( $entries[ $key ] ) ) {
		return $entries[ $key ];
	}
	// A collection without its own title: what it is for, when it fits.
	$term = get_queried_object();
	if ( defined( 'FRAVE_COLLECTION_TAXONOMY' ) && $term instanceof WP_Term && FRAVE_COLLECTION_TAXONOMY === $term->taxonomy ) {
		$category = get_term( (int) get_term_meta( $term->term_id, 'frave_category', true ), 'product_cat' );
		/* translators: 1: collection, 2: subcategory (lower case). */
		$title = $category instanceof WP_Term ? sprintf( __( 'Colección %1$s: fragancias para %2$s', 'frave' ), $term->name, mb_strtolower( $category->name ) ) : '';
		return array( 'title' => '' !== $title && mb_strlen( $title ) <= 52 ? $title : sprintf( /* translators: %s: collection. */ __( 'Colección %s: fragancias', 'frave' ), $term->name ) );
	}
	return null;
}
