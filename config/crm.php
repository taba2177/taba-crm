<?php
// config/crm.php
return [
    // AI/Gemini Configuration
    // Set your GEMINI_API_KEY in your .env file
    // Example: GEMINI_API_KEY=your_actual_api_key_here
    'gemini_api_key' => env('GEMINI_API_KEY','AIzaSyADsOIP5_llPdjdyo6_vgSPQhcbJK1-OZw'),

    // Default locale for the CRM
    'locale' => env('CRM_LOCALE', 'ar'),

    // Available locales
    'available_locales' => ['ar', 'en'],

    // Contact Information (used in SEO and contact pages)
    'contact' => [
        'phone' => env('CRM_CONTACT_PHONE', '+966500000000'),
        'address' => env('CRM_CONTACT_ADDRESS', ''),
        'city' => env('CRM_CONTACT_CITY', ''),
        'postal_code' => env('CRM_CONTACT_POSTAL_CODE', ''),
        'latitude' => env('CRM_CONTACT_LATITUDE', '24.774265'),
        'longitude' => env('CRM_CONTACT_LONGITUDE', '46.738586'),
        'social_links' => [
            // Add your social media links here
            // env('CRM_FACEBOOK_URL', ''),
            // env('CRM_TWITTER_URL', ''),
            // env('CRM_INSTAGRAM_URL', ''),
            // env('CRM_LINKEDIN_URL', ''),
        ],
    ],

    // Business Information (used in SEO structured data)
    'business' => [
        'price_range' => env('CRM_BUSINESS_PRICE_RANGE', ''),
        'opening_hours' => [
            'opens' => env('CRM_BUSINESS_OPENS', '09:00'),
            'closes' => env('CRM_BUSINESS_CLOSES', '18:00'),
        ],
    ],

    'middleware' => [
        'redirect.if.from.google' => \Taba\Crm\Http\Middleware\RedirectIfFromGoogle::class,
        'remove.public.from.url' => \Taba\Crm\Http\Middleware\RemovePublicFromUrl::class,
        'google.translate' => \Taba\Crm\Http\Middleware\GoogleTranslate::class,
        'force.https' => \Taba\Crm\Http\Middleware\ForceHttps::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | API Configuration
    |--------------------------------------------------------------------------
    |
    | Settings for the RESTful API used by headless frontends (Angular, etc.)
    |
    */
    'api' => [
        // Enable or disable the API
        'enabled' => env('CRM_API_ENABLED', true),

        // Cache TTL in seconds for API responses (default: 5 minutes)
        'cache_ttl' => env('CRM_API_CACHE_TTL', 300),

        // Sanctum token expiration in days
        'token_expiration_days' => env('CRM_API_TOKEN_DAYS', 30),

        // If true, only one active token per user (new login revokes old tokens)
        'single_session' => env('CRM_API_SINGLE_SESSION', false),

        // API rate limiting (requests per minute)
        'rate_limit' => env('CRM_API_RATE_LIMIT', 60),

        // Allowed CORS origins for the Angular app
        // Use '*' for all origins or specify your Angular app URL
        'cors_origins' => env('CRM_API_CORS_ORIGINS', '*'),

        // Default pagination size
        'per_page' => env('CRM_API_PER_PAGE', 15),

        // Maximum pagination size
        'max_per_page' => env('CRM_API_MAX_PER_PAGE', 100),
    ],

    /*
    |--------------------------------------------------------------------------
    | Brand / Theme
    |--------------------------------------------------------------------------
    |
    | Brand colors and font applied to the Filament admin panel so it matches
    | the consumer project's frontend identity.  Values here are defaults;
    | they can be overridden at runtime via CRM Settings (group "brand").
    |
    */
    'brand' => [
        'primary_color'   => env('CRM_BRAND_PRIMARY', '#0ea5e9'),   // sky-500
        'secondary_color' => env('CRM_BRAND_SECONDARY', '#64748b'), // slate-500
        'font_family'     => env('CRM_BRAND_FONT', 'Cairo'),
        'font_url'        => env('CRM_BRAND_FONT_URL', 'https://fonts.bunny.net/css?family=cairo:400,500,600,700'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Navigation Visibility
    |--------------------------------------------------------------------------
    |
    | Controls which sidebar items auto-hide when they have zero records.
    | 'auto_hide_empty' lists resource short-names that disappear when empty.
    | 'hidden_items' is managed by the super-admin via CRM Settings UI.
    |
    */
    'navigation' => [
        'auto_hide_empty' => [
            'contact-entries',
            'service-payments',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Login Page
    |--------------------------------------------------------------------------
    |
    | Slideshow images shown on the admin login page background.
    | Each entry is an image URL.  Interval is in seconds.
    |
    */
    'login' => [
        'slideshow_images' => [
            'https://images.pexels.com/photos/466685/pexels-photo-466685.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
        ],
        'slideshow_interval' => 6,
    ],

    /*
    |--------------------------------------------------------------------------
    | Extra Components
    |--------------------------------------------------------------------------
    |
    | Register additional SectionComponent classes beyond the built-in ones.
    | Later registrations override earlier ones with the same key.
    |
    */
    'extra_components' => [],
];
