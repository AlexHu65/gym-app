# Despliegue — Desarrollo local

Ver también [Setup](../development/setup.md).

## Laravel Sail

```bash
cd sline-webapp
cp .env.example .env
composer install
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate:fresh --seed
./vendor/bin/sail npm install && ./vendor/bin/sail npm run dev
```

## Servicios

| Servicio | Container | Puerto |
|---|---|---|
| App | laravel.test | 80 |
| MySQL | mysql | 3306 |
| Redis | redis | 6379 |
| soketi | soketi | 6001 |
| Mailpit | mailpit | 8025 (UI) |
| MinIO | minio | 9000 |
| Meilisearch | meilisearch | 7700 |

## compose.yaml

Definición Docker en `sline-webapp/compose.yaml`.

## Comandos dentro del contenedor

```bash
# Alias útil
alias sail='./vendor/bin/sail'

sail artisan migrate
sail artisan test
sail artisan queue:work
sail npm run build
sail composer install
```

O via docker exec:
```bash
docker exec sline-webapp-laravel.test-1 php artisan test
```

## Hot reload

Terminal 1: `sail up`
Terminal 2: `sail npm run dev`
Terminal 3: `sail artisan queue:work` (si testeas push/jobs)

## Mail testing

Mailpit UI: http://localhost:8025

Todos los emails en dev van a Mailpit.

Probar cada correo branded (ver [Correos branded](../domains/emails.md)):

```bash
sail artisan mail:send-test chain-created --to=tu@email.com --chain=1
sail artisan mail:send-test onboarding-completed --to=tu@email.com --chain=1
sail artisan mail:send-test user-created --to=tu@email.com --chain=1
sail artisan mail:send-test reservation-accepted --to=tu@email.com --reservation=1
sail artisan mail:send-test reservation-reminder-24h --to=tu@email.com --reservation=1
```

Opcional: `TEST_MAIL_TO` en `.env` como default de `--to`.

## Storage

```bash
sail artisan storage:link  # si necesario para public
```

Archivos privados NO necesitan link — se sirven via rutas autenticadas.

## Problemas comunes

| Problema | Solución |
|---|---|
| mysql not found in container | docker network connect (ver README) |
| Port 80 in use | Cambiar APP_PORT en .env |
| Permission denied storage | sail artisan cache:clear, chmod storage |
| Vite manifest missing | npm run build |

## App Expo local

```bash
cd sline-delivery-whitelabel-app
EXPO_PUBLIC_API_URL=http://localhost/api/v1 npx expo start
```

Usar IP LAN para device físico: `http://192.168.x.x/api/v1`

## Calidad local

```bash
sail artisan test --compact
sail bin pint --dirty
sail npm run lint        # si configurado
./vendor/bin/phpstan analyse --memory-limit=1G
```
