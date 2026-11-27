# Gym Rat — Guía de QA manual

## Ambientes y datos

| Ambiente | Preparación | Alcance |
| --- | --- | --- |
| Local | `.env` privado por componente y PostgreSQL 16 en Docker Compose. | Verificación de configuración y, cuando las aplicaciones estén inicializadas, validación de flujos con datos sintéticos. |
| Staging | API, sitio y panel desplegados con base de datos y credenciales propias. Compilaciones móviles apuntando a esa API. | Flujos de extremo a extremo con datos sintéticos, Apple Sandbox y cuentas de prueba de Google Play. |
| Producción | URL, base de datos y credenciales exclusivas de producción. | Comprobaciones de disponibilidad y navegación pública tras el despliegue. |

Las URL y cuentas de prueba de staging se registrarán al aprovisionar el ambiente. Prepara cuentas sintéticas de usuario Free, comprador de prueba y administradores con permisos diferenciados. Para revisar aislamiento, prepara dos tenants con datos distinguibles cuando se defina el modelo multitenant. Gestiona los accesos fuera del repositorio.

## Verificación local de GRT-008

1. Confirma que Git ignora los archivos privados `gym-rat-web/.env` y `gym-rat-app/.env`, y permite versionar las plantillas `.env.example`, `.env.staging.example` y `.env.production.example` de ambos componentes.
2. Configura `DB_PASSWORD` en `gym-rat-web/.env` y arranca PostgreSQL desde `gym-rat-web/`. El servicio debe quedar saludable.
3. Reinicia el servicio y confirma que el volumen conserva los datos. Consulta las instrucciones de [arranque y detención](README.md#web-y-postgresql).
4. Cuando se aprovisionen los despliegues, confirma que la compilación de staging apunta a su propia API y que sus operaciones quedan en la base de staging. Comprueba que staging y producción tienen depuración desactivada y usan configuración y credenciales separadas.

```sh
docker compose up -d --wait postgres
docker compose ps postgres
```

## Evidencia de GRT-008 — 27 de noviembre de 2026

| Verificación | Comando o consulta | Resultado |
| --- | --- | --- |
| PostgreSQL local | `docker compose ps` desde `gym-rat-web/` | PostgreSQL 16 saludable, publicado en `127.0.0.1:5432`. |
| Migraciones Laravel | `php artisan migrate:status` dentro del contenedor web | Las tres migraciones iniciales figuran como aplicadas. |
| Sitio web | `curl` a `http://127.0.0.1:8000/` | HTTP 200. |
| Pantalla de acceso de Filament | `curl` a `http://127.0.0.1:8000/admin/login` | HTTP 200. |
| Cliente móvil en navegador | `curl` a `http://127.0.0.1:8100/` | HTTP 200. |
| Exclusión de configuración privada | `git check-ignore gym-rat-web/.env gym-rat-app/.env` | Ambos archivos ignorados por Git. |

Los manifiestos y archivos de bloqueo existen en el directorio de trabajo. Queda pendiente versionarlos, repetir el arranque desde un checkout limpio, documentar la disponibilidad de PostGIS y verificar la separación de staging y producción en los destinos de despliegue. La evidencia HTTP cubre el arranque local y la pantalla de acceso del panel.

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

Los escenarios funcionales quedan pendientes hasta que se inicialicen y desplieguen los componentes correspondientes. Para el detalle de criterios y dependencias, consulta el [backlog](docs/trello-backlog.md) y las decisiones de [compra única](docs/decisions/ADR-003-one-time-full-unlock.md) y [administración multitenant](docs/decisions/ADR-006-admin-multitenant.md).

## Reporte de incidencias

- Indica tarjeta, ambiente, versión o commit y superficie afectada.
- Para web, incluye la ruta sin parámetros sensibles. Para móvil, registra dispositivo, sistema operativo y versión de la app.
- Describe el rol de prueba, precondiciones, pasos, resultado esperado, resultado observado y frecuencia.
- Adjunta evidencia con datos sintéticos o redactados. Omite credenciales, tokens, recibos de compra y datos personales.
