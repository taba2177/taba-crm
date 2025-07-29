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
            the post description data in :
                @foreach (\$post->blocks as \$block)
                  @switch(\$block->type)
                  @case('markdown')
                  @markdom(\$block->data->content)

            **### PERFECT OUTPUT EXAMPLE (for a single-section design) ###**
            This example demonstrates the correct output when the new design contains only ONE `<section>` tag. The result is a single JSON key-value pair. The Blade code must be a properly escaped JSON string.
            ```json
            {
                "example-section-component.blade.php": "@props(['posts'])\n\n@if(\$posts->isNotEmpty())\n<section class=\"bg-gray-100 dark:bg-gray-900 py-12 sm:py-16 lg:py-20\">\n    <div class=\"mx-auto max-w-7xl px-4 sm:px-6 lg:px-8\">\n        <div class=\"grid grid-cols-1 gap-8 md:grid-cols-2 xl:grid-cols-3\">\n            @foreach (\$posts as \$post)\n            {{-- Each card is a container for the post's blocks --}}\n            <div class=\"rounded-xl bg-white dark:bg-gray-800 p-6 lg:p-8 shadow-md ring-1 ring-gray-900/5 dark:ring-white/10\">\n                \n                {{-- Renders the exact block structure provided --}}\n                <div class=\"prose prose-sm dark:prose-invert max-w-none\">\n                    @foreach (\$post->blocks as\$block)\n                        @switch(\$block->type)\n                            @case('markdown')\n                                @markdom(\$block->data->content)\n                                @break\n\n                            @case('figure')\n                                {{-- Inlined <figure> to avoid using forbidden props on a sub-component --}}\n                                <figure class=\"!my-5\">\n                                    <img src=\"{{ \$block->data->image }}\" alt=\"{{ \$block->data->alt ?? '' }}\" class=\"w-full rounded-lg object-cover shadow-md\">\n                                    @if(!empty(\$block->data->caption))\n                                        <figcaption class=\"!mt-2 !text-xs !text-center text-gray-500 dark:text-gray-400\">{{ \$block->data->caption }}</figcaption>\n                                    @endif\n                                </figure>\n                                @break\n\n                            @default\n                                @if(app()->isLocal())\n                                    @dump(\$block)\n                                @endif\n                        @endswitch\n                    @endforeach\n                </div>\n\n            </div>\n            @endforeach\n        </div>\n    </div>\n</section>\n@endif\n"
            }
            ```
            **### END OF EXAMPLE ###**

            **### INSTRUCTIONS ###**
            1.  **Discard Old Styling:** Completely ignore the existing CSS classes and structure from the input code.
            2.  **Create a New, Modern UI:** Use standard Tailwind CSS utilities to create a fresh, professional design.
            3.  **Preserve Blade Logic:** You MUST preserve all original Blade logic, variables, and data access (e.g., `\$post->title`, `\$posts->first()->postCategory->name`).
            4.  **Component Structure (IMPORTANT LOGIC):**
                            and exstract he content in same this strucrue :
                    <div class="prose dark:prose-invert">
                        @foreach (\$post->blocks as \$block)
                        @switch(\$block->type)
                        @case('markdown')
                        @markdom(\$block->data->content)
                        @break
                        @case('figure')
                        <x-figure :image="\$block->data->image" :alt="\$block->data->alt" :caption="\$block->data->caption" />
                        @break
                        @default
                        @dump(\$block)
                        @endswitch
                        @endforeach
                    </div>
                    figure.blade.php already created 
                    all static content must be replaced by post data ,the final result must equal the rawcode design ,you must use src="{{ asset(\$post->image?->url ?? \$post->getRandomImage()) }}"
                use "rtl:" to apply multibale direction design,and in colors just use primary ,secondary,and gray,and white,black,
                the new design must be section handling posts must also begin with @props(['posts']) and not @props(['post']) \\n@if(\$posts->isNotEmpty())\\n@php\\n\$post=\$posts->first();\\n@endphp,and has the same post->blocks structure, dont ever use @props(['post']) becouse there is no post in props just posts and all static content must replaced with dynamic content .
                * **IF** your new design contains **more than one** `<section>` tag, then you MUST break the design into multiple component files (e.g., a main section and a repeatable card).
                * **ELSE IF** your new design contains only **one** `<section>` tag, you MUST return the entire new design as a **single component file**, just like in the example above. Do not split it.
            5.  **Output Format:** The final output must be a single, valid JSON object where keys are the filenames and values are the corresponding Blade code. Return **ONLY the raw JSON object**, without any explanations or markdown.

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
