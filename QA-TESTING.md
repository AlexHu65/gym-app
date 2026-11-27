# Gym Rat — Guía de QA manual

## Ambientes y datos

| Ambiente | Preparación | Alcance |
| --- | --- | --- |
| Local | `.env` privado por componente y PostgreSQL 16 en Docker Compose. | Verificación de configuración y, validación de flujos con datos sintéticos. |
| Staging | API, sitio y panel desplegados con base de datos y credenciales propias. Compilaciones móviles apuntando a esa API. | Flujos de extremo a extremo con datos sintéticos, Apple Sandbox y cuentas de prueba de Google Play. |
| Producción | URL, base de datos y credenciales exclusivas de producción. | Comprobaciones de disponibilidad y navegación pública tras el despliegue. |

Las URL y cuentas de prueba de staging se registrarán al aprovisionar el ambiente. Prepara cuentas sintéticas de usuario Free, comprador de prueba y administradores con permisos diferenciados. Para revisar aislamiento, prepara dos tenants con datos distinguibles cuando se defina el modelo multitenant. Gestiona los accesos fuera del repositorio.

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

### Separación de staging y producción

QA local usa contenedores y puertos aislados. El criterio de despliegue queda pendiente hasta comprobar en los destinos reales las URL y bases independientes, las credenciales propias, `APP_DEBUG=false` y la URL de API usada por la compilación móvil de staging. Registra el entorno y la evidencia sin incluir valores secretos.

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
