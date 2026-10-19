<?php
/**
 * Template Name: Pirámide olfativa
 * Template Post Type: page
 *
 * The olfactory pyramid applied to the client's product: hero, why a fragrance changes over
 * time, the interactive pyramid, a timeline of the three levels, what the pyramid means for
 * each kind of product, the scent-strip test, the families guide and a closing call to
 * action. Copy: inc/fragrance-pyramid.php.
 */

get_header();
?>
<main id="primary" class="site-main pyramid-page">
	<?php
	while ( have_posts() ) :
		the_post();
		$frave_line   = frave_line_term( 'fragancias' );
		$frave_quote  = $frave_line ? frave_line_quote_link( $frave_line ) : frave_company_action();
		$frave_levels = frave_fragrance_pyramid();
		$frave_guide  = frave_fragrance_guide_url();
		// Its own image when there is one; meanwhile a still life of the families series.
		$frave_image = file_exists( FRAVE_THEME_DIR . '/assets/media/piramide/piramide-olfativa-640.webp' ) ? 'assets/media/piramide/piramide-olfativa.webp' : 'assets/media/familias/oriental.webp';

		get_template_part(
			'template-parts/solutions/hero',
			null,
			array(
				'eyebrow'   => __( 'Guía de fragancias', 'frave' ),
				'title'     => __( 'La pirámide *olfativa*', 'frave' ),
				'lead'      => __( 'Cómo evoluciona una fragancia desde el primer instante hasta horas después, y qué significa para el producto que fabricas.', 'frave' ),
				'action'    => $frave_quote,
				'secondary' => array( __( 'Ver la pirámide', 'frave' ), '#piramide' ),
				'image'     => $frave_image,
			)
		);
		?>
		<section class="section company-statement" aria-labelledby="pyramid-intro-title">
			<div class="container">
				<div class="statement">
					<h2 class="statement__title" id="pyramid-intro-title" data-reveal="up"><?php echo frave_emphasis( __( 'Una fragancia no huele igual al minuto que *a las cuatro horas*.', 'frave' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
					<div class="statement__text" data-reveal="up">
						<p><?php esc_html_e( 'Cada ingrediente se evapora a su propio ritmo: los más ligeros desaparecen en minutos y los más densos permanecen durante horas. Por eso los perfumistas ordenan las notas en tres niveles, de la cima a la base de una pirámide.', 'frave' ); ?></p>
						<p><?php esc_html_e( 'No son etapas cerradas, sino protagonismos que se relevan. Entenderlos te ayuda a elegir la fragancia adecuada, a evaluar muestras y a saber qué esperar en tu producto.', 'frave' ); ?></p>
						<?php if ( '' !== $frave_guide ) : ?>
							<p><a class="link-arrow" href="<?php echo esc_url( $frave_guide ); ?>"><?php esc_html_e( 'Conoce también las familias olfativas', 'frave' ); ?><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></a></p>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>

		<?php get_template_part( 'template-parts/components/fragrance-pyramid' ); ?>

		<section class="section section--surface pyramid-timeline" aria-labelledby="pyramid-timeline-title">
			<div class="container">
				<div class="section-head" data-reveal="up">
					<p class="eyebrow"><?php esc_html_e( 'En el tiempo', 'frave' ); ?></p>
					<h2 id="pyramid-timeline-title"><?php echo frave_emphasis( __( 'Así se *relevan* las notas', 'frave' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
				</div>
				<figure class="pyramid-timeline__chart" data-reveal="up">
					<ol class="pyramid-timeline__rows">
						<?php foreach ( $frave_levels as $frave_slug => $frave_level ) : ?>
							<li class="pyramid-timeline__row pyramid-timeline__row--<?php echo esc_attr( $frave_slug ); ?>" style="--level-tone: <?php echo esc_attr( $frave_level['tone'] ); ?>; --level-accent: <?php echo esc_attr( $frave_level['accent'] ); ?>">
								<span class="pyramid-timeline__label"><strong><?php echo esc_html( $frave_level['short'] ); ?></strong> <?php echo esc_html( $frave_level['time'] ); ?></span>
								<span class="pyramid-timeline__track" aria-hidden="true"><span class="pyramid-timeline__bar"></span></span>
							</li>
						<?php endforeach; ?>
					</ol>
					<div class="pyramid-timeline__scale" aria-hidden="true">
						<span><?php esc_html_e( '0 min', 'frave' ); ?></span>
						<span><?php esc_html_e( '15 min', 'frave' ); ?></span>
						<span><?php esc_html_e( '1 h', 'frave' ); ?></span>
						<span><?php esc_html_e( '4 h', 'frave' ); ?></span>
						<span><?php esc_html_e( '8 h o más', 'frave' ); ?></span>
					</div>
					<figcaption><?php esc_html_e( 'Intensidad orientativa de cada nivel. Los tiempos cambian con la concentración, la base del producto y la piel.', 'frave' ); ?></figcaption>
				</figure>
			</div>
		</section>

		<section class="section pyramid-products" aria-labelledby="pyramid-products-title">
			<div class="container">
				<div class="section-head" data-reveal="up">
					<p class="eyebrow"><?php esc_html_e( 'Aplicada', 'frave' ); ?></p>
					<h2 id="pyramid-products-title"><?php echo frave_emphasis( __( 'Qué significa para *tu producto*', 'frave' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
					<p class="lead"><?php esc_html_e( 'La misma pirámide se vive distinto en un perfume, una crema, un detergente o una vela.', 'frave' ); ?></p>
				</div>
				<ul class="pyramid-products__list" data-reveal-group>
					<?php foreach ( frave_fragrance_pyramid_products() as $frave_product ) : ?>
						<li class="card pyramid-product">
							<span class="icon-tile"><?php echo frave_icon( $frave_product['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
							<h3><?php echo esc_html( $frave_product['title'] ); ?></h3>
							<p><?php echo esc_html( $frave_product['text'] ); ?></p>
							<p class="pyramid-product__key"><strong><?php esc_html_e( 'Clave:', 'frave' ); ?></strong> <?php echo esc_html( $frave_product['key'] ); ?></p>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</section>

		<section class="section section--sand pyramid-test" aria-labelledby="pyramid-test-title">
			<div class="container">
				<div class="section-head" data-reveal="up">
					<p class="eyebrow"><?php esc_html_e( 'Evalúa una muestra', 'frave' ); ?></p>
					<h2 id="pyramid-test-title"><?php echo frave_emphasis( __( 'La prueba de la tira, *en cuatro momentos*', 'frave' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
					<p class="lead"><?php esc_html_e( 'Una tira olfativa te deja ver la pirámide completa antes de decidir. Huele siempre sin otros perfumes cerca y prueba después en la base real de tu producto.', 'frave' ); ?></p>
				</div>
				<ol class="pyramid-test__steps" data-reveal-group>
					<?php foreach ( frave_fragrance_pyramid_test_steps() as $frave_index => $frave_step ) : ?>
						<li class="pyramid-test__step">
							<span class="pyramid-test__number" aria-hidden="true"><?php echo esc_html( (string) ( $frave_index + 1 ) ); ?></span>
							<p class="pyramid-test__time"><?php echo esc_html( $frave_step['time'] ); ?></p>
							<h3><?php echo esc_html( $frave_step['title'] ); ?></h3>
							<p><?php echo esc_html( $frave_step['text'] ); ?></p>
						</li>
					<?php endforeach; ?>
				</ol>
			</div>
		</section>

		<?php
		if ( '' !== trim( get_the_content() ) ) :
			?>
			<section class="section company-content">
				<div class="container content-container">
					<div class="entry-content"><?php the_content(); ?></div>
				</div>
			</section>
			<?php
		endif;

		get_template_part( 'template-parts/line/families', null, array( 'slug' => 'fragancias' ) );

		get_template_part(
			'template-parts/components/cta-panel',
			null,
			array(
				'title'    => __( '¿Buscas una fragancia que *dure* en tu producto?', 'frave' ),
				'text'     => __( 'Cuéntanos tu producto y su base: te ayudamos a elegir y evaluar fragancias con la salida, el corazón y el fondo que necesitas.', 'frave' ),
				'action'   => $frave_quote,
				'whatsapp' => frave_whatsapp_url( __( 'Hola, quisiera asesoría para elegir una fragancia para mi producto.', 'frave' ) ),
			)
		);
	endwhile;
	?>
</main>
<?php
get_footer();
