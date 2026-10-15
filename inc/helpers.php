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
 * <img> of a WebP shipped with the theme. "{$base}.webp" is the full image ($width ×
 * $height) and "{$base}-{$small}.webp" the same image $small pixels wide, offered in
 * srcset so phones download the light one. '' when the file is missing.
 *
 * @param array<string, string> $attr alt, sizes, loading, class… (alt defaults to '').
 */
function frave_theme_image( string $base, int $width, int $height, int $small, array $attr = array() ): string {
	if ( ! file_exists( FRAVE_THEME_DIR . '/' . $base . '.webp' ) ) {
		return '';
	}
	$attr += array(
		'alt'      => '',
		'sizes'    => '100vw',
		'loading'  => 'lazy',
		'decoding' => 'async',
	);
	$full  = frave_asset_url( $base . '.webp' );
	$light = frave_asset_url( "{$base}-{$small}.webp" );
	$html  = sprintf(
		'<img src="%1$s" srcset="%2$s %3$dw, %1$s %4$dw" width="%4$d" height="%5$d"',
		esc_url( $full ),
		esc_url( $light ),
		$small,
		$width,
		$height
	);
	foreach ( $attr as $name => $value ) {
		$html .= sprintf( ' %s="%s"', esc_attr( $name ), esc_attr( $value ) );
	}
	return $html . '>';
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
