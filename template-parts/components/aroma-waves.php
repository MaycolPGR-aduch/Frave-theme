<?php
/**
 * Aroma waves: three rings around the brand's drop, the hero decoration shared by the home
 * page (where they spread while the hero plays) and the interior heroes (still). Decorative.
 *
 * Arguments: class (placement, e.g. home-hero__waves), drop (false to leave the drop out).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_waves = wp_parse_args(
	$args ?? array(),
	array(
		'class' => '',
		'drop'  => true,
	)
);
?>
<div class="<?php echo esc_attr( trim( 'aroma-waves ' . $frave_waves['class'] ) ); ?>" aria-hidden="true">
	<span></span><span></span><span></span>
	<?php if ( $frave_waves['drop'] ) : ?>
		<svg class="aroma-waves__drop" viewBox="0 0 100 125" focusable="false"><path d="M50 2C40 22 4 52 4 78a46 46 0 0 0 92 0C96 52 60 22 50 2Z"/></svg>
	<?php endif; ?>
</div>
