# Backoffice Angular

Aplicacion administrativa para gestionar el catalogo de ejercicios y el contenido editorial.

Responsabilidades MVP:

- CRUD de ejercicios
- edicion de traducciones
- carga de media (MinIO)
- taxonomias
- importaciones
- publicacion (draft → published)
- auditoria basica

## Estado

Stub hasta **Sprint 3** (12–25 sep 2026). Los scripts de `package.json` son placeholders; aun no hay `ng serve` usable.

Calendario: [`../../docs/roadmap.md`](../../docs/roadmap.md)  
Arranque del monorepo: [`../../docs/getting-started.md`](../../docs/getting-started.md)  
API admin: [`../../docs/api-contract.md`](../../docs/api-contract.md)

## Cuando exista el scaffold real

```bash
cd frontend/admin
npm install
npm start
```

Puerto previsto: `4201`. API: `http://127.0.0.1:8000/api/v1`.

Login de prueba tras seed del backend: `admin@gym.local` / `Password123!`

## Entrada

- `src/main.ts` (placeholder actual)
- `src/app/` — shell, core y pages stub
