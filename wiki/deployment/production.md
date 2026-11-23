# Despliegue — Producción

## Railway

Deploy principal via Railway + Nixpacks.

### Archivos config

| Archivo | Función |
|---|---|
| `railway.json` | Builder Nixpacks, pre-deploy migrate |
| `nixpacks.toml` | PHP 8.3, composer --no-dev, npm ci build |

### Pre-deploy

```bash
php artisan migrate --force
```

Automático en cada deploy via `railway.json`.

### Build

1. `composer install --no-dev --optimize-autoloader`
2. `npm ci && npm run build`
3. Caché config/routes (si configurado)

### Proxy HTTPS

`bootstrap/app.php` trust all proxies — necesario detrás de Railway reverse proxy.

## Variables de entorno producción

### Obligatorias

```env
APP_KEY=
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.com

DB_CONNECTION=mysql
DB_HOST=
DB_PASSWORD=

BROADCAST_CONNECTION=pusher
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https

QUEUE_CONNECTION=redis
REDIS_HOST=
```

### Integraciones

```env
STRIPE_KEY=
STRIPE_SECRET=
STRIPE_WEBHOOK_SECRET=

VAPID_PUBLIC_KEY=
VAPID_PRIVATE_KEY=

GOOGLE_MAPS_API_KEY=

TENANT_ROOT_DOMAIN=tu-dominio.com
```

### Storage

Configurar S3-compatible storage (AWS S3, Railway volume, etc.):

```env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=
AWS_BUCKET=
```

## Procesos necesarios

| Proceso | Comando | Función |
|---|---|---|
| Web | `php-fpm` / nginx | HTTP |
| Queue worker | `php artisan queue:work --sleep=3` | Jobs, push |
| Scheduler | `* * * * * php artisan schedule:run` | Billing, GPS stale |
| soketi | soketi start | WebSockets |

Railway: configurar como services separados o multi-process.

## CI/CD

### Tests (`.github/workflows/tests.yml`)

Trigger: push/PR a main/develop

```
- Matrix PHP 8.3, 8.4, 8.5
- composer install
- npm ci && npm run build
- ./vendor/bin/pest
```

### Lint (`.github/workflows/lint.yml`)

```
- Pint (PHP)
- Prettier (JS/TS)
- ESLint
```

## Scheduled tasks

| Command | Schedule |
|---|---|
| `billing:generate-invoices` | Daily 02:00 |
| `orders:flag-stale-locations` | Every minute |

Requiere cron activo en producción.

## Expo app producción

1. EAS Build para iOS/Android
2. `EXPO_PUBLIC_API_URL=https://api.tu-dominio.com/api/v1`
3. Expo projectId para push

## Checklist deploy

- [ ] APP_DEBUG=false
- [ ] migrate --force en pre-deploy
- [ ] npm run build incluido en build
- [ ] Queue worker corriendo
- [ ] Scheduler/cron configurado
- [ ] soketi/Pusher accesible
- [ ] STRIPE webhook apuntando a prod URL
- [ ] VAPID keys generadas
- [ ] Storage S3 configurado
- [ ] TENANT_ROOT_DOMAIN correcto
- [ ] SSL/HTTPS activo

## Rollback

Railway permite rollback a deploy anterior desde dashboard.

Migraciones: **no** hacer rollback automático — crear migración forward fix.

## Monitoring

- Railway metrics (CPU, memory)
- `notifications_outbox` para push failures
- `audit_logs` para acciones admin
- Laravel log channel (config/logging.php)

## Alternativa: Laravel Cloud

El proyecto menciona Laravel Cloud como opción en AGENTS.md. Config actual está optimizada para Railway/Sail.
