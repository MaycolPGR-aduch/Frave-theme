<?php
/** "Razones para elegirnos": four cards with an icon, editable in ACF. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_icons = frave_home_reason_icons();
?>
<section class="section section--surface home-reasons has-section-bg" aria-labelledby="home-reasons-title">
	<?php
	get_template_part(
		'template-parts/components/section-bg',
		null,
		array(
			'image'  => 'textura-luz-sombra',
			'width'  => 2400,
			'height' => 1350,
			'small'  => 1200,
			'veil'   => 'surface',
		)
	);
	?>
	<div class="container">
		<div class="section-head section-head--center" data-reveal="up">
			<p class="eyebrow"><?php echo esc_html( frave_home_text( 'frave_home_reasons_eyebrow' ) ); ?></p>
			<h2 id="home-reasons-title"><?php echo frave_emphasis( frave_home_text( 'frave_home_reasons_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
			<p class="lead"><?php echo esc_html( frave_home_text( 'frave_home_reasons_text' ) ); ?></p>
			<?php $frave_expertise = frave_company_page_url( FRAVE_TEMPLATE_EXPERTISE ); ?>
			<?php if ( $frave_expertise ) : ?>
				<a class="link-arrow" href="<?php echo esc_url( $frave_expertise ); ?>"><?php esc_html_e( 'Conoce nuestros servicios', 'frave' ); ?><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></a>
			<?php endif; ?>
		</div>
		<ul class="feature-grid home-reasons__grid" data-reveal-group>
			<?php foreach ( array( 1, 2, 3, 4 ) as $frave_reason ) : ?>
				<?php
				$frave_title = frave_home_text( 'frave_home_reason_' . $frave_reason . '_title' );
				if ( '' === trim( $frave_title ) ) {
					continue;
				}
				$frave_icon = frave_home_text( 'frave_home_reason_' . $frave_reason . '_icon' );
				$frave_icon = isset( $frave_icons[ $frave_icon ] ) ? $frave_icon : 'sparkle';
				?>
				<li class="card card--interactive home-reason">
					<span class="icon-tile icon-tile--lg"><?php echo frave_icon( $frave_icon ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
					<h3><?php echo esc_html( $frave_title ); ?></h3>
					<p><?php echo esc_html( frave_home_text( 'frave_home_reason_' . $frave_reason . '_text' ) ); ?></p>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
