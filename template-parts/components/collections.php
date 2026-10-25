<?php
/**
 * A list of collection cards with its heading; nothing without collections.
 *
 * Arguments: collections (WP_Term[]), eyebrow, title (*words* highlighted), text, id,
 * surface (warm surface background), compact (inside a catalog page: no section padding),
 * show_category (on each card, default true), more (URL of a link beside the heading) and
 * more_label (its text; «Ver todas las colecciones» by default).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_list = $args['collections'] ?? array();
if ( ! $frave_list ) {
	return;
}
$frave_id       = $args['id'] ?? 'colecciones';
$frave_compact  = ! empty( $args['compact'] );
$frave_more     = $args['more'] ?? '';
$frave_classes  = $frave_compact ? 'collections collections--compact' : 'section collections';
$frave_classes .= ! empty( $args['surface'] ) ? ' section--surface' : '';
?>
<section class="<?php echo esc_attr( $frave_classes ); ?>" id="<?php echo esc_attr( $frave_id ); ?>" aria-labelledby="<?php echo esc_attr( $frave_id . '-title' ); ?>">
	<?php if ( ! $frave_compact ) : ?>
		<div class="container">
	<?php endif; ?>
	<div class="section-head<?php echo '' !== $frave_more ? ' section-head--split' : ''; ?>" data-reveal="up">
		<p class="eyebrow"><?php echo esc_html( $args['eyebrow'] ?? __( 'Colecciones', 'frave' ) ); ?></p>
		<h2 id="<?php echo esc_attr( $frave_id . '-title' ); ?>"><?php echo frave_emphasis( (string) ( $args['title'] ?? __( 'Nuestras *colecciones*', 'frave' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
		<?php if ( ! empty( $args['text'] ) ) : ?>
			<p class="lead"><?php echo esc_html( $args['text'] ); ?></p>
		<?php endif; ?>
		<?php if ( '' !== $frave_more ) : ?>
			<a class="link-arrow section-head__action" href="<?php echo esc_url( $frave_more ); ?>"><?php echo esc_html( ! empty( $args['more_label'] ) ? $args['more_label'] : __( 'Ver todas las colecciones', 'frave' ) ); ?><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></a>
		<?php endif; ?>
	</div>
	<ul class="collection-cards" data-reveal-group>
		<?php foreach ( $frave_list as $frave_collection ) : ?>
			<?php
			get_template_part(
				'template-parts/components/collection-card',
				null,
				array(
					'collection'    => $frave_collection,
					'show_category' => $args['show_category'] ?? true,
				)
			);
			?>
		<?php endforeach; ?>
	</ul>
	<?php if ( ! $frave_compact ) : ?>
		</div>
	<?php endif; ?>
</section>
