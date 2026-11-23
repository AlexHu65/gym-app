# Multi-tenancy

Sline usa **multi-tenancy single-database** con columna `tenant_id` en las tablas de negocio.

## Conceptos

| Término | Modelo | Descripción |
|---|---|---|
| Tenant / Cadena | `Chain` | Franquicia dueña de datos |
| Sucursal | `Branch` | Punto de venta dentro de una cadena |
| `tenant_id` | FK a `chains.id` | Aislamiento de datos |

## Resolución del tenant activo

El middleware `ResolveTenant` establece el tenant en este orden:

1. **Dominio personalizado** — `chains.custom_domain`
2. **Subdominio** — `{slug}.{tenant_root_domain}`
3. **Header `X-Tenant`** — slug de la cadena
4. **Usuario autenticado** — `users.tenant_id`

```php
// app/Http/Middleware/ResolveTenant.php
$chain = $this->fromDomain($request)
    ?? $this->fromHeader($request)
    ?? $this->fromUser($request);

if ($chain !== null) {
    $this->tenant->set($chain);
}
```

### Singleton CurrentTenant

```php
// app/Support/CurrentTenant.php
// Helper global:
current_tenant_id(); // int|null
```

## Global scope TenantScope

Modelos con trait `BelongsToTenant` aplican automáticamente:

```php
WHERE tenant_id = current_tenant_id()
```

### Modelos con scope de tenant

- `Branch`, `Category`, `Product`
- `Order`, `Shipment`
- `Coupon`, `Banner`
- `ModifierGroup`

### Cuándo omitir el scope

Para operaciones cross-tenant (admin) o al resolver sucursal:

```php
Branch::withoutGlobalScopes()->find($id);
Chain::query()->find($tenantId); // Chain no tiene scope
```

## Contexto por superficie

| Superficie | Cómo se resuelve el tenant |
|---|---|
| Admin (`/admin`) | Sin scope automático; admin ve todo |
| Franquicia (`/franquicia`) | `ResolveTenant` desde usuario `type=chain` |
| Sucursal (`/sucursal`) | `ResolveBranch` → carga chain de la sucursal |
| Tienda (`/t/{slug}/{branch}`) | Route model binding por `Chain.slug` |
| API repartidor | Usuario driver con `tenant_id` |

## Autorización en runtime (Franquicia)

Además del aislamiento de datos por `tenant_id`, el panel Franquicia aplica permisos por rol tenant:

| Pieza | Rol |
|---|---|
| `ChainAuthorization` | Evalúa `{modulo}.{accion}` del usuario chain |
| `AuthorizesChainPermissions` | Trait Filament → `canViewAny` / create / edit / delete |
| `users.tenant_role_id` | Rol activo del staff |
| Helper `chain_user_can()` | Checks ad-hoc en páginas/actions |

Ver [RBAC Franquicia](../RBAC-FRANQUICIA.md).

## ResolveBranch (portal sucursal)

Además del tenant, establece la sucursal activa:

```php
// Session key: sucursal_branch_id
// Validado contra branch_user pivot
$request->attributes->set('branch', $branch);
Inertia::share('sucursal', [...]);
```

Archivo: `app/Http/Middleware/ResolveBranch.php`

## White-label y branding

- `ChainBranding` — colores, logo, favicon por cadena (Franquicia, Sucursal, Tienda)
- `SlineBranding` — branding del panel Admin
- `BrandingResource` en Franquicia — edición de marca
- `MailBranding` — mismos logo/colores aplicados a correos transaccionales ([Correos branded](../domains/emails.md))

## Dominios y subdominios

Configuración en `config/app.php`:

```php
'tenant_root_domain' => env('TENANT_ROOT_DOMAIN'),
```

Ejemplo: `pollocrazy.sline.app` resuelve cadena con `slug=pollocrazy`.

## Checklist al añadir un modelo multi-tenant

1. Añadir columna `tenant_id` en migración (FK a `chains`)
2. Aplicar trait `BelongsToTenant` al modelo
3. En Filament Franquicia, el scope filtra automáticamente
4. En Admin, usar queries explícitas o relation managers
5. En tests, crear `Chain` y asignar `tenant_id` en factories
6. Nunca confiar en `tenant_id` del request del cliente — derivarlo del contexto autenticado

## Bugs comunes de multi-tenancy

| Síntoma | Causa probable | Solución |
|---|---|---|
| Datos de otra cadena visibles | Query sin scope en Admin | Revisar Eloquent query / Filament resource |
| 403 en Franquicia | Usuario sin `tenant_id` | Verificar provisioning del usuario chain |
| Producto no aparece en tienda | Scope + `is_active` + override sucursal | Revisar `BranchProductPresentation` |
| Pedido en sucursal incorrecta | Session `sucursal_branch_id` stale | Verificar pivot `branch_user` |
