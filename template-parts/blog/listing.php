<?php
/**
 * List of posts of the blog, a category or another post archive: topics, the latest post
 * as a wide card on the first page of the blog, the rest in a grid and the pagination.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_is_blog  = is_home();
$frave_featured = $frave_is_blog && ! is_paged();
$frave_topics   = frave_blog_categories();
$frave_current  = is_category() ? get_queried_object_id() : 0;

if ( $frave_is_blog ) {
	$frave_hero = array(
		'eyebrow' => __( 'Blog', 'frave' ),
		'title'   => frave_blog_title(),
		'lead'    => frave_blog_lead(),
	);
} elseif ( is_category() ) {
	$frave_hero = array(
		'eyebrow' => __( 'Tema', 'frave' ),
		'title'   => single_cat_title( '', false ),
		'lead'    => category_description(),
	);
} else {
	$frave_hero = array(
		'eyebrow' => __( 'Blog', 'frave' ),
		'title'   => wp_strip_all_tags( get_the_archive_title() ),
		'lead'    => get_the_archive_description(),
	);
}
?>
<main id="primary" class="site-main blog-page">
	<?php get_template_part( 'template-parts/components/page-hero', null, $frave_hero + array( 'trail' => true ) ); ?>
	<div class="section blog-listing">
		<div class="container">
			<?php if ( count( $frave_topics ) > 1 && '' !== frave_blog_url() ) : ?>
				<nav class="blog-topics" aria-label="<?php esc_attr_e( 'Temas del blog', 'frave' ); ?>">
					<ul class="blog-topics__list">
						<li><a class="line-chip line-chip--link"<?php echo $frave_is_blog ? ' aria-current="page"' : ''; ?> href="<?php echo esc_url( frave_blog_url() ); ?>"><?php esc_html_e( 'Todos', 'frave' ); ?></a></li>
						<?php foreach ( $frave_topics as $frave_topic ) : ?>
							<li><a class="line-chip line-chip--link"<?php echo $frave_topic->term_id === $frave_current ? ' aria-current="page"' : ''; ?> href="<?php echo esc_url( get_category_link( $frave_topic ) ); ?>"><?php echo esc_html( $frave_topic->name ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</nav>
			<?php endif; ?>

			<?php if ( have_posts() ) : ?>
				<?php if ( $frave_featured ) : ?>
					<?php the_post(); ?>
					<div class="blog-featured" data-reveal="up">
						<?php
						get_template_part(
							'template-parts/components/post-tile',
							null,
							array(
								'post'     => get_post(),
								'heading'  => 'h2',
								'featured' => true,
								'eager'    => true,
							)
						);
						?>
					</div>
				<?php endif; ?>
				<?php if ( have_posts() ) : ?>
					<ul class="blog-grid" data-reveal-group>
						<?php
						$frave_index = 0;
						while ( have_posts() ) :
							the_post();
							?>
							<li>
								<?php
								get_template_part(
									'template-parts/components/post-tile',
									null,
									array(
										'post'    => get_post(),
										'heading' => 'h2',
										'eager'   => ! $frave_featured && 0 === $frave_index++,
									)
								);
								?>
							</li>
						<?php endwhile; ?>
					</ul>
				<?php endif; ?>
				<?php
				the_posts_pagination(
					array(
						'mid_size'           => 1,
						'prev_text'          => frave_icon( 'arrow-right', 'icon--flip' ) . '<span class="screen-reader-text">' . esc_html__( 'Artículos más recientes', 'frave' ) . '</span>',
						'next_text'          => '<span class="screen-reader-text">' . esc_html__( 'Artículos anteriores', 'frave' ) . '</span>' . frave_icon( 'arrow-right' ),
						'before_page_number' => '<span class="screen-reader-text">' . esc_html__( 'Página', 'frave' ) . ' </span>',
						'aria_label'         => __( 'Páginas del blog', 'frave' ),
						'class'              => 'pagination',
					)
				);
				?>
			<?php else : ?>
				<?php get_template_part( 'template-parts/components/empty-state' ); ?>
			<?php endif; ?>
		</div>
	</div>
</main>
