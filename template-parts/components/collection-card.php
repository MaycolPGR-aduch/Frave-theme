<?php
/**
 * Card of a collection: picture (its own or its subcategory's), subcategory (or «Colección»), name, summary
 * and how many aromas it has. The whole card is the link to the collection's page.
 *
 * Arguments: collection (WP_Term of pa_coleccion), show_category (default true).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_collection = $args['collection'];
$frave_category   = ( $args['show_category'] ?? true ) ? frave_collection_category( $frave_collection ) : null;
$frave_image      = frave_collection_image( $frave_collection, '(max-width: 48rem) 90vw, (max-width: 72rem) 45vw, 30vw', array( 'class' => 'collection-card__image' ) );
$frave_text       = wp_trim_words( wp_strip_all_tags( $frave_collection->description ), 22 );
?>
<li class="collection-card">
	<div class="collection-card__media media-frame">
		<?php if ( '' !== $frave_image ) : ?>
			<?php echo $frave_image; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?>
		<?php else : ?>
			<span class="collection-card__art" aria-hidden="true"><?php echo frave_icon( 'fragrance' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
		<?php endif; ?>
	</div>
	<div class="collection-card__body">
		<p class="collection-card__eyebrow">
			<?php echo esc_html( $frave_category ? $frave_category->name : __( 'Colección', 'frave' ) ); ?>
		</p>
		<h3 class="collection-card__title"><a class="collection-card__link" href="<?php echo esc_url( frave_collection_url( $frave_collection ) ); ?>"><?php echo esc_html( $frave_collection->name ); ?></a></h3>
		<?php if ( '' !== $frave_text ) : ?>
			<p class="collection-card__text"><?php echo esc_html( $frave_text ); ?></p>
		<?php endif; ?>
		<p class="collection-card__meta">
			<?php
			echo esc_html(
				$frave_collection->count > 0
					/* translators: %d: number of aromas (products) in the collection. */
					? sprintf( _n( '%d aroma', '%d aromas', $frave_collection->count, 'frave' ), $frave_collection->count )
					: __( 'Aromas a pedido', 'frave' )
			);
			?>
			<span class="link-arrow" aria-hidden="true"><?php esc_html_e( 'Ver la colección', 'frave' ); ?><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
		</p>
	</div>
</li>
