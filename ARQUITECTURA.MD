# Arquitectura del tema Frave

- **Estado:** implementación inicial funcional.
- **Última revisión:** 11 de octubre de 2026.
- **Seguimiento:** [PLAN_WEB_CORPORATIVA.md](PLAN_WEB_CORPORATIVA.md) (plan vigente) y [ROADMAP.md](ROADMAP.md).

## Límites del sistema

Frave es un tema WordPress clásico con templates PHP. WordPress administra páginas, usuarios, medios y editor. WooCommerce administra productos, categorías, variaciones, precios, inventario, carrito, checkout, pedidos y pagos. ACF gratuito añade campos estructurados para el contenido editorial de la portada y puede desactivarse sin provocar un error fatal. El tema controla el HTML, CSS, navegación y presentación del comercio.

No se usa constructor visual, frontend desacoplado ni React. `theme.json` configura el editor y su paleta; no convierte el tema en Full Site Editing. La configuración de tienda y los datos de negocio pertenecen a la base de datos de cada instalación, fuera de Git.

## Mapa del repositorio

| Ruta | Responsabilidad actual |
| --- | --- |
| `functions.php` | Define constantes y carga los módulos de `inc/`. |
| `inc/setup.php` | Soporte del tema, menús, tamaños de imagen y ancho de contenido. |
| `inc/helpers.php` | Lectura opcional de ACF, URL de assets y botón del sistema (`frave_button()`, `.btn` con icono). |
| `page-templates/contacto.php`, `template-parts/components/contact-channels.php`, `assets/src/css/pages/contact.css`, `assets/src/js/modules/contact-form.js` | Página de contacto: cabecera con ventajas, formulario del plugin con su estilo, canales de atención y pasos; contador del mensaje y estado de envío; siguientes pasos tras enviar (`frave_peru_contact_success`). |
| `template-parts/components/social-links.php`, `assets/src/css/system/social.css` | Logos de las redes sociales en la cabecera, el menú móvil y el pie. |
| `inc/icons.php` | Iconos de línea (`frave_icon()`), logos de redes sociales (`frave_brand_icon()`), icono de cada línea de producto y el isotipo en SVG (`frave_isotype()`). |
| `inc/style-guide.php`, `template-parts/style-guide.php` | Guía interna del sistema visual en `/?sistema-visual`, solo para quien puede editar el tema y sin indexar. |
| `inc/enqueue.php` | Carga Vite en desarrollo o CSS y JS desde el manifest compilado. |
| `inc/woocommerce.php` | Wrappers del contenido, banda de cabecera del catálogo (título, descripción, migas y buscador), columnas, marco e imagen y categoría de las tarjetas, fragmento del contador del carrito, `frave_catalog_mode()` / `frave_shop_enabled()` y botón «Ver detalles» en modo catálogo. |
| `inc/navigation.php` | Menús de respaldo con la estructura corporativa (solo enlaces que existen), botones de submenú y descripciones para los menús de WordPress, y llamada a la acción de la cabecera. |
| `inc/acf.php` | Registra `acf-json/` como ruta de carga de grupos ACF. |
| `inc/catalog-filters.php` | Filtros del catálogo: consultas de disponibilidad y oferta, recuentos, URL, chips de filtros activos, maquetación a dos columnas y `noindex` de vistas filtradas. |
| `template-parts/catalog/` | Panel de filtros, barra de herramientas (botón «Filtrar», resultados y orden) y chips de filtros activos. |
| `assets/src/js/modules/drawer.js` | Cajón lateral modal compartido por el mini-carrito y los filtros: foco, Escape, fondo y un solo cajón abierto. |
| `inc/institutional.php`, `page-templates/` | Plantillas Contacto, Preguntas frecuentes y Política o texto legal; anclas e índice de secciones, datos `FAQPage`, enlaces legales del pie (menú `legal` o automático). |
| `patterns/` | Estructuras iniciales de las páginas institucionales (categoría «Frave: páginas institucionales»), con marcadores `[PENDIENTE]`. |
| `inc/category-detail.php` | Página de detalle de cada categoría del catálogo, donde se unen Líneas y Soluciones: «Se usa en» (soluciones que la usan), botón de cotizar, sus subcategorías en tarjetas y, sin productos, invitación a cotizar en lugar de la lista vacía y los filtros. |
| `inc/contact.php` | Ajustes de WhatsApp, canales de contacto y redes sociales en el Personalizador, enlaces `wa.me`, botón de consulta en la ficha y, en modo catálogo, bloque «¿Te interesa este producto?» con el enlace de cotización (`?producto=ID`). |
| `front-page.php`, `page.php`, `search.php`, `index.php`, `404.php` | Jerarquía de templates clásicos (los del blog, en su propia fila). |
| `header.php`, `footer.php` | Estructura global, navegación, búsqueda, carrito y pie. |
| `front-page.php`, `template-parts/home/` | Portada: hero con vídeo, líneas, cómo trabajamos, destacados, razones, catálogo, tendencias y ubicación. |
| `inc/home.php` | Textos por defecto de la portada (única fuente, también para los valores por defecto de ACF), resaltado con `*asteriscos*`, vídeo del hero y precarga de su imagen fija, datos de las tres líneas e iconos de las razones. |
| `inc/lines.php`, `page-templates/linea.php`, `template-parts/line/` | Páginas de línea (plantilla «Línea»): textos por defecto acordados con la gerencia, categoría de la página, portafolio desde las subcategorías, enlaces de cotización `?linea=`, imágenes y enlaces a la línea desde la portada, el menú y el catálogo. |
| `inc/company.php`, `page-templates/nosotros.php`, `page-templates/experticia.php`, `template-parts/company/` | Páginas Nosotros y Experticia: textos por defecto de la gerencia (única fuente, también para ACF), creencias, nube de palabras del Perú, «Cómo trabajamos» y las industrias de cada línea. |
| `inc/solutions.php`, `page-templates/soluciones.php`, `page-templates/solucion.php`, `template-parts/solutions/` | Soluciones por industria: textos por defecto, categorías de cada línea que usa cada industria, página central y enlaces del menú. |
| `template-parts/components/aroma-waves.php`, `assets/src/css/system/shapes.css` (`.aroma-waves`, `.hero-visual`, `.accent-word`) | Lenguaje común de las cabeceras: ondas de aroma alrededor de la gota de marca y palabra destacada en naranja y negrita (`frave_emphasis( …, 'accent' )`). |
| `template-parts/home/letters.php`, `assets/src/js/modules/letters.js`, `assets/media/letras/` | Portada: FRAVE grande con una foto de flor en cada letra (máscara recortada del logotipo, una baldosa por letra; A y V cortadas en diagonal), sola, entre el hero y «Nuestras líneas»; las fotos se desplazan al bajar y se acercan al pasar el cursor. |
| `template-parts/components/section-bg.php`, `assets/media/fondos/` | Foto de fondo de una sección bajo un velo (arena hacia el texto, o claro para la textura de luz y sombra), decorativa y con carga diferida: Nosotros «Nuestra esencia», panel del catálogo en la portada, franjas de aplicaciones de cada línea, y la textura en «Razones para elegirnos», «Industrias que atendemos» y la banda de familias de Fragancias. |
| `inc/intro.php`, `assets/images/frave-wordmark.webp` | Introducción de entrada (estilo y script en línea en `<head>` y tras `<body>`; `localStorage` recuerda la visita 12 horas; se omite con `navigator.webdriver` y con el filtro `frave_show_intro`) y logotipo de letras de la cabecera, que la introducción reutiliza en blanco. |
| `inc/fragrance-families.php`, `page-templates/familias-olfativas.php`, `template-parts/line/families.php`, `template-parts/components/family-wheel.php`, `assets/src/js/modules/family-wheel.js`, `assets/src/css/pages/families.css` | Guía de familias olfativas y rueda interactiva de familias (solución Perfumería): las nueve familias del catálogo (notas, qué transmiten, usos, color), familias por aplicación, enlace al catálogo filtrado cuando hay fragancias de esa familia y banda de familias en la página de Fragancias; la rueda es una lista de pestañas accesible que gira hacia la familia elegida. |
| `template-parts/solutions/cards.php`, `assets/src/js/modules/industry-panels.js` | Industrias en paneles que se expanden (página Soluciones y «Otras industrias»): uno abierto a la vez; en táctil, el primer toque abre y el segundo entra. |
| `template-parts/components/term-card.php` (variante `layout => band`), `assets/src/js/modules/term-cards.js` | Bandas del portafolio en líneas y soluciones: imagen fija mientras se recorren los tipos (escritorio) y ciclo automático con progreso y pausa (celular). |
| `template-parts/components/term-card.php`, `template-parts/components/applications.php` | Tarjeta de subcategoría (portafolio y soluciones) y banda de aplicaciones. |
| `template-parts/components/whatsapp-button.php`, `assets/src/js/modules/whatsapp-chat.js` | Botón flotante y chat de WhatsApp guiado (motivo, detalles y mensaje armado; no guarda datos). |
| `template-parts/components/highlights.php`, `template-parts/components/cta-panel.php` | Destacados con cifra animada y cierre con la imagen de contacto, compartidos por las páginas de línea y de empresa. |
| `inc/seo.php`, `assets/media/og/`, `assets/images/favicon*` | Mientras no haya un plugin SEO: descripción (o el extracto de la página) y etiquetas Open Graph de cada página (imágenes JPG de 1200 × 630 para compartir), datos estructurados JSON-LD (Organization, WebSite, BlogPosting, BreadcrumbList), canonical de listados y `noindex` de la búsqueda; favicon con el isotipo mientras no haya icono del sitio. |
| `inc/blog.php`, `home.php`, `category.php`, `archive.php`, `single.php`, `template-parts/blog/`, `template-parts/components/post-tile.php`, `assets/src/css/pages/blog.css`, `assets/src/js/modules/share.js` | Blog: página de entradas, temas, tarjeta de artículo, migas de pan, tiempo de lectura, enlaces para compartir (copiar enlace y menú del sistema con JavaScript), artículos relacionados; el blog entra en los menús cuando tiene artículos y la entrada de ejemplo de WordPress queda fuera. |
| `bin/crear-blog.php` | Crea la página /blog/, sus categorías y cuatro artículos de inicio en borrador (no se despliega). |
| `inc/product-page.php`, `assets/src/css/pages/product.css` | Ficha de producto: línea y subcategoría sobre el título, datos (atributos) a la vista con las medidas de menor a mayor, cotización, código y descripción como sección; orden de tamaño de los términos de presentación y capacidad. |
| `bin/crear-atributos.php`, `bin/plantilla-catalogo.py`, `bin/muestra-catalogo.csv`, `PLANTILLA_IMPORTACION_WOOCOMMERCE.xlsx` | Atributos globales de las líneas, plantilla de importación con ejemplos, categorías y atributos, y catálogo de muestra para pruebas locales (ver [GUIA_CATALOGO.md](GUIA_CATALOGO.md)). |
| `bin/crear-soluciones.php` | Crea /soluciones/ y una página por industria (no se despliega). |
| `inc/fragrance-pyramid.php`, `page-templates/piramide-olfativa.php`, `template-parts/components/fragrance-pyramid.php`, `template-parts/line/pyramid.php`, `assets/src/js/modules/tabs.js`, `assets/src/css/pages/pyramid.css` | Pirámide olfativa aplicada: niveles (tiempos, notas, familias, qué aportan al producto), pirámide interactiva hecha con las tres pestañas recortadas con `clip-path`, línea de tiempo, productos y prueba de la tira; pestañas genéricas reutilizables; sección de la pirámide en Fragancias y enlaces de la guía en el menú «Catálogo» y en el pie (`frave_fragrance_guide_links()`). |
| `bin/crear-guia-fragancias.php` | Crea /fragancias/familias-olfativas/ y /fragancias/piramide-olfativa/ con sus plantillas (no se despliega). |
| `bin/crear-paginas-empresa.php`, `bin/nube-peru.py` | Crean las páginas /nosotros/ y /experticia/, y generan la nube de palabras (`assets/media/experticia/`). No se despliegan. |
| `bin/crear-lineas.php` | Crea con WP-CLI el árbol de subcategorías acordado y las páginas /sabores/, /fragancias/ y /envases/ (no se despliega con el tema). |
| `assets/media/` | Vídeo por defecto del hero (MP4 y WebM sin sonido) y su último fotograma; imágenes de las líneas (`lineas/`) y de la llamada a la acción (`contacto/`); `.htaccess` con los tipos de vídeo. |
| `template-parts/cards/` y `template-parts/components/` | Tarjeta de categoría, banda de cabecera de páginas interiores (`page-hero.php`), formulario de búsqueda, enlace de carrito y estado vacío. |
| `assets/src/css/app.css` | Orden de capas, tokens, cabecera, menús, pie, WooCommerce y páginas institucionales. |
| `assets/src/css/pages/home.css`, `line.css`, `company.css` | Estilos de la portada, las páginas de línea y las de empresa. |
| `assets/src/css/system/` | Sistema de diseño: tipografía, botones, superficies, formas, movimiento, carrusel y bloques de página (ver [DESIGN.MD](DESIGN.MD)). |
| `assets/src/js/app.js`, `assets/src/js/modules/` | Entrada de assets, menú con submenús, estado de la cabecera al hacer scroll, aparición progresiva, carrusel, apilado, contadores, bandas de palabras ligadas al scroll y pasos en pestañas o acordeón. |
| `assets/dist/` | CSS, JavaScript, fuentes y manifest listos para servir; se versiona en Git. |
| `acf-json/` | Configuración versionada de los campos editoriales de la portada, las páginas de línea y las de empresa. |
| `theme.json`, `style.css` | Ajustes del editor y metadatos necesarios para activar el tema. |
| `bin/deploy.sh`, `.gitattributes` | Despliegue en Hostinger por SSH, con reversión y vaciado de caché, y lista de archivos que no se publican (`export-ignore`). |

Actualmente no existe un directorio `woocommerce/` de overrides. Tampoco existen módulos separados para SEO, rendimiento o seguridad; se incorporarán solo cuando haya una necesidad concreta que corresponda al tema.

## Flujo de una página

1. WordPress resuelve la URL y selecciona un template del tema o de WooCommerce.
2. El tema imprime header, contenido y footer; las secciones reutilizables se cargan con `get_template_part()`.
3. Para el catálogo y la compra, WooCommerce produce datos y formularios. El tema aporta wrappers y estilos mediante sus hooks públicos.
4. En la portada, cada sección lee sus campos ACF con `frave_home_text()`, que usa los textos por defecto de `inc/home.php` cuando faltan. Las líneas enlazan con su página de línea y toman la imagen de su categoría o la del tema; los destacados proceden de WooCommerce.
5. En una página de línea, `frave_line_text()` lee el campo ACF de la página o el texto por defecto de `inc/lines.php`; el portafolio sale de las subcategorías de WooCommerce.
6. WordPress encola `assets/src/js/app.js` desde Vite en desarrollo o los archivos con hash indicados por `assets/dist/manifest.json` en producción.

En la portada, los elementos con `data-reveal` y `data-reveal-group` se observan con `IntersectionObserver`. Las clases de preparación se añaden solo desde JavaScript; así el contenido no queda oculto cuando JS no carga. El módulo `assets/src/js/modules/section-transitions.js` omite el efecto cuando el visitante ha solicitado movimiento reducido.

## Integraciones y decisiones vigentes

- **Modo catálogo:** la decisión de no vender en línea es lógica comercial y vive en el plugin `frave-peru` (opción `frave_peru_catalog_mode`, filtro del mismo nombre): productos no comprables, precios fuera de páginas, `wc-ajax=get_variation`, datos estructurados y orden; carrito, checkout y cuenta redirigen al catálogo; la Store API pública queda cerrada. El tema solo lee `frave_catalog_mode()` para quitar la interfaz de compra (cuenta, carrito, mini-carrito, fragmentos, filtros de precio y oferta) y ofrecer cotización y WhatsApp. Sin el plugin, el tema vuelve al modo tienda.
- **Navegación:** `inc/navigation.php` añade a los elementos con hijos del menú principal un botón (`[data-submenu-toggle]`, `aria-expanded`); un enlace personalizado `#` se convierte en ese botón. En escritorio el submenú se abre al pasar el puntero (CSS, solo con `hover: hover`) o con el botón, y se cierra con Escape, un clic fuera o al salir el foco; en el menú móvil es un acordeón. Sin JavaScript, el panel se abre con el puntero en escritorio y los submenús quedan desplegados en móvil.
- **WooCommerce:** el soporte se declara en `inc/setup.php`; `inc/woocommerce.php` sustituye sus wrappers de contenido, oculta el sidebar de WooCommerce y define tres columnas de catálogo. El contador del carrito se actualiza mediante el filtro de fragmentos de WooCommerce. La lógica de compra sigue dentro del plugin.
- **Botones de WooCommerce:** `inc/woocommerce.php` añade al body la clase `woocommerce-block-theme-has-button-styles`, el mecanismo de WooCommerce para que un tema sustituya su estilo de botones; así no hace falta pelear con sus reglas sin capa.
- **Rendimiento:** `inc/enqueue.php` precarga las fuentes WOFF2 del manifest con `wp_preload_resources` y elimina el script y los estilos de emojis de WordPress.
- **Filtros del catálogo:** WooCommerce ejecuta las consultas con sus parámetros de URL; el tema solo añade `filter_stock_status=instock` (mismo nombre que los filtros de bloques de WooCommerce) y `on_sale=1` en `woocommerce_product_query`. Los atributos usan el widget de navegación por capas de WooCommerce (`the_widget( 'WC_Widget_Layered_Nav' )`) por cada atributo global, así los recuentos respetan los demás filtros y los atributos nuevos aparecen sin cambiar código. El tema añade a esos recuentos el precio y la oferta (`woocommerce_get_filtered_term_product_counts_query`), que WooCommerce no considera; con la tabla de atributos de WooCommerce activa, también los atributos elegidos con lógica «o». Sin JavaScript todo funciona con enlaces y un formulario GET; el panel queda sobre los productos.
- **Mini-carrito:** `template-parts/components/mini-cart.php` muestra el cajón con `woocommerce_mini_cart()` en todas las páginas salvo carrito y checkout. WooCommerce mantiene su contenido con el fragmento `div.widget_shopping_cart_content` y elimina productos por AJAX. `assets/src/js/modules/mini-cart.js` lo abre desde el enlace del carrito, tras `added_to_cart` y al cargar una página que añadió un producto (`did_action( 'woocommerce_add_to_cart' )`, p. ej. el formulario de la ficha). Es un diálogo modal: retiene el foco, se cierra con Escape o el fondo y devuelve el foco al enlace. `wc-cart-fragments` se carga en todas las páginas para que el contador y el cajón sean correctos aunque haya caché de página completa. Los estilos del contenido de WooCommerce van sin capa y con `#mini-cart` porque las reglas sin capa de WooCommerce solo aplican en páginas de tienda.
- **Páginas clásicas de comercio:** la instalación local usa los shortcodes `[woocommerce_cart]`, `[woocommerce_checkout]` y `[woocommerce_my_account]`. El tema les aplica estilos, pero su contenido y estado permanecen en WooCommerce.
- **Búsqueda de productos:** el formulario envía `s` y `post_type=product` a WordPress.
- **ACF:** el grupo `Inicio Frave` se guarda en `acf-json/group_frave_home.json`, con un campo por texto de la portada (prefijo `frave_home_`), el vídeo y su imagen fija. Los valores por defecto se inyectan desde `frave_home_defaults()` con `acf/load_field`, así no se duplican en el JSON. Precio, stock y catálogo siguen en WooCommerce. Los datos editados viven en la base de datos; el JSON versiona la estructura. `frave_field()` evita dependencia obligatoria de ACF.
- **Vídeo del hero:** se sirve con `preload="none"` y la imagen fija precargada (es el elemento más grande al cargar). `hero-video.js` lo carga y reproduce una sola vez si no hay movimiento reducido ni ahorro de datos, y muestra el botón de pausa (WCAG 2.2.2). La capa de texto del hero deja pasar los clics fuera del bloque de texto para que el botón funcione.
- **Páginas institucionales:** `page.php` presenta el contenido escrito en WordPress. El enlace de la sección editorial a `nosotros` aparece solo cuando existe una página publicada con ese slug. En el entorno local se creó esa página y se asignaron los menús desde el administrador; estos datos no forman parte de Git.
- **Assets:** Vite compila Tailwind, JavaScript y Lato local. `inc/enqueue.php` lee la entrada `assets/src/js/app.js` del manifest. Los hashes no se escriben manualmente.
- **Seguridad y SEO:** se usan las funciones públicas de WordPress para escapar salida y generar URLs, y el tema declara soporte de `title-tag`. La configuración SEO detallada corresponderá al sitio o a un plugin SEO elegido después.

## Reglas para extender el tema

1. Coloca cada cambio en su responsable: presentación en el tema, lógica comercial en WooCommerce, contenido editorial estructurado en ACF y configuración operativa en el sitio.
2. Para WooCommerce, usa primero hooks, filtros y funciones públicas. Si un override resulta imprescindible, documenta archivo, motivo y versión de plantilla soportada.
3. Añade secciones y componentes en `template-parts/` y evita duplicar consultas, markup o estilos entre páginas.
4. Mantén `functions.php` como arranque; incorpora módulos pequeños en `inc/` cuando aparezca una responsabilidad nueva.
5. Si cambias colores, tipografía o medidas globales, actualiza `assets/src/css/app.css`, `theme.json` y [DESIGN.MD](DESIGN.MD) de forma coherente.
6. Tras cambios en assets, ejecuta el build y versiona `assets/dist/` junto con el código fuente. Tras cambios de PHP, comprueba sintaxis y recorridos afectados.
7. Al añadir o cambiar archivos de `patterns/`, sube `Version` en `style.css` (y `FRAVE_VERSION`): WordPress guarda en caché los patrones del tema por versión y no vería los cambios.

## Riesgos y trabajo siguiente

La hoja `assets/src/css/app.css` concentra hoy todo el sistema visual y los estilos WooCommerce. Puede dividirse por componentes cuando el tamaño o la colaboración lo justifiquen. Filtros avanzados, módulos editoriales adicionales, pasarela real y configuración del hosting aún no forman parte de esta base. Consulta [GUIA_COMPILACION.MD](GUIA_COMPILACION.MD) y [GUIA_DESPLIEGUE.MD](GUIA_DESPLIEGUE.MD) para los flujos actuales.

## Registro de decisiones

| Fecha | Decisión | Motivo |
| --- | --- | --- |
| 2026-09-23 | Tema clásico, WooCommerce mediante hooks y sin overrides. | Reducir acoplamiento y mantenimiento de plantillas del plugin. |
| 2026-09-23 | Vite con manifest y `assets/dist/` versionado. | Permitir despliegue sin Node.js en el hosting. |
| 2026-09-23 | Escaneo de Tailwind limitado a PHP y JS del tema. | Evitar que cambios en documentación modifiquen el CSS compilado. |
| 2026-09-23 | ACF opcional con valores de respaldo. | Mantener visible la portada si ACF está desactivado temporalmente. |
| 2026-09-23 | Módulos editoriales fijos con campos de ACF gratuito. | Permitir edición controlada sin un constructor visual ni duplicar datos comerciales. |
| 2026-09-23 | `server.origin` de Vite apunta al servidor de desarrollo. | Resolver las fuentes importadas desde CSS cuando WordPress sirve el HTML en otro puerto. |
| 2026-09-28 | Requisitos comerciales de Perú (comprobante, DNI/RUC) en el plugin `frave-peru`, fuera del tema. | Son lógica comercial y datos de pedidos: deben conservarse si cambia el tema. El tema solo aporta estilos. Ver `D:/Frave-web/frave-peru/README.md`. |
| 2026-10-11 | Portada nueva con campos ACF renombrados (`frave_home_*`) en lugar de reutilizar los antiguos. | Los textos guardados del diseño anterior no se mezclan con los nuevos; los campos antiguos quedan sin uso en la base de datos. |
| 2026-10-11 | Sistema de diseño en `assets/src/css/system/` importado por `app.css`, con el orden de capas fijado al principio. Cabecera que superpone la página (`--header-height` como relleno del contenido) para que las bandas oscuras corran por detrás. | Separar el sistema de los estilos de cada página y permitir la cabecera flotante sin desplazar contenido. |
| 2026-10-10 | Web corporativa con catálogo: modo catálogo en `frave-peru` (activado por defecto) y la tienda en pausa sin borrar su código. | Frave pospone la venta en línea; un interruptor permite volver a vender sin rehacer el carrito, el checkout ni la boleta/factura. Ver [PLAN_WEB_CORPORATIVA.md](PLAN_WEB_CORPORATIVA.md). |

Anota aquí las decisiones que cambien los límites del sistema o las dependencias; las tareas concretas se siguen en [ROADMAP.md](ROADMAP.md).
