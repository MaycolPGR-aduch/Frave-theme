<?php
/**
 * "Tendencias": the latest three posts, without WordPress's sample post. Without posts
 * the section is not shown.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_posts = get_posts(
	array(
		'numberposts'      => 3,
		'post_status'      => 'publish',
		'post__not_in'     => frave_blog_sample_post_ids(),
		'suppress_filters' => false,
	)
);
if ( ! $frave_posts ) {
	return;
}
$frave_blog = frave_blog_url();
?>
<section class="section home-posts" aria-labelledby="home-posts-title">
	<div class="container">
		<div class="section-head section-head--split" data-reveal="up">
			<p class="eyebrow"><?php echo esc_html( frave_home_text( 'frave_home_posts_eyebrow' ) ); ?></p>
			<h2 id="home-posts-title"><?php echo frave_emphasis( frave_home_text( 'frave_home_posts_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
			<?php if ( '' !== $frave_blog ) : ?>
				<a class="link-arrow section-head__action" href="<?php echo esc_url( $frave_blog ); ?>"><?php esc_html_e( 'Ver todos los artículos', 'frave' ); ?><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></a>
			<?php endif; ?>
		</div>
		<ul class="home-posts__grid" data-reveal-group>
			<?php foreach ( $frave_posts as $frave_post ) : ?>
				<li><?php get_template_part( 'template-parts/components/post-tile', null, array( 'post' => $frave_post ) ); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
