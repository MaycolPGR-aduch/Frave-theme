# Despliegue local de Frave desde cero

- **Estado:** guía para Windows basada en la instalación local actual.
- **Última revisión:** 23 de septiembre de 2026.
- **Alcance:** crear una instalación nueva para desarrollar y probar el tema; el despliegue a hosting está en [GUIA_DESPLIEGUE.MD](GUIA_DESPLIEGUE.MD).

## Antes de empezar

El [repositorio Frave-theme](https://github.com/MaycolPGR-aduch/Frave-theme) contiene el **tema**, sus assets compilados y sus campos ACF. No contiene WordPress, plugins, base de datos, productos, imágenes subidas ni usuarios. Por eso, una instalación nueva no tendrá automáticamente los productos y textos de prueba que se ven en la instalación local actual.

Las rutas y herramientas se contrastaron con la instalación local en funcionamiento. No se ejecutó una reinstalación limpia para conservar sus datos actuales; en un equipo nuevo hay que completar el checklist del paso 8.

Esta guía reproduce la estructura usada en este equipo:

| Elemento | Ruta o dirección actual |
| --- | --- |
| Código del tema | `D:\Frave-web\frave` |
| WordPress local | `D:\Frave-web\.local-wordpress\wordpress` |
| Tema dentro de WordPress | Enlace de carpeta desde `wp-content\themes\frave` al código del tema |
| PHP | `C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe` |
| MySQL | Laragon, puerto `3307` en este equipo |
| Web | `http://127.0.0.1:8080/` |
| Vite | `http://127.0.0.1:5173/` |

En otro equipo, adapta rutas, versión de PHP y puerto de MySQL. No crees una segunda instalación encima de una existente: si estas rutas ya contienen WordPress, continúa en [Arranque diario](#arranque-diario).

## 1. Instalar herramientas

Instala Git, Node.js 22.12 o superior y un entorno con PHP 8.3 o superior y MySQL 8.0 o superior. En Windows se utiliza [Laragon](https://laragon.org/download) para PHP y MySQL. Comprueba que las herramientas responden:

```powershell
git --version
node --version
npm.cmd --version
& 'C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe' --version
```

La ruta de PHP refleja **este equipo**; si Laragon instaló otra versión, sustituye esa ruta. Confirma que PHP tenga las extensiones necesarias para WordPress y WooCommerce antes de continuar. Inicia una instancia MySQL desde Laragon o con la configuración local que vayas a usar y anota su puerto; la instancia actual de Frave usa `3307`.

## 2. Obtener el tema y WordPress

En una carpeta vacía, clona el tema:

```powershell
New-Item -ItemType Directory -Path 'D:\Frave-web' -Force | Out-Null
git clone https://github.com/MaycolPGR-aduch/Frave-theme.git D:\Frave-web\frave
```

Si `D:\Frave-web\frave` ya existe, usa esa copia y **omite el clone**. Crea la carpeta para WordPress:

```powershell
New-Item -ItemType Directory -Path 'D:\Frave-web\.local-wordpress' -Force | Out-Null
```

Descarga WordPress desde su [sitio oficial](https://wordpress.org/download/) y descomprime el ZIP dentro de esa carpeta, de modo que el archivo `wp-admin\install.php` termine exactamente en:

```text
D:\Frave-web\.local-wordpress\wordpress\wp-admin\install.php
```

El ZIP de WordPress ya contiene una carpeta `wordpress`. Evita terminar con `wordpress\wordpress\`. La [guía oficial de instalación](https://developer.wordpress.org/advanced-administration/before-install/howto-install/) explica la instalación manual del CMS.

## 3. Crear la base de datos local

Con MySQL iniciado, usa el cliente de base de datos de Laragon o cualquier cliente MySQL para crear una base vacía. Por ejemplo:

```sql
CREATE DATABASE frave_local CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Usa una cuenta local con permisos sobre `frave_local`. Anota para el instalador de WordPress: nombre de la base, usuario, contraseña y host. En este equipo el host es `127.0.0.1:3307`; una instalación nueva de Laragon puede usar otro puerto. No guardes la contraseña en el tema ni en Git.

## 4. Vincular el tema a WordPress

La instalación actual usa un **junction** de Windows. Así, editar `D:\Frave-web\frave` modifica inmediatamente el tema que ve WordPress. Ejecuta esto solo si la ruta de destino todavía no existe:

```powershell
$fraveThemeLink = 'D:\Frave-web\.local-wordpress\wordpress\wp-content\themes\frave'
if (Test-Path -LiteralPath $fraveThemeLink) { throw 'El tema ya existe en WordPress; revisa la ruta antes de continuar.' }
New-Item -ItemType Junction -Path $fraveThemeLink -Target 'D:\Frave-web\frave'
```

Comprueba el enlace con `Get-Item $fraveThemeLink | Select-Object LinkType,Target`. También puedes copiar la carpeta del tema, pero entonces tendrás que volver a copiarla después de cada cambio; no incluyas `.git/` ni `node_modules/` en esa copia.

## 5. Instalar WordPress

En una terminal, inicia el servidor PHP local:

```powershell
& 'C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe' -S 127.0.0.1:8080 -t 'D:\Frave-web\.local-wordpress\wordpress'
```

Abre `http://127.0.0.1:8080/wp-admin/install.php`. Sigue el asistente, proporciona los datos de la base de datos del paso 3 y crea un usuario administrador y contraseña **nuevos para esta instalación**. WordPress intentará crear `wp-config.php`; si no puede escribirlo, sigue las instrucciones del asistente para crearlo manualmente. Ese archivo debe quedarse dentro de la instalación local y fuera del repositorio del tema.

Con este servidor PHP simple, selecciona **Ajustes → Enlaces permanentes → Simple**. Esta instalación de desarrollo usa URLs con parámetros; las URLs bonitas requieren configurar un servidor con reglas de reescritura.

## 6. Preparar y arrancar los assets

Desde la carpeta del tema, instala dependencias y levanta Vite:

```powershell
cd D:\Frave-web\frave
npm.cmd ci
npm.cmd run dev
```

Deja Vite abierto en una **segunda terminal** mientras trabajas. En el `wp-config.php` local, antes de la línea que carga `wp-settings.php`, añade:

```php
define( 'FRAVE_VITE_DEV', true );
define( 'FRAVE_VITE_SERVER', 'http://127.0.0.1:5173' );
```

Activa **Frave** en **Apariencia → Temas**. El servidor de PHP (`8080`), MySQL y Vite (`5173`) deben estar activos para ver los estilos y cambios en vivo. Para usar el build compilado sin Vite, ejecuta `npm.cmd run build` y cambia `FRAVE_VITE_DEV` a `false`; el tema cargará `assets/dist/manifest.json`. Consulta [GUIA_COMPILACION.MD](GUIA_COMPILACION.MD) para verificar el build.

## 7. Configurar la tienda mínima

1. Instala y activa [WooCommerce](https://wordpress.org/plugins/woocommerce/) desde **Plugins → Añadir nuevo**. Instala [Advanced Custom Fields](https://wordpress.org/plugins/advanced-custom-fields/) si quieres editar las secciones editoriales de la portada desde WordPress; el tema funciona sin ACF.
2. Crea una página **Inicio** y selecciónala en **Ajustes → Lectura → Una página estática → Portada**. El tema usará `front-page.php` para mostrar el hero.
3. Revisa que WooCommerce haya creado y asignado **Tienda**, **Carrito**, **Finalizar compra** y **Mi cuenta**. Para seguir el flujo clásico de este tema, las tres últimas páginas deben contener respectivamente los bloques de shortcode `[woocommerce_cart]`, `[woocommerce_checkout]` y `[woocommerce_my_account]`. Revisa las asignaciones de páginas en los ajustes de WooCommerce.
4. Asigna menús a **Navegación principal** y **Navegación del pie** desde **Apariencia → Menús**. Si aún no hay menú principal, el tema muestra enlaces básicos de respaldo.
5. Si activaste ACF, sincroniza el grupo **Inicio Frave** cuando ACF lo ofrezca y edita los campos del hero, presentación, guía y cierre en la página Inicio. La estructura del grupo está versionada en `acf-json/`.
6. Crea una página **Nosotros** con slug `nosotros` y escribe su contenido desde el editor. La portada mostrará entonces el enlace **Conoce Frave**. Añade esa página a los menús si deseas mostrarla en la navegación.
7. Para una prueba similar a la instalación actual, configura Perú y moneda PEN, una zona de envío con tarifa plana y **contra entrega solo de prueba**. Revisa las opciones de impuestos en WooCommerce; la instalación local actual los tiene desactivados para el flujo básico.
8. Crea al menos una categoría con un producto publicado y precio para que aparezca en la portada. Marca productos como destacados si quieres controlar esa sección; si no hay destacados, la portada muestra productos recientes. Crea además uno variable y uno agotado si vas a probar esos estados.

WooCommerce administra los productos y la compra. No es necesario copiar `single-product.php` ni toda la carpeta de plantillas del plugin al tema. Las páginas clásicas y sus shortcodes están descritos por [WooCommerce](https://woocommerce.com/document/woocommerce-shortcodes/page-shortcodes/).

## 8. Verificar la instalación

- [ ] `http://127.0.0.1:8080/` muestra la portada de Frave sin errores.
- [ ] El navegador carga estilos y JavaScript desde `127.0.0.1:5173` en modo desarrollo, sin errores 404.
- [ ] Tienda, categoría y producto simple se pueden abrir; el producto se añade al carrito.
- [ ] Producto variable y agotado muestran las opciones y estados esperados, si los creaste.
- [ ] Carrito y checkout clásico muestran el envío y el método de pago de prueba.
- [ ] La página Mi cuenta se abre y los menús funcionan en móvil y escritorio.

Este checklist verifica el entorno **local**. La pasarela real, las tarifas, los impuestos y las políticas comerciales se definen antes de vender en producción.

## Arranque diario

Cuando la instalación ya existe, no repitas la descarga ni el asistente. Inicia la instancia MySQL de Frave en el puerto `3307` con tu configuración local y abre dos terminales:

```powershell
& 'C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe' -S 127.0.0.1:8080 -t 'D:\Frave-web\.local-wordpress\wordpress'
```

```powershell
cd D:\Frave-web\frave
npm.cmd run dev
```

Abre `http://127.0.0.1:8080/` para ver la web y `http://127.0.0.1:8080/wp-admin/` para editarla.

## Problemas frecuentes

| Síntoma | Revisión inicial |
| --- | --- |
| Error al conectar con la base de datos | MySQL activo, puerto correcto y datos de `wp-config.php`. |
| Página sin estilos | Vite activo en `5173` si `FRAVE_VITE_DEV` es `true`; si es `false`, manifest y `assets/dist/` presentes. |
| Productos o secciones vacías | Los datos y medios viven en la base de datos y `wp-content/uploads/`, no en Git. Crea o importa contenido de prueba. |
| URLs de producto con 404 | Usa enlaces permanentes **Simple** con este servidor PHP o configura reglas de reescritura en otro servidor. |
| No aparecen campos del hero | Activa ACF, revisa la sincronización de `acf-json/` y confirma que Inicio es la portada estática. |
| El puerto `8080` o `5173` está ocupado | Comprueba si el servidor ya está abierto antes de iniciar otro; Vite usa el puerto `5173` de forma estricta. |

Para reproducir **exactamente** el contenido de otra instalación se necesita también una exportación de su base de datos y de `wp-content/uploads/`, con ajuste de URLs al nuevo entorno. Esos datos no forman parte del repositorio y no se deben recrear copiando archivos internos de MySQL.

## Registro de cambios

| Fecha | Cambio |
| --- | --- |
| 2026-09-23 | Guía inicial para reconstruir el entorno local Frave en Windows. |
