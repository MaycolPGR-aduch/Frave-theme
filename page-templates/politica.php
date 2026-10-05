<?php
/**
 * Template Name: Política o texto legal
 * Template Post Type: page
 *
 * Privacy, terms, shipping, returns and other legal texts: last-updated date, index of
 * sections (second-level headings), readable measure and links to the other policies.
 */

get_header();
?>
<main id="primary" class="site-main section">
	<div class="container content-container">
		<?php while ( have_posts() ) : the_post(); ?>
			<article <?php post_class( 'page-content legal-page' ); ?> id="post-<?php the_ID(); ?>">
				<header class="page-header">
					<p class="eyebrow"><?php esc_html_e( 'INFORMACIÓN LEGAL', 'frave' ); ?></p>
					<h1><?php the_title(); ?></h1>
					<p class="legal-page__updated">
						<?php
						/* translators: %s: date the page was last modified. */
						printf( esc_html__( 'Última actualización: %s', 'frave' ), '<time datetime="' . esc_attr( get_the_modified_date( 'Y-m-d' ) ) . '">' . esc_html( get_the_modified_date( __( 'j \d\e F \d\e Y', 'frave' ) ) ) . '</time>' );
						?>
					</p>
				</header>
				<?php frave_page_index( get_post() ); ?>
				<div class="entry-content legal-page__content"><?php the_content(); ?></div>

				<?php $frave_others = array_filter( frave_legal_links(), static fn( $link ) => $link['id'] !== get_the_ID() ); ?>
				<?php if ( $frave_others ) : ?>
					<nav class="legal-page__others" aria-labelledby="legal-others-title">
						<h2 id="legal-others-title"><?php esc_html_e( 'Otras políticas', 'frave' ); ?></h2>
						<ul>
							<?php foreach ( $frave_others as $frave_link ) : ?>
								<li><a href="<?php echo esc_url( $frave_link['url'] ); ?>"><?php echo esc_html( $frave_link['title'] ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					</nav>
				<?php endif; ?>
			</article>
		<?php endwhile; ?>
	</div>
</main>
<?php get_footer(); ?>
