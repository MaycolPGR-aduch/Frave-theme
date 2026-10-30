<?php
/**
 * Blog: the page /blog/ as posts page (Ajustes → Lectura), its categories and the articles
 * written for Frave, as drafts for Frave to review and publish. Each article names its
 * product line and collections (post meta frave_line and frave_collections, for «Para tu
 * producto» under the article).
 *
 * From the site folder, with the theme's clone (bin/ is not deployed with the theme):
 *   wp eval-file ~/repos/frave-theme/bin/crear-blog.php
 * Add "publicar" to publish the articles at once (local and test sites):
 *   wp eval-file ~/repos/frave-theme/bin/crear-blog.php publicar
 *
 * Safe to run again: an existing page, category or article (by slug) keeps its text; an
 * article gets its line and collections if it has none, and its own cover
 * (assets/media/blog/{slug}.webp) once that image exists, while its cover is still the one
 * this script set. Until then the covers are other images of the theme.
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
$frave_news = get_term_by( 'slug', 'novedades', 'category' );
if ( $frave_news instanceof WP_Term && '' === trim( $frave_news->description ) ) {
	wp_update_term( $frave_news->term_id, 'category', array( 'description' => 'Colecciones, lanzamientos y noticias de Frave.' ) );
}

$frave_categories   = array(
	'guias-tecnicas' => array( 'Guías técnicas', 'Cómo elegir, dosificar y aplicar sabores, fragancias y envases en tus productos.' ),
	'tendencias'     => array( 'Tendencias', 'Lo que está marcando el rumbo en perfumería, cosmética, hogar y alimentos.' ),
);
$frave_category_ids = array();
if ( $frave_news instanceof WP_Term ) {
	$frave_category_ids['novedades'] = (int) $frave_news->term_id;
}
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

/**
 * Gutenberg markup from a light syntax: "## " and "### " headings, "- " list items, "> " a tip
 * (group block with the «Consejo» style), lines starting with "| " a table (the first one its
 * header, cells separated by " | "), anything else a paragraph.
 */
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
		if ( str_starts_with( $chunk, '### ' ) ) {
			$blocks[] = '<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">' . substr( $chunk, 4 ) . '</h3><!-- /wp:heading -->';
		} elseif ( str_starts_with( $chunk, '## ' ) ) {
			$blocks[] = '<!-- wp:heading --><h2 class="wp-block-heading">' . substr( $chunk, 3 ) . '</h2><!-- /wp:heading -->';
		} elseif ( str_starts_with( $chunk, '> ' ) ) {
			$blocks[] = '<!-- wp:group {"className":"is-style-frave-tip","layout":{"type":"constrained"}} --><div class="wp-block-group is-style-frave-tip"><!-- wp:paragraph --><p>' . substr( $chunk, 2 ) . '</p><!-- /wp:paragraph --></div><!-- /wp:group -->';
		} elseif ( str_starts_with( $chunk, '| ' ) ) {
			$rows     = array_map( static fn( string $row ): array => array_map( 'trim', explode( ' | ', substr( trim( $row ), 2 ) ) ), explode( "\n", $chunk ) );
			$cell     = static fn( string $tag, array $cells ): string => '<tr>' . implode( '', array_map( static fn( string $text ): string => "<{$tag}>{$text}</{$tag}>", $cells ) ) . '</tr>';
			$head     = $cell( 'th', array_shift( $rows ) );
			$body     = implode( '', array_map( static fn( array $cells ): string => $cell( 'td', $cells ), $rows ) );
			$blocks[] = '<!-- wp:table --><figure class="wp-block-table"><table class="has-fixed-layout"><thead>' . $head . '</thead><tbody>' . $body . '</tbody></table></figure><!-- /wp:table -->';
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
	array(
		'slug'        => 'sabores-para-paneton',
		'title'       => 'Sabores para panetón: cómo lograr un aroma que dure del horno a la mesa',
		'excerpt'     => 'El panetón pasa por fermentación, horneado y semanas en anaquel. Te contamos qué sabores lo definen, cómo resisten el calor y cómo probarlos antes de la campaña.',
		'category'    => 'guias-tecnicas',
		'line'        => 'sabores',
		'collections' => '',
		'image'       => array( 'assets/media/sabores/paneton.webp', 'Panetón con frutas confitadas sobre fondo arena' ),
		'content'     => <<<'TEXT'
En el Perú, el panetón es el protagonista de fin de año y su campaña se prepara con meses de anticipación. El cliente lo reconoce apenas abre la caja: ese aroma a vainilla, cítricos y masa mantecosa es lo que hace que vuelva a elegir tu marca. Lograrlo exige elegir bien el sabor y cuidarlo en cada etapa del proceso.

## Los sabores que definen un panetón

- <strong>Vainilla:</strong> la base dulce y cálida sobre la que se construye todo lo demás.
- <strong>Cítricos:</strong> naranja, limón o toronja confitada aportan frescura y realzan las frutas.
- <strong>Mantequilla y notas lácteas:</strong> dan la sensación de una masa rica y suave.
- <strong>Esencia de panetón:</strong> un acorde que reúne vainilla, cítricos y notas lácteas y frutales; es la firma clásica del producto.
- <strong>Versiones modernas:</strong> chocolate, frutos secos, lúcuma, maracuyá o café, para diferenciar una línea premium.

## El calor del horno, el gran reto

Las moléculas aromáticas son volátiles: una parte se pierde durante la fermentación larga y, sobre todo, en el horneado. Por eso conviene trabajar con sabores pensados para resistir el calor y que se integren en la fase grasa de la masa, donde se protegen mejor.

> <strong>Consejo:</strong> incorpora el sabor en el momento que indique su ficha técnica, normalmente en el amasado final junto con la mantequilla. Agregarlo antes no siempre suma intensidad: puede perderse en la fermentación.

## Que dure semanas en el anaquel

Un panetón se elabora con semanas de anticipación y debe llegar a la mesa con el mismo aroma con el que salió del horno. Influyen la dosis, la calidad del sabor, el envase (las bolsas con barrera conservan mejor el aroma) y el almacenamiento lejos del calor y la luz directa.

## Sabor, aroma y color trabajan juntos

Lo que el cliente percibe como «sabor» es la suma de lo que huele, lo que prueba y lo que ve. Un panetón muy dorado anticipa notas de mantequilla y caramelo; una miga clara, notas más lácteas. Si cambias el sabor, revisa también el color y las frutas para que la experiencia sea coherente.

## Cómo probarlo antes de la campaña

Prepara un lote piloto con dos o tres dosis distintas y evalúalo en varios momentos:

| Momento | Qué evaluar
| Masa cruda | Intensidad del aroma y si se integra sin dejar notas extrañas.
| Al salir del horno | Cuánto aroma se conservó y si aparece algún tono quemado o amargo.
| Al día siguiente | El equilibrio entre vainilla, cítricos y mantequilla ya asentado.
| A las cuatro y ocho semanas | Si el aroma se mantiene en el envase final.

Elige la dosis que mejor se conserve al final, no la que más huela en la masa.

En Frave te ayudamos a elegir el sabor adecuado para tu receta y tu proceso, y te acompañamos en las pruebas antes de la campaña. Cuéntanos cómo es tu panetón.
TEXT
		,
	),
	array(
		'slug'        => 'aromas-de-navidad-para-ambientacion',
		'title'       => 'Aromas de Navidad: fragancias para velas, difusores y espacios de temporada',
		'excerpt'     => 'Canela, naranja, vainilla y pino: los aromas que asociamos a las fiestas, cómo adaptarlos al verano peruano y en qué formatos funcionan mejor.',
		'category'    => 'tendencias',
		'line'        => 'fragancias',
		'collections' => 'aromas-para-ambientacion',
		'image'       => array( 'assets/media/familias/especiada.webp', 'Especias y frasco de fragancia sobre fondo arena' ),
		'content'     => <<<'TEXT'
Pocas épocas tienen un aroma tan reconocible como la Navidad. Las velas, los difusores y los ambientadores de temporada se venden más cuando su fragancia evoca de inmediato las fiestas, y las tiendas que perfuman sus espacios hacen que la visita se sienta especial.

## Los aromas que asociamos a las fiestas

| Aroma | Familia | Dónde funciona mejor
| Canela y clavo | Especiada | Velas, difusores y sachets
| Naranja con especias | Cítrica y especiada | Velas, sprays y ambientación de tiendas
| Vainilla y galleta | Gourmand | Velas, difusores y body splash de temporada
| Pino y abeto | Amaderada y aromática | Difusores, sprays y espacios amplios
| Frutos rojos | Frutal | Velas y ambientadores con un toque alegre

## Navidad con calor: el reto peruano

En el Perú, diciembre es verano. Las notas muy densas (especias intensas, resinas, vainilla pesada) pueden sentirse sofocantes en un ambiente caluroso. Una buena solución es combinar el corazón cálido de la Navidad con notas frescas: naranja y canela con un toque verde, pino con cítricos o vainilla con frutos rojos.

> <strong>Consejo:</strong> para tiendas y oficinas, elige versiones más ligeras y transparentes; reserva las más intensas para velas de uso doméstico, que se encienden por momentos.

## Velas de temporada

Las velas son el producto estrella de fin de año. Su fragancia debe sentirse tanto con la vela apagada como encendida, y resistir el calor de la cera sin perder su carácter. Si es tu primera colección, lee nuestra guía de fragancias para velas antes de producir.

## Difusores y sprays

Los difusores de varitas perfuman de forma continua y discreta; los sprays dan un impacto inmediato. En ambos, las notas cítricas y frescas aportan la primera impresión y las especiadas y amaderadas sostienen el aroma.

## Marketing olfativo en fin de año

Un aroma de temporada en la tienda hace que el cliente se quede más tiempo y recuerde la visita. La clave es la medida: un aroma suave, coherente con la marca y que no compita con los productos.

## Prepárate con tiempo

Para tener tu línea navideña en anaquel en noviembre, conviene elegir y probar las fragancias entre agosto y septiembre: así queda tiempo para curar las velas, ajustar la intensidad y producir.

En Frave tenemos fragancias para velas, difusores y ambientación de espacios, y te ayudamos a armar tu colección de temporada.
TEXT
		,
	),
	array(
		'slug'        => 'fragancias-para-velas',
		'title'       => 'Fragancias para velas: cómo lograr que huelan apagadas y encendidas',
		'excerpt'     => 'La cera, la temperatura, el curado y la mecha deciden cuánto perfuma una vela. Te explicamos cómo elegir la fragancia y probarla antes de producir.',
		'category'    => 'guias-tecnicas',
		'line'        => 'fragancias',
		'collections' => 'aromas-para-ambientacion',
		'image'       => array( 'assets/media/fondos/aplicaciones-fragancias.webp', 'Vela encendida, difusor y jabón sobre fondo arena' ),
		'content'     => <<<'TEXT'
Una vela aromática se juzga dos veces: cuando el cliente la huele en la tienda, apagada, y cuando la enciende en casa. Que perfume bien en ambos momentos depende de la fragancia, pero también de la cera, la temperatura a la que se mezcla, el tiempo de curado y la mecha.

## Dos momentos del aroma

- <strong>En frío:</strong> el aroma que desprende la vela apagada. Decide la compra.
- <strong>Encendida:</strong> el aroma que llena el ambiente cuando la cera se funde. Decide si el cliente vuelve a comprar.

Una fragancia puede ser muy intensa en frío y quedarse corta encendida, o al revés. Por eso hay que evaluar las dos.

## La cera cambia el resultado

| Cera | Cómo se comporta con la fragancia
| Parafina | Libera bien el aroma encendida y acepta una amplia variedad de fragancias.
| Soya | Natural y cremosa; suele necesitar más tiempo de curado para perfumar bien.
| Mezclas (soya con coco o parafina) | Buscan lo mejor de cada una: buen acabado y buena difusión.

La cantidad máxima de fragancia que admite cada cera la indica su fabricante. Superarla no hace que la vela huela más: la fragancia puede sudar o quemar mal.

## Temperatura y momento de agregar la fragancia

Agrega la fragancia cuando la cera está a la temperatura que recomiendan la ficha de la fragancia y la de la cera, y mezcla de forma suave y constante durante uno o dos minutos para que se integre por completo. Si la cera está demasiado caliente, parte del aroma se pierde; si está fría, la fragancia no se distribuye bien.

> <strong>Consejo:</strong> revisa el punto de inflamación de la fragancia en su ficha técnica y trabaja siempre por debajo de él.

## El curado: paciencia que se nota

Después de verter, la vela necesita reposar para que la fragancia se integre en la cera. Unos días suelen bastar con parafina; la soya puede necesitar una o dos semanas. Encenderla antes de tiempo da una impresión más débil de la que tendrá el producto final.

## La mecha y el recipiente

Una mecha del tamaño correcto forma una piscina de cera fundida pareja, que es la que libera el aroma. Si es pequeña, la vela hace un túnel y perfuma poco; si es grande, humea. El diámetro del recipiente define qué mecha usar.

## Cómo probar antes de producir

- Prepara velas con dos o tres dosis distintas de fragancia.
- Déjalas curar el tiempo completo.
- Huélelas en frío y luego enciéndelas en una habitación cerrada, una por vez.
- Evalúa a los 30 minutos y a las dos horas: intensidad, carácter y si aparece humo u hollín.

En Frave te ayudamos a elegir fragancias pensadas para velas y te orientamos en las pruebas.
TEXT
		,
	),
	array(
		'slug'        => 'fragancias-para-detergentes-y-suavizantes',
		'title'       => 'Fragancias para detergentes y suavizantes: el aroma que se queda en la ropa',
		'excerpt'     => 'Un detergente se huele al abrir el envase, durante el lavado y en la ropa seca. Te contamos cómo elegir la fragancia para cada momento y cómo evaluarla.',
		'category'    => 'guias-tecnicas',
		'line'        => 'fragancias',
		'collections' => 'detergentes,suavizantes-y-microcapsulas',
		'image'       => array( 'assets/media/colecciones/detergentes.webp', 'Botellas de detergente sin etiqueta y ropa doblada' ),
		'content'     => <<<'TEXT'
Para el cliente, la ropa limpia tiene un olor. Ese aroma es una de las razones por las que elige un detergente o un suavizante y vuelve a comprarlo, y es también uno de los más difíciles de lograr: tiene que resistir el agua, los tensioactivos y el secado.

## Tres momentos del aroma

- <strong>Al abrir el envase:</strong> la primera impresión, que decide la compra en el anaquel.
- <strong>Durante el lavado:</strong> el aroma que perfuma la lavandería o el baño.
- <strong>En la ropa seca:</strong> lo que el cliente realmente busca y recuerda.

## Detergente y suavizante no piden lo mismo

| Aspecto | Detergente | Suavizante
| Función del aroma | Transmitir limpieza | Dejar una huella duradera en la ropa
| Momento clave | El envase y el lavado | La ropa seca y guardada
| Notas habituales | Cítricas, frescas y florales | Florales, almizcladas y de algodón

## Que el aroma se quede: sustantividad

La capacidad de una fragancia de quedarse en la tela se llama sustantividad. La aportan sobre todo las notas de fondo, como los almizcles y algunas maderas, que resisten el enjuague. Las microcápsulas van un paso más allá: encierran la fragancia y la liberan con la fricción, cuando la prenda se usa.

> <strong>Consejo:</strong> si tu cliente valora que la ropa siga oliendo días después, pide una fragancia con buen fondo o una versión para microcápsulas.

## La base manda

Los tensioactivos, el pH, el color y la viscosidad de tu fórmula influyen en cómo se comporta la fragancia. Una que funciona perfecta en un suavizante puede perder fuerza o enturbiar un detergente transparente. Por eso conviene probarla siempre en tu propia base.

## Las familias que mejor funcionan

Las frescas (notas acuáticas y de algodón) transmiten higiene inmediata; las florales aportan suavidad; las cítricas, energía; y las aromáticas, como la lavanda, una sensación de limpieza natural. La guía de familias olfativas te ayuda a elegir el carácter de tu producto.

## Cómo evaluar la fragancia

- Prepara tu producto con dos o tres dosis distintas y obsérvalo unos días: color, transparencia y olor en el envase.
- Lava toallas o prendas de algodón iguales con cada versión.
- Huele la ropa húmeda, recién seca y a los tres y siete días de guardada.
- Compara siempre contra una referencia, a ser posible tu producto actual o el que quieres superar.

En Frave tenemos fragancias para detergentes, suavizantes y microcápsulas, y te acompañamos en las pruebas en tu fórmula.
TEXT
		,
	),
	array(
		'slug'        => 'tendencia-gourmand-en-body-splash',
		'title'       => 'La tendencia gourmand en body splash: aromas dulces que conquistan',
		'excerpt'     => 'Vainilla, caramelo, coco y frutos rojos con crema: por qué las fragancias gourmand triunfan en los body splash y cómo crear una línea sin que resulte empalagosa.',
		'category'    => 'tendencias',
		'line'        => 'fragancias',
		'collections' => '',
		'image'       => array( 'assets/media/familias/gourmand.webp', 'Vainilla, café y chocolate junto a un frasco de fragancia' ),
		'content'     => <<<'TEXT'
Los aromas dulces que recuerdan a postres y golosinas, la llamada familia gourmand, son una de las tendencias más fuertes de los últimos años, sobre todo en los body splash y las brumas corporales. Son fáciles de reconocer, transmiten confort y se recuerdan con facilidad.

## Qué es una fragancia gourmand

Es la que recrea aromas comestibles: vainilla, caramelo, chocolate, café, coco, almendra o praliné. Suele combinarse con notas frutales o florales para no resultar plana. Puedes ver sus notas y su carácter en la guía de familias olfativas.

## Por qué conquista el body splash

- <strong>Confort y nostalgia:</strong> evoca recuerdos agradables y momentos de disfrute.
- <strong>Fácil de usar:</strong> un body splash se aplica varias veces al día, y un aroma dulce y ligero resulta amable.
- <strong>Redes sociales:</strong> los aromas «que huelen a postre» se recomiendan y se comparten mucho.
- <strong>Público joven:</strong> conecta especialmente con consumidores que buscan aromas divertidos y expresivos.

## Las notas más pedidas

| Nota | Carácter | Combina con
| Vainilla | Cálida y envolvente | Frutos rojos, coco, flores blancas
| Caramelo | Dulce y tostado | Manzana, pera, almendra
| Coco | Cremoso y tropical | Piña, vainilla, notas solares
| Frutos rojos con crema | Alegre y jugoso | Vainilla, almizcle
| Café | Intenso y tostado | Vainilla, caramelo, maderas

## Dulce sin empalagar

El riesgo de lo gourmand es saturar. El equilibrio llega con una salida fresca (cítricos o frutas jugosas), un corazón floral o frutal y un fondo suave de almizcle o maderas que dé limpieza al final.

> <strong>Consejo:</strong> en un clima cálido como el de la costa peruana, prefiere versiones gourmand luminosas, con frutas y almizcles, a las muy densas y tostadas.

## Lo que hay que cuidar en un body splash

Un body splash tiene menos concentración de fragancia que un perfume y suele llevar una base de agua y alcohol. La fragancia debe disolverse sin enturbiar el producto, mantener su color y aportar la intensidad justa. Pide fragancias aptas para esta base y pruébalas en tu fórmula.

## Cómo armar tu línea

Una línea de body splash gourmand funciona bien con tres aromas: un clásico (vainilla o caramelo), uno frutal-gourmand (frutos rojos con crema o coco con piña) y uno más fresco para el verano. Así cubres distintos gustos sin duplicar perfiles.

En Frave te ayudamos a elegir las fragancias gourmand para tu línea de cuidado personal.
TEXT
		,
	),
	array(
		'slug'        => 'coleccion-lavavajillas',
		'title'       => 'Colección Lavavajillas: seis aromas para que tu producto se recuerde',
		'excerpt'     => 'Limón, lima, manzana, chicle-uva, avena y sandía: los aromas de nuestra colección para lavavajillas líquidos y cómo elegir el de tu marca.',
		'category'    => 'novedades',
		'line'        => 'fragancias',
		'collections' => 'lavavajillas',
		'image'       => array( 'assets/media/colecciones/lavavajillas.webp', 'Seis lavavajillas de colores con sus ingredientes' ),
		'content'     => <<<'TEXT'
Lavar los platos es una tarea diaria, y el aroma del lavavajillas es lo que la hace más agradable. Es también lo que distingue a una marca en un anaquel lleno de productos parecidos. Por eso preparamos una colección de seis aromas pensados para lavavajillas líquidos.

## Por qué el aroma importa en un lavavajillas

El cliente asocia el aroma con el poder de limpieza: un cítrico intenso se percibe como desengrasante; un aroma suave, como cuidado de las manos. Además, es lo que queda en la cocina y en la piel después de lavar.

## Los seis aromas

| Aroma | Familia | Qué transmite
| Limón | Cítrica | Limpieza y frescura clásicas
| Lima-limón | Cítrica | Energía y un toque verde
| Manzana | Frutal | Frescura jugosa y amable
| Chicle-uva (tutti frutti) | Frutal y gourmand | Un toque divertido y diferente
| Avena | Gourmand suave | Cuidado y suavidad para las manos
| Sandía | Frutal | Frescura veraniega

## Cuál elegir según tu cliente

- <strong>Para una línea clásica:</strong> limón o lima-limón, los aromas que el cliente asocia de inmediato con desengrasar.
- <strong>Para diferenciarte:</strong> manzana, sandía o chicle-uva, frescos y llamativos en el anaquel.
- <strong>Para una versión «cuidado de manos»:</strong> avena, suave y cremosa.

> <strong>Consejo:</strong> acompaña el aroma con el color del producto. Un lavavajillas amarillo que huele a limón o uno rosado que huele a sandía refuerzan la percepción y facilitan la elección.

## Compatibilidad con tu fórmula

Los lavavajillas llevan una carga alta de tensioactivos y, a veces, colorantes y conservantes que pueden influir en la fragancia. Recomendamos probarla en tu base y observar durante algunos días el color, la transparencia y la intensidad del aroma.

## Pide tu prueba

Cuéntanos cómo es tu lavavajillas y te enviamos información de los aromas de la colección para que elijas el de tu marca.
TEXT
		,
	),
	array(
		'slug'        => 'propiedades-organolepticas-de-los-alimentos',
		'title'       => 'Propiedades organolépticas: cómo perciben tus clientes un alimento',
		'excerpt'     => 'Color, aroma, sabor y textura: los atributos que percibimos con los sentidos deciden si un alimento gusta. Qué son, cómo se evalúan y dónde entra el saborizante.',
		'category'    => 'guias-tecnicas',
		'line'        => 'sabores',
		'collections' => '',
		'image'       => array( 'assets/media/fondos/aplicaciones-sabores.webp', 'Jugo, yogur con frutos rojos y panetón sobre fondo arena' ),
		'content'     => <<<'TEXT'
Antes de leer la etiqueta, el cliente ya decidió si un alimento le gusta: lo vio, lo olió y lo probó. Esas características que percibimos con los sentidos se llaman propiedades organolépticas, y entenderlas es la base para desarrollar un producto que se vuelva a comprar.

## Qué son las propiedades organolépticas

Son los atributos de un alimento que se perciben con los sentidos: su color y aspecto, su aroma, su sabor y su textura. No se miden con un instrumento, sino con las personas, y juntas forman la experiencia que el consumidor asocia con un producto y una marca.

## Vista: color y aspecto

Es la primera impresión. El color anticipa el sabor y la frescura: una bebida de fresa muy pálida «sabe» menos a fresa, y un pan dorado promete notas tostadas. También cuentan el brillo, la uniformidad y la presentación. Un cambio de color puede delatar oxidación o un proceso mal controlado.

## Olfato: el aroma

Percibimos el aroma de dos formas: al acercar el alimento a la nariz y desde la boca, cuando los compuestos volátiles suben por detrás del paladar mientras masticamos o bebemos. Esta segunda vía, la retronasal, explica por qué buena parte de lo que llamamos sabor en realidad lo percibe la nariz, y por qué con la nariz tapada todo parece insípido.

## Gusto: los sabores básicos

La lengua distingue cinco sabores básicos: dulce, salado, ácido, amargo y umami. Lo que en la industria se llama <strong>flavor</strong> es la suma de esos sabores, el aroma y otras sensaciones en la boca, como el picor, el frescor de la menta o la astringencia de un té.

## Tacto y oído: la textura

La textura completa la experiencia: lo crocante de una galleta, lo cremoso de un yogur, la viscosidad de un jarabe o el gas de una bebida. Incluso el sonido al morder influye en cómo percibimos la frescura.

| Sentido | Qué percibe | Ejemplo
| Vista | Color, brillo, forma | El dorado de un panetón
| Olfato | Aroma directo y retronasal | La vainilla al abrir un yogur
| Gusto | Dulce, salado, ácido, amargo, umami | La acidez de un néctar de maracuyá
| Tacto | Textura, temperatura, viscosidad | Lo cremoso de un helado
| Oído | Sonido al morder | Lo crocante de un snack

## Cómo usarlas al desarrollar un producto

- Define el perfil que buscas: cómo debe verse, oler, saber y sentirse en la boca.
- Evalúa con un panel de personas en condiciones controladas: misma luz, misma temperatura, muestras codificadas.
- Usa una ficha con los atributos y una escala, y compara siempre con una referencia.
- Repite la evaluación con el producto envasado y tras su vida útil.

> <strong>Consejo:</strong> cambia una sola variable por prueba (el sabor, la dosis o el color). Si cambias varias a la vez, no sabrás cuál mejoró el resultado.

## Dónde entra el saborizante

El saborizante es una de las herramientas más eficaces para ajustar el perfil organoléptico: refuerza o corrige el aroma y el sabor que el proceso debilita, da identidad a una receta y mantiene la consistencia entre lotes. Elegirlo bien depende de la base del producto, del proceso térmico y del perfil que buscas.

En Frave te ayudamos a elegir los sabores para tus alimentos y bebidas, y te acompañamos en las pruebas.
TEXT
		,
	),
);

// Line and collections of the first four articles (written before those fields existed).
$frave_extra = array(
	'como-elegir-el-frasco-para-tu-perfume' => array( 'envases', '' ),
	'marketing-olfativo-para-tu-marca'      => array( 'fragancias', 'aromas-para-ambientacion' ),
	'sabores-para-bebidas-y-lacteos'        => array( 'sabores', '' ),
);

foreach ( $frave_articles as $frave_index => $frave_article ) {
	list( $frave_line, $frave_collections ) = isset( $frave_extra[ $frave_article['slug'] ] ) ? $frave_extra[ $frave_article['slug'] ] : array( $frave_article['line'] ?? '', $frave_article['collections'] ?? '' );
	$frave_cover                            = 'assets/media/blog/' . $frave_article['slug'] . '.webp';
	$frave_cover                            = is_readable( get_theme_file_path( $frave_cover ) ) ? $frave_cover : $frave_article['image'][0];

	$frave_existing = get_page_by_path( $frave_article['slug'], OBJECT, 'post' );
	if ( $frave_existing instanceof WP_Post ) {
		WP_CLI::log( "El artículo «{$frave_existing->post_title}» ya existe." );
		if ( '' !== $frave_line && '' === (string) get_post_meta( $frave_existing->ID, 'frave_line', true ) ) {
			update_post_meta( $frave_existing->ID, 'frave_line', $frave_line );
			update_post_meta( $frave_existing->ID, 'frave_collections', $frave_collections );
		}
		// Its own cover replaces the provisional one, never a cover chosen in WordPress.
		$frave_thumbnail = (int) get_post_thumbnail_id( $frave_existing );
		$frave_source    = $frave_thumbnail ? (string) get_post_meta( $frave_thumbnail, '_frave_source', true ) : '';
		if ( ( ! $frave_thumbnail || '' !== $frave_source ) && $frave_source !== $frave_cover ) {
			$frave_image = frave_blog_image( $frave_cover, $frave_article['image'][1] );
			if ( $frave_image ) {
				set_post_thumbnail( $frave_existing->ID, $frave_image );
				WP_CLI::log( '  Portada actualizada.' );
			}
		}
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
	$frave_image = frave_blog_image( $frave_cover, $frave_article['image'][1] );
	if ( $frave_image ) {
		set_post_thumbnail( $frave_post_id, $frave_image );
	}
	if ( '' !== $frave_line ) {
		update_post_meta( $frave_post_id, 'frave_line', $frave_line );
		update_post_meta( $frave_post_id, 'frave_collections', $frave_collections );
	}
	WP_CLI::log( ( $frave_publish ? 'Artículo publicado: ' : 'Borrador creado: ' ) . $frave_article['title'] );
}

WP_CLI::success( $frave_publish ? 'Blog listo.' : 'Blog listo. Revisa los borradores en Entradas y publícalos cuando estén aprobados.' );
