# Quickstart: Laravel API Hardening + Angular Dynamic Frontend Integration

**Branch**: `001-api-angular-integration`  
**Spec**: [`spec.md`](./spec.md) | **Plan**: [`plan.md`](./plan.md)

---

## Prerequisites

| Tool        | Min Version | Purpose         |
| ----------- | ----------- | --------------- |
| PHP         | 8.2         | Laravel backend |
| Composer    | 2.x         | PHP deps        |
| Node.js     | 22.x        | Angular build   |
| npm         | 10.x        | Frontend deps   |
| MySQL       | 8.0+        | App database    |
| Angular CLI | 21.x        | (`ng` commands) |

---

## 1. Backend Setup (C:\WORK\taba-crm)

### Environment

Ensure `.env` has:

```ini
APP_URL=http://127.0.0.1:8000

# CORS — add production domain when deploying
CRM_API_CORS_ORIGINS=http://localhost:4200

# DB
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=taba_crm
DB_USERNAME=root
DB_PASSWORD=
```

### Run pending migrations

```bash
php artisan migrate
```

This includes:

- `[date]_add_phone_service_to_contact_entries_table` — adds `phone`, `service` cols, makes `email` nullable

### Run the CRM Settings seeder

```bash
php artisan db:seed --class=CrmSettingSeeder
```

Seeds all 24 required settings keys with Arabic defaults. **Idempotent** — safe to re-run on existing databases.

### Verify settings seeder

```bash
curl http://127.0.0.1:8000/api/v1/settings/grouped
# Expected: flat JSON object with hero_title, site_name, etc.
```

### Start the dev server

```bash
php artisan serve
# Listens on http://127.0.0.1:8000
```

---

## 2. Frontend Setup (C:\WORK\sbc-clean)

### Install dependencies (if not done)

```bash
npm install
```

### Development environment

`src/environments/environment.development.ts` is already configured:

```typescript
export const environment = {
    production: false,
    apiUrl: "http://127.0.0.1:8000/api/v1",
    defaultLocale: "ar",
    supportedLocales: ["ar", "en"],
};
```

### Start the Angular dev server

```bash
ng serve
# App runs on http://localhost:4200
```

### Verify CORS & API connection

Open browser DevTools → Network tab. Reload `http://localhost:4200`. Verify:

- `GET /api/v1/init` → 200, no CORS errors
- `GET /api/v1/home` → 200, posts have `image_url` as absolute URL

---

## 3. Production Deployment

### Backend — CORS for production domain

In production `.env`:

```ini
CRM_API_CORS_ORIGINS=http://localhost:4200,https://your-actual-domain.com
```

### Frontend — Production build

Update `src/environments/environment.ts` (`apiUrl` must not contain `your-domain.com`):

```typescript
export const environment = {
    production: true,
    apiUrl: "https://api.your-actual-domain.com/api/v1",
    // ...
};
```

Build:

```bash
ng build --configuration production
# Verify no "your-domain.com" in the bundle:
grep -r "your-domain.com" dist/ || echo "OK — no placeholder found"
```

---

## 4. Manual Steps (Held Migrations)

### Drop `homepage_sections` table

This migration is **NOT run automatically**. Execute only during a planned maintenance window:

```bash
php artisan migrate --path=database/migrations/[date]_drop_homepage_sections_table.php
```

> The table is superseded by `section_component` on `post_categories`. Dropping it has no effect on the running application.

---

## 5. Key URLs for Manual Testing

### Backend (Laravel running on port 8000)

| Endpoint                         | Expected                                                                |
| -------------------------------- | ----------------------------------------------------------------------- |
| `GET /api/v1/init`               | 200 — `data.settings` grouped by group key                              |
| `GET /api/v1/settings/grouped`   | 200 — flat `{ key: value }` with all 24 keys                            |
| `GET /api/v1/home`               | 200 — `data.featured_posts[*].image_url` is absolute URL string         |
| `GET /api/v1/posts`              | 200 — each post has top-level `image_url`                               |
| `GET /api/v1/posts/{slug}`       | 200 — `content` is array of blocks; figure blocks have `data.image_url` |
| `GET /api/v1/categories`         | 200 — each category has `image_url`                                     |
| `POST /api/v1/contact`           | 201 — creates ContactEntry; no email required                           |
| `POST /api/v1/contact` (×31/min) | 429 with `Retry-After` header                                           |

### Frontend (Angular running on port 4200)

| URL                                             | Expected                                  |
| ----------------------------------------------- | ----------------------------------------- |
| `http://localhost:4200/`                        | Homepage — ContentService-driven content  |
| `http://localhost:4200/blog/news`               | PostListComponent — paginated posts       |
| `http://localhost:4200/blog/news/my-first-post` | PostDetailComponent — full content blocks |
| `http://localhost:4200/page/about`              | PageComponent — CMS page content          |
| `http://localhost:4200/login`                   | LoginComponent — email/password form      |
| `http://localhost:4200/register`                | RegisterComponent — registration form     |

---

## 6. Acceptance Test Checklist

### US-1: API Returns Complete Data

- [ ] `GET /api/v1/settings/grouped` → flat JSON with all 24 keys non-null
- [ ] `GET /api/v1/posts` → each post has `image_url` as `https://...` string (not null/number)
- [ ] `GET /api/v1/categories` → each category has `image_url`
- [ ] `GET /api/v1/posts/{slug}` → `content` is array; figure blocks contain `data.image_url`

### US-2: CORS

- [ ] No CORS errors in browser DevTools when Angular runs on `localhost:4200`
- [ ] `OPTIONS` preflight to `/api/v1/init` returns 200 with `Access-Control-Allow-Origin: http://localhost:4200`
- [ ] Request from `http://evil.com` does NOT receive `Access-Control-Allow-Origin` for that origin

### US-3: Booking Form → API

- [ ] Fill all 4 steps → Submit → check `contact_entries` DB table for new record
- [ ] Submit with empty name → API returns 422 → form shows error message
- [ ] Successful submit shows PrimeNG Toast success message
- [ ] Form resets to step 1 after success

### US-4: Dynamic Homepage

- [ ] After seeder run: `LineOfWorkComponent` shows posts from `line-of-work` category
- [ ] `AboutComponent.pills` shows seeder values, not hardcoded array (once `about_pills` seeded)
- [ ] Disable API (stop Laravel server) → homepage loads with static fallback data, no crash

### US-5: Routes

- [ ] Direct browser navigation to `/blog/news` → PostListComponent renders
- [ ] Direct navigation to `/blog/news/test-slug` → PostDetailComponent renders
- [ ] Direct navigation to `/page/about` → PageComponent renders
- [ ] Non-existent slug → 404 message shown, no blank screen

### US-7: Production Build

- [ ] `ng build --configuration production` completes without errors
- [ ] No `your-domain.com` string in `dist/` output

### US-8: Rate Limiting

- [ ] 101 requests to `GET /api/v1/posts` within 1 min → 101st gets 429 + `Retry-After`
- [ ] 31 requests to `POST /api/v1/contact` within 1 min → 31st gets 429

### US-9: Dead Code Removal

- [ ] No files in `hero3/` directory
- [ ] `ng build` completes without errors after removal
- [ ] `grep -r "hero3" src/` → no results

---

## 7. Architecture Notes

### Why `getAllFlat()` not `getAllGrouped()` for `/settings/grouped`

`ContentService.setting()` in Angular iterates over groups (`for (group of Object.values(grouped))`). The `/init` endpoint uses grouped format intentionally. The `/settings/grouped` standalone endpoint needs flat format per spec. Two methods, two use cases — no shared state changed.

### Why `email` is nullable on ContactEntry

The booking form is a service request form (name, phone, service, date, time). Email is not relevant for a physical cleaning service booking. Making it nullable preserves the column for web contact forms while accommodating the booking flow.

### Why `supports_credentials: true` in CORS

Required for Sanctum cookie-based auth sessions. Without this, `sanctum/csrf-cookie` preflight fails and token-authenticated routes break from the Angular app.

### Why figure block `image_url` is injected in the resource (not controller)

`PostResource` owns the serialization contract. The controller's `->with(['image'])` already eager-loads the post's featured image; for figure blocks, `Media::find()` is called per-block but is bounded (typically < 10 figures per post) and cached by Eloquent. This avoids N+1 and keeps the controller thin.

### Why the `homepage_sections` migration is "held"

The table was created and may contain legacy data. Dropping it during an automated deploy risks data loss if anything still reads it. The `section_component` column on `post_categories` serves the same purpose. A deliberate maintenance-window drop is the safe path.
