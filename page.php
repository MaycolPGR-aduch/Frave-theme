<?php
/** Standard WordPress page. */
get_header();
?>
<main id="primary" class="site-main section">
	<div class="container content-container">
		<?php while ( have_posts() ) : the_post(); ?>
			<article <?php post_class( 'page-content' ); ?> id="post-<?php the_ID(); ?>">
				<header class="page-header">
					<p class="eyebrow"><?php esc_html_e( 'FRAVE', 'frave' ); ?></p>
					<h1><?php the_title(); ?></h1>
				</header>
				<div class="entry-content">
					<?php the_content(); ?>
					<?php wp_link_pages(); ?>
				</div>
			</article>
		<?php endwhile; ?>
	</div>
</main>
<?php get_footer(); ?>
