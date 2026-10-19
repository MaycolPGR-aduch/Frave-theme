<?php
/**
 * Wheel of olfactory families: a ring with the nine families of inc/fragrance-families.php
 * around the chosen one's name, and its card beside it (notes, its role in perfumery, links
 * to the guide and the catalog). Choosing a family turns the ring so it faces the card
 * (assets/src/js/modules/family-wheel.js); without JavaScript every card is shown.
 *
 * Arguments: eyebrow, title (*words* highlighted), text.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_wheel    = wp_parse_args(
	$args ?? array(),
	array(
		'eyebrow' => __( 'Familias olfativas', 'frave' ),
		'title'   => __( 'Gira la *rueda de los aromas*', 'frave' ),
		'text'    => __( 'Cada perfume pertenece a una familia. Elige una y descubre cómo huele, qué transmite y qué papel cumple en la perfumería.', 'frave' ),
	)
);
$frave_families = frave_fragrance_families();
$frave_total    = count( $frave_families );
$frave_step     = 360 / $frave_total;
$frave_guide    = frave_fragrance_guide_url();

// Ring segments in the SVG's user space (radius 50): angles clockwise from the top.
$frave_point = static function ( float $radius, float $degrees ): string {
	$radians = deg2rad( $degrees );
	return sprintf( '%.2f %.2f', $radius * sin( $radians ), -$radius * cos( $radians ) );
};
$frave_outer = 48;
$frave_inner = 27;
$frave_gap   = 1.1;
?>
<section class="section on-dark family-wheel" aria-labelledby="family-wheel-title" data-family-wheel>
	<span class="orbit family-wheel__orbit" aria-hidden="true"></span>
	<div class="container">
		<div class="section-head section-head--center" data-reveal="up">
			<p class="eyebrow"><?php echo esc_html( $frave_wheel['eyebrow'] ); ?></p>
			<h2 id="family-wheel-title"><?php echo frave_emphasis( $frave_wheel['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
			<?php if ( '' !== $frave_wheel['text'] ) : ?>
				<p class="lead"><?php echo esc_html( $frave_wheel['text'] ); ?></p>
			<?php endif; ?>
		</div>

		<div class="family-wheel__layout">
			<div class="family-wheel__stage" data-reveal="up">
				<div class="family-wheel__dial" data-wheel-dial>
					<svg class="family-wheel__ring" viewBox="-50 -50 100 100" aria-hidden="true" focusable="false">
						<?php
						$frave_index = 0;
						foreach ( $frave_families as $frave_slug => $frave_family ) :
							$frave_from = $frave_index * $frave_step + $frave_gap / 2;
							$frave_to   = ( $frave_index + 1 ) * $frave_step - $frave_gap / 2;
							$frave_mid  = deg2rad( ( $frave_index + 0.5 ) * $frave_step );
							++$frave_index;
							?>
							<path class="family-wheel__segment" data-wheel-segment="<?php echo esc_attr( $frave_slug ); ?>" fill="<?php echo esc_attr( $frave_family['tone'] ); ?>" style="--dx: <?php echo esc_attr( sprintf( '%.2fpx', 2.4 * sin( $frave_mid ) ) ); ?>; --dy: <?php echo esc_attr( sprintf( '%.2fpx', -2.4 * cos( $frave_mid ) ) ); ?>" d="<?php echo esc_attr( sprintf( 'M%1$s A%2$d %2$d 0 0 1 %3$s L%4$s A%5$d %5$d 0 0 0 %6$s Z', $frave_point( $frave_outer, $frave_from ), $frave_outer, $frave_point( $frave_outer, $frave_to ), $frave_point( $frave_inner, $frave_to ), $frave_inner, $frave_point( $frave_inner, $frave_from ) ) ); ?>"/>
						<?php endforeach; ?>
					</svg>
					<div class="family-wheel__tabs" role="tablist" aria-label="<?php esc_attr_e( 'Familias olfativas', 'frave' ); ?>">
						<?php
						$frave_index = 0;
						foreach ( $frave_families as $frave_slug => $frave_family ) :
							$frave_mid = deg2rad( ( $frave_index + 0.5 ) * $frave_step );
							// Label on the middle of the ring: radius 37.5 of 100.
							$frave_x = 50 + 37.5 * sin( $frave_mid );
							$frave_y = 50 - 37.5 * cos( $frave_mid );
							?>
							<button class="family-wheel__tab" type="button" role="tab" id="rueda-tab-<?php echo esc_attr( $frave_slug ); ?>" aria-controls="rueda-<?php echo esc_attr( $frave_slug ); ?>" aria-selected="<?php echo 0 === $frave_index ? 'true' : 'false'; ?>" tabindex="<?php echo 0 === $frave_index ? '0' : '-1'; ?>" data-number="<?php echo esc_attr( sprintf( '%02d', $frave_index + 1 ) ); ?>" style="--x: <?php echo esc_attr( sprintf( '%.2f%%', $frave_x ) ); ?>; --y: <?php echo esc_attr( sprintf( '%.2f%%', $frave_y ) ); ?>"><?php echo esc_html( $frave_family['name'] ); ?></button>
							<?php
							++$frave_index;
						endforeach;
						?>
					</div>
				</div>
				<div class="family-wheel__center" aria-hidden="true">
					<?php echo frave_icon( 'fragrance' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
					<span class="family-wheel__name" data-wheel-name><?php echo esc_html( reset( $frave_families )['name'] ); ?></span>
					<span class="family-wheel__count"><span data-wheel-number>01</span> / <?php echo esc_html( sprintf( '%02d', $frave_total ) ); ?></span>
				</div>
				<span class="family-wheel__pointer" aria-hidden="true"></span>
			</div>

			<div class="family-wheel__panels">
				<?php
				$frave_index = 0;
				foreach ( $frave_families as $frave_slug => $frave_family ) :
					++$frave_index;
					$frave_catalog = frave_fragrance_family_catalog_url( $frave_family['name'] );
					?>
					<div class="family-wheel__panel" role="tabpanel" id="rueda-<?php echo esc_attr( $frave_slug ); ?>" aria-labelledby="rueda-tab-<?php echo esc_attr( $frave_slug ); ?>" tabindex="0" style="--family-tone: <?php echo esc_attr( $frave_family['tone'] ); ?>">
						<p class="family-wheel__kicker">
							<?php
							/* translators: 1: family number, 2: number of families. */
							echo esc_html( sprintf( __( 'Familia %1$02d de %2$02d', 'frave' ), $frave_index, $frave_total ) );
							?>
						</p>
						<h3 class="family-wheel__title"><?php echo esc_html( $frave_family['name'] ); ?></h3>
						<p class="family-wheel__tagline"><?php echo esc_html( $frave_family['tagline'] ); ?></p>
						<p class="family-wheel__role"><strong><?php esc_html_e( 'En perfumería:', 'frave' ); ?></strong> <?php echo esc_html( $frave_family['perfumery'] ); ?></p>
						<ul class="family-wheel__notes" aria-label="<?php esc_attr_e( 'Notas típicas', 'frave' ); ?>">
							<?php foreach ( $frave_family['notes'] as $frave_note ) : ?>
								<li><?php echo esc_html( $frave_note ); ?></li>
							<?php endforeach; ?>
						</ul>
						<p class="family-wheel__conveys"><?php esc_html_e( 'Transmite', 'frave' ); ?>: <span><?php echo esc_html( $frave_family['conveys'] ); ?></span></p>
						<?php if ( '' !== $frave_guide || '' !== $frave_catalog ) : ?>
							<div class="family-wheel__actions">
								<?php if ( '' !== $frave_catalog ) : ?>
									<a class="link-arrow" href="<?php echo esc_url( $frave_catalog ); ?>">
										<?php
										/* translators: %s: family name in plural, e.g. «cítricas». */
										echo esc_html( sprintf( __( 'Ver fragancias %s', 'frave' ), $frave_family['plural'] ) );
										?>
										<?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
									</a>
								<?php endif; ?>
								<?php if ( '' !== $frave_guide ) : ?>
									<a class="link-arrow" href="<?php echo esc_url( $frave_guide . '#familia-' . $frave_slug ); ?>">
										<?php
										/* translators: %s: family name, e.g. «Cítrica». */
										echo esc_html( sprintf( __( 'Más sobre la familia %s', 'frave' ), $frave_family['name'] ) );
										?>
										<?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
									</a>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
				<?php if ( '' !== $frave_guide ) : ?>
					<?php frave_button( __( 'Ver la guía completa', 'frave' ), $frave_guide, 'btn btn--light family-wheel__guide' ); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
