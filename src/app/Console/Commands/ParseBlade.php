<?php

namespace App\Console\Commands;

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
    protected $signature = 'app:parse-blade';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse Blade templates and generate translation keys';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $bladeFiles = File::allFiles(resource_path('views/editable/'));
        $langFile = 'lang/en.json';
        $translations = [];

        // Load existing translations
        if (File::exists($langFile)) {
            $translations = json_decode(File::get($langFile), true);
        }

        foreach ($bladeFiles as $bladeFile) {
            $content = File::get($bladeFile);

            // Smart regex pattern to find content text
            $pattern = '/>(\s*[^\s<>][^<>]*?)<|(?<!\w)\b([^<>\s]+?)\b(?!\w)/si';

            $content = preg_replace_callback($pattern, function ($matches) use (&$translations) {
                $text = trim($matches[1] ?? $matches[2]);

                // Skip empty or Blade directive content
                if (empty($text) || strpos($text, '{{') !== false) {
                    return $matches[0];
                }

                // Create a short, unique key for the translation
                $key = $this->generateKey($text);

                // Add the translation to the array
                if (!isset($translations[$key])) {
                    $translations[$key] = $text;
                }

                // Replace the original text with the translation directive
                return str_replace($text, "{!! __('$key') !!}", $matches[0]);
            }, $content);

            // Save the modified content back to the file
            File::put($bladeFile, $content);
        }

        // Save the updated translations to the en.json file
        File::put($langFile, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    private function generateKey($text)
    {
        // Split the text into words
        $words = explode(' ', $text);

        // Filter out empty strings and keep the first 2-3 words, ensuring each word is at least 4 characters long
        $filteredWords = array_filter($words, function ($word) {
            return strlen($word) >= 4;
        });

        // Combine the first 2-3 meaningful words into a key
        $key = implode('_', array_slice($filteredWords, 0, 2));

        // Fallback to the first 4 characters if the key is too short
        if (strlen($key) < 4) {
            $key = substr($text, 0, 4);
        }

        return Str::slug($key, '_');
    }
}
