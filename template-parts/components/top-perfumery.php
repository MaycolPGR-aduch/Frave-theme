<?php
/**
 * The top of a perfumery subcategory (inc/top-perfumery.php): heading, one tab per audience
 * when there is more than one (assets/src/js/modules/tabs.js; without JavaScript every
 * list is shown), the ranking as panels stacked one under another and the note on the
 * brands. Nothing without published references.
 *
 * One panel is open at a time (assets/src/js/modules/top-panels.js), the first at the start.
 * On wide screens the panels open downward, the picture on the right, when the mouse points
 * at them; on phones they are an accordion opened with a tap. Without JavaScript all are open.
 *
 * Arguments: category (WP_Term).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_category = $args['category'] ?? null;
if ( ! $frave_category instanceof WP_Term ) {
	return;
}
$frave_groups = frave_top_groups( frave_top_entries( $frave_category ) );
if ( ! $frave_groups ) {
	return;
}
$frave_audiences = frave_top_audiences();
$frave_levels    = frave_top_levels();
$frave_guide     = frave_fragrance_guide_url();
$frave_tabs      = count( $frave_groups ) > 1;
$frave_first     = (string) array_key_first( $frave_groups );
?>
<section class="top-perfumery" id="top-perfumeria" aria-labelledby="top-perfumeria-title" data-frave-place="top-perfumeria"<?php echo $frave_tabs ? ' data-tabs' : ''; ?>>
	<div class="top-perfumery__head">
		<?php /* translators: %s: perfumery subcategory. */ ?>
		<p class="eyebrow"><?php echo esc_html( sprintf( __( 'Top · %s', 'frave' ), $frave_category->name ) ); ?></p>
		<h2 id="top-perfumeria-title"><?php echo frave_emphasis( __( 'Los perfumes que *marcan tendencia*', 'frave' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
		<p class="top-perfumery__lead"><?php esc_html_e( 'Los perfiles más buscados del mercado. Con nuestras esencias y asesoría técnica puedes crear el tuyo.', 'frave' ); ?></p>
		<?php if ( $frave_tabs ) : ?>
			<div class="top-perfumery__tabs" role="tablist" aria-label="<?php esc_attr_e( 'Para quién', 'frave' ); ?>">
				<?php foreach ( array_keys( $frave_groups ) as $frave_audience ) : ?>
					<button type="button" class="top-perfumery__tab" role="tab" id="top-tab-<?php echo esc_attr( $frave_audience ); ?>" aria-controls="top-panel-<?php echo esc_attr( $frave_audience ); ?>" aria-selected="<?php echo $frave_audience === $frave_first ? 'true' : 'false'; ?>"<?php echo $frave_audience === $frave_first ? '' : ' tabindex="-1"'; ?>><?php echo esc_html( $frave_audiences[ $frave_audience ] ); ?></button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>

	<?php foreach ( $frave_groups as $frave_audience => $frave_entries ) : ?>
		<div class="top-perfumery__panel" id="top-panel-<?php echo esc_attr( $frave_audience ); ?>"<?php echo $frave_tabs ? ' role="tabpanel" aria-labelledby="top-tab-' . esc_attr( $frave_audience ) . '"' : ''; ?>>
			<?php // The audience names the list without tabs (and before tabs.js runs). ?>
			<?php if ( $frave_tabs || 'unisex' !== $frave_audience ) : ?>
				<p class="top-perfumery__audience"><?php echo esc_html( $frave_audiences[ $frave_audience ] ); ?></p>
			<?php endif; ?>
			<ol class="top-panels" data-top-panels style="--count: <?php echo (int) count( $frave_entries ); ?>">
				<?php foreach ( $frave_entries as $frave_index => $frave_entry ) : ?>
					<?php
					$frave_data  = frave_top_entry( $frave_entry );
					$frave_quote = frave_top_quote_link( $frave_entry, $frave_category );
					$frave_id    = 'top-' . $frave_audience . '-' . ( $frave_index + 1 );
					$frave_open  = 0 === $frave_index;
					?>
					<li class="top-item<?php echo $frave_open ? ' is-open' : ''; ?>" data-top-item style="--family-tone: <?php echo esc_attr( $frave_data['family_tone'] ); ?>">
						<h3 class="top-item__heading">
							<button type="button" class="top-item__toggle" id="<?php echo esc_attr( $frave_id ); ?>" aria-expanded="<?php echo $frave_open ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( $frave_id . '-panel' ); ?>">
								<span class="top-item__rank" aria-hidden="true"><?php echo (int) $frave_index + 1; ?></span>
								<span class="top-item__thumb"><?php echo frave_top_image( $frave_entry, '3.5rem', array( 'class' => 'top-item__thumb-image' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></span>
								<span class="top-item__title">
									<span class="top-item__name"><?php echo esc_html( $frave_data['name'] ); ?></span>
									<?php if ( '' !== $frave_data['house'] ) : ?>
										<span class="top-item__house"><?php echo esc_html( $frave_data['house'] ); ?></span>
									<?php endif; ?>
								</span>
								<span class="top-item__chevron" aria-hidden="true"><?php echo frave_icon( 'chevron-down' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
							</button>
						</h3>
						<div class="top-item__panel" id="<?php echo esc_attr( $frave_id . '-panel' ); ?>" role="region" aria-labelledby="<?php echo esc_attr( $frave_id ); ?>">
							<div class="top-item__inner">
								<div class="top-item__media"><?php echo frave_top_image( $frave_entry, '(min-width: 64rem) 44rem, 92vw', array( 'class' => 'top-item__image' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></div>
								<div class="top-item__details">
									<?php if ( '' !== $frave_data['family'] ) : ?>
										<?php if ( '' !== $frave_guide ) : ?>
											<a class="family-chip top-item__family" href="<?php echo esc_url( $frave_guide . '#familia-' . $frave_data['family'] ); ?>"><?php echo esc_html( $frave_data['family_name'] ); ?></a>
										<?php else : ?>
											<span class="family-chip top-item__family"><?php echo esc_html( $frave_data['family_name'] ); ?></span>
										<?php endif; ?>
									<?php endif; ?>
									<?php if ( $frave_data['notes'] ) : ?>
										<dl class="top-item__notes">
											<?php foreach ( $frave_data['notes'] as $frave_level => $frave_notes ) : ?>
												<div><dt><?php echo esc_html( $frave_levels[ $frave_level ] ); ?></dt><dd><?php echo esc_html( implode( ', ', $frave_notes ) ); ?></dd></div>
											<?php endforeach; ?>
										</dl>
									<?php endif; ?>
									<?php if ( '' !== $frave_data['reason'] ) : ?>
										<p class="top-item__reason"><?php echo esc_html( $frave_data['reason'] ); ?></p>
									<?php endif; ?>
									<?php if ( $frave_quote ) : ?>
										<a class="btn btn--primary top-item__cta" href="<?php echo esc_url( $frave_quote['url'] ); ?>"<?php echo ! empty( $frave_quote['external'] ) ? ' target="_blank" rel="noopener"' : ''; ?>>
											<span><?php esc_html_e( 'Quiero crear este perfil', 'frave' ); ?><span class="screen-reader-text"><?php echo esc_html( ': ' . $frave_data['name'] ); ?></span></span>
											<span class="btn__icon"><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
										</a>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>
	<?php endforeach; ?>

	<p class="top-perfumery__note"><?php esc_html_e( 'Marcas citadas como referencia del mercado; pertenecen a sus titulares. Frave ofrece esencias e insumos para crear fragancias, no los perfumes mencionados.', 'frave' ); ?></p>
</section>
