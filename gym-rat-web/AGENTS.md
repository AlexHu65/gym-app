# Gym Rat — web, API y administración

Aplica las [instrucciones del repositorio](../AGENTS.md). Esta carpeta agrupa el sitio público, el panel administrativo y la API móvil en una aplicación Laravel.

## Responsabilidades

- Usa Filament v5 para el panel multitenant en `/admin`. Aplica políticas Laravel y valida en el servidor la membresía, el tenant y el permiso para las operaciones sobre recursos de tenant, incluidas consultas, acciones y exportaciones. El acceso a recursos globales requiere autorización explícita. Consulta [ADR-006](../docs/decisions/ADR-006-admin-multitenant.md) antes de diseñar el modelo de datos.
- Usa Inertia, React y Tailwind para la web pública. Presenta la oferta, capta interesados y dirige a la app para completar la compra. Mantén la web pública sin checkout.
- Usa Inertia para las vistas PWA administrativas justificadas por un flujo específico. Separa sus rutas, layouts y permisos de los del sitio público y evita duplicar operaciones de Filament. Mantén los datos sensibles fuera de la caché offline.
- Centraliza validación, autorización y lógica de negocio en Laravel. Verifica las transacciones de las tiendas en el backend antes de actualizar el derecho de acceso. Protege el módulo financiero con permisos explícitos.
- Usa PostgreSQL 16. Incorpora PostGIS cuando un requisito aprobado necesite la extensión.

## Desarrollo

- Consulta los manifiestos y archivos de bloqueo cuando existan. Durante GRT-008 fija la matriz compatible de PHP, Laravel y dependencias frontend antes de documentar los comandos de arranque.
- Usa datos sintéticos en local y staging. Versiona ejemplos de configuración sin secretos y conserva credenciales y datos separados por ambiente.
- Sigue la regla de pruebas del AGENTS.md raíz.
- Usa el Compose de la raíz para PostgreSQL local y `gym-rat-web/.env` para sus credenciales. Laravel ejecutado desde el equipo se conecta a `127.0.0.1`. Cuando se incorpore Laravel a la misma red de Compose, se conectará al servicio `postgres` por el puerto interno `5432`.
