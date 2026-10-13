<?php
/**
 * Article of the blog: breadcrumb, category, reading time, the cover image, the text with
 * share links beside it (below it on phones), tags and «Sigue leyendo».
 */
get_header();
?>
<main id="primary" class="site-main article-page">
	<?php
	while ( have_posts() ) :
		the_post();
		$frave_category = frave_post_category();
		get_template_part(
			'template-parts/components/page-hero',
			null,
			array(
				'eyebrow' => $frave_category ? $frave_category->name : __( 'Blog', 'frave' ),
				'title'   => get_the_title(),
				'meta'    => frave_post_meta_html(),
				'trail'   => true,
			)
		);
		$frave_related = frave_related_posts();
		?>
		<article <?php post_class( 'section article' ); ?> id="post-<?php the_ID(); ?>">
			<div class="container">
				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="article__cover">
						<?php
						the_post_thumbnail(
							'frave-editorial',
							array(
								'loading'       => 'eager',
								'fetchpriority' => 'high',
								'decoding'      => 'async',
								'sizes'         => '(min-width: 72rem) 68rem, 100vw',
							)
						);
						?>
						<?php if ( get_the_post_thumbnail_caption() ) : ?>
							<figcaption><?php the_post_thumbnail_caption(); ?></figcaption>
						<?php endif; ?>
					</figure>
				<?php endif; ?>
				<div class="article__layout">
					<div class="article__body entry-content">
						<?php if ( has_excerpt() ) : ?>
							<p class="article__lead"><?php echo esc_html( get_the_excerpt() ); ?></p>
						<?php endif; ?>
						<?php the_content(); ?>
					</div>
					<div class="article__aside">
						<?php get_template_part( 'template-parts/blog/share' ); ?>
					</div>
				</div>
				<?php $frave_tags = get_the_tags(); ?>
				<?php if ( $frave_tags ) : ?>
					<div class="article__tags">
						<h2 class="screen-reader-text"><?php esc_html_e( 'Etiquetas', 'frave' ); ?></h2>
						<ul class="blog-topics__list">
							<?php foreach ( $frave_tags as $frave_tag ) : ?>
								<li><a class="line-chip line-chip--link" href="<?php echo esc_url( get_tag_link( $frave_tag ) ); ?>">#<?php echo esc_html( $frave_tag->name ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
			</div>
		</article>

		<?php if ( $frave_related ) : ?>
			<section class="section section--surface article-related" aria-labelledby="article-related-title">
				<div class="container">
					<div class="section-head section-head--split" data-reveal="up">
						<p class="eyebrow"><?php esc_html_e( 'Sigue leyendo', 'frave' ); ?></p>
						<h2 id="article-related-title"><?php echo frave_emphasis( __( 'Más ideas para *tu próxima creación*', 'frave' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
						<?php if ( '' !== frave_blog_url() ) : ?>
							<a class="link-arrow section-head__action" href="<?php echo esc_url( frave_blog_url() ); ?>"><?php esc_html_e( 'Ver todos los artículos', 'frave' ); ?><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></a>
						<?php endif; ?>
					</div>
					<ul class="blog-grid" data-reveal-group>
						<?php foreach ( $frave_related as $frave_post ) : ?>
							<li><?php get_template_part( 'template-parts/components/post-tile', null, array( 'post' => $frave_post ) ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			</section>
		<?php endif; ?>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>
