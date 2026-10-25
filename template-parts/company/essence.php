<?php
/** "Nuestra esencia": management's one-sentence purpose on a sand band, over a perfumer's atelier photograph. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="section section--sand company-essence has-section-bg" aria-labelledby="company-essence-title">
	<?php get_template_part( 'template-parts/components/section-bg', null, array( 'image' => 'nosotros-esencia' ) ); ?>
	<div class="container company-essence__inner" data-reveal="up">
		<p class="eyebrow" id="company-essence-title"><?php esc_html_e( 'Nuestra esencia', 'frave' ); ?></p>
		<blockquote class="company-essence__quote">
			<p><?php echo frave_emphasis( frave_company_text( 'frave_about_essence_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></p>
		</blockquote>
		<p class="lead company-essence__text"><?php echo esc_html( frave_company_text( 'frave_about_essence_text' ) ); ?></p>
	</div>
</section>
