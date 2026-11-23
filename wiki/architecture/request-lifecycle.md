# Ciclo de vida de una petición

## Pipeline general (web)

```
HTTP Request
  → TrustProxies (Railway HTTPS)
  → Web middleware stack
  → ResolveTenant (Franquicia, algunas rutas web)
  → auth / guest
  → ValidateUser (email verificado + password_changed_at)
  → ResolveBranch (Sucursal)
  → EnsureChainIsOperational (Tienda)
  → HandleInertiaRequests (comparte props globales)
  → Controller / Filament Livewire
  → Response
```

## Middleware clave

| Middleware | Archivo | Función |
|---|---|---|
| `ResolveTenant` | `Http/Middleware/ResolveTenant.php` | Establece cadena activa |
| `ResolveBranch` | `Http/Middleware/ResolveBranch.php` | Sucursal activa + Inertia share |
| `ValidateUser` | `Http/Middleware/ValidateUser.php` | Bloquea sin email verificado o password inicial |
| `EnsureChainIsOperational` | `Http/Middleware/EnsureChainIsOperational.php` | Cadena activa para tienda |
| `HandleInertiaRequests` | `Http/Middleware/HandleInertiaRequests.php` | Props compartidas Inertia |
| `ApplyAdminPanelBranding` | Filament Admin | Branding Sline |
| `ApplyFranquiciaPanelBranding` | Filament Franquicia | Branding por cadena |

### ValidateUser — gate de acceso staff

Bloquea usuarios que:
1. No han verificado email (`email_verified_at` null)
2. No han establecido contraseña (`password_changed_at` null)

Excepciones: rutas de auth, Filament auth, Livewire updates.

### Redirecciones post-login

`App\Support\AuthRedirect` determina home por `UserType`:

| Type | Destino |
|---|---|
| `admin` | `/admin` |
| `chain`, `driver` | `/franquicia` |
| `branch` | `/sucursal` |
| `customer` | `/mis-pedidos` |

## Filament vs Inertia

### Filament (Admin / Franquicia)

```
Request → Panel middleware → Filament Authenticate
  → ValidateUser → Livewire component
  → Resource/Page/Widget (PHP)
  → Blade + Livewire response
```

Panel providers:
- `app/Providers/Filament/AdminPanelProvider.php`
- `app/Providers/Filament/FranquiciaPanelProvider.php`

Acceso: `User::canAccessPanel(Panel $panel)`

### Inertia (Sucursal / Tienda)

```
Request → Controller
  → Inertia::render('page/name', $props)
  → JSON page object + shared props
  → React render client-side
```

Entry frontend: `resources/js/app.tsx`

Layout routing en `app.tsx`:
- `sucursal/*` → `SucursalLayout`
- `tienda/*` → `TiendaLayout`
- `auth/*` → `AuthLayout`

## API (`/api/v1/*`)

Registrada en `bootstrap/app.php` con prefix `api/v1`.

```
Request → Sanctum auth (Bearer token)
  → Controller
  → JSON response
```

Sin Inertia. Usada exclusivamente por la app Expo repartidor.

## Broadcasting

Canales definidos en `routes/channels.php`:

| Canal | Quién puede suscribirse |
|---|---|
| `branch.{branch}.orders` | Staff cadena del tenant + usuarios de esa sucursal + repartidores |
| `order.{order}` | Admin, cadena, sucursal, cliente del pedido, repartidor asignado |

Evento principal: `App\Events\OrderChanged` → broadcast como `order.changed`.

## Colas y jobs

`SendOrderNotification` se encola al cambiar estado de pedido:
- Repartidor → `ExpoPushSender`
- Cliente → `WebPushSender`
- Log en `notifications_outbox`

**Producción**: requiere worker de cola activo (`php artisan queue:work`).

## Archivos de rutas

| Archivo | Prefix | Middleware típico |
|---|---|---|
| `routes/web.php` | `/` | auth, web |
| `routes/sucursal.php` | `sucursal` | auth, branch, validate.user |
| `routes/tienda.php` | `t/{chain:slug}` | chain.operational |
| `routes/api.php` | `api/v1` | sanctum (autenticadas) |
| `routes/settings.php` | `settings` | auth, validate.user |
| `routes/channels.php` | — | broadcasting auth |

Inspeccionar rutas:

```bash
vendor/bin/sail artisan route:list --path=sucursal
vendor/bin/sail artisan route:list --path=api
```
