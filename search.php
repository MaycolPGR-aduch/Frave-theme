<?php
/**
 * Search results for WordPress content. Product searches (post_type=product)
 * are rendered by WooCommerce's archive-product.php template.
 */
get_header();
?>
<main id="primary" class="site-main section">
	<div class="container content-container">
		<header class="page-header">
			<p class="eyebrow"><?php esc_html_e( 'BÚSQUEDA', 'frave' ); ?></p>
			<h1><?php /* translators: %s: search query. */ printf( esc_html__( 'Resultados para “%s”', 'frave' ), esc_html( get_search_query() ) ); ?></h1>
		</header>
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
</main>
<?php get_footer(); ?>
