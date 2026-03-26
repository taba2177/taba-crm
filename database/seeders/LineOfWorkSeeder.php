<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * LineOfWorkSeeder
 *
 * 1. Inserts 4 line-of-work posts (category 7) if they don't exist.
 * 2. Updates existing posts 1–13 to use FontAwesome-compatible icon classes.
 * 3. Inserts line_of_work settings if they don't exist.
 */
class LineOfWorkSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Update existing post icons to FontAwesome ────────────────
        $iconUpdates = [
            ['id' => 1,  'icon' => 'fa-solid fa-house'],
            ['id' => 2,  'icon' => 'fa-solid fa-building'],
            ['id' => 3,  'icon' => 'fa-solid fa-wand-magic-sparkles'],
            ['id' => 4,  'icon' => 'fa-solid fa-table-cells-large'],
            ['id' => 5,  'icon' => 'fa-solid fa-pump-soap'],
            ['id' => 6,  'icon' => 'fa-solid fa-droplet'],
            ['id' => 7,  'icon' => 'fa-solid fa-stairs'],
            ['id' => 8,  'icon' => 'fa-solid fa-users-gear'],
            ['id' => 9,  'icon' => 'fa-solid fa-toolbox'],
            ['id' => 10, 'icon' => 'fa-solid fa-shield-virus'],
            ['id' => 11, 'icon' => 'fa-solid fa-clipboard-check'],
            ['id' => 12, 'icon' => 'fa-solid fa-award'],
            ['id' => 13, 'icon' => 'fa-solid fa-calendar-check'],
            ['id' => 14, 'icon' => 'fa-solid fa-oil-well'],
            ['id' => 15, 'icon' => 'fa-solid fa-industry'],
            ['id' => 16, 'icon' => 'fa-solid fa-landmark'],
            ['id' => 17, 'icon' => 'fa-solid fa-heart-pulse'],
            ['id' => 18, 'icon' => 'fa-solid fa-signal'],
        ];

        foreach ($iconUpdates as $update) {
            DB::table('posts')->where('id', $update['id'])->update(['icon' => $update['icon']]);
        }
        $this->command->info('✅ Updated post icons to FontAwesome classes.');

        // ── 2. Insert line-of-work posts ────────────────────────────────
        $lineOfWorkPosts = [
            [
                'id'    => 19,
                'title' => '{"en":"Post-Construction Cleaning","ar":"تنظيف ما بعد البناء"}',
                'slug'  => 'post-construction-cleaning',
                'meta_title'       => '{"en":"Post-Construction Cleaning – SBC Clean","ar":"تنظيف ما بعد البناء – SBC كلين"}',
                'meta_description' => '{"en":"Professional post-construction cleanup ready for move-in","ar":"تجهيز منزلك للسكن بعد الإنشاء باحترافية"}',
                'metadata' => '[]',
                'images'   => null,
                'content'  => '{"en":[{"type":"markdown","data":{"content":"## Post-Construction Cleaning\n\nWe prepare your newly built or renovated space for move-in."}}],"ar":[{"type":"markdown","data":{"content":"## تنظيف ما بعد البناء\n\nنجهّز منزلك الجديد للسكن."}}]}',
                'homepage_section_component' => 'line-of-work',
                'homepage_section_content'   => null,
                'user_id'          => 1,
                'is_published'     => 1,
                'published_at'     => '2025-01-01 00:00:00',
                'post_category_id' => 7,
                'created_at'       => '2025-01-01 00:00:00',
                'updated_at'       => '2025-01-01 00:00:00',
                'image_id'         => null,
                'show_in_home'     => 1,
                'order'            => 1,
                'icon'             => 'fa-solid fa-hard-hat',
            ],
            [
                'id'    => 20,
                'title' => '{"en":"Furniture & Carpet Cleaning","ar":"تنظيف الأثاث والسجاد"}',
                'slug'  => 'furniture-carpet-cleaning',
                'meta_title'       => '{"en":"Furniture & Carpet Cleaning – SBC Clean","ar":"تنظيف أثاث وسجاد – SBC كلين"}',
                'meta_description' => '{"en":"Professional deep cleaning for upholstery, carpets and curtains","ar":"تنظيف عميق احترافي للمفروشات والسجاد والستائر"}',
                'metadata' => '[]',
                'images'   => null,
                'content'  => '{"en":[{"type":"markdown","data":{"content":"## Furniture & Carpet Cleaning\n\nRestore freshness to your furniture, sofas, carpets and curtains."}}],"ar":[{"type":"markdown","data":{"content":"## تنظيف الأثاث والسجاد\n\nأعد النضارة لأثاثك وأرائككم وسجادك وستائرك."}}]}',
                'homepage_section_component' => 'line-of-work',
                'homepage_section_content'   => null,
                'user_id'          => 1,
                'is_published'     => 1,
                'published_at'     => '2025-01-01 00:00:00',
                'post_category_id' => 7,
                'created_at'       => '2025-01-01 00:00:00',
                'updated_at'       => '2025-01-01 00:00:00',
                'image_id'         => null,
                'show_in_home'     => 1,
                'order'            => 2,
                'icon'             => 'fa-solid fa-couch',
            ],
            [
                'id'    => 21,
                'title' => '{"en":"Pest & Rodent Control","ar":"مكافحة الحشرات والقوارض"}',
                'slug'  => 'pest-control',
                'meta_title'       => '{"en":"Pest & Rodent Control – SBC Clean","ar":"مكافحة حشرات وقوارض – SBC كلين"}',
                'meta_description' => '{"en":"Safe and effective pest and rodent control for homes and businesses","ar":"مكافحة آمنة وفعالة للحشرات والقوارض للمنازل والشركات"}',
                'metadata' => '[]',
                'images'   => null,
                'content'  => '{"en":[{"type":"markdown","data":{"content":"## Pest & Rodent Control\n\nProtect your home and business from insects, cockroaches, rodents and other pests."}}],"ar":[{"type":"markdown","data":{"content":"## مكافحة الحشرات والقوارض\n\nاحمِ منزلك وشركتك من الحشرات والصراصير والقوارض."}}]}',
                'homepage_section_component' => 'line-of-work',
                'homepage_section_content'   => null,
                'user_id'          => 1,
                'is_published'     => 1,
                'published_at'     => '2025-01-01 00:00:00',
                'post_category_id' => 7,
                'created_at'       => '2025-01-01 00:00:00',
                'updated_at'       => '2025-01-01 00:00:00',
                'image_id'         => null,
                'show_in_home'     => 1,
                'order'            => 3,
                'icon'             => 'fa-solid fa-bug',
            ],
            [
                'id'    => 22,
                'title' => '{"en":"Exterior & Facade Cleaning","ar":"تنظيف الواجهات الخارجية"}',
                'slug'  => 'exterior-facade-cleaning',
                'meta_title'       => '{"en":"Exterior & Facade Cleaning – SBC Clean","ar":"تنظيف واجهات خارجية – SBC كلين"}',
                'meta_description' => '{"en":"High-quality exterior and building facade cleaning services","ar":"خدمات تنظيف واجهات المباني بجودة عالية"}',
                'metadata' => '[]',
                'images'   => null,
                'content'  => '{"en":[{"type":"markdown","data":{"content":"## Exterior & Facade Cleaning\n\nMaintain the visual appeal and longevity of your building exterior."}}],"ar":[{"type":"markdown","data":{"content":"## تنظيف الواجهات الخارجية\n\nحافظ على جمال واجهة مبناك وطول عمره."}}]}',
                'homepage_section_component' => 'line-of-work',
                'homepage_section_content'   => null,
                'user_id'          => 1,
                'is_published'     => 1,
                'published_at'     => '2025-01-01 00:00:00',
                'post_category_id' => 7,
                'created_at'       => '2025-01-01 00:00:00',
                'updated_at'       => '2025-01-01 00:00:00',
                'image_id'         => null,
                'show_in_home'     => 1,
                'order'            => 4,
                'icon'             => 'fa-regular fa-building',
            ],
        ];

        $driver = DB::connection()->getDriverName();
        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        }

        foreach ($lineOfWorkPosts as $post) {
            DB::table('posts')->updateOrInsert(['id' => $post['id']], $post);
        }

        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        }
        $this->command->info('✅ Line-of-work posts seeded (4 posts).');

        // ── 3. Insert line-of-work settings ────────────────────────────
        $settings = [
            [
                'key'            => 'line_of_work_section_title',
                'value'          => json_encode(['ar' => 'نطاق عملنا', 'en' => 'Our Scope of Work']),
                'group'          => 'line_of_work',
                'type'           => 'text',
                'is_translatable' => 1,
                'label'          => 'Line of Work — Section Title',
                'description'    => null,
                'order'          => 1,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'key'            => 'line_of_work_title',
                'value'          => json_encode(['ar' => 'احتياجات منشأتك في مكان واحد', 'en' => 'All Your Facility Needs in One Place']),
                'group'          => 'line_of_work',
                'type'           => 'text',
                'is_translatable' => 1,
                'label'          => 'Line of Work — Title',
                'description'    => null,
                'order'          => 2,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'key'            => 'line_of_work_description',
                'value'          => json_encode(['ar' => 'نأهل منشأتك للسكن بعد البناء ونعيد لأثاثك نظافته الاحترافية، نهتم بصيانة المبنى، ونقدم لك حماية شاملة من الحشرات', 'en' => 'From post-construction cleanup to deep furniture care, exterior facade cleaning and comprehensive pest control — we handle it all.']),
                'group'          => 'line_of_work',
                'type'           => 'textarea',
                'is_translatable' => 1,
                'label'          => 'Line of Work — Description',
                'description'    => null,
                'order'          => 3,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('crm_settings')->updateOrInsert(['key' => $setting['key']], $setting);
        }
        $this->command->info('✅ Line-of-work settings inserted.');
    }
}
