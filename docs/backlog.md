# Backlog tecnico por sprints

Cadencia: sprints de 2 semanas.  
Capacidad: 1 persona + Cursor/agente.  
Fuente de fechas: [`roadmap.md`](roadmap.md).  
Beta abierta: enero 2027.

## Sprint 0 — Docs y arranque

| | |
|---|---|
| Fechas | 1–14 ago 2026 |
| Objetivo | terreno tecnico y operativo listo |

Entregables:

- [x] estructura del monorepo
- [x] Docker Compose local
- [ ] README operativo validado en maquina local
- [ ] getting-started seguido end-to-end
- [ ] `.env` backend con Postgres (no sqlite)
- [ ] smoke `GET /api/v1/health`
- [x] convenciones de codigo documentadas
- [x] contrato de carpetas y naming

## Sprint 1 — Auth y acceso

| | |
|---|---|
| Fechas | 15–28 ago 2026 |
| Objetivo | base de seguridad y acceso |

Entregables:

- autenticacion Sanctum estable (login / logout / me)
- roles y permisos seedados
- middleware / policies de proteccion
- seeders de usuarios de prueba (admin)
- pruebas basicas de auth

## Sprint 2 — Dominio e importacion

| | |
|---|---|
| Fechas | 29 ago – 11 sep 2026 |
| Objetivo | ejercicios + dataset importable |

Entregables:

- migraciones core estables
- modelos ejercicios, traducciones, media, taxonomias
- importador idempotente de `exercises-dataset`
- validacion de integridad basica
- listado, detalle, filtros, paginacion, multilenguaje en lectura

## Sprint 3 — Admin scaffold

| | |
|---|---|
| Fechas | 12–25 sep 2026 |
| Objetivo | backoffice arrancable |

Entregables:

- scaffold real Angular admin
- login de administracion
- shell, routing y dashboard base
- consumo de API auth + listado admin

## Sprint 4 — CRUD editorial

| | |
|---|---|
| Fechas | 26 sep – 9 oct 2026 |
| Objetivo | editar y publicar contenido |

Entregables:

- CRUD de ejercicios
- edicion de traducciones
- estados draft / review / published / archived
- busqueda y filtros internos

## Sprint 5 — Media, taxonomias, auditoria

| | |
|---|---|
| Fechas | 10–23 oct 2026 |
| Objetivo | operar media y calidad editorial |

Entregables:

- carga de imagen y GIF (MinIO)
- validacion de media
- catalogos de taxonomia en UI
- log de cambios
- importacion masiva controlada desde admin

## Sprint 6 — Cliente PWA catalogo

| | |
|---|---|
| Fechas | 24 oct – 6 nov 2026 |
| Objetivo | experiencia de descubrimiento |

Entregables:

- scaffold real Angular + Ionic
- home
- busqueda y filtros
- detalle de ejercicio con media

## Sprint 7 — Favoritos, auth client, idioma

| | |
|---|---|
| Fechas | 7–20 nov 2026 |
| Objetivo | cuenta y personalizacion basica |

Entregables:

- login / registro en client
- favoritos
- selector de idioma
- sesion persistente

## Sprint 8 — Rutinas y seguimiento

| | |
|---|---|
| Fechas | 21 nov – 11 dic 2026 |
| Objetivo | valor de entrenamiento |

Entregables:

- creacion de rutina simple
- registro de sesion
- historial minimo
- progreso basico (conteos)

## Sprint 9 — Beta cerrada

| | |
|---|---|
| Fechas | 12–31 dic 2026 |
| Objetivo | estabilizacion y release candidate cerrado |

Entregables:

- pruebas integrales / E2E manuales
- correcciones UX criticas
- hardening de permisos
- staging y checklist de [`beta-plan.md`](beta-plan.md)
- 5–15 testers invitados

## Enero 2027 — Beta abierta

| | |
|---|---|
| Fechas | 1–31 ene 2027 |
| Objetivo | registro publico controlado |

Entregables:

- landing / onboarding minimo
- monitoreo y logs basicos
- canal de feedback unico
- metricas minimas (registros, vistas, sesiones, D7)
- solo bugs y P0 (feature freeze)

Ver checklist completo en [`beta-plan.md`](beta-plan.md).
