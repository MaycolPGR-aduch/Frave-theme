<?php
/**
 * Three facts on warm surfaces (.highlights). A title that starts with a figure
 * ("+500 referencias") shows the figure large and counts it up when it appears.
 *
 * Arguments:
 * - items: list of [ title, text, icon ]; items without a title are skipped.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_items = array_filter( $args['items'] ?? array(), static fn( $item ) => '' !== trim( (string) ( $item['title'] ?? '' ) ) );
if ( ! $frave_items ) {
	return;
}
?>
<ul class="highlights" data-reveal-group>
	<?php foreach ( $frave_items as $frave_item ) : ?>
		<?php $frave_figure = frave_split_figure( $frave_item['title'] ); ?>
		<li class="highlight">
			<?php if ( $frave_figure ) : ?>
				<?php // The figure counts up, so screen readers get it from the heading instead. ?>
				<p class="stat__value highlight__figure" aria-hidden="true"><small><?php echo esc_html( $frave_figure['prefix'] ); ?></small><span data-count-to="<?php echo (int) $frave_figure['number']; ?>"><?php echo esc_html( number_format_i18n( $frave_figure['number'] ) ); ?></span><small><?php echo esc_html( $frave_figure['suffix'] ); ?></small></p>
				<h3><span class="screen-reader-text"><?php echo esc_html( $frave_figure['prefix'] . $frave_figure['number'] . $frave_figure['suffix'] ); ?> </span><?php echo esc_html( $frave_figure['label'] ); ?></h3>
			<?php else : ?>
				<span class="icon-tile"><?php echo frave_icon( $frave_item['icon'] ?? 'sparkle' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
				<h3><?php echo esc_html( $frave_item['title'] ); ?></h3>
			<?php endif; ?>
			<?php if ( '' !== trim( (string) ( $frave_item['text'] ?? '' ) ) ) : ?>
				<p><?php echo esc_html( $frave_item['text'] ); ?></p>
			<?php endif; ?>
		</li>
	<?php endforeach; ?>
</ul>
