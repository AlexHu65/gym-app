# Depuración — Guía para corregir bugs

## Metodología general

1. **Identificar la superficie** — ¿Admin, Franquicia, Sucursal, Tienda o API?
2. **Reproducir con datos demo** — `migrate:fresh --seed`
3. **Localizar la capa** — ruta → middleware → controller/service → model
4. **Buscar test existente** — si falla, el test documenta el bug
5. **Verificar tenant/branch context** — causa #1 de bugs multi-tenant

## Checklist por superficie

### Filament (Admin / Franquicia)

```
□ Usuario tiene type correcto y is_active=true
□ canAccessPanel() retorna true
□ ValidateUser: email verificado + password_changed_at
□ Query del resource respeta tenant_id (Franquicia)
□ Livewire errors en browser devtools
□ Test: livewire(ResourcePage::class)
```

Archivos a revisar:
- `app/Filament/{Admin|Franquicia}/Resources/`
- Panel provider en `app/Providers/Filament/`

### Portal Sucursal (Inertia)

```
□ Usuario type=branch con pivot branch_user
□ Session sucursal_branch_id válida
□ ResolveBranch establece $request->attributes->get('branch')
□ Props Inertia en controller (SucursalController, SucursalOrderController)
□ Echo conectado (PUSHER_* en .env, soketi corriendo)
□ npm run build actualizado
```

Archivos clave:
- `routes/sucursal.php`
- `app/Http/Controllers/Sucursal/`
- `resources/js/pages/sucursal/`

### Tienda cliente

```
□ Cadena operational (EnsureChainIsOperational)
□ BranchProductPresentation resuelve precio/nombre efectivo
□ PlaceOrder valida modificadores disponibles
□ Stripe keys o FakeGateway
□ Cupón válido para branch/fecha
```

Archivos clave:
- `app/Http/Controllers/Tienda/TiendaController.php`
- `app/Services/PlaceOrder.php`
- `app/Services/BranchProductPresentation.php`

### API Repartidor

```
□ Token Sanctum válido
□ Usuario type=driver, is_active
□ Order pertenece al tenant del driver
□ Push token registrado en DriverProfile
```

Archivos clave:
- `routes/api.php`
- `app/Http/Controllers/Api/`

## Bugs frecuentes y soluciones

### "No veo el pedido en el tablero"

| Verificar | Cómo |
|---|---|
| Branch correcta | Session `sucursal_branch_id` vs `order.branch_id` |
| Status en columna correcta | `OrderStatus` enum + mapping en `board.tsx` |
| Realtime | soketi corriendo, Echo config, canal `branch.{id}.orders` |
| Tenant scope | Order tiene `tenant_id` correcto |

### "Precio incorrecto en tienda vs sucursal"

Flujo: `branch_products.price_override` → `BranchProductPresentation` → TiendaController / PlaceOrder.

Ver [Overrides de producto](../sucursal/product-overrides.md).

### "403 Acceso restringido"

| Contexto | Causa |
|---|---|
| Sucursal | Usuario no es `type=branch` o sin pivot |
| Franquicia | Usuario no es `type=chain` |
| Tienda | Cadena pausada/suspendida |
| Cualquiera | ValidateUser bloqueando (email/password) |

### "Pago con tarjeta no funciona"

1. Verificar `STRIPE_*` en `.env`
2. Sin keys → `FakeGateway` (simula éxito)
3. Webhook: `POST /api/v1/payments/webhook/stripe`
4. Test: `StripePaymentTest.php`

### "Push no llega"

1. Cliente: VAPID keys configuradas, suscripción en browser
2. Repartidor: token Expo en `DriverProfile.push_tokens_json`
3. Cola: `queue:work` corriendo en producción
4. Log: tabla `notifications_outbox`

### "Cambio frontend no se ve"

```bash
vendor/bin/sail npm run build
# o npm run dev con hot reload
```

Error Vite manifest → rebuild necesario.

## Herramientas de diagnóstico

```bash
# Rutas
vendor/bin/sail artisan route:list --name=sucursal

# Config
vendor/bin/sail artisan config:show broadcasting

# Cola
vendor/bin/sail artisan queue:failed

# Logs
vendor/bin/sail artisan pail

# Tinker (lectura)
vendor/bin/sail artisan tinker --execute 'App\Models\Order::find(5)?->status'
```

### Laravel Boost MCP (en Cursor)

- `database-query` — consultas read-only
- `database-schema` — estructura de tablas
- `browser-logs` — errores JS recientes
- `get-absolute-url` — URLs correctas del entorno

## Trazar un flujo de pedido

```
TiendaController@checkout
  → PlaceOrder::execute()
    → Order + OrderItems creados
    → OrderChanged::dispatch()
      → broadcast branch.{id}.orders
      → SendOrderNotification job
SucursalController@board
  → query orders by branch_id + status
  → Inertia render board.tsx
SucursalOrderController@accept
  → status transition + OrderChanged
```

Tests de referencia: `SucursalPortalTest`, `TiendaTest`, `PushNotificationTest`.

## Cuando el bug es de datos

```bash
# Reset completo
vendor/bin/sail artisan migrate:fresh --seed

# Solo re-seed demo orders
vendor/bin/sail artisan db:seed --class=SucursalOrderDemoSeeder
```

## Escalar la investigación

1. Buscar enum/status involucrado en `app/Enums/`
2. Buscar service en `app/Services/`
3. Grep del route name: `rg "sucursal.orders.accept" sline-webapp/`
4. Leer test Feature correspondiente — describe comportamiento esperado
