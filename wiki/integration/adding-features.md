# Guía de integración — Añadir funcionalidades nuevas

## Decision tree

```
¿Quién usa la feature?
│
├── Equipo Sline interno → Panel Admin (Filament)
├── Dueño/gerente cadena → Panel Franquicia (Filament)
├── Staff sucursal → Portal Sucursal (Inertia)
├── Cliente comensal → Tienda (Inertia)
├── Repartidor móvil → API + App Expo
└── Múltiples → Backend Service compartido + UIs separadas
```

## Checklist universal

Antes de codear, define:

1. **Superficie(s)** afectada(s)
2. **Modelos/tablas** nuevos o existentes
3. **¿Multi-tenant?** — ¿necesita `tenant_id` + `BelongsToTenant`?
4. **Service** — ¿lógica reutilizable entre superficies?
5. **Auth** — ¿qué UserType puede acceder?
6. **Tests** — Feature test mínimo
7. **Realtime** — ¿necesita broadcast?

## Flujo recomendado

```
1. Migración + Model (+ Factory)
2. Enum(s) si hay estados nuevos
3. Service con lógica de negocio
4. UI (Filament Resource / Inertia Page / API endpoint)
5. Rutas
6. Tests Pest
7. Pint + npm build
```

## Por tipo de cambio

| Tipo | Guía |
|---|---|
| CRUD admin/cadena | [Nuevo recurso Filament](./filament-resource.md) + módulo en [RBAC](../RBAC-FRANQUICIA.md) si es Franquicia |
| Página web interactiva | [Nueva página Inertia](./inertia-page.md) |
| Endpoint móvil | [Nuevo endpoint API](./api-endpoint.md) |
| Tabla/modelo nuevo | [Migraciones y modelos](./migrations.md) |
| Solo backend/batch | Service + Command/Job |
| Stats / heatmap Franquicia | [Estadísticas](../panels/franquicia-statistics.md) / [Heatmap](../panels/franquicia-heatmap.md) |

## Capas — dónde va cada cosa

| Responsabilidad | Ubicación | NO poner en |
|---|---|---|
| Validación request | Form Request o `$request->validate()` | Service |
| Lógica negocio | `app/Services/` | Controller |
| Autorización | Middleware, scopes | Controller (mínimo) |
| Query Eloquent simple | Controller/Resource | Service (si es trivial) |
| Transacciones DB | Service | Controller |
| Broadcast | Event dispatch desde Service | Controller directo |
| UI admin | Filament Resource/Page | Blade custom |
| UI PWA | React page + components | Blade |
| Tipos frontend | TypeScript interfaces | — |

## Ejemplo: añadir "Notas internas" a pedidos

```
1. Migration: orders.internal_notes (nullable text)
2. Model: Order — añadir a fillable
3. Franquicia OrderResource — Textarea en form/view
4. Sucursal order.tsx — mostrar/editar si aplica
5. SucursalOrderController — validar + save
6. Test: FranquiciaPanelTest + SucursalPortalTest
```

## Ejemplo: nuevo reporte en sucursal

```
1. Service method en BranchOrderStatistics
2. SucursalController@reports — nuevo prop
3. reports.tsx — nueva sección UI
4. Ruta existente /sucursal/reports (query param)
5. Test SucursalPortalTest
```

## Ejemplo: nueva acción API repartidor

```
1. Route en routes/api.php
2. DriverController method
3. Service si lógica compleja
4. App: api.ts + screen
5. Test DriverApiTest
```

## Convenciones de commit

```
feat(sucursal): añade filtro por turno en reportes
fix(orders): corrige transición a on_the_way al asignar repartidor
```

Ver [Convenciones](../development/conventions.md).

## Verificación pre-merge

```bash
vendor/bin/sail artisan test --compact --filter=TestRelacionado
vendor/bin/sail bin pint --dirty
vendor/bin/sail npm run build   # si hay cambios JS
```

## Anti-patterns a evitar

- ❌ Lógica de negocio en React — siempre validar en backend
- ❌ Queries sin tenant scope en Franquicia
- ❌ Strings mágicos — usar Enums
- ❌ URLs hardcodeadas en frontend — usar Wayfinder
- ❌ Nuevo folder `app/Repositories/` — usar Services
- ❌ Policies cuando el proyecto usa middleware + scopes
- ❌ Archivos públicos por defecto — usar private + signed URLs

## Referencia de archivos existentes

Antes de crear algo nuevo, busca un ejemplo similar:

| Necesito | Copiar patrón de |
|---|---|
| Filament CRUD | `Franquicia/Resources/Products/` |
| Filament stats page | `Franquicia/Pages/OrderStatisticsPage.php` |
| Inertia CRUD | `SucursalMenuController` + `menu.tsx` |
| Checkout flow | `PlaceOrder` + `TiendaController` |
| API endpoint | `DriverOrderController` |
| Excel export | `MenuExcel`, `InvoicePaymentsExcel` |
| Map component | `CoverageMapPicker` |
