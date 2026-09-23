<?php
/** Homepage. */
get_header();
?>
<main id="primary" class="site-main">
	<?php get_template_part( 'template-parts/sections/hero' ); ?>

	<?php if ( class_exists( 'WooCommerce' ) ) : ?>
		<?php get_template_part( 'template-parts/sections/categories' ); ?>
		<?php get_template_part( 'template-parts/sections/featured-products' ); ?>
	<?php else : ?>
		<section class="section setup-note">
			<div class="container setup-note__inner">
				<p class="eyebrow"><?php esc_html_e( 'CREA A TU MANERA', 'frave' ); ?></p>
				<h2><?php esc_html_e( 'Tu idea empieza con buenos ingredientes.', 'frave' ); ?></h2>
				<p><?php esc_html_e( 'Fragancias, esencias y materias primas para acompañar cada proyecto.', 'frave' ); ?></p>
			</div>
		</section>
	<?php endif; ?>
</main>
<?php
get_footer();
