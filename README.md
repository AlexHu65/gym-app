# Gym Rat

Gym Rat es un diario de entrenamiento para registrar sesiones y series, planificar rutinas y seguir el progreso. El MVP apunta a un lanzamiento en México en febrero de 2027.

| Superficie | Carpeta | Stack y responsabilidad |
| --- | --- | --- |
| App móvil | `gym-rat-app/` | Ionic, Angular y Capacitor para iOS y Android. Registro de entrenamientos, planes, historial y compra del desbloqueo digital. |
| Panel multitenant | `gym-rat-web/` | Filament v5 sobre Laravel para administrar usuarios, permisos, catálogo, operaciones y finanzas según el alcance autorizado. |
| Sitio público | `gym-rat-web/` | Laravel, Inertia, React y Tailwind para presentar Gym Rat, captar interesados y dirigirlos a la app para completar la compra. |

Laravel sirve la API móvil y comparte servicios y políticas con el sitio y el panel. El backend se organiza como monolito modular y usa PostgreSQL 16. PostGIS 3.7 está disponible en PostgreSQL 16 mediante `gym-rat-web/compose.postgis.yaml`. Las vistas administrativas PWA con Inertia se reservan para flujos específicos que complementen el panel.

El acceso digital se desbloquea con un pago único no consumible mediante Apple In-App Purchase o Google Play Billing. Laravel verifica la transacción de forma idempotente y registra el derecho de acceso permanente. El sitio público carece de checkout y de procesador de pagos.

## Convenciones y ambientes

- Usamos un monorepo para la app, la web y la documentación compartida. Nombramos las ramas de trabajo con el identificador de la tarjeta y una descripción breve, por ejemplo `codex/grt-008-documentacion-base`.
- Versionamos los archivos de ejemplo de configuración con valores ficticios. Las credenciales, llaves de firma y tokens se gestionan fuera de Git. Cualquier variable incluida en una compilación cliente se considera pública.
- Registramos cambios de alcance, datos, arquitectura o pagos en [decisiones del proyecto](docs/decisions/).

| Ambiente | Uso |
| --- | --- |
| Local | Desarrollo con base de datos local, datos sintéticos y configuración individual. |
| Staging local | Validación integrada en un proyecto Compose con base, puertos y credenciales propios. |
| Producción simulada | Configuración de producción validada en loopback, con base, puertos y credenciales propios. |

## Archivos de configuración

Cada carpeta tiene su propio `.env` local, excluido de Git, y tres plantillas versionables.

| Ambiente | Plantilla | Cómo se aplica |
| --- | --- | --- |
| Local | `.env.example` | Copia a `.env` dentro del componente si aún no existe. |
| Staging | `.env.staging.example` | Usa los valores de staging en la configuración del despliegue Laravel y de la compilación móvil. |
| Producción | `.env.production.example` | Usa los valores de producción en la configuración del despliegue Laravel y de la compilación móvil. |

Al desplegar Laravel, inyecta las variables del ambiente o aprovisiona un `.env` privado en el servidor. Configura una base de datos y credenciales propias por ambiente, conserva una `APP_KEY` distinta por ambiente y sustituye las URL de ejemplo. Genera la clave local con el comando de arranque indicado abajo.

Para la app móvil, `API_BASE_URL` apunta a la API de ese ambiente y los identificadores de Apple y Google se completan al configurar las tiendas. En un dispositivo físico, usa una dirección del backend local accesible desde el dispositivo en lugar de `localhost`. La integración de estas variables en la compilación móvil queda pendiente.

## Docker Compose por proyecto

Cada proyecto tiene su propio `Dockerfile` y `compose.yaml`. Los manifiestos y archivos de bloqueo están en Git. Para un entorno aislado con puertos alternativos, sigue la [guía de QA](QA-TESTING.md#arranque-en-un-checkout-limpio).

### Web y PostgreSQL

Desde `gym-rat-web/`, crea `.env` si hace falta y completa `DB_PASSWORD` con una contraseña local antes del primer comando de Compose.

```sh
test -f .env || cp .env.example .env
```

```sh
export GYM_RAT_UID="$(id -u)" GYM_RAT_GID="$(id -g)"
docker compose up -d --wait postgres
docker compose run --build --rm --no-deps dependencies
docker compose run --rm --no-deps dependencies npm run build
docker compose run --rm --no-deps dependencies php artisan key:generate --no-interaction
docker compose run --rm --no-deps dependencies php artisan migrate --no-interaction
docker compose run --rm --no-deps -p 127.0.0.1:8000:8000 dependencies \
  php artisan serve --host=0.0.0.0 --port=8000 --no-reload
```

Genera `APP_KEY` una vez por ambiente. La web queda en `http://127.0.0.1:8000/` y el acceso de Filament en `/admin/login`. Dentro de Compose, Laravel se conecta a `postgres:5432`. Conserva `--no-reload` para que el servidor PHP reciba esa configuración. PostgreSQL guarda los datos en un volumen y puedes detenerlo con `docker compose stop postgres`.

### App móvil

Desde `gym-rat-app/`, instala las dependencias desde el lockfile y arranca Angular.

```sh
test -f .env || cp .env.example .env
export GYM_RAT_UID="$(id -u)" GYM_RAT_GID="$(id -g)"
docker compose run --build --rm --no-deps dependencies
docker compose run --rm --no-deps -p 127.0.0.1:8100:8100 dependencies \
  npx ng serve --host 0.0.0.0 --port 8100
```

Abre `http://127.0.0.1:8100/` para ver el cliente en navegador. Las compilaciones nativas de iOS requieren macOS y Xcode.

## Estado de GRT-008

GRT-008 cubre infraestructura local. S0 está cerrado. Se verificó el arranque de la portada Inertia/React, Filament, Ionic y PostgreSQL 16 con PostGIS 3.7 desde dos checkouts de `00f519a`. Staging local y producción simulada tienen redes, volúmenes, bases y credenciales independientes. Consulta los [comandos y resultados de QA](QA-TESTING.md#staging-local-y-producción-simulada). El despliegue remoto queda para una etapa posterior.

- [Instrucciones del repositorio](AGENTS.md)
- [Instrucciones de la app móvil](gym-rat-app/AGENTS.md)
- [Instrucciones de la web y del panel](gym-rat-web/AGENTS.md)
- [Backlog del proyecto](docs/trello-backlog.md)
- [Decisión sobre administración multitenant](docs/decisions/ADR-006-admin-multitenant.md)
- [Decisión sobre compra única](docs/decisions/ADR-003-one-time-full-unlock.md)
