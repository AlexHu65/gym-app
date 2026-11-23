# Gym Rat — instrucciones para agentes

## Propósito del proyecto

Construir una app híbrida de entrenamiento para iOS y Android, un sitio web de presentación e inicio de contratación y un backoffice multitenant. El MVP debe estar listo para lanzamiento en febrero de 2027 y demostrar que los usuarios registran entrenamientos y pagan por funciones de seguimiento.

## Producto

- Propuesta: diario de entrenamiento con identidad de gimnasio clásico y cultura gym rat contemporánea.
- MVP: cuenta/perfil, catálogo de ejercicios licenciados por tandas, favoritos, planes editables, calendario, sesiones y series (peso, repeticiones, RPE), historial/gráficas esenciales, retos e insignias por hitos, niveles Free/Warm-Up, Garage Rat e Iron Rat (esta última condicionada a licencia y demanda), administración y módulo de backoffice de costos/rentabilidad con gráficas financieras.
- No incluir antes del lanzamiento salvo decisión explícita: feed social, rankings globales, wearables, nutrición, generación de planes con IA, marketplace de coaches y microservicios.
- Accesibilidad y experiencia inicial sencilla: una persona debe poder registrar su primer entrenamiento sin asistencia.

## Estructura del repositorio

- `gym-rat-app/` contiene la app móvil para iOS y Android.
- `gym-rat-web/` contiene el backend Laravel, el panel multitenant y el sitio público.
- `docs/` reúne el backlog y las decisiones compartidas. Consulta el [README](README.md) para las convenciones y los ambientes de GRT-008.

## Stack acordado

- App móvil: Ionic + Angular + Capacitor.
- Web pública de presentación y captación: Laravel + Inertia + React + Tailwind; rutas y permisos separados de la experiencia administrativa. Sin checkout web.
- API y lógica de negocio: Laravel + PHP, como monolito modular.
- Backoffice: Filament v5 para el panel administrativo multitenant (catálogo, operaciones, usuarios, permisos y módulo financiero). La autorización en Laravel debe comprobar el contexto y los permisos del tenant para cada operación sobre sus datos. Consulta [ADR-006](docs/decisions/ADR-006-admin-multitenant.md). Las vistas de portal administrativo que requieran una experiencia PWA personalizada usarán Laravel + Inertia + React + Tailwind, compartiendo backend y servicios con la web pública y Filament. Deben ser áreas complementarias, con rutas y permisos claros, no dos interfaces duplicadas para las mismas tareas.
- Infraestructura de desarrollo: Docker Compose para PostgreSQL 16 local. Mantener configuración, credenciales, redes y volúmenes separados por ambiente. Consulta el [README](README.md) para el arranque local.
- Base de datos: PostgreSQL 16. PostGIS estará disponible como extensión para necesidades geoespaciales aprobadas; no introduce funcionalidades geográficas al MVP por sí sola.
- Desbloqueo digital del MVP: pago único no consumible mediante Apple In-App Purchase y Google Play Billing. La web pública explica la oferta, capta leads y dirige al usuario a la app para completar la compra. Mantener el sitio sin checkout ni procesador de pagos web. El backend valida transacciones/notificaciones de tienda y gestiona un entitlement permanente independiente del proveedor. Las tarifas API de MuscleWiki son costos recurrentes de proveedor; no son suscripciones de Gym Rat al usuario.
- n8n para automatizaciones periféricas (leads, notificaciones consentidas, reportes), nunca como fuente de verdad de permisos o entrenamientos.
- Arquitectura MVP: monolito modular. No crear microservicios sin un motivo operativo validado.

## Reglas de trabajo

1. Antes de implementar, leer este archivo y cualquier `AGENTS.md` más cercano a los archivos que se editarán.
2. Antes de iniciar código, completar los entregables de descubrimiento y diseño aprobados en el backlog de `docs/trello-backlog.md`.
3. No asumir que el contenido de terceros tiene licencia comercial. MuscleWiki es el proveedor API seleccionado, con confirmación contractual escrita pendiente para acceso por compradores de pago único y conservación de historial. Respetar el límite de 30 días para metadatos de texto, caché privada de miniaturas en dispositivo hasta 24 h, `no-store` para bodymaps y video, y los headers entregados. No usar CDN/offline para multimedia ni asumir que los permisos permiten redistribución. Conservar licencia, atribución y procedencia; hasta resolver las dudas, no habilitar catálogo/API en producción pública.
4. No almacenar datos de tarjeta. Verificar transacciones, notificaciones e idempotencia en el backend antes de cambiar permisos.
5. Aplicar mínimo privilegio a roles y endpoints; no exponer datos personales en logs, analítica ni automatizaciones.
6. Diseñar el esquema para preservar el historial: editar una rutina no debe reescribir sesiones ya completadas.
7. Toda insignia/reto debe tener regla explícita, verificable e idempotente. Evitar incentivos que premien entrenar pese a dolor, lesión o fatiga.
8. No declarar una actividad terminada sin entregar sus criterios de aceptación y documentación señalados en la tarjeta.
9. Mantener decisiones técnicas y de producto en `docs/decisions/` cuando cambien alcance, stack, pagos, datos o privacidad.
10. Seguir las instrucciones del usuario sobre pruebas. No añadir ni ejecutar pruebas automáticamente salvo que las pidan; si se solicitan, documentar comandos y resultados.

## Decisiones abiertas que deben resolverse antes de construir

- Unidad de tenant, altas y membresías, recursos globales y por tenant, y estrategia de persistencia antes de implementar el panel multitenant.

- Usuario objetivo prioritario y propuesta Free/Pro final.
- Confirmación contractual MuscleWiki para usuarios finales con compra única, continuidad del contenido y derechos de streaming; crecimiento por planes API Testing/Growth/Professional/Enterprise según ADR-004.
- Elegibilidad para programas de tarifas reducidas de cada tienda y precios finales en MXN por storefront; el mercado inicial México y la moneda MXN ya están decididos.
- Política de privacidad, términos, eliminación/exportación de cuenta y tratamiento fiscal para México.
- SKU/precio MXN del desbloqueo único, autorización comercial de contenido para acceso vía API y cobertura de costos recurrentes con ingresos no recurrentes.
- Métricas objetivo del piloto y criterios de salida a lanzamiento.

## Definición de terminado común

Una tarjeta queda terminada cuando su entregable puede revisarse, cumple sus criterios de aceptación, incluye los textos/estados de error necesarios, tiene decisiones y dependencias documentadas y no contradice este archivo. El responsable actualiza Trello con enlaces a los artefactos y bloqueos pendientes.
