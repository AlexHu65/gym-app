# Dominio: Ajustes generales (Chain Settings)

Ajustes tipados por cadena con perfiles global y por sucursal. Catálogo EAV provisionado desde config; edición en Franquicia grupo **Administración**.

## Modelos

| Modelo | Tabla | Path |
|---|---|---|
| `ChainSettingGroup` | `chain_setting_groups` | `app/Models/ChainSettingGroup.php` |
| `ChainSettingDefinition` | `chain_setting_definitions` | `app/Models/ChainSettingDefinition.php` |
| `ChainSettingProfile` | `chain_setting_profiles` | `app/Models/ChainSettingProfile.php` |
| `ChainSettingValue` | `chain_setting_values` | `app/Models/ChainSettingValue.php` |

Todos los grupos/definiciones de sistema son tenant-scoped (`BelongsToTenant` donde aplica).

### `ChainSettingGroup`

`tenant_id`, `slug`, `name`, `description`, `is_system`.

### `ChainSettingDefinition`

`slug`, `label`, `group_key`, `type` (`text` \| `email` \| `tel` \| `url`), `is_system`, `storefront`.

### `ChainSettingProfile`

`tenant_id`, `branch_id` **nullable**.  
`scopeLabel()`: si `branch_id` es null → `"Toda la cadena (fallback global)"`.

### `ChainSettingValue`

EAV: `(profile_id, key, value)`.

## `ChainSettingStore`

Path: `app/Support/ChainSettingStore.php`

| Método | Rol |
|---|---|
| `resolved(Chain, ?Branch)` | branch profile → global profile → default `config/chain-settings.php` → null |
| `chainProfile()` | perfil `branch_id = null` |
| `branchProfile()` | un perfil por sucursal |
| `syncProfileValues()` / `setProfileValue()` | Validación por tipo; valor vacío borra fila (hereda fallback) |
| `contactPayload()` | Keys storefront para Inertia (`ChainBranding::payload()`) |
| `definitions()` / `groups()` | Catálogo del tenant |

## Provisioners

| Service | Path | Rol |
|---|---|---|
| `ChainSettingGroupProvisioner` | `app/Services/ChainSettingGroupProvisioner.php` | Sync grupos system desde `config/chain-settings.php`; `isInUse()` bloquea delete |
| `ChainSettingDefinitionProvisioner` | `app/Services/ChainSettingDefinitionProvisioner.php` | Sync keys; borra definitions system obsoletas; `isUsedInProfiles()` bloquea delete |

Cableados en `TenantRoleProvisioner::provision()`. Resync manual: `php artisan chain:provision-roles` (vía Sail según entorno).

Fuente de verdad del catálogo: `config/chain-settings.php` (`groups` + `keys`).

## Resources Filament

Panel `/franquicia`, grupo **Administración**. Trait `AuthorizesChainPermissions`.

| Resource | Slug | Modelo | Módulo RBAC |
|---|---|---|---|
| `GeneralSettings/GeneralSettingsResource` | `general-settings` | `ChainSettingProfile` | `general_settings` |
| `GeneralSettings/Groups/ChainSettingGroupResource` | `setting-groups` | `ChainSettingGroup` | `setting_groups` |
| `GeneralSettings/Definitions/ChainSettingDefinitionResource` | `setting-definitions` | `ChainSettingDefinition` | `setting_definitions` |

Rutas:

| Resource | Paths |
|---|---|
| General | `/franquicia/general-settings`, `/create`, `/{record}/edit` |
| Groups | `/franquicia/setting-groups`, … |
| Definitions | `/franquicia/setting-definitions`, … |

Permisos (`config/chain-permissions.php`, grupo `administracion`):  
`{general_settings|setting_groups|setting_definitions}.{view|create|edit|delete}`.

Roles: `owner` bypass; `admin` recibe los tres módulos; `manager` **excluido** (`TenantRoleProvisioner`).

## Perfiles: global vs sucursal

| Perfil | `branch_id` | Uso |
|---|---|---|
| Global | `null` | Fallback de toda la cadena |
| Sucursal | ID branch | Sobrescribe keys no vacías |

Resolución: valor sucursal (si no vacío) → global → default config.

En `GeneralSettingsForm`: select `branch_id` con opción global; **inmutable tras crear**.

Storefront:

- Landing `/t/{slug}` → solo perfil global
- Catálogo / checkout → perfil de la sucursal activa + fallback

## Ver también

- [Panel Franquicia](../panels/franquicia.md)
- [Multi-tenancy](../architecture/multi-tenancy.md)
- [Branding / tienda](../panels/tienda.md)
