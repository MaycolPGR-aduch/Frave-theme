<?php
/**
 * Line introduction: a large statement, the explanation and three highlights. A highlight
 * whose title starts with a figure ("+500 referencias") counts up when it appears.
 * On large screens it slides over the pinned hero.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_slug  = $args['slug'];
$frave_copy  = frave_line_copy( $frave_slug );
$frave_title = frave_line_text( 'intro_title', $frave_slug );
$frave_text  = frave_line_text( 'intro_text', $frave_slug );

$frave_highlights = array();
foreach ( array( 1, 2, 3 ) as $frave_index ) {
	$frave_highlights[] = array(
		'title' => frave_line_text( 'highlight_' . $frave_index . '_title', $frave_slug ),
		'text'  => frave_line_text( 'highlight_' . $frave_index . '_text', $frave_slug ),
		'icon'  => $frave_copy['icons'][ $frave_index - 1 ] ?? 'sparkle',
	);
}
$frave_has_highlights = '' !== implode( '', array_column( $frave_highlights, 'title' ) );

if ( '' === $frave_title && '' === $frave_text && ! $frave_has_highlights ) {
	echo '<div class="line-intro line-intro--empty"></div>';
	return;
}
?>
<section class="section line-intro" aria-labelledby="line-intro-title">
	<div class="container">
		<div class="statement">
			<?php if ( '' !== $frave_title ) : ?>
				<h2 class="statement__title" id="line-intro-title" data-reveal="up"><?php echo frave_emphasis( $frave_title ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
			<?php else : ?>
				<h2 class="screen-reader-text" id="line-intro-title"><?php esc_html_e( 'Presentación de la línea', 'frave' ); ?></h2>
			<?php endif; ?>
			<?php if ( '' !== $frave_text ) : ?>
				<div class="statement__text" data-reveal="up"><?php echo wp_kses_post( wpautop( esc_html( $frave_text ) ) ); ?></div>
			<?php endif; ?>
		</div>
		<?php get_template_part( 'template-parts/components/highlights', null, array( 'items' => $frave_highlights ) ); ?>
	</div>
</section>
