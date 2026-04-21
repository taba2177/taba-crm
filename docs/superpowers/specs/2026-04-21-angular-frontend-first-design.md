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
- A new artisan command (`php artisan crm:install`) already exists and orchestrates all installation tasks. The frontend publish step is added as a new task inside it — no separate command is needed.
- The developer runs `php artisan crm:install` — everything including the Angular build is handled automatically.
- The published `angular.json` **must** use the **object form** for `outputPath` to suppress Angular 21's automatic `browser/` subdirectory:
  ```json
  "outputPath": {
    "base": "../public",
    "browser": ""
  }
  ```
  A plain string `"../public"` still produces `public/browser/index.html` — the empty `"browser"` key is required to place `index.html` directly in `public/`.
- Laravel's `web.php` gains a **catch-all route** that serves `public/index.html` for all paths not matched by API or admin routes.
- Existing Livewire/Blade routes are kept in `web.php` with `@deprecated` doc comments — they still function for any project that hasn't published and built the Angular frontend yet, and will be removed in a future version.
- **Exception:** `Route::get('/', Home::class)` is the one route that MUST be replaced (not just annotated), because `/{any}` with a required non-empty segment never matches `/`. Replace it with:
  ```php
  Route::get('/', fn() => response()->file(public_path('index.html')));
  ```

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
trackWhatsApp(source?: string): void   // fires POST /api/v1/actions { action: 'whatsapp', source, page: window.location.pathname }
trackCall(source?: string): void       // fires POST /api/v1/actions { action: 'call', source, page: window.location.pathname }
trackFormSubmit(source?: string): void // fires POST /api/v1/actions { action: 'form', source, page: window.location.pathname }
```

Each method **automatically** appends `page: window.location.pathname` to the payload. Components never need to pass `page` explicitly. No direct HTTP usage in components.

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
| `ip_hash` | varchar(64) nullable | `hash_hmac('sha256', $request->ip(), config('app.key'))` — keyed hash, non-reversible even with full IPv4 enumeration |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

**Model:** `Taba\Crm\Models\ActionClick` — standard Eloquent, no auth required.

> **Note:** `country` and `city` columns are intentionally omitted from v1. GeoIP lookup requires a third-party package decision (e.g., `torann/geoip`, MaxMind) that is deferred to a future iteration. These columns can be added via a separate migration when a GeoIP strategy is chosen.

### 3.2 New API endpoint

`POST /api/v1/actions` — public, throttle: 60/min

Request body (JSON):
```json
{ "action": "whatsapp|call|form", "source": "organic|ads|direct|null", "page": "/path" }
```

Validation rules (enforced in `ActionClickApiController@store`):
- `action` — required, `in:whatsapp,call,form`
- `source` — nullable, string, max:50
- `page` — nullable, string, max:255

Response: `204 No Content`

`GET /api/v1/actions/summary` — authenticated (Sanctum). Consumed **only** by Filament widgets via server-side Eloquent queries (not by any Angular component). This endpoint is reserved for future use or external tooling. For v1 the widgets query `ActionClick` directly in PHP — the endpoint is registered but the widget implementations do not call it.

Response shape (for future reference):
```json
{
  "by_action": { "whatsapp": 0, "call": 0, "form": 0 },
  "by_source": { "organic": 0, "ads": 0, "direct": 0 },
  "daily": [ { "date": "2026-04-21", "count": 0 } ]
}
```

Controller: `Taba\Crm\Http\Controllers\Api\ActionClickApiController`

### 3.3 Routes update

`routes/api.php` gains **(inside the existing `Route::prefix('api/v1')` group)**:
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

### 3.4 Frontend publish — inside `crm:install`

The Angular frontend publish step is **integrated into the existing `InstallCommand`** (`src/Commands/InstallCommand.php`) rather than being a separate command.

A new protected method `publishAngularFrontend()` is added to `InstallCommand` and called as a task:

```php
$this->task('Publishing Angular frontend', fn() => $this->publishAngularFrontend());
```

Placement in `handle()`: after the existing `'Publishing package assets'` task and before the `skip-frontend` block.

**`publishAngularFrontend()` behaviour:**
- **Source path:** `dirname(__DIR__, 2) . '/frontend'` (2 levels up from `src/Commands/` = package root). Must NOT use `base_path('packages/...')` because the package may be installed in `vendor/` via Composer.
- **Destination:** `base_path('frontend')`
- If destination already exists, skip the copy silently (do not overwrite — developer may have customized it). Log a warning via `$this->warnings[]`.
- If `--skip-frontend` flag is set, skip this task entirely.

After `publishAngularFrontend()` runs, two additional tasks follow **inside the same `if (! $this->option('skip-frontend'))` block**:

```php
$this->task('Publishing Angular frontend', fn() => $this->publishAngularFrontend());
$this->task('Installing Angular npm packages', fn() => $this->runAngularNpmInstall());
$this->task('Building Angular frontend', fn() => $this->runAngularBuild());
```

**`runAngularNpmInstall()` behaviour:**
- Check npm is available (`npm --version`); warn and return false if not.
- Run `npm install` with working directory set to `base_path('frontend')` via `Process::path(base_path('frontend'))->run('npm install')`.
- On failure, add to `$this->errors[]`.

**`runAngularBuild()` behaviour:**
- Check npm is available; warn and return false if not.
- Run `npm run build` with working directory set to `base_path('frontend')` via `Process::path(base_path('frontend'))->run('npm run build')`.
- On failure, add to `$this->errors[]`.

**No manual steps** are left for the developer regarding the Angular frontend. `php artisan crm:install` is the single command that publishes, installs, and builds.

### 3.5 Dynamic SEO — bot-detection middleware

#### Strategy

Real browsers receive `index.html` as-is; Angular handles `<title>` and `<meta>` via `@angular/platform-browser` `Title` and `Meta` services after API data loads.

Crawlers and social bots (Googlebot, Bingbot, facebookexternalhit, Twitterbot, WhatsApp, Telegram, Slack, LinkedInBot, etc.) need pre-rendered tags injected **before** the HTML is sent. A new middleware `Taba\Crm\Http\Middleware\InjectSeoForBots` intercepts these requests, resolves the requested page's SEO data from the database, and injects the relevant tags into `index.html`.

#### Relation to `AddSeoDefaults`

`AddSeoDefaults` (already exists) sets structural defaults for Blade-rendered pages: charset, viewport, CSRF, favicon links, Twitter card type. It uses the `romanzinho/seo` package and is **not changed**.

`InjectSeoForBots` is a standalone middleware that operates on the raw `index.html` string response — it does **not** use `romanzinho/seo`. Both middlewares are independent.

#### New middleware: `InjectSeoForBots`

**Location:** `src/Http/Middleware/InjectSeoForBots.php`  
**Namespace:** `Taba\Crm\Http\Middleware`

**Bot-detection logic:**

```php
private const BOT_AGENTS = [
    'googlebot', 'bingbot', 'slurp', 'duckduckbot',
    'facebookexternalhit', 'twitterbot', 'linkedinbot',
    'whatsapp', 'telegram', 'slackbot', 'discordbot',
    'applebot', 'pinterest',
];

private function isBot(Request $request): bool
{
    $ua = strtolower($request->userAgent() ?? '');
    foreach (self::BOT_AGENTS as $bot) {
        if (str_contains($ua, $bot)) return true;
    }
    return false;
}
```

**URL-to-model resolution:**

Parse `$request->path()` (already stripped of leading slash by Laravel) to determine page type:

| URL pattern | Page type | SEO source |
|---|---|---|
| `` (empty / `/`) | Home | `CrmSetting` keys: `site_name`, `site_description`, `og_image` |
| `{slug}` (one segment) | Category page | `PostCategory::where('slug', $slug)->first()` — uses `name` as title, `description` as meta description (PostCategory has no dedicated meta columns) |
| `{catSlug}/{postSlug}` (two segments) | Post page | `Post::where('slug', $postSlug)->first()` — uses `meta_title` (fallback: `title`), `meta_description` (fallback: first 160 chars of `content`), first URL from `images` array |
| 3+ segments | Unknown | Fall through, serve vanilla `index.html` |

**Image data source:**

`Post::getImagesAttribute()` returns a **Curator `Media` collection** — not plain URLs. Each `Media` object has:
- `->url` — absolute file URL
- `->alt` — alt text entered in the Filament media manager
- `->caption` — optional caption

In the middleware, resolve the first image as:
```php
$firstImage   = $post->images->first();
$imageUrl     = $firstImage?->url ?? '';
$imageAlt     = $firstImage?->alt ?? ($post->meta_title ?? $post->title);
$imageCaption = $firstImage?->caption ?? '';
```

For home page: `$imageUrl` = `CrmSetting` `og_image` value; `$imageAlt` = site name.  
For category pages: no image injection — omit all image-related tags.

**`<html lang>` injection:**

Before injecting meta tags, patch the `<html>` opening tag to include the active locale:
```php
$html = preg_replace('/<html([^>]*)>/i', '<html$1 lang="' . app()->getLocale() . '">', $html, 1);
```
This ensures Google indexes the page under the correct language without relying on Angular loading first.

**Tag injection:**

Read `public_path('index.html')` as a string. Inject the following block immediately before `</head>`:

```html
<link rel="preload" as="image" fetchpriority="high" href="{image_url}">
<title>{title}</title>
<meta name="description" content="{description}">
<meta name="robots" content="index, follow">
<meta property="og:title" content="{title}">
<meta property="og:description" content="{description}">
<meta property="og:image" content="{image_url}">
<meta property="og:image:alt" content="{image_alt}">
<meta property="og:url" content="{canonical_url}">
<meta property="og:type" content="website|article">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{title}">
<meta name="twitter:description" content="{description}">
<meta name="twitter:image" content="{image_url}">
<meta name="twitter:image:alt" content="{image_alt}">
<link rel="canonical" href="{canonical_url}">
<link rel="alternate" hreflang="ar" href="{canonical_url}">
<link rel="alternate" hreflang="en" href="{canonical_url}">
<link rel="alternate" hreflang="x-default" href="{canonical_url}">
```

Rules:
- `canonical_url` = `url($request->path())`
- `og:type` = `article` for Post pages, `website` otherwise
- `<link rel="preload">` is only injected when `$imageUrl` is non-empty (Post and Home pages)
- `hreflang` uses the **same canonical URL for both `ar` and `en`** — the URL structure has no language prefix; locale is set via session/header. This tells Google the same URL serves both languages. `x-default` also points to the same URL.
- Image tags (`og:image`, `og:image:alt`, `twitter:image`, `twitter:image:alt`, preload) are **omitted entirely** when `$imageUrl` is empty

If `index.html` does not exist on disk, fall through to normal response pipeline without error.

**JSON-LD structured data (rich results):**

Immediately after the meta/OG tags block, inject a `<script type="application/ld+json">` block built from the same resolved data. Schema varies by page type:

*Home page — `WebSite` + `Organization`:*
```json
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "WebSite",
      "name": "{site_name}",
      "url": "{base_url}",
      "description": "{site_description}",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "{base_url}/search?q={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    },
    {
      "@type": "Organization",
      "name": "{site_name}",
      "url": "{base_url}",
      "logo": "{og_image_url}"
    }
  ]
}
```

*Category page — `CollectionPage` + `BreadcrumbList`:*
```json
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "CollectionPage",
      "name": "{category_name}",
      "description": "{category_description}",
      "url": "{canonical_url}"
    },
    {
      "@type": "BreadcrumbList",
      "itemListElement": [
        { "@type": "ListItem", "position": 1, "name": "{site_name}", "item": "{base_url}" },
        { "@type": "ListItem", "position": 2, "name": "{category_name}", "item": "{canonical_url}" }
      ]
    }
  ]
}
```

*Post page — `Article` + `BreadcrumbList`:*
```json
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Article",
      "headline": "{meta_title|title}",
      "description": "{meta_description}",
      "image": {
        "@type": "ImageObject",
        "url": "{image_url}",
        "description": "{image_alt}",
        "caption": "{image_caption}"
      },
      "url": "{canonical_url}",
      "datePublished": "{created_at ISO8601}",
      "dateModified": "{updated_at ISO8601}",
      "publisher": {
        "@type": "Organization",
        "name": "{site_name}",
        "logo": { "@type": "ImageObject", "url": "{og_image_url}" }
      }
    },
    {
      "@type": "BreadcrumbList",
      "itemListElement": [
        { "@type": "ListItem", "position": 1, "name": "{site_name}", "item": "{base_url}" },
        { "@type": "ListItem", "position": 2, "name": "{category_name}", "item": "{cat_url}" },
        { "@type": "ListItem", "position": 3, "name": "{post_title}", "item": "{canonical_url}" }
      ]
    }
  ]
}
```

> `{site_name}` and `{og_image_url}` for post/category pages are sourced from `CrmSetting::getAllGrouped()` — a single extra query per bot request, cached with the same `Cache::remember` key used in `HomeApiController`. `{cat_url}` for the post breadcrumb is built from the `catSlug` segment already parsed from the URL, no extra DB query needed.

The JSON-LD block is injected using `str_replace('</head>', $jsonLd . '</head>', $html)` on the same `index.html` string (same pass, after the meta tags injection).

**Registration:**

In `CrmServiceProvider::boot()`, alias the middleware:
```php
$router->aliasMiddleware('crm.seo', InjectSeoForBots::class);
```

In `routes/web.php`, apply it to the catch-all route:
```php
Route::middleware('crm.seo')
    ->get('/{any}', function () {
        return response()->file(public_path('index.html'));
    })->where('any', '^(?!api|admin|filament).*');
```

Also apply to the root route:
```php
Route::middleware('crm.seo')
    ->get('/', fn() => response()->file(public_path('index.html')));
```

#### Sitemap update

The existing `/sitemap` route in `routes/web.php` uses `spatie/sitemap` and writes XML to `public/sitemap.xml`. It currently references Blade-era named routes (`route('home')`, `route('dynamic.route', ...)`, `route('posts.show', ...)`). These names will either not exist or point to the wrong URLs after the Angular migration.

**Required change in `routes/web.php`:** Replace all `route(...)` calls inside the sitemap closure with direct URL construction:

| Before | After |
|---|---|
| `route('home')` | `url('/')` |
| `route('dynamic.route', ['slug' => $category->slug])` | `url($category->slug)` |
| `route('posts.show', ['category' => ..., 'post' => ...])` | `url($post->postCategory->slug . '/' . $post->slug)` |

Also remove the `Post::whereNotNull('homepage_section_component')` filter — all published posts should be in the sitemap, not just those with a homepage section.

#### `robots.txt`

`public/robots.txt` must include a `Sitemap:` directive pointing to the generated file. Update or verify it contains:

```
User-agent: *
Allow: /

Sitemap: APP_URL/sitemap.xml
```

Replace `APP_URL` with the actual domain or — better — add a one-time artisan command step in `crm:install` that appends the `Sitemap:` line with `url('/sitemap.xml')` if not already present. This avoids hardcoding the domain in a versioned file.

#### Angular side (real browsers)

Each page/route component calls Angular's `Title` and `Meta` services after the API response arrives:

```ts
// Example in PostComponent.ngOnInit()
this.title.setTitle(post.meta_title || post.title);
this.meta.updateTag({ name: 'description', content: post.meta_description || '' });
this.meta.updateTag({ property: 'og:title', content: post.meta_title || post.title });
this.meta.updateTag({ property: 'og:description', content: post.meta_description || '' });
this.meta.updateTag({ property: 'og:image', content: post.images?.[0]?.url || '' });
this.meta.updateTag({ property: 'og:image:alt', content: post.images?.[0]?.alt || post.title });
this.meta.updateTag({ name: 'twitter:title', content: post.meta_title || post.title });
this.meta.updateTag({ name: 'twitter:image:alt', content: post.images?.[0]?.alt || post.title });
```

Components that must implement this:
- `HomeComponent` — title from `settings.site_name`, og:image from settings `og_image`
- `CategoryComponent` — title from category `name`, no image  
- `PostComponent` — full set as above

This is **not optional** — without it, real users (non-bot) navigating client-side never get updated `<title>` in browser tab or history.

---

## 4. Filament Client Widgets — Fixed & Self-Contained

All widgets moved to correct namespace: `Taba\Crm\Filament\Client\Widgets`. All `App\Models\*` references replaced with package models or `ActionClick`.

| Widget | Display title | Data source | Metric / chart type |
|---|---|---|---|
| `WelcomeWidget` | *(unchanged)* | `PostCategory`, `Post`, `ContactEntry`, `CrmSetting` | Namespace fix only — no data change |
| `AccountWidget` | *(unchanged)* | `Filament\Widgets\AccountWidget` (extends) | Namespace fix only |
| `StatsOverview` | "نظرة عامة" (Overview) | `Post`, `PostCategory`, `ContactEntry` | Stat cards: published posts count, active categories count, total contact submissions |
| `ActionClicksOverview` | "نقرات التواصل" (Contact Clicks) | `ActionClick` | Stat cards: total clicks, WhatsApp clicks, Call clicks; filterable by period (7d / 30d / all) |
| `WeeklyClicksChart` | "النقرات حسب اليوم" (Clicks by Day) | `ActionClick` | Bar chart; X=date, Y=click count; filterable by `action` type and period |
| `AdvertisementsOverview` | "المنشورات حسب القسم" (Posts by Section) | `Post` grouped by `post_category_id` | Stat cards: one card per active category showing published post count |
| OffersOverview | "الرسائل الواردة" (Incoming Messages) | `ContactEntry` | Stat cards: total submissions, unread count (`is_read = false` — column confirmed in migration `2026_04_01_000001`), this-week count |
| SurveyAnswersChart | "الرسائل حسب الصفحة" (Messages by Page) | `ContactEntry` grouped by `page` column | Donut chart: breakdown of where contact submissions originated. **Requires** a new nullable `page` varchar(255) column on `contact_entries` — add as a migration in delivery step 1. The Angular contact form must send `page: window.location.pathname` in its submission payload, and `ContactEntryApiController@store` must save it. |
| `WeeklyReviewsChart` | "الرسائل الأسبوعية" (Weekly Messages) | `ContactEntry` grouped by date | Line chart; X=date (last 7 days), Y=submission count |

**`discoverWidgets()` verification:** Before implementing, confirm that `CrmClientPlugin::register()` calls `discoverWidgets()` with `in:` pointing to the absolute path of `src/Filament/Client/Widgets/` inside the package. If it resolves via `__DIR__` this is already correct; if it uses any other path, update the argument.

---

## 5. What Is NOT Changing

- Admin panel (`CrmPlugin`, `Filament/Admin/`) — untouched
- All existing API controllers and endpoints — untouched
- `CrmServiceProvider` boot logic (gates, translations, migrations) — untouched except adding the new middleware alias and new command tasks
- Angular routing, `api.service.ts`, `app.config.ts` — structure preserved, `action.service.ts` added alongside
- `AddSeoDefaults` middleware — untouched (structural defaults for Blade pages; independent of `InjectSeoForBots`)

---

## 6. Delivery Sequence

1. Add `ActionClick` migration + model + `ActionClickApiController` + register routes. Also add nullable `page` varchar(255) column to `contact_entries` migration + update `ContactEntry` fillable. Update `ContactEntryApiController@store` to save the `page` field from the request.
2. Fix all 9 widget namespaces and rewrite data sources
3. Add `action.service.ts` to Angular frontend source
4. Add `tokens.scss` with CSS custom properties; update components to use them
5. Add `publishAngularFrontend()`, `runAngularNpmInstall()`, and `runAngularBuild()` methods to the existing `InstallCommand`; wire them into the `handle()` method. Add robots.txt `Sitemap:` line injection to `InstallCommand`.
6. Update `routes/web.php`: catch-all + deprecation comments on Blade routes; apply `crm.seo` middleware to root and catch-all routes; fix sitemap route URL construction (remove `route(...)` references, use `url(...)` directly).
7. Implement `InjectSeoForBots` middleware (bot detection, `<html lang>` patch, meta/OG/image/hreflang tags, preload hint, JSON-LD rich results); register `crm.seo` alias in `CrmServiceProvider`.
8. Add full `Title`/`Meta` service calls (including `og:image`, `og:image:alt`, `twitter:image:alt`) to `HomeComponent`, `CategoryComponent`, `PostComponent`
9. Update package `README.md` — new "Frontend Setup" section

---

## 7. Success Criteria

- `php artisan crm:install` completes without errors: Angular frontend copied, npm packages installed, `ng build` succeeds, site loads from Angular with no errors
- WhatsApp/Call button clicks tracked in `action_clicks` table
- All Client panel widgets render with real data, no `App\Models\*` references
- No Blade views required for public-facing pages
- Changing `--color-primary` in `tokens.scss` and rebuilding produces a visually distinct theme with no other file changes required
- A `curl -A "Googlebot/2.1"` request to `/` returns HTML with: `lang="ar"` (or active locale) on `<html>`, `<meta property="og:title">`, JSON-LD `"@type": "WebSite"` + `"@type": "Organization"`, `hreflang` alternate links
- A `curl -A "facebookexternalhit"` request to a post URL returns `<meta property="og:image">`, `<meta property="og:image:alt">`, `<meta name="twitter:image:alt">` with real values from the Curator Media object
- A `curl -A "Googlebot/2.1"` request to a post URL returns JSON-LD `"@type": "Article"` with `headline`, `datePublished`, an `ImageObject` containing `url`+`description`+`caption`, and a 3-level `BreadcrumbList`
- `curl /sitemap.xml` returns valid XML containing `/`, category slugs, and post slug paths — no `route(...)` errors
- `public/robots.txt` contains a `Sitemap:` line pointing to `/sitemap.xml`
- A real browser request to any path receives the standard unmodified `index.html` (no injected tags — Angular sets them after load)
- A real browser request to any path receives the standard unmodified `index.html` (no injected tags — Angular sets them after load)
