# Integración: Stripe

## Overview

Pagos con tarjeta en checkout tienda. Sin keys configuradas → `FakeGateway` (simula éxito).

## Configuración

```env
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...
```

`config/services.php`:
```php
'stripe' => [
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
],
```

## Arquitectura

```
PaymentGateway (interface)
├── StripeGateway    — producción/dev con keys
└── FakeGateway      — fallback sin STRIPE_SECRET
```

Binding en `AppServiceProvider`:
```php
$this->app->bind(PaymentGateway::class, function () {
    return config('services.stripe.secret')
        ? app(StripeGateway::class)
        : app(FakeGateway::class);
});
```

Archivos:
- `app/Services/Payments/PaymentGateway.php`
- `app/Services/Payments/StripeGateway.php`
- `app/Services/Payments/FakeGateway.php`

## Flujo checkout

```
1. Cliente selecciona tarjeta en checkout
2. PlaceOrder → PaymentGateway::charge()
3. StripeGateway crea PaymentIntent
4. Order creado con payment_status
5. Al entregar: captura confirmada (driver API o auto)
```

Service: `app/Services/PlaceOrder.php`

## Webhook

```
POST /api/v1/payments/webhook/stripe
```

Controller: `app/Http/Controllers/Api/StripeWebhookController.php`

Verifica signature con `STRIPE_WEBHOOK_SECRET`.

Eventos manejados: payment_intent.succeeded, payment_intent.failed, etc.

## Métodos de pago

| Método | Enum | Gateway |
|---|---|---|
| Efectivo | `PaymentMethod::Cash` | Sin gateway |
| Tarjeta | `PaymentMethod::Card` | Stripe/Fake |

## Customer payment methods

Modelo: `CustomerPaymentMethod` — tarjetas guardadas (Stripe Customer).

## Testing

`tests/Feature/StripePaymentTest.php`

Con FakeGateway no se necesitan keys reales en CI.

## Debugging

| Problema | Verificar |
|---|---|
| Pago no procesa | STRIPE_SECRET, logs PlaceOrder |
| Webhook 400 | STRIPE_WEBHOOK_SECRET, signature |
| Dev sin Stripe | FakeGateway activo — expected behavior |
| Captura falla | PaymentIntent status, deliver endpoint |

```bash
# Verificar gateway activo
vendor/bin/sail artisan tinker --execute 'dump(app(App\Services\Payments\PaymentGateway::class)::class);'
```

## Pasarela por cadena (tenant)

Además del Stripe de plataforma, cada cadena puede conectar **Stripe y/o PayPal** propios vía `PaymentGatewayResource` → modelo `ChainPaymentSetting`. Se usa en checkout de tienda y depósitos de reservaciones.

Ver [Pasarela por cadena](./chain-payment-gateway.md).

## Producción

1. Keys live de Stripe Dashboard
2. Webhook endpoint apuntando a `/api/v1/payments/webhook/stripe`
3. Eventos: `payment_intent.succeeded`, `payment_intent.payment_failed`
4. HTTPS obligatorio
