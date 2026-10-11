<?php
/**
 * Internal style guide of the design system, to review and approve it: /?sistema-visual.
 * Only for users who can edit the theme; everyone else gets the normal page. It needs no
 * page in the database and is never indexed.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function frave_is_style_guide(): bool {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only view switch.
	return isset( $_GET['sistema-visual'] ) && current_user_can( 'edit_theme_options' );
}

function frave_style_guide_template( string $template ): string {
	return frave_is_style_guide() ? FRAVE_THEME_DIR . '/template-parts/style-guide.php' : $template;
}
add_filter( 'template_include', 'frave_style_guide_template', 99 );

function frave_style_guide_title( array $title ): array {
	if ( frave_is_style_guide() ) {
		$title['title'] = __( 'Sistema visual', 'frave' );
	}
	return $title;
}
add_filter( 'document_title_parts', 'frave_style_guide_title' );

function frave_style_guide_robots( array $robots ): array {
	if ( frave_is_style_guide() ) {
		$robots['noindex']  = true;
		$robots['nofollow'] = true;
	}
	return $robots;
}
add_filter( 'wp_robots', 'frave_style_guide_robots' );
