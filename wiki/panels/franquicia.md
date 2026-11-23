# Panel Franquicia (Portal Cadena)

**URL:** `/franquicia`  
**Stack:** Filament v5  
**Usuario:** `UserType::Chain`  
**Provider:** `app/Providers/Filament/FranquiciaPanelProvider.php`  
**Autorización:** RBAC tenant-scoped — ver [RBAC Franquicia](../RBAC-FRANQUICIA.md)

## Propósito

Portal del dueño/gerente de la cadena: operación diaria, menú, marketing, estadísticas, seguridad (roles/permisos) y administración del tenant (branding, ajustes, pasarela de pago, documentos).

## Acceso

```php
'type' === UserType::Chain && is_active
```

Middleware: `ResolveTenant` (desde `users.tenant_id`), `ValidateUser`, `ApplyFranquiciaPanelBranding`.

Auth: login custom, password reset y verificación de email habilitados en el panel.

Demo: `owner@pollocrazy.mx` / `password`

## Grupos de navegación

### Operación

| Resource / Page | Slug / ruta | Función | Módulo RBAC |
|---|---|---|---|
| `Branches/BranchResource` | `branches` | Sucursales, horarios (incl. L–V), zona entrega | `branches` |
| `Orders/OrderResource` (+ View) | `orders` | Pedidos de la cadena + detalle | `orders` (solo `view`) |
| `BranchReservations/BranchReservationResource` | `reservaciones` | Config mesas/horarios reservaciones | Flag + `CanManageFranquiciaReservations` (sin módulo RBAC) |
| `Drivers/DriverResource` (+ View) | `drivers` | Repartidores, finanzas, mensajes | `drivers` |
| `Shipments/ShipmentResource` (+ View) | `shipments` | Envíos terceros (Didi/Uber) | `shipments` |
| `DeliveryHeatmapPage` | `/franquicia/mapa-calor` | Mapa de calor de entregas | `delivery_heatmap` |

### Menú

| Resource | Función | Módulo RBAC |
|---|---|---|
| `Categories/CategoryResource` | Categorías | `categories` |
| `Products/ProductResource` | Productos y combos | `products` |
| `ModifierGroups/ModifierGroupResource` | Modificadores/ingredientes | `modifier_groups` |

### Marketing

| Resource | Función | Módulo RBAC |
|---|---|---|
| `Banners/BannerResource` | Banners tienda (ventana programada) | `banners` |
| `Coupons/CouponResource` | Cupones descuento | `coupons` |
| `Surveys/SurveyResource` | Encuestas post-compra / marketing | `surveys` |
| `Customers/CustomerResource` | Usuarios registrados (CRM) | `customers` |
| `AbandonedCarts/AbandonedCartResource` | Carritos abandonados | `abandoned_carts` (solo `view`) |

Detalle de dominio: [Marketing](../domains/marketing.md), [Encuestas](../domains/surveys.md).

### Estadísticas

| Page | Ruta | Permiso |
|---|---|---|
| `FranquiciaDashboard` | `/franquicia` | KPIs + filtros periodo |
| `OrderStatisticsPage` | `/franquicia/estadisticas-ordenes` | `statistics.view` |
| `FinancialStatisticsPage` | `/franquicia/estadisticas-financieras` | `statistics.view` |
| `BranchStatisticsPage` | `/franquicia/estadisticas-tiendas` | `statistics.view` |
| `BranchStatisticsDetailPage` | `/franquicia/estadisticas-tiendas/{branch}` | `statistics.view` (oculta nav) |
| `ProductStatisticsPage` | `/franquicia/estadisticas-productos` | `statistics.view` |
| `DriverStatisticsPage` | `/franquicia/estadisticas-repartidores` | `statistics.view` |
| `CouponStatisticsPage` | `/franquicia/estadisticas-cupones` | `statistics.view` |
| `ReservationStatisticsPage` | `/franquicia/estadisticas-reservaciones` | Flag reservaciones + roles chain |

Detalle: [Estadísticas Franquicia](./franquicia-statistics.md), [Mapa de calor](./franquicia-heatmap.md).

### Seguridad

| Resource | Función | Módulo RBAC |
|---|---|---|
| `Users/ChainUserResource` | Staff de cadena y sucursales | `users` |
| `Roles/TenantRoleResource` | Roles tenant | `roles` |
| `Permissions/TenantPermissionResource` | Permisos | `permissions` |
| `PermissionGroups/TenantPermissionGroupResource` | Grupos de permisos | `permission_groups` |

### Administración

| Resource | Función | Módulo RBAC |
|---|---|---|
| `Branding/BrandingResource` | White-label (colores, logo) | `branding` |
| `Documents/ChainDocumentResource` | Documentos legales | `documents` |
| `GeneralSettings/GeneralSettingsResource` | Ajustes generales (perfiles) | `general_settings` |
| `GeneralSettings/Groups/ChainSettingGroupResource` | Grupos de ajuste | `setting_groups` |
| `GeneralSettings/Definitions/ChainSettingDefinitionResource` | Definiciones de ajuste | `setting_definitions` |
| `PaymentGateway/PaymentGatewayResource` | Pasarela Stripe/PayPal por cadena | Sin módulo RBAC (scope tenant) |
| `PickerIntegration/PickerIntegrationResource` | Picker Express: Master Key + Api Key por sucursal | `picker` |

Detalle: [Ajustes generales](../domains/settings.md), [Pasarela de pago](../external/chain-payment-gateway.md), [Picker Express](../integration/picker.md).

## Widgets (~45+)

```
app/Filament/Franquicia/Widgets/
├── FranquiciaStatsOverview.php
├── OnboardingChecklistWidget.php
├── OrderStatistics/
├── FinancialStatistics/
├── BranchStatistics/
├── DriverStatistics/
├── ProductStatistics/
├── CouponStatistics/
├── DeliveryHeatmap/
└── ReservationStatistics/
```

KPIs y gráficas usan drilldowns accionables (`InteractsWithFranquiciaStatDrilldown`, `InteractsWithFranquiciaChartDrilldown`, `App\Support\FranquiciaStatDrilldown`).

## Concerns compartidos

`app/Filament/Franquicia/Concerns/`:

- `AuthorizesChainPermissions` — gate RBAC por módulo
- Filtros de periodo y sucursal para estadísticas
- Drilldowns de stats/charts
- `CanManageFranquiciaReservations` — acceso reservaciones (flag + rol)
- Concerns de branding / branding-aware forms

## Componentes de formulario custom

| Componente | Uso |
|---|---|
| `AddressFieldset` | Dirección estructurada |
| `CoverageMapPicker` | Zona de entrega en mapa |
| `GeoSelects` | País/estado/ciudad |
| Floor-plan picker (hook script) | Plano de mesas (reservaciones) |

Ubicación: `app/Filament/Forms/` + render hooks en el panel.

## Features destacadas

| Feature | Notas |
|---|---|
| Detalle de pedido | `ViewOrder` + `OrderInfolist` |
| Horarios L–V | Action en `HoursRelationManager`: aplicar lunes–viernes de un golpe |
| Banners programados | Ventana de visualización (`SyncsBannerScheduleForm`) |
| Reservaciones | Config cadena/sucursal; ops en [Sucursal](./sucursal.md) y [Tienda](./tienda.md) |
| Mapa de calor | Leaflet vía hook `delivery-heatmap-map-script` |
| Pasarela por tenant | `ChainPaymentSetting` — Stripe + PayPal |

## Branding

- `ChainBranding` — colores/logo por cadena
- Middleware: `ApplyFranquiciaPanelBranding`
- Correos: mismo branding vía `MailBranding` ([detalle](../domains/emails.md))
- Assets: coverage map, floor-plan picker, heatmap

## Multi-tenancy

Todos los resources aplican scope vía `BelongsToTenant`. Solo ven datos de su cadena. La autorización fina va por `users.tenant_role_id` ([RBAC](../RBAC-FRANQUICIA.md)).

## Geocoding

```
POST /franquicia/geocode
```

Controller: `Franquicia/GeocodeAddressController`  
Usa: `App\Support\Geocoder`

## Onboarding

Widget `OnboardingChecklistWidget` + `FranquiciaStatsOverview` (KPIs con filtros).

Observer: `TenantOnboardingObserver`  
Service: `ChainOnboardingService`

Al crear el admin de cadena y al activar onboarding desde Admin se envían correos branded
(`ChainCreatedNotification`, `ChainOnboardingCompletedNotification`). Ver [Correos branded](../domains/emails.md).

## Archivos clave

```
app/Filament/Franquicia/
├── Auth/
├── Concerns/
├── Pages/
├── Resources/
└── Widgets/
config/chain-permissions.php
app/Services/{ChainAuthorization,TenantRoleProvisioner}.php
```

## Añadir funcionalidad a Franquicia

1. Resource con `--panel=franquicia`
2. Modelo con `BelongsToTenant`
3. Registrar módulo en `config/chain-permissions.php` si aplica
4. `use AuthorizesChainPermissions` + `chainPermissionModule()`
5. Estructura: `Resource.php`, `Schemas/Form.php`, `Tables/Table.php`
6. Provisionar: `sail artisan chain:provision-roles`
7. Test Feature del panel

## Debugging Franquicia

| Problema | Verificar |
|---|---|
| Datos vacíos | `tenant_id` del usuario vs datos |
| 403 en resource | Permiso `{modulo}.{accion}`, rol, `chain:provision-roles` |
| Stats incorrectas | Services `Chain*Statistics`, filtros periodo |
| Mapa no carga | `GOOGLE_MAPS_API_KEY` o fallback Leaflet / heatmap script |
| Menú no sincroniza | ProductResource vs BranchProduct overrides |
| Reservaciones ocultas | `Chain::isReservationsEnabled()` + rol |

## Estadísticas — filtros comunes

Pages usan `DashboardPeriod` y concerns para periodo, sucursal y rango de fechas. Servicios: ver [Servicios](../backend/services.md#estadísticas-franquicia) y [Estadísticas Franquicia](./franquicia-statistics.md).
