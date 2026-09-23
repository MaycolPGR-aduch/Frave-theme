<?php
/** Closing invitation linked to the WooCommerce shop. */
$shop_url = wc_get_page_permalink( 'shop' );
?>
<section class="section shop-cta" aria-labelledby="shop-cta-heading">
	<div class="container shop-cta__inner">
		<div>
			<p class="eyebrow"><?php echo esc_html( frave_field( 'frave_cta_eyebrow', __( 'EXPLORA FRAVE', 'frave' ) ) ); ?></p>
			<h2 id="shop-cta-heading"><?php echo esc_html( frave_field( 'frave_cta_title', __( 'Encuentra el comienzo de tu próxima creación', 'frave' ) ) ); ?></h2>
			<p><?php echo esc_html( frave_field( 'frave_cta_text', __( 'Recorre el catálogo de fragancias, esencias e insumos.', 'frave' ) ) ); ?></p>
		</div>
		<?php frave_button( (string) frave_field( 'frave_cta_button', __( 'Ir a la tienda', 'frave' ) ), $shop_url, 'button button--light' ); ?>
	</div>
</section>
