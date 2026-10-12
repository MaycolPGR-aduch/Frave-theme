<?php
/** Applications of the line on the dark word band. */

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
	)
);
