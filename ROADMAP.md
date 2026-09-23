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

**Estado actual:** hay una tienda básica para desarrollo y pruebas. El pago de prueba y la configuración comercial local no habilitan ventas reales.

## Fase 2 — Identidad y contenido editorial

Objetivo: convertir la base funcional en una experiencia de marca completa y editable sin código.

- [ ] Aprobar paleta, tipografía, fotografía, tono de voz y uso del logotipo definitivo.
- [x] Añadir tokens semánticos de acento, primario, fondo, texto y borde; documentar la tipografía y el layout actuales en `DESIGN.MD`.
- [x] Corregir el contraste de botones, badges, paginación y enlaces del pie afectados por el naranja o por CSS de WooCommerce.
- [ ] Completar una auditoría de contraste de todos los estados y controles WooCommerce con contenido definitivo.
- [ ] Revisar la organización responsive del CSS para cumplir la estrategia móvil primero sin regresiones.
- [ ] Sustituir las imágenes de prueba o respaldos visuales por fotografías propias optimizadas, con texto alternativo y tamaños adecuados.
- [x] Añadir módulos independientes de presentación, guía de exploración y CTA hacia la tienda; categorías y destacados siguen alimentados por WooCommerce.
- [x] Definir campos editoriales controlados en ACF gratuito y versionar el grupo en `acf-json/`; mantener los datos de producto en WooCommerce.
- [x] Crear en el entorno local la página editable `Nosotros` y asignar menús principal y de pie desde WordPress.
- [ ] Definir con Frave el contenido de colecciones, novedades, ventajas verificables, recursos educativos y canales de contacto antes de añadir esos módulos y páginas.

**Criterio de cierre:** el equipo de Frave puede actualizar textos, imágenes y secciones aprobadas desde WordPress sin alterar el layout; la identidad visual está aprobada en móvil y escritorio.

**Validación de esta iteración (2026-09-23):** lint de los 27 PHP del tema, `npm run build`, manifest con seis archivos presentes, portada HTTP 200 con ACF activo e inactivo, campos ACF cargados, página `Nosotros` visible, Lato servido por Vite con HTTP 200 y portada de producción cargando CSS/JS del manifest. Se inspeccionaron las secciones nuevas a 408px y 1440px; la revisión completa de compra y accesibilidad sigue pendiente.

## Fase 3 — Experiencia de catálogo y compra

Objetivo: facilitar la exploración de un catálogo real y mejorar los flujos de compra.

- [ ] Revisar taxonomía, atributos, subcategorías, nombres y fichas de productos reales.
- [ ] Diseñar filtros útiles según el catálogo; conservar URLs y consultas compatibles con WooCommerce.
- [ ] Mejorar galería, selección de variaciones, información de stock y contenido de ficha según los tipos de producto reales.
- [ ] Evaluar mini-carrito, cross-selling, productos relacionados y navegación ampliada; implementar solo donde reduzcan fricción.
- [ ] Completar estados de carga, vacío y error para búsqueda, filtros, carrito y formularios.
- [ ] Probar teclado, foco, lectores de pantalla y viewports pequeños en todos los flujos de compra.

**Criterio de cierre:** un cliente encuentra un producto, elige una variación válida, completa un pedido de prueba y consulta su estado desde la cuenta sin bloqueos en móvil ni escritorio.

## Fase 4 — Preparación para producción

Objetivo: habilitar ventas reales con configuración comercial, calidad técnica y despliegue controlado.

- [ ] Definir con Frave zonas y tarifas de envío, impuestos, moneda, devoluciones, privacidad y términos comerciales.
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
