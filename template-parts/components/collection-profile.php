<?php
/**
 * The aromas of a collection (frave_collection_notes(), from the notes library of
 * inc/fragrance-notes.php), told two ways:
 * - «Los aromas de …»: one card per ingredient, tinted with its family's colour, with its
 *   line icon and name; pointing at it (or focusing or tapping it, note-cards.js) turns it
 *   to its description, its level and its family in the guide.
 * - «Cómo se percibe»: the ingredients on the levels of the olfactory pyramid (salida,
 *   corazón, fondo; only with two or more), and the families the collection brings together.
 * Nothing while the collection has no ingredients.
 *
 * Arguments: collection (WP_Term).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_collection = $args['collection'] ?? null;
if ( ! $frave_collection instanceof WP_Term ) {
	return;
}
$frave_notes = frave_collection_notes( $frave_collection );
if ( ! $frave_notes ) {
	return;
}
$frave_families    = frave_fragrance_families();
$frave_guide       = frave_fragrance_guide_url();
$frave_pyramid     = frave_fragrance_pyramid();
$frave_levels      = array(
	'top'   => $frave_pyramid['salida'] ?? array(),
	'heart' => $frave_pyramid['corazon'] ?? array(),
	'base'  => $frave_pyramid['fondo'] ?? array(),
);
$frave_level_names = array(
	'top'   => __( 'Nota de salida', 'frave' ),
	'heart' => __( 'Nota de corazón', 'frave' ),
	'base'  => __( 'Nota de fondo', 'frave' ),
);

// The ingredients by level and by family, in the collection's order.
$frave_tiers  = array_fill_keys( array_keys( $frave_levels ), array() );
$frave_groups = array();
foreach ( $frave_notes as $frave_slug => $frave_note ) {
	$frave_tiers[ $frave_note['level'] ][ $frave_slug ] = $frave_note;
	$frave_groups[ $frave_note['family'] ][]            = $frave_note['name'];
}
$frave_tiers = array_filter( $frave_tiers );
?>
<section class="collection-aromas" aria-labelledby="collection-aromas-title">
	<div class="section-head" data-reveal="up">
		<p class="eyebrow"><?php esc_html_e( 'Ingredientes', 'frave' ); ?></p>
		<?php /* translators: %s: collection name. */ ?>
		<h2 id="collection-aromas-title"><?php echo frave_emphasis( sprintf( __( 'Los aromas de *%s*', 'frave' ), $frave_collection->name ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
		<p class="lead"><?php esc_html_e( 'Pasa el cursor o toca cada ingrediente para conocerlo.', 'frave' ); ?></p>
	</div>
	<ul class="note-cards" data-note-cards data-reveal-group>
		<?php foreach ( $frave_notes as $frave_slug => $frave_note ) : ?>
			<?php
			$frave_family = $frave_families[ $frave_note['family'] ] ?? null;
			$frave_tone   = $frave_family ? $frave_family['tone'] : '#f1e6d8';
			?>
			<li class="note-card" tabindex="0" data-note-card style="--family-tone: <?php echo esc_attr( $frave_tone ); ?>">
				<div class="note-card__inner">
					<div class="note-card__face note-card__face--front">
						<span class="note-card__icon"><?php echo frave_note_icon( $frave_note['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
						<h3 class="note-card__name"><?php echo esc_html( $frave_note['name'] ); ?></h3>
						<?php if ( $frave_family ) : ?>
							<p class="note-card__family"><?php echo esc_html( $frave_family['name'] ); ?></p>
						<?php endif; ?>
					</div>
					<div class="note-card__face note-card__face--back">
						<p class="note-card__level"><?php echo esc_html( $frave_level_names[ $frave_note['level'] ] ?? '' ); ?></p>
						<p class="note-card__text"><?php echo esc_html( $frave_note['text'] ); ?></p>
						<?php if ( $frave_family && '' !== $frave_guide ) : ?>
							<a class="note-card__link" href="<?php echo esc_url( $frave_guide . '#familia-' . $frave_note['family'] ); ?>">
								<?php
								/* translators: %s: olfactory family, e.g. «Floral». */
								echo esc_html( sprintf( __( 'Familia %s', 'frave' ), $frave_family['name'] ) );
								?>
								<?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
							</a>
						<?php endif; ?>
					</div>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>
</section>

<section class="collection-profile" aria-labelledby="collection-profile-title">
	<div class="section-head" data-reveal="up">
		<p class="eyebrow"><?php esc_html_e( 'Perfil olfativo', 'frave' ); ?></p>
		<h2 id="collection-profile-title"><?php echo frave_emphasis( __( 'Cómo se *percibe*', 'frave' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
	</div>
	<div class="collection-profile__grid<?php echo count( $frave_notes ) < 2 ? ' collection-profile__grid--single' : ''; ?>" data-reveal="up">
		<?php if ( count( $frave_notes ) >= 2 ) : ?>
			<div class="collection-profile__pyramid">
				<h3 class="collection-profile__title"><?php esc_html_e( 'En la pirámide olfativa', 'frave' ); ?></h3>
				<ol class="profile-tiers">
					<?php foreach ( $frave_tiers as $frave_level => $frave_items ) : ?>
						<?php $frave_tier = $frave_levels[ $frave_level ]; ?>
						<li class="profile-tier profile-tier--<?php echo esc_attr( $frave_level ); ?>" style="--level-tone: <?php echo esc_attr( $frave_tier['tone'] ?? '#f1e6d8' ); ?>; --level-accent: <?php echo esc_attr( $frave_tier['accent'] ?? '#e2d3bf' ); ?>">
							<p class="profile-tier__label"><strong><?php echo esc_html( $frave_tier['short'] ?? '' ); ?></strong> <span><?php echo esc_html( $frave_tier['range'] ?? '' ); ?></span></p>
							<ul class="profile-tier__notes">
								<?php foreach ( $frave_items as $frave_item ) : ?>
									<li><?php echo frave_note_icon( $frave_item['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?><?php echo esc_html( $frave_item['name'] ); ?></li>
								<?php endforeach; ?>
							</ul>
						</li>
					<?php endforeach; ?>
				</ol>
				<?php $frave_pyramid_url = frave_fragrance_pyramid_url(); ?>
				<?php if ( '' !== $frave_pyramid_url ) : ?>
					<a class="link-arrow" href="<?php echo esc_url( $frave_pyramid_url ); ?>"><?php esc_html_e( 'Cómo funciona la pirámide olfativa', 'frave' ); ?><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<div class="collection-profile__families">
			<h3 class="collection-profile__title"><?php esc_html_e( 'Familias de la colección', 'frave' ); ?></h3>
			<dl class="profile-families">
				<?php foreach ( $frave_groups as $frave_family_slug => $frave_names ) : ?>
					<?php
					$frave_family = $frave_families[ $frave_family_slug ] ?? null;
					if ( ! $frave_family ) {
						continue;
					}
					?>
					<div class="profile-families__row">
						<dt>
							<?php if ( '' !== $frave_guide ) : ?>
								<a class="family-chip" href="<?php echo esc_url( $frave_guide . '#familia-' . $frave_family_slug ); ?>" style="--family-tone: <?php echo esc_attr( $frave_family['tone'] ); ?>"><?php echo esc_html( $frave_family['name'] ); ?></a>
							<?php else : ?>
								<span class="family-chip" style="--family-tone: <?php echo esc_attr( $frave_family['tone'] ); ?>"><?php echo esc_html( $frave_family['name'] ); ?></span>
							<?php endif; ?>
						</dt>
						<dd><?php echo esc_html( implode( ', ', $frave_names ) ); ?></dd>
					</div>
				<?php endforeach; ?>
			</dl>
			<?php if ( '' !== $frave_guide ) : ?>
				<a class="link-arrow" href="<?php echo esc_url( $frave_guide ); ?>"><?php esc_html_e( 'Conoce las familias olfativas', 'frave' ); ?><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></a>
			<?php endif; ?>
		</div>
	</div>
</section>
