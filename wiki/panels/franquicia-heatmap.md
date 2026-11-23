# Mapa de calor de entregas (Franquicia)

Visualización geo de pedidos delivery en Filament Franquicia (Leaflet + heatmap).

## Pages

| Page | `$routePath` | URL | Nav |
|---|---|---|---|
| `DeliveryHeatmapPage` | `mapa-calor` | `/franquicia/mapa-calor` | Operación, sort 5 |
| `DeliveryHeatmapBranchPage` | `mapa-calor/{branch}` | `/franquicia/mapa-calor/{branch}` | Oculta |

Paths: `app/Filament/Franquicia/Pages/DeliveryHeatmapPage.php`, `DeliveryHeatmapBranchPage.php`.

## Permiso

| Permiso | Catálogo | Trait |
|---|---|---|
| `delivery_heatmap.view` | módulo `delivery_heatmap`, acciones `['view']`, grupo `operacion` | `CanViewDeliveryHeatmap` → `chain_user_can('delivery_heatmap.view')` |

Fuente: `config/chain-permissions.php`.

## Service

`ChainDeliveryHeatmapService` — `app/Services/ChainDeliveryHeatmapService.php`

| Método | Rol |
|---|---|
| `branches()` | Sucursales con lat/lng |
| `deliveryPoints(...)` | Puntos de pedidos delivery en rango; `MAX_POINTS = 2000`; filtro `OrderDeliveryType::Delivery`, opcional `branchIds` / statuses |
| `branchMarkers()` | Pins de sucursal con conteos |

## Filtros

Trait: `HasFranquiciaDeliveryHeatmapFilters` (extiende periodo/sucursal + multi status de orden).  
Resolver: `FranquiciaDeliveryHeatmapFilters`.

Comparte enum `DashboardPeriod` con [estadísticas](./franquicia-statistics.md).

## Widgets

Dir: `app/Filament/Franquicia/Widgets/DeliveryHeatmap/`

| Widget | Modo | Notas |
|---|---|---|
| `DeliveryHeatmapMapWidget` | `overview` | Trait `InteractsWithDeliveryHeatmapMap`; view `filament.franquicia.widgets.delivery-heatmap-map` |
| `DeliveryHeatmapBranchMapWidget` | `branch` | Extiende el overview |

## Hook Leaflet (panel)

Registrado en `FranquiciaPanelProvider`:

| Hook | Vista |
|---|---|
| `PanelsRenderHook::HEAD_END` | `resources/views/filament/franquicia/coverage-map-assets.blade.php` (CSS Leaflet + estilos `.fi-delivery-heatmap-map`) |
| `PanelsRenderHook::SCRIPTS_AFTER` | `resources/views/filament/franquicia/delivery-heatmap-map-script.blade.php` |

Script: carga `leaflet@1.9.4` + `leaflet.heat@0.2.0` (unpkg).  
Alpine: componente `deliveryHeatmapMap`.  
Eventos: `delivery-heatmap-refresh`, `delivery-heatmap-focus-order`.

Provider de mapa por defecto: Leaflet (`MapProvider::config()`); Google Maps opcional si hay API key.

## Ver también

- [Panel Franquicia](./franquicia.md)
- [Estadísticas](./franquicia-statistics.md)
- [Mapas y broadcasting](../external/maps-broadcasting.md)
- [Pedidos](../domains/orders.md)
