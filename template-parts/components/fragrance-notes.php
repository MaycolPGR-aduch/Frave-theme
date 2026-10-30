<?php
/**
 * The notes of a perfumery subcategory (inc/fragrance-notes.php), as a pyramid: one band per
 * level (salida, corazón, fondo) with its notes as chips, the hallmarks of the style first.
 * A chip shows its note in the card under the bands (assets/src/js/modules/notes.js), and
 * «Sellos del estilo» keeps only the hallmarks. Without JavaScript the chips and the first
 * note's card show. Nothing when the category has no notes.
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
$frave_tiers = frave_category_notes( $frave_category );
if ( ! $frave_tiers ) {
	return;
}
$frave_families = frave_fragrance_families();
$frave_guide    = frave_fragrance_guide_url();
$frave_pyramid  = frave_fragrance_pyramid();
$frave_levels   = array(
	'top'   => array( __( 'Salida', 'frave' ), __( 'Los primeros minutos', 'frave' ), $frave_pyramid['salida'] ?? array() ),
	'heart' => array( __( 'Corazón', 'frave' ), __( 'Las primeras horas', 'frave' ), $frave_pyramid['corazon'] ?? array() ),
	'base'  => array( __( 'Fondo', 'frave' ), __( 'Lo que perdura', 'frave' ), $frave_pyramid['fondo'] ?? array() ),
);
$frave_all      = array_merge( ...array_values( $frave_tiers ) );
$frave_seals    = count( array_filter( $frave_all, static fn( array $item ): bool => $item['seal'] ) );

// The card starts with the first hallmark of the base, or else the first note.
$frave_first = null;
foreach ( array( 'base', 'heart', 'top' ) as $frave_level ) {
	foreach ( $frave_tiers[ $frave_level ] ?? array() as $frave_item ) {
		if ( $frave_item['seal'] ) {
			$frave_first = $frave_item + array( 'level' => $frave_level );
			break 2;
		}
	}
}
if ( ! $frave_first ) {
	$frave_level = (string) array_key_first( $frave_tiers );
	$frave_first = $frave_tiers[ $frave_level ][0] + array( 'level' => $frave_level );
}
$frave_style = mb_strtolower( mb_substr( $frave_category->name, 0, 1 ) ) . mb_substr( $frave_category->name, 1 );
$frave_quote = frave_line_quote_link( $frave_category );

/**
 * What the card says of a note (also written into each chip for notes.js).
 *
 * @param array<string, mixed> $item Note of frave_category_notes().
 * @return array{name: string, family: string, url: string, tone: string, level: string, text: string, perfumes: string, seal: bool}
 */
$frave_card  = static function ( array $item, string $level ) use ( $frave_families, $frave_guide, $frave_levels ): array {
	$family = $item['note']['family'];
	return array(
		'name'     => $item['note']['name'],
		'family'   => $frave_families[ $family ]['name'] ?? '',
		'url'      => '' !== $frave_guide ? $frave_guide . '#familia-' . $family : '',
		'tone'     => $frave_families[ $family ]['tone'] ?? '#f1e6d8',
		'level'    => $frave_levels[ $level ][0],
		'text'     => $item['note']['text'],
		'perfumes' => implode( ', ', $item['perfumes'] ),
		'seal'     => $item['seal'],
	);
};
$frave_shown = $frave_card( $frave_first, $frave_first['level'] );
?>
<section class="notes-section" id="ingredientes" aria-labelledby="ingredientes-title" data-notes data-frave-place="ingredientes">
	<div class="notes-section__head">
		<p class="eyebrow"><?php esc_html_e( 'Ingredientes', 'frave' ); ?></p>
		<?php /* translators: %s: perfumery style, in lower case (perfumería árabe). */ ?>
		<h2 id="ingredientes-title"><?php echo frave_emphasis( sprintf( __( 'Las notas de la *%s*', 'frave' ), $frave_style ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
		<p class="notes-section__lead"><?php esc_html_e( 'Los ingredientes que dan carácter a este estilo, ordenados como se perciben: de la salida al fondo. Toca uno para conocerlo.', 'frave' ); ?></p>
		<?php if ( $frave_seals > 0 ) : ?>
			<div class="notes-filter" role="group" aria-label="<?php esc_attr_e( 'Qué notas mostrar', 'frave' ); ?>">
				<?php /* translators: %d: number of notes. */ ?>
				<button type="button" class="notes-filter__button" data-notes-filter="all" aria-pressed="true"><?php echo esc_html( sprintf( __( 'Todas (%d)', 'frave' ), count( $frave_all ) ) ); ?></button>
				<button type="button" class="notes-filter__button" data-notes-filter="seals" aria-pressed="false">
					<span class="notes-seal-mark" aria-hidden="true"></span>
					<?php /* translators: %d: number of hallmark notes. */ ?>
					<?php echo esc_html( sprintf( __( 'Sellos del estilo (%d)', 'frave' ), $frave_seals ) ); ?>
				</button>
			</div>
		<?php endif; ?>
	</div>

	<div class="notes-layout">
	<div class="notes-pyramid">
		<?php foreach ( $frave_levels as $frave_level => list( $frave_label, $frave_time, $frave_tone ) ) : ?>
			<?php
			if ( empty( $frave_tiers[ $frave_level ] ) ) {
				continue;
			}
			$frave_has_seal = (bool) array_filter( $frave_tiers[ $frave_level ], static fn( array $item ): bool => $item['seal'] );
			?>
			<div class="notes-tier notes-tier--<?php echo esc_attr( $frave_level ); ?><?php echo $frave_has_seal ? ' has-seal' : ''; ?>" style="--level-tone: <?php echo esc_attr( $frave_tone['tone'] ?? '#f1e6d8' ); ?>; --level-accent: <?php echo esc_attr( $frave_tone['accent'] ?? '#e2d3bf' ); ?>">
				<h3 class="notes-tier__label">
					<span class="notes-tier__shape" aria-hidden="true"></span>
					<span><?php echo esc_html( $frave_label ); ?><small><?php echo esc_html( $frave_time ); ?></small></span>
				</h3>
				<ul class="notes-chips">
					<?php foreach ( $frave_tiers[ $frave_level ] as $frave_item ) : ?>
						<?php $frave_data = $frave_card( $frave_item, $frave_level ); ?>
						<li class="notes-chips__item<?php echo $frave_item['seal'] ? ' is-seal' : ''; ?>">
							<button type="button" class="note-chip" aria-controls="ingredientes-card" aria-pressed="<?php echo $frave_item['slug'] === $frave_first['slug'] ? 'true' : 'false'; ?>"
								data-name="<?php echo esc_attr( $frave_data['name'] ); ?>"
								data-family="<?php echo esc_attr( $frave_data['family'] ); ?>"
								data-url="<?php echo esc_url( $frave_data['url'] ); ?>"
								data-tone="<?php echo esc_attr( $frave_data['tone'] ); ?>"
								data-level="<?php echo esc_attr( $frave_data['level'] ); ?>"
								data-text="<?php echo esc_attr( $frave_data['text'] ); ?>"
								data-perfumes="<?php echo esc_attr( $frave_data['perfumes'] ); ?>"
								<?php echo $frave_item['seal'] ? 'data-seal' : ''; ?>>
								<span class="note-chip__icon" style="--family-tone: <?php echo esc_attr( $frave_data['tone'] ); ?>"><?php echo frave_note_icon( $frave_item['note']['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
								<span class="note-chip__name"><?php echo esc_html( $frave_data['name'] ); ?></span>
								<?php if ( $frave_item['seal'] ) : ?>
									<span class="notes-seal-mark" aria-hidden="true"></span>
									<span class="screen-reader-text"><?php esc_html_e( '(sello del estilo)', 'frave' ); ?></span>
								<?php endif; ?>
							</button>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endforeach; ?>
	</div>

	<div class="notes-card" id="ingredientes-card" aria-live="polite">
		<span class="notes-card__icon" data-card-icon style="--family-tone: <?php echo esc_attr( $frave_shown['tone'] ); ?>"><?php echo frave_note_icon( $frave_first['note']['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
		<div class="notes-card__body">
			<p class="notes-card__meta">
				<span data-card-level><?php echo esc_html( $frave_shown['level'] ); ?></span> ·
				<a data-card-family href="<?php echo esc_url( $frave_shown['url'] ); ?>"<?php echo '' === $frave_shown['url'] ? ' hidden' : ''; ?>><?php echo esc_html( $frave_shown['family'] ); ?></a>
				<span class="notes-card__seal" data-card-seal<?php echo $frave_shown['seal'] ? '' : ' hidden'; ?>><?php esc_html_e( 'Sello del estilo', 'frave' ); ?></span>
			</p>
			<h3 class="notes-card__name" data-card-name><?php echo esc_html( $frave_shown['name'] ); ?></h3>
			<p class="notes-card__text" data-card-text><?php echo esc_html( $frave_shown['text'] ); ?></p>
			<p class="notes-card__perfumes" data-card-perfumes-wrap<?php echo '' === $frave_shown['perfumes'] ? ' hidden' : ''; ?>>
				<?php esc_html_e( 'Presente en:', 'frave' ); ?> <strong data-card-perfumes><?php echo esc_html( $frave_shown['perfumes'] ); ?></strong>
			</p>
		</div>
	</div>
	</div>

	<div class="notes-section__foot">
		<p><?php esc_html_e( 'Notas de los perfumes del top de esta página y de la tradición del estilo. Te ayudamos a crear un perfil con ellas.', 'frave' ); ?></p>
		<?php if ( $frave_quote ) : ?>
			<a class="link-arrow" href="<?php echo esc_url( $frave_quote['url'] ); ?>"<?php echo ! empty( $frave_quote['external'] ) ? ' target="_blank" rel="noopener"' : ''; ?>><?php esc_html_e( 'Cuéntanos qué notas buscas', 'frave' ); ?><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></a>
		<?php endif; ?>
	</div>
</section>
