<?php
/**
 * Blog: the page /blog/ as posts page (Ajustes → Lectura), its categories and four
 * starter articles as drafts, for Frave to review and publish.
 *
 * From the site folder, with the theme's clone (bin/ is not deployed with the theme):
 *   wp eval-file ~/repos/frave-theme/bin/crear-blog.php
 * Add "publicar" to publish the articles at once (local and test sites):
 *   wp eval-file ~/repos/frave-theme/bin/crear-blog.php publicar
 *
 * Safe to run again: an existing page, category or article (by slug) is left as it is.
 * The cover images come from the theme (line images) and can be replaced in each post.
 */

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	exit( 'Run with WP-CLI only.' );
}

require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

$frave_publish = in_array( 'publicar', (array) ( $args ?? array() ), true );

/* ---------- Posts page ---------- */

$frave_blog_page = get_page_by_path( 'blog' );
if ( ! $frave_blog_page instanceof WP_Post ) {
	$frave_blog_id = wp_insert_post(
		array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => 'Blog',
			'post_name'    => 'blog',
			'post_excerpt' => 'Tendencias, guías técnicas y novedades para crear mejores productos con sabores, fragancias y envases.',
		),
		true
	);
	if ( is_wp_error( $frave_blog_id ) ) {
		WP_CLI::error( 'No se pudo crear la página del blog: ' . $frave_blog_id->get_error_message() );
	}
	WP_CLI::log( 'Página creada: Blog (/blog/)' );
} else {
	$frave_blog_id = $frave_blog_page->ID;
	WP_CLI::log( 'La página «Blog» ya existe.' );
}

$frave_current_blog = (int) get_option( 'page_for_posts' );
if ( ! $frave_current_blog ) {
	update_option( 'page_for_posts', $frave_blog_id );
	WP_CLI::log( 'Blog asignado como página de entradas (Ajustes → Lectura).' );
} elseif ( $frave_current_blog !== (int) $frave_blog_id ) {
	WP_CLI::warning( 'Ya hay otra página de entradas (ID ' . $frave_current_blog . '); no se cambia.' );
}

/* ---------- Categories ---------- */

// WordPress's default category gets a name that reads well on the site.
$frave_default = get_term( (int) get_option( 'default_category' ), 'category' );
if ( $frave_default instanceof WP_Term && in_array( $frave_default->slug, array( 'uncategorized', 'sin-categoria' ), true ) ) {
	wp_update_term(
		$frave_default->term_id,
		'category',
		array(
			'name' => 'Novedades',
			'slug' => 'novedades',
		)
	);
	WP_CLI::log( 'Categoría por defecto renombrada: Novedades.' );
}

$frave_categories   = array(
	'guias-tecnicas' => array( 'Guías técnicas', 'Cómo elegir, dosificar y aplicar sabores, fragancias y envases en tus productos.' ),
	'tendencias'     => array( 'Tendencias', 'Lo que está marcando el rumbo en perfumería, cosmética, hogar y alimentos.' ),
);
$frave_category_ids = array();
foreach ( $frave_categories as $frave_slug => list( $frave_name, $frave_description ) ) {
	$frave_term = get_term_by( 'slug', $frave_slug, 'category' );
	if ( $frave_term ) {
		$frave_category_ids[ $frave_slug ] = (int) $frave_term->term_id;
		continue;
	}
	$frave_term = wp_insert_term(
		$frave_name,
		'category',
		array(
			'slug'        => $frave_slug,
			'description' => $frave_description,
		)
	);
	if ( is_wp_error( $frave_term ) ) {
		WP_CLI::warning( "No se pudo crear la categoría «{$frave_name}»: " . $frave_term->get_error_message() );
		continue;
	}
	$frave_category_ids[ $frave_slug ] = (int) $frave_term['term_id'];
	WP_CLI::log( "Categoría creada: {$frave_name}" );
}

/* ---------- Starter articles ---------- */

/** Gutenberg markup from a light syntax: "## " heading, "- " list item, anything else a paragraph. */
function frave_blog_blocks( string $text ): string {
	$blocks = array();
	$list   = array();
	$flush  = static function () use ( &$list, &$blocks ): void {
		if ( $list ) {
			$items    = implode( '', array_map( static fn( $item ) => '<!-- wp:list-item --><li>' . $item . '</li><!-- /wp:list-item -->', $list ) );
			$blocks[] = '<!-- wp:list --><ul class="wp-block-list">' . $items . '</ul><!-- /wp:list -->';
			$list     = array();
		}
	};
	foreach ( preg_split( '/\n\s*\n|\n(?=- )/', trim( $text ) ) as $chunk ) {
		$chunk = trim( $chunk );
		if ( str_starts_with( $chunk, '- ' ) ) {
			$list[] = substr( $chunk, 2 );
			continue;
		}
		$flush();
		if ( str_starts_with( $chunk, '## ' ) ) {
			$blocks[] = '<!-- wp:heading --><h2 class="wp-block-heading">' . substr( $chunk, 3 ) . '</h2><!-- /wp:heading -->';
		} else {
			$blocks[] = '<!-- wp:paragraph --><p>' . $chunk . '</p><!-- /wp:paragraph -->';
		}
	}
	$flush();
	return implode( "\n\n", $blocks );
}

/** Attachment ID of a theme image, imported into the media library once. */
function frave_blog_image( string $path, string $alt ): int {
	$existing = get_posts(
		array(
			'post_type'   => 'attachment',
			'post_status' => 'inherit',
			'meta_key'    => '_frave_source',
			'meta_value'  => $path,
			'fields'      => 'ids',
			'numberposts' => 1,
		)
	);
	if ( $existing ) {
		return (int) $existing[0];
	}
	$source = get_theme_file_path( $path );
	if ( ! is_readable( $source ) ) {
		WP_CLI::warning( "No se encontró la imagen {$path}." );
		return 0;
	}
	$temporary = wp_tempnam( basename( $source ) );
	copy( $source, $temporary );
	$attachment = media_handle_sideload(
		array(
			'name'     => 'blog-' . basename( $source ),
			'tmp_name' => $temporary,
		),
		0
	);
	if ( is_wp_error( $attachment ) ) {
		wp_delete_file( $temporary );
		WP_CLI::warning( "No se pudo importar {$path}: " . $attachment->get_error_message() );
		return 0;
	}
	update_post_meta( $attachment, '_frave_source', $path );
	update_post_meta( $attachment, '_wp_attachment_image_alt', $alt );
	return (int) $attachment;
}

$frave_articles = array(
	array(
		'slug'     => 'hidrosoluble-o-liposoluble',
		'title'    => 'Hidrosoluble o liposoluble: cómo elegir la fragancia o el sabor para tu producto',
		'excerpt'  => 'La base de tu producto decide qué tipo de fragancia o sabor necesitas. Te explicamos la diferencia y cómo evitar separaciones, turbidez o pérdida de aroma.',
		'category' => 'guias-tecnicas',
		'image'    => array( 'assets/media/lineas/fragancias-escritorio.webp', 'Frascos de fragancia sobre fondo marfil' ),
		'content'  => <<<'TEXT'
Antes de hablar de notas, familias o tendencias, hay una pregunta que define si una fragancia o un sabor va a funcionar en tu producto: ¿la base es acuosa u oleosa? De esa respuesta depende la estabilidad, la claridad y la intensidad del resultado final.

## Qué significa cada término

Una fragancia o un sabor <strong>hidrosoluble</strong> está formulado para integrarse en bases con agua: bebidas, geles, ambientadores en agua, limpiadores o enjuagues bucales. Uno <strong>liposoluble</strong> se disuelve en aceites, grasas o alcohol: perfumería fina, velas, aceites corporales, chocolates o productos de panadería con grasa.

## Qué pasa si eliges mal

- La mezcla se separa o forma gotas en la superficie.
- El producto pierde transparencia y se ve turbio.
- El aroma o el sabor se siente débil, aunque aumentes la dosis.
- Cambian el color o la textura con el paso de las semanas.

## Cómo elegir

Empieza por la base: si la mayor parte es agua, piensa en una versión hidrosoluble; si es aceite, cera, grasa o alcohol, en una liposoluble. Luego considera el proceso: temperatura, tiempo de mezcla y si el producto pasará por calor (horneado, vertido de velas, pasteurización). Por último, define la intensidad que buscas y haz pruebas a pequeña escala antes de producir el lote completo.

## Pruebas antes de producir

Recomendamos preparar muestras con dos o tres dosis distintas y observarlas durante algunos días a temperatura ambiente y, si es posible, también con calor. Así verás a tiempo si hay separación, cambios de color o pérdida de intensidad.

En Frave te ayudamos a elegir la versión adecuada para tu base y tu proceso, y te orientamos con la dosis inicial para tus pruebas. Escríbenos y cuéntanos qué estás desarrollando.
TEXT
		,
	),
	array(
		'slug'     => 'como-elegir-el-frasco-para-tu-perfume',
		'title'    => 'Cómo elegir el frasco para tu perfume: capacidad, vidrio y válvula',
		'excerpt'  => 'El envase es lo primero que ve tu cliente y protege la fragancia. Estas son las decisiones clave para elegir el frasco y la válvula correctos.',
		'category' => 'guias-tecnicas',
		'image'    => array( 'assets/media/lineas/envases-escritorio.webp', 'Frascos de vidrio para perfumería' ),
		'content'  => <<<'TEXT'
Un buen perfume merece un envase a la altura. El frasco comunica la personalidad de tu marca antes de que el cliente pruebe la fragancia y, al mismo tiempo, la protege de la luz, el aire y la evaporación.

## 1. Define la capacidad según tu canal

- <strong>30 ml:</strong> ideal para lanzamientos, regalos o para que el cliente pruebe una fragancia nueva.
- <strong>50 ml:</strong> el formato más equilibrado entre precio y duración.
- <strong>100 ml o más:</strong> para fragancias consolidadas y clientes fieles.

Si vas a vender por catálogo o en línea, piensa también en el peso y el tamaño de la caja para el envío.

## 2. Elige el vidrio y la forma

El vidrio grueso y con buena transparencia transmite calidad. Las formas clásicas (cilíndricas o rectangulares) son versátiles y fáciles de etiquetar; las formas especiales ayudan a diferenciarte, pero conviene revisar que encajen con tu caja y tu etiqueta.

## 3. No olvides la válvula

La válvula atomizadora define la experiencia de uso: una buena válvula entrega una nube fina y uniforme y evita goteos. Verifica que el cuello del frasco y la válvula sean compatibles (por ejemplo, rosca o crimpado) y prueba la descarga antes de producir.

## 4. Pide muestras

Antes de comprar volumen, llena algunas muestras con tu fragancia y obsérvalas durante unos días: revisa el cierre, la descarga y cómo se ve el producto terminado con su etiqueta.

En Frave encuentras frascos de vidrio para perfumería y válvulas, y te asesoramos para que el envase y la fragancia funcionen juntos.
TEXT
		,
	),
	array(
		'slug'     => 'marketing-olfativo-para-tu-marca',
		'title'    => 'Marketing olfativo: cuando el aroma se vuelve parte de tu marca',
		'excerpt'  => 'Un aroma propio hace que tu tienda, oficina o evento se recuerde. Te contamos cómo empezar con el marketing olfativo y qué tener en cuenta.',
		'category' => 'tendencias',
		'image'    => array( 'assets/media/empresa/ingredientes.webp', 'Ingredientes naturales para fragancias' ),
		'content'  => <<<'TEXT'
El olfato es el sentido más ligado a la memoria y a las emociones. Por eso cada vez más marcas usan un aroma propio en sus tiendas, oficinas, hoteles y eventos: una forma de que el cliente reconozca el espacio y lo recuerde.

## Qué es el marketing olfativo

Es el uso planificado de una fragancia para reforzar la identidad de una marca. No se trata solo de que el lugar huela bien, sino de que huela siempre igual y en coherencia con lo que la marca quiere transmitir: frescura, calidez, lujo o energía.

## Por dónde empezar

- <strong>Define la personalidad de tu marca</strong> con tres o cuatro palabras (por ejemplo: natural, cercana, fresca).
- <strong>Traduce esas palabras a una familia olfativa:</strong> cítricas y verdes para frescura, amaderadas y especiadas para calidez, florales para elegancia.
- <strong>Elige el formato:</strong> difusores de varitas para espacios pequeños, aerosoles o equipos de difusión para áreas grandes y aromas para vehículos como extensión de la marca.
- <strong>Prueba en el espacio real</strong> y ajusta la intensidad: el aroma debe acompañar, no invadir.

## Más allá del local

Una vez definido, el aroma puede llegar a tus productos: velas, ambientadores o tarjetas aromatizadas que el cliente se lleva a casa y que vuelven a conectar con tu marca.

En Frave te ayudamos a encontrar el aroma que representa a tu marca y el formato de ambientación que mejor funciona en tu espacio.
TEXT
		,
	),
	array(
		'slug'     => 'sabores-para-bebidas-y-lacteos',
		'title'    => 'Sabores para bebidas y lácteos: claves para acertar con el perfil',
		'excerpt'  => 'Elegir un sabor no es solo elegir una fruta. Proceso, acidez y grasa cambian cómo se percibe. Estas claves te ayudan a acertar desde la primera prueba.',
		'category' => 'guias-tecnicas',
		'image'    => array( 'assets/media/lineas/sabores-escritorio.webp', 'Frutas frescas y sabores para alimentos' ),
		'content'  => <<<'TEXT'
Un mismo sabor puede percibirse muy distinto en un néctar, en un yogur o en una bebida carbonatada. La base, el proceso y el resto de ingredientes influyen tanto como el sabor elegido.

## La base cambia la percepción

- <strong>Acidez:</strong> en bebidas cítricas o carbonatadas realza las notas frescas y puede apagar las notas dulces o cremosas.
- <strong>Grasa:</strong> en lácteos y helados suaviza los sabores y necesita perfiles más redondos o lácteos que los acompañen.
- <strong>Azúcar y edulcorantes:</strong> cambian la intensidad y el final del sabor; conviene probar con la formulación definitiva.

## El proceso importa

Los tratamientos térmicos como la pasteurización pueden reducir algunas notas volátiles. Si tu producto pasa por calor, pide sabores pensados para resistirlo y agrégalos en la etapa recomendada del proceso.

## Combina para diferenciarte

Las combinaciones (fresa con plátano, maracuyá con mango, vainilla con caramelo) ayudan a crear productos con identidad propia. Los sabores de frutas peruanas también son una gran oportunidad para destacar en el mercado local y en exportación.

## Prueba en pequeño

Prepara muestras con distintas dosis, déjalas reposar y pruébalas frías y a temperatura ambiente. Así verás cómo evoluciona el sabor antes de producir el lote.

En Frave te asesoramos para elegir el perfil adecuado para tu bebida o lácteo y te acompañamos en las pruebas.
TEXT
		,
	),
);

foreach ( $frave_articles as $frave_index => $frave_article ) {
	$frave_existing = get_page_by_path( $frave_article['slug'], OBJECT, 'post' );
	if ( $frave_existing instanceof WP_Post ) {
		WP_CLI::log( "El artículo «{$frave_existing->post_title}» ya existe." );
		continue;
	}
	$frave_post_id = wp_insert_post(
		array(
			'post_type'     => 'post',
			'post_status'   => $frave_publish ? 'publish' : 'draft',
			'post_title'    => $frave_article['title'],
			'post_name'     => $frave_article['slug'],
			'post_excerpt'  => $frave_article['excerpt'],
			'post_content'  => frave_blog_blocks( $frave_article['content'] ),
			'post_category' => isset( $frave_category_ids[ $frave_article['category'] ] ) ? array( $frave_category_ids[ $frave_article['category'] ] ) : array(),
			// A day apart, so the order of the blog is stable.
			'post_date'     => gmdate( 'Y-m-d H:i:s', time() - DAY_IN_SECONDS * ( count( $frave_articles ) - $frave_index ) + (int) get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ),
		),
		true
	);
	if ( is_wp_error( $frave_post_id ) ) {
		WP_CLI::warning( "No se pudo crear «{$frave_article['title']}»: " . $frave_post_id->get_error_message() );
		continue;
	}
	$frave_image = frave_blog_image( $frave_article['image'][0], $frave_article['image'][1] );
	if ( $frave_image ) {
		set_post_thumbnail( $frave_post_id, $frave_image );
	}
	WP_CLI::log( ( $frave_publish ? 'Artículo publicado: ' : 'Borrador creado: ' ) . $frave_article['title'] );
}

WP_CLI::success( $frave_publish ? 'Blog listo.' : 'Blog listo. Revisa los borradores en Entradas y publícalos cuando estén aprobados.' );
