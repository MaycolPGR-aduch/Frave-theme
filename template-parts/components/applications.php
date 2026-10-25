<?php
/**
 * Applications on a sand band: two rows of large words that slide with the scroll
 * (decorative, ticker.js) and the same applications as a readable list.
 *
 * Arguments:
 * - items: list of applications; nothing is printed without them.
 * - title: heading; *words* are highlighted.
 * - text:  optional lead.
 * - image: optional background photograph in assets/media/fondos (see components/section-bg.php).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_items = array_values( array_filter( $args['items'] ?? array() ) );
if ( ! $frave_items ) {
	return;
}
$frave_title = (string) ( $args['title'] ?? '' );
$frave_text  = (string) ( $args['text'] ?? '' );
$frave_image = (string) ( $args['image'] ?? '' );

// Each row repeats the words until it is wider than any screen.
$frave_row = array_merge( $frave_items, $frave_items );
if ( count( $frave_row ) < 16 ) {
	$frave_row = array_merge( $frave_row, $frave_items );
}
$frave_half = (int) ceil( count( $frave_items ) / 2 );
$frave_rows = array(
	$frave_row,
	array_merge( array_slice( $frave_row, $frave_half ), array_slice( $frave_row, 0, $frave_half ) ),
);
?>
<section class="section section--sand line-applications<?php echo '' !== $frave_image ? ' has-section-bg' : ''; ?>" aria-labelledby="line-applications-title">
	<?php
	if ( '' !== $frave_image ) {
		get_template_part( 'template-parts/components/section-bg', null, array( 'image' => $frave_image ) );
	}
	?>
	<div class="ticker" aria-hidden="true" data-ticker>
		<?php foreach ( $frave_rows as $frave_row_index => $frave_words ) : ?>
			<div class="ticker__row<?php echo 1 === $frave_row_index ? ' ticker__row--reverse' : ''; ?>">
				<?php foreach ( $frave_words as $frave_word ) : ?>
					<span class="ticker__word"><?php echo esc_html( $frave_word ); ?></span><span class="ticker__sep"></span>
				<?php endforeach; ?>
			</div>
		<?php endforeach; ?>
	</div>

	<div class="container line-applications__grid">
		<div class="line-applications__head" data-reveal="up">
			<p class="eyebrow"><?php esc_html_e( 'Aplicaciones', 'frave' ); ?></p>
			<h2 id="line-applications-title"><?php echo frave_emphasis( '' !== $frave_title ? $frave_title : __( 'Aplicaciones', 'frave' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
			<?php if ( '' !== $frave_text ) : ?>
				<p class="lead"><?php echo esc_html( $frave_text ); ?></p>
			<?php endif; ?>
		</div>
		<ul class="line-applications__list" data-reveal-group>
			<?php foreach ( $frave_items as $frave_item ) : ?>
				<li><?php echo esc_html( $frave_item ); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
