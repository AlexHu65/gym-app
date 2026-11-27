# PRD-01 — Entrenamiento y progreso

**Estado:** borrador basado en brief y alcance MVP.  
**Usuarios:** levantadores amateur intermedios; cuenta individual.  
**Objetivo:** permitir registrar una sesión en el gimnasio con pocos pasos y consultar después un historial fiel.

## Problema y resultado

En las 10 entrevistas iniciales, alrededor de 8 personas no registraban sus cargas o no mantenían un registro adecuado. Es una señal de esa muestra, no una estimación de mercado. El producto debe ayudar a recordar ejercicios, series, repeticiones, carga y esfuerzo sin añadir fricción a la sesión.

**Resultado esperado:** una persona nueva puede guardar su primera sesión con ejercicio, carga y repeticiones en menos de tres minutos y sin ayuda, como objetivo de diseño de GRT-006.

## Alcance funcional

- Alta, inicio/cierre de sesión, recuperación de acceso y acceso a datos propios.
- Registrar una sesión desde un plan o en blanco; agregar ejercicios y series; editar entradas; guardar, pausar y finalizar.
- Una serie registra repeticiones y carga; RPE es opcional y representa autopercepción.
- Planes Pro editables por días, orden y ejercicios, con calendario y plantilla inicial.
- El historial muestra sesiones completadas y sus valores originales. Editar una rutina nunca altera una sesión ya completada.
- Free conserva únicamente su sesión de vista previa guardada; al guardar se presenta el paywall. El desbloqueo permite historial completo, récords personales y gráficas esenciales.
- Estados sin plan, sin ejercicios, sin historial, carga/error de red y recuperación de una sesión interrumpida.

## Requisitos y aceptación

| ID | Requisito | Aceptación |
|---|---|---|
| TR-01 | Captura rápida | Desde inicio se puede iniciar en blanco o desde plan, añadir ejercicio/serie y guardar sin perder entradas al navegar o interrumpir la app. |
| TR-02 | Datos de serie | Se persisten ejercicio, orden, repeticiones, carga/unidad y RPE opcional; validación señala el campo y permite corregirlo. |
| TR-03 | Sesión completada | Una sesión completada requiere al menos un ejercicio y una serie guardada; la fecha se presenta estable según zona horaria. |
| TR-04 | Historial inmutable | Cambiar plan/ejercicio posteriormente no modifica datos de sesiones completadas. |
| TR-05 | Límites Free/Pro | Free ve la sesión única de vista previa; después del guardado ve la oferta de compra. La interfaz comunica qué está limitado y cómo acceder. |
| TR-06 | Progreso | Récords/gráficas indican periodo, unidad y definición; sin datos explica cómo empezar; resultados coinciden con ejemplos calculados manualmente. |
| TR-07 | Sin conexión/interrupción | Mensajes explican si el dato está guardado localmente, pendiente de sincronizar o requiere acción; nunca afirman guardado si no ocurrió. |
| TR-08 | Acceso entre cuentas | Una cuenta no puede consultar ni modificar sesiones de otra. |

## Métrica y eventos

- Activación: primera sesión guardada.
- Métrica norte: usuarios activados con al menos tres sesiones completas en 14 días, con conteo y proporción sobre usuarios activados elegibles.
- Eventos mínimos de analítica: registro, primera sesión, sesión completada y errores. No incluir nombre, carga, repeticiones ni otro dato de entrenamiento identificable.

## Fuera de alcance

Feed, rankings, wearables, nutrición, generación de rutinas con IA, coaching/marketplace, analítica de datos de entrenamiento identificables.

## Dependencias y pendientes

- GRT-006: prototipo clicable y flujo del primer entrenamiento documentados.
- GRT-010/012/015–018: esquema, cuenta, planes, sesión, historial y progreso.
- Confirmar elección de unidades y zona horaria en el diseño detallado; no hay política explícita en los documentos fuente.

## Fuentes

[`brief.md`](../brief.md), [`mvp-scope.md`](../mvp-scope.md), GRT-006, GRT-010, GRT-012, GRT-015–018.
