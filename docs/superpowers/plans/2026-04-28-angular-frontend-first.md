# Angular-First Frontend & Agent-Ready Overhaul — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Make the `taba/crm` package fully Angular-SPA-first with no public Blade pages, correct Filament Client widgets, action click tracking, full SEO bot-injection, and agent-ready discoverability standards — all automated through `crm:install`.

**Architecture:** A Laravel middleware (`InjectSeoForBots`) detects crawlers and injects meta/OG/JSON-LD/hreflang tags into the static `public/index.html` before sending. A second middleware (`AddDiscoveryHeaders`) adds `Link:` HTTP headers for machine discovery. Angular components manage SEO via `Title`/`Meta` services for real browsers. `InstallCommand` is extended to publish the Angular frontend, run its build, and generate `llms.txt` + `robots.txt`.

**Tech Stack:** Laravel 11, Angular 21, Filament 3, Laravel Sanctum, Orchestra Testbench (SQLite :memory:), PHP 8.2+

**Spec:** `docs/superpowers/specs/2026-04-21-angular-frontend-first-design.md`

---

## File Map

### New files (create)

| File | Responsibility |
|---|---|
| `database/migrations/2026_04_28_000001_create_action_clicks_table.php` | `action_clicks` table |
| `database/migrations/2026_04_28_000002_add_page_to_contact_entries_table.php` | `page` column on `contact_entries` |
| `src/Models/ActionClick.php` | `ActionClick` Eloquent model |
| `src/Http/Controllers/Api/ActionClickApiController.php` | `store` + `summary` endpoints |
| `src/Http/Middleware/InjectSeoForBots.php` | Bot detection + meta/OG/JSON-LD injection |
| `src/Http/Middleware/AddDiscoveryHeaders.php` | `Link:` + `X-Api-Catalog:` response headers |
| `src/Filament/Client/Widgets/WelcomeWidget.php` | Filament client widget (namespace fix) |
| `src/Filament/Client/Widgets/AccountWidget.php` | Filament client widget (namespace fix) |
| `src/Filament/Client/Widgets/StatsOverview.php` | Posts/categories/contacts stat cards |
| `src/Filament/Client/Widgets/ActionClicksOverview.php` | WhatsApp/Call/Form click stats |
| `src/Filament/Client/Widgets/WeeklyClicksChart.php` | Daily click bar chart |
| `src/Filament/Client/Widgets/AdvertisementsOverview.php` | Posts-per-category stat cards |
| `src/Filament/Client/Widgets/OffersOverview.php` | Contact entries stats |
| `src/Filament/Client/Widgets/SurveyAnswersChart.php` | Contacts-by-page donut chart |
| `src/Filament/Client/Widgets/WeeklyReviewsChart.php` | Weekly contacts line chart |
| `frontend/src/app/services/action.service.ts` | WhatsApp/Call/Form tracking service |
| `frontend/src/styles/tokens.scss` | CSS custom property theme tokens |
| `tests/Feature/ActionClickApiTest.php` | API tests for action click endpoints |
| `tests/Feature/InjectSeoForBotsTest.php` | Middleware tests |
| `tests/Feature/AddDiscoveryHeadersTest.php` | Discovery headers tests |
| `tests/Feature/ContactEntryPageTest.php` | page column + API save test |
| `tests/Feature/MarkdownNegotiationTest.php` | Accept: text/markdown test |

### Modified files

| File | What changes |
|---|---|
| `src/Models/ContactEntry.php` | Add `page` to `$fillable` |
| `src/Http/Requests/Api/StoreContactEntryRequest.php` | Add `page` validation rule |
| `src/Http/Controllers/Api/ContactEntryApiController.php` | Save `page` from request in `store()` |
| `src/Http/Controllers/Api/PostApiController.php` | Add `Accept: text/markdown` negotiation in `show()` |
| `src/CrmServiceProvider.php` | Register `crm.seo` + `crm.discovery` middleware aliases |
| `src/Commands/InstallCommand.php` | Add `publishAngularFrontend()`, `runAngularNpmInstall()`, `runAngularBuild()`, `generateLlmsTxt()`, `writeRobotsTxt()` |
| `routes/api.php` | Add `POST actions` + `GET actions/summary` inside `api/v1` group |
| `routes/web.php` | Replace root Blade route; add catch-all with `crm.seo` + `crm.discovery`; fix sitemap `url()` calls |
| `frontend/angular.json` | Set `outputPath` to object form `{"base": "../public", "browser": ""}` |

---

## Task 1: ActionClick migration + model

**Files:**
- Create: `database/migrations/2026_04_28_000001_create_action_clicks_table.php`
- Create: `src/Models/ActionClick.php`

- [ ] **Step 1: Write the migration**

```php
<?php
// database/migrations/2026_04_28_000001_create_action_clicks_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('action_clicks', function (Blueprint $table) {
            $table->id();
            $table->enum('action', ['whatsapp', 'call', 'form']);
            $table->string('source', 50)->nullable();
            $table->string('page', 255)->nullable();
            $table->string('ip_hash', 64)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('action_clicks');
    }
};
```

- [ ] **Step 2: Write the model**

```php
<?php
// src/Models/ActionClick.php

namespace Taba\Crm\Models;

use Illuminate\Database\Eloquent\Model;

class ActionClick extends Model
{
    protected $fillable = [
        'action',
        'source',
        'page',
        'ip_hash',
    ];
}
```

- [ ] **Step 3: Commit**

```bash
git add database/migrations/2026_04_28_000001_create_action_clicks_table.php src/Models/ActionClick.php
git commit -m "feat: add ActionClick migration and model"
```

---

## Task 2: ContactEntry `page` column

**Files:**
- Create: `database/migrations/2026_04_28_000002_add_page_to_contact_entries_table.php`
- Modify: `src/Models/ContactEntry.php`
- Modify: `src/Http/Requests/Api/StoreContactEntryRequest.php`
- Modify: `src/Http/Controllers/Api/ContactEntryApiController.php`
- Create: `tests/Feature/ContactEntryPageTest.php`

- [ ] **Step 1: Write the failing test**

```php
<?php
// tests/Feature/ContactEntryPageTest.php

namespace Taba\Crm\Tests\Feature;

use Taba\Crm\Tests\TestCase;

class ContactEntryPageTest extends TestCase
{
    /** @test */
    public function contact_entry_store_saves_page_field(): void
    {
        $response = $this->postJson('/api/v1/contact', [
            'name'    => 'Test User',
            'message' => 'Hello world',
            'page'    => '/services',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('contact_entries', ['page' => '/services']);
    }

    /** @test */
    public function page_field_is_optional(): void
    {
        $response = $this->postJson('/api/v1/contact', [
            'name'    => 'Test User',
            'message' => 'Hello world',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('contact_entries', ['page' => null]);
    }
}
```

- [ ] **Step 2: Run test — expect FAIL** (`Column not found: page`)

```bash
cd packages/taba/crm && php ../../vendor/bin/phpunit tests/Feature/ContactEntryPageTest.php --testdox
```

- [ ] **Step 3: Write the migration**

```php
<?php
// database/migrations/2026_04_28_000002_add_page_to_contact_entries_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('contact_entries', function (Blueprint $table) {
            $table->string('page', 255)->nullable()->after('service');
        });
    }

    public function down(): void
    {
        Schema::table('contact_entries', function (Blueprint $table) {
            $table->dropColumn('page');
        });
    }
};
```

- [ ] **Step 4: Update `ContactEntry` fillable**

In `src/Models/ContactEntry.php`, add `'page'` to `$fillable`:

```php
protected $fillable = [
    'name',
    'email',
    'phone',
    'message',
    'service',
    'page',    // ← add this
    'is_read',
];
```

- [ ] **Step 5: Add `page` validation rule to `StoreContactEntryRequest`**

In `src/Http/Requests/Api/StoreContactEntryRequest.php`, add to `rules()`:

```php
'page' => ['nullable', 'string', 'max:255'],
```

- [ ] **Step 6: Verify `ContactEntryApiController::store()` uses `$request->validated()`**

Open `src/Http/Controllers/Api/ContactEntryApiController.php` and inspect the `store()` method.

- If it calls `ContactEntry::create($request->validated())` — no change needed; `page` is now in `validated()` automatically.
- If it calls `ContactEntry::create($request->only([...]))` or `ContactEntry::create([...])` with an explicit list — add `'page' => $request->validated('page')` to the array, or switch to `$request->validated()`.

> **Verify:** After saving, check `ContactEntry::first()->page` in Tinker equals the submitted value.

- [ ] **Step 7: Run test — expect PASS**

```bash
php ../../vendor/bin/phpunit tests/Feature/ContactEntryPageTest.php --testdox
```

- [ ] **Step 8: Commit**

```bash
git add database/migrations/2026_04_28_000002_add_page_to_contact_entries_table.php \
        src/Models/ContactEntry.php \
        src/Http/Requests/Api/StoreContactEntryRequest.php \
        src/Http/Controllers/Api/ContactEntryApiController.php \
        tests/Feature/ContactEntryPageTest.php
git commit -m "feat: add page column to contact_entries"
```

---

## Task 3: ActionClick API controller + routes

**Files:**
- Create: `src/Http/Controllers/Api/ActionClickApiController.php`
- Modify: `routes/api.php`
- Create: `tests/Feature/ActionClickApiTest.php`

- [ ] **Step 1: Write the failing tests**

```php
<?php
// tests/Feature/ActionClickApiTest.php

namespace Taba\Crm\Tests\Feature;

use Taba\Crm\Tests\TestCase;
use Taba\Crm\Models\ActionClick;

class ActionClickApiTest extends TestCase
{
    /** @test */
    public function it_stores_a_whatsapp_click(): void
    {
        $response = $this->postJson('/api/v1/actions', [
            'action' => 'whatsapp',
            'source' => 'organic',
            'page'   => '/home',
        ]);

        $response->assertStatus(204);
        $this->assertDatabaseHas('action_clicks', [
            'action' => 'whatsapp',
            'source' => 'organic',
            'page'   => '/home',
        ]);
    }

    /** @test */
    public function it_rejects_invalid_action_type(): void
    {
        $this->postJson('/api/v1/actions', ['action' => 'invalid'])
             ->assertStatus(422);
    }

    /** @test */
    public function it_hashes_the_ip_address(): void
    {
        $this->postJson('/api/v1/actions', ['action' => 'call']);

        $click = ActionClick::first();
        $this->assertNotNull($click->ip_hash);
        $this->assertEquals(64, strlen($click->ip_hash));
    }

    /** @test */
    public function summary_requires_authentication(): void
    {
        $this->getJson('/api/v1/actions/summary')->assertStatus(401);
    }
}
```

- [ ] **Step 2: Run tests — expect FAIL** (route not found)

```bash
php ../../vendor/bin/phpunit tests/Feature/ActionClickApiTest.php --testdox
```

- [ ] **Step 3: Write the controller**

```php
<?php
// src/Http/Controllers/Api/ActionClickApiController.php

namespace Taba\Crm\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Taba\Crm\Models\ActionClick;

class ActionClickApiController extends ApiController
{
    public function store(Request $request): Response
    {
        $data = $request->validate([
            'action' => ['required', 'in:whatsapp,call,form'],
            'source' => ['nullable', 'string', 'max:50'],
            'page'   => ['nullable', 'string', 'max:255'],
        ]);

        ActionClick::create(array_merge($data, [
            'ip_hash' => hash_hmac('sha256', $request->ip(), config('app.key')),
        ]));

        return response()->noContent();
    }

    public function summary(Request $request): JsonResponse
    {
        $byAction = ActionClick::selectRaw('action, count(*) as total')
            ->groupBy('action')
            ->pluck('total', 'action');

        $bySource = ActionClick::selectRaw('source, count(*) as total')
            ->whereNotNull('source')
            ->groupBy('source')
            ->pluck('total', 'source');

        $daily = ActionClick::selectRaw('DATE(created_at) as date, count(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return $this->success([
            'by_action' => $byAction,
            'by_source' => $bySource,
            'daily'     => $daily,
        ]);
    }
}
```

- [ ] **Step 4: Register routes** — add inside the `Route::prefix('api/v1')` group in `routes/api.php`, after the existing throttle group:

```php
use Taba\Crm\Http\Controllers\Api\ActionClickApiController;

// Action click tracking
Route::middleware('throttle:60,1')->post('actions', [ActionClickApiController::class, 'store']);
Route::middleware('auth:sanctum')->get('actions/summary', [ActionClickApiController::class, 'summary']);
```

- [ ] **Step 5: Run tests — expect PASS**

```bash
php ../../vendor/bin/phpunit tests/Feature/ActionClickApiTest.php --testdox
```

- [ ] **Step 6: Commit**

```bash
git add src/Http/Controllers/Api/ActionClickApiController.php routes/api.php tests/Feature/ActionClickApiTest.php
git commit -m "feat: add ActionClick API controller and routes"
```

---

## Task 4: Filament Client widgets — namespace + data fix

**Files:**
- Modify: all 9 files under `src/Filament/Client/Widgets/`

> Look at `src/Filament/Client/Widgets/.gitkeep` — the directory exists but is empty (or only has `.gitkeep`). Create/replace each widget file as specified below. Verify `CrmClientPlugin::register()` calls `discoverWidgets()` pointing to the absolute path of this directory.

- [ ] **Step 1: Verify `discoverWidgets()` path is correct**

Open `src/CrmClientPlugin.php` (or the plugin file). Find `discoverWidgets()`. Confirm the `in:` argument resolves to `src/Filament/Client/Widgets/` (using `__DIR__`). If it uses any other path, fix it now.

- [ ] **Step 2: Create `WelcomeWidget.php`** (namespace fix only, keep existing display logic)

```php
<?php
// src/Filament/Client/Widgets/WelcomeWidget.php

namespace Taba\Crm\Filament\Client\Widgets;

use Filament\Widgets\Widget;

class WelcomeWidget extends Widget
{
    protected static string $view = 'crm::filament.client.widgets.welcome';
    protected static ?int $sort = 1;
}
```

- [ ] **Step 3: Create `AccountWidget.php`** (extends Filament's built-in)

```php
<?php
// src/Filament/Client/Widgets/AccountWidget.php

namespace Taba\Crm\Filament\Client\Widgets;

use Filament\Widgets\AccountWidget as BaseAccountWidget;

class AccountWidget extends BaseAccountWidget
{
    protected static ?int $sort = 2;
}
```

- [ ] **Step 4: Create `StatsOverview.php`**

```php
<?php
// src/Filament/Client/Widgets/StatsOverview.php

namespace Taba\Crm\Filament\Client\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Taba\Crm\Models\ContactEntry;
use Taba\Crm\Models\Post;
use Taba\Crm\Models\PostCategory;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        return [
            Stat::make('المنشورات المنشورة', Post::published()->count()),
            Stat::make('الأقسام النشطة', PostCategory::count()),
            Stat::make('الرسائل الكلية', ContactEntry::count()),
        ];
    }
}
```

- [ ] **Step 5: Create `ActionClicksOverview.php`**

```php
<?php
// src/Filament/Client/Widgets/ActionClicksOverview.php

namespace Taba\Crm\Filament\Client\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Taba\Crm\Models\ActionClick;

class ActionClicksOverview extends BaseWidget
{
    protected static ?int $sort = 4;

    protected function getStats(): array
    {
        return [
            Stat::make('نقرات التواصل الكلية', ActionClick::count())
                ->description('آخر 30 يوماً: ' . ActionClick::where('created_at', '>=', now()->subDays(30))->count()),
            Stat::make('واتساب', ActionClick::where('action', 'whatsapp')->count()),
            Stat::make('اتصال', ActionClick::where('action', 'call')->count()),
            Stat::make('نموذج', ActionClick::where('action', 'form')->count()),
        ];
    }
}
```

- [ ] **Step 6: Create `WeeklyClicksChart.php`**

```php
<?php
// src/Filament/Client/Widgets/WeeklyClicksChart.php

namespace Taba\Crm\Filament\Client\Widgets;

use Filament\Widgets\ChartWidget;
use Taba\Crm\Models\ActionClick;
use Illuminate\Support\Carbon;

class WeeklyClicksChart extends ChartWidget
{
    protected static ?string $heading = 'النقرات حسب اليوم';
    protected static ?int $sort = 5;

    protected function getData(): array
    {
        $days = collect(range(6, 0))->map(fn($i) => now()->subDays($i)->format('Y-m-d'));

        $clicks = ActionClick::selectRaw('DATE(created_at) as date, count(*) as total')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->pluck('total', 'date');

        return [
            'datasets' => [[
                'label' => 'النقرات',
                'data'  => $days->map(fn($d) => $clicks[$d] ?? 0)->values()->toArray(),
            ]],
            'labels' => $days->values()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
```

- [ ] **Step 7: Create `AdvertisementsOverview.php`**

```php
<?php
// src/Filament/Client/Widgets/AdvertisementsOverview.php

namespace Taba\Crm\Filament\Client\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Taba\Crm\Models\Post;
use Taba\Crm\Models\PostCategory;

class AdvertisementsOverview extends BaseWidget
{
    protected static ?string $heading = 'المنشورات حسب القسم';
    protected static ?int $sort = 6;

    protected function getStats(): array
    {
        return PostCategory::withCount(['posts' => fn($q) => $q->published()])
            ->get()
            ->map(fn($cat) => Stat::make($cat->name, $cat->posts_count))
            ->toArray();
    }
}
```

- [ ] **Step 8: Create `OffersOverview.php`** (incoming messages stats)

```php
<?php
// src/Filament/Client/Widgets/OffersOverview.php

namespace Taba\Crm\Filament\Client\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Taba\Crm\Models\ContactEntry;

class OffersOverview extends BaseWidget
{
    protected static ?string $heading = 'الرسائل الواردة';
    protected static ?int $sort = 7;

    protected function getStats(): array
    {
        return [
            Stat::make('إجمالي الرسائل', ContactEntry::count()),
            Stat::make('غير مقروءة', ContactEntry::where('is_read', false)->count()),
            Stat::make('هذا الأسبوع', ContactEntry::where('created_at', '>=', now()->startOfWeek())->count()),
        ];
    }
}
```

- [ ] **Step 9: Create `SurveyAnswersChart.php`** (messages by page)

```php
<?php
// src/Filament/Client/Widgets/SurveyAnswersChart.php

namespace Taba\Crm\Filament\Client\Widgets;

use Filament\Widgets\ChartWidget;
use Taba\Crm\Models\ContactEntry;

class SurveyAnswersChart extends ChartWidget
{
    protected static ?string $heading = 'الرسائل حسب الصفحة';
    protected static ?int $sort = 8;

    protected function getData(): array
    {
        $rows = ContactEntry::selectRaw('page, count(*) as total')
            ->whereNotNull('page')
            ->groupBy('page')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        return [
            'datasets' => [[
                'data'            => $rows->pluck('total')->toArray(),
                'backgroundColor' => ['#6366f1','#22c55e','#f59e0b','#ef4444','#3b82f6','#ec4899','#14b8a6','#a855f7'],
            ]],
            'labels' => $rows->pluck('page')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
```

- [ ] **Step 10: Create `WeeklyReviewsChart.php`** (weekly messages line chart)

```php
<?php
// src/Filament/Client/Widgets/WeeklyReviewsChart.php

namespace Taba\Crm\Filament\Client\Widgets;

use Filament\Widgets\ChartWidget;
use Taba\Crm\Models\ContactEntry;

class WeeklyReviewsChart extends ChartWidget
{
    protected static ?string $heading = 'الرسائل الأسبوعية';
    protected static ?int $sort = 9;

    protected function getData(): array
    {
        $days = collect(range(6, 0))->map(fn($i) => now()->subDays($i)->format('Y-m-d'));

        $counts = ContactEntry::selectRaw('DATE(created_at) as date, count(*) as total')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->pluck('total', 'date');

        return [
            'datasets' => [[
                'label' => 'رسائل',
                'data'  => $days->map(fn($d) => $counts[$d] ?? 0)->values()->toArray(),
            ]],
            'labels' => $days->values()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
```

- [ ] **Step 11: Delete `.gitkeep` (now the directory has real files)**

```bash
rm src/Filament/Client/Widgets/.gitkeep
```

- [ ] **Step 12: Commit**

```bash
git add src/Filament/Client/Widgets/
git commit -m "feat: implement all 9 client panel widgets with correct namespace and package models"
```

---

## Task 5: Angular `action.service.ts` + `tokens.scss`

**Files:**
- Create: `frontend/src/app/services/action.service.ts`
- Create: `frontend/src/styles/tokens.scss`
- Modify: `frontend/angular.json` — outputPath object form

- [ ] **Step 1: Create `action.service.ts`**

```typescript
// frontend/src/app/services/action.service.ts

import { Injectable, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({ providedIn: 'root' })
export class ActionService {
  private http = inject(HttpClient);
  private url = '/api/v1/actions'; // relative — no environments/ folder in this package

  trackWhatsApp(source?: string): void {
    this.fire('whatsapp', source);
  }

  trackCall(source?: string): void {
    this.fire('call', source);
  }

  trackFormSubmit(source?: string): void {
    this.fire('form', source);
  }

  private fire(action: string, source?: string): void {
    this.http.post(this.url, {
      action,
      source: source ?? null,
      page: window.location.pathname,
    }).subscribe({ error: () => {} }); // fire-and-forget, silent fail
  }
}
```

- [ ] **Step 2: Create `tokens.scss`**

```scss
/* frontend/src/styles/tokens.scss
   Override any token here to rebrand the site for a new project. */

:root {
  --color-primary:     #1a56db;
  --color-primary-fg:  #ffffff;
  --color-secondary:   #6b7280;
  --color-accent:      #f59e0b;
  --color-bg:          #ffffff;
  --color-surface:     #f9fafb;
  --color-text:        #111827;
  --color-muted:       #6b7280;
  --color-border:      #e5e7eb;

  --font-family:       'Inter', 'Segoe UI', system-ui, sans-serif;
  --font-size-base:    1rem;

  --radius-sm:         0.25rem;
  --radius-md:         0.5rem;
  --radius-lg:         1rem;

  --spacing-xs:        0.25rem;
  --spacing-sm:        0.5rem;
  --spacing-md:        1rem;
  --spacing-lg:        2rem;
  --spacing-xl:        4rem;

  --shadow-sm:         0 1px 2px 0 rgb(0 0 0 / 0.05);
  --shadow-md:         0 4px 6px -1px rgb(0 0 0 / 0.1);
}
```

- [ ] **Step 3: Import `tokens.scss` in the main styles file**

In `frontend/src/styles.scss` (or `styles.css`), add at the top:
```scss
@import 'styles/tokens';
```

- [ ] **Step 4: Verify `angular.json` outputPath (already correct — likely no change needed)**

Open `frontend/angular.json` and confirm the `"outputPath"` key under `projects > [app-name] > architect > build > options` already reads:

```json
"outputPath": {
  "base": "../public",
  "browser": ""
}
```

> **Note:** The package ships `angular.json` with this object form already in place — **no edit is needed** unless it somehow differs. The empty `"browser": ""` key places built files (including `index.html`) directly in `public/` rather than `public/browser/`.

- [ ] **Step 5: Commit**

```bash
git add frontend/src/app/services/action.service.ts frontend/src/styles/tokens.scss \
        frontend/src/styles.scss frontend/angular.json
git commit -m "feat: add action.service.ts, tokens.scss, fix angular.json outputPath"
```

---

## Task 6: `InjectSeoForBots` middleware

**Files:**
- Create: `src/Http/Middleware/InjectSeoForBots.php`
- Create: `tests/Feature/InjectSeoForBotsTest.php`

- [ ] **Step 1: Write the failing tests**

```php
<?php
// tests/Feature/InjectSeoForBotsTest.php

namespace Taba\Crm\Tests\Feature;

use Taba\Crm\Tests\TestCase;

class InjectSeoForBotsTest extends TestCase
{
    private function putIndexHtml(string $content = '<html><head></head><body></body></html>'): void
    {
        file_put_contents(public_path('index.html'), $content);
    }

    protected function tearDown(): void
    {
        @unlink(public_path('index.html'));
        parent::tearDown();
    }

    /** @test */
    public function real_browser_gets_unmodified_html(): void
    {
        $this->putIndexHtml();
        $response = $this->get('/', ['User-Agent' => 'Mozilla/5.0 Chrome/124']);
        // Not a bot — file served as-is (no og:title injected)
        $this->assertStringNotContainsString('og:title', $response->getContent());
    }

    /** @test */
    public function googlebot_gets_og_title_injected(): void
    {
        $this->putIndexHtml();
        $response = $this->get('/', ['User-Agent' => 'Googlebot/2.1']);
        $this->assertStringContainsString('og:title', $response->getContent());
    }

    /** @test */
    public function html_lang_attribute_is_injected(): void
    {
        $this->putIndexHtml('<html><head></head><body></body></html>');
        $response = $this->get('/', ['User-Agent' => 'Googlebot/2.1']);
        $this->assertStringContainsString('lang="', $response->getContent());
    }

    /** @test */
    public function canonical_link_is_injected(): void
    {
        $this->putIndexHtml();
        $response = $this->get('/', ['User-Agent' => 'facebookexternalhit/1.1']);
        $this->assertStringContainsString('rel="canonical"', $response->getContent());
    }

    /** @test */
    public function json_ld_website_schema_injected_on_home(): void
    {
        $this->putIndexHtml();
        $response = $this->get('/', ['User-Agent' => 'Googlebot/2.1']);
        $this->assertStringContainsString('application/ld+json', $response->getContent());
        $this->assertStringContainsString('"@type": "WebSite"', $response->getContent());
    }

    /** @test */
    public function missing_index_html_falls_through_gracefully(): void
    {
        // No index.html — middleware must not crash; route returns 503 when file absent
        $response = $this->get('/', ['User-Agent' => 'Googlebot/2.1']);
        $response->assertStatus(503);
    }
}
```

- [ ] **Step 2: Run tests — expect FAIL**

```bash
php ../../vendor/bin/phpunit tests/Feature/InjectSeoForBotsTest.php --testdox
```

- [ ] **Step 3: Implement the middleware**

```php
<?php
// src/Http/Middleware/InjectSeoForBots.php

namespace Taba\Crm\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use Taba\Crm\Models\CrmSetting;
use Taba\Crm\Models\Post;
use Taba\Crm\Models\PostCategory;

class InjectSeoForBots
{
    private const BOT_AGENTS = [
        'googlebot', 'bingbot', 'slurp', 'duckduckbot',
        'facebookexternalhit', 'twitterbot', 'linkedinbot',
        'whatsapp', 'telegram', 'slackbot', 'discordbot',
        'applebot', 'pinterest',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $this->isBot($request)) {
            return $response;
        }

        $indexPath = public_path('index.html');
        if (! file_exists($indexPath)) {
            return $response;
        }

        $html = file_get_contents($indexPath);
        $html = $this->patchLang($html);
        [$meta, $jsonLd] = $this->buildSeoTags($request);
        $html = str_replace('</head>', $meta . $jsonLd . '</head>', $html);

        return response($html, 200)->header('Content-Type', 'text/html; charset=utf-8');
    }

    // -------------------------------------------------------------------------

    private function isBot(Request $request): bool
    {
        $ua = strtolower($request->userAgent() ?? '');
        foreach (self::BOT_AGENTS as $bot) {
            if (str_contains($ua, $bot)) return true;
        }
        return false;
    }

    private function patchLang(string $html): string
    {
        return preg_replace('/<html([^>]*)>/i', '<html$1 lang="' . app()->getLocale() . '">', $html, 1);
    }

    private function buildSeoTags(Request $request): array
    {
        $canonical = url($request->path());
        $base      = url('/');
        $segments  = array_values(array_filter(explode('/', $request->path())));
        $locale   = app()->getLocale();
        $settings = Cache::remember(
            'api_init_' . $locale,
            config('crm.api.cache_ttl', 300),
            fn () => CrmSetting::getAllGrouped()
        );

        // Locale-aware extractor: CrmSetting::getAllGrouped() returns raw JSON-cast values;
        // translatable settings are PHP arrays ['ar' => '...', 'en' => '...']
        $str = function ($value, string $default = '') use ($locale): string {
            if (is_array($value)) {
                return (string) ($value[$locale] ?? $value['en'] ?? $value['ar'] ?? $default);
            }
            return (string) ($value ?? $default);
        };

        $siteName = $str($settings['general']['site_name'] ?? null, config('app.name'));
        $ogImage  = $str($settings['general']['og_image'] ?? null, '');

        // --- Resolve page type ---
        if (count($segments) === 0) {
            // Home
            $title       = $siteName;
            $description = $str($settings['general']['site_description'] ?? null, '');
            $imageUrl    = $ogImage;
            $imageAlt    = $siteName;
            $imageCap    = '';
            $ogType      = 'website';
            $jsonLd      = $this->homeLd($siteName, $description, $base, $ogImage);
        } elseif (count($segments) === 1) {
            // Category
            $cat         = PostCategory::where('slug', $segments[0])->first();
            $title       = $cat?->name ?? $siteName;
            $description = $cat?->description ?? '';
            $imageUrl    = '';
            $imageAlt    = '';
            $imageCap    = '';
            $ogType      = 'website';
            $catUrl      = url($segments[0]);
            $jsonLd      = $this->categoryLd($title, $description, $catUrl, $siteName, $base);
        } elseif (count($segments) === 2) {
            // Post
            $post        = Post::where('slug', $segments[1])->published()->first();
            $title       = $post?->meta_title ?? $post?->title ?? $siteName;
            $description = $post?->meta_description ?? '';
            $firstImage  = $post?->images->first();
            $imageUrl    = $firstImage?->url ?? '';
            $imageAlt    = $firstImage?->alt ?? $title;
            $imageCap    = $firstImage?->caption ?? '';
            $ogType      = 'article';
            $catUrl      = url($segments[0]);
            $catName     = $post?->postCategory?->name ?? $segments[0];
            $jsonLd      = $this->articleLd(
                $title, $description, $canonical, $imageUrl, $imageAlt, $imageCap,
                $post?->created_at?->toIso8601String() ?? '',
                $post?->updated_at?->toIso8601String() ?? '',
                $siteName, $ogImage, $catName, $catUrl, $base
            );
        } else {
            // Unknown — no injection
            return ['', ''];
        }

        $meta = $this->buildMeta($title, $description, $canonical, $imageUrl, $imageAlt, $ogType);
        return [$meta, $jsonLd];
    }

    private function buildMeta(
        string $title, string $description, string $canonical,
        string $imageUrl, string $imageAlt, string $ogType
    ): string {
        $lines = [
            "<title>{$title}</title>",
            "<meta name=\"description\" content=\"" . e($description) . "\">",
            "<meta name=\"robots\" content=\"index, follow\">",
            "<meta property=\"og:title\" content=\"" . e($title) . "\">",
            "<meta property=\"og:description\" content=\"" . e($description) . "\">",
            "<meta property=\"og:url\" content=\"{$canonical}\">",
            "<meta property=\"og:type\" content=\"{$ogType}\">",
            "<meta name=\"twitter:card\" content=\"summary_large_image\">",
            "<meta name=\"twitter:title\" content=\"" . e($title) . "\">",
            "<meta name=\"twitter:description\" content=\"" . e($description) . "\">",
            "<link rel=\"canonical\" href=\"{$canonical}\">",
            "<link rel=\"alternate\" hreflang=\"ar\" href=\"{$canonical}\">",
            "<link rel=\"alternate\" hreflang=\"en\" href=\"{$canonical}\">",
            "<link rel=\"alternate\" hreflang=\"x-default\" href=\"{$canonical}\">",
        ];

        if ($imageUrl) {
            $lines[] = "<link rel=\"preload\" as=\"image\" fetchpriority=\"high\" href=\"{$imageUrl}\">";
            $lines[] = "<meta property=\"og:image\" content=\"{$imageUrl}\">";
            $lines[] = "<meta property=\"og:image:alt\" content=\"" . e($imageAlt) . "\">";
            $lines[] = "<meta name=\"twitter:image\" content=\"{$imageUrl}\">";
            $lines[] = "<meta name=\"twitter:image:alt\" content=\"" . e($imageAlt) . "\">";
        }

        return implode("\n", $lines) . "\n";
    }

    private function homeLd(string $siteName, string $desc, string $base, string $logo): string
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@graph'   => [
                [
                    '@type'           => 'WebSite',
                    'name'            => $siteName,
                    'url'             => $base,
                    'description'     => $desc,
                    'potentialAction' => [
                        '@type'       => 'SearchAction',
                        'target'      => "{$base}/search?q={search_term_string}",
                        'query-input' => 'required name=search_term_string',
                    ],
                ],
                [
                    '@type' => 'Organization',
                    'name'  => $siteName,
                    'url'   => $base,
                    'logo'  => $logo,
                ],
            ],
        ];
        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
    }

    private function categoryLd(string $name, string $desc, string $catUrl, string $siteName, string $base): string
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@graph'   => [
                ['@type' => 'CollectionPage', 'name' => $name, 'description' => $desc, 'url' => $catUrl],
                [
                    '@type'           => 'BreadcrumbList',
                    'itemListElement' => [
                        ['@type' => 'ListItem', 'position' => 1, 'name' => $siteName, 'item' => $base],
                        ['@type' => 'ListItem', 'position' => 2, 'name' => $name,     'item' => $catUrl],
                    ],
                ],
            ],
        ];
        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
    }

    private function articleLd(
        string $title, string $desc, string $canonical,
        string $imageUrl, string $imageAlt, string $imageCap,
        string $published, string $modified,
        string $siteName, string $logo, string $catName, string $catUrl, string $base
    ): string {
        $image = $imageUrl ? [
            '@type'       => 'ImageObject',
            'url'         => $imageUrl,
            'description' => $imageAlt,
            'caption'     => $imageCap,
        ] : null;

        $article = [
            '@type'         => 'Article',
            'headline'      => $title,
            'description'   => $desc,
            'url'           => $canonical,
            'datePublished' => $published,
            'dateModified'  => $modified,
            'publisher'     => [
                '@type' => 'Organization',
                'name'  => $siteName,
                'logo'  => ['@type' => 'ImageObject', 'url' => $logo],
            ],
        ];
        if ($image) $article['image'] = $image;

        $schema = [
            '@context' => 'https://schema.org',
            '@graph'   => [
                $article,
                [
                    '@type'           => 'BreadcrumbList',
                    'itemListElement' => [
                        ['@type' => 'ListItem', 'position' => 1, 'name' => $siteName, 'item' => $base],
                        ['@type' => 'ListItem', 'position' => 2, 'name' => $catName,  'item' => $catUrl],
                        ['@type' => 'ListItem', 'position' => 3, 'name' => $title,    'item' => $canonical],
                    ],
                ],
            ],
        ];
        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
    }
}
```

- [ ] **Step 4: Run tests — expect PASS**

```bash
php ../../vendor/bin/phpunit tests/Feature/InjectSeoForBotsTest.php --testdox
```

- [ ] **Step 5: Commit**

```bash
git add src/Http/Middleware/InjectSeoForBots.php tests/Feature/InjectSeoForBotsTest.php
git commit -m "feat: implement InjectSeoForBots middleware with meta/OG/JSON-LD injection"
```

---

## Task 7: `AddDiscoveryHeaders` middleware

**Files:**
- Create: `src/Http/Middleware/AddDiscoveryHeaders.php`
- Create: `tests/Feature/AddDiscoveryHeadersTest.php`

- [ ] **Step 1: Write the failing test**

```php
<?php
// tests/Feature/AddDiscoveryHeadersTest.php

namespace Taba\Crm\Tests\Feature;

use Taba\Crm\Tests\TestCase;

class AddDiscoveryHeadersTest extends TestCase
{
    private function putIndexHtml(): void
    {
        file_put_contents(public_path('index.html'), '<html><head></head><body></body></html>');
    }

    protected function tearDown(): void
    {
        @unlink(public_path('index.html'));
        parent::tearDown();
    }

    /** @test */
    public function home_page_includes_link_sitemap_header(): void
    {
        $this->putIndexHtml();
        $response = $this->get('/');
        $this->assertNotEmpty($response->headers->get('Link'));
        $this->assertStringContainsString('rel="sitemap"', $response->headers->get('Link'));
    }

    /** @test */
    public function home_page_includes_link_describedby_header(): void
    {
        $this->putIndexHtml();
        $response = $this->get('/');
        $this->assertStringContainsString('rel="describedby"', $response->headers->get('Link'));
    }
}
```

- [ ] **Step 2: Run — expect FAIL**

```bash
php ../../vendor/bin/phpunit tests/Feature/AddDiscoveryHeadersTest.php --testdox
```

- [ ] **Step 3: Implement the middleware**

```php
<?php
// src/Http/Middleware/AddDiscoveryHeaders.php

namespace Taba\Crm\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddDiscoveryHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $links = [
            '<' . url('/sitemap.xml') . '>; rel="sitemap"',
            '<' . url('/llms.txt') . '>; rel="describedby"',
            '<' . url('/api/v1') . '>; rel="service"',
        ];

        $response->headers->set('Link', implode(', ', $links));
        $response->headers->set('X-Api-Catalog', url('/api/v1'));

        return $response;
    }
}
```

- [ ] **Step 4: Run — expect PASS**

```bash
php ../../vendor/bin/phpunit tests/Feature/AddDiscoveryHeadersTest.php --testdox
```

- [ ] **Step 5: Commit**

```bash
git add src/Http/Middleware/AddDiscoveryHeaders.php tests/Feature/AddDiscoveryHeadersTest.php
git commit -m "feat: implement AddDiscoveryHeaders middleware"
```

---

## Task 8: Register middleware + update routes

**Files:**
- Modify: `src/CrmServiceProvider.php`
- Modify: `routes/web.php`

- [ ] **Step 1: Register middleware aliases in `CrmServiceProvider::boot()`**

Find the section in `boot()` where middleware aliases are registered from config. After the `foreach` loop, add:

```php
// SEO bot-injection middleware
$this->app['router']->aliasMiddleware('crm.seo', \Taba\Crm\Http\Middleware\InjectSeoForBots::class);

// Agent-ready discovery header middleware
$this->app['router']->aliasMiddleware('crm.discovery', \Taba\Crm\Http\Middleware\AddDiscoveryHeaders::class);
```

- [ ] **Step 2: Update `routes/web.php` — replace root route + add catch-all + fix sitemap**

Make the following three changes in `routes/web.php`:

**a) Replace** `Route::get('/', Home::class)->name('home');` with:

```php
// @deprecated Blade home route replaced by Angular SPA — kept as fallback until frontend is built
Route::middleware(['crm.seo', 'crm.discovery'])
    ->get('/', fn() => file_exists(public_path('index.html'))
        ? response()->file(public_path('index.html'))
        : response('', 503))
    ->name('home');
```

**b) Fix sitemap route** — replace all `route(...)` calls inside the sitemap closure with direct `url()` calls:

```php
// Home
$sitemap->add(
    Url::create(url('/'))
        ->setLastModificationDate(now())
        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
        ->setPriority(1.0)
);

// Categories
PostCategory::where('register_in_header', true)->each(function (PostCategory $category) use ($sitemap) {
    $sitemap->add(
        Url::create(url($category->slug))
            ->setLastModificationDate($category->updated_at)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            ->setPriority(0.8)
    );
});

// Posts — all published (not just homepage_section ones)
Post::with('postCategory')
    ->published()
    ->each(function (Post $post) use ($sitemap) {
        if ($post->postCategory) {
            $sitemap->add(
                Url::create(url($post->postCategory->slug . '/' . $post->slug))
                    ->setLastModificationDate($post->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setPriority(0.7)
            );
        }
    });
```

**c) Add catch-all at the bottom** of the `Route::middleware('web')->group(...)` block, after all other routes:

```php
// Angular SPA catch-all — serves public/index.html for all non-API/admin paths
// @deprecated Blade routes above will be removed in a future version
Route::middleware(['crm.seo', 'crm.discovery'])
    ->get('/{any}', fn() => file_exists(public_path('index.html'))
        ? response()->file(public_path('index.html'))
        : response('', 503))
    ->where('any', '^(?!api|admin|filament|preview|sitemap|lang).*');
```

- [ ] **Step 3: Commit**

```bash
git add src/CrmServiceProvider.php routes/web.php
git commit -m "feat: register crm.seo + crm.discovery middleware, update web routes for Angular SPA"
```

---

## Task 9: Markdown negotiation in `PostApiController`

**Files:**
- Modify: `src/Http/Controllers/Api/PostApiController.php`
- Create: `tests/Feature/MarkdownNegotiationTest.php`

- [ ] **Step 1: Write the failing test**

```php
<?php
// tests/Feature/MarkdownNegotiationTest.php

namespace Taba\Crm\Tests\Feature;

use Taba\Crm\Tests\TestCase;
use Taba\Crm\Models\Post;
use Taba\Crm\Models\PostCategory;

class MarkdownNegotiationTest extends TestCase
{
    /** @test */
    public function it_returns_markdown_when_accept_header_is_text_markdown(): void
    {
        $cat  = PostCategory::factory()->create();
        $post = Post::factory()->create([
            'post_category_id' => $cat->id,
            'status'           => 'published',
            'content'          => '# Hello World',
        ]);

        $response = $this->get(
            '/api/v1/posts/' . $post->slug,
            ['Accept' => 'text/markdown']
        );

        $response->assertStatus(200);
        $this->assertStringContainsString('text/markdown', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('# Hello World', $response->getContent());
    }

    /** @test */
    public function it_returns_json_by_default(): void
    {
        $cat  = PostCategory::factory()->create();
        $post = Post::factory()->create([
            'post_category_id' => $cat->id,
            'status'           => 'published',
        ]);

        $response = $this->getJson('/api/v1/posts/' . $post->slug);
        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['id', 'title']]);
    }
}
```

- [ ] **Step 2: Run — expect FAIL**

```bash
php ../../vendor/bin/phpunit tests/Feature/MarkdownNegotiationTest.php --testdox
```

- [ ] **Step 3: Add Markdown negotiation to `PostApiController::show()`**

In `src/Http/Controllers/Api/PostApiController.php`, add this block immediately **before** the `return (new PostResource($post))` line:

```php
// Markdown content negotiation for AI agents
if ($request->prefers('text/markdown')) {
    return response($post->content ?? '', 200)
        ->header('Content-Type', 'text/markdown; charset=utf-8')
        ->header('X-Post-Title', $post->meta_title ?? $post->title)
        ->header('X-Post-Slug', $post->slug);
}
```

- [ ] **Step 4: Run — expect PASS**

```bash
php ../../vendor/bin/phpunit tests/Feature/MarkdownNegotiationTest.php --testdox
```

- [ ] **Step 5: Commit**

```bash
git add src/Http/Controllers/Api/PostApiController.php tests/Feature/MarkdownNegotiationTest.php
git commit -m "feat: add Accept: text/markdown negotiation to posts API"
```

---

## Task 10: `InstallCommand` — Angular publish + build + llms.txt + robots.txt

**Files:**
- Modify: `src/Commands/InstallCommand.php`

- [ ] **Step 1: Add frontend tasks to `handle()` inside the `if (! $this->option('skip-frontend'))` block**

Find the block that contains `'Installing NPM packages'` and `'Building frontend assets'`. Add the three Angular tasks after it:

```php
if (! $this->option('skip-frontend')) {
    $this->task('Installing NPM packages', fn() => $this->runNpmInstall());
    $this->task('Publishing package assets', fn() => $this->publishAssets());
    $this->task('Building frontend assets', fn() => $this->runNpmBuild());

    // Angular frontend
    $this->task('Publishing Angular frontend', fn() => $this->publishAngularFrontend());
    $this->task('Installing Angular npm packages', fn() => $this->runAngularNpmInstall());
    $this->task('Building Angular frontend', fn() => $this->runAngularBuild());
} else {
    // ...existing skip-frontend code...
}
```

Also add after both blocks (always runs):

```php
$this->task('Generating llms.txt', fn() => $this->generateLlmsTxt());
$this->task('Writing robots.txt', fn() => $this->writeRobotsTxt());
```

- [ ] **Step 2: Add the four new protected methods to `InstallCommand`**

```php
protected function publishAngularFrontend(): bool
{
    $source = dirname(__DIR__, 2) . '/frontend'; // package root/frontend
    $dest   = base_path('frontend');

    if (File::isDirectory($dest)) {
        $this->warnings[] = 'Angular frontend already exists at ' . $dest . ' — skipping copy (customize freely).';
        return true;
    }

    File::copyDirectory($source, $dest);
    return true;
}

protected function runAngularNpmInstall(): bool
{
    $result = Process::path(base_path('frontend'))->run('npm install');
    if (! $result->successful()) {
        $this->errors[] = 'Angular npm install failed: ' . $result->errorOutput();
        return false;
    }
    return true;
}

protected function runAngularBuild(): bool
{
    $result = Process::path(base_path('frontend'))->run('npm run build');
    if (! $result->successful()) {
        $this->errors[] = 'Angular build failed: ' . $result->errorOutput();
        return false;
    }
    return true;
}

protected function generateLlmsTxt(): bool
{
    // Pull settings if available, fall back gracefully
    try {
        // CrmSetting::get() handles locale extraction for translatable settings correctly
        $siteName = \Taba\Crm\Models\CrmSetting::get('site_name', config('app.name'));
        $siteDesc = \Taba\Crm\Models\CrmSetting::get('site_description', '');
    } catch (\Throwable) {
        $siteName = config('app.name');
        $siteDesc = '';
    }

    $base    = url('/');
    $apiBase = url('/api/v1');
    $content = <<<LLMS
# {$siteName}

> {$siteDesc}

This site is built on the taba/crm Laravel package. Content is available via a REST API.

## Key URLs

- Homepage: {$base}/
- Sitemap: {$base}/sitemap.xml
- Posts API: {$apiBase}/posts
- Categories API: {$apiBase}/categories

## Content negotiation

Individual post pages support `Accept: text/markdown` for plain Markdown responses.
Example: GET {$apiBase}/posts/{slug} with Accept: text/markdown

## Allowed bots

All AI crawlers are permitted. See /robots.txt for details.
LLMS;

    File::put(public_path('llms.txt'), $content);
    return true;
}

protected function writeRobotsTxt(): bool
{
    $sitemapUrl = url('/sitemap.xml');
    $robotsPath = public_path('robots.txt');

    // Check if Sitemap line already exists
    if (File::exists($robotsPath) && str_contains(File::get($robotsPath), 'Sitemap:')) {
        $this->warnings[] = 'robots.txt already has a Sitemap directive — skipping overwrite.';
        return true;
    }

    $content = <<<ROBOTS
User-agent: *
Allow: /
Disallow: /admin
Disallow: /filament
Disallow: /api/v1/actions
Disallow: /preview/

# AI crawlers — explicitly allowed
User-agent: GPTBot
Allow: /

User-agent: ClaudeBot
Allow: /

User-agent: PerplexityBot
Allow: /

User-agent: anthropic-ai
Allow: /

User-agent: Applebot
Allow: /

User-agent: Googlebot-Extended
Allow: /

Sitemap: {$sitemapUrl}
ROBOTS;

    File::put($robotsPath, $content);
    return true;
}
```

- [ ] **Step 3: Commit**

```bash
git add src/Commands/InstallCommand.php
git commit -m "feat: add Angular publish/build/llms.txt/robots.txt tasks to InstallCommand"
```

---

## Task 11: Angular `Meta`/`Title` service in components

**Files:**
- Modify: `frontend/src/app/components/home/home.ts` — class `Home`
- Modify: `frontend/src/app/components/category/category.ts` — class `Category`
- Modify: `frontend/src/app/components/post-detail/post-detail.ts` — class `PostDetail`

> **Important:** The actual file paths and class names differ from Angular convention. Do **not** search for `HomeComponent` — find `class Home` in `home/home.ts`, `class Category` in `category/category.ts`, and `class PostDetail` in `post-detail/post-detail.ts`.
>
> Settings from `getNavigation()` arrive as `res.settings['general']['site_name']` — these are raw translatable values (`{ ar: '...', en: '...' }` objects). Always pass them through the `t()` helper from `../../utils/i18n` to get a locale-aware string.

- [ ] **Step 1: Update `home/home.ts` (class `Home`)**

Inject `Title` and `Meta` from `@angular/platform-browser`. Import `t` from `../../utils/i18n`.
The navigation data arrives in the existing `getNavigation()` subscription as `res.settings`.

```typescript
import { Title, Meta } from '@angular/platform-browser';
import { t } from '../../utils/i18n';

// In class Home — add alongside existing inject() calls:
private titleSvc = inject(Title);
private meta     = inject(Meta);

// Inside the getNavigation() subscription, after `res.settings` is available:
const gen = res.settings?.['general'] ?? {};
this.titleSvc.setTitle(t(gen['site_name']) || '');
this.meta.updateTag({ name: 'description',        content: t(gen['site_description']) });
this.meta.updateTag({ property: 'og:title',       content: t(gen['site_name']) });
this.meta.updateTag({ property: 'og:description', content: t(gen['site_description']) });
this.meta.updateTag({ property: 'og:image',       content: t(gen['og_image']) });
this.meta.updateTag({ property: 'og:image:alt',   content: t(gen['site_name']) });
```

- [ ] **Step 2: Update `category/category.ts` (class `Category`)**

The category name and description are already extracted via `t()` in the existing component — `t(res.category?.name, 'Category')`. Add `Title`/`Meta` updating after that:

```typescript
import { Title, Meta } from '@angular/platform-browser';
import { t } from '../../utils/i18n'; // already imported — verify and skip if present

// Add alongside existing inject() calls:
private titleSvc = inject(Title);
private meta     = inject(Meta);

// After category data loads (res.category available):
const name = t(res.category?.name, 'Category');
const desc = t(res.category?.description);
this.titleSvc.setTitle(name);
this.meta.updateTag({ name: 'description',        content: desc });
this.meta.updateTag({ property: 'og:title',       content: name });
this.meta.updateTag({ property: 'og:description', content: desc });
```

- [ ] **Step 3: Update `post-detail/post-detail.ts` (class `PostDetail`)**

Post fields (`title`, `meta_title`, `meta_description`) are already Spatie-translatable and arrive as objects from the API — use `t()` again:

```typescript
import { Title, Meta } from '@angular/platform-browser';
import { t } from '../../utils/i18n'; // already imported — verify and skip if present

// Add alongside existing inject() calls:
private titleSvc = inject(Title);
private meta     = inject(Meta);

// After post data loads (res.post available):
const postTitle = t(res.post?.meta_title) || t(res.post?.title, 'Post');
const postDesc  = t(res.post?.meta_description);
const imageUrl  = res.post?.images?.[0]?.url  || '';
const imageAlt  = res.post?.images?.[0]?.alt  || postTitle;

this.titleSvc.setTitle(postTitle);
this.meta.updateTag({ name: 'description',          content: postDesc });
this.meta.updateTag({ property: 'og:title',         content: postTitle });
this.meta.updateTag({ property: 'og:description',   content: postDesc });
this.meta.updateTag({ property: 'og:image',         content: imageUrl });
this.meta.updateTag({ property: 'og:image:alt',     content: imageAlt });
this.meta.updateTag({ name: 'twitter:title',        content: postTitle });
this.meta.updateTag({ name: 'twitter:image:alt',    content: imageAlt });
```

- [ ] **Step 4: Commit**

```bash
git add frontend/src/app/components/home/home.ts \
        frontend/src/app/components/category/category.ts \
        frontend/src/app/components/post-detail/post-detail.ts
git commit -m "feat: add Title/Meta service SEO updates to Home/Category/PostDetail components"
```

---

## Task 12: Full test suite + verification

- [ ] **Step 1: Run all package tests**

```bash
cd packages/taba/crm
php ../../vendor/bin/phpunit --testdox
```

Expected: All tests pass. Zero failures.

- [ ] **Step 2: Verify middleware aliases exist**

```bash
php ../../artisan crm:install --help
```

No error. Optionally run in a test environment to confirm `robots.txt` and `llms.txt` generation.

- [ ] **Step 3: Smoke test bot injection manually**

After a full `crm:install` (with `ng build` complete):

```bash
# Bot gets injected meta
curl -sA "Googlebot/2.1" http://localhost/ | grep -E "og:title|application/ld\+json|lang="

# Real browser gets plain index.html
curl -s http://localhost/ | grep og:title  # should return nothing

# Markdown negotiation
curl -H "Accept: text/markdown" http://localhost/api/v1/posts/<slug>
# Content-Type should be text/markdown
```

- [ ] **Step 4: Final commit**

```bash
git add -A
git commit -m "chore: run full test suite — all passing"
```

---

## Task 13: README update

**Files:**
- Modify: `README.md`

- [ ] **Step 1: Add "Frontend Setup" section to README**

Add after the existing Installation section:

```markdown
## Frontend Setup

The package includes an Angular 21 SPA frontend. `crm:install` handles everything automatically:

```bash
php artisan crm:install
```

This will:
1. Copy the Angular source to `frontend/` in your project root
2. Run `npm install` inside `frontend/`
3. Build with `ng build` — outputs directly to `public/`
4. Generate `public/llms.txt` for AI agent discoverability
5. Write `public/robots.txt` with AI bot Allow rules + Sitemap directive

### Customizing the theme

Edit `frontend/src/styles/tokens.scss` and change CSS custom properties:

```scss
:root {
  --color-primary: #your-brand-color;
  --font-family: 'Your Font', sans-serif;
}
```

Then rebuild: `cd frontend && npm run build`

### SEO

- Crawlers and social bots automatically receive pre-rendered meta tags, OG tags, and JSON-LD structured data
- Real browsers get the normal SPA — Angular's `Title`/`Meta` services update tags after load
- `public/llms.txt` exposes the site to AI agents
- `GET /api/v1/posts/{slug}` supports `Accept: text/markdown` for AI content consumption
```

- [ ] **Step 2: Commit**

```bash
git add README.md
git commit -m "docs: add Frontend Setup and SEO sections to README"
```
