# Integración: Migraciones y modelos

## Crear modelo completo

```bash
vendor/bin/sail artisan make:model MiModelo -mf
# -m migration, -f factory
```

Con seeder:
```bash
vendor/bin/sail artisan make:model MiModelo -mfs
```

## Migración

```php
Schema::create('mi_modelos', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tenant_id')->constrained('chains')->cascadeOnDelete();
    $table->string('name');
    $table->string('status')->default('active');
    $table->timestamps();

    $table->index(['tenant_id', 'created_at']);
});
```

### Convenciones

- `foreignId()->constrained()` para FKs
- Índices compuestos para queries frecuentes
- Enums como `string` column + cast en model
- Soft deletes solo si necesario (User los usa)

### Multi-tenant

Si el dato pertenece a una cadena:

```php
$table->foreignId('tenant_id')->constrained('chains');
```

## Modelo

```php
namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MiModelo extends Model
{
    use BelongsToTenant, HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'status' => MiStatus::class,
        ];
    }
}
```

Trait `BelongsToTenant` en `app/Models/Concerns/BelongsToTenant.php`:
- Añade global scope `TenantScope`
- Auto-set `tenant_id` on create si tenant resuelto

## Factory

```php
// database/factories/MiModeloFactory.php
class MiModeloFactory extends Factory
{
    public function definition(): array
    {
        return [
            'tenant_id' => Chain::factory(),
            'name' => fake()->words(2, true),
            'status' => MiStatus::Active,
        ];
    }
}
```

## Enum (si aplica)

```bash
vendor/bin/sail artisan make:enum MiStatus
```

```php
enum MiStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';

    public function getLabel(): string
    {
        return match ($this) {
            self::Active => 'Activo',
            self::Inactive => 'Inactivo',
        };
    }
}
```

## Seeder

```php
// database/seeders/MiModeloSeeder.php
MiModelo::factory(10)->create();
```

Registrar en `DatabaseSeeder` si aplica.

## Aplicar

```bash
vendor/bin/sail artisan migrate
vendor/bin/sail artisan migrate:status
```

## Modificar tabla existente

```bash
vendor/bin/sail artisan make:migration add_campo_to_mi_modelos_table
```

```php
Schema::table('mi_modelos', function (Blueprint $table) {
    $table->text('notas')->nullable()->after('name');
});
```

## Relaciones comunes

```php
// BelongsTo Chain (tenant)
public function chain(): BelongsTo
{
    return $this->belongsTo(Chain::class, 'tenant_id');
}

// BelongsTo Branch
public function branch(): BelongsTo
{
    return $this->belongsTo(Branch::class);
}

// HasMany
public function items(): HasMany
{
    return $this->hasMany(MiModeloItem::class);
}
```

## Inspeccionar schema

Laravel Boost MCP: `database-schema`  
O: `vendor/bin/sail artisan db:show --table=mi_modelos`

## Checklist nuevo modelo

- [ ] Migración con FKs e índices
- [ ] Model con casts y relaciones
- [ ] BelongsToTenant si multi-tenant
- [ ] Factory con states útiles
- [ ] Enum para status/type
- [ ] Seeder si datos base necesarios
- [ ] Service si lógica de negocio
- [ ] Test con RefreshDatabase

## Referencia schema

Ver [Modelos y relaciones](../backend/models.md) para entidades existentes.

36 migraciones en `database/migrations/` — revisar naming y patterns existentes antes de crear.
