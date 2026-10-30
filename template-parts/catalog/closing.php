<?php
/**
 * Closing of the catalog page (inc/catalog-overview.php): «¿No encuentras lo que buscas?»
 * with a quote and WhatsApp, in place of the footer's call to action.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_quote    = frave_header_cta();
$frave_whatsapp = frave_whatsapp_url( __( 'Hola, estoy viendo su catálogo y quisiera información sobre un producto.', 'frave' ) );
?>
<section class="catalog-closing" aria-labelledby="catalogo-cierre-title">
	<div class="catalog-closing__copy">
		<h2 id="catalogo-cierre-title"><?php echo frave_emphasis( __( '¿No encuentras lo que *buscas*?', 'frave' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
		<p><?php esc_html_e( 'Trabajamos más de 500 referencias y desarrollamos a tu medida. Cuéntanos qué necesitas.', 'frave' ); ?></p>
	</div>
	<div class="catalog-closing__actions btn-row">
		<?php if ( $frave_quote && empty( $frave_quote['external'] ) ) : ?>
			<?php frave_button( __( 'Solicitar cotización', 'frave' ), $frave_quote['url'] ); ?>
		<?php endif; ?>
		<?php if ( $frave_whatsapp ) : ?>
			<a class="btn btn--whatsapp" href="<?php echo esc_url( $frave_whatsapp ); ?>" target="_blank" rel="noopener" data-frave-whatsapp="catalog">
				<span><?php esc_html_e( 'Escríbenos por WhatsApp', 'frave' ); ?></span>
				<span class="screen-reader-text"><?php esc_html_e( '(se abre en una nueva pestaña)', 'frave' ); ?></span>
				<?php echo frave_whatsapp_icon(); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
			</a>
		<?php endif; ?>
	</div>
</section>
