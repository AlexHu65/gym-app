# Client PWA

App del usuario final: Angular + Ionic (PWA).

Objetivo MVP:

- explorar el catalogo de ejercicios
- ver detalle con media e instrucciones
- guardar favoritos
- crear rutinas simples
- registrar sesiones de entrenamiento
- login y selector de idioma

## Estado

Stub hasta **Sprint 6** (24 oct – 6 nov 2026). Los scripts de `package.json` son placeholders; aun no hay `ng serve` usable.

Calendario: [`../../docs/roadmap.md`](../../docs/roadmap.md)  
Arranque del monorepo: [`../../docs/getting-started.md`](../../docs/getting-started.md)

## Cuando exista el scaffold real

```bash
cd frontend/client
npm install
npm start
```

Puerto previsto: `4200`. API: `http://127.0.0.1:8000/api/v1`.

## Rutas base previstas

- `/`
- `/exercises`
- `/exercises/:id`
- `/favorites`
- `/workouts`
- `/login`

## Entrada

- `src/main.ts` (placeholder actual)
- `src/app/` — shell, core y pages stub
