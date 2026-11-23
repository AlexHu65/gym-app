# ADR-002 — Stack del backoffice y persistencia

- **Estado:** decisión adoptada para el MVP.
- **Fecha:** 23 de octubre de 2026.
- **Alcance:** backoffice de Gym Rat, API, web pública y app móvil.

## Contexto

El stack anterior asignaba Angular tanto a la web pública como al backoffice. Se adopta Laravel/Inertia/React/Tailwind para la web pública y las vistas de portal web, y Filament v5 para el panel administrativo. Se mantienen Ionic/Angular/Capacitor para la app móvil y Laravel/PHP como backend monolítico modular.

## Decisión

| Área | Tecnología | Responsabilidad |
|---|---|---|
| App móvil iOS/Android | Ionic + Angular + Capacitor | App de entrenamiento para usuarios. |
| Web pública | Laravel + Inertia + React + Tailwind | Información del producto y captación de leads; sin checkout web. |
| API y lógica de negocio | Laravel + PHP | Monolito modular, autenticación, reglas, API, permisos y acceso a datos. |
| Panel administrativo | Filament v5 | Administración interna de catálogo, planes/plantillas, retos/insignias, estado de derechos de acceso y backoffice financiero protegido por rol. |
| Portal web administrativo con experiencia PWA | Laravel + Inertia + React + Tailwind | Vistas de portal y flujos personalizados que requieran UX PWA y no dupliquen las operaciones cubiertas por Filament. Comparte la aplicación Laravel, autenticación, políticas y servicios con el sitio público; las rutas, layouts y permisos se mantienen separados. |
| Base de datos | PostgreSQL 16 + PostGIS disponible como extensión | Datos transaccionales; habilitar funciones PostGIS solo cuando un requisito aprobado las necesite. |

El alcance multitenant del panel se documenta en [ADR-006](ADR-006-admin-multitenant.md), que deja pendiente definir la unidad de tenant y el modelo de aislamiento.

Filament e Inertia/React son dos superficies web distintas: Filament está construido sobre Livewire y su panel builder define la administración en PHP; Inertia conecta rutas/controladores Laravel con páginas React. La web pública y el portal PWA pueden compartir componentes y base técnica React, pero sus rutas, layouts, autenticación cuando aplique y permisos se separan. Se mantienen tareas distintas frente a Filament para evitar interfaces duplicadas. El PWA no almacenará offline datos financieros, personales ni de entrenamiento.

## Límites y controles

- Un único backend Laravel aplica las mismas políticas de acceso, validación y auditoría desde Filament, Inertia y API. Ocultar una acción en la interfaz no reemplaza autorización en servidor.
- `/admin` queda reservado a roles administrativos/financieros autorizados. Las rutas del portal se asignan a sus roles de operador y se limitan a sus tareas expresas.
- Compartir autenticación no implica compartir sesión sin límites: proteger CSRF, sesiones, cookies, rate limits y separación de roles en ambas superficies.
- No habilitar caché offline de respuestas autenticadas o datos sensibles en el PWA. Como máximo puede cachearse el shell estático según revisión de seguridad.
- PostgreSQL 16 es la versión base. PostGIS se habilita en entornos de despliegue solo si el proveedor soporta la versión elegida; no añadir datos de ubicación al MVP por el hecho de tener la extensión.
- Antes de iniciar implementación, fijar versiones compatibles de PHP/Laravel/Filament/Livewire/React/Inertia/Tailwind y de la imagen PostgreSQL/PostGIS. Filament v5 documenta requisitos mínimos de PHP 8.2+, Laravel 11.28+ y Tailwind 4.1+; se validará la matriz vigente al crear el repositorio.

## Consecuencias

- El equipo conserva una administración CRUD/reportes rápida en Filament sin construir componentes equivalentes desde cero.
- Las vistas React del portal permiten una experiencia PWA personalizada, pero aumentan la superficie de frontend y el costo de mantener dos enfoques de interfaz. Solo se construirán donde haya una necesidad del portal que Filament no resuelva bien.
- El módulo financiero continuará bajo los roles financieros/admin definidos para GRT-021 y GRT-042–045.
- El uso de PostGIS no cambia contratos ni alcance funcional mientras no exista un requisito geoespacial.

## Fuentes técnicas

- [Filament v5 — instalación y requisitos](https://filamentphp.com/docs/5.x/introduction/installation)
- [Laravel — React starter kit con Inertia](https://laravel.com/docs/starter-kits)
- [Laravel — frontend con Inertia](https://laravel.com/docs/frontend)
- [PostGIS — manual](https://postgis.net/documentation/)

## Actualizaciones documentales

El stack acordado en `AGENTS.md`, el alcance del MVP y las tarjetas GRT-008, GRT-009, GRT-021 y GRT-027 deben mantenerse alineados con esta decisión.
