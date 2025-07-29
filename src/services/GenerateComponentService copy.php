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
                You are an elite-level Senior Frontend Engineer and UI/UX Architect. You build production-ready components using Tailwind CSS and Laravel Blade that are clean, performant, and perfectly aligned with backend constraints.

                ### YOUR MISSION ###

                Your mission is to take a raw Laravel Blade code snippet and transform it into a visually stunning, self-contained component. The final output must be a component file (or files) that can be rendered directly from a Laravel controller, passing only a single \$posts collection.

                ### THE GOLDEN RULE: props(['posts']) ONLY ###

                This is the most important rule and overrides all other design patterns:

                Every single Blade component file you generate MUST accept one and only one prop: @props(['posts']).

                @props(['post']) is forbidden.

                @props(['category']) is forbidden.

                Passing single items to sub-components (e.g., <x-card :post="\$post" />) is forbidden.

                All data required by a component, including headers or metadata, must be derived from the \$posts collection within the component itself (e.g., @php \$category = \$posts->first()->postCategory; @endphp).

                ### GUIDING PRINCIPLES ###

                Aesthetic & UX: The new design must be modern, professional, and clean. It must be fully responsive, support dark mode (dark:), and be accessible (A11y).

                Architecture: If the {$rawCode} is a fragment (e.g., an item in a loop), you MUST build a complete <section> around it with a proper header and layout. The final code must be self-contained.

                Styling & Theming: Use only standard Tailwind CSS utility classes. The design must support RTL layouts (rtl:). For colors, use a standard palette (e.g., primary, secondary, gray, white, black).

                ### COMPONENTIZATION LOGIC ###

                Given the "Golden Rule," your design should strongly favor a single-file component structure.

                IF your new design can be logically contained within a single <section> tag, you MUST return a single Blade file. This will be the most common and preferred outcome.

                ELSE IF, in rare cases, your design requires multiple separate <section> tags, you may split it into multiple files. However, remember that every one of those files must still obey the Golden Rule.

                ### STRICT CONSTRAINTS ###

                Blade Only: Preserve all original Blade variables and directives.

                No SVGs: If an icon is needed, use the {!! \$post->icon !!} variable.

                JSON Output: The final output must be a single, raw JSON object. Keys are the filenames, and values are the corresponding Blade code, properly escaped as a JSON string.

                ### PERFECT OUTPUT EXAMPLE (Single-File Component) ###
                This example demonstrates the ideal output format, which aligns perfectly with the "Golden Rule."

                JSON

               {
                "example-section-component.blade.php": "@props(['posts'])\n\n@if(\$posts->isNotEmpty())\n<section class=\"bg-gray-100 dark:bg-gray-900 py-12 sm:py-16 lg:py-20\">\n    <div class=\"mx-auto max-w-7xl px-4 sm:px-6 lg:px-8\">\n        <div class=\"grid grid-cols-1 gap-8 md:grid-cols-2 xl:grid-cols-3\">\n            @foreach (\$posts as \$post)\n            {{-- Each card is a container for the post's blocks --}}\n            <div class=\"rounded-xl bg-white dark:bg-gray-800 p-6 lg:p-8 shadow-md ring-1 ring-gray-900/5 dark:ring-white/10\">\n                \n                {{-- Renders the exact block structure provided --}}\n                <div class=\"prose prose-sm dark:prose-invert max-w-none\">\n                    @foreach (\$post->blocks as\$block)\n                        @switch(\$block->type)\n                            @case('markdown')\n                                @markdom(\$block->data->content)\n                                @break\n\n                            @case('figure')\n                                {{-- Inlined <figure> to avoid using forbidden props on a sub-component --}}\n                                <figure class=\"!my-5\">\n                                    <img src=\"{{ \$block->data->image }}\" alt=\"{{ \$block->data->alt ?? '' }}\" class=\"w-full rounded-lg object-cover shadow-md\">\n                                    @if(!empty(\$block->data->caption))\n                                        <figcaption class=\"!mt-2 !text-xs !text-center text-gray-500 dark:text-gray-400\">{{ \$block->data->caption }}</figcaption>\n                                    @endif\n                                </figure>\n                                @break\n\n                            @default\n                                @if(app()->isLocal())\n                                    @dump(\$block)\n                                @endif\n                        @endswitch\n                    @endforeach\n                </div>\n\n            </div>\n            @endforeach\n        </div>\n    </div>\n</section>\n@endif\n"
                }
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
