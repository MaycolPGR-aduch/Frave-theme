<?php
/**
 * Home hero: full-screen video that plays once and rests on its last frame (the fruit fall
 * on the left), a title with a highlighted word and the two main actions on the right. Without JavaScript, with reduced motion or
 * when the visitor saves data, the poster (last frame) is shown instead.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_media = frave_home_hero_media();
$frave_cta   = frave_header_cta();
?>
<section class="home-hero" aria-labelledby="home-hero-title" data-hero>
	<div class="home-hero__media">
		<video class="home-hero__video" muted playsinline preload="none" poster="<?php echo esc_url( $frave_media['poster'] ); ?>" aria-hidden="true" data-hero-video>
			<?php foreach ( $frave_media['sources'] as $frave_source ) : ?>
				<source src="<?php echo esc_url( $frave_source['src'] ); ?>" type="<?php echo esc_attr( $frave_source['type'] ); ?>">
			<?php endforeach; ?>
		</video>
		<button class="home-hero__toggle" type="button" hidden data-hero-toggle data-label-pause="<?php esc_attr_e( 'Pausar la animación', 'frave' ); ?>" data-label-play="<?php esc_attr_e( 'Reproducir la animación', 'frave' ); ?>" data-label-replay="<?php esc_attr_e( 'Ver de nuevo la animación', 'frave' ); ?>">
			<span data-hero-icon="pause"><?php echo frave_icon( 'pause' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
			<span data-hero-icon="play" hidden><?php echo frave_icon( 'play' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
			<span data-hero-icon="replay" hidden><?php echo frave_icon( 'replay' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
		</button>
	</div>
	<div class="container home-hero__inner">
		<div class="home-hero__copy">
			<p class="eyebrow"><?php echo esc_html( frave_home_text( 'frave_home_hero_eyebrow' ) ); ?></p>
			<h1 class="display home-hero__title" id="home-hero-title"><?php echo frave_emphasis( frave_home_text( 'frave_home_hero_title' ), 'pill' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h1>
			<p class="lead home-hero__lead"><?php echo esc_html( frave_home_text( 'frave_home_hero_text' ) ); ?></p>
			<div class="btn-row">
				<?php if ( $frave_cta ) : ?>
					<a class="btn btn--primary" href="<?php echo esc_url( $frave_cta['url'] ); ?>"<?php echo $frave_cta['external'] ? ' target="_blank" rel="noopener"' : ''; ?>>
						<span><?php echo esc_html( frave_home_text( 'frave_home_hero_primary' ) ); ?></span>
						<?php if ( $frave_cta['external'] ) : ?>
							<span class="screen-reader-text"><?php esc_html_e( '(se abre en una nueva pestaña)', 'frave' ); ?></span>
						<?php endif; ?>
						<span class="btn__icon"><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
					</a>
				<?php endif; ?>
				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<?php frave_button( frave_home_text( 'frave_home_hero_secondary' ), frave_catalog_shop_url(), 'btn btn--dark' ); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
