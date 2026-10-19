<?php
/**
 * Guide of olfactory families (template "Familias olfativas", /fragancias/familias-olfativas/):
 * the nine families of the catalog's «Familia olfativa» attribute, with their typical notes,
 * what they convey and the products they suit, plus which families suit each application.
 * The Fragancias line page links to it, and the wheel of families
 * (template-parts/components/family-wheel.php) presents them on the Perfumería solution.
 *
 * Copy written for Frave's clients, who make products (October 2026). Images, when they
 * exist: assets/media/familias/{slug}.webp (1080 × 1350) and {slug}-640.webp; the hero uses
 * familias-olfativas.webp. bin/crear-familias-olfativas.php creates the page.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const FRAVE_TEMPLATE_FRAGRANCE_FAMILIES = 'page-templates/familias-olfativas.php';

/**
 * The families, keyed by slug, in the order of the catalog attribute.
 *
 * @return array<string, array{name: string, plural: string, tagline: string, text: string, notes: string[], conveys: string, uses: string[], perfumery: string, tone: string}>
 */
function frave_fragrance_families(): array {
	return array(
		'citrica'   => array(
			'name'      => __( 'Cítrica', 'frave' ),
			'plural'    => __( 'cítricas', 'frave' ),
			'tagline'   => __( 'Frescura luminosa que se percibe al instante.', 'frave' ),
			'text'      => __( 'Nace de la cáscara de frutos como el limón, la naranja, la mandarina, la lima, el pomelo y la bergamota. Es chispeante y ligera: aparece en los primeros minutos y se asocia de inmediato con limpieza y energía. Por ser muy volátil suele acompañarse de notas florales o amaderadas que la sostienen en el tiempo.', 'frave' ),
			'notes'     => array( __( 'Limón', 'frave' ), __( 'Naranja', 'frave' ), __( 'Mandarina', 'frave' ), __( 'Lima', 'frave' ), __( 'Pomelo', 'frave' ), __( 'Bergamota', 'frave' ) ),
			'conveys'   => __( 'Limpieza, energía y frescura', 'frave' ),
			'uses'      => array( __( 'Lavavajillas y limpiadores', 'frave' ), __( 'Detergentes', 'frave' ), __( 'Colonias y body splash', 'frave' ), __( 'Ambientadores de auto', 'frave' ) ),
			'perfumery' => __( 'Abre casi todos los perfumes con su salida luminosa y es la base de colonias y aguas frescas.', 'frave' ),
			'tone'      => '#f4e6b9',
		),
		'floral'    => array(
			'name'      => __( 'Floral', 'frave' ),
			'plural'    => __( 'florales', 'frave' ),
			'tagline'   => __( 'La familia más amplia y versátil de la perfumería.', 'frave' ),
			'text'      => __( 'Reúne el aroma de flores como la rosa, el jazmín, el azahar, la gardenia, el lirio o la peonía, solas o en ramilletes. Va de lo delicado y atalcado a lo intenso y cremoso de las flores blancas. Es la base de muchos perfumes femeninos y una de las notas más reconocidas en el cuidado personal y textil.', 'frave' ),
			'notes'     => array( __( 'Rosa', 'frave' ), __( 'Jazmín', 'frave' ), __( 'Azahar', 'frave' ), __( 'Gardenia', 'frave' ), __( 'Lirio', 'frave' ), __( 'Peonía', 'frave' ) ),
			'conveys'   => __( 'Feminidad, elegancia y suavidad', 'frave' ),
			'uses'      => array( __( 'Perfumería fina', 'frave' ), __( 'Cremas y lociones', 'frave' ), __( 'Suavizantes', 'frave' ), __( 'Jabones', 'frave' ) ),
			'perfumery' => __( 'Es el corazón de la mayoría de perfumes femeninos, del soliflor de una sola flor al gran ramillete.', 'frave' ),
			'tone'      => '#f2dbdc',
		),
		'frutal'    => array(
			'name'      => __( 'Frutal', 'frave' ),
			'plural'    => __( 'frutales', 'frave' ),
			'tagline'   => __( 'Jugosa, alegre y fácil de reconocer.', 'frave' ),
			'text'      => __( 'Evoca frutas maduras como la manzana, la pera, el durazno, los frutos rojos, el mango o la piña. Es dulce y cercana, conecta muy bien con públicos jóvenes y se combina con facilidad con notas florales y gourmand. Da a los productos de uso diario un carácter alegre y reconocible.', 'frave' ),
			'notes'     => array( __( 'Manzana', 'frave' ), __( 'Pera', 'frave' ), __( 'Durazno', 'frave' ), __( 'Frutos rojos', 'frave' ), __( 'Mango', 'frave' ), __( 'Piña', 'frave' ) ),
			'conveys'   => __( 'Alegría, juventud y dulzura', 'frave' ),
			'uses'      => array( __( 'Body splash', 'frave' ), __( 'Shampoo y acondicionador', 'frave' ), __( 'Jabones líquidos', 'frave' ), __( 'Ambientadores', 'frave' ) ),
			'perfumery' => __( 'Aporta jugosidad y un aire juvenil a perfumes femeninos y body splash.', 'frave' ),
			'tone'      => '#f5d8c6',
		),
		'amaderada' => array(
			'name'      => __( 'Amaderada', 'frave' ),
			'plural'    => __( 'amaderadas', 'frave' ),
			'tagline'   => __( 'Cálida, seca y elegante.', 'frave' ),
			'text'      => __( 'Se construye con maderas como el sándalo, el cedro, el vetiver, el pachulí o el palo santo, y con acordes amaderados modernos. Aporta profundidad y fijación: suele quedarse en el fondo de la fragancia cuando las notas más ligeras ya se fueron. Es habitual en la perfumería masculina y unisex, y en ambientes que buscan calidez.', 'frave' ),
			'notes'     => array( __( 'Sándalo', 'frave' ), __( 'Cedro', 'frave' ), __( 'Vetiver', 'frave' ), __( 'Pachulí', 'frave' ), __( 'Palo santo', 'frave' ), __( 'Oud', 'frave' ) ),
			'conveys'   => __( 'Calidez, seguridad y sofisticación', 'frave' ),
			'uses'      => array( __( 'Perfumería masculina y unisex', 'frave' ), __( 'Velas y difusores', 'frave' ), __( 'Marketing olfativo', 'frave' ), __( 'Ambientadores de auto', 'frave' ) ),
			'perfumery' => __( 'Da fondo y fijación; protagoniza perfumes masculinos y unisex.', 'frave' ),
			'tone'      => '#e6d5c2',
		),
		'oriental'  => array(
			'name'      => __( 'Oriental', 'frave' ),
			'plural'    => __( 'orientales', 'frave' ),
			'tagline'   => __( 'Intensa, envolvente y duradera.', 'frave' ),
			'text'      => __( 'También llamada ambarada. Combina resinas, vainilla, ámbar, almizcle e incienso, a menudo con especias y flores. Es la familia de la perfumería árabe y de las fragancias de noche: deja una estela marcada y dura muchas horas en la piel.', 'frave' ),
			'notes'     => array( __( 'Ámbar', 'frave' ), __( 'Vainilla', 'frave' ), __( 'Almizcle', 'frave' ), __( 'Incienso', 'frave' ), __( 'Benjuí', 'frave' ), __( 'Oud', 'frave' ) ),
			'conveys'   => __( 'Lujo, sensualidad y misterio', 'frave' ),
			'uses'      => array( __( 'Perfumería árabe y de nicho', 'frave' ), __( 'Perfumes de noche', 'frave' ), __( 'Velas', 'frave' ), __( 'Difusores', 'frave' ) ),
			'perfumery' => __( 'Define la perfumería árabe y los perfumes de noche, de gran estela.', 'frave' ),
			'tone'      => '#ecd3b8',
		),
		'aromatica' => array(
			'name'      => __( 'Aromática', 'frave' ),
			'plural'    => __( 'aromáticas', 'frave' ),
			'tagline'   => __( 'Herbal, verde y revitalizante.', 'frave' ),
			'text'      => __( 'Reúne hierbas y plantas aromáticas como la lavanda, el romero, la salvia, la menta, el eucalipto o la albahaca. Transmite naturalidad y bienestar, y es la base de muchas fragancias masculinas y de spa. Combina muy bien con cítricos y maderas.', 'frave' ),
			'notes'     => array( __( 'Lavanda', 'frave' ), __( 'Romero', 'frave' ), __( 'Salvia', 'frave' ), __( 'Menta', 'frave' ), __( 'Eucalipto', 'frave' ), __( 'Albahaca', 'frave' ) ),
			'conveys'   => __( 'Bienestar, naturalidad y equilibrio', 'frave' ),
			'uses'      => array( __( 'Limpiadores multiusos', 'frave' ), __( 'Productos de spa', 'frave' ), __( 'Perfumería masculina', 'frave' ), __( 'Hoteles y spas', 'frave' ) ),
			'perfumery' => __( 'Es clave en las fougère y en los perfumes masculinos clásicos, frescos y limpios.', 'frave' ),
			'tone'      => '#dfe5d3',
		),
		'gourmand'  => array(
			'name'      => __( 'Gourmand', 'frave' ),
			'plural'    => __( 'gourmand', 'frave' ),
			'tagline'   => __( 'Golosa: fragancias que provocan.', 'frave' ),
			'text'      => __( 'Recrea aromas de postres y golosinas: vainilla, caramelo, chocolate, café, coco, almendra o praliné. Es reconfortante y fácil de recordar, y por eso gana terreno en body splash, cremas corporales y velas.', 'frave' ),
			'notes'     => array( __( 'Vainilla', 'frave' ), __( 'Caramelo', 'frave' ), __( 'Chocolate', 'frave' ), __( 'Café', 'frave' ), __( 'Coco', 'frave' ), __( 'Almendra', 'frave' ) ),
			'conveys'   => __( 'Calidez, confort y antojo', 'frave' ),
			'uses'      => array( __( 'Body splash', 'frave' ), __( 'Cremas corporales', 'frave' ), __( 'Velas', 'frave' ), __( 'Perfumería juvenil', 'frave' ) ),
			'perfumery' => __( 'Endulza perfumes juveniles y modernos; es una de las tendencias del momento.', 'frave' ),
			'tone'      => '#ead6c3',
		),
		'fresca'    => array(
			'name'      => __( 'Fresca', 'frave' ),
			'plural'    => __( 'frescas', 'frave' ),
			'tagline'   => __( 'Limpia, acuática y ligera.', 'frave' ),
			'text'      => __( 'Agrupa los acordes marinos, acuáticos, verdes y de ropa limpia: brisa de mar, pepino, té verde, hojas y algodón. Transmite higiene inmediata; por eso es protagonista en detergentes, suavizantes y limpiadores, y en la perfumería deportiva.', 'frave' ),
			'notes'     => array( __( 'Brisa marina', 'frave' ), __( 'Notas acuáticas', 'frave' ), __( 'Té verde', 'frave' ), __( 'Pepino', 'frave' ), __( 'Hojas verdes', 'frave' ), __( 'Algodón', 'frave' ) ),
			'conveys'   => __( 'Higiene, ligereza y vitalidad', 'frave' ),
			'uses'      => array( __( 'Detergentes y suavizantes', 'frave' ), __( 'Limpiapisos', 'frave' ), __( 'Ambientadores', 'frave' ), __( 'Perfumería deportiva', 'frave' ) ),
			'perfumery' => __( 'Protagoniza los perfumes deportivos y acuáticos de uso diario, ligeros y limpios.', 'frave' ),
			'tone'      => '#d9e6e8',
		),
		'especiada' => array(
			'name'      => __( 'Especiada', 'frave' ),
			'plural'    => __( 'especiadas', 'frave' ),
			'tagline'   => __( 'Vibrante y con carácter.', 'frave' ),
			'text'      => __( 'Incorpora especias como la canela, el clavo de olor, la pimienta, el cardamomo, el jengibre o la nuez moscada. Aporta calidez y contraste: en pequeñas dosis da personalidad a florales y amaderadas, y protagoniza fragancias de temporada y velas para fiestas.', 'frave' ),
			'notes'     => array( __( 'Canela', 'frave' ), __( 'Clavo de olor', 'frave' ), __( 'Pimienta', 'frave' ), __( 'Cardamomo', 'frave' ), __( 'Jengibre', 'frave' ), __( 'Nuez moscada', 'frave' ) ),
			'conveys'   => __( 'Energía, calidez y carácter', 'frave' ),
			'uses'      => array( __( 'Perfumería masculina', 'frave' ), __( 'Velas de temporada', 'frave' ), __( 'Difusores', 'frave' ), __( 'Ambientación navideña', 'frave' ) ),
			'perfumery' => __( 'Aporta calidez y contraste a perfumes masculinos y orientales.', 'frave' ),
			'tone'      => '#efd3bf',
		),
	);
}

/**
 * Families that usually suit each application, as a starting point for the client.
 *
 * @return array<string, string[]> Application => family slugs.
 */
function frave_fragrance_family_matrix(): array {
	return array(
		__( 'Perfumería fina', 'frave' )               => array( 'floral', 'amaderada', 'oriental', 'citrica', 'gourmand' ),
		__( 'Body splash', 'frave' )                   => array( 'frutal', 'floral', 'gourmand', 'fresca' ),
		__( 'Cremas, lociones y jabones', 'frave' )    => array( 'floral', 'frutal', 'fresca', 'gourmand' ),
		__( 'Shampoo y cuidado del cabello', 'frave' ) => array( 'frutal', 'floral', 'fresca' ),
		__( 'Detergentes y suavizantes', 'frave' )     => array( 'fresca', 'floral', 'citrica' ),
		__( 'Limpiadores y lavavajillas', 'frave' )    => array( 'citrica', 'fresca', 'aromatica' ),
		__( 'Velas y difusores', 'frave' )             => array( 'gourmand', 'amaderada', 'especiada', 'aromatica' ),
		__( 'Ambientadores de auto', 'frave' )         => array( 'fresca', 'citrica', 'amaderada', 'frutal' ),
		__( 'Marketing olfativo', 'frave' )            => array( 'amaderada', 'aromatica', 'citrica', 'floral' ),
	);
}

/** The guide's address, or '' while its page is not published. */
function frave_fragrance_guide_url(): string {
	$page = frave_page_with_template( FRAVE_TEMPLATE_FRAGRANCE_FAMILIES );
	return $page ? (string) get_permalink( $page ) : '';
}

/**
 * Fragancias in the catalog filtered by one family, while some fragrance has it; otherwise ''.
 * The family is the «Familia olfativa» attribute term with the same name.
 */
function frave_fragrance_family_catalog_url( string $name ): string {
	$taxonomy = 'pa_familia-olfativa';
	$line     = frave_line_term( 'fragancias' );
	if ( ! $line || ! taxonomy_exists( $taxonomy ) ) {
		return '';
	}
	$term = get_term_by( 'name', $name, $taxonomy );
	$url  = frave_term_products_url( $line );
	if ( ! $term instanceof WP_Term || $term->count < 1 || '' === $url ) {
		return '';
	}
	// The term also counts products of other lines (Sabores, essences): look in Fragancias only.
	$found = get_posts(
		array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery -- one indexed lookup per family.
				array(
					'taxonomy' => 'product_cat',
					'terms'    => $line->term_id,
				),
				array(
					'taxonomy' => $taxonomy,
					'terms'    => $term->term_id,
				),
				array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => 'exclude-from-catalog',
					'operator' => 'NOT IN',
				),
			),
		)
	);
	return $found ? add_query_arg( 'filter_familia-olfativa', $term->slug, $url ) : '';
}

/** Theme image of a family (or of the guide's hero, «familias-olfativas»): its base path, or ''. */
function frave_fragrance_family_image_base( string $slug ): string {
	$base = 'assets/media/familias/' . $slug;
	return file_exists( FRAVE_THEME_DIR . '/' . $base . '.webp' ) && file_exists( FRAVE_THEME_DIR . '/' . $base . '-640.webp' ) ? $base : '';
}

/** The guide closes with its own call to action instead of the footer's. */
function frave_fragrance_guide_hide_footer_cta( bool $show ): bool {
	return is_page_template( FRAVE_TEMPLATE_FRAGRANCE_FAMILIES ) ? false : $show;
}
add_filter( 'frave_show_footer_cta', 'frave_fragrance_guide_hide_footer_cta' );
