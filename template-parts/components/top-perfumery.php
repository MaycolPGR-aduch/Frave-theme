<?php
/**
 * The top of a perfumery subcategory (inc/top-perfumery.php): heading, one tab per audience
 * when there is more than one (assets/src/js/modules/tabs.js; without JavaScript every
 * list is shown), the first three as a podium and the rest as rows, and the note on the
 * brands. Nothing without published references.
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
			<ol class="top-podium">
				<?php foreach ( array_slice( $frave_entries, 0, 3 ) as $frave_index => $frave_entry ) : ?>
					<?php
					$frave_data  = frave_top_entry( $frave_entry );
					$frave_quote = frave_top_quote_link( $frave_entry, $frave_category );
					$frave_image = frave_top_image( $frave_entry, 0 === $frave_index ? '(max-width: 48rem) 90vw, 30rem' : '(max-width: 48rem) 40vw, 12rem', array( 'class' => 'top-card__image' ) );
					?>
					<li class="top-card top-card--<?php echo (int) $frave_index + 1; ?>">
						<div class="top-card__media" style="--family-tone: <?php echo esc_attr( $frave_data['family_tone'] ); ?>">
							<?php echo $frave_image; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?>
						</div>
						<div class="top-card__body">
							<span class="top-card__rank" aria-hidden="true"><?php echo (int) $frave_index + 1; ?></span>
							<h3 class="top-card__name"><?php echo esc_html( $frave_data['name'] ); ?></h3>
							<?php if ( '' !== $frave_data['house'] ) : ?>
								<p class="top-card__house"><?php echo esc_html( $frave_data['house'] ); ?></p>
							<?php endif; ?>
							<?php if ( '' !== $frave_data['family'] ) : ?>
								<?php if ( '' !== $frave_guide ) : ?>
									<a class="family-chip top-card__family" href="<?php echo esc_url( $frave_guide . '#familia-' . $frave_data['family'] ); ?>" style="--family-tone: <?php echo esc_attr( $frave_data['family_tone'] ); ?>"><?php echo esc_html( $frave_data['family_name'] ); ?></a>
								<?php else : ?>
									<span class="family-chip top-card__family" style="--family-tone: <?php echo esc_attr( $frave_data['family_tone'] ); ?>"><?php echo esc_html( $frave_data['family_name'] ); ?></span>
								<?php endif; ?>
							<?php endif; ?>
							<?php if ( $frave_data['notes'] ) : ?>
								<dl class="top-card__notes">
									<?php foreach ( $frave_data['notes'] as $frave_level => $frave_notes ) : ?>
										<div><dt><?php echo esc_html( $frave_levels[ $frave_level ] ); ?></dt><dd><?php echo esc_html( implode( ', ', array_slice( $frave_notes, 0, 0 === $frave_index ? 4 : 3 ) ) ); ?></dd></div>
									<?php endforeach; ?>
								</dl>
							<?php endif; ?>
							<?php if ( 0 === $frave_index && '' !== $frave_data['reason'] ) : ?>
								<p class="top-card__reason"><?php echo esc_html( $frave_data['reason'] ); ?></p>
							<?php endif; ?>
							<?php if ( $frave_quote ) : ?>
								<a class="link-arrow top-card__cta" href="<?php echo esc_url( $frave_quote['url'] ); ?>"<?php echo ! empty( $frave_quote['external'] ) ? ' target="_blank" rel="noopener"' : ''; ?>>
									<?php esc_html_e( 'Quiero crear este perfil', 'frave' ); ?><span class="screen-reader-text"><?php echo esc_html( ': ' . $frave_data['name'] ); ?></span>
									<?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
								</a>
							<?php endif; ?>
						</div>
					</li>
				<?php endforeach; ?>
			</ol>
			<?php if ( count( $frave_entries ) > 3 ) : ?>
				<ol class="top-rows" start="4">
					<?php foreach ( array_slice( $frave_entries, 3 ) as $frave_index => $frave_entry ) : ?>
						<?php
						$frave_data  = frave_top_entry( $frave_entry );
						$frave_quote = frave_top_quote_link( $frave_entry, $frave_category );
						?>
						<li class="top-row">
							<span class="top-row__rank" aria-hidden="true"><?php echo (int) $frave_index + 4; ?></span>
							<span class="top-row__media"><?php echo frave_top_image( $frave_entry, '4rem', array( 'class' => 'top-row__image' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></span>
							<span class="top-row__text">
								<strong class="top-row__name"><?php echo esc_html( $frave_data['name'] ); ?></strong>
								<span class="top-row__meta"><?php echo esc_html( implode( ' · ', array_filter( array( $frave_data['house'], $frave_data['family_name'] ) ) ) ); ?></span>
							</span>
							<?php if ( $frave_quote ) : ?>
								<a class="link-arrow top-row__cta" href="<?php echo esc_url( $frave_quote['url'] ); ?>"<?php echo ! empty( $frave_quote['external'] ) ? ' target="_blank" rel="noopener"' : ''; ?>>
									<?php esc_html_e( 'Crear este perfil', 'frave' ); ?><span class="screen-reader-text"><?php echo esc_html( ': ' . $frave_data['name'] ); ?></span>
									<?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
								</a>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ol>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>

	<p class="top-perfumery__note"><?php esc_html_e( 'Marcas citadas como referencia del mercado; pertenecen a sus titulares. Frave ofrece esencias e insumos para crear fragancias, no los perfumes mencionados.', 'frave' ); ?></p>
</section>
