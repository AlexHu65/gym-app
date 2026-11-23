# GRT-046 — Catálogo PPL bajo demanda (sustituye el manifiesto)

**Actualizado:** 13 de noviembre de 2026  
**Decisión:** Gym Rat no mantendrá un mapeo previo de IDs ni un bucket/paquete de ejercicios MuscleWiki. La selección PPL acordada sirve como ejemplo de rutina inicial y fixture manual, no como importación o inventario de catálogo. Cada lista preliminar se consulta en la API cuando la persona abre búsqueda o selecciona un grupo/filtro; el detalle y el medio se solicitan solo cuando la persona elige el ejercicio.

## Flujo aprobado

1. La app pide al backend Laravel resultados paginados según consulta/filtros de la persona (`/exercises` o `/search`), con `lang=es-es` en Growth. No solicitar ni replicar el catálogo completo.
2. Laravel adjunta `X-API-Key`, aplica límites de consulta y devuelve únicamente los campos necesarios para la selección. La clave nunca se incluye en el cliente móvil.
3. Al agregar a una rutina, Gym Rat conserva el identificador externo necesario para volver a consultar el ejercicio y los datos de rutina que la persona escribió. No hace precarga, descarga, exportación ni copia masiva. Las etiquetas propias que Gym Rat necesite para identificar registros internos deben ser originales y no copiar instrucciones/descripciones externas.
4. Al abrir el detalle o reproducir video, llamar a la API en ese momento. El video se reproduce dentro de la app bajo la política de streaming, sin URL de descarga expuesta.
5. Respetar caché y headers por tipo: metadatos de texto hasta 30 días como máximo; miniaturas solo caché privada en dispositivo hasta 24 h si los headers lo permiten; bodymaps según `no-store` documentado; video `no-store`, buffers transitorios únicamente. No disco, storage propio, CDN ni caché compartida para bytes de video.
6. Medir llamadas y consumo por usuario/sesión sin registrar URLs o tokens de media. Cada `/stream/` y cada solicitud `Range` cuenta para la cuota; establecer alertas y degradación ante 403/429/timeouts.
7. Si MuscleWiki no está disponible, la persona aún puede consultar/editar sus rutinas propias y registrar sesiones. Ocultar/reintentar el contenido de tercero sin borrar el historial de entrenamiento.

## Requisitos de selección UX

- La rutina PPL básica se puede proponer como plantilla propia de Gym Rat, pero sus ejercicios se resuelven desde el catálogo vigente al ser seleccionados.
- No prometer equivalencias/IDs antes de recibir respuesta real. Si la API no encuentra un nombre exacto, mostrar alternativas de la búsqueda y pedir que la persona elija; no sustituir silenciosamente un ejercicio.
- La ausencia de resultados de API no bloquea crear un ejercicio personal de texto (sin medios MuscleWiki) ni guardar el entrenamiento.
- Growth es el plan mínimo para beta abierta en español. Testing es solo inglés/integración cerrada.

## Criterios de aceptación revisados

- No existe dependencia de una lista prevalidada de 16 IDs, importación, paquete offline ni bucket comercial de ejercicios.
- La arquitectura del flujo bajo demanda, protección de clave, límites de caché/streaming, telemetría y comportamiento ante cuota agotada está documentada en [integración técnica](../architecture/musclewiki-api-integration.md).
- GRT-046 se puede cerrar como decisión de curación al aprobar este enfoque; las llamadas reales, consumo, catálogo efectivo y fallback se verifican durante la implementación/validación de integración, antes de abrir la beta.

## Fuente

- [Documentación API MuscleWiki](https://api.musclewiki.com/documentation)
- [Precios API MuscleWiki](https://api.musclewiki.com/pricing)
- [Términos API MuscleWiki](https://api.musclewiki.com/api-terms)
