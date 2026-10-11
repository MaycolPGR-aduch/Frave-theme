<?php
/**
 * "Tendencias": the latest three posts. Posts left in the default category (such as
 * WordPress's sample post) are skipped, and without posts the section is not shown.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_posts = get_posts(
	array(
		'numberposts'      => 3,
		'post_status'      => 'publish',
		'category__not_in' => array( (int) get_option( 'default_category' ) ),
		'suppress_filters' => false,
	)
);
if ( ! $frave_posts ) {
	return;
}
$frave_blog = (int) get_option( 'page_for_posts' );
?>
<section class="section home-posts" aria-labelledby="home-posts-title">
	<div class="container">
		<div class="section-head section-head--split" data-reveal="up">
			<p class="eyebrow"><?php echo esc_html( frave_home_text( 'frave_home_posts_eyebrow' ) ); ?></p>
			<h2 id="home-posts-title"><?php echo frave_emphasis( frave_home_text( 'frave_home_posts_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
			<?php if ( $frave_blog ) : ?>
				<a class="link-arrow section-head__action" href="<?php echo esc_url( get_permalink( $frave_blog ) ); ?>"><?php esc_html_e( 'Ver todos los artículos', 'frave' ); ?><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></a>
			<?php endif; ?>
		</div>
		<ul class="home-posts__grid" data-reveal-group>
			<?php foreach ( $frave_posts as $frave_post ) : ?>
				<li class="card card--interactive post-tile">
					<div class="post-tile__media media-frame">
						<?php if ( has_post_thumbnail( $frave_post ) ) : ?>
							<?php
							echo get_the_post_thumbnail(
								$frave_post,
								'frave-editorial',
								array(
									'alt'     => '',
									'loading' => 'lazy',
								)
							);
							?>
						<?php else : ?>
							<span class="post-tile__art" aria-hidden="true"><?php echo frave_isotype(); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
						<?php endif; ?>
					</div>
					<p class="post-tile__date"><time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d', $frave_post ) ); ?>"><?php echo esc_html( get_the_date( '', $frave_post ) ); ?></time></p>
					<h3><a class="card__link" href="<?php echo esc_url( get_permalink( $frave_post ) ); ?>"><?php echo esc_html( get_the_title( $frave_post ) ); ?></a></h3>
					<p><?php echo esc_html( wp_trim_words( get_the_excerpt( $frave_post ), 22 ) ); ?></p>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
