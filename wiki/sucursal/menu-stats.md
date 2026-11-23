# Gestión De Menú Y Estadísticas De Sucursal

Este documento describe los cambios implementados para el panel de sucursal relacionados con gestión de menú (`/sucursal/menu`) y estadísticas operativas (`/sucursal/reports`).

## Alcance

La sucursal ahora puede gestionar el menú con un enfoque híbrido:

- Editar productos y combos del menú maestro.
- Ajustar disponibilidad, precio especial y horario por sucursal.
- Prender o apagar ingredientes/modificadores solo para la sucursal actual.
- Consultar estadísticas por turno, día, semana y mes.

El acceso a gestión de menú queda habilitado para usuarios del portal de sucursal mientras se implementan permisos finos desde franquicia.

## Gestión De Menú

La pantalla principal vive en:

```text
sline-webapp/resources/js/pages/sucursal/menu.tsx
```

La vista muestra productos en tabla paginada con:

- Búsqueda por producto o categoría.
- Paginación local de 10 productos por página.
- Columnas de producto, categoría, precio base, precio sucursal, horario, estado y acciones.
- Edición desde modal.
- Botón rápido para prender o apagar disponibilidad.
- Pestaña separada para ingredientes/modificadores.

## Rutas De Menú

Las rutas están definidas en:

```text
sline-webapp/routes/sucursal.php
```

Rutas principales:

- `GET /sucursal/menu`: pantalla de gestión de menú.
- `GET /sucursal/menu/{product}/image/{image?}`: imagen del producto.
- `PATCH /sucursal/menu/{product}`: edita datos permitidos del producto o combo.
- `PATCH /sucursal/menu/{product}/branch`: edita overrides por sucursal.
- `POST /sucursal/menu/{product}/toggle`: prende o apaga producto para la sucursal.
- `POST /sucursal/menu/modifiers/{modifier}/toggle`: prende o apaga modificador para la sucursal.
- `GET /sucursal/reports`: estadísticas.
- `GET /sucursal/reports/drilldown`: drilldown de métricas.

El controlador vive en:

```text
sline-webapp/app/Http/Controllers/Sucursal/SucursalMenuController.php
```

## Modelo De Datos

Los overrides por producto usan la tabla existente `branch_products`:

- `is_available`
- `price_override`
- `available_from`
- `available_to`

La disponibilidad de ingredientes/modificadores por sucursal usa una tabla nueva:

```text
sline-webapp/database/migrations/2026_06_09_000001_create_branch_modifiers_table.php
```

Modelo:

```text
sline-webapp/app/Models/BranchModifier.php
```

Campos principales:

- `branch_id`
- `modifier_id`
- `is_available`

Esto evita apagar un ingrediente globalmente para toda la franquicia.

## Efecto En Tienda

La tienda respeta la disponibilidad por sucursal:

- El catálogo/producto filtra modificadores apagados para la sucursal.
- El checkout rechaza pedidos que intenten usar un modificador no disponible.

Archivos relevantes:

```text
sline-webapp/app/Http/Controllers/Tienda/TiendaController.php
sline-webapp/app/Services/PlaceOrder.php
```

## Estadísticas De Sucursal

La pantalla vive en:

```text
sline-webapp/resources/js/pages/sucursal/reports.tsx
```

El cálculo se extrae a:

```text
sline-webapp/app/Services/BranchOrderStatistics.php
```

Filtros soportados:

- `shift`: turno derivado de horarios de sucursal.
- `day`: día.
- `week`: semana.
- `month`: mes.

Query params:

```text
/sucursal/reports?period=day&date=2026-06-09
/sucursal/reports?period=shift&date=2026-06-09&shift=0
```

### Drilldown

Ruta: `GET /sucursal/reports/drilldown`  
Service: `BranchReportDrilldown` — desglose JSON por métrica del periodo.

## Imágenes de menú

```text
GET /sucursal/menu/{product}/image/{image?}
```

Sirve cover/galería del producto efectivo (override sucursal o franquicia via `source=franchise`).

## Métricas

La pantalla muestra métricas comerciales:

- Pedidos realizados.
- GMV.
- Ticket promedio.
- Pedidos delivery.
- Pedidos pickup.
- Cancelados.
- Efectivo.
- Tarjeta.
- Comisión Sline.

Y métricas operativas:

- Tiempo de aceptación.
- Tiempo en cocina.
- Tiempo en envío.
- Tiempo total.
- Porcentaje a tiempo en cocina.
- Desempeño operativo general.

## Turnos

Los turnos se derivan de `branch_hours`:

```text
sline-webapp/app/Models/BranchHour.php
```

Si la sucursal tiene varios horarios en el mismo día, cada ventana se muestra como un turno distinto. Si no hay horarios configurados, el reporte cae al día completo.

## Tiempos Operativos

El tiempo en cocina usa `order_status_history` para buscar la primera transición a `in_kitchen`.

Si no hay historial suficiente, usa como fallback:

```text
ready_at - accepted_at
```

El tiempo en envío usa:

```text
delivered_at - picked_up_at
```

Solo aplica a pedidos `delivery`.

## Índices De Reportes

Se agregó una migración con índices para mejorar consultas de reportes:

```text
sline-webapp/database/migrations/2026_06_09_000002_add_reporting_indexes_to_orders_tables.php
```

Incluye:

- `orders(branch_id, created_at)`
- `order_status_history(order_id, to_status, created_at)`

## Permisos Temporales

Por ahora todos los usuarios autenticados del portal de sucursal pueden gestionar menú.

La decisión queda centralizada en `canManageMenu()` dentro de:

```text
sline-webapp/app/Http/Controllers/Sucursal/SucursalMenuController.php
```

Esto permite conectar después permisos reales desde franquicia sin rediseñar la pantalla.

## Pruebas

Se agregaron pruebas en:

```text
sline-webapp/tests/Feature/SucursalPortalTest.php
sline-webapp/tests/Feature/TiendaTest.php
```

Cubren:

- Carga de gestión de menú.
- Edición de producto desde sucursal.
- Override por sucursal sin afectar otra sucursal.
- Disponibilidad de modificador por sucursal.
- Actualización de combos.
- Reportes por día, turno, semana y mes.
- Rechazo de pedidos con modificadores no disponibles.

## Comandos Recomendados

Aplicar migraciones:

```bash
cd sline-webapp
vendor/bin/sail artisan migrate
```

Ejecutar pruebas relacionadas:

```bash
vendor/bin/sail artisan test --compact tests/Feature/SucursalPortalTest.php tests/Feature/TiendaTest.php --filter="menu|modifier|reports|unavailable"
```
