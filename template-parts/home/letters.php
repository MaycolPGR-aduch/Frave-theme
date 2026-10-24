<?php
/**
 * Home: the FRAVE wordmark, large, with a flower photograph inside each letter (tulips,
 * mandevilla, sunflower, hydrangea and cosmos), alone, right after the hero, which it
 * slides over; «Nuestras líneas» follows.
 *
 * The letters are a mask (assets/media/letras/frave-mascara.webp, cut from the logo); behind
 * them, one tile per letter holds its photo. A and V share a diagonal gap, so their tiles are
 * cut along it. While the section crosses the screen the photos drift up or down inside the
 * letters (assets/src/js/modules/letters.js), and pointing at a letter brings its photo
 * closer; both stay still with reduced motion. The photos load only near the section.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Tiles as percentages of the word (measured on the logo); clip: the diagonal of A and V.
$frave_letters = array(
	'f-tulipanes'  => array(
		'left'  => 0,
		'width' => 16.746,
		'clip'  => '',
	),
	'r-mandevilla' => array(
		'left'  => 16.746,
		'width' => 21.04,
		'clip'  => '',
	),
	'a-girasol'    => array(
		'left'  => 37.786,
		'width' => 27.195,
		'clip'  => 'polygon(0 0, 66.316% 0, 100% 100%, 0 100%)',
	),
	'v-hortensia'  => array(
		'left'  => 55.821,
		'width' => 27.887,
		'clip'  => 'polygon(0 0, 100% 0, 100% 100%, 32.849% 100%)',
	),
	'e-cosmos'     => array(
		'left'  => 83.707,
		'width' => 16.293,
		'clip'  => '',
	),
);
?>
<section class="home-letters">
	<div class="container">
		<div class="home-letters__word" role="img" aria-label="Frave" data-letters data-reveal="up" style="<?php echo esc_attr( sprintf( "--letters-mask: url('%s')", frave_asset_url( 'assets/media/letras/frave-mascara.webp' ) ) ); ?>">
			<?php
			$frave_direction = 1;
			foreach ( $frave_letters as $frave_photo => $frave_tile ) :
				$frave_style = sprintf( 'left: %s%%; width: %s%%; --tile-dir: %d;', $frave_tile['left'], $frave_tile['width'], $frave_direction );
				if ( '' !== $frave_tile['clip'] ) {
					$frave_style .= ' --tile-clip: ' . $frave_tile['clip'] . ';';
				}
				// Neighbouring letters drift in opposite directions.
				$frave_direction = -$frave_direction;
				?>
				<span class="home-letters__tile" style="<?php echo esc_attr( $frave_style ); ?>">
					<?php
					echo frave_theme_image( // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper.
						'assets/media/letras/' . $frave_photo,
						720,
						720,
						320,
						array( 'sizes' => '(max-width: 48rem) 30vw, 22rem' )
					);
					?>
				</span>
			<?php endforeach; ?>
		</div>
	</div>
</section>
