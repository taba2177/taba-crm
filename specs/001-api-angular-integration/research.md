# Research: Laravel API Hardening + Angular Dynamic Frontend Integration

**Feature**: `001-api-angular-integration`  
**Phase**: 0 — All NEEDS CLARIFICATION items resolved from codebase analysis  
**Date**: 2026-03-12

---

## Decision 1 — PostResource `image_url` Resolution

**Question**: Does Curator Media's `url` accessor already return an absolute URL, or must `Storage::url()` be called explicitly?

**Finding**: `Post.image` is `BelongsTo(Awcodes\Curator\Models\Media::class)` via `image_id`. Curator's `Media` model has a `url` accessor computed via `Storage::url($this->path)`. `MediaResource` already exposes `'url' => $this->url`. The Angular `ContentService` already uses `p.image?.url` from the nested `ApiPost.image.url`. However, `ApiPost.image_url` as a top-level flat string field is **missing**; only the nested `image` object exists. The spec (FR-003) requires a top-level `image_url` field.

**Decision**: Add an explicit `image_url` field in `PostResource::toArray()`:

```php
'image_url' => $this->image?->url ?? null,
```

`$this->image->url` delegates to Curator's URL accessor which calls `Storage::url($this->path)`. This is correct when `APP_URL` is set. The existing nested `'image' => new MediaResource(...)` is preserved for backwards compatibility.

**Alternatives considered**: Call `Awcodes\Curator\Models\Media::find($this->image_id)?->url` in the resource. Rejected — triggers an extra query; `$this->whenLoaded('image')` is already resolved by the controller's `->with([..., 'image'])` eager load.

---

## Decision 2 — PostCategoryResource `image_url` Resolution

**Question**: `PostCategory.image` is a raw text/path column (NOT a BelongsTo relation to Curator Media). How should `image_url` be resolved?

**Finding**: `PostCategory` stores `image` as a plain string column (not `image_id`). It may contain a relative storage path, a full URL, or null. The `PostCategoryResource` currently exposes `'image' => $this->image` (raw string). No Curator relation exists on this model.

**Decision**: Compute `image_url` using:

```php
'image_url' => $this->image
    ? (str_starts_with($this->image, 'http') ? $this->image : \Illuminate\Support\Facades\Storage::url($this->image))
    : null,
```

This handles both cases: already-absolute URLs pass through unchanged; relative paths are wrapped with `Storage::url()`.

**Alternatives considered**: Always call `Storage::url()`. Rejected — if `$this->image` is already a full URL (e.g., `https://...`), `Storage::url()` would double-prefix and corrupt the value.

---

## Decision 3 — `/api/v1/settings/grouped` Returns Flat `{ key: value }`

**Question**: `CrmSetting::getAllGrouped()` returns nested `{ "group": { "key": "value" } }`. The spec (FR-002) requires `/settings/grouped` to return FLAT `{ "key": "value" }`. But `getAllGrouped()` is also used by the `/init` endpoint, which ContentService expects in nested form.

**Finding**:

- `/api/v1/init` → `settings: Record<group, Record<key, value>>` (nested). `ContentService.setting()` iterates over all groups with `for (const group of Object.values(grouped))`. This is correct and must not change.
- `/api/v1/settings/grouped` → spec requires FLAT `{ key: value }`. This is a separate endpoint used when Angular requests settings directly.

**Decision**: Add `CrmSetting::getAllFlat(): array` that returns a single flat key-value map:

```php
public static function getAllFlat(): array
{
    return static::orderBy('order')
        ->get()
        ->mapWithKeys(fn ($setting) => [$setting->key => $setting->value])
        ->toArray();
}
```

Update `SettingApiController::grouped()` to call `getAllFlat()` instead of `getAllGrouped()`. Keep `getAllGrouped()` unchanged for the `/init` endpoint.

**Alternatives considered**: Change `getAllGrouped()` to return flat and update ContentService to handle flat. Rejected — ContentService's `setting()` method already correctly handles the nested format and the spec says "no rewrites" to ContentService.

---

## Decision 4 — CORS Multi-Origin via ENV

**Question**: `config/cors.php` uses `env('CRM_API_CORS_ORIGINS', '*')` wrapped in an array. This cannot handle multiple comma-separated origins from a single env var.

**Finding**: Current: `'allowed_origins' => [env('CRM_API_CORS_ORIGINS', '*')]`. A multi-value env like `http://localhost:4200,https://sbc-clean.com` would be treated as a single incorrectly-formatted origin string.

**Decision**:

```php
'allowed_origins' => array_map(
    'trim',
    explode(',', env('CRM_API_CORS_ORIGINS', 'http://localhost:4200'))
),
```

- Default changes from `*` (insecure) to `http://localhost:4200` (FR-006).
- `supports_credentials` changes from `false` to `true` (FR-009, Sanctum compatibility).
- Multiple origins set via: `CRM_API_CORS_ORIGINS=http://localhost:4200,https://sbc-clean.com`.
- Acceptance Scenario 3 (evil.com blocked) is satisfied because only listed origins are allowed.

**Alternatives considered**: Using `CORS_ALLOWED_ORIGINS` (Laravel default env key). Rejected — existing codebase already uses `CRM_API_CORS_ORIGINS`; changing it would break existing deployments.

---

## Decision 5 — ContactEntry Schema: Add `phone` + `service`, Make `email` Nullable

**Question**: `BookingComponent` collects `name`, `phone`, `service`, `address`, `notes`, `date`, `time`. The `contact_entries` table only has `name` (required), `email` (required), `message` (required). No email field in the booking form.

**Finding**:

- `ContactEntry` model fillable: `['email', 'message', 'name']`
- `StoreContactEntryRequest` validates `name` (required), `email` (required), `message` (required)
- `ApiService.submitContact()` already accepts `{ name, email, phone?, subject?, message }`
- Spec's ContactEntry entity lists: `name`, `email`, `phone`, `message`, `service`

**Decision**:

1. Migration: add `phone` (string, nullable), `service` (string, nullable); change `email` to nullable.
2. `StoreContactEntryRequest`: make `email` nullable (`['nullable', 'email', 'max:255']`), add `phone` (`['nullable', 'string', 'max:30']`), add `service` (`['nullable', 'string', 'max:255']`).
3. `ContactEntry` fillable: add `phone`, `service`.
4. `BookingComponent` maps: `bookingData.phone → phone`, `bookingData.service → subject/service`, composites `address + notes + date + time → message`.

**Alternatives considered**: Add email step to BookingComponent. Rejected — disrupts existing 4-step UX; email is not relevant to a physical cleaning service booking.

---

## Decision 6 — Post Content Blocks + Figure `image_url` Resolution

**Question**: `Post.content` is stored as a JSON array (Filament TipTap). Does `PostResource` need to post-process `figure` blocks to inject `image_url`?

**Finding**: `Post::$casts = ['content' => 'array']` + translatable. Content is a structured array of `{ "type": "...", "data": { ... } }` blocks. Figure blocks store `data.image_id` referencing a Curator Media record. The spec (FR-005) requires figure blocks to include `image_url` as an absolute URL.

**Decision**: In `PostResource::toArray()`, process content blocks:

```php
'content' => $this->resolveContentBlocks(
    $this->getTranslation('content', $locale, false)
),
```

Add private helper `resolveContentBlocks(array $blocks): array` that iterates over blocks and for any block with `type === 'figure'` and `data.image_id`, injects `data.image_url` via `Media::find($id)?->url`. Limits to a single loop, no N+1 risk since content blocks are bounded (typically < 50 per post).

**Alternatives considered**: Pre-resolve all figure media in the controller and pass to the resource. Rejected — tighter coupling; resource should own its serialization.

---

## Decision 7 — `SiteContent` New Fields: `lineOfWork` + `aboutPills`

**Question**: What shape should the new `lineOfWork` field have on `SiteContent`? Should `aboutPills` also be a new field?

**Finding**:

- `LineOfWorkComponent` currently uses `brands[]` with `{ title, subtitle, icon }` shape.
- `AboutComponent` uses `aboutPills: string[]` (hardcoded). It already reads from `content()` for title/description/stats but not for pills.
- `ContentService` maps `content().features.features` as `{ title, icon }[]` — related but distinct concept.

**Decision**:

- Add to `SiteContent.about`: `pills: string[]` (simple text array, maps to `about_pills` JSON settings key)
- Add top-level `SiteContent.lineOfWork: { title: string; subtitle: string; icon: string; imageUrl: string | null }[]`
- `ContentService.mapToSiteContent()`: add two new mappers — `mapAboutPills()` and `mapLineOfWork()`
- `LineOfWorkComponent`: inject `ContentService`, use `content().lineOfWork`; fallback converts `brands[]` to the same interface shape
- `AboutComponent`: change `aboutPills` from hardcoded array to computed from `content().about.pills ?? fallbackPills`

**Alternatives considered**: Reuse `content().features.features` for about pills. Rejected — conceptually different data; features are "why choose us" bullets in a separate section; about pills describe company capabilities.

---

## Decision 8 — Rate Limiting Middleware in API Routes

**Question**: Public read endpoints currently have NO throttle middleware. Contact endpoint uses `throttle:5,1` (5/min, not 30/min as required).

**Finding**: `routes/api.php` line 82: `->middleware('throttle:5,1')` for `POST contact`. No throttle on read routes. Spec requires 100/min for reads (FR-010), 30/min for contact (FR-011).

**Decision**: Wrap all public read routes in a `throttle:100,1` middleware group. Change contact from `throttle:5,1` to `throttle:30,1`. Laravel's `ThrottleRequests` middleware automatically returns 429 with `Retry-After` header (FR-012) and `X-RateLimit-*` headers (already in `exposed_headers` in cors.php).

---

## Decision 9 — Angular New Routes Strategy

**Question**: Parameterized routes (`/blog/:category/:slug`) — do they need explicit entries in `app.routes.server.ts`?

**Finding**: `app.routes.server.ts` has `{ path: '**', renderMode: RenderMode.Server }`. This catchall applies `RenderMode.Server` to all routes including dynamic ones. No `RenderMode.Prerender` is requested for blog routes (dynamic content). No changes needed to server routes.

**Decision**: New routes added only to `app.routes.ts`. All use `loadComponent` (lazy-loaded standalone). Server routes remain unchanged. SSR will render them server-side on demand via `RenderMode.Server` from the catchall.

---

## Decision 10 — BookingComponent API Integration

**Question**: `BookingComponent` currently calls `window.open(whatsapp_url)` in `submitBooking()`. How should `ApiService.submitContact()` be wired with PrimeNG Toast?

**Finding**:

- `ApiService.submitContact({ name, email?, phone?, subject?, message })` exists and returns `Observable<ApiContactEntry>`.
- PrimeNG is already provided in `app.config.ts` (`providePrimeNG`).
- `BookingComponent` uses `ChangeDetectionStrategy.OnPush` — signals must drive the template state.
- The form does not collect `email`; `phone` and `service` are available.

**Decision**:

- Add `MessageService` injection (PrimeNG) and `Toast` to component imports.
- Introduce signals: `isSubmitting = signal(false)`, `submitError = signal<string | null>(null)`, `submitSuccess = signal(false)`.
- `submitBooking()` calls `api.submitContact({ name, phone, service, message: composedMessage })`, sets `isSubmitting(true)` before, handles `next` → `submitSuccess(true)` + reset form, handles `error` → parse 422 errors or show generic network error.
- Show PrimeNG Toast for success (`severity: 'success'`) and error (`severity: 'error'`).
- On success: reset `bookingData` and call `currentStep.set(1)` to allow re-submission.

---

## Decision 11 — Auth Component Scope

**Question**: How minimal should LoginComponent and RegisterComponent be?

**Finding**: `AuthService` is complete with `login(email, password)` and `register({name, email, password, password_confirmation})`. No new auth logic needed. Spec says minimal email/password fields with error display.

**Decision**: Each component uses Angular `ReactiveFormsModule` with a `FormGroup`, injects `AuthService`, shows error via `signal<string | null>(null)`. Template uses PrimeNG `InputText` + `Button` for style consistency. Auth guard (redirect if already logged in) uses `AuthService.isAuthenticated` computed signal in the route's `canActivate` guard — add `authGuard` that redirects `isAuthenticated` users away from `/login` and `/register`.

---

## Decision 12 — `homepage_sections` Drop Migration Strategy

**Question**: Should the migration run automatically in production CI/CD?

**Finding**: Migration file exists (`2025_07_02_125623_create_homepage_sections_table.php`) — table was created. The spec (FR-013) says: migration MUST be "held without running automatically in production." The `section_component` column on `PostCategory` supersedes it.

**Decision**: Create migration file `[date]_drop_homepage_sections_table.php` with a clear comment. Document in `quickstart.md` that this migration must be run manually during a maintenance window (not via automated deploy). Do NOT add it to `DatabaseSeeder::run()`.
