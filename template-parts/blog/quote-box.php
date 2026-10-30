<?php
/**
 * Quote box in the middle of an article (frave_article_quote_box()): an invitation to tell
 * Frave about the product, with the contact form (naming the article's line) and WhatsApp.
 *
 * Arguments: line (product line slug, or '').
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_line  = (string) ( $args['line'] ?? '' );
$frave_term  = '' !== $frave_line ? frave_line_term( $frave_line ) : null;
$frave_quote = $frave_term ? frave_line_quote_link( $frave_term ) : frave_company_action();
if ( ! $frave_quote ) {
	return;
}
/* translators: %s: article title. */
$frave_whatsapp = empty( $frave_quote['external'] ) ? frave_whatsapp_url( sprintf( __( 'Hola, leí «%s» y quisiera asesoría para mi producto.', 'frave' ), get_the_title() ) ) : '';
?>
<aside class="article-quote" aria-label="<?php esc_attr_e( 'Asesoría para tu producto', 'frave' ); ?>">
	<span class="article-quote__icon" aria-hidden="true"><?php echo frave_icon( 'chat' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
	<div class="article-quote__copy">
		<p class="article-quote__title"><?php esc_html_e( '¿Estás desarrollando un producto?', 'frave' ); ?></p>
		<p class="article-quote__text"><?php esc_html_e( 'Cuéntanos tu base, tu proceso y tu público: te orientamos con la fragancia, el sabor o el envase adecuados y con las pruebas.', 'frave' ); ?></p>
	</div>
	<div class="article-quote__actions">
		<a class="btn btn--primary btn--sm" href="<?php echo esc_url( $frave_quote['url'] ); ?>"<?php echo ! empty( $frave_quote['external'] ) ? ' target="_blank" rel="noopener"' : ''; ?>>
			<span><?php esc_html_e( 'Solicitar asesoría', 'frave' ); ?></span>
			<span class="btn__icon"><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
		</a>
		<?php if ( '' !== $frave_whatsapp ) : ?>
			<a class="link-arrow" href="<?php echo esc_url( $frave_whatsapp ); ?>" target="_blank" rel="noopener" data-frave-whatsapp="article">
				<?php esc_html_e( 'Escríbenos por WhatsApp', 'frave' ); ?>
				<span class="screen-reader-text"><?php esc_html_e( '(se abre en una nueva pestaña)', 'frave' ); ?></span>
				<?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
			</a>
		<?php endif; ?>
	</div>
</aside>
