# Patrones de riesgo y soluciones (solo obtención de datos)

Referencia para la detección de problemas de optimización en **obtención de datos** en Filament/Laravel. Solo se consideran flujos de lectura (consultas, listados, columnas, opciones de formularios). No se incluyen patrones de guardado, actualización ni borrado.

## 1. Tabla: getStateUsing / formatStateUsing con queries

**Qué buscar:** Columnas que usan `->getStateUsing(fn ($record) => ...)` o `->formatStateUsing(...)` donde dentro se llama a un método del modelo que hace consultas (ej. `$record->historialCalificacionesReprobadas()`, `$record->relacion()->count()`).

**Ejemplo de riesgo:**
```php
Tables\Columns\TextColumn::make('materias_reprobadas_total')
    ->getStateUsing(fn (Estudiante $record) => $record->historialCalificacionesReprobadas())
```
Una consulta (o varias, si el método hace N+1 interno) por cada fila de la tabla.

**Solución:** Precalcular en batch para la página actual (override `getTableRecords()` en la List page, servicio que devuelve mapa id => valor, Resource con `setBatchCountsMap` y getter que usa el mapa). Ver HistorialCompletoAsignaturasReprobadasResource + HistorialReprobadasBatchService.

---

## 2. Mismo dato en varias columnas sin caché

**Qué buscar:** Dos o más columnas que usan el mismo cálculo (mismo método o misma relación) por `$record`.

**Riesgo:** Sin caché, el cálculo se ejecuta dos veces por fila.

**Solución:** Caché por request en el Resource (array estático keyed por `$record->id`) o, mejor, batch precalculado (mapa) si el cálculo es costoso.

---

## 3. Query base sin eager load

**Qué buscar:** En el Resource, `modifyQueryUsing` o el query por defecto que no incluye `->with([...])` para relaciones que luego se acceden en columnas (ej. `$record->plantel->nombre`, `$record->grupos`).

**Riesgo:** N+1 al renderizar cada fila al acceder a la relación.

**Solución:** Añadir en `modifyQueryUsing` un `->with(['plantel', 'grupos' => fn ($q) => $q->orderBy(...)])` (o las relaciones que usen las columnas).

---

## 4. Formularios: options() con queries

**Qué buscar:** `Select::make()->options(function (Get $get) { ... Model::query()->... })`, o dentro de options `Estudiante::find($get('estudiante_id'))`, o cualquier `Model::` / `->get()` que se ejecute por cada render del campo.

**Riesgo:** Muchas consultas al abrir/actualizar el formulario o al cambiar valores que disparan re-render.

**Solución:** Precargar opciones en la List page y pasarlas al formulario, o cachear el resultado por request; evitar `find()`/queries dentro de closures de `options()` cuando sea posible.

---

## 5. Filtros whereHas y columnas con relación

**Qué buscar:** Filtro que usa `whereHas('relacion', ...)` pero la tabla muestra `$record->relacion->campo` sin que el query principal haga `with('relacion')`.

**Riesgo:** N+1 al mostrar la relación en cada fila.

**Solución:** Incluir `with('relacion')` en el query base (modifyQueryUsing o equivalente).
