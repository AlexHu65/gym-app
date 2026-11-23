# PRD-02 — Catálogo de ejercicios y favoritos

**Estado:** MuscleWiki API seleccionado; uso comercial pagado, display en app propia y streaming in-app permitidos bajo licencia limitada y revocable. No se requiere mapeo/semilla de IDs: catálogo bajo demanda. El API key y la integración real siguen pendientes de ejecución/confirmación.  
**Usuarios:** usuarios con acceso Free/desbloqueo; operadores de catálogo.  
**Objetivo:** encontrar un ejercicio apropiado y volver fácilmente a los usados con frecuencia.

## Alcance

- Consultar ejercicios autorizados bajo demanda mediante búsqueda y paginación desde Laravel; no importar ni replicar el dataset.
- Buscar en español y filtrar por equipo y grupo muscular.
- Ver metadatos e instrucciones; reproducir video por streaming dentro de la app conforme a caché, marca y atribución permitidas.
- Favoritos para usuarios con desbloqueo completo, persistentes por cuenta y sin duplicados.
- Estados vacíos, sin resultados, ejercicio no disponible, error de carga y contenido retirado.
- Free: puede consultar ejercicios textuales bajo demanda solo mientras haya cuota compartida disponible; no recibe thumbnails, imágenes, videos ni bodymaps, y puede guardar un solo entrenamiento. Al guardarlo se presenta inmediatamente la oferta de compra. Desbloqueo completo: funciones propias ampliadas y medios permitidos según plan API vigente. No comprar medios individuales ni precargar un bucket.

## Requisitos y aceptación

| ID | Requisito | Aceptación |
|---|---|---|
| CAT-01 | Procedencia auditable | Cada referencia activa identifica fuente/licencia; la UI aplica atribución y límites de cada medio. |
| CAT-02 | Búsqueda/filtros | Los resultados se pueden buscar en español y filtrar por equipo/grupo muscular; vacío y fallo explican cómo continuar. |
| CAT-03 | No filtrar medios no autorizados | Registros sin permiso aplicable no exponen URLs/archivos de medios en app, API pública, CDN ni fixtures de producción. |
| CAT-04 | Catálogo bajo demanda | Buscar/listar consulta API paginada según acción del usuario; no hay importación ni catálogo externo replicado; errores 403/429/falla permiten continuar con rutinas propias. |
| CAT-05 | Favoritos | El usuario con desbloqueo agrega/quita favoritos; el cambio persiste y se sincroniza con su cuenta. |
| CAT-06 | Acceso | Free ve la muestra definida; la compra permanente activa funciones y contenido cubierto. La cuota API/plan provider se gestiona aparte. |
| CAT-07 | Streaming/licencia | Video permanece dentro de la app, sin descarga ni almacenamiento persistente; caché limitada, marca intacta y atribución requerida. |
| CAT-08 | Reserva API | Existe un fondo para Testing y el plan productivo objetivo; se distinguen MXN $3,687 iniciales de la reserva adicional anual necesaria para Growth. |

## Restricciones de contenido

Los términos permiten display integrado y streaming bajo plan pagado; no permiten ofrecer API/dataset, exportar/revender datos, descargar ni rehostear medios. El acceso a medios es revocable y puede cambiar/retirarse; informarlo a compradores. Videos no se descargan, no se almacenan ni se envían a caché compartida/CDN. No mantener snapshots de metadatos externos >30 días sin autorización adicional.

El crecimiento de llamadas y funciones API sigue Testing para integración/beta cerrada en inglés → Growth para beta abierta y producción en español → Professional → Enterprise de ADR-004 y business-plan. No hay mapeo previo ni bucket de ejercicios.

## Fuera de alcance

Marketplace de ejercicios, contribuciones sociales y medios offline/no autorizados. El desbloqueo completo se vende una vez por SKU no consumible; precio aprobado de MXN $399 para apertura beta y $699 estándar. Configuración por storefront en GRT-003.

## Dependencias

GRT-004 (derechos y disclosure), GRT-046 (decisión de catálogo bajo demanda), GRT-047 (cotización/reserva API), GRT-010 (modelo de datos), GRT-013 (integración API bajo demanda), GRT-014 (explorador/favoritos), entitlements de GRT-023.

## Fuentes

[`exercise-source-register.md`](../../data/exercise-source-register.md), [`pricing-hypotheses.md`](../pricing-hypotheses.md), [`mvp-scope.md`](../mvp-scope.md), GRT-004, GRT-013–014.
