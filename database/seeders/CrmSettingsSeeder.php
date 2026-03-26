<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Taba\Crm\Models\CrmSetting;
use Taba\Crm\Models\Post;
use Taba\Crm\Models\PostCategory;

class CrmSettingsSeeder extends Seeder
{
    public function run(): void
    {
        // Check if CRM settings already exist
        $existingCount = CrmSetting::count();
        if ($existingCount > 0) {
            $this->command->info("⏭️  Skipped: CRM settings already exist ({$existingCount} records). Keeping existing settings.");
            return;
        }

        // Get first post and category for defaults
        $firstPost = Post::where('show_in_home', true)
            ->published()
            ->orderBy('order', 'asc')
            ->first();

        $firstCategory = PostCategory::orderBy('order', 'asc')->first();

        // Contact Information
        $settings = [
            // Contact Group
            [
                'key' => 'crm_contact_phone',
                'value' => '+966500000000',
                'type' => 'text',
                'group' => 'contact',
                'label' => ['en' => 'Phone Number', 'ar' => 'رقم الهاتف'],
                'description' => ['en' => 'Business contact phone number', 'ar' => 'رقم هاتف العمل'],
                'is_translatable' => false,
                'order' => 1,
            ],
            [
                'key' => 'crm_contact_email',
                'value' => 'info@example.com',
                'type' => 'text',
                'group' => 'contact',
                'label' => ['en' => 'Email Address', 'ar' => 'البريد الإلكتروني'],
                'description' => ['en' => 'Business contact email', 'ar' => 'البريد الإلكتروني للعمل'],
                'is_translatable' => false,
                'order' => 2,
            ],
            [
                'key' => 'crm_contact_address',
                'value' => ['en' => '', 'ar' => ''],
                'type' => 'text',
                'group' => 'contact',
                'label' => ['en' => 'Street Address', 'ar' => 'عنوان الشارع'],
                'description' => ['en' => 'Business street address', 'ar' => 'عنوان الشارع للعمل'],
                'is_translatable' => true,
                'order' => 3,
            ],
            [
                'key' => 'crm_contact_city',
                'value' => ['en' => 'Riyadh', 'ar' => 'الرياض'],
                'type' => 'text',
                'group' => 'contact',
                'label' => ['en' => 'City', 'ar' => 'المدينة'],
                'description' => ['en' => 'Business city', 'ar' => 'مدينة العمل'],
                'is_translatable' => true,
                'order' => 4,
            ],
            [
                'key' => 'crm_contact_postal_code',
                'value' => '',
                'type' => 'text',
                'group' => 'contact',
                'label' => ['en' => 'Postal Code', 'ar' => 'الرمز البريدي'],
                'description' => ['en' => 'Business postal code', 'ar' => 'الرمز البريدي للعمل'],
                'is_translatable' => false,
                'order' => 5,
            ],
            [
                'key' => 'crm_contact_latitude',
                'value' => '24.774265',
                'type' => 'text',
                'group' => 'contact',
                'label' => ['en' => 'Latitude', 'ar' => 'خط العرض'],
                'description' => ['en' => 'Location latitude coordinate', 'ar' => 'إحداثي خط العرض'],
                'is_translatable' => false,
                'order' => 6,
            ],
            [
                'key' => 'crm_contact_longitude',
                'value' => '46.738586',
                'type' => 'text',
                'group' => 'contact',
                'label' => ['en' => 'Longitude', 'ar' => 'خط الطول'],
                'description' => ['en' => 'Location longitude coordinate', 'ar' => 'إحداثي خط الطول'],
                'is_translatable' => false,
                'order' => 7,
            ],

            // Social Links
            [
                'key' => 'crm_contact_facebook',
                'value' => '',
                'type' => 'text',
                'group' => 'social',
                'label' => ['en' => 'Facebook URL', 'ar' => 'رابط فيسبوك'],
                'description' => ['en' => 'Facebook page URL', 'ar' => 'رابط صفحة فيسبوك'],
                'is_translatable' => false,
                'order' => 1,
            ],
            [
                'key' => 'crm_contact_twitter',
                'value' => '',
                'type' => 'text',
                'group' => 'social',
                'label' => ['en' => 'Twitter/X URL', 'ar' => 'رابط تويتر/X'],
                'description' => ['en' => 'Twitter/X profile URL', 'ar' => 'رابط حساب تويتر/X'],
                'is_translatable' => false,
                'order' => 2,
            ],
            [
                'key' => 'crm_contact_instagram',
                'value' => '',
                'type' => 'text',
                'group' => 'social',
                'label' => ['en' => 'Instagram URL', 'ar' => 'رابط إنستجرام'],
                'description' => ['en' => 'Instagram profile URL', 'ar' => 'رابط حساب إنستجرام'],
                'is_translatable' => false,
                'order' => 3,
            ],
            [
                'key' => 'crm_contact_linkedin',
                'value' => '',
                'type' => 'text',
                'group' => 'social',
                'label' => ['en' => 'LinkedIn URL', 'ar' => 'رابط لينكد إن'],
                'description' => ['en' => 'LinkedIn company URL', 'ar' => 'رابط صفحة لينكد إن'],
                'is_translatable' => false,
                'order' => 4,
            ],
            [
                'key' => 'crm_contact_youtube',
                'value' => '',
                'type' => 'text',
                'group' => 'social',
                'label' => ['en' => 'YouTube URL', 'ar' => 'رابط يوتيوب'],
                'description' => ['en' => 'YouTube channel URL', 'ar' => 'رابط قناة يوتيوب'],
                'is_translatable' => false,
                'order' => 5,
            ],

            // Business Information
            [
                'key' => 'crm_business_name',
                'value' => ['en' => config('app.name'), 'ar' => config('app.name')],
                'type' => 'text',
                'group' => 'business',
                'label' => ['en' => 'Business Name', 'ar' => 'اسم العمل'],
                'description' => ['en' => 'Your business or company name', 'ar' => 'اسم عملك أو شركتك'],
                'is_translatable' => true,
                'order' => 1,
            ],
            [
                'key' => 'crm_business_logo',
                'value' => '',
                'type' => 'image',
                'group' => 'business',
                'label' => ['en' => 'Business Logo', 'ar' => 'شعار العمل'],
                'description' => ['en' => 'Company logo image', 'ar' => 'صورة شعار الشركة'],
                'is_translatable' => false,
                'order' => 2,
            ],
            [
                'key' => 'crm_business_favicon',
                'value' => '',
                'type' => 'image',
                'group' => 'business',
                'label' => ['en' => 'Favicon', 'ar' => 'أيقونة المفضلة'],
                'description' => ['en' => 'Website favicon (small icon)', 'ar' => 'أيقونة الموقع الصغيرة'],
                'is_translatable' => false,
                'order' => 3,
            ],
            [
                'key' => 'crm_business_price_range',
                'value' => '',
                'type' => 'text',
                'group' => 'business',
                'label' => ['en' => 'Price Range', 'ar' => 'النطاق السعري'],
                'description' => ['en' => 'Business price range (e.g., SAR 500 - SAR 20000)', 'ar' => 'النطاق السعري للخدمات (مثال: 500 - 20000 ريال)'],
                'is_translatable' => false,
                'order' => 4,
            ],
            [
                'key' => 'crm_business_opens',
                'value' => '09:00',
                'type' => 'text',
                'group' => 'business',
                'label' => ['en' => 'Opening Time', 'ar' => 'وقت الفتح'],
                'description' => ['en' => 'Business opening time (24-hour format)', 'ar' => 'وقت فتح العمل (صيغة 24 ساعة)'],
                'is_translatable' => false,
                'order' => 5,
            ],
            [
                'key' => 'crm_business_closes',
                'value' => '18:00',
                'type' => 'text',
                'group' => 'business',
                'label' => ['en' => 'Closing Time', 'ar' => 'وقت الإغلاق'],
                'description' => ['en' => 'Business closing time (24-hour format)', 'ar' => 'وقت إغلاق العمل (صيغة 24 ساعة)'],
                'is_translatable' => false,
                'order' => 6,
            ],

            // SEO Defaults (from first post/category)
            [
                'key' => 'crm_seo_default_title',
                'value' => [
                    'en' => $firstPost?->meta_title ?? $firstPost?->title ?? $firstCategory?->name ?? config('app.name'),
                    'ar' => $firstPost?->meta_title ?? $firstPost?->title ?? $firstCategory?->name ?? config('app.name'),
                ],
                'type' => 'text',
                'group' => 'seo',
                'label' => ['en' => 'Default SEO Title', 'ar' => 'عنوان SEO الافتراضي'],
                'description' => ['en' => 'Default meta title for pages', 'ar' => 'عنوان الميتا الافتراضي للصفحات'],
                'is_translatable' => true,
                'order' => 1,
            ],
            [
                'key' => 'crm_seo_default_description',
                'value' => [
                    'en' => $firstPost?->meta_description ?? $firstCategory?->description ?? 'Professional services and solutions',
                    'ar' => $firstPost?->meta_description ?? $firstCategory?->description ?? 'خدمات وحلول احترافية',
                ],
                'type' => 'textarea',
                'group' => 'seo',
                'label' => ['en' => 'Default SEO Description', 'ar' => 'وصف SEO الافتراضي'],
                'description' => ['en' => 'Default meta description for pages', 'ar' => 'وصف الميتا الافتراضي للصفحات'],
                'is_translatable' => true,
                'order' => 2,
            ],

            // API Keys
            [
                'key' => 'crm_gemini_api_key',
                'value' => env('GEMINI_API_KEY', ''),
                'type' => 'text',
                'group' => 'api',
                'label' => ['en' => 'Gemini API Key', 'ar' => 'مفتاح Gemini API'],
                'description' => ['en' => 'Google Gemini AI API key for AI features', 'ar' => 'مفتاح Google Gemini AI للميزات الذكية'],
                'is_translatable' => false,
                'order' => 1,
            ],
        ];

        foreach ($settings as $setting) {
            CrmSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
