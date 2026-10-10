# Arquitectura del tema Frave

- **Estado:** implementación inicial funcional.
- **Última revisión:** 26 de septiembre de 2026.
- **Seguimiento:** [ROADMAP.md](ROADMAP.md).

## Límites del sistema

Frave es un tema WordPress clásico con templates PHP. WordPress administra páginas, usuarios, medios y editor. WooCommerce administra productos, categorías, variaciones, precios, inventario, carrito, checkout, pedidos y pagos. ACF gratuito añade campos estructurados para el contenido editorial de la portada y puede desactivarse sin provocar un error fatal. El tema controla el HTML, CSS, navegación y presentación del comercio.

No se usa constructor visual, frontend desacoplado ni React. `theme.json` configura el editor y su paleta; no convierte el tema en Full Site Editing. La configuración de tienda y los datos de negocio pertenecen a la base de datos de cada instalación, fuera de Git.

## Mapa del repositorio

| Ruta | Responsabilidad actual |
| --- | --- |
| `functions.php` | Define constantes y carga los módulos de `inc/`. |
| `inc/setup.php` | Soporte del tema, menús, tamaños de imagen y ancho de contenido. |
| `inc/helpers.php` | Lectura opcional de ACF, URL de assets y botón reutilizable. |
| `inc/enqueue.php` | Carga Vite en desarrollo o CSS y JS desde el manifest compilado. |
| `inc/woocommerce.php` | Wrappers del contenido, columnas de catálogo y fragmento del contador del carrito. |
| `inc/acf.php` | Registra `acf-json/` como ruta de carga de grupos ACF. |
| `inc/catalog-filters.php` | Filtros del catálogo: consultas de disponibilidad y oferta, recuentos, URL, chips de filtros activos, maquetación a dos columnas y `noindex` de vistas filtradas. |
| `template-parts/catalog/` | Panel de filtros, barra de herramientas (botón «Filtrar», resultados y orden) y chips de filtros activos. |
| `assets/src/js/modules/drawer.js` | Cajón lateral modal compartido por el mini-carrito y los filtros: foco, Escape, fondo y un solo cajón abierto. |
| `inc/institutional.php`, `page-templates/` | Plantillas Contacto, Preguntas frecuentes y Política o texto legal; anclas e índice de secciones, datos `FAQPage`, enlaces legales del pie (menú `legal` o automático). |
| `patterns/` | Estructuras iniciales de las páginas institucionales (categoría «Frave: páginas institucionales»), con marcadores `[PENDIENTE]`. |
| `inc/contact.php` | Ajustes de WhatsApp en el Personalizador, enlaces `wa.me` y botón de consulta en la ficha de producto. |
| `front-page.php`, `page.php`, `single.php`, `archive.php`, `search.php`, `index.php`, `404.php` | Jerarquía de templates clásicos. |
| `header.php`, `footer.php` | Estructura global, navegación, búsqueda, carrito y pie. |
| `template-parts/sections/` | Hero, categorías, destacados, presentación editorial, guía y cierre de la portada. |
| `template-parts/cards/` y `template-parts/components/` | Tarjeta de categoría, enlace de carrito y estado vacío. |
| `assets/src/css/app.css` | Tailwind, tokens, estilos de componentes y estilos WooCommerce. |
| `assets/src/js/app.js`, `assets/src/js/modules/` | Entrada de assets, menú móvil y revelado progresivo de módulos editoriales. |
| `assets/dist/` | CSS, JavaScript, fuentes y manifest listos para servir; se versiona en Git. |
| `acf-json/` | Configuración versionada de los campos editoriales de la portada. |
| `theme.json`, `style.css` | Ajustes del editor y metadatos necesarios para activar el tema. |
| `bin/deploy.sh`, `.gitattributes` | Despliegue en Hostinger por SSH, con reversión y vaciado de caché, y lista de archivos que no se publican (`export-ignore`). |

Actualmente no existe un directorio `woocommerce/` de overrides. Tampoco existen módulos separados para SEO, rendimiento o seguridad; se incorporarán solo cuando haya una necesidad concreta que corresponda al tema.

## Flujo de una página

1. WordPress resuelve la URL y selecciona un template del tema o de WooCommerce.
2. El tema imprime header, contenido y footer; las secciones reutilizables se cargan con `get_template_part()`.
3. Para el catálogo y la compra, WooCommerce produce datos y formularios. El tema aporta wrappers y estilos mediante sus hooks públicos.
4. En la portada, hero, presentación, guía y cierre leen campos ACF cuando están disponibles y usan valores de respaldo en caso contrario. Categorías y productos destacados proceden de WooCommerce.
5. WordPress encola `assets/src/js/app.js` desde Vite en desarrollo o los archivos con hash indicados por `assets/dist/manifest.json` en producción.

En la portada, los elementos con `data-reveal` y `data-reveal-group` se observan con `IntersectionObserver`. Las clases de preparación se añaden solo desde JavaScript; así el contenido no queda oculto cuando JS no carga. El módulo `assets/src/js/modules/section-transitions.js` omite el efecto cuando el visitante ha solicitado movimiento reducido.

## Integraciones y decisiones vigentes

- **WooCommerce:** el soporte se declara en `inc/setup.php`; `inc/woocommerce.php` sustituye sus wrappers de contenido, oculta el sidebar de WooCommerce y define tres columnas de catálogo. El contador del carrito se actualiza mediante el filtro de fragmentos de WooCommerce. La lógica de compra sigue dentro del plugin.
- **Botones de WooCommerce:** `inc/woocommerce.php` añade al body la clase `woocommerce-block-theme-has-button-styles`, el mecanismo de WooCommerce para que un tema sustituya su estilo de botones; así no hace falta pelear con sus reglas sin capa.
- **Rendimiento:** `inc/enqueue.php` precarga las fuentes WOFF2 del manifest con `wp_preload_resources` y elimina el script y los estilos de emojis de WordPress.
- **Filtros del catálogo:** WooCommerce ejecuta las consultas con sus parámetros de URL; el tema solo añade `filter_stock_status=instock` (mismo nombre que los filtros de bloques de WooCommerce) y `on_sale=1` en `woocommerce_product_query`. Los atributos usan el widget de navegación por capas de WooCommerce (`the_widget( 'WC_Widget_Layered_Nav' )`) por cada atributo global, así los recuentos respetan los demás filtros y los atributos nuevos aparecen sin cambiar código. El tema añade a esos recuentos el precio y la oferta (`woocommerce_get_filtered_term_product_counts_query`), que WooCommerce no considera; con la tabla de atributos de WooCommerce activa, también los atributos elegidos con lógica «o». Sin JavaScript todo funciona con enlaces y un formulario GET; el panel queda sobre los productos.
- **Mini-carrito:** `template-parts/components/mini-cart.php` muestra el cajón con `woocommerce_mini_cart()` en todas las páginas salvo carrito y checkout. WooCommerce mantiene su contenido con el fragmento `div.widget_shopping_cart_content` y elimina productos por AJAX. `assets/src/js/modules/mini-cart.js` lo abre desde el enlace del carrito, tras `added_to_cart` y al cargar una página que añadió un producto (`did_action( 'woocommerce_add_to_cart' )`, p. ej. el formulario de la ficha). Es un diálogo modal: retiene el foco, se cierra con Escape o el fondo y devuelve el foco al enlace. `wc-cart-fragments` se carga en todas las páginas para que el contador y el cajón sean correctos aunque haya caché de página completa. Los estilos del contenido de WooCommerce van sin capa y con `#mini-cart` porque las reglas sin capa de WooCommerce solo aplican en páginas de tienda.
- **Páginas clásicas de comercio:** la instalación local usa los shortcodes `[woocommerce_cart]`, `[woocommerce_checkout]` y `[woocommerce_my_account]`. El tema les aplica estilos, pero su contenido y estado permanecen en WooCommerce.
- **Búsqueda de productos:** el formulario envía `s` y `post_type=product` a WordPress.
- **ACF:** el grupo `Inicio Frave` se guarda en `acf-json/group_frave_home.json`. Sus campos de texto e imágenes pertenecen a secciones editoriales; precio, stock y catálogo siguen en WooCommerce. Los datos editados viven en la base de datos de WordPress; el JSON versiona la estructura del grupo. `frave_field()` evita dependencia obligatoria de ACF.
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

Anota aquí las decisiones que cambien los límites del sistema o las dependencias; las tareas concretas se siguen en [ROADMAP.md](ROADMAP.md).
