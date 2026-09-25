# Getting started — gym-app

Guia para levantar el monorepo en local y empezar a programar (1 persona + Cursor).

Para el calendario y prioridades ver [`roadmap.md`](roadmap.md).
Para el contrato de API ver [`api-contract.md`](api-contract.md).

## Requisitos

| Herramienta | Version minima sugerida |
|-------------|-------------------------|
| Docker Desktop / Compose | reciente |
| PHP | 8.3+ |
| Composer | 2.x |
| Node.js | 20 LTS (cuando se scaffolden los frontends reales) |
| npm | 10+ |

Opcional: `psql`, cliente S3/MinIO, Postman/Insomnia.

## Estructura relevante

```
gym-app/
├── backend/              # Laravel API
├── frontend/client/      # Angular + Ionic (PWA) — stub hasta Sprint 6
├── frontend/admin/       # Angular backoffice — stub hasta Sprint 3
├── exercises-dataset/    # Catalogo fuente (JSON + media)
├── docs/                 # Arquitectura, roadmap, contratos
├── infra/                # Notas de infraestructura local
└── docker-compose.yml    # Postgres, Redis, MinIO, Mailpit
```

## Puertos locales

| Servicio | Puerto | URL / uso |
|----------|--------|-----------|
| PostgreSQL | 5432 | `gym` / `gym` / DB `gym_app` |
| Redis | 6379 | cache / queues |
| MinIO API | 9000 | object storage S3-compatible |
| MinIO Console | 9001 | http://localhost:9001 (`gymadmin` / `gymadmin123`) |
| Mailpit SMTP | 1025 | correo de desarrollo |
| Mailpit UI | 8025 | http://localhost:8025 |
| Laravel API | 8000 | http://localhost:8000 |
| Client PWA | 4200 | cuando exista scaffold real |
| Admin | 4201 | cuando exista scaffold real |

## Arranque en orden

### 1. Infraestructura

Desde la raiz del monorepo:

```bash
docker compose up -d
docker compose ps
```

Espera a que Postgres este healthy antes de migrar.

### 2. Backend Laravel

```bash
cd backend
cp .env.example .env   # solo la primera vez
```

Configura al menos:

```env
APP_NAME="Gym App"
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=gym_app
DB_USERNAME=gym
DB_PASSWORD=gym

CACHE_STORE=redis
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379

FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=gymadmin
AWS_SECRET_ACCESS_KEY=gymadmin123
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=gym-media
AWS_ENDPOINT=http://127.0.0.1:9000
AWS_USE_PATH_STYLE_ENDPOINT=true

MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_FROM_ADDRESS="noreply@gym.local"
MAIL_FROM_NAME="${APP_NAME}"

ADMIN_EMAIL=admin@gym.local
ADMIN_NAME="Gym Admin"
ADMIN_PASSWORD=Password123!
```

Luego:

```bash
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve --host=127.0.0.1 --port=8000
```

Notas:

- `db:seed` corre roles, usuario admin e intenta importar `exercises-dataset/data/exercises.json`.
- La importacion puede tardar: el dataset tiene ~1,324 ejercicios.
- Si solo quieres roles + admin sin dataset: `php artisan db:seed --class=RolesAndPermissionsSeeder` y `php artisan db:seed --class=AdminUserSeeder`.

Smoke test:

```bash
curl -s http://127.0.0.1:8000/api/v1/health
curl -s "http://127.0.0.1:8000/api/v1/exercises?per_page=5"
```

Login de prueba (despues de seed):

- Email: `admin@gym.local`
- Password: `Password123!` (o el valor de `ADMIN_PASSWORD`)

```bash
curl -s -X POST http://127.0.0.1:8000/api/v1/auth/login \
  -H 'Content-Type: application/json' \
  -H 'Accept: application/json' \
  -d '{"email":"admin@gym.local","password":"Password123!"}'
```

### 3. Bucket MinIO (media)

1. Abre http://localhost:9001
2. Login con `gymadmin` / `gymadmin123`
3. Crea el bucket `gym-media` (o el nombre de `AWS_BUCKET`)
4. Politica de lectura publica solo si vas a servir URLs directas en local

### 4. Frontends

Hoy `frontend/client` y `frontend/admin` son stubs (scripts placeholder). Hasta el scaffold real (Sprints 3 y 6):

```bash
# No hay `ng serve` usable todavia.
# Trabaja contra la API con curl / cliente HTTP.
```

Cuando existan los scaffolds:

```bash
# Client (previsto)
cd frontend/client && npm install && npm start

# Admin (previsto)
cd frontend/admin && npm install && npm start
```

Actualiza esta seccion en el mismo PR que cree el scaffold Angular real.

## Flujo diario de desarrollo

1. `docker compose up -d` (si no esta corriendo).
2. Backend: `cd backend && php artisan serve`.
3. Si usas queues: `php artisan queue:work` en otra terminal.
4. Trabaja en una rama `feature/...` o `fix/...` (ver [`dev-conventions.md`](dev-conventions.md)).
5. Antes de cerrar: migraciones nuevas, tests relevantes, actualizar contrato API si cambio endpoints.

## Comandos utiles (backend)

```bash
php artisan migrate
php artisan migrate:fresh --seed   # destruye datos locales
php artisan db:seed --class=ExerciseDatasetSeeder
php artisan test
php artisan route:list --path=api
./vendor/bin/pint --dirty
```

## Que programar ahora

Segun [`roadmap.md`](roadmap.md):

1. Cerrar Sprint 0: validar este getting-started en tu maquina.
2. Sprint 1: endurecer Sanctum, roles y seeders hasta que login/me sean estables.
3. Sprint 2: import idempotente + filtros de catalogo completos.

No empieces el scaffold del admin hasta tener catalogo + auth estables.

## Problemas frecuentes

| Sintoma | Que revisar |
|---------|-------------|
| Connection refused Postgres | `docker compose ps`, puerto 5432 ocupado |
| Migraciones fallan con sqlite | `DB_CONNECTION=pgsql` en `.env` |
| Seed sin ejercicios | Existe `exercises-dataset/data/exercises.json` relativo a `backend/` |
| CORS en front futuro | Configurar dominios de client/admin en Sanctum / middleware CORS |
| MinIO 403 | Endpoint path-style, bucket creado, credenciales en `.env` |

## Documentacion relacionada

- [`architecture.md`](architecture.md) — diagramas y dominio
- [`api-contract.md`](api-contract.md) — endpoints
- [`backlog.md`](backlog.md) — sprints detallados
- [`beta-plan.md`](beta-plan.md) — betas cerrada y abierta
- [`dev-conventions.md`](dev-conventions.md) — git, naming, checklist
