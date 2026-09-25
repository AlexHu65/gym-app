# Gym App

Monorepo de una plataforma de entrenamiento basada en [`exercises-dataset`](exercises-dataset/): catalogo multilenguaje (~1,324 ejercicios con imagen/GIF), API Laravel, backoffice Angular y cliente PWA Angular + Ionic.

**Capacidad:** 1 persona + Cursor/agente  
**Meta:** beta abierta en **enero 2027** (beta cerrada: dic 2026)

## Que estamos construyendo (MVP)

Usuario final:

- Buscar y filtrar ejercicios
- Ver detalle con media e instrucciones
- Favoritos
- Rutinas simples
- Registro de sesiones
- Login y selector de idioma

Equipo interno:

- CRUD de ejercicios y traducciones
- Publicacion editorial (draft → published)
- Media y taxonomias
- Importacion del dataset
- Auditoria basica

Fuera del MVP (post beta abierta): IA, wearables, pagos, social, video en vivo, gamificacion.

## Stack

| Capa | Tecnologia |
|------|------------|
| API | Laravel 13 + Sanctum |
| DB | PostgreSQL 16 |
| Cache / queues | Redis 7 |
| Media | MinIO (S3-compatible) |
| Cliente | Angular + Ionic (PWA) |
| Admin | Angular |
| Mail local | Mailpit |

## Estructura

```
backend/              API Laravel y dominio
frontend/client/      App usuario (stub hasta Sprint 6)
frontend/admin/       Backoffice (stub hasta Sprint 3)
exercises-dataset/    Fuente del catalogo
docs/                 Roadmap, arquitectura, contratos
infra/                Notas de infraestructura local
docker-compose.yml    Postgres, Redis, MinIO, Mailpit
```

## Estado actual (31 jul 2026)

Listo / esqueleto:

- Compose local
- Modelos, migraciones core, controllers API v1, seeders e importador
- Documentacion de producto y tecnica

Pendiente inmediato (Sprint 0 → 1):

1. Validar arranque local con Postgres (ver getting-started)
2. Endurecer auth/roles
3. Completar import + filtros de catalogo
4. Scaffold real del admin (a partir de sep 2026)

## Requisitos

- Docker / Docker Compose
- PHP 8.3+ y Composer 2
- Node 20+ (cuando existan los frontends reales)

## Arranque rapido

Guia completa: [`docs/getting-started.md`](docs/getting-started.md)

```bash
# 1. Infra
docker compose up -d

# 2. Backend
cd backend
cp .env.example .env
# Configura DB pgsql (gym/gym @ gym_app) — ver getting-started
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve --host=127.0.0.1 --port=8000
```

Smoke:

```bash
curl -s http://127.0.0.1:8000/api/v1/health
```

Admin por defecto tras seed: `admin@gym.local` / `Password123!`

### Puertos

| Servicio | Puerto |
|----------|--------|
| PostgreSQL | 5432 |
| Redis | 6379 |
| MinIO | 9000 (API), 9001 (consola) |
| Mailpit | 1025 (SMTP), 8025 (UI) |
| Laravel | 8000 |

## Documentacion

| Doc | Contenido |
|-----|-----------|
| [`docs/getting-started.md`](docs/getting-started.md) | Arranque y flujo diario |
| [`docs/roadmap.md`](docs/roadmap.md) | Calendario ago 2026 → ene 2027 |
| [`docs/backlog.md`](docs/backlog.md) | Sprints y entregables |
| [`docs/beta-plan.md`](docs/beta-plan.md) | Beta cerrada y abierta |
| [`docs/architecture.md`](docs/architecture.md) | Arquitectura y dominio |
| [`docs/api-contract.md`](docs/api-contract.md) | Contrato de API |
| [`docs/dev-conventions.md`](docs/dev-conventions.md) | Git, naming, checklist |
| [`plan-app-entrenamiento.md`](plan-app-entrenamiento.md) | PRD y vision de producto |

## Como programar (orden)

1. Leer roadmap del sprint actual.
2. Seguir convenciones en `docs/dev-conventions.md`.
3. Cambios pequenos, smoke local, commit claro.
4. Actualizar `docs/api-contract.md` si tocas endpoints.

Orden de construccion hasta la beta:

1. Fundacion API (ago–sep 2026)
2. Backoffice (sep–oct 2026)
3. Cliente PWA (oct–nov 2026)
4. Rutinas y sesiones (nov–dic 2026)
5. Beta cerrada (dic 2026) → beta abierta (ene 2027)

## Scripts raiz

```bash
npm run docs          # recordatorio de docs
npm run dev:backend   # puntero al backend
npm run dev:client    # puntero al client (stub)
npm run dev:admin     # puntero al admin (stub)
```

Los comandos reales de cada app viven en su carpeta; ver getting-started.
