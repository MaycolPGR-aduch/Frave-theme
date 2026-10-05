# Guía de despliegue en hosting con cPanel

- **Estado:** procedimiento listo; falta contratar el hosting y completar la tabla [Datos de este hosting](#datos-de-este-hosting).
- **Última revisión:** 5 de octubre de 2026.
- **Relacionadas:** [GUIA_COMPILACION.MD](GUIA_COMPILACION.MD) (build, linters y pruebas), [PLAN_CONTENIDO_INSTITUCIONAL.md](PLAN_CONTENIDO_INSTITUCIONAL.md), [GUIA_CATALOGO.md](GUIA_CATALOGO.md).

Los nombres de ejemplo de esta guía son `USUARIO` (usuario de cPanel), `frave.pe` (dominio) y `staging.frave.pe` (subdominio de pruebas). Sustitúyelos por los reales. **No registres contraseñas ni claves en este repositorio.**

## 1. Qué se despliega

| Pieza | Origen | Destino en el hosting |
| --- | --- | --- |
| Tema Frave | [MaycolPGR-aduch/Frave-theme](https://github.com/MaycolPGR-aduch/Frave-theme), rama `main` | `public_html/wp-content/themes/frave` |
| Plugin Frave Perú | [MaycolPGR-aduch/frave-peru](https://github.com/MaycolPGR-aduch/frave-peru), rama `main` | `public_html/wp-content/plugins/frave-peru` |
| WordPress, WooCommerce y demás plugins | Instaladores del hosting y de WordPress | El propio sitio |
| Base de datos, medios (`uploads`), `wp-config.php` | Se crean en cada entorno | No están en Git |

Solo se publican los archivos que el sitio necesita. Las reglas `export-ignore` de `.gitattributes` dejan fuera las herramientas de desarrollo, las fuentes (`assets/src`), las pruebas y la documentación interna. Esto importa porque, por ejemplo, el README menciona datos del entorno local. Los assets compilados (`assets/dist`) sí van en el repositorio: **el servidor no necesita Node.js ni Composer**.

Los datos de prueba locales (productos `[MUESTRA]`, pedidos, hojas de reclamación y mensajes) **no se migran**.

## 2. Requisitos al contratar el hosting

Pide al proveedor que confirme cada punto antes de contratar:

- [ ] **PHP 8.1 o superior**, idealmente 8.3, seleccionable en *MultiPHP Manager*, con las extensiones `mysqli`, `curl`, `gd` o `imagick`, `intl`, `mbstring`, `xml`, `zip`, `openssl` y `sodium`.
- [ ] **MySQL 8.0+ o MariaDB 10.6+**.
- [ ] Límites ajustables en *MultiPHP INI Editor*: `memory_limit` ≥ 256M, `max_execution_time` ≥ 120, `upload_max_filesize` y `post_max_size` ≥ 64M.
- [ ] **SSL gratuito automático** (*AutoSSL* / Let's Encrypt) para el dominio y los subdominios.
- [ ] **Acceso SSH o Terminal** en cPanel y la herramienta **Git™ Version Control**. Sin ellas, usa el despliegue por ZIP ([sección 5](#5-alternativa-sin-git-subir-un-zip)).
- [ ] **Cron Jobs** (tareas programadas), necesarios para WooCommerce.
- [ ] **Correo con SMTP autenticado** y la herramienta *Email Deliverability* (SPF y DKIM).
- [ ] **Copias de seguridad automáticas** (por ejemplo, JetBackup) con restauración de archivos y bases de datos.
- [ ] Recomendable: servidor **LiteSpeed** (permite LiteSpeed Cache), caché de objetos (Redis o Memcached), centro de datos en América y subdominios ilimitados para staging.

## 3. Preparar el entorno de pruebas (staging)

Todo se prueba primero en `staging.frave.pe`. Producción se prepara igual cuando staging esté validado ([sección 11](#11-paso-a-producción)).

1. **Subdominio:** *Domains* → crear `staging.frave.pe`. Anota su carpeta raíz, por ejemplo `/home/USUARIO/staging.frave.pe`.
2. **HTTPS:** *SSL/TLS Status* → *Run AutoSSL*. Confirma el candado en el navegador antes de seguir.
3. **PHP:** *MultiPHP Manager* → PHP 8.3 para el subdominio. *MultiPHP INI Editor* → límites de la sección 2.
4. **WordPress:** *Softaculous* → WordPress en `https://staging.frave.pe`, idioma Español.
   - Usuario administrador distinto de `admin`, con contraseña larga y el correo de Frave.
   - Desactiva los plugins de ejemplo que ofrezca el instalador.
5. **`wp-config.php`:** en *File Manager*, añade antes de la línea `/* That's all, stop editing! */`:

   ```php
   define( 'WP_ENVIRONMENT_TYPE', 'staging' );          // 'production' en el sitio real.
   define( 'WP_DEBUG', true );                           // false en producción.
   define( 'WP_DEBUG_DISPLAY', false );                  // Nunca mostrar errores al visitante.
   define( 'WP_DEBUG_LOG', '/home/USUARIO/logs/staging-debug.log' ); // Fuera de la carpeta pública.
   define( 'DISALLOW_FILE_EDIT', true );                 // Sin editor de código en el administrador.
   define( 'FORCE_SSL_ADMIN', true );
   define( 'WP_MEMORY_LIMIT', '256M' );
   define( 'DISABLE_WP_CRON', true );                    // Lo sustituye el cron real del paso 6.
   ```

   No definas `FRAVE_VITE_DEV`: solo existe en el entorno local. Crea la carpeta `/home/USUARIO/logs` si no existe.
6. **Cron real:** *Cron Jobs* → cada 5 minutos:

   ```bash
   /usr/local/bin/php /home/USUARIO/staging.frave.pe/wp-cron.php >/dev/null 2>&1
   ```

   Si el hosting tiene varias versiones de PHP, usa la ruta de la 8.3 (por ejemplo `/opt/cpanel/ea-php83/root/usr/bin/php`). En *WooCommerce → Estado → Acciones programadas*, las acciones pendientes deben ir completándose.
7. **Plugins del repositorio de WordPress:** WooCommerce (sin el asistente de temas ni plugins extra), un plugin SMTP ([sección 7](#7-correo-smtp-y-dns)) y ACF gratuito si se editará el hero. La caché y el SEO se añaden después ([sección 8](#8-caché-y-rendimiento)).
8. **Visibilidad:** *Ajustes → Lectura* → marca «Pedir a los motores de búsqueda que no indexen este sitio». **Solo en staging.**

## 4. Desplegar el tema y el plugin con Git (recomendado)

Cada entorno usa su propio clon de cada repositorio. El nombre de la carpeta del clon elige el destino: un clon en `~/repositories/frave-theme-staging` despliega donde indique `~/.frave-deploy/frave-theme-staging.path`.

### 4.1 Indicar los destinos (una sola vez por entorno)

En *Terminal* (o por SSH):

```bash
mkdir -p ~/.frave-deploy
echo "/home/USUARIO/staging.frave.pe/wp-content/themes/frave"      > ~/.frave-deploy/frave-theme-staging.path
echo "/home/USUARIO/staging.frave.pe/wp-content/plugins/frave-peru" > ~/.frave-deploy/frave-peru-staging.path
```

Sin Terminal, crea esos archivos con *File Manager* (muestra los archivos ocultos). Cada archivo contiene una sola línea con la ruta. El script rechaza destinos que no estén dentro de `wp-content/themes` o `wp-content/plugins`.

### 4.2 Clonar los repositorios

*Git™ Version Control* → *Create* → activa *Clone a Repository*:

| Clone URL | Repository Path | Repository Name |
| --- | --- | --- |
| `https://github.com/MaycolPGR-aduch/Frave-theme.git` | `repositories/frave-theme-staging` | Frave tema (staging) |
| `https://github.com/MaycolPGR-aduch/frave-peru.git` | `repositories/frave-peru-staging` | Frave Perú (staging) |

Los clones van en `repositories/`, **nunca dentro de una carpeta pública**.

### 4.3 Desplegar

En cada repositorio: *Manage* → pestaña *Pull or Deploy* → **Deploy HEAD Commit**. cPanel ejecuta `.cpanel.yml`, que llama a `bin/deploy-cpanel.sh`. El script:

1. extrae el commit actual (sin los archivos `export-ignore`) junto al sitio;
2. comprueba que estén los archivos imprescindibles (por ejemplo, `assets/dist/manifest.json`);
3. sustituye la carpeta publicada por la nueva de una sola vez;
4. guarda la versión anterior en `~/.frave-deploy/<clon>.previous` para poder revertir;
5. escribe el commit desplegado en el archivo `REVISION` del tema o plugin.

El registro del despliegue aparece en *Pull or Deploy* → *Last Deployment Information*. Debe terminar con `Desplegado <commit> en <ruta>`.

La primera vez, activa en WordPress el tema **Frave** (*Apariencia → Temas*) y el plugin **Frave Perú** (*Plugins*).

### 4.4 Actualizar a una versión nueva

1. Fusiona los cambios en `main` en GitHub, con el CI en verde.
2. En cPanel, para cada repositorio: *Pull or Deploy* → **Update from Remote** y luego **Deploy HEAD Commit**.
3. Si hay caché, vacíala ([sección 8](#8-caché-y-rendimiento)).
4. Comprueba que `https://staging.frave.pe/wp-content/themes/frave/REVISION` muestra el commit esperado.

Si se añadieron o cambiaron patrones del tema, el commit debe subir `Version` en `style.css`. Si no, WordPress sigue usando los patrones guardados en caché.

### 4.5 Revertir

Si una versión falla, en *Terminal*:

```bash
cd ~/repositories/frave-theme-staging && bash bin/deploy-cpanel.sh --rollback
```

Esto restaura la versión anterior. Repetir el comando vuelve a la más nueva. Para el plugin, usa su carpeta de repositorio. Después corrige el error en GitHub y despliega de nuevo. Revertir el código no deshace cambios en la base de datos (pedidos, ajustes).

## 5. Alternativa sin Git: subir un ZIP

Si el plan no incluye Git o Terminal, genera los paquetes en un equipo con el repositorio. Solo incluyen lo commiteado, sin archivos de desarrollo:

```bash
npm.cmd run package
```

Esto crea `frave.zip` en la carpeta del tema. Para el plugin, desde la carpeta `frave-peru`:

```bash
git archive --format=zip --prefix=frave-peru/ -o frave-peru.zip HEAD
```

Súbelos en *Apariencia → Temas → Añadir → Subir tema* y en *Plugins → Añadir → Subir plugin*. Al actualizar, WordPress pregunta si quieres sustituir la versión instalada: confirma. Guarda el ZIP anterior por si necesitas revertir. **No uses el botón «Download ZIP» de GitHub**, que incluye todos los archivos de desarrollo.

## 6. Configurar WordPress y WooCommerce

En este orden, en el administrador de staging:

1. **Generales:** título, descripción, zona horaria **America/Lima**, formato de fecha `j \d\e F \d\e Y`.
2. **Enlaces permanentes:** «Nombre de la entrada» (`/%postname%/`).
3. **WooCommerce → Ajustes:**
   - País Perú, moneda Sol (PEN).
   - Impuestos, envíos y métodos de pago según lo que apruebe Frave.
   - Desactivar el modo «Próximamente» solo cuando la tienda deba verse.
4. **Páginas de WooCommerce:** carrito con `[woocommerce_cart]`, finalizar compra con `[woocommerce_checkout]` y mi cuenta con `[woocommerce_my_account]`. Si el asistente creó versiones con bloques, sustitúyelas por el shortcode. Los campos de boleta y factura solo existen en el checkout clásico; el plugin avisa si detecta el bloque.
5. **Portada y menús:** *Ajustes → Lectura* → portada estática. *Apariencia → Menús* → **Navegación principal** y **Navegación del pie** (el menú **Enlaces legales del pie** es opcional).
6. **Contacto:** *Apariencia → Personalizar → Contacto* (WhatsApp, correo, teléfono, horario, dirección).
7. **Frave Perú:**
   - *Reclamaciones → Ajustes*: razón social, RUC, domicilio y página del libro.
   - *Mensajes → Ajustes*: correo que recibe los mensajes de contacto.
8. **Páginas institucionales:** créalas desde los patrones de «Frave: páginas institucionales» con el texto aprobado ([PLAN_CONTENIDO_INSTITUCIONAL.md](PLAN_CONTENIDO_INSTITUCIONAL.md)).
   - Asigna la política de privacidad en *Ajustes → Privacidad*.
   - Asigna los términos en *WooCommerce → Ajustes → Avanzado*.
9. **Catálogo:** atributos globales y productos según [GUIA_CATALOGO.md](GUIA_CATALOGO.md).
10. **ACF** (si está activo): sincroniza el grupo «Inicio Frave» cuando el administrador lo ofrezca y edita el hero.

## 7. Correo (SMTP y DNS)

El cliente debe recibir la confirmación de pedido, la constancia del Libro de Reclamaciones (obligatoria) y las respuestas a sus mensajes. El correo de PHP sin autenticar suele acabar en spam.

1. **Cuenta de envío:** *Email Accounts* → crea una cuenta, por ejemplo `tienda@frave.pe`.
2. **Registros de autenticación:** *Email Deliverability* → instala o repara SPF y DKIM. Si el DNS del dominio está en otro proveedor, copia allí esos registros y añade DMARC (`v=DMARC1; p=none; rua=mailto:...`).
3. **Plugin SMTP:** instala uno (por ejemplo WP Mail SMTP o FluentSMTP) con el servidor SMTP de cPanel, puerto 465 (SSL), y esa cuenta. Envía el correo de prueba del plugin.
4. **Remitente:** en *WooCommerce → Ajustes → Correos electrónicos*, remitente `tienda@frave.pe` y color base naranja Frave (`#c43b00`).
5. **Prueba completa:** haz un pedido de prueba y registra una hoja en el Libro de Reclamaciones. Comprueba que llegan los correos al cliente y a la tienda y que no van a spam.

## 8. Caché y rendimiento

- **Servidor LiteSpeed:** instala **LiteSpeed Cache**. Excluye de la caché carrito, finalizar compra, mi cuenta, Libro de Reclamaciones y Contacto.
  - LiteSpeed Cache ya excluye las páginas de WooCommerce.
  - Frave Perú marca sus páginas como no cacheables (`DONOTCACHEPAGE` y cabeceras `no-cache`).
- **Contador del carrito:** el tema actualiza el contador y el mini-carrito por AJAX aunque la página venga de la caché.
- **Caché de objetos:** si el hosting ofrece Redis o Memcached, actívala desde el plugin de caché.
- **Al desplegar:** vacía la caché de página (y la de Cloudflare, si se usa). El CSS y el JS tienen nombres con hash, así que nunca se sirven versiones antiguas.
- **Sin optimizar dos veces:** no actives la minificación ni la combinación de CSS y JS del plugin de caché. El tema ya entrega sus archivos minificados y precarga sus fuentes. Si se activan, prueba de nuevo el mini-carrito y los filtros.

## 9. Seguridad y copias de seguridad

- **Actualizaciones:**
  - WordPress y los plugins de terceros: actualizaciones menores automáticas; las mayores, primero en staging.
  - Tema y Frave Perú: solo desde Git ([sección 4.4](#44-actualizar-a-una-versión-nueva)).
- **Acceso:** usuarios administradores nominales con contraseñas largas y doble factor (por ejemplo, Two Factor o Wordfence Login Security); el rol «Gestor de la tienda» para quien solo administra pedidos.
- **Copias de seguridad:**
  - Diarias en el hosting (JetBackup) y una copia externa semanal (por ejemplo, UpdraftPlus a Google Drive) con archivos y base de datos.
  - **Prueba una restauración en staging** antes de abrir la tienda.
- **Permisos:** carpetas 755 y archivos 644; `wp-config.php` 600 o 640 si el hosting lo permite.
- **Registros:** revisa `/home/USUARIO/logs/staging-debug.log` tras cada despliegue en staging. En producción, `WP_DEBUG` va en `false`.

## 10. Validar staging

- [ ] El tema y el plugin muestran en `REVISION` el commit esperado.
- [ ] Portada, tienda, filtros, categorías, búsqueda y fichas cargan sin errores; no hay archivos 404 en la consola del navegador.
- [ ] Menú móvil, mini-carrito y WhatsApp funcionan en móvil y en escritorio.
- [ ] Carrito, cupón, envío y checkout con boleta (DNI) y factura (RUC) completan pedidos; los correos llegan.
- [ ] Libro de Reclamaciones: la constancia llega al cliente, y la respuesta desde el administrador también.
- [ ] Formulario de contacto: el mensaje queda en *Mensajes* y llega al correo configurado.
- [ ] Pasarela real en modo prueba: pagos aprobados, rechazados y un reembolso.
- [ ] Copia de seguridad restaurada con éxito.
- [ ] **Pruebas automáticas contra staging**, desde el equipo de desarrollo:

  ```powershell
  $env:FRAVE_E2E_URL='https://staging.frave.pe'; npm.cmd run test:e2e
  ```

  Las pruebas crean pedidos, hojas de reclamación y mensajes de prueba: bórralos después. **Nunca las ejecutes contra producción.**

## 11. Paso a producción

Producción se monta limpia, sin los pedidos ni las hojas de prueba de staging:

1. Repite las secciones 3 y 4 para `frave.pe`, con `WP_ENVIRONMENT_TYPE` en `production`, `WP_DEBUG` en `false` y clones en `repositories/frave-theme` y `repositories/frave-peru`:

   ```bash
   echo "/home/USUARIO/public_html/wp-content/themes/frave"      > ~/.frave-deploy/frave-theme.path
   echo "/home/USUARIO/public_html/wp-content/plugins/frave-peru" > ~/.frave-deploy/frave-peru.path
   ```

2. Despliega **el mismo commit** que se validó en staging.
3. Configura producción como en las secciones 6 y 7. Para pasar el contenido de staging (páginas, productos, medios) usa un plugin de migración **solo si staging no tiene pedidos reales**. Antes, borra en staging los pedidos, hojas de reclamación y mensajes de prueba.
4. *Ajustes → Lectura*: **desmarca** «Pedir a los motores de búsqueda que no indexen este sitio».
5. Pasarela de pago en modo real y cron real activo.
6. **Pedido real de bajo importe** de principio a fin: cobro, correo, comprobante, cambio de estado, inventario y reembolso.
7. Plugin de SEO, mapa del sitio y alta en Google Search Console.
8. Redirecciones: `http://` → `https://` y una sola forma del dominio (con o sin `www`).

Después de abrir la tienda, **nunca sustituyas la base de datos de producción** por la de staging: se perderían los pedidos reales.

## 12. Operación habitual

| Tarea | Cómo |
| --- | --- |
| Publicar una mejora del tema o del plugin | PR en GitHub con CI en verde → fusionar → staging (4.4) → validar → producción (4.4) |
| Revertir una versión | `bash bin/deploy-cpanel.sh --rollback` en el clon afectado (4.5) |
| Saber qué versión está publicada | `https://frave.pe/wp-content/themes/frave/REVISION` |
| Actualizar WordPress, WooCommerce o plugins | Primero en staging; revisar tienda y checkout; luego producción |
| Revisar errores | Registro de PHP de staging; *WooCommerce → Estado → Registros* |

## Datos de este hosting

Complétala al contratar. **Sin contraseñas.**

| Dato | Valor |
| --- | --- |
| Proveedor y plan | |
| Usuario de cPanel (`USUARIO`) | |
| Servidor web (Apache, LiteSpeed, Nginx) | |
| PHP / MySQL o MariaDB | |
| Ruta de producción | `/home/USUARIO/public_html` |
| Ruta de staging | `/home/USUARIO/staging.frave.pe` |
| Ruta del binario PHP para cron | |
| SMTP (servidor y puerto) | |
| Copias de seguridad (frecuencia y retención) | |
| Responsable de los despliegues | |
| Commit en producción y fecha de salida | |

## Registro de cambios de la guía

| Fecha | Cambio |
| --- | --- |
| 2026-09-23 | Guía inicial independiente del proveedor; configuración del hosting pendiente. |
| 2026-10-05 | Guía para cPanel: requisitos, staging, despliegue con Git (`.cpanel.yml`, `bin/deploy-cpanel.sh`) o ZIP, configuración, correo, caché, seguridad, validación y paso a producción. |
