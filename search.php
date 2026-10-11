<?php
/**
 * Search results for WordPress content. Product searches (post_type=product)
 * are rendered by WooCommerce's archive-product.php template.
 */
get_header();
?>
<main id="primary" class="site-main">
	<?php
	get_template_part(
		'template-parts/components/page-hero',
		null,
		array(
			'eyebrow' => __( 'BÚSQUEDA', 'frave' ),
			/* translators: %s: search query. */
			'title'   => sprintf( __( 'Resultados para “%s”', 'frave' ), get_search_query() ),
		)
	);
	?>
	<div class="section">
		<div class="container content-container">
			<?php if ( have_posts() ) : ?>
				<div class="post-list">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<?php $frave_result_type = get_post_type_object( get_post_type() ); ?>
						<article <?php post_class( 'post-card' ); ?> id="post-<?php the_ID(); ?>">
							<p class="eyebrow"><?php echo esc_html( $frave_result_type ? $frave_result_type->labels->singular_name : '' ); ?></p>
							<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<div class="post-card__excerpt"><?php the_excerpt(); ?></div>
						</article>
					<?php endwhile; ?>
				</div>
				<?php the_posts_pagination(); ?>
			<?php else : ?>
				<?php get_template_part( 'template-parts/components/empty-state' ); ?>
			<?php endif; ?>
		</div>
	</div>
</main>
<?php get_footer(); ?>
