# Gym Rat — Guía de QA manual

## Ambientes y datos

| Ambiente | Preparación | Alcance |
| --- | --- | --- |
| Local | `.env` privado por componente y PostgreSQL 16 en Docker Compose. | Verificación de configuración y, validación de flujos con datos sintéticos. |
| Staging local | Checkout y proyectos Compose propios. | Verificar la base web, el panel y el cliente móvil con datos sintéticos. |
| Producción simulada | Checkout, proyectos Compose y credenciales independientes de staging. | Verificar la configuración de producción en loopback con datos sintéticos. |

Prepara cuentas sintéticas de usuario Free, comprador de prueba y administradores con permisos diferenciados. Para revisar aislamiento, prepara dos tenants con datos distinguibles cuando se defina el modelo multitenant. Gestiona los accesos fuera del repositorio.

## Arranque en un checkout limpio

Clona la rama de GRT-008 en un directorio nuevo y registra el SHA. Usa los comandos del [README](README.md#docker-compose-por-proyecto) desde las carpetas del clon, con estos ajustes para mantener QA local aislado del entorno habitual.

| Componente | Proyecto Compose | Puerto local |
| --- | --- | --- |
| PostgreSQL | `grt008-qa-web` | `15432` |
| Laravel y acceso de Filament | `grt008-qa-web` | `18000` |
| Cliente Ionic en navegador | `grt008-qa-app` | `18100` |

- Copia las plantillas locales y completa `DB_PASSWORD` en el `.env` web del clon antes de ejecutar Compose. Usa `export DB_PORT=15432` en la terminal web. Añade `-p grt008-qa-web` o `-p grt008-qa-app` después de `docker compose` según el componente.
- En `docker compose run`, publica `127.0.0.1:18000:8000` para Laravel y `127.0.0.1:18100:8100` para Angular. Conserva los puertos internos indicados en el README.
- Si el puerto ya está ocupado, identifica la instancia con `docker ps --filter publish=18000` o `docker ps --filter publish=18100`. Reutiliza la instancia si corresponde al mismo checkout de QA.
- Desde la raíz del clon, confirma con `git check-ignore gym-rat-web/.env gym-rat-app/.env` que ambos archivos privados están excluidos.

## Evidencia de GRT-008

El 27 de noviembre de 2026 se verificó el checkout limpio del commit `9ece7f6`.

| Verificación | Resultado |
| --- | --- |
| PostgreSQL 16 | Contenedor saludable en `127.0.0.1:15432`. |
| Dependencias y base web | Composer y npm instalaron desde sus archivos de bloqueo. Vite compiló, se generó `APP_KEY` y se aplicaron las tres migraciones iniciales. |
| Portada Laravel | `http://127.0.0.1:18000/` devolvió HTTP 200. La ruta sirve la vista Blade inicial. |
| Acceso de Filament | `http://127.0.0.1:18000/admin/login` devolvió HTTP 200. |
| Cliente Ionic en navegador | `http://127.0.0.1:18100/` devolvió HTTP 200. El contenedor monta la app del checkout limpio. |
| Archivos privados | Git ignora ambos `.env` locales. |
| PostGIS | `pg_available_extensions` confirmó que la extensión falta en la imagen local de PostgreSQL. |

### Staging local y producción simulada

Clona el repositorio dos veces y usa el mismo commit en ambos clones. En cada componente copia `.env.staging.example` o `.env.production.example` a `.env` según el clon. Usa estos valores y genera por separado cada `DB_PASSWORD` con `openssl rand -hex 24`.

| Configuración | Staging local | Producción simulada |
| --- | --- | --- |
| `APP_ENV` | `staging` | `production` |
| `APP_URL` | `http://127.0.0.1:18200` | `http://127.0.0.1:18300` |
| `DB_PORT` | `15434` | `15435` |
| `DB_DATABASE` y `DB_USERNAME` | `gym_rat_staging` | `gym_rat_production` |
| Proyecto Compose web | `grt008-staging` | `grt008-production` |
| Proyecto Compose móvil | `grt008-staging-app` | `grt008-production-app` |
| Puerto Ionic | `18210` | `18310` |
| `API_BASE_URL` del .env móvil | `http://127.0.0.1:18200/api` | `http://127.0.0.1:18300/api` |

En ambos `.env` web usa `APP_DEBUG=false`, `DB_HOST=postgres` y `SESSION_SECURE_COOKIE=false` para HTTP en loopback. Mantén `APP_KEY` vacía al crear cada clon. Si usas HTTPS en un despliegue futuro, conserva las cookies seguras de las plantillas.

Desde `gym-rat-web/` del clon de staging, ejecuta el siguiente bloque. Repite en el clon de producción con `GYM_PROJECT=grt008-production` y `GYM_WEB_PORT=18300`. Genera la clave y migra solo contra la base sintética del clon.

```sh
export GYM_RAT_UID="$(id -u)" GYM_RAT_GID="$(id -g)"
GYM_PROJECT=grt008-staging
GYM_WEB_PORT=18200
gym_web() {
  docker compose -f compose.yaml -f compose.postgis.yaml -p "$GYM_PROJECT" "$@"
}

gym_web up -d --wait postgres &&
gym_web run --build --rm --no-deps dependencies &&
gym_web run --rm --no-deps dependencies npm run build &&
gym_web run --rm --no-deps dependencies php artisan key:generate --force --no-interaction &&
gym_web run --rm --no-deps dependencies php artisan migrate --force --no-interaction &&
gym_web run -d --rm --no-deps -p "127.0.0.1:$GYM_WEB_PORT:8000" dependencies \
  php artisan serve --host=0.0.0.0 --port=8000 --no-reload
```

Usa un volumen nuevo para la imagen PostGIS. La configuración complementaria selecciona la imagen AMD64, que Docker Desktop emula en Apple Silicon. En cada clon móvil repite los comandos de [arranque del cliente](README.md#app-móvil), añade `-p grt008-staging-app` o `-p grt008-production-app` a Compose y publica `18210:8100` o `18310:8100` en loopback.

Consulta la base de cada proyecto desde su clon web:

```sh
gym_web exec -T postgres sh -ec \
  'psql -v ON_ERROR_STOP=1 -U "$POSTGRES_USER" -d "$POSTGRES_DB" -At' <<'SQL'
SELECT current_database(), current_user, current_setting('server_version');
SELECT name, default_version, installed_version
FROM pg_available_extensions WHERE name = 'postgis';
SQL
```

### Resultado de la separación local

El 27 de noviembre de 2026 se ejecutó el procedimiento con dos checkouts de `00f519a` y [el Compose complementario de PostGIS](gym-rat-web/compose.postgis.yaml). En ambos se instalaron dependencias, se compiló la web y se aplicaron las tres migraciones iniciales.

| Verificación | Staging local | Producción simulada |
| --- | --- | --- |
| Portada Laravel | HTTP 200; componente Inertia `Home` | HTTP 200; componente Inertia `Home` |
| `/admin/login` | HTTP 200 | HTTP 200 |
| Ionic en navegador | HTTP 200 en 18210 | HTTP 200 en 18310 |
| PostgreSQL / PostGIS | 16.16 / 3.7.0 | 16.16 / 3.7.0 |
| Usuario y base | `gym_rat_staging` | `gym_rat_production` |
| Volumen | `grt008-staging_postgres_data` | `grt008-production_postgres_data` |
| Red web | `grt008-staging_default` | `grt008-production_default` |
| Marcador sintético | `grt008_qa_marker` contiene `staging-only` | `to_regclass('public.grt008_qa_marker') IS NULL` devolvió verdadero |

La app móvil se comprobó en navegador. Angular aún debe consumir `API_BASE_URL` cuando se conecte a la API. El despliegue en servidores y las pruebas nativas corresponden a etapas posteriores.

## Flujos de aceptación del MVP

Ejecuta cada escenario cuando su funcionalidad esté disponible. Registra por separado el resultado en iOS y Android cuando aplique.

| Superficie | Escenario | Resultado esperado |
| --- | --- | --- |
| App móvil | Crear cuenta, iniciar sesión y cerrar sesión. | El usuario accede a su cuenta. Al cerrar sesión, las vistas privadas vuelven a exigir autenticación. |
| App móvil | Crear una rutina y registrar el primer entrenamiento con peso, repeticiones y RPE. | La sesión se guarda y aparece en el historial con las series capturadas. |
| App móvil | Editar una rutina después de completar una sesión. | La sesión anterior conserva sus ejercicios y valores. |
| App móvil | Cumplir una regla publicada de insignia y repetir el evento. | La insignia se concede una sola vez si se cumplen las condiciones. |
| App móvil | Comprar el desbloqueo único en el entorno de prueba de cada tienda. Probar compra pendiente, cancelación del flujo y reintento. | El backend concede acceso tras validar la transacción. Un evento duplicado produce un solo cambio efectivo. Una compra cancelada o pendiente de validación mantiene el acceso previo. |
| App móvil | Restaurar tras reinstalar o cambiar de dispositivo. Probar un conflicto de cuenta y un reembolso. | La restauración recupera el acceso de la cuenta vinculada. El conflicto se maneja sin conceder acceso a otra cuenta. El reembolso actualiza el acceso según la validación del backend. |
| Panel multitenant | Entrar a `/admin` con cuentas autorizadas y sin permiso. Intentar consultar, modificar o exportar registros de otro tenant. | El servidor limita cada operación al rol y tenant autorizados. El módulo financiero y los recursos globales requieren permisos explícitos. |
| Sitio público | Consultar la oferta, enviar datos sintéticos en el formulario y seguir la llamada a descargar o abrir la app. | El formulario valida entradas y confirma el envío. El sitio dirige la compra a la app. |

Los escenarios funcionales quedan pendientes hasta que se implementen los flujos correspondientes. Para el detalle de criterios y dependencias, consulta el [backlog](docs/trello-backlog.md) y las decisiones de [compra única](docs/decisions/ADR-003-one-time-full-unlock.md) y [administración multitenant](docs/decisions/ADR-006-admin-multitenant.md).

## Reporte de incidencias

- Indica tarjeta, ambiente, versión o commit y superficie afectada.
- Para web, incluye la ruta sin parámetros sensibles. Para móvil, registra dispositivo, sistema operativo y versión de la app.
- Describe el rol de prueba, precondiciones, pasos, resultado esperado, resultado observado y frecuencia.
- Adjunta evidencia con datos sintéticos o redactados. Omite credenciales, tokens, recibos de compra y datos personales.
