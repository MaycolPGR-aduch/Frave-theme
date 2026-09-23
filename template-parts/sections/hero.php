<?php
/** Homepage hero with editable ACF fields and safe defaults. */
$eyebrow   = frave_field( 'frave_hero_eyebrow', __( 'FRAGANCIAS Y ENVASES', 'frave' ) );
$title     = frave_field( 'frave_hero_title', __( "El arte de crear\ntu propia esencia", 'frave' ) );
$copy      = frave_field( 'frave_hero_text', __( 'Encuentra fragancias, esencias e insumos seleccionados para crear con intención.', 'frave' ) );
$image     = frave_field( 'frave_hero_image' );
$button    = frave_field( 'frave_hero_cta_label', __( 'Explorar productos', 'frave' ) );
$shop_url  = class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
$button_url = frave_field( 'frave_hero_cta_url', $shop_url );
$image_id  = is_array( $image ) ? absint( $image['ID'] ?? 0 ) : ( is_numeric( $image ) ? absint( $image ) : 0 );
$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'frave-editorial' ) : ( is_array( $image ) ? ( $image['url'] ?? '' ) : '' );
$image_alt = is_array( $image ) ? ( $image['alt'] ?? '' ) : '';
?>
<section class="hero" aria-labelledby="hero-title">
	<div class="container hero__inner">
		<div class="hero__copy">
			<p class="eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
			<h1 id="hero-title"><?php echo nl2br( esc_html( $title ), false ); ?></h1>
			<p class="hero__description"><?php echo esc_html( $copy ); ?></p>
			<?php frave_button( (string) $button, (string) $button_url ); ?>
		</div>
		<div class="hero__visual<?php echo $image_url ? ' hero__visual--image' : ''; ?>">
			<?php if ( $image_id ) : ?>
				<?php
				echo wp_get_attachment_image(
					$image_id,
					'frave-editorial',
					false,
					array(
						'class'         => 'hero__image',
						'alt'           => $image_alt,
						'sizes'         => '(max-width: 48rem) calc(100vw - 2.5rem), (max-width: 64rem) 45vw, 52vw',
						'fetchpriority' => 'high',
						'decoding'      => 'async',
					)
				);
				?>
			<?php elseif ( $image_url ) : ?>
				<img class="hero__image" src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" width="1200" height="850" fetchpriority="high" decoding="async">
			<?php else : ?>
				<div class="hero__still-life" aria-hidden="true">
					<div class="hero__halo"></div>
					<div class="hero__bottle hero__bottle--large"><span>F</span></div>
					<div class="hero__bottle hero__bottle--small"><span>FRAVE</span></div>
					<div class="hero__note hero__note--one"></div>
					<div class="hero__note hero__note--two"></div>
				</div>
			<?php endif; ?>
			<p class="hero__caption"><span></span><?php esc_html_e( 'Encuentra tu próxima creación', 'frave' ); ?></p>
		</div>
	</div>
</section>
