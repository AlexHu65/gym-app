# Gym Rat

Gym Rat es un diario de entrenamiento para registrar sesiones y series, planificar rutinas y seguir el progreso. El MVP apunta a un lanzamiento en México en febrero de 2027.

| Superficie | Carpeta | Stack y responsabilidad |
| --- | --- | --- |
| App móvil | `gym-rat-app/` | Ionic, Angular y Capacitor para iOS y Android. Registro de entrenamientos, planes, historial y compra del desbloqueo digital. |
| Panel multitenant | `gym-rat-web/` | Filament v5 sobre Laravel para administrar usuarios, permisos, catálogo, operaciones y finanzas según el alcance autorizado. |
| Sitio público | `gym-rat-web/` | Laravel, Inertia, React y Tailwind para presentar Gym Rat, captar interesados y dirigirlos a la app para completar la compra. |

Laravel sirve la API móvil y comparte servicios y políticas con el sitio y el panel. El backend se organiza como monolito modular y usa PostgreSQL 16. PostGIS queda disponible para requisitos aprobados. Las vistas administrativas PWA con Inertia se reservan para flujos específicos que complementen el panel.

El acceso digital se desbloquea con un pago único no consumible mediante Apple In-App Purchase o Google Play Billing. Laravel verifica la transacción de forma idempotente y registra el derecho de acceso permanente. El sitio público carece de checkout y de procesador de pagos.

## Convenciones y ambientes

- Usamos un monorepo para la app, la web y la documentación compartida. Nombramos las ramas de trabajo con el identificador de la tarjeta y una descripción breve, por ejemplo `codex/grt-008-documentacion-base`.
- Versionamos los archivos de ejemplo de configuración con valores ficticios. Las credenciales, llaves de firma y tokens se gestionan fuera de Git. Cualquier variable incluida en una compilación cliente se considera pública.
- Registramos cambios de alcance, datos, arquitectura o pagos en [decisiones del proyecto](docs/decisions/).

| Ambiente | Uso |
| --- | --- |
| Local | Desarrollo con base de datos local, datos sintéticos y configuración individual. |
| Staging | Validación integrada con servicios, base de datos y credenciales propios. Usar datos sintéticos y entornos de prueba de las tiendas. |
| Producción | Servicio público con infraestructura, datos, credenciales y configuración de tiendas independientes de staging. |

## Archivos de configuración

Cada carpeta tiene su propio `.env` local, excluido de Git, y tres plantillas versionables.

| Ambiente | Plantilla | Cómo se aplica |
| --- | --- | --- |
| Local | `.env.example` | Copia a `.env` dentro del componente si aún no existe. |
| Staging | `.env.staging.example` | Usa los valores de staging en la configuración del despliegue Laravel y de la compilación móvil. |
| Producción | `.env.production.example` | Usa los valores de producción en la configuración del despliegue Laravel y de la compilación móvil. |

Al desplegar Laravel, inyecta las variables del ambiente o aprovisiona un `.env` privado en el servidor. Configura una base de datos y credenciales propias por ambiente, conserva una `APP_KEY` distinta por ambiente y sustituye las URL de ejemplo. Cuando Laravel esté inicializado, genera la clave local con `php artisan key:generate` desde `gym-rat-web/` y completa los datos de tu PostgreSQL local.

Para la app móvil, `API_BASE_URL` apunta a la API de ese ambiente y los identificadores de Apple y Google se completan al configurar las tiendas. En un dispositivo físico, usa una dirección del backend local accesible desde el dispositivo en lugar de `localhost`. La lectura de estas variables durante la compilación queda pendiente de integrar al inicializar Angular y Capacitor.

## Docker Compose por proyecto

Cada proyecto tiene su propio `Dockerfile` y `compose.yaml`. Ejecuta los comandos dentro de la carpeta correspondiente. Para instalar dependencias, primero inicializa el proyecto y versiona sus manifiestos y archivos de bloqueo.

### Web y PostgreSQL

Completa `DB_PASSWORD` en `gym-rat-web/.env`. Desde `gym-rat-web/`, ejecuta:

```sh
GYM_RAT_UID="$(id -u)" GYM_RAT_GID="$(id -g)" docker compose up --build -d
docker compose logs dependencies
```

El servicio `dependencies` ejecuta `composer install` y `npm ci`. PostgreSQL 16 conserva los datos en un volumen y escucha en `127.0.0.1` en el puerto `DB_PORT`. Puedes iniciar solo la base con `docker compose up -d --wait postgres` y detenerla con `docker compose stop postgres`. Cambiar las variables `DB_*` después de inicializar el volumen requiere actualizar las credenciales en PostgreSQL.

Para servir Laravel dentro de Compose desde `gym-rat-web/`, usa el siguiente comando. `--no-reload` conserva las variables de conexión que Compose pasa al servidor PHP.

```sh
docker compose run --rm --no-deps -p 127.0.0.1:8000:8000 dependencies \
  php artisan serve --host=0.0.0.0 --port=8000 --no-reload
```

### App móvil

Desde `gym-rat-app/`, ejecuta:

```sh
GYM_RAT_UID="$(id -u)" GYM_RAT_GID="$(id -g)" docker compose up --build
```

El servicio `dependencies` ejecuta `npm ci` para la app. Las compilaciones nativas de iOS requieren macOS y Xcode.

Los servicios de instalación terminan al completar su trabajo. Las imágenes usan PHP 8.4, Composer 2 y Node 24 como base provisional. Staging y producción tendrán configuración, credenciales, redes y almacenamiento propios. El arranque de Laravel y del servidor de desarrollo móvil queda pendiente de la inicialización de ambos proyectos.

## Estado de GRT-008

La estructura inicial, las convenciones, las plantillas de ambiente y el Compose de PostgreSQL local están documentadas. Las carpetas todavía carecen de aplicaciones ejecutables. Queda pendiente fijar las versiones compatibles en los manifiestos, inicializar los proyectos, documentar el arranque y despliegue reproducibles y comprobar la separación de staging y producción.

- [Instrucciones del repositorio](AGENTS.md)
- [Instrucciones de la app móvil](gym-rat-app/AGENTS.md)
- [Instrucciones de la web y del panel](gym-rat-web/AGENTS.md)
- [Backlog del proyecto](docs/trello-backlog.md)
- [Decisión sobre administración multitenant](docs/decisions/ADR-006-admin-multitenant.md)
- [Decisión sobre compra única](docs/decisions/ADR-003-one-time-full-unlock.md)
