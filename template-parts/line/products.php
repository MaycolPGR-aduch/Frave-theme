<?php
/** Products of the line (featured first), with a link to its category in the catalog. Hidden without products. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_term = $args['term'];
if ( ! $frave_term || '' === frave_term_products_url( $frave_term ) ) {
	return;
}

get_template_part(
	'template-parts/home/featured',
	null,
	array(
		'category'   => $frave_term->slug,
		'eyebrow'    => __( 'Catálogo', 'frave' ),
		/* translators: %s: product line name. */
		'title'      => sprintf( __( 'Productos de *%s*', 'frave' ), $frave_term->name ),
		'link'       => frave_term_products_url( $frave_term ),
		/* translators: %s: product line name. */
		'link_label' => sprintf( __( 'Ver todo %s', 'frave' ), $frave_term->name ),
	)
);
