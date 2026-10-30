<?php
/**
 * Card of the fragrance guide (families and pyramid) among the articles of the blog.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_guide = frave_fragrance_guide_links();
if ( ! $frave_guide ) {
	return;
}
?>
<aside class="card blog-promo" aria-labelledby="blog-promo-title">
	<span class="blog-promo__art" aria-hidden="true"><?php echo frave_icon( 'fragrance' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
	<p class="eyebrow"><?php esc_html_e( 'Guía de fragancias', 'frave' ); ?></p>
	<h2 class="blog-promo__title" id="blog-promo-title"><?php echo frave_emphasis( __( 'Aprende a *leer un aroma*', 'frave' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
	<p class="blog-promo__text"><?php esc_html_e( 'Las familias olfativas y la pirámide de notas, explicadas para elegir la fragancia de tu producto.', 'frave' ); ?></p>
	<ul class="blog-promo__links">
		<?php foreach ( $frave_guide as $frave_link ) : ?>
			<li><a class="line-chip line-chip--link" href="<?php echo esc_url( $frave_link['url'] ); ?>"><?php echo frave_icon( $frave_link['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?><?php echo esc_html( $frave_link['label'] ); ?></a></li>
		<?php endforeach; ?>
	</ul>
</aside>
