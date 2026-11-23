# GRT-047 — Presupuesto de catálogo y MuscleWiki API

**Estado:** presupuesto y ruta escalonada aprobados por Producto; plan Testing para etapa inicial/beta cerrada. No se ha contratado ni pagado el servicio. Growth requiere una nueva reserva antes de contratar.  
**Fecha:** 13 de noviembre de 2026.  
**Bucket inicial:** MXN $3,687 (techo informado por Producto; no equivale a compra ni a efectivo disponible).

## Decisión y separación de costos

MuscleWiki API será el proveedor primario previsto para datos y video de ejercicios. El catálogo no se comprará por imagen/GIF individual como en la cotización GymVisual anterior; el acceso opera por cuota API recurrente. Los USD 14.85 de la cotización GymVisual con selección PPL (16 activos) quedan como antecedente sustituido, sin orden de compra ni uso en Gym Rat bajo esta decisión.

Mantener el bucket MXN $3,687 como reserva inicial de catálogo/API y separar en el ledger:

- **Cuota recurrente API:** plan, mes de servicio, llamadas incluidas, consumo, FX, impuestos y evidencia de pago.
- **Costos de activos/licencia:** solo si se aprueba una fuente adicional de medios en el futuro.
- **Presupuesto, compromiso y caja:** son estados distintos. La cifra del bucket no demuestra fondos depositados ni autoriza contratar por sí sola.

## Escalamiento de plan

| Etapa | Plan | Cuota mensual | Precio mensual USD | Pago anual estimado USD (−25%) | Gate |
|---|---|---:|---:|---:|---|
| Integración y beta cerrada | Testing | 1,000 | $10.00 | $90.00 | API inglés, sin rutinas/bodymaps; no presentar como servicio público en español. |
| Piloto público | Growth | 30,000 | $39.99 | $359.91 | API producción, 14 idiomas; informar dependencia de catálogo/API. |
| Expansión funcional | Professional | 100,000 | $99.99 | $899.91 | Rutinas/workouts/bodymaps; o >70% del límite Growth dos meses seguidos. |
| Escala futura | Enterprise | 300,000 | $199.99 | $1,799.91 | Opción futura; necesidad de capacidad/soporte o >70% de Professional dos meses seguidos. |

Precios consultados en [MuscleWiki Pricing](https://api.musclewiki.com/pricing), 13/11/2026; USD antes de impuestos y FX, sujetos a cambio. El plan anual se cobra por adelantado por doce meses con 25% de descuento; la cuota de llamadas no cambia y se reinicia mensualmente. El checkout es la fuente del monto final. Las recargas de 5,000 llamadas por USD $10 caducan al siguiente reset mensual y no son el plan de crecimiento preferido.

## Cobertura del bucket de MXN $3,687

Escenario al tipo Banxico para pagos de MXN $18.6192/USD (13/11/2026), sin impuestos ni variación futura:

| Plan | Mensual USD | Anual USD (−25%) | MXN/anual estimado +20%* | Bucket $3,687 |
|---|---:|---:|---:|---|
| Testing | $10.00 | $90.00 | $2,010.87 | Cubre el escenario anual +20% antes de impuestos/cambios FX. |
| Growth | $39.99 | $359.91 | $8,041.48 | No cubre el compromiso anual; brecha aproximada MXN $4,354.48 antes de impuestos/FX. |
| Professional | $99.99 | $899.91 | $20,106.73 | No cubre el escenario anual +20%. |
| Enterprise | $199.99 | $1,799.91 | $40,215.46 | Opción futura; no cubre el escenario anual +20%. |

\* Cálculo: precio anual USD × tipo de cambio × 1.20. Los planes anuales se cobran por adelantado; la reserva anual debe estar disponible al contratar. Fuente: [Banxico — Mercado cambiario](https://www.banxico.org.mx/tipcamb/tipCamMIAction.do?idioma=sp).

El bucket inicial permite el plan anual Testing por escenario, no el compromiso anual Growth. Antes de pasar a Growth, Finanzas debe aprobar recursos para el pago anual anticipado más contingencia/impuestos/FX, o elegir plan mensual con runway documentado mediante GRT-034. Recalcular FX al contratar.

## Licencia y operación

Los [términos API](https://api.musclewiki.com/api-terms) autorizan uso comercial en planes pagados, mostrar metadatos en la app propia y reproducir video dentro de ella. No exponer el catálogo como API/dataset ni permitir exportar, descargar o reusar medios. La licencia es revocable y pueden retirar contenido: el pago único de Gym Rat garantiza funciones propias, pero no disponibilidad perpetua de medios de terceros. Informar esto claramente en producto/tienda. No guardar snapshots de texto MuscleWiki por más de 30 días hasta confirmar permiso específico.

Videos: streaming en la app, sin descarga ni almacenamiento persistente; video nunca se escribe en disco, object storage ni caché compartida/CDN. Metadatos de texto hasta 30 días en cache privado del backend; miniaturas solo caché privada en el dispositivo ≤24 h; bodymaps sin caché conforme a `no-store` de la documentación; video solo buffer transitorio. Cada request `/stream/` y cada Range cuenta para la cuota; una reproducción usa varias llamadas. Clave API solo en backend. Añadir aviso legal literal y preservar branding del video. Preparar handling de 429, caída del API, cambio de cuota/precio y retiro de contenido.

## Criterios de aprobación y bloqueos

1. Bucket de MXN $3,687 registrado como presupuesto/reserva inicial aprobado; verificar impuestos y caja real antes de contratar.
2. Divulgación al comprador que distingue el unlock permanente de funciones Gym Rat de la disponibilidad temporal/condicionada de contenido MuscleWiki; diseño histórico guarda etiquetas propias/métricas y no copia metadata ni medios externos.
3. Plan Testing solo para etapa 0; Growth para piloto público en español; Professional si rutinas/workouts/bodymaps son necesarias o el uso rebasa el gate; Enterprise queda opcional para escala futura.
4. Mantener un año de costo objetivo +20% en caja antes de subir de nivel, salvo decisión documentada GRT-034 con un runway distinto.
5. Medir consumo/Range y enlazar evidencia de factura, FX y caja cuando se active la suscripción; no comprar desde este presupuesto.

**GRT-047 queda LISTO** como entregable de cotización y aprobación de presupuesto escalonado. No adquirir ni integrar activos de la cotización GymVisual sustituida. No contratar Growth desde la reserva actual: requiere nueva asignación presupuestaria documentada.
