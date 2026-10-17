<?php
/**
 * Hero of the solution pages, on the warm tone of the company pages: copy and actions,
 * and an image in an arch.
 *
 * Arguments: eyebrow, title (*words* highlighted), lead, action ([ url, external ] of the
 * quote button), secondary ([ label, url ]), image (theme asset path; a 4:5 WebP of
 * assets/media/soluciones also offers its 640 px version to phones).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_hero   = wp_parse_args(
	$args ?? array(),
	array(
		'eyebrow'   => '',
		'title'     => '',
		'lead'      => '',
		'action'    => null,
		'secondary' => null,
		'image'     => '',
	)
);
$frave_action = $frave_hero['action'];
$frave_base   = preg_replace( '/\.webp$/', '', $frave_hero['image'] );
$frave_image  = '' !== $frave_hero['image'] && $frave_base !== $frave_hero['image']
	? frave_theme_image(
		(string) $frave_base,
		1080,
		1350,
		640,
		array(
			'sizes'         => '(max-width: 30rem) 90vw, 26rem',
			'loading'       => 'eager',
			'fetchpriority' => 'high',
		)
	)
	: '';
?>
<section class="company-hero solution-hero" aria-labelledby="solution-hero-title">
	<span class="orbit orbit--soft company-hero__orbit" aria-hidden="true"></span>
	<div class="container company-hero__grid">
		<div class="company-hero__copy">
			<p class="eyebrow"><?php echo esc_html( $frave_hero['eyebrow'] ); ?></p>
			<h1 class="display company-hero__title" id="solution-hero-title"><?php echo frave_emphasis( $frave_hero['title'], 'pill' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h1>
			<?php if ( '' !== $frave_hero['lead'] ) : ?>
				<p class="lead company-hero__lead"><?php echo esc_html( $frave_hero['lead'] ); ?></p>
			<?php endif; ?>
			<div class="btn-row">
				<?php if ( $frave_action ) : ?>
					<a class="btn btn--primary" href="<?php echo esc_url( $frave_action['url'] ); ?>"<?php echo $frave_action['external'] ? ' target="_blank" rel="noopener"' : ''; ?>>
						<span><?php esc_html_e( 'Solicitar cotización', 'frave' ); ?></span>
						<?php if ( $frave_action['external'] ) : ?>
							<span class="screen-reader-text"><?php esc_html_e( '(se abre en una nueva pestaña)', 'frave' ); ?></span>
						<?php endif; ?>
						<span class="btn__icon"><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
					</a>
				<?php endif; ?>
				<?php if ( $frave_hero['secondary'] ) : ?>
					<?php frave_button( $frave_hero['secondary'][0], $frave_hero['secondary'][1], 'btn btn--dark', str_starts_with( $frave_hero['secondary'][1], '#' ) ? 'chevron-down' : 'arrow-right' ); ?>
				<?php endif; ?>
			</div>
		</div>
		<?php if ( '' !== $frave_hero['image'] ) : ?>
			<div class="solution-hero__media media-frame shape-arch">
				<?php if ( '' !== $frave_image && file_exists( FRAVE_THEME_DIR . '/' . $frave_base . '-640.webp' ) ) : ?>
					<?php echo $frave_image; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?>
				<?php else : ?>
					<img src="<?php echo esc_url( frave_asset_url( $frave_hero['image'] ) ); ?>" alt="" fetchpriority="high" decoding="async">
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
