<?php
/**
 * Template Name: Solución
 * Template Post Type: page
 *
 * One industry (the page's slug, see inc/solutions.php): hero, introduction, what Frave
 * offers it from every line (product subcategories), the wheel of olfactory families
 * (Perfumería), applications, the other industries and a closing call to action.
 * Copy: inc/solutions.php and the ACF group "Solución Frave".
 */

get_header();
?>
<main id="primary" class="site-main solution-page">
	<?php
	while ( have_posts() ) :
		the_post();
		$frave_slug  = frave_solution_slug();
		$frave_terms = '' !== $frave_slug ? frave_solution_terms( $frave_slug ) : array();
		$frave_quote = $frave_terms ? frave_line_quote_link( $frave_terms[0] ) : frave_company_action();
		$frave_title = '' !== $frave_slug ? frave_solution_text( 'hero_title', $frave_slug ) : get_the_title();

		get_template_part(
			'template-parts/solutions/hero',
			null,
			array(
				'eyebrow'   => __( 'Soluciones', 'frave' ),
				'title'     => $frave_title,
				'lead'      => frave_solution_text( 'lead', $frave_slug ),
				'action'    => $frave_quote,
				'secondary' => $frave_terms ? array( __( 'Lo que te ofrecemos', 'frave' ), '#oferta' ) : null,
				'image'     => frave_solutions()[ $frave_slug ]['image'] ?? '',
			)
		);

		$frave_intro_title = frave_solution_text( 'intro_title', $frave_slug );
		$frave_intro_text  = frave_solution_text( 'intro_text', $frave_slug );
		if ( '' !== $frave_intro_title || '' !== $frave_intro_text ) :
			?>
			<section class="section company-statement" aria-labelledby="solution-intro-title">
				<div class="container">
					<div class="statement">
						<h2 class="statement__title" id="solution-intro-title" data-reveal="up"><?php echo frave_emphasis( '' !== $frave_intro_title ? $frave_intro_title : get_the_title() ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
						<div class="statement__text" data-reveal="up"><?php echo wp_kses_post( wpautop( esc_html( $frave_intro_text ) ) ); ?></div>
					</div>
				</div>
			</section>
			<?php
		endif;

		if ( $frave_terms ) :
			?>
			<section class="section line-portfolio line-portfolio--bands" id="oferta" aria-labelledby="solution-offer-title">
				<div class="container">
					<div class="section-head" data-reveal="up">
						<p class="eyebrow"><?php esc_html_e( 'Lo que te ofrecemos', 'frave' ); ?></p>
						<h2 id="solution-offer-title"><?php echo frave_emphasis( __( 'De nuestras líneas, *para tu industria*', 'frave' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
					</div>
				</div>
				<ol class="line-groups line-groups--bands">
					<?php foreach ( $frave_terms as $frave_index => $frave_term ) : ?>
						<?php
						get_template_part(
							'template-parts/components/term-card',
							null,
							array(
								'term'      => $frave_term,
								'number'    => $frave_index + 1,
								'show_line' => true,
								'layout'    => 'band',
							)
						);
						?>
					<?php endforeach; ?>
				</ol>
			</section>
			<?php
		endif;

		if ( ! empty( frave_solutions()[ $frave_slug ]['families'] ) ) {
			get_template_part( 'template-parts/components/family-wheel' );
		}

		get_template_part(
			'template-parts/components/applications',
			null,
			array(
				'items' => frave_lines_of( frave_solution_text( 'applications', $frave_slug ) ),
				'title' => __( 'Productos en los que *participamos*', 'frave' ),
			)
		);

		if ( '' !== trim( get_the_content() ) ) :
			?>
			<section class="section company-content">
				<div class="container content-container">
					<div class="entry-content"><?php the_content(); ?></div>
				</div>
			</section>
			<?php
		endif;

		get_template_part(
			'template-parts/components/cta-panel',
			null,
			array(
				'title'    => frave_solution_text( 'cta_title', $frave_slug ),
				'text'     => frave_solution_text( 'cta_text', $frave_slug ),
				'action'   => $frave_quote,
				/* translators: %s: industry name. */
				'whatsapp' => frave_whatsapp_url( sprintf( __( 'Hola, quisiera información sobre sus soluciones para %s.', 'frave' ), get_the_title() ) ),
			)
		);

		get_template_part(
			'template-parts/solutions/cards',
			null,
			array(
				'cards'   => frave_solution_cards( $frave_slug ),
				'eyebrow' => __( 'Sigue explorando', 'frave' ),
				'title'   => __( 'Otras *industrias*', 'frave' ),
				'compact' => true,
			)
		);
	endwhile;
	?>
</main>
<?php
get_footer();
