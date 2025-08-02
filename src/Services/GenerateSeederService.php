<?php

namespace Taba\crm\Services;
use Gemini\Enums\ModelVariation;
use Gemini\GeminiHelper;
use Gemini;
// use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GenerateSeederService
{
    protected $geminiClient;
    protected string $model;

    public function __construct()
    {
        // Ensure GEMINI_API_KEY is set in your .env file
        $apiKey = env('GEMINI_API_KEY');

        if (empty($apiKey)) {
            throw new \Exception('GEMINI_API_KEY environment variable is not set.');
        }

        // Initialize the Gemini client using the Laravel facade
        $this->geminiClient = Gemini::client($apiKey);

        // Define the model to use. You can make this configurable if needed.
        // Using 'gemini-2.0-flash' directly as per previous conversations.
        $this->model = 'gemini-2.0-flash';
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

        // This is a "few-shot" prompt. We provide a perfect example to the AI
        // to ensure it follows the exact structure, including custom helper methods.
        $prompt = <<<PROMPT
You are an expert Laravel developer. Your task is to take new unstructured text and convert it into a complete Laravel Seeder class file that perfectly matches the structure, style, and logic of the example provided.

**### EXAMPLE SEEDER ###**
```php
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
                'email' => 'admin@relax-sofa.com',
                'password' => Hash::make('password'),
            ]);
        }

        // 1. Create Categories
        \$categories = [
            ['name' => ['en' => 'Home', 'ar' => 'الرئيسية'], 'slug' => 'hero', 'register_in_header' => 0, 'section_component' => 'hero'],
            ['name' => ['en' => 'About Us', 'ar' => 'من نحن'], 'slug' => 'about-us', 'register_in_header' => 1, 'section_component' => 'about', 'subtitle' => ['en' => 'Experts in sofa and majlis design', 'ar' => 'متخصصون في تصميم وتفصيل الكنب والمجالس']],
            // ... more categories
        ];
        foreach (\$categories as \$categoryData) {
            PostCategory::updateOrCreate(['slug' => \$categoryData['slug']], \$categoryData);
        }

        // 2. Create Tags
        \$tags = [
            ['name' => ['en' => 'Sofa Design', 'ar' => 'تصميم كنب'], 'slug' => 'sofa-design'],
            // ... more tags
        ];
        foreach (\$tags as \$tagData) {
            Tag::updateOrCreate(['slug' => \$tagData['slug']], ['name' => \$tagData['name']]);
        }

        // 3. Create Posts using the helper method
        \$this->createPost('hero', [
            'title' => ['en' => 'Hero Section', 'ar' => 'مرحبًا بك في الاريكة المريحة'],
            'content' => [
                'en' => [['type' => 'markdown', 'data' => ['content' => "Designing and detailing sofas and Arabic majlises with the best materials and highest quality standards.\n\nReady-made designs to help you choose, or custom detailing available."]]],
                'ar' => [['type' => 'markdown', 'data' => ['content' => "تصميم وتفصيل الكنب والمجالس العربية بأفضل الخامات وأعلى معايير الجودة\n\nتصميمات جاهزة تساعدك على الاختيار أو تفصيل حسب الطلب"]]],
            ]
            // ... more post data
        ]);
    }

    private function createPost(string \$categorySlug, array \$data, array \$metadata = [], array \$tags = []): void
    {
        \$category = PostCategory::where('slug', \$categorySlug)->firstOrFail();
        \$postData = [
            'title' => \$data['title'],
            'content' => \$data['content'] ?? null,
            'meta_title' => \$data['meta_title'] ?? \$data['title'],
            'meta_description' => \$data['meta_description'] ?? null,
            'post_category_id' => \$category->id,
            'user_id' => 1,
            'is_published' => true,
            'published_at' => now(),
            'metadata' => \$metadata,
        ];
        \$uniqueIdentifier = ['slug' => Str::slug(\$data['title']['en']), 'post_category_id' => \$category->id];
        \$post = Post::updateOrCreate(\$uniqueIdentifier, \$postData);
        if (!empty(\$tags)) {
            \$tagModels = Tag::whereIn('slug', \$tags)->pluck('id');
            \$post->tags()->sync(\$tagModels);
        }
    }
}
```
**### END OF EXAMPLE ###**

**### YOUR TASK ###**
Now, using the example above as a strict template, generate a new seeder class named `AISiteSeeder` based on the following unstructured text. Ensure you include the `createPost` helper method and all necessary logic.
and also try to saparate as possiable to be more categories and more posts
The final output must be **ONLY the raw PHP code** for the seeder class, without the `<?php` tag or any markdown formatting.

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
