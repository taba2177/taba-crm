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
        $apiKey = config('crm.gemini_api_key');
        if (empty($apiKey)) {
            throw new \Exception('GEMINI_API_KEY is not configured. Please set it in config/crm.php or .env file.');
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
            "why-choose-us.blade.php": "@props(['posts'])\n\n@if(\$posts->isNotEmpty())\n<section class=\"py-20 bg-gradient-to-r from-slate-900/50 to-purple-900/20 rtl:text-right\">\n    <div class=\"container mx-auto px-4 rtl:px-4\">\n        <div class=\"text-center mb-16 rtl:text-right\">\n            <h2 class=\"text-4xl md:text-5xl font-bold mb-6\"><span class=\"gradient-text\">{{ \$posts->first()->postCategory->name }}</span></h2>\n            <p class=\"text-xl text-gray-300 max-w-2xl mx-auto rtl:mx-auto\">{{ \$posts->first()->postCategory->description }}</p>\n        </div>\n        <div class=\"grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 rtl:gap-8\">\n            @foreach(\$posts as \$post)\n                <div class=\"text-center p-6 rounded-2xl glass-effect hover:scale-105 transition-transform duration-300 rtl:hover:-translate-x-2\">\n                    <div class=\"text-4xl mb-4\">\n                        @if(!empty(\$post->icon))\n                            <i class=\"{{ \$post->icon }}\"></i>\n                        @else\n                            <img src=\"{{ asset(\$post->image?->url ?? \$post->getRandomImage()) }}\" alt=\"{{ \$post->title ?? '' }}\" class=\"w-10 h-10 mx-auto\">\n                        @endif\n                    </div>\n                    <h3 class=\"text-xl font-bold text-white mb-3\">{{ \$post->title ?? '' }}</h3>\n                    <div class=\"prose dark:prose-invert text-gray-300\">\n                        @if(!empty(\$post->excerpt))\n                            {{ \$post->excerpt }}\n                        @else\n                            @foreach (\$post->blocks as \$block)\n                                @switch(\$block->type)\n                                    @case('markdown')\n                                        @markdom(\$block->data->content)\n                                        @break\n                                    @case('figure')\n                                        <x-figure :image=\"\$block->data->image\" :alt=\"\$block->data->alt\" :caption=\"\$block->data->caption\" />\n                                        @break\n                                    @default\n                                        @dump(\$block)\n                                @endswitch\n                            @endforeach\n                        @endif\n                    </div>\n                </div>\n            @endforeach\n        </div>\n    </div>\n</section>\n@endif"
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
        6.  dont change the colors and the design must be equal to the rawcode design

        **### NEW BLADE CODE TO REDESIGN & REFACTOR ###**
        ---
        {$codeChunk}
        ---
        PROMPT;

        $prompt2 = <<<PROMPT
            You are a senior frontend developer and UI/UX designer with expertise in creating modern, clean

            ```json
            {
                "why-choose-us.blade.php": "@props(['posts'])\n\n@if(\$posts->isNotEmpty())\n<section class=\"py-20 bg-gradient-to-r from-slate-900/50 to-purple-900/20 rtl:text-right\">\n    <div class=\"container mx-auto px-4 rtl:px-4\">\n        <div class=\"text-center mb-16 rtl:text-right\">\n            <h2 class=\"text-4xl md:text-5xl font-bold mb-6\"><span class=\"gradient-text\">{{ \$posts->first()->postCategory->name }}</span></h2>\n            <p class=\"text-xl text-gray-300 max-w-2xl mx-auto rtl:mx-auto\">{{ \$posts->first()->postCategory->description }}</p>\n        </div>\n        <div class=\"grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 rtl:gap-8\">\n            @foreach(\$posts as \$post)\n                <div class=\"text-center p-6 rounded-2xl glass-effect hover:scale-105 transition-transform duration-300 rtl:hover:-translate-x-2\">\n                    <div class=\"text-4xl mb-4\">\n                        @if(!empty(\$post->icon))\n                            <i class=\"{{ \$post->icon }}\"></i>\n                        @else\n                            <img src=\"{{ asset(\$post->image?->url ?? \$post->getRandomImage()) }}\" alt=\"{{ \$post->title ?? '' }}\" class=\"w-10 h-10 mx-auto\">\n                        @endif\n                    </div>\n                    <h3 class=\"text-xl font-bold text-white mb-3\">{{ \$post->title ?? '' }}</h3>\n                    <div class=\"prose dark:prose-invert text-gray-300\">\n                        @if(!empty(\$post->excerpt))\n                            {{ \$post->excerpt }}\n                        @else\n                            @foreach (\$post->blocks as \$block)\n                                @switch(\$block->type)\n                                    @case('markdown')\n                                        @markdom(\$block->data->content)\n                                        @break\n                                    @case('figure')\n                                        <x-figure :image=\"\$block->data->image\" :alt=\"\$block->data->alt\" :caption=\"\$block->data->caption\" />\n                                        @break\n                                    @default\n                                        @dump(\$block)\n                                @endswitch\n                            @endforeach\n                        @endif\n                    </div>\n                </div>\n            @endforeach\n        </div>\n    </div>\n</section>\n@endif"
            }
            ```
            @props(['posts'])
            @if(!empty(\$posts))
            <section id="services"
                class="overflow-x-hidden bg-primary-color relative after:absolute after:top-10 after:left-5 after:w-650px after:h-550px after:blur-[150px] after:rounded-50% after:bg-gradient-circle-2 after:-z-1 after:-translate-x-1/2 after:opacity-60">
                <div class="py-60px md:py-20 lg:py-30 overflow-x-hidden">
                    <div class="container">
                        <!-- section heading -->
                        <div class="max-w-560px">
                            <div class="mb-25px">
                                <span class="text-xs uppercase text-primary-color font-medium relative inline-block wow fadeInUp"
                                    data-wow-delay=".3s">{{ \$posts->first()->postCategory->name}}</span>
                            </div>
                            <h2 class="text-3xl md:text-size-35 lg:text-size-40 xl:text-size-45 font-semibold leading-1.2 -tracking-0.02em inline-block text-seondary-color dark:text-white-color wow fadeInUp"
                                data-wow-delay=".4s">
                                {{ \$posts->first()->postCategory->description }}
                            </h2>
                        </div>
                        <!-- services grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach(\$posts as \$index => \$post)
                            @php
                            \$delays = [0.3, 0.5, 0.7, 0.9];
                            \$delay = \$delays[\$index % 4];
                            @endphp
                            <div class="rounded-25px relative overflow-hidden p-30px bg-cream-light-color dark:bg-bg-color-6 backdrop-blur-[40px] group transition-all duration-500 before:absolute before:left-0 before:top-0 before:rounded-25px before:w-0 before:h-0 before:transition-all before:duration-500 hover:before:w-full hover:before:h-full before:border-t before:border-l before:border-primary-color before:opacity-0 before:invisible hover:before:opacity-100 hover:before:visible after:absolute after:right-0 after:bottom-0 after:rounded-25px after:w-0 after:h-0 after:transition-all after:duration-500 hover:after:w-full hover:after:h-full after:border-b after:border-r after:border-primary-color after:opacity-0 after:invisible hover:after:opacity-100 hover:after:visible wow fadeInUp"
                                data-wow-delay="{{ \$delay }}s">
                                <div class="mb-35px md:mb-75px">
                                    <span
                                        class="w-16 h-16 bg-primary-color rounded-20px inline-flex justify-center items-center leading-1">
                                        @if(!empty(\$post->icon))
                                        <i
                                            class="{{ \$post->icon }} text-size-34 text-white-color leading-1 inline-flex transition-all duration-500 group-hover:scale-x-[-1]"></i>
                                        @else
                                        <img src="{{ asset(\$post->image?->url ?? \$post->getRandomImage()) }}"
                                            alt="{{ \$post->title ?? '' }}" class="w-8 h-8 object-contain" />
                                        @endif
                                    </span>
                                </div>
                                <div>
                                    <h3
                                        class="text-xl md:text-size-22 lg:text-2xl xl:text-size-22 2xl:text-2xl mb-15px leading-1.2 font-semibold text-seondary-color dark:text-white-color">
                                        {{ \$post->title ?? '' }}
                                    </h3>
                                    <p class="text-primary-color-light dark:text-body-color mb-0 text-size-15">
                                        @if(!empty(\$post->excerpt))
                                        {{ \$post->excerpt }}
                                        @else
                                        <span
                                            class="prose dark:prose-invert max-w-none text-primary-color-light dark:text-white-color mb-2">
                                            @foreach (\$post->blocks as \$block)
                                            @switch(\$block->type)
                                            @case('markdown')
                                            @markdom(\$block->data->content)
                                            @break
                                            @case('figure')
                                            <x-figure :image="\$block->data->image" :alt="\$block->data->alt"
                                                :caption="\$block->data->caption" />
                                            @break
                                            @default
                                            @dump(\$block)
                                            @endswitch
                                            @endforeach
                                        </span>
                                        @endif
                                    </p>
                                </div>
                                <a class="absolute top-0 left-0 w-full h-full z-1" href="#"></a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
            @endif

        just convert the design the design to be exactly like
        1.  **Output Format:** The final output must be a single, valid JSON object where keys are the filenames and values are the corresponding Blade code. Return **ONLY the raw JSON object**, without any explanations or markdown.

        ---
        {$codeChunk}
        ---
        PROMPT;


        try {
            $result = $this->geminiClient
                ->generativeModel(model: $this->model)
                ->generateContent($prompt2);
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
