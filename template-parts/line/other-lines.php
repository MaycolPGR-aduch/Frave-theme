<?php
/** The other product lines, so the visit continues instead of ending on the footer. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_others = frave_other_lines( $args['slug'] );
if ( ! $frave_others ) {
	return;
}
?>
<section class="section line-others" aria-labelledby="line-others-title">
	<div class="container">
		<div class="section-head" data-reveal="up">
			<p class="eyebrow"><?php esc_html_e( 'Sigue explorando', 'frave' ); ?></p>
			<h2 id="line-others-title"><?php echo frave_emphasis( __( 'Conoce nuestras *otras líneas*', 'frave' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
		</div>
		<ul class="line-others__grid" data-reveal-group>
			<?php foreach ( $frave_others as $frave_other ) : ?>
				<li class="card card--interactive line-other line-other--<?php echo esc_attr( $frave_other['slug'] ); ?>">
					<div class="line-other__media media-frame shape-arch">
						<?php if ( '' !== $frave_other['image'] ) : ?>
							<img src="<?php echo esc_url( $frave_other['image'] ); ?>" width="600" height="750" alt="" loading="lazy" decoding="async">
						<?php else : ?>
							<span class="line-card__art" aria-hidden="true"><?php echo frave_icon( frave_product_line_icon( $frave_other['slug'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
						<?php endif; ?>
					</div>
					<div class="line-other__body">
						<h3><a class="card__link" href="<?php echo esc_url( $frave_other['url'] ); ?>"><?php echo esc_html( $frave_other['name'] ); ?></a></h3>
						<p><?php echo esc_html( $frave_other['text'] ); ?></p>
						<span class="link-arrow" aria-hidden="true"><?php esc_html_e( 'Conocer la línea', 'frave' ); ?><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
