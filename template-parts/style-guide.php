<?php
/**
 * Internal style guide of the design system (inc/style-guide.php). Placeholder visuals
 * and sample copy only; real photos and texts arrive with the content of each phase.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$frave_colors = array(
	array( 'Arena', '#f1e6d8', __( 'Banda de páginas y secciones destacadas (el beige de las fotos). Frave no usa negro.', 'frave' ), __( 'Tinta 12,9:1 · texto secundario #5c5248 6,2:1 · naranja #a83200 5,5:1', 'frave' ) ),
	array( 'Azul marino', '#1d2b4f', __( 'Pie de página y botones oscuros.', 'frave' ), __( 'Blanco 14:1 · naranja claro #ff8a5c 6:1', 'frave' ) ),
	array( 'Ink', '#202329', __( 'Texto principal.', 'frave' ), __( 'Sobre blanco 15,9:1', 'frave' ) ),
	array( 'Naranja Frave', '#fc4c02', __( 'Acento: líneas, iconos, píldoras y títulos grandes.', 'frave' ), __( 'Sobre blanco 3,4:1 (solo texto grande)', 'frave' ) ),
	array( 'Primario', '#c43b00', __( 'Botones con texto blanco, enlaces y textos de acento sobre claro.', 'frave' ), __( 'Blanco 5,3:1', 'frave' ) ),
	array( 'Naranja suave', '#fff1ea', __( 'Fondos de iconos y píldoras suaves.', 'frave' ), __( 'Primario encima 4,8:1', 'frave' ) ),
	array( 'Superficie', '#f6f4f1', __( 'Secciones alternas y fondos de imagen.', 'frave' ), __( 'Texto secundario encima 4,9:1', 'frave' ) ),
	array( 'Borde', '#e5e1dc', __( 'Bordes y divisores.', 'frave' ), '' ),
	array( 'Texto secundario', '#6b6b68', __( 'Descripciones y datos auxiliares.', 'frave' ), __( 'Sobre blanco 5,4:1', 'frave' ) ),
);
$frave_lines  = array(
	array( 'flavor', __( 'Sabores', 'frave' ), __( 'Alimentos, bebidas y repostería', 'frave' ) ),
	array( 'fragrance', __( 'Fragancias', 'frave' ), __( 'Perfumería, cuidado personal y hogar', 'frave' ) ),
	array( 'packaging', __( 'Envases', 'frave' ), __( 'Frascos, atomizadores y accesorios', 'frave' ) ),
	array( 'lab', __( 'Desarrollo a medida', 'frave' ), __( 'Fórmulas pensadas para tu producto', 'frave' ) ),
	array( 'quality', __( 'Calidad', 'frave' ), __( 'Insumos seleccionados y controlados', 'frave' ) ),
	array( 'truck', __( 'Despacho', 'frave' ), __( 'Entregas coordinadas a todo el Perú', 'frave' ) ),
);

get_header();
?>
<style>
	.sg-swatches { display: grid; grid-template-columns: repeat(auto-fill, minmax(min(100%, 15rem), 1fr)); gap: 1rem; margin: 0; padding: 0; list-style: none; }
	.sg-swatch { overflow: hidden; border: 1px solid var(--color-line); border-radius: var(--radius-md); background: #fff; }
	.sg-swatch__chip { display: block; height: 6rem; border-bottom: 1px solid var(--color-line); }
	.sg-swatch__body { display: grid; gap: 0.25rem; padding: 1rem 1.15rem 1.25rem; font-size: 0.88rem; }
	.sg-swatch__body code { color: var(--color-muted); }
	.sg-swatch__body small { color: var(--color-primary); font-weight: 700; }
	.sg-type > * { margin: 0 0 1.5rem; }
	.sg-panel { padding: clamp(1.5rem, 4vw, 2.5rem); border-radius: var(--radius-lg); }
	.sg-icons { display: grid; grid-template-columns: repeat(auto-fill, minmax(8.5rem, 1fr)); gap: 0.75rem; margin: 0; padding: 0; list-style: none; }
	.sg-icons li { display: grid; gap: 0.75rem; justify-items: center; padding: 1.25rem 0.5rem; border: 1px solid rgb(255 255 255 / 10%); border-radius: var(--radius-md); color: var(--color-on-dark-muted); font-size: 0.78rem; }
	.sg-icons .icon { width: 1.75rem; height: 1.75rem; color: #fff; }
	.sg-shapes { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 14rem), 1fr)); gap: clamp(1rem, 3vw, 2rem); align-items: end; }
	.sg-shapes figure { display: grid; gap: 0.75rem; margin: 0; }
	.sg-shapes figcaption { color: var(--color-muted); font-size: 0.85rem; text-align: center; }
	.sg-placeholder { width: 100%; aspect-ratio: 4 / 5; background: radial-gradient(circle at 30% 25%, #ffb38f, transparent 45%), linear-gradient(160deg, #fc4c02, #7a2200 70%, #121418); }
	.sg-placeholder--soft { background: radial-gradient(circle at 70% 30%, #fff, transparent 50%), linear-gradient(160deg, #f6f4f1, #e5d6c8); }
	.sg-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 12rem), 1fr)); gap: 2rem; margin-top: clamp(2.5rem, 5vw, 4rem); }
	.sg-line-card { display: grid; gap: 1rem; height: 100%; }
	.sg-stack-card { display: grid; gap: 1rem; min-height: 16rem; align-content: end; padding: clamp(1.75rem, 4vw, 3rem); border-radius: var(--radius-lg); }
	.sg-pin { display: grid; min-height: 70vh; place-items: center; text-align: center; }
	.sg-pin .display { color: #fff; }
	.sg-curtain { min-height: 60vh; }
</style>
<main id="primary" class="site-main style-guide">
	<?php
	get_template_part(
		'template-parts/components/page-hero',
		null,
		array(
			'eyebrow' => __( 'Uso interno', 'frave' ),
			'title'   => __( 'Sistema visual <strong>Frave</strong>', 'frave' ),
			'lead'    => __( 'Colores, tipografía, componentes y movimiento de la web corporativa, para revisarlos y aprobarlos. Solo lo ve el equipo con acceso al tema.', 'frave' ),
		)
	);
	?>

	<section class="section" aria-labelledby="sg-colors">
		<div class="container">
			<div class="section-head">
				<p class="eyebrow"><?php esc_html_e( '01 · Color', 'frave' ); ?></p>
				<h2 id="sg-colors"><?php echo wp_kses( __( 'Base corporativa, <strong>acento naranja</strong>', 'frave' ), array( 'strong' => array() ) ); ?></h2>
				<p class="lead"><?php esc_html_e( 'Tinta y noche dan la seriedad; el naranja de marca guía la mirada. Sobre blanco, el naranja de marca solo va en títulos grandes y en elementos sin texto; los botones usan el primario accesible.', 'frave' ); ?></p>
			</div>
			<ul class="sg-swatches">
				<?php foreach ( $frave_colors as list( $frave_name, $frave_hex, $frave_use, $frave_contrast ) ) : ?>
					<li class="sg-swatch">
						<span class="sg-swatch__chip" style="background: <?php echo esc_attr( $frave_hex ); ?>"></span>
						<span class="sg-swatch__body">
							<strong><?php echo esc_html( $frave_name ); ?></strong>
							<code><?php echo esc_html( $frave_hex ); ?></code>
							<span><?php echo esc_html( $frave_use ); ?></span>
							<?php if ( $frave_contrast ) : ?>
								<small><?php echo esc_html( $frave_contrast ); ?></small>
							<?php endif; ?>
						</span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>

	<section class="section section--surface" aria-labelledby="sg-type">
		<div class="container sg-type">
			<div class="section-head">
				<p class="eyebrow"><?php esc_html_e( '02 · Tipografía', 'frave' ); ?></p>
				<h2 id="sg-type"><?php echo wp_kses( __( 'Lato ligera para titular, <strong>negrita para subrayar</strong>', 'frave' ), array( 'strong' => array() ) ); ?></h2>
			</div>
			<p class="display"><?php echo wp_kses( __( 'Sabores y fragancias que <span class="pill">conectan</span> con tu marca', 'frave' ), array( 'span' => array( 'class' => array() ) ) ); ?></p>
			<p class="h1-sample" style="font-size: var(--text-h1); font-weight: 300; line-height: 1.06; letter-spacing: -0.035em;"><?php echo wp_kses( __( 'Título de página con <span class="pill pill--soft">píldora suave</span>', 'frave' ), array( 'span' => array( 'class' => array() ) ) ); ?></p>
			<p style="font-size: var(--text-h2); font-weight: 300; line-height: 1.06; letter-spacing: -0.035em;"><?php echo wp_kses( __( 'Título de sección con <span class="mark">marcador</span>', 'frave' ), array( 'span' => array( 'class' => array() ) ) ); ?></p>
			<p style="font-size: var(--text-h3); font-weight: 700; letter-spacing: -0.02em;"><?php esc_html_e( 'Subtítulo de tarjeta o bloque', 'frave' ); ?></p>
			<p class="lead"><?php esc_html_e( 'Párrafo de entrada: presenta la sección en una o dos frases, con un tamaño intermedio entre el título y el texto.', 'frave' ); ?></p>
			<p style="max-width: 40rem;"><?php esc_html_e( 'Texto de lectura a 16 px con interlineado 1,6. Frave acompaña a marcas y emprendedores con sabores, fragancias y envases seleccionados, y con asesoría para elegir lo que mejor funciona en cada producto.', 'frave' ); ?></p>
			<p class="eyebrow"><?php esc_html_e( 'Rótulo de sección', 'frave' ); ?></p>
		</div>
	</section>

	<section class="section" aria-labelledby="sg-buttons">
		<div class="container">
			<div class="section-head">
				<p class="eyebrow"><?php esc_html_e( '03 · Botones', 'frave' ); ?></p>
				<h2 id="sg-buttons"><?php esc_html_e( 'Una acción principal por bloque', 'frave' ); ?></h2>
				<p class="lead"><?php esc_html_e( 'Píldora con icono que avanza al pasar el puntero. El primario lleva a contactar; los demás acompañan.', 'frave' ); ?></p>
			</div>
			<div class="btn-row" style="margin-bottom: 1.5rem;">
				<?php frave_button( __( 'Solicitar asesoría', 'frave' ), '#sg-buttons' ); ?>
				<?php frave_button( __( 'Ver el catálogo', 'frave' ), '#sg-buttons', 'btn btn--dark' ); ?>
				<?php frave_button( __( 'Conoce Frave', 'frave' ), '#sg-buttons', 'btn btn--outline' ); ?>
				<?php frave_button( __( 'Pequeño', 'frave' ), '#sg-buttons', 'btn btn--primary btn--sm' ); ?>
				<a class="link-arrow" href="#sg-buttons"><?php esc_html_e( 'Enlace con flecha', 'frave' ); ?><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></a>
			</div>
			<div class="on-dark sg-panel btn-row">
				<?php frave_button( __( 'Solicitar asesoría', 'frave' ), '#sg-buttons' ); ?>
				<?php frave_button( __( 'Ver el catálogo', 'frave' ), '#sg-buttons', 'btn btn--light' ); ?>
				<?php frave_button( __( 'Conoce Frave', 'frave' ), '#sg-buttons', 'btn btn--outline' ); ?>
				<a class="link-arrow" href="#sg-buttons"><?php esc_html_e( 'Enlace con flecha', 'frave' ); ?><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></a>
			</div>
		</div>
	</section>

	<section class="section section--sand" aria-labelledby="sg-icons">
		<div class="container">
			<div class="section-head">
				<p class="eyebrow"><?php esc_html_e( '04 · Iconos e isotipo', 'frave' ); ?></p>
				<h2 id="sg-icons"><?php echo wp_kses( __( 'El trazo del <strong>isotipo</strong>', 'frave' ), array( 'strong' => array() ) ); ?></h2>
				<p class="lead"><?php esc_html_e( 'Iconos de línea con el mismo grosor y remates redondeados que el frasco de Frave.', 'frave' ); ?></p>
			</div>
			<ul class="sg-icons">
				<?php foreach ( array_keys( frave_icon_paths() ) as $frave_icon_name ) : ?>
					<li><?php echo frave_icon( $frave_icon_name ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?><?php echo esc_html( $frave_icon_name ); ?></li>
				<?php endforeach; ?>
				<li><span class="brand-seal" style="border-color: rgb(255 255 255 / 15%);"><?php echo frave_isotype(); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span><?php esc_html_e( 'isotipo', 'frave' ); ?></li>
			</ul>
		</div>
	</section>

	<section class="section" aria-labelledby="sg-shapes">
		<div class="container">
			<div class="section-head">
				<p class="eyebrow"><?php esc_html_e( '05 · Formas', 'frave' ); ?></p>
				<h2 id="sg-shapes"><?php esc_html_e( 'La gota y el frasco como marcos', 'frave' ); ?></h2>
				<p class="lead"><?php esc_html_e( 'Las fotografías se recortan con las siluetas del isotipo: así la web se reconoce como Frave aunque cambien las imágenes.', 'frave' ); ?></p>
			</div>
			<div class="sg-shapes">
				<figure><div class="media-frame shape-drop"><div class="sg-placeholder"></div></div><figcaption><?php esc_html_e( 'Gota · .shape-drop', 'frave' ); ?></figcaption></figure>
				<figure><div class="media-frame shape-bottle"><div class="sg-placeholder sg-placeholder--soft"></div></div><figcaption><?php esc_html_e( 'Frasco · .shape-bottle', 'frave' ); ?></figcaption></figure>
				<figure><div class="media-frame shape-arch"><div class="sg-placeholder"></div></div><figcaption><?php esc_html_e( 'Arco · .shape-arch', 'frave' ); ?></figcaption></figure>
			</div>
			<div class="divider-drop" style="margin-block: clamp(2.5rem, 5vw, 4rem);" aria-hidden="true"><svg viewBox="0 0 100 125"><path d="M50 2C40 22 4 52 4 78a46 46 0 0 0 92 0C96 52 60 22 50 2Z"/></svg></div>
		</div>
	</section>

	<section class="section section--surface" aria-labelledby="sg-cards">
		<div class="container">
			<div class="section-head section-head--split">
				<p class="eyebrow"><?php esc_html_e( '06 · Tarjetas y cifras', 'frave' ); ?></p>
				<h2 id="sg-cards"><?php esc_html_e( 'Razones para elegirnos', 'frave' ); ?></h2>
				<a class="link-arrow section-head__action" href="#sg-cards"><?php esc_html_e( 'Acción de la sección', 'frave' ); ?><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></a>
			</div>
			<div class="feature-grid" data-reveal-group>
				<?php foreach ( array_slice( $frave_lines, 3 ) as list( $frave_icon_name, $frave_title, $frave_text ) ) : ?>
					<article class="card card--interactive">
						<span class="icon-tile"><?php echo frave_icon( $frave_icon_name ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
						<h3><?php echo esc_html( $frave_title ); ?></h3>
						<p><?php echo esc_html( $frave_text ); ?></p>
					</article>
				<?php endforeach; ?>
				<article class="card card--dark card--interactive">
					<span class="icon-tile"><?php echo frave_icon( 'chat' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></span>
					<h3><?php esc_html_e( 'Asesoría cercana', 'frave' ); ?></h3>
					<p><?php esc_html_e( 'Tarjeta oscura para destacar un bloque.', 'frave' ); ?></p>
				</article>
			</div>
			<div class="sg-stats">
				<?php foreach ( array( array( 15, '+', __( 'años de experiencia (ejemplo)', 'frave' ) ), array( 300, '+', __( 'referencias en catálogo (ejemplo)', 'frave' ) ), array( 24, 'h', __( 'para responder una cotización (ejemplo)', 'frave' ) ) ) as list( $frave_value, $frave_unit, $frave_label ) ) : ?>
					<div class="stat">
						<span class="stat__value"><span data-count-to="<?php echo esc_attr( $frave_value ); ?>" aria-hidden="true"><?php echo esc_html( number_format_i18n( $frave_value ) ); ?></span><small aria-hidden="true"><?php echo esc_html( $frave_unit ); ?></small><span class="screen-reader-text"><?php echo esc_html( $frave_value . ' ' . $frave_unit ); ?></span></span>
						<span class="stat__label"><?php echo esc_html( $frave_label ); ?></span>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="section" aria-labelledby="sg-motion">
		<div class="container">
			<div class="section-head">
				<p class="eyebrow"><?php esc_html_e( '07 · Movimiento', 'frave' ); ?></p>
				<h2 id="sg-motion"><?php esc_html_e( 'Carrusel, apilado y telón', 'frave' ); ?></h2>
				<p class="lead"><?php esc_html_e( 'Todo funciona sin JavaScript y se detiene si el sistema pide movimiento reducido.', 'frave' ); ?></p>
			</div>

			<div class="carousel" data-carousel>
				<ul class="carousel__track" data-carousel-track tabindex="0" aria-label="<?php esc_attr_e( 'Líneas de Frave (ejemplo)', 'frave' ); ?>">
					<?php foreach ( $frave_lines as $frave_index => list( $frave_icon_name, $frave_title, $frave_text ) ) : ?>
						<li aria-roledescription="<?php esc_attr_e( 'diapositiva', 'frave' ); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: 1: slide number, 2: total. */ __( '%1$d de %2$d', 'frave' ), $frave_index + 1, count( $frave_lines ) ) ); ?>">
							<article class="card card--interactive sg-line-card">
								<div class="media-frame shape-bottle"><div class="sg-placeholder<?php echo 1 === $frave_index % 2 ? ' sg-placeholder--soft' : ''; ?>"></div></div>
								<h3><a class="card__link" href="#sg-motion"><?php echo esc_html( $frave_title ); ?></a></h3>
								<p><?php echo esc_html( $frave_text ); ?></p>
							</article>
						</li>
					<?php endforeach; ?>
				</ul>
				<div class="carousel__controls">
					<button class="carousel__button" type="button" data-carousel-prev aria-label="<?php esc_attr_e( 'Anterior', 'frave' ); ?>"><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></button>
					<button class="carousel__button" type="button" data-carousel-next aria-label="<?php esc_attr_e( 'Siguiente', 'frave' ); ?>"><?php echo frave_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?></button>
				</div>
			</div>

			<div class="stack" data-stack style="margin-top: clamp(3rem, 6vw, 5rem);">
				<?php foreach ( array( array( 'on-dark', __( 'Asesoría', 'frave' ) ), array( 'section--surface', __( 'Selección o desarrollo', 'frave' ) ), array( 'on-dark', __( 'Entrega', 'frave' ) ) ) as $frave_index => list( $frave_surface, $frave_step ) ) : ?>
					<article class="stack__item sg-stack-card <?php echo esc_attr( $frave_surface ); ?>">
						<p class="eyebrow"><?php echo esc_html( sprintf( /* translators: %d: step number. */ __( 'Paso %d', 'frave' ), $frave_index + 1 ) ); ?></p>
						<h3 style="font-size: var(--text-h2); font-weight: 300; margin: 0;"><?php echo esc_html( $frave_step ); ?></h3>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<div class="pin on-dark sg-pin">
		<div class="container">
			<p class="eyebrow"><?php esc_html_e( 'Hero fijo', 'frave' ); ?></p>
			<p class="display"><?php echo wp_kses( __( 'La sección siguiente <span class="pill">sube</span> por encima', 'frave' ), array( 'span' => array( 'class' => array() ) ) ); ?></p>
		</div>
	</div>
	<section class="section curtain sg-curtain" aria-label="<?php esc_attr_e( 'Telón', 'frave' ); ?>">
		<div class="container">
			<div class="section-head section-head--center">
				<p class="eyebrow"><?php esc_html_e( 'Telón', 'frave' ); ?></p>
				<h2><?php esc_html_e( 'Esquinas redondeadas y sombra al cubrir el hero', 'frave' ); ?></h2>
				<p class="lead"><?php esc_html_e( 'Al final de la página, en pantallas grandes, el contenido se despega y descubre el pie.', 'frave' ); ?></p>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
