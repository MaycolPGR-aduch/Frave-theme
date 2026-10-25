<?php
/** Applications of the line on the sand word band, over a photograph of the line in use when there is one. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_slug = $args['slug'];

get_template_part(
	'template-parts/components/applications',
	null,
	array(
		'items' => frave_lines_of( frave_line_text( 'applications', $frave_slug ) ),
		'title' => frave_line_text( 'applications_title', $frave_slug ),
		'text'  => frave_line_text( 'applications_text', $frave_slug ),
		'image' => file_exists( FRAVE_THEME_DIR . '/assets/media/fondos/aplicaciones-' . $frave_slug . '.webp' ) ? 'aplicaciones-' . $frave_slug : '',
	)
);
