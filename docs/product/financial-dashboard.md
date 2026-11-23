# Modelo financiero y KPIs del backoffice

**Tarjeta:** GRT-042 — Definir modelo financiero, KPIs y fuentes  
**Estado:** definición para pago único y costos API recurrentes; GRT-048 aprobó precios MXN $399 introductorio / $699 estándar. Proceeds/costos reales y configuración por storefront pendientes de implementación y conciliación.
**Actualizado:** 13 de noviembre de 2026.  
**Moneda de presentación:** MXN.  
**Consumidores:** GRT-034 (Go/No-Go), GRT-043 (importación y costos), GRT-044 (dashboard), GRT-045 (conciliación y permisos) y GRT-047 (bucket multimedia).

## Propósito y límites

El módulo sirve para monitorear ventas, liquidaciones, costos y economía unitaria del MVP. No sustituye la contabilidad, declaraciones fiscales, facturación ni estados oficiales de Apple o Google. Las cifras del tablero deben ser rastreables al reporte o comprobante de origen.

El MVP importa manualmente CSV de App Store Connect y Play Console y registra costos manualmente. No integra APIs de tiendas, no calcula obligaciones fiscales mexicanas y no hace pronósticos automáticos. Los reportes de tienda son fuente para actividad de ventas y proceeds; el depósito bancario es fuente para efectivo recibido; los comprobantes registrados son fuente para costos pagados. Los eventos de entitlement del backend sirven para validar acceso y conteos internos, no reemplazan reportes de caja.

## Convenciones del modelo

1. **Periodo de actividad** es el periodo de transacción/venta informado por la tienda; **periodo de liquidación** es el periodo que cubre el reporte financiero; **fecha de caja** es la fecha del depósito o pago comprobado. Se guardan los tres cuando existan y no se fuerzan a coincidir.
2. Toda cifra almacena importe decimal, código ISO de moneda original, importe convertido, moneda de presentación, tasa aplicada, fecha efectiva de la tasa, fuente de la tasa, reporte/archivo, periodo, tienda y estado. Nunca se suman importes de monedas distintas sin conversión explícita.
3. Cálculos monetarios usan precisión decimal y redondeo a 2 decimales solo al presentar o contabilizar cada línea conforme al reporte original. Se conserva el valor y la precisión de origen.
4. Cada dato se etiqueta como **real reportado**, **real pagado**, **estimado de tienda**, **supuesto** o **proyección**. Las estimaciones y proyecciones se muestran en paneles separados de importes pagados.
5. Los reportes originales se conservan sin modificación, con nombre, hash, fecha de importación, usuario importador, tienda, versión/formato y resultado de validación. Reimportar el mismo archivo/línea no debe duplicar importes; correcciones y ajustes se conservan como nuevas versiones o líneas compensatorias auditables.
6. Periodos de Apple siguen su calendario fiscal y zona de reporte; reportes diarios de Sales and Trends usan PT. Google Earnings es mensual. El dashboard ofrece el periodo de origen de la tienda y un filtro calendario MX (America/Mexico_City) claramente rotulado; no refecha transacciones silenciosamente.
7. Un reporte incompleto, con errores de parseo o sin moneda/fuente muestra estado **incompleto** y queda fuera de totales confirmados. No se imputan valores faltantes como cero.

## Diccionario de KPIs financieros

| KPI | Definición y fórmula | Fuente y frecuencia | Estado/lectura |
|---|---|---|---|
| Ventas brutas de cliente | Suma de importes cobrados al comprador antes de deducciones, según el campo de precio/importe de cliente disponible por línea. En Google, usar las líneas y moneda originales de Earnings; el reporte Estimated Sales solo sirve para tendencia. En Apple, usar Customer Price/ventas si el reporte lo ofrece; no sustituirlo por proceeds. | Reporte financiero mensual de tienda; Estimated Sales de Play solo en vista de tendencia. | Actividad reportada, no efectivo recibido. Se desglosa por tienda, storefront, producto/tier, tipo de transacción y moneda cuando el archivo tenga esos campos. |
| Impuestos y retenciones reportados por tienda | Suma por separado de impuestos indirectos incluidos/ajustados, retención fiscal y ajustes que el reporte identifica. No inferir tasas ni obligaciones fiscales locales. | Reportes financieros de tienda por cierre; revisión mensual. | Reportado por tienda; no equivale a liquidación fiscal mexicana. Desconocido se muestra como “sin dato”. |
| Comisión / cargos de tienda | Suma de comisiones, Google fees, service fees u otros cargos identificados en el reporte. Una comisión efectiva observada se calcula como cargos / base elegible del mismo reporte, storefront y periodo; solo cuando ambas bases están disponibles. | Apple Financial Report/Payment information; Google Earnings; mensual. | Real reportado. Si el reporte ya incluye proceeds netos, no volver a restar comisión para derivar el neto. |
| Reembolsos, contracargos y devoluciones | Suma con signo negativo de importes de retorno/refund/chargeback del reporte, clasificados por fecha de transacción y fecha de ajuste si difieren. | Reporte financiero de tienda; mensual y ajustes subsecuentes. | Real reportado. No contar dos veces una devolución ya neteada en proceeds. |
| Proceeds netos reportados | Importe neto que la tienda declara adeudar al desarrollador luego de deducciones que estén reflejadas en su campo proceeds/partner share/earnings. | Apple Financial Report o Google Earnings; mensual. | Real reportado, pendiente de caja hasta comprobar depósito. No recalcular con una tasa estándar si el reporte da el neto. |
| Efectivo neto liquidado | Suma de depósitos bancarios netos atribuibles a las tiendas, convertidos con el importe/tasa efectivos informados por tienda o banco. Si hay comisiones bancarias identificadas, se presentan aparte y el importe recibido coincide con el estado de cuenta. | Pago en App Store Connect/Play Console + estado bancario y referencia de pago; por depósito. | Real pagado. Apple indica que fees bancarios/intermediarios pueden hacer variar el depósito frente al pago emitido. |
| Cuentas por cobrar de tienda | Proceeds netos reportados − pagos aplicados a esos mismos periodos, ajustado por saldos arrastrados, umbrales y diferencias documentadas. | Reporte mensual + referencias de depósito; mensual. | Real reportado pendiente de liquidar; no se suma a caja. |
| Costos operativos registrados | Suma de costos con fecha, categoría, proveedor/referencia, moneda, importe, evidencia y estado (estimado, comprometido, facturado, pagado). Subcategorías: licencias/contenido, adquisición, infraestructura, soporte y operación/desarrollo. | Facturas, recibos, contratos y registro interno; actualización al ocurrir. | Separar devengado/comprometido de pagado. Horas del fundador no remuneradas se informan como aporte no pagado y no se omiten silenciosamente de un análisis económico completo. |
| Resultado operativo de caja del periodo | Efectivo neto liquidado en el periodo − costos efectivamente pagados en el periodo. Mostrar también neto de caja antes de costos no recurrentes, sin ocultarlos. | Depósitos y costos pagados; mensual. | Real pagado. No representa utilidad fiscal ni resultado devengado. |
| Resultado operativo devengado estimado | Proceeds de tienda reconocidos para el periodo de actividad − costos facturados/devengados atribuibles al mismo periodo. Identificar criterio de reconocimiento y ajustes posteriores; etiqueta de gestión, no asiento contable. | Reportes cerrados de tienda y comprobantes; mensual, con reexpresión auditada por ajustes. | Real reportado/registrado, no necesariamente cobrado. No sumar con resultado de caja. |
| Compras únicas validadas | Conteo de SKUs no consumibles validados por tienda y ligados a cuenta, separando devoluciones/contracargos. | Reporte de tienda + entitlement backend; por transacción y cierre mensual. | Unidades pagadas; restauraciones no cuentan como venta nueva. |
| Ingreso neto por compra (realizado) | Proceeds reportados de desbloqueos ÷ compras pagadas del SKU en el mismo periodo/storefront/moneda; mostrar numerador y denominador. | Reportes financieros y mapeo versionado de SKU; mensual. | Proceeds por unidad, no margen ni efectivo; excluir restauraciones y clasificar devoluciones con signo. |
| Contribución por cohorte de compra | Proceeds de la cohorte menos costos variables atribuibles (adquisición, soporte variable y API si existe método explícito). Costos compartidos se muestran aparte o con método versionado. | Reportes de tienda, cohortes y ledger; mensual y acumulado 12/24 meses. | Contribución antes de costos fijos; no anualizar como MRR. |
| CAC observado | Gasto de adquisición pagado atribuible a campaña/cohorte ÷ nuevas compras pagadas atribuibles en la ventana definida. Si atribución es incompleta, mostrar CAC combinado aproximado y limitación. | Facturas/plataformas de marketing y compras atribuidas con consentimiento/configuración válida; por cohorte. | No incluir leads ni descargas como pagadores. Con cero compras se muestra N/D, nunca cero. |
| Costo API MuscleWiki | Cuota mensual, top-ups, impuestos y FX pagados/comprometidos, separados por plan y periodo; calcular costo por 1,000 llamadas y por usuario activo cuando se disponga del denominador. | Panel/recibo MuscleWiki, contador de llamadas y ledger; mensual. | Separar Testing/Growth/Professional/Enterprise y real/comprometido/proyectado. No es pago del cliente. |
| Bucket de catálogo disponible | Aportes efectivamente recibidos y asignados al fondo de catálogo − costos del catálogo efectivamente pagados − reservas comprometidas documentadas. Saldo de presupuesto aprobado se informa por separado del saldo en efectivo. | Registro financiero interno, transferencias y facturas; actualización por movimiento. | Efectivo reservado para decisión de catálogo; no es ingreso, utilidad ni autorización para usar medios sin licencia. |
| Consumo del presupuesto multimedia | Gasto real pagado del bucket ÷ presupuesto aprobado; mostrar también comprometido, cotizado y restante incluyendo contingencia. | GRT-047, cotización/licencia y comprobantes; al actualizar. | Estimado vs real claramente separado. Registrar moneda fuente, FX, fecha/fuente, impuestos y contingencia. |
| Equilibrio/runway API (escenario) | Ventas únicas necesarias: `ceil((costos del horizonte + API recurrente + CAC + inversión incluida) / proceeds netos promedio por compra)`. Mostrar meses de caja disponible por etapa de API; si proceeds ≤ 0, “no alcanzable con estos supuestos”. | GRT-002/003/034, reportes de tienda, tarifas API y caja asignada; mensual. | Proyección, nunca MRR. Mostrar horizonte, moneda, plan API, FX, reserva y supuestos. |

### Métricas económicas por nivel y gate de lanzamiento

El desbloqueo completo se calcula por SKU/storefront MXN. Mostrar precio único, proceeds por periodo, comisión, impuestos/retenciones, reembolsos, contribución por cohorte, CAC, cuota API recurrente, runway de caja y ventas nuevas requeridas para equilibrio a 12/24 meses. No presentar MRR ni suscriptores como KPIs del negocio Gym Rat.

GRT-034 consume escenarios del dashboard, pero el panel no decide Go/No-Go automáticamente. La decisión requiere indicar muestra y ventana del piloto, evidencia de precio, costos reales vs supuestos, reversibilidad y umbrales. La regla para financiar otra tanda de medios requiere demanda observada y efectivo del bucket suficiente para cotización más 20% de reserva; pronósticos y presupuesto aprobado no son efectivo disponible. No se vende ni publica contenido sin autorización comercial escrita aplicable.

## Fuentes de datos y autoridad

| Fuente | Uso autorizado en el modelo | Periodicidad/disponibilidad | Notas de tratamiento |
|---|---|---|---|
| App Store Connect — Payments and Financial Reports / Financial Report | Ventas finales, proceeds, impuestos/ajustes reportados, unidades, moneda, territorio y datos de pago. Fuente financiera para obligaciones de Apple y cierre de proceeds. | Reporte del mes fiscal previo cuando esté finalizado; exportación manual en MVP. | Partner Share/Extended Partner Share ya reflejan proceeds después de impuestos aplicables y comisión; no volver a deducirlos. Proceeds estimados no son el pago final. El monto recibido puede diferir por FX, withholding y fees bancarios. Roles requeridos conforme a consola; mantener rol Finance/Admin. |
| App Store Connect — Sales and Trends | Tendencia y actividad de transacciones, productos y suscripciones. | Diario/semanal/mensual según informe; datos estimados de ventas/proceeds y FX basado en promedio móvil. | No usar como fuente de efectivo ni reemplazar Financial Reports. Distinguir PT/calendario fiscal Apple del calendario de caja. |
| Play Console — Earnings report | Transacciones mensuales, cargos/Google fees, devoluciones/chargebacks y montos originales/convertidos. Fuente financiera para earnings y payout. | Mensual; suele estar disponible en el mes siguiente. Importación manual MVP. | Cada fila puede representar un tipo de transacción; preservar importe/moneda original y convertidos. Ajustes pueden aparecer en archivos adicionales o ciclos posteriores. |
| Play Console — Estimated sales report | Tendencia de ventas de baja latencia. | Alta frecuencia. | Incluye precio pagado por compradores antes de impuestos y fees; Google advierte que no se use para contabilidad y que puede excluir withholding/chargebacks. Mantener fuera de totales financieros cerrados. |
| Backend Gym Rat — entitlements y eventos de validación | Conteo operativo de accesos pagados y catálogo/tier asignado; mapeo de transacción a cuenta sin exponer PII en el dashboard financiero. | Evento y snapshot diario. | Fuente de verdad del entitlement conforme ADR-001; no es fuente de monto cobrado. Reportar solo datos agregados y referencias técnicas mínimas. |
| Estado de cuenta bancario / aviso de pago de tienda | Efectivo recibido, fecha de depósito, importe, divisa y referencia. | Por depósito. | Reconciliar con pago emitido por tienda. Cargos bancarios identificados se registran aparte; no atribuir diferencia a comisión de tienda. |
| Factura, recibo, contrato y registro interno de costos | Costos fijos/variables, licencias, adquisición, infraestructura, soporte, operación y compromisos. | Al incurrir y al pagar. | Guardar evidencia con acceso limitado; dashboard no expone datos personales innecesarios. Si solo existe cotización, clasificar como estimado; no como gasto real. |
| Tabla de productos/SKUs y precios por tienda | Mapeo versionado de product ID no consumible a storefront, moneda, precio y vigencia. | Al cambio, con histórico. | Origen: configuración aprobada de App Store Connect/Play Console y decisiones GRT-002/003/048. Precio mostrado al cliente no sustituye importe de proceeds. |
| Tipo de cambio registrado | Conversión a MXN para análisis de gestión. | Por tasa asociada a transacción o periodo, guardando evidencia. | Preferencia: conservar el FX de liquidación reportado para caja; para comparativos por periodo, definir una fuente y política de tasa fechada. No inventar fuente/tasa ni sobrescribir la tasa original. |

Las reglas actuales de formato, campos y periodicidad de las tiendas pueden cambiar; confirmar en cada exportación. Apple dispone de reportes separados de Sales and Trends y Financial Reports; Google separa Estimated Sales de Earnings. En consecuencia, este modelo no agrega esos conjuntos como si fueran equivalentes.

## Categorías y tratamiento de costos

| Categoría | Ejemplos | Tratamiento mínimo |
|---|---|---|
| API MuscleWiki | Plan mensual, llamadas/top-ups, impuesto y FX; etapa Testing/Growth/Professional/Enterprise | Costo recurrente del proveedor, no suscripción Gym Rat. Registrar real/comprometido/proyectado, cuota y fuente. GRT-047 administra el bucket inicial MXN $3,687 y runway API. |
| Adquisición | Campañas y materiales pagados | Variable cuando atribuible a campaña/cohorte; separar gasto total de CAC atribuible. |
| Infraestructura | Hosting, base de datos, almacenamiento, CDN, monitoreo | Fijo o escalonado; separar estimación de factura y pago. |
| Soporte | Herramientas y horas contratadas | Registrar gasto atribuible y periodo; ocultar información personal de usuarios. |
| Operación/desarrollo | Servicios, herramientas, trabajo contratado | Registrar importes pagados/devengados. Trabajo de fundador no remunerado se muestra como exclusión del cálculo de caja y como costo económico imputado separado si se modela. |
| Comisiones bancarias y FX | Banco intermediario, conversión | Separar de comisión de tienda y registrar importe/divisa comprobados. |

## Frecuencia de actualización y responsables

- **Por importación:** Producto/Finanzas carga reportes de tienda; el sistema previsualiza periodo, tienda, moneda, totales, filas inválidas y duplicados. El mismo usuario puede preparar la importación, pero confirmar/cerrar requiere rol `Finance` o `Admin`; GRT-045 definirá autorización final.
- **Por depósito:** Finanzas vincula pago de tienda con comprobante bancario y registra diferencias/cargos.
- **Al incurrir o pagar un costo:** Finanzas registra evidencia, categoría, moneda, fecha, estado y centro/tier si aplica. Cambios posteriores conservan actor, fecha y valor anterior.
- **Diario:** snapshot de entitlements de compra, sesiones y consumo/errores API; no es una cifra de ingreso o caja.
- **Mensual:** revisar cada tienda cuando el reporte esté disponible, importar ajustes, conciliar cuenta por cobrar y depósitos, cerrar periodo o dejarlo explícitamente pendiente. Reabrir con motivo y asiento de ajuste; no editar silenciosamente el histórico.
- Cada panel presenta **periodo**, **tienda**, **moneda de origen**, **moneda de presentación**, **estado**, **fecha de última actualización** y **fuente**. Reportes parciales muestran advertencia y no se comparan contra periodos cerrados como si fueran completos.

## Ejemplo de conciliación reproducible (datos ficticios)

Este ejemplo es únicamente una prueba manual de las fórmulas; no representa tarifas ni ventas reales. Supóngase un archivo de tienda para un periodo con una moneda común y los siguientes totales informados: ventas de comprador MXN 1,000.00; impuestos/ajustes deducidos MXN 80.00; comisión/cargos MXN 150.00; reembolsos netos MXN 20.00. El reporte declara proceeds de MXN 750.00. Identidad de comprobación: `1,000 − 80 − 150 − 20 = 750`.

La tienda informa pago emitido de MXN 750.00 y el banco acredita MXN 740.00, con cargo bancario identificado de MXN 10.00. El registro muestra proceeds reportados 750.00, efectivo neto liquidado 740.00, comisión bancaria 10.00 y diferencia no explicada 0.00. Si el banco acredita 735.00 sin evidencia del cargo faltante, se reporta una diferencia abierta de MXN 5.00; no se ajusta la comisión de tienda para hacer cuadrar.

Si además se pagan MXN 300.00 de costos operativos durante ese periodo, el resultado operativo de caja es `740 − 300 = MXN 440.00`. Si el bucket tiene presupuesto aprobado de MXN 2,000, gastos pagados de MXN 400 y compromisos documentados de MXN 300, se informa saldo de presupuesto no comprometido MXN 1,300; el saldo de efectivo del bucket solo puede afirmarse con movimientos de caja comprobados. Las ventas únicas se informan por cohorte y periodo; no se proyectan como MRR.

Para reproducir, una tercera persona debe poder abrir archivo fuente y comprobante, identificar las líneas incluidas, verificar signo/moneda/tasa, consultar el mapeo SKU/categoría vigente y rehacer cada suma. Si falta un campo en el reporte real, se presenta **sin dato** y la identidad queda parcialmente conciliada, no rellenada con un supuesto invisible.

## Acceso y privacidad

El módulo financiero se limita a roles financieros y administradores explícitamente autorizados. Operaciones puede capturar metadatos operativos no sensibles si se define así en GRT-045, pero no consultar importes ni exportaciones financieras. Acciones de importación, edición, confirmación, cierre, reapertura y exportación quedan auditadas con actor/fecha/referencia. No copiar correos, nombres de clientes, datos de pago ni identificadores personales a KPIs, logs o automatizaciones; restringir archivos fuente y exportaciones. No se almacenan datos de tarjeta.

## Pendientes que no bloquean la definición del modelo

- GRT-048 aprobó precios MXN $399 introductorio / $699 estándar; GRT-003 configura el SKU por storefront. Usar proceeds reales y reportes financieros, no los anclajes USD de investigación.
- GRT-003: confirmar comisiones/condiciones por storefront, elegibilidad Apple y reglas de Google vigentes para México. Los porcentajes de escenarios previos en `pricing-hypotheses.md` y `ADR-001-payments.md` son supuestos hasta confirmación.
- GRT-046/GRT-047: no hay IDs precargados; el catálogo se consulta bajo demanda. Confirmar autorización de plan pagado/disclosure, consumo real por usuario y runway API; el presupuesto GymVisual USD 165/198 quedó sustituido y no es gasto vigente.
- GRT-043/045: definir esquemas importables, política final de cierre y autorización en la implementación sin alterar estas definiciones.
- GRT-034: acordar muestra y umbrales Go/No-Go y usar datos reales del piloto cuando existan.

## Criterios de aceptación de GRT-042

- [x] Cada KPI tiene definición, fórmula, fuente, frecuencia y etiqueta de real/estimado/escenario.
- [x] Ventas brutas, impuestos, comisiones, reembolsos, proceeds y efectivo están separados; las deducciones no se aplican dos veces.
- [x] Costos y bucket de catálogo distinguen cotización, presupuesto, compromiso, devengo y pago.
- [x] Contribución por SKU/cohorte de compra única, CAC y punto de equilibrio tienen base, exclusiones y límites claros; no se calculan MRR ni suscriptores activos Gym Rat.
- [x] FX conserva moneda fuente, tasa, fecha y procedencia; México se presenta en MXN.
- [x] Se documentaron roles, frecuencia, fuentes oficiales y un ejemplo aritmético reproducible.
- [ ] GRT-002 y GRT-048 ya definen oferta/precio; GRT-003 configura el SKU por tienda. GRT-047 define las etapas API y reserva, pero caja/contratación real debe comprobarse. GRT-034 incorpora proceeds y costos observados para rentabilidad y Go/No-Go. GRT-046 define el catálogo; llamadas reales se verifican en GRT-013.

## Fuentes oficiales y documentación relacionada

- [Apple: campos del reporte financiero](https://developer.apple.com/help/app-store-connect/reference/reporting/financial-report-fields/) — campos de ventas finales y proceeds/Partner Share.
- [Apple: ver pagos y proceeds](https://developer.apple.com/help/app-store-connect/getting-paid/view-payments-and-proceeds/) — diferencia entre proceeds estimados/pagados, impuestos, comisión, FX y posible variación por comisiones bancarias.
- [Apple: pagos y reportes financieros](https://developer.apple.com/help/app-store-connect/getting-paid/overview-of-receiving-payments/) — calendario fiscal, transacciones finalizadas y plazo/condiciones de pago.
- [Apple: disponibilidad de Sales and Trends](https://developer.apple.com/help/app-store-connect/reference/reporting/sales-and-trends-reports-availability) — tipos y calendario de reportes.
- [Google: descargar/exportar reportes mensuales](https://support.google.com/googleplay/android-developer/answer/6135870?hl=en) — diferencia entre Earnings y Estimated Sales, estructura de transacciones y periodicidad.
- [`ADR-001 — Pagos in-app`](../../decisions/ADR-001-payments.md) — fuente de verdad de entitlements y pagos del MVP.
- [`pricing-hypotheses.md`](pricing-hypotheses.md) — precios aprobados, supuestos económicos y economía del catálogo.
- [`PRD-06 — Backoffice y finanzas`](prd/06-backoffice-y-finanzas.md) — requisitos de superficie, conciliación y mínimo privilegio.
