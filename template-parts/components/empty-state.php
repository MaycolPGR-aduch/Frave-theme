<div class="empty-state">
	<p class="eyebrow"><?php esc_html_e( 'SIN RESULTADOS', 'frave' ); ?></p>
	<h2><?php esc_html_e( 'Todavía no hay contenido aquí.', 'frave' ); ?></h2>
	<p><?php esc_html_e( 'Prueba otra búsqueda o vuelve a la tienda para seguir explorando.', 'frave' ); ?></p>
	<?php if ( class_exists( 'WooCommerce' ) ) : ?>
		<?php frave_button( __( 'Explorar productos', 'frave' ), wc_get_page_permalink( 'shop' ) ); ?>
	<?php endif; ?>
</div>
