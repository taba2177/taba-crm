<?php

namespace Taba\Crm\Database\Seeders;

use Illuminate\Database\Seeder;
use Taba\Crm\Models\CrmSetting;

/**
 * Seeds all settings keys required by the Angular ContentService (sbc-clean).
 *
 * Uses updateOrCreate (upsert-safe) — safe to re-run at any time.
 * Keys must match exactly what ContentService.setting() looks for.
 *
 * Run: php artisan db:seed --class="Taba\Crm\Database\Seeders\AngularSettingsSeeder"
 */
class AngularSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // ── Site ──────────────────────────────────────────────────────────
            [
                'key'            => 'site_name',
                'value'          => 'SBC كلين',
                'type'           => 'text',
                'group'          => 'site',
                'label'          => ['en' => 'Site Name', 'ar' => 'اسم الموقع'],
                'is_translatable' => false,
                'order'          => 1,
            ],
            [
                'key'            => 'site_logo',
                'value'          => '',
                'type'           => 'image',
                'group'          => 'site',
                'label'          => ['en' => 'Site Logo', 'ar' => 'شعار الموقع'],
                'is_translatable' => false,
                'order'          => 2,
            ],

            // ── Hero ──────────────────────────────────────────────────────────
            [
                'key'            => 'hero_title',
                'value'          => 'نظافة احترافية تليق ببيتك',
                'type'           => 'text',
                'group'          => 'hero',
                'label'          => ['en' => 'Hero Title', 'ar' => 'عنوان الهيرو'],
                'is_translatable' => false,
                'order'          => 1,
            ],
            [
                'key'            => 'hero_description',
                'value'          => 'نقدم خدمات تنظيف احترافية شاملة للمنازل والشركات في المملكة العربية السعودية، بأيدي متخصصة وتقنيات حديثة.',
                'type'           => 'textarea',
                'group'          => 'hero',
                'label'          => ['en' => 'Hero Description', 'ar' => 'وصف الهيرو'],
                'is_translatable' => false,
                'order'          => 2,
            ],
            [
                'key'            => 'hero_cta_text',
                'value'          => 'احجز الآن',
                'type'           => 'text',
                'group'          => 'hero',
                'label'          => ['en' => 'Hero CTA Text', 'ar' => 'نص زر الهيرو'],
                'is_translatable' => false,
                'order'          => 3,
            ],
            [
                'key'            => 'hero_cta_link',
                'value'          => '#booking',
                'type'           => 'text',
                'group'          => 'hero',
                'label'          => ['en' => 'Hero CTA Link', 'ar' => 'رابط زر الهيرو'],
                'is_translatable' => false,
                'order'          => 4,
            ],
            [
                'key'            => 'hero_background_image',
                'value'          => '',
                'type'           => 'image',
                'group'          => 'hero',
                'label'          => ['en' => 'Hero Background Image', 'ar' => 'صورة خلفية الهيرو'],
                'is_translatable' => false,
                'order'          => 5,
            ],

            // ── About ─────────────────────────────────────────────────────────
            [
                'key'            => 'about_section_title',
                'value'          => 'من نحن',
                'type'           => 'text',
                'group'          => 'about',
                'label'          => ['en' => 'About Section Label', 'ar' => 'تسمية قسم من نحن'],
                'is_translatable' => false,
                'order'          => 1,
            ],
            [
                'key'            => 'about_title',
                'value'          => 'شركة SBC كلين — شريكك الموثوق في النظافة',
                'type'           => 'text',
                'group'          => 'about',
                'label'          => ['en' => 'About Title', 'ar' => 'عنوان من نحن'],
                'is_translatable' => false,
                'order'          => 2,
            ],
            [
                'key'            => 'about_description',
                'value'          => 'تأسست شركة SBC كلين لتقديم أعلى معايير النظافة والتعقيم في المملكة العربية السعودية. نجمع بين الخبرة الممتدة لأكثر من عشر سنوات وأحدث التقنيات لنضمن لك بيئة نظيفة وصحية.',
                'type'           => 'textarea',
                'group'          => 'about',
                'label'          => ['en' => 'About Description', 'ar' => 'وصف من نحن'],
                'is_translatable' => false,
                'order'          => 3,
            ],
            [
                'key'            => 'about_stats',
                'value'          => json_encode([
                    ['value' => 3000, 'label' => 'عميل سعيد', 'icon' => 'fa-solid fa-users', 'suffix' => '+'],
                    ['value' => 10, 'label' => 'سنة خبرة', 'icon' => 'fa-solid fa-medal', 'suffix' => '+'],
                    ['value' => 25, 'label' => 'مدينة نخدمها', 'icon' => 'fa-solid fa-location-dot', 'suffix' => '+'],
                    ['value' => 99, 'label' => 'رضا العملاء', 'icon' => 'fa-solid fa-star', 'suffix' => '%'],
                ]),
                'type'           => 'json',
                'group'          => 'about',
                'label'          => ['en' => 'About Stats', 'ar' => 'إحصائيات من نحن'],
                'is_translatable' => false,
                'order'          => 4,
            ],
            [
                'key'            => 'about_pills',
                'value'          => json_encode(['كوادر فنية مدربة', 'معدات وتقنيات حديثة', 'مواد آمنة ومعتمدة', 'ضمان جودة الخدمة']),
                'type'           => 'json',
                'group'          => 'about',
                'label'          => ['en' => 'About Feature Pills', 'ar' => 'نقاط مميزات من نحن'],
                'is_translatable' => false,
                'order'          => 5,
            ],

            // ── Services ──────────────────────────────────────────────────────
            [
                'key'            => 'services_section_title',
                'value'          => 'خدماتنا',
                'type'           => 'text',
                'group'          => 'services',
                'label'          => ['en' => 'Services Section Label', 'ar' => 'تسمية قسم الخدمات'],
                'is_translatable' => false,
                'order'          => 1,
            ],
            [
                'key'            => 'services_title',
                'value'          => 'خدمات تنظيف شاملة لكل احتياجاتك',
                'type'           => 'text',
                'group'          => 'services',
                'label'          => ['en' => 'Services Title', 'ar' => 'عنوان الخدمات'],
                'is_translatable' => false,
                'order'          => 2,
            ],
            [
                'key'            => 'services_description',
                'value'          => 'نوفر باقة متكاملة من خدمات التنظيف الاحترافية لمنزلك وشركتك.',
                'type'           => 'textarea',
                'group'          => 'services',
                'label'          => ['en' => 'Services Description', 'ar' => 'وصف الخدمات'],
                'is_translatable' => false,
                'order'          => 3,
            ],

            // ── Features ──────────────────────────────────────────────────────
            [
                'key'            => 'features_section_title',
                'value'          => 'لماذا نحن',
                'type'           => 'text',
                'group'          => 'features',
                'label'          => ['en' => 'Features Section Label', 'ar' => 'تسمية قسم المميزات'],
                'is_translatable' => false,
                'order'          => 1,
            ],
            [
                'key'            => 'features_title',
                'value'          => 'لماذا تختار SBC كلين؟',
                'type'           => 'text',
                'group'          => 'features',
                'label'          => ['en' => 'Features Title', 'ar' => 'عنوان المميزات'],
                'is_translatable' => false,
                'order'          => 2,
            ],
            [
                'key'            => 'features_description',
                'value'          => 'نتميز بتقديم خدمة عالية الجودة مع ضمان رضا العميل.',
                'type'           => 'textarea',
                'group'          => 'features',
                'label'          => ['en' => 'Features Description', 'ar' => 'وصف المميزات'],
                'is_translatable' => false,
                'order'          => 3,
            ],
            [
                'key'            => 'features_list',
                'value'          => json_encode([
                    ['title' => 'فريق متخصص ومدرب', 'icon' => 'fa-solid fa-users-gear'],
                    ['title' => 'معدات وتقنيات حديثة', 'icon' => 'fa-solid fa-spray-can-sparkles'],
                    ['title' => 'مواد تنظيف آمنة ومعتمدة', 'icon' => 'fa-solid fa-shield-halved'],
                    ['title' => 'خدمة على مدار الساعة', 'icon' => 'fa-solid fa-clock'],
                    ['title' => 'أسعار تنافسية وشفافة', 'icon' => 'fa-solid fa-tags'],
                    ['title' => 'ضمان رضا العميل', 'icon' => 'fa-solid fa-star'],
                ]),
                'type'           => 'json',
                'group'          => 'features',
                'label'          => ['en' => 'Features List', 'ar' => 'قائمة المميزات'],
                'is_translatable' => false,
                'order'          => 4,
            ],

            // ── Branches ──────────────────────────────────────────────────────
            [
                'key'            => 'branches_section_title',
                'value'          => 'تغطيتنا الجغرافية',
                'type'           => 'text',
                'group'          => 'branches',
                'label'          => ['en' => 'Branches Section Label', 'ar' => 'تسمية قسم الفروع'],
                'is_translatable' => false,
                'order'          => 1,
            ],
            [
                'key'            => 'branches_title',
                'value'          => 'نخدم أكثر من 25 مدينة في المملكة',
                'type'           => 'text',
                'group'          => 'branches',
                'label'          => ['en' => 'Branches Title', 'ar' => 'عنوان الفروع'],
                'is_translatable' => false,
                'order'          => 2,
            ],
            [
                'key'            => 'branches_description',
                'value'          => 'نمتد بفروعنا ومندوبينا عبر أكبر المدن السعودية لنكون قريبين منك دائماً.',
                'type'           => 'textarea',
                'group'          => 'branches',
                'label'          => ['en' => 'Branches Description', 'ar' => 'وصف الفروع'],
                'is_translatable' => false,
                'order'          => 3,
            ],
            [
                'key'            => 'branches_cities_count',
                'value'          => '25',
                'type'           => 'number',
                'group'          => 'branches',
                'label'          => ['en' => 'Branches Cities Count', 'ar' => 'عدد مدن التغطية'],
                'is_translatable' => false,
                'order'          => 4,
            ],

            // ── Partners ──────────────────────────────────────────────────────
            [
                'key'            => 'partners_section_title',
                'value'          => 'شركاؤنا',
                'type'           => 'text',
                'group'          => 'partners',
                'label'          => ['en' => 'Partners Section Label', 'ar' => 'تسمية قسم الشركاء'],
                'is_translatable' => false,
                'order'          => 1,
            ],
            [
                'key'            => 'partners_title',
                'value'          => 'شركاؤنا في النجاح',
                'type'           => 'text',
                'group'          => 'partners',
                'label'          => ['en' => 'Partners Title', 'ar' => 'عنوان الشركاء'],
                'is_translatable' => false,
                'order'          => 2,
            ],
            [
                'key'            => 'partners_description',
                'value'          => 'نفخر بشراكاتنا مع كبرى الشركات والمؤسسات في المملكة.',
                'type'           => 'textarea',
                'group'          => 'partners',
                'label'          => ['en' => 'Partners Description', 'ar' => 'وصف الشركاء'],
                'is_translatable' => false,
                'order'          => 3,
            ],

            // ── CTA ───────────────────────────────────────────────────────────
            [
                'key'            => 'cta_title',
                'value'          => 'جاهز لبيت أنظف وأكثر إشراقاً؟',
                'type'           => 'text',
                'group'          => 'cta',
                'label'          => ['en' => 'CTA Title', 'ar' => 'عنوان دعوة الإجراء'],
                'is_translatable' => false,
                'order'          => 1,
            ],
            [
                'key'            => 'cta_subtitle',
                'value'          => 'احجز الآن وستصلك فرقتنا المتخصصة في أقرب وقت',
                'type'           => 'text',
                'group'          => 'cta',
                'label'          => ['en' => 'CTA Subtitle', 'ar' => 'عنوان فرعي دعوة الإجراء'],
                'is_translatable' => false,
                'order'          => 2,
            ],
            [
                'key'            => 'cta_booking_text',
                'value'          => 'احجز الآن',
                'type'           => 'text',
                'group'          => 'cta',
                'label'          => ['en' => 'CTA Booking Button Text', 'ar' => 'نص زر الحجز'],
                'is_translatable' => false,
                'order'          => 3,
            ],
            [
                'key'            => 'cta_booking_link',
                'value'          => '#booking',
                'type'           => 'text',
                'group'          => 'cta',
                'label'          => ['en' => 'CTA Booking Link', 'ar' => 'رابط زر الحجز'],
                'is_translatable' => false,
                'order'          => 4,
            ],
            [
                'key'            => 'cta_calculator_text',
                'value'          => 'احسب السعر',
                'type'           => 'text',
                'group'          => 'cta',
                'label'          => ['en' => 'CTA Calculator Button Text', 'ar' => 'نص زر الحاسبة'],
                'is_translatable' => false,
                'order'          => 5,
            ],
            [
                'key'            => 'cta_calculator_link',
                'value'          => '#booking',
                'type'           => 'text',
                'group'          => 'cta',
                'label'          => ['en' => 'CTA Calculator Link', 'ar' => 'رابط الحاسبة'],
                'is_translatable' => false,
                'order'          => 6,
            ],
            [
                'key'            => 'cta_whatsapp_text',
                'value'          => 'تواصل عبر واتساب',
                'type'           => 'text',
                'group'          => 'cta',
                'label'          => ['en' => 'CTA WhatsApp Button Text', 'ar' => 'نص زر واتساب'],
                'is_translatable' => false,
                'order'          => 7,
            ],
            [
                'key'            => 'cta_whatsapp_link',
                'value'          => 'https://wa.me/966500000000',
                'type'           => 'text',
                'group'          => 'cta',
                'label'          => ['en' => 'CTA WhatsApp Link', 'ar' => 'رابط واتساب'],
                'is_translatable' => false,
                'order'          => 8,
            ],

            // ── Contact ───────────────────────────────────────────────────────
            [
                'key'            => 'contact_phone',
                'value'          => '+966500000000',
                'type'           => 'text',
                'group'          => 'contact',
                'label'          => ['en' => 'Contact Phone', 'ar' => 'رقم الهاتف'],
                'is_translatable' => false,
                'order'          => 1,
            ],
            [
                'key'            => 'contact_email',
                'value'          => 'info@sbc-clean.com',
                'type'           => 'text',
                'group'          => 'contact',
                'label'          => ['en' => 'Contact Email', 'ar' => 'البريد الإلكتروني'],
                'is_translatable' => false,
                'order'          => 2,
            ],
            [
                'key'            => 'contact_whatsapp',
                'value'          => 'https://wa.me/966500000000',
                'type'           => 'text',
                'group'          => 'contact',
                'label'          => ['en' => 'Contact WhatsApp Link', 'ar' => 'رابط واتساب'],
                'is_translatable' => false,
                'order'          => 3,
            ],

            // ── Footer ────────────────────────────────────────────────────────
            [
                'key'            => 'footer_logo',
                'value'          => '',
                'type'           => 'image',
                'group'          => 'footer',
                'label'          => ['en' => 'Footer Logo', 'ar' => 'شعار التذييل'],
                'is_translatable' => false,
                'order'          => 1,
            ],
            [
                'key'            => 'footer_description',
                'value'          => 'شركة SBC كلين — شريكك الموثوق في نظافة المنازل والشركات بالمملكة العربية السعودية.',
                'type'           => 'textarea',
                'group'          => 'footer',
                'label'          => ['en' => 'Footer Description', 'ar' => 'وصف التذييل'],
                'is_translatable' => false,
                'order'          => 2,
            ],
            [
                'key'            => 'footer_address',
                'value'          => 'الرياض، المملكة العربية السعودية',
                'type'           => 'text',
                'group'          => 'footer',
                'label'          => ['en' => 'Footer Address', 'ar' => 'عنوان التذييل'],
                'is_translatable' => false,
                'order'          => 3,
            ],
            [
                'key'            => 'footer_copyright',
                'value'          => '© 2025 SBC كلين — جميع الحقوق محفوظة.',
                'type'           => 'text',
                'group'          => 'footer',
                'label'          => ['en' => 'Footer Copyright', 'ar' => 'حقوق النشر'],
                'is_translatable' => false,
                'order'          => 4,
            ],

            // ── Social ────────────────────────────────────────────────────────
            [
                'key'            => 'social_links',
                'value'          => json_encode([
                    ['platform' => 'instagram', 'url' => '', 'icon' => 'fa-brands fa-instagram'],
                    ['platform' => 'twitter', 'url' => '', 'icon' => 'fa-brands fa-x-twitter'],
                    ['platform' => 'facebook', 'url' => '', 'icon' => 'fa-brands fa-facebook-f'],
                    ['platform' => 'linkedin', 'url' => '', 'icon' => 'fa-brands fa-linkedin-in'],
                ]),
                'type'           => 'json',
                'group'          => 'social',
                'label'          => ['en' => 'Social Links', 'ar' => 'روابط التواصل الاجتماعي'],
                'is_translatable' => false,
                'order'          => 1,
            ],

            // ── Legal ─────────────────────────────────────────────────────────
            [
                'key'            => 'legal_links',
                'value'          => json_encode([
                    ['label' => 'سياسة الخصوصية', 'url' => '/page/privacy'],
                    ['label' => 'الشروط والأحكام', 'url' => '/page/terms'],
                ]),
                'type'           => 'json',
                'group'          => 'legal',
                'label'          => ['en' => 'Legal Links', 'ar' => 'الروابط القانونية'],
                'is_translatable' => false,
                'order'          => 1,
            ],
        ];

        foreach ($settings as $setting) {
            CrmSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('✅ Angular settings seeded: ' . count($settings) . ' keys.');
    }
}
