# CRM Settings Management System

## Overview
A comprehensive Filament-based settings management system that allows administrators to configure all CRM settings from the dashboard without touching code or `.env` files.

## Features

### 🎯 Key Capabilities
- **Database-Driven Settings**: All settings stored in database with config fallback
- **Translatable Fields**: Support for bilingual content (English/Arabic)
- **Smart Defaults**: Automatically populated from posts and categories
- **User-Friendly Interface**: Beautiful tabbed interface with organized sections
- **Helper Functions**: Easy access to settings throughout the application

## Database Structure

### Migration: `create_crm_settings_table`
```php
Schema::create('crm_settings', function (Blueprint $table) {
    $table->id();
    $table->string('key')->unique();
    $table->json('value')->nullable();
    $table->string('type')->default('text');
    $table->string('group')->default('general');
    $table->json('label')->nullable();
    $table->json('description')->nullable();
    $table->boolean('is_translatable')->default(false);
    $table->integer('order')->default(0);
    $table->timestamps();
});
```

## Settings Groups

### 1. Contact Information (`contact`)
| Setting | Type | Translatable | Default |
|---------|------|--------------|---------|
| `crm_contact_phone` | text | ❌ | +966500000000 |
| `crm_contact_email` | text | ❌ | info@example.com |
| `crm_contact_address` | text | ✅ | From posts/categories |
| `crm_contact_city` | text | ✅ | Riyadh / الرياض |
| `crm_contact_postal_code` | text | ❌ | - |
| `crm_contact_latitude` | text | ❌ | 24.774265 |
| `crm_contact_longitude` | text | ❌ | 46.738586 |

### 2. Social Media (`social`)
| Setting | Type | Default |
|---------|------|---------|
| `crm_contact_facebook` | text | - |
| `crm_contact_twitter` | text | - |
| `crm_contact_instagram` | text | - |
| `crm_contact_linkedin` | text | - |
| `crm_contact_youtube` | text | - |

### 3. Business Information (`business`)
| Setting | Type | Translatable | Default |
|---------|------|--------------|---------|
| `crm_business_name` | text | ✅ | From config('app.name') |
| `crm_business_price_range` | text | ❌ | - |
| `crm_business_opens` | text | ❌ | 09:00 |
| `crm_business_closes` | text | ❌ | 18:00 |

### 4. SEO Defaults (`seo`)
| Setting | Type | Translatable | Default |
|---------|------|--------------|---------|
| `crm_seo_default_title` | text | ✅ | From first post meta_title |
| `crm_seo_default_description` | textarea | ✅ | From first post meta_description |

### 5. API Keys (`api`)
| Setting | Type | Default |
|---------|------|---------|
| `crm_gemini_api_key` | text (password) | From env('GEMINI_API_KEY') |

## Helper Functions

### `crm_setting($key, $default = null)`
Get any CRM setting with automatic fallback to config.

```php
// Get phone number
$phone = crm_setting('contact.phone');
// or
$phone = crm_setting('crm_contact_phone');

// With default
$email = crm_setting('contact.email', 'default@example.com');
```

### `crm_contact($field = null)`
Get contact information.

```php
// Get all contact info
$contact = crm_contact();
// Returns: ['phone' => '...', 'email' => '...', ...]

// Get specific field
$phone = crm_contact('phone');
$address = crm_contact('address');
```

### `crm_social_links()`
Get all non-empty social media links as array.

```php
$links = crm_social_links();
// Returns: ['https://facebook.com/...', 'https://twitter.com/...']
```

### `crm_business($field = null)`
Get business information.

```php
// Get all business info
$business = crm_business();

// Get specific field
$name = crm_business('name');
$opens = crm_business('opens');
```

## Filament Resource

### Navigation
- **Location**: Settings group
- **Icon**: Cog icon (heroicon-o-cog-6-tooth)
- **Sort Order**: 99 (bottom of navigation)

### Interface Tabs
1. **Contact Information** 📞
   - Phone, Email, Address (EN/AR)
   - City (EN/AR), Postal Code
   - GPS Coordinates (Latitude/Longitude)

2. **Social Media** 🔗
   - Facebook, Twitter/X, Instagram
   - LinkedIn, YouTube
   - All with URL validation

3. **Business Info** 🏢
   - Business Name (EN/AR)
   - Price Range
   - Opening/Closing Times

4. **SEO Defaults** 🔍
   - Default Title (EN/AR) - Max 60 chars
   - Default Description (EN/AR) - Max 160 chars

5. **API Keys** 🔑
   - Gemini API Key (password field with reveal)

### Actions
- **Save Changes**: Primary button to save all settings
- **Reset to Defaults**: Danger button to re-run seeder

## Seeder

### `CrmSettingsSeeder`
Automatically populates settings with smart defaults:

- **Contact Info**: Default values + GPS coordinates
- **Business Info**: From `config('app.name')`
- **SEO Defaults**: From first published post or first category
- **API Keys**: From environment variables

Run manually:
```bash
php artisan db:seed --class="Taba\Crm\Database\Seeders\CrmSettingsSeeder"
```

## Installation Process

The seeder runs automatically during `php artisan crm:install` in this order:
1. RolesAndPermissionsSeeder
2. UserSeeder
3. PostCategorySeeder
4. PostSeeder
5. **CrmSettingsSeeder** ← New!

## Usage in Code

### Before (Hardcoded)
```php
$phone = '+966583097425';
$businessName = 'مكتب جديان للحلول الهندسية';
```

### After (Dynamic from Database)
```php
$phone = crm_contact('phone');
$businessName = crm_business('name');
```

### Example: Home.php SEO Metadata
```php
protected function setSeoMetadata()
{
    $businessName = crm_business('name');
    $phone = crm_contact('phone');
    $socialLinks = crm_social_links();
    
    seo()
        ->title($this->title())
        ->description($this->desc())
        ->addSchema(
            Schema::localBusiness()
                ->name($businessName)
                ->telephone($phone)
                ->sameAs($socialLinks)
        );
}
```

## Benefits

### ✅ For Developers
- No hardcoded values
- Config fallback for safety
- Easy to extend
- Type-safe helper functions

### ✅ For Clients
- Manage everything from dashboard
- No technical knowledge needed
- Immediate preview of changes
- Bilingual support built-in

### ✅ For Package Reusability
- Self-contained configuration
- Works out of the box
- Smart defaults from content
- No manual setup required

## Files Created

1. **Migration**: `2024_01_20_000000_create_crm_settings_table.php`
2. **Model**: `src/Models/CrmSetting.php`
3. **Seeder**: `database/seeders/CrmSettingsSeeder.php`
4. **Resource**: `src/Filament/Resources/CrmSettingResource.php`
5. **Page**: `src/Filament/Resources/CrmSettingResource/Pages/ManageCrmSettings.php`
6. **View**: `resources/views/filament/pages/manage-crm-settings.blade.php`
7. **Helpers**: `src/helpers.php`

## Next Steps

### To Use in Your Project
1. Run migrations: `php artisan migrate`
2. Seed settings: Automatically done by `crm:install`
3. Access dashboard: Navigate to "Settings" → "CRM Settings"
4. Update values and save

## Component System Configuration

### `extra_components` (config key)
Register custom section components beyond the 36 built-in ones.

```php
// config/crm.php
'extra_components' => [
    \App\Components\CustomHero::class,
    \App\Components\TeamSection::class,
],
```

Each class must implement `Taba\Crm\Components\Contracts\SectionComponent`.

### Available Built-in Components

| Key | Layout | Description |
|-----|--------|-------------|
| `hero` | SINGLE | Hero/banner section |
| `about` | SINGLE | About us section |
| `services-grid` | LIST | Services grid |
| `services-cards` | LIST | Services cards |
| `portfolio-grid` | LIST | Portfolio/gallery grid |
| `team-grid` | LIST | Team members grid |
| `testimonials` | LIST | Client testimonials |
| `faq` | LIST | FAQ accordion |
| `blog-grid` | LIST | Blog posts grid |
| `contact` | SINGLE | Contact information |
| `cta` | SINGLE | Call to action |
| `features` | LIST | Features list |
| `pricing` | LIST | Pricing tables |
| `stats` | LIST | Statistics/counters |
| `timeline` | LIST | Timeline/history |
| `gallery` | LIST | Image gallery |
| `video-gallery` | LIST | Video gallery |
| `map` | SINGLE | Map section |
| `newsletter` | SINGLE | Newsletter subscription |
| `partners` | LIST | Partners/clients logos |
| `downloads` | LIST | Downloadable files |
| `branches` | LIST | Branch locations |
| `products` | LIST | Products catalog |
| `events` | LIST | Events listing |
| `courses` | LIST | Courses/training |
| `doctors` | LIST | Medical team |
| `departments` | LIST | Departments |
| `offers` | LIST | Special offers |
| `projects` | LIST | Projects showcase |
| `certifications` | LIST | Certifications/awards |
| `news` | LIST | News/announcements |
| `jobs` | LIST | Job openings |
| `before-after` | LIST | Before/after comparisons |
| `text-block` | SINGLE | Rich text content |
| `slider` | LIST | Image slider |
| `default` | LIST | Generic section |

### To Extend
Add new settings to `CrmSettingsSeeder`:
```php
[
    'key' => 'crm_my_setting',
    'value' => 'default value',
    'type' => 'text',
    'group' => 'general',
    'label' => ['en' => 'My Setting', 'ar' => 'إعدادي'],
    'is_translatable' => false,
    'order' => 1,
]
```

Then add to resource form schema in `CrmSettingResource.php`.

## Support

All settings are:
- ✅ Stored in database
- ✅ Cached for performance
- ✅ Translatable when needed
- ✅ Validated by Filament
- ✅ Accessible via helpers
- ✅ Fallback to config values
