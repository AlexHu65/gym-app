# Dominio: Repartidores

## Modelos

| Modelo | Función |
|---|---|
| `User` (type=driver) | Cuenta repartidor |
| `DriverProfile` | Perfil: vehículo, push tokens, balance |
| `DriverShift` | Turnos activos |
| `DriverLocation` | Pings GPS |

## Enums

| Enum | Uso |
|---|---|
| `DriverStatus` | Disponible, ocupado, offline |
| `VehicleType` | Moto, bicicleta, auto |

## Alta repartidor

Desde Franquicia → `Drivers/DriverResource`:
1. Crear User con type=driver
2. Crear DriverProfile
3. UserProvisioningService envía email setup

Página detalle: `Drivers/Pages/ViewDriver` — perfil, balance y acciones (mensaje SMS preparado).

## Estadísticas repartidores

Service: `ChainDriverStatistics`  
Widgets Filament con drilldowns vía `FranquiciaStatDrilldown` / `FranquiciaStatDrilldownTarget` (drivers, shipments, órdenes con delivery fee, etc.).

## Turnos

```
POST /api/v1/shifts       → Iniciar turno
POST /api/v1/shifts/end   → Terminar turno
```

Modelo: `DriverShift` con timestamps inicio/fin.

Afecta query `AvailableDriversQuery` — solo drivers en turno activo aparecen para asignación.

## GPS tracking

```
POST /api/v1/locations
{ lat, lng, accuracy, heading, speed }
```

Almacenado en `driver_locations`.

### Stale locations

Command: `orders:flag-stale-locations` (cada minuto)  
Marca ubicaciones antiguas — útil para detectar repartidor desconectado.

## Asignación a pedido

Desde sucursal:
```
POST /sucursal/orders/{order}/assign
{ driver_id: int }
```

- Cambia order status a `OnTheWay`
- Dispara OrderChanged + push al repartidor

Query drivers disponibles: `AvailableDriversQuery`

## App móvil

Ver [App repartidor](../panels/driver-app.md).

Flujo: pickup → deliver

## Balance / finanzas

Service: `DriverBalanceService`

Visible en `ViewDriver` (infolist) — balance acumulado por entregas efectivo/comisiones.

Test: `FranquiciaDriverBalanceTest.php`

## Mensajería

Service: `DriverMessagingService`

- Chat integrado en tracking de pedido (canal pedido)
- SMS al repartidor: `sendSms()` encola `NotificationOutbox` con `provider: twilio` (preparado; se envía cuando Twilio esté configurado)

Acción Filament: `DriverHeaderActions`
## Push notifications

- Token Expo en `DriverProfile.push_tokens_json`
- Registro: `POST /api/v1/me/push-token`
- Envío: `ExpoPushSender` via `SendOrderNotification` job

## Archivos clave

```
app/Models/DriverProfile.php, DriverShift.php, DriverLocation.php
app/Services/AvailableDriversQuery.php
app/Services/DriverBalanceService.php
app/Services/DriverMessagingService.php
app/Http/Controllers/Api/DriverController.php
app/Http/Controllers/Api/DriverOrderController.php
app/Filament/Franquicia/Resources/Drivers/
app/Support/Push/ExpoPushSender.php
resources/js/components/sucursal/assign-driver-modal.tsx
tests/Feature/DriverApiTest.php
```

## Debugging

| Problema | Verificar |
|---|---|
| No aparece en assign | Turno activo, DriverStatus, branch |
| GPS stale | POST /locations, command flag-stale |
| Push no llega | push_tokens_json, queue worker |
| Balance incorrecto | DriverBalanceService, payments |
