<?php
/** Toolbar above the products: filter button (drawer screens), result count and sorting. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_active = frave_catalog_active_filter_count();
?>
<div class="catalog-toolbar">
	<button class="catalog-toolbar__filters" type="button" aria-controls="catalog-filters" aria-expanded="false" data-catalog-filters-open>
		<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M4 7h10M18 7h2M4 17h4M12 17h8"></path><circle cx="16" cy="7" r="2"></circle><circle cx="10" cy="17" r="2"></circle></svg>
		<span><?php esc_html_e( 'Filtrar', 'frave' ); ?></span>
		<?php if ( $frave_active ) : ?>
			<span class="catalog-toolbar__count">
				<?php echo esc_html( number_format_i18n( $frave_active ) ); ?>
				<span class="screen-reader-text"><?php echo esc_html( _n( 'filtro activo', 'filtros activos', $frave_active, 'frave' ) ); ?></span>
			</span>
		<?php endif; ?>
	</button>
	<?php
	woocommerce_result_count();
	woocommerce_catalog_ordering();
	?>
</div>
