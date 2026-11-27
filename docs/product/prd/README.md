# PRDs de Gym Rat — MVP

**Estado:** borradores de producto para revisión; derivados de la documentación existente al 23 de octubre de 2026. No autorizan por sí mismos el inicio de implementación.  
**Horizonte:** lanzamiento controlado previsto para el 25 de febrero de 2027, con seguimiento hasta el 8 de marzo. Ver el [calendario del backlog](../../trello-backlog.md#calendario-propuesto).

Estos PRDs convierten el brief y alcance aprobados en requisitos por área. Los criterios heredados conservan su estado: una hipótesis o tarea de investigación no se presenta como validada. Antes de construir, cerrar dependencias de descubrimiento/diseño del backlog y resolver los bloqueos de abajo.

## Documentos

| PRD | Área | Tarjetas relacionadas |
|---|---|---|
| [PRD-01 — Entrenamiento y progreso](01-entrenamiento-y-progreso.md) | Cuenta, planes, calendario, sesión, historial y métricas | GRT-006, 010, 012, 015–018 |
| [PRD-02 — Catálogo y favoritos](02-catalogo-y-favoritos.md) | Descubrimiento, curación, fondo de inversión y derechos de contenido | GRT-004, 013–014, 046–047 |
| [PRD-03 — Desbloqueo único y acceso](03-suscripciones-y-acceso.md) | Acceso gratuito, paywall, compra móvil no consumible y entitlements | GRT-002–003, 023, 025–026, 048 |
| [PRD-04 — Retos e insignias](04-retos-e-insignias.md) | Progresión individual segura | GRT-007, 019–020 |
| [PRD-05 — Sitio público y captación](05-sitio-publico-y-leads.md) | Landing, consentimiento y leads | GRT-027–029 |
| [PRD-06 — Backoffice y finanzas](06-backoffice-y-finanzas.md) | Operación, permisos y rentabilidad | GRT-021, 042–045 |

## Decisiones y bloqueos que cruzan los PRDs

1. **Compra única de ejercicios:** confirmada dentro del MVP como paquetes temáticos in-app con acceso permanente. Falta definir composición/SKUs/precios MXN y actualizar el flujo de pagos para compra, restauración y reembolsos; no se ofrecerán compras individuales salvo decisión posterior.
2. **Prototipo del primer entrenamiento:** GRT-006 documenta el [flujo y prototipo clicable](../../design/first-workout-flow.md).
3. **Reglas de progresión:** falta el catálogo con reglas concretas solicitado por GRT-007. PRD-04 fija límites de seguridad y requisitos, pero no inventa insignias finales.
4. **Derechos y operación del catálogo:** términos publicados de MuscleWiki confirman uso comercial/display en app y streaming in-app bajo planes pagados; no exponer/exportar/revender datos o medios. El acceso puede revocarse/retirarse y debe divulgarse. GRT-047 administra MXN $3,687 y costo API escalonado. Testing solo beta cerrada; Growth para piloto en español; Professional según funciones/uso; Enterprise queda opcional futuro.
5. **Mercado y privacidad:** México confirmado y cobros MXN desde lanzamiento. Faltan precios locales por tienda, tipo de cambio/fecha/fuente, impuestos y artefactos de privacidad, términos, eliminación/exportación y tratamiento fiscal.

## Fuentes base

- [`brief.md`](../brief.md) y [`mvp-scope.md`](../mvp-scope.md)
- [`pricing-hypotheses.md`](../pricing-hypotheses.md)
- [`financial-dashboard.md`](../financial-dashboard.md)
- [`ADR-001-payments.md`](../../decisions/ADR-001-payments.md) y [`ADR-002-backoffice-stack.md`](../../decisions/ADR-002-backoffice-stack.md)
- [`exercise-source-register.md`](../../data/exercise-source-register.md)
- [`trello-backlog.md`](../../trello-backlog.md)
