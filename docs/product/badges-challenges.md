# GRT-007 — Catálogo inicial de insignias, reto y RPE

**Estado:** definición de producto aprobada para GRT-007 el 5 de noviembre de 2026. Reglas y ejemplos definidos. La prueba de comprensión/copy en GRT-006 queda como validación de interfaz, no como bloqueo para cerrar este entregable.  
**Alcance:** retos individuales e insignias visibles solo para su titular. No hay tabla pública, comparación entre usuarios ni premio material.

## Principios

- Reconocer el registro y la exploración del propio progreso; no prescribir cuánto debe entrenar una persona.
- Nunca premiar intensidad, cargas máximas, volumen extremo, entrenar en días consecutivos ni ignorar dolor/fatiga.
- Descansar, pausar un reto o no participar no causa pérdida de acceso, penalización ni mensajes de vergüenza.
- Una sesión cuenta solo cuando está completada y contiene al menos un ejercicio y una serie guardada válida. Borradores y sesiones pausadas no cuentan.
- El usuario puede ver el requisito, el avance, el periodo y la razón de otorgamiento.

## Definiciones comunes

- **Sesión elegible:** sesión propia en estado `completada`, con al menos un ejercicio y una serie persistida, no eliminada. Las sesiones de prueba/demostración no cuentan.
- **Fecha local:** `completed_at` de la sesión convertido a la zona IANA del usuario. La fecha no depende de cuándo sincronizó el dispositivo.
- **Semana:** lunes 00:00:00 inclusive a lunes siguiente 00:00:00 exclusivo, en la zona IANA guardada en el perfil. Mostrar fechas locales y abreviatura de zona. Si cambia la zona, se aplica desde la semana siguiente; las semanas ya iniciadas conservan la zona con que comenzaron. Si no hay zona válida, usar `America/Mexico_City` y permitir corregirla en perfil.
- **Sesión retroactiva:** puede contar si su hora de finalización pertenece al periodo y se sincroniza dentro de los 7 días posteriores al cierre. Después se conserva en historial, pero no cambia retos/insignias cerrados; mostrar que llegó tarde si afecta el resultado.
- **Corrección:** al editar una sesión completada se vuelve a evaluar progreso afectado. Cambiar notas/carga/RPE no cambia elegibilidad; quitar el único ejercicio o la única serie válida sí.
- **Eliminación:** sesión eliminada deja de contar. El premio provisional se revoca si deja de cumplirse su regla; se conserva un evento de auditoría sin exponerlo a otros usuarios. Una insignia se puede volver a ganar si la condición vuelve a cumplirse.
- **Idempotencia:** clave lógica `(user_id, rule_version, period_or_lifetime, milestone)`; procesar el mismo evento varias veces conserva un solo otorgamiento. Toda regla tiene versión. Al desactivar una regla se detienen nuevos otorgamientos sin borrar los ya obtenidos.

## Insignias iniciales

Todas son hitos personales, no niveles competitivos. `S` significa sesión elegible. Los conteos son acumulados durante la vida de la cuenta y se calculan sobre sesiones no eliminadas.

| ID | Nombre | Regla verificable | Copy al obtener | Ejemplo |
|---|---|---|---|---|
| B01 | Primer registro | Completar 1 S. | “Primer entrenamiento guardado. Ya tienes un registro para consultar.” | 1 sesión válida → sí; solo borrador → no. |
| B02 | Volviste al registro | Completar 3 S en días locales distintos. | “Tres días con entrenamiento registrado. Tu historial empieza a tomar forma.” | 3 sesiones en 3 días → sí; 3 en un solo día → no. |
| B03 | Semana documentada | Completar 2 S en días locales distintos dentro de una semana. | “Semana documentada. Buen trabajo llevando tu registro.” | Lunes y jueves → sí; dos sesiones el jueves → no. |
| B04 | Constancia en el tiempo | Completar al menos 1 S en cada una de 3 semanas consecutivas. | “Tres semanas con tu entrenamiento en el registro.” | Una sesión por semana en semanas contiguas → sí; una semana vacía → reinicia la secuencia. |
| B05 | Explorador del catálogo | Registrar 5 ejercicios distintos en sesiones elegibles. | “Ya registraste cinco ejercicios distintos.” | 5 IDs distintos → sí; repetir el mismo 5 veces → no. |
| B06 | Progreso registrado | En un ejercicio, guardar una serie con carga y repeticiones válidas en 3 sesiones distintas. No exige superar marcas. | “Tienes tres registros para comparar en este ejercicio.” | Mismo ejercicio en 3 sesiones → sí, aunque la carga no aumente. |

Los ejercicios se identifican por ID canónico, no por texto libre. B05/B06 solo usan ejercicios del catálogo o entradas manuales persistidas que tengan identidad estable; si el MVP no ofrece esa identidad, diferir estas insignias, sin inferir equivalencias por nombre.

## Reto semanal del MVP

**Nombre:** “Registra tu semana”. **Objetivo:** completar 2 sesiones elegibles en días locales distintos durante la semana. Es una invitación de registro, no una recomendación de entrenamiento ni una obligación de frecuencia.

- **Participación:** automática para usuarios Pro; no requiere aceptar, y se puede ocultar/pausar desde ajustes sin penalización. Para cuentas sin función Pro, no mostrarlo.
- **Progreso:** contar como máximo una vez cada sesión elegible; mostrar `0/2`, `1/2` o `2/2` y los días locales que cuentan. El mismo día solo aporta una sesión al objetivo.
- **Éxito:** al completar la segunda sesión elegible en un día distinto, marcar `completado`. Se otorga el reconocimiento semanal una sola vez por usuario y semana; sin puntos, rachas ni recompensa de acceso.
- **Cierre:** semana termina en el límite definido arriba. Conservar el resultado de periodos cerrados. La llegada dentro de la ventana retroactiva de 7 días puede actualizar un periodo cerrado; después no.
- **No cumplimiento:** estado “Semana cerrada” con el conteo alcanzado; no usar “fallaste”, reinicios punitivos ni pérdida de racha porque la racha no es parte del reto.
- **Cambios/eliminación:** reevaluar antes del cierre y durante la ventana retroactiva; si una sesión deja de ser elegible, descontarla. Si el reto ya había completado, retirar el estado completado si cae bajo 2. Conservar historial auditable de concesión/revocación.
- **Copy:** inicio: “Registra 2 entrenamientos en días distintos esta semana. Descansar también forma parte de tu entrenamiento.” Progreso: “Llevas {n} de 2 días registrados.” Completo: “Reto completado: registraste 2 días esta semana.”

## RPE (esfuerzo percibido)

RPE es la valoración subjetiva de esfuerzo de una serie por la persona, no una medición objetiva ni indicación médica. Es opcional por serie y nunca condiciona completar sesión, reto o insignia.

| Valor | Anclaje mostrado |
|---|---|
| 1–2 | Muy fácil |
| 3–4 | Fácil |
| 5–6 | Moderado |
| 7–8 | Difícil |
| 9 | Muy difícil |
| 10 | Esfuerzo máximo percibido |

Permitir valores enteros 1–10 o vacío; no preseleccionar valor. Texto de ayuda: “¿Qué tan difícil se sintió esta serie? Es tu percepción; puedes dejarlo en blanco.” No usar colores/copy que juzguen el valor ni recomendar subir carga. RPE 10 no produce insignia ni felicitación especial. Cero, decimales y valores mayores a 10 se rechazan con error junto al campo; al quitar el valor se guarda como nulo. El usuario puede corregirlo después; no se usa para retos ni recompensas.

## Casos de entrada/salida para aceptación

1. Una sesión completada con ejercicio y serie válida cuenta una vez, incluso si el evento se procesa repetidamente.
2. Una sesión pausada, borrador, eliminada o completada sin serie válida no cuenta.
3. Dos sesiones elegibles en fechas locales distintas de la misma semana completan el reto; dos en el mismo día dejan el progreso en `1/2`.
4. La semana cambia el lunes a medianoche de la zona congelada para esa semana; cambiar zona el miércoles no mueve sus sesiones a otra semana.
5. Una sesión retroactiva sincronizada al día 5 tras cierre actualiza el periodo; sincronizada al día 8 solo aparece en historial.
6. Eliminar la única sesión que hacía alcanzar el umbral revoca el logro afectado; restaurar/recrear elegibilidad lo vuelve a conceder una sola vez en su estado actual.
7. Un RPE vacío no bloquea guardado; `0`, `10.5` y `11` se rechazan; `1` y `10` se guardan sin efectos en premios.

## Privacidad, cuenta y administración

- Solo el usuario autenticado consulta sus avances. No hay perfil público, feed, ranking ni endpoint de comparación.
- Al eliminar la cuenta, borrar los datos personales y avances conforme a la política de retención/eliminación de cuenta; no mantener insignias identificables. Las métricas agregadas solo podrán conservarse si son realmente anónimas y la política lo permite.
- Administración puede editar copy para futuras versiones y desactivar reglas. Cambiar una fórmula requiere nueva versión, fecha de vigencia y migración/recalculo documentado; no reescribir silenciosamente premios previos.
- No almacenar RPE en eventos analíticos agregados ni usarlo para inferir salud, fatiga o perfil de usuario.

## Dependencias y pendientes de validación

- GRT-005 define elegibilidad Free/Pro y GRT-006 debe validar comprensión del RPE, copy, estados y zona horaria con usuarios antes de congelar UI.
- Confirmar que el perfil captura zona IANA; si no, añadirlo al diseño de cuenta antes de GRT-019/020.
- B05/B06 dependen de identidad estable de ejercicios/series prevista por GRT-010/016.
- Esta propuesta no sustituye política de eliminación/retención de cuenta. Los datos de reto no deben contradecirla.

## Fuente

GRT-007 en [`trello-backlog.md`](../trello-backlog.md), [`PRD-04`](prd/04-retos-e-insignias.md), [`PRD-01`](prd/01-entrenamiento-y-progreso.md), [`mvp-scope.md`](mvp-scope.md).
