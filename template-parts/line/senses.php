<?php
/**
 * Sabores line: «Propiedades organolépticas», a short guide right after the applications
 * band. Four senses (sight, smell, taste, touch and hearing) as cards with what each one
 * perceives and an example, and a closing note on where a flavoring helps. Each card opens
 * its section of the article on the topic (frave_line_properties_article()); without the
 * article published, the cards are plain and the closing note has no link.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( 'sabores' !== ( $args['slug'] ?? '' ) ) {
	return;
}

$frave_article = frave_line_properties_article( 'sabores' );
$frave_senses  = array(
	array(
		'icon'    => 'eye',
		'sense'   => __( 'Vista', 'frave' ),
		'title'   => __( 'Color y aspecto', 'frave' ),
		'text'    => __( 'La primera impresión: el color anticipa el sabor y la frescura, y también cuentan el brillo y la uniformidad.', 'frave' ),
		'example' => __( 'El dorado de un panetón', 'frave' ),
		'section' => 'vista-color-y-aspecto',
	),
	array(
		'icon'    => 'aroma',
		'sense'   => __( 'Olfato', 'frave' ),
		'title'   => __( 'Aroma', 'frave' ),
		'text'    => __( 'Se percibe al acercar el alimento y desde la boca, por vía retronasal: buena parte de lo que llamamos sabor lo percibe la nariz.', 'frave' ),
		'example' => __( 'La vainilla al abrir un yogur', 'frave' ),
		'section' => 'olfato-el-aroma',
	),
	array(
		'icon'    => 'spoon',
		'sense'   => __( 'Gusto', 'frave' ),
		'title'   => __( 'Sabores básicos', 'frave' ),
		'text'    => __( 'Dulce, salado, ácido, amargo y umami, junto con sensaciones como el picor o el frescor de la menta.', 'frave' ),
		'example' => __( 'La acidez de un néctar de maracuyá', 'frave' ),
		'section' => 'gusto-los-sabores-basicos',
	),
	array(
		'icon'    => 'sound',
		'sense'   => __( 'Tacto y oído', 'frave' ),
		'title'   => __( 'Textura', 'frave' ),
		'text'    => __( 'Lo cremoso, lo crocante, la viscosidad o el gas de una bebida; incluso el sonido al morder cambia la percepción.', 'frave' ),
		'example' => __( 'Lo crocante de un snack', 'frave' ),
		'section' => 'tacto-y-oido-la-textura',
	),
);
?>
<section class="section line-senses" aria-labelledby="line-senses-title">
	<div class="container">
		<div class="section-head" data-reveal="up">
			<p class="eyebrow"><?php esc_html_e( 'Propiedades organolépticas', 'frave' ); ?></p>
			<h2 id="line-senses-title"><?php echo frave_emphasis( __( 'Un buen sabor *se percibe con todos los sentidos*', 'frave' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
			<p class="lead"><?php esc_html_e( 'Son los atributos de un alimento que se perciben con los sentidos. Juntos forman la experiencia que tu cliente asocia con tu producto y con tu marca.', 'frave' ); ?></p>
		</div>
		<ol class="line-senses__list" data-reveal-group>
			<?php foreach ( $frave_senses as $frave_sense ) : ?>
				<li class="card line-sense<?php echo '' !== $frave_article ? ' card--interactive' : ''; ?>">
					<span class="icon-tile" aria-hidden="true"><?php echo frave_icon( $frave_sense['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
					<p class="line-sense__sense"><?php echo esc_html( $frave_sense['sense'] ); ?></p>
					<h3 class="line-sense__title">
						<?php if ( '' !== $frave_article ) : ?>
							<a class="card__link" href="<?php echo esc_url( $frave_article . '#' . $frave_sense['section'] ); ?>"><?php echo esc_html( $frave_sense['title'] ); ?></a>
						<?php else : ?>
							<?php echo esc_html( $frave_sense['title'] ); ?>
						<?php endif; ?>
					</h3>
					<p class="line-sense__text"><?php echo esc_html( $frave_sense['text'] ); ?></p>
					<p class="line-sense__example"><span><?php esc_html_e( 'Ejemplo:', 'frave' ); ?></span> <?php echo esc_html( $frave_sense['example'] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ol>
		<div class="line-senses__note" data-reveal="up">
			<span class="icon-tile" aria-hidden="true"><?php echo frave_icon( 'flavor' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
			<p>
				<strong><?php esc_html_e( 'Dónde entra el saborizante.', 'frave' ); ?></strong>
				<?php esc_html_e( 'Refuerza o corrige el aroma y el sabor que el proceso debilita, da identidad a tu receta y mantiene la consistencia entre lotes.', 'frave' ); ?>
			</p>
			<?php if ( '' !== $frave_article ) : ?>
				<a class="link-arrow" href="<?php echo esc_url( $frave_article ); ?>"><?php esc_html_e( 'Leer la guía completa', 'frave' ); ?><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></a>
			<?php endif; ?>
		</div>
	</div>
</section>
