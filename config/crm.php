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
];
