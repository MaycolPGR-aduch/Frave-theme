<?php
/** Catalog invitation: a sand panel over blurred sample shelves, with the product search and quick links to each line. */

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
				</ul>
			</div>
		</div>
	</div>
</section>
