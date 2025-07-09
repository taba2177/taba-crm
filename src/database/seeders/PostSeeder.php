<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post; // Ensure you have an App\Models\Post model
use App\Models\PostCategory; // Ensure you have an App\Models\PostCategory model
use Faker\Factory as FakerFactory; // Import FakerFactory for Arabic content
use Illuminate\Support\Str; // Import Str facade for slug generation

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Initialize Faker for Saudi Arabic locale to generate realistic Arabic text.
        $arabicFaker = FakerFactory::create('ar_SA');

        // Retrieve category IDs dynamically to ensure correct assignment,
        // even if IDs change in the future.
        $servicesCategoryId = PostCategory::where('slug', 'services')->first()->id;
        $ourWorksCategoryId = PostCategory::where('slug', 'our_works')->first()->id;
        $blogsCategoryId = PostCategory::where('slug', 'blogs')->first()->id;
        $faqsCategoryId = PostCategory::where('slug', 'faqs')->first()->id;
        $pricesCategoryId = PostCategory::where('slug', 'prices')->first()->id;

        // --- Seed Services Posts (الخدمات) ---
        // Create specific posts detailing the services offered by "بطور ماركتينج".
        $servicesContent = [
            [
                'title' => 'بناء العلامات التجارية والهوية البصرية',
                'content' => 'نقدم حلولاً متكاملة لبناء علامات تجارية قوية وهوية بصرية فريدة تعكس قيم شركتك وتترك انطباعاً لا يُنسى لدى جمهورك المستهدف. من تصميم الشعار إلى دليل الهوية الكامل، نضمن لك حضوراً بصرياً مميزاً.',
            ],
            [
                'title' => 'تصميم وتطوير المواقع والمتاجر الإلكترونية',
                'content' => 'نقوم بإنشاء مواقع ويب ومتاجر إلكترونية عصرية، متجاوبة، وسهلة الاستخدام، مصممة خصيصاً لتلبية احتياجات عملك وتحقيق أهدافك الرقمية. نركز على تجربة المستخدم والأداء العالي.',
            ],
            [
                'title' => 'إدارة حسابات التواصل الاجتماعي',
                'content' => 'نتولى إدارة حساباتك على منصات التواصل الاجتماعي الرئيسية (انستجرام، تويتر، سناب شات، لينكد إن، فيسبوك) بشكل احترافي. يشمل ذلك تخطيط المحتوى، التصميم، النشر اليومي، التفاعل مع الجمهور، وتحليل الأداء لزيادة الوعي بعلامتك التجارية.',
            ],
            [
                'title' => 'كتابة المحتوى الإبداعي',
                'content' => 'نقدم خدمات كتابة المحتوى الجذاب والمؤثر لموقعك الإلكتروني، مدونتك، حملاتك التسويقية، ومنصات التواصل الاجتماعي. محتوانا مصمم لجذب الانتباه، إثراء القراء، وتحويل الزوار إلى عملاء.',
            ],
            [
                'title' => 'التسويق الإلكتروني عبر محركات البحث (SEO & SEM)',
                'content' => 'نساعدك على الظهور في صدارة نتائج محركات البحث (جوجل) من خلال استراتيجيات تحسين محركات البحث (SEO) الفعالة وإدارة حملات الإعلانات المدفوعة (SEM)، لزيادة الزيارات المستهدفة لموقعك وتحقيق أقصى عائد على الاستثمار.',
            ],
            [
                'title' => 'إدارة المشاريع الرقمية',
                'content' => 'نقدم خدمات إدارة المشاريع الرقمية لضمان تنفيذ حملاتك ومشاريعك بكفاءة، في الوقت المحدد، وضمن الميزانية. نركز على التخطيط، التنفيذ، المراقبة، والتحسين المستمر.',
            ],
            [
                'title' => 'تصوير المنتجات الاحترافي',
                'content' => 'نلتقط صوراً احترافية لمنتجاتك تبرز جمالها وجودتها، مما يساعد على جذب العملاء وزيادة المبيعات في متجرك الإلكتروني أو حملاتك التسويقية.',
            ],
            [
                'title' => 'تصميم الشعارات الاحترافية',
                'content' => 'نصمم شعارات فريدة ومبتكرة تعبر عن جوهر علامتك التجارية وتترك انطباعاً قوياً لا يُنسى.',
            ],
            [
                'title' => 'تصميم الموشن جرافيك',
                'content' => 'نحول أفكارك إلى فيديوهات موشن جرافيك جذابة ومتحركة، مثالية لشرح الخدمات، عرض المنتجات، أو سرد قصص علامتك التجارية بطريقة إبداعية ومؤثرة.',
            ],
            [
                'title' => 'تصميم المطبوعات الإبداعية',
                'content' => 'نصمم جميع أنواع المطبوعات الاحترافية مثل الكروت الشخصية، البروشورات، الفلايرات، والكتيبات، بتصاميم جذابة تعزز من هويتك البصرية.',
            ],
            [
                'title' => 'تصميم البوسترات الإعلانية',
                'content' => 'نصمم بوسترات إعلانية لافتة للنظر، سواء للمناسبات، المنتجات، أو الخدمات، تضمن وصول رسالتك بفعالية إلى الجمهور المستهدف.',
            ],
            [
                'title' => 'تصميم البروفايلات التعريفية',
                'content' => 'نساعدك في إعداد وتصميم بروفايلات تعريفية احترافية لشركتك أو مشروعك، تعرض خدماتك، قيمك، وإنجازاتك بطريقة منظمة وجذابة.',
            ],
        ];

        foreach ($servicesContent as $item) {
            Post::factory()->create([
                'title' => $item['title'],
                'slug' => Str::slug($item['title']), // Generate SEO-friendly slug
                'content' => [['type' => 'markdown', 'data' => ['content' => $item['content']]]],
                'user_id' => 1, // Assuming a user with ID 1 exists. Adjust if necessary.
                'is_published' => true,
                'published_at' => $arabicFaker->dateTimeBetween('-1 year', 'now'),
                'post_category_id' => $servicesCategoryId,
            ]);
        }

        // --- Seed Our Works Posts (اعمالنا) ---
        // Showcase examples of projects or successful campaigns.
        $ourWorksContent = [
            [
            'title' => 'حملة تسويقية متكاملة لمتجر إلكتروني',
            'content' => 'قمنا بتصميم وتنفيذ حملة تسويقية شاملة لمتجر إلكتروني متخصص في بيع المنتجات اليدوية، شملت إدارة حسابات التواصل الاجتماعي، إعلانات جوجل، وكتابة محتوى جذاب، مما أدى إلى زيادة المبيعات بنسبة 40% في 3 أشهر.',
            'metadata' => [
            'key' => 'service',
            'value' => 'إدارة حسابات',
            ],
            ],
            [
            'title' => 'تحسين تجربة المستخدم لمتجر إلكتروني',
            'content' => 'قمننا بتحسين تجربة المستخدم لمتجر إلكتروني لزيادة المبيعات.',
            'metadata' => [
            'key' => 'service',
            'value' => 'تطوير مواقع',
            ],
            ],
            [
            'title' => 'تصوير منتجات لمتجر الكتروني',
            'content' => 'تصوير منتجات احترافي لمتجر الكتروني لزيادة المبيعات',
            'metadata' => [
            'key' => 'service',
            'value' => 'تصوير المنتجات',
            ],
            ],
            [
            'title' => 'إعادة تصميم هوية بصرية لشركة استشارات',
            'content' => 'عملنا على إعادة تصميم الهوية البصرية الكاملة لشركة استشارات رائدة، بما في ذلك الشعار، الألوان، الخطوط، والمطبوعات، لتعكس التطور والاحترافية التي وصلت إليها الشركة.',
            'metadata' => [
            'key' => 'service',
            'value' => 'هوية بصرية',
            ],
            ],
            [
            'title' => 'تصميم شعار لشركة استشارات',
            'content' => 'تصميم شعار احترافي لشركة استشارات',
            'metadata' => [
            'key' => 'service',
            'value' => 'تصميم شعار',
            ],
            ],
            [
            'title' => 'تصميم فلاير لشركة استشارات',
            'content' => 'تصميم فلاير احترافي لشركة استشارات',
            'metadata' => [
            'key' => 'service',
            'value' => 'مطبوعات إبداعية',
            ],
            ],
            [
            'title' => 'تطوير موقع إلكتروني لشركة عقارية',
            'content' => 'قمنا بتطوير موقع إلكتروني حديث ومتجاوب لشركة عقارية، يتيح عرض المشاريع، البحث عن العقارات، والتواصل المباشر مع العملاء، مع تحسين تجربة المستخدم وسهولة التصفح.',
            'metadata' => [
            'key' => 'service',
            'value' => 'تطوير مواقع',
            ],
            ],
            [
            'title' => 'تصميم موقع لشركة عقارية',
            'content' => 'تصميم موقع لشركة عقارية مع لوحة تحكم',
            'metadata' => [
            'key' => 'service',
            'value' => 'تصميم مواقع',
            ],
            ],
            [
            'title' => 'تطوير تطبيق لشركة عقارية',
            'content' => 'تطوير تطبيق لشركة عقارية مع لوحة تحكم',
            'metadata' => [
            'key' => 'service',
            'value' => 'تطوير تطبيقات',
            ],
            ],
            [
            'title' => 'حملة إعلانية ناجحة لمنتج جديد',
            'content' => 'أطلقنا حملة إعلانية مبتكرة لمنتج جديد في السوق، باستخدام استراتيجيات استهداف دقيقة عبر منصات التواصل الاجتماعي، مما حقق وعياً كبيراً بالمنتج وزيادة في الطلب عليه.',
            'metadata' => [
            'key' => 'service',
            'value' => 'تسويق الكتروني',
            ],
            ],
            [
            'title' => 'حملة اعلانية علي السوشيال ميديا',
            'content' => 'حملة اعلانية علي السوشيال ميديا لمنتج جديد',
            'metadata' => [
            'key' => 'service',
            'value' => 'إدارة حسابات',
            ],
            ],
            [
            'title' => 'حملة اعلانية علي جوجل',
            'content' => 'حملة اعلانية علي جوجل لمنتج جديد',
            'metadata' => [
            'key' => 'service',
            'value' => 'تسويق الكتروني',
            ],
            ],
            [
            'title' => 'إدارة محتوى سوشيال ميديا لمطعم',
            'content' => 'تولينا مسؤولية إدارة محتوى حسابات التواصل الاجتماعي لمطعم شهير، من خلال تصميم منشورات جذابة، وتنظيم مسابقات، والتفاعل مع المتابعين، مما عزز من حضور المطعم الرقمي وزاد من عدد الزوار.',
            'metadata' => [
            'key' => 'service',
            'value' => 'إدارة حسابات',
            ],
            ],
            [
            'title' => 'تصوير منتجات لمطعم',
            'content' => 'تصوير منتجات احترافي لمطعم',
            'metadata' => [
            'key' => 'service',
            'value' => 'تصوير المنتجات',
            ],
            ],
            [
            'title' => 'حملة اعلانية لمطعم',
            'content' => 'حملة اعلانية لمطعم علي السوشيال ميديا',
            'metadata' => [
            'key' => 'service',
            'value' => 'إدارة حسابات',
            ],
            ],
            [
            'title' => 'تصوير المنتجات',
            'content' => 'تصوير المنتجات باحترافية عالية',
            'metadata' => [
            'key' => 'service',
            'value' => 'تصوير المنتجات',
            ],
            ],
            [
            'title' => 'تصوير المنتجات',
            'content' => 'تصوير المنتجات باحترافية عالية',
            'metadata' => [
            'key' => 'service',
            'value' => 'تصوير المنتجات',
            ],
            ],
            [
            'title' => 'تصوير المنتجات',
            'content' => 'تصوير المنتجات باحترافية عالية',
            'metadata' => [
            'key' => 'service',
            'value' => 'تصوير المنتجات',
            ],
            ],
        ];


        foreach ($ourWorksContent as $item) {
            $slug = Str::slug($item['title']);
            $count = Post::where('slug', 'like', $slug . '%')->where('post_category_id', $ourWorksCategoryId)->count();
            if ($count > 0) {
                $slug .= '-' . ($count + 1);
            }
            Post::factory()->create([
            'title' => $item['title'],
            'slug' => $slug,
            'content' => [['type' => 'markdown', 'data' => ['content' => $item['content']]]],
            'user_id' => 1,
            'is_published' => true,
            'published_at' => $arabicFaker->dateTimeBetween('-1 year', 'now'),
            'post_category_id' => $ourWorksCategoryId,
            'metadata' => [$item['metadata']],
            ]);
        }

        // --- Seed Blog Posts (المدونة) ---
        // Generate a good number of general blog posts with varied content.
        for ($i = 0; $i < 15; $i++) {
            $contentBlocks = [
                $arabicFaker->realText(250),
                "## " . $arabicFaker->realText(40), // Main heading
                $arabicFaker->realText(400),
                "### " . $arabicFaker->realText(35), // Sub-heading
                $arabicFaker->realText(300),
                "## " . $arabicFaker->realText(50), // Another main heading
                $arabicFaker->realText(350),
            ];
            Post::factory()->create([
                'title' => $arabicFaker->realText(30),
                'slug' => $arabicFaker->slug(), // Use Faker's slug for more diverse blog slugs
                'content' => [['type' => 'markdown', 'data' => ['content' => implode("\n\n", $contentBlocks)]]],
                'user_id' => 1,
                'is_published' => true,
                'published_at' => $arabicFaker->dateTimeBetween('-1 year', 'now'),
                'post_category_id' => $blogsCategoryId,
            ]);
        }

        // --- Seed FAQs Posts (الأسئلة الشائعة) ---
        // Create specific questions and answers for the FAQ section.
        $faqs = [
            [
                'question' => 'ما هي الخدمات التي تقدمونها؟',
                'answer' => 'نقدم مجموعة واسعة من خدمات التسويق الرقمي والتصميم الإبداعي، بما في ذلك بناء العلامات التجارية، تصميم وتطوير المواقع، إدارة حسابات التواصل الاجتماعي، كتابة المحتوى، التسويق عبر محركات البحث، وتصوير المنتجات.',
            ],
            [
                'question' => 'ما الذي يميزكم عن الشركات الأخرى؟',
                'answer' => 'نتميز بفريق عمل احترافي وذو خبرة واسعة، التزامنا بالجودة والشفافية، حلولنا المتكاملة والمخصصة، واستجابتنا السريعة لاحتياجات العملاء، بالإضافة إلى أسعارنا التنافسية.',
            ],
            [
                'question' => 'كم تستغرق عملية تصميم الموقع الإلكتروني؟',
                'answer' => 'يعتمد الوقت المستغرق على مدى تعقيد المشروع ومتطلباته. بعد فهم احتياجاتك، نقدم جدولاً زمنياً تقديرياً. عادةً ما تتراوح المدة بين 4 إلى 12 أسبوعاً.',
            ],
            [
                'question' => 'هل تقدمون خدمات إدارة حملات الإعلانات المدفوعة؟',
                'answer' => 'نعم، نقدم خدمات إدارة حملات الإعلانات المدفوعة على جوجل ومنصات التواصل الاجتماعي، مع التركيز على تحقيق أفضل عائد على الاستثمار لعملائنا.',
            ],
            [
                'question' => 'كيف يمكنني البدء معكم؟',
                'answer' => 'يمكنك التواصل معنا عبر الهاتف أو البريد الإلكتروني أو نموذج الاتصال على موقعنا، وسيقوم فريقنا بالرد عليك لترتيب اجتماع لمناقشة احتياجاتك.',
            ],
            [
                'question' => 'هل تقدمون استشارات مجانية؟',
                'answer' => 'نعم، نقدم استشارة أولية مجانية لمناقشة مشروعك وتقديم رؤى أولية حول كيفية مساعدتك.',
            ],
            [
                'question' => 'هل يمكنكم مساعدتي في بناء علامة تجارية جديدة؟',
                'answer' => 'بالتأكيد! نحن متخصصون في بناء العلامات التجارية من الصفر، بدءاً من تصميم الشعار وصولاً إلى تطوير الهوية البصرية الكاملة واستراتيجية التسويق.',
            ],
        ];

        foreach ($faqs as $faq) {
            Post::factory()->create([
                'title' => $faq['question'],
                'slug' => Str::slug($faq['question']),
                'content' => [['type' => 'markdown', 'data' => ['content' => $faq['answer']]]],
                'user_id' => 1,
                'is_published' => true,
                'published_at' => $arabicFaker->dateTimeBetween('-1 year', 'now'),
                'post_category_id' => $faqsCategoryId,
            ]);
        }

        // --- Seed Prices Posts (الاسعار) ---
        // Detail each pricing package as found in the provided profile.
        $pricesContent = [
            [
            'title' => 'الباقة الماسية',
            'content' => '**السعر:** 25.000 ريال سعودي
    **الخدمات:**
    * إدارة مواقع التواصل الاجتماعي (كاملة) - انستجرام، تويتر، سناب شات، لينكد إن، إلخ.
    * تصميم الهايلات وتوسيع البايو.
    * المتابعة والرد على العملاء.
    * النشر اليومي وكتابة المحتوى.
    * تصميم 250 بوست، 250 ستوري، 250 تغريدة.
    **مدة الخدمة:** سنة.',
            'metadata' => [
                'key' => 'category',
                'value' => 'سنوية',            ],
            ],
            [
            'title' => 'الباقة الذهبية',
            'content' => '**السعر:** 15.000 ريال سعودي
    **الخدمات:**
    * إدارة مواقع التواصل الاجتماعي (كاملة).
    * تصميم الهايلات وترتيب البايو.
    * المتابعة والرد على العملاء.
    * النشر اليومي.
    * تصميم 125 بوست، 125 ستوري، 125 تغريدة.
    **مدة الخدمة:** 6 أشهر.',
            'metadata' => [
                'key' => 'category',
                'value' => 'سنوية',            ],
            ],
            [
            'title' => 'باقة البلاتينيوم',
            'content' => '**السعر:** 9.000 ريال سعودي
    **الخدمات:**
    * تصميم مواقع التواصل الاجتماعي (كاملة).
    * تصميم الهايلات وترتيب البايو.
    * المتابعة والرد على العملاء.
    * النشر اليومي.
    * تصميم 50 بوست، 50 ستوري، 50 تغريدة.
    **مدة الخدمة:** 3 أشهر.',
            'metadata' => [
                'key' => 'category',
                'value' => 'شهرية',            ],
            ],
            [
            'title' => 'الباقة الفضية',
            'content' => '**السعر:** 6.000 ريال سعودي
    **الخدمات:**
    * إدارة مواقع التواصل الاجتماعي (كاملة).
    * تصميم الهايلات وترتيب البايو.
    * المتابعة والرد على العملاء.
    * النشر اليومي وكتابة المحتوى.
    * تصميم 15 بوست، 15 ستوري، 15 تغريدة.
    **مدة الخدمة:** شهر.',
            'metadata' => [
                'key' => 'category',
                'value' => 'شهرية',            ],
            ],
            [
            'title' => 'الباقة البرونزية',
            'content' => '**السعر:** 3.000 ريال سعودي
    **الخدمات:**
    * إدارة مواقع التواصل الاجتماعي (كاملة).
    * تصميم الهايلات وترتيب البايو.
    * المتابعة والرد على العملاء.
    * النشر اليومي وكتابة المحتوى.
    * تصميم 15 بوست، 15 ستوري، 15 تغريدة.
    **مدة الخدمة:** شهر.',
             'metadata' => [
                'key' => 'category',
                'value' => 'شهرية',
                                        ],
            ],
        ];

        foreach ($pricesContent as $item) {
            Post::factory()->create([
            'title' => $item['title'],
            'slug' => Str::slug($item['title']),
            'content' => [['type' => 'markdown', 'data' => ['content' => $item['content']]]],
            'user_id' => 1,
            'is_published' => true,
            'published_at' => $arabicFaker->dateTimeBetween('-1 year', 'now'),
            'post_category_id' => $pricesCategoryId,
            'metadata' => [$item['metadata']],
            ]);
        }
    }
}
