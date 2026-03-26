# Client Panel Access & Dashboard Improvements

**Date**: 2026-03-26  
**Status**: Approved

## Problem

1. **Client users cannot log into the admin panel.** `canAccessPanel()` only allows `super_admin`. When a client user tries to log in, they see "بيانات الاعتماد هذه غير متطابقة" even though credentials are valid — Filament rejects them at the gate.

2. **Dashboard shows fake data.** VisitorAnalytics widget has hardcoded sample data. OthersAnalytics duplicates PaymentAnalytics. LatestPosts and RecentActivities are disabled.

## Solution

### Part 1: Client Panel Access

**canAccessPanel() change** — `packages/taba/crm/src/Models/User.php`

Allow `super_admin`, `admin`, and `client` roles to access the admin panel. Rely on Spatie permissions + FilamentShield to control what each role sees.

```php
public function canAccessPanel(Panel $panel): bool
{
    return $this->hasRole(['super_admin', 'admin', 'client']);
}
```

**Client permissions** — `database/seeders/RolesAndPermissionsSeeder.php`

Add minimal permissions for the `client` role:
- `view_any_post`, `view_post`, `create_post`, `update_post`
- `view_any_service::payment`, `view_service::payment`

No access to: users, roles, settings, media, categories, contact entries, AI tools.

**Resource scoping for clients** — PostResource and ServicePaymentResource

Override `getEloquentQuery()` in both resources. When the authenticated user has the `client` role, scope queries to `where('user_id', auth()->id())`. Admins and super_admins see all records.

### Part 2: Dashboard Improvements

**GlobalStatsOverview** — make role-aware:
- Admin/super_admin: all stats (total users, published posts, drafts, categories, contact entries)
- Client: personal stats only (my published posts, my drafts, my payments total)

**VisitorAnalytics** — replace fake data:
- Query real user registration data over last 7 days using the existing `getChartData()` pattern
- Rename heading to "تسجيلات المستخدمين الجدد" (New User Registrations)
- Hide from clients (admin-only widget)

**PaymentAnalytics** — keep as-is (already uses real ServicePayment data)

**OthersAnalytics** — remove (duplicates PaymentAnalytics with same model and date range)

**LatestPosts** — re-enable, make role-aware:
- Admin: latest 5 posts from all users
- Client: latest 5 of their own posts (scope by user_id)

**RecentActivities** — re-enable:
- Already has `HasWidgetShield` for permission control
- Visible to admins only; clients have no permission

**Widget order**:
1. GlobalStatsOverview (full width, stat cards)
2. PaymentAnalytics (half width)
3. VisitorAnalytics (half width) — admin only
4. LatestPosts (full width)
5. RecentActivities (full width) — admin only

## Files to Modify

| File | Change |
|------|--------|
| `packages/taba/crm/src/Models/User.php` | Expand `canAccessPanel()` to allow admin + client |
| `packages/taba/crm/src/CrmPlugin.php` | Enable LatestPosts, RecentActivities; remove OthersAnalytics |
| `packages/taba/crm/src/Filament/Widgets/GlobalStatsOverview.php` | Role-aware stats for clients |
| `packages/taba/crm/src/Filament/Widgets/VisitorAnalytics.php` | Replace fake data with real user registration query |
| `packages/taba/crm/src/Filament/Widgets/LatestPosts.php` | Scope query for client role |
| `database/seeders/RolesAndPermissionsSeeder.php` | Add client role permissions |
| `packages/taba/crm/src/Filament/Resources/PostResource.php` | `getEloquentQuery()` scoping for clients |
| `packages/taba/crm/src/Filament/Resources/ServicePaymentResource.php` | `getEloquentQuery()` scoping for clients |

## Out of Scope

- Google Analytics integration (no active config)
- Separate client panel at different URL
- New custom widgets beyond fixing existing ones
- Visual/styling redesign of the dashboard
