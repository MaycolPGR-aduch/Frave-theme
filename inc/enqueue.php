<?php
/** Vite development and production asset loading. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function frave_enqueue_theme_assets(): void {
	$dev_enabled = defined( 'FRAVE_VITE_DEV' ) && FRAVE_VITE_DEV;
	$dev_server  = defined( 'FRAVE_VITE_SERVER' ) ? untrailingslashit( FRAVE_VITE_SERVER ) : 'http://127.0.0.1:5173';

	if ( $dev_enabled ) {
		wp_enqueue_script_module( 'frave-vite-client', $dev_server . '/@vite/client', array(), null );
		wp_enqueue_script_module( 'frave-app', $dev_server . '/assets/src/js/app.js', array( 'frave-vite-client' ), null );
		return;
	}

	$manifest_path = FRAVE_THEME_DIR . '/assets/dist/manifest.json';
	if ( ! is_readable( $manifest_path ) ) {
		return;
	}

	$manifest = json_decode( (string) file_get_contents( $manifest_path ), true );
	$entry    = is_array( $manifest ) ? ( $manifest['assets/src/js/app.js'] ?? null ) : null;
	if ( ! is_array( $entry ) || empty( $entry['file'] ) ) {
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

/** Give editors a useful notice if production files have not been built yet. */
function frave_missing_build_notice(): void {
	if ( ! current_user_can( 'manage_options' ) || ( defined( 'FRAVE_VITE_DEV' ) && FRAVE_VITE_DEV ) ) {
		return;
	}
	if ( is_readable( FRAVE_THEME_DIR . '/assets/dist/manifest.json' ) ) {
		return;
	}
	?>
	<div class="notice notice-warning"><p>
		<?php esc_html_e( 'Frave no encuentra los assets compilados. Ejecuta npm run build en la carpeta del tema.', 'frave' ); ?>
	</p></div>
	<?php
}
add_action( 'admin_notices', 'frave_missing_build_notice' );
