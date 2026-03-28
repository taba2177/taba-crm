# Data Model: Laravel API Hardening + Angular Dynamic Frontend Integration

**Feature**: `001-api-angular-integration`  
**Phase**: 1 — Design  
**Date**: 2026-03-12

---

## Backend Entities (Laravel — `packages/taba/crm/src`)

### CrmSetting _(existing — new method + seeder)_

| Field             | Type                | Notes                                                            |
| ----------------- | ------------------- | ---------------------------------------------------------------- |
| `id`              | bigint              | PK                                                               |
| `key`             | string (unique)     | Lookup key                                                       |
| `value`           | json                | Cast to array; may be scalar, translatable object, or JSON array |
| `type`            | string              | `text`, `url`, `image`, `color`, `boolean`, `json`               |
| `group`           | string              | Admin grouping only; not exposed in flat API response            |
| `label`           | json (translatable) | Admin display label                                              |
| `description`     | json (translatable) | Admin hint text                                                  |
| `is_translatable` | boolean             | If true, `value` is `{"ar": "...", "en": "..."}`                 |
| `order`           | int                 | Sort order for admin display                                     |

**New method** — `getAllFlat(): array`  
Returns `{ "key": value }` for all settings in order. Used by `/api/v1/settings/grouped`.

```
{ "site_name": "SBC كلين", "hero_title": "...", "about_title": "...", ... }
```

**Existing method** — `getAllGrouped(): array` (unchanged)  
Returns `{ "group": { "key": value } }`. Used by `/api/v1/init` → consumed by ContentService.

**Required seeder keys** (24, FR-001):

| Key                 | Group      | Default Arabic Value                        |
| ------------------- | ---------- | ------------------------------------------- |
| `hero_title`        | `hero`     | `خدمات تنظيف مباني احترافية`                |
| `hero_description`  | `hero`     | `SBC كلين تقدم خدمات تنظيف وتعقيم شاملة`    |
| `hero_cta_text`     | `hero`     | `اطلب عرض سعر مجاني`                        |
| `hero_cta_link`     | `hero`     | `#booking`                                  |
| `about_title`       | `about`    | `متخصصون في تنظيف المباني`                  |
| `about_description` | `about`    | `مؤسسة رائدة في تنظيف المنشآت مقرها الرياض` |
| `features_title`    | `features` | `لماذا تختارنا؟`                            |
| `services_title`    | `services` | `خدماتنا`                                   |
| `partners_title`    | `partners` | `شركاؤنا`                                   |
| `branches_title`    | `branches` | `تغطيتنا الجغرافية`                         |
| `cta_title`         | `cta`      | `احجز خدمتك الآن`                           |
| `cta_subtitle`      | `cta`      | `خطوات بسيطة وسريعة`                        |
| `cta_book_link`     | `cta`      | `#booking`                                  |
| `contact_email`     | `contact`  | `info@sbc-clean.com`                        |
| `contact_phone`     | `contact`  | `+966550488892`                             |
| `contact_whatsapp`  | `contact`  | `+966550488892`                             |
| `site_name`         | `general`  | `SBC كلين`                                  |
| `site_logo`         | `general`  | `assets/logo.svg`                           |
| `footer_address`    | `footer`   | `الرياض، المملكة العربية السعودية`          |
| `footer_links`      | `footer`   | `[]` (JSON)                                 |
| `social_facebook`   | `social`   | `` (empty string)                           |
| `social_twitter`    | `social`   | ``                                          |
| `social_instagram`  | `social`   | ``                                          |
| `social_linkedin`   | `social`   | ``                                          |

**Additional seeder keys** (for new frontend fields):

| Key           | Group   | Default Value                                                                              | Used by          |
| ------------- | ------- | ------------------------------------------------------------------------------------------ | ---------------- |
| `about_pills` | `about` | `["كوادر فنية مدربة","معدات وتقنيات حديثة","مواد آمنة ومعتمدة","ضمان جودة الخدمة"]` (JSON) | `AboutComponent` |

**Seeder strategy**: All keys use `CrmSetting::upsert([...], ['key'], ['value'])` — idempotent; safe to re-run.

---

### Post _(existing — API resource extended)_

| Field              | Type                       | Notes                                                             |
| ------------------ | -------------------------- | ----------------------------------------------------------------- |
| `id`               | bigint                     | PK                                                                |
| `title`            | json (translatable)        |                                                                   |
| `slug`             | string (unique)            | URL slug                                                          |
| `content`          | json (translatable, array) | Array of `{ type, data }` blocks — see Content Block schema below |
| `excerpt`          | string (nullable)          | Short summary                                                     |
| `icon`             | string (nullable)          | FontAwesome class                                                 |
| `image_id`         | FK → `curator_media.id`    | Nullable; resolved by Curator                                     |
| `post_category_id` | FK → `post_categories.id`  |                                                                   |
| `show_in_home`     | boolean                    |                                                                   |
| `is_published`     | boolean                    |                                                                   |
| `published_at`     | datetime                   |                                                                   |

**API resource additions** (`PostResource`):

- `image_url: string | null` — `$this->image?->url ?? null` (Curator absolute URL)
- `content` — content blocks post-processed to inject `image_url` into figure-type blocks

#### Content Block Schema

Each element of the `content` array:

```json
{ "type": "markdown",  "data": { "text": "..." } }
{ "type": "heading",   "data": { "level": 2, "text": "..." } }
{ "type": "figure",    "data": { "image_id": 42, "caption": "...", "image_url": "https://..." } }
{ "type": "quote",     "data": { "text": "...", "author": "..." } }
{ "type": "list",      "data": { "items": ["...", "..."], "ordered": false } }
```

`figure` blocks have `image_url` injected by `PostResource::resolveContentBlocks()`:

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

---

### PostCategory _(existing — API resource extended)_

| Field                | Type                | Notes                                                              |
| -------------------- | ------------------- | ------------------------------------------------------------------ |
| `id`                 | bigint              | PK                                                                 |
| `name`               | json (translatable) |                                                                    |
| `slug`               | string              | `line-of-work`, `partners`, `services`, etc.                       |
| `image`              | string (nullable)   | Raw path/URL stored as string (no BelongsTo relation)              |
| `section_component`  | string (nullable)   | Blade/Angular component identifier; supersedes `homepage_sections` |
| `register_in_header` | boolean             |                                                                    |
| `order`              | int                 |                                                                    |

**API resource addition** (`PostCategoryResource`):

- `image_url: string | null` — resolves raw `image` field to absolute URL:

```php
'image_url' => $this->image
    ? (str_starts_with($this->image, 'http') ? $this->image : Storage::url($this->image))
    : null,
```

---

### ContactEntry _(existing — schema extended)_

| Field        | Type   | Changed?                    | Notes                                       |
| ------------ | ------ | --------------------------- | ------------------------------------------- |
| `id`         | bigint |                             | PK                                          |
| `name`       | string |                             | Required                                    |
| `email`      | string | **nullable** (was required) | Optional for booking submissions            |
| `message`    | text   |                             | Composed from address + notes + date + time |
| `phone`      | string | **NEW** (nullable)          | Direct from booking form                    |
| `service`    | string | **NEW** (nullable)          | Selected service from step 1                |
| `timestamps` |        |                             |                                             |

**Migration**: `[date]_add_phone_service_to_contact_entries_table.php`

```php
$table->string('phone', 30)->nullable()->after('email');
$table->string('service', 255)->nullable()->after('phone');
$table->string('email', 255)->nullable()->change();
```

**`StoreContactEntryRequest` validation update**:

```php
'name'    => ['required', 'string', 'max:255'],
'email'   => ['nullable', 'email', 'max:255'],
'phone'   => ['nullable', 'string', 'max:30'],
'message' => ['required', 'string', 'max:5000'],
'service' => ['nullable', 'string', 'max:255'],
```

---

### homepage_sections _(legacy — drop migration, held)_

Migration file: `[date]_drop_homepage_sections_table.php`

- Drops `homepage_sections` table
- Comment: "Superseded by `section_component` column on `post_categories`. Run manually during maintenance window."
- **NOT auto-run** — excluded from CI/CD deploy migrations.

---

## Frontend Entities (Angular — `C:\WORK\sbc-clean\src`)

### SiteContent _(extended)_

Changes to `src/app/data/content.ts`:

#### `AboutContent` — add `pills` field

```typescript
export interface AboutContent {
    sectionTitle: string;
    title: string;
    description: string;
    stats: Stat[];
    pills: string[]; // NEW — about capability bullets
}
```

Default fallback in `SITE_CONTENT`:

```typescript
about: {
  // ... existing fields unchanged ...
  pills: [
    'كوادر فنية مدربة',
    'معدات وتقنيات حديثة',
    'مواد آمنة ومعتمدة',
    'ضمان جودة الخدمة',
  ],
}
```

#### `LineOfWorkItem` — new interface + `SiteContent.lineOfWork` field

```typescript
export interface LineOfWorkItem {
    title: string;
    subtitle: string;
    icon: string;
    imageUrl: string | null;
}

export interface SiteContent {
    // ... existing fields unchanged ...
    lineOfWork: LineOfWorkItem[]; // NEW
}
```

Default in `SITE_CONTENT`:

```typescript
lineOfWork: [
  { title: 'تنظيف احترافي', subtitle: 'منازل وشركات ومؤسسات', icon: 'fa-solid fa-wand-magic-sparkles', imageUrl: null },
  { title: 'جلي ولمعة',     subtitle: 'رخام وسيراميك وبلاط',  icon: 'fa-regular fa-gem',              imageUrl: null },
  { title: 'تنظيف واجهات', subtitle: 'زجاج ومباني وأسطح',    icon: 'fa-regular fa-building',          imageUrl: null },
  { title: 'تعقيم خزانات', subtitle: 'مياه نظيفة وآمنة',     icon: 'fa-solid fa-droplet',             imageUrl: null },
],
```

---

### ApiPost _(extended)_

Changes to `src/app/models/api.models.ts`:

```typescript
export interface ApiContentBlock {
    type: "markdown" | "heading" | "figure" | "quote" | "list" | string;
    data: Record<string, unknown> & {
        image_url?: string | null; // injected by PostResource for figure type
    };
}

export interface ApiPost {
    // ... existing fields unchanged ...
    content: ApiContentBlock[] | string | null; // changed from: content: string
    image_url: string | null; // NEW — flat absolute URL
}
```

---

### ApiCategory _(extended)_

```typescript
export interface ApiCategory {
    // ... existing fields unchanged ...
    image_url: string | null; // NEW — absolute URL (was: image: string | null)
}
```

---

### Angular Routes

`src/app/app.routes.ts` additions:

| Path                    | Component             | File                                              |
| ----------------------- | --------------------- | ------------------------------------------------- |
| `/blog/:category`       | `PostListComponent`   | `pages/blog/post-list.component.ts`               |
| `/blog/:category/:slug` | `PostDetailComponent` | `pages/blog/post-detail/post-detail.component.ts` |
| `/page/:slug`           | `PageComponent`       | `pages/page/page.component.ts`                    |
| `/login`                | `LoginComponent`      | `pages/auth/login.component.ts`                   |
| `/register`             | `RegisterComponent`   | `pages/auth/register.component.ts`                |

All use `loadComponent` (lazy). All new components use `ChangeDetectionStrategy.OnPush`.

---

## Schema Change Summary

| System  | Entity                 | Change Type   | Description                                                                  |
| ------- | ---------------------- | ------------- | ---------------------------------------------------------------------------- |
| Laravel | `CrmSetting`           | Method added  | `getAllFlat(): array`                                                        |
| Laravel | `PostResource`         | Field added   | `image_url: string\|null`                                                    |
| Laravel | `PostResource`         | Field changed | `content` — figure blocks get `image_url` injected                           |
| Laravel | `PostCategoryResource` | Field added   | `image_url: string\|null`                                                    |
| Laravel | `ContactEntry`         | Schema change | Add `phone`, `service`; make `email` nullable                                |
| Laravel | `CrmSettingSeeder`     | New file      | 24 Arabic defaults + `about_pills`, upsert-safe                              |
| Laravel | `config/cors.php`      | Updated       | Multi-origin CSV env, default `localhost:4200`, `supports_credentials: true` |
| Laravel | `routes/api.php`       | Updated       | `throttle:100,1` on reads, `throttle:30,1` on contact                        |
| Angular | `SiteContent`          | Field added   | `lineOfWork: LineOfWorkItem[]`                                               |
| Angular | `AboutContent`         | Field added   | `pills: string[]`                                                            |
| Angular | `ApiPost`              | Field added   | `image_url: string\|null`; `content` typed as block array                    |
| Angular | `ApiCategory`          | Field added   | `image_url: string\|null`                                                    |
| Angular | `app.routes.ts`        | Routes added  | 5 new lazy-loaded routes                                                     |
| Angular | `environment.ts`       | Value updated | Production `apiUrl` placeholder replaced                                     |
| Angular | `hero3/`               | Deleted       | Hard delete — no references in build                                         |
