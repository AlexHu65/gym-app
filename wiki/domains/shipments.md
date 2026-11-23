# Dominio: Envíos terceros (Shipments)

Envíos gestionados via plataformas externas (Didi, Uber, **Picker Express**, etc.), separados del flujo de pedidos in-house.

Integración real (HTTP + webhooks): solo **Picker Express** — ver [Picker Express](../integration/picker.md). Didi/Uber/Other siguen siendo etiquetas manuales.

## Modelos

| Modelo | Tabla |
|---|---|
| `Shipment` | `shipments` |

Campos clave:
- `platform` — `ShipmentPlatform` (Didi, Uber, Other)
- `status` — `ShipmentStatus`
- `branch_id`, `driver_id`, `order_id` (opcional)
- Datos tracking externo

## Enums

| Enum | Valores |
|---|---|
| `ShipmentPlatform` | Didi, Uber, Picker, Other |
| `ShipmentStatus` | Pending, Assigned, OnTheWay, Delivered, Cancelled |

## Servicios

| Service | Función |
|---|---|
| `CreateShipment` | Crear envío standalone |
| `CreateShipmentFromOrder` | Crear envío vinculado a pedido |

## Superficies

### Franquicia
Resource: `Shipments/ShipmentResource` (módulo `shipments`)  
Página detalle: `Shipments/Pages/ViewShipment`

### Sucursal
Controller: `SucursalShipmentController`  
Pages: `shipments.tsx`, `shipment.tsx`

Rutas en `routes/sucursal.php`:

| Método | Ruta | Acción |
|---|---|---|
| GET | `/sucursal/shipments` | Listado |
| GET | `/sucursal/shipments/{shipment}` | Detalle |
| POST | `/sucursal/shipments/{shipment}/assign` | Asignar |
| POST | `/sucursal/shipments/{shipment}/pick-up` | Recoger |
| POST | `/sucursal/shipments/{shipment}/deliver` | Entregar |
| POST | `/sucursal/shipments/{shipment}/cancel` | Cancelar |

No hay `POST` create ni `PATCH` update en el portal sucursal (creación/edición vive en Franquicia).

## Diferencia vs Order delivery

| Aspecto | Order (in-house) | Shipment (tercero) |
|---|---|---|
| Repartidor | Propio (driver User) | Plataforma externa |
| Tracking | App Sline | Plataforma externa |
| Pago envío | Integrado checkout | Separado |
| Modelo | `orders` | `shipments` |

Un Shipment puede vincularse a un Order via `order_id` pero son entidades independientes.

## ResolveBranch share

Plataformas disponibles compartidas en Inertia:

```typescript
sucursal.shipmentPlatforms: [{ value: 'didi', label: 'Didi' }, ...]
```

Desde enum `ShipmentPlatform`.

## Archivos clave

```
app/Models/Shipment.php
app/Services/CreateShipment.php, CreateShipmentFromOrder.php
app/Enums/ShipmentPlatform.php, ShipmentStatus.php
app/Http/Controllers/Sucursal/SucursalShipmentController.php
app/Filament/Franquicia/Resources/Shipments/
resources/js/pages/sucursal/shipments.tsx, shipment.tsx
database/factories/ShipmentFactory.php
```

## Plataforma Picker

- Enum: `ShipmentPlatform::Picker`
- Columnas `picker_*` en `shipments`
- No aparece en el modal de assign in-house (`ResolveBranch` la filtra)
- Dispatch: `DispatchPickerBooking` al Ready / `dispatch-picker`

## Añadir plataforma nueva (etiqueta manual)

1. Añadir case a `ShipmentPlatform` enum con `getLabel()`
2. Aparece automáticamente en sucursal via ResolveBranch (excepto courier externos reales)
3. Actualizar forms Filament si hay lógica específica
4. Test

## Debugging

| Problema | Verificar |
|---|---|
| No aparece plataforma | ShipmentPlatform enum cases |
| Envío no se crea | CreateShipment validation, branch_id |
| Status no avanza | SucursalShipmentController transitions |
