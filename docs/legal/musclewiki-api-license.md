# Licencia y límites de MuscleWiki API en Gym Rat

**Revisión de fuente:** 13 de noviembre de 2026.  
**Fuente primaria:** [MuscleWiki API Terms of Use](https://api.musclewiki.com/api-terms), actualizados el 8 de octubre de 2026. Complemento: [precios API](https://api.musclewiki.com/pricing) y [documentación API](https://api.musclewiki.com/documentation).  
**Propósito:** registro de derechos concedidos y restricciones operativas para Producto, Ingeniería y Legal. Este resumen no reemplaza ni modifica los términos del proveedor.

## Derechos que aplican

Los planes pagados incluyen uso comercial, según la página de precios oficial. Los términos conceden una licencia limitada, no exclusiva, no transferible, no sublicenciable y revocable para:

- Llamar a MuscleWiki API desde la aplicación o servicio propio de Gym Rat.
- Mostrar metadata de ejercicios (por ejemplo, nombres, descripciones, músculos, categorías, dificultad e instrucciones) dentro de la app o web propia.
- Reproducir los videos servidos por el API directamente dentro de la aplicación propia, bajo las reglas de streaming que siguen.

La licencia se limita a aplicaciones de fitness, salud y entrenamiento. No confiere propiedad del catálogo ni garantiza disponibilidad perpetua de los recursos.

## Límites de uso y almacenamiento

| Contenido/uso | Regla documentada |
|---|---|
| Metadata textual | Cache permitido por máximo 30 días. No usar el caché como sincronización/exportación masiva ni conservar snapshots externos en historial más allá de ese TTL sin permiso escrito. |
| Miniaturas | Términos: máximo 24 horas. La documentación de API indica caché `private` del dispositivo; respetar ese header y no usar caché compartida. |
| Bodymaps | Términos: máximo 24 horas; la documentación indica `no-store`, regla más estricta que se debe obedecer. |
| Videos | `no-store`; solo caché transitoria de reproducción (buffer de navegador/memoria). No escribir bytes en disco, object storage, CDN ni caché compartida. |
| Proxy | Permitido para proteger la API key si es transparente: pasar headers del proveedor sin cambios y nunca persistir bytes de video en disco/storage/CDN. Para clientes nativos se contempla el flujo de token soportado por API. |
| Reproducción | Dentro de la propia app, sin facilitar URLs directas para descargar/reutilizar el video. No rehostear ni publicar medios en redes o plataformas externas. |

Está prohibido descargar, exportar, copiar o guardar videos, thumbnails o bodymaps para uso offline o fuera de los límites transitorios; revender, redistribuir, sublicenciar o exponer datos/API a terceros; construir una API o dataset competidor; hacer scraping, crawling, harvesting o bulk export; y usar el contenido para entrenar modelos IA/sistemas/datasets sin permiso escrito.

## Atribución y marca

Todo producto que muestre datos o videos de MuscleWiki debe incluir literalmente la siguiente atribución en Términos de Servicio, Política de Privacidad o documento legal equivalente:

> Exercise data and videos provided by MuscleWiki.com

El branding de MuscleWiki integrado en los videos no se puede quitar, ocultar, recortar ni alterar mediante superposición o re-encoding. Un crédito visible con enlace dentro de la app es recomendado, pero los términos indican que no es obligatorio.

## Cambios, disponibilidad y respuesta a fallas

MuscleWiki puede retirar ejercicios, medios, endpoints o datasets, suspender o terminar el acceso, incluso inmediatamente ante incumplimientos. Los términos permiten modificar precios con al menos siete días de aviso. Al agotar la cuota, las solicitudes responden `429` hasta el reinicio; no hay cobro automático por exceso. Cada `/stream/` y cada solicitud HTTP Range cuenta contra la cuota, y la reproducción puede generar varias llamadas.

El desbloqueo Gym Rat de pago único da acceso permanente a las funciones propias descritas en el SKU. No convierte el contenido MuscleWiki en un activo de Gym Rat ni garantiza acceso perpetuo, offline o ininterrumpido a medios de terceros. Si API/media no está disponible, las rutinas propias y el registro/historial de entrenamientos deben seguir funcionando; se puede ocultar o señalar la demostración como no disponible sin perder datos propios.

## Condiciones contractuales que requieren seguimiento

Los términos eligen la ley de Islas Caimán y sus tribunales; incluyen una indemnización del usuario a MuscleWiki por reclamos derivados del mal uso/incumplimiento y limitan la responsabilidad de MuscleWiki a las cuotas del API pagadas en los tres meses anteriores. Confirmar vigencia de términos y revisión legal antes del lanzamiento comercial. Registrar una nueva revisión si cambian términos, plan, precio, permisos, endpoints o headers.

## Referencias de implementación

- [Integración/caché/streaming](../architecture/musclewiki-api-integration.md)
- [Aviso para paywall y ficha de tienda](../product/musclewiki-user-disclosure.md)
- [Registro de fuentes/licencias](../data/exercise-source-register.md)
- [Plan de negocio y financiación API](../product/business-plan.md)
- [GRT-004 — documentación de derechos](https://trello.com/c/yMNo4Vg0/32-grt-004-documentar-derechos-y-l%C3%ADmites-de-musclewiki-api)
