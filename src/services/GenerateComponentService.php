<?php

namespace Taba\Crm\Services;

use Gemini;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GenerateComponentService
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
     * Converts a block of Blade/HTML code into multiple, separated Blade component files.
     *
     * @param string $rawCode The source Blade/HTML code.
     * @return array|null An associative array of [filename => code], or null on failure.
     */
    public function generate(string $rawCode): ?array
    {
        if (empty(trim($rawCode))) {
            return null;
        }

        $prompt = <<<PROMPT
            You are a senior frontend developer and UI/UX designer with expertise in creating modern, clean, and responsive web components using Tailwind CSS and Laravel Blade.

            **### YOUR TASK ###**
            Your task is to take the following new block of Blade code, completely **REDESIGN** its visual appearance, and then refactor the new design into a set of clean, reusable Blade components based on the rules provided.

            ### THE GOLDEN RULES: props(['posts']) ONLY,THE DESIGNS MUST BE EQUAL TO THE RAWCODE DESIGN ###
            Your mission is to take a raw Laravel Blade code snippet and transform it into a visually stunning, self-contained component. The final output must be a component file (or files) that can be rendered directly from a Laravel controller, passing only a single \$posts collection.

            **### PERFECT OUTPUT EXAMPLE (for a section design) ###**
            This example demonstrates the correct output when the new design contains only ONE `<section>` tag. The result is a single JSON key-value pair. The Blade code must be a properly escaped JSON string.
            ```json
                {
                    "services-section.blade.php": "@props(['posts'])\n\n@if(\$posts->isNotEmpty())\n@php\n\$post = \$posts->first();\n@endphp\n<section class=\"py-20 bg-gradient-to-r from-slate-900/50 to-purple-900/20 relative overflow-hidden rtl:text-right\">\n  <div class=\"absolute top-10 left-5 w-[650px] h-[550px] blur-[150px] rounded-full bg-gradient-to-br from-purple-500 to-blue-500 opacity-60 -z-10 rtl:right-5 rtl:left-auto\"></div>\n  \n  <div class=\"container mx-auto px-4 py-16 rtl:px-4\">\n    <div class=\"max-w-[560px] mb-16 rtl:ml-auto\">\n      <span class=\"text-xs uppercase text-primary-color font-medium\">{{ \$post->postCategory->name }}</span>\n      <h2 class=\"text-3xl md:text-4xl lg:text-5xl font-bold text-white mt-4\">\n        {{ \$post->postCategory->description }}\n      </h2>\n    </div>\n\n    <div class=\"grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8\">\n      @foreach(\$posts as \$index => \$post)\n      <div class=\"rounded-3xl relative overflow-hidden p-8 bg-white/10 backdrop-blur-[40px] group transition-all duration-500 hover:scale-105 border border-transparent hover:border-primary-color rtl:hover:-translate-x-2\">\n        <div class=\"mb-8\">\n          <span class=\"w-16 h-16 bg-primary-color rounded-xl inline-flex justify-center items-center\">\n            @if(!empty(\$post->icon))\n            <i class=\"{{ \$post->icon }} text-3xl text-white group-hover:scale-x-[-1] transition-all duration-500\"></i>\n            @else\n            <img src=\"{{ asset(\$post->image?->url ?? \$post->getRandomImage()) }}\" alt=\"{{ \$post->title ?? '' }}\"\n              class=\"w-8 h-8 object-contain\" />\n            @endif\n          </span>\n        </div>\n        <div>\n          <h3 class=\"text-xl md:text-2xl font-bold text-white mb-4\">\n            {{ \$post->title ?? '' }}\n          </h3>\n          <div class=\"prose dark:prose-invert text-gray-300\">\n            @if(!empty(\$post->excerpt))\n              {{ \$post->excerpt }}\n            @else\n              @foreach (\$post->blocks as \$block)\n                @switch(\$block->type)\n                  @case('markdown')\n                    @markdom(\$block->data->content)\n                    @break\n                  @case('figure')\n                    <x-figure :image=\"\$block->data->image\" :alt=\"\$block->data->alt\" :caption=\"\$block->data->caption\" />\n                    @break\n                  @default\n                    @dump(\$block)\n                @endswitch\n              @endforeach\n            @endif\n          </div>\n        </div>\n        <a class=\"absolute top-0 left-0 w-full h-full z-10\" href=\"#\"></a>\n      </div>\n      @endforeach\n    </div>\n  </div>\n</section>\n@endif"
                }
            ```
            **### END OF EXAMPLE ###**

            **### INSTRUCTIONS ###**
            convert just the design to be just like below 
            **Output Format:** The final output must be a single, valid JSON object where keys are the filenames and values are the corresponding Blade code. Return **ONLY the raw JSON object**, without any explanations or markdown.

            **### NEW BLADE CODE TO REDESIGN & REFACTOR ###**
            ---
            {$rawCode}
            ---
            PROMPT;

        try {
            $result = $this->geminiClient
                ->generativeModel(model: $this->model)
                ->generateContent($prompt);
            $jsonResponse = $result->text();

            // Clean the response to ensure it's valid JSON
            $cleanedJson = Str::of($jsonResponse)
                ->remove('```json')
                ->remove('```')
                ->trim();

            $structuredData = json_decode($cleanedJson, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($structuredData)) {
                return $structuredData;
            }

            Log::error('Gemini Component Service Error: Invalid JSON response.', ['response' => $jsonResponse]);
            return null;

        } catch (\Throwable $e) {
            Log::error("Gemini Component Service Exception: " . $e->getMessage(), [
                'source_code' => $rawCode,
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }
}