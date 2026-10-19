<?php
/**
 * Template Name: Familias olfativas
 * Template Post Type: page
 *
 * Guide of olfactory families for clients who make products: hero, what a family is, an
 * index, one block per family (notes, what it conveys, products it suits, its fragrances
 * in the catalog and a quote), the families for each application and a closing call to
 * action. Copy: inc/fragrance-families.php.
 */

get_header();
?>
<main id="primary" class="site-main families-page">
	<?php
	while ( have_posts() ) :
		the_post();
		$frave_line     = frave_line_term( 'fragancias' );
		$frave_quote    = $frave_line ? frave_line_quote_link( $frave_line ) : frave_company_action();
		$frave_families = frave_fragrance_families();
		$frave_hero_img = frave_fragrance_family_image_base( 'familias-olfativas' );
		$frave_line_url = frave_line_url( 'fragancias' );

		get_template_part(
			'template-parts/solutions/hero',
			null,
			array(
				'eyebrow'   => __( 'Guía de fragancias', 'frave' ),
				'title'     => __( 'Familias *olfativas*', 'frave' ),
				'lead'      => __( 'Las nueve familias de nuestro catálogo: cómo huelen, qué transmiten y en qué productos funcionan mejor, para que elijas la fragancia de tu marca con criterio.', 'frave' ),
				'action'    => $frave_quote,
				'secondary' => array( __( 'Explorar las familias', 'frave' ), '#familias' ),
				'image'     => '' !== $frave_hero_img ? $frave_hero_img . '.webp' : 'assets/media/soluciones/perfumeria.webp',
			)
		);
		?>
		<section class="section company-statement" aria-labelledby="families-intro-title">
			<div class="container">
				<div class="statement">
					<h2 class="statement__title" id="families-intro-title" data-reveal="up"><?php echo frave_emphasis( __( 'Una familia olfativa reúne fragancias con *un mismo carácter*.', 'frave' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
					<div class="statement__text" data-reveal="up">
						<p><?php esc_html_e( 'Agrupar las fragancias por familias ayuda a describirlas con un lenguaje común y a decidir más rápido: si tu público prefiere aromas frescos, cálidos o dulces, la familia te dice por dónde empezar.', 'frave' ); ?></p>
						<p><?php esc_html_e( 'No existe una clasificación única en el mundo y muchas fragancias combinan rasgos de varias familias. Aquí usamos las mismas nueve con las que puedes filtrar nuestro catálogo.', 'frave' ); ?></p>
						<?php if ( '' !== $frave_line_url ) : ?>
							<p><a class="link-arrow" href="<?php echo esc_url( $frave_line_url ); ?>"><?php esc_html_e( 'Conoce la línea Fragancias', 'frave' ); ?><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></a></p>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>

		<section class="section families" id="familias" aria-labelledby="families-title">
			<div class="container">
				<div class="section-head" data-reveal="up">
					<p class="eyebrow"><?php esc_html_e( 'Las familias', 'frave' ); ?></p>
					<h2 id="families-title"><?php echo frave_emphasis( __( 'Nueve familias, *muchas posibilidades*', 'frave' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
				</div>
				<nav class="families-index" aria-label="<?php esc_attr_e( 'Familias olfativas', 'frave' ); ?>" data-reveal="up">
					<ul>
						<?php foreach ( $frave_families as $frave_slug => $frave_family ) : ?>
							<li><a class="family-chip" href="#familia-<?php echo esc_attr( $frave_slug ); ?>" style="--family-tone: <?php echo esc_attr( $frave_family['tone'] ); ?>"><?php echo esc_html( $frave_family['name'] ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</nav>

				<div class="families__list">
					<?php
					$frave_number = 0;
					foreach ( $frave_families as $frave_slug => $frave_family ) :
						++$frave_number;
						$frave_base    = frave_fragrance_family_image_base( $frave_slug );
						$frave_catalog = frave_fragrance_family_catalog_url( $frave_family['name'] );
						?>
						<article class="family" id="familia-<?php echo esc_attr( $frave_slug ); ?>" style="--family-tone: <?php echo esc_attr( $frave_family['tone'] ); ?>" aria-labelledby="familia-<?php echo esc_attr( $frave_slug ); ?>-title">
							<div class="family__media media-frame shape-arch<?php echo '' === $frave_base ? ' family__media--placeholder' : ''; ?>" data-reveal="up">
								<?php if ( '' !== $frave_base ) : ?>
									<?php
									echo frave_theme_image( // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper.
										$frave_base,
										1080,
										1350,
										640,
										array( 'sizes' => '(max-width: 48rem) 90vw, 26rem' )
									);
									?>
								<?php else : ?>
									<span class="family__glyph" aria-hidden="true"><?php echo frave_icon( 'fragrance' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
								<?php endif; ?>
							</div>
							<div class="family__body" data-reveal="up">
								<p class="family__number"><?php echo esc_html( sprintf( '%02d', $frave_number ) ); ?></p>
								<h3 class="family__title" id="familia-<?php echo esc_attr( $frave_slug ); ?>-title"><?php echo esc_html( $frave_family['name'] ); ?></h3>
								<p class="family__tagline"><?php echo esc_html( $frave_family['tagline'] ); ?></p>
								<p class="family__text"><?php echo esc_html( $frave_family['text'] ); ?></p>
								<dl class="family__facts">
									<div>
										<dt><?php esc_html_e( 'Notas típicas', 'frave' ); ?></dt>
										<dd>
											<ul class="family__notes">
												<?php foreach ( $frave_family['notes'] as $frave_note ) : ?>
													<li><?php echo esc_html( $frave_note ); ?></li>
												<?php endforeach; ?>
											</ul>
										</dd>
									</div>
									<div>
										<dt><?php esc_html_e( 'Transmite', 'frave' ); ?></dt>
										<dd><?php echo esc_html( $frave_family['conveys'] ); ?></dd>
									</div>
									<div>
										<dt><?php esc_html_e( 'Ideal para', 'frave' ); ?></dt>
										<dd>
											<ul class="family__uses">
												<?php foreach ( $frave_family['uses'] as $frave_use ) : ?>
													<li><?php echo esc_html( $frave_use ); ?></li>
												<?php endforeach; ?>
											</ul>
										</dd>
									</div>
								</dl>
								<div class="family__actions">
									<?php if ( '' !== $frave_catalog ) : ?>
										<a class="link-arrow" href="<?php echo esc_url( $frave_catalog ); ?>">
											<?php
											/* translators: %s: family name in plural, e.g. «cítricas». */
											echo esc_html( sprintf( __( 'Ver fragancias %s', 'frave' ), $frave_family['plural'] ) );
											?>
											<?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
										</a>
									<?php endif; ?>
									<?php if ( $frave_quote ) : ?>
										<a class="link-arrow<?php echo '' !== $frave_catalog ? ' link-arrow--muted' : ''; ?>" href="<?php echo esc_url( $frave_quote['url'] ); ?>"<?php echo $frave_quote['external'] ? ' target="_blank" rel="noopener"' : ''; ?>>
											<?php
											/* translators: %s: family name in plural, e.g. «cítricas». */
											echo esc_html( sprintf( __( 'Cotizar fragancias %s', 'frave' ), $frave_family['plural'] ) );
											?>
											<?php if ( $frave_quote['external'] ) : ?>
												<span class="screen-reader-text"><?php esc_html_e( '(se abre en una nueva pestaña)', 'frave' ); ?></span>
											<?php endif; ?>
											<?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
										</a>
									<?php endif; ?>
								</div>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<section class="section section--surface families-matrix" id="por-producto" aria-labelledby="families-matrix-title">
			<div class="container">
				<div class="section-head" data-reveal="up">
					<p class="eyebrow"><?php esc_html_e( 'Por producto', 'frave' ); ?></p>
					<h2 id="families-matrix-title"><?php echo frave_emphasis( __( '¿Qué familia elegir para *tu producto*?', 'frave' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper. ?></h2>
					<p class="lead"><?php esc_html_e( 'Un punto de partida según la aplicación. La elección final depende de tu público, tu base y tu marca: cada fragancia se valida con pruebas en el producto.', 'frave' ); ?></p>
				</div>
				<ul class="families-matrix__list" data-reveal-group>
					<?php foreach ( frave_fragrance_family_matrix() as $frave_application => $frave_slugs ) : ?>
						<li class="families-matrix__row">
							<h3 class="families-matrix__name"><?php echo esc_html( $frave_application ); ?></h3>
							<ul class="families-matrix__families">
								<?php foreach ( $frave_slugs as $frave_slug ) : ?>
									<?php if ( isset( $frave_families[ $frave_slug ] ) ) : ?>
										<li><a class="family-chip" href="#familia-<?php echo esc_attr( $frave_slug ); ?>" style="--family-tone: <?php echo esc_attr( $frave_families[ $frave_slug ]['tone'] ); ?>"><?php echo esc_html( $frave_families[ $frave_slug ]['name'] ); ?></a></li>
									<?php endif; ?>
								<?php endforeach; ?>
							</ul>
						</li>
					<?php endforeach; ?>
				</ul>
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

		get_template_part(
			'template-parts/components/cta-panel',
			null,
			array(
				'title'    => __( '¿No sabes qué familia *elegir*?', 'frave' ),
				'text'     => __( 'Cuéntanos tu producto, tu público y tu base: te proponemos fragancias de distintas familias para que las compares.', 'frave' ),
				'action'   => $frave_quote,
				'whatsapp' => frave_whatsapp_url( __( 'Hola, quisiera asesoría para elegir la familia olfativa de mi producto.', 'frave' ) ),
			)
		);
	endwhile;
	?>
</main>
<?php
get_footer();
