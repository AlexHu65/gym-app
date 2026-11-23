# Dominio: Catálogo y menú

## Modelos

| Modelo | Función |
|---|---|
| `Category` | Categoría menú (tenant) |
| `Product` | Producto/combo maestro |
| `BranchProduct` | Override por sucursal |
| `ModifierGroup` | Grupo modificadores (ej. "Extras") |
| `Modifier` | Modificador individual (ej. "Queso extra") |
| `BranchModifier` | Disponibilidad modificador por sucursal |
| `ComboItem` | Items dentro de un combo |

## Jerarquía

```
Chain (tenant)
  └── Category
        └── Product (base_price, sku, images, ...)
              ├── ComboItem → Product (si es combo)
              └── ModifierGroup
                    └── Modifier (+ price_adjustment)
```

## Menú maestro vs override sucursal

| Nivel | Dónde se edita | Tabla |
|---|---|---|
| Maestro | Franquicia → Products | `products` |
| Override sucursal | Portal Sucursal → Menu | `branch_products` |
| Modificador sucursal | Portal Sucursal → Menu | `branch_modifiers` |

### Campos override (branch_products)

**Contenido** (muestra "Solo sucursal"):
- `name_override`, `description_override`
- `price_override`, `tax_rate_override`
- `calories_override`, `tags_override`
- `image_path_override`, `images_override`

**Operativos**:
- `is_available`, `available_from`, `available_to`

Ver detalle: [Overrides de producto](../sucursal/product-overrides.md)

## Servicio central: BranchProductPresentation

Archivo: `app/Services/BranchProductPresentation.php`

```php
$presentation = app(BranchProductPresentation::class);
$effective = $presentation->forProduct($product, $branch);

// Retorna datos efectivos: name, price, images, tags, isAvailable, etc.
```

Usado por:
- `SucursalMenuController` — edición menú
- `TiendaController` — catálogo/checkout
- `PlaceOrder` — validación y snapshots

## Disponibilidad

Un producto es visible en tienda si:
1. `products.is_active = true` (maestro)
2. `branch_products.is_available = true` (sucursal)
3. Dentro de ventana horaria (`available_from` / `available_to`)
4. Sucursal operacional

Modificador disponible si:
1. Activo en maestro
2. `branch_modifiers.is_available = true` para esa sucursal

## Import/export Excel

Service: `MenuExcel`  
Disponible en Franquicia → Products (acción export/import).

## Gestión desde Franquicia

Resources:
- `Categories/CategoryResource`
- `Products/ProductResource`
- `ModifierGroups/ModifierGroupResource`

## Gestión desde Sucursal

Controller: `SucursalMenuController`  
Page: `resources/js/pages/sucursal/menu.tsx`

Rutas:
```
GET    /sucursal/menu
PATCH  /sucursal/menu/{product}
PATCH  /sucursal/menu/{product}/branch
POST   /sucursal/menu/{product}/toggle
POST   /sucursal/menu/modifiers/{modifier}/toggle
```

Ver: [Menú y estadísticas](../sucursal/menu-stats.md)

## Imágenes

- Almacenamiento: disco local privado (MinIO/S3 en prod)
- No públicas por defecto
- Rutas firmadas/autenticadas para servir

```
GET /sucursal/menu/{product}/image/{image?}
GET /t/{chain}/{branch}/producto/{product}/imagen/{image?}
```

## Combos

Producto tipo combo contiene `ComboItem[]` — referencias a otros productos con cantidad.

Edición desde sucursal respeta estructura combo maestro.

## Promociones relacionadas

- `Coupon` — descuento checkout (ver PlaceOrder)
- `Banner` — promocional en landing tienda

## Archivos clave

```
app/Models/Product.php, BranchProduct.php, BranchModifier.php
app/Services/BranchProductPresentation.php, MenuExcel.php
app/Http/Controllers/Sucursal/SucursalMenuController.php
app/Filament/Franquicia/Resources/Products/
resources/js/pages/sucursal/menu.tsx
resources/js/pages/tienda/catalog.tsx, product.tsx
tests/Feature/SucursalPortalTest.php, TiendaTest.php
```

## Añadir campo al catálogo

1. Migración en `products` y/o `branch_products`
2. Actualizar Product model casts/fillable
3. Franquicia ProductResource form
4. BranchProductPresentation si es override
5. Sucursal menu.tsx si editable desde sucursal
6. Tienda product.tsx si visible al cliente
7. PlaceOrder si afecta checkout
8. Tests

## Debugging catálogo

| Problema | Verificar |
|---|---|
| Producto no en tienda | is_active, branch_products, horario |
| Precio diferente | price_override vs base_price |
| Modificador no disponible | branch_modifiers |
| Imagen rota | storage disk, ruta imagen, visibilidad |
| Override no guarda | migración branch_products, updateBranchOverride |
