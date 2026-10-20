<?php
/**
 * Fragancias line: the interactive olfactory pyramid, with a button to its full page. Only on
 * the Fragancias page, once the pyramid page is published.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_pyramid = 'fragancias' === ( $args['slug'] ?? '' ) ? frave_fragrance_pyramid_url() : '';
if ( '' === $frave_pyramid ) {
	return;
}

get_template_part(
	'template-parts/components/fragrance-pyramid',
	null,
	array(
		'eyebrow' => __( 'Pirámide olfativa', 'frave' ),
		'title'   => __( 'Cómo evoluciona *una fragancia*', 'frave' ),
		'text'    => __( 'Salida, corazón y fondo: toca cada nivel para ver cuándo se percibe, qué notas lo forman y qué aporta a tu producto.', 'frave' ),
		'more'    => $frave_pyramid,
		'surface' => true,
	)
);
