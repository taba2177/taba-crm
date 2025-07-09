<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Content;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ParseAndReplaceBladeFiles extends Command
{
    protected $signature = 'parse:replace-blade';
    protected $description = 'Parse Blade files, store content in the database, and replace static content with variables';

    public function __construct()
    {
        parent::__construct();
    }


//    public function handle(){

//     $bladeFiles = File::allFiles(resource_path('views/editable/'));

//     foreach ($bladeFiles as $bladeFile) {
//         $content = File::get($bladeFile);

//         $pattern = '/<h[1-6][^>]*><a[^>]*>(.*?)<\/a><\/h[1-6]>|<p[^>]*><a[^>]*>(.*?)<\/a><\/p>|<span[^>]*><a[^>]*>(.*?)<\/a><\/span>|<p[^>]*>(.*?)<br\/>(.*?)<\/p>|<p[^>]*>(.*?)<\/p>/si';

//         $content = preg_replace_callback($pattern, function ($matches) {
//             // Find the matched content
//             $tagContent = $matches[0];
//             $innerContent='';
//             // Determine which match group has the content
//             for ($i = 1; $i < count($matches); $i++) {
//                 if (!empty($matches[$i])) {
//                     $innerContent = $matches[$i];
//                     break;
//                 }
//             }

//             // Create a new content model and store it in the database
//             $contentModel = Content::create([
//                 'tag' => $matches[0],
//                 'content' => $innerContent

//             ]);
//             // Replace the inner content with the variable placeholder
//             $newContent = str_replace($innerContent, "{{ \$contents[$contentModel->id] ?? '$innerContent' }}", $tagContent);

//             return $newContent;
//         }, $content);

//         // Save the modified content back to the file
//         File::put($bladeFile, $content);
//     }
//    }
public function handle()
{
    $bladeFiles = File::allFiles(resource_path('views/editable/'));

    foreach ($bladeFiles as $bladeFile) {
        $content = File::get($bladeFile);

        // Regex pattern to match any tag with potential nested content
        $pattern = '/<([^\/>\s]+)([^>]*)>(.*?)<\/\1>/si';

        $content = preg_replace_callback($pattern, function ($matches) use (&$pattern) {
            // Get the content inside the current tag
            $innerContent = $matches[3];

            // If the content contains more tags, process them recursively
            if (preg_match($pattern, $innerContent)) {
                $innerContent = preg_replace_callback($pattern, function ($innerMatches) use (&$pattern) {
                    // Call the function recursively
                    return $this->replaceInnerText($innerMatches, $pattern);
                }, $innerContent);
            } else {
                // If the inner content is plain text and does not start with '<', wrap it in the translation function
                if (strpos($innerContent, '{{') === false && !preg_match('/^\s*<.*>/', $innerContent)) {
                    $innerContent = "$innerContent";
                }
            }

            // Return the tag with the new inner content
            return "<{$matches[1]}{$matches[2]}>{$innerContent}</{$matches[1]}>";
        }, $content);

        // Save the modified content back to the file
        File::put($bladeFile, $content);
    }
}

private function replaceInnerText($matches, $pattern)
{
    $innerContent = $matches[3];

    // Check for further nested tags
    if (preg_match($pattern, $innerContent)) {
        $innerContent = preg_replace_callback($pattern, function ($innerMatches) use (&$pattern) {
            return $this->replaceInnerText($innerMatches, $pattern);
        }, $innerContent);
    } else {
        // If the inner content does not start with '<', wrap it in the translation function
        if (strpos($innerContent, '{{') === false && !preg_match('/^\s*<.*>/', $innerContent)) {
            $innerContent = "$innerContent";
        }
    }

        $this->info('Blade files parsed, content stored, and replaced with variables.');
        return "<{$matches[1]}{$matches[2]}>{$innerContent}</{$matches[1]}>";
    }
}
