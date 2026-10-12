<?php
/**
 * Closing call to action of a line page: the quote request about the line and WhatsApp,
 * over the contact image (ingredients at the edges, room for the text in the middle).
 * It replaces the footer's invitation on these pages.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_slug  = $args['slug'];
$frave_term  = $args['term'];
$frave_quote = $frave_term ? frave_line_quote_link( $frave_term ) : frave_header_cta();
if ( ! $frave_quote ) {
	return;
}
$frave_text = frave_line_text( 'cta_text', $frave_slug );
/* translators: %s: product line name. */
$frave_whatsapp = $frave_quote['external'] ? '' : frave_whatsapp_url( sprintf( __( 'Hola, quisiera información sobre la línea %s.', 'frave' ), $frave_term ? $frave_term->name : get_the_title() ) );
?>
<section class="section line-cta" aria-labelledby="line-cta-title">
	<div class="container">
		<div class="line-cta__panel" data-reveal="up">
			<picture class="line-cta__media" aria-hidden="true">
				<source media="(min-width: 48rem)" srcset="<?php echo esc_url( frave_asset_url( 'assets/media/contacto/contacto-escritorio.webp' ) ); ?>" width="2164" height="727">
				<img src="<?php echo esc_url( frave_asset_url( 'assets/media/contacto/contacto-movil.webp' ) ); ?>" width="1122" height="1402" alt="" loading="lazy" decoding="async">
			</picture>
			<div class="line-cta__copy">
				<p class="eyebrow"><?php esc_html_e( 'Hablemos', 'frave' ); ?></p>
				<h2 id="line-cta-title"><?php echo frave_emphasis( frave_line_text( 'cta_title', $frave_slug ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
				<?php if ( '' !== $frave_text ) : ?>
					<p class="lead"><?php echo esc_html( $frave_text ); ?></p>
				<?php endif; ?>
				<div class="btn-row">
					<a class="btn btn--primary" href="<?php echo esc_url( $frave_quote['url'] ); ?>"<?php echo $frave_quote['external'] ? ' target="_blank" rel="noopener" data-frave-whatsapp="line"' : ''; ?>>
						<span><?php echo esc_html( $frave_quote['external'] ? __( 'Escríbenos por WhatsApp', 'frave' ) : __( 'Solicitar cotización', 'frave' ) ); ?></span>
						<?php if ( $frave_quote['external'] ) : ?>
							<span class="screen-reader-text"><?php esc_html_e( '(se abre en una nueva pestaña)', 'frave' ); ?></span>
						<?php endif; ?>
						<span class="btn__icon"><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
					</a>
					<?php if ( '' !== $frave_whatsapp ) : ?>
						<a class="btn btn--light" href="<?php echo esc_url( $frave_whatsapp ); ?>" target="_blank" rel="noopener" data-frave-whatsapp="line">
							<span><?php esc_html_e( 'Escríbenos por WhatsApp', 'frave' ); ?></span>
							<span class="screen-reader-text"><?php esc_html_e( '(se abre en una nueva pestaña)', 'frave' ); ?></span>
							<span class="btn__icon"><?php echo frave_whatsapp_icon(); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>
