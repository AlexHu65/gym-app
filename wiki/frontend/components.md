# Frontend — Componentes UI

## Sistema de diseño

Basado en **shadcn/ui** pattern con Radix UI + Tailwind 4.

Ubicación: `resources/js/components/ui/`

## Componentes base (ui/)

| Componente | Uso |
|---|---|
| `button.tsx` | Botones con variants |
| `input.tsx`, `textarea.tsx` | Campos texto |
| `select.tsx` | Selects |
| `dialog.tsx` | Modales |
| `dropdown-menu.tsx` | Menús contextuales |
| `table.tsx` | Tablas |
| `card.tsx` | Cards contenedoras |
| `badge.tsx` | Etiquetas status |
| `tabs.tsx` | Pestañas |
| `sidebar.tsx` | Sidebar navegación |
| `sonner.tsx` | Toast notifications |
| `alert.tsx` | Alertas |
| `checkbox.tsx`, `switch.tsx` | Toggles |
| `popover.tsx`, `tooltip.tsx` | Overlays |
| `sheet.tsx` | Panel lateral mobile |
| `skeleton.tsx` | Loading states |
| `scroll-area.tsx` | Scroll custom |
| `separator.tsx` | Divisores |
| `label.tsx` | Labels form |
| `avatar.tsx` | Avatares |
| `breadcrumb.tsx` | Breadcrumbs |
| `collapsible.tsx` | Secciones colapsables |
| `navigation-menu.tsx` | Nav menus |

Import:
```tsx
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogHeader } from '@/components/ui/dialog';
```

## Componentes compartidos

| Componente | Función |
|---|---|
| `heading.tsx` | Títulos consistentes |
| `text-link.tsx` | Links estilizados |
| `input-error.tsx` | Errores validación |
| `user-info.tsx`, `user-menu-content.tsx` | Info usuario |
| `nav-footer.tsx` | Footer navegación |
| `passkey-item.tsx` | Item passkey settings |
| `appearance-tabs.tsx` | Toggle tema |

## Componentes Tienda

`resources/js/components/tienda/`:

| Componente | Función |
|---|---|
| `tienda-navbar.tsx` | Nav principal tienda |
| `tienda-breadcrumb.tsx` | Breadcrumb |
| `branch-locator.tsx` | Selector sucursal |
| `branch-map.tsx` | Mapa sucursales |
| `landing-address-search.tsx` | Búsqueda dirección landing |
| `catalog-hero.tsx` | Hero catálogo |
| `promotions-hero.tsx` | Hero promociones |
| `category-card.tsx` | Tarjeta categoría |
| `category-carousel.tsx` | Carrusel categorías |
| `product-card.tsx` / `product-row.tsx` | Producto catálogo |
| `cart-product-card.tsx` | Línea carrito |
| `combo-card.tsx` / `combo-includes.tsx` / `combos-row.tsx` | Combos |
| `banner-card.tsx` | Banner marketing |
| `checkout-order-item.tsx` | Ítem checkout |
| `schedule-picker.tsx` | Selector pedido programado |
| `customer-auth-modal.tsx` | Modal login/registro |
| `order-status-stepper.tsx` | Stepper tracking |
| `step-header.tsx` | Header de pasos flujo |
| `use-horizontal-scroll.ts` | Scroll horizontal carruseles |

## Componentes Sucursal

`resources/js/components/sucursal/`:

| Componente | Función |
|---|---|
| `assign-driver-modal.tsx` | Modal asignar repartidor |
| `accept-reservation-modal.tsx` | Aceptar reservación |
| `reject-reservation-modal.tsx` | Rechazar reservación |
| `reservation-calendar-tab.tsx` | Tab calendario |
| `reservation-history-tab.tsx` | Tab historial |
| `reservation-live-map-tab.tsx` | Tab mapa en vivo |
| `reservation-map-panel.tsx` | Panel mapa |
| `reservation-operational-map.tsx` | Mapa operativo mesas |

## Layouts

| Layout | Superficie |
|---|---|
| `sucursal-layout.tsx` | Portal sucursal — nav, branding, branch switch |
| `tienda-layout.tsx` | Tienda — navbar, branding cadena |
| `app-layout.tsx` | Settings/dashboard general |
| `auth-layout.tsx` | Auth pages |
| `auth-card-layout.tsx` | Auth con card centrada |
| `settings-layout.tsx` | Settings sub-nav |

## Hooks

| Hook | Función |
|---|---|
| `use-appearance.tsx` | Tema claro/oscuro/sistema |
| `use-cart.ts` | Carrito tienda (persistente) |
| `use-sucursal-branding.ts` | CSS vars branding |
| `use-flash-toast.ts` | Flash → toast |
| `use-offline-queue.ts` | Cola acciones offline |
| `use-wake-lock.ts` | Screen wake lock (tablero) |
| `use-mobile.tsx` | Detección mobile |
| `use-initials.tsx` | Iniciales avatar |

## Branding dinámico

`ChainBranding` (backend) → props Inertia → CSS variables en layout:

```tsx
// use-sucursal-branding.ts aplica colores cadena
style={{ '--primary': branding.primaryColor } as CSSProperties}
```

## Patrones UI comunes

### Modal con form

```tsx
<Dialog open={open} onOpenChange={setOpen}>
    <DialogContent>
        <DialogHeader><DialogTitle>Título</DialogTitle></DialogHeader>
        <form onSubmit={submit}>...</form>
    </DialogContent>
</Dialog>
```

### Status badge

```tsx
<Badge variant={statusVariant[order.status]}>
    {order.status_label}
</Badge>
```

### Loading skeleton

```tsx
{loading ? <Skeleton className="h-8 w-full" /> : <Content />}
```

## Añadir componente UI

1. Verificar si existe en `components/ui/`
2. Si no, añadir via shadcn pattern (Radix + Tailwind)
3. Seguir convenciones existentes (variants con cva)
4. Export desde path `@/components/ui/nombre`

## Filament custom (PHP, no React)

Componentes PHP para forms Filament en `app/Filament/Forms/`:
- `AddressFieldset`
- `CoverageMapPicker`
- `GeoSelects`

No confundir con componentes React.

## Referencia

- [Inertia + React](./inertia-react.md)
- Skill `tailwindcss-development` para layouts responsive
