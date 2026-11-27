---
name: detectar-optimizacion-filament
description: Detecta problemas de optimización en la obtención de datos (N+1, consultas en bucle, falta de eager load) en recursos Filament y Laravel. Solo lecturas/consultas; excluye guardado, actualización y borrado. Use when the user asks to audit or improve performance of data loading, detect N+1, optimize reads in a Filament resource or module, or review load/cache in a section (e.g. "revisa historial reprobadas", "optimiza módulo estudiantes", "detectar N+1", "auditar carga", "filament lento").
---

# Detección de problemas de optimización en obtención de datos (Filament / Laravel)

**Alcance:** Solo optimización de **obtención de datos** (lecturas, consultas, listados, formularios al cargar opciones). No incluye guardado (create), actualización (update) ni borrado (delete).

## Cuándo aplicar este skill

Aplicar cuando el usuario pida:
- Revisar optimización o rendimiento de **carga** de datos en una pantalla/módulo.
- Detectar N+1 o consultas excesivas en **lecturas**.
- Auditar un proceso, módulo o subsección en cuanto a **obtención de datos** (ej. "revisa el módulo de estudiantes", "historial reprobadas", "recursos de Filament").
- Mejorar tiempos de carga en tablas o formularios Filament (listados, columnas, opciones de selects).

## 1. Definir alcance (proceso / módulo / subsección)

- Si el usuario indica **módulo, proceso o subsección** (ej. "estudiantes", "historial reprobadas", nombre de Resource o ruta `/admin/...`):
  - Mapear a rutas de código: `app/Filament/Resources/*Resource*.php`, páginas `*Resource/Pages/`, modelos y servicios relacionados en `app/Models/`, `app/Services/`.
  - Restringir búsquedas (codebase_search, grep) a esas carpetas/archivos.
- Si no hay alcance explícito, acotar por defecto a `app/Filament/` y modelos/servicios que toquen esos recursos.

## 2. Patrones a buscar (solo lecturas)

Buscar en el alcance definido únicamente en flujos de **obtención de datos** (tablas, columnas, listados, opciones de selects, filtros). Ignorar lógica de create/update/delete.

| Patrón | Dónde | Riesgo |
|--------|--------|--------|
| `getStateUsing` / `formatStateUsing` que llaman a métodos del modelo o a `->get()`/queries | Columnas de tabla Filament | N+1: una o más consultas por fila |
| Mismo cálculo usado en dos o más columnas sin caché ni batch | Tabla Filament | Doble (o más) costo por fila |
| Query base sin `with()` para relaciones usadas en columnas | `modifyQueryUsing` o query del Resource | N+1 al acceder a `$record->relacion` |
| `Select::make()->options(function () { ... Model::query()->... })` o `Estudiante::find($get('...'))` dentro de options | Formularios, Repeater | Query por render/opción |
| `whereHas` en filtros sin `with()` correspondiente si la relación se muestra en tabla | Filtros + columnas | N+1 al mostrar relación |

Flujo sugerido:
1. Grep/codebase: `getStateUsing`, `formatStateUsing`, `->options(`, `whereHas`, `modifyQueryUsing`, `with(`.
2. Cruzar con archivos del alcance y revisar si hay consultas dentro de closures por fila o por opción.

Detalle de patrones y ejemplos: [patrones.md](patrones.md).

## 3. Usar Context7 para Filament

Cuando el código analizado sea de Filament (Resources, Tables, Pages):

1. Llamar al MCP **user-context7** → **resolve-library-id** para obtener el library ID de Filament (ej. `filament/filament` o el que devuelva).
2. Llamar **query-docs** con ese `libraryId` y una pregunta concreta, ej.: "How to avoid N+1 queries in Filament tables? Eager loading, getStateUsing performance, batch loading table data."
3. Usar la documentación para validar hallazgos y sugerir soluciones alineadas con Filament.

## 4. Formato del informe

Entregar:

- **Hallazgos** por archivo (y línea si aplica):
  - Patrón detectado (ej. "getStateUsing llama a método que hace queries").
  - Impacto (N+1 en tabla, query por opción en select, etc.).
  - Prioridad: alta (tabla con muchos registros), media (formulario), baja (poco usado).
- **Recomendaciones** por hallazgo:
  - Batch service + mapa estático en Resource + override `getTableRecords()` en List page para precalcular por página (patrón de referencia abajo).
  - Caché por request (propiedad estática en Resource) si el mismo valor se usa en varias columnas y no se usa batch.
  - Añadir `with([...])` en `modifyQueryUsing` para relaciones usadas en columnas.
  - Reducir consultas en `options()`: precargar datos en la página (List) o cachear por request.

## 5. Patrón de referencia en este proyecto

Solución ya aplicada para evitar N+1 en una tabla Filament:

- **Resource** ([app/Filament/Resources/HistorialCompletoAsignaturasReprobadasResource.php](app/Filament/Resources/HistorialCompletoAsignaturasReprobadasResource.php)): propiedad estática `$batchCountsMap` (mapa `estudiante_id => count`), método `setBatchCountsMap()` y `getHistorialReprobadasCount($record)` que primero usa el mapa, y si no hay mapa usa caché por request (`$historialReprobadasCountCache`) para no duplicar llamadas.
- **List page** ([app/Filament/Resources/HistorialCompletoAsignaturasReprobadasResource/Pages/ListHistorialCompletoAsignaturasReprobadas.php](app/Filament/Resources/HistorialCompletoAsignaturasReprobadasResource/Pages/ListHistorialCompletoAsignaturasReprobadas.php)): override de `getTableRecords()`; tras `parent::getTableRecords()` extrae los IDs de los registros de la página, llama a un servicio de batch que devuelve los conteos, y asigna ese mapa al Resource con `setBatchCountsMap($counts)`.
- **Servicio batch** ([app/Services/HistorialReprobadas/HistorialReprobadasBatchService.php](app/Services/HistorialReprobadas/HistorialReprobadasBatchService.php)): misma lógica que el método del modelo que antes se llamaba por fila, pero en batch (carga estudiantes, grupos, horarios, calificaciones, etc. con pocas queries y calcula en memoria).

Usar este flujo como referencia al proponer soluciones similares (batch + mapa + getTableRecords override).
