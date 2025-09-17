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
    protected string $imagenApiUrl;

    public function __construct()
    {
        $this->geminiApiKey = env('GEMINI_API_KEY_IMAGEN');

        if (empty($this->geminiApiKey)) {
            throw new \Exception('GEMINI_API_KEY environment variable is not set.');
        }

        // We will focus on the highest-quality model as the primary attempt.
        $this->imagenApiUrl = "https://generativelanguage.googleapis.com/v1beta/models/imagen-3.0-generate-002:predict?key={$this->geminiApiKey}";
    }

    /**
     * Generates an image using a robust two-step approach: Primary AI -> Local Placeholder Fallback.
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
            if ($imageData) {
                $source = 'Imagen 3 AI';
            }
        } catch (\Exception $e) {
            Log::warning('Image Generation: Primary model failed. Falling back to local placeholder.', ['error' => $e->getMessage()]);
        }

        // --- Attempt 2: Local Placeholder Generation (Guaranteed Fallback) ---
        if (!$imageData) {
            try {
                Log::info('Image Generation: Generating local placeholder as a fallback...');
                $imageData = $this->generatePlaceholderImage($originalFilename);
                if ($imageData) {
                    $source = 'Local Placeholder';
                }
            } catch (\Exception $e) {
                Log::error('Image Generation: All services, including local placeholder, failed.', ['error' => $e->getMessage()]);
                // This will now only be thrown in a critical failure of the local generation.
                throw new \Exception('All image generation services failed, including the local placeholder fallback.');
            }
        }

        if (empty($imageData)) {
             throw new \Exception('Failed to generate or retrieve image data from any available service.');
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
        $response = Http::timeout(120)->post($this->imagenApiUrl, $payload);

        // This will throw an exception for 4xx or 5xx errors, triggering the catch block.
        $response->throw();

        return data_get($response->json(), 'predictions.0.bytesBase64Encoded');
    }

    /**
     * Generates a local placeholder image with text. This is the ultimate fallback.
     * Requires the GD PHP extension.
     *
     * @param string $text The text to write on the image.
     * @return string|null Raw PNG image data.
     */
    private function generatePlaceholderImage(string $text): ?string
    {
        if (!extension_loaded('gd')) {
            Log::error('Image Generation: GD extension is not loaded, cannot generate placeholder.');
            return null;
        }

        $width = 1200;
        $height = 675; // 16:9 aspect ratio
        $image = imagecreatetruecolor($width, $height);

        // Colors
        $bgColor = imagecolorallocate($image, 30, 41, 59); // Slate 800
        $textColor = imagecolorallocate($image, 226, 232, 240); // Slate 200

        imagefill($image, 0, 0, $bgColor);

        // Use the built-in GD font. This removes the need for an external .ttf file.
        $fontSize = 5; // GD fonts are numbered 1-5

        // Wrap text if it's too long
        $wrappedText = wordwrap($text, 30, "\n");

        // Calculate position to center the text block
        $lines = explode("\n", $wrappedText);
        $lineHeight = imagefontheight($fontSize) + 5;
        $totalHeight = count($lines) * $lineHeight;
        $startY = ($height - $totalHeight) / 2;

        foreach ($lines as $index => $line) {
            $textWidth = imagefontwidth($fontSize) * strlen($line);
            $x = ($width - $textWidth) / 2;
            $y = $startY + ($index * $lineHeight);
            imagestring($image, $fontSize, $x, $y, $line, $textColor);
        }

        // Capture the image output to a variable
        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);

        return $imageData;
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
