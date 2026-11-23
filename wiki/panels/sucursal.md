# Portal Sucursal

**URL:** `/sucursal`  
**Stack:** Inertia v3 + React 19 (PWA)  
**Usuario:** `UserType::Branch`  
**Middleware:** `auth`, `branch`, `validate.user`

## Propósito

Operación en tiempo real de una sucursal: tablero de pedidos (incl. programados), menú local, reportes con drilldown, reservaciones, envíos terceros.

## Acceso

- Login: `/sucursal/login` (también `/login` redirige según type)
- Demo: `lead0@pollocrazy.mx` / `password`
- Requiere pivot `branch_user` con al menos una sucursal

## Páginas

| Página | Ruta | Controller | Función |
|---|---|---|---|
| `board.tsx` | `GET /sucursal` | SucursalController | Tablero Kanban pedidos |
| `order.tsx` | `GET /sucursal/orders/{order}` | SucursalController | Detalle + mensajes |
| `menu.tsx` | `GET /sucursal/menu` | SucursalMenuController | Gestión menú local |
| `reports.tsx` | `GET /sucursal/reports` | SucursalController | Estadísticas operativas |
| — | `GET /sucursal/reports/drilldown` | SucursalController | Drilldown JSON de reportes |
| `reservations.tsx` | `GET /sucursal/reservaciones` | SucursalReservationController | Ops reservaciones |
| `shipments.tsx` | `GET /sucursal/shipments` | SucursalShipmentController | Lista envíos |
| `shipment.tsx` | `GET /sucursal/shipments/{shipment}` | SucursalShipmentController | Detalle envío |
| Auth | `/sucursal/login`, forgot/reset | SucursalAuthController | Login/recuperación |

Páginas React: `resources/js/pages/sucursal/` (minúscula).

## Rutas de acción (pedidos)

```
POST /sucursal/orders/{order}/accept      # Aceptar + ETA
POST /sucursal/orders/{order}/in-kitchen  # Mover a cocina
POST /sucursal/orders/{order}/ready       # Marcar listo
POST /sucursal/orders/{order}/assign      # Asignar repartidor
POST /sucursal/orders/{order}/picked-up   # Pickup recogido
POST /sucursal/orders/{order}/reject      # Rechazar
POST /sucursal/orders/{order}/messages    # Mensaje en pedido
```

Archivo rutas: `routes/sucursal.php`

### Pedidos programados

Los pedidos con `scheduled_for` aparecen en el tablero; las transiciones de cocina se bloquean hasta la hora (`ScheduledOrderTime` / `isScheduleLocked()`). Ver [Pedidos](../domains/orders.md#pedidos-programados) y [Panel de pedidos](../sucursal/order-panel.md).

## Rutas de reservaciones

```
GET  /sucursal/reservaciones
GET  /sucursal/reservaciones/mapa-estado
POST /sucursal/reservaciones/{reservation}/accept
POST /sucursal/reservaciones/{reservation}/reject
POST /sucursal/reservaciones/{reservation}/assign-table
POST /sucursal/reservaciones/{reservation}/seat
POST /sucursal/reservaciones/{reservation}/complete
```

Detalle: [Reservaciones sucursal](../sucursal/reservations.md), dominio [Reservaciones](../domains/reservations.md).

## Rutas de envíos

```
GET  /sucursal/shipments
GET  /sucursal/shipments/{shipment}
POST /sucursal/shipments/{shipment}/assign
POST /sucursal/shipments/{shipment}/pick-up
POST /sucursal/shipments/{shipment}/deliver
POST /sucursal/shipments/{shipment}/cancel
```

## Controllers

```
app/Http/Controllers/Sucursal/
├── SucursalAuthController.php
├── SucursalController.php              # Board, order, reports, drilldown, pause
├── SucursalOrderController.php         # Transiciones estado
├── SucursalMenuController.php          # Menú y overrides
├── SucursalReservationController.php   # Reservaciones
└── SucursalShipmentController.php
```

## Layout y componentes

| Archivo | Función |
|---|---|
| `layouts/sucursal-layout.tsx` | Layout principal con nav |
| `components/sucursal/assign-driver-modal.tsx` | Modal asignar repartidor |
| `components/sucursal/reservation-*.tsx` | UI reservaciones (calendario, mapa, acciones) |
| `interfaces/ISucursal.tsx` | Types TypeScript |

## Hooks

| Hook | Función |
|---|---|
| `use-sucursal-branding.ts` | Branding cadena |
| `use-offline-queue.ts` | Cola offline acciones |
| `use-wake-lock.ts` | Mantener pantalla activa |
| `use-flash-toast.ts` | Toasts de flash messages |

## Contexto compartido (Inertia)

`ResolveBranch` comparte prop `sucursal`:

```typescript
{
  currentBranchId: number,
  branches: [{ id, name, code }],
  branding: { colors, logo, ... },
  shipmentPlatforms: [{ value, label }]
}
```

## Tablero Kanban

Columnas en `board.tsx`:

| Columna | Statuses |
|---|---|
| Nuevos | pending, accepted |
| En cocina | in_kitchen |
| Listos | ready_for_pickup |
| En camino | on_the_way |
| Entregados | delivered |

Realtime via Echo canal `branch.{id}.orders`.

**Detalle completo:** [Panel de pedidos](../sucursal/order-panel.md)

## Menú sucursal

Override local de productos/modificadores sin cambiar menú maestro. Incluye ruta de imagen `GET /sucursal/menu/{product}/image/{image?}`.

**Detalle completo:**
- [Menú y estadísticas](../sucursal/menu-stats.md)
- [Overrides de producto](../sucursal/product-overrides.md)

## Reportes

Service: `BranchOrderStatistics`  
Drilldown: `BranchReportDrilldown`  
Filtros: shift, day, week, month + rango de fechas

```
GET /sucursal/reports?period=day&date=2026-06-09
GET /sucursal/reports?period=shift&date=2026-06-09&shift=0
GET /sucursal/reports/drilldown?...
```

## Cambio de sucursal

```
POST /sucursal/switch-branch
```

Actualiza `session('sucursal_branch_id')`.

## Pausar / reabrir sucursal

```
POST /sucursal/pause
POST /sucursal/reopen
```

Afecta `BranchStatus` — impacta disponibilidad en tienda. Los horarios L–V configurados en Franquicia también afectan el estado de apertura.

## Envíos terceros

Ciclo: assign → pick-up → deliver (o cancel). Ver [Envíos](../domains/shipments.md).

## Debugging sucursal

| Problema | Archivo a revisar |
|---|---|
| Tablero vacío | SucursalController, branch_id, OrderChanged |
| Pedido programado bloqueado | `ScheduledOrderTime`, `isScheduleLocked()` |
| Realtime no funciona | board.tsx Echo config, soketi |
| Menú no guarda / sin imagen | SucursalMenuController, BranchProductPresentation |
| 403 | ResolveBranch, branch_user pivot |
| Props incorrectos | SucursalController::orderCard() |
| Reservaciones | Flag cadena + SucursalReservationController |

Test principal: `tests/Feature/SucursalPortalTest.php`

## Añadir funcionalidad a sucursal

Ver [Nueva página Inertia](../integration/inertia-page.md).

1. Ruta en `routes/sucursal.php` con middleware `auth, branch, validate.user`
2. Controller en `Http/Controllers/Sucursal/`
3. Page React en `resources/js/pages/sucursal/`
4. Wayfinder generate
5. Test Feature
