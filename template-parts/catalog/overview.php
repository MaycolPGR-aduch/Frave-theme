<?php
/**
 * The catalog's summary before the products (inc/catalog-overview.php): anchors, index of
 * the lines, collections, perfumery tops, applications by industry, aromas and the heading
 * of the products. Each block is left out while it has nothing to show.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_lines       = frave_catalog_lines();
$frave_collections = function_exists( 'frave_collections' ) ? frave_collections() : array();
$frave_tops        = function_exists( 'frave_top_categories' ) ? frave_top_categories() : array();
$frave_solutions   = frave_solution_cards();
$frave_guide       = frave_fragrance_guide_url();

// Featured notes: the hallmarks of the perfumery styles, each leading to its style's notes.
$frave_notes    = function_exists( 'frave_fragrance_notes' ) ? frave_fragrance_notes() : array();
$frave_featured = array();
foreach ( function_exists( 'frave_style_seals' ) ? frave_style_seals() : array() as $frave_category_slug => $frave_slugs ) {
	$frave_category = get_term_by( 'slug', $frave_category_slug, 'product_cat' );
	foreach ( $frave_slugs as $frave_slug ) {
		if ( isset( $frave_notes[ $frave_slug ] ) && ! isset( $frave_featured[ $frave_slug ] ) ) {
			$frave_featured[ $frave_slug ] = $frave_category instanceof WP_Term ? frave_term_detail_url( $frave_category ) . '#ingredientes' : '';
		}
	}
}

$frave_anchors = array( array( __( 'Líneas', 'frave' ), '#catalogo-lineas' ) );
if ( $frave_collections ) {
	$frave_anchors[] = array( __( 'Colecciones', 'frave' ), '#catalogo-colecciones' );
}
if ( $frave_tops ) {
	$frave_anchors[] = array( __( 'Top de perfumería', 'frave' ), '#catalogo-top' );
}
if ( $frave_solutions ) {
	$frave_anchors[] = array( __( 'Por aplicación', 'frave' ), '#catalogo-aplicaciones' );
}
$frave_anchors[] = array( __( 'Por aroma', 'frave' ), '#catalogo-aromas' );
$frave_anchors[] = array( __( 'Productos', 'frave' ), '#productos' );
?>
<nav class="catalog-anchors" aria-label="<?php esc_attr_e( 'En esta página', 'frave' ); ?>">
	<?php foreach ( $frave_anchors as list( $frave_label, $frave_url ) ) : ?>
		<a href="<?php echo esc_url( $frave_url ); ?>"><?php echo esc_html( $frave_label ); ?></a>
	<?php endforeach; ?>
</nav>

<section class="catalog-block" id="catalogo-lineas" aria-labelledby="catalogo-lineas-title">
	<div class="section-head">
		<p class="eyebrow"><?php esc_html_e( 'Índice', 'frave' ); ?></p>
		<h2 id="catalogo-lineas-title"><?php echo frave_emphasis( __( 'Explora por *línea*', 'frave' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
	</div>
	<div class="catalog-index">
		<?php foreach ( $frave_lines as $frave_line ) : ?>
			<?php
			$frave_line_url = frave_line_url( $frave_line->slug );
			$frave_line_url = '' !== $frave_line_url ? $frave_line_url : frave_term_detail_url( $frave_line );
			$frave_icon     = array(
				'fragancias' => 'fragrance',
				'sabores'    => 'flavor',
			)[ $frave_line->slug ] ?? 'packaging';
			$frave_groups   = frave_catalog_children( $frave_line );
			// A line whose categories have types (Fragancias) spans the width, its groups side by side.
			$frave_wide = (bool) array_filter( $frave_groups, static fn( WP_Term $group ): bool => (bool) frave_catalog_children( $group ) );
			?>
			<div class="catalog-index__line<?php echo $frave_wide ? ' catalog-index__line--wide' : ''; ?>">
				<h3 class="catalog-index__title">
					<span class="catalog-index__icon"><?php echo frave_icon( $frave_icon ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
					<a href="<?php echo esc_url( $frave_line_url ); ?>"><?php echo esc_html( $frave_line->name ); ?></a>
				</h3>
				<ul class="catalog-index__list">
					<?php foreach ( $frave_groups as $frave_group ) : ?>
						<?php
						$frave_types = frave_catalog_children( $frave_group );
						$frave_count = function_exists( 'frave_collections' ) ? count( frave_collections( array( $frave_group ) ) ) : 0;
						?>
						<li class="catalog-index__group<?php echo $frave_types ? ' has-types' : ''; ?>">
							<a href="<?php echo esc_url( frave_term_detail_url( $frave_group ) ); ?>"><?php echo esc_html( $frave_group->name ); ?></a>
							<?php if ( ! $frave_types && $frave_count > 0 ) : ?>
								<?php /* translators: %d: number of collections. */ ?>
								<span class="catalog-index__count"><?php echo esc_html( sprintf( _n( '%d colección', '%d colecciones', $frave_count, 'frave' ), $frave_count ) ); ?></span>
							<?php endif; ?>
							<?php if ( $frave_types ) : ?>
								<ul>
									<?php foreach ( $frave_types as $frave_type ) : ?>
										<?php $frave_count = function_exists( 'frave_collections' ) ? count( frave_collections( array( $frave_type ) ) ) : 0; ?>
										<li>
											<a href="<?php echo esc_url( frave_term_detail_url( $frave_type ) ); ?>"><?php echo esc_html( $frave_type->name ); ?></a>
											<?php if ( $frave_count > 0 ) : ?>
												<?php /* translators: %d: number of collections. */ ?>
												<span class="catalog-index__count"><?php echo esc_html( sprintf( _n( '%d colección', '%d colecciones', $frave_count, 'frave' ), $frave_count ) ); ?></span>
											<?php endif; ?>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
				<?php if ( '' !== $frave_line_url ) : ?>
					<?php /* translators: %s: product line name. */ ?>
					<a class="link-arrow catalog-index__more" href="<?php echo esc_url( $frave_line_url ); ?>"><?php echo esc_html( sprintf( __( 'Conoce %s', 'frave' ), $frave_line->name ) ); ?><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></a>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
</section>

<?php if ( $frave_collections ) : ?>
	<section class="catalog-block" id="catalogo-colecciones" aria-labelledby="catalogo-colecciones-title">
		<div class="section-head section-head--split">
			<p class="eyebrow"><?php esc_html_e( 'Colecciones', 'frave' ); ?></p>
			<h2 id="catalogo-colecciones-title"><?php echo frave_emphasis( __( 'Listas para *tu producto*', 'frave' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
			<?php if ( '' !== frave_collections_url() ) : ?>
				<a class="link-arrow section-head__action" href="<?php echo esc_url( frave_collections_url() ); ?>"><?php esc_html_e( 'Ver todas las colecciones', 'frave' ); ?><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></a>
			<?php endif; ?>
		</div>
		<ul class="collection-cards catalog-collections" aria-label="<?php esc_attr_e( 'Colecciones (desliza para ver más)', 'frave' ); ?>">
			<?php foreach ( $frave_collections as $frave_collection ) : ?>
				<?php get_template_part( 'template-parts/components/collection-card', null, array( 'collection' => $frave_collection ) ); ?>
			<?php endforeach; ?>
		</ul>
	</section>
<?php endif; ?>

<?php if ( $frave_tops ) : ?>
	<div class="catalog-block" id="catalogo-top">
		<?php get_template_part( 'template-parts/components/top-perfumery-band', null, array( 'embedded' => true ) ); ?>
	</div>
<?php endif; ?>

<?php if ( $frave_solutions ) : ?>
	<section class="catalog-block" id="catalogo-aplicaciones" aria-labelledby="catalogo-aplicaciones-title">
		<div class="section-head">
			<p class="eyebrow"><?php esc_html_e( 'Por aplicación', 'frave' ); ?></p>
			<h2 id="catalogo-aplicaciones-title"><?php echo frave_emphasis( __( '¿Qué *fabricas*?', 'frave' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
			<p class="lead"><?php esc_html_e( 'Elige tu industria y descubre lo que te ofrecemos para cada producto.', 'frave' ); ?></p>
		</div>
		<ul class="catalog-uses">
			<?php foreach ( $frave_solutions as $frave_solution ) : ?>
				<?php $frave_uses = array_slice( frave_lines_of( frave_solution_text( 'applications', $frave_solution['slug'] ) ), 0, 6 ); ?>
				<li class="catalog-uses__item">
					<a class="catalog-uses__title" href="<?php echo esc_url( $frave_solution['url'] ); ?>">
						<span class="catalog-uses__icon"><?php echo frave_icon( $frave_solution['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
						<span><?php echo esc_html( $frave_solution['title'] ); ?></span>
						<?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
					</a>
					<?php if ( $frave_uses ) : ?>
						<p class="catalog-uses__list">
							<?php foreach ( $frave_uses as $frave_use ) : ?>
								<span class="line-chip"><?php echo esc_html( $frave_use ); ?></span>
							<?php endforeach; ?>
						</p>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</section>
<?php endif; ?>

<section class="catalog-block" id="catalogo-aromas" aria-labelledby="catalogo-aromas-title">
	<div class="section-head section-head--split">
		<p class="eyebrow"><?php esc_html_e( 'Por aroma', 'frave' ); ?></p>
		<h2 id="catalogo-aromas-title"><?php echo frave_emphasis( __( 'Busca por *familia olfativa*', 'frave' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
		<?php if ( '' !== $frave_guide ) : ?>
			<a class="link-arrow section-head__action" href="<?php echo esc_url( $frave_guide ); ?>"><?php esc_html_e( 'Ver la guía de familias', 'frave' ); ?><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></a>
		<?php endif; ?>
	</div>
	<ul class="catalog-families">
		<?php foreach ( frave_fragrance_families() as $frave_slug => $frave_family ) : ?>
			<li>
				<?php if ( '' !== $frave_guide ) : ?>
					<a class="family-chip" href="<?php echo esc_url( $frave_guide . '#familia-' . $frave_slug ); ?>" style="--family-tone: <?php echo esc_attr( $frave_family['tone'] ); ?>"><?php echo esc_html( $frave_family['name'] ); ?></a>
				<?php else : ?>
					<span class="family-chip" style="--family-tone: <?php echo esc_attr( $frave_family['tone'] ); ?>"><?php echo esc_html( $frave_family['name'] ); ?></span>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php if ( $frave_featured ) : ?>
		<p class="catalog-notes__label" id="catalogo-notas"><?php esc_html_e( 'Notas destacadas', 'frave' ); ?></p>
		<ul class="catalog-notes" aria-labelledby="catalogo-notas">
			<?php foreach ( $frave_featured as $frave_slug => $frave_url ) : ?>
				<?php $frave_note = $frave_notes[ $frave_slug ]; ?>
				<li>
					<a class="note-chip" href="<?php echo esc_url( $frave_url ); ?>">
						<span class="note-chip__icon" style="--family-tone: <?php echo esc_attr( frave_fragrance_families()[ $frave_note['family'] ]['tone'] ?? '#f1e6d8' ); ?>"><?php echo frave_note_icon( $frave_note['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
						<span class="note-chip__name"><?php echo esc_html( $frave_note['name'] ); ?></span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</section>

<div class="catalog-block catalog-products-head" id="productos">
	<div class="section-head">
		<p class="eyebrow"><?php esc_html_e( 'Productos', 'frave' ); ?></p>
		<h2><?php echo frave_emphasis( __( 'Todos los *productos*', 'frave' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
	</div>
</div>
