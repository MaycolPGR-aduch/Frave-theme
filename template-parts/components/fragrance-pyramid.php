<?php
/**
 * Interactive olfactory pyramid: three stacked tiers (top, heart and base notes) that are
 * the tabs of a tab list, and the chosen level's card beside them (when it is perceived,
 * its role, typical notes, the families that bring it and what it means for the product).
 * assets/src/js/modules/tabs.js switches the cards; without JavaScript every card is shown.
 *
 * Arguments: eyebrow, title (*words* highlighted), text.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_pyramid  = wp_parse_args(
	$args ?? array(),
	array(
		'eyebrow' => __( 'Cómo evoluciona una fragancia', 'frave' ),
		'title'   => __( 'Tres fases, *una sola fragancia*', 'frave' ),
		'text'    => __( 'Toca cada nivel de la pirámide para ver cuándo se percibe, qué notas lo forman y qué aporta a tu producto.', 'frave' ),
	)
);
$frave_levels   = frave_fragrance_pyramid();
$frave_families = frave_fragrance_families();
$frave_guide    = frave_fragrance_guide_url();
$frave_count    = count( $frave_levels );
?>
<section class="section pyramid" id="piramide" aria-labelledby="pyramid-title" data-tabs>
	<div class="container">
		<div class="section-head" data-reveal="up">
			<p class="eyebrow"><?php echo esc_html( $frave_pyramid['eyebrow'] ); ?></p>
			<h2 id="pyramid-title"><?php echo frave_emphasis( $frave_pyramid['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
			<?php if ( '' !== $frave_pyramid['text'] ) : ?>
				<p class="lead"><?php echo esc_html( $frave_pyramid['text'] ); ?></p>
			<?php endif; ?>
		</div>

		<div class="pyramid__layout">
			<div class="pyramid__figure" data-reveal="up">
				<div class="pyramid__shape" role="tablist" aria-label="<?php esc_attr_e( 'Niveles de la pirámide olfativa', 'frave' ); ?>" aria-orientation="vertical">
					<?php
					$frave_index = 0;
					foreach ( $frave_levels as $frave_slug => $frave_level ) :
						?>
						<div class="pyramid__level pyramid__level--<?php echo esc_attr( $frave_slug ); ?>" style="--level-tone: <?php echo esc_attr( $frave_level['tone'] ); ?>; --level-accent: <?php echo esc_attr( $frave_level['accent'] ); ?>">
							<button class="pyramid__tier" type="button" role="tab" id="piramide-tab-<?php echo esc_attr( $frave_slug ); ?>" aria-controls="piramide-<?php echo esc_attr( $frave_slug ); ?>" aria-selected="<?php echo 0 === $frave_index ? 'true' : 'false'; ?>" tabindex="<?php echo 0 === $frave_index ? '0' : '-1'; ?>">
								<span class="pyramid__tier-name"><?php echo esc_html( $frave_level['short'] ); ?></span>
								<span class="pyramid__tier-time"><?php echo esc_html( $frave_level['range'] ); ?></span>
							</button>
						</div>
						<?php
						++$frave_index;
					endforeach;
					?>
				</div>
				<p class="pyramid__axis" aria-hidden="true">
					<span><?php esc_html_e( 'Más volátil', 'frave' ); ?></span>
					<span><?php esc_html_e( 'Más persistente', 'frave' ); ?></span>
				</p>
			</div>

			<div class="pyramid__panels">
				<?php
				$frave_index = 0;
				foreach ( $frave_levels as $frave_slug => $frave_level ) :
					++$frave_index;
					?>
					<div class="pyramid__panel" role="tabpanel" id="piramide-<?php echo esc_attr( $frave_slug ); ?>" aria-labelledby="piramide-tab-<?php echo esc_attr( $frave_slug ); ?>" tabindex="0" style="--level-tone: <?php echo esc_attr( $frave_level['tone'] ); ?>; --level-accent: <?php echo esc_attr( $frave_level['accent'] ); ?>">
						<p class="pyramid__kicker">
							<?php
							/* translators: 1: level number, 2: number of levels. */
							echo esc_html( sprintf( __( 'Nivel %1$d de %2$d', 'frave' ), $frave_index, $frave_count ) );
							?>
						</p>
						<h3 class="pyramid__title"><?php echo esc_html( $frave_level['name'] ); ?></h3>
						<p class="pyramid__time"><?php echo frave_icon( 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?><?php echo esc_html( $frave_level['time'] ); ?></p>
						<p class="pyramid__role"><?php echo esc_html( $frave_level['role'] ); ?></p>
						<dl class="pyramid__facts">
							<div>
								<dt><?php esc_html_e( 'Notas típicas', 'frave' ); ?></dt>
								<dd>
									<ul class="pyramid__notes">
										<?php foreach ( $frave_level['notes'] as $frave_note ) : ?>
											<li><?php echo esc_html( $frave_note ); ?></li>
										<?php endforeach; ?>
									</ul>
								</dd>
							</div>
							<div>
								<dt><?php esc_html_e( 'Familias que la aportan', 'frave' ); ?></dt>
								<dd>
									<ul class="pyramid__families">
										<?php foreach ( $frave_level['families'] as $frave_family_slug ) : ?>
											<?php
											if ( ! isset( $frave_families[ $frave_family_slug ] ) ) {
												continue;
											}
											$frave_family = $frave_families[ $frave_family_slug ];
											?>
											<li>
												<?php if ( '' !== $frave_guide ) : ?>
													<a class="family-chip" href="<?php echo esc_url( $frave_guide . '#familia-' . $frave_family_slug ); ?>" style="--family-tone: <?php echo esc_attr( $frave_family['tone'] ); ?>"><?php echo esc_html( $frave_family['name'] ); ?></a>
												<?php else : ?>
													<span class="family-chip" style="--family-tone: <?php echo esc_attr( $frave_family['tone'] ); ?>"><?php echo esc_html( $frave_family['name'] ); ?></span>
												<?php endif; ?>
											</li>
										<?php endforeach; ?>
									</ul>
								</dd>
							</div>
						</dl>
						<p class="pyramid__product"><strong><?php esc_html_e( 'En tu producto:', 'frave' ); ?></strong> <?php echo esc_html( $frave_level['product'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
