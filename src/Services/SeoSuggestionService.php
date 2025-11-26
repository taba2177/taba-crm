<?php

namespace Taba\Crm\Services;

use Gemini;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Taba\Crm\Models\CrmSetting;

class SeoSuggestionService
{
    /**
     * Extracts a JSON object from a string that might contain other text.
     */
    private function extractJson(string $text): ?string
    {
        // Find the first '{' and the last '}'
        $firstBracket = strpos($text, '{');
        $lastBracket = strrpos($text, '}');

        if ($firstBracket === false || $lastBracket === false) {
            return null;
        }

        return substr($text, $firstBracket, ($lastBracket - $firstBracket + 1));
    }

    /**
     * Generates SEO suggestions based on provided text.
     *
     * @return array{meta_title: string, meta_description: string, keywords: array}|null
     */
    public function suggest(string $title, string $content): ?array
    {
        $textToAnalyze = "Title: {$title}\n\nContent Summary: " . Str::limit($content, 500);

        // A more direct and strict prompt
        $prompt = "Analyze the following text. Generate SEO meta title, description, and keywords.
        RULES:
        1.  Return ONLY a single, minified, valid JSON object.
        2.  The JSON must have three keys: \"meta_title\" (string, max 60 chars), \"meta_description\" (string, max 160 chars), and \"keywords\" (array of 5-7 strings).
        3.  Do not include any explanations, comments, or markdown formatting.

        TEXT TO ANALYZE:
        ---
        {$textToAnalyze}";

        try {
            $client = Gemini::client(CrmSetting::get('crm_gemini_api_key'));
            $result = $client->geminiPro()->generateContent($prompt);
            $rawResponse = $result->text();

            // Use the robust extraction method
            $jsonString = $this->extractJson($rawResponse);

            if (!$jsonString) {
                Log::error('SeoSuggestionService: No JSON object found in AI response.', ['raw_response' => $rawResponse]);
                return null;
            }

            $seoData = json_decode($jsonString, true);

            // Final validation after decoding
            if (json_last_error() !== JSON_ERROR_NONE || empty($seoData['meta_title']) || empty($seoData['meta_description']) || empty($seoData['keywords'])) {
                Log::error('SeoSuggestionService: Decoded JSON is invalid or incomplete.', [
                    'json_string' => $jsonString,
                    'raw_response' => $rawResponse
                ]);
                return null;
            }

            return $seoData;

        } catch (\Throwable $e) {
            Log::error('SeoSuggestionService: Exception during Gemini API call.', ['error' => $e->getMessage()]);
            report($e);
            return null;
        }
    }
}
