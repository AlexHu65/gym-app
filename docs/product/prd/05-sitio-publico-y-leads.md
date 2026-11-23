# PRD-05 — Sitio público y captación de leads

**Estado:** borrador basado en alcance y stack acordados.  
**Usuarios:** visitantes interesados en la app y beta.  
**Objetivo:** explicar la propuesta y permitir apuntarse a novedades/beta con consentimiento claro.

## Alcance

- Sitio responsive informativo, con propuesta de valor y beneficios verificables.
- Formulario mínimo de lead para beta/novedades, consentimiento explícito y propósito entendible.
- Confirmación de envío, errores accionables, privacidad y mecanismo de baja.
- Laravel + Inertia + React + Tailwind; rutas y permisos separados de backoffice.
- Automatizaciones periféricas de lead solo con consentimiento y bajo control; n8n no es fuente de verdad de usuario, pagos ni entrenamientos.

## Requisitos y aceptación

| ID | Requisito | Aceptación |
|---|---|---|
| WEB-01 | Mensaje fiel | No promete resultados físicos ni catálogo/funciones que todavía no estén disponibles. |
| WEB-02 | Captura voluntaria | Formulario explica finalidad y consentimiento antes del envío; solo solicita datos necesarios. |
| WEB-03 | Confirmación y error | Éxito confirma el registro; fallo conserva instrucciones para reintentar sin exponer errores internos. |
| WEB-04 | Baja/privacidad | La persona encuentra cómo retirar consentimiento y acceder a la política vigente. |
| WEB-05 | Sin venta | No hay checkout, enlace de pago externo ni concesión de Pro desde la web. |
| WEB-06 | Separación | Las rutas públicas no exponen vistas ni datos de administración; autorización se aplica en servidor. |

## Fuera de alcance

Checkout web, cuenta completa de entrenamiento en navegador, portal administrativo duplicado, automatizaciones que concedan permisos o modifiquen sesiones.

## Dependencias y pendientes

GRT-001/002 para mensaje y precios validados; GRT-011 para sistema visual; GRT-027 para landing; GRT-028/029 para automatizaciones. Política de privacidad, términos, jurisdicción y consentimiento deben cerrarse con GRT-030/035 antes de captar datos reales.

## Fuentes

[`brief.md`](../brief.md), [`mvp-scope.md`](../mvp-scope.md), [`ADR-002-backoffice-stack.md`](../../decisions/ADR-002-backoffice-stack.md), GRT-027–030.
