<?php
/**
 * Fragancias line: invitation to the guide of olfactory families, with a chip per family
 * that opens the guide at it. On the Fragancias page and at the end of the pyramid page,
 * once the guide is published.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_guide = 'fragancias' === ( $args['slug'] ?? '' ) ? frave_fragrance_guide_url() : '';
if ( '' === $frave_guide ) {
	return;
}
?>
<section class="section line-families has-section-bg" aria-labelledby="line-families-title">
	<?php
	get_template_part(
		'template-parts/components/section-bg',
		null,
		array(
			'image'  => 'textura-luz-sombra',
			'width'  => 2400,
			'height' => 1350,
			'small'  => 1200,
			'veil'   => 'white',
		)
	);
	?>
	<div class="container">
		<div class="section-head section-head--split" data-reveal="up">
			<p class="eyebrow"><?php esc_html_e( 'Guía de fragancias', 'frave' ); ?></p>
			<h2 id="line-families-title"><?php echo frave_emphasis( __( 'Conoce las *familias olfativas*', 'frave' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
			<p class="lead"><?php esc_html_e( 'Cómo huele cada familia, qué transmite y en qué productos funciona mejor: el punto de partida para elegir la fragancia de tu marca.', 'frave' ); ?></p>
			<?php frave_button( __( 'Ver la guía', 'frave' ), $frave_guide, 'btn btn--dark section-head__action' ); ?>
		</div>
		<ul class="line-families__list" data-reveal="up">
			<?php foreach ( frave_fragrance_families() as $frave_slug => $frave_family ) : ?>
				<li><a class="family-chip" href="<?php echo esc_url( $frave_guide . '#familia-' . $frave_slug ); ?>" style="--family-tone: <?php echo esc_attr( $frave_family['tone'] ); ?>"><?php echo esc_html( $frave_family['name'] ); ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
