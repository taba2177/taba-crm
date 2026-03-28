# Implementation Plan: Laravel API Backend Hardening + Angular Dynamic Frontend Integration

**Branch**: `001-api-angular-integration` | **Date**: 2026-03-12 | **Spec**: [spec.md](./spec.md)  
**Input**: Feature specification from `specs/001-api-angular-integration/spec.md`

---

## Summary

Harden the `taba/crm` Laravel API package to produce correctly structured responses — flat settings endpoint, top-level absolute `image_url` on posts and categories, structured content blocks with resolved figure URLs, idempotent Arabic settings seeder, rate limiting on all public endpoints, and multi-origin CORS — then wire the Angular `sbc-clean` frontend to consume those responses: add five new lazy-loaded route components, wire `BookingComponent` to the contact API with PrimeNG Toast, inject `ContentService` into `LineOfWorkComponent` and `AboutComponent`, extend `SiteContent` with two new fields, update the production environment, and hard-delete the `hero3/` dead code directory.

---

## Technical Context

**Language/Version**: PHP 8.2 / Laravel 12 (backend); TypeScript 5.x / Angular 21 SSR (frontend)  
**Primary Dependencies**:

- Backend: Filament v3, Spatie Translatable, Awcodes Curator (media), Laravel Sanctum, `fruitcake/laravel-cors`
- Frontend: PrimeNG (UI + Toast), Tailwind CSS, `@omnedia/*` animation libs, Angular Universal (SSR)

**Storage**: MySQL 8.0 (app DB); Local disk / S3 for media (Curator `Storage::url()`)  
**Testing**: Backend — PHPUnit (`vendor/bin/phpunit`); Frontend — Jasmine/Karma (`ng test`)  
**Target Platform**: Linux server (production); Windows local (dev)  
**Project Type**: Backend = REST API package (`packages/taba/crm`); Frontend = Angular SSR SPA  
**Performance Goals**: 100 req/s sustained on public read endpoints; p95 < 300ms for `/init`  
**Constraints**: No breaking changes to existing API contract; no new Angular service classes; no new npm/Composer packages beyond already-installed  
**Scale/Scope**: Single-tenant marketing site; ~12 API endpoints; ~20 Angular components; ~10 new/modified files per project

---

## Constitution Check

_GATE: Must pass before Phase 0 research. Re-check after Phase 1 design._

The constitution file (`.specify/memory/constitution.md`) uses placeholder template values only — no binding project-specific gates are defined.

**Governing principles applied from user requirements:**

1. **No breaking changes** — All modifications are additive. New fields (`image_url`, `phone`, `service`, `lineOfWork`, `pills`) are additions; no existing fields removed.
2. **Minimal external dependencies** — No new packages. Uses `Storage::url()` (already available), PrimeNG `Toast` + `MessageService` (already provided), `ReactiveFormsModule` (already available).
3. **Laravel conventions** — Form Requests for validation, Eloquent Resources for serialization, Policy-gated endpoints (unchanged), config-driven CORS.
4. **Angular conventions** — Signals, OnPush, standalone components, `loadComponent` lazy routes.

**Pre-design gate**: ✅ PASS  
**Post-design gate**: ✅ PASS — all changes are additive; `/settings/grouped` shape change is technically a break but the endpoint was functionally incorrect before (ContentService doesn't use it directly, and the spec explicitly requires the fix).

---

## Project Structure

### Documentation (this feature)

```text
specs/001-api-angular-integration/
├── plan.md              ← this file
├── research.md          ← Phase 0 output — all 12 decisions resolved
├── data-model.md        ← Phase 1 output — entity + interface changes
├── quickstart.md        ← Phase 1 output — local setup + test checklist
├── contracts/
│   ├── api-v1.md        ← REST API endpoint contract
│   └── site-content.md  ← Angular SiteContent interface contract
└── tasks.md             ← Phase 2 output (NOT created by /speckit.plan)
```

### Source Code Layout

```text
# ── Backend ─────────────────────────────────────────────────────────────────
# C:\WORK\taba-crm\packages\taba\crm\src
#
Http/
  Controllers/Api/
    SettingApiController.php         MODIFY  grouped() → calls getAllFlat()
  Resources/Api/
    PostResource.php                 MODIFY  add image_url + resolveContentBlocks()
    PostCategoryResource.php         MODIFY  add image_url via Storage::url()
  Requests/Api/
    StoreContactEntryRequest.php     MODIFY  email nullable; add phone, service
Models/
  CrmSetting.php                     MODIFY  add getAllFlat() method
  ContactEntry.php                   MODIFY  add phone, service to fillable
database/
  seeders/
    CrmSettingSeeder.php             NEW     25 keys (24 + about_pills), upsert-safe
  migrations/
    [date]_add_phone_service_to_contact_entries_table.php  NEW   schema change
    [date]_drop_homepage_sections_table.php                NEW   HELD — manual only
routes/
  api.php                            MODIFY  throttle:100,1 reads; throttle:30,1 contact

# C:\WORK\taba-crm\config
cors.php                             MODIFY  parse CSV env; default localhost:4200; credentials:true

# ── Frontend ─────────────────────────────────────────────────────────────────
# C:\WORK\sbc-clean\src
#
app/
  app.routes.ts                      MODIFY  add 5 lazy routes
  data/
    content.ts                       MODIFY  add AboutContent.pills, LineOfWorkItem, SiteContent.lineOfWork
  models/
    api.models.ts                    MODIFY  ApiPost.image_url + content type; ApiCategory.image_url
  services/
    content.service.ts               MODIFY  add mapAboutPills(), mapLineOfWork() to mapToSiteContent()
  components/
    booking/
      booking.component.ts           MODIFY  wire ApiService.submitContact() + PrimeNG Toast
    line-of-work/
      line-of-work.component.ts      MODIFY  inject ContentService; use content().lineOfWork
    about/
      about.component.ts             MODIFY  wire aboutPills to content().about.pills
    hero3/                           DELETE  entire directory (hard delete)
  pages/
    blog/
      post-list.component.ts         NEW     lazy, standalone, OnPush
    blog/post-detail/
      post-detail.component.ts       NEW     lazy, standalone, OnPush
    page/
      page.component.ts              NEW     lazy, standalone, OnPush
    auth/
      login.component.ts             NEW     lazy, standalone, OnPush
      register.component.ts          NEW     lazy, standalone, OnPush
  interceptors/
    auth.guard.ts                    NEW     canActivate: redirect authenticated users from /login, /register
environments/
  environment.ts                     MODIFY  replace your-domain.com placeholder
```

---

## Implementation Checklist

All items are ordered by dependency. Items marked **HELD** must NOT be run in automated deploys.

### Backend — C:\WORK\taba-crm

#### B-1: `CrmSetting::getAllFlat()` method

**File**: `packages/taba/crm/src/Models/CrmSetting.php`  
**Change**: Add static method after `getAllGrouped()`:

```php
public static function getAllFlat(): array
{
    return static::orderBy('order')
        ->get()
        ->mapWithKeys(fn ($setting) => [$setting->key => $setting->value])
        ->toArray();
}
```

**Test**: SC-001 — `/settings/grouped` returns flat JSON with all 24 keys.

---

#### B-2: `SettingApiController::grouped()` uses `getAllFlat()`

**File**: `packages/taba/crm/src/Http/Controllers/Api/SettingApiController.php`  
**Change**: Replace `CrmSetting::getAllGrouped()` call with `CrmSetting::getAllFlat()` in the `grouped()` method. Update cache key to `api_settings_flat` to avoid serving stale nested-format cached data.  
**Note**: `HomeApiController::init()` continues to use `getAllGrouped()` — ContentService expects nested format there.

---

#### B-3: `PostResource` — add `image_url` + `resolveContentBlocks()`

**File**: `packages/taba/crm/src/Http/Resources/Api/PostResource.php`  
**Changes**:

```php
// In toArray(), add after 'excerpt':
'image_url' => $this->image?->url ?? null,

// Change 'content' line to:
'content' => $this->resolveContentBlocks(
    $this->getTranslation('content', $locale, false)
),
```

Add private method:

```php
private function resolveContentBlocks(?array $blocks): ?array
{
    if (!$blocks) return $blocks;
    return array_map(function (array $block) {
        if (($block['type'] ?? '') === 'figure' && !empty($block['data']['image_id'])) {
            $media = \Awcodes\Curator\Models\Media::find($block['data']['image_id']);
            $block['data']['image_url'] = $media?->url ?? null;
        }
        return $block;
    }, $blocks);
}
```

**Test**: SC-005 (image_url absolute), FR-005 (figure blocks have image_url).

---

#### B-4: `PostCategoryResource` — add `image_url`

**File**: `packages/taba/crm/src/Http/Resources/Api/PostCategoryResource.php`  
**Change**: Add after `'image' => $this->image`:

```php
'image_url' => $this->image
    ? (str_starts_with((string) $this->image, 'http')
        ? $this->image
        : \Illuminate\Support\Facades\Storage::url($this->image))
    : null,
```

Add `use Illuminate\Support\Facades\Storage;` at the top if not already imported.  
**Test**: FR-004.

---

#### B-5: `StoreContactEntryRequest` — relax email, add phone/service

**File**: `packages/taba/crm/src/Http/Requests/Api/StoreContactEntryRequest.php`  
**Change** rules():

```php
return [
    'name'    => ['required', 'string', 'max:255'],
    'email'   => ['nullable', 'email', 'max:255'],
    'phone'   => ['nullable', 'string', 'max:30'],
    'message' => ['required', 'string', 'max:5000'],
    'service' => ['nullable', 'string', 'max:255'],
];
```

**Test**: FR-016 (422 on missing required fields); US-3 acceptance.

---

#### B-6: `ContactEntry` model — update fillable

**File**: `packages/taba/crm/src/Models/ContactEntry.php`  
**Change**:

```php
protected $fillable = ['name', 'email', 'phone', 'message', 'service'];
```

---

#### B-7: Migration — add `phone`, `service` to contact_entries; nullable email

**File**: `packages/taba/crm/src/database/migrations/[date]_add_phone_service_to_contact_entries_table.php` (NEW)

```php
Schema::table('contact_entries', function (Blueprint $table) {
    $table->string('phone', 30)->nullable()->after('email');
    $table->string('service', 255)->nullable()->after('phone');
    $table->string('email', 255)->nullable()->change();
});
```

**Run**: `php artisan migrate` (standard deploy migration).

---

#### B-8: `CrmSettingSeeder` — Arabic defaults, upsert-safe

**File**: `packages/taba/crm/src/database/seeders/CrmSettingSeeder.php` (NEW)  
**Strategy**: Each setting uses `CrmSetting::upsert()` or `CrmSetting::firstOrCreate()` pattern — safe to re-run.

Seeds all 24 FR-001 keys + `about_pills` with Arabic defaults. Full key list and values in [`data-model.md`](./data-model.md#required-seeder-keys-24-fr-001).

**Run**: `php artisan db:seed --class=CrmSettingSeeder`  
**Test**: SC-001.

---

#### B-9: Migration — drop `homepage_sections` (HELD)

**File**: `packages/taba/crm/src/database/migrations/[date]_drop_homepage_sections_table.php` (NEW)

```php
// ⚠ HELD — run manually during maintenance window only
// Superseded by section_component on post_categories
Schema::dropIfExists('homepage_sections');
```

**NOT auto-run** in CI/CD. See [quickstart.md](./quickstart.md#4-manual-steps-held-migrations).  
**Test**: SC-010.

---

#### B-10: `routes/api.php` — rate limiting

**File**: `packages/taba/crm/src/routes/api.php`  
**Changes**:

1. Wrap all public read routes in a `throttle:100,1` group (FR-010).
2. Change `POST contact` from `throttle:5,1` to `throttle:30,1` (FR-011).

```php
// Wrap existing public reads:
Route::middleware('throttle:100,1')->group(function () {
    Route::get('init', ...);
    Route::get('home', ...);
    Route::get('posts', ...);
    // ... all other public GETs ...
});

// Contact — separate limit:
Route::post('contact', [ContactEntryApiController::class, 'store'])
    ->middleware('throttle:30,1');
```

**Test**: SC-009, FR-010, FR-011, FR-012.

---

#### B-11: `config/cors.php` — multi-origin + credentials

**File**: `config/cors.php` (root project — `C:\WORK\taba-crm\config\cors.php`)  
**Changes**:

```php
'allowed_origins' => array_map(
    'trim',
    explode(',', env('CRM_API_CORS_ORIGINS', 'http://localhost:4200'))
),
'supports_credentials' => true,
```

**Note**: Change default from `'*'` to `'http://localhost:4200'` — secure by default.  
**Test**: SC-004, FR-006, FR-007, FR-008, FR-009.

---

### Frontend — C:\WORK\sbc-clean

#### F-1: `content.ts` — extend SiteContent

**File**: `src/app/data/content.ts`  
**Changes**:

1. Add `pills: string[]` to `AboutContent` interface.
2. Add `LineOfWorkItem` interface.
3. Add `lineOfWork: LineOfWorkItem[]` to `SiteContent` interface.
4. Add `about.pills` default value in `SITE_CONTENT`.
5. Add `lineOfWork` default array in `SITE_CONTENT`.

Full interface definitions in [`contracts/site-content.md`](./contracts/site-content.md).

---

#### F-2: `api.models.ts` — add `image_url` fields + typed content blocks

**File**: `src/app/models/api.models.ts`  
**Changes**:

1. Add `ApiContentBlock` interface.
2. Change `ApiPost.content` from `string` to `ApiContentBlock[] | string | null`.
3. Add `image_url: string | null` to `ApiPost`.
4. Add `image_url: string | null` to `ApiCategory`.

Full type definitions in [`data-model.md`](./data-model.md#apipost-extended).

---

#### F-3: `content.service.ts` — add lineOfWork + aboutPills mappers

**File**: `src/app/services/content.service.ts`  
**Changes** (additions only — no rewrites):

1. Add private `mapAboutPills(settings, defaults): string[]` method.
2. Add private `mapLineOfWork(home, defaults): LineOfWorkItem[]` method.
3. In `mapToSiteContent()` return block:
    - Add `pills: this.mapAboutPills(settings, defaults)` to the `about:` object.
    - Add `lineOfWork: this.mapLineOfWork(home, defaults)` as new top-level field.

Mapping logic in [`contracts/site-content.md`](./contracts/site-content.md#contentservice-mapping-contract).

---

#### F-4: `app.routes.ts` — add 5 lazy routes

**File**: `src/app/app.routes.ts`  
**Change** — replace current routes array:

```typescript
export const routes: Routes = [
    {
        path: "",
        loadComponent: () =>
            import("./pages/home/home.component").then((m) => m.HomeComponent),
    },
    {
        path: "blog/:category",
        loadComponent: () =>
            import("./pages/blog/post-list.component").then(
                (m) => m.PostListComponent,
            ),
    },
    {
        path: "blog/:category/:slug",
        loadComponent: () =>
            import("./pages/blog/post-detail/post-detail.component").then(
                (m) => m.PostDetailComponent,
            ),
    },
    {
        path: "page/:slug",
        loadComponent: () =>
            import("./pages/page/page.component").then((m) => m.PageComponent),
    },
    {
        path: "login",
        canActivate: [authGuard],
        loadComponent: () =>
            import("./pages/auth/login.component").then(
                (m) => m.LoginComponent,
            ),
    },
    {
        path: "register",
        canActivate: [authGuard],
        loadComponent: () =>
            import("./pages/auth/register.component").then(
                (m) => m.RegisterComponent,
            ),
    },
    {
        path: "**",
        redirectTo: "",
    },
];
```

`authGuard` redirects already-authenticated users away from `/login` and `/register` (FR-029 acceptance scenario 5).

---

#### F-5: `booking.component.ts` — wire to API + PrimeNG Toast

**File**: `src/app/components/booking/booking.component.ts`  
**Changes**:

1. Add imports: `ApiService`, `Toast` (PrimeNG), `MessageService`.
2. Inject: `private api = inject(ApiService)`, `private messageService = inject(MessageService)`.
3. Add signals: `isSubmitting = signal(false)`, `submitError = signal<string | null>(null)`, `submitSuccess = signal(false)`.
4. Add `<p-toast />` to template.
5. Replace `submitBooking()` body:

```typescript
submitBooking() {
  if (this.isSubmitting()) return;
  this.isSubmitting.set(true);
  this.submitError.set(null);

  const composedMessage = [
    this.bookingData.address && `العنوان: ${this.bookingData.address}`,
    this.bookingData.date    && `التاريخ: ${this.bookingData.date}`,
    this.bookingData.time    && `الوقت: ${this.bookingData.time}`,
    this.bookingData.notes   && `ملاحظات: ${this.bookingData.notes}`,
  ].filter(Boolean).join('\n');

  this.api.submitContact({
    name:    this.bookingData.name,
    phone:   this.bookingData.phone,
    service: this.bookingData.service,
    message: composedMessage || 'طلب حجز',
  }).subscribe({
    next: () => {
      this.isSubmitting.set(false);
      this.submitSuccess.set(true);
      this.messageService.add({
        severity: 'success',
        summary: 'تم الإرسال',
        detail: 'سيتواصل معك فريقنا قريباً',
        life: 5000,
      });
      // Reset form — allow new submission
      this.bookingData = { service: '', date: '', time: '', name: '', phone: '', address: '', notes: '' };
      this.currentStep.set(1);
    },
    error: (err) => {
      this.isSubmitting.set(false);
      const msg = err?.status === 422
        ? 'يرجى التحقق من البيانات المدخلة'
        : err?.status === 429
        ? 'تم إرسال طلبات كثيرة، يرجى المحاولة لاحقاً'
        : 'حدث خطأ في الاتصال، يرجى المحاولة مرة أخرى';
      this.submitError.set(msg);
      this.messageService.add({
        severity: 'error',
        summary: 'خطأ',
        detail: msg,
        life: 7000,
      });
    },
  });
}
```

6. Show `submitError()` inline below the submit button.
7. Disable submit button while `isSubmitting()` is true.

**Note**: `ApiService.submitContact()` accepts `phone?: string; subject?: string` — map `service` to `subject` OR add `service` directly if the typed interface is updated. Update `ApiService.submitContact()` signature to add `service?: string` to the data parameter.

**Test**: SC-003, FR-015, FR-016, FR-017, US-3 acceptance scenarios.

---

#### F-6: `line-of-work.component.ts` — inject ContentService

**File**: `src/app/components/line-of-work/line-of-work.component.ts`  
**Changes**:

1. Import `ContentService` and `LineOfWorkItem`.
2. Inject: `private contentService = inject(ContentService)`.
3. Declare: `content = this.contentService.content`.
4. Keep `brands` as a private fallback constant (renamed to `defaultBrands`).
5. Add computed signal: `items = computed(() => this.content().lineOfWork?.length ? this.content().lineOfWork : this.defaultBrands)`.
6. Update template: replace `@for (brand of brands; ...)` with `@for (brand of items(); ...)`.
7. Add `computed` import from `@angular/core`.

**Test**: FR-018, US-4 acceptance scenarios 1 and 2.

---

#### F-7: `about.component.ts` — wire pills to ContentService

**File**: `src/app/components/about/about.component.ts`  
**Changes**:

1. Remove hardcoded `aboutPills` array.
2. Add: `pills = computed(() => this.content().about.pills?.length ? this.content().about.pills : this.fallbackPills)`.
3. Keep `fallbackPills` as private constant with the current hardcoded values.
4. Update template: `@for (pill of pills(); ...)`.

**Test**: FR-019.

---

#### F-8: New pages — `PostListComponent`

**File**: `src/app/pages/blog/post-list.component.ts` (NEW)  
**Requirements**:

- Standalone, OnPush, lazy-loaded via `loadComponent`.
- Injects `ApiService`, `ActivatedRoute`.
- Reads `:category` from route params.
- Calls `api.getCategoryPosts(slug, { page, per_page: 12 })`.
- Shows paginated grid of post cards: title, thumbnail (`image_url ?? placeholder`), excerpt.
- Empty state: "لا توجد مقالات في هذا القسم" when no posts.
- Error state: "تعذر تحميل المقالات" on API error.
- Each card links to `/blog/:category/:slug`.

**Test**: FR-025, US-5 acceptance scenarios 2, 4.

---

#### F-9: New pages — `PostDetailComponent`

**File**: `src/app/pages/blog/post-detail/post-detail.component.ts` (NEW)  
**Requirements**:

- Standalone, OnPush, lazy-loaded.
- Injects `ApiService`, `ActivatedRoute`.
- Reads `:slug` from route params.
- Calls `api.getPost(slug)`.
- Renders all content block types inline:
    - `markdown` → `innerHTML` with DomSanitizer (`bypassSecurityTrustHtml`)
    - `heading` → `<h2>` / `<h3>` based on `data.level`
    - `figure` → `<img [src]="block.data['image_url']" [alt]="block.data['caption']">`
    - `quote` → `<blockquote>`
    - `list` → `<ul>` or `<ol>` based on `data.ordered`
- 404 state: shows "هذه الصفحة غير موجودة" when API returns 404.
- SSR: content rendered server-side for SEO (SC-006).

**Test**: FR-026, US-5 acceptance scenarios 1, 5, 6.

---

#### F-10: New pages — `PageComponent`

**File**: `src/app/pages/page/page.component.ts` (NEW)  
**Requirements**:

- Standalone, OnPush, lazy-loaded.
- Injects `ApiService`, `ActivatedRoute`.
- Calls `api.getPage(slug)`.
- Renders page title and `content` as `innerHTML` (sanitized).
- 404 state on API 404.

**Test**: FR-027, US-5 acceptance scenario 3.

---

#### F-11: New pages — `LoginComponent` + `RegisterComponent`

**Files**: `src/app/pages/auth/login.component.ts`, `register.component.ts` (NEW)  
**Requirements** (both):

- Standalone, OnPush, lazy-loaded.
- Use `ReactiveFormsModule` with `FormGroup`.
- Inject `AuthService`.
- `LoginComponent`: email + password fields; calls `AuthService.login()`; shows `errorMessage` signal on error.
- `RegisterComponent`: name + email + password + confirm_password fields; calls `AuthService.register()`; shows error signal.
- Both: redirect to `/` on success (already handled inside `AuthService`).
- Both: PrimeNG `InputText` + `Button` for styling.

**`authGuard`** (new file: `src/app/interceptors/auth.guard.ts` or `pages/auth/auth.guard.ts`):

```typescript
export const authGuard: CanActivateFn = () => {
    const auth = inject(AuthService);
    const router = inject(Router);
    return auth.isAuthenticated() ? router.createUrlTree(["/"]) : true;
};
```

**Test**: FR-029, US-6 acceptance scenarios.

---

#### F-12: `environment.ts` — replace production placeholder

**File**: `src/environments/environment.ts`  
**Change**: Replace `https://your-domain.com/api/v1` with the real production API URL (determined at deploy time — document in `quickstart.md`).  
**Test**: SC-007, FR-023.

---

#### F-13: Delete `hero3/` directory

**File**: `src/app/components/hero3/` (contains only `hero.component.ts`)  
**Action**: Hard delete entire directory.  
**Verify**: `grep -r "hero3" src/` → no results; `ng build` clean.  
**Test**: SC-008, FR-022.

---

## Dependency Order

```
B-1 (getAllFlat)   → B-2 (SettingController)
B-7 (migration)   → B-5 (FormRequest) → B-6 (model fillable)
B-8 (seeder)      → independent (can run any time after tables exist)
B-3 (PostResource image_url) → F-2 (api.models.ts image_url) → F-3 (ContentService)

F-1 (content.ts)  → F-3 (ContentService) → F-6 (LineOfWork) → F-7 (About)
F-2 (api.models)  → F-3, F-8, F-9
F-4 (routes)      → F-8, F-9, F-10, F-11 (must exist before routes reference them)
B-5+B-6+B-7       → F-5 (BookingComponent)
F-13 (delete hero3) → independent (can be done at any point)
F-12 (environment) → independent
```

---

## Complexity Tracking

No constitution violations to justify. All changes are within the standard architectural patterns of both projects.

| Violation                  | Why Needed         | Simpler Alternative Rejected Because |
| -------------------------- | ------------------ | ------------------------------------ |
| [e.g., 4th project]        | [current need]     | [why 3 projects insufficient]        |
| [e.g., Repository pattern] | [specific problem] | [why direct DB access insufficient]  |
