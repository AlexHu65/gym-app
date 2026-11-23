# Enums de dominio

Ubicación: `app/Enums/` (37 enums)

Los enums son la fuente de verdad para estados, roles y tipos. Siempre usar el enum, nunca strings mágicos.

## Usuarios y roles

| Enum | Valores | Uso |
|---|---|---|
| `UserType` | Admin, Chain, Branch, Driver, Customer | Columna `users.type` |
| `AdminRole` | Owner, Admin, Support | Rol admin |
| `ChainRole` | Owner, Manager, Admin | Rol cadena (legacy slug) |
| `BranchRole` | Lead, Staff | Pivot `branch_user.role` |

## Cadena y sucursal

| Enum | Uso |
|---|---|
| `ChainStatus` | Activa, pausada, suspendida |
| `BranchStatus` | Abierta, pausada, cerrada |
| `OnboardingStep` | Progreso onboarding cadena |
| `ChainActivityLevel` | Nivel actividad (dashboard admin) |
| `ChainDocumentType` | Tipos documento legal |

## Pedidos

| Enum | Valores clave | Uso |
|---|---|---|
| `OrderStatus` | Pending, Accepted, InKitchen, ReadyForPickup, OnTheWay, Delivered, Closed, Rejected, Cancelled, Refunded | Ciclo de vida |
| `OrderDeliveryType` | Delivery, Pickup, **DineIn** | Tipo entrega |
| `OrderSource` | Ios, Android, Web | Origen del pedido |
| `PaymentMethod` | Cash, Stripe, Paypal | Forma de pago |
| `PaymentStatus` | Pending, Authorized, Paid, Failed, Refunded | Estado pago |
| `PaymentType` | Charge, Refund, Adjustment | Tipo transacción |

### OrderStatus — transiciones

```
Pending → Accepted → InKitchen → ReadyForPickup
                                    ↓ (delivery + assign driver)
                               OnTheWay → Delivered

Pending → Rejected
* → Cancelled
ReadyForPickup → Delivered (pickup, marcar recogido)
```

Ver [Pedidos](../domains/orders.md) para flujos completos.

## Envíos

| Enum | Uso |
|---|---|
| `ShipmentStatus` | Pending, Assigned, OnTheWay, Delivered, Cancelled |
| `ShipmentPlatform` | Didi, Uber, Other |

## Facturación

| Enum | Uso |
|---|---|
| `BillingStatus` | Estado cobranza factura |
| `PlanType` | Tipo plan SaaS |
| `PlanCode` | Código plan (starter, pro, …) |

## Repartidores

| Enum | Uso |
|---|---|
| `DriverStatus` | Disponible, ocupado, offline |
| `VehicleType` | Moto, bicicleta, auto, walk |

## Promociones y marketing

| Enum | Uso |
|---|---|
| `CouponType` | Percentage, Fixed, FreeDelivery, DeliveryPercentage |
| `BannerLinkType` | None, Category, Product, Url |
| `SurveyQuestionType` | Text, Options, Stars |

## Reservaciones

| Enum | Uso |
|---|---|
| `ReservationStatus` | Pending, Confirmed, Seated, Completed, Rejected, Cancelled, NoShow |
| `ReservationDepositType` | None, Fixed, PerPerson, Percentage |
| `ReservationTableShape` | Rectangle, Circle |
| `ReservationTableMapState` | Available, Reserved, Occupied |
| `ReservationRejectionReason` | NoTablesAvailable, BranchClosed, ExceedsCapacity, Other |

## Estadísticas / drilldowns

| Enum | Uso |
|---|---|
| `FranquiciaStatDrilldownTarget` | Targets de KPIs (órdenes, shipments, drivers, customers, …) |
| `FranquiciaReservationDrilldownScope` | Alcances drilldown reservaciones |
| `DashboardPeriod` | Periodos dashboard Franquicia |

## Sistema

| Enum | Uso |
|---|---|
| `NotificationChannel` | Push, email, sms |
| `NotificationStatus` | Queued, Sent, Failed |
| `ActorType` | Quién realizó acción (audit) |
| `AuthProvider` | Google, Apple, Facebook, Email |

## Uso en código

### En modelos (cast)

```php
protected function casts(): array
{
    return [
        'status' => OrderStatus::class,
        'delivery_type' => OrderDeliveryType::class,
    ];
}
```

### En Filament

```php
Select::make('status')
    ->options(OrderStatus::class),

TextColumn::make('status')
    ->badge()
    ->color(fn (OrderStatus $state) => $state->getColor()),
```

Muchos enums implementan métodos helper: `getLabel()`, `getColor()`.

### En validación

```php
'status' => ['required', Rule::enum(OrderStatus::class)],
```

### En queries

```php
Order::query()->where('status', OrderStatus::Pending);
```

## Añadir un enum nuevo

```bash
vendor/bin/sail artisan make:enum MiEstado
```

Convenciones:
- Backed enum (`string` o `int`)
- TitleCase para cases: `InKitchen`, not `in_kitchen`
- Métodos `getLabel(): string` para UI en español
- Usar en migración: `$table->string('status')` + cast en modelo

## Debugging con enums

Si un status no aparece en la UI correcta:
1. Verificar valor en DB vs enum case
2. Verificar mapping en frontend (ej. columnas kanban en `board.tsx`)
3. Verificar transición en controller (¿se permite ese cambio?)

```bash
vendor/bin/sail artisan tinker --execute 'dump(App\Models\Order::find(1)->status);'
```
