<?php
/**
 * Home page. The hero stays in place on large screens while "Nuestras soluciones" rises
 * over it; the following sections scroll normally. Copy: ACF group "Inicio Frave"
 * (defaults in inc/home.php).
 */
get_header();
?>
<main id="primary" class="site-main home">
	<div class="home-intro">
		<?php get_template_part( 'template-parts/home/hero' ); ?>
		<?php get_template_part( 'template-parts/home/lines' ); ?>
	</div>
	<?php get_template_part( 'template-parts/home/steps' ); ?>
	<?php get_template_part( 'template-parts/home/featured' ); ?>
	<?php get_template_part( 'template-parts/home/reasons' ); ?>
	<?php get_template_part( 'template-parts/home/catalog-cta' ); ?>
	<?php get_template_part( 'template-parts/home/posts' ); ?>
	<?php get_template_part( 'template-parts/home/location' ); ?>
</main>
<?php
get_footer();
