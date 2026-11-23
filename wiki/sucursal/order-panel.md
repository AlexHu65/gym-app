# Panel De Sucursal · Pedidos Delivery Y Pickup

Este documento describe el comportamiento implementado para el panel de sucursal de Sline (`/sucursal`) y su detalle de pedidos (`/sucursal/orders/{order}`).

## Alcance

El panel de sucursal es una experiencia Inertia + React para operar pedidos en vivo. Permite visualizar:

- Número de pedido (`public_code`).
- Productos solicitados.
- Modificadores del pedido, usados en UI como ingredientes o extras.
- Estado del pedido con etiqueta legible.
- Tiempo estimado de preparación.
- Tipo de entrega: `Delivery`, `Recoger en tienda` o `Comer en` (`DineIn`).
- Pedidos programados (`scheduled_for`) con indicador de lock.
- Mensajes al cliente desde el detalle.
- Repartidor asignado cuando el pedido es delivery.
- Flujo específico para pickup en tienda.
- Flujo específico para delivery.

## Rutas Principales

- `GET /sucursal`: tablero Kanban de pedidos.
- `GET /sucursal/orders/{order}`: detalle del pedido.
- `POST /sucursal/orders/{order}/accept`: aceptar pedido y definir ETA.
- `POST /sucursal/orders/{order}/in-kitchen`: mover a cocina.
- `POST /sucursal/orders/{order}/ready`: marcar listo.
- `POST /sucursal/orders/{order}/assign`: asignar repartidor.
- `POST /sucursal/orders/{order}/picked-up`: marcar pedido pickup como recogido.
- `POST /sucursal/orders/{order}/reject`: rechazar/cancelar pedido.
- `POST /sucursal/orders/{order}/messages`: mensaje de chat del pedido.

## Pedidos programados

- Campo `scheduled_for` + labels vía `ScheduledOrderTime`.
- `Order::isScheduleLocked()`: locked mientras `now() < scheduled_for`.
- Tablero (`board.tsx`) y detalle (`order.tsx`) muestran horario programado y estado de lock.

## Modelo De Entrega

Los pedidos tienen `delivery_type`:

- `delivery`: pedido con dirección, distancia, costo de envío y posible repartidor.
- `pickup`: pedido para recoger en tienda, sin dirección, sin costo de envío y sin repartidor.
- `dine_in`: comer en sucursal (`OrderDeliveryType::DineIn`); sin envío ni repartidor.

La enum vive en:

```text
sline-webapp/app/Enums/OrderDeliveryType.php
```

La migración que agrega este campo y permite dirección nullable para pickup vive en:

```text
sline-webapp/database/migrations/2026_06_04_000001_add_delivery_type_to_orders_table.php
```

## Flujo Del Tablero

El tablero (`resources/js/pages/sucursal/board.tsx`) organiza pedidos en columnas:

- Nuevos: `pending`, `accepted`
- En cocina: `in_kitchen`
- Listos: `ready_for_pickup`
- En camino: `on_the_way`
- Entregados: `delivered`

Cada tarjeta muestra tipo de entrega, estado, cliente, productos, modificadores, forma de pago, distancia si aplica, ETA/preparación y repartidor si aplica.

## Flujo Delivery

1. El cliente crea un pedido con `delivery_type=delivery`.
2. La sucursal acepta el pedido.
3. La sucursal lo mueve a cocina.
4. La sucursal lo marca listo.
5. El pedido aparece en la columna **Listos**.
6. La sucursal asigna un repartidor.
7. Al asignar repartidor, el pedido cambia inmediatamente a `on_the_way`.
8. El tablero lo mueve a la columna **En camino**.

Nota: este comportamiento fue cambiado intencionalmente. Antes, asignar repartidor solo llenaba `driver_id` y dejaba el pedido en `ready_for_pickup`; ahora asignar repartidor también hace la transición a `on_the_way`.

## Flujo Pickup

1. El cliente crea un pedido con `delivery_type=pickup`.
2. No se requiere dirección.
3. No se cobra envío.
4. No se permite asignar repartidor.
5. Cuando el pedido está listo (`ready_for_pickup`), la sucursal usa **Marcar recogido**.
6. El pedido cambia a `delivered`.

## Datos Que Alimentan Inertia

Los props del tablero y detalle se arman en:

```text
sline-webapp/app/Http/Controllers/Sucursal/SucursalController.php
```

El helper principal es `orderCard()`. Incluye:

- `public_code`
- `status`
- `status_label`
- `delivery_type`
- `delivery_type_label`
- `estimated_ready_at`
- `prep_minutes`
- `driver`
- `items[].modifiers`

El detalle de pedido reutiliza esos datos y agrega notas, dirección y datos completos de cada item.

## Seeder Demo

Para que el flujo sea visible después de:

```bash
docker exec sline-webapp-laravel.test-1 php artisan migrate:fresh --seed
```

se agregó:

```text
sline-webapp/database/seeders/SucursalOrderDemoSeeder.php
```

Este seeder crea pedidos demostrativos para la primera sucursal de `Pollo Crazy`:

- `DEMO1`: delivery pendiente.
- `DEMO2`: pickup aceptado.
- `DEMO3`: delivery en cocina.
- `DEMO4`: pickup listo para recoger.
- `DEMO5`: delivery listo, sin repartidor, ideal para probar asignación.
- `DEMO6`: delivery en camino, con repartidor asignado.

Cada pedido demo incluye productos, modificadores y ETA de preparación.

Para probar el caso reportado:

1. Ejecuta:

```bash
docker exec sline-webapp-laravel.test-1 php artisan migrate:fresh --seed
```

2. Abre:

```text
http://localhost/sucursal/orders/5
```

3. Asigna un repartidor.
4. El pedido `DEMO5` debe cambiar de `ready_for_pickup` a `on_the_way`.
5. En el tablero debe moverse de **Listos** a **En camino**.

## Verificación Técnica

Pruebas afectadas:

```bash
docker exec sline-webapp-laravel.test-1 php artisan test tests/Feature/SucursalPortalTest.php tests/Feature/PushNotificationTest.php
```

También se validó:

```bash
docker exec sline-webapp-laravel.test-1 php artisan test tests/Feature/TiendaTest.php tests/Feature/SucursalPortalTest.php
```

## Archivos Relacionados

- `sline-webapp/app/Enums/OrderDeliveryType.php`
- `sline-webapp/app/Http/Controllers/Sucursal/SucursalController.php`
- `sline-webapp/app/Http/Controllers/Sucursal/SucursalOrderController.php`
- `sline-webapp/app/Http/Controllers/Tienda/TiendaController.php`
- `sline-webapp/app/Services/PlaceOrder.php`
- `sline-webapp/database/seeders/SucursalOrderDemoSeeder.php`
- `sline-webapp/resources/js/pages/sucursal/board.tsx`
- `sline-webapp/resources/js/pages/sucursal/order.tsx`
- `sline-webapp/resources/js/pages/tienda/checkout.tsx`
- `sline-webapp/resources/js/pages/tienda/tracking.tsx`
