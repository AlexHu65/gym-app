# Dominio: Clientes

## Modelos

| Modelo | Función |
|---|---|
| `User` (type=customer) | Cuenta cliente |
| `CustomerProfile` | Datos perfil |
| `CustomerAddress` | Direcciones guardadas |
| `CustomerPaymentMethod` | Métodos pago (Stripe) |
| `ChainCustomer` | CRM: relación cliente-cadena |

## Creación de clientes

| Flujo | Dónde |
|---|---|
| Checkout guest | PlaceOrder crea User + CustomerProfile |
| Login tienda | `/t/{chain}/entrar` |
| CRM manual | Franquicia → Customers |

## CRM en Franquicia

Resource: `Customers/CustomerResource`

| Aspecto | Valor |
|---|---|
| Grupo nav | Marketing |
| Label | Usuarios registrados |
| Módulo RBAC | `customers` (`customers.view/create/edit/delete`) |
| Trait | `AuthorizesChainPermissions` |

`CanManageFranquiciaCustomers` ya no es el gate principal.

Features:
- Listado clientes de la cadena
- Historial pedidos
- Datos contacto
- Acciones header custom (`CustomerHeaderActions`)

Service: `ChainCustomerService`

## Direcciones

Modelo: `CustomerAddress`

Usadas en checkout delivery. Geocoding para calcular distancia/costo envío.

## Pagos guardados

Modelo: `CustomerPaymentMethod`

Integración Stripe para tarjetas recurrentes (si habilitado).

## Historial pedidos

Ruta: `GET /mis-pedidos`  
Page: `resources/js/pages/tienda/orders.tsx`

Requiere auth customer.

## Tracking

Ruta: `GET /t/{chain}/seguimiento/{order}`

Features:
- Estado realtime (Echo)
- Chat
- Calificación post-entrega (`Rating`)
- Web push subscription

## Web Push

Lib: `resources/js/lib/web-push.ts`  
Service: `WebPushSender`  
Config: VAPID keys en `.env`

Cliente se suscribe en tracking page.

## Archivos clave

```
app/Models/CustomerProfile.php, CustomerAddress.php, ChainCustomer.php
app/Services/ChainCustomerService.php
app/Http/Controllers/Tienda/TiendaController.php
app/Filament/Franquicia/Resources/Customers/
resources/js/pages/tienda/orders.tsx, tracking.tsx
resources/js/lib/web-push.ts
tests/Feature/TiendaTest.php
```

## Debugging

| Problema | Verificar |
|---|---|
| Cliente no ve pedidos | Auth, order.customer_id |
| Dirección rechazada | Geocoder, delivery radius |
| Push no funciona | VAPID keys, subscription browser |
| No aparece en CRM | ChainCustomer pivot |
