# Guía de compilación de Frave

- **Estado:** procedimiento local actual.
- **Última revisión:** 23 de septiembre de 2026.
- **Configuración de producción:** [GUIA_DESPLIEGUE.MD](GUIA_DESPLIEGUE.MD).

## Qué se compila

Vite toma `assets/src/js/app.js` como entrada. Ese archivo importa Lato, `assets/src/css/app.css` y el módulo del menú móvil. Tailwind CSS 4 procesa el CSS con detección automática desactivada y examina solo los templates PHP y archivos JS indicados por `@source`; los Markdown no alteran las clases generadas. El resultado se escribe en `assets/dist/`: CSS, JavaScript y fuentes con hash, más `manifest.json`. WordPress lee el manifest desde `inc/enqueue.php`.

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

4. Abre `http://127.0.0.1:8080`. El servidor Vite escucha en `127.0.0.1:5173`; el puerto es fijo y se permite el origen local configurado en `vite.config.js`.

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

## Comprobación antes de entregar

- [ ] `npm.cmd run build` termina sin errores y el manifest apunta a archivos existentes.
- [ ] Con `FRAVE_VITE_DEV` en `false` o sin definir, portada, tienda, producto, carrito y checkout cargan CSS y JS sin errores 404.
- [ ] Los PHP modificados pasan `php -l` usando el ejecutable de PHP del entorno; la activación del tema no genera errores.
- [ ] El menú móvil y el contador del carrito funcionan tras el cambio.
- [ ] `assets/dist/` queda incluido en el commit y `node_modules/` permanece fuera de Git.

El proyecto aún no tiene un script de lint o una suite de pruebas automatizadas. Estas comprobaciones se realizan con PHP, build y recorridos funcionales; las ampliaciones se registran en [ROADMAP.md](ROADMAP.md).

## Registro de cambios de la guía

| Fecha | Cambio |
| --- | --- |
| 2026-09-23 | Procedimiento inicial basado en Vite, Tailwind y el entorno local actual. |
