# Integración: Push notifications

## Dos canales

| Destinatario | Tecnología | Sender |
|---|---|---|
| Cliente (browser) | Web Push (VAPID) | `WebPushSender` |
| Repartidor (app) | Expo Push | `ExpoPushSender` |

## Job central

`SendOrderNotification` — encolado al cambiar estado pedido.

```
1. Determina destinatario (customer o driver)
2. Construye payload
3. Envía via sender correspondiente
4. Log en notifications_outbox
```

Archivo: `app/Jobs/SendOrderNotification.php`

**Requiere queue worker en producción.**

## Web Push (cliente)

### Config

```env
VAPID_PUBLIC_KEY=
VAPID_PRIVATE_KEY=
VAPID_SUBJECT=mailto:admin@sline.app
```

Generar keys:
```bash
vendor/bin/sail artisan tinker --execute 'print_r(Minishlink\WebPush\VAPID::createVapidKeys());'
```

Package: `minishlink/web-push`

### Frontend

`resources/js/lib/web-push.ts` — suscripción en tracking page.

Flow:
1. Cliente acepta permisos browser
2. Subscribe con VAPID public key
3. POST subscription al backend
4. Backend almacena endpoint + keys

### Sender

`app/Support/Push/WebPushSender.php`

## Expo Push (repartidor)

No requiere server API keys — usa Expo Push service.

### Registro token

```
POST /api/v1/me/push-token
{ "token": "ExponentPushToken[...]" }
```

Almacenado en `DriverProfile.push_tokens_json`.

### Sender

`app/Support/Push/ExpoPushSender.php`

### App config

Producción: EAS `projectId` en build Expo.

## Outbox log

Tabla `notifications_outbox`:
- channel, status, payload, error
- Enum: `NotificationChannel`, `NotificationStatus`

Útil para debug de push fallidos.

## Notifier service

`app/Services/Notifier.php` — dispatch centralizado (email, push, etc.)

## Debugging

| Problema | Cliente Web | Repartidor Expo |
|---|---|---|
| No llega push | VAPID keys, browser permission | push-token registrado |
| Parcial | Service worker | Token expired |
| Delay | Queue worker | Queue worker |
| Error log | notifications_outbox | notifications_outbox |

```bash
# Ver últimos outbox
vendor/bin/sail artisan tinker --execute 'App\Models\NotificationOutbox::latest()->take(5)->get();'

# Queue worker
vendor/bin/sail artisan queue:work
```

## Testing

`tests/Feature/PushNotificationTest.php`

Usa `Queue::fake()` o mock senders.

## Añadir push a nuevo evento

1. Dispatch `SendOrderNotification` o crear job similar
2. Extender sender con payload type
3. Frontend: suscripción/registro token
4. Test con Queue::fake()
