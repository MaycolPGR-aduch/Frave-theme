# Frave WordPress Theme

Tema clásico personalizado para Frave, una tienda de fragancias, esencias e insumos de perfumería. WordPress administra páginas y medios; WooCommerce conserva el catálogo, inventario, variaciones, precios, carrito, checkout, pagos, pedidos y cuentas. La apariencia se implementa en este tema.

## Documentación del proyecto

- [ROADMAP.md](ROADMAP.md): tareas implementadas y siguientes fases.
- [DESIGN.MD](DESIGN.MD): identidad visual, tokens, componentes y decisiones de diseño.
- [ARQUITECTURA.MD](ARQUITECTURA.MD): límites del sistema, módulos e integraciones.
- [GUIA_COMPILACION.MD](GUIA_COMPILACION.MD): desarrollo local, build y validación de assets.
- [GUIA_DESPLIEGUE_LOCAL.MD](GUIA_DESPLIEGUE_LOCAL.MD): instalación local de WordPress y Frave desde cero en Windows.
- [GUIA_DESPLIEGUE.MD](GUIA_DESPLIEGUE.MD): entrega del tema y datos por completar al elegir hosting.

## Requisitos

- WordPress 6.9 o superior (validado con 7.1.2).
- PHP 8.3 o superior (validado con 8.3.30).
- WooCommerce 10.8 o superior (flujo validado con 11.1.2).
- Node.js 22.12 o superior y npm.
- MySQL 8.0+ o MariaDB 10.6+.
- Advanced Custom Fields gratuito es opcional (validado con 6.8.10); sin ACF, la portada utiliza contenido de respaldo.

La recomendación sigue las versiones actuales de WooCommerce y Vite. Comprueba los requisitos del hosting antes de desplegar.

## Instalación del tema

1. Copia la carpeta `frave` a `wp-content/themes/`.
2. En WordPress, activa **Frave** en **Apariencia → Temas**.
3. Instala y activa WooCommerce. ACF gratuito es opcional.
4. Asigna una página estática como portada y crea un menú para las ubicaciones **Navegación principal** y **Navegación del pie**.
5. Completa los datos de tienda, moneda, envíos, impuestos y pagos en los ajustes de WooCommerce.
6. En las páginas de carrito y checkout, selecciona las versiones clásicas de WooCommerce con `[woocommerce_cart]` y `[woocommerce_checkout]` si el asistente creó bloques. La cuenta usa `[woocommerce_my_account]`.

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

El entorno de desarrollo usa PHP 8.3 de Laragon y tiene WooCommerce y ACF Free instalados. La cuenta local de WordPress es `fraveadmin`; la contraseña de desarrollo se guarda fuera del tema en `D:/Frave-web/.local-wordpress/local-admin-password.txt`. Para volver a iniciar el servidor PHP desde PowerShell:

```powershell
& 'C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe' -S 127.0.0.1:8080 -t 'D:\Frave-web\.local-wordpress\wordpress'
```

Desde `D:/Frave-web/frave/`, inicia Vite en otra terminal:

```powershell
npm.cmd run dev
```

El `wp-config.php` local activa `FRAVE_VITE_DEV` y apunta a `127.0.0.1:5173`. MySQL se ejecuta localmente en el puerto `3307`. La cuenta de administrador y sus credenciales de prueba son solo para este entorno; la contraseña se conserva en un archivo local fuera del tema.

La tienda local está configurada en Perú, con moneda PEN, envío plano de prueba y pago contra entrega de prueba. Los impuestos están desactivados para validar el flujo básico. WordPress usa traducción `es_ES` porque no había paquete de idioma `es_PE` disponible para WooCommerce en la instalación inicial. Esta configuración es solo para pruebas y no está lista para recibir pagos reales.

Para probar el build de producción, compila con `npm.cmd run build` y cambia `FRAVE_VITE_DEV` a `false` en el `wp-config.php` local.

## Arquitectura

- `inc/`: configuración inicial, helpers, assets, ACF opcional e integración WooCommerce.
- `template-parts/`: hero, categorías, cards, búsqueda y estados vacíos reutilizables.
- `assets/src/`: CSS, JavaScript y sistema de diseño de Tailwind.
- `assets/dist/`: archivos listos para producción generados por Vite y versionados.
- `acf-json/`: grupo de campos de portada sincronizable con ACF gratuito.
- `woocommerce/`: no contiene overrides por ahora; los wrappers se integran con hooks públicos.

## WooCommerce

Las imágenes, variaciones, precios y existencias se administran en WooCommerce. El tema define estilos y wrappers de presentación mediante soporte y hooks públicos. Si un override de plantilla resulta imprescindible en el futuro, registra en este README su motivo y la versión de plantilla de WooCommerce que cubre.

Para las pruebas locales se cargaron productos simples, variables y agotados, un cupón de prueba, una zona de envío peruana de tarifa plana y un método manual de contra entrega. No introduzcas credenciales de pasarela real en el entorno de desarrollo. Antes de vender, configura y valida una pasarela real, impuestos, tarifas y políticas comerciales.
