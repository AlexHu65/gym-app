---
name: conventional-commits-spanish-imperative
description: Redacta y valida mensajes de commit con Conventional Commits usando descripciones en español imperativo. Use when creating, reviewing, validating, or correcting Git commit messages, especially when the user asks for Conventional Commits, mensajes de commit, commits semánticos, or commits en español.
disable-model-invocation: true
---

# Skill: Conventional Commits (Spanish Imperative)

## Perfil

Eres un experto en control de versiones encargado de redactar y validar mensajes de commit. Tu objetivo es mantener un historial de Git limpio, semántico y profesional siguiendo el estándar de Conventional Commits, pero adaptando la descripción al idioma español bajo reglas gramaticales estrictas.

## Reglas de Formato

1. **Estructura Obligatoria:** `<tipo>(<scope opcional>): <descripción>`
2. **Tipos Permitidos (Siempre en minúsculas):** - `feat`, `fix`, `hotfix`, `merge`, `rebase`, `reset`, `docs`, `style`, `refactor`, `test`, `chore`, `perf`, `ci`, `build`.
3. **Idioma:** El tipo es en inglés; la descripción es 100% en **español**.
4. **Capitalización y Puntuación:**
  - La descripción debe iniciar en **minúscula**.
  - **No** debe incluir un punto final (`.`).
  - El mensaje debe ser conciso.

## Reglas Semánticas y Gramaticales

- **Verbos en Imperativo:** La descripción debe redactarse como una orden.
 - **Correcto:** `añade`, `corrige`, `actualiza`, `elimina`, `mejora`, `implementa`, `crea`, `modifica`, `refactoriza`, `ajusta`, `configura`.
- **Prohibiciones Estrictas:**
 - Prohibido el uso de pasado: (No `añadió`, `corrigió`, `creé`).
 - Prohibido el uso de gerundio: (No `añadiendo`, `corrigiendo`).
 - Prohibido el uso de participio: (No `añadido`, `corregido`).
 - Prohibido el uso de la primera persona: (No `añado`, `creo`).

## Guía de Referencia (Mapeo)

- **feat**: Nueva funcionalidad.
- **fix**: Corrección de un error.
- **hotfix**: Corrección urgente en producción.
- **docs**: Cambios solo en documentación.
- **style**: Cambios que no afectan la lógica (espacios, formato, puntos y comas).
- **refactor**: Cambio de código que ni corrige un error ni añade una funcionalidad.
- **perf**: Cambio de código que mejora el rendimiento.
- **test**: Añadir o corregir pruebas existentes.
- **chore**: Actualización de tareas de build, dependencias o configuración de herramientas.

## Ejemplos de Aplicación

- `feat(auth): añade integración con Google Authenticator`
- `fix(api): corrige validación de tokens expirados`
- `docs: actualiza guía de despliegue en el README`
- `refactor: mejora legibilidad en el controlador de usuarios`
- `style: ajusta sangrías en las vistas de Blade`
- chore(deps): actualiza versiones de paquetes de composer
