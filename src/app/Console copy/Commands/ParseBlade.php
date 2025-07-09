<?php

namespace App\Console\Commands;

use DOMAttr;
use DOMDocument;
use DOMXPath;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ParseBlade extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:parse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse Blade templates and generate translation keys grouped by HTML tags';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $bladeFiles = File::allFiles(resource_path('views/tobe'));

        foreach ($bladeFiles as $bladeFile) {
            $fileName = explode('.', $bladeFile->getFilename())[0];
            $langFile = resource_path("lang/en/{$fileName}.php");
            $translations = [];

            // Load existing translations if they exist
            if (File::exists($langFile)) {
                $translations = include $langFile;
            }

            $content = File::get($bladeFile);
            $updatedContent = $this->processBladeContent($content, $fileName, $translations);

            // Save the updated Blade content back to the file
            File::put($bladeFile, $updatedContent);

            // Save the updated translations
            $this->saveTranslations($translations, $langFile);
        }

        $this->info('Blade templates parsed and translations generated successfully.');
    }

    /**
     * Process the content of a Blade file, extracting translatable strings grouped by HTML tags.
     *
     * @param string $content
     * @param string $fileName
     * @param array $translations
     * @return string
     */
    private function processBladeContent(string $content, string $fileName, array &$translations): string
    {
        // Regex to match <script> or <style> blocks (to skip them)
        $scriptPattern = '/<(script|style)\b[^>]*>(.*?)<\/\1>/is';
        $scriptBlocks = [];
        preg_match_all($scriptPattern, $content, $scriptBlocks);

        // Split content into non-script parts
        $nonScriptContent = preg_split($scriptPattern, $content);

        $processedContent = [];
        foreach ($nonScriptContent as $index => $htmlPart) {
            $processedContent[] = $this->extractTranslations($htmlPart, $fileName, $translations);

            // Add untouched script blocks back
            if (isset($scriptBlocks[0][$index])) {
                $processedContent[] = $scriptBlocks[0][$index];
            }
        }

        return implode('', $processedContent);
    }

    /**
     * Extract translatable strings from HTML content grouped by tag names.
     *
     * @param string $html
     * @param string $fileName
     * @param array $translations
     * @return string
     */
    private function extractTranslations(string $html, string $fileName, array &$translations): string
    {
        $doc = new DOMDocument('1.0', 'UTF-8');
        @$doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $xpath = new DOMXPath($doc);
        $textNodes = $xpath->query("//text()[normalize-space() and not(ancestor::script) and not(ancestor::style) and not(ancestor::meta)]");

        // Track how many times each text has been seen
        $textCounters = [];

        foreach ($textNodes as $node) {
            $parentNode = $node->parentNode;
            $tagName = $parentNode->nodeName;
            $text = trim($node->nodeValue);

            // Skip empty or Blade syntax
            if ($this->shouldSkipText($text)) {
                continue;
            }

            // Initialize the counter for this text
            if (!isset($textCounters[$text])) {
                $textCounters[$text] = 0;
            }

            // Increment the counter for this text
            $textCounters[$text]++;

            // Generate a unique translation key
            $key = $this->generateKey($text, $tagName);
            if ($textCounters[$text] > 1) {
                $key .= $textCounters[$text]; // Append counter for duplicates
            }

            // Initialize the tag group if not exists
            if (!isset($translations[$tagName])) {
                $translations[$tagName] = [];
            }

            // Add to translations if not already present
            if (!isset($translations[$tagName][$key])) {
                $translations[$tagName][$key] = $text;
            }

            // Replace the text with a translation directive
            $node->nodeValue = "{!! __('{$fileName}.{$tagName}.{$key}') !!}";
        }

        // Process translatable attributes
        $attributesToTranslate = ['title', 'placeholder', 'alt', 'content', 'onclick'];
        foreach ($attributesToTranslate as $attr) {
            $attributeNodes = $xpath->query("//@{$attr}[normalize-space()]");
            foreach ($attributeNodes as $attrNode) {
                $this->processAttributeNode($attrNode, $fileName, $translations, $textCounters);
            }
        }

        // Save HTML without <html> and <body> tags
        $output = '';
        foreach ($doc->childNodes as $node) {
            $output .= $doc->saveHTML($node);
        }

        return $output;
    }

    /**
     * Process attribute nodes for translation.
     *
     * @param DOMAttr $attrNode
     * @param string $fileName
     * @param array $translations
     * @param array $textCounters
     */
    private function processAttributeNode(DOMAttr $attrNode, string $fileName, array &$translations, array &$textCounters)
    {
        $element = $attrNode->ownerElement;
        $tagName = $element->nodeName;
        $attrName = $attrNode->name;
        $text = trim($attrNode->value);

        if ($this->shouldSkipText($text)) {
            return;
        }

        // Initialize the counter for this text
        if (!isset($textCounters[$text])) {
            $textCounters[$text] = 0;
        }

        // Increment the counter for this text
        $textCounters[$text]++;

        // Generate a unique translation key
        $key = $this->generateKey($text, "{$tagName}_{$attrName}");
        if ($textCounters[$text] > 1) {
            $key .= $textCounters[$text]; // Append counter for duplicates
        }

        // Initialize the tag group if not exists
        if (!isset($translations["{$tagName}_{$attrName}"])) {
            $translations["{$tagName}_{$attrName}"] = [];
        }

        // Add to translations if not already present
        if (!isset($translations["{$tagName}_{$attrName}"][$key])) {
            $translations["{$tagName}_{$attrName}"][$key] = $text;
        }

        // Replace the attribute value with a translation directive
        $attrNode->value = "{{ __('{$fileName}.{$tagName}_{$attrName}.{$key}') }}";
    }

    /**
     * Check if the text should be skipped.
     *
     * @param string $text
     * @return bool
     */
    private function shouldSkipText(string $text): bool
    {
        return empty($text) ||
            strpos($text, '{') !== false ||
            strpos($text, '->') !== false ||
            strpos($text, '}') !== false ||
            strpos($text, ')') !== false ||
            strpos($text, '@') !== false ||
            $this->matchesBladePattern($text);
    }

    /**
     * Check if the text matches Blade syntax patterns.
     *
     * @param string $text
     * @return bool
     */
    private function matchesBladePattern(string $text): bool
    {
        $patterns = [
            '/\{\{.*?\}\}/',           // Matches {{ ... }}
            '/\{!!.*?!!\}/',           // Matches {!! ... !!}
            '/^\@/',                   // Matches @php directives
            '/^\@if|^\@foreach|^\@/',  // Matches other Blade @ directives
            '/\{\{\-.*?\-\}\}/',       // Matches Blade comments {{-- ... --}}
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Generate a unique key for a translation string.
     *
     * @param string $text
     * @param string $context
     * @return string
     */
    private function generateKey(string $text, string $context = ''): string
    {
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        $words = explode(' ', $text);
        $filteredWords = array_filter($words, fn($word) => strlen($word) >= 4);
        $key = implode('_', array_slice($filteredWords, 0, 3));
        $key = Str::slug($key ?: substr($text, 0, 6), '_');
        return $context ? "{$context}_{$key}" : $key;
    }

    /**
     * Save translations to a PHP file.
     *
     * @param array $translations
     * @param string $filePath
     */
    private function saveTranslations(array $translations, string $filePath): void
    {
        $content = "<?php\n\nreturn [\n";

        foreach ($translations as $tag => $keys) {
            $content .= "    '$tag' => [\n";
            foreach ($keys as $key => $value) {
                $content .= "        '$key' => '" . addslashes($value) . "',\n";
            }
            $content .= "    ],\n";
        }

        $content .= "];\n";

        File::put($filePath, $content);
    }
}