<?php
/**
 * Card of a product subcategory (.line-group): picture, number, name, description, the
 * industries that use it, its own subcategories as a list of links, "Cotizar" and "Ver
 * detalle". The picture, the name, each subcategory and "Ver detalle" lead to the
 * category's detail page (inc/category-detail.php), with or without products. Pointing at
 * (or focusing) a subcategory swaps the picture for that subcategory's image
 * (term-cards.js). Used by line pages, solution pages and category pages.
 *
 * Arguments:
 * - term:      the product category.
 * - number:    position shown above the name.
 * - show_line: show the line it belongs to (solution pages mix lines).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_term     = $args['term'];
$frave_children = get_terms(
	array(
		'taxonomy'   => 'product_cat',
		'parent'     => $frave_term->term_id,
		'hide_empty' => false,
	)
);
$frave_children = is_wp_error( $frave_children ) ? array() : $frave_children;
$frave_detail   = frave_term_detail_url( $frave_term );
$frave_quote    = frave_line_quote_link( $frave_term );
$frave_text     = wp_strip_all_tags( $frave_term->description );
$frave_sizes    = '(max-width: 56rem) 90vw, 45vw';
$frave_image    = frave_term_image( $frave_term, $frave_sizes );
$frave_line     = ! empty( $args['show_line'] ) && $frave_term->parent ? frave_term_line_name( $frave_term ) : '';
// Solution pages already say the industry; elsewhere the card names the industries that use it.
$frave_uses = '' === $frave_line ? frave_term_solutions_html( $frave_term, 'line-group__uses' ) : '';

// One hidden layer per subcategory with an image, numbered from 1 (0 is the card's own).
$frave_layers = array();
if ( '' !== $frave_image ) {
	foreach ( $frave_children as $frave_child ) {
		$frave_layer = frave_term_image_layer( $frave_child, $frave_sizes, count( $frave_layers ) + 1 );
		if ( '' !== $frave_layer ) {
			$frave_layers[ $frave_child->term_id ] = array( count( $frave_layers ) + 1, $frave_layer );
		}
	}
}
?>
<li class="card line-group<?php echo '' !== $frave_image ? ' line-group--image' : ''; ?>"<?php echo $frave_layers ? ' data-term-card' : ''; ?>>
	<?php if ( '' !== $frave_image ) : ?>
		<?php // The name below is the link for keyboards and screen readers; the picture is a larger target for the pointer. ?>
		<a class="line-group__media media-frame" href="<?php echo esc_url( $frave_detail ); ?>" tabindex="-1" aria-hidden="true">
			<?php echo $frave_image; // phpcs:ignore WordPress.Security.EscapeOutput -- built and escaped by frave_term_image(). ?>
			<?php foreach ( $frave_layers as list( , $frave_layer ) ) : ?>
				<?php echo $frave_layer; // phpcs:ignore WordPress.Security.EscapeOutput -- built and escaped by frave_term_image(). ?>
			<?php endforeach; ?>
			<?php if ( $frave_layers ) : ?>
				<span class="line-group__caption" data-swap-caption hidden></span>
			<?php endif; ?>
		</a>
	<?php endif; ?>
	<div class="line-group__body">
		<span class="line-group__number" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', (int) ( $args['number'] ?? 1 ) ) ); ?><?php echo '' !== $frave_line ? ' · ' . esc_html( mb_strtoupper( $frave_line ) ) : ''; ?></span>
		<h3 class="line-group__title">
			<?php if ( '' !== $frave_detail ) : ?>
				<a href="<?php echo esc_url( $frave_detail ); ?>"><?php echo esc_html( $frave_term->name ); ?></a>
			<?php else : ?>
				<?php echo esc_html( $frave_term->name ); ?>
			<?php endif; ?>
			<?php echo '' !== $frave_line ? '<span class="screen-reader-text"> (' . esc_html( $frave_line ) . ')</span>' : ''; ?>
		</h3>
		<?php if ( '' !== $frave_text ) : ?>
			<p class="line-group__text"><?php echo esc_html( $frave_text ); ?></p>
		<?php endif; ?>
		<?php if ( '' !== $frave_uses ) : ?>
			<p class="line-group__uses-row"><?php echo wp_kses_post( $frave_uses ); ?></p>
		<?php endif; ?>
		<?php if ( $frave_children ) : ?>
			<div class="line-group__types-block">
				<p class="line-group__types-label" id="<?php echo esc_attr( 'types-' . $frave_term->term_id ); ?>"><?php esc_html_e( 'Elige un tipo', 'frave' ); ?></p>
				<ul class="line-group__types" aria-labelledby="<?php echo esc_attr( 'types-' . $frave_term->term_id ); ?>">
					<?php foreach ( $frave_children as $frave_child ) : ?>
						<?php
						$frave_child_url   = frave_term_detail_url( $frave_child );
						$frave_child_thumb = frave_term_thumb( $frave_child );
						$frave_swap        = isset( $frave_layers[ $frave_child->term_id ] ) ? (string) $frave_layers[ $frave_child->term_id ][0] : '';
						?>
						<li>
							<a class="type-link" href="<?php echo esc_url( $frave_child_url ); ?>"<?php echo '' !== $frave_swap ? ' data-swap="' . esc_attr( $frave_swap ) . '" data-swap-name="' . esc_attr( $frave_child->name ) . '"' : ''; ?>>
								<?php if ( '' !== $frave_child_thumb ) : ?>
									<span class="type-link__thumb"><?php echo $frave_child_thumb; // phpcs:ignore WordPress.Security.EscapeOutput -- built and escaped by frave_term_thumb(). ?></span>
								<?php endif; ?>
								<span class="type-link__name"><?php echo esc_html( $frave_child->name ); ?></span>
								<span class="type-link__go" aria-hidden="true"><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>
		<div class="line-group__actions">
			<?php if ( $frave_quote ) : ?>
				<a class="link-arrow" href="<?php echo esc_url( $frave_quote['url'] ); ?>"<?php echo $frave_quote['external'] ? ' target="_blank" rel="noopener"' : ''; ?>>
					<?php esc_html_e( 'Cotizar', 'frave' ); ?>
					<span class="screen-reader-text"><?php echo esc_html( $frave_term->name ); ?></span>
					<?php if ( $frave_quote['external'] ) : ?>
						<span class="screen-reader-text"><?php esc_html_e( '(se abre en una nueva pestaña)', 'frave' ); ?></span>
					<?php endif; ?>
					<?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
				</a>
			<?php endif; ?>
			<?php if ( '' !== $frave_detail ) : ?>
				<a class="link-arrow link-arrow--muted" href="<?php echo esc_url( $frave_detail ); ?>">
					<?php esc_html_e( 'Ver detalle', 'frave' ); ?>
					<span class="screen-reader-text"><?php echo esc_html( $frave_term->name ); ?></span>
					<?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</li>
