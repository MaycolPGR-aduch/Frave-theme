<?php
/**
 * "Nuestras soluciones": the three product lines as bottle-shaped cards. On phones they
 * become a swipeable carousel; the line image is the product category's image.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_lines = frave_home_lines();
?>
<section class="section home-lines" aria-labelledby="home-lines-title">
	<div class="container">
		<div class="section-head section-head--split" data-reveal="up">
			<p class="eyebrow"><?php echo esc_html( frave_home_text( 'frave_home_lines_eyebrow' ) ); ?></p>
			<h2 id="home-lines-title"><?php echo frave_emphasis( frave_home_text( 'frave_home_lines_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
			<p class="lead"><?php echo esc_html( frave_home_text( 'frave_home_lines_text' ) ); ?></p>
			<?php if ( class_exists( 'WooCommerce' ) ) : ?>
				<a class="link-arrow section-head__action" href="<?php echo esc_url( frave_catalog_shop_url() ); ?>"><?php esc_html_e( 'Ver todo el catálogo', 'frave' ); ?><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></a>
			<?php endif; ?>
		</div>

		<div class="carousel home-lines__carousel" data-carousel>
			<ul class="carousel__track" data-carousel-track tabindex="0" aria-label="<?php esc_attr_e( 'Líneas de productos de Frave', 'frave' ); ?>" data-reveal-group>
				<?php foreach ( $frave_lines as $frave_index => $frave_line ) : ?>
					<li aria-roledescription="<?php esc_attr_e( 'diapositiva', 'frave' ); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: 1: slide number, 2: total. */ __( '%1$d de %2$d', 'frave' ), $frave_index + 1, count( $frave_lines ) ) ); ?>">
						<article class="card card--interactive line-card line-card--<?php echo esc_attr( $frave_line['slug'] ); ?>">
							<div class="line-card__media media-frame shape-bottle">
								<?php if ( $frave_line['image_id'] ) : ?>
									<?php
									echo wp_get_attachment_image(
										$frave_line['image_id'],
										'frave-card',
										false,
										array(
											'alt'     => '',
											'sizes'   => '(max-width: 48rem) 80vw, 30vw',
											'loading' => 'lazy',
										)
									);
									?>
								<?php else : ?>
									<span class="line-card__art" aria-hidden="true"><?php echo frave_icon( $frave_line['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
								<?php endif; ?>
							</div>
							<div class="line-card__body">
								<h3><a class="card__link" href="<?php echo esc_url( $frave_line['url'] ); ?>"><?php echo esc_html( $frave_line['name'] ); ?></a></h3>
								<p><?php echo esc_html( $frave_line['text'] ); ?></p>
								<span class="link-arrow" aria-hidden="true"><?php esc_html_e( 'Ver productos', 'frave' ); ?><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
							</div>
						</article>
					</li>
				<?php endforeach; ?>
			</ul>
			<div class="carousel__controls">
				<button class="carousel__button" type="button" data-carousel-prev aria-label="<?php esc_attr_e( 'Línea anterior', 'frave' ); ?>"><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></button>
				<button class="carousel__button" type="button" data-carousel-next aria-label="<?php esc_attr_e( 'Línea siguiente', 'frave' ); ?>"><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></button>
			</div>
		</div>
	</div>
</section>
