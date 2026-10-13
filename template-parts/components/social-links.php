<?php
/**
 * Social profiles of Apariencia → Personalizar → Contacto, as round logo buttons that take
 * the colours of each network on hover. Nothing without profiles.
 *
 * Arguments:
 * - context: 'header' (large screens, next to the search), 'menu' (mobile drawer, with a
 *            label) or 'footer' (on the dark footer).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_profiles = frave_social_links();
if ( ! $frave_profiles ) {
	return;
}
$frave_context = in_array( $args['context'] ?? '', array( 'header', 'menu', 'footer' ), true ) ? $args['context'] : 'footer';
$frave_new_tab = __( '(se abre en una nueva pestaña)', 'frave' );
?>
<div class="social-links social-links--<?php echo esc_attr( $frave_context ); ?>">
	<?php if ( 'menu' === $frave_context ) : ?>
		<p class="social-links__title" id="social-links-menu-title"><?php esc_html_e( 'Síguenos', 'frave' ); ?></p>
	<?php endif; ?>
	<?php if ( 'menu' === $frave_context ) : ?>
		<ul class="social-links__list" aria-labelledby="social-links-menu-title">
	<?php else : ?>
		<ul class="social-links__list" aria-label="<?php esc_attr_e( 'Redes sociales de Frave', 'frave' ); ?>">
	<?php endif; ?>
		<?php foreach ( $frave_profiles as $frave_network => $frave_profile ) : ?>
			<li>
				<a class="social-link social-link--<?php echo esc_attr( $frave_network ); ?>" href="<?php echo esc_url( $frave_profile['url'] ); ?>" target="_blank" rel="noopener me">
					<?php echo frave_brand_icon( $frave_network ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
					<span class="screen-reader-text"><?php echo esc_html( $frave_profile['label'] . ' ' . $frave_new_tab ); ?></span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
