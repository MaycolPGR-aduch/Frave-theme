<?php
/** Removable chips for the filters in use, plus a link that clears them all. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_filters = frave_catalog_active_filters();
if ( ! $frave_filters ) {
	return;
}
?>
<section class="catalog-active-filters" aria-labelledby="catalog-active-filters-title">
	<h2 class="screen-reader-text" id="catalog-active-filters-title"><?php esc_html_e( 'Filtros activos', 'frave' ); ?></h2>
	<ul>
		<?php foreach ( $frave_filters as $frave_filter ) : ?>
			<li>
				<a class="catalog-chip" rel="nofollow" href="<?php echo esc_url( $frave_filter['url'] ); ?>">
					<span><?php echo esc_html( $frave_filter['label'] ); ?></span>
					<span class="catalog-chip__remove" aria-hidden="true">&times;</span>
					<span class="screen-reader-text"><?php esc_html_e( '(quitar filtro)', 'frave' ); ?></span>
				</a>
			</li>
		<?php endforeach; ?>
		<?php if ( count( $frave_filters ) > 1 ) : ?>
			<li><a class="catalog-active-filters__clear" rel="nofollow" href="<?php echo esc_url( frave_catalog_clear_url() ); ?>"><?php esc_html_e( 'Quitar todos', 'frave' ); ?></a></li>
		<?php endif; ?>
	</ul>
</section>
