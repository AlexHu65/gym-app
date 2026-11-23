# Integración: Nuevo recurso Filament

## Cuándo usar

CRUD, listados, dashboards administrativos para Admin o Franquicia.

## Crear resource

```bash
# Franquicia
vendor/bin/sail artisan make:filament-resource MiModelo --panel=franquicia --generate

# Admin
vendor/bin/sail artisan make:filament-resource MiModelo --panel=admin --generate
```

Genera: Resource, Form schema, Table, Pages (List, Create, Edit).

## Estructura de archivos

```
app/Filament/Franquicia/Resources/MiModelos/
├── MiModeloResource.php
├── Pages/
│   ├── ListMiModelos.php
│   ├── CreateMiModelo.php
│   └── EditMiModelo.php
├── Schemas/
│   └── MiModeloForm.php
└── Tables/
    └── MiModelosTable.php
```

Admin usa `app/Filament/Resources/` (sin subcarpeta Franquicia).

## Resource principal

```php
class MiModeloResource extends Resource
{
    protected static ?string $model = MiModelo::class;
    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedCube;
    protected static string | UnitEnum | null $navigationGroup = 'Operación';

    public static function form(Schema $schema): Schema
    {
        return MiModeloForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MiModelosTable::configure($table);
    }
}
```

## Form schema

```php
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;

Section::make('Datos')
    ->schema([
        TextInput::make('name')->required(),
        Select::make('status')->options(MiStatus::class),
    ]),
```

### Namespaces Filament v5

| Tipo | Namespace |
|---|---|
| Form fields | `Filament\Forms\Components\` |
| Layout | `Filament\Schemas\Components\` |
| Get/Set | `Filament\Schemas\Components\Utilities\` |
| Table columns | `Filament\Tables\Columns\` |
| Actions | `Filament\Actions\` |

## Multi-tenancy (Franquicia)

Si el modelo usa `BelongsToTenant`, el scope filtra automáticamente.

Al crear registros, asignar tenant:

```php
// En CreateMiModelo o mutator
protected function mutateFormDataBeforeCreate(array $data): array
{
    $data['tenant_id'] = current_tenant_id();
    return $data;
}
```

## Componentes custom existentes

Reutilizar de `app/Filament/Forms/`:
- `AddressFieldset` — direcciones
- `CoverageMapPicker` — mapa cobertura
- `GeoSelects` — selects geográficos

## Relation managers

Para relaciones HasMany en un Resource padre:

```bash
vendor/bin/sail artisan make:filament-relation-manager ChainResource branches name --panel=admin
```

## Widget en dashboard

```bash
vendor/bin/sail artisan make:filament-widget MiStats --panel=franquicia
```

Registrar en `FranquiciaDashboard` o page específica.

## Custom page

```bash
vendor/bin/sail artisan make:filament-page MiPagina --panel=franquicia
```

Para stats/reportes complejos. Ver `BranchStatisticsPage.php` como ejemplo.

## Testing

```php
use function Pest\Livewire\livewire;

$user = User::factory()->chain()->create();

livewire(ListMiModelos::class)
    ->assertCanSeeTableRecords($records);

livewire(CreateMiModelo::class)
    ->fillForm(['name' => 'Test'])
    ->call('create')
    ->assertNotified()
    ->assertHasNoFormErrors();
```

Ver [Testing](../development/testing.md).

## Checklist

- [ ] Model con factory
- [ ] Migración aplicada
- [ ] Resource en panel correcto
- [ ] Navigation group e icon
- [ ] tenant_id en create (Franquicia)
- [ ] Archivos private si sube files
- [ ] Test Livewire
- [ ] Pint

## Referencia

- [Panel Admin](../panels/admin.md)
- [Panel Franquicia](../panels/franquicia.md)
- AGENTS.md sección Filament
