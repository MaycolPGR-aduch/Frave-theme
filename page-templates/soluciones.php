<?php
/**
 * Template Name: Soluciones
 * Template Post Type: page
 *
 * Hub of the solutions by industry: hero, the industries with a published page, any
 * content written in the editor and a closing call to action.
 */

get_header();
?>
<main id="primary" class="site-main solutions-page">
	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part(
			'template-parts/solutions/hero',
			null,
			array(
				'eyebrow'   => __( 'Soluciones', 'frave' ),
				'title'     => __( 'Soluciones para *cada industria*', 'frave' ),
				'lead'      => __( 'Combinamos fragancias, sabores y envases según lo que necesita tu sector: perfumería, cosmética, hogar, ambientación y alimentos.', 'frave' ),
				'action'    => frave_company_action(),
				'secondary' => array( __( 'Ver las industrias', 'frave' ), '#industrias' ),
				'image'     => 'assets/media/hero-frutas-poster.jpg',
			)
		);
		get_template_part(
			'template-parts/solutions/cards',
			null,
			array(
				'cards'   => frave_solution_cards(),
				'eyebrow' => __( 'Industrias', 'frave' ),
				'title'   => __( '¿En qué industria está *tu producto*?', 'frave' ),
				'text'    => __( 'Cada solución reúne lo que te ofrecemos de nuestras tres líneas.', 'frave' ),
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
				'title'    => __( '¿No encuentras *tu industria*?', 'frave' ),
				'text'     => __( 'Cuéntanos sobre tu producto y te orientamos.', 'frave' ),
				'action'   => frave_company_action(),
				'whatsapp' => frave_whatsapp_url( frave_whatsapp_message() ),
			)
		);
	endwhile;
	?>
</main>
<?php
get_footer();
