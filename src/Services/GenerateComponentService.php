<?php

namespace Taba\Crm\Services;

use Gemini;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GenerateComponentService
{
    protected $geminiClient;
    protected string $model;

    /**
     * A reasonable character limit to decide when to split the code into chunks.
     * Most models have token limits around 8k-32k tokens. 4000 characters is a safe
     * and conservative threshold to avoid hitting these limits with the added prompt text.
     */
    private const CODE_LENGTH_THRESHOLD = 400000;

    public function __construct()
    {
        $apiKey = env('GEMINI_API_KEY');
        if (empty($apiKey)) {
            throw new \Exception('GEMINI_API_KEY environment variable is not set.');
        }
        $this->geminiClient = Gemini::client($apiKey);
        $this->model = 'gemini-2.0-flash';
    }

    /**
     * Converts a block of Blade/HTML code into multiple, separated Blade component files.
     * Handles both short and long code blocks by chunking if necessary.
     *
     * @param string $rawCode The source Blade/HTML code.
     * @return array|null An associative array of [filename => code], or null on failure.
     */
    public function generate(string $rawCode): ?array
    {
        if (empty(trim($rawCode))) {
            return null;
        }

        // If the code is long, split it and process each section individually.
        if (strlen($rawCode) > self::CODE_LENGTH_THRESHOLD) {
            $sections = $this->splitCodeIntoSections($rawCode);
            $allGeneratedFiles = [];

            foreach ($sections as $index => $sectionCode) {
                Log::info("Processing chunk " . ($index + 1) . " of " . count($sections));
                $generatedFiles = $this->processCodeChunk($sectionCode);

                if ($generatedFiles) {
                    $allGeneratedFiles = array_merge($allGeneratedFiles, $generatedFiles);
                } else {
                    Log::error("Failed to generate components for chunk " . ($index + 1));
                    // Optionally, you could decide to fail the entire process here.
                    // For now, we'll continue and try to process the other chunks.
                }
            }

            return !empty($allGeneratedFiles) ? $allGeneratedFiles : null;
        }

        // If the code is short, process it in a single request.
        return $this->processCodeChunk($rawCode);
    }

    /**
     * Splits a string of HTML into an array of strings, with each element
     * containing one top-level <section> tag and its content.
     *
     * @param string $html The raw HTML/Blade code.
     * @return array An array of HTML/Blade sections.
     */
    private function splitCodeIntoSections(string $html): array
    {
        // This regex splits the string by the <section> tag, keeping the tag in the result.
        // The `(?=<section\b)` is a positive lookahead that finds the position
        // right before "<section" without consuming it as part of the delimiter.
        // PREG_SPLIT_NO_EMPTY ensures we don't get an empty first element.
        $sections = preg_split('/(?=<section\b)/i', $html, -1, PREG_SPLIT_NO_EMPTY);

        return array_map('trim', $sections);
    }

    /**
     * Processes a single chunk of Blade/HTML code by sending it to the Gemini API.
     *
     * @param string $codeChunk The source Blade/HTML code chunk.
     * @return array|null An associative array of [filename => code], or null on failure.
     */
    private function processCodeChunk(string $codeChunk): ?array
    {
        $prompt = <<<PROMPT
        You are a senior frontend developer and UI/UX designer with expertise in creating modern, clean, and responsive web components using Tailwind CSS and Laravel Blade.

        **### YOUR TASK ###**
        Your task is to take the following new block of Blade code, completely **REDESIGN** its visual appearance, and then refactor the new design into a set of clean, reusable Blade components based on the rules provided.

        ### THE GOLDEN RULES: props(['posts']) ONLY,THE DESIGNS MUST BE EQUAL TO THE RAWCODE DESIGN ###
        Your mission is to take a raw Laravel Blade code snippet and transform it into a visually stunning, self-contained component. The final output must be a component file (or files) that can be rendered directly from a Laravel controller, passing only a single \$posts collection.

        **### PERFECT OUTPUT EXAMPLE (for a single-section design) ###**
        This example demonstrates the correct output when the new design contains only ONE `<section>` tag. The result is a single JSON key-value pair. The Blade code must be a properly escaped JSON string.
        ```json
        {
            "why-choose-us-section.blade.php": "@props(['posts'])\n\n@if(\$posts->isNotEmpty())\n<section class=\"py-20 bg-gradient-to-r from-slate-900/50 to-purple-900/20 rtl:text-right\">\n    <div class=\"container mx-auto px-4 rtl:px-4\">\n        <div class=\"text-center mb-16 rtl:text-right\">\n            <h2 class=\"text-4xl md:text-5xl font-bold mb-6\"><span class=\"gradient-text\">{{ \$posts->first()->postCategory->name }}</span></h2>\n            <p class=\"text-xl text-gray-300 max-w-2xl mx-auto rtl:mx-auto\">{{ \$posts->first()->postCategory->description }}</p>\n        </div>\n        <div class=\"grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 rtl:gap-8\">\n            @foreach(\$posts as \$post)\n                <div class=\"text-center p-6 rounded-2xl glass-effect hover:scale-105 transition-transform duration-300 rtl:hover:-translate-x-2\">\n                    <div class=\"text-4xl mb-4\">\n                        @if(!empty(\$post->icon))\n                            <i class=\"{{ \$post->icon }}\"></i>\n                        @else\n                            <img src=\"{{ asset(\$post->image?->url ?? \$post->getRandomImage()) }}\" alt=\"{{ \$post->title ?? '' }}\" class=\"w-10 h-10 mx-auto\">\n                        @endif\n                    </div>\n                    <h3 class=\"text-xl font-bold text-white mb-3\">{{ \$post->title ?? '' }}</h3>\n                    <div class=\"prose dark:prose-invert text-gray-300\">\n                        @if(!empty(\$post->excerpt))\n                            {{ \$post->excerpt }}\n                        @else\n                            @foreach (\$post->blocks as \$block)\n                                @switch(\$block->type)\n                                    @case('markdown')\n                                        @markdom(\$block->data->content)\n                                        @break\n                                    @case('figure')\n                                        <x-figure :image=\"\$block->data->image\" :alt=\"\$block->data->alt\" :caption=\"\$block->data->caption\" />\n                                        @break\n                                    @default\n                                        @dump(\$block)\n                                @endswitch\n                            @endforeach\n                        @endif\n                    </div>\n                </div>\n            @endforeach\n        </div>\n    </div>\n</section>\n@endif"
        }
        ```
        **### END OF EXAMPLE ###**

        **### INSTRUCTIONS ###**
        1.  **Discard Old Styling:** Completely ignore the existing CSS classes and structure from the input code.
        2.  **Create a New, Modern UI:** Use standard Tailwind CSS utilities to create a fresh, professional design.
        3.  **Preserve Blade Logic:** You MUST preserve all original Blade logic, variables, and data access (e.g., `\$post->title`, `\$posts->first()->postCategory->name`).
        4.  **Component Structure (IMPORTANT LOGIC):**
            all static content must be replaced by post data ,the final result must equal the rawcode design ,you must use src="{{ asset(\$post->image?->url ?? \$post->getRandomImage()) }}"
            use "rtl:" to support multi direction design
            the new design must be section handling posts must also begin with @props(['posts']) and not @props(['post']) \\n@if(\$posts->isNotEmpty())\\n@php\\n\$post=\$posts->first();\\n@endphp,and has the same post->blocks structure, dont ever use @props(['post']) becouse there is no post in props just posts and all static content must replaced with dynamic content .
            * **IF** your new design contains **more than one** `<section>` tag, then you MUST break the design into multiple component files (e.g., a main section and a repeatable card).
            * **ELSE IF** your new design contains only **one** `<section>` tag, you MUST return the entire new design as a **single component file**, just like in the example above. Do not split it.
        5.  **Output Format:** The final output must be a single, valid JSON object where keys are the filenames and values are the corresponding Blade code. Return **ONLY the raw JSON object**, without any explanations or markdown.
        6.  the result should be in same design and colors of original code
        **### NEW BLADE CODE TO REDESIGN & REFACTOR ###**
        ---
        {$codeChunk}
        ---
        PROMPT;

        try {
            $result = $this->geminiClient
                ->generativeModel(model: $this->model)
                ->generateContent($prompt);
            $jsonResponse = $result->text();

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
                'source_code' => $codeChunk,
                'trace' => Str::limit($e->getTraceAsString(), 1000),
            ]);
            return null;
        }
    }
}