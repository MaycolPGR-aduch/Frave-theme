<?php
/** Single post. */
get_header();
?>
<main id="primary" class="site-main section">
	<div class="container content-container">
		<?php while ( have_posts() ) : the_post(); ?>
			<article <?php post_class( 'article-content' ); ?> id="post-<?php the_ID(); ?>">
				<header class="page-header">
					<p class="eyebrow"><?php echo esc_html( get_the_date() ); ?></p>
					<h1><?php the_title(); ?></h1>
				</header>
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="article-content__image"><?php the_post_thumbnail( 'frave-editorial', array( 'loading' => 'eager', 'decoding' => 'async' ) ); ?></div>
				<?php endif; ?>
				<div class="entry-content"><?php the_content(); ?></div>
			</article>
		<?php endwhile; ?>
	</div>
</main>
<?php get_footer(); ?>
