<?php
/**
 * Catalog invitation: a sand panel over blurred sample shelves, with the product search,
 * quick links to each line and the collections, the catalog's figures (lines, categories,
 * collections, notes) and a link to the whole catalog.
 */

if ( ! defined( 'ABSPATH' ) || ! class_exists( 'WooCommerce' ) ) {
	return;
}
?>
<section class="section home-catalog" aria-labelledby="home-catalog-title">
	<div class="container">
		<div class="home-catalog__panel" data-reveal="scale">
			<?php
			get_template_part(
				'template-parts/components/section-bg',
				null,
				array(
					'image'  => 'catalogo',
					'width'  => 2400,
					'height' => 900,
					'small'  => 1200,
				)
			);
			?>
			<div class="home-catalog__copy">
				<p class="eyebrow"><?php echo esc_html( frave_home_text( 'frave_home_catalog_eyebrow' ) ); ?></p>
				<h2 id="home-catalog-title"><?php echo frave_emphasis( frave_home_text( 'frave_home_catalog_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
				<p class="lead"><?php echo esc_html( frave_home_text( 'frave_home_catalog_text' ) ); ?></p>
			</div>
			<div class="home-catalog__search">
				<?php get_template_part( 'template-parts/components/product-search-form' ); ?>
				<ul class="home-catalog__lines">
					<?php foreach ( frave_home_lines() as $frave_line ) : ?>
						<li>
							<a href="<?php echo esc_url( $frave_line['url'] ); ?>">
								<?php echo frave_icon( $frave_line['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
								<span><?php echo esc_html( $frave_line['name'] ); ?></span>
							</a>
						</li>
					<?php endforeach; ?>
					<?php if ( function_exists( 'frave_collections' ) && frave_collections() && '' !== frave_collections_url() ) : ?>
						<li>
							<a href="<?php echo esc_url( frave_collections_url() ); ?>">
								<?php echo frave_icon( 'sparkle' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
								<span><?php esc_html_e( 'Colecciones', 'frave' ); ?></span>
							</a>
						</li>
					<?php endif; ?>
				</ul>
				<?php if ( function_exists( 'frave_catalog_figures' ) ) : ?>
					<ul class="catalog-figures home-catalog__figures">
						<?php foreach ( frave_catalog_figures() as list( $frave_number, $frave_label ) ) : ?>
							<li><strong><?php echo esc_html( number_format_i18n( $frave_number ) ); ?></strong> <?php echo esc_html( $frave_label ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<a class="link-arrow home-catalog__all" href="<?php echo esc_url( frave_catalog_shop_url() ); ?>"><?php esc_html_e( 'Ver el catálogo completo', 'frave' ); ?><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></a>
			</div>
		</div>
	</div>
</section>
