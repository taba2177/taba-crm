<?php

// Generic starter categories for a fresh CRM install.
// Edit titles/descriptions in the Filament admin or in this file before seeding.

return [
    // ── 1. Homepage / Hero ─────────────────────────────────────────────
    [
        'id'                 => 1,
        'created_at'         => '2025-01-01 00:00:00',
        'updated_at'         => '2025-01-01 00:00:00',
        'slug'               => 'homepage',
        'name'               => '{"en":"Home","ar":"الرئيسية"}',
        'register_in_header' => 0,
        'HEAVY_SECTION'      => 1,
        'section_component'  => 'hero-section',
        'order'              => 1,
        'description'        => '{"en":"Welcome — replace this with your tagline","ar":"أهلًا بكم — استبدل هذا النص بشعار موقعك"}',
        'subtitle'           => '{"en":"Your tagline goes here","ar":"اكتب شعارك هنا"}',
    ],

    // ── 2. About ───────────────────────────────────────────────────────
    [
        'id'                 => 2,
        'created_at'         => '2025-01-01 00:00:00',
        'updated_at'         => '2025-01-01 00:00:00',
        'slug'               => 'about',
        'name'               => '{"en":"About Us","ar":"من نحن"}',
        'register_in_header' => 1,
        'HEAVY_SECTION'      => 0,
        'section_component'  => 'about-section',
        'order'              => 2,
        'description'        => '{"en":"Tell visitors who you are and what you do","ar":"عرّف الزوار بمن أنت وماذا تقدّم"}',
        'subtitle'           => '{"en":"Who We Are","ar":"من نحن"}',
    ],

    // ── 3. Services ────────────────────────────────────────────────────
    [
        'id'                 => 3,
        'created_at'         => '2025-01-01 00:00:00',
        'updated_at'         => '2025-01-01 00:00:00',
        'slug'               => 'services',
        'name'               => '{"en":"Our Services","ar":"خدماتنا"}',
        'register_in_header' => 1,
        'HEAVY_SECTION'      => 1,
        'section_component'  => 'services-section',
        'order'              => 3,
        'description'        => '{"en":"List the services or products you provide","ar":"اعرض الخدمات أو المنتجات التي تقدّمها"}',
        'subtitle'           => '{"en":"What We Offer","ar":"ما نقدّمه"}',
    ],

    // ── 4. Features / Why Choose Us ────────────────────────────────────
    [
        'id'                 => 4,
        'created_at'         => '2025-01-01 00:00:00',
        'updated_at'         => '2025-01-01 00:00:00',
        'slug'               => 'features',
        'name'               => '{"en":"Why Choose Us","ar":"لماذا تختارنا"}',
        'register_in_header' => 1,
        'HEAVY_SECTION'      => 0,
        'section_component'  => 'features-section',
        'order'              => 4,
        'description'        => '{"en":"Highlight what makes you stand out","ar":"اذكر ما يميّزك عن غيرك"}',
        'subtitle'           => '{"en":"Our Advantages","ar":"مميزاتنا"}',
    ],

    // ── 5. Partners ────────────────────────────────────────────────────
    [
        'id'                 => 5,
        'created_at'         => '2025-01-01 00:00:00',
        'updated_at'         => '2025-01-01 00:00:00',
        'slug'               => 'partners',
        'name'               => '{"en":"Our Partners","ar":"شركاؤنا"}',
        'register_in_header' => 1,
        'HEAVY_SECTION'      => 0,
        'section_component'  => 'partners-section',
        'order'              => 5,
        'description'        => '{"en":"Companies and clients you have worked with","ar":"الشركات والعملاء الذين عملت معهم"}',
        'subtitle'           => '{"en":"Trusted Partners","ar":"شركاء موثوقون"}',
    ],

    // ── 6. Branches / Coverage ─────────────────────────────────────────
    [
        'id'                 => 6,
        'created_at'         => '2025-01-01 00:00:00',
        'updated_at'         => '2025-01-01 00:00:00',
        'slug'               => 'branches',
        'name'               => '{"en":"Our Coverage","ar":"تغطيتنا"}',
        'register_in_header' => 1,
        'HEAVY_SECTION'      => 0,
        'section_component'  => 'branches-section',
        'order'              => 6,
        'description'        => '{"en":"Where you operate or which areas you serve","ar":"أماكن عملك أو المناطق التي تخدمها"}',
        'subtitle'           => '{"en":"Where We Serve","ar":"أين نخدم"}',
    ],

    // ── 7. Line of Work ────────────────────────────────────────────────
    [
        'id'                 => 7,
        'created_at'         => '2025-01-01 00:00:00',
        'updated_at'         => '2025-01-01 00:00:00',
        'slug'               => 'line-of-work',
        'name'               => '{"en":"How We Work","ar":"طريقة عملنا"}',
        'register_in_header' => 0,
        'HEAVY_SECTION'      => 0,
        'section_component'  => 'line-of-work-section',
        'order'              => 7,
        'description'        => '{"en":"Your typical process from first contact to delivery","ar":"خطوات العمل من أول تواصل حتى التسليم"}',
        'subtitle'           => '{"en":"Our Process","ar":"خطوات العمل"}',
    ],
];
