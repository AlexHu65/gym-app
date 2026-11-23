# Dominio: Usuarios y autenticación

## Modelo unificado

Todos los actores usan tabla `users` con discriminador `type`.

| UserType | Descripción | Panel/Home |
|---|---|---|
| `Admin` | Equipo Sline | `/admin` |
| `Chain` | Staff cadena | `/franquicia` |
| `Branch` | Staff sucursal | `/sucursal` |
| `Driver` | Repartidor | API móvil |
| `Customer` | Comensal | `/mis-pedidos` |

## Roles por type

| Enum | Para | Ejemplos |
|---|---|---|
| `AdminRole` | Admin | Owner, Support |
| `ChainRole` | Chain (legacy slug) | Owner, Manager, Admin |
| `BranchRole` | Branch (pivot) | Lead, Staff |

### Rol tenant (cadena)

Staff chain tiene `users.tenant_role_id` → `TenantRole`.  
Fuente preferida de permisos en `/franquicia`. Ver [RBAC Franquicia](../RBAC-FRANQUICIA.md).

El slug `users.role` se mantiene alineado con roles sistema vía `SyncsChainUserTenantRole`.

## Autenticación web (Fortify)

Config: `config/fortify.php`

Features habilitadas:
- Reset password
- Email verification
- Two-factor authentication (TOTP)
- Passkeys (WebAuthn)

Registro deshabilitado — usuarios creados por admin/cadena.

### Flujo alta staff (admin / chain)

Service: `Usuarios/UserProvisioningService`

```
1. Admin/Cadena crea usuario
2. Sistema genera password temporal
3. Envía StaffInvitationNotification (verify + set password + rol + link al panel)
4. Usuario verifica email
5. Usuario establece password en /password/initial
6. ValidateUser permite acceso a paneles
```

Staff chain: al crear/editar también se asigna `tenant_role_id` (rol del catálogo tenant).

El correo de invitación es branded por cadena (logo/colores) e indica el rol
(`ChainRole` / `BranchRole`) y el enlace a franquicias o sucursales según `type`.
Detalle: [Correos branded](./emails.md).

### Gate ValidateUser

Bloquea hasta:
- `email_verified_at` presente
- `password_changed_at` presente

Ver [Middleware](../backend/middleware.md#validateuser).

## Autenticación API (Sanctum)

Para app repartidor:
- `POST /api/v1/auth/login` → token
- Token en header `Authorization: Bearer {token}`
- Model: `User` con trait `HasApiTokens`

## Pivot branch_user

Staff sucursal se asigna via pivot:

| Campo | Función |
|---|---|
| `user_id` | Staff |
| `branch_id` | Sucursal |
| `role` | BranchRole |
| `is_primary` | Sucursal principal |

Un usuario branch puede tener múltiples sucursales.

## Redirecciones

`App\Support\AuthRedirect`:

```php
match ($user->type) {
    UserType::Admin => '/admin',
    UserType::Chain, UserType::Driver => '/franquicia',
    UserType::Branch => '/sucursal',
    UserType::Customer => '/mis-pedidos',
};
```

## Acceso Filament

```php
// User::canAccessPanel()
'admin' => type === Admin
'franquicia' => type === Chain
```

En Franquicia, el acceso a recursos/páginas se filtra por permisos tenant (`ChainAuthorization`). Ver [RBAC Franquicia](../RBAC-FRANQUICIA.md).

## Impersonation

Solo admin owner (`canImpersonate()`):
- Impersonar cadena → accede Franquicia como chain user
- Impersonar sucursal → accede Sucursal como branch user

Service: `ImpersonationService`

## Fortify Actions

```
app/Actions/Fortify/
├── CreateNewUser.php
└── ResetUserPassword.php
```

## Gestión usuarios por superficie

| Superficie | Resource/Controller |
|---|---|
| Admin | `Users/UserResource`, `Admins/AdminResource` |
| Franquicia | `Users/ChainUserResource` |
| Sucursal | (no gestión — solo uso) |
| API | AuthController |

## Clientes

- Creados en checkout o login tienda (`/t/{chain}/entrar`)
- `CustomerProfile`, `CustomerAddress`, `CustomerPaymentMethod`
- CRM: `ChainCustomer` en Franquicia

## Archivos clave

```
app/Models/User.php
app/Enums/UserType.php, AdminRole.php, ChainRole.php, BranchRole.php
app/Models/TenantRole.php
app/Services/Usuarios/UserProvisioningService.php
app/Services/ImpersonationService.php
app/Services/ChainAuthorization.php
app/Http/Middleware/ValidateUser.php
app/Support/AuthRedirect.php
app/Providers/FortifyServiceProvider.php
config/fortify.php, config/auth.php
tests/Feature/Auth/
```

## Debugging auth

| Problema | Verificar |
|---|---|
| Redirect loop | ValidateUser + email/password status |
| Wrong panel | UserType vs canAccessPanel |
| 403 sucursal | branch_user pivot |
| API 401 API | Sanctum token, user type driver |
| 2FA issues | two_factor_confirmed_at, recovery codes |

Tests: `tests/Feature/Auth/*`, `ValidateUserMiddleware` filter

## Añadir tipo de usuario nuevo

⚠️ Cambio arquitectónico grande. Pasos:
1. Añadir case a `UserType`
2. Actualizar `AuthRedirect`
3. Definir panel/rutas home
4. Middleware de acceso
5. Factory state
6. Tests completos

Preferir usar roles existentes antes de crear nuevo type.
