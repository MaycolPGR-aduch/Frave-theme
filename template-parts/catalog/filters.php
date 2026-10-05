<?php
/**
 * Catalog filter panel. A sidebar on wide screens; below 64rem JavaScript turns it into
 * a drawer opened from the toolbar. Without JavaScript it stays in the flow above the products.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_args     = frave_catalog_active_args();
$frave_tree     = frave_catalog_category_tree();
$frave_range    = frave_catalog_price_range();
$frave_in_stock = frave_catalog_in_stock_only();
$frave_on_sale  = frave_catalog_on_sale_only();
$frave_has_sale = $frave_on_sale || frave_catalog_sale_ids();
$frave_total    = (int) $GLOBALS['wp_query']->found_posts;
$frave_symbol   = html_entity_decode( get_woocommerce_currency_symbol() );

// A GET form drops the query string of its action, so its arguments travel as hidden fields.
$frave_base       = frave_catalog_base_url();
$frave_base_query = array();
wp_parse_str( (string) wp_parse_url( $frave_base, PHP_URL_QUERY ), $frave_base_query );
$frave_price_hidden = array_diff_key( array_merge( $frave_base_query, $frave_args ), array_flip( array( 'min_price', 'max_price' ) ) );
?>
<div class="catalog-filters-backdrop" data-catalog-filters-backdrop hidden></div>
<aside class="catalog-filters" id="catalog-filters" aria-labelledby="catalog-filters-title" data-catalog-filters>
	<div class="catalog-filters__header">
		<h2 class="catalog-filters__heading" id="catalog-filters-title" tabindex="-1"><?php esc_html_e( 'Filtrar productos', 'frave' ); ?></h2>
		<button class="catalog-filters__close" type="button" data-catalog-filters-close>
			<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="m6 6 12 12M18 6 6 18"></path></svg>
			<span class="screen-reader-text"><?php esc_html_e( 'Cerrar filtros', 'frave' ); ?></span>
		</button>
	</div>

	<div class="catalog-filters__body">
		<?php if ( $frave_tree ) : ?>
			<details class="catalog-filter catalog-filter--categories" open>
				<summary class="catalog-filter__title"><?php esc_html_e( 'Categorías', 'frave' ); ?></summary>
				<div class="catalog-filter__content">
					<a class="catalog-categories__link catalog-categories__all<?php echo is_shop() && ! is_search() ? ' is-current' : ''; ?>" href="<?php echo esc_url( frave_catalog_shop_url() ); ?>"<?php echo is_shop() && ! is_search() ? ' aria-current="page"' : ''; ?>>
						<span><?php esc_html_e( 'Todos los productos', 'frave' ); ?></span>
					</a>
					<?php frave_catalog_render_category_tree( $frave_tree ); ?>
				</div>
			</details>
		<?php endif; ?>

		<?php if ( ( $frave_range && $frave_range['max'] > $frave_range['min'] ) || isset( $frave_args['min_price'] ) || isset( $frave_args['max_price'] ) ) : ?>
			<details class="catalog-filter catalog-filter--price" open>
				<summary class="catalog-filter__title"><?php esc_html_e( 'Precio', 'frave' ); ?></summary>
				<div class="catalog-filter__content">
					<form class="catalog-price" method="get" action="<?php echo esc_url( strtok( $frave_base, '?' ) ); ?>" data-price-filter>
						<?php foreach ( $frave_price_hidden as $frave_name => $frave_value ) : ?>
							<input type="hidden" name="<?php echo esc_attr( $frave_name ); ?>" value="<?php echo esc_attr( $frave_value ); ?>">
						<?php endforeach; ?>
						<div class="catalog-price__fields">
							<p class="catalog-price__field">
								<?php /* translators: %s: currency symbol. */ ?>
								<label for="catalog-min-price"><?php echo esc_html( sprintf( __( 'Desde (%s)', 'frave' ), $frave_symbol ) ); ?></label>
								<input type="number" id="catalog-min-price" name="min_price" min="0" step="1" inputmode="decimal" value="<?php echo esc_attr( $frave_args['min_price'] ?? '' ); ?>" placeholder="<?php echo esc_attr( $frave_range ? (string) $frave_range['min'] : '' ); ?>">
							</p>
							<p class="catalog-price__field">
								<?php /* translators: %s: currency symbol. */ ?>
								<label for="catalog-max-price"><?php echo esc_html( sprintf( __( 'Hasta (%s)', 'frave' ), $frave_symbol ) ); ?></label>
								<input type="number" id="catalog-max-price" name="max_price" min="0" step="1" inputmode="decimal" value="<?php echo esc_attr( $frave_args['max_price'] ?? '' ); ?>" placeholder="<?php echo esc_attr( $frave_range ? (string) $frave_range['max'] : '' ); ?>">
							</p>
						</div>
						<button type="submit" class="catalog-price__submit"><?php esc_html_e( 'Aplicar precio', 'frave' ); ?></button>
					</form>
				</div>
			</details>
		<?php endif; ?>

		<?php if ( frave_catalog_offers_stock_filter() || $frave_has_sale ) : ?>
			<details class="catalog-filter catalog-filter--status" open>
				<summary class="catalog-filter__title"><?php esc_html_e( 'Disponibilidad y ofertas', 'frave' ); ?></summary>
				<div class="catalog-filter__content">
					<ul class="catalog-options">
						<?php if ( frave_catalog_offers_stock_filter() ) : ?>
							<li><?php echo frave_catalog_option_html( __( 'Solo disponibles', 'frave' ), frave_catalog_url( array( 'filter_stock_status' => $frave_in_stock ? null : 'instock' ) ), null, $frave_in_stock ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></li>
						<?php endif; ?>
						<?php if ( $frave_has_sale ) : ?>
							<li><?php echo frave_catalog_option_html( __( 'En oferta', 'frave' ), frave_catalog_url( array( 'on_sale' => $frave_on_sale ? null : '1' ) ), null, $frave_on_sale ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></li>
						<?php endif; ?>
					</ul>
				</div>
			</details>
		<?php endif; ?>

		<?php
		// One section per global attribute; WooCommerce prints nothing when no product in the current results uses it.
		foreach ( frave_catalog_filter_attributes() as $frave_attribute ) {
			the_widget(
				'WC_Widget_Layered_Nav',
				array(
					'title'        => $frave_attribute->attribute_label,
					'attribute'    => $frave_attribute->attribute_name,
					'display_type' => 'list',
					'query_type'   => 'or',
				),
				frave_catalog_widget_args( 'attribute' )
			);
		}

		// Shown only once products have ratings.
		the_widget( 'WC_Widget_Rating_Filter', array( 'title' => __( 'Valoración', 'frave' ) ), frave_catalog_widget_args( 'rating' ) );
		?>
	</div>

	<div class="catalog-filters__footer">
		<button type="button" class="catalog-filters__results" data-catalog-filters-close>
			<?php
			/* translators: %s: number of products. */
			echo esc_html( sprintf( _n( 'Ver %s producto', 'Ver %s productos', $frave_total, 'frave' ), number_format_i18n( $frave_total ) ) );
			?>
		</button>
		<?php if ( frave_catalog_active_filter_count() > 0 ) : ?>
			<a class="catalog-filters__clear" href="<?php echo esc_url( frave_catalog_clear_url() ); ?>"><?php esc_html_e( 'Quitar filtros', 'frave' ); ?></a>
		<?php endif; ?>
	</div>
</aside>
