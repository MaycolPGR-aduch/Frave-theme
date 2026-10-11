<?php
/** "Cómo trabajamos": the introduction stays in place while the three steps stack over each other. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="section section--dark home-steps" aria-labelledby="home-steps-title">
	<span class="orbit orbit--soft home-steps__orbit" aria-hidden="true"></span>
	<div class="container home-steps__grid">
		<div class="home-steps__intro" data-reveal="up">
			<p class="eyebrow"><?php echo esc_html( frave_home_text( 'frave_home_steps_eyebrow' ) ); ?></p>
			<h2 id="home-steps-title"><?php echo frave_emphasis( frave_home_text( 'frave_home_steps_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
			<p class="lead"><?php echo esc_html( frave_home_text( 'frave_home_steps_text' ) ); ?></p>
			<?php $frave_cta = frave_header_cta(); ?>
			<?php if ( $frave_cta && ! $frave_cta['external'] ) : ?>
				<?php frave_button( frave_home_text( 'frave_home_hero_primary' ), $frave_cta['url'] ); ?>
			<?php endif; ?>
		</div>
		<ol class="stack home-steps__list" data-stack>
			<?php foreach ( array( 1, 2, 3 ) as $frave_step ) : ?>
				<li class="stack__item card card--dark home-step">
					<span class="home-step__number" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $frave_step ) ); ?></span>
					<h3><?php echo esc_html( frave_home_text( 'frave_home_step_' . $frave_step . '_title' ) ); ?></h3>
					<p><?php echo esc_html( frave_home_text( 'frave_home_step_' . $frave_step . '_text' ) ); ?></p>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
