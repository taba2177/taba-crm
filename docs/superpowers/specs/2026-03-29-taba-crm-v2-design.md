# taba/crm v2 — Design Specification

## Overview

Redesign taba/crm from a developer-centric Filament admin panel into a **polymorphic, dual-panel CMS package** that works for any kind of website. The package already powers 25+ section types through PostCategory/Post models; v2 wraps this in a component registry, adds a simplified client panel, and introduces API v2 — all without breaking the existing data schema.

**Target:** Filament 5, PHP 8.2+, Laravel 10/11/12

## Problem Statement

Current taba/crm v1 exposes the full Filament admin dashboard to clients (non-technical business owners in Saudi Arabia). They find it overwhelming. The developer builds the site, but the client can't confidently edit text, images, or contact info without risking the layout.

## Goals

1. **Client simplicity** — Non-technical users edit their website sections through a focused, Arabic-first dashboard
2. **Developer flexibility** — Admin panel retains full power; new section types require only adding a PHP class
3. **Universal fit** — The package adapts to any website (restaurant, clinic, law firm, portfolio, e-commerce landing page) through polymorphic components
4. **API-first** — Component-aware API v2 for Angular/React headless frontends
5. **Zero data migration** — Existing PostCategory + Post + CrmSetting schema is preserved

## Non-Goals

- Multi-tenant SaaS (each install = one site)
- Frontend theme engine (Blade templates remain project-customizable via Laravel view overrides)
- Full e-commerce (ServicePayments stays as-is)
- AI features in v2.0 (deferred to v2.1+)

---

## Architecture

### Dual Filament Panels

Two Filament panels share the same database. No data duplication.

**Admin Panel** (`/admin` — `CrmPlugin`)
- Full CRUD: Posts, PostCategories, Pages, Users, ContactEntries, ServicePayments, CrmSettings
- Component picker on PostCategory (dropdown of registered components)
- AI pages: GenerateSiteFromAI, GenerateComponentsFromAI
- Role/permission management (filament-shield)
- Activity log, exception viewer
- Unchanged from v1 in functionality

**Client Panel** (`/dashboard` — `CrmClientPlugin`)
- **Dynamic sidebar navigation** — Each active PostCategory appears as a direct navigation item with the component's real icon. Posts appear as sub-navigation items beneath their category. This gives clients a clear tree: category → posts.
- Navigation structure:
  - **Dashboard** — Welcome stats overview
  - **Each PostCategory** — Auto-generated nav item per active category, using `SectionComponent::icon()` as the nav icon and `PostCategory::name` as the label. For SINGLE-layout sections, clicking goes directly to the edit form. For LIST-layout sections, clicking shows a list of posts with create/edit/delete/reorder. Posts appear as sub-nav items when there are multiple.
  - **الصفحات** (Pages) — Simple page list with inline title/content editing
  - **الرسائل** (Messages) — Read-only contact entry list with unread count badge
  - **الإعدادات** (Settings) — Business info: phone, email, address, social links, logo
  - **حسابي** (Profile) — Name, email, password
- **Sorting & ordering**: Drag-to-reorder for both categories (admin) and posts (client). Sortable columns in list views. Category order controls nav order.
- **Refined forms**: Create/edit forms are grouped into logical sections (basic info, media, SEO, extras). View pages show clean read-only layouts. Edit forms use proper placeholder text, help text, and Arabic labels.
- Arabic-first, RTL layout
- Filament's built-in auth with a separate `client` guard
- No raw model CRUD exposed — every form is generated from the component registry

### Polymorphic Component System

The core architectural change. Each website section type is a **self-describing PHP class** implementing the `SectionComponent` interface.

#### SectionComponent Interface

```php
interface SectionComponent
{
    // Identity
    public function key(): string;           // 'hero', 'cards', 'faq'
    public function label(): array;          // ['ar' => 'القسم الرئيسي', 'en' => 'Hero']
    public function icon(): string;          // 'heroicon-o-sparkles'
    public function description(): array;    // Short explanation of this section type

    // Layout
    public function layout(): SectionLayout; // SINGLE (one block) | LIST (repeatable items)

    // Client edit form — returns Filament form schema arrays
    public function sectionFields(): array;  // Fields for section header (→ PostCategory)
    public function itemFields(): array;     // Fields per item (→ Post) — empty for SINGLE

    // Rendering
    public function bladeView(): string;     // 'crm::components.homepage.hero'
    public function toApi(PostCategory $section): array; // JSON shape for API v2

    // Validation & constraints
    public function rules(): array;          // Laravel validation rules
    public function maxItems(): ?int;        // null = unlimited
}

enum SectionLayout { case SINGLE; case LIST; }
```

#### Layout Types

- **SINGLE** — Section has only header-level data (stored in PostCategory fields + metadata). Examples: Hero, Contact, Map, CTA banner.
- **LIST** — Section has a header plus repeatable items (PostCategory = header, Posts = items). Examples: Services, FAQ, Portfolio, Team, Prices, Reviews.

#### Data Mapping

No new tables. Component fields map to existing columns:

| Component Field | Stored In | Column |
|---|---|---|
| Section title | PostCategory | `name` (translatable) |
| Section subtitle | PostCategory | `description` (translatable) |
| Section extras | PostCategory | `subtitle` (translatable JSON used as metadata) |
| Item title | Post | `title` (translatable) |
| Item body | Post | `content` (translatable) |
| Item image | Post | `image_id` (FK to media) |
| Item extras (icon, price, cta_url, rating, etc.) | Post | `metadata` (translatable JSON) |
| Item order | Post | `order` |
| Section order | PostCategory | `order` |
| Section visibility | PostCategory | `is_active` |
| Component type | PostCategory | `section_component` |

The `metadata` JSON column on Post is the polymorphic storage — each component defines which keys it uses via `itemFields()`.

#### ComponentRegistry

Auto-discovers and resolves component classes.

```php
class ComponentRegistry
{
    // Registration (3 methods)
    public static function register(string $componentClass): void;    // Manual
    public static function discoverIn(string $namespace): void;       // Auto-discover
    public static function fromConfig(array $components): void;       // Config-driven

    // Resolution
    public static function resolve(string $key): SectionComponent;    // key → instance
    public static function all(): Collection;                         // All registered
    public static function forSelect(): array;                        // key => label (for admin dropdown)

    // Querying
    public static function has(string $key): bool;
    public static function keys(): array;
}
```

**Discovery order:**
1. Package built-in components (`Taba\Crm\Components\Sections\*`)
2. App-level components (developer registers in their ServiceProvider)
3. Config-driven (`crm.extra_components` array)

Later registrations override earlier ones (same key = override), allowing developers to replace built-in components.

#### Built-in Components (25+, 1:1 with existing Blade templates)

Each existing `src/views/components/homepage/*.blade.php` gets a corresponding SectionComponent class:

Each component uses a specific, meaningful Heroicon:

| Component | Icon | Layout |
|---|---|---|
| HeroComponent | `heroicon-o-sparkles` | SINGLE |
| Hero2Component | `heroicon-o-fire` | SINGLE |
| Hero3Component | `heroicon-o-bolt` | SINGLE |
| CardsComponent | `heroicon-o-squares-2x2` | LIST |
| Cards2Component | `heroicon-o-rectangle-group` | LIST |
| ServicesGridComponent | `heroicon-o-wrench-screwdriver` | LIST |
| ServicesCardsComponent | `heroicon-o-briefcase` | LIST |
| ServicesCarouselComponent | `heroicon-o-arrows-right-left` | LIST |
| ServicesSwiperComponent | `heroicon-o-arrow-path` | LIST |
| ServicesListComponent | `heroicon-o-clipboard-document-list` | LIST |
| ServicesFeaturesComponent | `heroicon-o-check-badge` | LIST |
| FaqComponent | `heroicon-o-question-mark-circle` | LIST |
| Faq2Component | `heroicon-o-chat-bubble-left-right` | LIST |
| ContactComponent | `heroicon-o-envelope` | SINGLE |
| Contact2Component | `heroicon-o-phone` | SINGLE |
| PortfolioComponent | `heroicon-o-photo` | LIST |
| ReviewsCarouselComponent | `heroicon-o-star` | LIST |
| PricesComponent | `heroicon-o-currency-dollar` | LIST |
| Prices2Component | `heroicon-o-banknotes` | LIST |
| Prices3Component | `heroicon-o-receipt-percent` | LIST |
| GalleryComponent | `heroicon-o-camera` | LIST |
| TeamComponent | `heroicon-o-user-group` | LIST |
| CounterComponent | `heroicon-o-chart-bar` | LIST |
| TimelineComponent | `heroicon-o-clock` | LIST |
| BrandsComponent | `heroicon-o-building-storefront` | LIST |
| MapComponent | `heroicon-o-map-pin` | SINGLE |
| CtaComponent | `heroicon-o-megaphone` | SINGLE |
| FeaturesComponent | `heroicon-o-light-bulb` | LIST |
| TestimonialsComponent | `heroicon-o-chat-bubble-bottom-center-text` | LIST |
| AboutUsComponent | `heroicon-o-information-circle` | SINGLE |
| LatestNewsComponent | `heroicon-o-newspaper` | LIST |
| LeftRightImageComponent | `heroicon-o-arrows-pointing-out` | SINGLE |
| SingleViewCallActionComponent | `heroicon-o-rocket-launch` | SINGLE |
| SingleViewPricingPackagesComponent | `heroicon-o-tag` | LIST |
| SingleViewVisionMissionComponent | `heroicon-o-eye` | SINGLE |
| SingleViewWhyChooseUsComponent | `heroicon-o-hand-thumb-up` | SINGLE |
| DefaultComponent | `heroicon-o-rectangle-stack` | SINGLE |

#### Field Types

Components build their forms using these standard field types:

| Type | Description | Supports `.translatable()` |
|---|---|---|
| `text` | Single-line text input | Yes |
| `textarea` | Multi-line text input | Yes |
| `richtext` | TipTap rich text editor | Yes |
| `image` | Curator media picker | No |
| `url` | URL input with validation | No |
| `icon` | Icon picker (Heroicons) | No |
| `number` | Numeric input | No |
| `toggle` | Boolean on/off | No |
| `select` | Dropdown with options | No |
| `color` | Color picker | No |
| `date` | Date picker | No |
| `location` | Lat/lng coordinate input | No |

Field types are built by `FieldFactory` which returns Filament form components. Extensible via `FieldFactory::extend('type', callback)`.

### Dynamic Edit Form (Client Panel)

### Navigation-Driven Section Editing

Each active PostCategory is a **direct sidebar navigation item**. The sidebar dynamically builds from the database:

1. Each active PostCategory becomes a nav item with its component's icon and the category name as label
2. For **SINGLE** layout: clicking the nav item opens the section edit form directly (fields from `sectionFields()`)
3. For **LIST** layout: clicking the nav item opens a list/table of Posts. Create/edit/delete/reorder are available. Each post has its own edit form (fields from `itemFields()`). The section header fields appear at the top of the list page as an editable card.
4. Navigation order matches `PostCategory::order` — reorderable from admin panel
5. **Smart navigation for single-item categories:** When a LIST-layout category has only one Post, the nav item links directly to the edit form rather than showing a list of one.

### Form Refinements

**Create forms:**
- Grouped into logical fieldset sections (basic info, media, SEO/metadata, extras)
- Arabic placeholder text and help text
- Translatable fields show AR/EN tabs side by side
- Image fields show preview thumbnails
- Required fields clearly marked

**Edit forms:**
- Same layout as create, pre-populated with existing data
- Save + continue editing option
- Unsaved changes warning
- Sidebar shows publishing status, dates, category info

**View pages (admin):**
- Clean read-only layout with all fields displayed
- Image fields show actual images
- Translatable fields show all language values
- Quick action buttons (edit, delete, duplicate)

**Sorting & ordering:**
- Drag-to-reorder in list views for both categories and posts
- Sortable table columns (date, title, order, status)
- Bulk actions (publish, unpublish, delete) in admin panel

The client never sees raw database fields. They see contextual labels like "عنوان الخدمة" (Service Title) instead of "Post Title".

### API v2

New endpoints alongside v1 (v1 routes preserved for backward compatibility).

**Public Routes** (no auth):

```
GET  /api/v2/sections              — All active sections, ordered, with items
GET  /api/v2/sections/{id}         — Single section with items
GET  /api/v2/settings              — Public site settings (phone, email, social, logo)
GET  /api/v2/pages/{slug}          — Page by slug
GET  /api/v2/menus                 — Navigation menus
POST /api/v2/contact               — Submit contact form
GET  /api/v2/components            — Available component types (for frontend routing)
```

**Authenticated Routes** (Sanctum):

```
GET    /api/v2/admin/sections      — All sections (including inactive)
POST   /api/v2/admin/sections      — Create section
PUT    /api/v2/admin/sections/{id} — Update section
DELETE /api/v2/admin/sections/{id} — Delete section
// ... mirrors admin panel CRUD
```

**Section JSON shape** (returned by each component's `toApi()`):

```json
{
  "id": 1,
  "component": "services-grid",
  "order": 2,
  "is_active": true,
  "title": {"ar": "خدماتنا", "en": "Our Services"},
  "subtitle": {"ar": "...", "en": "..."},
  "fields": {},
  "items": [
    {
      "id": 10,
      "order": 1,
      "title": {"ar": "تصميم مواقع", "en": "Web Design"},
      "content": {"ar": "...", "en": "..."},
      "image": "/storage/...",
      "fields": {
        "icon": "heroicon-o-globe"
      }
    }
  ]
}
```

Angular/React frontends use `component` key to select the right rendering component. The `fields` object contains component-specific extras from metadata.

---

## Package Structure (v2)

```
src/
├── CrmPlugin.php                  # Admin panel plugin (existing, refactored namespace)
├── CrmClientPlugin.php            # Client panel plugin (NEW)
├── CrmServiceProvider.php         # Service provider (extended)
│
├── Components/                    # NEW — Polymorphic component system
│   ├── Contracts/
│   │   ├── SectionComponent.php   # Interface
│   │   └── SectionLayout.php      # Enum
│   ├── Registry/
│   │   └── ComponentRegistry.php  # Discovery + resolution
│   ├── Fields/
│   │   └── FieldFactory.php       # Field type builders
│   ├── Sections/                  # Built-in components (25+)
│   │   ├── HeroComponent.php
│   │   ├── CardsComponent.php
│   │   └── ...
│   └── Concerns/
│       ├── HasTranslatableFields.php
│       └── HasItemLayout.php
│
├── Filament/
│   ├── Admin/                     # Existing resources, moved namespace
│   │   ├── Resources/
│   │   ├── Pages/
│   │   └── Widgets/
│   └── Client/                    # NEW — Client panel
│       ├── Resources/
│       │   ├── SectionResource.php    # Dynamic per-category resource
│       │   └── PageResource.php
│       ├── Pages/
│       │   ├── Dashboard.php          # Welcome stats overview
│       │   ├── EditSection.php        # SINGLE-layout section edit
│       │   ├── SiteSettings.php
│       │   └── Messages.php
│       ├── Widgets/
│       │   └── WelcomeWidget.php
│       └── Navigation/
│           └── DynamicCategoryNavigation.php  # Builds nav from DB
│
├── Http/
│   └── Controllers/Api/
│       └── V2/                    # NEW — API v2
│           ├── SectionController.php
│           ├── SettingController.php
│           ├── PageController.php
│           ├── MenuController.php
│           ├── ContactController.php
│           └── ComponentController.php
│
├── Models/                        # Unchanged
├── Services/                      # Existing + extended
├── views/
│   ├── components/homepage/       # Existing Blade templates
│   └── client/                    # NEW — Client panel views
└── ...
```

---

## Localization

- Arabic-first: AR is the default locale, EN is optional
- All component text fields using `.translatable()` produce AR/EN inputs
- Client panel UI labels in Arabic by default (using existing lang files)
- Config: `crm.locales` controls available locales (default: `['ar', 'en']`)
- RTL: Client panel forces `dir="rtl"` layout

## Authentication & Authorization

- Admin panel: existing Filament auth + filament-shield roles/permissions
- Client panel: separate Filament panel with `client` guard
- Developer assigns users to panels during site setup
- Client users see only `CrmClientPlugin` panel — no access to admin resources
- API v2 authenticated routes: Sanctum tokens (same as v1)

## Migration Path (v1 → v2)

1. **Filament 3 → 5**: Follow Filament's official upgrade guide
2. **Namespace reorganization**: Move existing resources to `Filament/Admin/` — functionality unchanged
3. **Component class creation**: Each existing Blade template gets a SectionComponent class (1:1 mapping). Existing `section_component` values on PostCategories match the component keys
4. **Client panel addition**: New panel reads same DB — no data migration
5. **API v2 routes**: Added alongside v1 — both coexist
6. **No breaking changes to existing data**: All PostCategory, Post, CrmSetting records work as-is

## Release Strategy

- **v2.0**: Filament 5 + Component Registry + Client Panel + API v2
- **v2.1**: AI-powered editing (Gemini integration in client panel)
- **v2.2**: AI site templates, developer tooling improvements

## Extensibility Summary

| Extension Point | How |
|---|---|
| New section type | Add a PHP class implementing `SectionComponent` |
| Custom field type | `FieldFactory::extend('type', callback)` |
| Override built-in component | Register a class with the same `key()` |
| Custom API transform | Override `toApi()` in your component class |
| Custom Blade template | Laravel view override: `vendor:publish` + edit |
| Extra locales | `crm.locales` config array |
| Middleware | `crm.middleware` config (per-panel) |
| Client panel branding | Filament panel configuration in `CrmClientPlugin` |

## Testing Strategy

- **Unit tests**: ComponentRegistry resolution, FieldFactory output, SectionComponent interface compliance
- **Feature tests**: Client panel section CRUD, API v2 endpoints, component discovery
- **Integration tests**: Full flow — register component → appears in admin picker → editable in client panel → returned in API
- **Existing tests**: v1 tests continue passing (backward compat)
