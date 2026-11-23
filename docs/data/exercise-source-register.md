# Registro de fuente y licenciamiento del catálogo de ejercicios

**Tarjeta:** GRT-004 — Resolver derechos y procedencia del catálogo de ejercicios.  
**Estado:** MuscleWiki API es el proveedor primario seleccionado por Producto para datos y videos. Según los términos del 8 oct 2026, los planes pagados incluyen uso comercial y se concede licencia limitada, no exclusiva, no transferible, no sublicenciable y revocable para consultar el API desde la app propia y mostrar metadata; el video puede reproducirse dentro de ella sujeto a reglas de streaming. No permite distribuir el dataset, exportar/revender datos ni descargar/rehostear medios. El acceso puede suspenderse o revocarse y el contenido puede retirarse. Los términos incluyen ley de Islas Caimán, indemnización e importante límite de responsabilidad; requieren revisión legal antes del lanzamiento.  
**Actualizado:** 13 de noviembre de 2026.

El resumen operativo publicado de licencia, restricciones y atribución está en [docs/legal/musclewiki-api-license.md](../legal/musclewiki-api-license.md).

> **Decisión vigente / reemplazo:** las evaluaciones de fuentes alternas y las tarifas por GIF/imagen de GymVisual que siguen son antecedentes de investigación; no representan una compra, proveedor seleccionado ni presupuesto vigente. MuscleWiki API y las reglas actuales de caché constan en [ADR-004](../decisions/ADR-004-musclewiki-api-growth.md) y el [plan técnico](../architecture/musclewiki-api-integration.md). No ordenar los activos de GymVisual bajo la cotización histórica de USD 14.85.

La lectura de los términos vigentes es acotada: un plan pagado concede licencia limitada y revocable, uso comercial, llamadas desde la propia app y display de metadata allí; el streaming debe ocurrir dentro de la app mediante URL del API o proxy transparente que no persiste bytes y pasa headers sin cambios. No se puede ofrecer descarga/reutilización de URLs ni exponer dataset/API Gym Rat. Attribution literal “Exercise data and videos provided by MuscleWiki.com” debe aparecer en Términos/Privacidad/equivalente y la marca del video permanece visible. Metadata textual puede cachearse máximo 30 días; miniaturas/bodymaps hasta 24h por términos, pero respetar reglas/headers más estrictas de API (miniatura privada en dispositivo; bodymap y video no-store). Para usuarios del desbloqueo único, Gym Rat vende entitlement a sus propias funciones; no garantiza disponibilidad perpetua de medios de terceros. Las sesiones conservan series/cargas/repeticiones/RPE, clave interna estable y etiqueta propia Gym Rat; no guardan snapshots de metadata/video MuscleWiki.

## Fuente propuesta

- Repositorio: [hasaneyldrm/exercises-dataset](https://github.com/hasaneyldrm/exercises-dataset).
- El README reporta 1,324 ejercicios, cada uno con un GIF animado, miniatura 180×180, metadatos e instrucciones en 10 idiomas.
- Campos textuales/estructurales e instrucciones están separados de los medios. El archivo LICENSE declara MIT para código, herramientas, estructura y texto; excluye expresamente los directorios de imágenes y videos.
- El NOTICE dice que GIFs y miniaturas son propiedad de Gym visual, que la copia del repositorio se hizo bajo permiso separado de 180×180 con atribución, y que clonar el repositorio no otorga esos derechos a quien lo clona.

## Uso previsto de Gym Rat

App híbrida comercial con desbloqueo de pago único que mostrará ejercicios del catálogo a usuarios finales. No se producirán imágenes ni GIFs propios durante esta etapa. Todo proveedor debe autorizar por escrito el uso dentro de la app comercial, acceso por usuarios y modelo de pago adoptado.

## Lectura de términos y bloqueo

Los términos actuales de Gym visual permiten bajo su licencia comercial N-CRFL usar medios no marcados para ilustrar apps iOS/Android, pero la misma licencia prohíbe usos en servicios bajo demanda, productos digitales destinados a reventa, redistribución o asignación de derechos a terceros. También indica que la licencia se otorga al titular de la cuenta/organización, no es transferible, y que una miniatura pública no sustituye la compra/licencia.

Esto deja un conflicto material para los medios mostrados dentro de Gym Rat. El pago único en Gym Rat no elimina la restricción *on-demand*. Comprar los productos listados no prueba por sí solo que el uso de Gym Rat esté autorizado. **No integrar ni distribuir medios** hasta recibir confirmación escrita de Gym visual que cubra explícitamente una app comercial, desbloqueo de pago único, entrega/visualización a usuarios, streaming o caché, CDN, plataformas iOS/Android, territorios, atribución y persistencia del acceso.

## Alternativa revisada: ExRx Trainer ClipArt

La página del producto compartida por Producto indica un paquete con más de 1,500 ilustraciones JPG por USD 119 y advierte expresamente que la licencia no permite republicar imágenes dentro de software, incluidas apps web o móviles. La FAQ compartida confirma que ninguna de sus colecciones ClipArt se puede mostrar dentro de una app y propone Exercise API para esa necesidad. Por tanto, **Trainer ClipArt se descarta como medio del catálogo de Gym Rat** aunque la descarga se entregue después del pago.

La FAQ de Exercise API compartida por Producto confirma dos modalidades licenciadas para integrar datos en apps: Economy permite mostrar nombre, músculo objetivo, aparato y miniatura; exige un botón junto a cada ejercicio que abra la página correspondiente de ExRx en el navegador. ExRx describe la compensación de Economy como tráfico referido al sitio, sin cuotas mensuales Premium. Premium permite todos los campos, videos, miniaturas, instrucciones y metadatos mediante cuotas mensuales; ofrece cuenta de desarrollador gratuita con límite de llamadas, pero puede responder con errores al excederlo. Ninguna modalidad está aún aprobada para Gym Rat: faltan precios/cuotas, contrato y límites del plan gratuito, y confirmar uso con desbloqueo de pago único, idiomas, caché/offline, CDN, atribución, territorios, disponibilidad y consecuencias de terminación. No reutilizar archivos de Trainer ClipArt ni extraerlos del sitio para sustituir la API.

Los términos de uso de ExRx compartidos por Producto (última actualización indicada: 10 ago 2026) prohíben scraping automatizado y copia manual del contenido, incluso personal, así como reconstruir su biblioteca o taxonomía juntando nombres/URLs. Permiten guardar nombres y enlaces individuales solo dentro de entrenamientos/programas originales propios; prohíben modificar, vender o republicar contenido sin permiso escrito. Los enlaces deben abrir páginas (no archivos directos de imágenes), no mostrarse enmarcados y conservar URL/navegación visibles. Para Economy, la integración debe abrir la página de detalle en navegador externo tal como requiere la FAQ/API y no incrustarla en un frame; confirmar en el contrato/API términos que el flujo externo y su display de miniatura están permitidos para el app. Premium solo puede servir contenido dentro de Gym Rat bajo el contrato específico que otorgue esos derechos; la página general de términos no es ese contrato.

Evaluación inicial: Economy es la opción más compatible con un ingreso único por no imponer la cuota mensual Premium descrita, pero su experiencia saca al usuario de Gym Rat para ver el detalle y el contenido completo en ExRx. Premium ofrece la experiencia más completa dentro de Gym Rat, pero introduce un costo recurrente al proveedor que debe cubrirse con el precio único y la reserva operativa. El plan gratuito de desarrollo no es una base de producción sin conocer el cupo y la transición a un plan pagado.

La página de ExRx Premium compartida por Producto describe una suscripción recurrente ($7 cada 4 semanas o $49 anual) para acceder al sitio de ExRx y a su propio web app con contenido premium. No especifica una licencia para integrar/republicar el contenido en Gym Rat, ni un derecho API, exportación, caché o acceso para usuarios finales de Gym Rat. Por ello **ExRx Premium no es una licencia alternativa ni una fuente integrable** bajo la evidencia disponible; el costo recurrente tampoco corresponde a la decisión comercial de pago único. No compartir credenciales para automatizar o redistribuir el contenido.

## Alternativa revisada: AthleteKit API

La documentación pública presenta una API de solo lectura para un catálogo de más de 1,177 movimientos y programas estructurados en TRKR DSL, con filtros por patrón, equipo, levantamiento principal y búsqueda. Los objetos documentados incluyen nombre, categoría, equipo, patrones, indicador de levantamiento principal y descripción opcional. En lo revisado no aparecen campos ni endpoints públicos de imágenes o videos; por tanto, AthleteKit podría evaluarse como fuente de metadatos/programas, pero **no sustituye el bucket multimedia**.

El acceso de prueba ofrece 26 solicitudes mensuales; los límites superiores se cotizan manualmente mientras el producto está en etapa pre-lanzamiento. Las respuestas 304 mediante ETag también consumen una solicitud mensual. La documentación identifica `/sync/*` como uso interno/de sincronización de primera parte, por lo que no se debe basar la integración en esos endpoints. No encontré términos de licencia comercial, permiso para redistribuir datos a usuarios de una app de pago único, tarifas, límites de producción ni garantías de continuidad en la documentación pública.

**Estado: candidato no aprobado.** Antes de integrar, solicitar por escrito autorización para la app comercial iOS/Android con desbloqueo único, derechos de almacenamiento y presentación de datos, atribución, idiomas, caché/offline, cuotas y sobreconsumo, precio y continuidad/terminación; confirmar además cobertura y calidad de descripciones para la rutina PPL/MVP. Los secretos de API, si se aprueba, deben permanecer del lado servidor. Cualquier cuota recurrente debe caber en el ingreso único más la reserva operativa.

## Proveedor seleccionado: MuscleWiki API

Los términos API actualizados el 8 de octubre de 2026 conceden una licencia limitada, revocable, no transferible y no sublicenciable para llamar a la API y mostrar metadatos de ejercicios dentro de una app propia relacionada con fitness. También prohíben revender, redistribuir, sublicenciar o exponer los datos de API a terceros. Aunque autorizan display dentro de la app, no aclaran expresamente si el acceso de clientes que pagan un único desbloqueo constituye redistribución; obtener confirmación escrita antes de lanzar.

Los términos abarcan videos, miniaturas e imágenes bodymap con restricciones estrictas: no descargar ni almacenar de forma permanente; miniaturas/bodymaps pueden cachearse hasta 24 horas, metadatos de texto hasta 30 días y video solo en caché transitoria. El video debe reproducirse dentro de la app desde las URLs de la API; se permite un proxy transparente del backend si no guarda el video en disco ni caché compartida/CDN y pasa los encabezados sin cambios. Para móviles nativos se documentan tokens de medios. La marca en video no puede ocultarse. Se exige incluir literalmente “Exercise data and videos provided by MuscleWiki.com” en Términos, Política de Privacidad o documento equivalente.

La página de precios anuncia Basic gratuito solo para Playground (sin clave API directa), Testing por USD 10/mes y 1,000 llamadas (inglés, sin rutinas/bodymaps), Growth por USD 39.99/mes y 30,000 llamadas (multilingüe, sin rutinas/bodymaps), Professional por USD 99.99/mes y 100,000 llamadas (incluye rutinas/bodymaps), y Enterprise por USD 199.99/mes y 300,000 llamadas. La página indica uso comercial en los planes de pago, top-ups de 5,000 llamadas por USD 10 para planes elegibles y cambios de precio con 7 días de aviso. Los excesos de cuota responden con 429; MuscleWiki se reserva el derecho de retirar contenido/endpoints y suspender o terminar acceso. Los precios son USD y deben confirmarse antes de presupuestar.

**Decisión de Producto: seleccionado con condición de producción.** Usar Testing únicamente para integración y beta cerrada; Growth es el primer plan candidato a producción en español. Escalar a Professional/Enterprise con umbrales de uso, necesidad funcional, reserva de caja y aprobación GRT-034. La licencia no permite acceso offline ni propiedad permanente de los medios; el servicio debe seguir activo para mostrar contenido. Antes de acceso público, obtener confirmación escrita de que el modelo de pago único y los usuarios finales cumplen la prohibición de redistribución, y confirmar display/streaming, revocación, continuidad, retirada de contenido, precios/renovaciones, cuota, sobreconsumo, SLA, territorios y atribución legal. Incluir el costo recurrente en el plan de negocio; no presentar el API como licencia perpetua. Mantener clave y llamadas en backend, aplicar límites y manejar 429/baja del proveedor. Ver [ADR-004](../decisions/ADR-004-musclewiki-api-growth.md).

## Tarifas públicas consultadas

La página de reglas de precio presenta tarifas por pedido para artículos distintos en el carrito:

| Tipo de medio | 1–9 artículos | 10+ artículos |
|---|---:|---:|
| GIF animado | USD 3.60 cada uno | USD 0.90 cada uno |
| Ilustración/imagen fija | USD 3.00 cada una | USD 0.75 cada una |

La ficha enlazada de ejemplo muestra USD 3.60 por GIF y ofrece cuatro tamaños; no presupongo que esa compra incluya una licencia independiente de la miniatura JPG del dataset. Gym visual indica que los paquetes se cotizan contactándoles, pero su categoría pública Pack actualmente no lista productos. Por eso, estos son cálculos de lista, no cotización ni confirmación de derechos.

## Presupuesto preliminar por alcance

Supuesto: un ID único de ejercicio requiere un GIF. El escenario “GIF + imagen” cobra también una ilustración por ID, solo si Gym visual confirma que esa miniatura se licencia por separado. Se aplica tarifa de 10+ unidades en un pedido; no se cuentan dos veces variaciones que reutilicen el mismo archivo, tras verificar `media_id`.

| Catálogo provisional | GIFs | Solo GIFs | GIF + ilustración por ejercicio |
|---|---:|---:|---:|
| Garage Rat inicial | 100 | USD 90.00 | USD 165.00 |
| Expansión Iron Rat | 150 adicionales | USD 135.00 | USD 247.50 |
| MVP ampliado (total) | 250 | USD 225.00 | USD 412.50 |
| Expansión | 500 | USD 450.00 | USD 825.00 |
| Catálogo completo publicado | 1,324 | USD 1,191.60 | USD 2,178.60 |

Los costos no incluyen impuestos, conversión de moneda, tarifa de paquete personalizada, derechos de miniatura no confirmados, operación ni cambios de precio. A 1–9 elementos, usar USD 3.60/GIF y USD 3/imagen según las reglas públicas.

### Selección y bucket de inversión inicial

Crear y aprobar un bucket de inversión para seleccionar **100 ejercicios únicos**, cada uno completo con **GIF e imagen**, cubriendo movimientos principales de pecho, espalda, hombros, brazos, piernas, glúteos, pantorrilla y core; priorizar barra, mancuernas, cable, máquinas y peso corporal. Free tendrá disponibles 50 de estos ejercicios; Garage Rat incluye los 100. La referencia pública de lista para 100 pares es **USD 165** antes de impuestos. Reserva preliminar con 20% de contingencia: **USD 198 + impuestos/cargos**, convertida a MXN con tipo de cambio, fecha y fuente registrados. Este no es monto final de compra: requiere cotización, confirmación de si la imagen fija se licencia aparte y autorización escrita para uso por suscripción y compra única.

Antes de ordenar, curar IDs únicos, deduplicar `media_id`, validar que cada ejercicio tenga los dos medios completos y pedir cotización. No integrar ni distribuir hasta que permisos, compra e inventario estén documentados. Ampliar a 250 (“Iron Rat”) solo tras validar demanda y reservar la cotización incremental más 20% de contingencia.

No adquirir los 1,324 de inicio: el valor incremental de variaciones y ejercicios poco usados todavía no está validado. Se puede ampliar más allá de 250 cuando las búsquedas sin resultado y el uso real lo justifiquen.

## Atribución

El NOTICE del dataset pide preservar el aviso `© Gym visual — https://gymvisual.com/` y limita su copia distribuida a 180×180. La licencia propia de Gym Rat debe confirmar si la atribución debe aparecer en la pantalla de detalle, junto a cada medio, en los créditos legales o en varios sitios. Conservar una copia del contrato/licencia y la atribución por registro.

## Próximos pasos de GRT-004

1. Verificar con el plan paid contratado que la llave habilita el idioma/endpoint elegido y que el catálogo necesario aparece en consultas bajo demanda; no preparar manifiesto de IDs ni importar ejercicios.
2. Publicar la atribución literal en Términos/Privacidad y conservar intacta la marca en video; incorporar el disclosure de compra única en paywall/tienda.
3. Completar revisión legal de ley aplicable, indemnización, responsabilidad limitada, revocación/terminación y alcance de la licencia frente al entitlement Gym Rat permanente. Solicitar aclaración escrita al proveedor solo si la revisión detecta ambigüedad material.
4. Mantener reserva del plan API separada de cualquier bucket multimedia anterior; ver [plan de negocio](../product/business-plan.md).
5. Integrar desde backend; no descargar videos ni persistir medios; registrar procedencia y caché dentro de los máximos/headers permitidos. No exponer API/dataset.

## Fuentes consultadas

- [LICENSE del dataset](https://github.com/hasaneyldrm/exercises-dataset/blob/main/LICENSE)
- [NOTICE.md del dataset](https://github.com/hasaneyldrm/exercises-dataset/blob/main/NOTICE.md)
- [README y tamaño declarado del dataset](https://github.com/hasaneyldrm/exercises-dataset/blob/main/README.md)
- [Términos de uso de Gym visual](https://gymvisual.com/content/3-terms-and-conditions-of-use)
- [Reglas públicas de precio de Gym visual](https://gymvisual.com/content/6-price-rules)
- [Ficha del GIF de ejemplo](https://gymvisual.com/animated-gifs/12089--kneeling-back-rotation-stretch-female.html)
- [Documentación de AthleteKit API](https://docs.athletekit.dev/)
- [Términos de MuscleWiki API](https://api.musclewiki.com/api-terms)
- [Precios de MuscleWiki API](https://api.musclewiki.com/pricing)

**Limitación:** este registro es operativo, no una opinión legal. Confirmar los permisos comerciales para la app directamente con el titular antes de usar o lanzar los medios.
