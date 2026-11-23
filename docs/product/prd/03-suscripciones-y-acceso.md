# PRD-03 — Desbloqueo único y acceso a funciones

**Estado:** compra única adoptada conforme a ADR-003; precio aprobado en GRT-048; configuración de storefront en GRT-003. MuscleWiki seleccionado conforme a ADR-004, con disponibilidad de medios condicionada a licencia/plan API.  
**Objetivo:** explicar una compra única, validar el pago de tienda y asignar acceso permanente de forma segura.

## Oferta

- Free/Warm-Up: vista previa de un entrenamiento guardado por cuenta; después de guardar se muestra la oferta de compra. Sin miniaturas, imágenes, video ni historial posterior; consultas textuales de ejercicios sujetas al presupuesto compartido de llamadas.
- Desbloqueo completo: un SKU no consumible; MXN $399 de apertura (primeras 100 compras o 60 días, lo que ocurra primero), luego MXN $699 estándar. Habilita funciones Gym Rat incluidas y medios MuscleWiki solo mientras API, plan y licencia los permitan.
- No habrá suscripciones recurrentes de Gym Rat en el MVP ni checkout web.
- Las cuotas mensuales MuscleWiki son gasto de proveedor asumido por Gym Rat y no generan pagos recurrentes al usuario.

## Requisitos

| ID | Requisito |
|---|---|
| ACC-01 | Antes de comprar se muestran el precio total MXN, el contenido/funciones incluidas, el carácter de pago único y límites relevantes. |
| ACC-02 | La compra ocurre mediante Apple In-App Purchase o Google Play Billing; no usar checkout web ni almacenar tarjeta. |
| ACC-03 | El backend valida la transacción con la tienda y asigna el entitlement a la cuenta; el cliente nunca puede auto otorgarse acceso. |
| ACC-04 | Un producto no consumible, reembolso, contracargo, restauración, compra pendiente, fallo de verificación y conflicto de cuenta tienen estados claros y auditables. |
| ACC-05 | Las notificaciones de tienda se procesan idempotentemente; la restauración y sincronización recuperan el entitlement entre dispositivos de la misma cuenta. |
| ACC-06 | Reembolso/contracargo confirmado revoca el entitlement afectado; compras fallidas o canceladas no conceden acceso. |
| ACC-07 | El paywall ofrece una única ruta pagada activa y el acceso gratuito; no muestra precios mensuales/anuales ni renovaciones de Garage Rat/Iron Rat. |
| ACC-08 | La web pública informa/captura leads, sin compra ni transferencia de entitlement. |
| ACC-09 | Si contenido MuscleWiki no está autorizado/disponible por cuota, 429, retiro o terminación, explicar la indisponibilidad sin perder sesiones o historial propios del usuario. |
| ACC-10 | Mantener separados el estado no consumible de cliente y el estado de la suscripción API de MuscleWiki. Nunca mostrar la suscripción API como cargo del cliente. |

## Ciclo de vida

Compra iniciada → pendiente/validada/rechazada → entitlement activo → restauración/sincronización; la devolución o contracargo confirmado revoca acceso pagado. No existen renovación, cancelación ni expiración del producto Gym Rat. El servicio MuscleWiki tiene ciclo separado: plan API activo → consumo/alerta → renovación mensual/cambio de plan → pago fallido, cuota agotada, cambio de precio o terminación. API down no invalida por sí mismo la compra de tienda, pero puede limitar acceso a contenido de tercero según términos y comunicación contractual.

## Condiciones de salida

1. GRT-002/048 definen el paquete y precio MXN aprobados; conversión real se mide en GRT-034.
2. GRT-003 configura producto no consumible en ambas tiendas y documenta políticas/estados.
3. GRT-023 modela entitlement permanente independiente de API y proveedor de pagos.
4. GRT-025 muestra paywall de compra única, estado/gestión/restauración y fallos.
5. GRT-026 valida compra, reembolso, restauración, notificaciones duplicadas y acceso entre dispositivos en sandbox.
6. GRT-004/047/ADR-004 registran los términos públicos pagados, el disclosure de disponibilidad condicional, atribución, límites de caché y regla de historial antes de activar medios en producción.
7. GRT-034 acredita precio y caja para costos API recurrentes aunque el cliente pague una sola vez.

## Dependencias y fuentes

GRT-002, GRT-003, GRT-023, GRT-025, GRT-026, GRT-034, GRT-047 y GRT-048. Ver [ADR-003](../../decisions/ADR-003-one-time-full-unlock.md), [ADR-004](../../decisions/ADR-004-musclewiki-api-growth.md), [pricing-hypotheses](../pricing-hypotheses.md) y [business-plan](../business-plan.md).
