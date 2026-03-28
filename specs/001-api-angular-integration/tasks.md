# Tasks: Laravel API Backend Hardening + Angular Dynamic Frontend Integration

**Feature**: `001-api-angular-integration`
**Generated**: 2026-03-12
**Input**: spec.md, plan.md, data-model.md, contracts/api-v1.md, contracts/site-content.md

---

## Format: `[ID] [P?] [Story?] Description — file path`

- **[P]**: Parallelizable (separate files, no unresolved deps)
- **[US#]**: User Story label from spec.md
- **Deps**: Prior task IDs that must be complete first
- **Project**: `taba-crm` = `C:\WORK\taba-crm` | `sbc-clean` = `C:\WORK\sbc-clean`

---

## Phase 1 — Laravel Backend

**Ordering rationale**: Model method first → API resources (image_url, content blocks) → request/model/schema for contact → seeder → CORS → rate limiting → held migration last.

### US1: API Serves Complete, Correctly Structured Data (P1)

**Goal**: `/api/v1/settings/grouped` returns a flat `{ key: value }` object; every Post and Category includes an absolute `image_url`; content blocks include resolved figure `image_url`.

**Independent Test**: `GET /api/v1/settings/grouped` → flat JSON with all 24 keys, non-null values. `GET /api/v1/posts` → each post has `image_url` starting with `https://`.

---

- [ ] B-01 [US1] Add `getAllFlat()` static method to `CrmSetting` model — `packages/taba/crm/src/Models/CrmSetting.php`
    - **Project**: taba-crm
    - **Change**: Add `public static function getAllFlat(): array` after the existing `getAllGrouped()` method. Implementation: `return static::orderBy('order')->get()->mapWithKeys(fn ($s) => [$s->key => $s->value])->toArray();`
    - **Deps**: none

- [ ] B-02 [US1] Update `SettingApiController::grouped()` to call `getAllFlat()` — `packages/taba/crm/src/Http/Controllers/Api/SettingApiController.php`
    - **Project**: taba-crm
    - **Change**: In the `grouped()` method, replace `CrmSetting::getAllGrouped()` with `CrmSetting::getAllFlat()`. Change the cache key from whatever it is now to `api_settings_flat` to prevent serving stale nested-format data. Leave `HomeApiController::init()` calling `getAllGrouped()` — ContentService's init path expects grouped format.
    - **Deps**: B-01

- [ ] B-03 [P] [US1] Add `image_url` field and `resolveContentBlocks()` to `PostResource` — `packages/taba/crm/src/Http/Resources/Api/PostResource.php`
    - **Project**: taba-crm
    - **Change**:
        1. In `toArray()`, add after `'excerpt'`: `'image_url' => $this->image?->url ?? null,`
        2. Change the existing `'content'` line to: `'content' => $this->resolveContentBlocks($this->getTranslation('content', $locale, false)),`
        3. Add private method `resolveContentBlocks(?array $blocks): ?array` that iterates blocks; for any block where `$block['type'] === 'figure'` and `$block['data']['image_id']` is set, resolves `\Awcodes\Curator\Models\Media::find($block['data']['image_id'])?->url ?? null` and injects it as `$block['data']['image_url']`. Returns unmodified blocks for all other types.
    - **Deps**: none (Curator model already available via vendor)

- [ ] B-04 [P] [US1] Add `image_url` field to `PostCategoryResource` — `packages/taba/crm/src/Http/Resources/Api/PostCategoryResource.php`
    - **Project**: taba-crm
    - **Change**: Add `use Illuminate\Support\Facades\Storage;` at the top if not present. In `toArray()`, add after the `'image'` field: `'image_url' => $this->image ? (str_starts_with((string) $this->image, 'http') ? $this->image : Storage::url($this->image)) : null,`
    - **Deps**: none

- [ ] B-05 [US1] Create `CrmSettingSeeder` with 24 Arabic default keys + `about_pills` — `packages/taba/crm/src/database/seeders/CrmSettingSeeder.php` _(NEW FILE)_
    - **Project**: taba-crm
    - **Change**: Create a new seeder class `CrmSettingSeeder`. Use `CrmSetting::upsert([...], ['key'], ['value'])` — idempotent, safe to re-run. Must seed all 24 FR-001 keys plus `about_pills`. All keys, groups, and Arabic default values are specified in `data-model.md` § "Required seeder keys". The `about_pills` value is a JSON-encoded array: `'["كوادر فنية مدربة","معدات وتقنيات حديثة","مواد آمنة ومعتمدة","ضمان جودة الخدمة"]'`. Each row must include `key`, `value`, `group`, `type`, `order`; `label` and `description` can be the Arabic key name in JSON translatable format. Set `is_translatable = false` for all (values are already Arabic strings). Run via: `php artisan db:seed --class=CrmSettingSeeder`
    - **Deps**: B-01 (`getAllFlat` must exist to verify the seeder result via the endpoint)

**Checkpoint — US1 verifiable**: After B-01→B-05, run seeder then hit `GET /api/v1/settings/grouped` → flat JSON; `GET /api/v1/posts` → `image_url` present and absolute.

---

### US2: CORS (P1)

**Goal**: Angular dev server on `localhost:4200` and configured production origins can call all `/api/v1/*` routes without CORS preflight failures.

**Independent Test**: `OPTIONS http://localhost:8000/api/v1/init` with `Origin: http://localhost:4200` → response includes `Access-Control-Allow-Origin: http://localhost:4200` and 200.

---

- [ ] B-06 [US2] Configure multi-origin CORS with credentials — `config/cors.php` _(root project)_
    - **Project**: taba-crm
    - **Change**:
        1. Replace the `'allowed_origins'` value with: `array_map('trim', explode(',', env('CRM_API_CORS_ORIGINS', 'http://localhost:4200')))`
        2. Set `'supports_credentials' => true`
        3. Ensure `'allowed_headers'` includes `Authorization`, `Content-Type`, `Accept`, `Accept-Language`, `X-Requested-With`, `X-XSRF-TOKEN`
        4. Ensure `'allowed_methods'` includes `GET`, `POST`, `PUT`, `PATCH`, `DELETE`, `OPTIONS`
        5. Set `'max_age' => 86400`
        6. Document in `.env.example`: `CRM_API_CORS_ORIGINS=http://localhost:4200,https://sbc-clean.com`
    - **Deps**: none (independent CORS config)

**Checkpoint — US2 verifiable**: After B-06, CORS preflight to any `/api/v1/` route from `localhost:4200` returns `Access-Control-Allow-Origin` header.

---

### US3: Booking/Contact Form Backend (P2)

**Goal**: `POST /api/v1/contact` accepts `phone` and `service` fields; `email` is optional.

---

- [ ] B-07 [US3] Create migration to add `phone`, `service` columns and make `email` nullable on `contact_entries` — `packages/taba/crm/src/database/migrations/YYYY_MM_DD_add_phone_service_to_contact_entries_table.php` _(NEW FILE)_
    - **Project**: taba-crm
    - **Change**: Create a new migration. In `up()`: `$table->string('phone', 30)->nullable()->after('email')`, `$table->string('service', 255)->nullable()->after('phone')`, `$table->string('email', 255)->nullable()->change()`. In `down()`: drop `phone` and `service` columns and revert `email` to `not null`. Run via: `php artisan migrate`
    - **Deps**: none

- [ ] B-08 [P] [US3] Update `ContactEntry` model `$fillable` array — `packages/taba/crm/src/Models/ContactEntry.php`
    - **Project**: taba-crm
    - **Change**: Set `protected $fillable = ['name', 'email', 'phone', 'message', 'service'];`
    - **Deps**: B-07 (columns must exist before model references them, though PHP won't error at class load time)

- [ ] B-09 [P] [US3] Update `StoreContactEntryRequest` validation rules — `packages/taba/crm/src/Http/Requests/Api/StoreContactEntryRequest.php`
    - **Project**: taba-crm
    - **Change**: Replace the `rules()` return array with: `'name' => ['required', 'string', 'max:255']`, `'email' => ['nullable', 'email', 'max:255']`, `'phone' => ['nullable', 'string', 'max:30']`, `'message' => ['required', 'string', 'max:5000']`, `'service' => ['nullable', 'string', 'max:255']`. Remove any existing `'email' => 'required'` rules.
    - **Deps**: B-07

**Checkpoint — US3 backend verifiable**: `POST /api/v1/contact` with `{ name, phone, message, service }` (no email) → 201 Created. Database `contact_entries` row has populated `phone` and `service` columns.

---

### US8: Rate Limiting (P3)

**Goal**: Public read endpoints throttled at 100 req/min; contact write endpoint at 30 req/min. 429 responses include `Retry-After`.

---

- [ ] B-10 [US8] Apply throttle middleware groups in API routes — `packages/taba/crm/src/routes/api.php`
    - **Project**: taba-crm
    - **Change**:
        1. Wrap all existing public read routes (`init`, `home`, `posts`, `posts/{slug}`, `categories`, `categories/{slug}/posts`, `settings/grouped`, `menus`, `reviews`, `pages/{slug}`) in a `Route::middleware('throttle:100,1')->group(...)` block.
        2. Change the `POST contact` route's throttle from its current value (if any) to `throttle:30,1`. If no throttle exists, add `.middleware('throttle:30,1')` to that route.
        3. Auth routes (`auth/login`, `auth/register`) should remain outside the throttled group or have their own appropriate limits — do not change auth route throttling.
    - **Deps**: B-06 (CORS should be verified working before throttle is tested from browser)

**Checkpoint — US8 verifiable**: 101 rapid GETs to `/api/v1/posts` → 101st returns 429 with `Retry-After` header.

---

### US9: Dead Database Objects (P4)

**Goal**: `homepage_sections` table is documented for future removal via a HELD migration.

---

- [ ] B-11 [US9] Create HELD migration to drop `homepage_sections` table — `packages/taba/crm/src/database/migrations/YYYY_MM_DD_drop_homepage_sections_table.php` _(NEW FILE)_
    - **Project**: taba-crm
    - **Change**: Create a new migration file. At the top of the file add a prominent comment: `// ⚠ HELD — run manually during maintenance window only. Superseded by section_component column on post_categories.` In `up()`: `Schema::dropIfExists('homepage_sections');`. In `down()`: recreate the table definition (or leave as no-op with a comment explaining restoration requires a backup). **Do NOT run this migration in CI/CD.** Do NOT add it to `DatabaseSeeder` or any auto-run list. Document it in `quickstart.md` under "Manual Steps — Held Migrations".
    - **Deps**: none (standalone migration file)

---

## Phase 2 — Angular Frontend

**Ordering rationale**: Environment config first (independent) → data/type models → content service → component wiring → new page components → routes (must reference components) → dead code deletion last.

### US7: Production Environment (P2)

**Goal**: `ng build --configuration production` produces a bundle with no `your-domain.com` placeholder.

---

- [ ] F-01 [P] [US7] Replace production API URL placeholder in environment file — `src/environments/environment.ts`
    - **Project**: sbc-clean
    - **Change**: Find the `apiUrl` property currently set to `'https://your-domain.com/api/v1'` (or similar placeholder). Replace the placeholder host with the real production API base URL. If the URL is not yet determined, replace with a clearly labelled `TODO` constant and document the required change in `quickstart.md` § "Pre-deploy checklist". The development environment file (`environment.development.ts`) should already point to `http://localhost:8000/api/v1` — verify this is correct and leave it unchanged.
    - **Deps**: none

---

### Foundation — Models and Interfaces (blocks US1/US3/US4/US5)

These two tasks are pure type/interface changes with no runtime logic. Must be done before any service or component tasks that reference the new fields.

---

- [ ] F-02 [P] Add `pills`, `LineOfWorkItem`, and `lineOfWork` to SiteContent — `src/app/data/content.ts`
    - **Project**: sbc-clean
    - **Change**:
        1. Add `pills: string[]` to the `AboutContent` interface (after `stats: Stat[]`).
        2. Add new `export interface LineOfWorkItem { title: string; subtitle: string; icon: string; imageUrl: string | null; }` (before `SiteContent`).
        3. Add `lineOfWork: LineOfWorkItem[]` to the `SiteContent` interface (after `footer: FooterContent`).
        4. In the `SITE_CONTENT` constant, add `pills: ['كوادر فنية مدربة', 'معدات وتقنيات حديثة', 'مواد آمنة ومعتمدة', 'ضمان جودة الخدمة']` inside the `about: { ... }` object.
        5. In `SITE_CONTENT`, add a new top-level `lineOfWork: [...]` array with the 4 Arabic default items from `data-model.md` § "LineOfWorkItem — new interface + SiteContent.lineOfWork field". All four items have `imageUrl: null`.
    - **Deps**: none

- [ ] F-03 [P] Add `ApiContentBlock`, `image_url` to `ApiPost`, `image_url` to `ApiCategory` — `src/app/models/api.models.ts`
    - **Project**: sbc-clean
    - **Change**:
        1. Add `export interface ApiContentBlock { type: 'markdown' | 'heading' | 'figure' | 'quote' | 'list' | string; data: Record<string, unknown> & { image_url?: string | null; }; }` before `ApiPost`.
        2. On `ApiPost`: change the `content` field type from `string` (or whatever it currently is) to `ApiContentBlock[] | string | null`. Add new field `image_url: string | null`.
        3. On `ApiCategory`: add `image_url: string | null`.
        4. Do not remove existing fields — all additions are backward-compatible.
    - **Deps**: none

---

### US1 + US4: ContentService Mapping (P1/P2)

---

- [ ] F-04 [US1] Add `mapAboutPills()` and `mapLineOfWork()` to `ContentService` — `src/app/services/content.service.ts`
    - **Project**: sbc-clean
    - **Change** (additions only — do not touch existing methods):
        1. Add `private mapAboutPills(settings: ApiSettingsGrouped, defaults: SiteContent): string[]` method. Logic: call `this.setting(settings, 'about_pills')`; if non-null, attempt `JSON.parse()`; if result is an array, return it; on any error or non-array, return `defaults.about.pills`.
        2. Add `private mapLineOfWork(home: ApiHomeData | null, defaults: SiteContent): LineOfWorkItem[]` method. Logic: if `home?.featured_posts` is empty or null, return `defaults.lineOfWork`; otherwise filter `featured_posts` where `p.category?.slug === 'line-of-work'`; if empty, return `defaults.lineOfWork`; otherwise map each `ApiPost` to `LineOfWorkItem`: `{ title: p.title, subtitle: p.excerpt ?? '', icon: p.icon ?? 'fa-solid fa-check', imageUrl: p.image_url ?? null }`.
        3. In `mapToSiteContent()` return block: in the `about: { ... }` object, add `pills: this.mapAboutPills(settings, defaults),`. As a new top-level field, add `lineOfWork: this.mapLineOfWork(home, defaults),`.
        4. Import `LineOfWorkItem` from `../data/content`.
    - **Deps**: F-02 (SiteContent interface), F-03 (ApiPost.image_url type)

---

### US3: Booking/Contact Form Frontend (P2)

---

- [ ] F-05 [US3] Extend `ApiService.submitContact()` to accept `service` field — `src/app/services/api.service.ts`
    - **Project**: sbc-clean
    - **Change**: Find the `submitContact()` method and its parameter/data type. Add `service?: string` to the contact data interface or inline parameter type (whichever pattern the method currently uses). The final outgoing payload should include `name`, `phone?`, `email?`, `message`, `service?` — matching the `POST /api/v1/contact` contract in `contracts/api-v1.md`. Do not break existing callers by keeping newly added fields optional.
    - **Deps**: none

- [ ] F-06 [US3] Wire `BookingComponent` to contact API with PrimeNG Toast — `src/app/components/booking/booking.component.ts`
    - **Project**: sbc-clean
    - **Change**:
        1. Add imports: `MessageService` from `primeng/api`, `Toast` from `primeng/toast`, `ApiService` (already available in project).
        2. Inject: `private api = inject(ApiService)` and `private messageService = inject(MessageService)` (if not already injected).
        3. Add to `imports` array in `@Component`: `Toast`, `MessageService` (or ensure `MessageService` is provided at component level if not provided globally).
        4. Add signals (if not present): `isSubmitting = signal(false)`, `submitError = signal<string | null>(null)`, `submitSuccess = signal(false)`.
        5. Add `<p-toast />` to the component template (top of template, outside main form wrapper).
        6. Replace the body of `submitBooking()` (currently a no-op or placeholder) with the implementation from `plan.md` § F-5: compose `composedMessage` from `address`, `date`, `time`, `notes`; call `this.api.submitContact({ name, phone, service, message })`.subscribe with: `next` → set `submitSuccess(true)`, show success toast, reset all booking data and return to step 1; `error` → check `err.status` for 422 (validation), 429 (rate limit), or generic — set `submitError` and show error toast.
        7. Add `[disabled]="isSubmitting()"` to the submit button in the template.
        8. Render `submitError()` signal value in an inline error element below the submit button (e.g. `@if (submitError()) { <p class="text-red-500">{{ submitError() }}</p> }`).
    - **Deps**: F-05 (ApiService updated signature), B-07 + B-08 + B-09 (backend must accept phone/service)

---

### US4: Dynamic Homepage Sections (P2)

---

- [ ] F-07 [US4] Inject `ContentService` into `LineOfWorkComponent` and replace static `brands` array — `src/app/components/line-of-work/line-of-work.component.ts`
    - **Project**: sbc-clean
    - **Change**:
        1. Import `ContentService`, `LineOfWorkItem` from their respective paths, and `computed` from `@angular/core`.
        2. Inject: `private contentService = inject(ContentService)`.
        3. Declare: `content = this.contentService.content`.
        4. Rename the existing `brands` (or equivalent) static array to `defaultBrands` (or `fallbackItems`) — keep its values intact.
        5. Add: `items = computed(() => this.content().lineOfWork?.length ? this.content().lineOfWork : this.defaultBrands)`.
        6. In the template, replace `@for (brand of brands; ...)` with `@for (brand of items(); ...)`. Update property bindings to match `LineOfWorkItem` shape: `.title`, `.subtitle`, `.icon`, `.imageUrl`.
        7. Handle `imageUrl` being `string | null`: use `@if (brand.imageUrl)` around any `<img>` tag; show icon fallback otherwise.
    - **Deps**: F-02 (LineOfWorkItem interface), F-04 (ContentService.content().lineOfWork populated)

- [ ] F-08 [US4] Wire `AboutComponent` pills to `ContentService` signal — `src/app/components/about/about.component.ts`
    - **Project**: sbc-clean
    - **Change**:
        1. Ensure `ContentService` is injected (it may already be — check first).
        2. Copy the hardcoded `aboutPills` array values to a private `fallbackPills` constant (do not delete the values, just rename/reuse them as fallback).
        3. Remove the field-level `aboutPills` array assignment. Replace with: `pills = computed(() => this.content().about.pills?.length ? this.content().about.pills : this.fallbackPills)`.
        4. In the template, replace `@for (pill of aboutPills; ...)` with `@for (pill of pills(); ...)`. Add `computed` import if not present.
    - **Deps**: F-02 (AboutContent.pills field), F-04 (ContentService maps about.pills)

**Checkpoint — US1+US4 verifiable**: Start Angular dev server. `ContentService` resolves from `/api/v1/init`. `LineOfWorkComponent` renders API posts; `AboutComponent` pills come from settings. Both fall back to static data if ContentService fails.

---

### US5: Route-Based Navigation (P3)

New page components must be created before they are referenced in the routes array.

---

- [ ] F-09 [P] [US5] Create `PostListComponent` — `src/app/pages/blog/post-list.component.ts` _(NEW FILE)_
    - **Project**: sbc-clean
    - **Change**: Create a standalone, `OnPush`, lazy-loadable component. Template must:
        - Inject `ApiService` and `ActivatedRoute`.
        - Read `:category` param from route (use `toSignal(route.paramMap)` pattern).
        - On category param change, call `apiService.getCategoryPosts(categorySlug, { page: 1, per_page: 12 })` (or equivalent existing method on `ApiService`).
        - Show a loading state while fetching.
        - Render a grid of post cards: each card shows `image_url ?? '/assets/placeholder.jpg'`, `title`, `excerpt`, and links to `/blog/:category/:slug`.
        - Show empty state message `لا توجد مقالات في هذا القسم` when the posts array is empty.
        - Show error state message `تعذر تحميل المقالات` when API call fails.
        - Use Tailwind CSS grid classes consistent with the rest of the project.
    - **Deps**: F-03 (ApiPost.image_url type)

- [ ] F-10 [P] [US5] Create `PostDetailComponent` — `src/app/pages/blog/post-detail/post-detail.component.ts` _(NEW FILE)_
    - **Project**: sbc-clean
    - **Change**: Create a standalone, `OnPush`, lazy-loadable component. Requirements:
        - Inject `ApiService`, `ActivatedRoute`, `DomSanitizer`.
        - Read `:slug` param from route.
        - Call `apiService.getPost(slug)`.
        - Render post `title`, formatted `published_at` date, and all `content` blocks in order. Block rendering rules:
            - `markdown` → render `data.text` as innerHTML via `DomSanitizer.bypassSecurityTrustHtml()`
            - `heading` → `<h2>` or `<h3>` based on `data['level']`
            - `figure` → `<img [src]="block.data['image_url']" [alt]="block.data['caption']">` (skip if `image_url` is null)
            - `quote` → `<blockquote>{{ block.data['text'] }}</blockquote>` with optional author attribution
            - `list` → `<ul>` or `<ol>` based on `data['ordered']`, with `<li>` for each item in `data['items']`
        - Show 404 state message `هذه الصفحة غير موجودة` when API returns HTTP 404.
        - Content must be rendered server-side for SSR/SEO compatibility (do not use `isPlatformBrowser` guards around the API call).
    - **Deps**: F-03 (ApiContentBlock type, ApiPost.image_url)

- [ ] F-11 [P] [US5] Create `PageComponent` — `src/app/pages/page/page.component.ts` _(NEW FILE)_
    - **Project**: sbc-clean
    - **Change**: Create a standalone, `OnPush`, lazy-loadable component. Requirements:
        - Inject `ApiService`, `ActivatedRoute`, `DomSanitizer`.
        - Read `:slug` param from route.
        - Call `apiService.getPage(slug)` (or equivalent — check existing ApiService methods).
        - Render page `title` in an `<h1>` and `content` as sanitized `innerHTML`.
        - Show loading state; show 404 message on HTTP 404 response.
    - **Deps**: F-03 (for API type alignment)

---

### US6: Authentication Routes (P3)

---

- [ ] F-12 [P] [US6] Create `authGuard` to redirect authenticated users from `/login` and `/register` — `src/app/pages/auth/auth.guard.ts` _(NEW FILE)_
    - **Project**: sbc-clean
    - **Change**: Create an `authGuard` as a `CanActivateFn`: `export const authGuard: CanActivateFn = () => { const auth = inject(AuthService); const router = inject(Router); return auth.isAuthenticated() ? router.createUrlTree(['/']) : true; };`. Import `AuthService` from its existing path in the project. Export `authGuard` as a named export.
    - **Deps**: none (`AuthService` already exists)

- [ ] F-13 [P] [US6] Create `LoginComponent` — `src/app/pages/auth/login.component.ts` _(NEW FILE)_
    - **Project**: sbc-clean
    - **Change**: Create a standalone, `OnPush`, lazy-loadable component. Requirements:
        - Imports: `ReactiveFormsModule`, PrimeNG `InputText`, `Button` (consistent with project patterns).
        - FormGroup with `email` (required, email validator) and `password` (required) controls.
        - Inject `AuthService`.
        - On valid submit: call `AuthService.login(email, password)` (check existing signature). On success, redirect to `/` (or `AuthService` may handle redirect internally — verify). On error: show Arabic error message signal, e.g., `'بيانات الدخول غير صحيحة'`.
        - Show field-level validation messages for required/format errors.
        - Disable submit button while request is in-flight.
    - **Deps**: F-12 (guard file, though not a module dependency — both needed for the route)

- [ ] F-14 [P] [US6] Create `RegisterComponent` — `src/app/pages/auth/register.component.ts` _(NEW FILE)_
    - **Project**: sbc-clean
    - **Change**: Create a standalone, `OnPush`, lazy-loadable component. Requirements:
        - FormGroup with `name` (required), `email` (required, email), `password` (required, min 8), `password_confirmation` (required, must match `password` using cross-field validator).
        - Inject `AuthService`.
        - On valid submit: call `AuthService.register(...)`. On success: redirect to `/`. On error: show Arabic error message.
        - Same PrimeNG + Tailwind styling as `LoginComponent`.
    - **Deps**: F-12

---

### US5+US6: Wire All Routes (P3)

---

- [ ] F-15 [US5] Add 5 lazy-loaded routes to `app.routes.ts` — `src/app/app.routes.ts`
    - **Project**: sbc-clean
    - **Change**: Add the following routes to the existing `routes` array. Insert before the `{ path: '**', redirectTo: '' }` wildcard catch-all (keep the wildcard last):
        ```typescript
        {
          path: 'blog/:category',
          loadComponent: () => import('./pages/blog/post-list.component').then(m => m.PostListComponent),
        },
        {
          path: 'blog/:category/:slug',
          loadComponent: () => import('./pages/blog/post-detail/post-detail.component').then(m => m.PostDetailComponent),
        },
        {
          path: 'page/:slug',
          loadComponent: () => import('./pages/page/page.component').then(m => m.PageComponent),
        },
        {
          path: 'login',
          canActivate: [authGuard],
          loadComponent: () => import('./pages/auth/login.component').then(m => m.LoginComponent),
        },
        {
          path: 'register',
          canActivate: [authGuard],
          loadComponent: () => import('./pages/auth/register.component').then(m => m.RegisterComponent),
        },
        ```
        Import `authGuard` from `./pages/auth/auth.guard`. Do not remove or reorder existing routes (home route must remain).
    - **Deps**: F-09 (PostListComponent), F-10 (PostDetailComponent), F-11 (PageComponent), F-12 (authGuard), F-13 (LoginComponent), F-14 (RegisterComponent)

**Checkpoint — US5+US6 verifiable**: Navigate to `/blog/news` → PostListComponent renders. Navigate to `/login` → LoginForm renders. Navigate to `/login` while authenticated → redirected to `/`.

---

### US9: Dead Code Removal (P4)

---

- [ ] F-16 [P] [US9] Delete `hero3/` component directory — `src/app/components/hero3/`
    - **Project**: sbc-clean
    - **Change**: Hard-delete the entire `hero3/` directory (contains `hero.component.ts` and any associated template/spec files). Before deleting: search the workspace with `grep -r "hero3\|Hero3Component" src/` to confirm no active references. If any live import or route reference is found, remove those first. After deletion: run `ng build` to confirm no compilation errors.
    - **Deps**: none (independent — no other task creates references to hero3)

---

## Dependencies & Execution Order

### Phase Dependency Map

```
B-01 ─────────────────────────────► B-02
B-07 ─────────────────────────────► B-08
               └──────────────────► B-09
B-03 ─┐
B-04 ─┤ (parallel) ─────────────── no deps
B-05  │ (needs B-01 for verification, not compilation)
B-06  │ (independent CORS) ──────► B-10

F-02 ─┐
F-03 ─┤ (parallel models) ──────► F-04 ──────────► F-07
      │                         │               └► F-08
      │                         └► (also gates F-09, F-10)
F-05 ──────────────────────────► F-06
F-09, F-10, F-11 ─┐ (parallel)
F-12, F-13, F-14 ─┤ (parallel after F-12)
                  └──────────────► F-15
```

### Cross-Phase Dependencies

| Frontend Task             | Requires Backend                                        |
| ------------------------- | ------------------------------------------------------- |
| F-06 (`BookingComponent`) | B-07, B-08, B-09 (contact_entries schema + validation)  |
| F-04 (`ContentService`)   | B-01, B-02, B-05 (flat settings endpoint + seeder data) |
| F-07, F-08                | B-03, B-04 (image_url in API responses)                 |
| F-09, F-10                | B-03 (PostResource image_url + content blocks)          |

### Parallel Opportunities

**Backend** (same developer can batch these):

- B-03 + B-04 (different Resource files)
- B-08 + B-09 (different files, both depend only on B-07)

**Frontend** (same developer can batch these):

- F-02 + F-03 (pure interface files)
- F-09 + F-10 + F-11 (three new page components, different files)
- F-13 + F-14 (two auth page components, different files)
- F-01 + F-16 (fully independent of all other tasks)

---

## Implementation Summary

| Phase                      | Tasks                  | Stories                               |
| -------------------------- | ---------------------- | ------------------------------------- |
| Phase 1 — Laravel Backend  | B-01 → B-11 (11 tasks) | US1, US2, US3, US8, US9               |
| Phase 2 — Angular Frontend | F-01 → F-16 (16 tasks) | US7, US1+US4, US3, US4, US5, US6, US9 |
| **Total**                  | **27 tasks**           | **7 user stories**                    |

**MVP Scope** (P1 stories only — US1 + US2): B-01 → B-06 + F-02 + F-03 + F-04 = 9 tasks. Delivers a working API with correct data shapes and no CORS errors. All other Angular wiring becomes testable from this baseline.
