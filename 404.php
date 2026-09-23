<?php
/** Not found. */
get_header();
?>
<main id="primary" class="site-main section">
	<div class="container empty-state empty-state--large">
		<p class="eyebrow">404</p>
		<h1><?php esc_html_e( 'Esta esencia aún no existe.', 'frave' ); ?></h1>
		<p><?php esc_html_e( 'La página que buscas pudo cambiar de lugar. Vuelve al inicio o explora la tienda.', 'frave' ); ?></p>
		<div class="button-row">
			<?php frave_button( __( 'Volver al inicio', 'frave' ), home_url( '/' ) ); ?>
			<?php if ( class_exists( 'WooCommerce' ) ) : ?>
				<?php frave_button( __( 'Ir a la tienda', 'frave' ), wc_get_page_permalink( 'shop' ), 'button button--secondary' ); ?>
			<?php endif; ?>
		</div>
	</div>
</main>
<?php get_footer(); ?>
