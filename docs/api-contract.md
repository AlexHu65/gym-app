# Contrato inicial de API

Base URL propuesta:

- `https://api.local.test/api/v1`

## Auth

- `POST /auth/login`
- `POST /auth/logout`
- `GET /me`

## Exercises

- `GET /exercises`
- `GET /exercises/{id}`
- `POST /exercises` `admin`
- `PATCH /exercises/{id}` `admin`
- `DELETE /exercises/{id}` `admin`

## Taxonomies

- `GET /taxonomies`
- `POST /taxonomies` `admin`
- `PATCH /taxonomies/{id}` `admin`

## Favorites

- `GET /favorites`
- `POST /favorites`
- `DELETE /favorites/{id}`

## Workout plans

- `GET /workout-plans`
- `POST /workout-plans`
- `GET /workout-plans/{id}`
- `PATCH /workout-plans/{id}`

## Workout sessions

- `GET /workout-sessions`
- `POST /workout-sessions`
- `GET /workout-sessions/{id}`

## Admin

- `GET /admin/dashboard`
- `GET /admin/exercises`
- `POST /admin/exercises/import`
- `GET /admin/audit-logs`

