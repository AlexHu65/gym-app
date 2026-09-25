# Backend — Gym App API

API Laravel del monorepo gym-app. Expone `/api/v1` para cliente, admin e importacion del catalogo.

Guia completa del monorepo: [`../docs/getting-started.md`](../docs/getting-started.md)

## Stack

- Laravel 13
- Sanctum
- PostgreSQL (Compose en la raiz)
- Redis / MinIO / Mailpit en local

## Arranque

Desde la raiz:

```bash
docker compose up -d
```

Luego:

```bash
cd backend
cp .env.example .env   # primera vez
# Configurar DB pgsql: gym / gym / gym_app — ver docs/getting-started.md
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

Admin por defecto (seed): `admin@gym.local` / `Password123!`

## Comandos utiles

```bash
php artisan route:list --path=api
php artisan db:seed --class=ExerciseDatasetSeeder
php artisan test
./vendor/bin/pint --dirty
```

## Layout relevante

- `app/Models` — dominio
- `app/Http/Controllers/Api/V1` — API publica y auth
- `app/Http/Controllers/Api/V1/Admin` — backoffice
- `app/Services/ExerciseImportService.php` — import del dataset
- `database/seeders` — roles, admin, dataset
- `routes/api.php` — rutas versionadas

## Contrato

Ver [`../docs/api-contract.md`](../docs/api-contract.md) y [`../docs/architecture.md`](../docs/architecture.md).

## Nota

Este README sustituye el README generico de Laravel del scaffold. Para documentacion del framework: https://laravel.com/docs
