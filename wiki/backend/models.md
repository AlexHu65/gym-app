# Modelos y relaciones

Ubicación: `sline-webapp/app/Models/` (58 modelos)

## Diagrama de entidades principal

```
Plan ──< Chain (tenant)
         │
         ├──< Branch ──< BranchHour, BranchHoliday
         │       │
         │       ├──< BranchProduct >── Product ──< Category
         │       ├──< BranchModifier >── Modifier ── ModifierGroup
         │       ├──< BranchReservationSetting, BranchReservationHour
         │       ├──< BranchReservationTable ── Reservation
         │       └──< Order ──< OrderItem, OrderStatusHistory
         │               ├── Payment, Rating, ChatMessage
         │               └── Shipment (opcional)
         │
         ├──< User (chain staff via tenant_id)
         │       └── TenantRole ── TenantPermission / TenantPermissionGroup
         ├──< Subscription ──< Invoice ──< InvoiceItem
         │                              └── InvoicePayment
         ├──< Coupon, Banner, ChainDocument
         ├──< Survey ── SurveyQuestion ── SurveyQuestionOption
         │                └── SurveyResponse ── SurveyResponseAnswer
         ├──< AbandonedCart
         ├──< ChainPaymentSetting
         ├──< ChainSettingGroup / ChainSettingDefinition
         │       └── ChainSettingProfile / ChainSettingValue
         └──< ChainCustomer >── Customer(User)

User (branch) >──< Branch  (pivot: branch_user)
User (driver) ── DriverProfile, DriverShift, DriverLocation
User (customer) ── CustomerProfile, CustomerAddress
```

## Modelos core

### Chain (tenant)

| Campo clave | Descripción |
|---|---|
| `slug` | URL tienda: `/t/{slug}/` |
| `status` | `ChainStatus` enum |
| `onboarding_step` | Progreso onboarding |
| `custom_domain` | White-label domain |
| `plan_id` | Plan SaaS activo |

Relaciones: `branches`, `categories`, `products`, `users`, `subscriptions`.

### Branch (sucursal)

| Campo clave | Descripción |
|---|---|
| `tenant_id` | FK Chain |
| `status` | `BranchStatus` |
| `lat`, `lng` | Geolocalización |
| `delivery_radius_km` | Zona de cobertura |
| `delivery_fee` | Costo envío base |

Relaciones: `hours`, `holidays`, `products` (via BranchProduct), `orders`, `users` (pivot),
`reservationSettings`, `reservationHours`, `reservationTables`, `reservations`.

Trait: `BelongsToTenant`

### User

Tabla unificada para todos los actores.

| Campo | Descripción |
|---|---|
| `type` | `UserType`: admin, chain, branch, driver, customer |
| `tenant_id` | Cadena (null para admin) |
| `role` | Rol específico del type (legacy slug en chain) |
| `tenant_role_id` | FK → `tenant_roles` (staff chain) |
| `is_active` | Acceso activo |
| `password_changed_at` | Gate ValidateUser |

Implementa: `FilamentUser`, `MustVerifyEmail`, `PasskeyUser`, `HasApiTokens`.

Métodos clave:
- `canAccessPanel(Panel)` — acceso Filament
- `canImpersonate()` — solo admin owner
- `branches()` — pivot para staff sucursal

### Order

| Campo clave | Descripción |
|---|---|
| `public_code` | Código visible (ej. DEMO1) |
| `status` | `OrderStatus` |
| `delivery_type` | `delivery`, `pickup` o `dine_in` |
| `scheduled_for` | Pedido programado (nullable) |
| `branch_id`, `customer_id`, `driver_id` | Actores |
| `payment_method` | cash / stripe / paypal |
| `estimated_ready_at` | ETA preparación |

Relaciones: `items`, `statusHistory`, `payment`, `driver`, `branch`, `customer`.

Métodos: `isScheduleLocked()` — locked si `scheduled_for` futuro.

### Product / BranchProduct

- `Product` — menú maestro de la cadena
- `BranchProduct` — overrides por sucursal (precio, nombre, disponibilidad)

Ver [Catálogo](../domains/catalog.md).

## Multi-tenancy

Modelos con `BelongsToTenant`:
`Branch`, `Category`, `Product`, `Order`, `Shipment`, `Coupon`, `Banner`, `ModifierGroup`

## Factories

`database/factories/`:

| Factory | States útiles |
|---|---|
| `UserFactory` | `admin()`, `chain()`, `branch()`, `driver()`, `customer()` |
| `ChainFactory` | cadena completa |
| `BranchFactory` | sucursal con geo |
| `OrderFactory` | pedido con items |
| `ProductFactory` | producto con categoría |

Uso en tests:

```php
$user = User::factory()->branch()->create();
$order = Order::factory()->for($branch)->create(['status' => OrderStatus::Pending]);
```

## Seeders

| Seeder | Cuándo corre |
|---|---|
| `PlanSeeder` | Siempre |
| `CountrySeeder`, `TimezoneSeeder` | Siempre |
| `AdminSeeder` | Siempre |
| `DemoSeeder` | Solo non-production |
| `SucursalOrderDemoSeeder` | Dentro de DemoSeeder |
| `DemoBillingSeeder` | Datos facturación demo |

## Convenciones Eloquent

- `$guarded = ['id']` en la mayoría de modelos
- Casts via método `casts(): array` (Laravel 13)
- Enums backed en columnas de estado/tipo
- Soft deletes en `User`
- Hidden attributes via `#[Hidden(...)]` en User

## Añadir un modelo nuevo

Ver [Migraciones y modelos](../integration/migrations.md).

Pasos:
1. `vendor/bin/sail artisan make:model MiModelo -mf`
2. Si es multi-tenant: trait `BelongsToTenant` + columna `tenant_id`
3. Factory con states relevantes
4. Seeder si necesita datos base
