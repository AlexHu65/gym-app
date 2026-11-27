# Gym Rat — backlog para Trello (MVP febrero 2027)

Este documento está organizado para crear listas por sprint y tarjetas por actividad. Cada tarjeta trae descripción, puntos de inicio, entregable, aceptación y dependencias. Las rutas de código son **propuestas para el futuro**, no archivos existentes; durante Sprint 0 se confirmará la estructura del repositorio.

## Convenciones para el tablero

- **Listas:** una lista por sprint; mantener `Backlog`, `En curso`, `Revisión`, `Hecho` y `Bloqueado` como estados/columnas si prefieres Scrum/Kanban mixto.
- **Tarjeta:** `[ID] Título`.
- **Etiquetas:** una épica por tarjeta (`E01 Producto`, `E02 UX/Marca`, `E03 Plataforma`, `E04 Entrenamiento`, `E05 Progresión`, `E06 Monetización`, `E07 Operaciones`, `E08 Lanzamiento`); añadir `Bloqueante`, `Riesgo legal`, `Móvil`, `Web`, `Backend` cuando aplique.
- **Criticidad Trello:** `CRITICIDAD · P0 — Bloquea lanzamiento` para seguridad, pagos, derechos o flujo central sin alternativa aceptable; `CRITICIDAD · P1 — Requisito esencial` para requisitos necesarios pero recortables/mitigables. Sin etiqueta significa que no se calificó como crítico en esta revisión; no sustituye severidad de bugs.
- **Estimación:** puntos relativos (1, 2, 3, 5, 8). Refinar al inicio de cada sprint; no son horas.
- **Responsable sugerido:** Producto, Diseño, Backend, Frontend/Móvil, QA/Release, Growth/Operaciones. Una persona puede cubrir varios roles.
- **Definition of Done:** entregable enlazado; criterios de aceptación satisfechos; errores/estados vacíos contemplados; documentación actualizada; sin secretos ni datos personales en el repositorio.
- **Capacidad:** plan provisional para equipo pequeño. Si una persona cubre todo, proteger el alcance y cortar elementos opcionales antes de reducir QA o pagos seguros.

## PRDs de producto

**Decisiones vigentes — 13 nov 2026:** Gym Rat usa desbloqueo no consumible de pago único. Los términos MuscleWiki para planes pagados autorizan uso comercial, display de metadatos y streaming dentro de la app propia; prohíben exportar/revender datos o medios. Testing solo beta cerrada; Growth primer plan público en español; Professional por necesidad/cuota; Enterprise es escala opcional futura. La retención histórica de metadatos externos >30 días sigue sin autorización confirmada.

Se prepararon PRDs derivados: [`docs/product/prd/README.md`](product/prd/README.md). Cubren entrenamiento/progreso, catálogo, desbloqueo único/acceso, retos, sitio/leads y finanzas. El plan API/caché/streaming está en [`docs/architecture/musclewiki-api-integration.md`](architecture/musclewiki-api-integration.md).

El plan de negocio y etapas proveedor están en [`docs/product/business-plan.md`](product/business-plan.md); precios y anualidad en [`pricing-hypotheses.md`](product/pricing-hypotheses.md). México/MXN y pago único se mantienen. GRT-003 quedó en LISTO con flujo/ADR y fuentes oficiales; configuración y sandbox pertenecen a implementación/release. GRT-047 está en LISTO. GRT-046 está en LISTO: catálogo por consultas bajo demanda, sin IDs ni bucket. GRT-048 está aprobada y en LISTO: MXN $399 precio introductorio para primeras 100 compras o 60 días de beta abierta, luego MXN $699 estándar; GRT-034 concilia proceeds. GRT-004 está en LISTO con los términos y restricciones publicados en `docs/legal/musclewiki-api-license.md`; la atribución literal y el aviso de compra quedan listos para incorporar en los documentos legales/pantallas de producto antes del lanzamiento.

**Auditoría Trello — 23 oct 2026:** se inspeccionaron 45 tarjetas; GRT-001 completo, GRT-024 diferido. Estado histórico. Las decisiones posteriores sustituyen descripciones comerciales antiguas; a 13 nov GRT-046, GRT-047, GRT-048 y GRT-004 están LISTO con sus decisiones/documentación respectivas; la configuración de pagos y publicación legal en el producto se asigna a sus tareas de implementación/lanzamiento.

## Épicas

| ID | Épica | Resultado de negocio |
|---|---|---|
| E01 | Producto y validación | Definir a quién sirve el MVP y demostrar interés antes de construir. |
| E02 | Marca, UX y contenido | Crear identidad propia y recorridos que lleven al primer entrenamiento. |
| E03 | Plataforma y seguridad | Tener base técnica confiable, API, datos y administración segura. |
| E04 | Entrenamiento | Registrar, organizar y consultar entrenamientos. |
| E05 | Progresión y engagement | Reflejar constancia y progreso con retos e insignias sanos. |
| E06 | Monetización | Convertir usuarios a Pro con acceso consistente en web y móvil. |
| E07 | Operaciones y crecimiento | Captar leads y operar soporte/automatizaciones con control. |
| E08 | Beta, tiendas y lanzamiento | Validar calidad, obtener aprobación y lanzar de forma controlada. |
| E09 | Finanzas y rentabilidad | Conciliar ingresos in-app, comisiones y gastos; dar visibilidad al margen y punto de equilibrio desde el backoffice. |

## Calendario propuesto

**Reprogramación del 13 nov 2026.** Descubrimiento tiene como fecha prevista de cierre el 18 de noviembre. Fundación inicia el jueves 19 de noviembre. Los sprints S1–S8 se desplazan tres días naturales respecto al calendario anterior y conservan su duración. El lanzamiento controlado tiene como objetivo el 25 de febrero de 2027, sujeto a los criterios de salida y a la aprobación de las tiendas, con seguimiento hasta el 8 de marzo.

| Sprint | Fechas | Objetivo / salida |
|---|---|---|
| S0 — Descubrimiento | 26 oct–18 nov 2026 | Decisiones de producto, prototipo y riesgos resueltos antes del código. |
| S1 — Fundación | 19–30 nov 2026 | Repositorios, arquitectura, diseño base y entornos definidos. |
| S2 — Registro de entrenamiento | 3–14 dic 2026 | Usuario puede explorar ejercicios, crear plan y registrar sesión. |
| S3 — Progreso y backoffice | 17–28 dic 2026 | Historial, métricas esenciales y operación básica de catálogo/planes. Navidad reduce la capacidad prevista. |
| S4 — Acceso y compra única | 31 dic 2026–11 ene 2027 | Entitlement permanente y compra in-app en sandbox; Año Nuevo reduce la capacidad prevista. |
| S5 — Web y automatizaciones | 14–25 ene 2027 | Landing, captura de leads y automatizaciones controladas. |
| S6 — Beta cerrada | 28 ene–8 feb 2027 | Usuarios piloto completan el flujo principal; incidencias críticas resueltas. |
| S7 — Preparar tiendas | 11–22 feb 2027 | Candidatos de release enviados y materiales de revisión listos. |
| S8 — Lanzamiento | 25 feb–8 mar 2027 | Lanzamiento controlado, soporte y seguimiento de métricas. |

Fechas de tiendas son una meta, no garantía de aprobación. Si el alcance o el equipo no permiten cumplir, reducir extras (no seguridad, pagos ni calidad de lanzamiento).

---

## S0 — Descubrimiento (26 oct–18 nov 2026)

### [GRT-001] Definir segmento inicial, problema y propuesta de valor
**Épica:** E01 · **Puntos:** 3 · **Responsable:** Producto

**Descripción:** El segmento primario acordado son levantadores amateur intermedios que son socios de gimnasios independientes; no entrenadores ni profesionales. En 10 entrevistas, alrededor de 8 personas no registran sus cargas o no llevan un registro adecuado. Tratarlo como señal exploratoria de esa muestra, no como prevalencia del mercado. La propuesta inicial es ayudarles a registrar entrenamientos y consultar su progreso sin complicar la sesión. Documentar problema, alternativa actual, contexto, exclusiones y tres hipótesis medibles. No inventar demografía no investigada.

**Para iniciar:** entrevistar a 8–12 personas del segmento; preguntar cómo registran hoy, qué abandonan y por qué pagarían; separar observaciones de opiniones.

**Entregable:** `docs/product/brief.md` con segmento, problema, evidencia, propuesta, hipótesis de activación y recurrencia, exclusiones y lenguaje de marca. Definición inicial completada; hipótesis de activación y recurrencia siguen pendientes de validación.

**Aceptación:** el brief nombra un segmento primario, una necesidad prioritaria y criterios para validar o descartar cada hipótesis.

**Dependencias:** ninguna.

### [GRT-002] Validar disposición de pago y paquete Free/Pro
**Épica:** E01/E06 · **Puntos:** 3 · **Responsable:** Producto/Growth

**Estado:** LISTO — decisión de Producto documentada el 13 nov 2026. La decisión no constituye validación empírica de disposición a pagar; conversión y objeciones se medirán en beta mediante GRT-034.

**Descripción:** Definir acceso Free/Warm-Up y el paquete de un único desbloqueo no consumible de pago único. No ofrecer suscripciones Garage Rat/Iron Rat al cliente; esos nombres quedan como etapas de catálogo. México es mercado inicial. Precio SKU aprobado en GRT-048. La decisión del paquete está cerrada; comportamiento de compra y disposición a pagar se observarán en beta, sin presentarlos como validados antes de contar con datos.

**Para iniciar:** usar el brief GRT-001, precios GRT-048 y las reglas Free aprobadas por Producto.

**Entregable:** matriz Free/desbloqueo único, nota de precios GRT-048, economics por tienda y gates API en `docs/product/pricing-hypotheses.md`, `docs/product/business-plan.md` y `docs/architecture/musclewiki-api-integration.md`.

**Aceptación (decisión cerrada):** Free guarda solo un entrenamiento por cuenta y muestra el paywall después de guardarlo; no usa miniaturas, imágenes, bodymaps ni videos; las llamadas de metadata quedan sujetas a cuota compartida, caché autorizada y alarmas; precios GRT-048 reflejados como aprobados. No se afirma validación empírica de disposición a pagar. Sin bucket ni lista estática de IDs.

**Dependencias:** GRT-001.

### [GRT-003] Mapear políticas de pago de tiendas y flujo de compra
**Épica:** E06/E08 · **Puntos:** 5 · **Responsable:** Producto/Backend

**Estado:** LISTO — diseño y mapeo de políticas documentados en ADR-003; las políticas Apple/Google se revisaron en documentación oficial el 13 nov 2026. La oferta de 100 ventas o 60 días requiere conteo compartido y desactivación operativa; no se halló un cap global nativo entre tiendas. GRT-026 valida compra/restore en sandbox.

**Descripción:** Documentar políticas y flujo de compra única no consumible en Apple IAP y Google Play Billing; México como storefront inicial y MXN. Un entitlement permanente de funciones Gym Rat; sin suscripciones ni checkout web. GRT-048 aprobó MXN $399 introductorio (primeras 100 compras o 60 días) y $699 estándar.

**Para iniciar:** usar documentación oficial vigente; definir estados, diagrama de secuencia, producto por tienda y mecánica de oferta introductoria.

**Entregable:** diagrama de secuencia y ADR vigente `docs/decisions/ADR-003-one-time-full-unlock.md`; ADR-001 se conserva como historial supersedido. Documentar fuentes oficiales Apple/Google y supuestos de oferta.

**Aceptación (diseño):** compra, pendiente, validación servidor, reembolso/contracargo, restauración, conflicto de cuenta, eventos duplicados e idempotencia cubiertos; sin renovación/cancelación/expiración Gym Rat; backend como fuente de verdad; oferta para MX storefront documentada. Configuración real y sandbox pasan a GRT-026/release.

**Dependencias:** GRT-048 para precio/definición del SKU (resuelta). La matriz Free de GRT-002 sigue siendo necesaria para el contenido final del paywall y GRT-026 para sandbox, pero no bloquea el entregable documental/política de GRT-003.

### [GRT-004] Resolver derechos y procedencia del catálogo de ejercicios
**Épica:** E03/E04 · **Puntos:** 3 · **Responsable:** Producto/Operaciones

**Derechos vigentes (términos del 8 oct 2026):** licencia limitada, no exclusiva, no transferible, no sublicenciable y revocable para llamar al API desde la aplicación/servicio propio y mostrar metadata dentro del producto; los planes pagados incluyen uso comercial. Se permite reproducir video directamente dentro de la app usando URLs servidas por API y proxy transparente sujeto a condiciones. No equivale a propiedad ni licencia perpetua.

**Restricciones:** no descargar/exportar/copiar/almacenar medios, rehostear ni servirlos fuera de la app; no exponer API/datos a terceros, revender/redistribuir/sublicenciar, scrapear o usar contenido para entrenar IA sin permiso escrito. Video `no-store` con buffers transitorios; proxy sin disco/storage/CDN y headers sin cambios. Texto hasta 30 días; miniaturas/bodymaps máximo 24 h según términos, respetando headers y documentación más restrictivos. Incluir literalmente “Exercise data and videos provided by MuscleWiki.com” en Términos/Privacidad o equivalente y conservar marca del video. MuscleWiki puede retirar contenido/terminar acceso y cambiar tarifas con 7 días de aviso. Los términos remiten a ley de Islas Caimán, incluyen indemnización amplia y limitan responsabilidad a cuotas de los últimos 3 meses; requiere revisión legal antes de lanzamiento.

**Descripción:** documentar esos derechos y límites en el registro de procedencia, arquitectura, disclosure y fallback; no inferir derecho a acceso perpetuo u offline. GRT-046 usa búsquedas bajo demanda; no hay paquete precargado.

**Para iniciar:** publicar atribución literal y branding intacto; diseñar historial con referencias y datos Gym Rat propios, sin snapshots externos. Coordinar plan API con GRT-047.

**Entregable:** `docs/data/exercise-source-register.md` con fuente, términos, atribución, límites y procedencia; copy de usuario en `docs/product/musclewiki-user-disclosure.md`; resumen de derechos en esta tarjeta. Costos desde GRT-047 y catálogo bajo demanda según GRT-046.

**Aceptación documental (cerrada):** licencia y límites transcritos/resumidos y enlazados desde `docs/legal/musclewiki-api-license.md`, `docs/data/exercise-source-register.md`, `docs/product/musclewiki-user-disclosure.md` y la arquitectura; texto literal de atribución incluido en el aviso preparado; caché/streaming y flujo bajo demanda documentados; fallback de API especificado. Integrar el aviso legal en las superficies publicadas y completar revisión legal contractual antes del lanzamiento como gate de release, no como trabajo de esta tarjeta documental.

**Dependencias:** ninguna.

### [GRT-046] Definir catálogo MuscleWiki bajo demanda
**Épica:** E03/E04 · **Puntos:** 3 · **Responsable:** Producto/Operaciones

**Descripción:** No crear mapeo previo de IDs ni bucket/paquete de ejercicios. Al abrir el selector y según consulta/filtros de cada persona, obtener resultados paginados desde MuscleWiki; pedir detalle/video solo al seleccionar. La rutina PPL básica puede ser plantilla Gym Rat; los ejercicios se resuelven en el momento de selección. Decisión en `docs/data/GRT-046-musclewiki-ppl-manifest.md`.

**Para iniciar:** GRT-004 y plan/contrato API vigentes; integrar consulta autenticada desde Laravel, paginación, idioma español en Growth, caché/headers permitidos, telemetría y límites. La API key queda en backend. No importar ni sincronizar masivamente.

**Entregable:** flujo de búsqueda y selección bajo demanda documentado; sin inventario/IDs precargados ni medios comprados.

**Aceptación:** flujo de selección definido; cada selección conserva solo referencia necesaria y datos propios de entrenamiento; detalle/reproducción bajo demanda; cuotas/429 medidos y degradación documentada; metadatos hasta 30 días, miniaturas privadas en dispositivo ≤24 h si headers lo permiten, bodymap/video `no-store`; rutinas y sesiones funcionan sin API. La decisión de alcance está lista; la integración se valida antes de beta abierta.

**Dependencias:** ninguna; la selección de IDs puede avanzar sin copiar ni distribuir los medios.

### [GRT-047] Cotizar y aprobar bucket de inversión multimedia
**Épica:** E03/E04/E09 · **Puntos:** 3 · **Responsable:** Producto/Operaciones/Finanzas

**Estado de decisión:** LISTO (13 nov 2026). El presupuesto MXN $3,687 aprobado cubre el escenario anual Testing +20% al FIX consultado y deja identificada la brecha Growth. No implica que se haya contratado o pagado el plan.

**Descripción:** Administrar bucket inicial MXN $3,687 y cuotas MuscleWiki separadas. Testing USD $10/mes solo beta cerrada; Growth USD $39.99/mes primer nivel público español; Professional USD $99.99/mes si funciones/cuota lo requieren. Enterprise USD $199.99/mes queda para escala futura. Mantener 12 meses +20% antes de avanzar.

**Para iniciar:** aplicar términos públicos vigentes; registrar pago mensual/anual, impuestos, FX, top-ups, límites y aviso de cambios; decidir qué plan se financiará y comprobar caja disponible. No requerir contrato custom para display/streaming permitido en app propia; sí mantener sin resolver la retención histórica externa >30 días hasta permiso específico.

**Entregable:** reserva API por etapas, presupuesto MXN con mensual/anual, tipo de cambio/fecha/fuente, impuestos/cargos y contingencia; evidencia enlazada en registro de fuente y plan de negocio.

**Aceptación:** Testing queda en beta; ruta mensual/anual por etapa y puertas de upgrade definidas; términos pagados de display/streaming documentados y disclosure preparado; historial guarda datos Gym Rat propios y no snapshots externos; caché respeta términos/headers y Range está incluido en el medidor; presupuesto inicial MXN $3,687 asignado con reserva +20% para Testing. Growth requiere nueva asignación antes de contratar.

**Dependencias:** GRT-004 (términos/disclosure registrados). No depende de un mapeo GRT-046. Telemetría de consumo real y reserva suficiente sí son condiciones para el Go/No-Go de beta abierta en Growth.

**Avance (13 nov 2026):** MuscleWiki reemplaza la cotización GymVisual. Precio, descuento anual, cuotas, caché y runway documentados en [`docs/data/GRT-047-multimedia-budget-proposal.md`](data/GRT-047-multimedia-budget-proposal.md), [`ADR-004`](../decisions/ADR-004-musclewiki-api-growth.md) y el [plan de negocio](product/business-plan.md). El bucket cubre Testing anual +20%; Growth anual con +20% requiere aprox. MXN $8,041.48 antes de impuestos/FX, una brecha de $4,354.48. No se ha comprado la suscripción.

### [GRT-048] Definir SKU y precio MXN del desbloqueo único
**Épica:** E01/E06 · **Puntos:** 3 · **Responsable:** Producto/Monetización

**Decisión aprobada por Producto (13 nov 2026):** un SKU no consumible, un pago, sin suscripciones Gym Rat: MXN $399 para primeras 100 compras o primeros 60 días de beta abierta (lo que ocurra primero); después MXN $699 estándar. Entitlement permanente para funciones Gym Rat propias; contenido MuscleWiki sujeto a API/licencia y sin garantía de disponibilidad perpetua. La configuración de IDs/precio por storefront se ejecuta en GRT-003; proceeds e impuestos observados en GRT-034.

**Para iniciar:** aprobado el precio, trasladarlo a la configuración de tienda mediante GRT-003. Referencia de sostenibilidad: Growth anual +20% reserva ≈ MXN $8,041 antes de impuestos/FX; supuesto de comisión de tienda 30% implica 29 ventas a $399 o 17 a $699 para cubrir solo el API anual reservado. No incluye otros gastos y no representa margen validado; sustituir el supuesto por proceeds reales en GRT-034.

**Entregable:** decisión aprobada de contenido/precios y sensibilidad en `docs/product/pricing-hypotheses.md`, disponible para implementación por tienda.

**Aceptación (decisión cerrada):** Producto aprobó precios y límites de la oferta; el modelo muestra el runway preliminar API y sus supuestos; alcance de entitlement y disclosure de disponibilidad revocable definidos. GRT-003 configura el SKU/precio en tiendas, GRT-034 reemplaza supuestos con proceeds reales y mantiene economía de 12/24 meses. No ofrecer suscripciones Gym Rat.

**Dependencias:** GRT-002, GRT-003, GRT-047. GRT-046 ya no condiciona una lista de IDs; el catálogo se consulta bajo demanda.

### [GRT-005] Definir alcance MVP y criterios de salida
**Épica:** E01/E08 · **Puntos:** 3 · **Responsable:** Producto

**Estado:** LISTO — alcance y criterios documentados. La respuesta de conversión, configuración de tiendas, integración API y gates de lanzamiento siguen en sus tarjetas de implementación/operación.

**Descripción:** Congelar alcance y métricas del MVP: Free/Warm-Up permite un entrenamiento de vista previa por cuenta y un SKU no consumible, sin suscripciones Gym Rat ni Garage/Iron Rat al consumidor. Free no accede a imágenes ni medios; llamadas de texto están limitadas por cuota compartida y controles de consumo. México/MXN; precios aprobados GRT-048. Catálogo MuscleWiki bajo demanda, sin IDs ni bucket precargado.

**Para iniciar:** usar GRT-001 a GRT-004 y GRT-046 a GRT-048; estimar disponibilidad real del equipo y fechas de revisión de tiendas.

**Entregable:** `docs/product/mvp-scope.md`, métrica norte, métricas de guardia y criterios de beta/lanzamiento.

**Aceptación:** cada función del MVP tiene usuario, resultado y criterio; Free/desbloqueo están separados; catálogo, precio y licencias no se contradicen; métricas, gates de beta/lanzamiento, exclusiones y regla de recorte están documentados. La disposición a pagar no está probada; medirla en beta.

**Dependencias:** GRT-001, GRT-002, GRT-003, GRT-004, GRT-047, GRT-048. GRT-046 está resuelta como decisión de catálogo; la integración que habilita pertenece a GRT-013.

### [GRT-042] Definir modelo financiero, KPIs y fuentes
**Épica:** E01/E09 · **Puntos:** 3 · **Responsable:** Producto/Operaciones

**Descripción:** Definir semántica y fórmulas del backoffice para ventas únicas, impuestos/retenciones, comisiones de tienda, refunds, efectivo liquidado, costo recurrente de API MuscleWiki (Testing/Growth/Professional; Enterprise como etapa futura), adquisición, infraestructura, soporte y operación; resultado operativo, contribución por SKU/cohorte y punto de equilibrio. México opera en MXN; conservar moneda fuente y FX con fecha/fuente. Separar efectivo real, comprometido y proyecciones; no modelar MRR ni suscriptores Gym Rat.

**Para iniciar:** usar GRT-002/003 y revisar los criterios Go/No-Go de GRT-034 como consumidores del modelo; consultar reportes de App Store Connect/Play Console y acordar categorías, periodos y roles autorizados.

**Entregable:** `docs/product/financial-dashboard.md` con diccionario de KPIs, fórmulas, fuentes, supuestos, frecuencia de actualización y ejemplos reconciliables.

**Aceptación:** no se mezclan importes brutos con netos ni reales con estimados; un tercero puede reproducir el cálculo de un periodo con sus fuentes.

**Dependencias:** GRT-002, GRT-003, GRT-046.

### [GRT-006] Diseñar flujo y prototipo del primer entrenamiento
**Épica:** E02/E04 · **Puntos:** 5 · **Responsable:** Diseño/Producto

**Descripción:** Diseñar onboarding corto y camino desde abrir la app hasta completar una sesión; añadir estados sin plan, sin ejercicios y sin conexión. Usar lenguaje y dirección visual de vieja escuela con lectura clara y accesible.

**Para iniciar:** definir tareas de usuario y dispositivos objetivo; prototipar alta, inicio, selección/creación de plan, sesión y resumen.

**Entregable:** prototipo clicable y flujo `docs/design/first-workout-flow.md`.

**Aceptación:** el prototipo clicable y el flujo documentado cubren el primer entrenamiento y los estados alternos definidos. Entregables completos. Las sesiones moderadas se retiraron del alcance el 23 de noviembre de 2026.

**Dependencias:** GRT-001, GRT-005.

### [GRT-007] Definir reglas de retos, insignias y RPE
**Épica:** E05 · **Puntos:** 3 · **Responsable:** Producto/Contenido

**Descripción:** Especificar primeras insignias y un reto semanal con reglas reproducibles, periodos, zona horaria y prevención de duplicados. RPE es autopercepción; evitar que medallas presionen a entrenar con dolor o fatiga.

**Para iniciar:** seleccionar 5–8 insignias de constancia/progreso; definir cálculo con sesiones completadas y datos que ya recoge el MVP.

**Entregable:** catálogo aprobado en [`docs/product/badges-challenges.md`](product/badges-challenges.md) con nombre, regla, límites, copy y ejemplos. Definición aprobada el 5 nov 2026; el flujo y copy visual se documentan en GRT-006.

**Aceptación:** cumplida en el catálogo enlazado: cada regla tiene ejemplos de entrada/salida, usa datos del MVP y no depende de likes; se documentan periodo, zona horaria, sincronización tardía, correcciones, eliminación, idempotencia, RPE y copy seguro.

**Dependencias:** GRT-005, GRT-006.

---

## S1 — Fundación (19–30 nov 2026)

### [GRT-008] Crear repositorio, convenciones y ambientes
**Épica:** E03 · **Puntos:** 5 · **Responsable:** Backend/Frontend

**Descripción:** Acordar monorepo o repos separados, formato de ramas, manejo de configuración, entornos local/staging/producción y secretos para Ionic/Angular/Capacitor, Laravel/PHP, Filament 5, Laravel/Inertia/React/Tailwind y PostgreSQL 16 con extensión PostGIS disponible. Esta es la primera tarjeta que crea código, después de cerrar S0.

**Para iniciar:** confirmar estructura y disponibilidad del equipo; definir runtime/versiones soportadas; no guardar secretos en Git.

**Entregable:** estructura inicial documentada, `.gitignore`, ejemplo de configuración sin secretos, instrucciones de desarrollo y arranque reproducible de ambientes locales.

**Aceptación:** un desarrollador puede levantar cada componente desde un checkout limpio. Staging local y producción simulada tienen puertos, redes, bases, volúmenes y credenciales independientes. PostgreSQL 16 ofrece PostGIS. El despliegue remoto queda para una etapa posterior.

**Dependencias:** S0 terminado.

**Seguimiento de GRT-008 — 27 nov 2026**

- [x] Documentar el monorepo, las convenciones, los archivos de configuración de cada ambiente y la guía de QA. Agregar `.gitignore` y Compose para PostgreSQL local. Ver [README](../README.md), [Compose web](../gym-rat-web/compose.yaml) y [Compose móvil](../gym-rat-app/compose.yaml) y [QA](../QA-TESTING.md).
- [x] S0 (sprint de descubrimiento) cerrado, confirmado por el responsable del proyecto el 27 de noviembre de 2026. [GRT-006](design/first-workout-flow.md) entregó el flujo y el prototipo.
- [x] Versionar Laravel, Filament y el cliente Ionic/Angular/Capacitor junto con sus archivos de bloqueo y Compose independientes en `9ece7f6`.
- [x] Verificar el arranque desde un checkout limpio. PostgreSQL saludable y HTTP 200 para la portada Laravel, acceso de Filament y cliente Ionic en navegador. Ver [evidencia de QA](../QA-TESTING.md#evidencia-de-grt-008).
- [x] Integrar Inertia/React y crear la portada inicial. Compilación con Vite en Docker satisfactoria. La evidencia HTTP del commit `9ece7f6` corresponde a la portada anterior.
- [x] Verificar la disponibilidad de PostGIS 3.7.0 en PostgreSQL 16.16 mediante [Compose complementario](../gym-rat-web/compose.postgis.yaml).
- [x] Levantar Inertia/React, Filament e Ionic en staging local y producción simulada desde `00f519a`. Comprobar la separación de bases con un dato sintético exclusivo de staging. Ver [resultado de QA](../QA-TESTING.md#resultado-de-la-separación-local).

### [GRT-009] Definir arquitectura modular y contratos API
**Épica:** E03 · **Puntos:** 5 · **Responsable:** Backend

**Descripción:** Diseñar módulos Laravel para identidad, catálogo, entrenamiento, retos, permisos/pagos y administración; definir contratos API y errores comunes. Mantener monolito modular en el MVP. Filament 5 será el panel administrativo; las pantallas PWA construidas con Inertia/React se limitarán a flujos de portal que no dupliquen operaciones del panel. Documentar integración, rutas, autenticación y autorización compartidas.

**Para iniciar:** revisar MVP y flujos; acordar versionado API, autenticación, paginación, validación y formato de error.

**Entregable:** ADR y OpenAPI inicial en `docs/architecture/`.

**Aceptación:** flujos principales tienen endpoints/responsables documentados; ninguna regla crítica depende de n8n.

**Dependencias:** GRT-005, GRT-008.

### [GRT-010] Diseñar esquema PostgreSQL e historial inmutable
**Épica:** E03/E04 · **Puntos:** 5 · **Responsable:** Backend

**Descripción:** Modelar usuarios, perfiles, ejercicios, planes/versiones, sesiones, series, favoritos y permisos. Preservar una copia o versión de la prescripción al completar sesión para que editar plan no altere historial.

**Para iniciar:** convertir flujos/prototipo en entidades y definir unidades, zona horaria, RPE opcional, peso y métricas.

**Entregable:** diagrama ER y decisiones de migraciones en `docs/architecture/data-model.md`.

**Aceptación:** se documentan relaciones, índices, borrado/retención y ejemplo de edición de plan sin modificar sesión anterior.

**Dependencias:** GRT-009.

### [GRT-011] Crear sistema visual y biblioteca de componentes
**Épica:** E02 · **Puntos:** 5 · **Responsable:** Diseño/Frontend

**Descripción:** Definir tipografía, color, iconografía, tono y componentes reutilizables; energía old-school sin sacrificar contraste, accesibilidad ni legibilidad en gimnasio.

**Para iniciar:** recoger referencias del brief y probar pantallas con brillo alto/bajo y uso con una mano.

**Entregable:** tokens y componentes de diseño, ejemplos de estados y `docs/design/visual-system.md`.

**Aceptación:** botones, formularios, tarjetas, modales y vacíos comparten patrones; contraste y tamaños táctiles revisados.

**Dependencias:** GRT-006.

### [GRT-012] Configurar autenticación y perfiles
**Épica:** E03 · **Puntos:** 5 · **Responsable:** Backend/Frontend

**Descripción:** Construir alta, login, recuperación y perfil mínimo. Definir consentimiento, eliminación de cuenta y manejo seguro de sesiones.

**Para iniciar:** definir campos estrictamente necesarios y proveedor de correo; no solicitar historial médico ni información sensible no requerida.

**Entregable:** flujo autenticado funcional en staging y documentación de campos/retención.

**Aceptación:** usuario crea/inicia sesión, recupera acceso, cierra sesión y puede solicitar eliminación; errores comunes tienen mensajes comprensibles.

**Dependencias:** GRT-008, GRT-009, GRT-010.

---

## S2 — Registro de entrenamiento (3–14 dic 2026)

### [GRT-013] Integrar catálogo API bajo demanda
**Épica:** E04 · **Puntos:** 5 · **Responsable:** Backend/Operaciones

**Descripción:** Integrar el catálogo MuscleWiki bajo demanda desde Laravel. No hacer carga/importación masiva ni guardar un catálogo replicado. Normalizar únicamente la respuesta transitoria necesaria para selector y detalle; persistir referencia externa ligada a selección individual más datos propios de rutina/sesión, conforme a GRT-004 y sus TTL.

**Para iniciar:** GRT-004 documentado; acceso Growth para producción en español, API key backend y contrato/headers revisados.

**Entregable:** servicio Laravel de búsqueda/detalle paginados, límites y errores documentados, sin comando/semilla de carga masiva.

**Aceptación:** respuesta de búsqueda entrega resultados en español en runtime; clave API protegida; no replica el dataset; telemetría/cuota y 429/fallback documentados; persistencia externa limitada a referencia por selección individual y TTL permitido.

**Dependencias:** GRT-004, GRT-010, GRT-046.

### [GRT-014] Construir explorador, búsqueda y favoritos
**Épica:** E04 · **Puntos:** 5 · **Responsable:** Frontend/Móvil/Backend

**Descripción:** Listar ejercicios, buscar por nombre, filtrar por equipo/parte del cuerpo y marcar favoritos. Incluir datos de muestra y vacío útil.

**Para iniciar:** cerrar campos visibles y comportamiento de filtros; paginación para no cargar todo el catálogo.

**Entregable:** pantallas móviles y endpoints para catálogo/favoritos.

**Aceptación:** se encuentran ejercicios con teclado móvil; filtros pueden combinarse; favoritos persisten en cuenta y no se duplican.

**Dependencias:** GRT-011, GRT-012, GRT-013.

### [GRT-015] Crear planes y calendario semanal
**Épica:** E04 · **Puntos:** 8 · **Responsable:** Frontend/Móvil/Backend

**Descripción:** Permitir crear/editar plan, días y ejercicios; asignar sesiones al calendario y usar una plantilla de inicio. No desarrollar aún marketplace de planes.

**Para iniciar:** acordar límite razonable del plan Free y estructura de versión; definir flujo de reordenamiento y día de descanso.

**Entregable:** CRUD de planes y calendario usable en app; plantilla inicial revisada.

**Aceptación:** usuario crea plan, añade ejercicios y días, cambia orden y ve el plan en calendario; edición no modifica sesiones previas.

**Dependencias:** GRT-010, GRT-014.

### [GRT-016] Registrar sesión, series y esfuerzo (RPE)
**Épica:** E04 · **Puntos:** 8 · **Responsable:** Frontend/Móvil/Backend

**Descripción:** Ejecutar sesión desde plan o en blanco; registrar series, repeticiones, carga y RPE opcional; pausar/finalizar y guardar borrador ante interrupciones.

**Para iniciar:** probar interacción con manos ocupadas, teclado numérico, unidades y pérdida de conexión; decidir comportamiento offline mínimo.

**Entregable:** flujo de sesión con persistencia y resumen terminado.

**Aceptación:** usuario inicia, agrega/edita series, marca ejercicio, guarda o finaliza; la app no pierde entradas al cambiar de pantalla y maneja errores de red.

**Dependencias:** GRT-015.

### [GRT-017] Implementar historial básico de sesiones
**Épica:** E04/E05 · **Puntos:** 3 · **Responsable:** Backend/Frontend

**Descripción:** Consultar sesiones pasadas y detalle por ejercicio; mostrar fecha, duración si existe, series y cargas sin presentar métricas falsas.

**Para iniciar:** definir paginación, zona horaria y formato de unidades; incluir estados vacíos.

**Entregable:** vista de historial y endpoints de lectura.

**Aceptación:** sesión recién terminada aparece en historial con sus valores exactos; el orden no cambia por huso horario.

**Dependencias:** GRT-016.

---

## S3 — Progreso y backoffice (17–28 dic 2026)

### [GRT-018] Mostrar récords personales y gráficas esenciales
**Épica:** E05 · **Puntos:** 5 · **Responsable:** Backend/Frontend

**Descripción:** Mostrar progresión por ejercicio y resúmenes semanales sencillos; definir claramente volumen/carga y evitar estimaciones engañosas.

**Para iniciar:** decidir cálculos con ejemplos de datos; manejar unidades, ejercicios sin carga y registros parciales.

**Entregable:** métricas documentadas y pantallas de progreso.

**Aceptación:** cada visualización indica periodo/unidad; cálculos coinciden con ejemplos manuales; estados sin datos explican qué registrar.

**Dependencias:** GRT-017.

### [GRT-019] Crear motor de reglas idempotente para insignias
**Épica:** E05 · **Puntos:** 5 · **Responsable:** Backend

**Descripción:** Evaluar reglas basadas en eventos de sesión y conceder cada insignia una sola vez; guardar por qué y cuándo se otorgó.

**Para iniciar:** usar catálogo de GRT-007; decidir si recalcular por evento o trabajo diferido y cómo corregir datos inválidos.

**Entregable:** motor/servicio de reglas, historial de premios y catálogo editable.

**Aceptación:** repetir un mismo evento no duplica insignia; reglas tienen ejemplos; administración puede desactivar premio sin borrar historial.

**Dependencias:** GRT-007, GRT-016.

### [GRT-020] Implementar reto semanal simple
**Épica:** E05 · **Puntos:** 5 · **Responsable:** Backend/Frontend

**Descripción:** Crear un reto individual con objetivo de sesiones completadas durante un periodo y mostrar avance. Los retos grupales se posponen.

**Para iniciar:** definir calendario/zonas horarias, inscripción automática o manual, límites y copy; no recompensar conductas inseguras.

**Entregable:** reto inicial, avance, resultado y herramienta de administración.

**Aceptación:** progreso se cuenta una sola vez por sesión; fechas son consistentes; usuario puede entender objetivo y estado.

**Dependencias:** GRT-019.

### [GRT-021] Construir backoffice mínimo con roles
**Épica:** E03/E07 · **Puntos:** 8 · **Responsable:** Backend/Frontend

**Descripción:** Implementar con Filament 5 el acceso administrativo separado para editar ejercicios, planes/plantillas, retos e insignias, consultar usuarios y ver estado de suscripción. Incluir una sección financiera protegida para revisar ingresos, comisiones, reembolsos, costos y rentabilidad; respetar roles y auditar cambios/importaciones. Las vistas Inertia/React del portal, si se necesitan, consumen los mismos servicios y políticas Laravel y no duplican acciones administrativas. No permitir cambios de pago sin registro de auditoría.

**Para iniciar:** definir roles mínimos (admin/editor/soporte), operaciones permitidas y campos personales ocultos.

**Entregable:** backoffice con control de acceso, registro de auditoría y navegación a módulo financiero (GRT-043–045).

**Aceptación:** API protege cada acción, cambios quedan auditados y el operador no puede ver ni editar información innecesaria.

**Dependencias:** GRT-009, GRT-010, GRT-013, GRT-019.

### [GRT-043] Registrar costos e importar liquidaciones de tiendas
**Épica:** E03/E06/E09 · **Puntos:** 8 · **Responsable:** Backend/Operaciones

**Descripción:** Implementar un registro de costos fijos/variables y una importación repetible de reportes de Apple/Google para ventas, comisiones, impuestos/retenciones, reembolsos y liquidaciones. En MVP aceptar archivos CSV exportados por el operador; conservar periodo, storefront, moneda, fecha de reporte, fuente y referencia de origen. No tratar notificaciones de compra como ingresos liquidados.

**Para iniciar:** GRT-042 aprobado; revisar formatos de reportes de ambas tiendas; definir categorías de gasto, validación de columnas, duplicados y permisos de operador.

**Entregable:** modelo/tablas, endpoints/admin de captura e importación, plantilla CSV de gastos, reporte de errores y conciliación idempotente.

**Aceptación:** reimportar un mismo archivo no duplica movimientos; importes fuente permanecen trazables; se muestran diferencias y filas rechazadas antes de confirmar; valores reales y estimados se identifican.

**Dependencias:** GRT-021, GRT-042.

### [GRT-044] Construir dashboard financiero y gráficas de rentabilidad
**Épica:** E03/E09 · **Puntos:** 8 · **Responsable:** Frontend/Backend

**Descripción:** Crear el módulo de rentabilidad del backoffice con resumen del periodo, serie mensual de ventas únicas, comisiones, reembolsos, efectivo neto liquidado, gastos y resultado operativo; mostrar contribución por SKU/cohorte y distancia al punto de equilibrio. Incluir gasto mensual MuscleWiki, uso/cuota y runway por etapa. Filtrar por fechas, tienda, moneda y SKU. Diferenciar reales, comprometidos y proyecciones; no presentar MRR/suscriptores como métricas Gym Rat.

**Para iniciar:** consumir el modelo GRT-042 y las importaciones/ledger de GRT-043; definir estados vacíos, permisos y formato de moneda.

**Entregable:** panel responsive con gráficas y tablas accesibles, filtros y detalle trazable hasta los datos fuente; definición en `docs/product/financial-dashboard.md`.

**Aceptación:** cada gráfico señala periodo, unidad y estado de dato; totales coinciden con el ledger importado; ventas únicas separadas de costo API recurrente y no se muestran MRR/suscriptores Gym Rat.

**Dependencias:** GRT-021, GRT-042, GRT-043.

### [GRT-045] Validar conciliación, permisos y operación del módulo financiero
**Épica:** E03/E09 · **Puntos:** 5 · **Responsable:** QA/Backend/Operaciones

**Descripción:** Validar flujos CSV de Apple/Google y captura de gastos con casos de comisión, impuestos, refunds, monedas, filas duplicadas, correcciones y ciclos parciales de reportes. Revisar RBAC, trazabilidad, errores y conciliación de productos no consumibles de pago único.

**Para iniciar:** GRT-043/044 implementadas en staging y datos sintéticos representativos; asignar quién puede importar, aprobar y consultar información.

**Entregable:** matriz de conciliación, checklist de permisos, evidencia de escenarios y runbook mensual de cierre financiero.

**Aceptación:** totales se explican hasta sus fuentes; importaciones repetidas no duplican; usuarios sin rol financiero no acceden; discrepancias no quedan ocultas y hay procedimiento de corrección.

**Dependencias:** GRT-043, GRT-044.

### [GRT-022] Implementar métricas de producto con privacidad
**Épica:** E07/E08 · **Puntos:** 3 · **Responsable:** Producto/Backend

**Descripción:** Medir eventos mínimos: registro, primer entrenamiento, sesión completada, pantalla de paywall, inicio/fin de suscripción y errores. Evitar incluir cargas, nombres o datos sensibles en analítica.

**Para iniciar:** definir diccionario de eventos, retención y consentimiento; decidir herramienta y ambiente.

**Entregable:** esquema de eventos y panel de funnel/retención inicial.

**Aceptación:** eventos de staging no contaminan producción; identificadores minimizados; se puede calcular activación, conversión y retención.

**Dependencias:** GRT-005, GRT-012, GRT-016.

---

## S4 — Acceso y compra única (31 dic 2026–11 ene 2027)

### [GRT-023] Crear permisos de producto independientes del proveedor
**Épica:** E06 · **Puntos:** 5 · **Responsable:** Backend

**Descripción:** Modelar Free/Warm-Up y un entitlement permanente de desbloqueo completo, desacoplado del proveedor de pago. No hay suscripción Gym Rat ni periodo de gracia/cancelación. La disponibilidad de API MuscleWiki es un estado operativo separado, nunca el permiso de compra del usuario.

**Para iniciar:** usar ADR de pagos; definir estados de prueba y política de expiración.

**Entregable:** servicio de entitlements y matriz de permisos Free/Warm-Up y desbloqueo completo; estado API de proveedor separado.

**Aceptación:** cliente no puede otorgarse Pro; permisos derivan de estado confirmado y se pueden reconciliar por proveedor.

**Dependencias:** GRT-003, GRT-009.

### [GRT-025] Crear paywall y gestión visible de suscripción
**Épica:** E06 · **Puntos:** 5 · **Responsable:** Diseño/Frontend

**Descripción:** Presentar Free/Warm-Up y un único desbloqueo completo de pago único. Mostrar precio total MXN, funciones/contenido incluidos y dependencia de servicio API según condiciones aprobadas; no mostrar renovación/cancelación ni productos de suscripción Gym Rat.

**Para iniciar:** validar nombres con usuarios, consultar `docs/product/pricing-hypotheses.md`, usar matriz de acceso Free/Garage Rat/Iron Rat y revisar reglas de ambas tiendas.

**Entregable:** paywall móvil de compra única, estado/restauración y errores; la web queda informativa y de captación.

**Aceptación:** no hay precio ambiguo; usuario puede encontrar cómo administrar/cancelar; accesibilidad y errores de compra incluidos.

**Dependencias:** GRT-002, GRT-003, GRT-023.

### [GRT-026] Preparar integración móvil de compras y restauración
**Épica:** E06/E08 · **Puntos:** 8 · **Responsable:** Móvil/Backend

**Descripción:** Configurar un SKU no consumible de desbloqueo completo en Apple/Google; validar transacciones con backend y asignar entitlement permanente. Separar compras de tienda del plan mensual MuscleWiki administrado por Operaciones/Finanzas.

**Para iniciar:** verificar soporte actual de Ionic/Capacitor, costos y reglas de tienda vigentes para Apple In-App Purchase y Google Play Billing.

**Entregable:** prueba end-to-end en sandbox de compra, validación, reembolso y restauración en iOS/Android; no hay renovación/cancelación ni migración de tier Gym Rat.

**Aceptación:** compra sandbox concede acceso solo tras validación; restaurar compra y cambiar dispositivo recupera estado; eventos duplicados de tienda se procesan de forma idempotente.

**Dependencias:** GRT-003, GRT-023, GRT-025.

---

## S5 — Web y automatizaciones (14–25 ene 2027)

### [GRT-027] Publicar landing y captura de leads
**Épica:** E07 · **Puntos:** 5 · **Responsable:** Frontend/Growth

**Descripción:** Crear con Laravel + Inertia + React + Tailwind la página pública con propuesta, beneficios verificables, precio/espera, preguntas frecuentes, privacidad y formulario. Mantener rutas y layouts separados del backoffice. No usar claims de resultados físicos garantizados.

**Para iniciar:** brief, identidad, precio experimental y llamada a la acción elegida.

**Entregable:** sitio responsive desplegado con analítica consentida y formulario funcional, integrado al monolito Laravel; no incluye checkout web.

**Aceptación:** formulario valida correo y consentimiento; confirmación clara; el lead llega al destino operativo sin duplicados.

**Dependencias:** GRT-001, GRT-002, GRT-011.

### [GRT-028] Automatizar onboarding y ciclo de lead en n8n
**Épica:** E07 · **Puntos:** 3 · **Responsable:** Growth/Operaciones

**Descripción:** Automatizar confirmación y una secuencia breve de bienvenida/actualización de beta. Respetar consentimiento, baja y límites de frecuencia.

**Para iniciar:** mapear datos mínimos, sistema destino y plantillas de mensajes; probar workflow con datos ficticios.

**Entregable:** workflow versionado/documentado, credenciales seguras y manejo de error.

**Aceptación:** test y producción están separados; workflow no envía duplicados; baja detiene mensajes; error alerta al equipo.

**Dependencias:** GRT-027.

### [GRT-029] Automatizar avisos de soporte y resumen operativo
**Épica:** E07 · **Puntos:** 3 · **Responsable:** Operaciones/Backend

**Descripción:** Enviar aviso interno por estados de pago que requieren atención y un resumen agregado de leads/activación. No poner datos de entrenamiento personales en mensajes.

**Para iniciar:** definir eventos fuente, canal interno y responsables; usar datos agregados y claves de correlación mínimas.

**Entregable:** workflows versionados y guía de recuperación/reintento.

**Aceptación:** alertas son accionables, duplicados deduplicados y fallos de automatización visibles; pagos no dependen de workflow para confirmar estado.

**Dependencias:** GRT-022, GRT-026.

### [GRT-030] Definir soporte, privacidad y operaciones de cuenta
**Épica:** E07/E08 · **Puntos:** 5 · **Responsable:** Producto/Operaciones

**Descripción:** Preparar canal de soporte, FAQ, procedimiento de reembolso/cancelación, eliminación/exportación de cuenta, retención y respuesta a incidentes. Revisar textos con asesoría local.

**Para iniciar:** inventario de datos/terceros, proceso de pago y países objetivo.

**Entregable:** política publicada, términos, FAQ y runbook interno de soporte/incidente.

**Aceptación:** usuario puede contactar soporte y solicitar acciones; responsables y tiempos internos están asignados; textos no prometen cumplimiento no verificado.

**Dependencias:** GRT-003, GRT-012, GRT-023.

---

## S6 — Beta cerrada (28 ene–8 feb 2027)

### [GRT-031] Preparar beta, reclutar y moderar feedback
**Épica:** E08 · **Puntos:** 3 · **Responsable:** Producto/Growth

**Descripción:** Reclutar 15–30 personas del segmento objetivo, dar instrucciones y recoger feedback estructurado sobre primer entrenamiento, historial y disposición de pago.

**Para iniciar:** landing, prototipo o build estable, consentimiento y criterio de selección.

**Entregable:** cohortes, guion de sesión, formulario de feedback y tablero de incidencias priorizado.

**Aceptación:** cada participante tiene objetivo/tarea y canal de soporte; feedback se vincula con evidencia y prioridad.

**Dependencias:** GRT-027, GRT-030.

### [GRT-032] Ejecutar revisión de calidad de flujos críticos
**Épica:** E08 · **Puntos:** 5 · **Responsable:** QA/Equipo

**Descripción:** Revisar alta, entrenamiento, guardado, historial, permisos, compra in-app, restauración y cancelación en dispositivos representativos. Registrar pasos reproducibles y severidad.

**Para iniciar:** build candidata, cuentas de prueba y matriz de dispositivos/OS.

**Entregable:** informe de aceptación, lista de fallos y decisión Go/No-Go de beta.

**Aceptación:** no quedan fallos bloqueantes de pérdida de sesiones, acceso indebido o cobro; issues restantes tienen severidad y responsable.

**Dependencias:** GRT-016, GRT-018, GRT-021, GRT-026.

### [GRT-033] Corregir fricciones de activación y fallos críticos
**Épica:** E04/E08 · **Puntos:** 8 · **Responsable:** Equipo

**Descripción:** Priorizar defectos que impiden empezar, guardar sesión o entender el valor de Pro; limitar mejoras de pulido a evidencia del piloto.

**Para iniciar:** analizar sesiones de prueba, métricas y tickets; decidir máximo de alcance del sprint.

**Entregable:** nueva build y changelog de beta con issues cerrados y conocidos.

**Aceptación:** los bloqueos críticos están cerrados y los participantes pueden completar el flujo sin ayuda o se documenta un plan correctivo concreto.

**Dependencias:** GRT-031, GRT-032.

### [GRT-034] Decidir precio y criterios Go/No-Go
**Épica:** E01/E06/E08 · **Puntos:** 3 · **Responsable:** Producto

**Descripción:** Calcular viabilidad del desbloqueo único y sostenibilidad del costo API MuscleWiki de 12/24 meses. Usar proceeds netos, reembolsos, CAC, tarifas mensuales API por etapa, infraestructura, soporte y operación; decidir Go/No-Go y gates de escalamiento.

**Para iniciar:** definir muestra mínima, precio único en MXN y límites de pérdida/runway; reunir datos de piloto sin interpretar muestras pequeñas como certeza.

**Entregable:** proyección de 12/24 meses por ventas únicas, punto de equilibrio y reserva API por etapa, evidencia de precio y decisión Go/No-Go/reversión en `business-plan.md`, contrastada con GRT-044.

**Aceptación:** observados y supuestos separados; proceeds no se confunden con utilidad; se identifica número de ventas nuevas requerido para sostener costo API; crecimiento de plan requiere 12 meses +20% en caja y permiso contractual.

**Dependencias:** GRT-002, GRT-022, GRT-031, GRT-042, GRT-044.

---

## S7 — Preparar tiendas (11–22 feb 2027)

### [GRT-035] Preparar fichas, privacidad y cuentas de revisión
**Épica:** E08 · **Puntos:** 5 · **Responsable:** Release/Producto

**Descripción:** Preparar nombre, descripción, capturas reales, clasificación, enlaces de privacidad/soporte, cuenta demo y explicación de compras/servicios externos.

**Para iniciar:** feature freeze, build candidata y requisitos vigentes de ambas tiendas.

**Entregable:** materiales completos de App Store Connect y Play Console, demo account y checklist de revisión.

**Aceptación:** todas las URLs y credenciales funcionan; capturas representan la app; suscripciones IAP visibles y revisables donde se requieren.

**Dependencias:** GRT-025, GRT-026, GRT-030, GRT-032.

### [GRT-036] Revisar seguridad, respaldo y recuperación
**Épica:** E03/E08 · **Puntos:** 5 · **Responsable:** Backend/Operaciones

**Descripción:** Verificar control de acceso, secretos, rate limits, backups y recuperación; revisar que logs no contengan credenciales ni datos personales innecesarios.

**Para iniciar:** staging cercano a producción y lista de datos/proveedores; pedir revisión especializada si el equipo no cubre seguridad.

**Entregable:** checklist firmado, evidencia de restauración en entorno no productivo y lista de riesgos aceptados/corregidos.

**Aceptación:** backup se puede restaurar, secretos están fuera del repo, roles probados y fallos críticos cerrados.

**Dependencias:** GRT-008, GRT-012, GRT-021.

### [GRT-037] Enviar builds y resolver observaciones de tiendas
**Épica:** E08 · **Puntos:** 5 · **Responsable:** Release/Móvil

**Descripción:** Enviar candidatos a revisión, monitorizar estado y resolver rechazos o preguntas con cambios acotados. Mantener una build web funcional mientras se revisan.

**Para iniciar:** materiales listos, pagos revisados y demo account activa.

**Entregable:** builds aprobadas o registro de observaciones con plan, dueño y nueva fecha.

**Aceptación:** ambas fichas están aprobadas o hay decisión explícita de lanzamiento web/beta y fecha alternativa; no se oculta funcionalidad a revisión.

**Dependencias:** GRT-035, GRT-036.

### [GRT-038] Congelar release y validar observabilidad
**Épica:** E08 · **Puntos:** 3 · **Responsable:** Release/Backend

**Descripción:** Definir versión de lanzamiento, alertas de errores/API/pago, tablero diario y procedimiento de rollback. Hacer prueba operativa sin datos reales sensibles.

**Para iniciar:** candidata aprobada, métricas de GRT-022 y runbooks disponibles.

**Entregable:** checklist de release, versión etiquetada y dashboard de operación.

**Aceptación:** equipo puede detectar caída, pagos fallidos y aumento de errores; rollback y contacto de responsables están documentados.

**Dependencias:** GRT-022, GRT-029, GRT-036, GRT-037.

---

## S8 — Lanzamiento (25 feb–8 mar 2027)

### [GRT-039] Lanzamiento controlado por cohortes
**Épica:** E08/E07 · **Puntos:** 3 · **Responsable:** Producto/Growth

**Descripción:** Abrir acceso gradualmente a lista de espera y usuarios nuevos; vigilar errores, activación, fallos de pago y soporte antes de ampliar adquisición.

**Para iniciar:** Go/No-Go aprobado, tienda/web listos y equipo de guardia asignado.

**Entregable:** plan y registro diario de lanzamiento, notas de versión y mensajes a usuarios.

**Aceptación:** cada expansión de cohorte usa métricas/umbrales acordados; existe canal para detener adquisición y atender incidencias.

**Dependencias:** GRT-034, GRT-038.

### [GRT-040] Operar soporte y pagos en primera semana
**Épica:** E06/E07/E08 · **Puntos:** 3 · **Responsable:** Operaciones

**Descripción:** Resolver cuentas sin acceso, cancelaciones, compras duplicadas y preguntas frecuentes; reconciliar transacciones/notificaciones de Apple y Google Play con entitlements.

**Para iniciar:** runbook, responsable diario, accesos mínimos y tablero de pagos.

**Entregable:** registro de incidencias y reporte de salud del primer periodo.

**Aceptación:** casos tienen responsable/estado; divergencias de acceso se investigan; no se exponen secretos en tickets.

**Dependencias:** GRT-026, GRT-030, GRT-038.

### [GRT-041] Revisar resultados y priorizar siguiente ciclo
**Épica:** E01/E08 · **Puntos:** 2 · **Responsable:** Producto

**Descripción:** Revisar funnel, retención temprana, conversión, soporte y feedback; escoger una mejora de producto y una de adquisición para el siguiente ciclo.

**Para iniciar:** al menos una semana de datos, definiciones de métricas y contexto de tamaño de muestra.

**Entregable:** informe de lanzamiento y backlog priorizado para marzo.

**Aceptación:** decisiones ligadas a evidencia y límites de muestra; se documentan hipótesis nuevas, métricas y dueño.

**Dependencias:** GRT-022, GRT-039, GRT-040.

---

## Post-MVP / diferido

### [GRT-024] Evaluar pagos web (fuera del MVP)
**Épica:** E06 · **Puntos:** por estimar · **Responsable:** Producto/Backend

**Descripción:** Reevaluar después del lanzamiento si conviene ofrecer pagos web. Cualquier propuesta deberá revisar políticas vigentes de Apple y Google, elegibilidad por país y experiencia de suscripción antes de elegir proveedor o diseñar sincronización de entitlements.

**Para iniciar:** contar con datos de conversión/retención del MVP y una decisión explícita de priorización.

**Entregable:** decisión documentada sobre incluir o descartar pagos web en una fase posterior, con análisis de políticas, costos y arquitectura.

**Aceptación:** no se implementa ni comunica checkout web hasta aprobar la decisión posterior al MVP; el acceso Pro se mantiene basado en entitlements confirmados.

**Dependencias:** resultados de GRT-041 y revisión de políticas de tienda.

---

## Dependencias principales

```text
GRT-001 → GRT-002 → GRT-003 → GRT-023 → GRT-025 → GRT-026
GRT-001 → GRT-005 → GRT-006 → GRT-011 → GRT-008/009/010
GRT-004 + GRT-046 → GRT-013 → GRT-014 → GRT-015 → GRT-016 → GRT-017 → GRT-018
GRT-047 → GRT-048 → GRT-023/025/026
GRT-007 → GRT-019 → GRT-020
GRT-027 → GRT-028
GRT-022 + GRT-031 → GRT-034 → GRT-039 → GRT-041
GRT-002/003 → GRT-042 → GRT-043 → GRT-044 → GRT-045 → GRT-034
GRT-030 + GRT-035 + GRT-036 → GRT-037 → GRT-038 → GRT-039
```

## Qué mover fuera del lanzamiento si hay retraso

1. Grupos, rankings, retos sociales y chat.
2. Planes ilimitados/marketplace y herramientas avanzadas de coach.
3. IA dentro de la app, wearables y nutrición.
4. Automatizaciones de marketing no necesarias para onboarding y soporte.
5. Gráficas avanzadas; conservar historial fiable y métricas básicas.

No recortar: permisos y seguridad de pagos, derechos de contenido, preservación del historial, cuenta/eliminación, flujos de sesión, soporte, política de privacidad ni preparación de revisión de tiendas.
