<?php
/** Closing call to action of a line page: the quote request about the line and WhatsApp. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_slug = $args['slug'];
$frave_term = $args['term'];
$frave_name = $frave_term ? $frave_term->name : get_the_title();

get_template_part(
	'template-parts/components/cta-panel',
	null,
	array(
		'title'    => frave_line_text( 'cta_title', $frave_slug ),
		'text'     => frave_line_text( 'cta_text', $frave_slug ),
		'action'   => $frave_term ? frave_line_quote_link( $frave_term ) : frave_header_cta(),
		/* translators: %s: product line name. */
		'whatsapp' => frave_whatsapp_url( sprintf( __( 'Hola, quisiera información sobre la línea %s.', 'frave' ), $frave_name ) ),
	)
);
