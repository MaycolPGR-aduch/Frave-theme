# Guía de compilación de Frave

- **Estado:** procedimiento local actual.
- **Última revisión:** 26 de septiembre de 2026.
- **Configuración de producción:** [GUIA_DESPLIEGUE.MD](GUIA_DESPLIEGUE.MD).

## Qué se compila

Vite toma `assets/src/js/app.js` como entrada. Ese archivo importa Lato, `assets/src/css/app.css` y los módulos del menú móvil y las transiciones de portada. Tailwind CSS 4 procesa el CSS con detección automática desactivada y examina solo los templates PHP y archivos JS indicados por `@source`; los Markdown no alteran las clases generadas. El resultado se escribe en `assets/dist/`: CSS, JavaScript y fuentes con hash, más `manifest.json`. WordPress lee el manifest desde `inc/enqueue.php`.

La configuración `source(none)` y `@source` sigue la [guía oficial de detección de Tailwind](https://tailwindcss.com/docs/detecting-classes-in-source-files).

`style.css` contiene los metadatos del tema; el CSS que ve el visitante sale del build. Los templates PHP, `theme.json`, `acf-json/` y el logotipo no necesitan compilarse.

## Requisitos

- Node.js 22.12 o superior y npm; las dependencias están fijadas en `package-lock.json`.
- Una instalación WordPress con el tema en `wp-content/themes/frave/` para probar las páginas.
- Para el entorno local documentado: PHP 8.3 de Laragon, MySQL local en el puerto `3307`, WordPress en `D:/Frave-web/.local-wordpress/wordpress/` y servidor PHP en `http://127.0.0.1:8080`.

Ejecuta los comandos desde la carpeta del tema. En este equipo Windows es `D:\Frave-web\frave`; en otros equipos, usa su ruta equivalente.

## Primera instalación o actualización de dependencias

En PowerShell:

```powershell
cd D:\Frave-web\frave
node --version
npm.cmd --version
npm.cmd ci
```

`npm ci` instala exactamente las versiones resueltas por el lockfile y no requiere actualizarlo. Si se modifican dependencias deliberadamente, revisa y versiona también `package-lock.json`.

## Desarrollo con recarga en vivo

1. Inicia MySQL y la instalación local de WordPress. Si necesitas iniciar el servidor PHP manualmente:

   ```powershell
   & 'C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe' -S 127.0.0.1:8080 -t 'D:\Frave-web\.local-wordpress\wordpress'
   ```

2. En `wp-config.php` **solo del entorno local**, define las constantes antes de cargar WordPress:

   ```php
   define( 'FRAVE_VITE_DEV', true );
   define( 'FRAVE_VITE_SERVER', 'http://127.0.0.1:5173' );
   ```

3. En otra terminal, desde la carpeta del tema, ejecuta:

   ```powershell
   npm.cmd run dev
   ```

4. Abre `http://127.0.0.1:8080`. El servidor Vite escucha en `127.0.0.1:5173`; el puerto es fijo y se permite el origen local configurado en `vite.config.js`. `server.origin` hace que las fuentes de Lato se pidan a Vite y no al servidor PHP. Si cambias el puerto de Vite, cambia también `server.origin` y `FRAVE_VITE_SERVER`.

El modo desarrollo solo carga entradas desde Vite. Si Vite está detenido, inicia el servidor o desactiva `FRAVE_VITE_DEV` para usar el último build de `assets/dist/`.

## Build para producción

```powershell
cd D:\Frave-web\frave
npm.cmd ci
npm.cmd run build
```

En macOS o Linux, usa `npm ci` y `npm run build`. El build limpia `assets/dist/` y lo regenera. No hardcodees los nombres con hash en PHP o en la documentación. Confirma que `assets/dist/manifest.json` contiene la entrada `assets/src/js/app.js` y que sus rutas existen.

Comprobación reproducible en PowerShell:

```powershell
$fraveManifest = Get-Content .\assets\dist\manifest.json -Raw | ConvertFrom-Json
$fraveEntry = $fraveManifest.'assets/src/js/app.js'
if (-not $fraveEntry) { throw 'Falta la entrada principal en el manifest.' }
$fraveFiles = @($fraveEntry.file) + @($fraveEntry.css) + @($fraveEntry.assets)
$fraveMissing = $fraveFiles | Where-Object { $_ -and -not (Test-Path (Join-Path 'assets/dist' $_)) }
if ($fraveMissing) { throw "Faltan archivos del build: $($fraveMissing -join ', ')" }
```

Después del build, usa `git status` para comprobar si cambiaron los archivos compilados y versiona `assets/dist/` junto con el cambio de código. El servidor de producción sirve estos archivos directamente y no necesita Node.js, npm ni Tailwind.

## Plantilla de traducción

`languages/frave.pot` recoge los textos traducibles del tema. Regénerala cuando añadas o cambies textos con funciones `__()`, `esc_html_e()` y similares. Desde `D:/Frave-web/.local-wordpress/`:

```powershell
& 'C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe' wp-cli.phar --path=wordpress i18n make-pot ../frave ../frave/languages/frave.pot --domain=frave --exclude=node_modules,assets/dist,acf-json,vendor
```

Los textos con marcadores (`%s`, `%d`) llevan un comentario `/* translators: … */` en la línea anterior. Los textos del menú móvil se pasan desde PHP con atributos `data-label-*`, así que también son traducibles.

## Comprobación antes de entregar

- [ ] `npm.cmd run build` termina sin errores y el manifest apunta a archivos existentes.
- [ ] Con `FRAVE_VITE_DEV` en `false` o sin definir, portada, tienda, producto, carrito y checkout cargan CSS y JS sin errores 404.
- [ ] Los PHP modificados pasan `php -l` usando el ejecutable de PHP del entorno; la activación del tema no genera errores.
- [ ] El menú móvil y el contador del carrito funcionan tras el cambio.
- [ ] `assets/dist/` queda incluido en el commit y `node_modules/` y `vendor/` permanecen fuera de Git.
- [ ] Los linters pasan (ver abajo).

## Linters e integración continua

La primera vez, instala las herramientas de PHP con Composer (Laragon lo incluye):

```powershell
& 'C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe' 'C:\laragon\bin\composer\composer.phar' install
```

Comprobaciones disponibles:

| Comando | Qué revisa |
| --- | --- |
| `npm.cmd run lint:js` | ESLint sobre `assets/src/js` y los archivos de configuración. |
| `vendor/bin/phpcs` (con el PHP de Laragon) | Estándar WordPress-Extra, dominio de traducción `frave`, prefijos y compatibilidad con PHP 8.1+. `vendor/bin/phpcbf` corrige el formato automáticamente. |

GitHub Actions (`.github/workflows/ci.yml`) ejecuta en cada pull request y en cada push a `main`: `php -l` con PHP 8.1 y 8.3, PHPCS, ESLint, el build y una comprobación de que el `assets/dist/` del commit coincide con el código fuente. Si esa última falla, ejecuta `npm.cmd run build` y haz commit de `assets/dist/`.

## Pruebas de principio a fin (Playwright)

`tests/e2e/` recorre la tienda como un cliente: portada, filtros del catálogo, mini-carrito (producto simple y variable), checkout con boleta y factura (validación de DNI y RUC y pedido real), Libro de Reclamaciones, formulario de contacto, 404 y, en un móvil emulado, menú, panel de filtros y ausencia de desbordamiento.

**En local**, con MySQL, PHP y Vite en marcha y el WordPress de Laragon:

```powershell
npm.cmd run test:e2e
```

Usa el Chrome instalado (no descarga navegadores) y la URL `http://127.0.0.1:8080`; otra URL se indica con `FRAVE_E2E_URL`. Las pruebas encuentran productos y páginas navegando, así que funcionan con los datos de muestra locales. Crean pedidos y hojas de reclamación de prueba en la base local. La prueba de contacto se omite si la página Contacto no está publicada.

**En CI**, el trabajo «E2E» crea con `@wordpress/env` (Docker) un WordPress efímero con la última versión de WooCommerce y el plugin `frave-peru`, carga una tienda mínima con `tests/e2e/fixtures/seed.php` y ejecuta las pruebas en Chromium. Falla también si el tema o el plugin escriben errores o avisos en el registro de PHP. Si falla, descarga el artefacto `playwright-report` del run para ver capturas y trazas. Con Docker instalado se puede reproducir en local: `npm.cmd run env:start`, `npm.cmd run env:seed` y `$env:FRAVE_E2E_URL='http://localhost:8888'; npm.cmd run test:e2e`.

## Registro de cambios de la guía

| Fecha | Cambio |
| --- | --- |
| 2026-09-23 | Procedimiento inicial basado en Vite, Tailwind y el entorno local actual. |
| 2026-09-23 | Se documenta el origen de assets en desarrollo tras corregir las peticiones 404 de Lato. |
| 2026-09-28 | Se añade la generación de `languages/frave.pot` con WP-CLI. |
| 2026-10-05 | Se añaden PHPCS, ESLint y el flujo de GitHub Actions. |
| 2026-10-05 | Se añaden las pruebas de principio a fin con Playwright y el entorno efímero de CI con wp-env. |
