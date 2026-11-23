# Integración: Mapas y broadcasting

## Mapas

### Configuración

`config/maps.php`:
```php
'google_api_key' => env('GOOGLE_MAPS_API_KEY'),
'default_center' => ['lat' => 19.4326, 'lng' => -99.1332],
```

```env
GOOGLE_MAPS_API_KEY=
```

### Providers

`App\Support\Maps\MapProvider` — selecciona provider:
- **Google Maps** — si hay API key
- **Leaflet/OpenStreetMap** — fallback sin key

### Geocoding

`App\Support\Geocoder` — dirección → lat/lng

Endpoint Franquicia:
```
POST /franquicia/geocode
```

Controller: `Franquicia/GeocodeAddressController`

Usado en:
- Checkout tienda (calcular distancia/envío)
- Branch setup (dirección sucursal)
- Coverage zone

### Componentes mapa

| Componente | Uso |
|---|---|
| `CoverageMapPicker` | Filament — zona entrega sucursal |
| `AddressFieldset` | Filament — dirección estructurada |
| Floor-plan picker | Filament — plano mesas (reservaciones) |
| Heatmap Leaflet | Franquicia — mapa de calor entregas |
| `branch-locator.tsx` | Tienda — selector sucursal |

Archivos: `app/Filament/Forms/CoverageMapPicker.php`  
Heatmap: [Mapa de calor](../panels/franquicia-heatmap.md) · `ChainDeliveryHeatmapService`

### Cálculo envío

En PlaceOrder:
1. Geocode dirección cliente
2. Calcular distancia a branch
3. Verificar dentro de `delivery_radius_km`
4. Aplicar `delivery_fee`

Support: `App\Support\Geo`

## Broadcasting (soketi)

Ver documentación completa: [Tiempo real](../frontend/realtime.md)

### Resumen infra

| Entorno | WebSocket |
|---|---|
| Dev (Sail) | soketi container, port 6001 |
| Prod | soketi en Railway o Pusher Cloud |

### Docker service

`compose.yaml` incluye soketi. Variables en `.env.example`.

### Eventos broadcast

| Event | Canales |
|---|---|
| `OrderChanged` | `branch.{id}.orders`, `order.{id}` |
| `ChatMessageSent` | canal chat pedido |

### Frontend

Laravel Echo + pusher-js configurado en app.tsx.

## Redis

Usado para:
- Cache
- Queue driver (prod)
- Session (opcional)

Servicio Sail: `redis:6379`

## MinIO (S3)

Storage S3-compatible en dev.

`config/filesystems.php` — disk `s3` apunta a MinIO local.

Archivos producto/documentos: disco `local` private por defecto.

## Meilisearch

Incluido en Sail pero reservado para búsqueda futura.

## Debugging mapas

| Problema | Solución |
|---|---|
| Mapa blank | GOOGLE_MAPS_API_KEY o fallback Leaflet |
| Geocode fail | Address format, API quota |
| Envío rechazado | delivery_radius_km, distancia |
| Coverage picker | JS console errors, API key |

## Debugging broadcasting

Ver [Tiempo real](../frontend/realtime.md#debugging-realtime).

```bash
# soketi status
docker ps | grep soketi

# Test broadcast log
BROADCAST_CONNECTION=log
```

## Producción checklist

- [ ] GOOGLE_MAPS_API_KEY con billing enabled
- [ ] soketi/Pusher accesible via wss
- [ ] PUSHER_* env vars correctas
- [ ] Redis para queue
- [ ] S3/MinIO para storage prod
