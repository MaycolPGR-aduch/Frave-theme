# Guía de despliegue en Hostinger

- **Estado:** hosting contratado (plan Premium), WordPress instalado y `frave.pe` apuntando a Hostinger con SSL; falta el primer despliegue del tema y el plugin.
- **Última revisión:** 9 de octubre de 2026.
- **Relacionadas:** [GUIA_DESPLIEGUE_LOCAL.MD](GUIA_DESPLIEGUE_LOCAL.MD) (entorno local), [GUIA_COMPILACION.MD](GUIA_COMPILACION.MD) (build, linters y pruebas), [PLAN_CONTENIDO_INSTITUCIONAL.md](PLAN_CONTENIDO_INSTITUCIONAL.md), [GUIA_CATALOGO.md](GUIA_CATALOGO.md).

Nombres de ejemplo de esta guía (sustitúyelos por los reales):

- `u123456789`: usuario SSH de Hostinger.
- `IP` y `PUERTO`: servidor y puerto SSH que muestra hPanel.
- `DOMINIO`: carpeta del sitio en `~/domains/`, normalmente `frave.pe`. Compruébalo con `ls ~/domains`.

**No registres contraseñas ni claves en este repositorio.** El menú de hPanel cambia a menudo: si no encuentras una opción, escribe su nombre en el buscador del menú lateral del sitio.

## 1. Qué se despliega

| Pieza | Origen | Destino en Hostinger |
| --- | --- | --- |
| Tema Frave | [MaycolPGR-aduch/Frave-theme](https://github.com/MaycolPGR-aduch/Frave-theme), rama `main` | `~/domains/DOMINIO/public_html/wp-content/themes/frave` |
| Plugin Frave Perú | [MaycolPGR-aduch/frave-peru](https://github.com/MaycolPGR-aduch/frave-peru), rama `main` | `~/domains/DOMINIO/public_html/wp-content/plugins/frave-peru` |
| WordPress, WooCommerce y demás plugins | Instalador de Hostinger y de WordPress | El propio sitio |
| Base de datos, medios (`uploads`), `wp-config.php` | Se crean en Hostinger | No están en Git |

Solo se publican los archivos que el sitio necesita: las reglas `export-ignore` de `.gitattributes` dejan fuera las herramientas de desarrollo, las fuentes (`assets/src`), las pruebas y la documentación interna. Los assets compilados (`assets/dist`) van en el repositorio, así que **el servidor no necesita Node.js ni Composer**.

Los datos de prueba locales (productos `[MUESTRA]`, pedidos, hojas de reclamación y mensajes) **no se migran**.

## 2. Entornos y flujo de trabajo

El plan Premium no incluye la herramienta de staging de Hostinger, así que hay dos entornos:

- **Local** (`http://127.0.0.1:8080`): pruebas. Se arranca con `Iniciar Frave` ([GUIA_DESPLIEGUE_LOCAL.MD](GUIA_DESPLIEGUE_LOCAL.MD#arranque-diario)).
- **Hostinger:** la web real.

| Tipo de cambio | Ejemplos | Flujo |
| --- | --- | --- |
| **Contenido y ajustes** | Productos, precios, stock, páginas, menús, imágenes, Personalizador, ajustes de WooCommerce | Directamente en el administrador de Hostinger. No pasa por Git. |
| **Código no crítico** | Estilos, textos fijos del tema, maquetación, documentación | Commit → GitHub con el CI en verde → publicar ([sección 6](#6-publicar-cambios)) → revisar en Hostinger. Si algo sale mal, revertir ([sección 7](#7-revertir)). |
| **Código crítico** | Carrito, checkout, boleta y factura, Libro de Reclamaciones, contacto, filtros y consultas, correos, cambios de versión de WooCommerce o PHP | Probar en local, con `npm.cmd run test:e2e` → GitHub con el CI en verde → copia de seguridad en hPanel → publicar → repetir en Hostinger el recorrido afectado. |

Reglas que evitan perder trabajo:

1. **El código solo llega a Hostinger por Git.** No edites archivos del tema ni del plugin con el Administrador de archivos ni con el editor de WordPress: el siguiente despliegue los sustituye. `DISALLOW_FILE_EDIT` ([sección 3](#3-preparar-el-sitio-una-sola-vez)) desactiva ese editor.
2. **Solo se publica lo que está en `main`** en GitHub, con el CI en verde.
3. **La base de datos de Hostinger nunca se sustituye por la local**: se perderían pedidos, clientes y hojas de reclamación reales.
4. **Las pruebas automáticas solo se ejecutan en local.** Crean pedidos y hojas de reclamación; contra la web real dejarían registros falsos.

## 3. Preparar el sitio (una sola vez)

1. **PHP:** *Configuración de PHP* → versión **8.3**. En *Opciones de PHP*: `memory_limit` ≥ 256M, `max_execution_time` ≥ 120, `upload_max_filesize` y `post_max_size` ≥ 64M.
2. **WordPress:** *Sitios web* → tu sitio → *Instalador automático* → **WordPress**, idioma Español.
   - Usuario administrador distinto de `admin`, con contraseña larga y el correo de Frave.
   - Si el asistente ofrece instalar WooCommerce, plantillas o plugins de IA, no los añadas: WooCommerce se instala después (paso 6). Deja **LiteSpeed Cache**.
3. **HTTPS:** *Seguridad → SSL*. Hostinger emite el certificado al conectar el dominio ([sección 12](#12-dominio-y-dns)); comprueba el candado en el navegador.
4. **`wp-config.php`:** con el *Administrador de archivos*, abre `public_html/wp-config.php` y añade antes de la línea `/* That's all, stop editing! */`:

   ```php
   define( 'WP_ENVIRONMENT_TYPE', 'production' );
   define( 'WP_DEBUG', false );
   define( 'WP_DEBUG_LOG', '/home/u123456789/logs/frave-debug.log' ); // Fuera de la carpeta pública.
   define( 'WP_DEBUG_DISPLAY', false );                                // Nunca mostrar errores al visitante.
   define( 'DISALLOW_FILE_EDIT', true );                               // Sin editor de código en el administrador.
   define( 'FORCE_SSL_ADMIN', true );
   define( 'WP_MEMORY_LIMIT', '256M' );
   define( 'DISABLE_WP_CRON', true );                                  // Lo sustituye el cron real del paso 5.
   ```

   No definas `FRAVE_VITE_DEV`: solo existe en el entorno local. Para investigar un error, cambia `WP_DEBUG` a `true` un momento y revisa el registro; vuelve a `false` al terminar. La carpeta `~/logs` se crea en la [sección 5](#5-primer-despliegue).
5. **Cron real:** *Cron Jobs* → tipo **PHP**, cada 5 minutos, con el archivo `/home/u123456789/domains/DOMINIO/public_html/wp-cron.php`. Más tarde, en *WooCommerce → Estado → Acciones programadas*, las acciones pendientes deben ir completándose.
6. **Plugins:** WooCommerce y, si se editará el hero, ACF gratuito. El plugin SMTP se añade al configurar el correo ([sección 10](#10-correo)).
7. **Mientras se prepara la tienda:**
   - *Ajustes → Lectura* → marca «Pedir a los motores de búsqueda que no indexen este sitio».
   - Deja WooCommerce en modo **Próximamente**: solo los administradores conectados ven la tienda.

## 4. Acceso SSH (una sola vez)

1. **Activar:** *Acceso SSH* → **Habilitar**. Anota IP, puerto y usuario, y define una contraseña SSH.
2. **Clave en tu equipo** (opcional, evita escribir la contraseña en cada publicación). En PowerShell:

   ```powershell
   ssh-keygen -t ed25519 -C "frave-hostinger"
   Get-Content $env:USERPROFILE\.ssh\id_ed25519.pub
   ```

   Pega la línea que aparece en *Acceso SSH → Claves SSH → Añadir clave SSH*.
3. **Conectar** desde PowerShell:

   ```powershell
   ssh -p PUERTO u123456789@IP
   ```

4. **Comprobar** dentro del servidor:

   ```bash
   git --version
   wp --info
   ls ~/domains
   ```

   `ls ~/domains` muestra la carpeta `DOMINIO`. Si `wp` no existe, el despliegue funciona igual, pero la caché hay que vaciarla a mano ([sección 11](#11-caché-y-rendimiento)).

## 5. Primer despliegue

Conectado por SSH, sustituye `DOMINIO` y ejecuta:

```bash
mkdir -p ~/repos ~/.frave-deploy ~/logs
git clone https://github.com/MaycolPGR-aduch/Frave-theme.git ~/repos/frave-theme
git clone https://github.com/MaycolPGR-aduch/frave-peru.git ~/repos/frave-peru
echo "$HOME/domains/DOMINIO/public_html/wp-content/themes/frave"      > ~/.frave-deploy/frave-theme.path
echo "$HOME/domains/DOMINIO/public_html/wp-content/plugins/frave-peru" > ~/.frave-deploy/frave-peru.path
cd ~/repos/frave-theme && bash bin/deploy.sh
cd ~/repos/frave-peru && bash bin/deploy.sh
```

- **Clones:** los repositorios son públicos, así que se clonan sin claves. Viven en `~/repos`, **fuera de `public_html`**.
- **Destinos:** cada clon publica en la ruta de `~/.frave-deploy/<carpeta del clon>.path`. El script rechaza rutas que no estén dentro de `wp-content/themes` o `wp-content/plugins`.

`bin/deploy.sh`:

1. extrae el commit actual, sin los archivos `export-ignore`;
2. comprueba que estén los archivos imprescindibles (por ejemplo, `assets/dist/manifest.json`);
3. sustituye la carpeta publicada de una sola vez, de modo que el sitio nunca ve una mezcla de versiones;
4. guarda la versión anterior en `~/.frave-deploy/<clon>.previous` para poder revertir;
5. escribe el commit en el archivo `REVISION`;
6. vacía la caché de LiteSpeed con WP-CLI.

Cada despliegue termina con `Desplegado <commit> en <ruta>`. Después, activa **Frave** en *Apariencia → Temas* y **Frave Perú** en *Plugins*, y continúa con la [sección 9](#9-configurar-wordpress-y-woocommerce).

## 6. Publicar cambios

1. **En tu equipo:** sube los cambios a GitHub y llévalos a `main`, con un PR para los cambios críticos. Espera el CI en verde.
2. **Antes de un cambio crítico:** haz una copia en *Copias de seguridad* de hPanel.
3. **En Hostinger** (por SSH):

   ```bash
   cd ~/repos/frave-theme && git pull --ff-only && bash bin/deploy.sh
   cd ~/repos/frave-peru && git pull --ff-only && bash bin/deploy.sh
   ```

   Basta con el repositorio que cambió. También puedes hacerlo desde PowerShell sin abrir una sesión:

   ```powershell
   ssh -p PUERTO u123456789@IP "cd ~/repos/frave-theme && git pull --ff-only && bash bin/deploy.sh"
   ```

4. **Comprobar:** `https://DOMINIO/wp-content/themes/frave/REVISION` muestra el commit publicado. Revisa en el navegador lo que cambió y, si fue un cambio crítico, el recorrido completo (por ejemplo, un pedido con boleta).

`git pull --ff-only` falla si el clon del servidor tiene cambios propios. No debería tenerlos nunca: el código se cambia en local.

Si se añadieron o cambiaron patrones del tema, el commit debe subir `Version` en `style.css`. Si no, WordPress sigue usando los patrones guardados en caché.

## 7. Revertir

Si una versión falla, por SSH:

```bash
cd ~/repos/frave-theme && bash bin/deploy.sh --rollback
```

Se restaura la versión anterior (y repetir el comando vuelve a la más nueva). Para el plugin, usa `~/repos/frave-peru`. Después corrige el error en local, súbelo a `main` y publica de nuevo. **Revertir el código no deshace cambios en la base de datos** (pedidos, ajustes); para eso está la copia de seguridad del paso 6.2.

## 8. Alternativa sin SSH: subir un ZIP

Si el SSH no está disponible, genera los paquetes en tu equipo. Solo incluyen lo que está en un commit, sin archivos de desarrollo:

```powershell
cd D:\Frave-web\frave
npm.cmd run package
cd D:\Frave-web\frave-peru
git archive --format=zip --prefix=frave-peru/ -o frave-peru.zip HEAD
```

Súbelos en *Apariencia → Temas → Añadir → Subir tema* y en *Plugins → Añadir → Subir plugin*, confirma la sustitución y vacía la caché de LiteSpeed. Guarda el ZIP anterior para poder revertir. **No uses el botón «Download ZIP» de GitHub**, que incluye todos los archivos de desarrollo.

## 9. Configurar WordPress y WooCommerce

En este orden, en el administrador de Hostinger:

1. **Generales:** título, descripción, zona horaria **America/Lima**, formato de fecha `j \d\e F \d\e Y`.
2. **Enlaces permanentes:** «Nombre de la entrada» (`/%postname%/`).
3. **WooCommerce → Ajustes:**
   - País Perú, moneda Sol (PEN).
   - Impuestos, envíos y métodos de pago según lo que apruebe Frave.
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

## 10. Correo

El cliente debe recibir la confirmación de pedido, la constancia del Libro de Reclamaciones (obligatoria) y las respuestas a sus mensajes. El correo de PHP sin autenticar suele acabar en spam.

Los buzones `@frave.pe` están en Hostbox ([sección 12](#12-dominio-y-dns)), así que la web envía a través de ellos. **No actives el correo de Hostinger** («Configurar servicio de email gratis»): pediría cambiar el MX y los buzones de Hostbox dejarían de recibir correo.

1. **Buzón de envío:** usa uno existente (por ejemplo, `soporte@frave.pe`) o crea uno en el cPanel de Hostbox (*Cuentas de correo electrónico*).
2. **Plugin SMTP** (por ejemplo, WP Mail SMTP o FluentSMTP): servidor `mail.frave.pe`, puerto 465 (SSL), usuario y contraseña de ese buzón. Si el plugin da error de certificado, usa el servidor que indica cPanel en *Cuentas de correo electrónico → Connect Devices*. Envía el correo de prueba del plugin.
3. **Registros DNS:** el SPF, el DKIM (`default._domainkey`) y el DMARC de frave.pe ya están en la zona de Hostbox y cubren los envíos por su servidor. No hace falta añadir nada.
4. **Remitente:** en *WooCommerce → Ajustes → Correos electrónicos*, el mismo buzón como remitente y color base naranja Frave (`#c43b00`).
5. **Prueba completa:** haz un pedido de prueba y registra una hoja en el Libro de Reclamaciones. Comprueba que llegan los correos al cliente y a la tienda y que no van a spam. Después borra el pedido y la hoja de prueba.

## 11. Caché y rendimiento

- **LiteSpeed Cache** viene con el WordPress de Hostinger.
  - Ya excluye carrito, finalizar compra y mi cuenta.
  - Frave Perú marca el Libro de Reclamaciones y Contacto como no cacheables (`DONOTCACHEPAGE` y cabeceras `no-cache`).
- **Al desplegar:** `bin/deploy.sh` vacía la caché. Si muestra «vacíala ahora desde el administrador», usa *LiteSpeed Cache → Panel → Purgar todo*. Hasta entonces, las páginas guardadas apuntan a CSS y JS que ya no existen y la web se vería sin estilos.
- **Contador del carrito:** el tema lo actualiza, junto con el mini-carrito, por AJAX aunque la página venga de la caché.
- **Sin optimizar dos veces:** no actives la minificación ni la combinación de CSS y JS de LiteSpeed. El tema ya entrega sus archivos minificados y precarga sus fuentes. Si se activan, prueba de nuevo el mini-carrito y los filtros.

## 12. Dominio y DNS

Conectado el 9 de octubre de 2026. La web está en Hostinger; el dominio, sus DNS y el correo siguen en Hostbox:

| Pieza | Dónde está |
| --- | --- |
| Registro del dominio `frave.pe` | Hostbox (renovación anual en marzo) |
| Zona DNS | *Zone Editor* del cPanel de Hostbox. Es la cuenta de hosting de Fragantik, en la que `frave.pe` es un dominio adicional. Servidores de nombres `ns1`–`ns4.mysecurecloudhost.com`. |
| Web | Hostinger: registro A `frave.pe` → `89.116.115.251` (TTL 300); `www` es un CNAME a `frave.pe` |
| Correo | Hostbox: MX → `mail.frave.pe`, registro A → `185.181.252.46`. Webmail en `webmail.frave.pe`. |

Reglas:

- **No cambies los servidores de nombres a los de Hostinger** ni actives su correo: los buzones `@frave.pe` dejarían de recibir correo. Hostinger muestra esas opciones como recomendadas; ignóralas mientras el correo esté en Hostbox.
- **El cPanel de Hostbox** se abre en `https://fragantik.com:2083` o `https://cpanel.frave.pe:2083`, ya no en `frave.pe:2083`. Contiene también la web y el correo de Fragantik: toca solo las filas de `frave.pe`.
- **Dispositivos de correo:** el servidor de entrada y salida es `mail.frave.pe`, nunca `frave.pe` a secas.
- **Dependencia:** si la cuenta de Hostbox vence o se elimina el dominio adicional, frave.pe se queda sin DNS ni correo, y la tienda deja de verse. Mantenla al día. Para independizarlo habría que migrar antes los buzones, y después pasar los servidores de nombres y el correo a otro proveedor.

El SSL lo emite Hostinger automáticamente (*Seguridad → SSL*). `http://` y `www` redirigen a `https://frave.pe`.

## 13. Antes de abrir la tienda

- [ ] El tema y el plugin muestran en `REVISION` el commit esperado.
- [ ] Portada, tienda, filtros, categorías, búsqueda y fichas cargan sin errores; no hay archivos 404 en la consola del navegador.
- [ ] Menú móvil, mini-carrito y WhatsApp funcionan en móvil y en escritorio.
- [ ] Carrito, cupón, envío y checkout con boleta (DNI) y factura (RUC) completan pedidos; los correos llegan.
- [ ] Libro de Reclamaciones: la constancia llega al cliente, y la respuesta desde el administrador también.
- [ ] Formulario de contacto: el mensaje queda en *Mensajes* y llega al correo configurado.
- [ ] Pasarela real en modo prueba: pagos aprobados, rechazados y un reembolso.
- [ ] Copia de seguridad restaurada con éxito al menos una vez.
- [ ] Borrados los pedidos, hojas de reclamación y mensajes de prueba.

Para abrir:

1. Desmarca «Pedir a los motores de búsqueda que no indexen este sitio» y desactiva el modo **Próximamente** de WooCommerce.
2. Pasarela de pago en modo real.
3. **Pedido real de bajo importe** de principio a fin: cobro, correo, comprobante, cambio de estado, inventario y reembolso.
4. Plugin de SEO, mapa del sitio y alta en Google Search Console.
5. Redirecciones: `http://` → `https://` y una sola forma del dominio (con o sin `www`).

## 14. Seguridad y copias de seguridad

- **Actualizaciones:**
  - WordPress y los plugins de terceros: menores automáticas. Las mayores, sobre todo de WooCommerce, son cambios críticos: primero en local y con copia de seguridad antes de aplicarlas en Hostinger.
  - Tema y Frave Perú: solo desde Git ([sección 6](#6-publicar-cambios)).
- **Acceso:** administradores nominales con contraseñas largas y doble factor (por ejemplo, Two Factor o Wordfence Login Security); el rol «Gestor de la tienda» para quien solo administra pedidos. Activa también el doble factor en la cuenta de Hostinger.
- **Copias de seguridad:**
  - Las del plan, en *Copias de seguridad* de hPanel; comprueba allí su frecuencia.
  - Una copia externa semanal (por ejemplo, UpdraftPlus a Google Drive) con archivos y base de datos.
  - Una copia manual antes de cada cambio crítico.
- **Registros:** `~/logs/frave-debug.log` (con `WP_DEBUG_LOG`) y *WooCommerce → Estado → Registros*.

## 15. Operación habitual

| Tarea | Cómo |
| --- | --- |
| Cambiar productos, precios, páginas o ajustes | En el administrador de Hostinger |
| Publicar una mejora del tema o del plugin | `main` con CI en verde → `git pull --ff-only && bash bin/deploy.sh` en el clon ([sección 6](#6-publicar-cambios)) |
| Revertir una versión | `bash bin/deploy.sh --rollback` en el clon afectado ([sección 7](#7-revertir)) |
| Saber qué versión está publicada | `https://DOMINIO/wp-content/themes/frave/REVISION` y `.../plugins/frave-peru/REVISION` |
| Actualizar WordPress, WooCommerce o plugins | Mayores: primero en local, copia de seguridad, luego Hostinger |
| Revisar errores | `~/logs/frave-debug.log`; *WooCommerce → Estado → Registros* |

## Datos de este hosting

Complétala con lo que muestre hPanel. **Sin contraseñas.**

| Dato | Valor |
| --- | --- |
| Proveedor y plan | Hostinger, Premium (copias de seguridad semanales) |
| Usuario SSH, IP y puerto | |
| Carpeta del sitio (`~/domains/DOMINIO`) | |
| Dominio temporal de Hostinger | `darkseagreen-clam-146548.hostingersite.com` |
| Dominio | `frave.pe` (DNS y correo en Hostbox, ver [sección 12](#12-dominio-y-dns)) |
| PHP / MySQL o MariaDB | PHP 8.3 |
| SMTP (servidor, puerto y buzón) | `mail.frave.pe`, 465 (buzón de Hostbox) |
| Copias de seguridad (frecuencia y retención) | Semanales (plan Premium) |
| Responsable de los despliegues | |
| Commit en producción y fecha de salida | |

## Registro de cambios de la guía

| Fecha | Cambio |
| --- | --- |
| 2026-09-23 | Guía inicial independiente del proveedor; configuración del hosting pendiente. |
| 2026-10-05 | Guía para cPanel: requisitos, staging, despliegue con Git o ZIP, configuración, correo, caché, seguridad, validación y paso a producción. |
| 2026-10-08 | Guía para Hostinger Premium: SSH, `bin/deploy.sh` (antes `deploy-cpanel.sh`) con vaciado de caché, flujo local → Hostinger según el tipo de cambio y conexión del dominio. |
| 2026-10-09 | frave.pe conectado con un registro A en la zona DNS de Hostbox; el correo sigue en Hostbox (MX `mail.frave.pe`) y la web envía por su SMTP. |
