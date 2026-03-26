<?php

namespace Database\Seeders;

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Taba\Crm\Models\Post;
use Taba\Crm\Models\PostCategory;
use Illuminate\Support\Str;
use Taba\Crm\Models\User;
use Illuminate\Support\Facades\Hash;
use Taba\Crm\Models\Tag;

class AISiteSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Categories with Descriptions
        $categories = [
            [
                'name' => ['en' => 'Overview', 'ar' => 'نظرة عامة'],
                'slug' => 'overview',
                'subtitle' => ['en' => 'About Gedeon Brand', 'ar' => 'نظرة عامة على علامة جديان التجارية'],
                'description' => ['en' => 'Overview of Gedeon brand, specialization, brand identity and vision.', 'ar' => 'نظرة عامة على علامة جديان التجارية، التخصص، هوية العلامة التجارية والرؤية.'],
                'order' => 1,
                'register_in_header' => true,
                'section_component' => 'about-us'
            ],
            [
                'name' => ['en' => 'Why Choose Us', 'ar' => 'لماذا تختار جديان؟'],
                'slug' => 'why-choose-us',
                'subtitle' => ['en' => 'Reasons to Choose Gedeon', 'ar' => 'أسباب اختيار جديان'],
                'description' => ['en' => 'Reasons to choose Gedeon services.', 'ar' => 'أسباب اختيار خدمات جديان.'],
                'order' => 2,
                'register_in_header' => false,
                'section_component' => 'features-list'
            ],
            [
                'name' => ['en' => 'Our Services', 'ar' => 'خدماتنا'],
                'slug' => 'our-services',
                'subtitle' => ['en' => 'Detailed Services', 'ar' => 'خدماتنا مفصلة'],
                'description' => ['en' => 'Detailed information about our services.', 'ar' => 'معلومات مفصلة حول خدماتنا.'],
                'order' => 3,
                'register_in_header' => true,
                'section_component' => 'services-list'
            ],
            [
                'name' => ['en' => 'Service Journey', 'ar' => 'رحلة مع خدماتنا'],
                'slug' => 'service-journey',
                'subtitle' => ['en' => 'How We Deliver Services', 'ar' => 'كيف نقدم الخدمات'],
                'description' => ['en' => 'Details on how our service is delivered, from request to delivery.', 'ar' => 'تفاصيل حول كيفية تقديم خدمتنا، من الطلب إلى التسليم.'],
                'order' => 4,
                'register_in_header' => false,
                'section_component' => 'process'
            ],
            [
                'name' => ['en' => 'Target Projects', 'ar' => 'أنواع المشاريع'],
                'slug' => 'target-projects',
                'subtitle' => ['en' => 'Types of Projects We Target', 'ar' => 'أنواع المشاريع التي نستهدفها'],
                'description' => ['en' => 'Types of projects we target (Hospitals, Commercial Centers, etc.).', 'ar' => 'أنواع المشاريع التي نستهدفها (مستشفيات، مراكز تجارية، إلخ).'],
                'order' => 5,
                'register_in_header' => false,
                'section_component' => 'portfolio'
            ],
            [
                'name' => ['en' => 'Testimonials', 'ar' => 'آراء عملاءنا'],
                'slug' => 'testimonials',
                'subtitle' => ['en' => 'Customer Reviews', 'ar' => 'تقييمات العملاء'],
                'description' => ['en' => 'Customer opinions and feedback.', 'ar' => 'آراء العملاء وملاحظاتهم.'],
                'order' => 6,
                'register_in_header' => false,
                'section_component' => 'testimonials'
            ],
            [
                'name' => ['en' => 'Articles', 'ar' => 'مقالات'],
                'slug' => 'articles',
                'subtitle' => ['en' => 'Blog Posts', 'ar' => 'تدوينات المدونة'],
                'description' => ['en' => 'Our articles and blog posts.', 'ar' => 'مقالاتنا وتدوينات المدونة.'],
                'order' => 7,
                'register_in_header' => false,
                'section_component' => 'blog'
            ],
            [
                'name' => ['en' => 'Success Partners', 'ar' => 'شركاء النجاح'],
                'slug' => 'success-partners',
                'subtitle' => ['en' => 'Our Partners', 'ar' => 'شركاؤنا'],
                'description' => ['en' => 'Our success partners.', 'ar' => 'شركاؤنا في النجاح.'],
                'order' => 8,
                'register_in_header' => false,
                'section_component' => 'brand-marque'
            ],
            [
                'name' => ['en' => 'Footer', 'ar' => 'الخاتمة'],
                'slug' => 'footer',
                'subtitle' => ['en' => 'Contact Information', 'ar' => 'معلومات الاتصال'],
                'description' => ['en' => 'Footer section with contact info, terms, and logo.', 'ar' => 'قسم التذييل مع معلومات الاتصال والشروط والشعار.'],
                'order' => 9,
                'register_in_header' => false,
                'section_component' => 'contact'
            ],
        ];

        foreach ($categories as $categoryData) {
            PostCategory::updateOrCreate(['slug' => $categoryData['slug']], [
                'name' => $categoryData['name'],
                'description' => $categoryData['description'],
                'order' => $categoryData['order'] ?? null,
                'register_in_header' => $categoryData['register_in_header'] ?? false,
                'subtitle' => $categoryData['subtitle'] ?? null,
                'section_component' => $categoryData['section_component'] ?? null,
            ]);
        }

        // 2. Create Tags
        $tags = [
            ['name' => ['en' => 'Engineering', 'ar' => 'هندسة'], 'slug' => 'engineering'],
            ['name' => ['en' => 'Design', 'ar' => 'تصميم'], 'slug' => 'design'],
        ];

        foreach ($tags as $tagData) {
            Tag::updateOrCreate(['slug' => $tagData['slug']], [
                'name' => $tagData['name'],
            ]);
        }

        // 3. Create Posts
        $overviewPost = [
            'title' => ['en' => '', 'ar' => 'نظرة عامة على علامة جديان التجارية'],
            'content' => [
                'en' => [['type' => 'markdown', 'data' => ['content' => '']]],
                'ar' => [['type' => 'markdown', 'data' => ['content' => '* **التخصص:** جديان هي علامة تجارية متخصصة في التصميم الهندسي وخدمات المكاتب الفنية.
* **هوية العلامة التجارية:** تستمد هوية العلامة التجارية من صقر الجديان، الذي يرمز إلى القوة والتركيز والدقة، مما يعكس التزام الشركة بالجودة والاحترافية.
* **الرؤية:** تعتمد جديان على الابتكار والتكامل الهندسي لتلبية احتياجات المشاريع من خلال تقديم خدمات موثوقة تلبي أعلى المعايير الفنية. رؤية الشركة هي أن تكون علامة تجارية متخصصة تجمع بين الإبداع الهندسي والدقة الفنية، مما يجعلها هوية قوية ومتفردة في السوق.']]
            ]
        ]];
        $this->createPost('overview', $overviewPost, [], ['engineering', 'design']);
        $this->createPost('overview', $overviewPost, [], ['engineering', 'design']);

        $servicesAndOperationsPost = [
            'title' => ['en' => '', 'ar' => 'الخدمات والعمليات'],
            'content' => [
                'en' => [['type' => 'markdown', 'data' => ['content' => '']]],
                'ar' => [['type' => 'markdown', 'data' => ['content' => '* **الخدمات الرئيسية:** تشمل مجالات عمل جديان حساب الكميات، إعداد وتقديم المواد، مراجعة المخططات، والتحليل الفني.
* **فلسفة العلامة التجارية:** تصميم الشعار مستوحى من صقر الجديان ليعكس القيم الأساسية للعلامة التجارية وهي القوة، الرؤية الثاقبة، والدقة. كما أن تصميم الشعار يشكل حرف "الجيم"، وهو الحرف الأول من اسم "جديان"، مما يعزز الهوية البصرية.']]
            ]
        ]];
        $this->createPost('overview', $servicesAndOperationsPost, [], ['engineering', 'design']);
        $whyChooseUsPost = [
            'title' => ['en' => '', 'ar' => 'لماذا تختار جديان؟'],
            'content' => [
                'en' => [['type' => 'markdown', 'data' => ['content' => '']]],
                'ar' => [['type' => 'markdown', 'data' => ['content' => 'نحن نقدم خدمات عالية الجودة بأسعار تنافسية.']]]
            ]
        ];
        $this->createPost('why-choose-us', $whyChooseUsPost, [], []);
    $ourServicesPost = [
        'title' => ['en' => '', 'ar' => 'حساب الكميات'],
        'content' => [
            'en' => [['type' => 'markdown', 'data' => ['content' => '']]],
            'ar' => [['type' => 'markdown', 'data' => ['content' => 'وصف خدمة حساب الكميات']]]
        ]
    ];

        $this->createPost('our-services', $ourServicesPost, [], ['engineering']);
        $ourServicesPost2 = [
            'title' => ['en' => '', 'ar' => 'إعداد وتقديم المواد'],
            'content' => [
                'en' => [['type' => 'markdown', 'data' => ['content' => '']]],
                'ar' => [['type' => 'markdown', 'data' => ['content' => 'وصف خدمة إعداد وتقديم المواد']]]
            ]
        ];
        $this->createPost('our-services', $ourServicesPost2, [], ['engineering']);
        $ourServicesPost3 = [
            'title' => ['en' => '', 'ar' => 'مراجعة المخططات'],
            'content' => [
                'en' => [['type' => 'markdown', 'data' => ['content' => '']]],
                'ar' => [['type' => 'markdown', 'data' => ['content' => 'وصف خدمة مراجعة المخططات']]]
            ]
        ];
        $this->createPost('our-services', $ourServicesPost3, [], ['engineering']);
        $ourServicesPost4 = [
            'title' => ['en' => '', 'ar' => 'التحليل الفني'],
            'content' => [
                'en' => [['type' => 'markdown', 'data' => ['content' => '']]],
                'ar' => [['type' => 'markdown', 'data' => ['content' => 'وصف خدمة التحليل الفني']]]
            ]
        ];
        $this->createPost('our-services', $ourServicesPost4, [], ['engineering']);
        $serviceJourneyPost = [
            'title' => ['en' => '', 'ar' => 'رحلة مع خدماتنا'],
            'content' => [
                'en' => [['type' => 'markdown', 'data' => ['content' => '']]],
                'ar' => [['type' => 'markdown', 'data' => ['content' => 'بنتكلم فيها عن كيفية الخدمة بدءا من طلب العميل إلى تسليمه']]]
            ]
        ];
        $this->createPost('service-journey', $serviceJourneyPost, [], []);
        $targetProjectsPost = [
            'title' => ['en' => '', 'ar' => 'أنواع المشاريع البنستهدفها'],
            'content' => [
                'en' => [['type' => 'markdown', 'data' => ['content' => '']]],
                'ar' => [['type' => 'markdown', 'data' => ['content' => 'مستشفيات-مراكز تجارية.... إلخ']]]
            ]
        ];
        $this->createPost('target-projects', $targetProjectsPost, [], []);
        $testimonialsPost = [
            'title' => ['en' => '', 'ar' => 'آراء عملاءنا'],
            'content' => [
                'en' => [['type' => 'markdown', 'data' => ['content' => '']]],
                'ar' => [['type' => 'markdown', 'data' => ['content' => 'آراء العملاء']]]
            ]
        ];
        $this->createPost('testimonials', $testimonialsPost, [], []);
        $articlesPost = [
            'title' => ['en' => '', 'ar' => 'مقالات'],
            'content' => [
                'en' => [['type' => 'markdown', 'data' => ['content' => '']]],
                'ar' => [['type' => 'markdown', 'data' => ['content' => 'مقالات']]]
            ]
        ];
        $this->createPost('articles', $articlesPost, [], []);
        $successPartnersPost = [
            'title' => ['en' => '', 'ar' => 'شركاء النجاح'],
            'content' => [
                'en' => [['type' => 'markdown', 'data' => ['content' => '']]],
                'ar' => [['type' => 'markdown', 'data' => ['content' => 'شركاء النجاح']]]
            ]
        ];
        $this->createPost('success-partners', $successPartnersPost, [], []);
        $footerPost = [
            'title' => ['en' => '', 'ar' => 'الخاتمة'],
            'content' => [
                'en' => [['type' => 'markdown', 'data' => ['content' => '']]],
                'ar' => [['type' => 'markdown', 'data' => ['content' => 'فيها مكان المكتب و رقم الجوال و الإيميل، الشروط والأحكام مع ضرورة ظهور شعار جديان بصورة واضحة و متناسبة']]]
            ]
        ];
        $this->createPost('footer', $footerPost, [], []);

    }

    private function createPost(string $categorySlug, array $data, array $metadata = [], array $tags = []): void
    {
        $category = PostCategory::where('slug', $categorySlug)->first();
        if (is_null($category)) {
            // In a real scenario, you might throw an exception or log an error.
            // For this seeder, we'll just skip it if the category isn't found.
            return;
        }

        $post = Post::updateOrCreate(['slug' => Str::slug($data['title']['ar'])], [
            'title' => $data['title'],
            'content' => $data['content'],
            'meta_title' => $data['meta_title'] ?? $data['title'],
            'meta_description' => $data['meta_description'] ?? null,
            'post_category_id' => $category->id,
            'user_id' => 1,
            'is_published' => true,
            'published_at' => now(),
            'metadata' => $metadata,
            'homepage_section_component' => $data['homepage_section_component'] ?? null,
            'homepage_section_content' => $data['homepage_section_content'] ?? null,
        ]);

        if (!empty($tags)) {
            $tagModels = Tag::whereIn('slug', $tags)->pluck('id');
            $post->tags()->sync($tagModels);
        }
    }
}
