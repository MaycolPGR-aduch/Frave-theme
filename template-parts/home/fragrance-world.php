<?php
/**
 * «El mundo de las fragancias»: four ways into Frave's fragrance guide, each with a small
 * drawing made from what the site already shows: the olfactory families (a ring in their
 * tones), the pyramid (its three levels), the perfumery tops (their ranking) and the notes
 * (some note icons). A way in shows only while its page exists. Copy: ACF group "Inicio
 * Frave" (defaults in inc/home.php).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_families = frave_fragrance_families();
$frave_ways     = array();

$frave_guide = frave_fragrance_guide_url();
if ( '' !== $frave_guide ) {
	// The ring: one equal slice per family, in its tone.
	$frave_step  = 360 / max( 1, count( $frave_families ) );
	$frave_stops = array();
	foreach ( array_values( $frave_families ) as $frave_index => $frave_family ) {
		$frave_stops[] = sprintf( '%1$s %2$sdeg %3$sdeg', $frave_family['tone'], round( $frave_index * $frave_step, 2 ), round( ( $frave_index + 1 ) * $frave_step, 2 ) );
	}
	$frave_ways[] = array(
		'title' => __( 'Familias olfativas', 'frave' ),
		/* translators: %d: number of olfactory families. */
		'text'  => sprintf( __( 'Las %d familias y qué transmite cada una.', 'frave' ), count( $frave_families ) ),
		'link'  => __( 'Ver la guía', 'frave' ),
		'url'   => $frave_guide,
		'art'   => '<span class="world-art__wheel" style="background: conic-gradient(' . esc_attr( implode( ', ', $frave_stops ) ) . ')"></span>',
	);
}

$frave_pyramid_url = frave_fragrance_pyramid_url();
if ( '' !== $frave_pyramid_url ) {
	$frave_levels = '';
	foreach ( frave_fragrance_pyramid() as $frave_level ) {
		$frave_levels .= '<span style="--level-tone: ' . esc_attr( $frave_level['accent'] ) . '"></span>';
	}
	$frave_ways[] = array(
		'title' => __( 'Pirámide olfativa', 'frave' ),
		'text'  => __( 'Cómo cambia un aroma: salida, corazón y fondo.', 'frave' ),
		'link'  => __( 'Ver la pirámide', 'frave' ),
		'url'   => $frave_pyramid_url,
		'art'   => '<span class="world-art__pyramid">' . $frave_levels . '</span>',
	);
}

$frave_tops = function_exists( 'frave_top_categories' ) ? frave_top_categories() : array();
if ( $frave_tops ) {
	$frave_ways[] = array(
		'title' => __( 'Top de perfumería', 'frave' ),
		'text'  => __( 'Los perfumes que marcan tendencia en cada estilo.', 'frave' ),
		'link'  => __( 'Ver el top', 'frave' ),
		'url'   => frave_term_detail_url( $frave_tops[0] ) . '#top-perfumeria',
		'art'   => '<span class="world-art__rank"><span>1</span><span>2</span><span>3</span></span>',
	);
}

// The notes: the perfumery style with hallmarks first (perfumería árabe: oud, rosa…).
$frave_seals = function_exists( 'frave_style_seals' ) ? frave_style_seals() : array();
foreach ( $frave_seals as $frave_slug => $frave_note_slugs ) {
	$frave_category = get_term_by( 'slug', $frave_slug, 'product_cat' );
	if ( ! $frave_category instanceof WP_Term ) {
		continue;
	}
	$frave_notes = frave_fragrance_notes();
	$frave_icons = '';
	foreach ( array_slice( $frave_note_slugs, 0, 4 ) as $frave_note_slug ) {
		if ( isset( $frave_notes[ $frave_note_slug ] ) ) {
			$frave_icons .= '<span>' . frave_note_icon( $frave_notes[ $frave_note_slug ]['icon'] ) . '</span>';
		}
	}
	$frave_ways[] = array(
		'title' => __( 'Notas e ingredientes', 'frave' ),
		'text'  => __( 'Oud, vainilla, rosa de Taif… las notas que dan carácter a cada estilo.', 'frave' ),
		'link'  => __( 'Ver las notas', 'frave' ),
		'url'   => frave_term_detail_url( $frave_category ) . '#ingredientes',
		'art'   => '<span class="world-art__notes">' . $frave_icons . '</span>',
	);
	break;
}

if ( ! $frave_ways ) {
	return;
}
?>
<section class="section home-world" aria-labelledby="home-world-title">
	<div class="container">
		<div class="home-world__panel">
			<div class="section-head" data-reveal="up">
				<p class="eyebrow"><?php echo esc_html( frave_home_text( 'frave_home_world_eyebrow' ) ); ?></p>
				<h2 id="home-world-title"><?php echo frave_emphasis( frave_home_text( 'frave_home_world_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
				<p class="lead"><?php echo esc_html( frave_home_text( 'frave_home_world_text' ) ); ?></p>
			</div>
			<ul class="world-ways" data-reveal-group>
				<?php foreach ( $frave_ways as $frave_way ) : ?>
					<li class="world-way">
						<span class="world-way__art" aria-hidden="true"><?php echo $frave_way['art']; // phpcs:ignore WordPress.Security.EscapeOutput -- built and escaped above. ?></span>
						<h3 class="world-way__title"><a href="<?php echo esc_url( $frave_way['url'] ); ?>"><?php echo esc_html( $frave_way['title'] ); ?></a></h3>
						<p class="world-way__text"><?php echo esc_html( $frave_way['text'] ); ?></p>
						<span class="link-arrow world-way__link" aria-hidden="true"><?php echo esc_html( $frave_way['link'] ); ?><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>
