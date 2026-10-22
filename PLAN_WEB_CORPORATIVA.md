# Plan de la web corporativa de Frave

- **Inicio:** 10 de octubre de 2026.
- **Estado:** fases 1 a 7 publicadas en frave.pe (tema 1.13.0, plugin 1.6.0; blog con sus cuatro artículos de inicio publicado el 13-10). Faltan los productos de Frave y, en el administrador, WhatsApp y datos de contacto, redes sociales, Libro de Reclamaciones, Search Console y aprobación del sistema visual.
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

Comprobado en la auditoría de la fase 7 (13-10-2026):

- [x] Imágenes en WebP/AVIF con `srcset` y carga diferida (la portada de la referencia pesa 4,2 MB en 80 peticiones).
- [x] Sin jQuery, Swiper, AOS ni librerías por CDN: CSS nativo (`position: sticky`, scroll-snap) y el JavaScript propio del tema. En modo catálogo, jQuery solo se carga en la ficha, para la galería de WooCommerce.
- [x] Un único responsable de las metaetiquetas SEO (el tema no duplica canonical ni Open Graph, y se aparta si se activa un plugin SEO).
- [x] Un `h1` por página y texto alternativo útil en las imágenes que comunican.
- [x] Mapa que se carga solo al pedirlo, sin Google Maps en cada visita.
- [x] Antispam sin reCAPTCHA (campo trampa y tiempo mínimo).
- [x] Sin enlaces a anclas inexistentes, botones vacíos ni restos de pruebas en el HTML.
- [x] Movimiento reducido respetado en todas las transiciones.

## Mapa del sitio objetivo

Según los acuerdos de gerencia (12-10-2026): **Líneas** son Sabores, Fragancias y Envases; **Soluciones**, las industrias que se atienden; **Servicios** (llamada «Experticia» hasta el 15-10-2026, renombrada para no parecerse a la referencia), la asesoría técnica, cómo trabajamos y la nube de palabras del Perú. La línea de sabores se sigue llamando «Sabores».

```
Inicio
├── Nosotros
├── Líneas ▾       Sabores · Fragancias · Envases
├── Soluciones ▾   por industria (perfumería, cosmética, hogar, ambientación, alimentos y bebidas…)
├── Servicios      asesoría técnica, cómo trabajamos, nube de palabras del Perú
├── Catálogo       (sin precios, con filtros)
├── Blog           (solo cuando haya artículos)
└── Contacto ▾     Formulario · WhatsApp · Libro de Reclamaciones
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

Recursos gráficos en `D:\Frave-web
ecursos-graficosrave-v1` (generados con IA, WebP ya optimizados, 40–200 KiB): portadas de Sabores, Fragancias y Envases en escritorio (16:9, espacio a la izquierda) y móvil (4:5, espacio arriba), franja de contacto, imagen base del hero y dos *storyboards* de referencia. Son conceptuales: los envases no son modelos confirmados del catálogo.

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

- [x] Plantilla «Línea» conectada a una categoría de producto (ACF «Línea Frave» o el slug de la página), con los textos acordados con la gerencia como valores por defecto.
- [x] Árbol de subcategorías acordado y páginas /sabores/, /fragancias/ y /envases/ creados por `bin/crear-lineas.php`.
- [x] Portafolio a partir de las subcategorías de WooCommerce: descripción, tipos como etiquetas, «Cotizar» con la subcategoría escrita en el formulario (`?linea=`, plugin 1.2.0) y «Ver productos» solo cuando los hay.
- [x] Presentación con destacados (cifra animada), aplicaciones en una banda de palabras ligada al scroll, productos de la línea, cierre con la imagen de contacto y las otras líneas.
- [x] Imágenes de los recursos gráficos v1 en el hero de cada línea y en las tarjetas de la portada; menú «Líneas», portada y categorías del catálogo enlazan con las páginas.
- [x] Imágenes generadas (serie beige del 14-10) en las 24 categorías y subcategorías: tarjetas del portafolio y de Soluciones, miniaturas redondas en los tipos, cabecera de cada categoría del catálogo en escritorio; galería «Del fruto a tu producto» con 13 sabores en Sabores; fondo beige en las secciones finales de la portada; foto de reunión (escena ilustrativa, no el equipo real) en Nosotros. Una imagen subida en *Productos → Categorías* sustituye a la del tema (tema 1.13.0).
- [ ] Validar con Frave los textos y las imágenes (generadas con IA; los envases no son modelos del catálogo, y la reunión no muestra a su equipo).

#### Publicar la fase 4

1. Publicar primero el plugin (1.2.0) y después el tema (1.5.0), como siempre.
2. Desde la carpeta del sitio, crear el árbol de subcategorías y las páginas de línea:

   ```bash
   wp eval-file ~/repos/frave-theme/bin/crear-lineas.php
   wp litespeed-purge all
   ```

3. Si el administrador de ACF ofrece «Sincronización disponible» para «Línea Frave», sincronizarlo.
4. Comprobar /sabores/, /fragancias/ y /envases/, el menú «Líneas» y que «Cotizar» abre el formulario con la línea escrita.

### Fase 5 — Empresa, experticia, soluciones y contacto

- [x] Nosotros: el texto de la gerencia, la esencia y las cuatro creencias y comportamientos (5a).
- [ ] Equipo y años de experiencia en Nosotros, cuando Frave los entregue (se pueden escribir en el editor de la página).
- [x] Experticia: nube de palabras con la silueta del Perú, asesoría técnica con destacados, cómo trabajamos (pestañas en escritorio, acordeón en móvil, con enlaces directos) e industrias por línea (5a).
- [x] Soluciones: página central y cinco páginas por industria que cruzan las líneas (perfumería, cosmética y cuidado personal, hogar, ambientación y marketing olfativo, alimentos y bebidas) (5b).
- [x] Razones para elegirnos de la portada con los atributos de la gerencia y enlaces a Experticia (5a).

#### Publicar la fase 5a

1. Publicar el tema (1.6.0); el plugin no cambia.
2. Desde la carpeta del sitio:

   ```bash
   wp eval-file ~/repos/frave-theme/bin/crear-paginas-empresa.php
   wp litespeed-purge all
   ```

3. En ACF, sincronizar «Nosotros Frave» y «Experticia Frave» si aparece «Sincronización disponible».

#### Publicar la fase 5b

1. Publicar el plugin (1.3.0) y después el tema (1.7.0).
2. Desde la carpeta del sitio:

   ```bash
   wp eval-file ~/repos/frave-theme/bin/crear-soluciones.php
   wp litespeed-purge all
   ```

3. En ACF, sincronizar «Solución Frave» si aparece «Sincronización disponible».
4. Configurar el número de WhatsApp (Apariencia → Personalizar → Contacto) para activar el chat guiado.
5. Revisar la política de privacidad, que el consentimiento del formulario enlaza (Ley 29733).
- [x] Contacto ampliado: empresa, ciudad y línea de interés (completada desde `?linea=` o `?producto=`), consentimiento según la Ley 29733 con aviso de derechos y fecha guardada (plugin 1.3.0) (5b).
- [x] Chat de WhatsApp guiado (cotización, consulta de producto, otra consulta; reclamos al Libro de Reclamaciones), sin guardar datos en la web (5b). Necesita el número de WhatsApp en producción.

#### Publicar los ajustes del 15-10 (tema 1.14.0)

Solo cambia el tema. Después de desplegarlo, desde la carpeta del sitio (`~/domains/frave.pe/public_html`):

```bash
wp theme mod set frave_social_linkedin "https://www.linkedin.com/in/frave-fragancias-y-envases-4b8229442"
wp eval-file ~/repos/frave-theme/bin/crear-paginas-empresa.php
wp litespeed-purge all
```

El primero añade LinkedIn a las redes (también se puede hacer en *Apariencia → Personalizar → Contacto*); el segundo renombra la página Experticia a Servicios (`/servicios/`; `/experticia/` redirige). En ACF, sincronizar «Servicios Frave» si aparece «Sincronización disponible».

#### Publicar Líneas y Soluciones unidas (tema 1.16.0)

Solo cambia el tema. Después de desplegarlo, desde la carpeta del sitio:

```bash
wp eval-file ~/repos/frave-theme/bin/crear-lineas.php
wp option update woocommerce_coming_soon no
wp litespeed-purge all
```

El primero añade las descripciones nuevas a las subcategorías que no tenían (no toca las que ya tienen texto). El segundo saca el catálogo del modo «Próximamente» de WooCommerce, que mostraba a los visitantes una página genérica en `/catalogo/` y en cada categoría: con el modo catálogo del plugin activo no hay precios ni compras, así que las páginas de detalle pueden verse. Equivale a *WooCommerce → Ajustes → Visibilidad del sitio → En directo*.

### Fase 6 — Catálogo listo para cargar

- [x] Guía del catálogo y plantilla de importación al día con el árbol acordado: hoja para completar con listas desplegables, ejemplos de las tres líneas (incluido un producto variable), rutas de categoría, atributos e instrucciones ([GUIA_CATALOGO.md](GUIA_CATALOGO.md)).
- [x] Atributos globales por línea (`bin/crear-atributos.php`): presentación, familia olfativa, perfil de sabor, aplicación, solubilidad, capacidad, material y cierre; los filtros siguen ese orden y las medidas se ordenan de menor a mayor.
- [x] Ficha de producto rediseñada: línea y subcategoría sobre el título, datos a la vista, cotización, código y descripción como sección; sin la barra de búsqueda bajo la cabecera.
- [x] Categorías con sus subcategorías como accesos rápidos sobre los productos.
- [x] Especificación de las fotos de producto.
- [x] Importación de prueba en local con el importador de WooCommerce (10 productos y 3 variaciones, todas las columnas asignadas solas).
- [ ] Listado de productos y fotos de Frave; importación en frave.pe.
- [ ] Tienda en «Visible» y revisión de enlaces de líneas, soluciones y destacados con los productos reales.

#### Publicar la fase 6

1. Publicar el tema (1.9.0).
2. Desde la carpeta del sitio: `wp eval-file ~/repos/frave-theme/bin/crear-atributos.php`.
3. Cuando estén los productos: importarlos (ver la guía), volver a ejecutar `crear-atributos.php`, poner la tienda en «Visible» y vaciar la caché.

### Fase 7 — Blog, SEO, rendimiento y salida

- [x] Blog: página de entradas con temas y el último artículo destacado, artículo con migas de pan, tiempo de lectura, compartir (WhatsApp, LinkedIn, Facebook, correo, copiar enlace y menú de compartir del teléfono) y «Sigue leyendo». Aparece en el menú cuando hay artículos publicados. `bin/crear-blog.php` crea la página, las categorías y cuatro artículos de inicio en borrador (13-10).
- [x] Descripción y Open Graph por página desde el tema mientras no haya plugin SEO; favicon con el isotipo (revisión general, 12-10).
- [x] SEO técnico desde el tema, sin metadatos duplicados: datos estructurados (Organization con contacto y redes, WebSite, BlogPosting y BreadcrumbList), canonical en listados, `noindex` en la búsqueda y en vistas filtradas, el extracto de una página como su descripción y redes sociales en el Personalizador y en el pie (13-10). **Plugin SEO:** no hace falta para salir; si Frave quiere análisis por página, Yoast o Rank Math funcionan y el tema deja de imprimir sus etiquetas solo.
- [x] Rendimiento: medición en frave.pe con móvil simulado (CPU 4× más lenta, 4G lenta): LCP de 1,8 a 2,7 s, CLS 0, bloqueo del hilo principal casi nulo y respuesta a la interacción por debajo de 70 ms. Mejoras: logos WebP (de 231 a 46 KB), portadas de línea un 25 % más livianas, vídeo del hero después de la carga (y nunca con conexión lenta o ahorro de datos), página de contacto en caché y sin scripts de compra ni jQuery en modo catálogo (13-10). La ficha y el catálogo se miden cuando estén los productos.
- [x] Accesibilidad: axe (WCAG 2.2 AA) en las 18 plantillas en escritorio y móvil, con el menú y el chat abiertos; recorrido completo con teclado; esquema de encabezados y regiones; reajuste a 320 px. Corregido: enlace «Saltar al contenido» sin texto visible, migas de la ficha distinguibles solo por color, región de migas en inglés (13-10). Pendiente: una pasada con NVDA o VoiceOver con el contenido definitivo.
- [ ] Search Console: verificar el dominio y enviar el mapa del sitio (`/wp-sitemap.xml`). Necesita la cuenta de Google de Frave.
- [ ] Revisión completa en producción y aviso a Frave.

#### Publicar la fase 7

1. Publicar primero el plugin (1.5.0) y después el tema (1.10.0).
2. Desde la carpeta del sitio: `wp eval-file ~/repos/frave-theme/bin/crear-blog.php` (página /blog/, categorías y cuatro artículos en borrador) y `wp litespeed-purge all`.
3. Revisar los borradores en **Entradas**, cambiar lo que haga falta (textos e imágenes) y publicarlos: el blog aparece en el menú con el primer artículo publicado.
4. Redes sociales en **Apariencia → Personalizar → Contacto** (Facebook, Instagram, LinkedIn, TikTok, YouTube).

### Fase 8 — Puesta en marcha y entrega

Antes de anunciar la web: que los mensajes lleguen, que el sitio esté protegido, que se pueda medir si capta clientes y que Frave sepa manejarlo.

- [ ] **Correo confiable:** prueba real del formulario de contacto y del Libro de Reclamaciones hasta la bandeja de entrada (13-10: mensaje de prueba del formulario guardado y aviso aceptado por el servidor hacia gerenciafrave@gmail.com, el correo del administrador, porque «Mensajes → Ajustes» está vacío; falta confirmar que llegó a la bandeja y no a spam; DNS de frave.pe con SPF que autoriza al servidor de la web, DKIM `default` y DMARC `p=none`); envío por SMTP (correo de Hostinger) en lugar de PHP `mail()`; registros SPF, DKIM y DMARC de frave.pe. Frave usa hoy frave.cp@gmail.com: recomendar un correo del dominio (por ejemplo contacto@frave.pe) para los avisos y la imagen corporativa.
- [ ] **Acceso al webmail desde el enlace viejo:** redirigir `frave.pe/webmail` a `https://webmail.frave.pe` (desde el traslado del dominio `frave.pe` apunta a Hostinger y el correo sigue en Hostbox; el personal tenía guardados `frave.pe/webmail` y `frave.pe:2096`). Accesos vigentes en `CORREO_FRAVE.md`, fuera del repositorio.
- [ ] **Seguridad:** reemplazar el usuario «admin», borrar los temas inactivos, cabeceras de seguridad (HSTS, `X-Content-Type-Options`, `Referrer-Policy`, `Permissions-Policy`), XML-RPC cerrado, límite de intentos de acceso y copias de seguridad automáticas comprobadas en Hostinger.
- [ ] **Medición de contactos:** formularios enviados, clics en WhatsApp y cotizaciones por página. Propuesta: contador propio en el administrador, sin cookies ni aviso; alternativa GA4 con aviso de cookies y cambio en la política de privacidad. *Decisión pendiente de Frave.*
- [ ] **Manual para Frave:** editar textos (ACF), publicar artículos, responder mensajes y reclamos, cargar productos con la plantilla y qué no tocar; publicado como página para compartir.
- [ ] Search Console (y Bing Webmaster Tools): verificar el dominio y enviar `/wp-sitemap.xml`.
- [ ] Revisión final en producción y aviso de salida a Frave.

Después: **carga del catálogo** (con el listado y las fotos de Frave: importación, tienda en «Visible», medición de ficha y catálogo) y, cuando Frave decida vender, **tienda en línea** sobre la base ya construida ([ROADMAP.md](ROADMAP.md)). Al reactivarla, rehacer la cabecera: con la tienda activa el menú suma «Tienda» y «Mi cuenta» y los iconos de carrito y cuenta, y entre 1024 y 1280 px desborda unos 114 px (medido en el CI el 15-10).

### Contenidos de la línea Fragancias

Contenido propio, inspirado en la estructura de la Academia del Perfume (academiadelperfume.com) pero escrito para quien fabrica un producto y no para el consumidor final, sin copiar textos ni citar marcas comerciales.

- [x] **Guía de familias olfativas** (19-10, tema 1.20.0): las nueve familias del atributo del catálogo, con enlace a sus fragancias y familias por aplicación. Imágenes de las diez (portada y familias) integradas el 19-10 (tema 1.20.1). Pendiente: validación de los textos por Frave.
- [x] **Rueda de los aromas** (19-10, tema 1.20.0): sección interactiva en Soluciones → Perfumería; se puede activar en otras industrias.
- [x] **Pirámide olfativa aplicada** (19-10, tema 1.21.0): /fragancias/piramide-olfativa/ con pirámide interactiva, línea de tiempo, qué significa para seis tipos de producto y la prueba de la tira. Imagen de cabecera integrada el 19-10 (tema 1.21.1). Pendiente: validación de los textos por Frave.
- [ ] **Concentraciones y dosificación por aplicación:** Parfum, EDP, EDT, colonia y body splash, y porcentajes orientativos por producto. *Requiere los porcentajes de Frave.*
- [ ] **Cómo elegir la fragancia para tu producto** — *en espera: se consultará a Frave cómo quiere la sección antes de construirla (20-10).* Propuesta aprobada en idea: página `/fragancias/como-elegir/` con un **asistente de cinco preguntas** (producto; para quién; qué debe transmitir; base: agua, alcohol, aceite, cera, tensioactivos o «no sé»; etapa: prototipo, lanzamiento o producción) que da una ficha con 2–3 familias recomendadas (cruce de producto y carácter con los datos de la guía), un consejo según la base y qué esperar de la pirámide, y **«Solicitar asesoría con esta ficha»**: abre el formulario de contacto con el resumen ya escrito (cambio pequeño en el plugin para leerlo) o lo envía por WhatsApp; no guarda nada hasta que el cliente envía. Además: las decisiones explicadas, tabla de bases, «De la muestra a la producción» (brief, propuesta, muestras, estabilidad, escalado), errores comunes, preguntas frecuentes con datos estructurados y cierre con asesoría; medición de inicios, finales y envíos del asistente. Preguntas para Frave: muestras (si se envían, costo, cantidad, plazo), cantidades mínimas y tiempos, documentos que entregan (ficha técnica, hoja de seguridad, IFRA), quién hace las pruebas de estabilidad.
- [ ] **Glosario de perfumería** (40–60 términos, con datos estructurados).
- [ ] **Notas e ingredientes**, con un apartado «Aromas del Perú».
- [ ] **Artículos de tendencias** en el blog (unos dos al mes).
- [ ] Rueda olfativa de Frave descargable.

#### Publicar la pirámide olfativa (tema 1.21.0)

Solo cambia el tema. Después de desplegarlo:

```bash
cd ~/domains/frave.pe/public_html && wp eval-file ~/repos/frave-theme/bin/crear-guia-fragancias.php && wp litespeed-purge all
```

#### Publicar la guía de familias olfativas (tema 1.20.0)

Solo cambia el tema. Después de desplegarlo, desde la carpeta del sitio:

```bash
wp eval-file ~/repos/frave-theme/bin/crear-guia-fragancias.php
wp litespeed-purge all
```

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
- [ ] Revisión legal de la política de privacidad, los términos y las preguntas frecuentes (publicadas el 12-10 con textos base); razón social, RUC y domicilio para la política y el Libro de Reclamaciones.
- [ ] Revisión de los cuatro artículos de inicio del blog (temas técnicos generales, sin datos de la empresa) y temas para los siguientes.
- [x] Direcciones de las redes sociales: Instagram y TikTok (13-10).
- [ ] Validación de los textos de la guía de familias olfativas; porcentajes de dosificación por aplicación; notas peruanas que trabajan de verdad (palo santo, muña, cacao…); condiciones para enviar muestras.
- [ ] Acceso a Google Search Console (o a la cuenta de Google con la que se verificará el dominio).

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
| 2026-10-12 | Fase 4 implementada (tema 1.5.0, plugin 1.2.0): páginas de Sabores, Fragancias y Envases con los textos de la gerencia, árbol de subcategorías acordado (`bin/crear-lineas.php`), portafolio, banda de aplicaciones, cotización con la línea escrita e imágenes v1; la portada pasa a «Nuestras líneas» con imágenes y el texto de posicionamiento. Evidencia: PHPCS del tema y del plugin, ESLint y build; Playwright en local con el modo catálogo, incluidas las 7 pruebas nuevas de líneas (la del menú, con el menú automático como en producción); capturas a 1280, 1440 y 1920px y a 390px sin desbordamiento. Se corrigieron la legibilidad del hero sobre la imagen de Sabores y la posición de la imagen en pantallas anchas. |
| 2026-10-12 | Fase 5a implementada (tema 1.6.0): Nosotros (hero con la gota, quiénes somos, esencia y cuatro creencias) y Experticia (nube de palabras del Perú generada como texto real por `bin/nube-peru.py`, asesoría con destacados, cómo trabajamos en pestañas o acordeón con direcciones `#paso-…` e industrias por línea), páginas creadas por `bin/crear-paginas-empresa.php`, razones de la portada con los atributos de la gerencia y bloques compartidos en `system/blocks.css`. Evidencia: PHPCS, ESLint y build; Playwright en local con el menú automático (40 correctas, 8 omitidas: las de compra y una de tienda), incluidas 5 pruebas nuevas de empresa y el acordeón en móvil; capturas a 1440px y 390px sin desbordamiento y menú completo a 1024px. |
| 2026-10-12 | Fase 5b implementada (tema 1.7.0, plugin 1.3.0): Soluciones con página central y cinco industrias (`bin/crear-soluciones.php`) que reúnen las categorías de cada línea; formulario con empresa, ciudad, línea de interés y consentimiento de la Ley 29733 (columnas Empresa y Línea en Mensajes); chat de WhatsApp guiado sin guardar datos; menú automático con Soluciones (Inicio cede su lugar con siete entradas). Tarjeta de subcategoría y banda de aplicaciones pasan a componentes. Evidencia: PHPCS del tema y del plugin, ESLint y build; Playwright en local con el menú automático (45 correctas, 8 omitidas), incluidas las pruebas nuevas de soluciones, formulario, chat y chat en móvil; capturas a 1440px y 390px y menú a 1024px. Se corrigió que las páginas hijas (industrias) no aparecían en la consulta por plantilla. |
| 2026-10-12 | Revisión general de frave.pe (público y administrador). Corregido en código (tema 1.8.0, plugin 1.4.0): textos por defecto visibles en gris en los campos ACF de línea y solución, contraste de los números de las tarjetas y del texto superior del hero de Envases, descripción y Open Graph por página con imágenes JPG para compartir, favicon por defecto, usuarios ocultos en la API, archivos de autor redirigidos, mapa del sitio sin usuarios ni páginas de tienda en modo catálogo. Pendiente en el administrador: ver la lista de acciones de la revisión (visibilidad «Próximamente» de WooCommerce, portada estática, nombre del sitio, zona horaria, país y moneda, datos de contacto, páginas legales, entrada de ejemplo, temas inactivos, usuario «admin», correo SMTP). |
| 2026-10-12 | Páginas legales publicadas en frave.pe: Política de privacidad (Ley 29733, asignada como página de privacidad del sitio y enlazada desde el formulario), Términos y condiciones y Preguntas frecuentes (con datos estructurados FAQPage); Libro de Reclamaciones creado en borrador hasta tener razón social, RUC y domicilio. Los textos describen el sitio real (formulario, chat de WhatsApp sin almacenamiento, solo cookies técnicas y de seguridad); para que eso sea cierto, el plugin 1.4.1 apaga la atribución de pedidos (cookies sbjs_*) en modo catálogo. Las preguntas frecuentes se enlazan desde Contacto y el pie (tema 1.8.1). |
| 2026-10-12 | Ajustes del administrador aplicados en frave.pe: nombre «Frave» y eslogan, zona horaria America/Lima, Perú (Lima) y soles en WooCommerce, página «Inicio» como portada estática (el grupo ACF «Inicio Frave» ya se edita ahí), «Hello world!» en la papelera, Hostinger Reach desactivado y caché vaciada. Comprobado en línea: títulos «… – Frave», mapa del sitio solo con páginas, sin cookies ni servicios de terceros. Siguen pendientes: datos de contacto, catálogo en «Próximamente» hasta cargar productos, Libro de Reclamaciones, temas inactivos, usuario «admin» y correo SMTP. |
| 2026-10-12 | Fase 6 implementada (tema 1.9.0): plantilla de importación y muestra generadas por `bin/plantilla-catalogo.py`, atributos globales (`bin/crear-atributos.php`) con orden de filtros y de medidas, ficha de producto rediseñada, subcategorías como accesos rápidos y guía del catálogo reescrita. Evidencia: importación real con el importador de WooCommerce en local (todas las columnas asignadas solas; 10 productos y 3 variaciones con categorías, atributos e imágenes); PHPCS, ESLint y build; Playwright con el modo catálogo y con la tienda activa, incluidas 4 pruebas nuevas de ficha y categorías. Se corrigieron dos estilos de WooCommerce sin capa que anulaban la rejilla y la foto fija de la ficha. |
| 2026-10-13 | Fase 7 implementada en `main` (tema 1.10.0, plugin 1.5.0): blog con migas de pan, compartir, temas y relacionados (`bin/crear-blog.php` con cuatro artículos de inicio en borrador); datos estructurados, canonical de listados, `noindex` de búsqueda y redes sociales en el Personalizador; logos WebP, portadas más livianas, vídeo del hero diferido, contacto en caché y sin scripts de compra en modo catálogo; correcciones de accesibilidad. Evidencia: medición de Core Web Vitals en frave.pe con móvil simulado (LCP 1,8–2,7 s, CLS 0); axe WCAG 2.2 AA en 18 plantillas, teclado, encabezados y reajuste a 320 px; PHPCS, ESLint; Playwright 56 pruebas con el modo catálogo y 26 con la tienda activa, incluidas 5 nuevas (blog y enlace de salto). La API de PageSpeed no tiene cuota sin clave: la medición se hizo con Chrome y limitación de CPU y red. |
| 2026-10-13 | Fase 7 publicada en frave.pe y revisada: tema 1.10.0 y plugin 1.5.0 activos, `crear-blog.php` ejecutado y los cuatro artículos publicados a pedido (fechas del 10 al 13-10), caché de LiteSpeed vaciada; «Blog» aparece en el menú de todas las páginas. Recorrido de 38 páginas sin errores de PHP, consola ni enlaces rotos, un `h1` por página y sin desbordes; axe estricto solo marca la banda decorativa de palabras (oculta a lectores). Datos estructurados y Open Graph correctos en los artículos. Medición móvil simulada después de la publicación: LCP portada 1,6 s (antes 2,1), Fragancias 1,8 s (antes 2,7), Contacto 1,4 s (antes 4,2; TTFB de 2,9 a 0,3 s por la caché), blog 2,5 s, artículo 1,6 s; CLS 0; JavaScript de 52 a 5 KB por página. La página del catálogo sigue siendo el aviso «Próximamente» de WooCommerce hasta que haya productos. |
| 2026-10-13 | Redes de Frave (Instagram y TikTok) en la cabecera, el menú móvil y el pie como logos con los colores de cada red al pasar el cursor (tema 1.11.0). Corregido de la fase 7: entre 1024 y 1180 px las flechas de «Líneas» y «Contacto» se aplastaban hasta desaparecer; la cabecera se compacta en ese tramo y una prueba lo vigila. Fase 8 (puesta en marcha y entrega) anotada con sus pendientes. Datos recibidos de Frave: Instagram @fragancias_frave, TikTok @fragancias_frave, WhatsApp +51 914 591 932 y correo frave.cp@gmail.com. |
| 2026-10-13 | Página de contacto rediseñada como entrada principal (tema 1.12.0, plugin 1.6.0): cabecera con ventajas, formulario en tarjeta elevada con bloques numerados, tema y línea como píldoras accesibles, contador del mensaje, estado «Enviando…» y confirmación con WhatsApp y catálogo; canales con WhatsApp destacado, pasos de atención y enlaces a preguntas frecuentes y reclamos. El botón flotante de WhatsApp (activo desde que se cargó el número) pasa a ser una región propia. Evidencia: axe sin avisos en contacto, enviado y Libro de Reclamaciones; Playwright con modo catálogo y con tienda, incluidas 2 pruebas nuevas del formulario. |
| 2026-10-13 | Contacto publicado y revisado en frave.pe (tema 1.12.0, plugin 1.6.0): axe sin avisos en Contacto y Libro de Reclamaciones, sin desbordes ni errores. Prueba real como visitante: mensaje «PRUEBA TÉCNICA – web Frave» guardado en Mensajes con el aviso por correo «Enviado» (aceptado por el servidor) a gerenciafrave@gmail.com; pendiente confirmar la llegada. Tema 1.12.1: las líneas del formulario siguen el orden del sitio (Sabores, Fragancias, Envases). |
| 2026-10-15 | Imágenes generadas incorporadas (tema 1.13.0): las 39 imágenes de `imagenes-generadas` revisadas una a una (sin textos, logotipos ni defectos visibles; WebP de 30–113 KB) y copiadas al tema por slug en `assets/media/categorias/` (1200 px, 600 px y miniatura de 120 px), `assets/media/sabores/`, `empresa/reunion` y `fondo-beige`. `frave_term_image()` usa la imagen de la categoría del administrador o, si no hay, la del tema. Evidencia: capturas en escritorio y móvil de Fragancias, Sabores, Envases, categoría del catálogo, portada y Nosotros; PHPCS y ESLint limpios; Playwright 31 pruebas de las páginas afectadas, incluidas 4 nuevas (imágenes del portafolio y miniaturas, galería de Sabores, cabecera de categoría, foto de Nosotros y fondo de la portada). |
| 2026-10-15 | CI de `main` en rojo desde el 13-10, sin efecto en frave.pe: el paso «Playwright (tienda)» filtraba por `home`, que también coincide con la ruta del servidor (`/home/runner/…`), y ejecutaba todas las pruebas con la tienda activa; ahí la cabecera de 8 entradas más carrito y cuenta desborda a 1024 px. Filtro corregido para que solo corran los 7 archivos previstos; la cabecera del modo catálogo gana margen a 1024 px (de 4 a 20 px: logotipo y separación algo menores entre 1024 y 1280 px) y la prueba informa de sus medidas al fallar. El desborde con la tienda activa queda anotado para su reactivación. |
| 2026-10-15 | Primera tanda de correcciones de Frave (tema 1.14.0): LinkedIn en las redes de la cabecera, el menú móvil, el pie y contacto (el tema ya lo admitía: falta la dirección en producción); logotipo de la cabecera un 18 % más estrecho (máx. 184 px), que además deja sitio al tercer icono de 1024 a 1600 px; nube del Perú de 56 a 23 términos, más grandes (de 8–16,6 a 10,5–19,8) y separados; «Experticia» pasa a «Servicios» en página, menú, enlaces, plantilla y ACF, con redirección permanente desde `/experticia/`. Evidencia: medición de la cabecera de frave.pe con tres iconos y el logo nuevo (sin desborde a 390, 1024, 1280, 1440 y 1600 px); capturas de la nube en escritorio y móvil; PHPCS, ESLint, build; Playwright 35 pruebas de empresa, navegación, móvil, blog, portada y líneas, incluida una nueva para la redirección. |
| 2026-10-15 | Sin negro, a pedido de Frave (tema 1.15.0, opción C elegida: arena + azul marino): banda de las páginas interiores, «Tu proyecto, paso a paso», panel del catálogo, aplicaciones de las líneas, esencia de Nosotros y «Cómo trabajamos» pasan a arena (#f1e6d8, el beige de las fotos) con tinta y acentos en naranja oscuro; pie, botones oscuros, controles seleccionados y velo del menú en azul marino (#1d2b4f), con textos de acento en naranja claro. El logotipo sigue naranja: el azul es solo de apoyo. Evidencia: capturas de las siete zonas; axe sin avisos de contraste nuevos en 10 páginas a 1440 y 390 px (el enlace naranja del pie se corrigió a 6:1; los números decorativos de Nosotros, ocultos a lectores, ya existían); Playwright 64 pruebas, PHPCS, ESLint y build. |
| 2026-10-15 | Libro de Reclamaciones publicado en frave.pe con `bin/configurar-libro.php` del plugin (nuevo): FRAVE-FRAGANCIAS Y ENVASES E.I.R.L., RUC 20613844091 (dígito verificador correcto), Av. Guillermo de la Fuente 321, Urb. Santa Luzmila, Comas, Lima; zona horaria America/Lima; avisos a servicioalcliente@frave.pe; enlace en el pie de todas las páginas. Evidencia: página con los datos del proveedor y la fecha del día, 24 campos (15 obligatorios), sin caché (`no-store`), axe WCAG 2 AA sin avisos a 1440 y 390 px. Pendiente: confirmar el envío por SMTP con WP Mail SMTP (sin hacer una hoja de prueba: la numeración es correlativa y las hojas no se borran) y la revisión legal del texto. |
| 2026-10-15 | Líneas y Soluciones unidas (tema 1.16.0): la página de cada categoría del catálogo es su página de detalle, con su imagen, descripción, «Se usa en» (las soluciones que la usan, también por sus categorías superiores), «Solicitar cotización», sus subcategorías en tarjetas y, sin productos, una invitación a cotizar por formulario o WhatsApp en lugar de la lista vacía y los filtros. En líneas, soluciones y categorías, el nombre de cada tarjeta, todas las etiquetas de tipos y «Ver detalle» llevan a esa página, haya o no productos. Descripciones redactadas (propuesta para revisión de Frave) para cabello, piel, mascotas, textil, superficies, cocina y lavavajillas, difusores de varitas, vehículos, aerosoles y marketing olfativo. Hallado en frave.pe: el catálogo y sus categorías mostraban el «Próximamente» genérico de WooCommerce a los visitantes. Evidencia: capturas en escritorio y móvil; axe WCAG 2 AA sin avisos en categoría, subcategoría vacía, línea y solución; PHPCS, ESLint, build; Playwright 64 pruebas más 2 nuevas (recorrido línea → detalle → tipo, y subcategoría sin productos). |
| 2026-10-15 | Líneas y Soluciones publicadas y revisadas en frave.pe (tema 1.16.0, «Próximamente» de WooCommerce desactivado): las 25 páginas de detalle enlazadas desde las tres líneas y las cinco soluciones responden con su imagen, descripción y «Se usa en», ninguna etiqueta queda sin enlace y axe no marca avisos. Tema 1.16.1: `/catalogo/` sin productos ya no muestra «No se han encontrado productos» con un filtro suelto, sino las tres líneas en tarjetas con sus tipos y la invitación a cotizar (comprobado en local con los productos de muestra en borrador). |
| 2026-10-17 | Tarjetas del portafolio más interactivas (tema 1.17.0), en líneas, soluciones y categorías: se elevan al pasar el cursor y su imagen lleva al detalle; los tipos dejan de ser etiquetas pequeñas y pasan a filas de acción bajo «Elige un tipo» (miniatura, nombre y flecha que se rellena de naranja); al señalar un tipo, la imagen de la tarjeta se funde con la suya y una píldora lo nombra, y al salir vuelve la principal. Con teclado, el foco hace lo mismo; con movimiento reducido no hay desplazamientos. Las imágenes de los tipos solo se descargan cuando el cursor o el foco llega a la tarjeta. Evidencia: capturas en reposo, al señalar «Perfumería árabe» y en móvil; axe WCAG 2 AA sin avisos en cuatro páginas a 1440 y 390 px; Playwright 41 pruebas de las páginas afectadas, incluida una nueva del cambio de imagen. |
| 2026-10-17 | Portada invertida a pedido de Frave (tema 1.18.0): video nuevo generado por el equipo con Google Flow (Veo), con las frutas cayendo en el tercio izquierdo, y el título y los botones a la derecha sobre el beige limpio. Revisado: 1920×1080, 8 s, sin textos ni marcas, último fotograma apto como imagen fija; se quitó la pista de audio. Optimizado a 1280×720: MP4 662 KB (antes 841) y WebM 410 KB (antes 724), imagen fija de 50 KB; nombres nuevos (`hero-frutas-v2.*`) para que ninguna caché sirva el video anterior. Entre 1024 y 1280 px el video se desplaza a la izquierda y el texto se estrecha para que ninguna fruta quede detrás del texto. Evidencia: capturas a 390, 1024, 1152, 1280, 1366, 1440, 1536 y 1920 px, con la imagen final y a mitad de la animación; Playwright 19 pruebas de portada, móvil y SEO. |
| 2026-10-17 | Fragancias, línea principal de Frave, va siempre primero (tema 1.18.1): orden Fragancias, Sabores, Envases en el menú «Líneas», «Nuestras líneas» de la portada, el panel del catálogo, las tarjetas y el filtro de `/catalogo/`, «Otras líneas» y el formulario de contacto. `bin/crear-lineas.php` fija además ese orden en las categorías de WooCommerce en cada ejecución. En producción: desplegar el tema y ejecutar `wp eval-file ~/repos/frave-theme/bin/crear-lineas.php`. |
| 2026-10-17 | Imágenes propias en las cabeceras de Soluciones (tema 1.19.0), en lugar de las recicladas: página central, Perfumería, Cosmética y cuidado personal, Cuidado del hogar, Ambientación y marketing olfativo, y Alimentos y bebidas (`recursos-graficos/soluciones`, generadas con la serie beige como referencia). Revisadas al detalle: sin textos ni logotipos, envases genéricos, recorte en arco sin cortes. En `assets/media/soluciones/` a 1080×1350 (52–88 KB) y 640×800 (20–32 KB) con `srcset`; imágenes para compartir de 1200×630 (`og/solucion-*.jpg`, 52–72 KB) con todo el grupo de productos. Se retiró la imagen fija del video anterior, que ya no se usaba. Evidencia: capturas de las seis cabeceras y en móvil, `og:image` de cada página comprobado; Playwright de soluciones, SEO y móvil. |
| 2026-10-18 | El personal no podía entrar al correo: usaban `frave.pe/webmail` y enlaces `frave.pe:2096/cpsess…`, que desde el traslado llevan a la web en Hostinger. Acceso vigente comprobado: `https://webmail.frave.pe` (y `https://fragantik.com:2096`) responde con certificado válido. Direcciones de webmail, cPanel y servidores IMAP/SMTP anotadas en `CORREO_FRAVE.md`, fuera del repositorio. Queda pendiente redirigir `frave.pe/webmail` (fase 8). |
| 2026-10-19 | Guía de familias olfativas (tema 1.20.0), primer contenido de la línea Fragancias tras estudiar la sección «El Perfume» de la Academia del Perfume: página /fragancias/familias-olfativas/ con las nueve familias del atributo del catálogo (Cítrica, Floral, Frutal, Amaderada, Oriental, Aromática, Gourmand, Fresca, Especiada), cada una con sus notas, lo que transmite, los productos en que funciona, «Ver fragancias …» hacia el catálogo filtrado (solo cuando hay fragancias de esa familia en Fragancias) y cotización; índice de familias, «¿Qué familia elegir para tu producto?» con nueve aplicaciones y cierre con asesoría. Textos propios para fabricantes, sin marcas comerciales. La página de Fragancias suma una banda con las familias. Hasta tener las imágenes, cada familia muestra su color. `frave_page_with_template()` ahora encuentra también páginas hijas. Evidencia: capturas a 1440 y 390 px sin desbordes; axe WCAG 2.2 AA sin avisos en la guía (escritorio y móvil) y en Fragancias; PHPCS, ESLint, build; Playwright 69 correctas (3 nuevas de la guía). |
| 2026-10-19 | «Gira la rueda de los aromas» en Soluciones → Perfumería (tema 1.20.0): sección sobre azul marino con un anillo de las nueve familias en sus colores; al elegir una (clic, toque o flechas del teclado) el anillo gira para ponerla frente a su ficha —a la derecha en escritorio, abajo en móvil—, el centro muestra su nombre y la ficha cuenta su papel en la perfumería, notas, lo que transmite y enlaces a la guía y al catálogo. Lista de pestañas accesible, sin giro con movimiento reducido y con todas las fichas visibles sin JavaScript. Evidencia: capturas a 1440 y 390 px antes y después de girar, sin desbordes ni errores de consola; axe WCAG 2.2 AA sin avisos en Perfumería (escritorio y móvil); PHPCS, ESLint; Playwright con 2 pruebas nuevas de la rueda. |
| 2026-10-19 | Tema 1.20.0 desplegado en Hostinger. Imágenes de la guía de familias olfativas integradas (tema 1.20.1): portada y nueve familias generadas con `recursos-graficos/familias-olfativas/PROMPTS.md` (mismo frasco genérico, luz y ángulo; fondo del color de cada familia), revisadas sin textos ni logotipos y con el grupo en la parte baja para el recorte en arco. En `assets/media/familias/` a 1080 × 1350 (59–97 KB) y 640 × 800 (25–40 KB); imagen para compartir `og/familias-olfativas.jpg` (1200 × 630, 121 KB). Evidencia: capturas a 1440 y 390 px sin desbordes ni errores. frave.pe muestra a los visitantes «Frave is under maintenance», así que la revisión en producción queda pendiente del enlace privado. La guía aún no se creó en producción: `wp eval-file` debe ejecutarse desde `~/domains/frave.pe/public_html`. |
| 2026-10-19 | Guía creada en producción y modo mantenimiento desactivado: revisadas en frave.pe /fragancias/familias-olfativas/ (nueve familias, «Cotizar fragancias …» en cada una; sin «Ver fragancias» porque el catálogo aún no tiene productos), la banda de familias en /fragancias/ y la rueda en /soluciones/perfumeria/ (gira hacia la familia elegida en escritorio y móvil). Sin errores de consola ni desbordes a 1440 y 390 px. CI del tema 1.20.1 (imágenes) en verde; pendiente desplegarlo. |
| 2026-10-19 | Tema 1.20.1 desplegado y revisado en frave.pe: la guía muestra la portada y las nueve familias con sus imágenes (las diez cargan, sin imágenes rotas; el navegador elige la versión de 640 px en pantallas normales), comparte con `og/familias-olfativas.jpg`, sin errores de consola ni desbordes a 1440 y 390 px. |
| 2026-10-19 | Pirámide olfativa aplicada (tema 1.21.0): página /fragancias/piramide-olfativa/ con introducción («una fragancia no huele igual al minuto que a las cuatro horas»), pirámide interactiva —tres niveles recortados con `clip-path` que son pestañas: cuándo se percibe, su papel, notas típicas, familias que lo aportan con enlace a la guía y qué significa para el producto—, línea de tiempo de los tres niveles, «Qué significa para tu producto» (perfumes, body splash, cosméticos, detergentes, velas, difusores, cada uno con su clave), la prueba de la tira en cuatro momentos, banda de familias y cierre con asesoría. Enlaces desde la guía de familias y desde Fragancias. `bin/crear-familias-olfativas.php` pasa a ser `bin/crear-guia-fragancias.php` y crea las dos páginas. Módulo genérico de pestañas (`tabs.js`). Evidencia: capturas a 1440 y 390 px sin desbordes ni errores; axe WCAG 2.2 AA sin avisos en la pirámide (escritorio y móvil), la guía y Fragancias; PHPCS, ESLint, build; Playwright 72 correctas (2 nuevas). |
| 2026-10-19 | Imagen de la pirámide olfativa integrada (tema 1.21.1): generada con `recursos-graficos/piramide-olfativa/PROMPT.md`, de la misma serie que las familias (frasco, luz y ángulo), con los ingredientes apilados en pirámide —maderas, vainilla y ámbar en la base; rosa y jazmín al centro; limón y menta en la cima— y el tercio superior libre para el arco. En `assets/media/piramide/` a 1080 × 1350 (87 KB) y 640 × 800 (34 KB); para compartir, `og/piramide-olfativa.jpg` (1200 × 630) con los lados completados con el fondo desenfocado. Evidencia: capturas de la cabecera a 1440 y 390 px; `og:image` comprobado. |
| 2026-10-19 | Guía de fragancias más visible (tema 1.22.0): el menú «Catálogo» de la cabecera despliega Familias olfativas y Pirámide olfativa —«Líneas» queda solo con las tres líneas, como pidió Frave—; el pie de página suma la columna «Guía de fragancias» (cuatro columnas en escritorio, tres en tableta, una en móvil); la página de Fragancias suma la sección «Cómo evoluciona una fragancia» con la pirámide interactiva y «Ver la pirámide completa», y la banda de familias deja su enlace a la pirámide, que ya tiene sección propia. Producción usa el menú automático del tema (comprobado), así que el cambio aparece solo al desplegar; un menú asignado en *Apariencia → Menús* lo reemplazaría. Evidencia: capturas del submenú, la sección y el pie a 1440, 900 y 390 px; axe WCAG 2.2 AA sin avisos en Fragancias (escritorio y móvil) y la pirámide a 900 px; prueba del encaje de la cabecera a 1024 px con el submenú nuevo; PHPCS, ESLint, build; Playwright 73 correctas (1 nueva). |
| 2026-10-19 | Introducción de entrada y logotipo de letras (tema 1.23.0), a pedido de Frave: la cabecera muestra solo «FRAVE / Fragancias y envases» (sin el frasco), recortado del logotipo transparente en alta resolución (720 × 240, 20 KB) y algo más angosto, lo que deja aire al menú. La primera página de una visita —de nuevo pasadas 12 horas— abre con el naranja de marca en degradado radial (el recurso gráfico recibido, hecho con CSS) y las letras en blanco con una línea de carga; al terminar de cargar se levanta como un telón: mínimo 1,4 s para que se lea, máximo 3,5 s, y un clic la adelanta. Estilo y script en línea, sin depender de la hoja de estilos; reutiliza el archivo del logotipo de la cabecera (precargado), así que no suma descargas; sin cookies (`localStorage`); oculta a lectores de pantalla; con movimiento reducido solo se desvanece; los navegadores automatizados la omiten. Evidencia: capturas de la introducción y la cabecera a 1440 y 390 px; axe WCAG 2.2 AA sin avisos en la portada; encaje de la cabecera a 1024 px; PHPCS, ESLint, build; Playwright 76 correctas (3 nuevas). |
| 2026-10-20 | «Cómo elegir la fragancia para tu producto» detallada (asistente de cinco preguntas que termina en una solicitud de asesoría con la ficha escrita) y dejada en espera: se consultará con Frave antes de construirla. Detalle y preguntas en la fase de contenidos de Fragancias. |
| 2026-10-20 | Correo de la web por SMTP: WP Mail SMTP en frave.pe con «Otro SMTP» (`mail.frave.pe`, SSL 465, buzón servicioalcliente@frave.pe, remitente forzado «Frave <servicioalcliente@frave.pe>»); conexión desde Hostinger comprobada por SSH; DNS ya en regla (SPF con `+mx`, DKIM `default`, DMARC `p=none`; certificado de `mail.frave.pe` válido hasta el 02-12-2026). Pruebas: el correo de prueba de WP Mail SMTP llegó a la bandeja de entrada de Gmail (no a spam) y un mensaje real del formulario quedó en «Mensajes» y llegó a servicioalcliente@frave.pe. El Libro de Reclamaciones no se prueba en producción (numeración correlativa): usa el mismo envío. Pendiente: cambiar el correo visible de contacto (hoy frave.cp@gmail.com) por servicioalcliente@frave.pe y decidir el acuse de recibo al cliente. |
| 2026-10-20 | Acuse de recibo del formulario de contacto (plugin 1.7.0): quien escribe recibe «Recibimos tu consulta — Frave», de texto fijo, con el plazo «24 horas hábiles» (editable en *Mensajes → Ajustes*, donde también se puede desactivar), el tema y la línea elegidos entre las opciones y el enlace a WhatsApp; saluda por el nombre solo si es una palabra de letras, así que no sirve para enviar texto ajeno a terceros; responder llega a la dirección de avisos y la ficha del mensaje indica si se envió. Evidencia en local con Mailpit: dos envíos (uno con un «nombre» que traía un enlace, que no se repitió), aviso a Frave con respuesta al cliente y acuse con respuesta a Frave; ajuste de activar y desactivar comprobado; PHPCS; Playwright de formularios (5 correctas). Plazo pendiente de confirmar con Frave. |
| 2026-10-22 | Portada más viva, propuesta B elegida entre tres maquetas (tema 1.24.0): título en tres renglones «Fragancias y sabores / que *conectan con* / tu marca», cuyas palabras destacadas se alternan («conectan con», «distinguen a», «elevan», «dan vida a») en naranja y negrita sin mover el resto del texto; ondas de aroma que salen de la gota de marca detrás del título; entrada más corta y fila de datos de confianza (+500 referencias · Asesoría técnica · Fabricantes internacionales, editable en ACF). Palabras y ondas dan dos vueltas (unos 20 s) y se aquietan en «conectan con»; el botón del vídeo pausa, reanuda o repite todo el movimiento (WCAG 2.2.2); con movimiento reducido nada se mueve; los lectores de pantalla leen una sola frase. Sintaxis nueva del título en ACF: `*a|b|c*`. Evidencia: capturas a 1440, 1100 y 390 px en dos momentos y en el estado final; pausa, final y movimiento reducido comprobados; axe WCAG 2.2 AA sin avisos en la portada (tres anchos); PHPCS, ESLint, build; Playwright 77 correctas (1 nueva y 2 ampliadas). En producción hay que actualizar el título y la entrada guardados en ACF. |
| 2026-10-22 | Tema 1.24.0 desplegado y revisado en frave.pe (palabras que se alternan, ondas, entrada y datos de confianza; textos de ACF actualizados por WP-CLI). Cabeceras unificadas con la portada (tema 1.25.0): la palabra destacada pasa de píldora a naranja en negrita en líneas, Nosotros, Servicios, soluciones, guía de fragancias y banda de cabecera (contacto, catálogo, blog), lo que además corrige la píldora de «diferenciarse», que tapaba las colas de «que buscan»; las órbitas y el frasco de marca de agua se reemplazan por las ondas de aroma, quietas: alrededor de la imagen (Nosotros, soluciones, guía, categorías del catálogo), a la derecha con la gota (bandas sin imagen) o detrás del texto (líneas, Servicios). Solo la portada se mueve. Además, los números decorativos de «Nuestras creencias» se dibujan con CSS (axe los marcaba por su tono claro). Evidencia: capturas de nueve cabeceras a 1440 px y cinco a 390 px; axe WCAG 2.2 AA sin avisos en Nosotros, Servicios, Perfumería, Fragancias, Contacto, catálogo y portada; PHPCS, ESLint, build; Playwright 81 correctas (4 nuevas). |
| 2026-10-22 | Tema 1.25.0 desplegado. Vídeo de la portada en móvil como fondo (tema 1.25.1, solución provisional): deja de ser una tarjeta con esquinas; ocupa todo el ancho bajo el texto, recortado a las frutas (cuadrado en móvil, 16:10 en tableta) y fundido con el beige por arriba; el botón de pausa baja a la parte visible. Pendiente la solución final: vídeo vertical 9:16 solo para celulares, con la caída en la mitad inferior y el texto encima (prompt y fotograma final de referencia en `recursos-graficos/video-portada-movil/`). Evidencia: capturas a 390, 768 y 1440 px tras la animación; axe sin avisos en la portada a 390 y 768 px; Playwright de portada y móvil (16 correctas). |
