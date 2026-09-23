# Diseño de Frave

- **Estado:** sistema visual inicial, pendiente de aprobación final de marca.
- **Última revisión:** 23 de septiembre de 2026.
- **Seguimiento:** [ROADMAP.md](ROADMAP.md).

## Dirección visual

Frave busca una experiencia elegante, limpia y editorial para fragancias, esencias e insumos de perfumería. La identidad inicial usa el logotipo horizontal naranja disponible en los recursos de marca, fondos claros, amplio espacio en blanco y fotografía de producto administrada desde WordPress y WooCommerce. Las referencias externas sirven para estudiar calidad de composición y experiencia; el diseño de Frave debe mantener una identidad propia.

La portada actual ofrece un hero con contenido editable, categorías y productos destacados. Cuando no se ha cargado una imagen editorial, el hero muestra una composición hecha con CSS. Esa composición es un respaldo visual, no una fotografía comercial definitiva.

## Fuentes de verdad

| Elemento | Archivo actual | Regla de cambio |
| --- | --- | --- |
| Tokens y estilos del frontend | `assets/src/css/app.css` | Cambiar aquí los valores y componentes visibles. |
| Paleta y opciones del editor | `theme.json` | Mantener los valores compartidos alineados con el CSS. |
| Logotipo de respaldo | `assets/images/frave-logo.png` | Conservar los originales de marca fuera del tema. |
| Contenido editorial del hero | `acf-json/group_frave_home.json` y `template-parts/sections/hero.php` | Editar contenido desde la portada; versionar cambios de estructura de campos. |
| Imágenes, precios, stock y atributos de producto | WooCommerce y la biblioteca de medios | No duplicar estos datos en ACF. |

## Color inicial

| Token | Valor | Uso actual |
| --- | --- | --- |
| `brand-orange` | `#fc4c02` | Acentos, botones y elementos de marca. |
| `brand-orange-dark` | `#d84000` | Texto de acento y estados hover. |
| `ink` | `#202329` | Texto principal y botones oscuros. |
| `muted` | `#6b6b68` | Texto secundario. |
| `surface` | `#f6f4f1` | Secciones y superficies suaves. |
| `line` | `#e5e1dc` | Bordes y divisores. |
| `success` | `#276749` | Estados positivos y disponibilidad. |
| `warning` | `#9c6b1b` | Avisos. |
| `error` | `#b42318` | Errores y agotados. |
| `white` | `#ffffff` | Fondo y texto sobre superficies oscuras. |

La paleta es provisional hasta aprobar el branding. Para nuevos componentes, usar el token semántico más cercano y revisar el contraste del par de colores elegido. El naranja principal con texto blanco tiene una relación aproximada de **3,40:1**; los botones y badges con texto pequeño requieren ajuste antes de considerarse accesibles para WCAG AA. Este trabajo figura en el plan de la fase 2.

## Tipografía y composición

- **Familia:** Lato 400 y 700 alojada localmente mediante `@fontsource/lato`, con Arial y `sans-serif` como respaldo.
- **Texto base:** `1rem`, interlineado `1.6`.
- **Títulos generales:** negrita, interlineado `1.12` y espaciado de letras `-0.035em`; `h1` y `h2` usan tamaños fluidos con `clamp()`.
- **Roles presentes:** eyebrow en mayúsculas, título principal, títulos de sección, cuerpo, navegación, botones, etiquetas y texto auxiliar. La escala completa de display, caption y labels queda por formalizar al aprobar marca.
- **Contenedor:** ancho máximo de `80rem` con gutters fluidos; las secciones usan espaciado vertical adaptable.
- **Tarjetas:** radio aproximado de `1.15rem`; botones y controles principales usan contorno redondeado.

## Responsive y movimiento

La interfaz cubre móvil, tablet y escritorio. El catálogo pasa de dos columnas en móvil a tres en escritorio; las categorías pasan de dos a cuatro. El menú usa una presentación móvil hasta `63.99rem` y el layout estrecho se ajusta por debajo de `47.99rem`. El ancho mínimo de diseño actual es `320px`.

El CSS actual contiene estilos generales y ajustes con `max-width`. Al ampliar el sistema, revisar la organización para cumplir la estrategia móvil primero sin alterar los flujos existentes. Las transiciones son breves y se reducen cuando el sistema indica `prefers-reduced-motion`.

## Componentes y estados actuales

| Componente | Estado | Ubicación |
| --- | --- | --- |
| Header, menú móvil, búsqueda y footer | Inicial | `header.php`, `footer.php`, `template-parts/components-search.php` |
| Botón y enlace textual | Inicial | `inc/helpers.php`, `assets/src/css/app.css` |
| Hero, sección de categorías y destacados | Inicial | `template-parts/sections/` |
| Tarjeta de categoría y estado vacío | Inicial | `template-parts/cards/`, `template-parts/components/` |
| Enlace y contador del carrito | Inicial | `template-parts/components/cart-link.php` |
| Formularios, mensajes y páginas WooCommerce | Estilos base | `assets/src/css/app.css` |
| Filtros avanzados, mini-carrito, modal y drawer genéricos | Pendiente | Fases posteriores, según necesidad real |

## Imágenes y accesibilidad

Las imágenes subidas a WordPress deben tener dimensiones apropiadas, texto alternativo útil cuando comuniquen contenido y versiones responsive generadas por la biblioteca de medios. El tema registra tamaños `frave-card` y `frave-editorial`; las imágenes de categoría usan carga diferida y el hero prioriza la imagen principal cuando existe. La calidad final depende de las fotografías y de la configuración del servidor.

La base incluye HTML semántico, enlace para saltar al contenido, estados `:focus-visible` y navegación móvil con atributos ARIA y cierre por Escape. Queda pendiente una auditoría completa con teclado, lector de pantalla, contraste y contenido definitivo.

## Registro de cambios y decisiones

| Fecha | Cambio | Estado |
| --- | --- | --- |
| 2026-09-23 | Se documenta el sistema visual inicial observado en el tema. | Base provisional |

Al aprobar una decisión visual, actualiza esta guía y los tokens correspondientes; compila los assets y comprueba portada, catálogo, producto, carrito y checkout en móvil y escritorio. Registra en la tabla qué cambió y por qué.
