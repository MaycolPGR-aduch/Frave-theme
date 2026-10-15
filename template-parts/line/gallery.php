<?php
/**
 * Inspiration gallery of the line: a swipeable carousel of square images with their
 * names (frave_line_gallery()). Only lines with images ship one; hidden otherwise.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_slug    = $args['slug'];
$frave_gallery = frave_line_gallery( $frave_slug );
if ( ! $frave_gallery ) {
	return;
}
$frave_total = count( $frave_gallery );
$frave_index = 0;
?>
<section class="section line-gallery" aria-labelledby="line-gallery-title">
	<div class="container">
		<div class="section-head section-head--split" data-reveal="up">
			<p class="eyebrow"><?php esc_html_e( 'Inspiración', 'frave' ); ?></p>
			<h2 id="line-gallery-title"><?php echo frave_emphasis( __( 'Del fruto a tu *producto*', 'frave' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
			<p class="lead"><?php esc_html_e( 'Frutas, especias, café, panetón: perfiles que dan identidad a bebidas, postres, snacks y productos de cuidado bucal.', 'frave' ); ?></p>
		</div>

		<div class="carousel line-gallery__carousel" data-carousel>
			<ul class="carousel__track" data-carousel-track tabindex="0" aria-label="<?php esc_attr_e( 'Perfiles de sabor', 'frave' ); ?>" data-reveal-group>
				<?php foreach ( $frave_gallery as $frave_image => $frave_caption ) : ?>
					<li aria-roledescription="<?php esc_attr_e( 'diapositiva', 'frave' ); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: 1: slide number, 2: total. */ __( '%1$d de %2$d', 'frave' ), ++$frave_index, $frave_total ) ); ?>">
						<figure class="gallery-tile">
							<div class="gallery-tile__media media-frame">
								<?php echo frave_theme_image( "assets/media/{$frave_slug}/{$frave_image}", 800, 800, 400, array( 'sizes' => '(max-width: 48rem) 70vw, 18rem' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?>
							</div>
							<figcaption class="gallery-tile__caption"><?php echo esc_html( $frave_caption ); ?></figcaption>
						</figure>
					</li>
				<?php endforeach; ?>
			</ul>
			<div class="carousel__controls">
				<button class="carousel__button" type="button" data-carousel-prev aria-label="<?php esc_attr_e( 'Imagen anterior', 'frave' ); ?>"><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></button>
				<button class="carousel__button" type="button" data-carousel-next aria-label="<?php esc_attr_e( 'Imagen siguiente', 'frave' ); ?>"><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></button>
			</div>
		</div>
	</div>
</section>
