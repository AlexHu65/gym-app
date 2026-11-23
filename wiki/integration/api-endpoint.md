# Integración: Nuevo endpoint API

## Cuándo usar

Features para la app repartidor Expo u otros clientes API.

## Convenciones

- Prefix: `/api/v1/` (configurado en `bootstrap/app.php`)
- Auth: Laravel Sanctum Bearer token
- Respuestas: JSON
- Controller en `app/Http/Controllers/Api/`

## Crear endpoint

### 1. Ruta

```php
// routes/api.php
Route::middleware('auth:sanctum')->group(function () {
    Route::get('mi-recurso', [MiController::class, 'index']);
    Route::post('mi-recurso', [MiController::class, 'store']);
    Route::post('orders/{order}/mi-accion', [DriverOrderController::class, 'miAccion']);
});
```

### 2. Controller

```php
namespace App\Http\Controllers\Api;

class MiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // Validar type
        if ($user->type !== UserType::Driver) {
            abort(403);
        }

        return response()->json([
            'data' => MiService::listar($user),
        ]);
    }
}
```

### 3. Service (si lógica compleja)

```php
// app/Services/MiService.php
class MiService
{
    public function listar(User $driver): Collection { ... }
}
```

### 4. Test

```php
// tests/Feature/DriverApiTest.php o MiApiTest.php
$user = User::factory()->driver()->create();
Sanctum::actingAs($user);

$this->getJson('/api/v1/mi-recurso')
    ->assertOk()
    ->assertJsonStructure(['data']);
```

## Auth

### Login

```
POST /api/v1/auth/login
{ "email": "...", "password": "..." }

Response: { "token": "...", "user": {...} }
```

Throttled. Controller: `AuthController`.

### Requests autenticados

Header: `Authorization: Bearer {token}`

### Logout

```
POST /api/v1/auth/logout
```

## Rate limiting

Login throttled en routes. Para nuevos endpoints sensibles, añadir throttle en route group.

## Validación

```php
$validated = $request->validate([
    'lat' => ['required', 'numeric'],
    'lng' => ['required', 'numeric'],
]);
```

Preferir Form Request si validación compleja:

```bash
vendor/bin/sail artisan make:request Api/MiRequest
```

## Autorización

Sin Policies — verificar manualmente:

```php
// Usuario es driver activo
if ($user->type !== UserType::Driver || ! $user->is_active) {
    abort(403);
}

// Recurso pertenece al tenant
if ($order->tenant_id !== $user->tenant_id) {
    abort(404);
}

// Driver asignado al pedido
if ($order->driver_id !== $user->id) {
    abort(403);
}
```

## App Expo — consumir endpoint

```typescript
// src/lib/api.ts
export async function miRecurso(token: string) {
    const res = await fetch(`${API_URL}/mi-recurso`, {
        headers: { Authorization: `Bearer ${token}` },
    });
    return res.json();
}
```

Types en `src/lib/types.ts`.

## Webhook (sin auth Sanctum)

```php
// routes/api.php — fuera del group auth
Route::post('payments/webhook/stripe', StripeWebhookController::class);
```

Verificar signature del provider.

## Checklist

- [ ] Ruta en api.php con prefix v1
- [ ] Auth sanctum (excepto webhooks)
- [ ] Validación input
- [ ] Autorización tenant + type
- [ ] JSON response consistente
- [ ] Feature test con Sanctum::actingAs
- [ ] App client actualizado (si aplica)

## Referencia

- [App repartidor](../panels/driver-app.md)
- `tests/Feature/DriverApiTest.php`
