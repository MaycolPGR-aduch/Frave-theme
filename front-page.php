<?php
/**
 * Home page. The hero stays in place on large screens while "Nuestras soluciones" rises
 * over it; the following sections scroll normally, and the last ones (articles and
 * location) share the beige backdrop. Copy: ACF group "Inicio Frave" (defaults in
 * inc/home.php).
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
	<?php
	// Closing sections over the warm backdrop of the hero video, without the fruit.
	ob_start();
	get_template_part( 'template-parts/home/posts' );
	get_template_part( 'template-parts/home/location' );
	$frave_closing = trim( (string) ob_get_clean() );
	if ( '' !== $frave_closing ) :
		?>
		<div class="home-closing" style="<?php echo esc_attr( sprintf( "--closing-image: url('%s'); --closing-image-wide: url('%s')", frave_asset_url( 'assets/media/fondo-beige-836.webp' ), frave_asset_url( 'assets/media/fondo-beige.webp' ) ) ); ?>">
			<?php echo $frave_closing; // phpcs:ignore WordPress.Security.EscapeOutput -- template output. ?>
		</div>
	<?php endif; ?>
</main>
<?php
get_footer();
