# Propuesta de catálogo de Frave

Estado: borrador para validar con el inventario real. La base local usa cuatro categorías y cinco productos de demostración; no representan el catálogo final. Solo en local existen los atributos globales de prueba «Familia olfativa» y «Presentación», creados por `seed-catalog-filters.php` para revisar los filtros; en producción no se han creado atributos ni aplicado una taxonomía.

## Categorías candidatas

La navegación debe seguir la forma en que los clientes buscan y compran. Conviene mantener pocas categorías de primer nivel y usar subcategorías solo cuando exista suficiente variedad.

| Categoría candidata | Estado | Nota |
| --- | --- | --- |
| Fragancias terminadas | Por validar | Confirmar qué productos terminados vende Frave y cómo los clasifica. |
| Esencias | Por validar | Confirmar si el catálogo distingue esencias de fragancias terminadas y cómo las nombra comercialmente. |
| Insumos para perfumería | Por validar | Posibles subcategorías: bases, fijadores y materias primas aromáticas, solo si el inventario lo justifica. |
| Envases y accesorios | Por validar | El material de marca menciona envases; confirmar disponibilidad y tipos antes de crear la categoría. |
| Kits y herramientas | Condicional | Crear únicamente si Frave comercializa estos productos. |

No crear categorías por género, familia olfativa o presentación si funcionan mejor como filtros o atributos. Evitar categorías vacías, nombres duplicados y jerarquías profundas. Confirmar el vocabulario con el equipo comercial antes de cargar productos.

## Atributos candidatos

Los atributos globales de WooCommerce deben ser reutilizables y consistentes en todo el catálogo. Se crearán solo después de revisar productos y valores reales.

| Atributo candidato | Uso posible | Consideración |
| --- | --- | --- |
| Presentación | Variaciones por volumen, peso o unidad | Acordar unidades y formatos de escritura; por ejemplo, no mezclar `30 ml`, `30ML` y `0.03 L`. |
| Familia olfativa | Información o filtro de esencias y fragancias | Usar los valores confirmados por proveedores o por Frave. |
| Concentración | Información de fragancias terminadas | Aplicar solo cuando el dato exista y tenga un significado comercial confirmado. |
| Uso o aplicación | Segmentar insumos | Incorporarlo solo si ayuda a encontrar productos y sus valores están definidos. |

Para variaciones, cada producto padre debe declarar todos los valores disponibles. Cada fila de variación usa un único valor y referencia el SKU del padre. El nombre y valor del atributo deben coincidir exactamente. Un atributo global usa `Attribute N global = 1` cuando ya existe en WooCommerce; uno local usa `0`.

## Filtros del catálogo

La tienda, las categorías y la búsqueda de productos muestran un panel de filtros (barra lateral en escritorio y botón «Filtrar» con panel lateral en móvil y tablet). Casi todo aparece solo a partir de los datos de WooCommerce:

| Filtro | De dónde sale | Qué hace falta |
| --- | --- | --- |
| Categorías | Categorías de producto | Nada: muestra las de primer nivel y despliega las subcategorías de la categoría abierta. Se ordenan como en **Productos → Categorías** (arrastrando). |
| Precio | Precios de los productos | Nada: el rango sugerido se calcula con los productos de la vista actual. |
| Solo disponibles | Estado de inventario | Se oculta si WooCommerce ya esconde los agotados (**Ajustes → Productos → Inventario**). |
| En oferta | Precio rebajado | Aparece cuando algún producto tiene precio rebajado vigente. |
| Un filtro por atributo | **Atributos globales** (**Productos → Atributos**) | Crear el atributo global, sus valores y asignarlos a los productos. Cada atributo aparece como una sección con casillas y recuentos cuando algún producto de la vista lo usa. |
| Valoración | Reseñas aprobadas | Aparece cuando hay productos valorados. |

Reglas para que los atributos funcionen como filtro:

- Solo los **atributos globales** se pueden filtrar. Los atributos «personalizados» creados dentro de un producto (como el `Volumen` del producto variable de muestra) no aparecen en los filtros.
- El orden de los valores se define en el atributo (orden personalizado arrastrando los términos). El orden de las secciones sigue el de **Productos → Atributos**.
- Dentro de un mismo atributo, marcar varios valores muestra productos con **cualquiera** de ellos (p. ej., Cítrica o Floral). Entre atributos distintos se combinan (Cítrica **y** 50 ml).
- Un atributo que solo sea informativo puede ocultarse de los filtros con el filtro de PHP `frave_catalog_filter_attributes`.

Las URL usan los parámetros propios de WooCommerce (`filter_{atributo}`, `min_price`, `max_price`, `rating_filter`, `orderby`), más `filter_stock_status=instock` y `on_sale=1`. Las páginas filtradas se marcan `noindex, follow` para que los buscadores no indexen cada combinación.

Para probar los filtros en local, `D:/Frave-web/.local-wordpress/seed-catalog-filters.php` crea los atributos candidatos «Familia olfativa» y «Presentación», una subcategoría «Esencias cítricas», una oferta y una reseña, todo sobre los productos `[MUESTRA]`. No lo ejecutes en producción.

## Datos que pertenecen a WooCommerce

Precio, precio promocional, SKU, inventario, dimensiones, imágenes, atributos y variaciones se administran en WooCommerce. No duplicarlos en ACF. Mantener SKU únicos y estables, incluso entre variaciones.

La importación nativa espera `Type` como `simple`, `variable` o `variation` según la fila. Las filas del padre deben preceder a sus variaciones. Usa `Parent` con el SKU del producto padre. Para stock, una cantidad numérica activa su gestión; dejarla vacía desactiva la gestión de inventario para esa fila.

## Uso de la plantilla

Abre `PLANTILLA_IMPORTACION_WOOCOMMERCE.xlsx` y completa la hoja **Importar a Woo**. La primera fila conserva los encabezados del importador nativo; no agregues títulos ni notas encima. Exporta solo esa hoja como CSV UTF-8. Los archivos de Excel no se importan directamente con el importador nativo de WooCommerce.

Las columnas de dimensiones se prepararon como kg y cm. Si la tienda de destino usa otras unidades, ajusta el texto del encabezado entre paréntesis para que coincida con sus unidades de WooCommerce. Escribe precios como números sin símbolo de moneda, usando la moneda configurada en la tienda. Las imágenes deben estar previamente cargadas o tener URLs directamente accesibles. Usa `1` y `0` para valores booleanos. Separa varios valores con comas, excepto cuando una coma forme parte de un nombre; revisa el escapado antes de importar.

Primero prueba un producto en borrador (`Published = -1`) y, si hay variaciones, importa un producto padre con una o dos variaciones en la instalación local. Revisa el mapeo, la ficha, las imágenes, los precios y el inventario antes de procesar el resto del catálogo.

Consulta el [esquema oficial del importador CSV de WooCommerce](https://woocommerce.com/document/product-csv-importer-exporter/) antes de futuras importaciones; el mapeo puede variar con las versiones y extensiones activas.

## Datos pendientes de Frave

- Inventario aprobado con nombres comerciales y SKU.
- Clasificación de productos terminados, esencias, insumos y envases.
- Unidades y presentaciones que se venderán.
- Familias olfativas, concentraciones y otros atributos que realmente se informarán.
- Precios, promociones, stock y política para productos agotados.
- Fotografías propias y texto alternativo de cada producto.
- Categorías que deben aparecer en la navegación principal.
