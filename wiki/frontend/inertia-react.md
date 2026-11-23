# Frontend — Inertia + React

## Stack

- **Inertia v3** — SPA sin API REST separada para web
- **React 19** — UI library
- **Tailwind CSS 4** — estilos utility-first
- **Vite 8** — bundler
- **Wayfinder** — rutas tipadas desde Laravel
- **Radix UI** — primitivos accesibles (shadcn-style)

## Entry point

`resources/js/app.tsx`

Responsabilidades:
- Crear app Inertia con React
- Resolver layouts por page name
- Configurar Echo (realtime)
- Tema appearance

## Estructura

```
resources/js/
├── app.tsx                 # Entry
├── pages/
│   ├── sucursal/           # Portal sucursal
│   ├── tienda/             # App cliente
│   ├── auth/               # Login, 2FA, passkeys
│   ├── settings/           # Perfil, seguridad
│   ├── dashboard.tsx
│   └── welcome.tsx
├── components/
│   ├── ui/                 # shadcn/Radix primitives
│   ├── tienda/             # Componentes tienda
│   ├── sucursal/           # Componentes sucursal
│   └── ...                 # Shared (heading, nav, etc.)
├── layouts/
│   ├── sucursal-layout.tsx
│   ├── tienda-layout.tsx
│   ├── app-layout.tsx
│   └── auth-layout.tsx
├── hooks/
├── lib/
│   ├── utils.ts
│   ├── web-push.ts
│   └── idb-queue.ts
└── interfaces/
```

## Layouts

Asignación en page o en `app.tsx`:

```tsx
// Por page
MiPage.layout = (page) => <SucursalLayout>{page}</SucursalLayout>;

// Global en app.tsx por pattern
if (name.startsWith('sucursal/')) { ... }
```

## Wayfinder — rutas tipadas

Generar:
```bash
vendor/bin/sail artisan wayfinder:generate
```

Uso:
```tsx
import { board } from '@/routes/sucursal';
import { accept } from '@/routes/sucursal/orders';

router.visit(board.url());
router.post(accept.url({ order: orderId }), { prep_minutes: 15 });
```

Import paths:
- `@/routes/` — named routes
- `@/actions/` — controller actions

## Inertia v3 features usables

| Feature | Uso |
|---|---|
| `useForm` | Formularios con validación |
| `useHttp` | Requests standalone |
| `router.post/visit` | Navegación |
| Deferred props | `Inertia::defer()` en backend |
| Polling | Actualización periódica |
| Prefetching | Links con prefetch |
| Flash data | Mensajes post-redirect |

## Formularios

```tsx
import { useForm } from '@inertiajs/react';

const { data, setData, post, processing, errors } = useForm({
    prep_minutes: 15,
});

const submit = () => post(accept.url({ order: order.id }));
```

Errores: `errors.field` o componente `<InputError message={errors.field} />`

## Build

```bash
vendor/bin/sail npm run dev    # HMR desarrollo
vendor/bin/sail npm run build  # Producción
```

Error "Unable to locate file in Vite manifest" → rebuild necesario.

## TypeScript

Interfaces en pages o `interfaces/`:

```tsx
interface OrderCard {
    id: number;
    public_code: string;
    status: string;
    delivery_type: 'delivery' | 'pickup';
    items: OrderItem[];
}
```

Props tipadas en componente:
```tsx
export default function Board({ orders }: { orders: OrderCard[] }) { ... }
```

## Convenciones

- Pages: PascalCase export, file kebab-case
- Componentes reutilizables en `components/`
- No fetch directo a API — usar Inertia router o Wayfinder
- Toasts: `sonner` via `use-flash-toast.ts`
- Iconos: `lucide-react`

## Skill

Activar `inertia-react-development` en `.agents/skills/` al trabajar frontend.

## Referencia

- [Componentes UI](./components.md)
- [Tiempo real](./realtime.md)
- [Nueva página Inertia](../integration/inertia-page.md)
