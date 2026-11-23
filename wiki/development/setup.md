# Setup local

## Requisitos

- Docker Desktop
- Git

## Primer arranque

```bash
cd sline-webapp
cp .env.example .env
composer install
./vendor/bin/sail up -d
```

> **Importante**: ejecuta Artisan y Composer **dentro del contenedor Sail**, no en el host.

### Migraciones y datos demo

```bash
docker exec sline-webapp-laravel.test-1 php artisan migrate:fresh --seed
```

En producción el seeder solo carga planes + admin inicial.

### Frontend

```bash
docker exec sline-webapp-laravel.test-1 npm install
docker exec sline-webapp-laravel.test-1 npm run dev    # hot reload
# o
docker exec sline-webapp-laravel.test-1 npm run build  # producción local
```

## Servicios Docker (Sail)

| Servicio | Puerto | Uso |
|---|---|---|
| `laravel.test` | 80 | App Laravel |
| `mysql` | 3306 | Base de datos |
| `redis` | 6379 | Cache / colas |
| `soketi` | 6001 | WebSockets (Pusher) |
| `mailpit` | 8025 | Email testing (UI) |
| `minio` | 9000 | S3-compatible storage |
| `meilisearch` | 7700 | Búsqueda (reservado) |

### Gotcha: red Docker

Si `mysql` no resuelve dentro del contenedor:

```bash
docker network connect --alias mysql sline-webapp_sail sline-webapp-mysql-1
```

## Credenciales demo

| Rol | Email | Password | URL |
|---|---|---|---|
| Admin Sline | `admin@sline.app` | `password` | `/admin` |
| Dueño cadena | `owner@pollocrazy.mx` | `password` | `/franquicia` |
| Staff sucursal | `lead0@pollocrazy.mx` | `password` | `/sucursal` |
| Tienda cliente | — | — | `/t/pollo-crazy/1` |

## Variables de entorno críticas

Ver `.env.example`. Las más relevantes:

```env
# Base
APP_URL=http://localhost
TENANT_ROOT_DOMAIN=localhost

# Stripe (opcional en dev)
STRIPE_KEY=
STRIPE_SECRET=
STRIPE_WEBHOOK_SECRET=

# Web Push
VAPID_PUBLIC_KEY=
VAPID_PRIVATE_KEY=

# Realtime (soketi)
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=6001

# Maps (opcional)
GOOGLE_MAPS_API_KEY=
```

Sin `STRIPE_SECRET` se usa `FakeGateway` (pagos simulados).

Generar VAPID keys:

```bash
vendor/bin/sail artisan tinker --execute 'print_r(Minishlink\WebPush\VAPID::createVapidKeys());'
```

## App repartidor (Expo)

```bash
cd sline-delivery-whitelabel-app
npm install
EXPO_PUBLIC_API_URL=http://localhost/api/v1 npx expo start
```

Login con usuario `type=driver` del seeder demo.

## Comandos frecuentes

```bash
# Tests
vendor/bin/sail artisan test --compact

# Formato PHP
vendor/bin/sail bin pint --dirty

# Rutas
vendor/bin/sail artisan route:list

# Cola (desarrollo)
vendor/bin/sail artisan queue:work

# Wayfinder (generar rutas TS)
vendor/bin/sail artisan wayfinder:generate
```

## Flujo de desarrollo típico

1. Crear rama desde `develop` o `main`
2. Implementar cambio (ver [Guía de integración](../integration/adding-features.md))
3. Escribir/actualizar test Pest
4. `vendor/bin/sail artisan test --compact --filter=TuTest`
5. `vendor/bin/sail bin pint --dirty`
6. `vendor/bin/sail npm run build` si hay cambios frontend
