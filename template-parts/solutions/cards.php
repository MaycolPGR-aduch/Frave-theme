<?php
/**
 * Grid of industries with a published page: icon, name, summary and a link to the page.
 *
 * Arguments: cards (frave_solution_cards()), eyebrow, title (*words* highlighted), text,
 * surface (true for the warm surface background).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_cards = $args['cards'] ?? array();
if ( ! $frave_cards ) {
	return;
}
?>
<section class="section solution-list<?php echo ! empty( $args['surface'] ) ? ' section--surface' : ''; ?>" id="industrias" aria-labelledby="solution-list-title">
	<div class="container">
		<div class="section-head" data-reveal="up">
			<p class="eyebrow"><?php echo esc_html( $args['eyebrow'] ?? __( 'Soluciones', 'frave' ) ); ?></p>
			<h2 id="solution-list-title"><?php echo frave_emphasis( (string) ( $args['title'] ?? '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
			<?php if ( ! empty( $args['text'] ) ) : ?>
				<p class="lead"><?php echo esc_html( $args['text'] ); ?></p>
			<?php endif; ?>
		</div>
		<ul class="solution-cards" data-reveal-group>
			<?php foreach ( $frave_cards as $frave_card ) : ?>
				<li class="card card--interactive solution-card">
					<span class="icon-tile icon-tile--lg"><?php echo frave_icon( $frave_card['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
					<h3><a class="card__link" href="<?php echo esc_url( $frave_card['url'] ); ?>"><?php echo esc_html( $frave_card['title'] ); ?></a></h3>
					<p><?php echo esc_html( $frave_card['summary'] ); ?></p>
					<span class="link-arrow" aria-hidden="true"><?php esc_html_e( 'Ver la solución', 'frave' ); ?><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
