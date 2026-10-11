<?php
/**
 * Template Name: Preguntas frecuentes
 * Template Post Type: page
 *
 * Group questions under second-level headings and write each one as a Details block
 * (question in the summary, answer inside). The page gets an index of groups and
 * FAQPage structured data built from those blocks.
 */

get_header();
?>
<main id="primary" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part(
			'template-parts/components/page-hero',
			null,
			array(
				'eyebrow' => __( 'AYUDA', 'frave' ),
				'title'   => get_the_title(),
			)
		);
		?>
		<div class="section">
			<div class="container content-container">
				<article <?php post_class( 'page-content faq-page' ); ?> id="post-<?php the_ID(); ?>">
					<?php frave_page_index( get_post() ); ?>
					<div class="entry-content"><?php the_content(); ?></div>

					<?php
					$frave_contact = frave_page_with_template( FRAVE_TEMPLATE_CONTACT );
					$frave_wa      = frave_whatsapp_url( frave_whatsapp_message() );
					?>
					<?php if ( $frave_contact || $frave_wa ) : ?>
						<aside class="faq-help" aria-labelledby="faq-help-title">
							<h2 id="faq-help-title"><?php esc_html_e( '¿No encontraste tu respuesta?', 'frave' ); ?></h2>
							<p><?php esc_html_e( 'Escríbenos y te ayudamos a elegir o a resolver tu consulta.', 'frave' ); ?></p>
							<div class="button-row">
								<?php if ( $frave_contact ) : ?>
									<?php frave_button( __( 'Ir a contacto', 'frave' ), get_permalink( $frave_contact ) ); ?>
								<?php endif; ?>
								<?php if ( $frave_wa ) : ?>
									<a class="whatsapp-button" href="<?php echo esc_url( $frave_wa ); ?>" target="_blank" rel="noopener" data-frave-whatsapp="faq">
										<?php echo frave_whatsapp_icon(); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
										<span><?php esc_html_e( 'Escribir por WhatsApp', 'frave' ); ?></span>
										<span class="screen-reader-text"><?php esc_html_e( '(se abre en una nueva pestaña)', 'frave' ); ?></span>
									</a>
								<?php endif; ?>
							</div>
						</aside>
					<?php endif; ?>
				</article>
			</div>
		</div>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>
