# Estadísticas Franquicia

Pages Filament bajo `/franquicia` con filtros de periodo/sucursal, widgets KPI/charts y drilldowns a resources.

## Permiso

| Permiso | Catálogo | Trait |
|---|---|---|
| `statistics.view` | `config/chain-permissions.php` módulo `statistics`, acciones `['view']`, grupo `estadisticas` | `CanViewFranquiciaStatistics` → `chain_user_can('statistics.view')` |

`owner` bypass vía `ChainAuthorization`. Rol `admin` provisionado con este permiso.

## Pages

| Page | `$routePath` | URL | Nav sort |
|---|---|---|---|
| `FranquiciaDashboard` | (home) | `/franquicia` | — |
| `BranchStatisticsPage` | `estadisticas-tiendas` | `/franquicia/estadisticas-tiendas` | 1 |
| `OrderStatisticsPage` | `estadisticas-ordenes` | `/franquicia/estadisticas-ordenes` | 2 |
| `DriverStatisticsPage` | `estadisticas-repartidores` | `/franquicia/estadisticas-repartidores` | 3 |
| `FinancialStatisticsPage` | `estadisticas-financieras` | `/franquicia/estadisticas-financieras` | 4 |
| `ProductStatisticsPage` | `estadisticas-productos` | `/franquicia/estadisticas-productos` | 5 |
| `ReservationStatisticsPage` | `estadisticas-reservaciones` | `/franquicia/estadisticas-reservaciones` | 6 |
| `CouponStatisticsPage` | `estadisticas-cupones` | `/franquicia/estadisticas-cupones` | 7 |
| `BranchStatisticsDetailPage` | `estadisticas-tiendas/{branch}` | `/franquicia/estadisticas-tiendas/{branch}` | oculta |

Paths: `app/Filament/Franquicia/Pages/`. Grupo nav: **Estadísticas** (excepto dashboard).

## Filtro `DashboardPeriod`

Enum: `app/Enums/DashboardPeriod.php`

| Case | Valor |
|---|---|
| `Today` | `today` |
| `Yesterday` | `yesterday` |
| `Last7Days` | `last_7_days` |
| `ThisMonth` | `this_month` |
| `LastMonth` | `last_month` |
| `Last3Months` | `last_3_months` |
| `Custom` | `custom` |

Trait: `HasFranquiciaPeriodAndBranchFilters` — fields `period` (default `today`), `custom_from`/`custom_until`, multi `branch_ids`.  
Resolución: `DashboardPeriodRange::resolve()`, `FranquiciaDashboardOrderStatisticsFilters::resolve()`.

## Services `Chain*Statistics`

| Service | Path | Uso principal |
|---|---|---|
| `ChainDashboardStatistics` | `app/Services/ChainDashboardStatistics.php` | Dashboard KPIs |
| `ChainBranchStatistics` | `app/Services/ChainBranchStatistics.php` | Ranking tiendas |
| `ChainBranchDetailStatistics` | `app/Services/ChainBranchDetailStatistics.php` | Detalle por sucursal |
| `ChainOrderStatistics` | `app/Services/ChainOrderStatistics.php` | Stats órdenes |
| `ChainFinancialStatistics` | `app/Services/ChainFinancialStatistics.php` | Financiero |
| `ChainProductStatistics` | `app/Services/ChainProductStatistics.php` | Productos |
| `ChainDriverStatistics` | `app/Services/ChainDriverStatistics.php` | Repartidores |
| `ChainCouponStatistics` | `app/Services/ChainCouponStatistics.php` | Cupones |
| `ChainReservationStatistics` | `app/Services/ChainReservationStatistics.php` | Reservaciones |

Reservaciones: también `ReservationStatisticsService` en dominio.

## Widgets por página

### `FranquiciaDashboard`

`OnboardingChecklistWidget` + cards `Stat` via `HasChainDashboardStats` (no widgets de stats nombrados).

### `BranchStatisticsPage`

`FranquiciaBranchRankingsWidget`

### `OrderStatisticsPage`

`FranquiciaOrderStatusKpiStats`, `FranquiciaOrderModalityChart`, `FranquiciaOrderChannelChart`, `FranquiciaOrderAverageTimesStats`, `FranquiciaOrderPeakHoursChart`, `FranquiciaOrderPeakDaysChart`, `FranquiciaRecentOrdersWidget`

### `FinancialStatisticsPage`

`FranquiciaFinancialKpiStats`, `FranquiciaPaymentMethodChart`, `FranquiciaRevenueByDateChart`, `FranquiciaDeliveryPaymentChart`, `FranquiciaPickupPaymentChart`, `FranquiciaTopOrdersWidget`

### `ProductStatisticsPage`

`FranquiciaProductKpiStats`, `FranquiciaProductSalesWidget`

### `DriverStatisticsPage`

`FranquiciaDriverKpiStats`, `FranquiciaDriverRecentShipmentsWidget`, `FranquiciaDriverStatusChart`, `FranquiciaDriverVehicleChart`, `FranquiciaDriverPlatformChart`, `FranquiciaTopDriversChart`, `FranquiciaTopDriversTable`

### `CouponStatisticsPage`

`FranquiciaCouponKpiStats`, `FranquiciaCouponPlatformChart`, `FranquiciaCouponDailyChart`, `FranquiciaTopCouponsWidget`, `FranquiciaRecentCouponRedemptionsWidget`

### `ReservationStatisticsPage`

`FranquiciaReservationKpiStats`, `FranquiciaReservationNewReservationsChart`, `FranquiciaReservationBreakdownTables`, `FranquiciaReservationDistributionPanel`, `FranquiciaReservationDrilldownHost`

### `BranchStatisticsDetailPage` (tabs)

`FranquiciaBranchIncomeStats`, `FranquiciaBranchSalesStats`, widgets de órdenes (mismo set que Order), `FranquiciaBranchProductStatsWidget`, `FranquiciaBranchFinancialStats`, `FranquiciaBranchCustomerStats`

Namespace widgets: `app/Filament/Franquicia/Widgets/` (y subdirs).

## Drilldowns

| Pieza | Path | Rol |
|---|---|---|
| `FranquiciaStatDrilldown` | `app/Support/FranquiciaStatDrilldown.php` | URLs filtradas a Order/Shipment/Customer/Driver/Product/Coupon resources |
| `FranquiciaStatDrilldownTarget` | `app/Enums/FranquiciaStatDrilldownTarget.php` | Targets (~24 cases: p.ej. `OrdersAccepted`, `ShipmentsDelivered`, `CustomersNew`, `ProductsActive`, `Drivers`) |
| `InteractsWithFranquiciaStatDrilldown` | `app/Filament/Franquicia/Concerns/InteractsWithFranquiciaStatDrilldown.php` | `applyStatDrilldown(Stat, Target)` → `->url()`; deshabilitado si falla validación de periodo |

`requiresPeriod()` es `false` solo para `Drivers` y `ProductsActive`.

## Reservaciones: gate extra

`ReservationStatisticsPage` usa **`CanViewFranquiciaReservationStatistics`**:

1. `statistics.view`
2. `Chain::isReservationsEnabled()`

`shouldRegisterNavigation()` aplica el mismo gate.  
Filtro de sucursales: solo branches con `reservationSettings.is_active = true`.

Sin flag → página/nav no aparecen. Ver [Reservaciones](../domains/reservations.md).

## Ver también

- [Panel Franquicia](./franquicia.md)
- [Marketing / cupones](../domains/marketing.md)
- [Mapa de calor](./franquicia-heatmap.md)
- [Reservaciones](../domains/reservations.md)
