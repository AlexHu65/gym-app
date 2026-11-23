# PRD-04 — Retos e insignias

**Estado:** definición de producto de GRT-007 aprobada el 5 de noviembre de 2026 en [`badges-challenges.md`](../badges-challenges.md). GRT-006 valida comprensión/copy de interfaz; confirmar captura de zona IANA e identidad estable de ejercicios durante diseño de datos/flujo.  
**Objetivo:** reflejar constancia y progreso individual con reglas verificables, sin presionar a entrenar cuando no conviene.

## Alcance

- Conjunto inicial de 5–8 insignias vinculadas a hitos verificables con datos que ya recoge el MVP.
- Un reto individual semanal con objetivo de sesiones completadas; sin grupos ni ranking.
- Reglas reproducibles con periodo y zona horaria definidos, elegibilidad clara y tratamiento de sesiones corregidas/eliminadas.
- Otorgamiento idempotente; una misma condición no crea duplicados.
- Mostrar nombre, explicación, avance, periodo y estado vacío/no elegible.

## Requisitos y aceptación

| ID | Requisito | Aceptación |
|---|---|---|
| PROG-01 | Regla explícita | Cada reto/insignia tiene fórmula o condición, periodo, fuente de datos, límites y ejemplos entrada/salida documentados antes de activarse. |
| PROG-02 | Verificabilidad | Solo se cuentan sesiones completadas con al menos un ejercicio y una serie guardada. |
| PROG-03 | Idempotencia | Reprocesar eventos o sincronizar varias veces no crea premios repetidos. |
| PROG-04 | Seguridad | El copy no elogia dolor, fatiga, volumen extremo ni entrenar todos los días; el usuario puede descansar sin perder acceso o ser avergonzado. |
| PROG-05 | Transparencia | El usuario puede entender qué acción cuenta y por qué recibió o no recibió el reconocimiento. |
| PROG-06 | Privacidad | Progreso visible solo al usuario; no hay tabla pública ni comparación global. |

## Reglas

El catálogo, umbrales, periodo semanal, zona horaria, sesiones retroactivas, correcciones/eliminaciones, RPE, idempotencia, copy y ejemplos de entrada/salida están aprobados y especificados en [`badges-challenges.md`](../badges-challenges.md). La interfaz debe pasar validación de comprensión con usuarios en GRT-006.

## Dependencias

GRT-005, GRT-006, GRT-007, GRT-016, GRT-019 y GRT-020.

## Fuentes

[`mvp-scope.md`](../mvp-scope.md), GRT-007, GRT-019–020.
