<div class="empty-state">
	<p class="eyebrow"><?php esc_html_e( 'SIN RESULTADOS', 'frave' ); ?></p>
	<h2><?php esc_html_e( 'Todavía no hay contenido aquí.', 'frave' ); ?></h2>
	<p><?php esc_html_e( 'Prueba otra búsqueda o vuelve al catálogo para seguir explorando.', 'frave' ); ?></p>
	<?php if ( class_exists( 'WooCommerce' ) ) : ?>
		<?php frave_button( __( 'Ver el catálogo', 'frave' ), frave_catalog_shop_url() ); ?>
	<?php endif; ?>
</div>
