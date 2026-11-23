# Integración: Nueva página Inertia

## Cuándo usar

Features para Sucursal o Tienda — experiencias PWA interactivas.

## Pasos

### 1. Ruta

```php
// routes/sucursal.php
Route::middleware(['auth', 'branch', 'validate.user'])->group(function () {
    Route::get('/mi-feature', [SucursalMiController::class, 'index'])
        ->name('sucursal.mi-feature');
});
```

### 2. Controller

```php
// app/Http/Controllers/Sucursal/SucursalMiController.php
namespace App\Http\Controllers\Sucursal;

class SucursalMiController extends Controller
{
    public function index(Request $request): Response
    {
        $branch = $request->attributes->get('branch');

        return Inertia::render('sucursal/mi-feature', [
            'datos' => MiService::datos($branch),
        ]);
    }
}
```

### 3. Page React

```tsx
// resources/js/pages/sucursal/mi-feature.tsx
import SucursalLayout from '@/layouts/sucursal-layout';

interface Props {
    datos: DatosType[];
}

export default function MiFeature({ datos }: Props) {
    return (
        <div>
            {/* UI */}
        </div>
    );
}

MiFeature.layout = (page: React.ReactNode) => (
    <SucursalLayout>{page}</SucursalLayout>
);
```

### 4. Wayfinder

```bash
vendor/bin/sail artisan wayfinder:generate
```

Usar en frontend:
```tsx
import { index } from '@/routes/sucursal/mi-feature';

router.visit(index.url());
```

### 5. Layout routing (si auth page)

Si es página auth, registrar en `resources/js/app.tsx`:

```tsx
// StaffAuthLayout para login pages
// SucursalLayout para authenticated
```

### 6. Test

```php
$this->actingAs($branchUser)
    ->get(route('sucursal.mi-feature'))
    ->assertOk()
    ->assertInertia(fn ($page) => $page
        ->component('sucursal/mi-feature')
        ->has('datos')
    );
```

## Acciones POST/PATCH

```tsx
import { router } from '@inertiajs/react';
import { update } from '@/routes/sucursal/mi-feature';

router.post(update.url({ order: orderId }), { campo: valor });
```

Controller:
```php
public function update(Request $request, Order $order): RedirectResponse
{
    $validated = $request->validate([...]);
    // Service logic
    return back()->with('success', 'Actualizado');
}
```

## Props compartidas

Globales via `HandleInertiaRequests`:
- `auth.user`
- `flash`

Sucursal via `ResolveBranch`:
- `sucursal.currentBranchId`, `branches`, `branding`

Compartir nueva prop global → editar `HandleInertiaRequests.php`.

## Componentes UI

Reutilizar de `resources/js/components/ui/`:
- `Button`, `Input`, `Dialog`, `Select`, `Table`, etc.

Específicos:
- `components/sucursal/` — sucursal
- `components/tienda/` — tienda

## Realtime (opcional)

Si la page necesita updates en vivo:

```tsx
import Echo from 'laravel-echo';

useEffect(() => {
    Echo.private(`branch.${branchId}.orders`)
        .listen('.order.changed', handler);
    return () => Echo.leave(`branch.${branchId}.orders`);
}, [branchId]);
```

Ver [Tiempo real](../frontend/realtime.md).

## Hooks existentes

| Hook | Uso |
|---|---|
| `use-cart.ts` | Carrito tienda |
| `use-sucursal-branding.ts` | Branding |
| `use-flash-toast.ts` | Toasts |
| `use-offline-queue.ts` | Offline sucursal |

## Tienda vs Sucursal

| Aspecto | Sucursal | Tienda |
|---|---|---|
| Middleware | auth, branch, validate.user | chain.operational |
| Layout | SucursalLayout | TiendaLayout |
| Controller dir | Sucursal/ | Tienda/ |
| Pages dir | pages/sucursal/ | pages/tienda/ |
| Branch context | `$request->attributes->get('branch')` | Route binding `{branch}` |

## Checklist

- [ ] Ruta con middleware correcto
- [ ] Controller retorna Inertia::render
- [ ] Page TSX con types
- [ ] Layout asignado
- [ ] Wayfinder generado
- [ ] npm run build
- [ ] Feature test

## Referencia

- [Portal Sucursal](../panels/sucursal.md)
- [Tienda](../panels/tienda.md)
- [Inertia + React](../frontend/inertia-react.md)
