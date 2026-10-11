<?php
/**
 * "Dónde estamos": contact channels and a map that loads only when the visitor asks for
 * it, so Google Maps is not contacted on every visit. Needs an address in
 * Apariencia → Personalizar → Contacto.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_channels = frave_contact_channels();
if ( empty( $frave_channels['address'] ) ) {
	return;
}
$frave_address = $frave_channels['address']['value'];
$frave_query   = rawurlencode( preg_replace( '/\s+/', ' ', $frave_address ) );
$frave_icons   = array(
	'address'  => 'pin',
	'hours'    => 'clock',
	'phone'    => 'phone',
	'email'    => 'mail',
	'whatsapp' => 'whatsapp',
);
?>
<section class="section home-location" aria-labelledby="home-location-title">
	<div class="container home-location__grid">
		<div class="home-location__copy" data-reveal="up">
			<p class="eyebrow"><?php echo esc_html( frave_home_text( 'frave_home_location_eyebrow' ) ); ?></p>
			<h2 id="home-location-title"><?php echo frave_emphasis( frave_home_text( 'frave_home_location_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
			<p class="lead"><?php echo esc_html( frave_home_text( 'frave_home_location_text' ) ); ?></p>
			<ul class="home-location__channels">
				<?php foreach ( $frave_icons as $frave_key => $frave_icon ) : ?>
					<?php
					if ( empty( $frave_channels[ $frave_key ] ) ) {
						continue;
					}
					$frave_channel = $frave_channels[ $frave_key ];
					?>
					<li>
						<span class="icon-tile"><?php echo 'whatsapp' === $frave_icon ? frave_whatsapp_icon() : frave_icon( $frave_icon ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
						<span>
							<strong><?php echo esc_html( $frave_channel['label'] ); ?></strong>
							<?php if ( $frave_channel['url'] ) : ?>
								<a href="<?php echo esc_url( $frave_channel['url'] ); ?>"<?php echo 'whatsapp' === $frave_key ? ' target="_blank" rel="noopener"' : ''; ?>><?php echo esc_html( $frave_channel['value'] ); ?></a>
							<?php else : ?>
								<span><?php echo nl2br( esc_html( $frave_channel['value'] ), false ); ?></span>
							<?php endif; ?>
						</span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<div class="home-map" data-map data-map-src="<?php echo esc_url( 'https://www.google.com/maps?q=' . $frave_query . '&output=embed' ); ?>" data-map-title="<?php echo esc_attr( sprintf( /* translators: %s: address. */ __( 'Mapa: %s', 'frave' ), $frave_address ) ); ?>">
			<div class="home-map__placeholder">
				<span class="icon-tile icon-tile--lg"><?php echo frave_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
				<p><?php echo nl2br( esc_html( $frave_address ), false ); ?></p>
				<button class="btn btn--dark btn--sm" type="button" data-map-load hidden><span><?php esc_html_e( 'Ver el mapa', 'frave' ); ?></span><span class="btn__icon"><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span></button>
				<a class="link-arrow" href="<?php echo esc_url( 'https://www.google.com/maps/search/?api=1&query=' . $frave_query ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Abrir en Google Maps', 'frave' ); ?><span class="screen-reader-text"><?php esc_html_e( '(se abre en una nueva pestaña)', 'frave' ); ?></span><?php echo frave_icon( 'arrow-up-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></a>
				<small><?php esc_html_e( 'El mapa se carga desde Google al pulsar el botón.', 'frave' ); ?></small>
			</div>
		</div>
	</div>
</section>
