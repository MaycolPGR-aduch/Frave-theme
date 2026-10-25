# Guía del catálogo de Frave

Cómo preparar y cargar los productos de frave.pe. El catálogo funciona en **modo catálogo**: sin precios ni compra en línea, con «Solicitar cotización» en cada ficha. Todo lo que se carga sirve también para la futura tienda.

## Resumen del proceso

1. **Una vez por sitio:** crear las categorías y los atributos (comandos más abajo).
2. **Fotos:** prepararlas con la especificación de esta guía y subirlas a **Medios**.
3. **Plantilla:** completar la hoja «Importar a Woo» de `PLANTILLA_IMPORTACION_WOOCOMMERCE.xlsx` y guardarla como **CSV UTF-8**.
4. **Prueba:** importar dos o tres productos en borrador (`Published = -1`) y revisarlos.
5. **Importación:** importar el resto en **Productos → Importar**. Las columnas se asignan solas.
6. **Publicación:** cuando el catálogo esté listo, poner la tienda en **Visible** (WooCommerce → Ajustes → Visibilidad del sitio) y vaciar la caché.

## Categorías

Las categorías siguen el árbol acordado con la gerencia (octubre de 2026). `bin/crear-lineas.php` las crea con sus descripciones; en la plantilla, la hoja **Categorías** tiene la ruta exacta de cada una.

| Línea | Subcategorías (y tipos) |
| --- | --- |
| Sabores | Bebidas y productos lácteos · Confitería y panadería · Salados · Cuidado bucal |
| Fragancias | Perfumería (de diseñador, árabe, exclusiva o nicho) · Fragancias cosméticas (cuidado del cabello, de la piel, de mascotas) · Fragancias para el cuidado del hogar (cuidado textil, de superficies, cocina y lavavajillas) · Ambientación (difusores de varitas, vehículos, aerosoles y sprays, marketing olfativo) |
| Envases | Frascos de vidrio para perfumería · Frascos para difusores · Válvulas |

Asigna cada producto a la categoría **más específica** que le corresponda (por ejemplo, `Fragancias > Perfumería > Perfumería árabe`): las páginas de línea, las soluciones por industria y los filtros suben solos por el árbol. Las etiquetas y los «Ver productos» de las páginas de línea solo enlazan cuando su categoría tiene productos, así que el sitio nunca lleva a una categoría vacía.

## Atributos

Los atributos **globales** son los que aparecen en los filtros del catálogo y como datos en la ficha («Familia olfativa: Oriental · Amaderada»). `bin/crear-atributos.php` los crea con valores sugeridos; la importación añade cualquier otro valor que encuentre.

| Atributo | Línea | Valores sugeridos |
| --- | --- | --- |
| Presentación | Todas | Formatos de venta: 250 g, 500 g, 1 kg, 5 kg… |
| Familia olfativa | Fragancias | Cítrica, Floral, Frutal, Amaderada, Oriental, Aromática, Gourmand, Fresca, Especiada |
| Perfil de sabor | Sabores | Frutal, Cítrico, Lácteo, Dulce, Tostado, Especiado, Salado, Mentolado |
| Aplicación | Fragancias y sabores | Perfumería fina, Body splash, Cremas y lociones, Jabones, Shampoo y acondicionador, Detergentes y suavizantes, Limpiadores, Velas, Difusores, Ambientadores, Bebidas, Lácteos, Confitería, Panadería, Snacks, Cuidado bucal |
| Solubilidad | Fragancias y sabores | Hidrosoluble, Liposoluble |
| Capacidad | Envases | 30 ml, 50 ml, 100 ml, 120 ml… |
| Material | Envases | Vidrio, PET, Polipropileno, Aluminio |
| Colección | Fragancias | Shampoo y acondicionador, Mascarillas y cremas de peinar, Romero, Pitahaya e Hibiscus, Mascotas, Suavizantes y microcápsulas, Detergentes, Limpiadores de piso, Aromas para ambientación (cada una con su subcategoría; ver README → Colecciones) |
| Cierre | Envases | Válvula atomizadora, Bomba dosificadora, Tapa rosca, Tapa a presión, Gotero |

Reglas:

- Escribe los valores **siempre igual** (mismas mayúsculas y unidades): `250 g`, no `250g` ni `250 G`. Cada variante distinta sería otro valor en los filtros.
- **Presentación** y **Capacidad** se ordenan solas de menor a mayor (250 g, 500 g, 1 kg) si el valor empieza con la cantidad y una unidad conocida (mg, g, kg, ml, cc, l, oz).
- Los filtros siguen este orden: colección, familia olfativa o perfil de sabor, aplicación, presentación, solubilidad, capacidad, material y cierre. Cada sección aparece solo si algún producto de la vista usa ese atributo.
- Para añadir un atributo nuevo, créalo en **Productos → Atributos** antes de importar. Un atributo solo informativo puede ocultarse de los filtros con el filtro de PHP `frave_catalog_filter_attributes`.

## Fotos de producto

Para que el catálogo se vea uniforme con el resto del sitio:

| | Especificación |
| --- | --- |
| Formato | Cuadrado (1:1), mínimo **1200 × 1200 px**. |
| Fondo | Marfil liso y cálido (similar a `#F3ECE2`), como las portadas de las líneas, o blanco roto. Sin texturas fuertes ni degradados oscuros. |
| Encuadre | Producto centrado, ocupando cerca del **70 %** del alto, con aire alrededor: las tarjetas recortan un poco los bordes. |
| Luz | Suave, desde arriba a la izquierda, con sombra natural. |
| Contenido | Sin texto, precios, logotipos ni marcas de agua encima. Para sabores y fragancias, el envase real del producto o un elemento sensorial coherente (fruta, flor) si aún no hay envase. |
| Archivo | JPG o WebP, calidad 80, **menos de 300 KB**. Nombre = SKU (`FRA-ARA-001.jpg`). |
| Texto alternativo | El nombre del producto (se completa en Medios). |

Sube las fotos a **Medios → Añadir nuevo**, copia la URL de cada una (botón «Copiar URL al portapapeles») y pégala en la columna `Images`. Varias fotos se separan con comas; la primera es la principal.

## Plantilla de importación

`PLANTILLA_IMPORTACION_WOOCOMMERCE.xlsx` tiene cinco hojas:

- **Importar a Woo:** la única que se completa. La primera fila tiene los encabezados del importador nativo de WooCommerce; no los cambies ni agregues filas encima. Tiene listas desplegables para tipo, publicado, categorías y atributos.
- **Ejemplos:** diez productos de muestra de las tres líneas, incluido un producto variable con sus variaciones, como referencia.
- **Categorías:** las rutas válidas para la columna `Categories`.
- **Atributos:** los nombres exactos y los valores sugeridos.
- **Instrucciones:** qué escribir en cada columna, con ejemplos.

Columnas principales:

| Columna | Qué escribir |
| --- | --- |
| `Type` | `simple`; o `variable` para un producto con opciones (p. ej. capacidades) y `variation` para cada opción, en filas debajo de su padre. |
| `SKU` | Código único y estable; las variaciones, el del padre con un sufijo (`ENV-VID-001-30`). |
| `Name` | Nombre comercial (vacío en las variaciones). |
| `Published` | `1` publicado, `0` privado, `-1` borrador. |
| `Is featured?` | `1` para mostrarlo en «Productos destacados» de la portada y de su línea. |
| `Short description` | Dos o tres líneas: se ven junto al título, en la vista previa al compartir y como descripción para buscadores. |
| `Description` | Detalle: notas, uso recomendado, dosis sugerida. Se muestra en «Detalles del producto». |
| `Regular price` | Opcional, en soles sin símbolo. En modo catálogo no se muestra. |
| `Categories` | Ruta de la hoja Categorías. |
| `Images` | URL de las fotos subidas a Medios. |
| `Attribute N name / value(s) / visible / global` | Hasta cuatro atributos: nombre exacto, valores separados por comas, `1`, `1`. En productos variables, `Attribute N default` indica la opción inicial. |

Guarda la hoja como **CSV UTF-8** (Archivo → Guardar como → CSV UTF-8). Los archivos de Excel no se importan directamente.

## Importar en WooCommerce

1. **Productos → Importar → Elegir archivo** con el CSV y **Continuar**.
2. La pantalla de asignación reconoce todas las columnas (Tipo, SKU, Nombre, Categorías, Imágenes, atributos…). Revisa que ninguna quede en «No importar» y pulsa **Ejecutar el importador**.
3. Para corregir datos, vuelve a importar el CSV marcando **Actualizar productos existentes**: los productos se identifican por SKU.

Las fotos se descargan desde sus URL durante la importación; con muchas fotos, importa por tandas de unas 50 filas.

## Comandos (una vez por sitio)

Desde la carpeta del sitio, con el clon del tema en `~/repos/frave-theme`:

```bash
wp eval-file ~/repos/frave-theme/bin/crear-lineas.php
wp eval-file ~/repos/frave-theme/bin/crear-atributos.php
wp eval-file ~/repos/frave-theme/bin/crear-colecciones.php
wp rewrite flush
```

Todos se pueden repetir sin cambiar lo que ya existe. Después de una importación grande, vuelve a ejecutar `crear-atributos.php` para ordenar las presentaciones y capacidades que se hayan creado.

## Prueba local

`bin/muestra-catalogo.csv` tiene diez productos `[MUESTRA]` (con imágenes de las líneas) para probar la importación y el catálogo en local. **No lo importes en producción.** `python bin/plantilla-catalogo.py` (con `openpyxl`) regenera la plantilla y la muestra si cambian las categorías o los atributos.

## Datos pendientes de Frave

- Listado de productos: nombre comercial, SKU, línea y subcategoría.
- Presentaciones o capacidades de cada producto.
- Atributos que se informarán (familia olfativa o perfil, aplicación, solubilidad…) y validación de los valores sugeridos.
- Descripción corta y detalle de cada producto.
- Fotos con la especificación de esta guía.
- Productos destacados para la portada y cada línea.
