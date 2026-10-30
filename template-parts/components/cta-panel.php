<?php
/**
 * Closing call to action over the contact image (ingredients at the edges, room for the text
 * in the middle). Pages that use it turn off the footer's invitation (frave_show_footer_cta).
 *
 * Arguments:
 * - title:    heading; *words* are highlighted.
 * - text:     optional lead.
 * - action:   [ url, external ] of the main button (contact form or WhatsApp).
 * - whatsapp: optional WhatsApp URL for a second button, when the main one is the form.
 * - bare:     true to print only the panel, inside a page that already has its container
 *             (a catalog page).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_panel  = wp_parse_args(
	$args ?? array(),
	array(
		'title'    => '',
		'text'     => '',
		'action'   => null,
		'whatsapp' => '',
		'bare'     => false,
	)
);
$frave_action = $frave_panel['action'];
if ( ! $frave_action ) {
	return;
}
$frave_bare = ! empty( $frave_panel['bare'] );
?>
<?php if ( ! $frave_bare ) : ?>
<section class="section cta-section" aria-labelledby="cta-panel-title">
	<div class="container">
<?php endif; ?>
		<div class="cta-panel<?php echo $frave_bare ? ' cta-panel--bare' : ''; ?>" data-reveal="up"<?php echo $frave_bare ? ' role="region" aria-labelledby="cta-panel-title"' : ''; ?>>
			<picture class="cta-panel__media" aria-hidden="true">
				<source media="(min-width: 48rem)" srcset="<?php echo esc_url( frave_asset_url( 'assets/media/contacto/contacto-escritorio.webp' ) ); ?>" width="2164" height="727">
				<img src="<?php echo esc_url( frave_asset_url( 'assets/media/contacto/contacto-movil.webp' ) ); ?>" width="1122" height="1402" alt="" loading="lazy" decoding="async">
			</picture>
			<div class="cta-panel__copy">
				<p class="eyebrow"><?php esc_html_e( 'Hablemos', 'frave' ); ?></p>
				<h2 id="cta-panel-title"><?php echo frave_emphasis( $frave_panel['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
				<?php if ( '' !== $frave_panel['text'] ) : ?>
					<p class="lead"><?php echo esc_html( $frave_panel['text'] ); ?></p>
				<?php endif; ?>
				<div class="btn-row">
					<a class="btn btn--primary" href="<?php echo esc_url( $frave_action['url'] ); ?>"<?php echo $frave_action['external'] ? ' target="_blank" rel="noopener" data-frave-whatsapp="cta"' : ''; ?>>
						<span><?php echo esc_html( $frave_action['external'] ? __( 'Escríbenos por WhatsApp', 'frave' ) : __( 'Solicitar cotización', 'frave' ) ); ?></span>
						<?php if ( $frave_action['external'] ) : ?>
							<span class="screen-reader-text"><?php esc_html_e( '(se abre en una nueva pestaña)', 'frave' ); ?></span>
						<?php endif; ?>
						<span class="btn__icon"><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
					</a>
					<?php if ( ! $frave_action['external'] && '' !== $frave_panel['whatsapp'] ) : ?>
						<a class="btn btn--light" href="<?php echo esc_url( $frave_panel['whatsapp'] ); ?>" target="_blank" rel="noopener" data-frave-whatsapp="cta">
							<span><?php esc_html_e( 'Escríbenos por WhatsApp', 'frave' ); ?></span>
							<span class="screen-reader-text"><?php esc_html_e( '(se abre en una nueva pestaña)', 'frave' ); ?></span>
							<span class="btn__icon"><?php echo frave_whatsapp_icon(); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
<?php if ( ! $frave_bare ) : ?>
	</div>
</section>
<?php endif; ?>
