<?php
/** Shared template helpers. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/** Return an ACF value when available, otherwise use the supplied fallback. */
function frave_field( string $name, $fallback = '' ) {
	if ( function_exists( 'get_field' ) ) {
		$value = get_field( $name );
		if ( null !== $value && false !== $value && '' !== $value ) {
			return $value;
		}
	}

	return $fallback;
}

/** Return the URL of a theme asset. */
function frave_asset_url( string $path ): string {
	return FRAVE_THEME_URI . '/' . ltrim( $path, '/' );
}

/** Render a reusable button with an optional URL. */
function frave_button( string $label, string $url, string $class = 'button button--primary' ): void {
	if ( '' === $label || '' === $url ) {
		return;
	}
	?>
	<a class="<?php echo esc_attr( $class ); ?>" href="<?php echo esc_url( $url ); ?>">
		<?php echo esc_html( $label ); ?>
		<span aria-hidden="true">&rarr;</span>
	</a>
	<?php
}
