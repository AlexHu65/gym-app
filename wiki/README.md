# Documentación Sline

Guía completa del monorepo **Sline**: plataforma white-label de delivery SaaS multi-tenant para cadenas de restaurantes.

> Actualizada contra la rama `dev` (julio 2026): marketing, encuestas, reservaciones, ajustes generales, RBAC, mapa de calor, estadísticas ampliadas, pasarela por tenant, correos branded.

## Para quién es esta documentación

- **Corregir bugs**: empieza por [Depuración](./development/debugging.md) y el dominio afectado en [Dominios de negocio](./domains/).
- **Integrar funcionalidades nuevas**: lee [Guía de integración](./integration/adding-features.md) y la superficie correspondiente (Filament, Inertia o API).
- **Entender el sistema**: [Arquitectura](./architecture/overview.md) → [Backend](./backend/) → [Paneles](./panels/).
- **Permisos del portal cadena**: [RBAC Franquicia](./RBAC-FRANQUICIA.md).

## Mapa del repositorio

```
sline/
├── wiki/                          ← Estás aquí
├── PRDs/                          # Especificaciones funcionales (referencia de producto)
├── sline-webapp/                  # Backend Laravel 13 + Filament + PWAs + API
└── sline-delivery-whitelabel-app/ # App repartidor (Expo / React Native)
```

## Las cinco superficies del producto

| Superficie | Usuario | Stack | URL |
|---|---|---|---|
| **Admin Sline** | Equipo interno | Filament v5 | `/admin` |
| **Portal Cadena (Franquicia)** | Dueño/gerente del tenant | Filament v5 | `/franquicia` |
| **Portal Sucursal** | Encargado/cajero | Inertia + React (PWA) | `/sucursal` |
| **App Cliente (Tienda)** | Comensal | Inertia + React (PWA) | `/t/{slug}/{branch}` |
| **App Repartidor** | Repartidor | Expo → API REST | `/api/v1/*` |

## Índice por tema

### Arquitectura

| Documento | Contenido |
|---|---|
| [Visión general](./architecture/overview.md) | Stack, flujo de datos, diagrama de capas |
| [Multi-tenancy](./architecture/multi-tenancy.md) | `tenant_id`, scopes, RBAC chain en runtime |
| [Ciclo de vida de una petición](./architecture/request-lifecycle.md) | Middleware, auth, Inertia vs Filament |

### Desarrollo

| Documento | Contenido |
|---|---|
| [Setup local](./development/setup.md) | Sail, seeders, credenciales demo |
| [Convenciones](./development/conventions.md) | PHP, React, Filament, commits |
| [Testing](./development/testing.md) | Pest, patrones, CI |
| [Depuración](./development/debugging.md) | Checklist por tipo de bug |

### Backend Laravel

| Documento | Contenido |
|---|---|
| [Modelos y relaciones](./backend/models.md) | Entidades (58), Eloquent, factories |
| [Servicios](./backend/services.md) | Lógica de negocio por dominio |
| [Middleware](./backend/middleware.md) | Auth, tenant, branch, validación |
| [Eventos y jobs](./backend/events-jobs.md) | Realtime, notificaciones, colas |
| [Enums](./backend/enums.md) | Estados, roles, tipos |

### Paneles y superficies

| Documento | Contenido |
|---|---|
| [Panel Admin](./panels/admin.md) | Recursos, widgets, cobranza |
| [Panel Franquicia](./panels/franquicia.md) | Operación, menú, marketing, seguridad, admin |
| [Estadísticas Franquicia](./panels/franquicia-statistics.md) | Pages, widgets, filtros, drilldowns |
| [Mapa de calor](./panels/franquicia-heatmap.md) | Heatmap de entregas por cadena/sucursal |
| [Portal Sucursal](./panels/sucursal.md) | Tablero, menú, reportes, reservaciones, envíos |
| [Tienda cliente](./panels/tienda.md) | Catálogo, checkout, tracking, reservar, encuestas |
| [App repartidor](./panels/driver-app.md) | Expo, API Sanctum |

### Dominios de negocio

| Documento | Contenido |
|---|---|
| [Pedidos](./domains/orders.md) | Ciclo de vida, programados, estados, servicios |
| [Catálogo y menú](./domains/catalog.md) | Productos, modificadores, overrides |
| [Marketing](./domains/marketing.md) | Banners, cupones, CRM, carritos abandonados |
| [Encuestas](./domains/surveys.md) | CRUD Franquicia + token público tienda |
| [Reservaciones](./domains/reservations.md) | Flag cadena, config, ops sucursal, tienda `/reservar` |
| [Ajustes generales](./domains/settings.md) | Groups, definitions, profiles, values |
| [Facturación SaaS](./domains/billing.md) | Planes, suscripciones, facturas |
| [Envíos terceros](./domains/shipments.md) | Didi, Uber, plataformas externas |
| [Usuarios y auth](./domains/users-auth.md) | Tipos, roles, `tenant_role_id`, provisioning |
| [Correos branded](./domains/emails.md) | Notificaciones mail por franquicia + `mail:send-test` |
| [Repartidores](./domains/drivers.md) | Turnos, GPS, balance, stats |
| [Clientes](./domains/customers.md) | CRM marketing, direcciones, pagos |

### Autorización

| Documento | Contenido |
|---|---|
| [RBAC Franquicia](./RBAC-FRANQUICIA.md) | Permisos tenant-scoped, roles, provisionamiento |

### Frontend

| Documento | Contenido |
|---|---|
| [Inertia + React](./frontend/inertia-react.md) | Páginas, layouts, Wayfinder |
| [Componentes UI](./frontend/components.md) | shadcn/Radix, tienda, sucursal |
| [Tiempo real](./frontend/realtime.md) | Echo, soketi, OrderChanged |

### Integración (cómo añadir cosas nuevas)

| Documento | Contenido |
|---|---|
| [Guía general](./integration/adding-features.md) | Decision tree: ¿Filament, Inertia o API? |
| [Nuevo recurso Filament](./integration/filament-resource.md) | Resource, form, table, tests |
| [Nueva página Inertia](./integration/inertia-page.md) | Controller, React, rutas |
| [Nuevo endpoint API](./integration/api-endpoint.md) | Sanctum, controladores, tests |
| [Migraciones y modelos](./integration/migrations.md) | Schema, factories, seeders |
| [Picker Express](./integration/picker.md) | Flota externa: keys, quote, booking, webhooks |

### Integraciones externas

| Documento | Contenido |
|---|---|
| [Stripe](./external/stripe.md) | Pagos plataforma / FakeGateway |
| [Pasarela por cadena](./external/chain-payment-gateway.md) | Stripe + PayPal tenant (`ChainPaymentSetting`) |
| [Picker Express](./integration/picker.md) | Delivery externo (Master Key, Pre Checkout, webhooks) |
| [Push notifications](./external/push-notifications.md) | Web Push, Expo Push |
| [Mapas y broadcasting](./external/maps-broadcasting.md) | Google Maps, soketi, heatmap |

### Despliegue

| Documento | Contenido |
|---|---|
| [Desarrollo local](./deployment/local.md) | Docker Sail, servicios |
| [Producción](./deployment/production.md) | Railway, CI/CD, variables |

### Sucursal (detalle funcional)

| Documento | Contenido |
|---|---|
| [Panel de pedidos](./sucursal/order-panel.md) | Kanban, programados, delivery vs pickup |
| [Menú y estadísticas](./sucursal/menu-stats.md) | Overrides, reportes, drilldown |
| [Overrides de producto](./sucursal/product-overrides.md) | `branch_products`, presentación |
| [Reservaciones](./sucursal/reservations.md) | Calendario, mapa, accept → complete |

## Flujo de pedido end-to-end

```
Cliente (Tienda) → PlaceOrder → OrderChanged (broadcast)
    → Sucursal (tablero realtime) → aceptar → cocina → listo
    → asignar repartidor in-house  Ó  Picker Express (booking + webhooks)
    → Cliente (tracking + calificación)
```

Con Picker habilitado por sucursal: la tienda cotiza con Pre Checkout y al marcar Ready se crea el booking automáticamente. Detalle en [Picker Express](./integration/picker.md).

Detalle completo en [Pedidos](./domains/orders.md).

## Flujo de reservación (si el módulo está activo)

```
Cliente (Tienda /reservar) → slots → borrador → depósito (Stripe/PayPal)
    → Sucursal (accept / assign-table / seat / complete)
    → Franquicia (config mesas + estadísticas)
```

Detalle en [Reservaciones](./domains/reservations.md).

## Credenciales demo

Tras `migrate:fresh --seed`:

| Rol | Email | Password |
|---|---|---|
| Admin Sline | `admin@sline.app` | `password` |
| Dueño cadena | `owner@pollocrazy.mx` | `password` |
| Staff sucursal | `lead0@pollocrazy.mx` … `lead2@` | `password` |
| Tienda demo | — | `/t/pollo-crazy/{branchId}` |

## Referencias adicionales

- [README raíz](../README.md) — onboarding rápido
- [PRDs](../PRDs/) — especificaciones de producto
- [AGENTS.md](../sline-webapp/AGENTS.md) — convenciones Laravel Boost del proyecto
- [Auditoría MVP Filament](./AUDITORIA-Y-PROPUESTA-MVP-FILAMENT.md) — contexto histórico de producto
