# Gym Rat — precios y costos del catálogo

**Estado:** precios aprobados por Producto el 13 de noviembre de 2026: apertura beta MXN $399 y precio estándar de crecimiento MXN $699; un SKU no consumible por persona, pago único, sin suscripción de Gym Rat. La configuración de producto/precio por storefront y la conciliación de proceeds reales siguen pendientes de implementación. Los términos públicos de MuscleWiki permiten uso comercial pagado, llamadas desde la app propia y display/streaming dentro de ella; la licencia es revocable y no permite exportar/revender datos.  
**Actualizado:** 13 de noviembre de 2026.  
**Tarjetas:** GRT-002, GRT-003, GRT-023, GRT-025, GRT-026, GRT-034, GRT-047 y GRT-048.

## Oferta a clientes

Gym Rat no venderá suscripciones recurrentes en el MVP. Free/Warm-Up es una vista previa de un solo entrenamiento registrado por cuenta; tras guardarlo se presenta inmediatamente la oferta de compra. Free no recibe miniaturas, imágenes, videos ni otros medios. Las consultas de texto pasan por el backend y están limitadas por la cuota compartida del plan MuscleWiki activo. Un SKU no consumible habilita permanentemente las funciones Gym Rat incluidas; medios de terceros dependen del plan API vigente. Producto aprobó el precio y la ventana introductoria; GRT-003 configura MXN por storefront y GRT-034 mide conversión/proceeds reales.

| Oferta | Modelo de cobro | Precio actual |
|---|---|---|
| Free / Warm-Up | Un entrenamiento registrado por cuenta; sin imágenes, miniaturas, videos ni historial posterior | MXN $0 |
| Desbloqueo Gym Rat — apertura beta | Un pago, compra móvil no consumible; acceso permanente a las funciones propias descritas en SKU | **Aprobado: MXN $399**, primeras 100 compras o primeros 60 días, lo que ocurra primero |
| Desbloqueo Gym Rat — crecimiento | El mismo SKU y entitlement permanente; precio estándar cuando termine la oferta inicial | **Aprobado: MXN $699**; configurar por storefront |
| Paquete de catálogo adicional | Compra única opcional solo si se aprueba alcance futuro | Fuera del SKU inicial hasta validar derechos, coste y demanda |

No mostrar los antiguos precios mensuales/anuales de Garage Rat e Iron Rat como oferta activa. Los nombres pueden quedar como etapas/capacidad interna de crecimiento, no como planes de suscripción al usuario.

La compra única cubre las funciones propias de Gym Rat detalladas en el SKU; no convierte el contenido de MuscleWiki en propiedad ni garantiza disponibilidad perpetua de medios externos. El aviso previo a la compra debe decir que la consulta y reproducción dependen del API, sus cuotas, disponibilidad, plan pagado y términos vigentes. Gym Rat debe seguir permitiendo ver/editar rutinas propias y registrar entrenamientos cuando falte el API. No vender el precio de beta como prueba gratis, ni presentar el precio futuro como compromiso definitivo antes de configurarlo en las tiendas.

## MuscleWiki: planes API y puertas de crecimiento

Las cuotas son costos de proveedor pagados por Gym Rat, independientes de la compra única de los clientes. Precios públicos en USD consultados el 13/11/2026; confirmar impuestos, facturación anual, tipo de cambio y precio vigente antes de contratar. La página declara uso comercial para planes pagados.

| Etapa | Plan API | Precio público | Cuota/alcance útil | Criterio de avance |
|---|---|---:|---|---|
| 0 — integración y beta cerrada | Testing | USD $10/mes | 1,000 llamadas/mes; API key; inglés; sin rutinas/workouts ni bodymaps | Solo conectar, validar llamadas y probar video. No usar para lanzamiento público en español. |
| 1 — piloto público | Growth | USD $39.99/mes | 30,000 llamadas/mes; 14 idiomas; uso de producción; sin rutinas/workouts ni bodymaps | Subir después de que integración y contrato estén aprobados; construir las rutinas Gym Rat propias. |
| 2 — expansión del producto | Professional | USD $99.99/mes | 100,000 llamadas/mes; rutinas/workouts, bodymaps, músculos secundarios/terciarios | Subir si las funciones se necesitan o el uso sostenido proyectado alcanza 70% de cuota Growth durante 2 meses consecutivos. |
| 3 — escala | Enterprise | USD $199.99/mes | 300,000 llamadas/mes; funciones Professional y soporte prioritario | Opción futura, fuera de la ruta inicial; considerar solo si se requieren sus capacidades. |

La opción anual publicada ofrece 25% de descuento pagado por adelantado durante 12 meses, sin cuota adicional de llamadas: estimados derivados de tarifas mensuales: Testing USD $90.00/año, Growth USD $359.91/año, Professional USD $899.91/año y Enterprise USD $1,799.91/año. La cuota se reinicia mensualmente; verificar el total del checkout y los impuestos antes de contratar. El plan anual compromete doce meses.

Mantener alertas al 70%, 85% y 95%. Una respuesta 429 al agotar cuota debe degradar la experiencia con mensaje accionable y sin bloquear el registro de entrenamiento. Top-ups publicados: 5,000 llamadas por USD $10 en Growth/Professional/Enterprise; solo comprar tras demostrar que el costo unitario y la cuota base siguen siendo mejores, ya que expiran al siguiente reset mensual. No depender del plan gratuito Basic para la app: funciona solo en Playground y no tiene API key directa.

### Financiación y sostenibilidad

Antes de avanzar de etapa, el saldo disponible en la reserva de operación debe cubrir **12 meses del plan API objetivo más 20% de contingencia**, impuestos y FX. Fórmula: `reserva mínima MXN = cuota mensual USD × 12 × tipo de cambio presupuestado × 1.20 + impuestos/cargos`. Las ventas únicas futuras aportan nuevos ingresos, pero cada cliente mantiene acceso permanente; no asumir renovaciones para financiar la API.

GRT-047 mantiene el bucket inicial de **MXN $3,687** como reserva informada; reconocerlo como caja solo con fondos comprobados. Al tipo Banxico para pagos de 13/11/2026 de MXN $18.6192/USD, el plan anual (25% de descuento, pago por adelantado) más 20% de reserva equivale aproximadamente a MXN $2,010.87 para Testing y MXN $8,041.48 para Growth, antes de impuestos/FX. El bucket no cubre el pago anual Growth +20%; faltan unos MXN $4,354.48 antes de impuestos/FX. Consultar [Banxico — tipo de cambio](https://www.banxico.org.mx/tipcamb/tipCamMIAction.do?idioma=sp) y recalcular al contratar.

**Gate financiero:** no activar un plan API superior si la caja disponible no cubre 12 meses del costo total con contingencia o si el precio único aún no cubre el costo de adquisición y operación esperado por comprador. GRT-034 compara el ingreso neto de ventas únicas (después de comisión, impuestos y reembolsos) con soporte, tiendas, proveedor, infraestructura y adquisición para horizontes de 12/24 meses. Si no cierra, reducir catálogo/llamadas, diferir el lanzamiento público o revisar el precio único antes de vender.

## Uso de contenido MuscleWiki

- No descargar ni guardar videos; transmitirlos dentro de la app desde URLs/API de MuscleWiki. El proxy del backend debe ser transparente, pasar encabezados sin cambios y no escribir bytes de video a disco, almacenamiento ni caché compartida/CDN.
- Metadatos de texto: caché hasta 30 días; miniaturas privadas solo en el dispositivo hasta 24 horas si los headers lo permiten; bodymaps `no-store`; video `no-store` y solo buffer transitorio de reproducción.
- Mantener intacta la marca incrustada en videos y añadir literalmente “Exercise data and videos provided by MuscleWiki.com” a Términos/Privacidad o documento equivalente.
- La API es revocable y el proveedor puede retirar contenido/endpoints y cambiar precios con 7 días de aviso. La permanencia del entitlement de Gym Rat no debe presentarse como garantía de disponibilidad ininterrumpida de medios de tercero. Definir contingencia y disclosure tras aclaración contractual.
- Clave API exclusivamente en Laravel/backend, nunca en el binario móvil.

## Hipótesis de precio y cobertura de API

Escenario de referencia para México: Growth anual **USD $359.91** (pago anticipado, descuento publicado de 25%), tipo Banxico de **MXN $18.6192/USD** al 13/11/2026 y reserva operativa de 20% → **MXN $8,041.48/año**, antes de impuestos, FX aplicado por el procesador, comisiones o infraestructura. Se usa 30% de comisión de tienda como supuesto conservador de planeación, no como tarifa contractual confirmada; a falta de proceeds reales se excluyen IVA/impuestos, reembolsos, soporte, infraestructura, marketing y costos administrativos. Por eso este cálculo es umbral inferior, no margen neto.

| Precio único hipotético | Después de supuesto 30% tienda | Ventas nuevas para cubrir solo Growth anual + 20% reserva |
|---:|---:|---:|
| MXN $399 (beta de apertura) | MXN $279.30 | 29 ventas/año |
| MXN $699 (estándar crecimiento) | MXN $489.30 | 17 ventas/año |

El precio introductorio está aprobado para las primeras 100 compras o los primeros 60 días de beta abierta, lo que ocurra primero; después se aplicará MXN $699. Aun con 17 compras nuevas al precio estándar cada año, el modelo solo cubre el plan Growth anual con reserva; no cubre los demás costos ni demuestra que un comprador individual haya financiado su propio acceso de por vida. Si cesan las ventas, la cuota de la API debe financiarse con reserva existente u otra fuente aprobada. No prometer videos perpetuos; mantener valor duradero en funcionalidades e historial propios.

La cuota Growth es 30,000 llamadas al mes: daría como techo aritmético 3,000 usuarios activos si cada uno consumiera 10 llamadas, 1,000 si consumiera 30, o 500 si consumiera 60. No es pronóstico: `/stream/` y cada `Range` consumen llamadas adicionales, y las solicitudes ampliadas pueden costar más. Durante beta, medir llamadas reales por usuario y sesión, top-10% de consumo, costo de medios y tasa de 429 antes de escalar. Growth es el mínimo de beta abierta en español; Testing cuesta menos pero solo ofrece inglés y 1,000 llamadas, así que no sirve como plan de producción español.

**Regla de sostenibilidad:** ventas nuevas requeridas = `ceil((costo anual API objetivo + reserva + gastos incrementales e impuestos) / proceeds netos reales por compra)`. Actualizar proceeds con reportes Apple/Google, país, impuestos, reembolsos y programa de tarifas real; recalcular para Professional si se habilitan esas capacidades. La compra es pago único; no asumir LTV recurrente.

## GRT-002 — decisión de producto: Free y desbloqueo

| Área | Base documentada | Falta para cerrar |
|---|---|---|
| Desbloqueo completo | Pago único no consumible; precio aprobado GRT-048; funciones propias listadas en `mvp-scope.md`; medios MuscleWiki dependen de API/plan/licencia. | Copy de compra y configuración por tienda pasan a GRT-003/026; no reabrir el precio salvo nueva evidencia o decisión de Producto. |
| Free/Warm-Up | Una sesión guardada por cuenta; paywall inmediato después de guardar; sin medios de terceros; API textual bajo demanda y cuota compartida con caché autorizada. | Decisión aprobada por Producto el 13 nov 2026; controles operativos y de producto descritos en `musclewiki-api-integration.md`. |
| Evidencia | GRT-001 reporta 10 entrevistas sobre el problema de registro. | La disposición a pagar no está validada empíricamente. Medir exposición al paywall, conversión, abandono y llamadas/usuario durante beta en GRT-034; no describir esta decisión como hallazgo de mercado. |

La decisión de paquete Free/desbloqueo queda cerrada como decisión de Producto y está formalizada en [ADR-005](../decisions/ADR-005-free-preview-and-api-budget.md). La eficacia de conversión y la sostenibilidad de cuota siguen siendo hipótesis a medir en beta; no bloquean el alcance documental del MVP y no deben reportarse como validadas hasta contar con datos.

## Pendientes de precio al consumidor

1. Configurar el producto no consumible, sus precios/oferta introductoria y restauración en los storefronts de Apple/Google (GRT-003).
2. Conciliar proceeds reales por tienda, FX, impuestos, reembolsos y costos API escalonados (GRT-034).
3. Divulgar que el unlock permanente cubre funciones propias, mientras que la disponibilidad de metadatos/video de MuscleWiki depende del plan activo y está sujeta a términos de no exportación, revocación y retiro. Resolver expresamente la conservación de labels/metadatos de terceros en el historial más allá de 30 días.

## Fuentes

- [Precios de MuscleWiki API](https://api.musclewiki.com/pricing)
- [Términos de MuscleWiki API](https://api.musclewiki.com/api-terms)
- [ADR-003 — pago único](../decisions/ADR-003-one-time-full-unlock.md)
- [GRT-047 — presupuesto multimedia/API](../data/GRT-047-multimedia-budget-proposal.md)
