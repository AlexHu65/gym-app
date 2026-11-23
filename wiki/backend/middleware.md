# Middleware

Ubicación: `sline-webapp/app/Http/Middleware/`

## Middleware globales y por ruta

| Middleware | Alias | Usado en |
|---|---|---|
| `ResolveTenant` | — | Franquicia panel, algunas rutas web |
| `ResolveBranch` | `branch` | Rutas `sucursal.*` autenticadas |
| `ValidateUser` | `validate.user` | Staff autenticado (post-login gate) |
| `EnsureChainIsOperational` | `chain.operational` | Rutas tienda |
| `HandleInertiaRequests` | — | Stack web (comparte props) |
| `HandleAppearance` | — | Tema claro/oscuro |
| `ApplyAdminPanelBranding` | — | Panel Admin Filament |
| `ApplyFranquiciaPanelBranding` | — | Panel Franquicia Filament |

## ResolveTenant

**Archivo:** `ResolveTenant.php`

Resuelve cadena activa (ver [Multi-tenancy](../architecture/multi-tenancy.md)):
1. Custom domain
2. Subdomain
3. Header `X-Tenant`
4. `users.tenant_id`

Establece singleton `CurrentTenant`.

## ResolveBranch

**Archivo:** `ResolveBranch.php`

Requisitos:
- Usuario autenticado con `type = branch`
- Al menos una sucursal en pivot `branch_user`

Acciones:
1. Lee/valida `session('sucursal_branch_id')`
2. Carga branch con chain
3. Establece tenant via chain
4. `$request->attributes->set('branch', $branch)`
5. `Inertia::share('sucursal', ...)` — branding, branches, platforms
6. `Inertia::share('impersonation', ...)` — banner si admin impersona

**Acceder a branch en controller:**

```php
$branch = $request->attributes->get('branch');
// o inyectar desde route si se bindea
```

## ValidateUser

**Archivo:** `ValidateUser.php`

Gate post-autenticación para staff. Redirige a:
- Verificación email si `email_verified_at` es null
- `/password/initial` si `password_changed_at` es null

Excluye: rutas auth, Filament auth pages, Livewire.

**Bug común:** usuario nuevo no puede acceder a paneles → falta verificar email o establecer password.

## EnsureChainIsOperational

**Archivo:** `EnsureChainIsOperational.php`

Bloquea tienda si cadena está pausada, suspendida o en onboarding incompleto.

## HandleInertiaRequests

**Archivo:** `HandleInertiaRequests.php`

Comparte props globales a todas las páginas Inertia:
- `auth.user`
- `flash` messages
- `appearance`
- Configuración app

Extender aquí para props compartidas nuevas.

## Filament auth middleware

En panel providers:

```php
->authMiddleware([
    Authenticate::class,
    ValidateUser::class,
])
```

Franquicia además incluye `ResolveTenant` en middleware stack.

## Registrar middleware nuevo

```php
// bootstrap/app.php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'branch' => ResolveBranch::class,
        'validate.user' => ValidateUser::class,
        'chain.operational' => EnsureChainIsOperational::class,
    ]);
})
```

## Orden de ejecución en sucursal

```
web stack
→ auth (session)
→ branch (ResolveBranch)
→ validate.user (ValidateUser)
→ controller
```

Definido en `routes/sucursal.php`:

```php
Route::middleware(['auth', 'branch', 'validate.user'])->group(function () {
    // rutas autenticadas sucursal
});
```

## Debugging middleware

```php
// Temporal en controller
logger('branch', [$request->attributes->get('branch')?->id]);
logger('tenant', [current_tenant_id()]);
```

O inspeccionar session:

```bash
vendor/bin/sail artisan tinker --execute 'session()->get("sucursal_branch_id")'
```

## Añadir middleware a una ruta nueva

1. Crear clase: `vendor/bin/sail artisan make:middleware MiMiddleware`
2. Registrar alias en `bootstrap/app.php`
3. Aplicar en route group o controller constructor
4. Escribir test Feature que verifique 403/200 según caso
