# Testing

## Framework

**Pest 4** con plugin Livewire para tests Filament.

Config: `tests/Pest.php`, `phpunit.xml`

## Ejecutar tests

```bash
# Todos
vendor/bin/sail artisan test --compact

# Archivo específico
vendor/bin/sail artisan test --compact tests/Feature/SucursalPortalTest.php

# Filtro por nombre
vendor/bin/sail artisan test --compact --filter="accepts order"

# Suite staff/auth
vendor/bin/sail artisan test --compact --filter="UserProvisioning|ValidateUserMiddleware"
```

## Estructura

```
tests/
├── Feature/           # 70+ tests (principal)
│   ├── Auth/          # Flujos Fortify
│   └── Filament/      # Recursos Admin
├── Unit/              # 6 unit tests
└── TestCase.php
```

## Patrones comunes

### Setup con factories

```php
use App\Models\User;
use App\Models\Chain;

$chain = Chain::factory()->create();
$user = User::factory()->branch()->for($chain, 'chain')->create();

$this->actingAs($user);
```

Factory states disponibles en `UserFactory`: `admin()`, `chain()`, `branch()`, `driver()`, `customer()`.

### Test HTTP / Inertia

```php
$this->actingAs($branchUser)
    ->get(route('sucursal.board'))
    ->assertOk()
    ->assertInertia(fn ($page) => $page
        ->component('sucursal/board')
        ->has('orders')
    );
```

### Test Filament Livewire

```php
use function Pest\Livewire\livewire;

livewire(ListOrders::class)
    ->assertCanSeeTableRecords($orders)
    ->searchTable('DEMO1')
    ->assertCanSeeTableRecords([$order]);
```

### Test de servicio

```php
$order = app(PlaceOrder::class)->execute($branch, $customer, $cartData);

expect($order->status)->toBe(OrderStatus::Pending);
```

## Tests por dominio (referencia)

| Archivo | Cubre |
|---|---|
| `SucursalPortalTest.php` | Portal sucursal completo |
| `TiendaTest.php` | Catálogo, checkout, cupones |
| `DriverApiTest.php` | API repartidor Sanctum |
| `FranquiciaPanelTest.php` | Panel cadena |
| `StripePaymentTest.php` | Pagos tarjeta |
| `BillingCollectionTest.php` | Facturación SaaS |
| `PushNotificationTest.php` | Notificaciones push |
| `BrandedMailFlowTest.php` | Correos branded + `mail:send-test` |
| `ChainOnboardingTest.php` | Onboarding + mails cadena creada / activada |
| `Auth/UserProvisioningTest.php` | Invitación staff (rol + links) |
| `Auth/*` | Login, 2FA, password reset |

## CI/CD

`.github/workflows/tests.yml`:
- Matrix PHP 8.3, 8.4, 8.5
- `npm run build` + `./vendor/bin/pest`

`.github/workflows/lint.yml`:
- Pint, Prettier, ESLint

## Reglas del proyecto

1. **Todo cambio debe tener test** — escribir o actualizar test existente
2. Ejecutar el mínimo de tests necesarios antes de commit
3. No borrar tests sin aprobación
4. Preferir factories sobre `Model::create()` manual
5. `RefreshDatabase` en tests que modifican DB

## Crear un test nuevo

```bash
vendor/bin/sail artisan make:test --pest MiFeatureTest
```

## Larastan

Análisis estático con baseline:

```bash
vendor/bin/sail ./vendor/bin/phpstan analyse --memory-limit=1G
```

El baseline (`phpstan-baseline.neon`) suprime falsos positivos de Eloquent. Solo falla ante errores **nuevos**.

## Frontend typecheck

```bash
cd sline-delivery-whitelabel-app && npx tsc --noEmit
```

Para el webapp, ESLint/Prettier en CI.
