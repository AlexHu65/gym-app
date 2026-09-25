# Infraestructura local

Base operativa del entorno de desarrollo del monorepo.

## Contenido actual

La composicion vive en la raiz:

- [`../docker-compose.yml`](../docker-compose.yml)

Servicios:

| Servicio | Puerto | Credenciales / notas |
|----------|--------|----------------------|
| PostgreSQL 16 | 5432 | user/pass `gym`, DB `gym_app` |
| Redis 7 | 6379 | sin password |
| MinIO | 9000 / 9001 | `gymadmin` / `gymadmin123` |
| Mailpit | 1025 / 8025 | UI en http://localhost:8025 |

## Arranque

Desde la raiz del monorepo:

```bash
docker compose up -d
docker compose ps
docker compose logs -f postgres
```

Despues configura el backend segun [`../docs/getting-started.md`](../docs/getting-started.md).

## MinIO

1. http://localhost:9001
2. Crear bucket `gym-media` (o el de `AWS_BUCKET`)
3. Apuntar Laravel con endpoint path-style a `http://127.0.0.1:9000`

## Siguientes pasos (ops)

- Variables de entorno por ambiente (staging / prod) documentadas junto al beta-plan
- Scripts de healthcheck / validacion de Compose
- Backups de Postgres en staging antes de beta cerrada (dic 2026)

Roadmap: [`../docs/roadmap.md`](../docs/roadmap.md)
