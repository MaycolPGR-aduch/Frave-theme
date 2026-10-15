<?php
/**
 * Wide photograph of a working meeting with fragrance samples and packaging. It is an
 * illustrative scene, not Frave's actual team: the text beside it must not present it as
 * such.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_photo = frave_theme_image(
	'assets/media/empresa/reunion',
	1600,
	900,
	800,
	array(
		'alt'   => __( 'Reunión de trabajo en una oficina, con muestras de fragancias, tiras olfativas y frascos sobre la mesa.', 'frave' ),
		'sizes' => '(max-width: 80rem) 92vw, 76rem',
	)
);
if ( '' === $frave_photo ) {
	return;
}
?>
<div class="section company-photo">
	<div class="container">
		<figure class="company-photo__media media-frame" data-reveal="scale">
			<?php echo $frave_photo; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?>
		</figure>
	</div>
</div>
