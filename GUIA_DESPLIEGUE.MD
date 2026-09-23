# Guía de despliegue de Frave

- **Estado:** guía base; falta seleccionar y configurar el hosting.
- **Última revisión:** 23 de septiembre de 2026.
- **Compilación:** [GUIA_COMPILACION.MD](GUIA_COMPILACION.MD).

## Alcance del despliegue

Este repositorio contiene el **tema** `frave`, no una instalación completa de WordPress. El destino del tema en cualquier instalación estándar es `wp-content/themes/frave/`. El código, `assets/dist/`, `acf-json/`, `theme.json`, `style.css` y el logotipo deben llegar juntos. WordPress, WooCommerce, ACF opcional, la base de datos, los medios y `wp-config.php` se administran por separado.

La fuente versionada actual es la rama `main` de [Frave-theme](https://github.com/MaycolPGR-aduch/Frave-theme). Cada entrega debe identificar el commit desplegado para facilitar la reversión.

La instalación actual en `D:/Frave-web/.local-wordpress/wordpress/` es de desarrollo. Usa PEN, envío plano y contra entrega de prueba, con impuestos desactivados. Esa configuración no define las condiciones de una tienda real.

## Antes de publicar en staging

1. Confirma que el servidor cumple los requisitos de WordPress, PHP, base de datos y WooCommerce indicados en [README.md](README.md). El proveedor y sus versiones disponibles siguen pendientes de confirmar.
2. Prepara un sitio de staging con HTTPS, WordPress, WooCommerce y, si se editará el hero, ACF gratuito.
3. Haz una copia de seguridad recuperable del sitio y de la base de datos antes de reemplazar un tema existente. Guarda una copia del tema anterior si aplica.
4. Ejecuta el build descrito en [GUIA_COMPILACION.MD](GUIA_COMPILACION.MD) y confirma que `assets/dist/manifest.json` y sus archivos con hash están en el mismo commit.
5. Define en el `wp-config.php` del destino `FRAVE_VITE_DEV` como `false` o deja la constante sin definir. El servidor Vite local no se usa en staging ni en producción.

## Entrega del tema

El método concreto de entrega (Git, archivo ZIP, SFTP o herramienta del proveedor) se definirá cuando se conozca el hosting. En todos los casos, copia la carpeta `frave` completa dentro de `wp-content/themes/`, con la estructura interna intacta. Excluye `.git/`, `node_modules/`, archivos `.env`, configuraciones locales y recursos originales ajenos al tema. No ejecutes `npm install` en el servidor si los assets compilados ya están incluidos.

Tras copiar los archivos, activa **Frave** en **Apariencia → Temas**. Si el tema ya estaba activo, limpia la caché del sitio o CDN según el proveedor y verifica que el navegador solicita las rutas nuevas del manifest. Una ausencia del manifest deja la página sin los estilos y scripts del tema; el administrador recibe un aviso cuando está en modo producción.

## Configuración en WordPress

- Asigna una página estática como portada y los menús **Navegación principal** y **Navegación del pie**.
- Instala y activa WooCommerce. Configura las páginas de carrito y checkout con `[woocommerce_cart]` y `[woocommerce_checkout]` si fueron creadas como bloques. La cuenta usa `[woocommerce_my_account]`.
- Si ACF está activo, sincroniza el grupo `Inicio Frave` desde `acf-json/` cuando el administrador muestre la opción; edita el hero en la portada. Sin ACF, el tema muestra contenido de respaldo.
- Carga categorías, productos, imágenes, precios, stock, atributos y variaciones en WooCommerce. Los datos locales de prueba no se copian automáticamente al nuevo sitio.
- Configura moneda, zonas y tarifas de envío, impuestos, cupones, correos y métodos de pago según las decisiones comerciales aprobadas.

## Validación en staging

- [ ] La portada, categorías, búsqueda, ordenamiento y ficha de producto cargan sin errores PHP o JavaScript.
- [ ] No hay errores 404 de CSS, JS, fuentes ni imágenes; el manifest apunta a los archivos entregados.
- [ ] Menú, búsqueda y carrito funcionan en móvil y escritorio; el contador del header refleja los cambios.
- [ ] Producto simple, variable y agotado muestran los estados correctos.
- [ ] Carrito, cupón, envío, checkout de prueba, confirmación, correo y cuenta completan el flujo esperado.
- [ ] Se revisan foco, teclado, contraste, imágenes responsive y rendimiento con contenido representativo.
- [ ] HTTPS, caché, copias de seguridad, registros y actualizaciones funcionan en el entorno elegido.

## Paso a producción y reversión

La publicación de ventas reales requiere pasarela configurada, impuestos, tarifas, políticas comerciales y pruebas de pedidos aprobados y rechazados. Antes de activar campañas o recibir pedidos, realiza un pedido real controlado de bajo importe y revisa el estado del pedido, inventario, email, cobro y reembolso.

Si una versión del tema falla, conserva el commit anterior y su `assets/dist/` para restaurar **código y assets juntos**. No sustituyas una base de datos de producción activa por una copia anterior sin un plan que preserve los pedidos nuevos. Si el fallo involucra plugins o esquema de datos, evalúa la reversión con la copia de seguridad del sitio completo.

## Datos que faltan cuando se elija hosting

| Decisión pendiente | Información a registrar aquí |
| --- | --- |
| Proveedor y entorno | Panel, versión de PHP y base de datos, límites y ubicación del sitio. |
| Método de entrega | Git, ZIP, SFTP o pipeline; ubicación exacta y responsable del despliegue. |
| Dominio y HTTPS | Dominio final, certificados, redirecciones y entorno staging. |
| Operación | Backups, recuperación, logs, correo transaccional, caché y tareas programadas. |
| Comercio | Pasarela, moneda, impuestos, envíos y políticas aprobadas para Perú. |
| Verificación | Checklist y fecha de salida a producción, resultado del pedido real controlado. |

Actualiza esta sección con rutas y comandos específicos solo después de confirmar el proveedor. No registres contraseñas, claves de pago ni datos de clientes en este repositorio.

## Registro de cambios de la guía

| Fecha | Cambio |
| --- | --- |
| 2026-09-23 | Guía inicial independiente del proveedor; configuración del hosting pendiente. |
