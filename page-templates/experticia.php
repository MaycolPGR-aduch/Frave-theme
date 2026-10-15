<?php
/**
 * Template Name: Servicios
 * Template Post Type: page
 *
 * What Frave knows how to do: hero with the word cloud of Peru, technical advice and
 * highlights, "Cómo trabajamos" (tabs or accordion), the industries served, any content
 * written in the editor and a closing call to action. Copy: inc/company.php and the ACF
 * group "Servicios Frave". Called Experticia until October 2026.
 */

get_header();
?>
<main id="primary" class="site-main company-page expertise-page">
	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part(
			'template-parts/company/hero',
			null,
			array(
				'prefix'    => 'frave_expertise',
				'visual'    => 'cloud',
				'secondary' => array( __( 'Cómo trabajamos', 'frave' ), '#como-trabajamos' ),
			)
		);
		get_template_part(
			'template-parts/company/statement',
			null,
			array(
				'prefix'     => 'frave_expertise',
				'highlights' => true,
				'icons'      => array( 'fragrance', 'lab', 'link' ),
			)
		);
		get_template_part( 'template-parts/company/method' );
		get_template_part( 'template-parts/company/industries' );
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
				'title'    => frave_company_text( 'frave_expertise_cta_title' ),
				'text'     => frave_company_text( 'frave_expertise_cta_text' ),
				'action'   => frave_company_action(),
				'whatsapp' => frave_whatsapp_url( frave_whatsapp_message() ),
			)
		);
	endwhile;
	?>
</main>
<?php
get_footer();
