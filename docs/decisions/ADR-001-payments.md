# ADR-001 — Pagos in-app y costos por transacción

- **Estado:** supersedida por [ADR-003 — Desbloqueo completo mediante pago único](ADR-003-one-time-full-unlock.md) el 12 de noviembre de 2026. Se conserva como historial y para requisitos de compra in-app/validación que sigan aplicando.
- **Tarjeta:** GRT-003 — Mapear políticas de pago de tiendas y flujo de compra.
- **Fecha de revisión:** 23 de octubre de 2026.
- **Mercado confirmado:** México; mostrar y cobrar precios en MXN desde el lanzamiento. Los precios USD de referencia deben convertirse a precios/SKUs locales por tienda con fecha, fuente de tipo de cambio, impuestos y redondeo documentados.

> **Archivo histórico:** desde el 12 de noviembre de 2026 no es fuente vigente para oferta, SKU, estados de entitlement o checklist de cierre. Las secciones de suscripciones/paquetes y “Pendientes para cerrar GRT-003” a continuación describen la decisión anterior y quedan supersedidas por [ADR-003](ADR-003-one-time-full-unlock.md). Para el diseño vigente de compra única, usar ADR-003 y su diagrama de secuencia.

## Decisión

Todas las compras digitales del MVP se realizarán dentro de las apps móviles mediante Apple In-App Purchase en iOS y Google Play Billing en Android distribuido por Google Play. Incluye suscripciones Garage Rat/Iron Rat y paquetes temáticos de ejercicios de compra única; no se venderán ejercicios individuales salvo decisión posterior.

La web pública servirá para informar y captar leads. No tendrá checkout ni enlace de pago externo durante el MVP. La posibilidad de cobrar en web se difiere para una decisión posterior al lanzamiento.

El backend será fuente de verdad para entitlements de suscripción y derechos permanentes de paquetes. Validará estados/notificaciones de tiendas, los procesará de forma idempotente y los asociará a la cuenta Gym Rat para sincronizar acceso entre dispositivos. Reembolsos/contracargos revocan el paquete afectado; cancelar o expirar una suscripción no revoca paquetes comprados. La app permitirá restaurar compras y dirigir al usuario a la administración/cancelación de la tienda correspondiente.

## Contexto y políticas

Las funciones Pro son servicios digitales que se consumen en la app. La política de Google Play exige su sistema de facturación para estas compras salvo excepciones/programas aplicables. Apple también tiene reglas de compra in-app y excepciones regionales; este MVP seguirá el flujo nativo estándar y no dependerá de excepciones.

Fuentes oficiales revisadas:

- [Política de pagos de Google Play](https://support.google.com/googleplay/android-developer/answer/10281818?hl=en): incluye suscripciones de fitness y funciones digitales bajo su política de facturación.
- [App Review Guidelines de Apple, sección 3.1](https://developer.apple.com/app-store/review/guidelines/): requisitos de compra para funciones y servicios digitales.
- [Google Play service fees](https://support.google.com/googleplay/android-developer/answer/112622?hl=en): tarifas varían según mercado, programa y rollout.
- [Apple Developer Program y Small Business Program](https://developer.apple.com/programs/whats-included/): tarifa estándar y reducción condicionada a programa/elegibilidad.

## Fuente de estimación y costos base

El equipo aportó [Actualizatec — Comisiones de App Store y Google Play](https://actualizatec.com/blog/comisiones-ingresos-app-store-google-play/), actualizado el 4 de agosto de 2026. Se conserva como referencia de escenarios, contrastada con términos oficiales; sus porcentajes no se deben aplicar de forma universal.

| Plataforma / concepto | Supuesto de costo inicial | Notas |
|---|---:|---|
| Apple App Store | **15%** si se aprueba la elegibilidad para Small Business; **30%** como escenario conservador mientras no haya aprobación | Apple también publica 15% para renovaciones que califican tras el primer año. Modelo provisional a confirmar en App Store Connect. |
| Google Play, suscripción en México | **15%** para la estimación de lanzamiento | La documentación consultada indica 15% para suscripciones en mercados donde el nuevo esquema todavía no se ha desplegado. El calendario prevé despliegue global posterior al MVP; reconfirmar para México antes del lanzamiento. |
| Google Play, mercados que ya recibieron el esquema nuevo | **10% de tarifa de servicio + 5% de facturación de Play** al usar Google Play Billing | No asumirlo para México antes de que la actualización regional entre en vigor. |
| Cuenta Apple Developer | **USD 99/año** | Tarifa publicada por Apple; la moneda local puede variar. |
| Registro de Google Play Console | **USD 25, pago único** | Estimación citada por Actualizatec; verificar en Play Console al crear la cuenta. |

En México, las estimaciones de comisión por cobro mensual de USD 10–15 serían USD 1.50–2.25 al 15%, o USD 3.00–4.50 al 30%. Para el anual de USD 114–171 (incluido el descuento del 5% de GRT-002), serían USD 17.10–25.65 al 15%, o USD 34.20–51.30 al 30%. Son cálculos antes de impuestos y sujetos al importe base que determine la tienda.

## Flujo de suscripción

1. La app presenta Free y Pro, con precio mensual y anual, descuento anual, periodo de renovación, condiciones y enlace a cancelación.
2. La compra y el cobro ocurren en la hoja nativa de Apple o Google Play.
3. El cliente envía identificadores/transacción al backend; el backend valida con el proveedor y persiste el entitlement.
4. Las notificaciones de servidor de la tienda actualizan renovaciones, cancelaciones, reembolsos, expiraciones y periodos de gracia sin duplicar efectos.
5. La restauración consulta al proveedor y reconcilia la compra con la cuenta autenticada. Si la suscripción pertenece a otra cuenta Gym Rat, se explica el conflicto y se ofrece soporte; no se crea una compra duplicada silenciosamente.
6. La web pública no puede conceder acceso Pro por pago ni iniciar un cobro.

## Implicaciones

- El acceso a Pro depende de un estado verificado en backend; nunca de un callback del cliente por sí solo.
- Los registros de Apple y Google son fuentes de eventos de compra; Gym Rat conserva el entitlement unificado por cuenta y un historial auditable.
- Las tarifas son configurables por tienda/territorio y deben revisarse antes de lanzamiento.
- GRT-024 queda fuera del MVP. Solo podrá reabrirse con una decisión posterior y una nueva revisión de políticas.
- n8n no participa en confirmación de compras ni concesión de permisos.

## Pendientes para cerrar GRT-003

- Confirmar países y storefronts de lanzamiento.
- Verificar elegibilidad real de Apple Small Business y alta de ambas cuentas de tienda.
- Revalidar tarifas y reglas antes de enviar las versiones a revisión.
- Definir los estados de entitlement, eventos de notificación, reconciliación y conflictos entre cuenta de Gym Rat y cuenta de tienda.
- Definir SKUs y composición de paquetes de compra única; excluir contenido ya incluido en el nivel actual para evitar doble cobro.
- Fijar precios de suscripción y paquetes en MXN por tienda, registrando conversión, fecha/fuente, impuestos y redondeo.
- Documentar licencias que permiten el acceso permanente a GIF e imagen tras la compra única.
- Crear diagrama de secuencia de compra, renovación, cancelación, reembolso, expiración y restauración.
