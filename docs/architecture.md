# Arquitectura detallada

## Visión general

La plataforma se compone de tres superficies principales:

- `backend`: API Laravel
- `client`: app PWA con Angular + Ionic
- `admin`: backoffice Angular

Servicios de soporte:

- PostgreSQL para datos transaccionales
- Redis para cache, jobs y colas
- S3 compatible para imagenes y GIFs
- Mailpit para desarrollo local

## Diagrama de contexto

```mermaid
flowchart LR
  User["Usuario final"]
  Editor["Editor / Admin"]
  Client["Angular + Ionic PWA"]
  Backoffice["Angular Admin"]
  API["Laravel API"]
  DB["PostgreSQL"]
  Cache["Redis"]
  Media["S3 / MinIO"]

  User --> Client
  Editor --> Backoffice
  Client --> API
  Backoffice --> API
  API --> DB
  API --> Cache
  API --> Media
```

## Diagrama de contenedores

```mermaid
flowchart TB
  subgraph Frontends
    Client["client app"]
    Admin["admin app"]
  end

  subgraph Backend
    API["Laravel API"]
    Queue["Queue workers"]
    Scheduler["Scheduler"]
  end

  subgraph Infra
    PG["PostgreSQL"]
    Redis["Redis"]
    S3["Object Storage"]
  end

  Client --> API
  Admin --> API
  API --> PG
  API --> Redis
  API --> S3
  Queue --> Redis
  Scheduler --> PG
```

## Modelo de dominio

```mermaid
erDiagram
  USERS ||--o{ FAVORITES : has
  USERS ||--o{ WORKOUT_PLANS : owns
  USERS ||--o{ WORKOUT_SESSIONS : logs
  EXERCISES ||--o{ EXERCISE_TRANSLATIONS : has
  EXERCISES ||--o{ EXERCISE_MEDIA : has
  EXERCISES ||--o{ FAVORITES : in
  EXERCISES ||--o{ WORKOUT_PLAN_ITEMS : in
  EXERCISES ||--o{ WORKOUT_SESSION_ITEMS : in
  USERS ||--o{ AUDIT_LOGS : performs
  IMPORT_BATCHES ||--o{ EXERCISES : seeds
```

## Flujo de importacion

```mermaid
sequenceDiagram
  actor Admin
  participant UI as Backoffice
  participant API as Laravel API
  participant JOB as Import Job
  participant DB as PostgreSQL
  participant S3 as Object Storage

  Admin->>UI: Subir dataset o iniciar importacion
  UI->>API: POST /api/v1/admin/imports/exercises
  API->>JOB: Encolar importacion
  JOB->>DB: Crear/actualizar ejercicios
  JOB->>S3: Registrar media referenciada
  JOB->>DB: Guardar resumen y errores
  JOB-->>API: Resultado
  API-->>UI: Estado de importacion
```

## Estrategia de persistencia

### Tablas principales

- `users`
- `roles`
- `permissions`
- `exercises`
- `exercise_translations`
- `exercise_media`
- `taxonomy_terms`
- `favorites`
- `workout_plans`
- `workout_plan_items`
- `workout_sessions`
- `workout_session_items`
- `audit_logs`
- `import_batches`

### Decisiones de modelado

- `exercises` guarda el registro canónico del ejercicio.
- `exercise_translations` guarda texto e instrucciones por locale.
- `exercise_media` separa imagen, GIF y metadata de licencia.
- `taxonomy_terms` centraliza opciones reutilizables para filtros y selectores.
- `audit_logs` registra acciones administrativas relevantes.

## Contratos de API

### Autenticacion

- `POST /api/v1/auth/login`
- `POST /api/v1/auth/logout`
- `GET /api/v1/me`

### Catalogo

- `GET /api/v1/exercises`
- `GET /api/v1/exercises/{id}`
- `GET /api/v1/taxonomies`

### Usuario final

- `POST /api/v1/favorites`
- `DELETE /api/v1/favorites/{id}`
- `POST /api/v1/workout-plans`
- `POST /api/v1/workout-sessions`

### Backoffice

- `GET /api/v1/admin/exercises`
- `POST /api/v1/admin/exercises`
- `PATCH /api/v1/admin/exercises/{id}`
- `POST /api/v1/admin/imports/exercises`
- `GET /api/v1/admin/audit-logs`

## Consideraciones tecnicas

- El API debe versionarse desde el inicio con `/api/v1`.
- Los filtros de catalogo deben resolverse en PostgreSQL para el MVP.
- Las imagenes y GIFs deben almacenarse fuera del repositorio.
- La importacion del dataset debe ser idempotente.
- El backoffice no debe escribir directamente al mismo tiempo que el importador sin control de estados.

