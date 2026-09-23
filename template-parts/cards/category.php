<?php
/** Category card. */
$category = $args['category'] ?? null;
if ( ! $category instanceof WP_Term ) {
	return;
}
$thumbnail_id = (int) get_term_meta( $category->term_id, 'thumbnail_id', true );
?>
<a class="category-card" href="<?php echo esc_url( get_term_link( $category ) ); ?>">
	<span class="category-card__image">
		<?php if ( $thumbnail_id ) : ?>
			<?php echo wp_get_attachment_image( $thumbnail_id, 'frave-card', false, array( 'alt' => esc_attr( $category->name ), 'loading' => 'lazy', 'decoding' => 'async' ) ); ?>
		<?php else : ?>
			<span class="category-card__mark" aria-hidden="true">F</span>
		<?php endif; ?>
	</span>
	<span class="category-card__meta">
		<span class="category-card__name"><?php echo esc_html( $category->name ); ?></span>
		<span class="category-card__arrow" aria-hidden="true">&rarr;</span>
	</span>
</a>
