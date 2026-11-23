# Diseño técnico: finanzas del backoffice

**Alcance:** diseño preparatorio para GRT-043, GRT-044 y GRT-045.  
**Estado:** propuesto; requiere ratificación dentro de GRT-009 y GRT-021 antes de implementación.  
**Base de negocio:** [`financial-dashboard.md`](../product/financial-dashboard.md).  
**Fecha:** 13 de noviembre de 2026.

## Condiciones de inicio de implementación

Este diseño no reemplaza los prerequisitos del backlog. Antes del código deben estar disponibles:

- GRT-008: scaffold/entornos del stack acordado (Laravel + Filament 5 + PostgreSQL 16).
- GRT-009: arquitectura modular, autenticación, autorización, contratos y formato común de errores.
- GRT-010: convención de migraciones/identificadores/fechas y retención.
- GRT-021: roles administrativos implementados; GRT-043, GRT-044 y GRT-045 dependen explícitamente de esta tarjeta.
- GRT-042: semántica financiera adoptada (definida en `financial-dashboard.md`).

No almacenar archivos financieros en un bucket público ni en logs. No aceptar notificaciones del cliente como comprobante de ingreso. En MVP, los archivos se descargan manualmente de las consolas y los depósitos se reconcilian manualmente.

## Módulo y límites

Agregar `Finance` como módulo del monolito Laravel; sus modelos, servicios y políticas son consumidos por Filament. Las vistas del módulo deben llamar al mismo servicio de dominio que cualquier endpoint/portal, sin duplicar cálculos en componentes UI. El módulo solo entrega agregados al dashboard; el acceso a archivo fuente y detalle de movimientos queda restringido al rol financiero/admin.

### Componentes lógicos

1. **Carga y prevalidación:** recibe un archivo de reporte o plantilla de gastos, identifica tipo/versión, genera vista previa y errores por fila; aún no crea movimientos contabilizados.
2. **Adaptadores de tienda:** uno por familia de reporte. Transforman campos de origen a un formato normalizado sin perder columnas originales necesarias para trazabilidad.
3. **Confirmación idempotente:** persiste el lote y sus líneas con clave única estable; los ajustes/reimportaciones quedan como nuevas importaciones auditadas.
4. **Registro de costos y FX:** alta/corrección auditada de costos, compromisos, pagos y tasas, con evidencia privada.
5. **Conciliación:** asocia proceeds/pagos de tienda con depósitos bancarios y explica diferencias; no modifica silenciosamente los datos de origen.
6. **Consultas financieras:** KPI y series derivados del ledger normalizado, el mapa de SKU versionado y snapshots de entitlements.

## Archivos y adaptadores de importación

No asumir que ambos proveedores entregan un CSV homogéneo. Aceptar exportaciones delimitadas compatibles con el formato vigente de cada fuente, incluyendo CSV UTF-8/UTF-16 y reportes de Apple delimitados por tabuladores; admitir ZIP solo si contiene un único reporte permitido. Rechazar archivos ejecutables, HTML, hojas con macros, ZIP con rutas absolutas/`..`, archivos anidados, múltiples reportes ambiguos o tamaños superiores al límite configurable. Límite inicial propuesto: 20 MiB por archivo comprimido y 100 MiB descomprimido; validar con muestras reales antes de aprobar GRT-043.

| Adaptador | Entrada primaria | Campos normalizados mínimos | Reglas específicas |
|---|---|---|---|
| Apple Financial Report | All Countries/Regions Single File o Multiple File; extensión/formato exportado de la consola | periodo fiscal, vendor/product ID, unidades, Customer Price + moneda, Partner Share/Extended Partner Share + moneda, Sale or Return, país de venta, producto/order type, archivo y versión | `Customer Price` incluye los impuestos que Apple recauda; `Partner Share` ya es proceeds después de impuestos aplicables y comisión. Retorno conserva signo/clase y no se resta otra vez si ya está neteado. Datos agregados no inventan fecha transaccional por orden. |
| Google Play Earnings | Reporte mensual de Earnings descargado, normalmente contenido en paquete ZIP de exportación | transaction/issue date según campo, tipo de transacción, producto/SKU, país, importe original + moneda, importe convertido + moneda, tax/fee/refund/adjustment, referencia de orden si se incluye, archivo y versión | Una orden produce tipos de línea distintos (charge, Google fee, tax, refund, adjustment, etc.); preservar cada clase/signo y el identificador fuente. No usar Estimated Sales para finanzas cerradas. Los ajustes que llegan después se importan como transacciones nuevas del periodo/ciclo de reporte indicado. |
| Gastos Gym Rat (plantilla propia) | CSV descargado de plantilla versionada | `external_ref`, fecha de gasto, fecha de pago opcional, estado (`estimated`, `committed`, `invoiced`, `paid`), categoría, proveedor/referencia, descripción no personal, importe, moneda, tax incluido/desconocido, centro/tier opcional, referencia/evidencia privada | Filas sin fecha, importe válido, moneda ISO o categoría se rechazan; gasto estimado/comprometido nunca cuenta como efectivo pagado. La plantilla define separador/decimal y su versión en cabecera. |

El parser debe detectar delimitador, codificación y encabezados, pero exigir coincidencia con un adaptador/versión permitidos. Normalizar números respetando configuración de fuente, no locale del navegador. Las filas sin moneda, periodo o importe requerido se rechazan; dimensiones ausentes se guardan como `null` y se presentan como “Sin dato”. No inferir tipo de cambio, tienda, tier ni impuestos.

### Flujo de importación

1. Usuario `Finance` abre Importaciones, elige Apple, Google o gastos, periodo/fuente y archivo.
2. Backend valida extensión real/contenido, tamaño, codificación, estructura y tipo de reporte; calcula SHA-256 y guarda temporalmente en almacenamiento privado con vencimiento corto.
3. Pantalla previa muestra nombre/hash, tienda, reporte/periodo, filas válidas, duplicadas, rechazadas, importes por moneda y tipo de línea. No mostrar contenido personal irrelevante.
4. El usuario corrige el archivo fuera del sistema y vuelve a cargarlo, o confirma filas válidas si las reglas permiten omitir rechazadas. La decisión se guarda con el lote.
5. Solo al confirmar se insertan lote y movimientos en transacción de base de datos; repetir archivo/líneas existentes no duplica y devuelve el resultado anterior/duplicados identificados.
6. Si una tienda emite archivo corregido/ajuste, no se sobrescribe un lote confirmado: se crea lote nuevo con referencia a lote/periodo previo y clase `correction`/`adjustment`; las cifras antiguas siguen auditables.
7. Cada movimiento expone reporte y fila de origen. El original solo es accesible para Finance/Admin y se conserva según política de retención aprobada en GRT-010/036.

Estados de lote: `uploaded`, `previewed`, `confirmed`, `partially_confirmed`, `rejected`, `superseded`. Los errores se asocian a fila/campo/código/mensaje accionable. Mensajes mínimos: tipo de archivo no reconocido; codificación/delimitador no compatible; encabezados faltantes; formato de periodo/fecha inválido; moneda no ISO; importe ambiguo; fila duplicada; archivo ya importado; filas rechazadas; no hay filas importables; permiso denegado; falla de almacenamiento; reporte parcialmente confirmado; corrección vinculada a periodo cerrado.

## Persistencia propuesta

Nombres tentativos para revisar con GRT-009/010; montos `NUMERIC`, nunca `FLOAT`.

| Entidad | Datos clave | Integridad/auditoría |
|---|---|---|
| `finance_import_batches` | `id`, store, report_type, report_version, source_period_start/end, original_filename, sha256, storage_key privado, uploaded_by, status, row counts, source_currency set, imported_at, confirmed_at, supersedes_batch_id nullable | `sha256 + store + report_type + source_period` para detectar reenvío exacto; no bloquear reportes complementarios o correcciones válidas. Cambio de estado con usuario/fecha. |
| `finance_transactions` | batch_id, source_row_key, store, transaction_class, product_id, country/storefront, activity_date, settlement_period, quantity, customer_amount/currency nullable, proceeds_amount/currency nullable, fee_amount/currency nullable, tax_amount/currency nullable, refund_amount/currency nullable, original_row JSON restringido, sku_mapping_version nullable | UNIQUE(`batch_id`,`source_row_key`); importes y moneda conservan valor de proveedor; signos y clasificación explícitos. No persistir PII importada si no es necesaria. |
| `finance_costs` | category, status, incurred_at, paid_at nullable, vendor_reference, description, amount/currency, tax treatment, cost_center/tier nullable, evidence_key privado, exchange_rate_id nullable, external_ref nullable | Referencia externa única opcional. Corrección vía historial/audit log; no borrar gasto pagado sin evento compensatorio. |
| `finance_fx_rates` | from_currency, to_currency, rate, effective_at, source_name, source_reference, captured_by | UNIQUE de par/fecha/fuente; la tasa usada se copia en el cálculo/conversión, no se cambia retroactivamente al actualizar tabla. |
| `finance_payouts` | store, statement_reference, issued_at, received_at, gross_reported_amount/currency, bank_received_amount/currency, bank_fee_amount/currency nullable, exchange_rate_id, source_key, status | Referencia idempotente; no almacenar número completo de cuenta ni datos de tarjeta. Adjuntos privados y mínimos. |
| `finance_reconciliations` | payout_id, batch_id(s), applied_amount/currency, difference_amount/currency, status, note_code, note, created_by | Aplicaciones no pueden exceder saldo sin autorización explícita; diferencias explicadas o abiertas; historial de reapertura/corrección. |
| `finance_sku_mappings` | store, product_id, product_kind (`non_consumable_one_time`), storefront/currency, customer_price, valid_from/to, version, approved_by | Sin mapeo no se agrega a las ventas de compra única. Mantener periodos de vigencia y cambios de precio, sin reescribir actividad histórica. |

El JSON original conserva estructura para inspección/reproducibilidad, no para acumular PII. Definir allowlist de campos por adaptador; descartar campos de comprador, email o datos de pago aunque aparezcan en el CSV. Guardar un checksum del archivo fuente, no el archivo en el repositorio Git.

## Servicios, rutas y permisos (propuesta para ratificar en GRT-009/021)

La autorización se aplica en servidor a acciones Filament, rutas, descargas, exports y servicios de dominio; ocultar un botón no es control de acceso.

| Acción | `admin` | `finance` | `operations` | Sin rol |
|---|---:|---:|---:|---:|
| Ver dashboard agregado | Sí | Sí | No | No |
| Cargar/previsualizar/confirmar importación | Sí | Sí | No | No |
| Ver/descargar fuente financiera | Sí | Sí | No | No |
| Crear/editar/reabrir costo o conciliación | Sí | Sí | No | No |
| Ver datos operativos no financieros | Sí | Según rol GRT-021 | Sí | No |
| Administrar usuarios/roles | Sí (auditado) | No | No | No |

Roles y capacidades son tentativos; alinear a mínimo privilegio definido en GRT-021 antes de implementarlos. Propuesta de operaciones de aplicación: `finance.import.preview`, `finance.import.confirm`, `finance.costs.create/update`, `finance.payouts.reconcile`, `finance.dashboard.view`, `finance.export`. Las rutas exactas se incorporarán al contrato API central, no se fijan antes de GRT-009.

Auditoría registra actor, acción, fecha UTC, entidad/tipo, id/referencia y resultado. Evitar guardar filas completas en logs. Exportación descarga CSV calculado con filtros y marca de fecha/fuente; toda exportación crea evento de auditoría.

## Cálculo del dashboard y experiencia de usuario (GRT-044)

- Filtros superiores: intervalo, tienda, storefront/país, moneda de origen/presentación y tier. Periodo por defecto: último mes financiero **cerrado**; si no hay cierre, mostrar vacío con acción “Importa el primer reporte”.
- Banda de estado: tienda/periodo, fecha de importación, “Parcial / Cerrado / Diferencia abierta”, moneda y fuente. Un periodo en curso aparece como estimado o parcial y no comparte estilo con periodos cerrados.
- Tarjetas: ventas únicas, proceeds, efectivo liquidado, costos pagados, costo mensual API (Testing/Growth/Professional; Enterprise como opción futura) y runway de caja. Cada tarjeta incluye etiqueta `reportado`, `pagado`, `estimado` o `escenario` y tooltip con fórmula.
- Serie de barras/líneas: ventas brutas de cliente; deductions/proceeds como series separadas; gastos pagados y resultado de caja. No apilar magnitudes si su semántica/base difiere.
- Tabla por SKU/storefront y cohorte de compra única: proceeds atribuibles, contribución y costo asignado; sin SKU mapeado muestra “Sin clasificar”. No calcular suscriptores activos ni MRR Gym Rat.
- Conciliación: cada cifra enlaza a tabla de líneas fuente/lote/reporte y a payout; diferencias abiertas se resaltan con valor absoluto, motivo y última acción.
- Empty states: “Aún no hay reportes importados”, “Sin costos registrados”, “Sin datos para los filtros”, “No se puede calcular por falta de moneda/tasa/SKU”, “Reporte incompleto; revisar filas rechazadas”.
- Errores: permiso denegado con instrucción para solicitar acceso al admin; fallo de importación mantiene el archivo sin confirmar y permite descargar reporte de errores; exportación fallida informa que no se cambió el ledger.
- Accesibilidad: tablas con encabezados y caption, gráficas con tabla equivalente/texto-resumen, contraste AA, estados no indicados solo por color y navegación por teclado.

## Validación operativa GRT-045

Escenarios de revisión manual en staging, usando archivos sintéticos y muestras oficiales autorizadas sin PII. No constituyen pruebas automatizadas.

1. Apple con venta normal: precio al cliente 100, proceeds/Partner Share 70; al importar, proceeds queda 70 y no se vuelve a restar comisión.
2. Apple Return con cantidad/importe negativos: la devolución reduce venta/proceeds una sola vez y conserva `Sale or Return`/signo de origen.
3. Google Earnings con Charge, Google fee, Tax y Charge refund en líneas separadas: cada clase se conserva; la suma de líneas concuerda con la definición de reporte y el payout se enlaza sin volver a restar fee.
4. Google Estimated Sales se carga por error en flujo de finanzas: rechazar o clasificar exclusivamente como tendencia; no entra en proceeds/caja.
5. Archivo exacto repetido: segunda carga detecta hash/lote repetido y no incrementa totales.
6. Reimportación de archivo corregido y ajuste de ciclo posterior: nuevo lote versionado, referencia a anterior y efecto neto visible sin borrar registros previos.
7. MXN y moneda extranjera: filtros mantienen monedas separadas; sin tasa documentada no se agrega a MXN y muestra “sin tasa”.
8. Campo/encabezado/codificación desconocidos, fila corrupta o total parcial: vista previa accionable; no se cierra periodo con filas omitidas sin decisión explícita.
9. SKU desconocido: permanece sin clasificar, no se asigna a tier por nombre aproximado.
10. Mensual/anual: MRR anual = precio recurrente/12, separado de pago anual en efectivo.
11. Gasto cotizado/comprometido/facturado/pagado: solo el pagado afecta resultado de caja; el bucket muestra comprometido y saldo disponible por separado.
12. Reconciliación de depósito inferior/superior a proceeds: diferencia abierta; cargos bancarios requieren evidencia; no alterar fee de tienda para cuadrar.
13. Usuario `operations` y usuario sin rol intentan abrir dashboard, export, import, attachment y endpoint directo: denegado y auditado; `finance` puede realizar solo acciones financieras aprobadas.
14. Archivos con PII o campos de pedido que puedan identificar cliente: el dashboard/log/export no los reproduce; los campos se descartan o enmascaran conforme allowlist.
15. Reapertura de periodo cerrado y corrección: requiere rol Finance/Admin, motivo, actor/fecha, mantiene el valor previo y señala periodos/exports recalculados.

### Cierre mensual propuesto

1. Descargar los reportes oficiales de periodo de App Store Connect Financial Reports y Google Play Earnings; no mezclar con reportes de tendencia.
2. Importar y revisar cantidad de filas, totales, moneda, periodo y warnings; confirmar cada lote aprobado.
3. Registrar payouts y evidencia bancaria, vincular a proceeds del periodo y documentar diferencias/fees bancarios.
4. Registrar costos facturados/pagados y compromisos que afecten reserva/bucket; verificar moneda/tasa/fuente.
5. Revisar SKU sin mapear, filas huérfanas, tasas faltantes, refunds/ajustes posteriores y productos no consumibles/entitlements de tiendas.
6. Exportar conciliación, confirmar cierre solo si las diferencias están explicadas; si no, dejar periodo parcial/abierto con responsable y próxima acción.
7. Guardar el runbook y evidencia de acceso/roles en ubicación privada; no enviar reportes por automatizaciones externas.

## Decisiones que se difieren a tarjetas base

- GRT-009 define nombres de rutas, DTOs, errores, service boundaries y convenciones de auditoría.
- GRT-010 define migraciones finales, retención/borrado, timezone y precisión decimal compartida.
- GRT-021 aprueba matriz RBAC y auditoría de acciones administrativas.
- GRT-036/045 definen almacenamiento/retención, backups y evidencia de recuperación.
- GRT-047 proporciona presupuesto/licencia real; GRT-002/003/048 proporcionan mapa/precios SKU por storefront; GRT-034 aprueba umbrales de decisión.

## Criterios de diseño cubiertos

- GRT-043: formato de entrada, staging/previsualización, errores, deduplicación, trazabilidad, costos, FX y ajustes inmutables definidos.
- GRT-044: filtros, métricas, gráficas, estados vacíos/error, accesibilidad y drill-down de conciliación definidos.
- GRT-045: matriz manual de conciliación, permisos, casos de moneda/refund/duplicado/ajuste y runbook definidos.
- **Implementación y aceptación en staging siguen pendientes** hasta completar prerequisitos y trabajo de código.

## Fuentes oficiales consultadas

- [Apple: campos del reporte financiero](https://developer.apple.com/help/app-store-connect/reference/reporting/financial-report-fields/) — importes, campos de producto, moneda, `Sale or Return`, Customer Price y Partner Share.
- [Apple: ver pagos y proceeds](https://developer.apple.com/help/app-store-connect/getting-paid/view-payments-and-proceeds/) — rol requerido, pagos emitidos/estimados, FX, withholding y diferencias de banco.
- [Google: reportes financieros mensuales](https://support.google.com/googleplay/android-developer/answer/6135870?hl=en) — Earnings/Estimated Sales separados; archivos mensuales, transacciones por línea, comisiones, chargebacks y ajustes.
- [`ADR-002 — Stack del backoffice`](../decisions/ADR-002-backoffice-stack.md).
- [`Modelo financiero GRT-042`](../product/financial-dashboard.md).
