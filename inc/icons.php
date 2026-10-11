<?php
/**
 * Line icons and the Frave isotype as inline SVG.
 *
 * Icons share the stroke style of the isotype (round caps, 1.6 on a 24px grid) and take
 * the current text colour. They are decorative: the link or button around them carries
 * the accessible name.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** SVG paths of each icon on a 24×24 grid. */
function frave_icon_paths(): array {
	return array(
		'arrow-right'    => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'arrow-up-right' => '<path d="M7 17 17 7M8 7h9v9"/>',
		'chevron-down'   => '<path d="m6 9 6 6 6-6"/>',
		'search'         => '<circle cx="10.8" cy="10.8" r="6.4"/><path d="m16 16 4.2 4.2"/>',
		'close'          => '<path d="m6 6 12 12M18 6 6 18"/>',
		'check'          => '<path d="m5 12.5 4.5 4.5L19 7.5"/>',
		'flavor'         => '<path d="M12 7.6c-1.2-1-2.6-1.4-4-1.1-2.6.6-3.8 3.2-3.2 6.4.6 3.4 3 7.1 5.2 7.1.8 0 1.3-.4 2-.4s1.2.4 2 .4c2.2 0 4.6-3.7 5.2-7.1.6-3.2-.6-5.8-3.2-6.4-1.4-.3-2.8.1-4 1.1Z"/><path d="M12 7.6c0-2 .8-3.4 2.4-4.1"/>',
		'fragrance'      => '<path d="M10 3.5h4v3h-4z"/><rect x="5.5" y="6.5" width="13" height="14" rx="2.2"/><path d="M12 10.2c-1.6 2-2.4 3.3-2.4 4.4a2.4 2.4 0 0 0 4.8 0c0-1.1-.8-2.4-2.4-4.4Z"/>',
		'packaging'      => '<path d="M9 2.8h6M10 2.8v3.4h4V2.8"/><path d="M8.5 6.2h7l1 2.3v11a1.7 1.7 0 0 1-1.7 1.7H9.2a1.7 1.7 0 0 1-1.7-1.7v-11z"/><path d="M7.5 12h9"/>',
		'chat'           => '<path d="M5 18.5V7.8A2.8 2.8 0 0 1 7.8 5h8.4A2.8 2.8 0 0 1 19 7.8v5.4a2.8 2.8 0 0 1-2.8 2.8H9z"/><path d="M9 9.5h6M9 12.5h4"/>',
		'mail'           => '<rect x="3.5" y="5.5" width="17" height="13" rx="2"/><path d="m4.5 7 7.5 6 7.5-6"/>',
		'phone'          => '<path d="M8.6 4.5H6.3a1.8 1.8 0 0 0-1.8 1.9c.3 7.2 5.9 12.8 13.1 13.1a1.8 1.8 0 0 0 1.9-1.8v-2.3l-3.4-1.4-1.7 1.7a10.4 10.4 0 0 1-4.9-4.9l1.7-1.7z"/>',
		'pin'            => '<path d="M12 21s6.5-5.6 6.5-11a6.5 6.5 0 0 0-13 0c0 5.4 6.5 11 6.5 11Z"/><circle cx="12" cy="10" r="2.3"/>',
		'clock'          => '<circle cx="12" cy="12" r="8.5"/><path d="M12 7.5V12l3 2"/>',
		'lab'            => '<path d="M9.5 3.5h5M10.5 3.5v5.2L5.4 17.4a2 2 0 0 0 1.7 3.1h9.8a2 2 0 0 0 1.7-3.1l-5.1-8.7V3.5"/><path d="M7.6 14.5h8.8"/>',
		'quality'        => '<circle cx="12" cy="9.5" r="5.5"/><path d="m9.6 9.6 1.7 1.7 3.2-3.3"/><path d="m8.6 14-1.6 6.5 5-2.5 5 2.5-1.6-6.5"/>',
		'truck'          => '<path d="M3.5 6.5h10v9h-10z"/><path d="M13.5 9.5h3.8l3.2 3.4v2.6h-7"/><circle cx="7.5" cy="17.5" r="1.8"/><circle cx="17" cy="17.5" r="1.8"/>',
		'users'          => '<circle cx="9" cy="8.5" r="3"/><path d="M3.8 19c.6-3.3 2.6-5 5.2-5s4.6 1.7 5.2 5"/><path d="M15.5 6a2.8 2.8 0 0 1 0 5.4M17 14.2c1.8.5 3 2 3.4 4.8"/>',
		'leaf'           => '<path d="M5 19c0-8 5-13.5 14-14 0 9-5.5 14-14 14Z"/><path d="M5 19 13 11"/>',
		'sparkle'        => '<path d="M12 3.5c.6 4.2 2.3 5.9 6.5 6.5-4.2.6-5.9 2.3-6.5 6.5-.6-4.2-2.3-5.9-6.5-6.5 4.2-.6 5.9-2.3 6.5-6.5Z"/>',
		'pause'          => '<path d="M9 6v12M15 6v12"/>',
		'play'           => '<path d="M8 5.5v13l10.5-6.5z"/>',
		'replay'         => '<path d="M4.5 12a7.5 7.5 0 1 0 2.2-5.3L4.5 9"/><path d="M4.5 4.5V9H9"/>',
		'book'           => '<path d="M4 5.5C4 4.7 4.7 4 5.5 4H11v16H5.5c-.8 0-1.5-.7-1.5-1.5v-13Z"/><path d="M20 5.5c0-.8-.7-1.5-1.5-1.5H13v16h5.5c.8 0 1.5-.7 1.5-1.5v-13Z"/><path d="M6.5 8h2.5M6.5 11h2.5M15 8h2.5M15 11h2.5"/>',
	);
}

/** Inline SVG icon, or '' for an unknown name. */
function frave_icon( string $name, string $class_name = '' ): string {
	$paths = frave_icon_paths();
	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}
	return sprintf(
		'<svg class="%1$s" viewBox="0 0 24 24" aria-hidden="true" focusable="false">%2$s</svg>',
		esc_attr( trim( 'icon icon--' . $name . ' ' . $class_name ) ),
		$paths[ $name ]
	);
}

/** Icon of each product line, by product category slug. */
function frave_product_line_icon( string $slug ): string {
	$icons = array(
		'sabores'    => 'flavor',
		'fragancias' => 'fragrance',
		'envases'    => 'packaging',
	);
	return $icons[ $slug ] ?? '';
}

/**
 * The Frave isotype (bottle with a drop) as a line drawing, for watermarks and seals.
 * Decorative unless a label is given.
 */
function frave_isotype( string $class_name = '', string $label = '' ): string {
	$a11y = '' === $label ? 'aria-hidden="true" focusable="false"' : 'role="img" aria-label="' . esc_attr( $label ) . '"';
	return '<svg class="' . esc_attr( trim( 'isotype ' . $class_name ) ) . '" viewBox="0 0 64 80" ' . $a11y . '>'
		. '<rect x="25" y="2" width="14" height="9" rx="2"/><circle cx="32" cy="6.5" r="1.6"/>'
		. '<path d="M28 11v7M36 11v7"/>'
		. '<rect x="6" y="18" width="52" height="58" rx="6"/>'
		. '<path d="M14 27v39h10M50 27v39H40"/>'
		. '<path d="M32 31c-6.2 7.6-9.4 13-9.4 17.6a9.4 9.4 0 0 0 18.8 0c0-4.6-3.2-10-9.4-17.6Z"/>'
		. '<path d="M27.6 50.4a5 5 0 0 0 4 4.4"/>'
		. '</svg>';
}
