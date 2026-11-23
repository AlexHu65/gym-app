# Convenciones del proyecto

## PHP / Laravel

- PHP 8.3+ con tipos explícitos en parámetros y retorno
- Constructor property promotion
- Enums con TitleCase: `OrderStatus::InKitchen`
- PHPDoc con array shapes para estructuras complejas
- Lógica de negocio en `app/Services/`, no en controllers
- Crear archivos con Artisan: `vendor/bin/sail artisan make:...`
- Formatear con Pint: `vendor/bin/sail bin pint --dirty`

### Estructura de un Service

```php
// app/Services/MiServicio.php
namespace App\Services;

class MiServicio
{
    public function __construct(
        protected OtraDependencia $dep,
    ) {}

    public function ejecutar(Modelo $modelo, array $data): Resultado
    {
        // lógica de negocio
    }
}
```

Inyectar en controller vía constructor o `app(MiServicio::class)`.

## Filament v5

- Crear recursos: `vendor/bin/sail artisan make:filament-resource Nombre --panel=franquicia`
- Namespaces correctos:
  - Forms: `Filament\Forms\Components\`
  - Layout: `Filament\Schemas\Components\`
  - Tables: `Filament\Tables\Columns\`
  - Actions: `Filament\Actions\` (nunca sub-namespaces)
- Archivos privados: `->visibility('private')` por defecto; usar URLs firmadas
- Seguir estructura de recursos existentes: `Resource.php`, `Schemas/Form.php`, `Tables/Table.php`, `Pages/`

Ver [Nuevo recurso Filament](../integration/filament-resource.md).

## Inertia + React

- Páginas en `resources/js/pages/{area}/`
- Componentes reutilizables en `resources/js/components/`
- Layouts en `resources/js/layouts/`
- Rutas tipadas con Wayfinder: `@/routes/`, `@/actions/`
- UI: Radix + Tailwind 4 (estilo shadcn en `components/ui/`)
- Iconos: `lucide-react`
- Toasts: `sonner`

Ver [Inertia + React](../frontend/inertia-react.md).

## Tests (Pest)

- Feature tests por defecto en `tests/Feature/`
- `RefreshDatabase` en tests que tocan DB
- Factories con states: `User::factory()->chain()->create()`
- Filament: `Pest\Livewire\livewire()`
- Ejecutar mínimo necesario: `--filter=nombreTest`

## Commits

Formato Conventional Commits en español imperativo:

```
feat(sucursal): añade filtro por turno en reportes
fix(billing): corrige cálculo de comisión en factura
refactor(orders): extrae validación de cupón a servicio
```

Tipos: `feat`, `fix`, `hotfix`, `docs`, `style`, `refactor`, `test`, `chore`, `perf`, `ci`, `build`.

## Naming

| Elemento | Convención | Ejemplo |
|---|---|---|
| Modelos | Singular PascalCase | `Order`, `BranchProduct` |
| Services | Verbo + sustantivo | `PlaceOrder`, `CreateShipment` |
| Enums | Sustantivo + Status/Type/Role | `OrderStatus`, `UserType` |
| Controllers Sucursal | `Sucursal*Controller` | `SucursalOrderController` |
| Pages React | kebab-case | `board.tsx`, `order.tsx` |
| Filament Resources | `{Model}Resource` | `OrderResource` |

## Qué NO hacer

- No crear carpetas base nuevas sin consenso (`app/Repositories/`, `app/DTOs/`)
- No cambiar dependencias sin aprobación
- No usar Policies cuando el proyecto usa middleware + scopes
- No hardcodear URLs en frontend — usar Wayfinder
- No asumir visibilidad pública de archivos subidos
- No ejecutar artisan en el host cuando Sail está activo

## Skills del proyecto

En `sline-webapp/.agents/skills/` hay guías especializadas:

| Skill | Cuándo activar |
|---|---|
| `filament-security-audit` | Auditoría de seguridad Filament |
| `inertia-react-development` | Páginas React/Inertia |
| `fortify-development` | Auth, 2FA, passkeys |
| `pest-testing` | Escribir o arreglar tests |
| `echo-development` | Broadcasting, Echo |
| `wayfinder-development` | Rutas tipadas frontend |
| `laravel-best-practices` | Código PHP Laravel |
