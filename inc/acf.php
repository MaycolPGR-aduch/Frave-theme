<?php
/** Optional ACF integration. Field groups are versioned in /acf-json. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function frave_acf_json_load_point( array $paths ): array {
	$paths[] = FRAVE_THEME_DIR . '/acf-json';
	return array_unique( $paths );
}
add_filter( 'acf/settings/load_json', 'frave_acf_json_load_point' );
