# Sucursal: Reservaciones

Operación diaria de mesas en el portal Sucursal (Inertia + React). Dominio completo: [Reservaciones](../domains/reservations.md).

## Página y controller

| Pieza | Path |
|---|---|
| Inertia page | `resources/js/pages/sucursal/reservations.tsx` (nombre Inertia: `sucursal/reservations`) |
| Controller | `app/Http/Controllers/Sucursal/SucursalReservationController.php` |
| Rutas | `routes/sucursal.php` (middleware `auth`, `branch`, `validate.user`) |

## Guards

1. Cadena: `Chain::isReservationsEnabled()`
2. Sucursal: `Branch::hasActiveReservations()`
3. Reservation debe pertenecer a la branch resuelta

## Rutas

| Método | Path | Nombre | Acción |
|---|---|---|---|
| `GET` | `/sucursal/reservaciones` | `sucursal.reservations.index` | `index` |
| `GET` | `/sucursal/reservaciones/mapa-estado` | `sucursal.reservations.map-state` | `mapState` (JSON; `?preview=1&date=`) |
| `POST` | `/sucursal/reservaciones/{reservation}/accept` | `sucursal.reservations.accept` | `accept` |
| `POST` | `/sucursal/reservaciones/{reservation}/reject` | `sucursal.reservations.reject` | `reject` |
| `POST` | `/sucursal/reservaciones/{reservation}/assign-table` | `sucursal.reservations.assign-table` | `assignTable` |
| `POST` | `/sucursal/reservaciones/{reservation}/seat` | `sucursal.reservations.seat` | `seat` |
| `POST` | `/sucursal/reservaciones/{reservation}/complete` | `sucursal.reservations.complete` | `complete` |

Backend de mutaciones: `ReservationService`.

Al **accept**, el cliente recibe `ReservationAcceptedNotification` (branded) y se
programan reminders (`SendReservationReminder` → email 24h / SMS 2h). Ver [Correos branded](../domains/emails.md).

## Flujo operativo

```
Pending ──accept──► Confirmed ──seat──► Seated ──complete──► Completed
   │                    │
   └──reject──► Rejected │──assign-table──► (mesa reasignada, sigue Confirmed)
```

| Acción | Status resultante | Notas |
|---|---|---|
| `accept` | `Confirmed` | Modal con sugerencia de mesas (`ReservationTableSuggestionService`) |
| `reject` | `Rejected` | Requiere `ReservationRejectionReason` |
| `assign-table` | (sin cambio de status) | Reasigna `branch_reservation_table_id` |
| `seat` | `Seated` | Cliente llegó |
| `complete` | `Completed` | Servicio terminado |

## Tabs UI (`?tab=`)

| Tab | Query | Componente |
|---|---|---|
| Pendientes | `pending` (default) | Listado + modales accept/reject en `reservations.tsx` |
| Calendario | `calendar` | `reservation-calendar-tab.tsx` |
| Mapa | `map` | `reservation-live-map-tab.tsx` (+ mapa operativo) |
| Historial | `history` | `reservation-history-tab.tsx` |

## Componentes (`resources/js/components/sucursal/`)

| Archivo | Rol |
|---|---|
| `accept-reservation-modal.tsx` | Aceptar + elegir mesa |
| `reject-reservation-modal.tsx` | Rechazar con motivo |
| `reservation-calendar-tab.tsx` | Vista calendario (`ReservationCalendarService`) |
| `reservation-history-tab.tsx` | Historial terminal (`ReservationHistoryService`) |
| `reservation-live-map-tab.tsx` | Tab mapa en vivo |
| `reservation-map-panel.tsx` | Panel del mapa |
| `reservation-operational-map.tsx` | Mapa operativo / estados |

Mapa-estado: poll/refresh contra `sucursal.reservations.map-state` → `ReservationMapStateService` (`available` / `reserved` / `occupied`).

## Realtime

Canal: `branch.{branchId}.reservations`  
Evento: `ReservationChanged` (`app/Events/ReservationChanged.php`)

## Ver también

- [Dominio Reservaciones](../domains/reservations.md)
- [Portal Sucursal](../panels/sucursal.md)
- [Pasarela depósitos](../external/chain-payment-gateway.md)
- [Estados / enums](../backend/enums.md)
