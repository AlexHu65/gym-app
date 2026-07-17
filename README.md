# Gym App Monorepo

Monorepo para una app de entrenamiento basada en `exercises-dataset`, con:

- backend en Laravel
- cliente PWA en Angular + Ionic
- backoffice en Angular
- base de datos PostgreSQL
- almacenamiento de media compatible con S3

## Estructura

- `backend/`: API Laravel y dominio de negocio
- `frontend/client/`: experiencia del usuario final
- `frontend/admin/`: backoffice administrable
- `infra/`: servicios locales y soporte de desarrollo
- `docs/`: backlog, arquitectura y contratos
- `exercises-dataset/`: fuente inicial del catalogo de ejercicios

## Primer objetivo

Levantar una base tecnica lista para:

1. importar el dataset completo de ejercicios;
2. exponer un API versionado;
3. operar contenido desde backoffice;
4. consumirlo desde la app PWA.

## Documentacion

- [Backlog tecnico](docs/backlog.md)
- [Arquitectura detallada](docs/architecture.md)
- [Contrato inicial de API](docs/api-contract.md)

