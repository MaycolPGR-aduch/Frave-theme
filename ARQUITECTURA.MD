# Arquitectura del tema Frave

- **Estado:** implementación inicial funcional.
- **Última revisión:** 23 de septiembre de 2026.
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
| `front-page.php`, `page.php`, `single.php`, `archive.php`, `search.php`, `index.php`, `404.php` | Jerarquía de templates clásicos. |
| `header.php`, `footer.php` | Estructura global, navegación, búsqueda, carrito y pie. |
| `template-parts/sections/` | Hero, categorías, destacados, presentación editorial, guía y cierre de la portada. |
| `template-parts/cards/` y `template-parts/components/` | Tarjeta de categoría, enlace de carrito y estado vacío. |
| `assets/src/css/app.css` | Tailwind, tokens, estilos de componentes y estilos WooCommerce. |
| `assets/src/js/app.js`, `assets/src/js/modules/navigation.js` | Entrada de assets y comportamiento del menú móvil. |
| `assets/dist/` | CSS, JavaScript, fuentes y manifest listos para servir; se versiona en Git. |
| `acf-json/` | Configuración versionada de los campos editoriales de la portada. |
| `theme.json`, `style.css` | Ajustes del editor y metadatos necesarios para activar el tema. |

Actualmente no existe un directorio `woocommerce/` de overrides. Tampoco existen módulos separados para SEO, rendimiento o seguridad; se incorporarán solo cuando haya una necesidad concreta que corresponda al tema.

## Flujo de una página

1. WordPress resuelve la URL y selecciona un template del tema o de WooCommerce.
2. El tema imprime header, contenido y footer; las secciones reutilizables se cargan con `get_template_part()`.
3. Para el catálogo y la compra, WooCommerce produce datos y formularios. El tema aporta wrappers y estilos mediante sus hooks públicos.
4. En la portada, hero, presentación, guía y cierre leen campos ACF cuando están disponibles y usan valores de respaldo en caso contrario. Categorías y productos destacados proceden de WooCommerce.
5. WordPress encola `assets/src/js/app.js` desde Vite en desarrollo o los archivos con hash indicados por `assets/dist/manifest.json` en producción.

## Integraciones y decisiones vigentes

- **WooCommerce:** el soporte se declara en `inc/setup.php`; `inc/woocommerce.php` sustituye sus wrappers de contenido, oculta el sidebar de WooCommerce y define tres columnas de catálogo. El contador del carrito se actualiza mediante el filtro de fragmentos de WooCommerce. La lógica de compra sigue dentro del plugin.
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

Anota aquí las decisiones que cambien los límites del sistema o las dependencias; las tareas concretas se siguen en [ROADMAP.md](ROADMAP.md).
