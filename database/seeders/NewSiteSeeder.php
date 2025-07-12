<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Taba\Crm\Models\Post;
use Taba\Crm\Models\PostCategory;
use Illuminate\Support\Str;
use Taba\Crm\Models\User;
use Illuminate\Support\Facades\Hash;
use Taba\Crm\Models\Tag;

class NewSiteSeeder extends Seeder
{
    public function run(): void
    {
        // 0. Create a default user if none exists
        if (User::count() == 0) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
            ]);
        }

        // 1. Create Categories with Descriptions
        $categories = [
            [
                'name' => ['en' => 'Homepage', 'ar' => 'الصفحة الرئيسية'],
                'slug' => 'homepage',
                'subtitle' => ['en' => 'Global Digital Marketing', 'ar' => 'التسويق الالكتروني العالمي'],
                'description' => ['en' => 'The main landing page of our website, featuring the latest updates and highlights.', 'ar' => 'الصفحة الرئيسية لموقعنا، تعرض آخر التحديثات وأبرز النقاط.'],
                'order' => 1,
                'register_in_header' => true,
                'section_component' => 'hero'
            ],
            [
                'name' => ['en' => 'About Us', 'ar' => 'من نحن'],
                'slug' => 'about-us',
                'subtitle' => ['en' => 'Learn More About Us', 'ar' => 'تعرف على المزيد عنا'],
                'description' => ['en' => 'Learn about our story, our team, and the values that drive us.', 'ar' => 'تعرف على قصتنا وفريقنا والقيم التي تحركنا.'],
                'order' => 2,
                'register_in_header' => true,
                'section_component' => 'about-us'
            ],
            [
                'name' => ['en' => 'Vision & Mission', 'ar' => 'الرؤية والأهداف'],
                'slug' => 'vision-mission',
                'subtitle' => ['en' => 'Our Goals and Aspirations', 'ar' => 'أهدافنا وتطلعاتنا'],
                'description' => ['en' => 'Discover our long-term vision and the mission that guides our daily actions.', 'ar' => 'اكتشف رؤيتنا طويلة الأمد والرسالة التي توجه أعمالنا اليومية.'],
                'order' => 3,
                'register_in_header' => false,
                'section_component' => 'vision-mission'
            ],
            [
                'name' => ['en' => 'Why Choose Us?', 'ar' => 'لماذا نحن؟'],
                'slug' => 'why-choose-us',
                'subtitle' => ['en' => 'Reasons to Partner With Us', 'ar' => 'أسباب الشراكة معنا'],
                'description' => ['en' => 'Find out the compelling reasons to partner with us for your business needs.', 'ar' => 'اكتشف الأسباب المقنعة للشراكة معنا لتلبية احتياجات عملك.'],
                'order' => 4,
                'register_in_header' => false,
                'section_component' => 'why-choose-us'
            ],
            [
                'name' => ['en' => 'Our Services', 'ar' => 'خدماتنا'],
                'slug' => 'our-services',
                'subtitle' => ['en' => 'What We Do', 'ar' => 'ماذا نقدم'],
                'description' => ['en' => 'A comprehensive overview of the professional services we offer to help your business grow.', 'ar' => 'نظرة شاملة على الخدمات الاحترافية التي نقدمها لمساعدة عملك على النمو.'],
                'order' => 5,
                'register_in_header' => true,
                'section_component' => 'services-list'
            ],
            [
                'name' => ['en' => 'Pricing Packages', 'ar' => 'باقات الأسعار'],
                'slug' => 'pricing-packages',
                'subtitle' => ['en' => 'Flexible Plans for You', 'ar' => 'خطط مرنة لك'],
                'description' => ['en' => 'Explore our flexible and competitive pricing packages tailored to suit your requirements.', 'ar' => 'اكتشف باقات الأسعار المرنة والتنافسية المصممة لتناسب متطلباتك.'],
                'order' => 6,
                'register_in_header' => true,
                'section_component' => 'pricing-packages'
            ],
            [
                'name' => ['en' => 'Contact Us', 'ar' => 'تواصل معنا'],
                'slug' => 'contact-us',
                'subtitle' => ['en' => 'Reach Out to Us', 'ar' => 'تواصل معنا'],
                'description' => ['en' => 'Get in touch with our team for any inquiries, support, or collaboration opportunities.', 'ar' => 'تواصل مع فريقنا لأي استفسارات أو دعم أو فرص تعاون.'],
                'order' => 7,
                'register_in_header' => true,
                'section_component' => 'contact'
            ],
            [
                'name' => ['en' => 'Our Portfolio', 'ar' => 'أعمالنا'],
                'slug' => 'portfolio',
                'subtitle' => ['en' => 'Our Recent Work', 'ar' => 'أحدث أعمالنا'],
                'description' => ['en' => 'A showcase of our best projects and success stories.', 'ar' => 'عرض لأفضل مشاريعنا وقصص نجاحها.'],
                'order' => 8,
                'register_in_header' => true,
                'section_component' => 'portfolio'
            ],
            [
                'name' => ['en' => 'Features', 'ar' => 'المميزات'],
                'slug' => 'features',
                'subtitle' => ['en' => 'Why Choose Us', 'ar' => 'لماذا تختارنا'],
                'description' => ['en' => 'The key features and benefits of our services.', 'ar' => 'الميزات والفوائد الرئيسية لخدماتنا.'],
                'order' => 9,
                'register_in_header' => false,
                'section_component' => 'services-features'
            ],
            [
                'name' => ['en' => 'Testimonials', 'ar' => 'الآراء'],
                'slug' => 'testimonials',
                'subtitle' => ['en' => 'What Our Clients Say', 'ar' => 'ماذا يقول عملاؤنا'],
                'description' => ['en' => 'Read what our clients have to say about our work.', 'ar' => 'اقرأ ما يقوله عملاؤنا عن عملنا.'],
                'order' => 10,
                'register_in_header' => false,
                'section_component' => 'services-carousel'
            ],
            [
                'name' => ['en' => 'FAQ', 'ar' => 'الأسئلة الشائعة'],
                'slug' => 'faq',
                'subtitle' => ['en' => 'Frequently Asked Questions', 'ar' => 'الأسئلة المتداولة'],
                'description' => ['en' => 'Find answers to common questions about our services.', 'ar' => 'ابحث عن إجابات للأسئلة الشائعة حول خدماتنا.'],
                'order' => 11,
                'register_in_header' => false,
                'section_component' => 'faq'
            ],
            [
                'name' => ['en' => 'Blog', 'ar' => 'المدونة'],
                'slug' => 'blog',
                'subtitle' => ['en' => 'Latest News', 'ar' => 'آخر الأخبار'],
                'description' => ['en' => 'Read our latest articles and insights on digital marketing.', 'ar' => 'اقرأ أحدث مقالاتنا ورؤانا حول التسويق الرقمي.'],
                'order' => 12,
                'register_in_header' => true,
                'section_component' => 'latest-news'
            ],
            [
                'name' => ['en' => 'Call to Action', 'ar' => 'دعوة للعمل'],
                'slug' => 'call-action',
                'subtitle' => ['en' => 'Get in Touch', 'ar' => 'تواصل معنا'],
                'description' => ['en' => 'Contact us to get started on your next project.', 'ar' => 'اتصل بنا لبدء مشروعك القادم.'],
                'order' => 13,
                'register_in_header' => false,
                'section_component' => 'call-action'
            ],
            [
                'name' => ['en' => 'Brand Marque', 'ar' => 'ماركة العلامة التجارية'],
                'slug' => 'brand-marque',
                'subtitle' => ['en' => 'Our Valued Partners', 'ar' => 'شركاؤنا الكرام'],
                'description' => ['en' => 'Showcasing our brand partners and their contributions.', 'ar' => 'عرض شركائنا في العلامة التجارية ومساهماتهم.'],
                'order' => 14,
                'register_in_header' => false,
                'section_component' => 'brand-marque'
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
            ['name' => ['en' => 'Marketing', 'ar' => 'تسويق'], 'slug' => 'marketing'],
            ['name' => ['en' => 'Digital', 'ar' => 'رقمي'], 'slug' => 'digital'],
            ['name' => ['en' => 'SEO', 'ar' => 'تحسين محركات البحث'], 'slug' => 'seo'],
            ['name' => ['en' => 'Social Media', 'ar' => 'وسائل التواصل الاجتماعي'], 'slug' => 'social-media'],
            ['name' => ['en' => 'Web Design', 'ar' => 'تصميم مواقع'], 'slug' => 'web-design'],
        ];

        foreach ($tags as $tagData) {
            Tag::updateOrCreate(['slug' => $tagData['slug']], [
                'name' => $tagData['name'],
            ]);
        }

        // 3. Create Posts
        $homepageContent = [
            'title' => ['en' => 'Welcome to Bat-Tour Marketing', 'ar' => 'مرحباً بكم في بطور للتسويق الالكتروني'],
            'meta_title' => ['en' => 'Bat-Tour Marketing - Homepage', 'ar' => 'بطور للتسويق - الصفحة الرئيسية'],
            'meta_description' => ['en' => 'Welcome to Bat-Tour Digital Marketing. Together, always towards a better future. Our services are your best choice. We are a specialized agency in digital marketing and IT solutions.', 'ar' => 'مرحباً بكم في بطور للتسويق الالكتروني معاً دائماً نحو مستقبل أفضل. خدماتنا هي اختيارك الأفضل. نحن وكالة متخصصة في التسويق الرقمي وحلول تكنولوجيا المعلومات.'],
            'content' => [
                'en' => [
                    ['type' => 'markdown', 'data' => ['content' => 'Welcome to Bat-Tour Digital Marketing. Together, always towards a better future. Our services are your best choice. We are a specialized agency in digital marketing and IT solutions.']],
                    ['type' => 'markdown', 'data' => ['content' => 'Power, speed, and intelligence in the ever-changing world of digital marketing. We elevate your brand to the sky. Bat-Tour Digital Marketing is your own wings to ascend to the top.']]
                ],
                'ar' => [
                    ['type' => 'markdown', 'data' => ['content' => 'مرحباً بكم في بطور للتسويق الالكتروني معاً دائماً نحو مستقبل أفضل. خدماتنا هي اختيارك الأفضل. نحن وكالة متخصصة في التسويق الرقمي وحلول تكنولوجيا المعلومات.']],
                    ['type' => 'markdown', 'data' => ['content' => 'القوة، السرعة، الذكاء في عالم التسويق الرقمي المتغير باستمرار، نحلق بعلامتك التجارية إلى السماء. بطور للتسويق الالكتروني هي أجنحتك الخاصة للصعود إلى القمة.']]
                ]
            ]
        ];
        $this->createPost('homepage', $homepageContent, [
            'featured' => true,
            'subtitle' => ['en' => 'Global Digital Marketing', 'ar' => 'التسويق الالكتروني العالمي'],
            'fun_facts' => [
                ['number' => 100, 'text' => ['en' => 'Years of <br />Experience', 'ar' => 'سنوات من <br />الخبرة']],
                ['number' => '100+', 'text' => ['en' => 'Project <br />Completed', 'ar' => 'مشروع <br />مكتمل']],
                ['number' => '100K', 'text' => ['en' => 'Happy <br />Clients', 'ar' => 'عملاء <br />سعداء']],
                ['number' => 100, 'text' => ['en' => 'Years of <br />Experience', 'ar' => 'سنوات من <br />الخبرة']]
            ],
            'social_links' => [
                ['icon' => 'fa-brands fa-twitter', 'url' => '#'],
                ['icon' => 'fa-light fa-basketball', 'url' => '#'],
                ['icon' => 'fa-brands fa-linkedin-in', 'url' => '#'],
                ['icon' => 'fa-brands fa-github', 'url' => '#']
            ],
            'homepage_section_component' => 'hero',
        ], ['marketing', 'digital'], null);

        $aboutUsSections = [
            [
                'title' => ['en' => 'Our Story', 'ar' => 'قصتنا'],
                'meta_title' => ['en' => 'Bat-Tour Marketing - Our Story', 'ar' => 'بطور للتسويق - قصتنا'],
                'meta_description' => ['en' => 'Learn about the story of Bat-Tour Digital Marketing, our vision, and how we work with clients to achieve their goals.', 'ar' => 'تعرف على قصة بطور للتسويق الرقمي، رؤيتنا، وكيف نعمل مع عملائنا لتحقيق أهدافهم.'],
                'content' => [
                    'en' => [['type' => 'markdown', 'data' => ['content' => 'Our team started in 2019 from Egypt, and we have overcome many challenges to leave a distinctive mark. By the grace of God, the foundation stone of our company, Bat-Tour Digital Marketing, was laid in 2023 in Riyadh, Saudi Arabia.']]],
                    'ar' => [['type' => 'markdown', 'data' => ['content' => 'بدأ فريقنا في عام 2019 من مصر، وتجاوزنا العديد من التحديات لنترك بصمة مميزة. وبحمد الله، تم وضع حجر أساس شركتنا بطور للتسويق الالكتروني في عام 2023 بالرياض، المملكة العربية السعودية.']]]
                ]
            ],
            [
                'title' => ['en' => 'Who are we?', 'ar' => 'من نحن؟'],
                'meta_title' => ['en' => 'Bat-Tour Marketing - Who are we?', 'ar' => 'بطور للتسويق - من نحن؟'],
                'meta_description' => ['en' => 'Discover who Bat-Tour Digital Marketing is and our expertise in digital marketing and creative design.', 'ar' => 'اكتشف من هي بطور للتسويق الرقمي وخبرتنا في التسويق الرقمي والتصميم الإبداعي.'],
                'content' => [
                    'en' => [['type' => 'markdown', 'data' => ['content' => 'Who are we? We are Bat-Tour Digital Marketing, a company specializing in digital marketing and creative design. Our vision is shared, and our goals are clear and specific. Our team includes experts in the fields of e-marketing, website design, brand building, visual identity, graphic design, and social media account management.']]],
                    'ar' => [['type' => 'markdown', 'data' => ['content' => 'من نحن؟ نحن بطور للتسويق الالكتروني شركة متخصصة في التسويق الرقمي والتصميم الإبداعي. رؤيتنا مشتركة وأهدافنا واضحة ومحددة. يضم فريقنا خبراء في مجالات التسويق الإلكتروني، تصميم المواقع، بناء العلامات التجارية، الهوية البصرية، تصميم الجرافيك، وإدارة حسابات التواصل الاجتماعي.']]]
                ]
            ],
            [
                'title' => ['en' => 'How do we work?', 'ar' => 'كيف نعمل؟'],
                'meta_title' => ['en' => 'Bat-Tour Marketing - How we work', 'ar' => 'بطور للتسويق - كيف نعمل'],
                'meta_description' => ['en' => 'Understand our collaborative approach to working with clients to achieve their goals.', 'ar' => 'فهم نهجنا التعاوني في العمل مع العملاء لتحقيق أهدافهم.'],
                'content' => [
                    'en' => [['type' => 'markdown', 'data' => ['content' => 'How do we work? We work closely with our clients to understand their needs and goals, and to provide customized solutions that fit their budget and timeline. We put our values at the heart of everything we do: building trust, inspiration, connection, and perseverance.']]],
                    'ar' => [['type' => 'markdown', 'data' => ['content' => 'كيف نعمل؟ نعمل بشكل وثيق مع عملائنا لفهم احتياجاتهم وأهدافهم، وتقديم حلول مخصصة تناسب ميزانيتهم وجدولهم الزمني. نحن نضع قيمنا في قلب كل ما نفعله: بناء الثقة، الترابط، الإصرار، الشفافية، الإلهام، والنصر، وخلق بيئة ونتائج أفضل وتأسيس قواعد صلبة من الثقة بيننا وبين العملاء وزملاء العمل، بغض النظر عن أي عوائق أو صعوبات.']]]
                ]
            ],
            [
                'title' => ['en' => 'Who do we serve?', 'ar' => 'لمن نقدم خدماتنا؟'],
                'meta_title' => ['en' => 'Bat-Tour Marketing - Who we serve', 'ar' => 'بطور للتسويق - لمن نقدم خدماتنا'],
                'meta_description' => ['en' => 'Learn about our target audience and the types of businesses we help succeed.', 'ar' => 'تعرف على جمهورنا المستهدف وأنواع الأعمال التي نساعدها على النجاح.'],
                'content' => [
                    'en' => [['type' => 'markdown', 'data' => ['content' => 'Who do we serve? We target entrepreneurs, institutions, companies, and emerging projects that aspire to achieve continuous success and prosperity in the world of entrepreneurship.']]],
                    'ar' => [['type' => 'markdown', 'data' => ['content' => 'لمن نقدم خدماتنا؟ نستهدف رواد الأعمال، المؤسسات، الشركات، والمشاريع الناشئة التي تطمح إلى تحقيق النجاح والازدهار المستمر في عالم ريادة الأعمال.']]]
                ]
            ],
            [
                'title' => ['en' => 'Why choose us?', 'ar' => 'لماذا نحن؟'],
                'meta_title' => ['en' => 'Bat-Tour Marketing - Why choose us?', 'ar' => 'بطور للتسويق - لماذا نحن؟'],
                'meta_description' => ['en' => 'Discover the compelling reasons to choose Bat-Tour Digital Marketing as your partner.', 'ar' => 'اكتشف الأسباب المقنعة لاختيار بطور للتسويق الرقمي كشريك لك.'],
                'content' => [
                    'en' => [['type' => 'markdown', 'data' => ['content' => 'Why choose us? If you are looking for a reliable and creative partner in the field of digital marketing and creative design, Bat-Tour Digital Marketing is your digital shield and your partner towards success. Do not miss the opportunity to join our list of satisfied customers, and let us raise your sales indicators and fly your brand to the sky.']]],
                    'ar' => [['type' => 'markdown', 'data' => ['content' => 'إذا كنت تبحث عن شريك موثوق ومبدع في مجال التسويق الرقمي والتصميم الإبداعي، فإن بطور للتسويق الالكتروني هي درعك الرقمي وشريكك نحو النجاح. لا تفوت فرصة الانضمام إلى قائمة عملائنا الراضين، ودعنا نرتقي بمؤشرات مبيعاتك ونحلق بعلامتك التجارية إلى السماء.']]]
                ]
            ],
        ];

        foreach ($aboutUsSections as $index => $section) {
            $metadata = ['section' => 'about'];
            if ($index === 0) {
                $metadata['homepage_section_component'] = 'default';
            }
            $this->createPost('about-us', $section, $metadata, ['marketing'], null);
        }

        $visionMissionSections = [
            [
                'title' => ['en' => 'Our Vision', 'ar' => 'رؤيتنا'],
                'meta_title' => ['en' => 'Bat-Tour Marketing - Our Vision', 'ar' => 'بطور للتسويق - رؤيتنا'],
                'meta_description' => ['en' => 'Explore the vision of Bat-Tour Digital Marketing and our commitment to your success.', 'ar' => 'اكتشف رؤية بطور للتسويق الرقمي والتزامنا بنجاحك.'],
                'content' => [
                    'en' => [['type' => 'markdown', 'data' => ['content' => 'Our Vision: We strive to be the preferred partner for institutions, companies, projects, and entrepreneurs, side by side towards success and prosperity. We aim to achieve your vision and goals by building trust, connection, perseverance, transparency, inspiration, and victory, and creating a better environment and results and establishing solid foundations of trust between us and our clients and colleagues, regardless of any obstacles or difficulties.']]],
                    'ar' => [['type' => 'markdown', 'data' => ['content' => 'رؤيتنا: نسعى جاهدين لأن نكون الشريك المفضل للمؤسسات والشركات والمشاريع ورواد الأعمال، جنباً إلى جنب نحو النجاح والازدهار. نهدف إلى تحقيق رؤيتك وأهدافك من خلال بناء الثقة، الترابط، الإصرار، الشفافية، الإلهام، والنصر، وخلق بيئة ونتائج أفضل وتأسيس قواعد صلبة من الثقة بيننا وبين العملاء وزملاء العمل، بغض النظر عن أي عوائق أو صعوبات.']]]
                ]
            ],
            [
                'title' => ['en' => 'Our Goals', 'ar' => 'أهدافنا'],
                'meta_title' => ['en' => 'Bat-Tour Marketing - Our Goals', 'ar' => 'بطور للتسويق - أهدافنا'],
                'meta_description' => ['en' => 'Discover the goals of Bat-Tour Digital Marketing and how we aim to achieve them.', 'ar' => 'اكتشف أهداف بطور للتسويق الرقمي وكيف نهدف إلى تحقيقها.'],
                'content' => [
                    'en' => [['type' => 'markdown', 'data' => ['content' => 'Our Goals: • Inspire the community with the creative solutions we offer. • Transform emerging institutions and companies into well-known brands. • Provide high-quality and innovative services in the field of digital marketing and creative design in the region. • Create a solid name for commercial and investment projects and achieve the desired success and goals. • Excellence in every project we undertake.']]],
                    'ar' => [['type' => 'markdown', 'data' => ['content' => 'أهدافنا: • إلهام المجتمع بالحلول الإبداعية التي نقدمها. • تحويل المؤسسات والشركات الناشئة إلى علامات تجارية معروفة. • تقديم خدمات عالية الجودة ومبتكرة في مجال التسويق الرقمي والتصميم الإبداعي في المنطقة. • خلق اسم راسخ للمشاريع التجارية والاستثمارية وتحقيق النجاح والأهداف المرجوة. • التميز في كل مشروع نقوم به.']]]
                ]
            ],
        ];

        foreach ($visionMissionSections as $index => $section) {
            $metadata = ['section' => 'about'];
            if ($index === 0) {
                $metadata['homepage_section_component'] = 'default';
            }
            $this->createPost('vision-mission', $section, $metadata, ['digital'], null);
        }

        $whyChooseUsContent = [
            'title' => ['en' => 'Why Choose Us?', 'ar' => 'لماذا نحن؟'],
            'meta_title' => ['en' => 'Bat-Tour Marketing - Why Choose Us?', 'ar' => 'بطور للتسويق - لماذا نحن؟'],
            'meta_description' => ['en' => 'Discover the reasons to choose Bat-Tour Digital Marketing for your business needs.', 'ar' => 'اكتشف الأسباب لاختيار بطور للتسويق الرقمي لاحتياجات عملك.'],
            'content' => [
                'en' => [
                    ['type' => 'markdown', 'data' => ['content' => 'Why choose Bat-Tour Advertising and Advertising over other companies? • Leadership and professionalism: A leadership team with extensive experience in global and regional companies. • Working in a highly organizational environment: to ensure complete transparency and professionalism for our clients. • Close understanding and global focus: understanding and respecting the customs and traditions of all our clients. • High-value offers and attractive opportunities: to obtain a large profit return. • Ensuring the most appropriate prices and the best offers for our customers. • Providing a variety of services to our customers in line with the highest international standards. • Extreme accuracy in delivery dates and completion of work to the fullest. • Our company is your digital shield and success partner. • We raise your sales indicators and fly your brand to the sky. • Achieving continuous success and prosperity in the world of entrepreneurship.']]
                ],
                'ar' => [
                    ['type' => 'markdown', 'data' => ['content' => 'لماذا تختار شركة بطور للدعاية والإعلان دون غيرها من الشركات؟ • الريادة والاحتراف: فريق قيادي ذو خبرة واسعة في شركات عالمية وإقليمية. • العمل في بيئة تنظيمية شديدة: لتأمين الشفافية والمهنية الكاملة لعملائنا. • فهم وثيق وتركيز عالمي: تفهم واحترام عادات وتقاليد كل عملائنا. • عروض ذات قيمة عالية وفرص مغرية: للحصول على عائد ربحي كبير. • ضمان أنسب الأسعار وأفضل العروض لعملائنا. • توفير مجموعة متنوعة من الخدمات لعملائنا بما يتماشى مع أعلى المعايير الدولية. • دقة فائقة في مواعيد التسليم وإتمام العمل على أكمل وجه. • شركتنا هي درعك الرقمي وشريك النجاح. • نرتقي بمؤشرات مبيعاتك ونحلق بعلامتك التجارية إلى السماء. • تحقيق النجاح والازدهار المستمر في عالم ريادة الأعمال.']]
                ]
            ]
        ];
        $this->createPost('why-choose-us', $whyChooseUsContent, ['homepage_section_component' => 'default'], [], null);

        // 4. Create Individual Service Posts
        $services = [
            [
                'title' => ['en' => 'Brand Building and Visual Identity', 'ar' => 'بناء العلامات التجارية والهوية البصرية'],
                'content' => [
                    'en' => [['type' => 'markdown', 'data' => ['content' => 'We establish and enhance your brand identity to create a lasting impression.']]],
                    'ar' => [['type' => 'markdown', 'data' => ['content' => 'نؤسس ونعزز هوية علامتك التجارية لخلق انطباع دائم.']]]
                ],
            ],
            [
                'title' => ['en' => 'Website and E-store Design and Development', 'ar' => 'تصميم وتطوير المواقع والمتاجر الإلكترونية'],
                'content' => [
                    'en' => [['type' => 'markdown', 'data' => ['content' => 'Creating stunning and functional websites and e-commerce stores tailored to your needs.']]],
                    'ar' => [['type' => 'markdown', 'data' => ['content' => 'إنشاء مواقع ومتاجر إلكترونية مذهلة وعملية مصممة خصيصًا لتلبية احتياجاتك.']]]
                ],
            ],
            [
                'title' => ['en' => 'Social Media Account Management', 'ar' => 'إدارة حسابات التواصل الاجتماعي'],
                'content' => [
                    'en' => [['type' => 'markdown', 'data' => ['content' => 'We manage your social media presence to engage your audience and grow your brand.']]],
                    'ar' => [['type' => 'markdown', 'data' => ['content' => 'ندير وجودك على وسائل التواصل الاجتماعي لإشراك جمهورك وتنمية علامتك التجارية.']]]
                ],
            ],
            [
                'title' => ['en' => 'Content Writing', 'ar' => 'كتابة المحتوى'],
                'content' => [
                    'en' => [['type' => 'markdown', 'data' => ['content' => 'Crafting compelling and SEO-friendly content that drives traffic and conversions.']]],
                    'ar' => [['type' => 'markdown', 'data' => ['content' => 'صياغة محتوى جذاب ومتوافق مع محركات البحث يجذب الزوار ويزيد التحويلات.']]]
                ],
            ],
            [
                'title' => ['en' => 'E-marketing through Search Engines (Google)', 'ar' => 'التسويق الإلكتروني عبر محركات البحث (جوجل)'],
                'content' => [
                    'en' => [['type' => 'markdown', 'data' => ['content' => 'Implementing effective SEO and SEM strategies to boost your visibility on Google.']]],
                    'ar' => [['type' => 'markdown', 'data' => ['content' => 'تنفيذ استراتيجيات فعالة لتحسين محركات البحث والتسويق عبرها لزيادة ظهورك على جوجل.']]]
                ],
            ],
            [
                'title' => ['en' => 'Project Management and Digital Presence Enhancement', 'ar' => 'إدارة المشاريع وتعزيز وجودها الرقمي'],
                'content' => [
                    'en' => [['type' => 'markdown', 'data' => ['content' => 'We manage your projects and enhance your digital footprint for maximum impact.']]],
                    'ar' => [['type' => 'markdown', 'data' => ['content' => 'ندير مشاريعك ونعزز بصمتك الرقمية لتحقيق أقصى تأثير.']]]
                ],
            ],
            [
                'title' => ['en' => 'Product Photography', 'ar' => 'تصوير المنتجات'],
                'content' => [
                    'en' => [['type' => 'markdown', 'data' => ['content' => 'High-quality product photography that showcases your products in the best light.']]],
                    'ar' => [['type' => 'markdown', 'data' => ['content' => 'تصوير منتجات عالي الجودة يعرض منتجاتك في أفضل صورة.']]]
                ],
            ],
            [
                'title' => ['en' => 'Logo Design', 'ar' => 'تصميم الشعارات'],
                'content' => [
                    'en' => [['type' => 'markdown', 'data' => ['content' => 'Creating unique and memorable logos that represent your brand perfectly.']]],
                    'ar' => [['type' => 'markdown', 'data' => ['content' => 'تصميم شعارات فريدة لا تُنسى تمثل علامتك التجارية بشكل مثالي.']]]
                ],
            ],
            [
                'title' => ['en' => 'Motion Graphic Design', 'ar' => 'تصميم الموشن جرافيك'],
                'content' => [
                    'en' => [['type' => 'markdown', 'data' => ['content' => 'Engaging motion graphics to bring your brand\'s story to life.']]],
                    'ar' => [['type' => 'markdown', 'data' => ['content' => 'موشن جرافيك جذاب لإحياء قصة علامتك التجارية.']]]
                ],
            ],
            [
                'title' => ['en' => 'Publication Design', 'ar' => 'تصميم المطبوعات'],
                'content' => [
                    'en' => [['type' => 'markdown', 'data' => ['content' => 'Professional design for all your print materials, from brochures to reports.']]],
                    'ar' => [['type' => 'markdown', 'data' => ['content' => 'تصميم احترافي لجميع موادك المطبوعة، من الكتيبات إلى التقارير.']]]
                ],
            ],
            [
                'title' => ['en' => 'Poster Design', 'ar' => 'تصميم البوسترات'],
                'content' => [
                    'en' => [['type' => 'markdown', 'data' => ['content' => 'Eye-catching poster designs for your events, promotions, and campaigns.']]],
                    'ar' => [['type' => 'markdown', 'data' => ['content' => 'تصاميم بوسترات جذابة لفعالياتك وعروضك الترويجية وحملاتك.']]]
                ],
            ],
            [
                'title' => ['en' => 'Profile Design', 'ar' => 'تصميم البروفايلات'],
                'content' => [
                    'en' => [['type' => 'markdown', 'data' => ['content' => 'Crafting professional and appealing company profiles.']]],
                    'ar' => [['type' => 'markdown', 'data' => ['content' => 'صياغة بروفايلات شركات احترافية وجذابة.']]]
                ],
            ],
        ];


        foreach ($services as $index => $service) {
            $metadata = [];
            if ($index === 0) {
                $metadata['homepage_section_component'] = 'services-list';
            }
            $this->createPost('our-services', $service, $metadata, ['marketing', 'web-design']);
        }

        // 5. Create Individual Pricing Package Posts
        $pricingPackages = [
            [
                'title' => ['en' => 'Diamond Package', 'ar' => 'الباقة الماسية'],
                'content' => [
                    'en' => [['type' => 'markdown', 'data' => ['content' => '• Price: 25,000 SAR. • Services: Social media management (full) - Instagram, Twitter, Snapchat, LinkedIn, etc. • Designing highlights and expanding the bio. • Follow-up and response to customers. • Daily publishing and content writing. • Designing 250 posts, 250 stories, 250 tweets. • Service duration: one year.']]],
                    'ar' => [['type' => 'markdown', 'data' => ['content' => '• السعر: 25,000 ريال سعودي. • الخدمات: إدارة مواقع التواصل الاجتماعي (كاملة) - انستجرام، تويتر، سناب شات، لينكد إن، إلخ. • تصميم الهايلات وتوسيع البايو. • المتابعة والرد على العملاء. • النشر اليومي وكتابة المحتوى. • تصميم 250 بوست، 250 ستوري، 250 تغريدة. • مدة الخدمة: سنة.']]]
                ],
            ],
            [
                'title' => ['en' => 'Gold Package', 'ar' => 'الباقة الذهبية'],
                'content' => [
                    'en' => [['type' => 'markdown', 'data' => ['content' => '• Price: 15,000 SAR. • Services: Social media management (full). • Designing highlights and arranging the bio. • Follow-up and response to customers. • Daily publishing. • Designing 125 posts, 125 stories, 125 tweets. • Service duration: 6 months.']]],
                    'ar' => [['type' => 'markdown', 'data' => ['content' => '• السعر: 15,000 ريال سعودي. • الخدمات: إدارة مواقع التواصل الاجتماعي (كاملة). • تصميم الهايلات وترتيب البايو. • المتابعة والرد على العملاء. • النشر اليومي. • تصميم 125 بوست، 125 ستوري، 125 تغريدة. • مدة الخدمة: 6 أشهر.']]]
                ],
            ],
            [
                'title' => ['en' => 'Platinum Package', 'ar' => 'باقة البلاتينيوم'],
                'content' => [
                    'en' => [['type' => 'markdown', 'data' => ['content' => '• Price: 9,000 SAR. • Services: Social media design (full). • Designing highlights and arranging the bio. • Follow-up and response to customers. • Daily publishing. • Designing 50 posts, 50 stories, 50 tweets. • Service duration: 3 months.']]],
                    'ar' => [['type' => 'markdown', 'data' => ['content' => '• السعر: 9,000 ريال سعودي. • الخدمات: تصميم مواقع التواصل الاجتماعي (كاملة). • تصميم الهايلات وترتيب البايو. • المتابعة والرد على العملاء. • النشر اليومي. • تصميم 50 بوست، 50 ستوري، 50 تغريدة. • مدة الخدمة: 3 أشهر.']]]
                ],
            ],
            [
                'title' => ['en' => 'Silver Package', 'ar' => 'الباقة الفضية'],
                'content' => [
                    'en' => [['type' => 'markdown', 'data' => ['content' => '• Price: 6,000 SAR. • Services: Social media management (full). • Designing highlights and arranging the bio. • Follow-up and response to customers. • Daily publishing and content writing. • Designing 15 posts, 15 stories, 15 tweets. • Service duration: one month.']]],
                    'ar' => [['type' => 'markdown', 'data' => ['content' => '• السعر: 6,000 ريال سعودي. • الخدمات: إدارة مواقع التواصل الاجتماعي (كاملة). • تصميم الهايلات وترتيب البايو. • المتابعة والرد على العملاء. • النشر اليومي وكتابة المحتوى. • تصميم 15 بوست، 15 ستوري، 15 تغريدة. • مدة الخدمة: شهر.']]]
                ],
            ],
            [
                'title' => ['en' => 'Bronze Package', 'ar' => 'الباقة البرونزية'],
                'content' => [
                    'en' => [['type' => 'markdown', 'data' => ['content' => '• Price: 3,000 SAR. • Services: Social media management (full). • Designing highlights and arranging the bio. • Follow-up and response to customers. • Daily publishing and content writing. • Designing 15 posts, 15 stories, 15 tweets. • Service duration: one month.']]],
                    'ar' => [['type' => 'markdown', 'data' => ['content' => '• السعر: 3,000 ريال سعودي. • الخدمات: إدارة مواقع التواصل الاجتماعي (كاملة). • تصميم الهايلات وترتيب البايو. • المتابعة والرد على العملاء. • النشر اليومي وكتابة المحتوى. • تصميم 15 بوست، 15 ستوري، 15 تغريدة. • مدة الخدمة: شهر.']]]
                ],
            ],
        ];

        foreach ($pricingPackages as $index => $package) {
            $metadata = [];
            if ($index === 0) {
                $metadata['homepage_section_component'] = 'default';
            }
            $this->createPost('pricing-packages', $package, $metadata, ['marketing']);
        }

        $contactUsContent = [
            'title' => ['en' => 'Contact Us', 'ar' => 'تواصل معنا'],
            'meta_title' => ['en' => 'Bat-Tour Marketing - Contact Us', 'ar' => 'بطور للتسويق - تواصل معنا'],
            'meta_description' => ['en' => 'Get in touch with Bat-Tour Digital Marketing for inquiries, support, or to start your next project.', 'ar' => 'تواصل مع بطور للتسويق الرقمي للاستفسارات، الدعم، أو لبدء مشروعك القادم.'],
            'content' => [
                'en' => [
                    ['type' => 'markdown', 'data' => ['content' => 'We are here to help you achieve your digital goals. Contact us today! • Phone: +966557459656 / 00201121274776 • Email: info@bat-tourmarketing.com • Website: www.bat-tour.com • Address: Al-Yarmouk District, Riyadh, Saudi Arabia.']]
                ],
                'ar' => [
                    ['type' => 'markdown', 'data' => ['content' => 'نحن هنا لمساعدتك في تحقيق أهدافك الرقمية. تواصل معنا اليوم! • رقم الهاتف: 966557459656+ / 00201121274776 • البريد الإلكتروني: info@bat-tourmarketing.com • الموقع الإلكتروني: www.bat-tour.com • العنوان : منطقة اليرموك، الرياض، المملكة العربية السعودية.']]
                ]
            ]
        ];
        $this->createPost('contact-us', $contactUsContent, ['homepage_section_component' => 'call-action'], [], null);

        // New posts for categories that didn't have them
        $brandMarqueContent = [
            'title' => ['en' => 'Our Valued Partners', 'ar' => 'شركاؤنا الكرام'],
            'meta_title' => ['en' => 'Bat-Tour Marketing - Brand Marque', 'ar' => 'بطور للتسويق - ماركة العلامة التجارية'],
            'meta_description' => ['en' => 'Showcasing our brand partners and their contributions.', 'ar' => 'عرض شركائنا في العلامة التجارية ومساهماتهم.'],
            'content' => [
                'en' => [['type' => 'markdown', 'data' => ['content' => 'We collaborate with leading brands to deliver exceptional results.']]],
                'ar' => [['type' => 'markdown', 'data' => ['content' => 'نتعاون مع العلامات التجارية الرائدة لتقديم نتائج استثنائية.']]]
            ]
        ];
        $this->createPost('brand-marque', $brandMarqueContent, ['homepage_section_component' => 'brand-marque'], [], null);

        $faqContent = [
            'title' => ['en' => 'General Questions', 'ar' => 'أسئلة عامة'],
            'meta_title' => ['en' => 'Bat-Tour Marketing - FAQ', 'ar' => 'بطور للتسويق - الأسئلة الشائعة'],
            'meta_description' => ['en' => 'Find answers to common questions about our services.', 'ar' => 'ابحث عن إجابات للأسئلة الشائعة حول خدماتنا.'],
            'content' => [
                'en' => [['type' => 'markdown', 'data' => ['content' => 'Here are some frequently asked questions.']]],
                'ar' => [['type' => 'markdown', 'data' => ['content' => 'إليك بعض الأسئلة المتداولة.']]]
            ]
        ];
        $this->createPost('faq', $faqContent, ['homepage_section_component' => 'faq'], [], null);

        $aboutUsContent = [
            'title' => ['en' => 'Our Company Story', 'ar' => 'قصة شركتنا'],
            'meta_title' => ['en' => 'Bat-Tour Marketing - About Us', 'ar' => 'بطور للتسويق - من نحن'],
            'meta_description' => ['en' => 'Learn about our journey and what makes us unique.', 'ar' => 'تعرف على رحلتنا وما يميزنا.'],
            'content' => [
                'en' => [['type' => 'markdown', 'data' => ['content' => 'Founded in 2019, Bat-Tour Marketing has grown to become a leader in digital solutions.']]],
                'ar' => [['type' => 'markdown', 'data' => ['content' => 'تأسست بطور للتسويق عام 2019، ونمت لتصبح رائدة في الحلول الرقمية.']]]
            ]
        ];
        $this->createPost('about-us', $aboutUsContent, ['homepage_section_component' => null], [], null);

        $visionMissionContent = [
            'title' => ['en' => 'Our Core Values', 'ar' => 'قيمنا الأساسية'],
            'meta_title' => ['en' => 'Bat-Tour Marketing - Vision & Mission', 'ar' => 'بطور للتسويق - الرؤية والأهداف'],
            'meta_description' => ['en' => 'Our vision and mission drive our commitment to excellence.', 'ar' => 'رؤيتنا ورسالتنا تدفعان التزامنا بالتميز.'],
            'content' => [
                'en' => [['type' => 'markdown', 'data' => ['content' => 'We are committed to innovation, integrity, and client success.']]],
                'ar' => [['type' => 'markdown', 'data' => ['content' => 'نحن ملتزمون بالابتكار والنزاهة ونجاح العميل.']]]
            ]
        ];
        $this->createPost('vision-mission', $visionMissionContent, ['homepage_section_component' => null], [], null);

        $whyChooseUsContent = [
            'title' => ['en' => 'Why Partner with Bat-Tour?', 'ar' => 'لماذا الشراكة مع بطور؟'],
            'meta_title' => ['en' => 'Bat-Tour Marketing - Why Choose Us?', 'ar' => 'بطور للتسويق - لماذا نحن؟'],
            'meta_description' => ['en' => 'Discover the unique advantages of choosing Bat-Tour Marketing.', 'ar' => 'اكتشف المزايا الفريدة لاختيار بطور للتسويق.'],
            'content' => [
                'en' => [['type' => 'markdown', 'data' => ['content' => 'Our expertise, dedication, and proven results make us the ideal choice.']]],
                'ar' => [['type' => 'markdown', 'data' => ['content' => 'خبرتنا وتفانينا ونتائجنا المثبتة تجعلنا الخيار الأمثل.']]]
            ]
        ];
        $this->createPost('why-choose-us', $whyChooseUsContent, ['homepage_section_component' => null], [], null);

        $pricingPackagesContent = [
            'title' => ['en' => 'Our Service Packages', 'ar' => 'باقات خدماتنا'],
            'meta_title' => ['en' => 'Bat-Tour Marketing - Pricing Packages', 'ar' => 'بطور للتسويق - باقات الأسعار'],
            'meta_description' => ['en' => 'Explore our flexible and competitive pricing packages.', 'ar' => 'اكتشف باقات الأسعار المرنة والتنافسية.'],
            'content' => [
                'en' => [['type' => 'markdown', 'data' => ['content' => 'We offer a range of packages designed to meet diverse business needs.']]],
                'ar' => [['type' => 'markdown', 'data' => ['content' => 'نقدم مجموعة من الباقات المصممة لتلبية احتياجات الأعمال المتنوعة.']]]
            ]
        ];
        $this->createPost('pricing-packages', $pricingPackagesContent, ['homepage_section_component' => null], [], null);

        // Example blog posts
        $blogPost1 = [
            'title' => ['en' => 'The Future of Digital Marketing', 'ar' => 'مستقبل التسويق الرقمي'],
            'meta_title' => ['en' => 'Digital Marketing Trends', 'ar' => 'اتجاهات التسويق الرقمي'],
            'meta_description' => ['en' => 'Explore the upcoming trends shaping the digital marketing landscape.', 'ar' => 'اكتشف الاتجاهات القادمة التي تشكل مشهد التسويق الرقمي.'],
            'content' => [
                'en' => [['type' => 'markdown', 'data' => ['content' => 'Artificial intelligence, personalization, and video content are key.']]],
                'ar' => [['type' => 'markdown', 'data' => ['content' => 'الذكاء الاصطناعي، التخصيص، ومحتوى الفيديو هي المفتاح.']]]
            ]
        ];
        $this->createPost('blog', $blogPost1, ['homepage_section_component' => 'latest-news'], ['marketing', 'digital'], null);

        $blogPost2 = [
            'title' => ['en' => 'SEO Best Practices for 2025', 'ar' => 'أفضل ممارسات تحسين محركات البحث لعام 2025'],
            'meta_title' => ['en' => 'SEO Guide', 'ar' => 'دليل تحسين محركات البحث'],
            'meta_description' => ['en' => 'A comprehensive guide to optimizing your website for search engines in 2025.', 'ar' => 'دليل شامل لتحسين موقع الويب الخاص بك لمحركات البحث في عام 2025.'],
            'content' => [
                'en' => [['type' => 'markdown', 'data' => ['content' => 'Focus on user experience, mobile-first indexing, and semantic search.']]],
                'ar' => [['type' => 'markdown', 'data' => ['content' => 'ركز على تجربة المستخدم، الفهرسة المتنقلة أولاً، والبحث الدلالي.']]]
            ]
        ];
        $this->createPost('blog', $blogPost2, ['homepage_section_component' => 'latest-news'], ['seo'], null);

        $portfolioContent = [
            'title' => ['en' => 'Our Creative Portfolio', 'ar' => 'أعمالنا الإبداعية'],
            'meta_title' => ['en' => 'Bat-Tour Marketing - Portfolio', 'ar' => 'بطور للتسويق - أعمالنا'],
            'meta_description' => ['en' => 'Explore our diverse range of successful projects and creative solutions.', 'ar' => 'استكشف مجموعتنا المتنوعة من المشاريع الناجحة والحلول الإبداعية.'],
            'content' => [
                'en' => [['type' => 'markdown', 'data' => ['content' => 'From stunning websites to engaging campaigns, see what we can do for you.']]],
                'ar' => [['type' => 'markdown', 'data' => ['content' => 'من المواقع الإلكترونية المذهلة إلى الحملات الجذابة، شاهد ما يمكننا فعله لك.']]]
            ]
        ];
        $this->createPost('portfolio', $portfolioContent, ['homepage_section_component' => 'portfolio'], [], null);

        $featuresContent = [
            'title' => ['en' => 'Key Features of Our Services', 'ar' => 'الميزات الرئيسية لخدماتنا'],
            'meta_title' => ['en' => 'Bat-Tour Marketing - Features', 'ar' => 'بطور للتسويق - المميزات'],
            'meta_description' => ['en' => 'Discover the unique advantages and benefits of choosing our digital marketing solutions.', 'ar' => 'اكتشف المزايا والفوائد الفريدة لاختيار حلول التسويق الرقمي لدينا.'],
            'content' => [
                'en' => [['type' => 'markdown', 'data' => ['content' => 'We offer innovative strategies, expert team, and measurable results.']]],
                'ar' => [['type' => 'markdown', 'data' => ['content' => 'نقدم استراتيجيات مبتكرة، فريق خبراء، ونتائج قابلة للقياس.']]]
            ]
        ];
        $this->createPost('features', $featuresContent, ['homepage_section_component' => 'services-features'], [], null);

        $testimonialsContent = [
            'title' => ['en' => 'What Our Clients Say', 'ar' => 'ماذا يقول عملاؤنا'],
            'meta_title' => ['en' => 'Bat-Tour Marketing - Testimonials', 'ar' => 'بطور للتسويق - الآراء'],
            'meta_description' => ['en' => 'Read authentic feedback from our satisfied clients about their experience with Bat-Tour Marketing.', 'ar' => 'اقرأ آراء حقيقية من عملائنا الراضين حول تجربتهم مع بطور للتسويق.'],
            'content' => [
                'en' => [['type' => 'markdown', 'data' => ['content' => 'Our clients consistently praise our professionalism and effectiveness.']]],
                'ar' => [['type' => 'markdown', 'data' => ['content' => 'يثني عملاؤنا باستمرار على احترافيتنا وفعاليتنا.']]]
            ]
        ];
        $this->createPost('testimonials', $testimonialsContent, ['homepage_section_component' => 'services-carousel'], [], null);
    }

    private function createPost(string $categorySlug, array $data, array $metadata = [], array $tags = [], ?int $imageId = null): void
    {
        $category = PostCategory::where('slug', $categorySlug)->first();
        if (is_null($category)) {
            dd('Category not found for slug:', $categorySlug);
        }

        $post = Post::updateOrCreate(['slug' => Str::slug($data['title']['en'])], [
            'title' => $data['title'],
            'content' => $data['content'],
            'meta_title' => $data['meta_title'] ?? $data['title'],
            'meta_description' => $data['meta_description'] ?? null,
            'image_id' => $imageId,
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
