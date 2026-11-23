# Gym Rat — app móvil

Aplica las [instrucciones del repositorio](../AGENTS.md). Esta carpeta contiene el cliente Ionic, Angular y Capacitor para iOS y Android.

- Facilita el primer registro de entrenamiento y conserva el historial de sesiones completadas al editar una rutina.
- Usa la API Laravel para identidad, catálogo, entrenamiento y derechos de acceso. Aplica los límites de caché y contenido de MuscleWiki definidos en el AGENTS.md raíz.
- Ofrece el desbloqueo de pago único no consumible mediante Apple In-App Purchase y Google Play Billing. Usa el estado de acceso validado por el backend y contempla la restauración de compras según el flujo de cada tienda.
- Configura la URL de API y los identificadores de tienda por ambiente. Guarda los secretos de backend y las llaves de firma fuera del cliente y del repositorio.
- Fija las versiones de Ionic, Angular, Capacitor y sus herramientas de desarrollo durante GRT-008. Sigue la regla de pruebas del AGENTS.md raíz.
- Usa la URL del backend correspondiente al ambiente. Docker Compose administra PostgreSQL local. La compilación nativa de iOS se realiza con macOS y Xcode.
