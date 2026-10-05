# Estructura propuesta para contenido institucional

Este documento organiza el contenido antes de construir páginas definitivas. Los textos entre corchetes son datos pendientes, no copy publicable.

## Nosotros

1. Presentación breve de Frave y a quién atiende.
2. Qué comercializa: fragancias, esencias, insumos y otras líneas confirmadas.
3. Enfoque y experiencia de la empresa, con afirmaciones verificables.
4. Fotografía o imagen institucional propia.
5. Enlace al catálogo y canal de contacto.

Pendiente: historia, cobertura, experiencia, propuesta de valor y material visual aprobado.

## Contacto

1. Canales oficiales: WhatsApp, correo y/o teléfono.
2. Horario de atención y días disponibles.
3. Dirección o zona de cobertura si se decide publicarla.
4. Formulario con nombre, correo, asunto y mensaje, más estado de envío y protección contra spam.
5. Enlaces a políticas de privacidad y tratamiento de datos.

Pendiente: confirmar los canales, horarios, datos legales y responsable de atender mensajes. No publicar teléfonos, direcciones ni correos de prueba.

## Preguntas frecuentes

Organizar respuestas cortas por tema:

- productos y presentaciones;
- pedidos y medios de pago;
- envíos, cobertura y tiempos;
- cambios, devoluciones y productos abiertos;
- asesoría para elegir esencias o insumos.

Pendiente: definir respuestas junto con las políticas operativas reales para evitar prometer condiciones no aprobadas.

## Políticas comerciales

Publicar páginas separadas para privacidad, términos y condiciones, envíos y cambios/devoluciones. Usar el texto legal revisado por Frave y alineado con su operación en Perú. El tema puede presentar estas páginas, pero no redacta asesoría legal ni fija políticas por defecto.

## Implementación (2026-09-28)

Las plantillas y la estructura ya existen; el contenido real sigue pendiente de Frave. En la instalación local, `seed-institutional-pages.php` crea las seis páginas **en borrador** con esta estructura y marcadores `[PENDIENTE: …]`.

| Página | Plantilla (Atributos de página) | Estructura inicial (Patrones → Frave: páginas institucionales) |
| --- | --- | --- |
| Contacto | **Contacto**: canales del Personalizador y formulario del plugin Frave Perú | Párrafo de introducción |
| Preguntas frecuentes | **Preguntas frecuentes**: índice de grupos, acordeones y ayuda al final | Preguntas frecuentes (estructura) |
| Términos y condiciones | **Política o texto legal** | Términos y condiciones (estructura) |
| Política de privacidad | **Política o texto legal** | Política de privacidad (estructura), Ley 29733 |
| Política de envíos | **Política o texto legal** | Política de envíos (estructura) |
| Cambios y devoluciones | **Política o texto legal** | Cambios y devoluciones (estructura) |

### Cómo editar

- **Canales de contacto:** Apariencia → Personalizar → **Contacto** (WhatsApp, correo, teléfono, horario, dirección o cobertura). Lo que quede vacío no se muestra.
- **Formulario de contacto:** lo añade la plantilla Contacto. Los mensajes quedan en el administrador, en **Mensajes**, y se envían al correo configurado en **Mensajes → Ajustes**.
- **Preguntas frecuentes:** cada grupo es un título de nivel 2 y cada pregunta un bloque **Detalles** (pregunta en el resumen, respuesta dentro). El índice y los datos estructurados `FAQPage` se generan solos; las respuestas que aún contengan `[PENDIENTE` se excluyen de esos datos.
- **Textos legales:** cada título de nivel 2 es una sección del índice «En esta página». La fecha de «Última actualización» es la de la última edición de la página. Al pie aparecen enlaces a las demás políticas publicadas.
- **Enlaces legales del pie:** muestran automáticamente, por orden de página, la política de privacidad (Ajustes → Privacidad), los términos (WooCommerce → Ajustes → Avanzado), las páginas con la plantilla de texto legal y el Libro de Reclamaciones. Para elegirlos a mano, asigna un menú a **Enlaces legales del pie**.

### Antes de publicar cada página

1. Sustituir todos los `[PENDIENTE: …]` por el texto aprobado; en las políticas, revisado por la asesoría legal.
2. Publicar la página. Hasta entonces no aparece en el pie ni en «Otras políticas».
3. Términos y condiciones: al publicarla, WooCommerce muestra la casilla «He leído y acepto los términos» en el checkout. Mientras esté en borrador, el plugin Frave Perú oculta esa casilla para no enlazar a una página inexistente.
4. Política de privacidad: al publicarla, los formularios (checkout, contacto, Libro de Reclamaciones) enlazan a ella en su casilla de consentimiento.

## Datos estructurados y edición

Las páginas institucionales se gestionan como páginas normales de WordPress con bloques básicos (títulos, párrafos, listas y Detalles). Los datos globales de contacto viven en el Personalizador porque se reutilizan en varias plantillas. No habilitar un constructor libre ni copiar datos de producto en ACF.
