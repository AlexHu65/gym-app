# Plan de beta

Objetivo: validar el MVP con usuarios reales antes de invertir en features post-MVP.

| Fase | Fechas | Audiencia |
|------|--------|-----------|
| Beta cerrada | 12–31 dic 2026 | 5–15 invitados conocidos |
| Beta abierta | 1–31 ene 2027 | registro publico controlado |

Capacidad: 1 persona + Cursor. Feature freeze de alcance nuevo desde el **12 dic 2026** (solo bugs y P0).

Calendario maestro: [`roadmap.md`](roadmap.md).

## Que debe funcionar (MVP)

### Usuario final

- Registro / login
- Explorar catalogo con busqueda y filtros
- Ver detalle de ejercicio (media + instrucciones)
- Guardar y quitar favoritos
- Crear una rutina simple
- Registrar al menos una sesion de entrenamiento
- Cambiar idioma basico del contenido disponible

### Equipo interno (admin)

- Login de administracion
- Listar / crear / editar ejercicios
- Editar traducciones
- Publicar / despublicar (estados draft → published)
- Subir o asociar imagen/GIF
- Ver auditoria basica de cambios criticos

### Operacion

- Staging desplegado y documentado
- Catalogo importado desde `exercises-dataset` sin errores bloqueantes
- Backups basicos de Postgres en staging
- Logs revisables ante fallos

## Fuera de beta (no bloquear lanzamiento)

- IA, wearables, pagos, social, video en vivo, gamificacion
- App store nativa (PWA basta)
- Analitica avanzada (eventos basicos si, BI no)
- Workflow editorial con muchos roles (basta editor + super-admin)

## Beta cerrada (dic 2026)

### Objetivo

Encontrar bugs de recorrido completo y friccion de UX con gente de confianza, sin ruido publico.

### Audiencia

- 5–15 personas: amigos, colegas, posibles usuarios de gym
- Preferible mezcla: principiante + intermedio
- Acceso solo por invitacion (lista de emails o enlace no publico)

### Checklist de entrada (go / no-go)

- [ ] `GET /api/v1/exercises` responde en staging con datos reales
- [ ] Login usuario y admin funcionan
- [ ] Favoritos create/delete OK
- [ ] Crear 1 rutina y 1 sesion OK en client
- [ ] Admin puede publicar un ejercicio de prueba
- [ ] No hay P0 abiertos (crash de login, catalogo vacio, perdida de datos)
- [ ] Instrucciones de acceso enviadas a invitados
- [ ] Canal de feedback listo (formulario, Discord, o issues privadas)

### Durante la beta cerrada

- Sesiones de feedback cortas (15–20 min) o formulario estructurado
- Triage diario: P0 mismo dia, P1 en el sprint, P2 backlog post-beta
- No agregar features pedidas “nice to have”

### Checklist de salida hacia beta abierta

- [ ] Al menos 5 testers completaron el recorrido minimo (buscar → favorito → rutina → sesion)
- [ ] P0 = 0
- [ ] P1 criticos de UX corregidos o documentados con workaround
- [ ] Onboarding minimo escrito (como registrarse, que probar)
- [ ] Monitoreo basico activo (errores 5xx / logs)

## Beta abierta (ene 2027)

### Objetivo

Abrir el producto a registro controlado, medir uso real y estabilizar operacion.

### Audiencia

- Registro publico con aviso de “beta”
- Capacidad de pausar registros si la infra o el soporte se saturan
- Comunicacion clara: puede haber cambios y resets de datos de staging/prod beta

### Criterios de lanzamiento (1 ene 2027)

- [ ] Checklist de salida de beta cerrada cumplido
- [ ] Landing / mensaje de beta visible
- [ ] Politica simple de datos (que se guarda, contacto)
- [ ] Limite o rate limit basico en auth y APIs publicas
- [ ] Plan de rollback / mantenimiento documentado en una pagina

### Metricas minimas (semanal)

| Metrica | Para que sirve |
|---------|----------------|
| Registros nuevos | Demanda / friccion de signup |
| Ejercicios vistos | Engagement de catalogo |
| Favoritos creados | Intencion de uso |
| Sesiones completadas | Valor de entrenamiento |
| Retencion D7 | Si vuelve la gente |
| Errores 5xx / crashes | Salud tecnica |

No hace falta un stack de analytics completo: eventos basicos en backend o un servicio ligero bastan.

### Durante enero 2027

- Freeze de features nuevas de alcance MVP+
- Solo bugs, seguridad, performance y copy/onboarding
- Revisar metricas cada viernes
- Recoger feedback publico en un solo canal

### Criterios de salida de la beta abierta

La beta abierta “cierra” (pasa a producto estable o siguiente fase) cuando:

- [ ] Uptime de staging/prod beta estable la ultima semana (>= 99% practico en horario de uso)
- [ ] Errores criticos por debajo de un umbral acordado (ej. < 1% de requests 5xx en rutas core)
- [ ] Feedback canalizado (no solo DMs dispersos)
- [ ] Backlog post-beta priorizado (top 10)
- [ ] Decision documentada: seguir iterando MVP, monetizar despues, o pausar

## Roles durante la beta

| Rol | Responsable (solo) |
|-----|--------------------|
| Triage de bugs | Desarrollador |
| Comunicacion a testers | Desarrollador |
| Contenido editorial | Desarrollador (o un editor invitado con admin) |
| Infra / deploy | Desarrollador |

Si entra un segundo colaborador despues, separar “contenido” de “bugs/infra”.

## Plantilla de reporte para testers

```text
Dispositivo / navegador:
Que intentaste hacer:
Que esperabas:
Que paso:
Pasos para reproducir:
Gravedad (bloquea / molesta / detalle):
Captura o video (si aplica):
```

## Riesgos y mitigaciones

| Riesgo | Mitigacion |
|--------|------------|
| Scope creep en dic/ene | Feature freeze escrito y visible en README |
| Import/media inestable | Re-importar en staging antes de invitar |
| Un solo operador saturado | Limitar invitados en cerrada; pausar registros en abierta |
| Datos de prueba en prod beta | Aviso de posible wipe; backups antes de cambios grandes |

## Enlaces

- [`roadmap.md`](roadmap.md)
- [`backlog.md`](backlog.md)
- [`getting-started.md`](getting-started.md)
- [`architecture.md`](architecture.md)
