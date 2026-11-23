# Eventos, jobs y colas

## Events

Ubicación: `app/Events/`

| Event | Broadcast | Canales |
|---|---|---|
| `OrderChanged` | Sí (`ShouldBroadcast`) | `branch.{id}.orders`, `order.{id}` |
| `ChatMessageSent` | Sí | Canal de chat del pedido |

### OrderChanged

Evento central del sistema realtime.

```php
// Dispatch
OrderChanged::dispatch($order);

// Frontend escucha como:
// .listen('order.changed', callback)
```

Archivo: `app/Events/OrderChanged.php`

Broadcast name: `order.changed`

**Cuándo se dispara:** cada transición de estado de pedido (accept, in-kitchen, ready, assign, deliver, reject).

### ChatMessageSent

Para chat cliente-repartidor/sucursal en página de tracking.

## Jobs

Ubicación: `app/Jobs/`

| Job | Queue | Función |
|---|---|---|
| `SendOrderNotification` | default | Push notification al cambiar pedido |
| `SendReservationReminder` | default | Recordatorio reserva 24h (email branded) / 2h (SMS outbox) |
| `NotifyBranchStaffOfNewReservation` | default | Push staff ante reserva pendiente |

Correos branded (Notifications + markdown): ver [Correos branded](../domains/emails.md).

### SendOrderNotification

```
1. Determina destinatario (driver o customer)
2. Driver → ExpoPushSender
3. Customer → WebPushSender
4. Registra en notifications_outbox
```

**Producción:** requiere worker activo.

```bash
vendor/bin/sail artisan queue:work
# o en Railway: proceso separado queue worker
```

## Observers

| Observer | Modelos | Función |
|---|---|---|
| `TenantOnboardingObserver` | Branch, Category, Product | Actualiza progreso onboarding |

Registrado en `AppServiceProvider`.

## Listeners

| Listener | Event | Función |
|---|---|---|
| `UpdateUserLastLoginAt` | `Illuminate\Auth\Events\Login` | Actualiza `last_login_at` |

## Broadcasting setup

### Backend

`config/broadcasting.php` — driver `pusher` apuntando a soketi.

`.env`:
```env
BROADCAST_CONNECTION=pusher
PUSHER_APP_ID=app-id
PUSHER_APP_KEY=app-key
PUSHER_APP_SECRET=app-secret
PUSHER_HOST=127.0.0.1
PUSHER_PORT=6001
PUSHER_SCHEME=http
```

### Frontend

`resources/js/` — Laravel Echo + pusher-js.

Canal sucursal en `board.tsx`:
```typescript
Echo.private(`branch.${branchId}.orders`)
    .listen('.order.changed', (e) => { /* actualizar tablero */ });
```

### Autorización de canales

`routes/channels.php`:

```php
Broadcast::channel('branch.{branch}.orders', function (User $user, Branch $branch) {
    // admin, chain staff del tenant, branch users, drivers
});

Broadcast::channel('order.{order}', function (User $user, Order $order) {
    // participantes del pedido
});
```

## Scheduled commands

| Command | Schedule | Función |
|---|---|---|
| `billing:generate-invoices` | Daily 02:00 | Facturas suscripción |
| `orders:flag-stale-locations` | Every minute | GPS repartidor stale |

## Añadir un evento nuevo

```bash
vendor/bin/sail artisan make:event MiEvento
```

Para broadcast:
```bash
vendor/bin/sail artisan make:event MiEvento --broadcast
```

Patrón:
1. Crear event con `ShouldBroadcast` si aplica
2. Definir canal en `routes/channels.php`
3. Dispatch desde Service después de la operación
4. Escuchar en frontend con Echo
5. Test: `Event::fake()` o assert broadcast en Feature test

## Añadir un job nuevo

```bash
vendor/bin/sail artisan make:job MiJob
```

Dispatch:
```php
MiJob::dispatch($model);
// o
MiJob::dispatch($model)->afterCommit();
```

Preferir `afterCommit()` si el job depende de una transacción DB completada.

## Debugging realtime

| Problema | Verificar |
|---|---|
| No conecta Echo | soketi corriendo, PUSHER_* correctos |
| 403 en canal | Auth user + callback channels.php |
| Evento no llega | `BROADCAST_CONNECTION` != log/null |
| Delay | Queue worker no corriendo (solo afecta jobs, no broadcast sync) |

```bash
# Ver eventos broadcast en log (dev)
BROADCAST_CONNECTION=log
```
