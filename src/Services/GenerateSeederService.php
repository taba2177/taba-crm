<?php

namespace Taba\Crm\Services;

use Gemini;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Taba\Crm\Models\CrmSetting;

class GenerateSeederService
{
    protected $geminiClient;
    protected string $model;

    public function __construct()
    {
        $apiKey = CrmSetting::get('crm_gemini_api_key'); //config('crm.gemini_api_key');
        if (empty($apiKey)) {
            throw new \Exception('GEMINI_API_KEY is not configured. Please set it in config/crm.php or .env file.');
        }
        $this->geminiClient = Gemini::client($apiKey);
        // Using a powerful model is crucial for complex instruction following and code generation.
        $this->model = 'gemini-2.0-flash-lite';
    }

    /**
     * Converts unstructured text into a complete Laravel seeder class file using Gemini AI.
     *
     * @param string $text The unstructured source text describing the website content.
     * @return string|null The full PHP code for the seeder class, or null on failure.
     */
    public function generate(string $text): ?string
    {
        if (empty(trim($text))) {
            return null;
        }

        // A highly-detailed, professional prompt designed for maximum accuracy and structure.
        $prompt = <<<PROMPT
You are a senior Laravel developer specializing in data architecture and content management systems. Your task is to transform unstructured text into a complete, production-ready Laravel Seeder class. You must meticulously follow the data model, philosophy, and strict rules outlined below, using the provided "Golden Example" as the ultimate source of truth for structure and style.

**### Core Philosophy & Data Model ###**
you will make full categorieable content for the page depends on the prevented content
the content must be full with its all details ,you can focuse on arabic and leave any "en" just empty
* **`PostCategory` is a "Section"**: Think of a `PostCategory` as a major, navigable section of a webpage (e.g., 'Hero', 'About Us', 'Our Services', 'Testimonials', 'FAQ'). You must infer all its details: `name`, a URL-friendly `slug`, a `subtitle`, a descriptive `description`, a display `order`, whether it `register_in_header`, and a `section_component` name that hints at its frontend rendering.
* **`Post` is a "Content Block"**: Think of a `Post` as a single, discrete piece of content *within* a `PostCategory`. A section can, and often should, be composed of many `Post` records. For example, a "Why Choose Us" section should be broken down into multiple `Post` records, one for each reason. An "Our Services" section must have a separate `Post` for each individual service listed.
* **`metadata` is for "Structured Data"**: The `metadata` JSON field is critical. It is used to store any structured data that isn't plain paragraph text. This includes statistics, lists of features, key-value pairs, social media links, button text, or filter tags. Your job is to identify this data and model it as a clean PHP array.

**### The Metadata Cookbook: How to Structure `metadata` ###**

Use the following patterns to model data for the `\$metadata` parameter of the `createPost` helper method.

* **For Key-Value Information (e.g., Contact Details):**
    ```php
    ['contact_details' => [
        ['key' => ['en' => '', 'ar' => 'رقم الهاتف'], 'value' => '+966557459656'],
        ['key' => ['en' => '', 'ar' => 'البريد الإلكتروني'], 'value' => 'info@bat-tourmarketing.com'],
    ]]
    ```

**### STRICT RULES OF ENGAGEMENT ###**

1.  **CONTENT INTEGRITY (ABSOLUTE RULE):** You **MUST** use the exact text provided in the input. Do not paraphrase, summarize, invent, or omit any content. Your sole purpose is to structure, not to author.
2.  **GRANULARITY MANDATE:** Decompose the source text into the smallest logical pieces. If you see a list of services, values, team members, or FAQ questions, each item **MUST** become its own separate `Post` record. Do not group them in a single post.
3.  **METADATA MASTERY:** Diligently apply the **Metadata Cookbook** patterns. Identify any structured data and correctly format it into the `\$metadata` array for the `createPost` method.
4.  **COMPONENT INFERENCE:** Intelligently infer the `section_component` name for each `PostCategory` based on its content. Use common web patterns like: 'hero', 'about-us', 'services-list', 'pricing-packages', 'portfolio', 'testimonials', 'faq', 'contact', 'call-action', 'brand-marque'.
5.  **LANGUAGE-SPECIFIC INSTRUCTIONS:**
    * For all multilingual fields (`name`, `title`, `content`, etc.), the `ar` key should contain the provided Arabic text.
    * The `en` key's content **MUST be an empty string `''`**.
    * The `content` for Arabic should be styled using basic markdown for headings (`#`, `##`, `###`) and lists (`*`).
6.  **TEMPLATE ADHERENCE:** The final output **MUST** be a complete seeder class that perfectly mirrors the structure, methods, namespaces, and logic of the "Golden Example" below. This includes the `createPost` helper method.
7.  **OUTPUT FORMAT:** The final output must be **ONLY the raw PHP code** for the seeder class. Do **NOT** include the opening `<?php` tag or any markdown formatting like `\`\`\`php`.

**### GOLDEN EXAMPLE (Full Version - Your Source of Truth) ###**
```
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
        \$categories = [
            [
                'name' => ['en' => '', 'ar' => 'الصفحة الرئيسية'],
                'slug' => 'homepage',
                'subtitle' => ['en' => '', 'ar' => 'التسويق الالكتروني العالمي'],
                'description' => ['en' => '', 'ar' => 'الصفحة الرئيسية لموقعنا، تعرض آخر التحديثات وأبرز النقاط.'],
                'order' => 1,
                'register_in_header' => true,
                'section_component' => 'hero'
            ],
            [
                'name' => ['en' => '', 'ar' => 'من نحن'],
                'slug' => 'about-us',
                'subtitle' => ['en' => '', 'ar' => 'تعرف على المزيد عنا'],
                'description' => ['en' => '', 'ar' => 'تعرف على قصتنا وفريقنا والقيم التي تحركنا.'],
                'order' => 2,
                'register_in_header' => true,
                'section_component' => 'about-us'
            ],
            [
                'name' => ['en' => 'Our Services', 'ar' => 'خدماتنا'],
                'slug' => 'our-services',
                'subtitle' => ['en' => 'What We Do', 'ar' => 'ماذا نقدم'],
                'description' => ['en' => '', 'ar' => 'نظرة شاملة على الخدمات الاحترافية التي نقدمها.'],
                'order' => 5,
                'register_in_header' => true,
                'section_component' => 'services-list'
            ],
        ];

        foreach (\$categories as \$categoryData) {
            PostCategory::updateOrCreate(['slug' => \$categoryData['slug']], [
                'name' => \$categoryData['name'],
                'description' => \$categoryData['description'],
                'order' => \$categoryData['order'] ?? null,
                'register_in_header' => \$categoryData['register_in_header'] ?? false,
                'subtitle' => \$categoryData['subtitle'] ?? null,
                'section_component' => \$categoryData['section_component'] ?? null,
            ]);
        }

        // 2. Create Tags
        \$tags = [
            ['name' => ['en' => 'Marketing', 'ar' => 'تسويق'], 'slug' => 'marketing'],
            ['name' => ['en' => 'Digital', 'ar' => 'رقمي'], 'slug' => 'digital'],
        ];

        foreach (\$tags as \$tagData) {
            Tag::updateOrCreate(['slug' => \$tagData['slug']], [
                'name' => \$tagData['name'],
            ]);
        }

        // 3. Create Posts
        \$homepageContent = [
            'title' => ['en' => '', 'ar' => 'مرحباً بكم في بطور للتسويق الالكتروني'],
            'content' => [
                'en' => [['type' => 'markdown', 'data' => ['content' => '']]],
                'ar' => [['type' => 'markdown', 'data' => ['content' => '## القوة، السرعة، الذكاء في عالم التسويق الرقمي المتغير باستمرار، نحلق بعلامتك التجارية إلى السماء.']]]
            ]
        ];
        \$this->createPost('homepage', \$homepageContent, [
            'fun_facts' => [
                ['number' => 100, 'text' => ['en' => '', 'ar' => 'سنوات من <br />الخبرة']],
                ['number' => '100+', 'text' => ['en' => '', 'ar' => 'مشروع <br />مكتمل']],
            ],
            'social_links' => [
                ['icon' => 'fa-brands fa-twitter', 'url' => '#'],
            ],
        ], ['marketing', 'digital']);

        \$aboutUsPost = [
            'title' => ['en' => '', 'ar' => 'قصتنا'],
            'content' => [
                'en' => [['type' => 'markdown', 'data' => ['content' => '']]],
                'ar' => [['type' => 'markdown', 'data' => ['content' => '# قصة شركتنا \n بدأ فريقنا في عام 2019 من مصر...']]]
            ]
        ];
        \$this->createPost('about-us', \$aboutUsPost, [], ['marketing']);

        \$servicePost = [
            'title' => ['en' => '', 'ar' => 'بناء العلامات التجارية والهوية البصرية'],
            'content' => [
                'en' => [['type' => 'markdown', 'data' => ['content' => '']]],
                'ar' => [['type' => 'markdown', 'data' => ['content' => 'نؤسس ونعزز هوية علامتك التجارية لخلق انطباع دائم.']]]
            ]
        ];
        \$this->createPost('our-services', \$servicePost, [], ['marketing']);
    }

    private function createPost(string \$categorySlug, array \$data, array \$metadata = [], array \$tags = []): void
    {
        \$category = PostCategory::where('slug', \$categorySlug)->first();
        if (is_null(\$category)) {
            // In a real scenario, you might throw an exception or log an error.
            // For this seeder, we'll just skip it if the category isn't found.
            return;
        }

        \$post = Post::updateOrCreate(['slug' => Str::slug(\$data['title']['ar'])], [
            'title' => \$data['title'],
            'content' => \$data['content'],
            'meta_title' => \$data['meta_title'] ?? \$data['title'],
            'meta_description' => \$data['meta_description'] ?? null,
            'post_category_id' => \$category->id,
            'user_id' => 1,
            'is_published' => true,
            'published_at' => now(),
            'metadata' => \$metadata,
            'homepage_section_component' => \$data['homepage_section_component'] ?? null,
            'homepage_section_content' => \$data['homepage_section_content'] ?? null,
        ]);

        if (!empty(\$tags)) {
            \$tagModels = Tag::whereIn('slug', \$tags)->pluck('id');
            \$post->tags()->sync(\$tagModels);
        }
    }
}
**### NEW UNSTRUCTURED TEXT TO PROCESS ###**
---
{$text}
---
PROMPT;

        try {
            // Using a more powerful model is recommended for complex code generation
            $result =   $this->geminiClient
                ->generativeModel(model: $this->model)
                ->generateContent($prompt);
            $phpCode = $result->text();

            // Clean the response to ensure it's just the raw PHP code.
            $cleanedCode = Str::of($phpCode)
                ->remove('```php')
                ->remove('```')
                ->trim();

            return $cleanedCode;

        } catch (\Throwable $e) {
            Log::error("Gemini Seeder Generation Exception: " . $e->getMessage(), [
                'source_text' => $text,
                'trace' => $e->getTraceAsString(),
            ]);
            return null; // Indicate failure
        }
    }
}