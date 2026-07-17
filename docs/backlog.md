# Backlog tecnico por sprints

Cadencia propuesta: sprints de 2 semanas.

## Sprint 0

Objetivo:

- preparar el terreno tecnico y operativo.

Entregables:

- estructura del monorepo
- Docker Compose local
- convenciones de codigo
- README tecnico inicial
- contrato de carpetas y naming

## Sprint 1

Objetivo:

- base de seguridad y acceso.

Entregables:

- autenticacion con Sanctum
- roles y permisos
- middleware de proteccion
- seeders iniciales de usuarios de prueba

## Sprint 2

Objetivo:

- dominio de ejercicios e importacion del dataset.

Entregables:

- migracion base
- modelos de ejercicios, traducciones y media
- importador del dataset `exercises-dataset`
- validacion de integridad de datos

## Sprint 3

Objetivo:

- API de catalogo lista para consumo.

Entregables:

- listado de ejercicios
- detalle de ejercicio
- filtros y paginacion
- busqueda por nombre, body part, equipment y target
- soporte multilenguaje en lectura

## Sprint 4

Objetivo:

- backoffice v1.

Entregables:

- login de administracion
- dashboard base
- CRUD de ejercicios
- edicion de traducciones
- estados draft/review/published/archived

## Sprint 5

Objetivo:

- media, taxonomias y auditoria.

Entregables:

- carga de imagen y GIF
- validacion de media
- catalogos de taxonomia
- log de cambios
- importacion masiva controlada

## Sprint 6

Objetivo:

- cliente PWA v1.

Entregables:

- home
- busqueda
- filtros
- detalle de ejercicio
- favoritos
- login

## Sprint 7

Objetivo:

- rutinas y seguimiento.

Entregables:

- creacion de rutina
- registro de sesion
- historial
- progreso basico

## Sprint 8

Objetivo:

- observabilidad y optimizacion.

Entregables:

- eventos de analitica
- logs utiles
- consultas optimizadas
- hardening de permisos

## Sprint 9

Objetivo:

- estabilizacion final y release candidate.

Entregables:

- pruebas integrales
- correcciones UX
- revisiones de accesibilidad
- staging y checklist de produccion

