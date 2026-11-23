# Diseño técnico — integración MuscleWiki API

**Fecha:** 13 de noviembre de 2026.  
**Stack:** Laravel monolito modular, Ionic/Angular/Capacitor, PostgreSQL; secret API solo backend.  
**Decisión:** [ADR-004](../decisions/ADR-004-musclewiki-api-growth.md).  
**Regla Free/cuota:** [ADR-005](../decisions/ADR-005-free-preview-and-api-budget.md).  
**Gates pendientes:** plan API activo para requests directas; validar respuestas/campos e idioma del catálogo bajo demanda en la integración; regla de historial que no retenga metadata externa más de 30 días sin permiso adicional.

**Política Free aprobada:** una sesión guardada por cuenta; sin thumbnails, imágenes, bodymaps ni video. Después de guardar la primera sesión, presentar el paywall. Cualquier consulta de texto pasa por cuota compartida y los mismos límites de backend para todos los usuarios.

## Alcance

Laravel consume MuscleWiki para metadatos y URLs/token de streaming. La app nunca recibe la API key permanente ni usa un endpoint Gym Rat que funcione como API general de datos MuscleWiki. Entrega solo datos de ejercicio requeridos por pantallas autorizadas. Gym Rat crea y versiona sus propias plantillas PPL; no requiere endpoints de rutinas API en Growth.

## Política de caché por tipo de respuesta

| Datos/bytes | Política operativa | Capa permitida | TTL/expiración | Efecto en cuota |
|---|---|---|---|---|
| Metadatos textuales (nombre, músculos, equipo, pasos, categoría, dificultad) | Caché de respuesta por endpoint, ID, filtros, idioma y versión de schema; no hacer bulk export. Hard expiry; refrescar al vencer. | Laravel Cache/Redis interno; también edge solo si headers/contrato lo permiten | Máximo 30 días | Reduce llamadas de detalles/listados repetidos dentro del TTL. |
| Miniaturas | Pasar respuesta y `Cache-Control` tal como llega. Cache privada en cliente de usuario, no CDN/Redis/shared edge. | Dispositivo/cliente solamente | Hasta 24 h si los headers lo permiten; validar `private` | Un cache hit de dispositivo puede ahorrar una llamada. Evitar prefetch masivo. |
| Bodymaps | No almacenar ni cachear; docs/headers indican `no-store`. No solicitar en Testing/Growth. | Render directo en memoria y descartar al salir si se habilita Professional+ | No-cache/no-store | Cada solicitud puede consumir una llamada. |
| Video | Streaming directo a player; proxy transparente sin disco ni cache compartida. Mantener headers originales, incluyendo `no-store`; no capturar bytes en logs/traces. | Buffer transitorio normal del reproductor/memoria | Solo duración de reproducción; sin offline/preload persistente | Cada request `/stream/` y cada Range request cuenta; un solo playback puede costar varias llamadas. No asumir que caching reduce gasto. |
| URL de media tokenizada | Token corto entregado al player para `/stream/` solamente. No persistir, cachear, compartir, incluir en logs/analytics/crash reports, ni reutilizar fuera de sesión. | Memoria del proceso del reproductor | Expira por default a 15 min | Crear token no sustituye llamadas de streaming ni cuota. Reusar token durante una sesión si sigue válido. |

**Precedencia:** los términos API permiten ciertos TTL máximos, pero la API documenta headers más restrictivos para miniaturas/bodymaps/video. Pasar headers sin cambios y respetar `private`/`no-store`; no elevar la caché por configuración propia. El límite de términos siempre es techo, no obligación de retención.

El plan técnico asume que Laravel Cache usa Redis interno privado con TTL para texto autorizado. Si Redis no está en la infraestructura aprobada, usar el cache store privado de Laravel con la misma expiración. No guardar cuerpo de video ni imágenes de medios en PostgreSQL, filesystem, object storage, backup o CDN.

## Flujo

1. App solicita catálogo/búsqueda/detalle al backend autenticado; el backend valida entitlement y aplica throttling.
2. Laravel busca en caché de metadatos con key canónica (`resource + id + lang + filtros + tier`); ante hit vigente responde al usuario en el contexto de la app. En miss consulta API con `X-API-Key` server-side, valida respuesta, elimina campos no necesitados/tokenizados, guarda texto con hard TTL ≤30 días y responde el mínimo requerido.
3. Para lista inicial de entrenamiento, devolver detalle mínimo/paginado y cargar ejercicios según búsqueda/demanda. No consumir `/full` en Growth; no hacer crawl, sync total ni precargar todo el catálogo.
4. Para play, backend verifica permiso de usuario y genera media token short-lived con `POST /media/token`; devuelve URL HTTPS al reproductor con vencimiento, sin registrarla en logs. Alternativa de proxy solo si resulta necesaria: passthrough transparente, respuesta y headers sin cambios, streaming incremental, sin buffering a archivo ni cache intermedia.
5. Reproductor solicita `Range`/chunks directamente a MuscleWiki usando token. Cada request se agrega a consumo. Al expirar o responder 401, renovar token una vez; no reintentar en bucle. 429 se traduce a estado de cuota del proveedor, con `Retry-After`/reset y CTA de fallback, no un error técnico bruto.
6. App puede continuar creando y guardando entrenamiento sin acceso API; para video/metadata ausente muestra estado claro. Si metadata cache expiró, refrescar on-demand antes de revelar/guardar datos derivados.

## Cómputo de llamadas y presupuesto

- Contar solicitudes reales por usuario activo, pantalla, búsqueda, apertura de detalle y reproducción. Medir aparte `/stream/` y cada Range request; considerar expansión de `/full` como costo múltiple y evitarla en Testing/Growth.
- Cachear metadata reduce llamadas repetidas mientras TTL está vigente; no documentar ETag/304 como mecanismo de ahorro ni asumir que las respuestas condicionales están soportadas. Top-ups no reemplazan una cuota adecuada.
- Instrumentación agregada sin PII: `api_plan`, `route_class`, `cache_hit`, `http_status`, `calls_cost_header`, `stream_range_count`, latencia y mes/cuota. Nunca guardar ejercicio consultado junto con cuenta personal si no es necesario; evitar URL/token en logs.
- Revisar proyección mensual con bandas 70/85/95%; enviar alarmas al responsable de Operaciones/Finanzas y al canal interno configurado. Testing: 1,000 llamadas; Growth: 30,000; Professional: 100,000; Enterprise: 300,000. Fijar plan solo con telemetría de piloto y precios públicos vigentes.
- Aplicar budget guard central al consumo del mes. En 70% notificar; en 85% limitar búsquedas de bajo valor y desactivar cualquier precarga; en 95% pausar nuevas consultas gratuitas no cacheadas y alertar para decisión manual. Al llegar a la cuota o recibir 429, detener reintentos y consultas no esenciales hasta el reset/cambio de plan; nunca comprar top-ups automáticamente. Mantener disponibles las rutinas propias y el registro/lectura de entrenamientos ya guardados. No inventar una cuota por usuario que el proveedor no publica.
- El paywall Free se activa inmediatamente después de que el servidor confirme el guardado de la primera sesión. La cuenta conserva esa sesión; rechazar de forma explicada intentos de guardar otra sesión sin entitlement. No condicionar la lectura/edición de datos ya creados a la API.
- Free no solicita rutas de thumbnail, image, bodymap o video, ni recibe URLs/token de medios. El control debe aplicarse también en endpoints del backend; ocultar controles en la app no basta.
- Cada cambio de etapa requiere presupuesto MXN por 12 meses +20% y GRT-034; el plan se renueva mensual, Gym Rat no cobra renovación.

## Datos e historial

El catálogo de Gym Rat mantiene una clave interna estable (`exercise_key`) y sus etiquetas/descripciones propias; una tabla de correspondencia guarda el ID externo MuscleWiki usado solo para solicitar el contenido. Las sesiones guardan `exercise_key`, versión de la etiqueta propia y sets/reps/peso/RPE, no copian nombre, instrucciones, músculos, video ni imágenes de respuesta MuscleWiki. Esto permite que editar una plantilla no reescriba sesiones pasadas y que el historial siga visible aunque el API o un medio se retire. El ID externo se trata como referencia de integración y no se ofrece/exporta a terceros; refrescar/invalidar su correspondencia conforme al API vigente. La respuesta textual de MuscleWiki sigue cacheada máximo 30 días.

## Seguridad y privacidad

- `MUSCLEWIKI_API_KEY` en secret manager/entorno backend, nunca en móvil, frontend, URL, logs o repositorio.
- User-level authorization before media-token mint; per-user/per-device throttling and abuse limits; keep token URLs out of access logs/reverse-proxy analytics.
- Validate hostname/scheme of media URLs before passing to native player to prevent SSRF/open redirect; allow only official API HTTPS origins and approved `/stream/` paths.
- Never send API keys or media tokens to n8n/analytics; use redaction filters for headers, query params and crash reports.
- Keep MuscleWiki data attribution exact in Terms/Privacy and preserve embedded video branding.

## Tolerancia a fallas

- API 429: stop retries until quota reset/backoff; report quota state in backend, retain local session logging, disable optional browse/play actions with clear message.
- 401/403 token or expired key: refresh token once if expired; persistent failure raises operations alert. Never refresh/replay forever.
- 5xx/503: exponential retry within bounded server budget, no repeated user-triggered storms; use only unexpired permitted metadata cache.
- Video 404/withdrawal: mark resource unavailable for session only, do not cache a negative media file beyond documented rules; allow continue workout.
- Subscription/API billing failure or plan change: alert Finance/Operations, forecast downgrade; customer entitlement remains validated independently but API feature availability may degrade per contractual terms.

## Implementación por etapas

1. Product/legal gate: paid-plan terms permit commercial in-app display and in-app video streaming; disclose API-dependent media and its availability limits in paywall/store terms. For history, persist Gym Rat-authored training data and external reference ID; do not retain external metadata snapshots beyond 30 days unless separately authorized.
2. Testing: server adapter, secret storage, allowed metadata cache, token flow, call metering, error states; closed beta only.
3. Growth: Spanish production plan, catalog search/detail and PPL templates authored by Gym Rat. Do not enable routine endpoints or bodymaps.
4. Professional: add secondary/tertiary muscles and bodymaps only if user need and plan economics justify them; follow no-cache bodymap header.

## Catálogo bajo demanda (GRT-046)

Gym Rat does not preload a set of MuscleWiki exercise IDs, import a catalog, or maintain an exercise-media bucket. Render search/list results from the live API when the user opens the exercise picker or changes a query/filter; request exercise detail and media only when the user selects or opens an item. Use paginated `/exercises` or `/search` calls, with `lang=es-es` on Growth. The PPL routine is a Gym Rat-owned template; its proposed entries are search prompts, not a fixed external manifest.

Persist only the external ID reference required to resolve a user-selected exercise and Gym Rat-authored training data (sets, load, reps, RPE, timestamps). Do not persist a response snapshot or external instructions into long-lived workout history. Cache text metadata for no longer than 30 days, keyed by endpoint/query/language and honoring response headers. Do not turn query caching into bulk synchronization. Keep the API key in Laravel; enforce query/page limits and log call counts without media URLs/tokens.

For media, use the supported token/proxy flow; stream only inside the app, do not reveal downloadable URLs, and do not write video bytes to disk, object storage, or shared/CDN cache. Every `/stream/` and each Range request count against quota; monitor calls per active user/session and per playback. A 429/403/timeout must not block viewing or editing Gym Rat-authored routines or saving training sessions. Offer retry or hide unavailable third-party detail/media while preserving workout data.

This is the approved product approach, not evidence that production API credentials or live integration have been tested. Validate actual response fields, language, rate/quota behavior, and stream call counts with the paid account before opening the beta.
5. Enterprise: only after Professional quota/feature gate and contracted priority service.
6. Run manual integration review in staging: cache expiry, language-separated cache keys, `Cache-Control` passthrough, multiple Range calls, token expiry/leak redaction, 429/backoff, API billing failure, removed exercise, and session logging during outage.

## Referencias

- [MuscleWiki API terms](https://api.musclewiki.com/api-terms)
- [MuscleWiki API documentation](https://api.musclewiki.com/documentation)
- [MuscleWiki API pricing](https://api.musclewiki.com/pricing)
- [Business plan](../product/business-plan.md)
