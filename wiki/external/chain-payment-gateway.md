# Pasarela de pago por tenant (Chain)

Stripe y PayPal configurados **por cadena** desde Franquicia. Complementa el binding global documentado en [Stripe](./stripe.md).

## Resource Filament

| Aspecto | Valor |
|---|---|
| Resource | `app/Filament/Franquicia/Resources/PaymentGateway/PaymentGatewayResource.php` |
| Slug | `payment-gateway` |
| URL | `/franquicia/payment-gateway` |
| Grupo nav | Administración (sort 6) |
| Label | Pasarela de pago |
| Página | `ManagePaymentGateway` (`EditRecord`) |
| Modelo | `ChainPaymentSetting` |

Al montar: `ChainPaymentSettingsService::ensureSettings()` crea el registro del tenant si no existe.

**Auth:** no hay módulo en `chain-permissions.php`. `canEdit()` exige `record.chain_id === current_tenant_id` (sin `AuthorizesChainPermissions`).

Form: `PaymentGateway/Schemas/PaymentGatewayForm.php`. Acciones header: test conexión PayPal/Stripe.

## Modelo `ChainPaymentSetting`

Path: `app/Models/ChainPaymentSetting.php`  
Relación: `Chain::paymentSettings()` HasOne.

| Campos PayPal | Campos Stripe |
|---|---|
| `paypal_enabled` | `stripe_enabled` |
| `paypal_client_id` | `stripe_publishable_key` |
| `paypal_secret` (encrypted) | `stripe_secret_key` (encrypted) |
| `paypal_mode` (`sandbox`\|`live`) | `stripe_webhook_secret` (encrypted) |

Helpers: `isPaypalConfigured()`, `isStripeConfigured()` (enabled + keys; Stripe exige prefijos `pk_` / `sk_`).

## Service

`ChainPaymentSettingsService` — `app/Services/ChainPaymentSettingsService.php`

| Método | Rol |
|---|---|
| `ensureSettings(Chain)` | `firstOrCreate` por cadena |
| `isPaypalAvailableForChain()` / `isStripeAvailableForChain()` | Gate storefront |
| `update(...)` | Persiste; secretos en blanco se preservan |
| `testPaypalConnection()` / `testStripeConnection()` | Diagnóstico UI |
| `stripeGatewayForOrder(Order)` | `ChainStripeGateway::forChain()` o fallback `PaymentGateway` global |

## Clientes por proveedor

| Proveedor | Clase | Notas |
|---|---|---|
| Stripe tenant | `app/Services/Payments/ChainStripeGateway.php` | `forChain(Chain)` con `stripe_secret_key` del tenant |
| PayPal tenant | `PaypalApiClient::forSettings(ChainPaymentSetting)` | Credenciales del registro |
| Fallback global | `PaymentGateway` → `StripeGateway` / `FakeGateway` | Env `STRIPE_SECRET` — ver [stripe.md](./stripe.md) |

## Uso: checkout tienda

| Pieza | Comportamiento |
|---|---|
| `PlaceOrder` | Valida `PaymentMethod::Stripe` / `Paypal` con availability del tenant; autoriza con `ChainStripeGateway::forChain` |
| `TiendaController::checkout` | Pasa `paypal_available`, `stripe_available` a Inertia |
| Confirm / capture Stripe | Via `ChainStripeGateway::forChain($chain)` |

Rutas checkout: tienda estándar (no listadas aquí; ver dominio [Pedidos](../domains/orders.md)).

## Uso: depósitos de reservación

`ReservationDepositService` (`app/Services/ReservationDepositService.php`):

1. `webPaymentMethods(Branch)` — filtra `stripe` / `paypal` según settings de mesa + availability de cadena
2. `pay(Reservation, method)` — Stripe: `authorizeReservation()` → `deposit_status=Pending` luego complete → `Authorized`; PayPal: createOrder → return capture → `Paid`

Controller: `TiendaReservationController` (rutas `/t/{chain}/reservar/.../pago/...`).  
Detalle de rutas: [Reservaciones](../domains/reservations.md).

## Relación con `ReservationDepositGateway`

Interface `app/Services/Payments/ReservationDepositGateway.php` + `StripeReservationDepositGateway` / `FakeReservationDepositGateway`.  
Bound en `AppServiceProvider`, pero el **flujo live de tienda** usa `ReservationDepositService` + `ChainStripeGateway` / `PaypalApiClient`, no la interface.

## Webhooks

`POST /api/v1/payments/webhook/stripe` sigue el secret **global** (`STRIPE_WEBHOOK_SECRET`) documentado en [stripe.md](./stripe.md).  
El campo `stripe_webhook_secret` del tenant existe en el modelo; no está cableado al form Filament como fuente del webhook.

## Mapa mental

```
Global env STRIPE_*          ChainPaymentSetting (tenant UI)
        │                              │
        ▼                              ▼
  StripeGateway / Fake          ChainStripeGateway / PaypalApiClient
        │                              │
        └──── PlaceOrder / depósitos ──┘
              (prioriza tenant si configurado)
```

## Ver también

- [Stripe (global)](./stripe.md)
- [Reservaciones](../domains/reservations.md)
- [Pedidos](../domains/orders.md)
- [Panel Franquicia](../panels/franquicia.md)
