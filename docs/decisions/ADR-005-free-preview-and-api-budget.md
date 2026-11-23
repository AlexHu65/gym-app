# ADR-005 — Vista previa Free y control de cuota MuscleWiki

- **Estado:** Aprobada por Producto
- **Fecha:** 13 de noviembre de 2026
- **Responsables:** Producto, Backend, Operaciones
- **Tarjetas:** GRT-002, GRT-005; implementación/medición en GRT-013, GRT-026 y GRT-034

## Contexto

Gym Rat ofrece un desbloqueo móvil de pago único, mientras que las llamadas a MuscleWiki generan un costo recurrente compartido. Es necesario limitar el costo de las vistas gratuitas y proteger la cuota contratada sin hacer depender las funciones propias de Gym Rat de la disponibilidad del proveedor.

## Decisión

1. Free/Warm-Up es una vista previa de un único entrenamiento guardado por cuenta. La persona conserva ese entrenamiento. Después de que backend confirme el guardado, la app muestra inmediatamente la oferta de compra; una sesión posterior requiere el entitlement de pago.
2. Free no recibe ni solicita thumbnails, imágenes, videos, bodymaps ni tokens de medios. La selección de ejercicios puede usar metadata textual bajo demanda, pasando por el backend autenticado.
3. Todo consumo de MuscleWiki pertenece a una cuota mensual compartida de Gym Rat, no a una cuota individual vendida al usuario. No precargar buckets o listas de IDs. Aplicar caché únicamente conforme a términos, documentación y headers: texto máximo 30 días; medios con reglas más estrictas, y Free sin medios.
4. El backend instrumenta consumo y activa alarmas internas al 70%, 85% y 95% de la cuota mensual. Al 85%, limita consultas opcionales de bajo valor; al 95%, pausa consultas Free nuevas no cacheadas. Al agotarse la cuota o recibir 429, detiene consultas no esenciales hasta reset o decisión manual de cambio de plan. No comprar top-ups automáticamente.
5. El límite por cuenta y el control de cuota se aplican en backend. La app puede registrar y consultar datos propios existentes aunque la API esté agotada o indisponible. Un rechazo debe explicar cómo continuar y no descartar datos de entrenamiento.
6. El precio no consumible queda según GRT-048: MXN $399 para las primeras 100 compras o los primeros 60 días de beta abierta, lo que ocurra primero; después MXN $699. La permanencia cubre las funciones propias incluidas, no garantiza disponibilidad perpetua de medios de terceros.

## Consecuencias

- El paywall se presenta tras el guardado de la única sesión gratuita, sin interrumpir ni perder esa sesión.
- El consumo real de texto/metadatos y, para usuarios desbloqueados, de medios debe medirse por ruta/usuario/sesión para operar dentro de la cuota. Cachear metadata reduce misses repetidos permitidos; no cambia límites ni habilita almacenamiento prohibido.
- Los porcentajes 70/85/95 son controles internos de Gym Rat, además de alertas que el proveedor pueda ofrecer. Si falta el medidor confiable, se desactiva o reduce el acceso opcional y no se asume cuota libre.
- La conversión y disposición a pagar no se consideran validadas con esta decisión. Medir exposición, compras, abandono y costos durante beta y revisar en GRT-034.
- La compra puede seguir activa aunque MuscleWiki cambie precios, retire contenido o termine la licencia; divulgar esa dependencia y mantener registro, historial y rutinas propios disponibles.

## Criterios para implementación

- Backend aplica idempotentemente el límite de una sesión Free por cuenta, incluyendo reinstalación/cambio de dispositivo.
- Guardar la primera sesión no depende de mostrar el paywall con éxito; una vez guardada, el CTA se presenta y se puede recuperar si la app se interrumpe.
- Endpoints de thumbnail/image/bodymap/video rechazan accesos Free, aunque se invoquen directamente fuera de la UI.
- La cuota se mide desde respuestas del proveedor y/o contadores confiables; alertas llegan a responsables operativos. El comportamiento en 85%, 95%, reset mensual y 429 tiene estados/mensajes documentados.
- No se ejecutan top-ups ni cambio de plan sin aprobación operativa.

## Referencias

- [Términos de MuscleWiki API](https://api.musclewiki.com/api-terms)
- [Documentación de MuscleWiki API](https://api.musclewiki.com/documentation)
- [Precios de MuscleWiki API](https://api.musclewiki.com/pricing)
- [GRT-002 — paquete Free/desbloqueo](https://trello.com/c/x8wiRaqK/31-grt-002-validar-disposici%C3%B3n-de-pago-y-paquete-free-pro)
- [GRT-005 — alcance MVP](https://trello.com/c/4WoTjjov/36-grt-005-definir-alcance-mvp-y-criterios-de-salida)
