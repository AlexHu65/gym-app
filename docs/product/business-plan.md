# Gym Rat — plan de negocio y crecimiento sostenido

**Fecha:** 13 de noviembre de 2026.  
**Modelo de cliente:** desbloqueo digital de pago único; sin suscripciones Gym Rat en el MVP.  
**Proveedor de ejercicios/video:** MuscleWiki API, planes pagados cubren uso comercial, display dentro de la app y streaming; no se ofrece exportación/redistribución del dataset. La disponibilidad de medios de terceros depende de API/plan y puede cambiar o revocarse.
**Mercado inicial:** México; precio al cliente en MXN; cobro dentro de las tiendas móviles.

## Tesis de negocio

Gym Rat ayuda a levantadores amateur intermedios a registrar entrenamientos y seguir su progreso con una experiencia sencilla. La adquisición inicial se concentra en gimnasios independientes, comunidades locales y contenido educativo de bajo costo. La conversión ocurre con un pago único que desbloquea las funciones y catálogo descritos en el SKU. La tienda móvil procesa y restaura la compra; el backend mantiene un entitlement permanente ligado a la cuenta.

El pago único evita renovaciones al cliente. A la vez, MuscleWiki cobra una tarifa mensual o anual anticipada por el API. El entitlement permanente cubre las funciones propias de Gym Rat; los medios de terceros se muestran dentro de la app mientras el servicio esté disponible según los términos. El precio y la tienda deben decirlo claramente. La sostenibilidad depende de ventas nuevas suficientes, reserva y diseño que permita seguir registrando/consultando entrenamientos aunque el API falle o retire un medio.

## Oferta y monetización

- **Free/Warm-Up:** una vista previa de entrenamiento guardado por cuenta; el paywall aparece inmediatamente después de guardar. Sin thumbnails, imágenes ni videos. Las búsquedas textuales están sujetas a la cuota compartida disponible y a controles de consumo.
- **Desbloqueo completo:** precio aprobado por Producto: pago único no consumible de MXN $399 durante las primeras 100 compras o 60 días de beta abierta (lo que ocurra primero), luego MXN $699 estándar. La persona compra el mismo SKU una sola vez; la vigencia permanente aplica a funciones Gym Rat propias. No cubre disponibilidad perpetua del contenido MuscleWiki, que se consulta/visualiza mientras lo permitan API, cuota y licencia. La configuración por tienda queda en GRT-003.
- **Expansiones futuras:** paquetes de catálogo solo después de validar demanda, licencias, política de tienda y economía; no son necesarios para la primera etapa.
- **Web pública:** información, lista de espera y captación con consentimiento; sin checkout.

No presentar los antiguos tiers Garage Rat/Iron Rat como suscripciones vigentes. Pueden servir como hitos internos para el crecimiento del catálogo, no como niveles con renovación recurrente.

## Crecimiento operativo por etapas

Los límites de avance combinan adopción, calidad y caja. El criterio de llamadas se evalúa con consumo real de API y proyección de 60 días; no cambiar de plan solo por descargas o usuarios registrados.

| Etapa | Actividad | Plan MuscleWiki | Gate de salida |
|---|---|---|---|
| Preparación — Testing | Integración técnica, prototipo y beta cerrada en inglés; hasta 1,000 llamadas/mes y sin rutinas/bodymaps de API. No es etapa de producción en español. | USD $10/mes o $90/año anticipado | Separar funciones propias de contenido MuscleWiki condicionado; trazabilidad de cuota; streaming sin descarga; atribución legal incorporada; errores 429 y contingencia verificados. |
| Piloto — Growth | Primer uso público en México y español; catálogo de movimientos y videos en línea, rutinas propias de Gym Rat. 30,000 llamadas/mes. | USD $39.99/mes o $359.91/año anticipado | Validar primera sesión, conversión, retención y soporte. Mantener el costo cubierto por caja para 12 meses + 20%; comunicar disponibilidad API. |
| Escala profesional — Professional | Añadir endpoints API de rutinas, bodymaps o músculos secundarios/terciarios; hasta 100,000 llamadas/mes. | USD $99.99/mes o $899.91/año anticipado | Necesidad funcional demostrada o >70% de cuota Growth durante dos meses; reserva de 12 meses + 20%; costo de API cubierto por margen neto acumulado. |
| Escala futura — Enterprise | Operación de mayor volumen con soporte prioritario; hasta 300,000 llamadas/mes. No está en la ruta comprometida inicial. | USD $199.99/mes | Necesidad de soporte/capacidad o >70% de cuota Professional durante dos meses; proyección validada, SLA escrito y misma reserva financiera. |

Las tarifas publicadas son USD antes de impuestos/FX; todos los planes pagados incluyen uso comercial. El plan anual se paga por adelantado por doce meses con 25% de descuento y mantiene la misma cuota mensual, que se reinicia el primer día de cada mes. El proveedor puede cambiar las tarifas con siete días de aviso. Top-ups de 5,000 llamadas por USD $10 son una medida excepcional y temporal; expiran al reset mensual y no son automáticos. Configurar alertas internas al 70/85/95% además de las notificaciones del proveedor al 80/90/100%. Testing es solo integración/beta cerrada en inglés; como el producto abrirá en español, Growth es también el mínimo para beta abierta, no una etapa opcional después de ella. El tráfico Free y pagado consume la misma cuota asignada a Gym Rat; controlar abuso y limitar/pausar búsquedas gratuitas cuando la reserva de operación lo requiera.

### Caché y costo efectivo de llamadas

El caché autorizado mejora la eficiencia de llamadas de metadatos. Texto: máximo 30 días y siempre respetando headers; Free no solicita medios, así que sus vistas previas no causan llamadas de imágenes/video. Thumbnails, si se habilitan para desbloqueados, solo caché privada de dispositivo hasta 24 horas si headers lo permiten; bodymaps `no-store`; video `no-store`, solo buffers transitorios. Cada `/stream/` y cada Range request cuenta como llamada. El backend mantendrá TTL por tipo, respetará `Cache-Control` sin alteración, y no asumirá ETag/304 como mecanismo tarifario. Medir el consumo real por usuario/sesión en beta. Ver [diseño de integración](../architecture/musclewiki-api-integration.md).

## Economía y control de caja

1. Registrar ingresos de tienda como proceeds reportados y caja efectivamente recibida; separar comisiones, impuestos, reembolsos, FX, costos fijos, soporte, adquisición y tarifas API.
2. Mantener un presupuesto de API independiente del bucket inicial de MXN $3,687 y distinguir aprobado, comprometido, facturado y pagado. No considerar presupuesto aprobado como efectivo disponible.
3. Antes de avanzar un plan, conservar 12 meses del costo total del plan objetivo + 20% + impuestos/FX en caja asignada. Referencia al tipo Banxico para pagos de 13 nov 2026: MXN $18.6192/USD; recalcular con la tasa y modalidad de cobro al contratar.
4. Calcular la cantidad de nuevas compras que hace falta para financiar el servicio continuo: `ventas nuevas necesarias = ceil((costo API anual + reserva + costos incrementales e impuestos) / proceeds netos medios por compra)`. Como referencia de planeación, Growth anual + 20% son ~MXN $8,041 antes de impuestos/FX; con un supuesto de tienda del 30%, se requieren 29 nuevas ventas a MXN $399 o 17 a MXN $699 para cubrir únicamente ese costo anual. No incluye IVA, reembolsos ni gastos operativos y no equivale a rentabilidad. Actualizar con proceeds observados por tienda. No tratar el pago único como ingreso recurrente ni usar MRR.
5. Si el flujo de nuevas compras no cubre el costo API, detener adquisición/expansión y renegociar términos, precio/alcance o proveedor antes de vender una promesa de servicio permanente que dependa de una API revocable.

## Métricas de crecimiento y decisión

- Activación: porcentaje de nuevos usuarios que registra su primera sesión; tiempo hasta primer entrenamiento guardado.
- Valor: usuarios que registran ≥3 sesiones en 14 días y vuelven a consultar su historial; mostrar numerador, denominador y cohorte.
- Conversión: compras no consumibles / usuarios elegibles que vieron el paywall; proceeds netos y reembolsos por storefront.
- Adquisición: CAC por canal cuando haya atribución suficiente; gasto combinado si la atribución no es confiable.
- Servicio: llamadas API por usuario activo y por sesión, porcentaje de cuota, 429, latencia/fallos de video, disponibilidad y costo mensual efectivo.
- Retención/caja: cohortes de 30/90 días, ventas nuevas, saldo y meses de runway de API; sin MRR ni conteo de suscriptores como KPI de negocio.

GRT-034 registra el Go/No-Go y reglas de reversión. No subir de plan hasta aprobar el gate. Si proveedor cambia precio con siete días de aviso, retira contenido o termina el acceso, notificar al equipo, bloquear nuevos claims comerciales, presentar estado de contingencia al usuario y ejecutar la estrategia contractual/operativa aprobada.

## Riesgos y decisiones abiertas

- **Licencia/continuidad:** los términos otorgan licencia limitada, no exclusiva, no transferible, no sublicenciable y revocable para llamar al API desde la app/servicio propio y mostrar metadata dentro de la app. Las reglas específicas de medios prohíben descargar/exportar/copiar/almacenar medios, rehostearlos o entregarlos fuera de la app; el video se reproduce dentro de la app desde el API bajo proxy transparente permitido. Se prohíbe reventa/redistribución/sublicencia/exposición del API, scraping y uso de contenido para entrenar IA sin permiso escrito. MuscleWiki puede retirar contenido o terminar acceso. Mostrar el texto de atribución literal y conservar marca incrustada del video. El contrato dice ley de Islas Caimán; límites de responsabilidad e indemnización hacen necesaria revisión legal antes de lanzamiento. No vender acceso perpetuo a videos de terceros; informar disponibilidad condicionada y dejar entrenamientos funcionales sin video/API.
- **Dependencia:** medios online y cuota mensual. Acordar SLA, eliminación, continuidad, transición y aviso suficiente; no prometer descarga/offline ni propiedad de medios.
- **Límite de historial/caché:** metadata textual externa tiene máximo de caché de 30 días. No persistir snapshots derivados de MuscleWiki en sesiones más allá de ese límite sin aclaración escrita; conservar datos de entrenamiento propios, ID externo y etiqueta de usuario/Gym Rat que no reproduzca metadata externa.
- **Economía:** ingreso único contra gasto mensual por comprador de larga duración. Precio MXN y reserva de operación deben considerar costo de vida útil, no solo costo de adquisición inicial.
- **Datos/cobertura:** validar catálogo, español disponible (`es-es` según docs), PPL y atribución antes de cerrar el SKU.
- **Tiendas:** confirmar configuración de producto no consumible, precios MXN y tarifas/programas vigentes antes de publicar.

## Referencias de trabajo

- [Hipótesis de precio y planes API](pricing-hypotheses.md)
- [ADR-003 — modelo de pago único](../decisions/ADR-003-one-time-full-unlock.md)
- [ADR-004 — MuscleWiki y crecimiento API](../decisions/ADR-004-musclewiki-api-growth.md)
- [GRT-047 — bucket multimedia/API](../data/GRT-047-multimedia-budget-proposal.md)
- [Registro de licencias de ejercicios](../data/exercise-source-register.md)
- [Aviso de contenido de terceros para paywall/tienda](musclewiki-user-disclosure.md)
