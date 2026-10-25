<?php
/**
 * Background photograph of a section, behind its content and under a colour veil that keeps
 * the text readable (.section-bg in system/surfaces.css). The section takes the class
 * has-section-bg. Decorative: hidden from assistive technology, loaded only near the
 * section, with a lighter file for phones.
 *
 * Arguments:
 * - image: base path of a WebP in assets/media/fondos (without .webp); "{image}-{small}.webp"
 *          is its phone version.
 * - width, height, small: size of the full file and width of the light one.
 * - veil: modifier of the veil: 'sand' (sand towards the text on the left, the default),
 *         'surface' or 'white' (a light texture veil, even across the section).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_bg       = wp_parse_args(
	$args ?? array(),
	array(
		'image'  => '',
		'width'  => 1920,
		'height' => 1080,
		'small'  => 960,
		'veil'   => 'sand',
	)
);
$frave_bg_image = '' !== $frave_bg['image']
	? frave_theme_image(
		'assets/media/fondos/' . $frave_bg['image'],
		(int) $frave_bg['width'],
		(int) $frave_bg['height'],
		(int) $frave_bg['small'],
		array( 'sizes' => '100vw' )
	)
	: '';
if ( '' === $frave_bg_image ) {
	return;
}
?>
<div class="section-bg section-bg--<?php echo esc_attr( $frave_bg['veil'] ); ?>" aria-hidden="true"><?php echo $frave_bg_image; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></div>
