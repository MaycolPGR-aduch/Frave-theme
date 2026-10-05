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
function frave_button( string $label, string $url, string $classes = 'button button--primary' ): void {
	if ( '' === $label || '' === $url ) {
		return;
	}
	?>
	<a class="<?php echo esc_attr( $classes ); ?>" href="<?php echo esc_url( $url ); ?>">
		<?php echo esc_html( $label ); ?>
		<span aria-hidden="true">&rarr;</span>
	</a>
	<?php
}

/** Small label above the title of generic pages; WooCommerce pages say where the customer is. */
function frave_page_eyebrow(): string {
	if ( function_exists( 'is_cart' ) ) {
		if ( is_cart() ) {
			return __( 'TU COMPRA', 'frave' );
		}
		if ( is_checkout() ) {
			return is_wc_endpoint_url( 'order-received' ) ? __( 'PEDIDO RECIBIDO', 'frave' ) : __( 'TU PEDIDO', 'frave' );
		}
		if ( is_account_page() ) {
			return __( 'TU CUENTA', 'frave' );
		}
	}
	return __( 'FRAVE', 'frave' );
}
