<?php
/** Generic archive. */
get_header();
?>
<main id="primary" class="site-main section">
	<div class="container content-container">
		<header class="page-header">
			<p class="eyebrow"><?php esc_html_e( 'JOURNAL FRAVE', 'frave' ); ?></p>
			<?php the_archive_title( '<h1>', '</h1>' ); ?>
			<?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
		</header>
		<?php if ( have_posts() ) : ?>
			<div class="post-list">
				<?php while ( have_posts() ) : the_post(); ?>
					<article <?php post_class( 'post-card' ); ?> id="post-<?php the_ID(); ?>">
						<?php if ( has_post_thumbnail() ) : ?><a class="post-card__image" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"><?php the_post_thumbnail( 'frave-card', array( 'loading' => 'lazy' ) ); ?></a><?php endif; ?>
						<p class="eyebrow"><?php echo esc_html( get_the_date() ); ?></p>
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
