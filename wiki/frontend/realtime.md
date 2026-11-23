# Frontend — Tiempo real

## Stack

| Componente | Tecnología |
|---|---|
| Backend broadcast | Laravel Events + Pusher driver |
| WebSocket server | soketi (dev/prod) |
| Frontend client | Laravel Echo + pusher-js |

## Configuración

### Backend (.env)

```env
BROADCAST_CONNECTION=pusher
PUSHER_APP_ID=app-id
PUSHER_APP_KEY=app-key
PUSHER_APP_SECRET=app-secret
PUSHER_HOST=127.0.0.1
PUSHER_PORT=6001
PUSHER_SCHEME=http
```

`soketi` corre como servicio Docker en Sail.

### Frontend

Echo se inicializa en `resources/js/app.tsx` (o bootstrap file).

Variables Vite:
```env
VITE_PUSHER_APP_KEY=
VITE_PUSHER_HOST=
VITE_PUSHER_PORT=
```

## Evento principal: OrderChanged

```php
// Backend dispatch
OrderChanged::dispatch($order);
```

Broadcast:
- Name: `order.changed`
- Canales: `branch.{branch_id}.orders`, `order.{order_id}`

Archivo: `app/Events/OrderChanged.php`

## Canales y autorización

`routes/channels.php`:

```php
Broadcast::channel('branch.{branch}.orders', function (User $user, Branch $branch) {
    // Admin, chain staff, branch users, drivers del tenant
});

Broadcast::channel('order.{order}', function (User $user, Order $order) {
    // Participantes del pedido
});
```

## Uso en Sucursal (tablero)

`resources/js/pages/sucursal/board.tsx`:

```typescript
useEffect(() => {
    const channel = Echo.private(`branch.${branchId}.orders`);

    channel.listen('.order.changed', (event: OrderChangedEvent) => {
        // Actualizar lista de pedidos
        router.reload({ only: ['orders'] });
        // o merge optimista en state local
    });

    return () => {
        channel.stopListening('.order.changed');
        Echo.leave(`branch.${branchId}.orders`);
    };
}, [branchId]);
```

## Uso en Tienda (tracking)

`resources/js/pages/tienda/tracking.tsx`:

```typescript
Echo.private(`order.${orderId}`)
    .listen('.order.changed', (event) => {
        setOrderStatus(event.order.status);
    });
```

## Chat realtime

Event: `ChatMessageSent`  
Canal: asociado al pedido.

## Debugging realtime

| Problema | Solución |
|---|---|
| WebSocket no conecta | Verificar soketi running: `docker ps` |
| 403 subscribing | Auth + channels.php callback |
| Eventos no llegan | `BROADCAST_CONNECTION` != `log`/`null` |
| Delay | Normal si usa `router.reload` — considerar merge local |
| CORS/wss prod | `PUSHER_SCHEME=https`, proxy config |

### Dev: log driver

```env
BROADCAST_CONNECTION=log
```

Eventos se escriben en log, no en WebSocket. Útil para debug backend.

### Verificar soketi

```bash
docker ps | grep soketi
# Reiniciar si cambiaste PUSHER_*:
docker restart sline-webapp-soketi-1
```

## Cola vs broadcast

- **Broadcast** (OrderChanged): sincrónico via WebSocket — no necesita queue worker
- **Jobs** (SendOrderNotification): asíncrono — **sí** necesita `queue:work`

## Producción

Railway/prod:
1. soketi o Pusher Cloud configurado
2. Variables PUSHER_* en env
3. HTTPS → `PUSHER_SCHEME=https`, port 443
4. Queue worker para push notifications

## Añadir evento realtime

1. Event implements `ShouldBroadcast`
2. Definir `broadcastOn()`, `broadcastAs()`
3. Canal en `channels.php` con auth
4. Dispatch desde Service
5. Listen en frontend con Echo
6. Test backend (Event::fake o assert broadcast)

Ver [Eventos y jobs](../backend/events-jobs.md).

## Skill

Activar `echo-development` en `.agents/skills/` para broadcasting.
