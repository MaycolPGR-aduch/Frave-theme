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
	$frave_highlight_title = frave_line_text( 'highlight_' . $frave_index . '_title', $frave_slug );
	if ( '' === $frave_highlight_title ) {
		continue;
	}
	$frave_highlights[] = array(
		'title'  => $frave_highlight_title,
		'text'   => frave_line_text( 'highlight_' . $frave_index . '_text', $frave_slug ),
		'icon'   => $frave_copy['icons'][ $frave_index - 1 ] ?? 'sparkle',
		'figure' => frave_split_figure( $frave_highlight_title ),
	);
}

if ( '' === $frave_title && '' === $frave_text && ! $frave_highlights ) {
	echo '<div class="line-intro line-intro--empty"></div>';
	return;
}
?>
<section class="section line-intro" aria-labelledby="line-intro-title">
	<div class="container">
		<div class="line-intro__grid">
			<?php if ( '' !== $frave_title ) : ?>
				<h2 class="line-intro__title" id="line-intro-title" data-reveal="up"><?php echo frave_emphasis( $frave_title ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
			<?php else : ?>
				<h2 class="screen-reader-text" id="line-intro-title"><?php esc_html_e( 'Presentación de la línea', 'frave' ); ?></h2>
			<?php endif; ?>
			<?php if ( '' !== $frave_text ) : ?>
				<div class="line-intro__text" data-reveal="up"><?php echo wp_kses_post( wpautop( esc_html( $frave_text ) ) ); ?></div>
			<?php endif; ?>
		</div>

		<?php if ( $frave_highlights ) : ?>
			<ul class="line-highlights" data-reveal-group>
				<?php foreach ( $frave_highlights as $frave_highlight ) : ?>
					<li class="line-highlight">
						<?php if ( $frave_highlight['figure'] ) : ?>
							<?php $frave_figure = $frave_highlight['figure']; ?>
							<?php // The figure counts up, so screen readers get it from the heading instead. ?>
							<p class="stat__value line-highlight__figure" aria-hidden="true"><small><?php echo esc_html( $frave_figure['prefix'] ); ?></small><span data-count-to="<?php echo (int) $frave_figure['number']; ?>"><?php echo esc_html( number_format_i18n( $frave_figure['number'] ) ); ?></span><small><?php echo esc_html( $frave_figure['suffix'] ); ?></small></p>
							<h3><span class="screen-reader-text"><?php echo esc_html( $frave_figure['prefix'] . $frave_figure['number'] . $frave_figure['suffix'] ); ?> </span><?php echo esc_html( $frave_figure['label'] ); ?></h3>
						<?php else : ?>
							<span class="icon-tile"><?php echo frave_icon( $frave_highlight['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
							<h3><?php echo esc_html( $frave_highlight['title'] ); ?></h3>
						<?php endif; ?>
						<?php if ( '' !== $frave_highlight['text'] ) : ?>
							<p><?php echo esc_html( $frave_highlight['text'] ); ?></p>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</section>
