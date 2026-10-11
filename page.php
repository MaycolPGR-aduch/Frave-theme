<?php
/** Standard WordPress page. */
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
				'eyebrow' => frave_page_eyebrow(),
				'title'   => get_the_title(),
			)
		);
		?>
		<div class="section">
			<div class="container content-container">
				<article <?php post_class( 'page-content' ); ?> id="post-<?php the_ID(); ?>">
					<div class="entry-content">
						<?php the_content(); ?>
						<?php wp_link_pages(); ?>
					</div>
				</article>
			</div>
		</div>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>
