<?php
/** "Nuestra esencia": management's one-sentence purpose on a sand band, with the isotype as a seal. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="section section--sand company-essence" aria-labelledby="company-essence-title">
	<span class="orbit orbit--soft company-essence__orbit" aria-hidden="true"></span>
	<div class="company-essence__decor" aria-hidden="true"><?php echo frave_isotype(); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></div>
	<div class="container company-essence__inner" data-reveal="up">
		<p class="eyebrow" id="company-essence-title"><?php esc_html_e( 'Nuestra esencia', 'frave' ); ?></p>
		<blockquote class="company-essence__quote">
			<p><?php echo frave_emphasis( frave_company_text( 'frave_about_essence_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></p>
		</blockquote>
		<p class="lead company-essence__text"><?php echo esc_html( frave_company_text( 'frave_about_essence_text' ) ); ?></p>
	</div>
</section>
