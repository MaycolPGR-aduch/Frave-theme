<?php
/**
 * The industries with a published page as expanding panels: each one shows its picture,
 * icon and name; the open one grows and adds its summary, the product lines it draws on and
 * «Ver la solución». The first is open; pointing at another (or focusing it) opens that one
 * (industry-panels.js). On phones and tablets they stack as an accordion: a tap opens a
 * closed panel, and a tap on the open one leads to its page. The whole panel is the link.
 *
 * Arguments: cards (frave_solution_cards()), eyebrow, title (*words* highlighted), text,
 * surface (true for the warm surface background), compact (lower panels, for «Otras
 * industrias» at the end of each solution).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_cards = $args['cards'] ?? array();
if ( ! $frave_cards ) {
	return;
}
$frave_classes  = 'section solution-list';
$frave_classes .= ! empty( $args['surface'] ) ? ' section--surface' : '';
$frave_classes .= ! empty( $args['compact'] ) ? ' solution-list--compact' : '';
?>
<section class="<?php echo esc_attr( $frave_classes ); ?>" id="industrias" aria-labelledby="solution-list-title">
	<div class="container">
		<div class="section-head" data-reveal="up">
			<p class="eyebrow"><?php echo esc_html( $args['eyebrow'] ?? __( 'Soluciones', 'frave' ) ); ?></p>
			<h2 id="solution-list-title"><?php echo frave_emphasis( (string) ( $args['title'] ?? '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
			<?php if ( ! empty( $args['text'] ) ) : ?>
				<p class="lead"><?php echo esc_html( $args['text'] ); ?></p>
			<?php endif; ?>
		</div>
		<ul class="industry-panels" data-panels data-reveal="up">
			<?php foreach ( $frave_cards as $frave_index => $frave_card ) : ?>
				<?php
				$frave_image = '' !== $frave_card['image'] && file_exists( FRAVE_THEME_DIR . '/' . $frave_card['image'] . '-640.webp' )
					? frave_theme_image(
						$frave_card['image'],
						1080,
						1350,
						640,
						array(
							'class' => 'industry-panel__image',
							'sizes' => '(max-width: 63.99rem) 100vw, 40vw',
						)
					)
					: '';
				?>
				<li class="industry-panel solution-card<?php echo 0 === $frave_index ? ' is-open' : ''; ?><?php echo '' === $frave_image ? ' industry-panel--plain' : ''; ?>" data-panel>
					<?php echo $frave_image; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?>
					<span class="industry-panel__icon" aria-hidden="true"><?php echo frave_icon( $frave_card['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
					<div class="industry-panel__content">
						<h3 class="industry-panel__title"><a class="industry-panel__link" href="<?php echo esc_url( $frave_card['url'] ); ?>"><?php echo esc_html( $frave_card['title'] ); ?></a></h3>
						<div class="industry-panel__more">
							<p class="industry-panel__summary"><?php echo esc_html( $frave_card['summary'] ); ?></p>
							<?php if ( $frave_card['lines'] ) : ?>
								<ul class="industry-panel__lines" aria-label="<?php esc_attr_e( 'Líneas que usa', 'frave' ); ?>">
									<?php foreach ( $frave_card['lines'] as $frave_line ) : ?>
										<li><?php echo esc_html( $frave_line ); ?></li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
							<span class="industry-panel__go" aria-hidden="true"><?php esc_html_e( 'Ver la solución', 'frave' ); ?><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
						</div>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
