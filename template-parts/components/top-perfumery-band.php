<?php
/**
 * Band of the perfumery tops (inc/top-perfumery.php) on the Fragancias line and the
 * Perfumería solution: one card per subcategory with a top, showing the first perfume of
 * each audience (or the first three when there is one audience) and a link to the whole
 * top on the subcategory's page. Nothing without published references.
 *
 * Arguments: within (WP_Term[]: only the subcategories one of them or below them).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_categories = frave_top_categories( $args['within'] ?? null );
if ( ! $frave_categories ) {
	return;
}
$frave_audiences = frave_top_audiences();
?>
<section class="section top-band" aria-labelledby="top-band-title" data-frave-place="top-perfumeria">
	<div class="container">
		<div class="section-head" data-reveal="up">
			<p class="eyebrow"><?php esc_html_e( 'Top de perfumería', 'frave' ); ?></p>
			<h2 id="top-band-title"><?php echo frave_emphasis( __( 'Los perfumes que *marcan tendencia*', 'frave' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
			<p class="lead"><?php esc_html_e( 'Lo más buscado del mercado en cada estilo de perfumería. Inspírate y crea tu propia fragancia con nuestras esencias.', 'frave' ); ?></p>
		</div>
		<ul class="top-band__list" data-reveal-group>
			<?php foreach ( $frave_categories as $frave_category ) : ?>
				<?php
				$frave_groups = frave_top_groups( frave_top_entries( $frave_category ) );
				$frave_url    = frave_term_detail_url( $frave_category );
				$frave_lead   = reset( $frave_groups )[0] ?? null;
				?>
				<li class="top-band__card">
					<?php if ( $frave_lead ) : ?>
						<div class="top-band__media"><?php echo frave_top_image( $frave_lead, '(max-width: 48rem) 90vw, 26rem', array( 'class' => 'top-band__image' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></div>
					<?php endif; ?>
					<div class="top-band__body">
						<h3 class="top-band__title"><?php echo esc_html( $frave_category->name ); ?></h3>
						<ul class="top-band__leaders">
							<?php
							// Several audiences: the first of each. One audience: its first three.
							$frave_leaders = array();
							$frave_size    = count( $frave_groups ) > 1 ? 1 : 3;
							foreach ( $frave_groups as $frave_audience => $frave_entries ) {
								foreach ( array_slice( $frave_entries, 0, $frave_size ) as $frave_rank => $frave_entry ) {
									$frave_leaders[] = array( $frave_audience, $frave_rank + 1, $frave_entry );
								}
							}
							?>
							<?php foreach ( $frave_leaders as list( $frave_audience, $frave_rank, $frave_entry ) ) : ?>
								<?php $frave_data = frave_top_entry( $frave_entry ); ?>
								<li>
									<span class="top-band__rank" aria-hidden="true"><?php echo (int) $frave_rank; ?></span>
									<span>
										<?php if ( count( $frave_groups ) > 1 || ( 'unisex' !== $frave_audience && 1 === $frave_rank ) ) : ?>
											<span class="top-band__audience"><?php echo esc_html( $frave_audiences[ $frave_audience ] ); ?></span>
										<?php endif; ?>
										<strong><?php echo esc_html( $frave_data['name'] ); ?></strong>
										<?php if ( '' !== $frave_data['house'] ) : ?>
											<span class="top-band__house"><?php echo esc_html( $frave_data['house'] ); ?></span>
										<?php endif; ?>
									</span>
								</li>
							<?php endforeach; ?>
						</ul>
						<?php if ( '' !== $frave_url ) : ?>
							<a class="link-arrow top-band__link" href="<?php echo esc_url( $frave_url . '#top-perfumeria' ); ?>">
								<?php esc_html_e( 'Ver el top', 'frave' ); ?><span class="screen-reader-text"><?php echo esc_html( ': ' . $frave_category->name ); ?></span>
								<?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
							</a>
						<?php endif; ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
		<p class="top-perfumery__note"><?php esc_html_e( 'Marcas citadas como referencia del mercado; pertenecen a sus titulares.', 'frave' ); ?></p>
	</div>
</section>
