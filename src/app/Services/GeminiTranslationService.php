<?php

namespace App\Services;

use Gemini\Enums\ModelVariation;
use Gemini\GeminiHelper;
use Gemini; // Use the Laravel facade for easier client access
use Illuminate\Support\Facades\Log; // For logging errors

class GeminiTranslationService
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
     * Translates text from a source language to a target language using Gemini.
     *
     * @param string $text The text to translate.
     * @param string $sourceLang The source language code (e.g., 'ar', 'en').
     * @param string $targetLang The target language code (e.g., 'en', 'ar').
     * @return string|null The translated text, or null on failure.
     */
    public function translate(string $text, string $sourceLang, string $targetLang): ?string
    {
        if (empty(trim($text))) {
            return null; // No text to translate
        }

        try {
            // Construct a clear prompt for the AI to ensure clean output
            $prompt = sprintf(
                "Translate the following %s text to %s. Provide only the translated text, without any additional comments, explanations, or formatting (like bullet points or quotation marks, unless they are part of the original text):\n\n\"%s\"",
                $this->getLanguageName($sourceLang),
                $this->getLanguageName($targetLang),
                $text
            );

            $result = $this->geminiClient
                ->generativeModel(model: $this->model)
                ->generateContent($prompt);

            // Access the text from the result
            $translatedText = $result->text();

            // Basic post-processing: Gemini might sometimes add unwanted prefixes/suffixes.
            // This is a simple trim, more complex cleaning might be needed based on Gemini's output patterns.
            $translatedText = trim($translatedText, " \t\n\r\0\x0B\"'");

            return $translatedText;
        } catch (\Throwable $e) {
            Log::error("Gemini Translation Error: " . $e->getMessage(), [
                'source_text' => $text,
                'source_lang' => $sourceLang,
                'target_lang' => $targetLang,
                'model' => $this->model,
                'trace' => $e->getTraceAsString(),
            ]);
            return null; // Indicate translation failure
        }
    }

    /**
     * Translates a batch of texts from a source language to a target language.
     *
     * @param array $texts An array of strings to translate.
     * @param string $sourceLang The source language code.
     * @param string $targetLang The target language code.
     * @return array An array of translated texts, with original texts as keys.
     */
    public function translateMany(array $texts, string $sourceLang, string $targetLang): array
    {
        if (empty($texts)) {
            return [];
        }

        try {
            $prompt = "Translate the following JSON object from {$this->getLanguageName($sourceLang)} to {$this->getLanguageName($targetLang)}. "
                    . "The JSON object contains key-value pairs. Translate the value for each key. "
                    . "Return ONLY a valid JSON object with the same keys and the translated values. "
                    . "Do not include any extra text, explanations, or markdown formatting.\n\n"
                    . json_encode($texts, JSON_UNESCAPED_UNICODE);

            Log::info('Gemini Translation: Sending batch prompt.', ['prompt' => $prompt]);

            $result = $this->geminiClient
                ->generativeModel(model: $this->model)
                ->generateContent($prompt);

            $responseJson = $result->text();
            Log::info('Gemini Translation: Raw batch response.', ['response' => $responseJson]);

            // Clean the response to ensure it's a valid JSON string
            $responseJson = trim($responseJson, " \t\n\r\0\x0B\`");
            if (Str::startsWith($responseJson, 'json')) {
                $responseJson = Str::after($responseJson, 'json');
            }

            $translatedTexts = json_decode($responseJson, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Gemini Translation: Failed to decode JSON response.', ['response' => $responseJson, 'error' => json_last_error_msg()]);
                return array_fill_keys(array_keys($texts), null); // Return nulls on failure
            }

            Log::info('Gemini Translation: Decoded batch response.', ['translated_texts' => $translatedTexts]);
            return $translatedTexts;

        } catch (\Throwable $e) {
            Log::error("Gemini Translation Error (translateMany): " . $e->getMessage(), [
                'source_texts' => $texts,
                'source_lang' => $sourceLang,
                'target_lang' => $targetLang,
                'model' => $this->model,
            ]);
            return array_fill_keys(array_keys($texts), null);
        }
    }

    /**
     * Helper to get a human-readable language name for the prompt.
     *
     * @param string $langCode
     * @return string
     */
    protected function getLanguageName(string $langCode): string
    {
        return match ($langCode) {
            'ar' => 'Arabic',
            'en' => 'English',
            // Add more languages as needed
            default => $langCode,
        };
    }
}