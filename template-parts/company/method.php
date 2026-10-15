<?php
/**
 * "Cómo trabajamos": four steps. With JavaScript they are tabs on large screens and an
 * accordion on phones (method.js), with one step open; each step has its own address
 * (#paso-escuchamos…) to link to it directly. Without JavaScript every step is visible.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_steps = array();
foreach ( array( 1, 2, 3, 4 ) as $frave_index ) {
	$frave_title = frave_company_text( 'frave_expertise_step_' . $frave_index . '_title' );
	if ( '' === trim( $frave_title ) ) {
		continue;
	}
	$frave_steps[] = array(
		'number' => sprintf( '%02d', count( $frave_steps ) + 1 ),
		'title'  => $frave_title,
		'text'   => frave_company_text( 'frave_expertise_step_' . $frave_index . '_text' ),
		'id'     => 'paso-' . sanitize_title( $frave_title ),
	);
}
if ( ! $frave_steps ) {
	return;
}
?>
<section class="section section--sand company-method" id="como-trabajamos" aria-labelledby="company-method-title">
	<span class="orbit orbit--soft company-method__orbit" aria-hidden="true"></span>
	<div class="container">
		<div class="section-head section-head--split" data-reveal="up">
			<p class="eyebrow"><?php esc_html_e( 'Método', 'frave' ); ?></p>
			<h2 id="company-method-title"><?php echo frave_emphasis( frave_company_text( 'frave_expertise_method_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
			<p class="lead"><?php echo esc_html( frave_company_text( 'frave_expertise_method_text' ) ); ?></p>
		</div>
		<div class="method" data-method style="--steps: <?php echo count( $frave_steps ); ?>">
			<?php foreach ( $frave_steps as $frave_row => $frave_step ) : ?>
				<h3 class="method__heading" style="--row: <?php echo (int) $frave_row + 1; ?>">
					<button class="method__tab" type="button" id="<?php echo esc_attr( $frave_step['id'] ); ?>-tab" aria-expanded="true" aria-controls="<?php echo esc_attr( $frave_step['id'] ); ?>" data-method-tab>
						<span class="method__number" aria-hidden="true"><?php echo esc_html( $frave_step['number'] ); ?></span>
						<span class="method__label"><?php echo esc_html( $frave_step['title'] ); ?></span>
						<?php echo frave_icon( 'chevron-down', 'method__chevron' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
					</button>
				</h3>
				<div class="method__panel" id="<?php echo esc_attr( $frave_step['id'] ); ?>" role="region" aria-labelledby="<?php echo esc_attr( $frave_step['id'] ); ?>-tab">
					<span class="method__big" aria-hidden="true"><?php echo esc_html( $frave_step['number'] ); ?></span>
					<p class="method__title" aria-hidden="true"><?php echo esc_html( $frave_step['title'] ); ?></p>
					<p class="method__text"><?php echo esc_html( $frave_step['text'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
