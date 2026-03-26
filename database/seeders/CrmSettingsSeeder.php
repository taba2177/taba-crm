<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Taba\Crm\Models\CrmSetting;

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

        $settings = [

            // ═══════════════════════════════════════════════════════════
            //  SITE  —  global site identity
            // ═══════════════════════════════════════════════════════════
            [
                'key'   => 'site_name',
                'value' => ['en' => 'SBC Clean', 'ar' => 'SBC كلين'],
                'type'  => 'text',
                'group' => 'site',
                'label' => ['en' => 'Site Name', 'ar' => 'اسم الموقع'],
                'description' => ['en' => 'Name displayed in browser tabs and SEO', 'ar' => 'الاسم المعروض في المتصفح ومحركات البحث'],
                'is_translatable' => true,
                'order' => 1,
            ],
            [
                'key'   => 'site_logo',
                'value' => 'assets/logo.svg',
                'type'  => 'image',
                'group' => 'site',
                'label' => ['en' => 'Site Logo', 'ar' => 'شعار الموقع'],
                'description' => ['en' => 'Main site logo', 'ar' => 'شعار الموقع الرئيسي'],
                'is_translatable' => false,
                'order' => 2,
            ],

            // ═══════════════════════════════════════════════════════════
            //  HERO  —  homepage hero section
            // ═══════════════════════════════════════════════════════════
            [
                'key'   => 'hero_title',
                'value' => ['en' => 'Professional Building Cleaning Services', 'ar' => 'خدمات تنظيف مباني احترافية'],
                'type'  => 'text',
                'group' => 'hero',
                'label' => ['en' => 'Hero Title', 'ar' => 'عنوان البطل'],
                'description' => ['en' => 'Main headline in the hero section', 'ar' => 'العنوان الرئيسي في قسم البطل'],
                'is_translatable' => true,
                'order' => 1,
            ],
            [
                'key'   => 'hero_description',
                'value' => ['en' => 'SBC Clean offers comprehensive cleaning and disinfection services for residential, commercial and industrial properties. Highly trained staff, professional equipment and certified safe products — all at competitive prices.', 'ar' => 'SBC كلين تقدم خدمات تنظيف وتعقيم شاملة للمنشآت السكنية والتجارية والصناعية. كوادر مدربة على أعلى مستوى، معدات احترافية ومنظفات آمنة معتمدة — بأسعار منافسة.'],
                'type'  => 'textarea',
                'group' => 'hero',
                'label' => ['en' => 'Hero Description', 'ar' => 'وصف البطل'],
                'description' => ['en' => 'Description text below hero title', 'ar' => 'النص الوصفي أسفل عنوان البطل'],
                'is_translatable' => true,
                'order' => 2,
            ],
            [
                'key'   => 'hero_cta_text',
                'value' => ['en' => 'Request a Free Quote', 'ar' => 'اطلب عرض سعر مجاني'],
                'type'  => 'text',
                'group' => 'hero',
                'label' => ['en' => 'Hero CTA Text', 'ar' => 'نص زر البطل'],
                'description' => ['en' => 'Call-to-action button text', 'ar' => 'نص زر الإجراء'],
                'is_translatable' => true,
                'order' => 3,
            ],
            [
                'key'   => 'hero_cta_link',
                'value' => 'https://wa.me/966550488892',
                'type'  => 'text',
                'group' => 'hero',
                'label' => ['en' => 'Hero CTA Link', 'ar' => 'رابط زر البطل'],
                'description' => ['en' => 'URL the CTA button links to', 'ar' => 'الرابط الذي يشير إليه الزر'],
                'is_translatable' => false,
                'order' => 4,
            ],
            [
                'key'   => 'hero_background_image',
                'value' => 'assets/hero-bg.jpg',
                'type'  => 'image',
                'group' => 'hero',
                'label' => ['en' => 'Hero Background', 'ar' => 'خلفية البطل'],
                'description' => ['en' => 'Background image for the hero section', 'ar' => 'صورة خلفية قسم البطل'],
                'is_translatable' => false,
                'order' => 5,
            ],

            // ═══════════════════════════════════════════════════════════
            //  ABOUT  —  about section
            // ═══════════════════════════════════════════════════════════
            [
                'key'   => 'about_section_title',
                'value' => ['en' => 'About Us', 'ar' => 'من نحن'],
                'type'  => 'text',
                'group' => 'about',
                'label' => ['en' => 'About Section Title', 'ar' => 'عنوان قسم من نحن'],
                'description' => ['en' => 'Section header for About', 'ar' => 'عنوان القسم'],
                'is_translatable' => true,
                'order' => 1,
            ],
            [
                'key'   => 'about_title',
                'value' => ['en' => 'Building Cleaning Specialists Since Day One', 'ar' => 'متخصصون في تنظيف المباني منذ البداية'],
                'type'  => 'text',
                'group' => 'about',
                'label' => ['en' => 'About Title', 'ar' => 'عنوان من نحن'],
                'description' => ['en' => 'Main about heading', 'ar' => 'العنوان الرئيسي لمن نحن'],
                'is_translatable' => true,
                'order' => 2,
            ],
            [
                'key'   => 'about_description',
                'value' => ['en' => 'SBC Clean (مؤسسة تخصص المباني لأعمال التنظيف) is a leading facility cleaning company based in Riyadh, Saudi Arabia. We specialize in residential, commercial and specialized surface cleaning with highly trained staff operating at the highest levels of service and efficiency. From apartments and villas to corporate offices and building facades, we deliver spotless results using professional equipment and certified safe products.', 'ar' => 'مؤسسة تخصص المباني لأعمال التنظيف (SBC كلين) مؤسسة رائدة في تنظيف المنشآت مقرها الرياض، المملكة العربية السعودية. نتخصص في التنظيف السكني والتجاري والعناية بالأسطح المتخصصة بكوادر مدربة على أعلى مستويات الخدمة والكفاءة. من الشقق والفلل إلى المكاتب التجارية وواجهات المباني، نقدم نتائج مثالية باستخدام معدات احترافية ومنتجات آمنة معتمدة.'],
                'type'  => 'textarea',
                'group' => 'about',
                'label' => ['en' => 'About Description', 'ar' => 'وصف من نحن'],
                'description' => ['en' => 'About section body text', 'ar' => 'النص الرئيسي لقسم من نحن'],
                'is_translatable' => true,
                'order' => 3,
            ],
            [
                'key'   => 'about_stats',
                'value' => json_encode([
                    ['value' => 3000, 'label' => 'عميل سعيد',   'icon' => 'users',    'suffix' => '+'],
                    ['value' => 150,  'label' => 'كادر مدرب',   'icon' => 'award',    'suffix' => '+'],
                    ['value' => 10,   'label' => 'سنوات خبرة',  'icon' => 'calendar', 'suffix' => '+'],
                    ['value' => 15,   'label' => 'شهادة معتمدة', 'icon' => 'shield',   'suffix' => '+'],
                ]),
                'type'  => 'json',
                'group' => 'about',
                'label' => ['en' => 'About Stats', 'ar' => 'إحصائيات من نحن'],
                'description' => ['en' => 'Statistics counters in the about section', 'ar' => 'عدادات الإحصائيات في قسم من نحن'],
                'is_translatable' => false,
                'order' => 4,
            ],

            // ═══════════════════════════════════════════════════════════
            //  SERVICES  —  section headings
            // ═══════════════════════════════════════════════════════════
            [
                'key'   => 'services_section_title',
                'value' => ['en' => 'Our Services', 'ar' => 'خدماتنا'],
                'type'  => 'text',
                'group' => 'sections',
                'label' => ['en' => 'Services Section Title', 'ar' => 'عنوان قسم الخدمات'],
                'description' => ['en' => 'Section header for Services', 'ar' => 'عنوان قسم الخدمات'],
                'is_translatable' => true,
                'order' => 1,
            ],
            [
                'key'   => 'services_title',
                'value' => ['en' => 'Comprehensive Cleaning & Disinfection Solutions', 'ar' => 'حلول تنظيف وتعقيم شاملة'],
                'type'  => 'text',
                'group' => 'sections',
                'label' => ['en' => 'Services Title', 'ar' => 'عنوان الخدمات'],
                'description' => ['en' => 'Main heading for the services section', 'ar' => 'العنوان الرئيسي لقسم الخدمات'],
                'is_translatable' => true,
                'order' => 2,
            ],
            [
                'key'   => 'services_description',
                'value' => ['en' => 'From residential deep cleaning to commercial facility maintenance, we offer a full range of professional cleaning services tailored to your needs', 'ar' => 'من التنظيف العميق للمنازل إلى صيانة المنشآت التجارية، نقدم مجموعة كاملة من خدمات التنظيف الاحترافية المصممة لاحتياجاتكم'],
                'type'  => 'textarea',
                'group' => 'sections',
                'label' => ['en' => 'Services Description', 'ar' => 'وصف الخدمات'],
                'description' => ['en' => 'Subtitle for the services section', 'ar' => 'النص الفرعي لقسم الخدمات'],
                'is_translatable' => true,
                'order' => 3,
            ],

            // ═══════════════════════════════════════════════════════════
            //  FEATURES  —  section headings
            // ═══════════════════════════════════════════════════════════
            [
                'key'   => 'features_section_title',
                'value' => ['en' => 'Why Choose Us', 'ar' => 'لماذا تختارنا'],
                'type'  => 'text',
                'group' => 'sections',
                'label' => ['en' => 'Features Section Title', 'ar' => 'عنوان قسم المميزات'],
                'description' => ['en' => 'Section header for Features', 'ar' => 'عنوان قسم المميزات'],
                'is_translatable' => true,
                'order' => 4,
            ],
            [
                'key'   => 'features_title',
                'value' => ['en' => 'Service Standards That Set Us Apart', 'ar' => 'معايير خدمة تميّزنا عن غيرنا'],
                'type'  => 'text',
                'group' => 'sections',
                'label' => ['en' => 'Features Title', 'ar' => 'عنوان المميزات'],
                'description' => ['en' => 'Main heading for features', 'ar' => 'العنوان الرئيسي للمميزات'],
                'is_translatable' => true,
                'order' => 5,
            ],
            [
                'key'   => 'features_description',
                'value' => ['en' => 'SBC Clean utilizes highly trained staff operating at the highest levels of service and efficiency, backed by professional equipment and safe, certified cleaning products', 'ar' => 'SBC كلين تعمل بكوادر مدربة على أعلى مستويات الخدمة والكفاءة، مدعومة بمعدات احترافية ومنظفات آمنة ومعتمدة'],
                'type'  => 'textarea',
                'group' => 'sections',
                'label' => ['en' => 'Features Description', 'ar' => 'وصف المميزات'],
                'description' => ['en' => 'Subtitle for features section', 'ar' => 'النص الفرعي لقسم المميزات'],
                'is_translatable' => true,
                'order' => 6,
            ],

            // ═══════════════════════════════════════════════════════════
            //  BRANCHES
            // ═══════════════════════════════════════════════════════════
            [
                'key'   => 'branches_section_title',
                'value' => ['en' => 'Our Coverage', 'ar' => 'تغطيتنا'],
                'type'  => 'text',
                'group' => 'sections',
                'label' => ['en' => 'Branches Section Title', 'ar' => 'عنوان قسم التغطية'],
                'description' => ['en' => 'Section header for branches', 'ar' => 'عنوان قسم التغطية'],
                'is_translatable' => true,
                'order' => 7,
            ],
            [
                'key'   => 'branches_title',
                'value' => ['en' => 'Serving Riyadh & Surrounding Areas', 'ar' => 'نخدم الرياض والمناطق المحيطة'],
                'type'  => 'text',
                'group' => 'sections',
                'label' => ['en' => 'Branches Title', 'ar' => 'عنوان الفروع'],
                'description' => ['en' => 'Main heading for branches section', 'ar' => 'العنوان الرئيسي لقسم الفروع'],
                'is_translatable' => true,
                'order' => 8,
            ],
            [
                'key'   => 'branches_description',
                'value' => ['en' => 'Our teams are strategically deployed across Riyadh and surrounding areas to serve residential, commercial and industrial clients with the same high quality and professional standards', 'ar' => 'فرقنا تنتشر بشكل استراتيجي في الرياض والمناطق المحيطة لخدمة العملاء السكنيين والتجاريين والصناعيين بنفس الجودة العالية والمعايير الاحترافية'],
                'type'  => 'textarea',
                'group' => 'sections',
                'label' => ['en' => 'Branches Description', 'ar' => 'وصف الفروع'],
                'description' => ['en' => 'Body text for branches section', 'ar' => 'النص الأساسي لقسم الفروع'],
                'is_translatable' => true,
                'order' => 9,
            ],
            [
                'key'   => 'branches_cities_count',
                'value' => '5',
                'type'  => 'text',
                'group' => 'sections',
                'label' => ['en' => 'Cities Count', 'ar' => 'عدد المدن'],
                'description' => ['en' => 'Number of cities served', 'ar' => 'عدد المدن المخدومة'],
                'is_translatable' => false,
                'order' => 10,
            ],

            // ═══════════════════════════════════════════════════════════
            //  PARTNERS  —  section headings
            // ═══════════════════════════════════════════════════════════
            [
                'key'   => 'partners_section_title',
                'value' => ['en' => 'Our Partners', 'ar' => 'شركاؤنا'],
                'type'  => 'text',
                'group' => 'sections',
                'label' => ['en' => 'Partners Section Title', 'ar' => 'عنوان قسم الشركاء'],
                'description' => ['en' => 'Section header for partners', 'ar' => 'عنوان قسم الشركاء'],
                'is_translatable' => true,
                'order' => 11,
            ],
            [
                'key'   => 'partners_title',
                'value' => ['en' => 'Trusted by Leading Organizations', 'ar' => 'موثوقون من المؤسسات الرائدة'],
                'type'  => 'text',
                'group' => 'sections',
                'label' => ['en' => 'Partners Title', 'ar' => 'عنوان الشركاء'],
                'description' => ['en' => 'Main heading for partners section', 'ar' => 'العنوان الرئيسي لقسم الشركاء'],
                'is_translatable' => true,
                'order' => 12,
            ],
            [
                'key'   => 'partners_description',
                'value' => ['en' => 'We are proud to serve some of the most respected organizations in Saudi Arabia with our professional cleaning services', 'ar' => 'نفتخر بخدمة بعض أبرز المؤسسات في المملكة العربية السعودية بخدمات التنظيف الاحترافية'],
                'type'  => 'textarea',
                'group' => 'sections',
                'label' => ['en' => 'Partners Description', 'ar' => 'وصف الشركاء'],
                'description' => ['en' => 'Subtitle for partners section', 'ar' => 'النص الفرعي لقسم الشركاء'],
                'is_translatable' => true,
                'order' => 13,
            ],

            // ═══════════════════════════════════════════════════════════
            //  CTA  —  call-to-action section
            // ═══════════════════════════════════════════════════════════
            [
                'key'   => 'cta_title',
                'value' => ['en' => 'Ready for a Spotless Space?', 'ar' => 'جاهز لمكان نظيف ولامع؟'],
                'type'  => 'text',
                'group' => 'cta',
                'label' => ['en' => 'CTA Title', 'ar' => 'عنوان الإجراء'],
                'description' => ['en' => 'Call-to-action section title', 'ar' => 'عنوان قسم الإجراء'],
                'is_translatable' => true,
                'order' => 1,
            ],
            [
                'key'   => 'cta_subtitle',
                'value' => ['en' => 'Get a free inspection and quotation today.', 'ar' => 'احصل على معاينة وعرض سعر مجاني اليوم.'],
                'type'  => 'text',
                'group' => 'cta',
                'label' => ['en' => 'CTA Subtitle', 'ar' => 'العنوان الفرعي'],
                'description' => ['en' => 'Subtitle in the CTA section', 'ar' => 'العنوان الفرعي في قسم الإجراء'],
                'is_translatable' => true,
                'order' => 2,
            ],
            [
                'key'   => 'cta_booking_text',
                'value' => ['en' => 'Request a Free Quote', 'ar' => 'اطلب عرض سعر مجاني'],
                'type'  => 'text',
                'group' => 'cta',
                'label' => ['en' => 'CTA Booking Text', 'ar' => 'نص زر الحجز'],
                'description' => ['en' => 'Text for booking button', 'ar' => 'نص زر الحجز'],
                'is_translatable' => true,
                'order' => 3,
            ],
            [
                'key'   => 'cta_booking_link',
                'value' => 'https://wa.me/966550488892',
                'type'  => 'text',
                'group' => 'cta',
                'label' => ['en' => 'CTA Booking Link', 'ar' => 'رابط الحجز'],
                'description' => ['en' => 'URL for booking button', 'ar' => 'رابط زر الحجز'],
                'is_translatable' => false,
                'order' => 4,
            ],
            [
                'key'   => 'cta_calculator_text',
                'value' => ['en' => 'View Our Services', 'ar' => 'تعرف على خدماتنا'],
                'type'  => 'text',
                'group' => 'cta',
                'label' => ['en' => 'CTA Calculator Text', 'ar' => 'نص زر الخدمات'],
                'description' => ['en' => 'Text for services button', 'ar' => 'نص زر الخدمات'],
                'is_translatable' => true,
                'order' => 5,
            ],
            [
                'key'   => 'cta_calculator_link',
                'value' => '#services',
                'type'  => 'text',
                'group' => 'cta',
                'label' => ['en' => 'CTA Calculator Link', 'ar' => 'رابط الخدمات'],
                'description' => ['en' => 'URL for services button', 'ar' => 'رابط زر الخدمات'],
                'is_translatable' => false,
                'order' => 6,
            ],
            [
                'key'   => 'cta_whatsapp_text',
                'value' => ['en' => 'Chat on WhatsApp', 'ar' => 'تواصل عبر واتساب'],
                'type'  => 'text',
                'group' => 'cta',
                'label' => ['en' => 'CTA WhatsApp Text', 'ar' => 'نص واتساب'],
                'description' => ['en' => 'WhatsApp button text', 'ar' => 'نص زر واتساب'],
                'is_translatable' => true,
                'order' => 7,
            ],
            [
                'key'   => 'cta_whatsapp_link',
                'value' => 'https://wa.me/966550488892',
                'type'  => 'text',
                'group' => 'cta',
                'label' => ['en' => 'CTA WhatsApp Link', 'ar' => 'رابط واتساب'],
                'description' => ['en' => 'WhatsApp chat URL', 'ar' => 'رابط محادثة واتساب'],
                'is_translatable' => false,
                'order' => 8,
            ],

            // ═══════════════════════════════════════════════════════════
            //  CONTACT  —  company contact information
            // ═══════════════════════════════════════════════════════════
            [
                'key'   => 'contact_phone',
                'value' => '+966 550 488 892',
                'type'  => 'text',
                'group' => 'contact',
                'label' => ['en' => 'Phone Number', 'ar' => 'رقم الهاتف'],
                'description' => ['en' => 'Business phone number', 'ar' => 'رقم هاتف العمل'],
                'is_translatable' => false,
                'order' => 1,
            ],
            [
                'key'   => 'contact_email',
                'value' => 'info@sbc-clean.com',
                'type'  => 'text',
                'group' => 'contact',
                'label' => ['en' => 'Email Address', 'ar' => 'البريد الإلكتروني'],
                'description' => ['en' => 'Business email', 'ar' => 'البريد الإلكتروني للعمل'],
                'is_translatable' => false,
                'order' => 2,
            ],
            [
                'key'   => 'contact_whatsapp',
                'value' => '966550488892',
                'type'  => 'text',
                'group' => 'contact',
                'label' => ['en' => 'WhatsApp Number', 'ar' => 'رقم واتساب'],
                'description' => ['en' => 'WhatsApp number (without +)', 'ar' => 'رقم واتساب (بدون +)'],
                'is_translatable' => false,
                'order' => 3,
            ],
            [
                'key'   => 'contact_address',
                'value' => ['en' => 'Al Yarmouk District, Riyadh, Saudi Arabia', 'ar' => 'حي اليرموك، الرياض، المملكة العربية السعودية'],
                'type'  => 'text',
                'group' => 'contact',
                'label' => ['en' => 'Address', 'ar' => 'العنوان'],
                'description' => ['en' => 'Business address', 'ar' => 'عنوان العمل'],
                'is_translatable' => true,
                'order' => 4,
            ],
            [
                'key'   => 'contact_latitude',
                'value' => '24.7806',
                'type'  => 'text',
                'group' => 'contact',
                'label' => ['en' => 'Latitude', 'ar' => 'خط العرض'],
                'description' => ['en' => 'Map latitude', 'ar' => 'خط العرض للخريطة'],
                'is_translatable' => false,
                'order' => 5,
            ],
            [
                'key'   => 'contact_longitude',
                'value' => '46.7380',
                'type'  => 'text',
                'group' => 'contact',
                'label' => ['en' => 'Longitude', 'ar' => 'خط الطول'],
                'description' => ['en' => 'Map longitude', 'ar' => 'خط الطول للخريطة'],
                'is_translatable' => false,
                'order' => 6,
            ],

            // ═══════════════════════════════════════════════════════════
            //  FOOTER
            // ═══════════════════════════════════════════════════════════
            [
                'key'   => 'footer_logo',
                'value' => 'assets/logo.svg',
                'type'  => 'image',
                'group' => 'footer',
                'label' => ['en' => 'Footer Logo', 'ar' => 'شعار الذيل'],
                'description' => ['en' => 'Logo in the footer', 'ar' => 'الشعار في ذيل الموقع'],
                'is_translatable' => false,
                'order' => 1,
            ],
            [
                'key'   => 'footer_description',
                'value' => ['en' => 'SBC Clean – Building Cleaning Specialists. We provide professional cleaning, disinfection and specialized surface care services for residential, commercial and industrial facilities across Riyadh.', 'ar' => 'SBC كلين – متخصصون في تنظيف المباني. نقدم خدمات تنظيف وتعقيم وعناية بالأسطح المتخصصة للمنشآت السكنية والتجارية والصناعية في أنحاء الرياض.'],
                'type'  => 'textarea',
                'group' => 'footer',
                'label' => ['en' => 'Footer Description', 'ar' => 'وصف الذيل'],
                'description' => ['en' => 'Short description in footer', 'ar' => 'وصف مختصر في الذيل'],
                'is_translatable' => true,
                'order' => 2,
            ],
            [
                'key'   => 'footer_address',
                'value' => ['en' => 'Al Yarmouk District, Riyadh, Saudi Arabia', 'ar' => 'حي اليرموك، الرياض، المملكة العربية السعودية'],
                'type'  => 'text',
                'group' => 'footer',
                'label' => ['en' => 'Footer Address', 'ar' => 'عنوان الذيل'],
                'description' => ['en' => 'Address shown in footer', 'ar' => 'العنوان المعروض في الذيل'],
                'is_translatable' => true,
                'order' => 3,
            ],
            [
                'key'   => 'footer_copyright',
                'value' => ['en' => '© 2026 SBC Clean. All rights reserved.', 'ar' => '© 2026 SBC كلين. جميع الحقوق محفوظة.'],
                'type'  => 'text',
                'group' => 'footer',
                'label' => ['en' => 'Copyright Text', 'ar' => 'نص حقوق النشر'],
                'description' => ['en' => 'Copyright line in footer', 'ar' => 'نص حقوق النشر في الذيل'],
                'is_translatable' => true,
                'order' => 4,
            ],
            [
                'key'   => 'social_links',
                'value' => json_encode([
                    ['platform' => 'instagram', 'url' => 'https://www.instagram.com/sbc_clean', 'icon' => 'instagram'],
                    ['platform' => 'twitter',   'url' => 'https://x.com/sbc_clean',             'icon' => 'twitter'],
                    ['platform' => 'snapchat',  'url' => 'https://snapchat.com/add/sbc_clean',  'icon' => 'snapchat'],
                    ['platform' => 'tiktok',    'url' => 'https://www.tiktok.com/@sbc_clean',   'icon' => 'tiktok'],
                ]),
                'type'  => 'json',
                'group' => 'footer',
                'label' => ['en' => 'Social Links', 'ar' => 'روابط التواصل'],
                'description' => ['en' => 'Social media links (JSON array)', 'ar' => 'روابط وسائل التواصل (مصفوفة JSON)'],
                'is_translatable' => false,
                'order' => 5,
            ],
            [
                'key'   => 'legal_links',
                'value' => json_encode([
                    ['label' => 'الشروط والأحكام',    'url' => '/terms'],
                    ['label' => 'سياسة الخصوصية',     'url' => '/privacy'],
                    ['label' => 'المقترحات والشكاوى', 'url' => '/complaint'],
                ]),
                'type'  => 'json',
                'group' => 'footer',
                'label' => ['en' => 'Legal Links', 'ar' => 'روابط قانونية'],
                'description' => ['en' => 'Legal / policy links (JSON array)', 'ar' => 'روابط قانونية / سياسات (مصفوفة JSON)'],
                'is_translatable' => false,
                'order' => 6,
            ],

            // ═══════════════════════════════════════════════════════════
            //  SEO
            // ═══════════════════════════════════════════════════════════
            [
                'key'   => 'seo_default_title',
                'value' => ['en' => 'SBC Clean – Professional Building Cleaning Services in Riyadh', 'ar' => 'SBC كلين – خدمات تنظيف مباني احترافية في الرياض'],
                'type'  => 'text',
                'group' => 'seo',
                'label' => ['en' => 'Default SEO Title', 'ar' => 'عنوان SEO الافتراضي'],
                'description' => ['en' => 'Default meta title for pages', 'ar' => 'عنوان الميتا الافتراضي للصفحات'],
                'is_translatable' => true,
                'order' => 1,
            ],
            [
                'key'   => 'seo_default_description',
                'value' => ['en' => 'Professional cleaning, disinfection and surface care services for homes, offices and commercial buildings in Riyadh. Free inspection. Call +966 550 488 892.', 'ar' => 'خدمات تنظيف وتعقيم وعناية بالأسطح احترافية للمنازل والمكاتب والمباني التجارية في الرياض. معاينة مجانية. اتصل +966 550 488 892.'],
                'type'  => 'textarea',
                'group' => 'seo',
                'label' => ['en' => 'Default SEO Description', 'ar' => 'وصف SEO الافتراضي'],
                'description' => ['en' => 'Default meta description', 'ar' => 'وصف الميتا الافتراضي'],
                'is_translatable' => true,
                'order' => 2,
            ],

            // ═══════════════════════════════════════════════════════════
            //  BUSINESS  —  operational details
            // ═══════════════════════════════════════════════════════════
            [
                'key'   => 'business_name',
                'value' => ['en' => 'SBC Clean', 'ar' => 'مؤسسة تخصص المباني لأعمال التنظيف'],
                'type'  => 'text',
                'group' => 'business',
                'label' => ['en' => 'Legal Business Name', 'ar' => 'الاسم القانوني'],
                'description' => ['en' => 'Official registered business name', 'ar' => 'الاسم القانوني المسجل'],
                'is_translatable' => true,
                'order' => 1,
            ],
            [
                'key'   => 'business_website',
                'value' => 'https://www.sbc-clean.com',
                'type'  => 'text',
                'group' => 'business',
                'label' => ['en' => 'Website URL', 'ar' => 'رابط الموقع'],
                'description' => ['en' => 'Official website address', 'ar' => 'عنوان الموقع الرسمي'],
                'is_translatable' => false,
                'order' => 2,
            ],
            [
                'key'   => 'business_opens',
                'value' => '08:00',
                'type'  => 'text',
                'group' => 'business',
                'label' => ['en' => 'Opening Time', 'ar' => 'وقت الفتح'],
                'description' => ['en' => 'Business opening time', 'ar' => 'وقت فتح العمل'],
                'is_translatable' => false,
                'order' => 3,
            ],
            [
                'key'   => 'business_closes',
                'value' => '20:00',
                'type'  => 'text',
                'group' => 'business',
                'label' => ['en' => 'Closing Time', 'ar' => 'وقت الإغلاق'],
                'description' => ['en' => 'Business closing time', 'ar' => 'وقت إغلاق العمل'],
                'is_translatable' => false,
                'order' => 4,
            ],
            [
                'key'   => 'business_price_range',
                'value' => 'SAR 200 - SAR 15000',
                'type'  => 'text',
                'group' => 'business',
                'label' => ['en' => 'Price Range', 'ar' => 'النطاق السعري'],
                'description' => ['en' => 'Service price range', 'ar' => 'النطاق السعري للخدمات'],
                'is_translatable' => false,
                'order' => 5,
            ],

            // ═══════════════════════════════════════════════════════════
            //  API  —  third-party keys
            // ═══════════════════════════════════════════════════════════
            [
                'key'   => 'gemini_api_key',
                'value' => env('GEMINI_API_KEY', ''),
                'type'  => 'text',
                'group' => 'api',
                'label' => ['en' => 'Gemini API Key', 'ar' => 'مفتاح Gemini API'],
                'description' => ['en' => 'Google Gemini AI API key', 'ar' => 'مفتاح Google Gemini AI'],
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

        $this->command->info('✅ CRM settings seeded with SBC Clean data.');
    }
}
