# Client Access & Dashboard Improvements — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Allow client users to access the Filament admin panel with scoped data, and improve dashboard widgets with real data and role-awareness.

**Architecture:** Expand `canAccessPanel()` to accept all three roles. Use Filament's `getEloquentQuery()` override to scope resources per role. Make dashboard widgets role-aware using `auth()->user()->hasRole()` checks.

**Tech Stack:** Laravel 11, Filament v3, Spatie Laravel Permission, FilamentShield

**Spec:** `docs/superpowers/specs/2026-03-26-client-access-dashboard-improvements-design.md`

---

### Task 1: Fix canAccessPanel() to allow client and admin roles

**Files:**
- Modify: `packages/taba/crm/src/Models/User.php:88-91`

- [ ] **Step 1: Update canAccessPanel()**

In `packages/taba/crm/src/Models/User.php`, change:

```php
public function canAccessPanel(Panel $panel): bool
{
    return $this->hasRole(['super_admin', 'admin', 'client']);
}
```

- [ ] **Step 2: Verify syntax**

Run: `php -l packages/taba/crm/src/Models/User.php`
Expected: `No syntax errors detected`

- [ ] **Step 3: Commit**

```bash
git add packages/taba/crm/src/Models/User.php
git commit -m "fix: allow admin and client roles to access Filament panel"
```

---

### Task 2: Update RolesAndPermissionsSeeder with FilamentShield permissions for client

**Files:**
- Modify: `database/seeders/RolesAndPermissionsSeeder.php`

Note: The seeder skips if roles exist. For existing databases, run `php artisan shield:generate` or manually assign permissions. This change only affects fresh installs.

- [ ] **Step 1: Add FilamentShield-style permissions for client role**

In `database/seeders/RolesAndPermissionsSeeder.php`, after the existing client role `syncPermissions`, add the FilamentShield permissions that Filament actually checks:

```php
// FilamentShield permissions for client panel access
$shieldClientPermissions = [
    'view_any_post',
    'view_post',
    'create_post',
    'update_post',
    'view_any_service::payment',
    'view_service::payment',
];

foreach ($shieldClientPermissions as $permission) {
    Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
}

$clientRole->givePermissionTo($shieldClientPermissions);
```

Append this after the existing `$clientRole->syncPermissions([...]);` block. Do NOT replace the existing syncPermissions — those are the generic permissions. Add the Shield ones on top.

- [ ] **Step 2: Verify syntax**

Run: `php -l database/seeders/RolesAndPermissionsSeeder.php`
Expected: `No syntax errors detected`

- [ ] **Step 3: Commit**

```bash
git add database/seeders/RolesAndPermissionsSeeder.php
git commit -m "feat: add FilamentShield permissions for client role"
```

---

### Task 3: Scope PostResource for client users

**Files:**
- Modify: `packages/taba/crm/src/Filament/Resources/PostResource.php`

- [ ] **Step 1: Add getEloquentQuery() override**

Add this method inside the `PostResource` class, after the existing `use Translatable;` line and class properties (before the `form()` method):

```php
public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
{
    $query = parent::getEloquentQuery();

    if (auth()->user()->hasRole('client')) {
        $query->where('user_id', auth()->id());
    }

    return $query;
}
```

- [ ] **Step 2: Verify syntax**

Run: `php -l packages/taba/crm/src/Filament/Resources/PostResource.php`
Expected: `No syntax errors detected`

- [ ] **Step 3: Commit**

```bash
git add packages/taba/crm/src/Filament/Resources/PostResource.php
git commit -m "feat: scope PostResource to client's own posts"
```

---

### Task 4: Scope ServicePaymentResource for client users

**Files:**
- Modify: `packages/taba/crm/src/Filament/Resources/ServicePaymentResource.php`

- [ ] **Step 1: Add getEloquentQuery() override**

Add this method inside the `ServicePaymentResource` class, after the `getPluralModelLabel()` method:

```php
public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
{
    $query = parent::getEloquentQuery();

    if (auth()->user()->hasRole('client')) {
        $query->where('user_id', auth()->id());
    }

    return $query;
}
```

- [ ] **Step 2: Verify syntax**

Run: `php -l packages/taba/crm/src/Filament/Resources/ServicePaymentResource.php`
Expected: `No syntax errors detected`

- [ ] **Step 3: Commit**

```bash
git add packages/taba/crm/src/Filament/Resources/ServicePaymentResource.php
git commit -m "feat: scope ServicePaymentResource to client's own payments"
```

---

### Task 5: Make GlobalStatsOverview role-aware

**Files:**
- Modify: `packages/taba/crm/src/Filament/Widgets/GlobalStatsOverview.php`

- [ ] **Step 1: Replace getStats() with role-aware version**

Replace the entire `getStats()` method with:

```php
protected function getStats(): array
{
    $user = auth()->user();
    $isClient = $user->hasRole('client');

    $endDate = Carbon::now();
    $startDateCurrent = $endDate->copy()->subDays(7);
    $startDatePrevious = $startDateCurrent->copy()->subDays(7);

    if ($isClient) {
        // Client sees only their own stats
        $myPublished = Post::where('user_id', $user->id)->where('is_published', true)->count();
        $myDrafts = Post::where('user_id', $user->id)->where('is_published', false)->count();
        $myPayments = ServicePayment::where('user_id', $user->id)->sum('amount');

        return [
            Stat::make(__('My Published Posts'), Number::format($myPublished))
                ->description(__('Your live posts'))
                ->color('success')
                ->icon('heroicon-o-newspaper'),

            Stat::make(__('My Drafts'), Number::format($myDrafts))
                ->description(__('Posts pending publication'))
                ->color('warning')
                ->icon('heroicon-o-pencil-square'),

            Stat::make(__('My Payments'), __('SAR') . ' ' . Number::format($myPayments, 2))
                ->description(__('Total from your payments'))
                ->color('primary')
                ->icon('heroicon-o-banknotes'),
        ];
    }

    // Admin/super_admin sees everything
    $currentUserCount = User::whereBetween('created_at', [$startDateCurrent, $endDate])->count();
    $previousUserCount = User::whereBetween('created_at', [$startDatePrevious, $startDateCurrent])->count();
    $userChange = $this->calculatePercentageChange($currentUserCount, $previousUserCount);

    $currentEntries = ContactEntry::whereBetween('created_at', [$startDateCurrent, $endDate])->count();
    $previousEntries = ContactEntry::whereBetween('created_at', [$startDatePrevious, $startDateCurrent])->count();
    $entryChange = $this->calculatePercentageChange($currentEntries, $previousEntries);

    return [
        Stat::make(__('Total Users'), Number::format(User::count()))
            ->description(sprintf('%d%% %s', abs($userChange), $userChange >= 0 ? __('increase') : __('decrease')))
            ->descriptionIcon($userChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
            ->color($userChange >= 0 ? 'success' : 'danger')
            ->chart($this->getChartData(User::class, $startDateCurrent))
            ->icon('heroicon-o-users'),

        Stat::make(__('Published Posts'), Number::format(Post::where('is_published', true)->count()))
            ->description(__('Total live posts'))
            ->color('success')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->icon('heroicon-o-newspaper'),

        Stat::make(__('Draft Posts'), Number::format(Post::where('is_published', false)->count()))
            ->description(__('Posts pending publication'))
            ->color('warning')
            ->icon('heroicon-o-pencil-square'),

        Stat::make(__('Post Categories'), Number::format(PostCategory::count()))
            ->description(__('Total number of categories'))
            ->color('info')
            ->icon('heroicon-o-tag'),

        Stat::make(__('Contact Form Entries'), Number::format(ContactEntry::count()))
            ->description(sprintf('%d%% %s', abs($entryChange), $entryChange >= 0 ? __('increase') : __('decrease')))
            ->descriptionIcon($entryChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
            ->color($entryChange >= 0 ? 'success' : 'danger')
            ->chart($this->getChartData(ContactEntry::class, $startDateCurrent))
            ->icon('heroicon-o-inbox-stack'),
    ];
}
```

- [ ] **Step 2: Verify syntax**

Run: `php -l packages/taba/crm/src/Filament/Widgets/GlobalStatsOverview.php`
Expected: `No syntax errors detected`

- [ ] **Step 3: Commit**

```bash
git add packages/taba/crm/src/Filament/Widgets/GlobalStatsOverview.php
git commit -m "feat: make GlobalStatsOverview role-aware for clients"
```

---

### Task 6: Replace VisitorAnalytics fake data with real user registration data

**Files:**
- Modify: `packages/taba/crm/src/Filament/Widgets/VisitorAnalytics.php`

- [ ] **Step 1: Replace entire file contents**

```php
<?php

namespace Taba\Crm\Filament\Widgets;

use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Taba\Crm\Models\User;

class VisitorAnalytics extends ChartWidget
{
    use HasWidgetShield;

    protected static ?string $heading = null;

    protected int | string | array $columnSpan = 'full/2';

    public function getHeading(): ?string
    {
        return __('New User Registrations');
    }

    protected function getData(): array
    {
        $startDate = Carbon::now()->subDays(7);

        $data = User::query()
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->pluck('count', 'date');

        return [
            'datasets' => [
                [
                    'label' => __('New Users'),
                    'data' => $data->values()->toArray(),
                    'backgroundColor' => 'rgba(129, 223, 67, 0.5)',
                    'borderColor' => 'rgba(129, 223, 67, 1)',
                ],
            ],
            'labels' => $data->keys()->map(fn ($date) => Carbon::parse($date)->format('M d'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
```

Key changes: real DB query, `HasWidgetShield` trait (admin-only via permission), translatable heading.

- [ ] **Step 2: Verify syntax**

Run: `php -l packages/taba/crm/src/Filament/Widgets/VisitorAnalytics.php`
Expected: `No syntax errors detected`

- [ ] **Step 3: Commit**

```bash
git add packages/taba/crm/src/Filament/Widgets/VisitorAnalytics.php
git commit -m "feat: replace fake VisitorAnalytics data with real user registration query"
```

---

### Task 7: Make LatestPosts role-aware

**Files:**
- Modify: `packages/taba/crm/src/Filament/Widgets/LatestPosts.php`

- [ ] **Step 1: Add client scoping to the table query**

Replace the `table()` method:

```php
public function table(Table $table): Table
{
    $query = PostResource::getEloquentQuery()->latest()->limit(5);

    if (auth()->user()->hasRole('client')) {
        $query->where('user_id', auth()->id());
    }

    return $table
        ->query($query)
        ->columns([
            Tables\Columns\TextColumn::make('title')
                ->label(__('Title')),
            Tables\Columns\TextColumn::make('user.name')
                ->label(__('Author')),
            Tables\Columns\TextColumn::make('created_at')
                ->label(__('Created At'))
                ->dateTime(),
        ])
        ->actions([
            Tables\Actions\Action::make('edit')
                ->label(__('Edit'))
                ->url(fn (Post $record): string => PostResource::getUrl('edit', ['record' => $record])),
        ]);
}
```

- [ ] **Step 2: Verify syntax**

Run: `php -l packages/taba/crm/src/Filament/Widgets/LatestPosts.php`
Expected: `No syntax errors detected`

- [ ] **Step 3: Commit**

```bash
git add packages/taba/crm/src/Filament/Widgets/LatestPosts.php
git commit -m "feat: scope LatestPosts widget for client users"
```

---

### Task 8: Update CrmPlugin widget registration

**Files:**
- Modify: `packages/taba/crm/src/CrmPlugin.php`

- [ ] **Step 1: Update widgets array**

In the `register()` method, find the `->widgets([...])` call and replace it with:

```php
->widgets([
    GlobalStatsOverview::class,
    PaymentAnalytics::class,
    VisitorAnalytics::class,
    LatestPosts::class,
    RecentActivities::class,
])
```

This removes `OthersAnalytics` (duplicated PaymentAnalytics data) and enables `LatestPosts` + `RecentActivities`.

- [ ] **Step 2: Verify syntax**

Run: `php -l packages/taba/crm/src/CrmPlugin.php`
Expected: `No syntax errors detected`

- [ ] **Step 3: Commit**

```bash
git add packages/taba/crm/src/CrmPlugin.php
git commit -m "feat: enable LatestPosts and RecentActivities, remove OthersAnalytics"
```

---

### Task 9: Manual verification

- [ ] **Step 1: Clear caches**

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan shield:generate --all
```

The `shield:generate` creates/syncs FilamentShield permissions for all resources and widgets.

- [ ] **Step 2: Assign client permissions**

For existing databases where the seeder was already run, manually assign permissions:

```bash
php artisan tinker --execute="
\$client = \Spatie\Permission\Models\Role::findByName('client');
\$perms = ['view_any_post','view_post','create_post','update_post','view_any_service::payment','view_service::payment'];
foreach(\$perms as \$p) { \Spatie\Permission\Models\Permission::firstOrCreate(['name'=>\$p,'guard_name'=>'web']); }
\$client->givePermissionTo(\$perms);
echo 'Done';
"
```

- [ ] **Step 3: Test admin login**

Login as `taba@admin.com` / `admin` at `/admin`. Verify:
- All stats visible in GlobalStatsOverview
- VisitorAnalytics shows real user registration chart (may be empty if no recent registrations)
- LatestPosts table visible with all posts
- RecentActivities table visible
- PaymentAnalytics chart visible

- [ ] **Step 4: Test client login**

Create or use a client user. Login at `/admin`. Verify:
- Dashboard shows "My Published Posts", "My Drafts", "My Payments" stats only
- LatestPosts shows only their own posts
- VisitorAnalytics NOT visible (permission denied)
- RecentActivities NOT visible (permission denied)
- Post resource shows only their own posts
- ServicePayment resource shows only their own payments
- No access to Users, Roles, Settings, Media, Categories, ContactEntries in sidebar

- [ ] **Step 5: Final commit**

```bash
git add -A
git commit -m "feat: complete client access and dashboard improvements"
```
