# ADR-004 — MuscleWiki API y crecimiento por etapas

- **Estado:** proveedor y tarifas públicas verificadas; catálogo bajo demanda decidido, sin IDs precargados ni bucket; alcance permitido es licencia limitada/revocable de uso comercial/display/streaming bajo términos del plan pagado; faltan API key e integración en vivo, publicación de disclosure y precio Gym Rat validado.
- **Fecha:** 13 de noviembre de 2026.
- **Decide:** MuscleWiki API como fuente primaria prevista de metadatos y video de ejercicios. Se mantiene la compra única de cliente definida por ADR-003; las suscripciones MuscleWiki son un costo de proveedor, no un producto Gym Rat.
- **Mercado:** México; cliente paga en MXN.

## Decisión

Adoptar MuscleWiki API para resolver búsquedas, detalles y demostraciones de video bajo demanda dentro de Gym Rat. No hacer precarga, mapeo previo de IDs ni bucket de ejercicios. Empezar con plan Testing únicamente en integración y beta cerrada; Growth es el mínimo para beta abierta en español; escalar a Professional y Enterprise por uso, capacidad funcional y caja conforme a `docs/product/business-plan.md`.

Los términos de la API conceden uso comercial en aplicaciones de fitness, display de metadatos en la app propia y streaming de video dentro de esa app con plan pagado. Gym Rat mostrará contenido integrado en sus pantallas y no ofrecerá endpoints, exportación, descarga, copia, reventa o acceso al dataset como servicio a terceros. La licencia es revocable; el proveedor puede retirar recursos y cambiar precios; la compra única garantiza el entitlement a funciones propias, no disponibilidad perpetua de medios de terceros. Informar esa dependencia antes de la compra. Para preservar historial, guardar métricas propias, clave interna, versión de etiqueta Gym Rat y referencia externa; no copiar snapshots de metadata/media MuscleWiki a sesiones. Respuestas textuales externas solo en caché hasta 30 días.

## Escalamiento y costos de referencia

| Etapa | Plan | Precio público mensual USD | Cuota mensual | Función |
|---|---|---:|---:|---|
| Integración/beta cerrada | Testing | $10 | 1,000 | API key, inglés; no rutinas ni bodymaps. |
| Piloto público | Growth | $39.99 | 30,000 | Producción y 14 idiomas; sin rutinas/bodymaps. |
| Expansión funcional | Professional | $99.99 | 100,000 | Rutinas/workouts, bodymaps y músculos secundarios/terciarios. |
| Escala | Enterprise | $199.99 | 300,000 | Funciones Professional y soporte prioritario. |

Testing no es plan de producción en español. Growth es el primer plan de producción previsto. Subir de nivel si se requiere una función exclusiva del nivel siguiente o si el consumo/proyección supera 70% de la cuota actual por dos meses consecutivos; actualizar solo cuando caja asignada cubra 12 meses del plan objetivo +20%, impuestos y FX. Alertas operativas al 70/85/95%; 429 debe producir una experiencia degradada explicable sin bloquear el registro de sesiones. Top-ups de 5,000 por USD $10 se reservan para desbordes temporales y no sustituyen un upgrade; caducan al siguiente reset mensual.

El orden comprometido es Testing (integración/beta cerrada en inglés) → Growth (beta abierta/producción en español) → Professional (capacidades API de rutinas, bodymaps y músculos secundarios/terciarios si se justifican). Enterprise se conserva como extensión de escala cuando cuota/soporte Professional no alcance; no se presupuesta ni activa ahora.

El precio comercial de pago único Gym Rat fue aprobado por Producto el 13/11/2026: MXN $399 durante las primeras 100 compras o 60 días de beta abierta, lo que ocurra primero, y MXN $699 estándar después. GRT-048 documenta la decisión; la configuración por storefront y proceeds observados se gestionan en GRT-003/GRT-034.

Precios consultados en la página pública el 13/11/2026; están en USD y pueden cambiar con 7 días de aviso. Confirmar impuestos, modalidades de facturación, vigencia y FX al contratar. Página de [precios](https://api.musclewiki.com/pricing); [términos](https://api.musclewiki.com/api-terms).

La página ofrece pago mensual o anual con 25% de descuento por doce meses pagados por adelantado; la cuota sigue siendo mensual y en plan anual se reinicia el día 1. Con precios mensuales actuales, el pago anual estimado es Testing $90.00, Growth $359.91, Professional $899.91 y Enterprise $1,799.91 USD antes de impuestos. Verificar el importe del checkout antes de contratar; la facturación anual es un compromiso de 12 meses.

## Reglas de integración

- API key del lado servidor en Laravel; nunca incluirla en binario móvil.
- Video reproducido solo dentro de la app desde URL/API. Proxy de backend transparente permitido solo sin escribir video a disco, object storage o caché compartida/CDN y pasando headers sin cambios; usar tokens de medios en clientes nativos según la documentación.
- Caché de metadata texto hasta 30 días en store privado y con TTL duro; puede ahorrar llamadas repetidas. No hacer bulk export. Para imágenes, la documentación de API limita thumbnails a caché `private` del dispositivo ≤24 horas y bodymaps indica `no-store` (más restrictivo que el máximo general de Términos de 24 h); respetar headers sin modificar. Video `no-store`, solo buffers transitorios.
- Cada request `/stream/` y cada byte-range request cuenta contra la cuota; una reproducción puede consumir varias llamadas. No cachear video para bajar costos. Medir llamadas/playback del reproductor antes de fijar Growth/Professional. No asumir ETag/304 ni atribuir ahorro de cuota a respuestas condicionales sin confirmación/documentación.
- No quitar/ocultar marca embebida. Incluir textualmente “Exercise data and videos provided by MuscleWiki.com” en Términos, Privacidad o equivalente y un link de crédito visible recomendado.
- No construir datasets/API competidores ni usar contenido para entrenar sistemas de IA.
- Telemetría de consumo agregada y sin datos de entrenamiento identificables. Aplicar cuota, límites, backoff y manejo de 429.

## Implicaciones para pago único

La compra Gym Rat otorga entitlement no consumible y no se renueva. La licencia de MuscleWiki y sus costos API sí pueden expirar o cambiar. Por ello el catálogo API no se considera activo de propiedad permanente de Gym Rat. El precio de compra y el disclosure al comprador deben cubrir esta dependencia. GRT-034 debe verificar que las ventas netas nuevas y la reserva cubran la vida operativa esperada del servicio; no proyectar MRR ni renovaciones de usuario.

El presupuesto inicial MXN $3,687 queda como tope de reserva de lanzamiento; separar ese fondo de los costos API. Con el precio anual publicado (−25%) y tipo Banxico para pagos MXN $18.6192/USD de 13/11/2026, Testing +20% requiere cerca de MXN $2,010.87 y Growth cerca de MXN $8,041.48, antes de impuestos/FX; el bucket no cubre el pago anual Growth +20%. Actualizar la reserva con la tasa/checkout vigente antes de contratar o subir de plan.

## Criterios de aceptación antes de producción

1. Configurar términos/paywall para explicar que el unlock propio es permanente pero el contenido de tercero está sujeto al plan API activo, a disponibilidad/revocación/retiro según términos; no exponer el dataset como API/exportación.
2. Revisión de catálogo/español y rutina PPL; validar que el modo API elegido no depende de endpoints fuera de la cuota/plan.
3. Diagrama y manejo probado de quota/429, caída del proveedor, token expirado, video inaccesible, actualización/eliminación de contenido y renovación fallida de plan API.
4. API key guardada en servidor; evidencia de que videos no se persisten ni pasan por caché no autorizada.
5. Reserva aprobada de 12 meses +20% para etapa objetivo y precio de desbloqueo validado en MXN.
6. Términos/Privacidad incluyen el texto de atribución legal requerido.
7. Diseño de caché/streaming documentado y revisado contra documentación/headers: TTL≤30d metadata; miniatura solo en dispositivo ≤24h; bodymap/video no-store; tokens/URLs excluidos de logs; historial conserva etiqueta Gym Rat propia y métricas, sin metadata/media externa.
