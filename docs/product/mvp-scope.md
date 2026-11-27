# Alcance del MVP y criterios de salida

**Estado:** alcance MVP definido para revisión; Free, pago único y límites de consulta se decidieron por Producto. La validación de conversión y el desempeño real de cuota ocurren en beta (GRT-034), no son evidencia de mercado ya obtenida. Términos de plan pagado MuscleWiki permiten display/streaming comercial in-app, con licencia revocable; no se garantiza disponibilidad perpetua. Consultar [ADR-003](../decisions/ADR-003-one-time-full-unlock.md), [ADR-004](../decisions/ADR-004-musclewiki-api-growth.md), [ADR-005](../decisions/ADR-005-free-preview-and-api-budget.md) y [plan de negocio](business-plan.md).  
**Fecha:** 13 de noviembre de 2026.  
**Tarjeta:** GRT-005 — Definir alcance MVP y criterios de salida.  
**Mercado inicial:** México. Presentar y vender desde el lanzamiento en pesos mexicanos (MXN). Precio aprobado: $399 MXN para primeras 100 compras o 60 días de beta abierta (lo que ocurra primero), luego $699 MXN estándar. La configuración por storefront y el precio cobrado que muestra la tienda se confirman en GRT-003.

## Objetivo del MVP

Ayudar a levantadores amateur intermedios de gimnasios independientes a registrar sus sesiones y consultar su progreso con pocos pasos. El MVP debe demostrar que el usuario puede completar y recuperar entrenamientos, que vuelve a usar el registro y que una parte está dispuesta a pagar por seguimiento y un catálogo ampliado.

El MVP se entrega como app móvil híbrida iOS/Android, sitio web público informativo y de captación, API y backoffice. La arquitectura es un monolito modular. Las compras digitales se realizan en las tiendas móviles; el backend valida compras y mantiene los permisos.

### Stack técnico

- App móvil: Ionic + Angular + Capacitor.
- Sitio público: Laravel + Inertia + React + Tailwind, limitado a información y captación.
- Backend/API: Laravel + PHP, monolito modular.
- Backoffice: Filament v5 para administración; Laravel + Inertia + React + Tailwind para vistas de portal con experiencia PWA que no dupliquen el panel. La web pública comparte esta base técnica, con rutas y layouts separados.
- Datos: PostgreSQL 16; PostGIS disponible como extensión, sin alcance geoespacial en el MVP.

La decisión, responsabilidades y controles están en [`ADR-002-backoffice-stack.md`](../decisions/ADR-002-backoffice-stack.md).

## Usuarios y niveles incluidos

| Nivel | Alcance del lanzamiento | Referencia USD (precio al usuario en MXN) |
|---|---|---|
| **Free / Warm-Up** | Vista previa limitada a un entrenamiento guardado por cuenta. Sin miniaturas, imágenes, videos ni otros medios. Después de guardar la sesión se muestra inmediatamente la oferta de compra; para registrar otro entrenamiento debe desbloquear. Consulta de texto de ejercicios bajo demanda solo dentro de la cuota compartida API disponible. | Gratis |
| **Desbloqueo completo** | SKU no consumible de pago único para las funciones Gym Rat incluidas (planes/calendario, favoritos, historial completo, gráficas y retos del MVP) y demostraciones MuscleWiki que estén disponibles bajo el plan API vigente. | Aprobado: MXN $399 introductorio / MXN $699 estándar; oferta inicial primeras 100 compras o 60 días |

Free/Warm-Up funciona como acceso limitado de prueba; no se cobrarán suscripciones a los usuarios. Garage Rat e Iron Rat quedan como nombres históricos/etapas internas, no como planes con renovación. La sesión de vista previa se conserva en la cuenta y no se pierde al mostrar la oferta. El límite de un entrenamiento se aplica en backend por cuenta. Las consultas de ejercicios nunca exponen la clave del proveedor y se pueden limitar o pausar si la reserva de llamadas está cerca de agotarse; registrar/consultar datos propios de sesiones no depende de que MuscleWiki esté disponible. No restringir el catálogo a un bucket fijo de ejercicios.

El catálogo usa datos/video MuscleWiki en streaming, no GIF e imagen comprados por activo. El unlock permanente cubre funciones propias de Gym Rat; la tienda/paywall debe explicar que los medios externos dependen del API activo y pueden cambiar o retirarse. No exportar dataset, descargar/rehostear medios ni prometer acceso offline. No persistir snapshots de metadatos externos más de 30 días sin autorización específica.

## Funciones incluidas y criterios de aceptación

| Área / usuario | Resultado esperado | Criterio de aceptación del MVP |
|---|---|---|
| Cuenta y perfil — todos | Acceder a sus propios datos y controlar su cuenta. | Puede registrarse, iniciar/cerrar sesión, recuperar acceso y solicitar eliminación. Se muestran errores accionables y no se solicitan datos personales innecesarios. |
| Catálogo — todos | Encontrar un ejercicio disponible para su entrenamiento. | Lista y búsqueda paginadas consultan la API bajo demanda según acción del usuario; no existe semilla/manifiesto/bucket de ejercicios. En español con Growth. Free recibe solo metadata textual y no puede solicitar/recibir thumbnails, imágenes, bodymaps, videos ni tokens de medios. Detalle/video de usuarios desbloqueados solo bajo demanda y con atribución, sin almacenamiento prohibido. Agotamiento/error explicados. |
| Favoritos — desbloqueo completo | Volver rápidamente a ejercicios elegidos. | Puede marcar/desmarcar; cambios persisten en su cuenta y no producen duplicados. |
| Planes y calendario — desbloqueo completo | Preparar y consultar una rutina. | Puede crear/editar días, orden y ejercicios, y verlos en calendario. Editar el plan no altera sesiones completadas. Incluye plantilla inicial y estados vacíos. |
| Sesión y series — todos | Registrar el entrenamiento con ejercicio, series, repeticiones, carga y RPE opcional. | Puede iniciar desde un plan o en blanco, añadir/editar series, pausar, guardar y finalizar. Las entradas sobreviven navegación e interrupciones; los errores de red explican recuperación y no descartan datos guardados localmente. Free puede guardar una sesión por cuenta; al confirmar el guardado aparece inmediatamente la oferta y una segunda sesión requiere unlock. El SKU completo da acceso al historial propio completo. |
| Historial — todos | Consultar sesiones anteriores con valores fiables. | Sesiones completadas aparecen con fecha, ejercicios, series, repeticiones y cargas originales; orden estable por zona horaria. Free consulta la única sesión de vista previa; el desbloqueo muestra historial y permite registrar sesiones posteriores. |
| Progreso — desbloqueo completo | Entender cambios en sus propios levantamientos. | Récords y gráficas esenciales indican periodo, unidad y definición; cálculos concuerdan con ejemplos manuales; sin datos se explica cómo empezar. |
| Retos e insignias — desbloqueo completo | Reconocer hitos sin empujar a entrenar de forma insegura. | Reglas explícitas, reproducibles e idempotentes; no se premia entrenar con dolor, fatiga ni completar sesiones no verificables. El reto del MVP es individual; no hay ranking. |
| Desbloqueo y permisos — todos | Entender qué incluye el pago único y restaurar la compra. | Un SKU no consumible muestra precio total MXN y funciones/contenido concretos; $399 para primeras 100 compras o 60 días de beta, luego $699. Compra/restauración pasa por Apple o Google y backend valida antes de otorgar acceso permanente. Eventos duplicados son idempotentes. Web sin checkout. Oferta en consolas se configura/testea en GRT-003/GRT-026. |
| Sitio público — visitantes | Entender la propuesta y apuntarse a novedades/beta. | Página responsive, beneficios verificables, formulario con consentimiento, confirmación y baja/privacidad claras. Sin venta ni checkout web. |
| Backoffice — operadores autorizados | Administrar catálogo, contenido, retos, usuarios y estado de compra con acceso mínimo. | Roles y acciones protegidos; cambios e importaciones auditables; datos personales ocultos cuando no son necesarios. La sección financiera se limita a usuarios con rol financiero/admin. |
| Finanzas — operadores autorizados | Conciliar ingresos, liquidaciones y costos con trazabilidad. | Importación manual de CSV y captura de costos permiten revisar ventas, comisiones, impuestos/retenciones, reembolsos, efectivo liquidado y resultado del periodo. Reales, pendientes y proyecciones se distinguen; monedas no se suman sin conversión documentada. |

## Fuera del MVP

- Feed social, chat, grupos, rankings globales o retos grupales.
- Wearables, nutrición, generación de planes con IA y marketplace de coaches.
- Microservicios; se mantiene el monolito modular salvo motivo operativo aprobado.
- Checkout web, suscripciones Gym Rat, procesador de pago web, conexión directa a APIs de tiendas para el módulo financiero, pronósticos automáticos y contabilidad fiscal.
- Producir medios propios de ejercicios durante esta etapa.
- Iron Rat en el primer lanzamiento salvo que pase expresamente su gate de catálogo, licencia y demanda.
- Analítica que incluya nombres, cargas u otros datos de entrenamiento identificables.

## Métrica norte y métricas de guardia

### Métrica norte

**Usuarios activados que registran al menos tres sesiones completas en una ventana móvil de 14 días.** Una sesión cuenta cuando el usuario la finaliza y tiene al menos un ejercicio y una serie guardada. Se reporta como conteo y como proporción de usuarios activados elegibles; no se suman sesiones de prueba ni eventos duplicados.

Un usuario queda **activado** al completar su primera sesión guardada (la vista previa Free). Las métricas de repetición y conversión deben segmentarse por acceso Free/desbloqueado, para que el límite de una sesión Free no se interprete como abandono. El objetivo de facilidad de inicio es registrar una sesión con ejercicio, carga y repeticiones sin ayuda y en menos de tres minutos.

### Métricas de guardia

- **Retención de valor:** en piloto de dos semanas con al menos 10 usuarios del segmento, al menos 6 registran tres o más sesiones y al menos 6 consultan una sesión anterior antes de orientar su siguiente entrenamiento.
- **Integridad de sesiones:** cero pérdidas de sesiones confirmadas en los escenarios críticos de la revisión de beta; cualquier pérdida reproducible bloquea la salida.
- **Acceso y cobro:** cero casos conocidos de acceso Pro no autorizado, cobro duplicado atribuible a la app o compra cobrada sin reconciliación/estado visible. Los casos bloqueantes deben resolverse antes de ampliar una cohorte.
- **Estabilidad operativa:** errores de API, fallas de compra/restauración y tickets de soporte se revisan diariamente durante beta y lanzamiento controlado; cada incidente crítico tiene dueño y resolución antes de ampliar distribución.
- **Privacidad y seguridad:** no se registran datos personales o de entrenamiento en logs/analítica innecesarios; una vulnerabilidad crítica o acceso cruzado entre cuentas bloquea lanzamiento.
- **Derechos de contenido:** cero medios publicados sin autorización aplicable y plan/licencia vigente; atribución trazable por fuente; se comunica retiro/revocación posible y no se promete permanencia de medios de terceros.
- **Economía:** GRT-034 documenta costos reales/supuestos, nuevas ventas necesarias para equilibrio y runway de API recurrente, permiso comercial y criterio de reversión. No se declara rentabilidad basándose solo en el pago inicial del proveedor.

Los resultados de muestras pequeñas se informan con numerador, denominador y limitaciones; no se extrapolan como prevalencia del mercado.

## Criterios de salida

### Entrada y salida de beta cerrada

**Entrada:** build estable con alta, registro y guardado de sesión; consentimiento y soporte listos; cohortes reclutadas (15–30 personas objetivo); analítica mínima y sin datos sensibles; catálogo y medios limitados a lo autorizado.

**Salida positiva de beta:**

1. El flujo de la primera sesión permite guardar ejercicio, carga y repeticiones.
2. En piloto de dos semanas y muestra de al menos 10, se alcanzan los umbrales de retención de valor indicados arriba; si no se alcanza la muestra mínima, se registra como evidencia exploratoria y Producto decide si amplía piloto.
3. La revisión de flujos críticos no encuentra fallas bloqueantes de pérdida de sesiones, acceso indebido o cobro; fallos restantes tienen severidad, responsable y plan fechado.
4. Se entiende qué incluye el acceso gratuito y el pago único; participantes pueden encontrar cómo comprar/restaurar.

Si no se cumplen los puntos 1–3, no se amplía la beta: se corrige la causa o se recluta una muestra adicional cuando la evidencia sea insuficiente. No se compensa una falla de integridad, privacidad o pagos con buenos resultados de uso.

### Go/No-Go de lanzamiento

**Go** cuando se cumplan todos estos puntos:

1. Beta con salida positiva o una decisión documentada de Producto que explique datos, limitaciones y mitigación; sin fallos críticos abiertos de sesiones, privacidad, seguridad o pagos.
2. App y SKU no consumible aprobados/configurados en las tiendas para territorios previstos; compra, reembolso, restauración y reconciliación verificados en sandbox y estados de producción disponibles.
3. Entitlements concedidos exclusivamente por validación backend; la web pública no ofrece checkout.
4. El catálogo consultado y cada medio tienen procedencia, autorización/plan vigente y atribución revisables. El paywall/legales explican que la compra permanente cubre funciones Gym Rat, mientras que medios MuscleWiki dependen de la licencia y disponibilidad del API. Si faltan permisos, se excluyen esos medios.
5. Política de privacidad, términos, soporte, eliminación de cuenta, revisión de seguridad, respaldo/restauración, monitoreo y rollback están listos según las tarjetas de lanzamiento.
6. GRT-034 registra la decisión económica del desbloqueo, precio, costos API recurrentes, horizonte de 12/24 meses, punto de equilibrio y criterio de reversión.
7. Hay responsable de guardia y capacidad de detener adquisición/cohortes si fallan métricas de integridad, acceso, pagos o soporte.

Una aprobación de tienda pendiente impide el lanzamiento público de la app afectada. Puede hacerse un lanzamiento alternativo web/beta solo mediante decisión explícita con alcance, fecha y limitaciones documentadas; no se ocultan funcionalidades a revisión.

## Gate de crecimiento de catálogo y API

El crecimiento sigue las etapas Testing → Growth → Professional → Enterprise definidas en [ADR-004](../decisions/ADR-004-musclewiki-api-growth.md) y el [plan de negocio](business-plan.md). Testing se limita a integración/beta cerrada; Growth es el primer plan para producción en español. Subir por necesidad de funciones o si la cuota supera 70% durante dos meses; exigir reserva de caja para 12 meses +20%, validación contractual y aprobación GRT-034. La compra única de Gym Rat no se renueva ni genera MRR, aunque la API continúe facturándose mensualmente.

## Acceso permanente del comprador y dependencia API

El SKU de desbloqueo completo se venderá una sola vez como producto no consumible; el entitlement se valida en backend, se restaura entre dispositivos y solo se revoca por reembolso/contracargo confirmado conforme a reglas de tienda. Sin embargo, el video y metadatos MuscleWiki dependen del plan API activo y de la licencia revocable del proveedor. Los términos revisados documentan uso comercial en planes pagados para la app propia; antes del lanzamiento, Legal revisará alcance, revocación, cambio de tarifa y terminación. Si detecta ambigüedad material sobre el acceso de compradores únicos, solicitar aclaración escrita al proveedor. Definir contingencia. No prometer descarga/offline ni acceso permanente a archivos de tercero.

- Mostrar precio MXN, pago único y funciones/contenido incluidos antes de comprar.
- Mantener clave API en Laravel/backend; cumplir streaming, caché y atribución de MuscleWiki.
- Financiar la cuota con caja real y proceeds netos de nuevas compras; medir runway de 12/24 meses, no MRR.
- Medir proceeds netos frente a comisiones de tienda, impuestos, reembolsos, CAC, soporte, infraestructura y cuota API para confirmar sostenibilidad del precio aprobado; cualquier cambio del precio requiere nueva decisión de Producto.

## Regla de recorte por retraso

Si el calendario o caja se desvían, recortar en este orden: catálogo/API de pago superior a Growth; gráficas avanzadas; retos/insignias adicionales; automatizaciones no esenciales. Mantener registro e historial fiable, seguridad, privacidad, derechos, compra in-app y preparación de revisión de tiendas. Un recorte debe registrar función, impacto, responsable y fecha de reevaluación; no reducir QA ni seguridad de pagos.

## Dependencias y decisiones relacionadas

- Segmento, problema e hipótesis: [`brief.md`](brief.md) (GRT-001).
- Niveles, precios, comisiones y gate de catálogo: [`pricing-hypotheses.md`](pricing-hypotheses.md) (GRT-002, GRT-034).
- Compra in-app y fuente de verdad de entitlements: [`ADR-003-one-time-full-unlock.md`](../decisions/ADR-003-one-time-full-unlock.md) (GRT-003).
- Licencias, atribución y procedencia: [`exercise-source-register.md`](../data/exercise-source-register.md) (GRT-004).
- Criterios de beta, pagos, tiendas y salida: GRT-031 a GRT-040 en [`trello-backlog.md`](../trello-backlog.md).

## Criterio de cierre de GRT-005

El alcance vendible, funciones y aceptación, exclusiones, métricas, gates de beta/lanzamiento y regla de recorte están documentados aquí. Las reglas Free/desbloqueo aprobadas en GRT-002 y las condiciones de acceso a medios/API están incorporadas sin bucket fijo. Los cambios posteriores de precios, territorios, derechos o pagos requieren actualizar este documento y decisiones dependientes antes de implementación/publicación.
