<?php
/**
 * Olfactory pyramid, applied (template "Pirámide olfativa", /fragancias/piramide-olfativa/):
 * how a fragrance unfolds over time (top, heart and base notes), what that means for each
 * kind of product and how to evaluate a sample on a scent strip. Part of the fragrance guide
 * with the olfactory families (inc/fragrance-families.php).
 *
 * Copy written for Frave's clients, who make products (October 2026). Times are typical
 * ranges: they change with the concentration, the base and the skin. Each level has a light
 * tone (backgrounds, chips) and a stronger accent (the pyramid's chosen tier, the timeline).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const FRAVE_TEMPLATE_FRAGRANCE_PYRAMID = 'page-templates/piramide-olfativa.php';

/**
 * The three levels, from the top of the pyramid down.
 *
 * @return array<string, array{name: string, short: string, range: string, time: string, role: string, notes: string[], families: string[], product: string, tone: string, accent: string}>
 */
function frave_fragrance_pyramid(): array {
	return array(
		'salida'  => array(
			'name'     => __( 'Notas de salida', 'frave' ),
			'short'    => __( 'Salida', 'frave' ),
			'range'    => __( '0–15 min', 'frave' ),
			'time'     => __( 'Primeros 5 a 15 minutos', 'frave' ),
			'role'     => __( 'Son las más ligeras y volátiles: lo primero que se percibe al aplicar o abrir el producto. Dan la primera impresión, despiertan el interés y se desvanecen pronto para dar paso al corazón.', 'frave' ),
			'notes'    => array( __( 'Bergamota', 'frave' ), __( 'Limón', 'frave' ), __( 'Mandarina', 'frave' ), __( 'Menta', 'frave' ), __( 'Notas verdes', 'frave' ), __( 'Frutas ligeras', 'frave' ) ),
			'families' => array( 'citrica', 'fresca', 'aromatica', 'frutal' ),
			'product'  => __( 'Deciden la compra: es lo que el cliente huele al destapar el producto en la tienda.', 'frave' ),
			'tone'     => '#f4e6b9',
			'accent'   => '#ecc867',
		),
		'corazon' => array(
			'name'     => __( 'Notas de corazón', 'frave' ),
			'short'    => __( 'Corazón', 'frave' ),
			'range'    => __( '15 min–4 h', 'frave' ),
			'time'     => __( 'De los 15 minutos a unas 4 horas', 'frave' ),
			'role'     => __( 'Son el alma de la fragancia y le dan su carácter. Aparecen cuando la salida se suaviza, hacen de puente hacia el fondo y son las que más tiempo se reconocen mientras se usa el producto.', 'frave' ),
			'notes'    => array( __( 'Rosa', 'frave' ), __( 'Jazmín', 'frave' ), __( 'Azahar', 'frave' ), __( 'Durazno', 'frave' ), __( 'Canela', 'frave' ), __( 'Lavanda', 'frave' ) ),
			'families' => array( 'floral', 'frutal', 'especiada', 'aromatica' ),
			'product'  => __( 'Definen la identidad: es lo que el cliente recordará y asociará con tu marca.', 'frave' ),
			'tone'     => '#f2dbdc',
			'accent'   => '#e3a3ab',
		),
		'fondo'   => array(
			'name'     => __( 'Notas de fondo', 'frave' ),
			'short'    => __( 'Fondo', 'frave' ),
			'range'    => __( '1 h–6 h o más', 'frave' ),
			'time'     => __( 'Desde la primera hora y por 6 horas o más', 'frave' ),
			'role'     => __( 'Son las más densas y persistentes. Se revelan poco a poco, dan profundidad y fijación, y son lo último que queda en la piel, el cabello, la ropa o el ambiente.', 'frave' ),
			'notes'    => array( __( 'Sándalo', 'frave' ), __( 'Cedro', 'frave' ), __( 'Vainilla', 'frave' ), __( 'Ámbar', 'frave' ), __( 'Almizcle', 'frave' ), __( 'Pachulí', 'frave' ) ),
			'families' => array( 'amaderada', 'oriental', 'gourmand' ),
			'product'  => __( 'Sostienen la duración: de ellas depende que el aroma siga ahí horas después.', 'frave' ),
			'tone'     => '#e6d5c2',
			'accent'   => '#c49a74',
		),
	);
}

/**
 * How the pyramid behaves in each kind of product.
 *
 * @return array<int, array{title: string, text: string, key: string, icon: string}>
 */
function frave_fragrance_pyramid_products(): array {
	return array(
		array(
			'title' => __( 'Perfumes y colonias', 'frave' ),
			'text'  => __( 'En la piel se despliega la pirámide completa. La concentración —Parfum, EDP, EDT o colonia— define cuánto dura cada fase.', 'frave' ),
			'key'   => __( 'Si buscas duración, cuida el fondo: es el que sostiene el perfume.', 'frave' ),
			'icon'  => 'fragrance',
		),
		array(
			'title' => __( 'Body splash', 'frave' ),
			'text'  => __( 'Con menor concentración, lo que más se percibe son la salida y el corazón: frescura y alegría inmediatas.', 'frave' ),
			'key'   => __( 'Elige una salida atractiva y un corazón amable; no esperes la duración de un perfume.', 'frave' ),
			'icon'  => 'sparkle',
		),
		array(
			'title' => __( 'Cremas, jabones y shampoo', 'frave' ),
			'text'  => __( 'La salida y el corazón se perciben al usar el producto; en la piel o el cabello queda el fondo, más suave.', 'frave' ),
			'key'   => __( 'La base (pH, tensioactivos, aceites) puede alterar las notas más volátiles: prueba la fragancia en tu fórmula.', 'frave' ),
			'icon'  => 'leaf',
		),
		array(
			'title' => __( 'Detergentes y suavizantes', 'frave' ),
			'text'  => __( 'El aroma se huele en tres momentos: al abrir el envase, durante el lavado y en la ropa ya seca.', 'frave' ),
			'key'   => __( 'Lo que queda en la ropa depende de notas de fondo persistentes, como los almizcles.', 'frave' ),
			'icon'  => 'check',
		),
		array(
			'title' => __( 'Velas', 'frave' ),
			'text'  => __( 'Apagada, la vela muestra sobre todo sus notas más volátiles; encendida, el calor libera el corazón y el fondo.', 'frave' ),
			'key'   => __( 'Evalúa el aroma en frío y con la vela encendida.', 'frave' ),
			'icon'  => 'bulb',
		),
		array(
			'title' => __( 'Difusores y ambientadores', 'frave' ),
			'text'  => __( 'La fragancia se evapora sin parar: las notas de salida se van primero y el aroma evoluciona con los días.', 'frave' ),
			'key'   => __( 'Una fórmula equilibrada conserva su carácter de la primera a la última semana.', 'frave' ),
			'icon'  => 'pin',
		),
	);
}

/**
 * Evaluating a sample on a scent strip: when to smell it and what each moment shows.
 *
 * @return array<int, array{time: string, title: string, text: string}>
 */
function frave_fragrance_pyramid_test_steps(): array {
	return array(
		array(
			'time'  => __( 'Al instante', 'frave' ),
			'title' => __( 'La salida', 'frave' ),
			'text'  => __( 'Moja la tira, espera unos segundos a que se evapore el alcohol y huélela a un par de centímetros, sin rozar la nariz.', 'frave' ),
		),
		array(
			'time'  => __( '15 a 30 minutos', 'frave' ),
			'title' => __( 'El corazón', 'frave' ),
			'text'  => __( 'La primera impresión se suaviza y aparece el carácter de la fragancia: ¿sigue gustándote?', 'frave' ),
		),
		array(
			'time'  => __( '2 a 4 horas', 'frave' ),
			'title' => __( 'El fondo', 'frave' ),
			'text'  => __( 'Las notas más densas toman protagonismo. Es lo que tus clientes olerán durante buena parte del día.', 'frave' ),
		),
		array(
			'time'  => __( 'Al día siguiente', 'frave' ),
			'title' => __( 'La fijación', 'frave' ),
			'text'  => __( 'Lo que aún queda en la tira indica cuánto dura la fragancia. Anota tus impresiones en cada momento y compara muestras.', 'frave' ),
		),
	);
}

/** The pyramid page's address, or '' while it is not published. */
function frave_fragrance_pyramid_url(): string {
	$page = frave_page_with_template( FRAVE_TEMPLATE_FRAGRANCE_PYRAMID );
	return $page ? (string) get_permalink( $page ) : '';
}

/** The page closes with its own call to action instead of the footer's. */
function frave_fragrance_pyramid_hide_footer_cta( bool $show ): bool {
	return is_page_template( FRAVE_TEMPLATE_FRAGRANCE_PYRAMID ) ? false : $show;
}
add_filter( 'frave_show_footer_cta', 'frave_fragrance_pyramid_hide_footer_cta' );

/**
 * Published pages of the fragrance guide, for the header's Catálogo menu and the footer.
 *
 * @return array<int, array{label: string, url: string, icon: string}>
 */
function frave_fragrance_guide_links(): array {
	$links = array();
	foreach (
		array(
			array( __( 'Familias olfativas', 'frave' ), frave_fragrance_guide_url(), 'fragrance' ),
			array( __( 'Pirámide olfativa', 'frave' ), frave_fragrance_pyramid_url(), 'clock' ),
		) as list( $label, $url, $icon )
	) {
		if ( '' !== $url ) {
			$links[] = array(
				'label' => $label,
				'url'   => $url,
				'icon'  => $icon,
			);
		}
	}
	return $links;
}
