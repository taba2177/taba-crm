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

    'middleware' => [
        'redirect.if.from.google' => \Taba\Crm\Http\Middleware\RedirectIfFromGoogle::class,
        'remove.public.from.url' => \Taba\Crm\Http\Middleware\RemovePublicFromUrl::class,
        'google.translate' => \Taba\Crm\Http\Middleware\GoogleTranslate::class,
        'force.https' => \Taba\Crm\Http\Middleware\ForceHttps::class,
    ],
];