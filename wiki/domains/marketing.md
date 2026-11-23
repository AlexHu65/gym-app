# Dominio: Marketing

Grupo Filament **Marketing** del panel Franquicia (`/franquicia`). Incluye banners, cupones, CRM de clientes registrados, carritos abandonados y (aparte) [encuestas](./surveys.md).

## Resources Filament

| Resource | Slug / rutas | Modelo | Módulo RBAC | Sort |
|---|---|---|---|---|
| `Banners/BannerResource` | `banners` | `Banner` | `banners` | 1 |
| `Coupons/CouponResource` | `coupons` | `Coupon` | `coupons` | 2 |
| `Customers/CustomerResource` | `customers` | `User` (customer) | `customers` | 3 |
| `AbandonedCarts/AbandonedCartResource` | `abandoned-carts` | `AbandonedCart` | `abandoned_carts` (solo `view`) | 4 |
| `Surveys/SurveyResource` | `surveys` | `Survey` | `surveys` | — |

Paths: `app/Filament/Franquicia/Resources/{Banners,Coupons,Customers,AbandonedCarts,Surveys}/`

Todos usan `AuthorizesChainPermissions` → `chainPermissionModule()`.

Catálogo: `config/chain-permissions.php` grupo `marketing`.

---

## Banners

### Modelo `Banner` — tabla `banners`

Archivo: `app/Models/Banner.php`  
Trait: `BelongsToTenant` (`tenant_id` → `chains`)

| Campo | Tipo / notas |
|---|---|
| `title`, `eyebrow` | Texto (eyebrow nullable, max 80) |
| `image_path` | Upload disco `public`, dir `banners` |
| `link_type` | enum `BannerLinkType` |
| `link_target` | ID categoría/producto o URL |
| `start_at`, `end_at` | Ventana de visualización (datetime) |
| `branch_ids` | array JSON; vacío = todas las sucursales |
| `sort_order`, `is_active` | Orden y flag |

Scope: `currentlyVisible()` — visible si `start_at`/`end_at` son nulos o el instante actual cae en el rango.

Métodos útiles: `resolveLinkUrl()`, `toCatalogPayload()` (`id`, `title`, `eyebrow`, `image_path`, `link_url`).

### Enum `BannerLinkType`

`app/Enums/BannerLinkType.php`

| Case | Valor | Label |
|---|---|---|
| `None` | `none` | Ninguno |
| `Category` | `category` | Categoría |
| `Product` | `product` | Producto |
| `Url` | `url` | URL |

### Ventana / timezone

| Pieza | Path | Rol |
|---|---|---|
| `BannerSchedule` | `app/Support/BannerSchedule.php` | Convierte form ↔ UTC con timezone de la cadena |
| `SyncsBannerScheduleForm` | `app/Filament/Franquicia/Concerns/SyncsBannerScheduleForm.php` | Trait en Create/Edit: muta `start_at`/`end_at` al fill/save |

Formulario: `Banners/Schemas/BannerForm.php`.

### Consumo en tienda

`TiendaController` (catálogo): banners `is_active` + `currentlyVisible()` filtrados por `branch_ids`.  
Ruta: `GET /t/{chain}/{branch}` → `tienda.catalog`.

---

## Cupones

### Modelo `Coupon` — tabla `coupons`

Archivo: `app/Models/Coupon.php`  
Unique: `(tenant_id, code)`

| Campo | Notas |
|---|---|
| `code`, `description` | Identificador y texto |
| `type` | `CouponType` |
| `value`, `min_order_amount`, `max_discount` | decimales |
| `usage_limit_total`, `usage_limit_per_user`, `used_count` | Límites |
| `start_at`, `end_at` | Vigencia |
| `branch_ids`, `sources`, `delivery_types` | arrays; vacío = aplica a todo |
| `is_active` | boolean |

Métodos: `appliesToSource(OrderSource)`, `appliesToDeliveryType(OrderDeliveryType)`.  
Relación: `redemptions()` → `CouponRedemption`.

### Enum `CouponType`

`app/Enums/CouponType.php`

| Case | Valor | Label |
|---|---|---|
| `Percentage` | `percentage` | Porcentaje |
| `Fixed` | `fixed` | Monto fijo |
| `FreeDelivery` | `free_delivery` | Envío gratis |
| `DeliveryPercentage` | `delivery_percentage` | Porcentaje en envío |

### Modelo `CouponRedemption` — tabla `coupon_redemptions`

Archivo: `app/Models/CouponRedemption.php`

Campos: `coupon_id`, `customer_id` → `User`, `order_id`, `discount_amount`, `created_at` (sin `updated_at`).

### Estadísticas

`ChainCouponStatistics` — `app/Services/ChainCouponStatistics.php`  
UI: `CouponStatisticsPage` (`/franquicia/estadisticas-cupones`) — ver [Estadísticas Franquicia](../panels/franquicia-statistics.md).

### Tienda

`POST /t/{chain}/{branch}/coupon` → `tienda.coupon` (validación en checkout).

Formulario Franquicia: `Coupons/Schemas/CouponForm.php` (secciones Cupón / Disponibilidad / Reglas de uso).

---

## Customers (CRM)

No hay modelo `Customer`. El resource opera sobre `User` con `UserType::Customer` + pivote `ChainCustomer`.

| Pieza | Path |
|---|---|
| Resource | `Customers/CustomerResource.php` |
| Modelo pivote | `app/Models/ChainCustomer.php` (`chain_customers`) |
| Perfil | `app/Models/CustomerProfile.php` |
| Service | `app/Services/ChainCustomerService.php` |
| Acciones header | `Customers/Actions/CustomerHeaderActions.php` |

Páginas: list / view / edit. **`canCreate()` = false** (no alta manual en UI).

Relation managers (solo lectura): Addresses, PaymentMethods, Ratings, Orders.

Detalle de dominio: [Clientes](./customers.md).

Permisos: `customers.view|create|edit|delete` (creación denegada en resource pese al catálogo).

---

## Carritos abandonados

### Modelo `AbandonedCart` — tabla `abandoned_carts`

Archivo: `app/Models/AbandonedCart.php`

| Campo | Notas |
|---|---|
| `tenant_id`, `customer_id`, `session_token` | Tenant + identidad (invitado por token) |
| `branch_id` | Sucursal |
| `items` | JSON |
| `checkout_step` | `cart` \| `checkout` \| `payment` |
| `last_activity_at`, `abandoned_at` | Actividad / abandono |

Scopes: `abandoned()`, `pending()`.  
Accessors: `customer_display_name`, `items_count`, `total`.

### `AbandonedCartTracker`

Archivo: `app/Services/AbandonedCartTracker.php`

| Método | Rol |
|---|---|
| `sync(...)` | Upsert carrito pendiente; items vacíos → `clear()` |
| `clear(...)` | Borra pendiente |
| `promoteStale(?minutes)` | Marca `abandoned_at` si inactivo ≥ N minutos |
| `abandonedAfterMinutes()` | `config('orders.abandoned_cart_minutes', 20)` |

Comando: `carts:promote-abandoned` (`PromoteAbandonedCarts`) — schedule cada minuto en `routes/console.php`.

Tienda: `POST /t/{chain}/{branch}/cart/sync` → `tienda.cart.sync`.

### Resource

`AbandonedCartResource` — solo `index` + `view`.  
`canCreate/canEdit/canDelete` = false. Query limitado a `->abandoned()`.  
Permiso: `abandoned_carts.view`.

---

## Encuestas

Documentadas en [Encuestas](./surveys.md) (`SurveyResource`, tienda pública `/t/{chain}/encuesta/{token}`).

---

## Roles sistema (marketing)

`TenantRoleProvisioner`:

| Rol | Marketing |
|---|---|
| `owner` | Todos los permisos |
| `manager` | Incluye módulos marketing (incl. `customers`) |
| `admin` | `banners`, `coupons`, `surveys`, `abandoned_carts`; **excluye** `customers` |

---

## Ver también

- [Panel Franquicia](../panels/franquicia.md)
- [Estadísticas Franquicia](../panels/franquicia-statistics.md)
- [Clientes](./customers.md)
- [Encuestas](./surveys.md)
