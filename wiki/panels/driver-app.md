# App Repartidor

**Repo:** `sline-delivery-whitelabel-app/`  
**Stack:** Expo SDK 56, React Native  
**API:** `/api/v1/*` con Laravel Sanctum

## Propósito

App móvil white-label para repartidores: ver pedidos asignados, recoger, entregar, GPS tracking y push notifications.

## Setup

```bash
cd sline-delivery-whitelabel-app
npm install
EXPO_PUBLIC_API_URL=http://localhost/api/v1 npx expo start
```

## Estructura

```
src/
├── app/
│   ├── _layout.tsx       # Root layout
│   ├── index.tsx         # Entry redirect
│   ├── login.tsx         # Login
│   ├── home.tsx          # Dashboard
│   ├── orders.tsx        # Lista pedidos
│   └── order/[id].tsx    # Detalle pedido
├── lib/
│   ├── api.ts            # HTTP client (fetch + Sanctum)
│   ├── auth.tsx          # Auth context/provider
│   ├── push.ts           # Expo push registration
│   └── types.ts          # TypeScript types
├── components/
└── hooks/
```

## Autenticación

```
POST /api/v1/auth/login     # email + password → token
GET  /api/v1/me             # usuario actual
POST /api/v1/auth/logout    # invalidar token
```

Usuario debe ser `type=driver`, `is_active=true`.

Token Sanctum en header: `Authorization: Bearer {token}`

## Endpoints principales

| Method | Path | Función |
|---|---|---|
| POST | `auth/login` | Login (throttled) |
| GET | `me` | Perfil repartidor |
| POST | `auth/logout` | Logout |
| POST | `me/push-token` | Registrar token Expo push |
| POST | `shifts` | Iniciar turno |
| POST | `shifts/end` | Terminar turno |
| POST | `locations` | Ping GPS |
| GET | `orders` | Pedidos activos |
| POST | `orders/{order}/pickup` | Marcar recogido |
| POST | `orders/{order}/deliver` | Marcar entregado |

Archivo: `routes/api.php`  
Controllers: `app/Http/Controllers/Api/`

## Flujo de pedido (repartidor)

```
1. Sucursal asigna repartidor → status on_the_way
2. Push notification via ExpoPushSender
3. Repartidor ve pedido en app (GET /orders)
4. POST /orders/{id}/pickup → picked_up_at
5. POST /orders/{id}/deliver → delivered
   - Efectivo: registra cobro
   - Tarjeta: captura Stripe
6. OrderChanged broadcast → tracking cliente
```

## GPS tracking

```
POST /api/v1/locations
{ lat, lng, accuracy, heading, speed }
```

Almacenado en `driver_locations`.  
Command: `orders:flag-stale-locations` (cada minuto).

## Push notifications

- Expo Push API (sin server keys)
- Token registrado en `DriverProfile.push_tokens_json`
- Job: `SendOrderNotification` → `ExpoPushSender`

Producción: configurar EAS `projectId`.

## Turnos

```
POST /api/v1/shifts       # Iniciar
POST /api/v1/shifts/end   # Terminar
```

Modelo: `DriverShift`  
Afecta disponibilidad en `AvailableDriversQuery`.

## Controllers API

```
app/Http/Controllers/Api/
├── AuthController.php
├── DriverController.php       # shifts, locations, push-token
├── DriverOrderController.php  # pickup, deliver
└── StripeWebhookController.php
```

## Relación con webapp

| Webapp | App |
|---|---|
| Franquicia → Drivers | Alta repartidor |
| Sucursal → assign | Asignación pedido |
| OrderChanged event | Lista actualizada (polling o push) |
| DriverBalanceService | Balance visible en franquicia |

## Debugging app repartidor

| Problema | Verificar |
|---|---|
| Login 401 | Usuario driver, password, is_active |
| No ve pedidos | driver_id en order, status on_the_way |
| Push no llega | push-token registrado, SendOrderNotification |
| GPS no actualiza | POST /locations, permisos location |
| API unreachable | EXPO_PUBLIC_API_URL, CORS, Sanctum |

Test: `tests/Feature/DriverApiTest.php`

Typecheck:
```bash
cd sline-delivery-whitelabel-app && npx tsc --noEmit
```

## Añadir endpoint API

Ver [Nuevo endpoint API](../integration/api-endpoint.md).

## Añadir pantalla app

1. Crear screen en `src/app/`
2. Añadir método en `src/lib/api.ts`
3. Types en `src/lib/types.ts`
4. Test backend en `DriverApiTest.php`
