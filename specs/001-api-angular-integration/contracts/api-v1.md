# API Contract: `/api/v1` — taba/crm REST API

**Version**: v1 (no breaking changes in this feature)  
**Base URL**: `{apiUrl}/api/v1` — configured in `environment.ts` / `environment.development.ts`  
**Auth**: Laravel Sanctum Bearer token (`Authorization: Bearer <token>`)  
**Required headers** (all requests):

```
Accept: application/json
Accept-Language: ar | en
```

---

## Standard Response Envelope

### Success

```json
{
  "success": true,
  "message": "Success",
  "data": { ... }
}
```

### Paginated Success

```json
{
  "success": true,
  "message": "Success",
  "data": [ ... ],
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 72,
    "from": 1,
    "to": 15
  }
}
```

### Error

```json
{
    "success": false,
    "message": "Validation failed",
    "errors": { "name": ["The name field is required."] }
}
```

---

## Rate Limits

| Endpoint Group            | Limit              | Header on 429            |
| ------------------------- | ------------------ | ------------------------ |
| All public read endpoints | 100 req / min / IP | `Retry-After: <seconds>` |
| `POST /api/v1/contact`    | 30 req / min / IP  | `Retry-After: <seconds>` |

---

## CORS Policy

```
Access-Control-Allow-Origin: http://localhost:4200 [+ production domains from env]
Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS
Access-Control-Allow-Headers: Accept, Accept-Language, Authorization, Content-Language, Content-Type, X-Requested-With, X-XSRF-TOKEN
Access-Control-Allow-Credentials: true
Access-Control-Max-Age: 86400
```

Configure allowed origins via environment variable:

```
CRM_API_CORS_ORIGINS=http://localhost:4200,https://sbc-clean.com
```

---

## Public Endpoints (no auth required)

### `GET /api/v1/init`

Bootstrap endpoint. Returns all data needed for Angular app initialization in a single request.

**Response `data`**:

```json
{
  "settings": {
    "general": { "site_name": "SBC كلين", "site_logo": "assets/logo.svg" },
    "hero":    { "hero_title": "...", "hero_description": "...", "hero_cta_text": "...", "hero_cta_link": "..." },
    "about":   { "about_title": "...", "about_description": "...", "about_pills": ["...", "..."] },
    "features":{ "features_title": "..." },
    "services":{ "services_title": "..." },
    "branches":{ "branches_title": "...", "branches_cities_count": "5" },
    "cta":     { "cta_title": "...", "cta_subtitle": "...", "cta_book_link": "..." },
    "contact": { "contact_email": "...", "contact_phone": "...", "contact_whatsapp": "..." },
    "footer":  { "footer_address": "...", "footer_links": "[]" },
    "social":  { "social_facebook": "", "social_twitter": "", "social_instagram": "", "social_linkedin": "" }
  },
  "menus": [ { "id": 1, "name": "main", "items": [ ... ] } ],
  "navigation": [ { "id": 1, "name": "الرئيسية", "slug": "homepage", "register_in_header": true } ],
  "locale": "ar",
  "locales": ["ar", "en"],
  "app_name": "TABA CRM"
}
```

> **Note**: `settings` is grouped by `group` key (nested). `ContentService.setting()` iterates groups to find any key. This structure is unchanged.

---

### `GET /api/v1/home`

Homepage data. Featured posts and reviews.

**Response `data`**:

```json
{
  "categories": [ PostCategory ],
  "featured_posts": [ Post ],
  "reviews": [ Review ]
}
```

---

### `GET /api/v1/settings/grouped`

Flat key-value settings map. **Changed in this feature** — was nested, now flat.

**Response `data`**:

```json
{
  "site_name": "SBC كلين",
  "hero_title": "خدمات تنظيف مباني احترافية",
  "about_title": "...",
  ...
}
```

All 24 keys from FR-001 are guaranteed present after `CrmSettingSeeder` is run.

---

### `GET /api/v1/posts`

**Query params**:
| Param | Type | Description |
|---|---|---|
| `category` | string | Filter by category slug |
| `tag` | string | Filter by tag slug |
| `search` | string | Full-text search in title/content |
| `show_in_home` | boolean | |
| `sort_by` | string | One of: `published_at`, `created_at`, `title`, `order`, `id` |
| `sort_dir` | `asc` \| `desc` | |
| `page` | int | |
| `per_page` | int | Default 15 |
| `include` | string | Comma-separated: `postCategory,user,tags,image` |

**Post object** (single item in paginated `data[]`):

```json
{
  "id": 1,
  "title": "عنوان المقال",
  "slug": "article-slug",
  "content": [
    { "type": "markdown", "data": { "text": "..." } },
    { "type": "figure",   "data": { "image_id": 42, "caption": "...", "image_url": "https://..." } },
    { "type": "heading",  "data": { "level": 2, "text": "..." } },
    { "type": "quote",    "data": { "text": "...", "author": "..." } },
    { "type": "list",     "data": { "items": ["..."], "ordered": false } }
  ],
  "excerpt": "...",
  "icon": "fa-solid fa-check",
  "image_url": "https://storage.example.com/media/image.jpg",
  "image": { "id": 42, "url": "https://...", "alt": "...", "width": 1200, "height": 800 },
  "show_in_home": true,
  "is_published": true,
  "published_at": "2026-01-15T10:00:00+00:00",
  "category": { ... },
  "tags": [],
  "created_at": "2026-01-01T00:00:00+00:00",
  "updated_at": "2026-01-01T00:00:00+00:00"
}
```

> **Key**: `image_url` is a **top-level flat string** (new in this feature). `image` nested object is preserved for backward compatibility.

---

### `GET /api/v1/posts/{slug}`

Single published post by slug. Returns same Post object as above.  
Returns `404` if slug not found or post not published.

---

### `GET /api/v1/categories`

All categories.

**Category object**:

```json
{
    "id": 1,
    "name": "نطاق العمل",
    "slug": "line-of-work",
    "description": "...",
    "subtitle": "...",
    "image": null,
    "image_url": "https://storage.example.com/categories/image.jpg",
    "section_component": "line-of-work",
    "register_in_header": true,
    "order": 1,
    "created_at": "...",
    "updated_at": "..."
}
```

> **Key**: `image_url` is new (resolved absolute URL). `image` raw field preserved.

---

### `GET /api/v1/categories/{slug}/posts`

Paginated posts for a specific category slug. Uses same pagination as `/posts`.

---

### `GET /api/v1/pages/{slug}`

Single CMS page by slug.

```json
{
    "id": 1,
    "title": "من نحن",
    "slug": "about",
    "content": "...",
    "meta_title": null,
    "meta_description": null,
    "is_published": true,
    "created_at": "...",
    "updated_at": "..."
}
```

---

### `POST /api/v1/contact`

Submit a contact/booking entry. Rate limited: **30 req/min/IP**.

**Request body**:

```json
{
    "name": "محمد عبدالله", // required, string, max:255
    "email": "user@example.com", // nullable, email
    "phone": "0512345678", // nullable, string, max:30
    "message": "أريد خدمة تنظيف...", // required, string, max:5000
    "service": "تنظيف فلل" // nullable, string, max:255
}
```

**Response** (201 Created):

```json
{
    "success": true,
    "message": "Contact message sent successfully",
    "data": {
        "id": 23,
        "name": "محمد عبدالله",
        "email": null,
        "phone": "0512345678",
        "message": "...",
        "service": "تنظيف فلل",
        "created_at": "..."
    }
}
```

**Error responses**:

- `422 Unprocessable Entity` — validation failed, `errors` map of field → messages array
- `429 Too Many Requests` — rate limit exceeded, `Retry-After` header present

---

## Auth Endpoints (public)

### `POST /api/v1/auth/login`

```json
// Request
{ "email": "admin@example.com", "password": "..." }

// Response 200
{
  "data": {
    "user": { "id": 1, "name": "Admin", "email": "admin@example.com", "roles": ["admin"] },
    "token": "1|abc...",
    "token_type": "Bearer"
  }
}
```

- `401` on invalid credentials.

### `POST /api/v1/auth/register`

```json
// Request
{
    "name": "...",
    "email": "...",
    "password": "...",
    "password_confirmation": "..."
}

// Response 201 — same shape as login
```

---

## Protected Endpoints (Bearer token required)

### `POST /api/v1/auth/logout`

Revokes current token.

### `GET /api/v1/auth/me`

Returns current authenticated user.

---

## Breaking Changes

None. All additions are:

- New fields (`image_url`, `phone`, `service`) added to existing response objects
- `/settings/grouped` shape changed from nested→flat — this endpoint was previously broken for frontend use anyway (ContentService did not use it directly)
