<?php
/**
 * Contact channels beside the contact form: WhatsApp as the quickest way (highlighted),
 * email, phone, hours and address from the Customizer, the social profiles, how a request
 * is handled, and the Libro de Reclamaciones.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_channels = frave_contact_channels();
$frave_whatsapp = $frave_channels['whatsapp'] ?? null;
unset( $frave_channels['whatsapp'] );
$frave_claims = function_exists( 'frave_peru_claims_page_url' ) ? frave_peru_claims_page_url() : '';
$frave_faq    = frave_page_with_template( FRAVE_TEMPLATE_FAQ );
$frave_icons  = array(
	'email'   => 'mail',
	'phone'   => 'phone',
	'hours'   => 'clock',
	'address' => 'pin',
);
$frave_steps  = array(
	__( 'Nos cuentas qué producto estás desarrollando.', 'frave' ),
	__( 'Un asesor revisa tu consulta y te orienta.', 'frave' ),
	__( 'Recibes la propuesta o la cotización.', 'frave' ),
);
?>
<section class="contact-channels" aria-labelledby="contact-channels-title">
	<p class="eyebrow"><?php esc_html_e( 'Canales de atención', 'frave' ); ?></p>
	<h2 id="contact-channels-title"><?php echo frave_emphasis( __( 'Elige cómo *conversar*', 'frave' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>

	<?php if ( $frave_whatsapp ) : ?>
		<div class="contact-whatsapp">
			<span class="contact-whatsapp__icon" aria-hidden="true"><?php echo frave_whatsapp_icon(); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
			<div class="contact-whatsapp__copy">
				<p class="contact-whatsapp__title"><?php esc_html_e( 'La forma más rápida', 'frave' ); ?></p>
				<p class="contact-whatsapp__number"><?php echo esc_html( frave_format_whatsapp_number( $frave_whatsapp['value'] ) ); ?></p>
			</div>
			<a class="btn btn--whatsapp contact-whatsapp__button" href="<?php echo esc_url( $frave_whatsapp['url'] ); ?>" target="_blank" rel="noopener" data-frave-whatsapp="contact">
				<span><?php esc_html_e( 'Escribir por WhatsApp', 'frave' ); ?></span>
				<span class="screen-reader-text"><?php esc_html_e( '(se abre en una nueva pestaña)', 'frave' ); ?></span>
				<span class="btn__icon"><?php echo frave_icon( 'arrow-up-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
			</a>
		</div>
	<?php endif; ?>

	<?php if ( $frave_channels ) : ?>
		<ul class="contact-channels__list">
			<?php foreach ( $frave_channels as $frave_key => $frave_channel ) : ?>
				<li class="contact-channel">
					<span class="contact-channel__icon" aria-hidden="true"><?php echo frave_icon( $frave_icons[ $frave_key ] ?? 'chat' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
					<span class="contact-channel__body">
						<span class="contact-channel__label"><?php echo esc_html( $frave_channel['label'] ); ?></span>
						<?php if ( $frave_channel['url'] ) : ?>
							<a class="contact-channel__value" href="<?php echo esc_url( $frave_channel['url'] ); ?>"><?php echo esc_html( $frave_channel['value'] ); ?></a>
						<?php else : ?>
							<span class="contact-channel__value"><?php echo nl2br( esc_html( $frave_channel['value'] ), false ); ?></span>
						<?php endif; ?>
					</span>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php elseif ( ! $frave_whatsapp ) : ?>
		<p class="contact-channels__empty"><?php esc_html_e( 'Escríbenos mediante el formulario y te responderemos por correo.', 'frave' ); ?></p>
	<?php endif; ?>

	<?php if ( frave_social_links() ) : ?>
		<div class="contact-channels__social">
			<p class="contact-channels__subtitle"><?php esc_html_e( 'Síguenos', 'frave' ); ?></p>
			<?php get_template_part( 'template-parts/components/social-links', null, array( 'context' => 'contact' ) ); ?>
		</div>
	<?php endif; ?>

	<div class="contact-steps">
		<p class="contact-channels__subtitle"><?php esc_html_e( 'Cómo atendemos tu solicitud', 'frave' ); ?></p>
		<ol class="contact-steps__list">
			<?php foreach ( $frave_steps as $frave_step ) : ?>
				<li><?php echo esc_html( $frave_step ); ?></li>
			<?php endforeach; ?>
		</ol>
	</div>

	<?php if ( $frave_faq || $frave_claims ) : ?>
		<ul class="contact-channels__links">
			<?php if ( $frave_faq ) : ?>
				<li><a class="link-arrow" href="<?php echo esc_url( get_permalink( $frave_faq ) ); ?>"><?php esc_html_e( 'Preguntas frecuentes', 'frave' ); ?><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></a></li>
			<?php endif; ?>
			<?php if ( $frave_claims ) : ?>
				<li><a class="link-arrow link-arrow--muted" href="<?php echo esc_url( $frave_claims ); ?>"><?php esc_html_e( '¿Un reclamo? Libro de Reclamaciones', 'frave' ); ?><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></a></li>
			<?php endif; ?>
		</ul>
	<?php endif; ?>
</section>
