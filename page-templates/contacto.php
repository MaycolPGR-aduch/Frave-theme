<?php
/**
 * Template Name: Contacto
 * Template Post Type: page
 *
 * Intro from the editor, contact channels from Appearance → Customize → Contacto,
 * and the contact form of the frave-peru plugin.
 */

get_header();
?>
<main id="primary" class="site-main section">
	<div class="container content-container">
		<?php while ( have_posts() ) : the_post(); ?>
			<article <?php post_class( 'page-content contact-page' ); ?> id="post-<?php the_ID(); ?>">
				<header class="page-header">
					<p class="eyebrow"><?php esc_html_e( 'CONTACTO', 'frave' ); ?></p>
					<h1><?php the_title(); ?></h1>
				</header>
				<?php if ( '' !== trim( get_the_content() ) ) : ?>
					<div class="entry-content"><?php the_content(); ?></div>
				<?php endif; ?>

				<div class="contact-grid">
					<?php get_template_part( 'template-parts/components/contact-channels' ); ?>

					<?php if ( ! has_shortcode( get_the_content(), 'frave_contacto' ) ) : ?>
						<section class="contact-form-section" aria-labelledby="contact-form-title">
							<h2 id="contact-form-title"><?php esc_html_e( 'Escríbenos', 'frave' ); ?></h2>
							<?php
							if ( shortcode_exists( 'frave_contacto' ) ) {
								echo do_shortcode( '[frave_contacto]' );
							} else {
								echo '<p>' . esc_html__( 'Activa el plugin Frave Perú para mostrar el formulario de contacto.', 'frave' ) . '</p>';
							}
							?>
						</section>
					<?php endif; ?>
				</div>
			</article>
		<?php endwhile; ?>
	</div>
</main>
<?php get_footer(); ?>
