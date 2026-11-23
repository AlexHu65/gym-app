# ADR-006 — Administración multitenant

- **Estado**. Adoptada para el alcance del panel. Diseño de tenants pendiente.
- **Fecha**. 23 de noviembre de 2026.

## Decisión

Gym Rat tendrá un panel administrativo multitenant en Filament v5 dentro del monolito Laravel. Esta decisión complementa [ADR-002](ADR-002-backoffice-stack.md). Cada operación sobre un recurso de tenant debe validar en el servidor el contexto, la membresía y los permisos correspondientes. El acceso global requiere un permiso explícito.

La app móvil conserva su función de diario de entrenamiento. El sitio público presenta Gym Rat, capta interesados y dirige a la app para completar la compra digital según [ADR-003](ADR-003-one-time-full-unlock.md).

## Diseño pendiente

Antes de implementar el panel, definir qué representa un tenant en Gym Rat, cómo se crean tenants y membresías, qué recursos son globales o pertenecen a un tenant y qué estrategia de persistencia y aislamiento corresponde. Documentar estas decisiones con el diseño de arquitectura y autorización.
