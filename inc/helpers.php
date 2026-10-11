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

/**
 * Render a design system button (.btn) with its arrow icon. Variants: btn--primary,
 * btn--dark, btn--light, btn--outline, plus btn--sm.
 */
function frave_button( string $label, string $url, string $classes = 'btn btn--primary', string $icon = 'arrow-right' ): void {
	if ( '' === $label || '' === $url ) {
		return;
	}
	?>
	<a class="<?php echo esc_attr( $classes ); ?>" href="<?php echo esc_url( $url ); ?>">
		<span><?php echo esc_html( $label ); ?></span>
		<?php if ( $icon ) : ?>
			<span class="btn__icon"><?php echo frave_icon( $icon ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
		<?php endif; ?>
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
