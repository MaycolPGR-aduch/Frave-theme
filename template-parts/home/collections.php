<?php
/**
 * «Listas para tu producto»: every collection (inc/collections.php) in a row that scrolls,
 * as on the catalog page (pages/catalog.css), with a link to the hub. Nothing without
 * collections.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_collections = function_exists( 'frave_collections' ) ? frave_collections() : array();
if ( ! $frave_collections ) {
	return;
}
$frave_hub = frave_collections_url();
?>
<section class="section home-collections" aria-labelledby="home-collections-title">
	<div class="container">
		<div class="section-head<?php echo '' !== $frave_hub ? ' section-head--split' : ''; ?>" data-reveal="up">
			<p class="eyebrow"><?php echo esc_html( frave_home_text( 'frave_home_collections_eyebrow' ) ); ?></p>
			<h2 id="home-collections-title"><?php echo frave_emphasis( frave_home_text( 'frave_home_collections_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
			<p class="lead"><?php echo esc_html( frave_home_text( 'frave_home_collections_text' ) ); ?></p>
			<?php if ( '' !== $frave_hub ) : ?>
				<a class="link-arrow section-head__action" href="<?php echo esc_url( $frave_hub ); ?>"><?php esc_html_e( 'Ver todas las colecciones', 'frave' ); ?><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></a>
			<?php endif; ?>
		</div>
		<ul class="collection-cards catalog-collections" aria-label="<?php esc_attr_e( 'Colecciones (desliza para ver más)', 'frave' ); ?>">
			<?php foreach ( $frave_collections as $frave_collection ) : ?>
				<?php get_template_part( 'template-parts/components/collection-card', null, array( 'collection' => $frave_collection ) ); ?>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
