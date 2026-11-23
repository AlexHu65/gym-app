# Dominio: Encuestas

Encuestas de marketing / post-compra por cadena. Se gestionan en Franquicia y se responden en la tienda pública vía token.

## Modelos

Todos usan `$guarded = ['id']` (mass-assign excepto `id`).

| Modelo | Tabla | Path |
|---|---|---|
| `Survey` | `surveys` | `app/Models/Survey.php` |
| `SurveyQuestion` | `survey_questions` | `app/Models/SurveyQuestion.php` |
| `SurveyQuestionOption` | `survey_question_options` | `app/Models/SurveyQuestionOption.php` |
| `SurveyResponse` | `survey_responses` | `app/Models/SurveyResponse.php` |
| `SurveyResponseAnswer` | `survey_response_answers` | `app/Models/SurveyResponseAnswer.php` |

### `Survey`

| Campo | Notas |
|---|---|
| `tenant_id` | FK `chains` (cascade); trait `BelongsToTenant` |
| `title` | Título |
| `public_token` | Token URL pública (auto en create: 12 chars random lower) |
| `ends_at` | datetime nullable |
| `is_active` | boolean |

Relaciones: `questions()` (ordenado por `position`), `responses()`, `chain()` vía `tenant_id`.

### `SurveyQuestion`

Campos: `survey_id`, `prompt`, `type` → `SurveyQuestionType`, `position`.  
Relaciones: `survey()`, `options()` (ordenado por `position`).

### `SurveyQuestionOption`

Campos: `survey_question_id`, `label`, `position`.  
Relación: `question()`.

### `SurveyResponse`

| Campo | Notas |
|---|---|
| `survey_id`, `user_id` | Usuario opcional (cliente logueado) |
| `respondent_fingerprint` | Dedup anónimos |
| `submitted_at` | datetime |

Unique: `(survey_id, respondent_fingerprint)`, `(survey_id, user_id)`.  
Relaciones: `survey()`, `user()`, `answers()`.

### `SurveyResponseAnswer`

Campos: `survey_response_id`, `survey_question_id`, `answer_option_id`, `answer_text`, `answer_stars`.  
Relaciones: `response()`, `question()`, `option()`.

## Enum `SurveyQuestionType`

`app/Enums/SurveyQuestionType.php`

| Case | Valor | Label |
|---|---|---|
| `Text` | `text` | Texto |
| `Options` | `options` | Opciones |
| `Stars` | `stars` | Estrellas |

## Franquicia: `SurveyResource`

Path: `app/Filament/Franquicia/Resources/Surveys/SurveyResource.php`

| Aspecto | Valor |
|---|---|
| Grupo nav | Marketing |
| Slug | `surveys` |
| URLs | `/franquicia/surveys`, `/create`, `/{record}`, `/{record}/edit` |
| Labels | encuesta / encuestas |
| Módulo RBAC | `surveys` |
| Permisos | `surveys.view`, `surveys.create`, `surveys.edit`, `surveys.delete` |
| Trait | `AuthorizesChainPermissions` |

Páginas: `ListSurveys`, `CreateSurvey`, `ViewSurvey`, `EditSurvey`.  
Relation manager: `SurveyResponsesRelationManager`.

Create: `AssignsTenantOnCreate` + generación de `public_token`.

Link / QR público: `app/Support/SurveyPublicLink.php` (`url`, `qrSvg`, `viewData`) — usado en form, infolist y table.

## Tienda pública

Controller: `app/Http/Controllers/Tienda/SurveyPublicController.php`  
Rutas: `routes/tienda.php` (prefijo `t/{chain:slug}`, middleware `chain.operational`)

| Método | Path | Nombre | Acción |
|---|---|---|---|
| `GET` | `/t/{chain}/encuesta/{token}` | `tienda.surveys.show` | `show` |
| `POST` | `/t/{chain}/encuesta/{token}` | `tienda.surveys.store` | `store` |

Resolución: `Survey` por `tenant_id` = cadena + `public_token`.  
Anónimos: cookie `survey_device_token` → fingerprint. Logueados: unique por `user_id`.

## Relación con pedidos

**No hay** `order_id` ni vínculo Eloquent a `Order`. Las calificaciones post-entrega son otro flujo (`POST .../seguimiento/{order}/rating`).

## Seeder

`database/seeders/SurveySeeder.php`:

- Invocado desde `DemoSeeder` (solo no-producción vía `DatabaseSeeder`)
- Cadena demo `pollo-crazy`
- 3 encuestas ejemplo + `seedSampleResponses()` (8 respuestas anónimas por encuesta si no hay)

Factories: `SurveyFactory`, `SurveyQuestionFactory`, `SurveyQuestionOptionFactory`, `SurveyResponseFactory`, `SurveyResponseAnswerFactory`.

## Roles

`TenantRoleProvisioner`: `owner`, `manager` y `admin` incluyen permisos `surveys.*`.

## Ver también

- [Marketing](./marketing.md)
- [Panel Franquicia](../panels/franquicia.md)
- [Clientes](./customers.md)
