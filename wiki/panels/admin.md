# Panel Admin Sline

**URL:** `/admin`  
**Stack:** Filament v5  
**Usuario:** `UserType::Admin`  
**Provider:** `app/Providers/Filament/AdminPanelProvider.php`

## Propósito

Panel interno del equipo Sline. Gestión de plataforma, cadenas, cobranza SaaS y operación global.

## Acceso

```php
// User::canAccessPanel()
'type' === UserType::Admin && is_active
```

Login: `app/Filament/Admin/Auth/Login.php`

Demo: `admin@sline.app` / `password`

## Grupos de navegación

### Plataforma

| Resource | Modelo | Función |
|---|---|---|
| `Chains/ChainResource` | Chain | CRUD cadenas + relation managers |
| `Plans/PlanResource` | Plan | Planes SaaS |
| `Admins/AdminResource` | User (admin) | Admins Sline |
| `Users/UserResource` | User (todos) | Todos los usuarios |
| `ChainDocuments/ChainDocumentResource` | ChainDocument | Documentos legales |
| `AuditLogs/AuditLogResource` | AuditLog | Auditoría (read-only) |

**ChainResource relation managers:**
- Branches, Categories, Products, Users, Documents

### Cobranza

| Resource | Modelo | Función |
|---|---|---|
| `Subscriptions/SubscriptionResource` | Subscription | Suscripciones activas |
| `Invoices/InvoiceResource` | Invoice | Facturas SaaS |
| `InvoicePayments/InvoicePaymentResource` | InvoicePayment | Pagos recibidos |

### Operación

| Resource | Modelo | Función |
|---|---|---|
| `Orders/OrderResource` | Order | Pedidos globales (read/manage) |
| `Drivers/DriverResource` | User (driver) | Repartidores globales |

## Pages

| Page | Grupo | Función |
|---|---|---|
| `AdminDashboard` | — | KPIs plataforma, salud, actividad cadenas |
| `BillingDashboard` | Cobranza | Overview facturación |

## Widgets

| Widget | Ubicación | Función |
|---|---|---|
| `AdminStatsOverview` | Dashboard | KPIs generales |
| `PlatformHealthOverview` | Dashboard | Salud técnica |
| `ChainsActivityTable` | Dashboard | Tabla actividad cadenas |
| `BillingOverview` | BillingDashboard | Resumen cobranza |
| `BillingClientsTable` | BillingDashboard | Clientes con deuda |

## Branding

- `SlineBranding` — colores/assets Sline
- Views: `resources/views/filament/admin/`
- Middleware: `ApplyAdminPanelBranding`

## Impersonation

Admin owner puede impersonar cadenas y sucursales:

```
POST /impersonation/chains/{chain}
POST /impersonation/branches/{branch}
POST /impersonation/stop
```

Servicio: `ImpersonationService`  
Controller: `ImpersonationController`

## Archivos clave

```
app/Filament/
├── Admin/Auth/
├── Pages/AdminDashboard.php, BillingDashboard.php
├── Resources/
│   ├── Chains/
│   ├── Plans/
│   ├── Invoices/
│   └── ...
└── Widgets/
```

## Añadir funcionalidad al Admin

1. Decidir si es Resource, Page o Widget
2. `vendor/bin/sail artisan make:filament-resource Nombre --panel=admin`
3. Sin scope tenant — queries globales
4. Test en `tests/Feature/Filament/`

Ver [Nuevo recurso Filament](../integration/filament-resource.md).

## Debugging Admin

| Problema | Verificar |
|---|---|
| 403 login | `type=admin`, `is_active=true` |
| No ve cadena | ChainResource query sin filtros erróneos |
| Billing incorrecto | `SubscriptionBillingService`, command `billing:generate-invoices` |
| Impersonation falla | `canImpersonate()` — solo admin owner |

## Multi-tenancy en Admin

Admin **no** usa `TenantScope`. Ve datos de todas las cadenas. Al editar recursos de cadena, usar relation managers o filtros explícitos por `tenant_id`.
