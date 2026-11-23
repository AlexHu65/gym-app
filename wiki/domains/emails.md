# Dominio: Correos branded por franquicia

Correos transaccionales con branding automático de la cadena (logo, color primario, nombre y contacto). No hay CMS de plantillas HTML: se reutiliza el módulo de Branding (`chains.logo_path`, `primary_color`, etc.) vía `MailBranding`.

## Arquitectura

```
Trigger (Admin / Franquicia / Sucursal)
    → Notification (ShouldQueue cuando aplica)
    → markdown view en resources/views/mail/
    → <x-mail.branded-message> + <x-mail.branded-button>
    → MailBranding::forMail($chain)
```

| Pieza | Path |
|---|---|
| Helper branding mail | `app/Support/MailBranding.php` |
| Layout branded | `resources/views/components/mail/branded-message.blade.php` |
| Botón con color primario | `resources/views/components/mail/branded-button.blade.php` |
| Notificaciones | `app/Notifications/*.php` |
| Vistas markdown | `resources/views/mail/*.blade.php` |
| Comando de prueba | `app/Console/Commands/SendTestMailCommand.php` (`mail:send-test`) |

Fallback sin cadena: `SlineBranding` (marca plataforma).

## Flujos

| # | Trigger | Destinatario | Notification | Vista |
|---|---|---|---|---|
| 1 | Admin crea cadena (`ChainOnboardingService::createAdminUser`) | Admin de franquicia recién creado | `ChainCreatedNotification` | `mail/chain-created` |
| 2 | Admin activa onboarding (`ChainOnboardingService::activate`) | Users `type=chain` activos de la cadena | `ChainOnboardingCompletedNotification` | `mail/chain-onboarding-completed` |
| 3 | Alta staff (`UserProvisioningService`) | Usuario nuevo | `StaffInvitationNotification` | `mail/staff-invitation` |
| 4a | Sucursal acepta reserva (`ReservationService::accept`) | `customer_email` | `ReservationAcceptedNotification` | `mail/reservation-accepted` |
| 4b | Job 24h antes (`SendReservationReminder`) | `customer_email` | `ReservationReminderNotification` | `mail/reservation-reminder` |

### 1. Cadena creada

- Bienvenida + CTA **Ir a franquicias**.
- CTA opcional **Cambiar contraseña** (token de reset; el password del wizard se mantiene).
- Disparo en `createAdminUser()` tras crear el user.

### 2. Onboarding completado

- Confirma cadena activa + resumen (sucursales / categorías / productos).
- CTA **Ir a franquicias**.
- Se envía a todos los usuarios chain activos con email.

### 3. Usuario nuevo (franquicia / sucursal)

- Incluye **rol legible** (`ChainRole` / `BranchRole`) y tipo de usuario.
- Links:
  - `type=chain` / `driver` → panel franquicias
  - `type=branch` → login sucursales
- Sigue enviando verificación de correo + establecer contraseña.

### 4. Reservas (cliente)

Ya existían; ahora usan layout branded.

Flags en `branch_reservation_settings` (no romper opt-out):

- `notify_client_email` — correo al cliente
- `notify_reminder_24h` — recordatorio email 24h antes
- `notify_reminder_2h` — SMS 2h (no es email branded)

Detalle operativo: [Reservaciones](./reservations.md).

## Comando de prueba

En local los mails van a **Mailpit** (`http://localhost:8025`). Ver [Desarrollo local](../deployment/local.md).

```bash
# Desde el contenedor Sail
docker exec sline-webapp-laravel.test-1 php artisan mail:send-test {type} --to=email@ejemplo.com [opciones]

# O con Sail
./vendor/bin/sail artisan mail:send-test {type} --to=email@ejemplo.com [opciones]
```

| `type` | Opciones | Nota |
|---|---|---|
| `chain-created` | `--chain=ID` `[--user=ID]` | Genera token de reset |
| `onboarding-completed` | `--chain=ID` `[--user=ID]` | |
| `user-created` | `--chain=ID` `[--user=ID]` | Genera token de reset |
| `reservation-accepted` | `--reservation=ID` | |
| `reservation-reminder-24h` | `--reservation=ID` | |

`--to` es obligatorio (o define `TEST_MAIL_TO` en `.env`).  
`chain-created` y `user-created` **crean un token real** de reset en `password_reset_tokens`.

También existe el endpoint legado `GET /test-mail` (`TestMail` + `TEST_MAIL_TO`).

## Tests

```bash
docker exec sline-webapp-laravel.test-1 php artisan test --compact \
  tests/Feature/BrandedMailFlowTest.php \
  tests/Feature/ChainOnboardingTest.php \
  tests/Feature/Auth/UserProvisioningTest.php
```

Cobertura: disparos en onboarding, contenido de rol/links en invitación, branding en reservas, comando `mail:send-test`.

## Añadir un correo branded nuevo

1. Crear `App\Notifications\MiNotification` (preferir `ShouldQueue`).
2. Vista markdown en `resources/views/mail/mi-correo.blade.php` usando `<x-mail.branded-message :chain="$chain" :branding="$branding">`.
3. En `toMail()`, llamar `MailBranding::forMail($chain)` y pasar `$chain` + `$branding` a la vista.
4. Botones con `<x-mail.branded-button :url="..." :primary="$branding['primary']" :on-primary="$branding['on_primary']">`.
5. Enganchar el disparo en el service correspondiente.
6. Añadir el tipo al comando `mail:send-test` si conviene probarlo a mano.
7. Test Pest con `Notification::fake()` y, si aplica, `->toMail($user)->render()`.

## Relacionado

- [Usuarios y auth](./users-auth.md) — provisioning e invitación
- [Reservaciones](./reservations.md) — accept + reminders
- [Eventos y jobs](../backend/events-jobs.md) — `SendReservationReminder`
- [Multi-tenancy / branding UI](../architecture/multi-tenancy.md#white-label-y-branding)
- [Push](../external/push-notifications.md) — canal distinto (no email)
