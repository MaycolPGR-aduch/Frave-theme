# Sistema visual de Frave

- **Estado:** sistema corporativo implementado (fase 2 del plan); pendiente de aprobación de Frave.
- **Última revisión:** 11 de octubre de 2026.
- **Seguimiento:** [PLAN_WEB_CORPORATIVA.md](PLAN_WEB_CORPORATIVA.md).
- **Ver en vivo:** con sesión de administrador, abre `/?sistema-visual` (local o frave.pe). Muestra colores, tipografía, botones, iconos, formas, tarjetas, cifras y movimiento con contenido de ejemplo.

## Dirección

Una web corporativa de captación que tiene que enganchar: titulares grandes y ligeros, superficies oscuras con profundidad, un único acento naranja y movimiento al hacer scroll. La referencia de estructura y ritmo es [disaromas.com](https://disaromas.com/); la identidad es de Frave:

- **Base corporativa:** blanco, arena y tinta, con azul marino en el pie (Frave no usa negro). El naranja de marca guía la mirada y no se usa como fondo de grandes superficies.
- **Motivo gráfico propio:** la gota y el frasco del isotipo recortan fotografías, aparecen como marca de agua y dan forma a los iconos.
- **Movimiento progresivo:** todo se ve sin JavaScript y se detiene con `prefers-reduced-motion`.

Lo que se evita de la referencia: librerías por CDN (jQuery, Swiper, AOS), imágenes sin optimizar, títulos sin `h1`, botones sin destino y metadatos duplicados.

## Fuentes de verdad

| Elemento | Archivo |
| --- | --- |
| Tokens (color, radios, sombras, movimiento, escala tipográfica) | `assets/src/css/app.css` (`@theme` y `:root`) |
| Tipografía: `.display`, `.lead`, `.pill`, `.mark`, `.section-head` | `assets/src/css/system/typography.css` |
| Botones `.btn` | `assets/src/css/system/buttons.css` y `frave_button()` en `inc/helpers.php` |
| Superficies, tarjetas, iconos en mosaico, cifras y banda de cabecera | `assets/src/css/system/surfaces.css` y `template-parts/components/page-hero.php` |
| Formas, isotipo e iconos | `assets/src/css/system/shapes.css` y `inc/icons.php` |
| Movimiento | `assets/src/css/system/motion.css` y `assets/src/js/modules/` |
| Carrusel | `assets/src/css/system/carousel.css` y `assets/src/js/modules/carousel.js` |
| Cabecera, menús, pie, WooCommerce y páginas institucionales | `assets/src/css/app.css` |
| Paleta del editor | `theme.json` |

El orden de capas CSS se fija al principio de `app.css` (`@layer theme, utilities, base, components;`): los archivos del sistema se importan antes y, sin esa línea, su capa `components` quedaría por debajo de `base`.

## Color

| Token | Valor | Uso | Contraste |
| --- | --- | --- | --- |
| `sand` | `#f1e6d8` | Banda de páginas, secciones destacadas y panel del catálogo (el beige de las fotos) | Tinta 12,9:1 |
| `sand-soft` / `sand-line` | `#f8f1e8` / `#e2d3bf` | Matiz y bordes sobre arena | — |
| `sand-muted` / `sand-accent` | `#5c5248` / `#a83200` | Texto secundario y textos de acento sobre arena | 6,2:1 / 5,5:1 |
| `navy` / `navy-2` | `#1d2b4f` / `#24365e` | Pie, botones oscuros y controles seleccionados | Blanco 14:1 |
| `orange-light` | `#ff8a5c` | Textos de acento sobre azul marino (el naranja de marca da 4,1:1) | 6:1 |
| `ink` / `foreground` | `#202329` | Texto principal | Sobre blanco 15,9:1 |
| `brand-orange` / `accent` | `#fc4c02` | Líneas, iconos, píldoras, títulos grandes | Sobre blanco 3,4:1 (solo texto grande) |
| `primary` | `#c43b00` | Botones con texto blanco, enlaces y acentos sobre claro | Blanco 5,3:1 |
| `primary-hover` | `#a83200` | Hover de botones primarios | Blanco 6,7:1 |
| `orange-soft` | `#fff1ea` | Fondo de iconos y píldoras suaves | Primario encima 4,8:1 |
| `orange-tint` | `#ffe1d1` | Matices de ilustración | — |
| `surface` / `surface-2` | `#f6f4f1` / `#efebe6` | Secciones alternas, fondos de imagen | Texto secundario 4,9:1 |
| `line` / `border` | `#e5e1dc` | Bordes y divisores | — |
| `muted` | `#6b6b68` | Descripciones | Sobre blanco 5,4:1 |
| `on-dark` / `on-dark-muted` | `#f4f2ef` / `#c3cadb` | Texto sobre azul marino | 13:1 / 8,5:1 |
| `success`, `warning`, `error` | `#276749`, `#9c6b1b`, `#b42318` | Estados | — |
| `whatsapp` / `whatsapp-dark` | `#0f7d6f` / `#075e54` | Botones de WhatsApp (el verde de marca da 1,98:1) | Blanco 5,0:1 |

**Logotipo:** la cabecera usa solo las letras (`assets/images/frave-wordmark.webp`, «FRAVE / Fragancias y envases» en naranja, sin el frasco); el pie conserva el logotipo completo. **Introducción de entrada:** la primera página de una visita (de nuevo pasadas 12 horas) se abre con el naranja de marca en degradado radial y las letras en blanco, con una línea que se completa; luego se levanta como un telón (o se desvanece, con movimiento reducido).

Frave no usa negro: desde el 15-10-2026 las superficies que eran negras son arena (secciones y bandas) o azul marino (pie, botones oscuros y controles seleccionados). El azul es un color de apoyo; el logotipo y la marca siguen en naranja.

Regla práctica: sobre blanco, el naranja de marca solo en títulos de 24 px o más y en elementos sin texto; para texto y botones, `primary`. Sobre arena, los textos pequeños de acento van en `sand-accent`; sobre azul marino, en `orange-light`.

## Tipografía

Lato local (`@fontsource/lato`) en 300, 400 y 700, con precarga de los tres WOFF2.

| Rol | Tamaño | Peso |
| --- | --- | --- |
| Display (hero) `.display` | `clamp(3rem, 7vw, 6.25rem)` | 300, interlineado 1 |
| `h1` | `clamp(2.6rem, 5.4vw, 4.6rem)` | 300 |
| `h2` | `clamp(2.1rem, 4vw, 3.4rem)` | 300; 700 en paneles, formularios, WooCommerce y pie |
| `h3` | `clamp(1.3rem, 2vw, 1.6rem)` | 700 |
| Entrada `.lead` | `clamp(1.1rem, 1.6vw, 1.3rem)` | 400, color `muted` |
| Texto | 1rem, interlineado 1,6 | 400 |
| Rótulo `.eyebrow` | 0,75rem, mayúsculas, espaciado 0,2em, línea naranja delante | 700, color `primary` (naranja sobre oscuro) |

El énfasis dentro de un título se hace con `<strong>` (700), con `.pill` (palabra sobre naranja; `--soft`, `--ink`, `--outline`) o con `.mark` (subrayado de rotulador). **En las cabeceras** (portada, líneas, empresa, soluciones, guía y banda de cabecera) la palabra destacada va en naranja `primary` y negrita (`.accent-word`, o `<strong>` en la banda), sin píldora, y detrás van las **ondas de aroma** (`template-parts/components/aroma-waves.php`): tres anillos naranjas alrededor de la gota de marca. Solo en la portada se mueven y las palabras se alternan; en las demás quedan quietas, alrededor de la imagen (Nosotros, soluciones, guía, categorías), a la derecha (banda de cabecera) o detrás del texto (líneas, Servicios). `.section-head` agrupa rótulo, título y entrada; `--center` los centra y `--split` pone una acción a la derecha.

## Espacio, radios y sombras

- Contenedor de 80rem con márgenes fluidos (`--site-gutter`). Las páginas interiores alinean su contenido con el logo y limitan la lectura a 58rem.
- Secciones con `--section-space` = `clamp(4.5rem, 9vw, 8rem)`.
- Radios: `--radius-sm` 0,75rem, `--radius-md` 1,25rem (tarjetas), `--radius-lg` 2rem (marcos), `--radius-xl` hasta 3,25rem (bandas y secciones superpuestas).
- Sombras: `--shadow-soft` (reposo), `--shadow-lift` (tarjeta al pasar el puntero, cabecera al hacer scroll), `--shadow-header`.

## Componentes

| Componente | Clases | Notas |
| --- | --- | --- |
| Botón | `.btn` + `--primary`, `--dark`, `--light`, `--outline`, `--sm`; `.btn__icon`; `.btn-row` | Píldora con icono circular que avanza en hover. No se llama `.button` porque WooCommerce restyliza `.button` en sus páginas. `frave_button( $label, $url, $classes, $icon )` lo genera. |
| Enlace con flecha | `.link-arrow` | Acción secundaria. |
| Cabecera | `.site-header`, `.site-header__inner` | Cápsula blanca flotante, fija arriba; se eleva al hacer scroll (`.is-scrolled`). Superpone la página (`--header-height`), así las bandas oscuras corren por detrás. |
| Submenú | `.sub-menu`, `.menu-item__icon`, `.menu-item__description` | Panel con iconos en escritorio, acordeón en móvil. Las líneas toman su icono de la categoría; otros elementos con la clase `frave-icon-NOMBRE`. |
| Menú móvil | `.primary-navigation.is-open` | Tarjeta bajo la cápsula con «Solicitar asesoría» al final. |
| Banda de cabecera | `template-parts/components/page-hero.php` | Fondo arena con resplandor naranja suave, ondas de aroma a la derecha (alrededor de la imagen si la hay), esquinas inferiores redondeadas. Rótulo, título, entrada, línea de datos, migas y buscador opcionales. La usan las páginas, entradas, archivo, búsqueda y el catálogo. |
| Superficies | `.section--dark` / `.on-dark`, `.section--surface`, `.section--overlap` | Lo oscuro ajusta títulos, rótulos, píldoras, iconos y botones de contorno. |
| Tarjeta | `.card` + `--surface`, `--dark`, `--interactive`; `.card__link` | La interactiva sube y gana sombra; `.card__link` hace clicable toda la tarjeta con foco visible. |
| Icono en mosaico | `.icon-tile`, `--lg` | Naranja suave sobre claro, naranja translúcido sobre oscuro. |
| Cifra | `.stat`, `.stat__value`, `.stat__label` | Con `data-count-to` cuenta al aparecer. |
| Tarjeta de producto | `.product-card__media`, `.product-card__category` | Marco con zoom de imagen y categoría sobre el título. |
| Pie | `.site-footer` | Azul marino con isotipo, franja «¿Tienes un proyecto en mente?» y botones, columnas con iconos de contacto y Libro de Reclamaciones. |

## Formas e iconos

- **Marcos** `.media-frame` + `.shape-drop` (gota), `.shape-bottle` (frasco con cuello) o `.shape-arch` (arco). Proporción 4:5; la máscara recorta la imagen.
- **Isotipo** `frave_isotype()`: frasco con gota a línea, para marcas de agua y sellos (`.brand-seal`).
- **Órbitas** `.orbit` (naranja) y `.orbit--soft` (blanca) para profundidad.
- **Separador** `.divider-drop`: línea, gota, línea.
- **Iconos** `frave_icon( $nombre )`: trazo 1,6 con remates redondeados sobre 24 px, color del texto. Lista completa en `frave_icon_paths()` y en `/?sistema-visual`. Sabores, Fragancias y Envases tienen icono propio (`frave_product_line_icon()`).

## Movimiento

| Efecto | Uso | Implementación |
| --- | --- | --- |
| Aparición | `data-reveal="up|left|right|scale|fade|clip"`, `data-reveal-group` (escalonado hasta 7) | `section-transitions.js` con `IntersectionObserver`. |
| Hero fijo y telón | `.pin` seguido de `.curtain` | CSS: el hero queda fijo y la sección siguiente sube con esquinas redondeadas y sombra. |
| Apilado | `.stack[data-stack] > .stack__item` | Las tarjetas se fijan bajo la cabecera y se encogen al cubrirlas la siguiente (`stack.js`). |
| Carrusel | `.carousel[data-carousel]` | Scroll nativo con *scroll snap* (táctil, trackpad, flechas con foco); `carousel.js` gestiona anterior y siguiente y los oculta si todo cabe. |
| Pie que se descubre | automático | En pantallas desde 64rem de ancho y 46rem de alto, el contenido se despega del pie al final. |
| Cabecera | automático | Sombra mayor al hacer scroll (`header-scroll.js`). |
| Contadores | `data-count-to` | `counter.js`; el valor final está en el HTML. |

Duraciones y curvas en `--duration-fast` (160 ms), `--duration` (320 ms), `--duration-slow` (750 ms) y `--ease-out`.

## Portada

| Sección | Superficie | Detalles |
| --- | --- | --- |
| Hero | Beige del vídeo `#e9d6c0` | Vídeo a la izquierda con degradado hacia el texto; en celular, un vídeo vertical propio (9:16) de fondo, que sube detrás del final del texto con las frutas cayendo debajo; en tableta, el horizontal a todo el ancho bajo el texto, fundido con el beige; la imagen fija va debajo del vídeo y cada pantalla descarga solo la suya; título display en tres renglones cuyas palabras destacadas se alternan en naranja `#c43b00` y negrita («conectan con», «distinguen a», «elevan», «dan vida a»), sobre ondas de aroma que salen de la gota de marca; fila de datos de confianza con vistos; rótulo `#a83200` y entrada `#4a4642` por contraste sobre beige; fijo en escritorio. Palabras y ondas dan dos vueltas (unos 20 s) y se aquietan; el mismo botón del vídeo las pausa o las repite; con movimiento reducido no se mueven. |
| Nuestras soluciones | Blanco, sube sobre el hero con esquinas redondeadas | Tarjetas con marco de frasco; sin imagen, degradado de la línea con su icono. Carrusel en móvil, tres columnas en escritorio. |
| Cómo trabajamos | Arena con órbita, pasos en tarjetas blancas | Introducción fija a la izquierda y pasos apilados a la derecha con números grandes en naranja. |
| Destacados | Blanco | Cuatro tarjetas de producto. |
| Razones | Superficie | Cuatro tarjetas con icono grande. |
| Catálogo | Panel arena redondeado | Buscador y accesos a las líneas en píldoras. |
| Tendencias | Blanco | Tarjetas de entrada; sin imagen, el isotipo. |
| Dónde estamos | Blanco | Canales con iconos y mapa bajo demanda. |

El vídeo se reproduce una vez y descansa en su último fotograma, que también es la imagen fija. Recomendado: MP4 H.264 sin sonido, 1920×1080, unos 8 segundos, con el sujeto a la derecha y el tercio izquierdo despejado para el texto.

## Páginas de línea

Misma estructura para Sabores, Fragancias y Envases, con el tono de cada imagen (`--line-tone`):

- **Hero:** la imagen horizontal llena la pantalla en escritorio y se funde a la izquierda con el tono de la línea, donde va el texto; en pantallas anchas empieza junto al texto centrado. En móvil, la imagen vertical va debajo del texto y su parte superior se desvanece en el tono. Queda fija mientras sube la presentación.
- **Presentación:** frase grande con énfasis en naranja oscuro y tres destacados en superficie cálida; un título que empieza con cifra («+500 referencias») se anima al aparecer.
- **Portafolio en bandas (líneas y soluciones, desde el 23-10):** una banda por categoría a todo el ancho, alternando el lado de la imagen y el fondo (blanco y arena suave). En escritorio la imagen (3:2) ocupa media pantalla y queda fija mientras se recorre el texto, así que al señalar cualquier tipo su imagen se ve completa; el texto se alinea con el contenido de la página. En el celular la imagen va primero, a todo el ancho, y pasa sola por la de la categoría y la de cada tipo (resaltando su fila), con una línea de progreso y un botón de pausa; sin movimiento reducido. Las páginas de categoría siguen con las tarjetas que se describen a continuación.
- **Portafolio (tarjetas, páginas de categoría):** tarjetas blancas numeradas sobre superficie que se elevan al pasar el cursor; la imagen, el nombre y «Ver detalle» llevan a la página de detalle de la categoría. En Fragancias van a dos columnas porque sus subcategorías tienen tipos: bajo «Elige un tipo», una fila por tipo con miniatura, nombre y flecha en círculo que se rellena de naranja; al señalar una fila (o enfocarla con el teclado), la imagen de la tarjeta se funde con la de ese tipo y una píldora lo nombra (`term-cards.js`; las imágenes de los tipos solo se descargan cuando el cursor o el foco llega a la tarjeta).
- **Aplicaciones:** sección oscura con dos filas de palabras grandes (una con contorno, otra sólida) separadas por la gota de marca, que se desplazan en sentidos opuestos según el scroll. No se mueven solas, así que no necesitan botón de pausa; con movimiento reducido quedan quietas. La lista legible está debajo.
- **Cierre:** panel con la imagen de contacto (ingredientes en los bordes y el texto en el centro) y las otras líneas con imagen en arco.

Imágenes de línea: horizontal 16:9 con el tercio izquierdo despejado y vertical 4:5 con la parte superior despejada, fondo marfil y luz suave desde arriba a la izquierda.

## Nosotros y Experticia

- **Hero cálido** (#f2e2cc) a dos columnas: texto a la izquierda; a la derecha, los ingredientes en la gota de marca (Nosotros) o la nube de palabras del Perú (Experticia). La sección siguiente sube con las esquinas redondeadas.
- **Nube del Perú:** silueta en marfil con borde naranja, el isotipo en el centro y los 56 términos en mayúsculas de Lato: los principales en naranja oscuro, los secundarios en tinta y el resto en gris. Aparecen uno tras otro desde el centro; con movimiento reducido se ven de inmediato.
- **Esencia:** frase grande y ligera sobre arena, con el énfasis en naranja y el isotipo como sello de fondo.
- **Creencias:** tarjetas blancas sobre superficie con icono, número grande en naranja claro y el comportamiento separado por una línea, en naranja oscuro.
- **Cómo trabajamos:** sobre arena, pestañas verticales con una barra naranja en la activa y un panel con el número en contorno; en móvil, acordeón con chevron.
- **Bloques compartidos** (`system/blocks.css`): frase con explicación (`.statement`), destacados (`.highlights`), banda de palabras (`.ticker`) y cierre con la imagen de contacto (`.cta-panel`).

## Contacto

- **Cabecera** oscura con espacio extra abajo: las dos tarjetas suben sobre ella. Bajo la introducción, tres ventajas con icono naranja en un círculo translúcido.
- **Formulario:** tarjeta blanca con sombra de elevación; título ligero con la parte importante en negrita; bloques con su número en un círculo naranja suave. Campos sobre la superficie clara, sin borde, que al enfocarse pasan a blanco con borde `primary` y un anillo naranja translúcido. Tema y línea como píldoras: la elegida en tinta con una marca que aparece delante; «Aún no lo sé» con borde punteado. Consentimiento en un bloque claro y botón naranja con la flecha en un círculo, que gira mientras envía.
- **Canales:** tarjeta marfil con WhatsApp en una tarjeta blanca (icono verde y botón verde a todo el ancho), canales con icono en círculo naranja suave, redes con borde, pasos numerados con círculo de borde naranja y enlaces con flecha.
- **Enviado:** bloque verde muy claro con el visto en un círculo verde, título ligero y acciones (WhatsApp y catálogo).

## Soluciones y chat de WhatsApp

- **Soluciones:** el hero cálido de las páginas de empresa con una imagen en arco; las tarjetas de lo que se ofrece son las del portafolio de línea, con la línea de origen junto al número («01 · FRAGANCIAS»). La página central usa tarjetas con icono.
- **Chat de WhatsApp:** panel sobre el botón flotante con la cabecera en el verde oscuro de WhatsApp (5:1 con blanco), la pregunta como un mensaje recibido y las opciones como botones con borde verde. En móvil ocupa el ancho de la pantalla menos los márgenes.

## Ficha de producto y catálogo

- **Ficha:** a la izquierda, la foto sobre la superficie marfil (queda fija al bajar en escritorio); a la derecha, la línea y la subcategoría como texto superior enlazado, el título ligero, la descripción corta, los datos del producto como etiqueta en mayúsculas y valores en píldoras, la cotización sobre naranja suave y el código. Debajo, «Detalles del producto» y «También te puede interesar».
- **Catálogo:** en una categoría con subcategorías, estas aparecen como píldoras sobre los productos, con su número de productos.
- **Fotos:** cuadradas, fondo marfil liso, producto centrado con aire (ver [GUIA_CATALOGO.md](GUIA_CATALOGO.md)).

## Blog

- **Página de entradas:** banda de cabecera con migas de pan; temas como píldoras (el activo en tinta); el último artículo como tarjeta ancha con imagen a la izquierda, título ligero grande y «Leer artículo»; el resto en una rejilla de tres columnas en escritorio.
- **Tarjeta de artículo:** imagen 16:10 sobre la superficie marfil (o el isotipo si no hay imagen), tema en naranja oscuro y fecha en mayúsculas espaciadas, título en negrita y extracto en gris. Toda la tarjeta es el enlace.
- **Artículo:** título ligero de hasta 3,6 rem en la banda oscura, fecha y tiempo de lectura; imagen de portada redondeada; texto de 44 rem como máximo a 1,1 rem con interlineado 1,75, viñetas naranjas y citas con borde naranja; en escritorio, «Compartir» en una columna estrecha que acompaña la lectura. Botones de compartir como píldoras con el logo de cada red en su color.
- **Redes sociales:** logos en círculos que, al pasar el cursor o con el foco, se rellenan desde el centro con el color de cada red (degradado de Instagram; negro con el eco cian y rojo de TikTok; azules de Facebook y LinkedIn; rojo de YouTube). En la cabecera van en tinta junto a la búsqueda, separados por una línea; en el menú móvil, con borde bajo «Síguenos»; en el pie, con borde claro sobre el azul marino.

## Accesibilidad

HTML semántico, enlace para saltar al contenido, foco visible en todos los controles (también en la búsqueda, que muestra un anillo en toda la píldora), menús con `aria-expanded`, Escape y retención del foco, carrusel navegable con teclado y movimiento reducido.

Auditoría de la fase 7 (13-10-2026): axe con WCAG 2.2 AA en todas las plantillas (escritorio y móvil, con menú y chat abiertos), recorrido con teclado, esquema de encabezados y regiones, y reajuste a 320 px. Quedan como avisos aceptados las palabras del ticker y los números de las creencias: son decorativos y están ocultos a los lectores de pantalla. Pendiente: una pasada con lector de pantalla con el contenido definitivo.

Regla aprendida: el color de enlace global de `theme.json` no tiene capa y gana a cualquier color del tema en un enlace; los enlaces con fondo propio (botones, píldoras activas, enlace de salto, paginación) declaran su color con `!important`.

## Registro de cambios y decisiones

| Fecha | Cambio | Estado |
| --- | --- | --- |
| 2026-09-23 | Se documenta el sistema visual inicial observado en el tema. | Base provisional |
| 2026-09-23 | Se usa `primary` oscuro para texto blanco y se añaden secciones editoriales controladas. | Implementado |
| 2026-09-28 | Botones de WhatsApp en verde oscuro accesible; el flotante queda bajo el menú móvil y en móvil muestra solo el icono. | Implementado |
| 2026-09-28 | Mini-carrito lateral con «Finalizar compra» como acción principal. | Implementado (en pausa con el modo catálogo) |
| 2026-10-05 | Botones de WooCommerce con el estilo del tema (`woocommerce-block-theme-has-button-styles`) y etiqueta «¡Oferta!» en `primary`. Páginas WooCommerce alineadas con el sistema. | Implementado |
| 2026-10-10 | Modo catálogo: menú con submenús, «Solicitar asesoría», bloque de cotización en la ficha y «Ver detalles» con contorno. | Implementado |
| 2026-10-11 | Sistema corporativo (fase 2): tokens noche y naranja suave, Lato 300 en títulos, botones `.btn` con icono, píldoras y marcador, cabecera en cápsula flotante, submenús con iconos, banda de cabecera en páginas y catálogo, pie oscuro con llamada a la acción, formas de gota y frasco, isotipo, iconos de línea, tarjetas, cifras, carrusel, apilado, hero fijo con telón y pie que se descubre. Guía en `/?sistema-visual`. | Implementado; pendiente de aprobación de Frave |
| 2026-10-15 | Sin negro, a pedido de Frave: secciones destacadas, banda de páginas y panel del catálogo en arena (`sand`, el beige de las fotos) con tinta y acentos en naranja oscuro; pie, botones oscuros y controles seleccionados en azul marino (`navy`), con textos de acento en naranja claro. Contrastes AA comprobados. | Implementado |

| 2026-10-13 | Página de contacto rediseñada (cabecera con ventajas, formulario en bloques con opciones como píldoras, canales con WhatsApp destacado y confirmación con siguientes pasos); widget de WhatsApp como región propia. | Implementado |
| 2026-10-13 | Redes sociales en cabecera, menú móvil y pie; entre 1024 y 1280 px la cabecera compacta logo, letra y espacios para que el menú quepa con sus flechas. | Implementado |
| 2026-10-13 | Blog (tarjetas, artículo, compartir), redes sociales en el pie, menú de siete entradas que cabe desde 1024 px, enlace de salto legible y migas de la ficha subrayadas. | Implementado |

Al aprobar o cambiar una decisión visual, actualiza esta guía, los tokens y `theme.json`; compila los assets y revisa `/?sistema-visual`, portada, catálogo, ficha y contacto en móvil y escritorio. Registra qué cambió y por qué.
