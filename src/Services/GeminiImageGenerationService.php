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
    protected string $apiKey;
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
        // --- UPDATED ---
        // Switched to the gemini-2.5-flash-image-preview model which is more accessible.
        // This model uses the 'generateContent' endpoint.
        $this->apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-image-preview:generateContent?key={$this->apiKey}";

        if (empty($this->apiKey)) {
            throw new \Exception('GEMINI_API_KEY environment variable is not set.');
        }
    }

    /**
     * Generates an image based on a prompt and saves it to the Curator media library.
     *
     * @param string $prompt The text prompt for image generation.
     * @param string $originalFilename A name to use for the saved file.
     * @return Media The newly created Curator Media model instance.
     */
    public function generateAndSaveImage(string $prompt, string $originalFilename = 'ai-generated-image'): Media
    {
        Log::info('Gemini Image Generation: Starting...', ['prompt' => $prompt]);

        // --- UPDATED ---
        // The payload structure is different for the generateContent endpoint.
        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'responseModalities' => ['TEXT', 'IMAGE']
            ],
        ];

        $response = Http::timeout(120)->post($this->apiUrl, $payload);

        if (!$response->successful()) {
            Log::error('Gemini Image Generation: API request failed.', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            throw new RequestException($response);
        }

        // --- UPDATED ---
        // The response structure is different. We need to find the part with inlineData.
        $part = collect($response->json('candidates.0.content.parts', []))
            ->firstWhere('inlineData');

        $base64Data = $part['inlineData']['data'] ?? null;


        if (empty($base64Data)) {
            Log::error('Gemini Image Generation: No image data in API response.', ['response' => $response->json()]);
            throw new \Exception('Failed to generate image: No data received from the API.');
        }

        // Decode the base64 string and create a unique filename
        $imageData = base64_decode($base64Data);
        $sanitizedFilename = Str::slug($originalFilename);
        $filename = uniqid($sanitizedFilename . '_') . '.png';
        $disk = config('curator.disk');
        $directory = config('curator.directory');
        $path = "{$directory}/{$filename}";

        // Save the image to the designated storage disk
        Storage::disk($disk)->put($path, $imageData);

        // Get file details
        $size = Storage::disk($disk)->size($path);
        list($width, $height) = getimagesize(Storage::disk($disk)->path($path));

        // Create the Media record for Curator
        $media = Media::create([
            'name' => $sanitizedFilename,
            'path' => $path,
            'disk' => $disk,
            'type' => 'image',
            'ext' => 'png',
            'size' => $size,
            'width' => $width,
            'height' => $height,
        ]);

        Log::info('Gemini Image Generation: Successfully created and saved media.', ['media_id' => $media->id]);

        return $media;
    }
}
