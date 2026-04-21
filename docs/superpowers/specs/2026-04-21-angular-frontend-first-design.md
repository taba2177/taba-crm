# Angular-First Frontend & Client Widget Overhaul

**Date:** 2026-04-21  
**Status:** Approved  
**Scope:** `packages/taba/crm`

---

## 1. Goal

Make the `taba/crm` package fully self-contained with an Angular SPA as the only consumer-facing frontend. Remove the dependency on Blade/Livewire for public pages. Fix all Filament Client widgets so they work exclusively with package-owned models. Give developers a clean, fast customization path for every new project.

---

## 2. What Changes

### 2.1 Frontend — Angular takes over

- The `frontend/` folder inside the package becomes the **canonical Angular source**.
- A new artisan command (`php artisan crm:frontend:publish`) copies it to the consuming project root.
- The developer runs `npm install && ng build` once; the build output lands in `public/`.
- Laravel's `web.php` gains a **catch-all route** that serves `public/index.html` for all paths not matched by API or admin routes.
- Existing Livewire/Blade routes are kept in `web.php` with `@deprecated` doc comments — they still function for any project that hasn't published and built the Angular frontend yet, and will be removed in a future version.

### 2.2 Frontend customization contract

Two layers, independent of each other:

| Layer | File | What to change |
|---|---|---|
| Theme tokens | `frontend/src/styles/tokens.scss` | CSS custom properties: `--color-primary`, `--color-secondary`, `--font-family`, `--radius-*`, `--spacing-*` |
| Tailwind config | `frontend/tailwind.config.js` | Extends package preset; add project-specific colors/utilities |
| Component swap | `frontend/src/app/components/<name>/` | Replace any component's HTML/TS while routing and services stay intact |

**Invariants** (never touch per project):
- `api.service.ts` — all HTTP calls
- `action.service.ts` — click/form tracking
- `app.routes.ts` — route definitions
- `app.config.ts` — Angular providers

### 2.3 New `action.service.ts`

Angular service exposing three methods:

```ts
trackWhatsApp(source?: string): void   // fires POST /api/v1/actions { action: 'whatsapp', source, page }
trackCall(source?: string): void       // fires POST /api/v1/actions { action: 'call', source, page }
trackFormSubmit(source?: string): void // fires POST /api/v1/actions { action: 'form', source, page }
```

Components inject this service and call it on button/link clicks. No direct HTTP usage in components.

---

## 3. Backend Changes

### 3.1 `ActionClick` model — package-owned

**New migration:** `action_clicks`

| Column | Type | Notes |
|---|---|---|
| `id` | bigint unsigned | PK |
| `action` | enum(`whatsapp`,`call`,`form`) | required |
| `source` | varchar(50) nullable | `organic`, `ads`, `direct`, or null |
| `page` | varchar(255) nullable | URL path where the event occurred |
| `country` | varchar(100) nullable | from IP geo, best-effort |
| `city` | varchar(100) nullable | from IP geo, best-effort |
| `ip_hash` | varchar(64) nullable | SHA-256 of IP — no raw IP stored |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

**Model:** `Taba\Crm\Models\ActionClick` — standard Eloquent, no auth required.

### 3.2 New API endpoint

`POST /api/v1/actions` — public, throttle: 60/min

Request body (JSON):
```json
{ "action": "whatsapp|call|form", "source": "organic|ads|direct|null", "page": "/path" }
```

Response: `204 No Content`

`GET /api/v1/actions/summary` — authenticated (Sanctum), used by widgets only. Returns aggregated counts by action, source, and daily breakdown for a requested period (query param `period=7d|30d|all`).

Controller: `Taba\Crm\Http\Controllers\Api\ActionClickApiController`

### 3.3 Routes update

`routes/api.php` gains:
```php
// Action click tracking (public)
Route::middleware('throttle:60,1')->post('actions', [ActionClickApiController::class, 'store']);

// Action click summary (authenticated)
Route::middleware('auth:sanctum')->get('actions/summary', [ActionClickApiController::class, 'summary']);
```

`routes/web.php` gains (at the bottom, after all named routes):
```php
// Angular SPA catch-all — serves public/index.html for all frontend routes
// @deprecated Blade routes above this line will be removed in a future version
Route::get('/{any}', function () {
    return response()->file(public_path('index.html'));
})->where('any', '^(?!api|admin|filament).*');
```

### 3.4 New artisan command

`php artisan crm:frontend:publish`

- Source: `packages/taba/crm/frontend/`
- Destination: `{base_path()}/frontend/`
- Behaviour: warns if destination exists, asks for confirmation before overwriting
- After copy, prints:
  ```
  Frontend published to frontend/
  Next steps:
    cd frontend
    npm install
    ng build
  ```

Registered in `CrmServiceProvider` via `$this->commands([PublishFrontendCommand::class])`.

---

## 4. Filament Client Widgets — Fixed & Self-Contained

All widgets moved to correct namespace: `Taba\Crm\Filament\Client\Widgets`. All `App\Models\*` references replaced with package models or `ActionClick`.

| Widget | Fixed data source | Notes |
|---|---|---|
| `WelcomeWidget` | `PostCategory`, `Post`, `ContactEntry`, `CrmSetting` | Namespace fix only |
| `AccountWidget` | `Filament\Widgets\AccountWidget` (extends) | Namespace fix only |
| `StatsOverview` | `Post`, `PostCategory`, `ContactEntry` | Replaces app-model stats with package equivalents |
| `ActionClicksOverview` | `ActionClick` | Replaces `App\Models\advertisement` etc. with real click data |
| `WeeklyClicksChart` | `ActionClick` | Filter by action type and date range |
| `AdvertisementsOverview` | `Post` stats by category | Repurposed — shows posts per category |
| `OffersOverview` | `ContactEntry` | Repurposed — contact submissions over time |
| `SurveyAnswersChart` | `ContactEntry` | Repurposed — form submissions breakdown by page |
| `WeeklyReviewsChart` | `ContactEntry` | Messages per week chart |

`CrmClientPlugin::register()` already calls `discoverWidgets()` — no plugin changes needed once namespaces are correct.

---

## 5. What Is NOT Changing

- Admin panel (`CrmPlugin`, `Filament/Admin/`) — untouched
- All existing API controllers and endpoints — untouched
- `CrmServiceProvider` boot logic (gates, translations, migrations) — untouched except adding the new command
- Angular routing, `api.service.ts`, `app.config.ts` — structure preserved, `action.service.ts` added alongside

---

## 6. Delivery Sequence

1. Add `ActionClick` migration + model + `ActionClickApiController` + register routes
2. Fix all 9 widget namespaces and rewrite data sources
3. Add `action.service.ts` to Angular frontend source
4. Add `tokens.scss` with CSS custom properties; update components to use them
5. Add `php artisan crm:frontend:publish` command
6. Update `routes/web.php` with catch-all + deprecation comments on Blade routes
7. Update package `README.md` — new "Frontend Setup" section

---

## 7. Success Criteria

- `php artisan crm:frontend:publish` → `npm install` → `ng build` → site loads from Angular with no errors
- WhatsApp/Call button clicks tracked in `action_clicks` table
- All Client panel widgets render with real data, no `App\Models\*` references
- No Blade views required for public-facing pages
- A new project can be up and running with a customized theme in under 30 minutes (change `tokens.scss`, run build)
