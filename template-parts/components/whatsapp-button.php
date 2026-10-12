<?php
/**
 * Floating WhatsApp button, rendered once from the footer. With JavaScript it opens a short
 * guided chat (whatsapp-chat.js): the visitor picks a reason, adds a few details and
 * continues in WhatsApp with a tidy message. Nothing is stored on the site. Claims go to
 * the Libro de Reclamaciones, as the law requires. Without JavaScript it is a plain link.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! frave_show_whatsapp_floating() ) {
	return;
}

$frave_claims  = function_exists( 'frave_peru_claims_page_url' ) ? frave_peru_claims_page_url() : '';
$frave_product = '';
if ( function_exists( 'is_product' ) && is_product() ) {
	$frave_current = wc_get_product( get_queried_object_id() );
	$frave_product = $frave_current ? $frave_current->get_name() . ' ' . $frave_current->get_permalink() : '';
}
?>
<a class="whatsapp-float" href="<?php echo esc_url( frave_whatsapp_url( frave_whatsapp_message() ) ); ?>" target="_blank" rel="noopener" data-frave-whatsapp="floating" data-wa-chat-open aria-controls="wa-chat">
	<?php echo frave_whatsapp_icon(); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
	<span class="whatsapp-float__label"><?php esc_html_e( '¿Dudas? Escríbenos', 'frave' ); ?></span>
	<span class="screen-reader-text"><?php esc_html_e( 'por WhatsApp (se abre en una nueva pestaña)', 'frave' ); ?></span>
</a>

<div class="wa-chat" id="wa-chat" role="dialog" aria-labelledby="wa-chat-title" hidden data-wa-chat data-wa-base="<?php echo esc_url( frave_whatsapp_url() ); ?>" data-product="<?php echo esc_attr( $frave_product ); ?>">
	<div class="wa-chat__head">
		<span class="wa-chat__avatar" aria-hidden="true"><?php echo frave_whatsapp_icon(); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
		<div>
			<p class="wa-chat__title" id="wa-chat-title"><?php esc_html_e( 'Frave por WhatsApp', 'frave' ); ?></p>
			<p class="wa-chat__subtitle"><?php esc_html_e( 'Cuéntanos qué necesitas', 'frave' ); ?></p>
		</div>
		<button class="wa-chat__close" type="button" data-wa-chat-close aria-label="<?php esc_attr_e( 'Cerrar', 'frave' ); ?>"><?php echo frave_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></button>
	</div>

	<div class="wa-chat__body" data-wa-step="reason">
		<p class="wa-chat__bubble"><?php esc_html_e( 'Hola, ¿en qué te podemos ayudar?', 'frave' ); ?></p>
		<ul class="wa-chat__options">
			<li><button type="button" data-wa-reason="quote"><?php esc_html_e( 'Solicitar una cotización', 'frave' ); ?></button></li>
			<li><button type="button" data-wa-reason="product"><?php esc_html_e( 'Consultar por un producto', 'frave' ); ?></button></li>
			<li><button type="button" data-wa-reason="other"><?php esc_html_e( 'Otra consulta', 'frave' ); ?></button></li>
			<?php if ( $frave_claims ) : ?>
				<li><a href="<?php echo esc_url( $frave_claims ); ?>"><?php esc_html_e( 'Presentar un reclamo', 'frave' ); ?><span class="wa-chat__note"><?php esc_html_e( 'En nuestro Libro de Reclamaciones virtual', 'frave' ); ?></span></a></li>
			<?php endif; ?>
		</ul>
	</div>

	<form class="wa-chat__body" data-wa-step="details" hidden>
		<p class="wa-chat__bubble" data-wa-prompt></p>
		<p class="wa-chat__field">
			<label for="wa-chat-name"><?php esc_html_e( 'Tu nombre (opcional)', 'frave' ); ?></label>
			<input id="wa-chat-name" type="text" autocomplete="name" maxlength="80" data-wa-name>
		</p>
		<p class="wa-chat__field" data-wa-for="quote">
			<label for="wa-chat-line"><?php esc_html_e( 'Línea de interés', 'frave' ); ?></label>
			<select id="wa-chat-line" data-wa-line>
				<option value=""><?php esc_html_e( 'Elige una línea', 'frave' ); ?></option>
				<?php foreach ( frave_product_lines() as $frave_line ) : ?>
					<option><?php echo esc_html( $frave_line ); ?></option>
				<?php endforeach; ?>
				<option value="<?php esc_attr_e( 'varias líneas', 'frave' ); ?>"><?php esc_html_e( 'Varias líneas', 'frave' ); ?></option>
			</select>
		</p>
		<p class="wa-chat__field" data-wa-for="product">
			<label for="wa-chat-product"><?php esc_html_e( '¿Qué producto?', 'frave' ); ?></label>
			<input id="wa-chat-product" type="text" maxlength="160" data-wa-product>
		</p>
		<p class="wa-chat__field">
			<label for="wa-chat-message"><?php esc_html_e( 'Detalles (opcional)', 'frave' ); ?></label>
			<textarea id="wa-chat-message" rows="3" maxlength="600" data-wa-message></textarea>
		</p>
		<p class="wa-chat__privacy"><?php esc_html_e( 'Esta web no guarda lo que escribes: el mensaje se abre en WhatsApp para que lo envíes tú.', 'frave' ); ?></p>
		<div class="wa-chat__actions">
			<button class="wa-chat__back" type="button" data-wa-back><?php esc_html_e( 'Atrás', 'frave' ); ?></button>
			<a class="btn btn--whatsapp" href="<?php echo esc_url( frave_whatsapp_url() ); ?>" target="_blank" rel="noopener" data-wa-send data-frave-whatsapp="chat">
				<span><?php esc_html_e( 'Continuar en WhatsApp', 'frave' ); ?></span>
				<span class="screen-reader-text"><?php esc_html_e( '(se abre en una nueva pestaña)', 'frave' ); ?></span>
				<span class="btn__icon"><?php echo frave_whatsapp_icon(); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
			</a>
		</div>
	</form>
</div>
