# Plan de producto y arquitectura para app de entrenamiento

Fecha de elaboracion: 17 de julio de 2026
Objetivo de salida: beta publica antes de diciembre de 2026

## 1. Contexto

El repositorio `exercises-dataset` ya aporta una base muy solida para arrancar:

- 1,324 ejercicios
- imagen y GIF por ejercicio
- instrucciones paso a paso
- soporte multilenguaje en el contenido
- esquema JSON formal para validacion

Esto permite construir una app con dos superficies claras:

1. App cliente para usuarios finales.
2. Backoffice administrable para alimentar, editar y publicar contenido.

La propuesta de stack para el primer lanzamiento es:

- Backend: Laravel
- Base de datos: PostgreSQL
- App cliente: Angular + Ionic
- Backoffice: Angular web
- Storage de media: S3 compatible
- Autenticacion: Laravel Sanctum

## 2. Objetivo del producto

Construir una plataforma de entrenamiento donde:

- el usuario final encuentre ejercicios, rutinas y seguimiento de entrenamiento de forma sencilla;
- el equipo interno pueda administrar ejercicios, traducciones, categorias, imagenes, GIFs y reglas de publicacion sin tocar codigo;
- el sistema este listo para escalar hacia planes personalizados, analitica y monetizacion.

## 3. Validacion del material existente

Antes de construir, conviene validar lo que ya tenemos en el dataset:

- El dataset contiene la taxonomia base para ejercicios: `category`, `body_part`, `equipment`, `target`, `muscle_group` y `secondary_muscles`.
- El contenido esta orientado a ejercicios individuales, no a programas o rutinas completas.
- El esquema JSON exige idiomas base y permite extensiones, mientras que el README menciona 9 idiomas. Esto sugiere que el modelo de datos debe ser flexible para no perder idiomas futuros.
- Cada ejercicio ya trae media y metadatos suficientes para un catalogo robusto.

Conclusiones:

- El dataset sirve perfecto como catalogo inicial.
- Falta construir las entidades de negocio: usuarios, rutinas, sesiones, favoritos, progreso y publicacion de contenido.
- El backoffice es indispensable para mantener calidad editorial.

## 4. PRDs necesarios

### PRD-01 - App cliente de entrenamiento

Objetivo:

- Dar al usuario una experiencia rapida para descubrir ejercicios, guardar favoritos, armar rutinas y registrar entrenamiento.

Alcance MVP:

- inicio con busqueda y filtros
- catalogo de ejercicios
- detalle de ejercicio con media e instrucciones
- favoritos
- rutinas o planes simples
- historial basico de entrenamiento
- login y registro
- selector de idioma

Fuera del MVP:

- recomendaciones con IA
- sincronizacion con wearables
- video en vivo
- social feed

### PRD-02 - Backoffice administrable

Objetivo:

- Permitir al equipo operar el catalogo sin depender de desarrollo.

Alcance MVP:

- CRUD de ejercicios
- edicion de traducciones
- gestion de imagen y GIF
- gestion de categorias, equipos y musculos
- publicacion y despublicacion
- busqueda y filtros internos
- carga masiva desde JSON/CSV
- historial de cambios basico

### PRD-03 - Motor de contenido y taxonomia

Objetivo:

- Estandarizar como se almacenan, versionan y publican ejercicios.

Alcance MVP:

- taxonomia unica para body part, equipment, target y muscle group
- versionado de ejercicios
- estados: draft, review, published, archived
- validacion de idiomas y media obligatoria

### PRD-04 - Autenticacion, roles y permisos

Objetivo:

- Separar operaciones de cliente y de administracion.

Roles sugeridos:

- Super Admin
- Content Editor
- Reviewer
- Support
- Customer/User

Alcance MVP:

- login
- recuperacion de password
- proteccion de rutas
- permisos por modulo
- audit de acciones criticas

### PRD-05 - Seguimiento y analitica basica

Objetivo:

- Entender uso real del producto desde el inicio.

Alcance MVP:

- eventos de busqueda
- ejercicios vistos
- favoritos
- rutinas creadas
- sesiones completadas
- conversion a registro y retencion

### PRD-06 - Operacion y despliegue

Objetivo:

- Garantizar que el sistema sea mantenible y desplegable en produccion.

Alcance MVP:

- ambientes dev, staging y prod
- backups automaticos
- logs centralizados
- monitoreo basico
- pipeline de despliegue

## 5. Definicion funcional del MVP

Si el objetivo es salir antes de diciembre, el MVP debe enfocarse en lo que aporta valor real y se puede terminar a tiempo.

### Para el usuario final

- explorar ejercicios con busqueda y filtros
- ver detalle con imagen, GIF e instrucciones
- guardar favoritos
- crear rutina simple
- registrar una sesion de entrenamiento
- cambiar idioma

### Para el equipo interno

- crear, editar y publicar ejercicios
- cargar media
- revisar traducciones
- corregir taxonomia
- importar dataset inicial
- ver cambios y auditar ediciones

### Lo que no debe entrar al primer release

- inteligencia artificial personalizada
- gamificacion avanzada
- integraciones con terceros
- marketplace o pagos
- coaching en tiempo real

## 6. Arquitectura tecnica propuesta

### 6.1 Estructura general

Propuesta recomendada:

- `backend`: Laravel API
- `client-app`: Angular + Ionic
- `admin-web`: Angular
- `shared`: librerias compartidas de tipos, validaciones y utilidades

Si se quiere reducir friccion, puede usarse un monorepo para compartir modelos y componentes.

### 6.2 Backend con Laravel

Responsabilidades:

- exponer API REST
- autenticar usuarios
- administrar permisos
- validar payloads
- importar dataset
- versionar ejercicios
- servir catalogo y rutinas
- registrar eventos de auditoria

Paquetes o capacidades recomendadas:

- Laravel Sanctum para autenticar app y admin
- Jobs y queue para importacion y procesamiento de media
- Scheduler para sincronizaciones y limpiezas
- Policies/Gates para permisos
- Form Requests para validacion
- API Resources para respuestas consistentes

### 6.3 Base de datos en PostgreSQL

PostgreSQL es una buena eleccion por:

- soporte solido para relaciones
- JSONB para contenido flexible
- indices poderosos
- buena escalabilidad para catalogos y filtros

Modelo minimo sugerido:

- `users`
- `roles`
- `permissions`
- `exercises`
- `exercise_translations`
- `exercise_media`
- `exercise_tags` o tablas de taxonomia
- `favorites`
- `workout_plans`
- `workout_plan_items`
- `workout_sessions`
- `workout_session_items`
- `audit_logs`
- `import_batches`

### 6.4 Modelo de datos sugerido para ejercicios

Recomendacion:

- `exercises`: datos estaticos del ejercicio
- `exercise_translations`: titulo e instrucciones por idioma
- `exercise_media`: imagen, GIF, attribution y version de archivo
- `exercise_secondary_muscles`: relacion muchos a muchos si se quiere normalizar

Ventaja:

- facilita multilenguaje real
- simplifica cambios futuros
- evita una sola tabla gigante con campos repetidos

### 6.5 API propuesta

Endpoints base sugeridos:

- `POST /auth/login`
- `POST /auth/logout`
- `GET /me`
- `GET /exercises`
- `GET /exercises/{id}`
- `GET /exercises/{id}/translations`
- `GET /taxonomies`
- `POST /favorites`
- `DELETE /favorites/{id}`
- `POST /workout-plans`
- `GET /workout-plans`
- `POST /workout-sessions`
- `POST /admin/exercises`
- `PUT /admin/exercises/{id}`
- `POST /admin/imports/exercises`
- `GET /admin/audit-logs`

### 6.6 Busqueda y filtros

Para el MVP, PostgreSQL puede cubrir bien:

- busqueda por nombre
- filtro por body part
- filtro por equipment
- filtro por target
- filtro por idiomas o disponibilidad

Si el catalogo crece o se vuelve mas complejo, se puede agregar un motor dedicado despues.

### 6.7 App cliente con Ionic

Responsabilidades:

- autenticar y mantener sesion
- mostrar catalogo
- navegar por rutinas y entrenamientos
- guardar favoritos
- soportar mobile first
- opcionalmente PWA

Ventaja:

- una sola base Angular para web movil y experiencia nativa ligera

### 6.8 Backoffice con Angular

Responsabilidades:

- gestion de contenido
- flujos de revision
- carga masiva
- moderacion de media
- administracion de taxonomia
- dashboards operativos

Recomendacion:

- separar claramente el backoffice del cliente aunque compartan librerias.

## 7. Flujo de contenido

Flujo recomendado:

1. Se importa el dataset inicial.
2. El contenido queda en estado `draft` o `review`.
3. Un editor corrige taxonomia, idiomas o media.
4. Un reviewer valida.
5. Se publica al catalogo de la app.
6. Cualquier cambio queda auditado.

## 8. Plan de trabajo hasta diciembre de 2026

### Fase 0 - Definicion y preparacion
Periodo: 17 al 31 de julio de 2026

Entregables:

- PRDs cerrados
- alcance MVP aprobado
- arquitectura tecnica aprobada
- definicion de roles
- wireframes iniciales
- repositorios y ambientes creados
- convenciones de codigo y despliegue

### Fase 1 - Fundacion tecnica
Periodo: agosto de 2026

Entregables:

- proyecto Laravel creado
- esquema PostgreSQL definido
- autenticacion y roles listos
- importador inicial del dataset
- almacenamiento de media configurado
- endpoints base del catalogo
- logs y backups basicos

### Fase 2 - Backoffice
Periodo: septiembre de 2026

Entregables:

- login de admin
- CRUD de ejercicios
- gestion de traducciones
- carga de media
- filtros y busqueda
- publicacion/despublicacion
- auditoria basica

### Fase 3 - App cliente
Periodo: octubre de 2026

Entregables:

- home
- busqueda y filtros
- detalle de ejercicio
- favoritos
- rutina simple
- registro de entrenamiento
- selector de idioma

### Fase 4 - End to end y calidad
Periodo: noviembre de 2026

Entregables:

- pruebas integrales
- ajustes de UX
- optimizacion de consultas
- control de errores
- control de permisos
- validacion de carga masiva
- beta cerrada

### Fase 5 - Preparacion de salida
Periodo: 1 al 15 de diciembre de 2026

Entregables:

- hardening
- correcciones finales
- monitoreo
- documentacion operativa
- publicacion de beta o release inicial

## 9. Hitos y criterios de exito

### Hitos

- H1: PRD cerrado y backlog priorizado
- H2: API y base de datos funcionando
- H3: importacion de ejercicios estable
- H4: backoffice usable por contenido
- H5: app cliente navegable y lista para beta
- H6: despliegue estable en staging y produccion

### Criterios de exito

- el catalogo carga sin errores
- los editores pueden publicar sin soporte tecnico
- el usuario puede buscar, guardar y entrenar
- las consultas son rapidas y estables
- el sistema tiene trazabilidad de cambios

## 10. Riesgos principales

- Falta definir el alcance real del producto y querer meter demasiadas funciones en el primer release.
- Media y traducciones pueden requerir limpieza previa.
- Si no se modela bien el contenido multilenguaje, luego sera costoso corregirlo.
- Sin backoffice, el mantenimiento manual se vuelve inviable.
- Sin analitica desde el inicio, sera dificil saber que mejorar.

## 11. Recomendaciones de arranque

Orden sugerido para empezar:

1. Cerrar el PRD del MVP.
2. Definir roles y permisos.
3. Diseñar el modelo de datos en PostgreSQL.
4. Construir el backend Laravel con autenticacion y catalogo.
5. Importar el dataset completo.
6. Levantar el backoffice.
7. Construir la app cliente.
8. Probar con usuarios reales.
9. Ajustar y publicar antes de diciembre.

## 12. Decisiones que conviene tomar antes de desarrollar

- Nombre comercial del producto
- Perfil del usuario principal
- Idiomas obligatorios del primer release
- Si la rutina sera solo catalogo o tambien planificador
- Si el contenido se publica por revision simple o por workflow editorial
- Donde se alojaran imagenes y GIFs
- Si la app cliente sera solo movil o tambien web/PWA
- Si la monetizacion entra en el MVP o se deja para una fase posterior

## 13. Conclusion

Con `exercises-dataset` ya existe una base de contenido suficiente para acelerar mucho el arranque. El camino mas seguro para salir en diciembre de 2026 es construir un MVP con:

- backend Laravel
- PostgreSQL bien modelado
- backoffice administrable
- app cliente en Angular/Ionic
- importacion inicial automatizada
- workflow editorial simple

La clave del exito no es solo mostrar ejercicios, sino hacer que el equipo pueda operar el contenido y que el producto quede listo para crecer sin reescribir la base.
