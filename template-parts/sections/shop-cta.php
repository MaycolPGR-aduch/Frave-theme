<?php
/** Closing invitation linked to the catalog. */
$shop_url = frave_catalog_shop_url();
?>
<section class="section shop-cta" aria-labelledby="shop-cta-heading">
	<div class="container shop-cta__inner" data-reveal="up">
		<div>
			<p class="eyebrow"><?php echo esc_html( frave_field( 'frave_cta_eyebrow', __( 'EXPLORA FRAVE', 'frave' ) ) ); ?></p>
			<h2 id="shop-cta-heading"><?php echo esc_html( frave_field( 'frave_cta_title', __( 'Encuentra el comienzo de tu próxima creación', 'frave' ) ) ); ?></h2>
			<p><?php echo esc_html( frave_field( 'frave_cta_text', __( 'Recorre el catálogo de sabores, fragancias y envases.', 'frave' ) ) ); ?></p>
		</div>
		<?php frave_button( (string) frave_field( 'frave_cta_button', __( 'Ver el catálogo', 'frave' ) ), $shop_url, 'btn btn--light' ); ?>
	</div>
</section>
