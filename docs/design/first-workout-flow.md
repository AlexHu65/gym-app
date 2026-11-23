# GRT-006 — Flujo y prototipo del primer entrenamiento

**Estado:** prototipo clicable listo para revisión; prueba moderada pendiente.  
**Fecha:** 27 de octubre de 2026.  
**Responsables:** Diseño / Producto.  
**Prototipo Lovable:** [Gym Rat First Workout — vista previa](https://id-preview--926b4a43-7e2c-4b84-a215-a8530d27dcad.lovable.app) · [editar en Lovable](https://lovable.dev/projects/926b4a43-7e2c-4b84-a215-a8530d27dcad)

## Objetivo y criterio de éxito

Una persona nueva del segmento prioritario debe poder iniciar y guardar su primera sesión con al menos un ejercicio, carga y repeticiones en menos de tres minutos, sin ayuda. La hipótesis de GRT-001/MVP requiere que al menos 4 de 5 participantes lo consigan en una prueba moderada. Este documento y el prototipo preparan esa prueba; no reportan resultados de usuarios.

## Usuario, tarea y dispositivos

- **Usuario:** levantador amateur intermedio, socio de un gimnasio independiente, que quiere recordar qué hizo y volver a consultar sus cargas.
- **Tarea primaria:** desde la bienvenida, registrar una sesión libre con un ejercicio, una serie, carga en kg y repeticiones, y dejarla guardada.
- **Tarea alternativa:** comenzar desde un plan sugerido; si no tiene uno, continuar en blanco sin bloqueo.
- **Dispositivos objetivo del prototipo:** teléfono, vertical, viewport mínimo de 360 × 800 (Android) y referencia de 390 × 844 (iPhone). La sesión se prueba con una mano, a brillo bajo/medio, simulando interrupciones breves.
- **Supuestos de diseño por validar:** kg como unidad inicial visible y selección de ejercicios mediante búsqueda o lista; la unidad preferida y el comportamiento real en gimnasio no están resueltos por el PRD.

## Recorrido principal

1. **Bienvenida breve:** explica el diario de entrenamiento en una frase; CTA visible para empezar. El alta/autenticación no se interpone en el prototipo ni en la primera captura.
2. **Inicio:** CTA principal «Registrar entrenamiento»; alternativa «Elegir un plan» y opción clara de empezar en blanco. Si no hay plan, explicar que se puede añadir un ejercicio directamente.
3. **Elegir ejercicio:** buscar o escoger un ejercicio del catálogo autorizado. El prototipo usa nombres de muestra y no incorpora GIF, imágenes ni contenido de terceros.
4. **Registrar serie:** mostrar ejercicio y fila de serie con carga (kg) y repeticiones; RPE permanece opcional y secundario. Permitir añadir/editar series y añadir otro ejercicio. Señalar errores junto al campo que los causa.
5. **Guardar y terminar:** indicar el estado real del guardado; exigir al menos un ejercicio y una serie válida para finalizar. El resumen confirma ejercicios/series guardados y ofrece volver al inicio.

**Ruta del prototipo:** bienvenida → inicio → registro libre → selector de ejercicio → captura de serie → resumen. La alternativa desde plan converge en captura de serie. El prototipo debe permitir volver, editar y reanudar sin reconstruir la sesión.

### Implementación actual en Lovable

El prototipo incluye bienvenida, inicio, planes, selector de ejercicio con 12 nombres de muestra, captura de sesión y resumen. La sesión permite buscar/filtrar, registrar y editar series, añadir ejercicios/series, ver validación junto a la entrada, guardar, pausar/reanudar y terminar solo después de registrar una serie válida. Un control «Prototipo» da acceso a atajos de pantallas, sin plan, búsqueda vacía, modo sin conexión/sincronización pendiente, error recuperable, interrupción y reinicio.

El selector muestra junto a «Press de banca» una ilustración SVG original de muestra; se reutiliza al lado del ejercicio en la sesión. Lleva la etiqueta «Imagen de muestra · no es contenido final» y una nota para lectores de pantalla. El resto del catálogo usa iniciales. No se incorporaron imágenes externas ni medios de terceros; esta ilustración solo comunica la ubicación y tratamiento visual del espacio en el prototipo, no es un recurso final para el catálogo comercial.

Datos y estados son simulados en memoria de la pestaña; no hay backend, autenticación, pagos ni analítica. Recargar la pestaña borra la sesión demo. El texto del prototipo explica esta limitación.

**Comprobación reportada por el agente de Lovable:** recorrido feliz en viewport 360 × 800 (Press de banca, 60 kg × 8, guardar, terminar y abrir resumen), chequeo de tipos sin errores y revisión visual de la ilustración en el selector y la sesión, sin errores de consola. El viewport 390 × 844 y los estados alternativos no se revisaron uno por uno; esto no sustituye la prueba con participantes.

## Estados y mensajes requeridos

| Estado | Comportamiento/copy orientativo | Recuperación |
|---|---|---|
| Sin plan | «Aún no tienes un plan. Puedes empezar en blanco y añadir ejercicios.» | CTA «Empezar en blanco» y «Elegir ejercicio». |
| Búsqueda sin resultados | «No encontramos ese ejercicio.» | Limpiar búsqueda, cambiar filtro o regresar; nunca dejar una pantalla vacía sin salida. |
| Entrada inválida/incompleta | Marcar el campo: «Escribe repeticiones» / «Usa un valor de 0 o mayor». | Mantener lo escrito y permitir corregir. No habilitar completar hasta tener una serie válida. |
| Sin conexión | Diferenciar captura local y sincronización pendiente: «Sin conexión. La sesión está guardada en este dispositivo y se sincronizará al reconectar» solo si la persistencia local está confirmada. | «Reintentar sincronización»; mantener estado visible. |
| Interrupción | Avisar que hay una sesión sin terminar y ofrecer reanudar o descartar con confirmación. | Reanudar donde quedó. Descartar requiere acción explícita. |
| Error recuperable al guardar | «No se pudo confirmar el guardado. Conservamos tus cambios aquí; intenta de nuevo» solo si efectivamente se conservaron. | Reintentar; no navegar al resumen ni afirmar éxito hasta confirmar. |
| Guardado confirmado | «Sesión guardada» con resumen de datos. | Ver resumen o volver al inicio. |

En el prototipo de Lovable los datos son de demostración y viven en memoria del navegador; pueden perderse al recargar o cerrar. Los avisos de guardado/sin conexión son simulados y no prueban almacenamiento durable, sincronización ni recuperación real. El UI debe identificar esta limitación en «Acerca de este prototipo» y evitar presentar el prototipo como producto funcional.

## Límites de alcance y tono

- Español de México, frases breves y controles fáciles de tocar con una mano.
- Dirección de marca: gimnasio clásico/garage, carbón y papel cálido con acento naranja; contraste alto y tipografía de lectura clara.
- Sin medios de ejercicios sin licencia, pagos, upsell, feed, rankings, streaks ni promesas físicas.
- Celebrar el registro y la constancia sin avergonzar ni presionar a entrenar con dolor, fatiga o lesión.
- No enviar nombres, cargas, repeticiones ni otros datos de entrenamiento a analítica.

## Guion de prueba moderada (5 participantes)

### Preparación

Reclutar cinco levantadores amateur intermedios que entrenen en gimnasios independientes. Probar el prototipo en teléfono; obtener consentimiento para tomar notas. No pedir nombre completo ni datos de salud. Reiniciar el prototipo entre sesiones.

### Introducción neutral

«Estamos revisando esta experiencia, no evaluándote a ti. Piensa en voz alta y usa la app como lo harías en el gimnasio. Si algo no se entiende, dímelo; no te ayudaré salvo que te quedes bloqueado. Esta es una demostración, así que no guarda datos reales».

### Tareas

1. «Acabas de abrir Gym Rat. Empieza a registrar lo que hiciste hoy».
2. «Añade Press de banca y registra una serie de 60 kg por 8 repeticiones».
3. «Guarda y termina la sesión; muéstrame qué quedó registrado».
4. En una vuelta aparte, pide que encuentre qué hacer si no tiene un plan o no aparece un ejercicio.
5. Activa el estado de interrupción o sin conexión y pregunta qué cree que ocurrirá con sus datos.

No revelar etiquetas/controles o sugerir el siguiente paso. Dar hasta tres minutos a la tarea primaria; si pide ayuda o recibe una pista, contar como asistida.

### Registro por participante

| ID anónimo | Completó ejercicio + kg + reps | Tiempo (s) | Ayuda/pistas | Error o dato perdido | Fricción observada / cita breve |
|---|---|---:|---|---|---|
| P1 | Pendiente | — | — | — | — |
| P2 | Pendiente | — | — | — | — |
| P3 | Pendiente | — | — | — | — |
| P4 | Pendiente | — | — | — | — |
| P5 | Pendiente | — | — | — | — |

### Decisión posterior a la prueba

- **Métrica:** contar participantes que completan tarea primaria sin ayuda en menos de 180 s; reportar numerador y denominador (objetivo mínimo 4/5), tiempos y limitación de muestra.
- **Fricciones:** registrar paso/pantalla, evidencia, severidad (bloqueante, alta, media, baja) y frecuencia observada; separar observación de interpretación.
- **Priorización:** corregir primero pérdida/confusión de datos, bloqueos de finalización y errores de navegación; después reducir pasos y ajustar lenguaje/jerarquía visual.
- No congelar interacción hasta probar con cinco personas y documentar los cambios/prioridades. Si no se alcanza el objetivo, revisar el flujo y repetir con una muestra nueva.

## Artefactos, dependencias y pendientes

- PRD relacionado: [`docs/product/prd/01-entrenamiento-y-progreso.md`](../product/prd/01-entrenamiento-y-progreso.md).
- Alcance y umbrales de beta: [`docs/product/mvp-scope.md`](../product/mvp-scope.md).
- Dependencias de tarjeta: GRT-001 y GRT-005 completadas según backlog.
- **Pendiente para cierre:** ejecutar la prueba moderada con cinco usuarios, completar la tabla, registrar fricciones y cambios, y actualizar la tarjeta Trello con este documento, el prototipo y el resultado. No declarar GRT-006 Hecho hasta cubrir esa aceptación.
- **Dependencia posterior:** GRT-011 debe convertir la dirección visual validada en sistema visual; confirmar unidades/zona horaria y requisitos de persistencia con las tarjetas de entrenamiento antes de implementar.
