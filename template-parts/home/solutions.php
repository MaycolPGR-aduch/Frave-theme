<?php
/**
 * «¿En qué industria estás?»: the industries with a published page as expanding panels
 * (template-parts/solutions/cards.php), right after the product lines, so the home says for
 * whom Frave works as well as what it offers. Copy: ACF group "Inicio Frave" (defaults in
 * inc/home.php).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_template_part(
	'template-parts/solutions/cards',
	null,
	array(
		'cards'   => frave_solution_cards(),
		'eyebrow' => frave_home_text( 'frave_home_solutions_eyebrow' ),
		'title'   => frave_home_text( 'frave_home_solutions_title' ),
		'text'    => frave_home_text( 'frave_home_solutions_text' ),
	)
);
