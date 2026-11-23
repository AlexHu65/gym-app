# Servicios de dominio

Ubicación: `sline-webapp/app/Services/`

La lógica de negocio vive aquí. Controllers y Filament delegan en servicios.

## Mapa por dominio

### Pedidos

| Servicio | Responsabilidad |
|---|---|
| `PlaceOrder` | Checkout completo: validación, precios, cupón, pago, creación Order |
| `AvailableDriversQuery` | Repartidores disponibles para asignación |
| `BranchOrderStatistics` | Métricas operativas portal sucursal |
| `BranchReportDrilldown` | Drilldown JSON de métricas reports |

### Envíos terceros

| Servicio | Responsabilidad |
|---|---|
| `CreateShipment` | Crear envío Didi/Uber/Other |
| `CreateShipmentFromOrder` | Envío vinculado a pedido existente |

### Catálogo

| Servicio | Responsabilidad |
|---|---|
| `BranchProductPresentation` | Producto efectivo por sucursal (overrides) |
| `MenuExcel` | Import/export menú Excel |

### Facturación SaaS

| Servicio | Responsabilidad |
|---|---|
| `SubscriptionBillingService` | Generación facturas suscripción |
| `BillingCollectionService` | Cobranza y estados overdue |
| `InvoiceCollectionService` | Listado y filtros facturas |
| `InvoicePaymentService` | Registro pagos de factura |
| `InvoicePaymentsExcel` | Export Excel pagos |

### Cadena / onboarding

| Servicio | Responsabilidad |
|---|---|
| `ChainOnboardingService` | Pasos onboarding cadena; mails al crear admin y al activar ([Correos](../domains/emails.md)) |
| `ChainDocumentAccess` | Acceso archivos documentos |
| `ChainCustomerService` | CRM clientes |

### Estadísticas Franquicia

| Servicio | Responsabilidad |
|---|---|
| `ChainDashboardStatistics` | KPIs dashboard |
| `ChainOrderStatistics` | Stats pedidos |
| `ChainBranchStatistics` | Stats por sucursal |
| `ChainBranchDetailStatistics` | Detalle sucursal |
| `ChainFinancialStatistics` | Stats financieras |
| `ChainProductStatistics` | Stats productos |
| `ChainDriverStatistics` | Stats repartidores |
| `ChainCouponStatistics` | Stats cupones |
| `ChainReservationStatistics` | Stats reservaciones |
| `ChainDeliveryHeatmapService` | Mapa de calor entregas |
| `ChainPlatformStatsService` | Stats plataforma admin |

Drilldowns de widgets Franquicia: `App\Support\FranquiciaStatDrilldown` + enums `FranquiciaStatDrilldownTarget` / `FranquiciaReservationDrilldownScope`.

### Reservaciones

| Servicio | Responsabilidad |
|---|---|
| `PlaceReservation` | Alta reservación cliente |
| `ReservationService` | Ciclo de vida / transiciones |
| `ReservationAvailabilityService` | Disponibilidad |
| `ReservationSlotService` | Slots horarios |
| `ReservationCapacityService` | Capacidad |
| `ReservationCalendarService` | Calendario operaciones |
| `ReservationMapStateService` | Estado mapa mesas |
| `ReservationTableSuggestionService` | Sugerencia mesa |
| `ReservationDepositService` | Depósitos |
| `ReservationNotifier` | Notificaciones email/SMS |
| `ReservationHistoryService` | Historial |
| `ReservationStatisticsService` | Métricas reservaciones |
| `ReservationBranchCoverageService` | Cobertura sucursales |
| `BranchReservationSettingsService` | Settings sucursal |
| `BranchReservationTableSyncService` | Sync mesas |
| `ChainReservationsModuleService` | Módulo habilitado por cadena |

### Ajustes de cadena / pagos / carritos

| Servicio | Responsabilidad |
|---|---|
| `ChainSettingGroupProvisioner` | Provisiona grupos |
| `ChainSettingDefinitionProvisioner` | Provisiona definiciones |
| `ChainPaymentSettingsService` | Stripe/PayPal por cadena |
| `AbandonedCartTracker` | Tracking carritos abandonados |

Store de valores: `App\Support\ChainSettingStore`.

### Repartidores

| Servicio | Responsabilidad |
|---|---|
| `DriverBalanceService` | Balance repartidor |
| `DriverMessagingService` | Mensajería repartidor |

### Usuarios / RBAC tenant

| Servicio | Responsabilidad |
|---|---|
| `Usuarios/UserProvisioningService` | Alta staff: temp password + email |
| `ImpersonationService` | Admin impersonation |
| `ChainAuthorization` | Permisos runtime Franquicia |
| `TenantRoleProvisioner` | Roles sistema + defaults |
| `TenantPermissionProvisioner` | Permisos sistema |
| `TenantPermissionGroupProvisioner` | Grupos sistema |

### Pagos

| Servicio | Responsabilidad |
|---|---|
| `Payments/PaymentGateway` | Interface |
| `Payments/StripeGateway` / `ChainStripeGateway` | Stripe (plataforma / cadena) |
| `Payments/PaypalGateway` | PayPal por cadena |
| `Payments/FakeGateway` | Simulación dev sin keys |
| `Payments/ReservationDepositGateway` | Depósitos reservación |

Binding en `AppServiceProvider`:

```php
$this->app->bind(PaymentGateway::class, function () {
    return config('services.stripe.secret')
        ? app(StripeGateway::class)
        : app(FakeGateway::class);
});
```

### Notificaciones

| Servicio | Responsabilidad |
|---|---|
| `Notifier` | Dispatch centralizado notificaciones |

## Patrón de uso

### Desde controller

```php
public function __construct(
    protected PlaceOrder $placeOrder,
) {}

public function checkout(Request $request, Chain $chain, Branch $branch)
{
    $order = $this->placeOrder->execute($branch, $request->user(), $validated);

    return redirect()->route('tienda.tracking', [$chain, $order]);
}
```

### Desde Filament action

```php
Action::make('generateInvoice')
    ->action(fn (Chain $record) => app(SubscriptionBillingService::class)
        ->generateForChain($record));
```

## PlaceOrder — flujo crítico

Archivo: `app/Services/PlaceOrder.php`

```
1. Validar branch operacional y horario
2. Resolver productos efectivos (BranchProductPresentation)
3. Validar modificadores disponibles (BranchModifier)
4. Calcular subtotal, envío, descuento cupón, comisión
5. Procesar pago (StripeGateway / FakeGateway / cash)
6. Crear Order + OrderItems con snapshots
7. Registrar OrderStatusHistory
8. Dispatch OrderChanged + SendOrderNotification
```

**Al debuggear checkout**, empezar aquí.

## BranchProductPresentation — flujo crítico

Archivo: `app/Services/BranchProductPresentation.php`

Centraliza resolución de producto efectivo:

```php
$presentation = app(BranchProductPresentation::class);
$effective = $presentation->forProduct($product, $branch);

// $effective['name'], ['price'], ['images'], etc.
```

Usado por: SucursalMenuController, TiendaController, PlaceOrder.

## Cuándo crear un Service nuevo

✅ Crear service cuando:
- Lógica usada desde 2+ lugares (controller + Filament + job)
- Operación con múltiples modelos/transacciones
- Reglas de negocio complejas

❌ No crear service para:
- CRUD simple de un modelo (usar Eloquent directo en Filament)
- Una línea de lógica usada una sola vez

```bash
vendor/bin/sail artisan make:class Services/MiServicio
```

## Commands que usan services

| Command | Service |
|---|---|
| `billing:generate-invoices` | `SubscriptionBillingService` |
| `orders:flag-stale-locations` | (query directa DriverLocation) |

Schedule en `routes/console.php` o `bootstrap/app.php`.
