<?php
/** Floating WhatsApp button, rendered once from the footer. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! frave_show_whatsapp_floating() ) {
	return;
}
?>
<a class="whatsapp-float" href="<?php echo esc_url( frave_whatsapp_url( frave_whatsapp_message() ) ); ?>" target="_blank" rel="noopener" data-frave-whatsapp="floating">
	<?php echo frave_whatsapp_icon(); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
	<span class="whatsapp-float__label"><?php esc_html_e( '¿Dudas? Escríbenos', 'frave' ); ?></span>
	<span class="screen-reader-text"><?php esc_html_e( 'por WhatsApp (se abre en una nueva pestaña)', 'frave' ); ?></span>
</a>
