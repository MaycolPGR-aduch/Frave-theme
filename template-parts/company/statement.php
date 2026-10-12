<?php
/**
 * A large sentence beside its explanation, with optional highlights below.
 *
 * Arguments:
 * - prefix:     field prefix (frave_about or frave_expertise); reads _intro_title and _intro_text.
 * - highlights: read _highlight_N_title and _highlight_N_text (N = 1–3) as well.
 * - icons:      icons of the highlights without a figure.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_prefix = $args['prefix'];
$frave_title  = frave_company_text( $frave_prefix . '_intro_title' );
$frave_text   = frave_company_text( $frave_prefix . '_intro_text' );
$frave_items  = array();
if ( ! empty( $args['highlights'] ) ) {
	foreach ( array( 1, 2, 3 ) as $frave_index ) {
		$frave_items[] = array(
			'title' => frave_company_text( $frave_prefix . '_highlight_' . $frave_index . '_title' ),
			'text'  => frave_company_text( $frave_prefix . '_highlight_' . $frave_index . '_text' ),
			'icon'  => $args['icons'][ $frave_index - 1 ] ?? 'sparkle',
		);
	}
}
?>
<section class="section company-statement" aria-labelledby="company-statement-title">
	<div class="container">
		<div class="statement">
			<h2 class="statement__title" id="company-statement-title" data-reveal="up"><?php echo frave_emphasis( $frave_title ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
			<div class="statement__text" data-reveal="up"><?php echo wp_kses_post( wpautop( esc_html( $frave_text ) ) ); ?></div>
		</div>
		<?php get_template_part( 'template-parts/components/highlights', null, array( 'items' => $frave_items ) ); ?>
	</div>
</section>
