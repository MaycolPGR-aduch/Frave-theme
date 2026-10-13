# Frave WordPress Theme

Tema clásico personalizado para Frave (sabores, fragancias y envases). Desde octubre de 2026 la web es un **sitio corporativo de captación con catálogo**: presenta la empresa, muestra los productos sin precios y lleva al cliente a contactar. La venta en línea queda en pausa detrás del **modo catálogo** del plugin Frave Perú y se reactivará más adelante. WordPress administra páginas y medios; WooCommerce conserva el catálogo y, cuando se active la tienda, inventario, precios, carrito, checkout, pagos, pedidos y cuentas. La apariencia se implementa en este tema.

## Documentación del proyecto

- [PLAN_WEB_CORPORATIVA.md](PLAN_WEB_CORPORATIVA.md): **plan vigente**, fases de la web corporativa y registro de avances.
- [ROADMAP.md](ROADMAP.md): base construida y plan de la futura tienda en línea.
- [DESIGN.MD](DESIGN.MD): sistema visual (colores, tipografía, componentes, formas y movimiento). Para verlo en vivo, abre `/?sistema-visual` con sesión de administrador.
- [ARQUITECTURA.MD](ARQUITECTURA.MD): límites del sistema, módulos e integraciones.
- [GUIA_COMPILACION.MD](GUIA_COMPILACION.MD): desarrollo local, build y validación de assets.
- [GUIA_DESPLIEGUE_LOCAL.MD](GUIA_DESPLIEGUE_LOCAL.MD): instalación local de WordPress y Frave desde cero en Windows.
- [GUIA_DESPLIEGUE.MD](GUIA_DESPLIEGUE.MD): despliegue en Hostinger y flujo de trabajo entre local y la web real.
- [GUIA_CATALOGO.md](GUIA_CATALOGO.md): propuesta de categorías y atributos, y preparación del catálogo.
- [PLAN_CONTENIDO_INSTITUCIONAL.md](PLAN_CONTENIDO_INSTITUCIONAL.md): estructura propuesta para las páginas institucionales.
- [PLANTILLA_IMPORTACION_WOOCOMMERCE.xlsx](PLANTILLA_IMPORTACION_WOOCOMMERCE.xlsx): columnas del importador nativo de WooCommerce para preparar productos.

## Requisitos

- WordPress 6.9 o superior (validado con 7.1.2).
- PHP 8.1 o superior (validado con 8.3.30; el código no usa sintaxis posterior a 8.1).
- WooCommerce 10.8 o superior (flujo validado con 11.1.2).
- Node.js 22.12 o superior y npm.
- MySQL 8.0+ o MariaDB 10.6+.
- Advanced Custom Fields gratuito es opcional (validado con 6.8.10); sin ACF, la portada utiliza contenido de respaldo.
- Plugin **Frave Perú** (`D:/Frave-web/frave-peru/`, fuera de este repositorio): modo catálogo, formulario de contacto, Libro de Reclamaciones y, con la tienda activa, boleta o factura con DNI, CE, pasaporte o RUC en el checkout. Consulta su `README.md`. Sin el plugin activo el tema se comporta como tienda.

La recomendación sigue las versiones actuales de WooCommerce y Vite. Comprueba los requisitos del hosting antes de desplegar.

## Instalación del tema

1. Despliega el tema en `wp-content/themes/frave` con Git o con el paquete `frave.zip` (`npm run package`), como explica [GUIA_DESPLIEGUE.MD](GUIA_DESPLIEGUE.MD). No copies la carpeta del repositorio tal cual: incluye herramientas y documentación interna.
2. En WordPress, activa **Frave** en **Apariencia → Temas**.
3. Instala y activa WooCommerce. ACF gratuito es opcional.
4. Asigna una página estática como portada. Si deseas el enlace institucional de la portada, crea una página publicada con slug `nosotros`; su contenido se edita con el editor de WordPress. Los menús se explican en [Navegación](#navegación).
5. Completa los datos de tienda, moneda, envíos, impuestos y pagos en los ajustes de WooCommerce.
6. En las páginas de carrito y checkout, selecciona las versiones clásicas de WooCommerce con `[woocommerce_cart]` y `[woocommerce_checkout]` si el asistente creó bloques. La cuenta usa `[woocommerce_my_account]`.
7. Para las páginas de contacto, preguntas frecuentes y políticas, sigue [PLAN_CONTENIDO_INSTITUCIONAL.md](PLAN_CONTENIDO_INSTITUCIONAL.md): cada una usa una plantilla del tema y parte de un patrón de «Frave: páginas institucionales».
8. Para los canales de contacto y WhatsApp, abre **Apariencia → Personalizar → Contacto** e introduce el número de WhatsApp Business de Frave (para un celular de Perú bastan los 9 dígitos; se añade el 51). Sin número no se muestra ningún botón.

### Modo catálogo

Con el modo catálogo del plugin Frave Perú activo (*WooCommerce → Ajustes → Productos*, activado por defecto), el plugin quita precios y compras, y el tema:

- quita de la cabecera la cuenta y el carrito, y no carga el mini-carrito ni `wc-cart-fragments`;
- muestra la barra de búsqueda de productos solo en el catálogo, las fichas y los resultados;
- cambia «Añadir al carrito» de las tarjetas por **«Ver detalles»** y pone en la ficha el bloque **«¿Te interesa este producto?»** con «Solicitar cotización» (abre el formulario de contacto con el producto ya escrito, `?producto=ID`) y «Consultar por WhatsApp»;
- oculta en los filtros el precio y «En oferta»;
- añade al `body` la clase `frave-catalog-mode`.

«Solicitar cotización» y el botón «Solicitar asesoría» de la cabecera aparecen cuando hay una página publicada con la plantilla **Contacto**; sin ella, la cabecera ofrece WhatsApp. Al desactivar el modo vuelve la tienda tal como estaba.

### Navegación

El menú asignado a **Navegación principal** admite un nivel de submenú: en escritorio se abre como panel (al pasar el puntero o con su botón) y en el menú móvil como acordeón. Para un grupo sin página propia, como «Soluciones», usa un enlace personalizado con URL `#`: el tema lo convierte en un botón. La **descripción** de cada elemento del submenú (actívala en *Opciones de pantalla* de Apariencia → Menús) aparece bajo su nombre.

Mientras no haya menús asignados, el tema muestra uno de respaldo con la estructura corporativa y solo enlaces que existen: Inicio, Nosotros (página `nosotros`), Soluciones (categorías de producto `sabores`, `fragancias` y `envases` que ya tengan productos), Catálogo y Contacto (con WhatsApp y Libro de Reclamaciones). El pie usa los mismos enlaces sin submenús.

### Contacto por WhatsApp

Con un número configurado, el tema muestra:

- un **botón flotante** en todas las páginas excepto el checkout, donde taparía «Realizar pedido» en móvil. En móvil solo muestra el icono;
- **«Consultar por WhatsApp»** en cada ficha de producto (en el bloque de cotización del modo catálogo, o bajo el botón de compra con la tienda activa), con un mensaje que incluye el nombre y el enlace del producto;
- un enlace **«Escríbenos por WhatsApp»** en el pie.

El mensaje inicial general, el botón flotante y el de las fichas se ajustan en la misma sección del Personalizador. Los enlaces llevan `data-frave-whatsapp="floating|product|footer"` para medir clics cuando se configure analítica. El filtro `frave_show_whatsapp_floating` permite ocultar el botón flotante en otras páginas.

### Portada

La portada (`front-page.php`, `template-parts/home/`) tiene estas secciones:

1. **Hero** con vídeo a pantalla completa. Se reproduce una vez y se queda en el último fotograma; tiene botón de pausa. Con movimiento reducido o ahorro de datos se muestra la imagen fija. En escritorio queda fijo mientras sube la sección siguiente.
2. **Nuestras líneas:** Sabores, Fragancias y Envases en tarjetas con forma de frasco que llevan a su página de línea. Usa la imagen de cada categoría de producto o, sin ella, la del tema.
3. **Cómo trabajamos:** tres pasos que se apilan al hacer scroll.
4. **Productos destacados:** la estrella de WooCommerce.
5. **Razones para elegirnos:** cuatro tarjetas con icono.
6. **Catálogo:** buscador y accesos a cada línea.
7. **Tendencias:** solo con entradas publicadas fuera de la categoría por defecto.
8. **Dónde estamos:** solo con dirección en el Personalizador; el mapa de Google se carga al pedirlo.

Todos los textos, el vídeo y su imagen fija se editan en el grupo ACF **Inicio Frave** de la página de portada. Una palabra entre `*asteriscos*` se resalta: en el hero sobre fondo naranja, en las demás secciones en negrita. Los campos vacíos muestran los textos por defecto de `inc/home.php`; sin ACF, también. El vídeo por defecto está en `assets/media/` (MP4 y WebM sin sonido, más el último fotograma en JPG). Si el administrador de ACF ofrece **Sincronización disponible** para «Inicio Frave», sincronízalo para editar los campos nuevos.

### Páginas de línea

Sabores, Fragancias y Envases tienen su página con la plantilla **Línea** (`page-templates/linea.php`): hero con la imagen de la línea, presentación con tres destacados, portafolio, aplicaciones en una banda de palabras, productos de la línea, llamada a la acción y las otras líneas.

- **Categoría:** la elegida en el grupo ACF **Línea Frave** o, si no, la categoría con el mismo slug que la página.
- **Portafolio:** una tarjeta por subcategoría de WooCommerce, con su nombre, descripción e imagen; sus propias subcategorías aparecen como etiquetas. Cada tarjeta ofrece «Cotizar» (`?linea=slug` en la página de contacto, que el plugin convierte en el mensaje) y «Ver productos» cuando los hay.
- **Textos:** los acordados con la gerencia están en `inc/lines.php`; cada uno se reemplaza en ACF y el texto por defecto se ve en gris dentro del campo. Las imágenes por defecto están en `assets/media/lineas/`.
- **Crear el árbol y las páginas:** `wp eval-file RUTA/bin/crear-lineas.php` crea las subcategorías acordadas con sus descripciones y las páginas /sabores/, /fragancias/ y /envases/. Se puede repetir: no cambia lo que ya existe.
- El pie no muestra su invitación a contactar en estas páginas, que cierran con la suya (filtro `frave_show_footer_cta`).

### Soluciones por industria

La página **Soluciones** (`page-templates/soluciones.php`, /soluciones/) presenta las industrias, y cada industria tiene su página con la plantilla **Solución** (`page-templates/solucion.php`, /soluciones/perfumeria/…): hero, presentación, **lo que le ofrece cada línea** (tarjetas de las categorías de producto, con la línea a la que pertenecen, «Cotizar» y «Ver productos»), aplicaciones, cierre y las otras industrias.

- **Industrias y categorías:** `inc/solutions.php` (`frave_solutions()`): Perfumería, Cosmética y cuidado personal, Cuidado del hogar, Ambientación y marketing olfativo, Alimentos y bebidas. La industria es el slug de la página.
- **Textos:** editables en el grupo ACF **Solución Frave**, con el texto por defecto en gris dentro de cada campo.
- **Crear las páginas:** `wp eval-file RUTA/bin/crear-soluciones.php`.
- **Menú automático:** «Soluciones» con la página central y un acceso por industria. Con siete entradas, «Inicio» deja su lugar (el logotipo ya lleva a la portada).

### Blog

La página de entradas (Ajustes → Lectura) muestra los temas (categorías con artículos), el último artículo destacado y los demás en tarjetas. Cada artículo tiene migas de pan, categoría, fecha y tiempo de lectura, la imagen destacada, el texto con los enlaces para compartir al lado (debajo en móvil) y «Sigue leyendo» con artículos del mismo tema.

- **Crear el blog:** `wp eval-file RUTA/bin/crear-blog.php` crea la página /blog/, la asigna como página de entradas, renombra «Sin categoría» como «Novedades», crea «Guías técnicas» y «Tendencias», y cuatro artículos de inicio **en borrador** con imágenes de las líneas. Con `publicar` al final los publica (sitios de prueba).
- **Menú:** «Blog» aparece en el menú y en el pie cuando hay al menos un artículo publicado. La entrada de ejemplo de WordPress («Hello world!») no cuenta y no se muestra en ningún listado.
- **Texto de la cabecera:** el extracto de la página Blog (o un texto por defecto).
- **Compartir:** WhatsApp, LinkedIn, Facebook y correo; con JavaScript, también «Copiar enlace» y, en teléfonos, el menú de compartir del sistema.
- Las fechas se muestran como «13 de octubre de 2026», sea cual sea el formato de Ajustes → Generales.

### Metadatos para buscadores y redes

Mientras no haya un plugin SEO activo, `inc/seo.php` se encarga de:

- la descripción de cada página (el texto de su hero, o el **extracto** de la página si se completa en el editor) y las etiquetas Open Graph que usan WhatsApp, Facebook o LinkedIn para la vista previa, con imágenes JPG de `assets/media/og/`;
- los datos estructurados (JSON-LD): la organización con su logotipo, contacto y redes, el sitio, cada artículo del blog y sus migas de pan;
- la dirección canónica de los listados (blog, categorías y catálogo sin filtros) y `noindex` en los resultados de búsqueda (las vistas filtradas del catálogo ya lo tenían);
- el favicon con el isotipo, si no hay icono del sitio en el Personalizador.

Con Yoast, Rank Math, AIOSEO, SEOPress o The SEO Framework activos, el tema no imprime nada de esto.

**Redes sociales:** las direcciones de Facebook, Instagram, LinkedIn, TikTok y YouTube se completan en **Apariencia → Personalizar → Contacto**; las que tienen dirección aparecen en el pie y en los datos de la organización.

### Chat de WhatsApp guiado

Con un número configurado, el botón flotante abre un chat: el visitante elige el motivo (cotización, producto u otra consulta), completa unos datos opcionales y continúa en WhatsApp con el mensaje ya armado. Los reclamos llevan al Libro de Reclamaciones. La web no guarda nada; sin JavaScript, el botón es un enlace directo a WhatsApp.

### Nosotros y Experticia

Dos plantillas con los textos de la gerencia (`inc/company.php`), editables en los grupos ACF **Nosotros Frave** y **Experticia Frave**:

- **Nosotros** (`page-templates/nosotros.php`): hero con los ingredientes en forma de gota, quiénes somos, la esencia en una banda oscura y las cuatro creencias con su comportamiento.
- **Experticia** (`page-templates/experticia.php`): hero con la **nube de palabras con la silueta del Perú**, asesoría técnica con destacados, «Cómo trabajamos» (pestañas en escritorio, acordeón en móvil; cada paso tiene su dirección, como `#paso-escuchamos`) y las industrias que atiende cada línea.
- **La nube** es texto real generado por `python bin/nube-peru.py` en `assets/media/experticia/` (SVG y la lista para lectores de pantalla); para cambiar sus palabras, edita la lista del script y vuelve a ejecutarlo.
- **Crear las páginas:** `wp eval-file RUTA/bin/crear-paginas-empresa.php`. Si ya existe una página Nosotros con la plantilla por defecto, le asigna la nueva; lo escrito en el editor se muestra antes del cierre.
- El menú automático añade Nosotros y Experticia cuando están publicadas; la portada enlaza Experticia desde «Razones para elegirnos» y «Cómo trabajamos».

## Desarrollo de assets

Desde la carpeta del tema:

```bash
npm install
npm run dev
```

Para habilitar HMR en el `wp-config.php` local, antes de la línea que indica que se deje de editar:

```php
define( 'FRAVE_VITE_DEV', true );
define( 'FRAVE_VITE_SERVER', 'http://127.0.0.1:5173' );
```

Para compilar producción:

```bash
npm run build
```

El build genera CSS, módulos JavaScript, fuentes y `assets/dist/manifest.json`. Incluye `assets/dist/` en Git y despliega su contenido junto con el tema. El servidor de producción no necesita Node.js.

## Entorno local de Frave

El repositorio contiene solo el tema. La instalación local de WordPress está en `D:/Frave-web/.local-wordpress/wordpress/`, fuera del repositorio del tema. Está conectada a MySQL local por el puerto `3307`; el servidor PHP atiende `http://127.0.0.1:8080`. No subas `wp-config.php`, contraseñas ni datos de clientes.

El entorno de desarrollo usa PHP 8.3 de Laragon y tiene WooCommerce y ACF Free instalados. La cuenta local de WordPress es `fraveadmin`; la contraseña de desarrollo se guarda fuera del tema en `D:/Frave-web/.local-wordpress/local-admin-password.txt`. Para arrancar todo de una vez, abre `D:/Frave-web/Iniciar Frave.cmd`; para apagarlo, `Detener Frave.cmd` (ver [GUIA_DESPLIEGUE_LOCAL.MD](GUIA_DESPLIEGUE_LOCAL.MD#arranque-diario)). Para volver a iniciar solo el servidor PHP desde PowerShell:

```powershell
& 'C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe' -S 127.0.0.1:8080 -t 'D:\Frave-web\.local-wordpress\wordpress'
```

Desde `D:/Frave-web/frave/`, inicia Vite en otra terminal:

```powershell
npm.cmd run dev
```

El `wp-config.php` local activa `FRAVE_VITE_DEV` y apunta a `127.0.0.1:5173`. MySQL se ejecuta localmente en el puerto `3307`. La cuenta de administrador y sus credenciales de prueba son solo para este entorno; la contraseña se conserva en un archivo local fuera del tema.

La tienda local está configurada en Perú, con moneda PEN, envío plano de prueba y pago contra entrega de prueba. La opción de visibilidad `woocommerce_coming_soon` está en `no` solo en esta base local para poder revisar las fichas y el proceso de compra; cualquier hosting de producción debe conservar su visibilidad configurada de forma deliberada. Los impuestos están desactivados para validar el flujo básico. WordPress usa traducción `es_ES` porque no había paquete de idioma `es_PE` disponible para WooCommerce en la instalación inicial. Esta configuración es solo para pruebas y no está lista para recibir pagos reales.

Para probar el build de producción, compila con `npm.cmd run build` y cambia `FRAVE_VITE_DEV` a `false` en el `wp-config.php` local.

## Arquitectura

- `inc/`: configuración inicial, helpers, assets, ACF opcional, integración WooCommerce y modo catálogo, contacto y navegación.
- `template-parts/`: secciones de la portada (`home/`), banda de cabecera, búsqueda, catálogo, estados vacíos y la guía del sistema visual.
- `assets/media/`: vídeo por defecto del hero y su imagen fija.
- `assets/src/`: CSS (con el sistema de diseño en `css/system/`) y JavaScript nativo por módulos.
- `assets/dist/`: archivos listos para producción generados por Vite y versionados.
- `acf-json/`: grupo de campos editoriales de portada sincronizable con ACF gratuito.
- `woocommerce/`: no contiene overrides por ahora; los wrappers se integran con hooks públicos.

## WooCommerce

Las imágenes, variaciones, precios y existencias se administran en WooCommerce. El tema define estilos y wrappers de presentación mediante soporte y hooks públicos. Si un override de plantilla resulta imprescindible en el futuro, registra en este README su motivo y la versión de plantilla de WooCommerce que cubre.

Para las pruebas locales se cargaron productos simples, variables y agotados, cuatro categorías de muestra, un cupón de prueba, una zona de envío peruana de tarifa plana y un método manual de contra entrega. Los cinco productos publicados están rotulados `[MUESTRA]`, usan precios ficticios y no tienen fotografías comerciales. No migres esos datos de demostración a producción. No introduzcas credenciales de pasarela real en el entorno de desarrollo. Antes de vender, configura y valida una pasarela real, impuestos, tarifas y políticas comerciales.

## Preparación del catálogo

La taxonomía no está aprobada para producción. La base local contiene cuatro categorías y cinco productos publicados para revisar el diseño; los productos llevan `[MUESTRA]` en el nombre y sus descripciones aclaran que son ficticios. No migres esos datos al hosting. Revisa [GUIA_CATALOGO.md](GUIA_CATALOGO.md) y confirma categorías, productos y atributos con el catálogo real. Para preparar una importación real, abre `PLANTILLA_IMPORTACION_WOOCOMMERCE.xlsx`, completa la hoja **Importar a Woo** sin cambiar los nombres de las columnas y expórtala desde Excel o LibreOffice como **CSV UTF-8**. Sube el CSV desde **Productos → Todos los productos → Importar** y valida primero una muestra pequeña en la instalación local. Ajusta los encabezados de peso y dimensiones si la tienda de destino usa unidades distintas de kg y cm. La plantilla no incluye filas de ejemplo.
