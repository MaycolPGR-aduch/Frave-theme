<?php
/**
 * "Razones para elegirnos": the four reasons (editable in ACF) as expanding panels, like the
 * industries (industry-panels.js): each one shows its picture, icon and title, and the open
 * one grows with its text. The reasons have no page, so a panel only opens. Pictures:
 * assets/media/razones/razon-{1-4}.webp (1080 × 1350) and -640.webp; until they exist, each
 * panel shows its own colour with its icon as a large mark. On white, without a background
 * texture: the photographs of the panels are enough.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_icons = frave_home_reason_icons();
?>
<section class="section home-reasons" aria-labelledby="home-reasons-title">
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
		<ul class="industry-panels home-reasons__panels" data-panels data-reveal="up">
			<?php
			$frave_open = true;
			foreach ( array( 1, 2, 3, 4 ) as $frave_reason ) :
				$frave_title = frave_home_text( 'frave_home_reason_' . $frave_reason . '_title' );
				if ( '' === trim( $frave_title ) ) {
					continue;
				}
				$frave_icon    = frave_home_text( 'frave_home_reason_' . $frave_reason . '_icon' );
				$frave_icon    = isset( $frave_icons[ $frave_icon ] ) ? $frave_icon : 'sparkle';
				$frave_base    = 'assets/media/razones/razon-' . $frave_reason;
				$frave_image   = file_exists( FRAVE_THEME_DIR . '/' . $frave_base . '-640.webp' )
					? frave_theme_image(
						$frave_base,
						1080,
						1350,
						640,
						array(
							'class' => 'industry-panel__image',
							'sizes' => '(max-width: 63.99rem) 100vw, 40vw',
						)
					)
					: '';
				$frave_classes = 'industry-panel reason-panel reason-panel--' . $frave_reason;
				if ( $frave_open ) {
					$frave_classes .= ' is-open';
				}
				if ( '' === $frave_image ) {
					$frave_classes .= ' reason-panel--plain';
				}
				$frave_open = false;
				?>
				<?php // Focusable so the keyboard can open it: the reasons have no page of their own. ?>
				<li class="<?php echo esc_attr( $frave_classes ); ?>" data-panel tabindex="0">
					<?php if ( '' !== $frave_image ) : ?>
						<?php echo $frave_image; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?>
					<?php else : ?>
						<span class="reason-panel__mark" aria-hidden="true"><?php echo frave_icon( $frave_icon ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
					<?php endif; ?>
					<span class="industry-panel__icon" aria-hidden="true"><?php echo frave_icon( $frave_icon ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
					<div class="industry-panel__content">
						<h3 class="industry-panel__title"><?php echo esc_html( $frave_title ); ?></h3>
						<div class="industry-panel__more">
							<p class="industry-panel__summary"><?php echo esc_html( frave_home_text( 'frave_home_reason_' . $frave_reason . '_text' ) ); ?></p>
						</div>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
