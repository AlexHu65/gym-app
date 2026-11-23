# PRD-06 — Backoffice y finanzas

**Estado:** requisitos y modelo KPI definidos; diseño técnico preparatorio para GRT-043–045 en `docs/architecture/financial-module-design.md`. Implementación pendiente de GRT-008/009/010/021 y scaffold del stack.  
**Usuarios:** operadores de catálogo/soporte y personal financiero/admin con mínimo privilegio.  
**Objetivo:** operar el servicio y conciliar ingresos/gastos con trazabilidad.

## Superficies

- Filament v5: panel administrativo para catálogo, operaciones, usuarios, retos/insignias, estado de compra no consumible y finanzas protegidas por rol.
- Laravel + Inertia + React + Tailwind: solo flujos de portal PWA que Filament no cubra bien; no duplicar CRUD/operaciones.
- Un backend Laravel aplica políticas, validaciones y auditoría en API, Filament y portal. PWA no cachea datos autenticados/personales/financieros/entrenamientos.

## Requisitos

| ID | Requisito | Aceptación |
|---|---|---|
| OPS-01 | Mínimo privilegio | Roles separados para operaciones y finanzas; acciones autorizadas en servidor y accesos denegados explicados. |
| OPS-02 | Auditoría | Cambios de catálogo, importaciones y acciones administrativas dejan actor, fecha, tipo de acción y referencia sin copiar datos personales innecesarios. |
| OPS-03 | Operación de catálogo | Se puede revisar estado de licencia/procedencia y evitar publicación de medios sin autorización. |
| FIN-01 | Importación de liquidaciones | CSV de App Store Connect/Play Console se previsualiza, valida, deduplica y registra; errores por fila se pueden corregir antes de confirmar. |
| FIN-02 | Registro de costos | Costo fijo/variable con categoría, fecha, moneda, proveedor/referencia y evidencia. |
| FIN-03 | Conciliación | Mostrar bruto, impuestos/retenciones, comisiones, reembolsos, efectivo liquidado, costos y resultado por periodo, con fuente y moneda. |
| FIN-04 | No mezclar bases | Separar real, pendiente y proyección; no sumar divisas sin tasa, fecha y fuente; compras, proceeds, caja y costo API mensual son conceptos separados; no usar MRR Gym Rat. |
| FIN-05 | Reproducibilidad | Tercera persona puede reproducir el resultado del periodo desde reportes fuente y gastos registrados. |
| FIN-06 | Exportación y acceso | Tabla conciliatoria exportable; solo rol financiero/admin consulta información financiera. |
| FIN-07 | Punto de equilibrio | Exponer proceeds netos por compra, nuevas ventas necesarias, costo API por etapa, runway, costos fijos y CAC; costo de medios/API no equivale a rentabilidad total. |

## Diseño técnico para implementación

GRT-043–045 tienen un diseño preparatorio en [`docs/architecture/financial-module-design.md`](../../architecture/financial-module-design.md): formatos por tienda, previsualización/confirmación idempotente, modelo de persistencia, permisos propuestos, estados vacíos/errores, dashboard, escenarios de conciliación y runbook. La matriz RBAC/rutas permanece sujeta a GRT-009 y GRT-021. La aceptación final requiere implementación y validación manual en staging conforme al backlog.

## Fuera de alcance

Integración directa con APIs de tiendas, contabilidad fiscal, facturación, pronósticos automáticos, multi-entidad, analítica avanzada de cohortes y PWA offline sensible.

## Dependencias

ADR-002; GRT-021 para roles/backoffice; GRT-042 para semántica KPI; GRT-043 para CSV/costos; GRT-044 dashboard; GRT-045 conciliación/permisos; GRT-034 decisiones de economía.

## Fuentes

[`financial-dashboard.md`](../financial-dashboard.md), [`ADR-002-backoffice-stack.md`](../../decisions/ADR-002-backoffice-stack.md), [`mvp-scope.md`](../mvp-scope.md), GRT-021, GRT-042–045.
