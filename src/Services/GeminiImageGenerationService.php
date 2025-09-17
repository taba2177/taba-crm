<?php

namespace Taba\Crm\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Awcodes\Curator\Models\Media; // Use the Curator Media model
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;

class GeminiImageGenerationService
{
    protected ?string $geminiApiKey;
    protected ?string $unsplashApiKey;

    // Define API endpoints for different models
    protected string $imagenApiUrl;
    protected string $geminiFlashApiUrl;
    protected string $unsplashApiUrl;


    public function __construct()
    {
        $this->geminiApiKey = env('GEMINI_API_KEY');
        $this->unsplashApiKey = env('UNSPLASH_ACCESS_KEY');

        if (empty($this->geminiApiKey)) {
            throw new \Exception('GEMINI_API_KEY environment variable is not set.');
        }

        // Define endpoints for both primary and secondary AI models
        $this->imagenApiUrl = "https://generativelanguage.googleapis.com/v1beta/models/imagen-3.0-generate-002:predict?key={$this->geminiApiKey}";
        $this->geminiFlashApiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-image-preview:generateContent?key={$this->geminiApiKey}";
        $this->unsplashApiUrl = "https://api.unsplash.com/search/photos";
    }

    /**
     * Generates an image using a waterfall approach: Imagen -> Gemini Flash -> Unsplash.
     *
     * @param string $prompt The text prompt for image generation.
     * @param string $originalFilename A name to use for the saved file.
     * @return Media The newly created Curator Media model instance.
     */
    public function generateAndSaveImage(string $prompt, string $originalFilename = 'ai-generated-image'): Media
    {
        $imageData = null;
        $source = 'unknown';

        // --- Attempt 1: Primary AI Model (Imagen 3) ---
        try {
            Log::info('Image Generation: Attempting with primary model (Imagen 3)...');
            $imageData = $this->generateWithImagen($prompt);
            $source = 'Imagen 3 AI';
        } catch (\Exception $e) {
            Log::warning('Image Generation: Primary model failed. Trying secondary...', ['error' => $e->getMessage()]);
        }

        // --- Attempt 2: Secondary AI Model (Gemini Flash) ---
        if (!$imageData) {
            try {
                Log::info('Image Generation: Attempting with secondary model (Gemini Flash)...');
                $imageData = $this->generateWithGeminiFlash($prompt);
                $source = 'Gemini Flash AI';
            } catch (\Exception $e) {
                Log::warning('Image Generation: Secondary model failed. Trying stock photo fallback...', ['error' => $e->getMessage()]);
            }
        }

        // --- Attempt 3: Stock Photo Fallback (Unsplash) ---
        if (!$imageData && !empty($this->unsplashApiKey)) {
            try {
                Log::info('Image Generation: Attempting with stock photo fallback (Unsplash)...');
                $imageData = $this->getImageFromUnsplash($prompt);
                $source = 'Unsplash Fallback';
            } catch (\Exception $e) {
                Log::error('Image Generation: All services failed.', ['error' => $e->getMessage()]);
                throw new \Exception('All image generation services failed. Please check logs and API keys.');
            }
        }

        if (empty($imageData)) {
             throw new \Exception('Failed to generate image from any available service.');
        }

        return $this->saveImageDataToCurator($imageData, $originalFilename, $source);
    }

    /**
     * Calls the Imagen 3 API.
     * @return string|null Base64 image data on success.
     */
    private function generateWithImagen(string $prompt): ?string
    {
        $payload = ['instances' => [['prompt' => $prompt]], 'parameters' => ['sampleCount' => 1]];
        $response = Http::timeout(120)->post($this->imagenApiUrl, $payload)->json();
        return data_get($response, 'predictions.0.bytesBase64Encoded');
    }

    /**
     * Calls the Gemini Flash Image Preview API.
     * @return string|null Base64 image data on success.
     */
    private function generateWithGeminiFlash(string $prompt): ?string
    {
        $payload = [
            'contents' => [['parts' => [['text' => $prompt]]]],
            'generationConfig' => ['responseModalities' => ['TEXT', 'IMAGE']],
        ];
        $response = Http::timeout(120)->post($this->geminiFlashApiUrl, $payload)->json();
        $part = collect(data_get($response, 'candidates.0.content.parts', []))->firstWhere('inlineData');
        return $part['inlineData']['data'] ?? null;
    }

    /**
     * Fetches an image from Unsplash as a fallback.
     * @return string|null Raw image binary data on success.
     */
    private function getImageFromUnsplash(string $prompt): ?string
    {
        $response = Http::withHeaders(['Authorization' => "Client-ID {$this->unsplashApiKey}"])
            ->get($this->unsplashApiUrl, ['query' => $prompt, 'per_page' => 1, 'orientation' => 'landscape']);

        $imageUrl = data_get($response->json(), 'results.0.urls.regular');

        if ($imageUrl) {
            // Download the image data directly
            return Http::get($imageUrl)->body();
        }
        return null;
    }

    /**
     * Saves raw or base64 image data to storage and creates a Curator Media record.
     */
    private function saveImageDataToCurator(string $imageData, string $filename, string $source): Media
    {
        // Check if data is base64 and decode it if so
        if (base64_encode(base64_decode($imageData, true)) === $imageData){
            $imageData = base64_decode($imageData);
        }

        $sanitizedFilename = Str::slug($filename);
        $newFilename = uniqid($sanitizedFilename . '_') . '.png';
        $disk = config('curator.disk');
        $directory = config('curator.directory');
        $path = "{$directory}/{$newFilename}";

        Storage::disk($disk)->put($path, $imageData);

        $size = Storage::disk($disk)->size($path);
        list($width, $height) = getimagesize(Storage::disk($disk)->path($path));

        $media = Media::create([
            'name' => $sanitizedFilename,
            'path' => $path,
            'disk' => $disk,
            'type' => 'image',
            'ext' => 'png',
            'size' => $size,
            'width' => $width,
            'height' => $height,
            'alt' => "{$filename} - Generated by {$source}" // Add source info to alt text
        ]);

        Log::info('Image Generation: Successfully created and saved media.', [
            'media_id' => $media->id,
            'source' => $source
        ]);

        return $media;
    }
}
