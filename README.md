<div align="center">
  <img src="https://raw.githubusercontent.com/taba-center/taba-logo/main/TABA-LOGO-2022-horizontal.png" alt="Taba CRM Logo" width="400">
  <h1>Taba CRM Package for Laravel</h1>
  <p>
    A complete, "plug-and-play" CRM panel for Laravel, powered by Filament.
  </p>
  <p>
    <a href="https://packagist.org/packages/taba/crm"><img src="https://img.shields.io/packagist/v/taba/crm.svg?style=flat-square" alt="Latest Version on Packagist"></a>
    <a href="https://github.com/taba-center/crm/blob/main/LICENSE.md"><img src="https://img.shields.io/packagist/l/taba/crm.svg?style=flat-square" alt="License"></a>
    <a href="https://packagist.org/packages/taba/crm"><img src="https://img.shields.io/packagist/dt/taba/crm.svg?style=flat-square" alt="Total Downloads"></a>
  </p>
</div>

---

**Taba CRM** is a complete, reusable package that provides a full-featured CRM panel. It includes resources for managing posts, categories, and users, and comes pre-configured with essential plugins for a rich user experience.

## ✨ Features

- **Resource Management:** Pre-built Filament resources for Posts, Categories, and Users.
- **Plugin Ecosystem:** Integrated with popular plugins like Breezy (Profiles), Curator (Media), and Peek (Previews).
- **Simple Installation:** Get up and running with a single custom Artisan command.
- **Customizable:** Publishable assets (config, views, etc.) allow for easy customization.

## 📋 Prerequisites

Before you begin, ensure you have a fresh Laravel project with the following configured:
- Laravel 10+
- Filament 3+ installed (`php artisan filament:install --panels`)
- Database connection set up in your `.env` file.

## 🚀 Installation

Getting started is simple. Follow these steps to integrate Taba CRM into your project.

### Step 1: Require with Composer

First, pull the package into your project.

```bash
composer require taba/crm
```

### Step 2: Run the Install Command

Next, run our custom installation command. This smart command handles all the necessary setup for the package and its dependencies, including:
- Detecting and removing Tailwind CSS v4 if present (converting to v3)
- Automatically converting your `app.css` from Tailwind v4 to v3 syntax
- Removing `@tailwindcss/vite` plugin if present
- Configuring Tailwind, Vite, and PostCSS
- Publishing assets and running migrations

```bash
php artisan crm:install
```

> **Note:** The install command is smart and non-destructive. It will:
> - Detect if you're using Tailwind CSS v4 and automatically convert your files to v3
> - Skip modifications if files are already properly configured
> - Only add what's needed without breaking existing configurations

### Step 3: Register the Plugin

To activate the CRM panel, you need to register the `CrmPlugin` in your project's `AdminPanelProvider`.

Open `app/Providers/Filament/AdminPanelProvider.php` and add the plugin to the `plugins()` array:

```php
// app/Providers/Filament/AdminPanelProvider.php

use Taba\Crm\CrmPlugin; // 👈 Import the plugin at the top

public function panel(Panel $panel): Panel
{
    return $panel
        // ... other panel settings
        ->plugins([
            new CrmPlugin(), // 👈 Add this line
        ]);
}
```

### Step 4: Compile Frontend Assets

Finally, compile your project's frontend assets to ensure the admin panel's styles and scripts are loaded correctly.

```bash
npm install
npm run dev
```

And you're done! 🎉 You can now visit `/admin` and log in to access your new CRM panel.

---

---

## 🅰️ Angular SPA Frontend

`crm:install` automatically publishes the Angular 21 SPA frontend to the `frontend/` directory in your Laravel root and wires up the Angular build output to be served as the public website.

### What gets installed

| Step | What happens |
|------|--------------|
| `Publishing Angular frontend` | Copies `packages/taba/crm/frontend/` → `frontend/` (skipped if already exists) |
| `Installing Angular npm packages` | Runs `npm install` inside `frontend/` |
| `Building Angular frontend` | Runs `npm run build` inside `frontend/` — output lands in `public/browser/` |

To skip all frontend steps (server-side only):

```bash
php artisan crm:install --skip-frontend
```

### CSS Design Tokens

Edit `frontend/src/styles/tokens.scss` to customise brand colours, fonts, and spacing:

```scss
// frontend/src/styles/tokens.scss
:root {
  --clr-primary: #0d6efd;
  --clr-accent:  #ff6b35;
  --font-heading: 'Cairo', sans-serif;
}
```

---

## 🔍 SEO & AI Discoverability

### Middleware

| Alias | Class | Purpose |
|-------|-------|---------|
| `crm.seo` | `InjectSeoForBots` | Detects crawlers and injects full `<meta>` + Open Graph tags into SPA HTML |
| `crm.discovery` | `AddDiscoveryHeaders` | Adds `Link:` headers pointing to `/sitemap.xml`, OpenAPI spec, and API catalog |

Apply them in your routes:

```php
Route::middleware(['crm.seo', 'crm.discovery'])->group(function () {
    Route::get('/{any}', fn() => view('app'))->where('any', '.*');
});
```

### `llms.txt`

`crm:install` writes `public/llms.txt` — a human-and-AI-readable Markdown summary of the site and its API, following the emerging [llms.txt](https://llmstxt.org) convention. Regenerate at any time:

```bash
php artisan crm:install --skip-frontend
```

### `robots.txt`

`crm:install` also creates or updates `public/robots.txt` with explicit `Allow` directives for AI crawlers (GPTBot, Google-Extended, etc.) and a `Sitemap:` pointer.

### Markdown content negotiation

The posts API supports `Accept: text/markdown` for AI agents that prefer raw Markdown over JSON:

```bash
curl -H "Accept: text/markdown" https://yoursite.com/api/v1/posts/my-post-slug
```

Response headers include `X-Post-Title` and `X-Post-Slug`.

---

## 🏗️ v2 — Component System & Client Panel

### Polymorphic Section Components

v2 introduces a **polymorphic component system** where each section type (Hero, FAQ, Services Grid, etc.) is a self-contained PHP class implementing `SectionComponent`. Each component defines its own form fields, validation rules, API output, and blade view.

**36 built-in components** are auto-discovered and ready to use. You can also register custom components.

#### Creating a Custom Component

```php
use Taba\Crm\Components\Contracts\SectionComponent;
use Taba\Crm\Components\Contracts\SectionLayout;
use Taba\Crm\Models\PostCategory;

class MyComponent implements SectionComponent
{
    public function key(): string { return 'my-component'; }
    public function label(): array { return ['ar' => 'المكون', 'en' => 'My Component']; }
    public function icon(): string { return 'heroicon-o-star'; }
    public function description(): array { return ['ar' => 'وصف المكون', 'en' => 'Component description']; }
    public function layout(): SectionLayout { return SectionLayout::SINGLE; }

    public function sectionFields(): array
    {
        return [
            \Taba\Crm\Components\Fields\FieldFactory::make('text', 'heading', ['ar' => 'العنوان', 'en' => 'Heading']),
        ];
    }

    public function itemFields(): array { return []; }
    public function bladeView(): string { return 'components.homepage.my-component'; }

    public function toApi(PostCategory $section): array
    {
        return [
            'id' => $section->id,
            'component' => $this->key(),
            'order' => $section->order,
            'title' => $section->getTranslations('name'),
        ];
    }

    public function rules(): array { return ['name.ar' => 'required|string']; }
    public function maxItems(): ?int { return null; }
}
```

#### Registering Custom Components

Add your component class to `config/crm.php`:

```php
'extra_components' => [
    \App\Components\MyComponent::class,
],
```

### Dual-Panel Architecture

v2 introduces a **Client Panel** (`/dashboard`) alongside the existing Admin Panel (`/admin`):

- **Admin Panel** (`CrmPlugin`): Full CRM management at `/admin`
- **Client Panel** (`CrmClientPlugin`): Simplified content editing at `/dashboard`

Register both panels in your `AdminPanelProvider`:

```php
->plugins([
    new \Taba\Crm\CrmPlugin(),          // Admin panel
    new \Taba\Crm\CrmClientPlugin(),     // Client panel
])
```

### API v2

Component-aware API endpoints alongside the existing v1 API:

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v2/sections` | GET | All active sections with component data |
| `/api/v2/sections/{id}` | GET | Single section |
| `/api/v2/settings` | GET | Grouped site settings |
| `/api/v2/pages/{slug}` | GET | Page by slug |
| `/api/v2/menus` | GET | All menus |
| `/api/v2/components` | GET | Available component types |
| `/api/v2/contact` | POST | Submit contact message |

Authenticated admin endpoints (requires Sanctum token):

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v2/admin/sections` | POST | Create section |
| `/api/v2/admin/sections/{id}` | PUT | Update section |
| `/api/v2/admin/sections/{id}` | DELETE | Deactivate section |

---

## 🔧 Customization (Optional)

If you need to modify the package's default behavior, you can publish its assets.

```bash
php artisan vendor:publish --tag=crm-config
php artisan vendor:publish --tag=crm-views
php artisan vendor:publish --tag=crm-database
```

## 📄 License

The Taba CRM is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
