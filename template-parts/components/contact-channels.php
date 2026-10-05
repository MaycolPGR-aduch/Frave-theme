<?php
/** Contact channels from the Customizer, plus the Libro de Reclamaciones. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_channels = frave_contact_channels();
$frave_claims   = function_exists( 'frave_peru_claims_page_url' ) ? frave_peru_claims_page_url() : '';
?>
<section class="contact-channels" aria-labelledby="contact-channels-title">
	<h2 id="contact-channels-title"><?php esc_html_e( 'Canales de atención', 'frave' ); ?></h2>
	<?php if ( $frave_channels ) : ?>
		<dl class="contact-channels__list">
			<?php foreach ( $frave_channels as $frave_key => $frave_channel ) : ?>
				<div class="contact-channels__item contact-channels__item--<?php echo esc_attr( $frave_key ); ?>">
					<dt><?php echo esc_html( $frave_channel['label'] ); ?></dt>
					<dd>
						<?php if ( 'whatsapp' === $frave_key ) : ?>
							<a class="whatsapp-button" href="<?php echo esc_url( $frave_channel['url'] ); ?>" target="_blank" rel="noopener" data-frave-whatsapp="contact">
								<?php echo frave_whatsapp_icon(); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
								<span><?php esc_html_e( 'Escribir por WhatsApp', 'frave' ); ?></span>
								<span class="screen-reader-text"><?php esc_html_e( '(se abre en una nueva pestaña)', 'frave' ); ?></span>
							</a>
						<?php elseif ( $frave_channel['url'] ) : ?>
							<a href="<?php echo esc_url( $frave_channel['url'] ); ?>"><?php echo esc_html( $frave_channel['value'] ); ?></a>
						<?php else : ?>
							<?php echo nl2br( esc_html( $frave_channel['value'] ), false ); ?>
						<?php endif; ?>
					</dd>
				</div>
			<?php endforeach; ?>
		</dl>
	<?php else : ?>
		<p class="contact-channels__empty"><?php esc_html_e( 'Escríbenos mediante el formulario y te responderemos por correo.', 'frave' ); ?></p>
	<?php endif; ?>
	<?php if ( $frave_claims ) : ?>
		<p class="contact-channels__claims">
			<?php esc_html_e( '¿Quieres registrar un reclamo o una queja?', 'frave' ); ?>
			<a href="<?php echo esc_url( $frave_claims ); ?>"><?php esc_html_e( 'Usa el Libro de Reclamaciones', 'frave' ); ?></a>
		</p>
	<?php endif; ?>
</section>
