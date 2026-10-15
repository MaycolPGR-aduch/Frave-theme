<?php
/**
 * Template Name: Nosotros
 * Template Post Type: page
 *
 * Who Frave is: hero with the ingredients, the company in management's words, a photo of
 * a working meeting, its essence, the four beliefs and behaviors, any content written in
 * the editor (team, history) and a closing call to action. Copy: inc/company.php and the
 * ACF group "Nosotros Frave".
 */

get_header();
?>
<main id="primary" class="site-main company-page about-page">
	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part(
			'template-parts/company/hero',
			null,
			array(
				'prefix'    => 'frave_about',
				'visual'    => 'image',
				'secondary' => frave_company_page_url( FRAVE_TEMPLATE_EXPERTISE )
					? array( __( 'Nuestra experticia', 'frave' ), frave_company_page_url( FRAVE_TEMPLATE_EXPERTISE ) )
					: array( __( 'Ver el catálogo', 'frave' ), frave_catalog_shop_url() ),
			)
		);
		get_template_part( 'template-parts/company/statement', null, array( 'prefix' => 'frave_about' ) );
		get_template_part( 'template-parts/company/photo' );
		get_template_part( 'template-parts/company/essence' );
		get_template_part( 'template-parts/company/beliefs' );
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
				'title'    => frave_company_text( 'frave_about_cta_title' ),
				'text'     => frave_company_text( 'frave_about_cta_text' ),
				'action'   => frave_company_action(),
				'whatsapp' => frave_whatsapp_url( frave_whatsapp_message() ),
			)
		);
	endwhile;
	?>
</main>
<?php
get_footer();
