<?php
/**
 * Article card: image (or the isotype on a warm surface), category, date, title and
 * excerpt. The whole card is the link (card__link).
 *
 * Arguments:
 * - post:     WP_Post.
 * - heading:  heading level of the title, 'h2' or 'h3'.
 * - featured: wide card with a larger title (first post of the blog).
 * - eager:    load the image at once (first card above the fold).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_tile = wp_parse_args(
	$args ?? array(),
	array(
		'post'     => null,
		'heading'  => 'h3',
		'featured' => false,
		'eager'    => false,
	)
);
$frave_post = get_post( $frave_tile['post'] );
if ( ! $frave_post ) {
	return;
}
$frave_heading  = 'h2' === $frave_tile['heading'] ? 'h2' : 'h3';
$frave_category = frave_post_category( $frave_post );
?>
<article class="card card--interactive post-tile<?php echo $frave_tile['featured'] ? ' post-tile--featured' : ''; ?>">
	<div class="post-tile__media media-frame">
		<?php if ( has_post_thumbnail( $frave_post ) ) : ?>
			<?php
			echo get_the_post_thumbnail(
				$frave_post,
				'frave-editorial',
				array(
					'alt'           => '',
					'loading'       => $frave_tile['eager'] ? 'eager' : 'lazy',
					'fetchpriority' => $frave_tile['eager'] ? 'high' : 'auto',
					'sizes'         => $frave_tile['featured'] ? '(min-width: 64rem) 50vw, 100vw' : '(min-width: 64rem) 33vw, (min-width: 40rem) 50vw, 100vw',
				)
			);
			?>
		<?php else : ?>
			<span class="post-tile__art" aria-hidden="true"><?php echo frave_isotype(); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
		<?php endif; ?>
	</div>
	<div class="post-tile__body">
		<p class="post-tile__meta">
			<?php if ( $frave_category ) : ?>
				<span class="post-tile__category"><?php echo esc_html( $frave_category->name ); ?></span>
			<?php endif; ?>
			<time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d', $frave_post ) ); ?>"><?php echo esc_html( frave_post_date( $frave_post ) ); ?></time>
		</p>
		<<?php echo esc_html( $frave_heading ); ?> class="post-tile__title"><a class="card__link" href="<?php echo esc_url( get_permalink( $frave_post ) ); ?>"><?php echo esc_html( get_the_title( $frave_post ) ); ?></a></<?php echo esc_html( $frave_heading ); ?>>
		<p class="post-tile__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt( $frave_post ), $frave_tile['featured'] ? 36 : 22 ) ); ?></p>
		<?php if ( $frave_tile['featured'] ) : ?>
			<span class="link-arrow post-tile__more" aria-hidden="true"><?php esc_html_e( 'Leer artículo', 'frave' ); ?><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
		<?php endif; ?>
	</div>
</article>
