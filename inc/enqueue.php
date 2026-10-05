<?php
/** Vite development and production asset loading. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function frave_vite_dev_enabled(): bool {
	return defined( 'FRAVE_VITE_DEV' ) && FRAVE_VITE_DEV;
}

/** The production entry from assets/dist/manifest.json, read once per request, or null. */
function frave_vite_entry(): ?array {
	static $entry = false;
	if ( false !== $entry ) {
		return $entry;
	}

	$entry         = null;
	$manifest_path = FRAVE_THEME_DIR . '/assets/dist/manifest.json';
	if ( is_readable( $manifest_path ) ) {
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local file in the theme, not a remote URL.
		$manifest  = json_decode( (string) file_get_contents( $manifest_path ), true );
		$candidate = is_array( $manifest ) ? ( $manifest['assets/src/js/app.js'] ?? null ) : null;
		if ( is_array( $candidate ) && ! empty( $candidate['file'] ) ) {
			$entry = $candidate;
		}
	}
	return $entry;
}

function frave_enqueue_theme_assets(): void {
	if ( frave_vite_dev_enabled() ) {
		$dev_server = defined( 'FRAVE_VITE_SERVER' ) ? untrailingslashit( FRAVE_VITE_SERVER ) : 'http://127.0.0.1:5173';
		wp_enqueue_script_module( 'frave-vite-client', $dev_server . '/@vite/client', array(), null );
		wp_enqueue_script_module( 'frave-app', $dev_server . '/assets/src/js/app.js', array( 'frave-vite-client' ), null );
		return;
	}

	$entry = frave_vite_entry();
	if ( ! $entry ) {
		return;
	}

	foreach ( $entry['css'] ?? array() as $index => $css_file ) {
		wp_enqueue_style(
			'frave-app' . ( $index ? '-' . absint( $index ) : '' ),
			frave_asset_url( 'assets/dist/' . ltrim( $css_file, '/' ) ),
			array(),
			FRAVE_VERSION
		);
	}

	wp_enqueue_script_module(
		'frave-app',
		frave_asset_url( 'assets/dist/' . ltrim( $entry['file'], '/' ) ),
		array(),
		FRAVE_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'frave_enqueue_theme_assets' );

/**
 * Preload the Lato WOFF2 files (400 for text, 700 for headings): both are used above the
 * fold, and without a preload the browser only discovers them after parsing the CSS.
 */
function frave_preload_fonts( array $resources ): array {
	$entry = frave_vite_dev_enabled() ? null : frave_vite_entry();
	foreach ( $entry['assets'] ?? array() as $asset ) {
		if ( str_ends_with( $asset, '.woff2' ) ) {
			$resources[] = array(
				'href'        => frave_asset_url( 'assets/dist/' . ltrim( $asset, '/' ) ),
				'as'          => 'font',
				'type'        => 'font/woff2',
				'crossorigin' => 'anonymous',
			);
		}
	}
	return $resources;
}
add_filter( 'wp_preload_resources', 'frave_preload_fonts' );

/**
 * Drop WordPress's emoji detection script and styles on the front end: every modern
 * browser renders emoji natively, so they only add a blocking inline script per page.
 */
function frave_disable_emoji(): void {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_enqueue_scripts', 'wp_enqueue_emoji_styles' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'emoji_svg_url', '__return_false' );
}
add_action( 'init', 'frave_disable_emoji' );

/** Give editors a useful notice if production files have not been built yet. */
function frave_missing_build_notice(): void {
	if ( ! current_user_can( 'manage_options' ) || frave_vite_dev_enabled() ) {
		return;
	}
	if ( frave_vite_entry() ) {
		return;
	}
	?>
	<div class="notice notice-warning"><p>
		<?php esc_html_e( 'Frave no encuentra los assets compilados. Ejecuta npm run build en la carpeta del tema.', 'frave' ); ?>
	</p></div>
	<?php
}
add_action( 'admin_notices', 'frave_missing_build_notice' );
