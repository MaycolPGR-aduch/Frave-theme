<?php
/** "Nuestras creencias y comportamientos": four numbered cards, each with the behavior it leads to. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_icons = frave_company_belief_icons();
?>
<section class="section section--surface company-beliefs" aria-labelledby="company-beliefs-title">
	<div class="container">
		<div class="section-head" data-reveal="up">
			<p class="eyebrow"><?php esc_html_e( 'Cultura', 'frave' ); ?></p>
			<h2 id="company-beliefs-title"><?php echo frave_emphasis( frave_company_text( 'frave_about_beliefs_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
			<p class="lead"><?php echo esc_html( frave_company_text( 'frave_about_beliefs_text' ) ); ?></p>
		</div>
		<ol class="company-beliefs__grid" data-reveal-group>
			<?php foreach ( $frave_icons as $frave_index => $frave_icon ) : ?>
				<?php
				$frave_title = frave_company_text( 'frave_about_belief_' . $frave_index . '_title' );
				if ( '' === trim( $frave_title ) ) {
					continue;
				}
				$frave_behavior = frave_company_text( 'frave_about_belief_' . $frave_index . '_behavior' );
				?>
				<li class="card company-belief">
					<div class="company-belief__head">
						<span class="icon-tile icon-tile--lg"><?php echo frave_icon( $frave_icon ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
						<span class="company-belief__number" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $frave_index ) ); ?></span>
					</div>
					<h3><?php echo esc_html( $frave_title ); ?></h3>
					<p><?php echo esc_html( frave_company_text( 'frave_about_belief_' . $frave_index . '_text' ) ); ?></p>
					<?php if ( '' !== trim( $frave_behavior ) ) : ?>
						<p class="company-belief__behavior"><strong><?php esc_html_e( 'Comportamiento:', 'frave' ); ?></strong> <?php echo esc_html( $frave_behavior ); ?></p>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
