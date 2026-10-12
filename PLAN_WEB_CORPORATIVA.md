# Plan de la web corporativa de Frave

- **Inicio:** 10 de octubre de 2026.
- **Estado:** fases 1, 2 y 3 publicadas en frave.pe. Pendientes: la página de Contacto y el WhatsApp en producción (paso 4 de «Publicar la fase 1»), los productos del catálogo y la aprobación del sistema visual en `/?sistema-visual`.
- **Sustituye como plan vigente a:** [ROADMAP.md](ROADMAP.md), que queda como registro de la base construida y como plan de la futura tienda en línea.

## Cambio de rumbo

Frave pospone la venta en línea. La web pasa a ser un **sitio corporativo de captación**: presenta la empresa y sus líneas, muestra un catálogo informativo y lleva al cliente a contactar (formulario, WhatsApp). La tienda se implementará más adelante sobre la misma base.

| Decisión | Detalle |
| --- | --- |
| Referencia principal | [disaromas.com](https://disaromas.com/): estructura de secciones, estilo, transiciones y arte. Se toma la idea, no los textos, fotos ni iconos. |
| Público | Todo tipo de cliente, con **aire corporativo**. |
| Líneas de negocio | **Sabores, Fragancias y Envases**. El lema del logo («Fragancias y envases») se mantiene por ahora. |
| Catálogo | Sección extra para informar mejor. **Sin precios**: el precio se da cuando el cliente contacta. |
| Comercio electrónico | En pausa detrás de un interruptor («modo catálogo»). Carrito, checkout, cuenta y boleta/factura siguen en el código para reactivarlos. |
| Frontend | Nivel profesional y llamativo: la web tiene que enganchar al cliente. |

## Principios de calidad

Lo que se toma de la referencia: hero a pantalla completa con palabras resaltadas, secciones que se fijan y se apilan al hacer scroll, carruseles, formas y fotos recortadas, bloque de ventajas, footer que se descubre al final y chat de WhatsApp guiado.

Con identidad Frave: naranja `#fc4c02` como acento, tinta oscura y blanco como base corporativa, Lato, y la gota y el frasco del isotipo como motivo gráfico.

Errores de la referencia que se evitan desde el principio:

- [ ] Imágenes en WebP/AVIF con `srcset` y carga diferida (la portada de la referencia pesa 4,2 MB en 80 peticiones).
- [ ] Sin jQuery, Swiper, AOS ni librerías por CDN: CSS nativo (`position: sticky`, scroll-snap) y el JavaScript propio del tema.
- [ ] Un único responsable de las metaetiquetas SEO (el tema no duplica canonical ni Open Graph).
- [ ] Un `h1` por página y texto alternativo útil en las imágenes que comunican.
- [ ] Mapa que se carga solo al pedirlo, sin Google Maps en cada visita.
- [ ] Antispam sin reCAPTCHA (campo trampa y tiempo mínimo, ya implementados).
- [ ] Sin enlaces a anclas inexistentes, botones vacíos ni restos de pruebas en el HTML.
- [ ] Movimiento reducido respetado en todas las transiciones.

## Mapa del sitio objetivo

```
Inicio
├── Nosotros
├── Soluciones ▾  Sabores · Fragancias · Envases
├── Catálogo      (sin precios, con filtros)
├── Cómo trabajamos
├── Blog          (solo cuando haya artículos)
└── Contacto ▾    Formulario · WhatsApp · Libro de Reclamaciones
Pie: Preguntas frecuentes · Política de privacidad · Términos de uso
```

Las páginas de envíos y de cambios y devoluciones quedan en borrador hasta que haya venta en línea.

## Acuerdos de gerencia (12-10-2026)

Documento «ACUERDOS FRAVE - PROYECTO PAG WEB» (fuera del repositorio). Resumen:

- **Secciones pedidas:** Home, Nosotros, Experticia, Catálogo, Líneas, Soluciones y Blog; venta en línea «próximamente».
- **Posicionamiento:** empresa peruana B2B de provisión integral de fragancias e insumos para perfumería, cosmética, cuidado personal y del hogar; conecta a sus clientes con fabricantes internacionales de fragancias y sabores; asesoría técnica; más de 500 referencias de fragancias y esencias hidrosolubles.
- **Creencias y comportamientos:** 01 Orientamos nuestro trabajo a generar valor · 02 Construimos relaciones que perduran · 03 Evolucionamos con el mercado · 04 Crecemos a través del conocimiento compartido. Esencia: «Conectamos conocimiento, confianza e innovación para crear valor».
- **Razones / experticia:** nube de palabras (56 términos de aplicaciones y atributos) con la silueta del mapa del Perú.
- **Árbol de categorías:**
  - Fragancias → Perfumería (diseñador, árabe, exclusiva o nicho) · Fragancias de consumo: cosméticas (cabello, piel, mascotas) y cuidado del hogar (textil, superficies, cocina y lavavajillas) · Ambientación (difusores de varitas, vehículos, aerosoles y sprays, marketing olfativo).
  - Sabores («Aromas») → bebidas y lácteos, confitería y panadería, salados, cuidado bucal. Imágenes sugeridas: manzana, maracuyá, fresa, vainilla, panetón, café y té.
  - Envases («Insumos de envases») → frascos de vidrio para perfumería, frascos para difusores, válvulas.
- **Textos de cada concepto:** perfumería, nicho, diseñador, árabe, cosméticas, hogar, ambientación, envases y aromas (para las páginas de línea).
- **Referencia visual adicional:** milagrosbeauty.com (cabecera en cápsula, botones píldora con flecha, tarjetas redondeadas con etiqueta, fondos teñidos del color de marca, banda de mensajes en movimiento).
- **Identidad:** la lámina de creencias usa un logotipo FRAVE azul marino con «INSUMOS AROMÁTICOS» y una onda naranja, distinto del logotipo naranja actual («FRAGANCIAS Y ENVASES»). Por confirmar.

Recursos gráficos en `D:\Frave-webecursos-graficosrave-v1` (generados con IA, WebP ya optimizados, 40–200 KiB): portadas de Sabores, Fragancias y Envases en escritorio (16:9, espacio a la izquierda) y móvil (4:5, espacio arriba), franja de contacto, imagen base del hero y dos *storyboards* de referencia. Son conceptuales: los envases no son modelos confirmados del catálogo.

## Fases

**Flujo de trabajo:** mientras la web esté en construcción se trabaja directamente sobre `main` en los dos repositorios, sin ramas ni pull requests. Cada bloque de trabajo se cierra con sus pruebas, se sube a `main` con el CI en verde y se publica por SSH (ver [GUIA_DESPLIEGUE.MD](GUIA_DESPLIEGUE.MD)). Cuando un cambio toca el tema y el plugin, **el plugin se sube y se publica primero**: el CI del tema instala `frave-peru` desde su rama `main`.

### Fase 1 — Modo catálogo y navegación corporativa

Objetivo: que frave.pe deje de parecer una tienda mientras se construye lo demás.

Plugin `frave-peru`:

- [x] Interruptor «Modo catálogo» en *WooCommerce → Ajustes → Productos*, activado por defecto (versión 1.1.0 del plugin).
- [x] Con el modo activo: productos no comprables, precios y etiqueta de oferta ocultos (también en `?wc-ajax=get_variation`), carrito, checkout y cuenta redirigen al catálogo, sin orden ni filtro por precio, Store API pública cerrada y sin `offers` en los datos estructurados.
- [x] Formulario de contacto: temas de una web de captación (cotización, productos, distribución, proveedores), sin número de pedido, y mensaje rellenado desde «Solicitar cotización» (`?producto=ID`).

Tema `frave`:

- [x] Header sin cuenta ni carrito, con buscador y botón «Solicitar asesoría»; sin mini-carrito ni `wc-cart-fragments`. La barra de búsqueda queda solo en el catálogo.
- [x] Menú principal con submenús accesibles (panel en escritorio, acordeón en móvil, descripciones) y menús de respaldo con la estructura nueva (versión 1.2.0 del tema).
- [x] Ficha de producto con bloque «¿Te interesa este producto?»: «Solicitar cotización» y «Consultar por WhatsApp».
- [x] Tarjetas del catálogo con «Ver detalles» en lugar de «Añadir al carrito».
- [x] Filtros sin precio ni oferta.
- [x] Textos de tienda sustituidos (portada, 404, búsqueda, pie): «Catálogo», «Ver el catálogo», sabores, fragancias y envases.
- [x] Pie con columna de contacto (canales, cotización, WhatsApp, Libro de Reclamaciones) en lugar de «Tu cuenta».
- [x] Pruebas E2E: modo catálogo, cotización y submenús; carrito y checkout se repiten en CI con el modo desactivado.

Sitio (base de datos, local y producción):

- [x] Local: página «Catálogo», categorías Sabores, Fragancias y Envases, contacto publicado y menús nuevos (`.local-wordpress/seed-sitio-corporativo.php`).
- [ ] Producción: página de la tienda renombrada a «Catálogo» (`/catalogo/`).
- [ ] Producción: categorías `sabores`, `fragancias` y `envases`.
- [ ] Producción: página de contacto revisada y publicada (sin ella no aparecen «Solicitar asesoría» ni «Solicitar cotización»).
- [ ] Producción: número de WhatsApp Business en *Apariencia → Personalizar → Contacto*.
- [ ] Producción: menú principal asignado (opcional: mientras no haya uno, el tema muestra el de respaldo con la misma estructura).

#### Publicar la fase 1

1. Publicar **primero el plugin** (`main` de `frave-peru`): con el plugin nuevo y el tema anterior, la web ya oculta precios y compras.
2. Publicar el tema (`main` de `frave`).
3. En el servidor, desde la carpeta del sitio (`~/domains/frave.pe/public_html`):

   ```bash
   wp post update $(wp option get woocommerce_shop_page_id) --post_title="Catálogo" --post_name=catalogo
   wp term create product_cat Sabores --slug=sabores
   wp term create product_cat Fragancias --slug=fragancias
   wp term create product_cat Envases --slug=envases
   wp rewrite flush
   ```

   Si una categoría ya existe, WP-CLI lo indica y no la duplica.
4. Publicar la página de contacto y configurar WhatsApp desde el administrador; vaciar la caché de LiteSpeed.
5. Comprobar en frave.pe: sin precios ni carrito, `/carrito/` lleva al catálogo, la ficha ofrece cotización y el formulario llega con el producto.

### Fase 2 — Sistema visual corporativo

- [x] Tokens: noche, tinta y blanco como base, naranja como acento, naranja suave, superficies, radios, sombras y movimiento.
- [x] Tipografía Lato con títulos grandes y ligeros (peso 300 añadido y precargado) y énfasis con negrita, píldora o marcador.
- [x] Motivos gráficos con la gota y el frasco: marcos de imagen, isotipo como marca de agua y sello, órbitas y separador.
- [x] Componentes: botones `.btn` con icono, píldoras, tarjetas, iconos de línea, cifras, cabecera en cápsula flotante con submenú con iconos, banda de cabecera para páginas y catálogo, pie oscuro con llamada a la acción.
- [x] Librería de transiciones: hero fijo con telón, apilado al hacer scroll, aparición, carrusel con *scroll snap*, pie que se descubre, cabecera al hacer scroll y contadores.
- [x] [DESIGN.MD](DESIGN.MD) reescrito como guía del sistema y paleta de `theme.json` actualizada.
- [x] Guía interna en `/?sistema-visual` (solo administradores) para revisar y aprobar el sistema.
- [ ] Aprobación de Frave: paleta, tipografía, formas y movimiento.

### Fase 3 — Portada nueva

- [x] Hero a pantalla completa con vídeo (una reproducción, último fotograma como imagen fija, botón de pausa, sin vídeo con movimiento reducido o ahorro de datos), título con píldora y dos acciones; fijo en escritorio mientras sube la sección siguiente.
- [x] Nuestras soluciones: Sabores, Fragancias y Envases en tarjetas con forma de frasco (imagen y descripción de cada categoría); carrusel en móvil.
- [x] Cómo trabajamos: introducción fija y tres pasos apilados.
- [x] Productos destacados sin precio.
- [x] Razones para elegirnos: cuatro tarjetas con icono.
- [x] Panel del catálogo con buscador y accesos a las líneas (la invitación a contactar ya está en el pie).
- [x] Tendencias: solo con entradas fuera de la categoría por defecto.
- [x] Dónde estamos: canales y mapa de Google bajo demanda; solo con dirección configurada.
- [x] Campos ACF de la portada reorganizados por pestañas (`frave_home_*`), con los textos por defecto en `inc/home.php` y resaltado con `*asteriscos*`.
- [x] Vídeo del hero: queda como definitivo el de frutas creado por el equipo (720p, 8 s).
- [ ] Validar con Frave los textos por defecto de la portada y subir imágenes de las categorías.

### Fase 4 — Páginas de línea

- [ ] Plantilla «Línea» conectada a una categoría de producto.
- [ ] Tarjetas a partir de las subcategorías de WooCommerce, con enlace al catálogo filtrado.
- [ ] Bloque de presentaciones y aplicaciones, destacados de la línea y llamada a la acción.
- [ ] Páginas Sabores, Fragancias y Envases; menú apuntando a ellas.

### Fase 5 — Empresa y contacto

- [ ] Nosotros: historia, objetivo, valores y equipo.
- [ ] Cómo trabajamos: pestañas en escritorio, acordeón en móvil, con enlaces directos.
- [ ] Contacto ampliado: empresa, ciudad, línea de interés, producto y consentimiento según la Ley 29733.
- [ ] Chat de WhatsApp guiado (cotización, consulta de producto, reclamo), sin guardar datos en la web.

### Fase 6 — Blog, SEO, rendimiento y salida

- [ ] Blog y artículo con migas de pan y compartir funcional.
- [ ] Plugin SEO elegido, sin metadatos duplicados; títulos y descripciones.
- [ ] Imágenes optimizadas y medición de LCP, CLS e INP en portada, línea, catálogo, ficha y contacto.
- [ ] Auditoría de accesibilidad con teclado y lector de pantalla.
- [ ] Revisión completa en producción y aviso a Frave.

## Lo que debe aportar Frave

Se puede construir con textos de ejemplo marcados; para publicar hace falta:

- [ ] Historia, años de experiencia, objetivo y valores. *Recibidos: texto de Nosotros y creencias (12-10); faltan años y equipo.*
- [ ] Ventajas reales para «Razones para elegirnos».
- [x] Subcategorías de Sabores, Fragancias y Envases (acuerdos de gerencia).
- [ ] Fotos de productos, equipo y local.
- [ ] Sedes, horarios, WhatsApp, correo y redes sociales.
- [ ] Primeros productos del catálogo (nombre, descripción, fotos, presentaciones).
- [ ] Imagen de cada línea y validación de los textos de la portada. *Imágenes recibidas (IA, conceptuales); falta validar textos.*
- [ ] Certificaciones, solo si existen.
- [ ] Texto revisado de la política de privacidad y los términos de uso.

## Registro de avances

| Fecha | Avance |
| --- | --- |
| 2026-10-10 | Cambio de rumbo acordado y plan creado. Comienza la fase 1 en las ramas `feature/modo-catalogo` del tema y del plugin. |
| 2026-10-10 | Fase 1 implementada. Evidencia: `php -l`, PHPCS del tema y del plugin, ESLint y build; Playwright en local con el modo catálogo (19 pruebas correctas, 7 de compra omitidas a propósito) y con la tienda activa (12 correctas); revisión en el navegador a 1366px y 375px (cabecera, submenús, catálogo, ficha, cotización con el formulario rellenado, menú móvil) sin desbordamiento horizontal ni errores PHP; tienda intacta al desactivar el modo. Se reforzó la prueba del producto variable, que podía enviar el formulario antes de que WooCommerce eligiera la variación. Pendiente: publicación y datos de producción. |
| 2026-10-11 | Pull requests de la fase 1 fusionados (tema #3, plugin #2). A partir de ahora se trabaja directamente sobre `main`. |
| 2026-10-11 | Fase 2 implementada (tema 1.3.0, plugin 1.1.1: sin valoraciones en modo catálogo). Evidencia: PHPCS, ESLint y build; Playwright en local con el modo catálogo (24 correctas, 7 de compra omitidas a propósito) y con la tienda activa (16 correctas); capturas a 1440px y 390px de la guía, catálogo, ficha, contacto, portada, 404, submenú y menú móvil, sin desbordamiento horizontal; cabecera fija medida a 10–12 px del borde al hacer scroll. Se corrigió el orden de capas CSS, que hacía ganar a los estilos base sobre los componentes. |
| 2026-10-11 | Fase 3 implementada (tema 1.4.0) con el vídeo de frutas provisional (720p, optimizado de 2,4 MB a 0,84 MB en MP4 y 0,72 MB en WebM, sin sonido). Evidencia: PHPCS, ESLint y build; Playwright en local con el modo catálogo (29 correctas, 7 de compra omitidas a propósito) y con la tienda activa (21 correctas), incluidas las nuevas pruebas del hero (pausa y movimiento reducido), líneas, pasos y mapa; capturas a 1440px y 390px sin desbordamiento, con el hero fijo y la sección de soluciones subiendo por encima. La prueba de la pausa detectó que la capa de texto tapaba el botón en escritorio; corregido. |
| 2026-10-12 | Fases 1 a 3 publicadas en frave.pe (revisión `0f26947`). Comprobado en Chrome a 1440px y 390px: portada sin desbordamiento, vídeo reproduciéndose, modo catálogo activo (`/carrito/` lleva al catálogo) y las tres líneas enlazadas. El vídeo de frutas queda como definitivo; se probó recodificar el WebM con más calidad y la mejora no compensaba el peso. LiteSpeed servía el `.webm` como `text/plain`: se añade `assets/media/.htaccess` con su tipo y una caché de 7 días. Falta en producción: página de Contacto (sin ella no aparece «Solicitar asesoría») y WhatsApp. |
| 2026-10-12 | Revisados los acuerdos de gerencia (secciones, Nosotros, creencias, árbol de categorías, textos por concepto, referencia milagrosbeauty.com) y los recursos gráficos v1. Pendiente de confirmar: logotipo azul «Insumos aromáticos», diferencia entre Líneas, Soluciones y Experticia, y nombre «Sabores» o «Aromas». |
