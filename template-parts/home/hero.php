<?php
/**
 * Home hero: full-screen video that plays once and rests on its last frame (the fruit fall
 * on the left); on the right, a title whose highlighted words take turns over aroma waves
 * that spread from the brand's drop, the two main actions and three facts. One button pauses,
 * resumes or replays all that motion (assets/src/js/modules/hero-video.js). Without
 * JavaScript or with reduced motion nothing moves; the video also stays as its poster (last
 * frame) when the visitor saves data. The still lies under the video, which is transparent
 * until it plays; phones get the portrait still and video, behind the end of the copy.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_media = frave_home_hero_media();
$frave_cta   = frave_header_cta();
?>
<section class="home-hero<?php echo $frave_media['portrait'] ? ' home-hero--portrait' : ''; ?>" aria-labelledby="home-hero-title" data-hero>
	<div class="home-hero__media">
		<picture class="home-hero__still">
			<?php if ( $frave_media['portrait'] ) : ?>
				<source media="<?php echo esc_attr( FRAVE_HERO_PORTRAIT_QUERY ); ?>" srcset="<?php echo esc_url( $frave_media['portrait']['poster'] ); ?>" width="720" height="1280">
			<?php endif; ?>
			<img src="<?php echo esc_url( $frave_media['poster'] ); ?>" alt="" fetchpriority="high" decoding="async">
		</picture>
		<?php
		// Phones swap in the portrait sources before the video loads (hero-video.js).
		$frave_portrait = $frave_media['portrait'] ? wp_json_encode(
			array(
				'query'   => FRAVE_HERO_PORTRAIT_QUERY,
				'sources' => $frave_media['portrait']['sources'],
			)
		) : '';
		?>
		<video class="home-hero__video" muted playsinline preload="none" aria-hidden="true" data-hero-video<?php echo '' !== $frave_portrait ? ' data-portrait="' . esc_attr( $frave_portrait ) . '"' : ''; ?>>
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
			<?php get_template_part( 'template-parts/components/aroma-waves', null, array( 'class' => 'home-hero__waves' ) ); ?>
			<h1 class="display home-hero__title" id="home-hero-title"><?php echo frave_hero_title_html( frave_home_text( 'frave_home_hero_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h1>
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
			<?php $frave_facts = frave_lines_of( frave_home_text( 'frave_home_hero_facts' ) ); ?>
			<?php if ( $frave_facts ) : ?>
				<ul class="home-hero__facts">
					<?php foreach ( $frave_facts as $frave_fact ) : ?>
						<li><?php echo frave_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?><?php echo esc_html( $frave_fact ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>
</section>
