<?php
/**
 * Template Name: Línea
 * Template Post Type: page
 *
 * A product line (Sabores, Fragancias, Envases): hero with the line image, introduction
 * and highlights, the portfolio of subcategories, applications, the line's products,
 * the other lines and a closing call to action. Copy and images: inc/lines.php and the
 * ACF group "Línea Frave".
 */

get_header();
?>
<main id="primary" class="site-main line-page">
	<?php
	while ( have_posts() ) :
		the_post();
		$frave_slug = frave_line_slug();
		$frave_line = array(
			'slug' => $frave_slug,
			'term' => frave_line_term( $frave_slug ),
		);
		?>
		<div class="line-intro-wrap">
			<?php get_template_part( 'template-parts/line/hero', null, $frave_line ); ?>
			<?php get_template_part( 'template-parts/line/intro', null, $frave_line ); ?>
		</div>
		<?php
		get_template_part( 'template-parts/line/portfolio', null, $frave_line );
		get_template_part( 'template-parts/line/applications', null, $frave_line );
		get_template_part( 'template-parts/line/products', null, $frave_line );
		if ( '' !== trim( get_the_content() ) ) :
			?>
			<section class="section line-content">
				<div class="container content-container">
					<div class="entry-content"><?php the_content(); ?></div>
				</div>
			</section>
			<?php
		endif;
		get_template_part( 'template-parts/line/cta', null, $frave_line );
		get_template_part( 'template-parts/line/other-lines', null, $frave_line );
	endwhile;
	?>
</main>
<?php
get_footer();
