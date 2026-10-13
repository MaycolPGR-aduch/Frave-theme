<?php
/**
 * Template Name: Contacto
 * Template Post Type: page
 *
 * The main way in for customers: a hero with the page's own introduction (its content in the
 * editor) and what Frave offers, then the contact form of the frave-peru plugin beside the
 * contact channels from Apariencia → Personalizar → Contacto, both lifted over the hero.
 * The form comes first, also for keyboards and screen readers.
 */

get_header();
?>
<main id="primary" class="site-main contact-page-main">
	<?php
	while ( have_posts() ) :
		the_post();
		$frave_intro = has_shortcode( get_the_content(), 'frave_contacto' ) ? '' : get_the_content();
		get_template_part(
			'template-parts/components/page-hero',
			null,
			array(
				'eyebrow' => get_the_title(),
				'title'   => __( 'Hablemos de <strong>tu próximo producto</strong>', 'frave' ),
				'lead'    => '' !== trim( wp_strip_all_tags( $frave_intro ) ) ? wp_strip_all_tags( $frave_intro ) : __( '¿Necesitas una cotización o asesoría sobre sabores, fragancias o envases? Escríbenos por el canal que prefieras.', 'frave' ),
				'facts'   => array(
					array( 'chat', __( 'Asesoría técnica especializada', 'frave' ) ),
					array( 'target', __( 'Cotización según tu proyecto', 'frave' ) ),
					array( 'mail', __( 'Respuesta por correo o WhatsApp', 'frave' ) ),
				),
			)
		);
		?>
		<div class="contact-layout">
			<div class="container">
				<article <?php post_class( 'contact-page' ); ?> id="post-<?php the_ID(); ?>">
					<section class="contact-form-card" aria-labelledby="contact-form-title">
						<header class="contact-form-card__head">
							<p class="eyebrow"><?php esc_html_e( 'Formulario', 'frave' ); ?></p>
							<h2 id="contact-form-title"><?php echo frave_emphasis( __( 'Cuéntanos *qué necesitas*', 'frave' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
							<p><?php esc_html_e( 'Completa el formulario y un asesor de Frave te responderá con la información o la cotización que necesitas.', 'frave' ); ?></p>
						</header>
						<?php
						if ( shortcode_exists( 'frave_contacto' ) ) {
							echo do_shortcode( '[frave_contacto]' );
						} else {
							echo '<p>' . esc_html__( 'Activa el plugin Frave Perú para mostrar el formulario de contacto.', 'frave' ) . '</p>';
						}
						?>
					</section>

					<?php get_template_part( 'template-parts/components/contact-channels' ); ?>
				</article>
			</div>
		</div>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>
