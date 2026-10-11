<?php
/** Single post. */
get_header();
?>
<main id="primary" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part(
			'template-parts/components/page-hero',
			null,
			array(
				'eyebrow' => get_the_date(),
				'title'   => get_the_title(),
			)
		);
		?>
		<div class="section">
			<div class="container content-container">
				<article <?php post_class( 'article-content' ); ?> id="post-<?php the_ID(); ?>">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="article-content__image">
						<?php
						the_post_thumbnail(
							'frave-editorial',
							array(
								'loading'  => 'eager',
								'decoding' => 'async',
							)
						);
						?>
															</div>
					<?php endif; ?>
					<div class="entry-content"><?php the_content(); ?></div>
				</article>
			</div>
		</div>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>
