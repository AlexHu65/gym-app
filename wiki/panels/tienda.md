# Tienda cliente (App Cliente)

**URL:** `/t/{chain:slug}/{branch}`  
**Stack:** Inertia v3 + React 19 (PWA)  
**Usuario:** Cliente (guest o autenticado `UserType::Customer`)

## Propósito

Experiencia de compra white-label para el comensal: catálogo, carrito, checkout, tracking y calificación.

## Rutas principales

| Página | Ruta | Función |
|---|---|---|
| Home / catálogo | `GET /t/{chain}/{branch}` | Landing + catálogo |
| Producto | `GET /t/{chain}/{branch}/product/{product}` | Detalle producto |
| Carrito | `GET /t/{chain}/{branch}/cart` | Carrito |
| Checkout | `GET /t/{chain}/{branch}/checkout` | Checkout (auth) |
| Pedidos | `GET /mis-pedidos` | Historial (auth) |
| Tracking | `GET /t/{chain}/seguimiento/{order}` | Tracking en vivo |
| Login | `GET /entrar` | Login cliente |
| Reservaciones | `GET /t/{chain}/reservar*` | Flujo reservar mesa (auth) |
| Encuesta | `GET|POST /t/{chain}/encuesta/{token}` | Encuesta pública |

Archivo: `routes/tienda.php`

## Controllers

| Controller | Alcance |
|---|---|
| `TiendaController` | Catálogo, carrito, checkout, tracking, cupones |
| `TiendaReservationController` | Flujo `/reservar*` (slots, borrador, pago depósito) |
| `SurveyPublicController` | Encuestas por token |
| `TiendaAuthController` | Login/registro tienda |
| `TiendaProfileController` | Perfil y direcciones |

## Middleware

- `chain.operational` — cadena activa
- Route model binding: `{chain:slug}`, `{branch}`, `{product}`

## Componentes tienda

```
resources/js/components/tienda/
├── branch-locator.tsx, branch-map.tsx
├── catalog-hero.tsx, promotions-hero.tsx
├── category-card.tsx, category-carousel.tsx
├── product-card.tsx, product-row.tsx, cart-product-card.tsx
├── combo-card.tsx, combo-includes.tsx, combos-row.tsx
├── banner-card.tsx, checkout-order-item.tsx
├── schedule-picker.tsx          # Pedido programado
├── customer-auth-modal.tsx
├── landing-address-search.tsx, order-status-stepper.tsx
├── step-header.tsx, tienda-navbar.tsx, tienda-breadcrumb.tsx
└── use-horizontal-scroll.ts
```

Layout: `resources/js/layouts/tienda-layout.tsx`

## Reservaciones (cliente)

Rutas bajo `/t/{chain}/reservar*` (`TiendaReservationController`): selector sucursal, slots, borrador, pago depósito (Stripe/PayPal) y éxito.

## Encuestas

`/t/{chain}/encuesta/{token}` — `SurveyPublicController` (pública, sin auth).

## Pedidos programados

Checkout con `schedule-picker.tsx` → `scheduled_for` validado por `ScheduledOrderTime`.

## Hooks y libs

| Archivo | Función |
|---|---|
| `hooks/use-cart.ts` | Estado carrito (localStorage/IDB) |
| `lib/web-push.ts` | Suscripción push cliente |
| `lib/idb-queue.ts` | Cola offline carrito |

## Flujo de checkout

```
1. Cliente arma carrito (use-cart)
2. checkout.tsx — dirección, pago, cupón, programado
3. POST checkout → TiendaController
4. PlaceOrder::execute()
   - Valida productos efectivos (BranchProductPresentation)
   - Valida modificadores (BranchModifier)
   - Calcula precios, envío, cupón
   - Procesa pago (cash/card/Stripe)
5. Redirect → tracking.tsx
6. OrderChanged broadcast → sucursal board
```

Service: `app/Services/PlaceOrder.php`

## Producto efectivo

La tienda muestra datos resueltos por sucursal (overrides):

- Nombre, descripción, precio, imágenes, tags
- Modificadores filtrados por disponibilidad sucursal

Service: `BranchProductPresentation`

Ver [Overrides](../sucursal/product-overrides.md).

## Tipos de entrega

| Tipo | Enum | Comportamiento |
|---|---|---|
| Delivery | `OrderDeliveryType::Delivery` | Requiere dirección, cobra envío |
| Pickup | `OrderDeliveryType::Pickup` | Sin dirección, sin envío |
| Dine-in | `OrderDeliveryType::DineIn` | Comer en sucursal |

## Pagos

Config por cadena: `ChainPaymentSetting` + `ChainPaymentSettingsService`.

| Método | Comportamiento |
|---|---|
| Efectivo | Sin integración externa |
| Stripe | `ChainStripeGateway` / StripeGateway vía settings de cadena |
| PayPal | `PaypalGateway` + return/cancel en rutas order |

Checkout: `POST /t/{chain}/{branch}/order` y flujos `stripe` / `paypal` en `routes/tienda.php`.

Webhook plataforma: `POST /api/v1/payments/webhook/stripe`

## Cupones

```
POST /t/{chain}/{branch}/cupon
```

Validación en PlaceOrder: fecha, branch, monto mínimo, usos.

## Cliente autenticado

- Login: `/t/{chain}/entrar`
- Historial: `/mis-pedidos`
- Perfil: `CustomerProfile`, `CustomerAddress`
- CRM: `ChainCustomer` (visible en Franquicia)

## Tracking en vivo

`tracking.tsx`:
- Estado pedido en tiempo real (Echo canal `order.{id}`)
- Chat con repartidor/sucursal
- Calificación post-entrega
- Web push para updates

## Branding

White-label via `ChainBranding` — colores, logo, favicon por cadena.

Compartido en layout tienda desde controller/middleware.

## Imágenes producto

Rutas autenticadas/públicas para imágenes privadas:

```
GET /t/{chain}/{branch}/product/{product}/image/{image?}
```

## Debugging tienda

| Problema | Verificar |
|---|---|
| Producto no aparece | is_active, branch_products.is_available, horario |
| Precio incorrecto | BranchProductPresentation, price_override |
| Checkout falla | PlaceOrder logs, modificador unavailable |
| Pago tarjeta | STRIPE_* keys, FakeGateway fallback |
| Cupón rechazado | Coupon rules, branch_id, dates |
| Cadena no carga | EnsureChainIsOperational, ChainStatus |

Test principal: `tests/Feature/TiendaTest.php`

## Demo

```
http://localhost/t/pollo-crazy/1
```

(branch ID depende del seeder — verificar en DB o seeders)

## Añadir funcionalidad a tienda

1. Ruta en `routes/tienda.php`
2. Método en `TiendaController` (o controller dedicado si crece)
3. Page en `resources/js/pages/tienda/`
4. Componentes en `components/tienda/`
5. Test en `TiendaTest.php`

Ver [Nueva página Inertia](../integration/inertia-page.md).
