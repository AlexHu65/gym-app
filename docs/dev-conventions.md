# Convenciones de desarrollo

Reglas ligeras para trabajar solo + Cursor sin perder trazabilidad.

## Git

### Ramas

| Prefijo | Uso |
|---------|-----|
| `main` | Estable; lo que puede ir a staging |
| `feature/<tema>` | Nueva capacidad (ej. `feature/exercise-filters`) |
| `fix/<tema>` | Correccion (ej. `fix/login-cors`) |
| `docs/<tema>` | Solo documentacion |
| `chore/<tema>` | Tooling, deps, limpieza |

Flujo sugerido:

1. Partir de `main` actualizado.
2. Una rama por objetivo de sprint o subtarea clara.
3. Merge a `main` cuando el objetivo este done (tests/smoke OK).

Evitar ramas eternas. Si una rama supera ~2 semanas, partir o mergear parciales.

### Commits

- Mensajes en espanol o ingles, pero consistentes en el repo (preferencia: espanol claro o ingles corto).
- Forma: `tipo: resumen en imperativo`
  - `feat: filtrar ejercicios por equipment`
  - `fix: corregir logout de Sanctum`
  - `docs: actualizar roadmap de beta abierta`
  - `chore: alinear .env.example con Postgres`
- Un commit = un cambio coherente. No mezclar refactor grande + feature.

### Que no commitear

- `.env`, secretos, dumps de DB
- Media pesada del dataset si ya vive en `exercises-dataset/`
- `node_modules/`, `vendor/` (ya ignorados)

## API

- Prefijo versionado: `/api/v1/...`
- Respuestas JSON via API Resources cuando existan
- Errores con codigo HTTP correcto y cuerpo predecible
- Cambios breaking → nueva version (`v2`) o periodo de deprecacion documentado en [`api-contract.md`](api-contract.md)
- Toda ruta nueva o cambiada se refleja en el contrato en el mismo PR/commit de docs

## Backend (Laravel)

- Controllers delgados; logica de dominio en Services / Actions
- Validacion con Form Requests
- Autorizacion con Policies / Gates + roles seedados
- Migraciones siempre reversibles cuando sea practico
- Importaciones pesadas via Jobs cuando el flujo deje de ser seed-only
- Nombres:
  - Modelos: singular PascalCase (`Exercise`)
  - Tablas: plural snake_case (`exercise_translations`)
  - Rutas admin bajo `/api/v1/admin/...`

## Frontend

Hasta el scaffold real, no inventar estructura paralela.

Cuando existan apps:

- `frontend/client`: Angular + Ionic, mobile-first / PWA
- `frontend/admin`: Angular web, sin Ionic
- Carpetas tipicas: `core/` (auth, http, guards), `pages/`, `shared/`
- No compartir UI obligatoriamente entre client y admin en el MVP; compartir tipos solo si reduce friccion

## Idiomas y contenido

- Locale por defecto del producto: definir en sprint de client (es/en como minimo viable)
- Textos de ejercicio viven en `exercise_translations`, no hardcodeados en UI
- UI chrome puede ir en i18n del frontend

## Checklist antes de mergear a `main`

- [ ] Arranca en local siguiendo [`getting-started.md`](getting-started.md)
- [ ] Migraciones aplicadas sin error
- [ ] Smoke de endpoints tocados (`curl` o test)
- [ ] `php artisan test` en verde si hay tests del area
- [ ] Contrato API actualizado si cambio superficie publica
- [ ] Sin secretos en el diff
- [ ] Scope alineado al sprint actual del [`roadmap.md`](roadmap.md)

## Trabajo con Cursor / agentes

- Pegar el objetivo del sprint y el criterio Done when del roadmap
- Pedir cambios pequenos y revisables
- No dejar que el agente “complete el MVP” en un solo paso
- Tras cada bloque: correr smoke local antes del siguiente prompt

## Issues / tareas

Formato minimo de una tarea:

```text
Titulo:
Sprint:
Objetivo:
Criterio de done:
Fuera de alcance:
```

El detalle por sprint esta en [`backlog.md`](backlog.md).
