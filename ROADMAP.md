# Plan de desarrollo de Frave

Actualizado: 23 de septiembre de 2026.

Este archivo registra el avance del tema y las siguientes fases. El código del tema vive en este repositorio; la configuración de WordPress, WooCommerce, productos y pedidos vive en la instalación correspondiente. Una casilla marcada indica que la función existe en el tema o que la tarea se completó en el entorno local. No implica que la tienda esté lista para vender en producción.

## Objetivo y decisiones técnicas

Crear una tienda de perfumería con identidad propia, usando un tema clásico personalizado. WordPress gestiona contenidos y medios; WooCommerce conserva la lógica comercial. El tema controla la presentación con PHP, Tailwind CSS 4, Vite y JavaScript nativo. ACF gratuito se usa para contenido editorial estructurado y es opcional en tiempo de ejecución. Los cambios de WooCommerce se hacen primero mediante soporte, hooks y filtros; actualmente no hay templates sobrescritos.

**Leyenda:** `[x]` implementado; `[ ]` pendiente. Las tareas de validación se marcan solo cuando existe evidencia de la comprobación indicada.

## Fase 1 — Base funcional (implementada)

### Arquitectura y entorno

- [x] Crear repositorio Git del tema `frave`, con rama `main` y remoto de GitHub.
- [x] Separar la instalación local de WordPress del repositorio del tema.
- [x] Crear tema clásico con templates PHP esenciales y `functions.php` como arranque de módulos en `inc/`.
- [x] Declarar soporte de WordPress y WooCommerce, menús, tamaños de imagen y ajustes del editor en `theme.json`.
- [x] Mantener los recursos originales de marca fuera del tema e incorporar el logotipo necesario.
- [x] Documentar requisitos, instalación, desarrollo, despliegue y arquitectura en `README.md`.

### Assets y sistema visual inicial

- [x] Configurar Vite, Tailwind CSS 4, escaneo de PHP, recarga en desarrollo y manifest de producción.
- [x] Versionar `assets/dist/` con CSS, JavaScript y fuentes compilados con hash.
- [x] Cargar assets desde WordPress mediante el manifest, sin hashes escritos a mano.
- [x] Definir paleta inicial con el naranja `#fc4c02`, tipografía Lato local, layout responsive y estados de foco.
- [x] Crear componentes iniciales de botón, búsqueda, tarjeta de categoría, enlace de carrito y estado vacío.
- [x] Respetar `prefers-reduced-motion` en las transiciones existentes.

### Contenido y navegación

- [x] Construir header, menú móvil, búsqueda de productos y footer.
- [x] Crear portada inicial con hero, categorías con productos y selección de productos destacados o recientes.
- [x] Registrar campos ACF del hero en `acf-json/`, con contenido de respaldo cuando ACF no está activo.
- [x] Permitir editar productos, categorías, precios, imágenes y promociones desde WooCommerce.

### Comercio básico

- [x] Integrar catálogo, categorías, búsqueda, ordenamiento y paginación con WooCommerce.
- [x] Presentar producto simple y variable, precio, descuentos, stock, galería y relacionados mediante WooCommerce.
- [x] Estilizar carrito, checkout clásico, cupones, mensajes y cuenta del cliente.
- [x] Mantener actualizado el contador del carrito del header tras cambios mediante fragmentos de WooCommerce.
- [x] Configurar en el entorno local PEN, envío plano peruano y contra entrega de prueba.
- [x] Registrar en el README la instalación local y los productos y escenarios de prueba utilizados.

### Validación registrada de la primera entrega

- [x] Compilar assets de producción y versionar manifest y archivos referenciados.
- [x] Registrar en el README que el entorno local se validó con WordPress, WooCommerce y ACF gratuito.
- [ ] Repetir una ronda documentada de pruebas después de futuros cambios: activación con y sin ACF, PHP lint, build, navegación móvil y escritorio, producto simple y variable, agotado, carrito, cupón, pedido de prueba y cuenta.
- [ ] Medir Core Web Vitals y auditar accesibilidad con contenido e imágenes definitivos.
- [x] Linters e integración continua: PHPCS (WordPress-Extra, PHP 8.1+), ESLint y GitHub Actions con build verificado (2026-10-05). Ver `GUIA_COMPILACION.MD`.
- [ ] Añadir pruebas automatizadas de los recorridos principales (catálogo, carrito, checkout, formularios).

**Estado actual:** hay una tienda básica para desarrollo y pruebas. El pago de prueba y la configuración comercial local no habilitan ventas reales.

## Fase 2 — Identidad y contenido editorial

Objetivo: convertir la base funcional en una experiencia de marca completa y editable sin código.

- [ ] Aprobar paleta, tipografía, fotografía, tono de voz y uso del logotipo definitivo.
- [x] Incorporar a la portada entradas progresivas y escalonadas, con transiciones entre secciones y soporte para movimiento reducido.
- [x] Añadir tokens semánticos de acento, primario, fondo, texto y borde; documentar la tipografía y el layout actuales en `DESIGN.MD`.
- [x] Corregir el contraste de botones, badges, paginación y enlaces del pie afectados por el naranja o por CSS de WooCommerce.
- [x] Completar el ciclo de foco con Tab/Mayús+Tab en el menú móvil, reforzar el foco visible sobre superficies oscuras y quitar retrasos con movimiento reducido.
- [ ] Completar una auditoría de contraste de todos los estados y controles WooCommerce con contenido definitivo.
- [ ] Revisar la organización responsive del CSS para cumplir la estrategia móvil primero sin regresiones.
- [ ] Sustituir las imágenes de prueba o respaldos visuales por fotografías propias optimizadas, con texto alternativo y tamaños adecuados.
- [x] Añadir módulos independientes de presentación, guía de exploración y CTA hacia la tienda; categorías y destacados siguen alimentados por WooCommerce.
- [x] Definir campos editoriales controlados en ACF gratuito y versionar el grupo en `acf-json/`; mantener los datos de producto en WooCommerce.
- [x] Crear en el entorno local la página editable `Nosotros` y asignar menús principal y de pie desde WordPress.
- [ ] Definir con Frave el contenido de colecciones, novedades, ventajas verificables, recursos educativos y canales de contacto antes de añadir esos módulos y páginas.
- [x] Documentar una estructura editorial inicial para páginas institucionales y los datos que Frave debe aportar antes de publicarlas.
- [x] Plantillas de Contacto (canales + formulario con mensajes guardados), Preguntas frecuentes (índice, acordeones, `FAQPage`) y Política o texto legal (fecha, índice, otras políticas); patrones con la estructura de FAQ, privacidad, términos, envíos y cambios; enlaces legales en el pie.
- [ ] Redactar con Frave (y su asesoría legal en las políticas) el contenido de las seis páginas en borrador, completar los canales de contacto y publicarlas.

**Criterio de cierre:** el equipo de Frave puede actualizar textos, imágenes y secciones aprobadas desde WordPress sin alterar el layout; la identidad visual está aprobada en móvil y escritorio.

**Validación móvil y accesibilidad (2026-09-26):** build de Vite completado; portada y rutas principales respondieron HTTP 200; JavaScript, CSS y logo de footer sirvieron desde local. Se revisaron los breakpoints de 320px, 768px y 1024px, las reglas de foco y `prefers-reduced-motion` en código. La interacción de teclado y la vista por viewport requieren comprobación manual en el navegador; la auditoría con lector de pantalla y contenido final sigue pendiente.

**Validación de esta iteración (2026-09-26):** `npm.cmd run build`, comprobación de las seis referencias del manifest, `php -l` en las seis plantillas PHP modificadas y `git diff --check` completados. No se pudo verificar la portada en vivo después del build: los servicios locales de WordPress, Vite y MySQL no estaban activos (puertos 8080, 5173 y 3307 cerrados).

**Validación de esta iteración (2026-09-23):** lint de los 27 PHP del tema, `npm run build`, manifest con seis archivos presentes, portada HTTP 200 con ACF activo e inactivo, campos ACF cargados, página `Nosotros` visible, Lato servido por Vite con HTTP 200 y portada de producción cargando CSS/JS del manifest. Se inspeccionaron las secciones nuevas a 408px y 1440px; la revisión completa de compra y accesibilidad sigue pendiente.

**Catálogo y compra (2026-09-26):** fichas locales de prueba simple, variable y agotada respondieron HTTP 200. En una sesión aislada se añadió un producto al carrito y se comprobaron el formulario del carrito y los campos del checkout sin enviar pedidos. WooCommerce tenía activo «Próximamente», que ocultaba el catálogo; se desactivó solo en la base local y se documentó en el README. La propuesta de categorías, los atributos y la plantilla se registran sin crear categorías ni productos reales. Los encabezados de la plantilla se contrastaron con el esquema del importador nativo de WooCommerce.

**Catálogo de muestra (2026-09-26):** la portada local muestra las cuatro categorías y cuatro productos destacados. Las cuatro páginas de categoría y dos fichas añadidas respondieron HTTP 200. Se reutilizaron los tres productos de QA y se añadieron dos productos simples con SKU `DEMO-`; todos los productos publicados llevan `[MUESTRA]` y descripción de advertencia. No se añadieron fotos comerciales; se muestran los marcadores de WooCommerce.

**Revisión técnica (2026-09-26):** `npm.cmd run build` completado; lint de los 27 archivos PHP, sintaxis de los módulos JavaScript, rutas del manifest y paquete XLSX de la plantilla comprobados. La plantilla abre con una hoja y 46 encabezados reconocidos, sin filas de productos de ejemplo. `git diff --check` pasó. La revisión visual manual y el flujo completo de pedido siguen pendientes.

**Correcciones de auditoría (2026-09-28):** (1) la descripción del hero ya no muestra `<br />` literal: el campo ACF usa `new_lines` vacío y la plantilla aplica `nl2br( esc_html() )`; sincroniza el grupo **Inicio Frave** en ACF si el admin lo ofrece. (2) La ficha de producto declara zoom, lightbox y carrusel de galería de WooCommerce. (3) El menú móvil se cierra solo al cruzar el breakpoint (`matchMedia` `change`), no con los `resize` que provoca la barra de direcciones al hacer scroll. (4) Los destacados respetan la visibilidad de catálogo y la opción de ocultar agotados; las categorías de portada excluyen la categoría por defecto. (5) El hero ya no usa la animación de entrada, que lo ocultaba con `opacity: 0` y retrasaba el LCP. Evidencia: lint PHP, `npm.cmd run build`, portada con texto multilínea y un destacado oculto temporalmente (datos restaurados), flags de galería activos en la ficha y prueba del menú en Chrome headless a 375px y 1280px.

**Ajustes menores y comprobante Perú (2026-09-28):** el enlace del carrito ya no corta la página si falta WooCommerce; `search.php` queda solo para contenido (las búsquedas de productos las renderiza WooCommerce); la imagen de categoría usa `alt=""`; las etiquetas del menú móvil vienen de PHP y son traducibles; el botón «Conoce Frave» usa el campo ACF `frave_story_page` con `nosotros` como respaldo; se añaden `screenshot.png` y `languages/frave.pot`; el tema requiere PHP 8.1. El plugin `frave-peru` añade boleta/factura con validación de DNI, CE, pasaporte y RUC (dígito verificador). Evidencia: lint PHP, `npm.cmd run build`, 15 casos unitarios de validación, y en Chrome headless errores de DNI y RUC inválidos más los pedidos locales de prueba #33 (factura) y #34 (boleta), cada uno con solo los datos de su comprobante; datos visibles en pedido recibido, correo y administrador.

**Libro de Reclamaciones (2026-09-28):** lint PHP, build, pruebas de Pascua (2024–2027), plazo de 15 días hábiles con Semana Santa, 8 de octubre y fin de año, contador correlativo y trampa de tiempo. En Chrome headless: errores de validación con datos conservados, rechazo del campo trampa, hojas locales de prueba 000001-2026 y 000002-2026 con confirmación, clave de confirmación alterada rechazada, formulario sin desbordamiento a 375px, respuesta enviada desde el administrador y hoja marcada como respondida. Mailpit capturó la constancia, el aviso a la tienda (con Reply-To del consumidor) y la respuesta. La prueba encontró y corrigió IDs duplicados entre metabox y textarea. La zona horaria local sigue en UTC.

**WhatsApp (2026-09-28):** lint PHP, build, pruebas de normalización y validación del número. Con un número ficticio temporal (ya retirado): enlaces `wa.me` correctos en portada, ficha (mensaje con nombre y enlace del producto) y pie; sin botón flotante en el checkout; en móvil, botón de 56×56 px sin desbordamiento, bajo el fondo del menú abierto y sin tapar la última línea del pie. Sin número no se muestra ningún botón. La prueba visual detectó que las reglas sin capa de WooCommerce y de `theme.json` alteraban colores y disposición; se corrigió.

**Mini-carrito (2026-09-28):** lint, build y prueba en Chrome headless. Carrito vacío: apertura desde el icono con «Explorar productos», cierre con Escape y foco devuelto al icono. Tienda: añadir por AJAX abre el cajón con el producto, el mensaje y el contador en 1; el Tab recorre solo el cajón; el fondo lo cierra; eliminar por AJAX deja el contador en 0 y el foco en el título. Ficha variable: el formulario abre el cajón al recargar con la variante «50 ml». La página del carrito no tiene cajón. A 375px ocupa el ancho sin desbordamiento. Sin JavaScript el cajón queda oculto y el icono enlaza al carrito. La prueba detectó que WooCommerce enfoca su aviso 500 ms después de cargar, detrás del cajón abierto; una guarda `focusin` devuelve el foco al cajón.

**Filtros del catálogo (2026-09-28):** lint, build y datos locales de `seed-catalog-filters.php`. En el servidor: tienda con 6 secciones; familia Cítrica o Floral (2 productos, recuentos ajustados); solo disponibles (oculta el agotado); en oferta (1); precio 30–40 con recuentos que ya excluyen productos fuera de rango; categoría con subcategoría desplegada; búsqueda que conserva `s` en enlaces y en el formulario de precio; combinación sin resultados con chips y «Quitar todos los filtros»; valoración; redirección de precios vacíos, invertidos o no numéricos; `noindex, follow` al filtrar. En Chrome headless: barra lateral fija en escritorio; clic en atributo y envío del precio conservando filtros; cajón modal en móvil con foco retenido, Escape y retorno al botón; al pasar a escritorio vuelve a ser barra lateral; el mini-carrito (refactorizado al módulo `drawer.js`) repite los resultados de su prueba y cierra los filtros al abrirse. Se corrigieron la URL base con enlaces simples (`?post_type=product`), los recuentos con precio y el botón de resultados que WooCommerce volvía gris.

**Páginas institucionales (2026-09-28):** lint PHP, build, tema a la versión 1.1.0 (necesario para que WordPress registre los patrones nuevos). Con las páginas publicadas temporalmente y datos de contacto ficticios (todo revertido después): canales del Personalizador y un solo formulario en Contacto; errores de validación con datos conservados, campo trampa rechazado, envío válido guardado en «Mensajes» y capturado en Mailpit con Reply-To del remitente; FAQ con índice de 5 grupos y acordeones; extracción de preguntas para `FAQPage` que omite las respuestas `[PENDIENTE]`; política con fecha en español, 9 secciones cuyas anclas coinciden con el índice y «Otras políticas»; enlaces legales del pie en orden de página; sin desbordamiento a 390px; casilla de términos en el checkout con la página publicada y oculta en borrador. El Libro de Reclamaciones repitió su prueba tras mover su antispam a `includes/antispam.php`.

**Botones, rendimiento y tabla de atributos (2026-10-05):** en modo producción (`FRAVE_VITE_DEV` en `false`, restaurado después): CSS, JS y las dos fuentes con precarga responden 200, sin script de emojis. Botones del listado, ficha simple y variable (deshabilitada hasta elegir), carrito (cupón, actualizar, finalizar compra), checkout y cuenta: fondo oscuro y texto blanco; los enlaces-botón tenían el texto invisible por el color global de enlaces y se corrigió. Etiqueta de oferta naranja en listado y ficha. Mini-carrito sin cambios. Con la tabla de atributos de WooCommerce activada temporalmente, sus recuentos ignoraban los atributos elegidos con lógica «o»; se completó el filtro de recuentos y 7 combinaciones dan resultados idénticos con y sin la tabla.

## Fase 3 — Experiencia de catálogo y compra

Objetivo: facilitar la exploración de un catálogo real y mejorar los flujos de compra.

- [ ] Revisar taxonomía, atributos, subcategorías, nombres y fichas de productos reales.
- [x] Preparar una propuesta inicial de categorías y atributos, pendiente de validación con el inventario real.
- [x] Crear una plantilla con los encabezados del importador CSV nativo de WooCommerce, sin filas de productos ficticias.
- [x] Cargar en la instalación local cuatro categorías y cinco productos publicados de muestra para revisar portada, catálogo, ficha simple, ficha variable y estado agotado; marcar todos los productos con `[MUESTRA]`.
- [x] Contacto por WhatsApp: botón flotante (oculto en el checkout), «Consultar por WhatsApp» con el producto en la ficha y enlace en el pie, configurables en el Personalizador.
- [ ] Configurar el número de WhatsApp Business de Frave y el horario de atención que se comunicará.
- [x] Filtros del catálogo con parámetros de WooCommerce: categorías, precio, disponibilidad, oferta, un filtro por cada atributo global (automático), valoración, chips de filtros activos, cajón en móvil y `noindex` en vistas filtradas.
- [ ] Crear los atributos globales reales (familia olfativa, presentación, etc.) al cargar el inventario definitivo, siguiendo `GUIA_CATALOGO.md`.
- [x] Botones de WooCommerce con el estilo del tema en todo el sitio (listado, ficha, carrito, checkout, cuenta) y etiqueta «¡Oferta!» en el naranja accesible.
- [x] Rendimiento: precarga de Lato (400 y 700) desde el manifest y sin el script de emojis de WordPress.
- [ ] Mejorar galería, selección de variaciones, información de stock y contenido de ficha según los tipos de producto reales.
- [x] Mini-carrito lateral: se abre desde el icono del carrito y al añadir un producto (AJAX o formulario de la ficha), con eliminación por AJAX, foco retenido y sin JavaScript sigue enlazando al carrito.
- [ ] Evaluar cross-selling, productos relacionados y navegación ampliada; implementar solo donde reduzcan fricción.
- [ ] Completar estados de carga, vacío y error para búsqueda, filtros, carrito y formularios.
- [ ] Probar teclado, foco, lectores de pantalla y viewports pequeños en todos los flujos de compra.
- [x] Validar con productos temporales las fichas simple, variable y agotada, añadir al carrito, carrito y formulario de checkout sin crear pedidos.

**Criterio de cierre:** un cliente encuentra un producto, elige una variación válida, completa un pedido de prueba y consulta su estado desde la cuenta sin bloqueos en móvil ni escritorio.

## Fase 4 — Preparación para producción

Objetivo: habilitar ventas reales con configuración comercial, calidad técnica y despliegue controlado.

- [ ] Definir con Frave zonas y tarifas de envío, impuestos, moneda, devoluciones, privacidad y términos comerciales.
- [x] Pedir el comprobante (boleta o factura) en el checkout clásico, con DNI, carné de extranjería, pasaporte o RUC validados; plugin `frave-peru`, fuera del tema.
- [ ] Confirmar con Frave si la boleta exige documento siempre (comportamiento actual) o solo por encima de S/ 700.
- [ ] Integrar la emisión electrónica de boletas y facturas con un proveedor OSE/PSE autorizado por SUNAT, usando los datos que guarda `frave-peru`.
- [x] Libro de Reclamaciones virtual en `frave-peru`: hoja con numeración correlativa, constancia por correo, plazo de 15 días hábiles, respuesta desde el administrador y enlace en el pie del tema.
- [ ] Completar en producción razón social, RUC y domicilio del libro, zona horaria America/Lima y SMTP; validar el texto de la hoja con la asesoría legal de Frave y los feriados del año.
- [ ] Seleccionar e integrar pasarela real; verificar pagos aprobados, rechazados, reembolsos y notificaciones en un entorno de pruebas.
- [ ] Revisar correos transaccionales, estados de pedido, inventario, cupones y cuentas con datos representativos.
- [ ] Configurar dominio, HTTPS, copias de seguridad, actualizaciones, caché y observabilidad en hosting o staging.
- [ ] Validar SEO técnico, títulos, indexación, breadcrumbs y compatibilidad con el plugin SEO elegido, sin duplicar metadatos.
- [ ] Medir LCP, CLS e INP en páginas de portada, catálogo, producto, carrito y checkout; corregir cuellos de botella comprobados.
- [ ] Ejecutar checklist de accesibilidad y pruebas funcionales completas en staging, con y sin ACF cuando proceda.
- [ ] Compilar y desplegar `assets/dist/` junto con el tema; verificar rutas del manifest y ausencia de errores PHP y JavaScript.

**Criterio de cierre:** pedido real de bajo importe validado de extremo a extremo en producción, con envío, impuestos, correos y pasarela correctos; políticas publicadas y plan de recuperación probado.

## Fase 5 — Evolución continua

- [ ] Priorizar mejoras con datos de búsqueda, conversión, soporte y rendimiento.
- [ ] Añadir nuevas secciones o componentes únicamente cuando respondan a necesidades reales del catálogo y del equipo editorial.
- [ ] Revisar compatibilidad al actualizar WordPress, WooCommerce, ACF, Tailwind y Vite.
- [ ] Documentar cada futuro override de WooCommerce, su motivo y la versión de plantilla soportada.

## Cómo mantener este plan

Al cerrar una tarea, marca su casilla y añade evidencia en el commit, PR o nota de la fase. Si una tarea cambia de alcance, actualiza su criterio de cierre. Mantén la configuración comercial específica de cada entorno fuera del tema y evita registrar credenciales o datos de clientes en Git.
