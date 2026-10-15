<?php
/**
 * Card of a product subcategory (.line-group): number, name, description, the industries
 * that use it, its own subcategories as chips, "Cotizar" and "Ver detalle". The name, the
 * chips and "Ver detalle" lead to the category's detail page (inc/category-detail.php),
 * with or without products. Used by line pages, solution pages and category pages.
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
$frave_image    = frave_term_image( $frave_term, '(max-width: 56rem) 90vw, 45vw' );
$frave_line     = ! empty( $args['show_line'] ) && $frave_term->parent ? frave_term_line_name( $frave_term ) : '';
// Solution pages already say the industry; elsewhere the card names the industries that use it.
$frave_uses = '' === $frave_line ? frave_term_solutions_html( $frave_term, 'line-group__uses' ) : '';
?>
<li class="card line-group<?php echo '' !== $frave_image ? ' line-group--image' : ''; ?>">
	<?php if ( '' !== $frave_image ) : ?>
		<div class="line-group__media media-frame">
			<?php echo $frave_image; // phpcs:ignore WordPress.Security.EscapeOutput -- built and escaped by frave_term_image(). ?>
		</div>
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
			<ul class="line-group__types" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: subcategory name. */ __( 'Tipos de %s', 'frave' ), $frave_term->name ) ); ?>">
				<?php foreach ( $frave_children as $frave_child ) : ?>
					<?php
					$frave_child_url   = frave_term_detail_url( $frave_child );
					$frave_child_thumb = frave_term_thumb( $frave_child );
					$frave_chip_class  = '' !== $frave_child_thumb ? ' line-chip--thumb' : '';
					$frave_chip_thumb  = '' !== $frave_child_thumb ? '<span class="line-chip__thumb">' . $frave_child_thumb . '</span>' : '';
					?>
					<li>
						<?php if ( '' !== $frave_child_url ) : ?>
							<a class="line-chip line-chip--link<?php echo esc_attr( $frave_chip_class ); ?>" href="<?php echo esc_url( $frave_child_url ); ?>"><?php echo $frave_chip_thumb; // phpcs:ignore WordPress.Security.EscapeOutput -- built and escaped by frave_term_thumb(). ?><?php echo esc_html( $frave_child->name ); ?><?php echo frave_icon( 'arrow-up-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></a>
						<?php else : ?>
							<span class="line-chip<?php echo esc_attr( $frave_chip_class ); ?>"><?php echo $frave_chip_thumb; // phpcs:ignore WordPress.Security.EscapeOutput -- built and escaped by frave_term_thumb(). ?><?php echo esc_html( $frave_child->name ); ?></span>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
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
