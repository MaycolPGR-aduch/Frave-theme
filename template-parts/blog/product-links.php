<?php
/**
 * «Para tu producto» after an article (frave_post_product_links()): the collections it is about
 * as cards, and links to its line and, for Fragancias, to the families guide and the pyramid.
 * Nothing when the article names neither.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_found = frave_post_product_links();
if ( ! $frave_found['collections'] && ! $frave_found['links'] ) {
	return;
}
?>
<section class="section article-product" aria-labelledby="article-product-title">
	<div class="container">
		<div class="section-head" data-reveal="up">
			<p class="eyebrow"><?php esc_html_e( 'Para tu producto', 'frave' ); ?></p>
			<h2 id="article-product-title"><?php echo frave_emphasis( __( 'Lleva estas ideas *a tu fórmula*', 'frave' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
		</div>
		<?php if ( $frave_found['links'] ) : ?>
			<ul class="article-product__links" data-reveal="up">
				<?php foreach ( $frave_found['links'] as $frave_link ) : ?>
					<li>
						<a class="line-chip line-chip--link" href="<?php echo esc_url( $frave_link['url'] ); ?>">
							<?php echo '' !== $frave_link['icon'] ? frave_icon( $frave_link['icon'] ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
							<?php echo esc_html( $frave_link['label'] ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<?php if ( $frave_found['collections'] ) : ?>
			<ul class="collection-cards article-product__collections" data-reveal-group>
				<?php foreach ( $frave_found['collections'] as $frave_collection ) : ?>
					<?php
					get_template_part(
						'template-parts/components/collection-card',
						null,
						array(
							'collection'    => $frave_collection,
							'show_category' => true,
						)
					);
					?>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</section>
