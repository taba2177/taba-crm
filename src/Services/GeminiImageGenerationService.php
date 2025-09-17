<?php

namespace Taba\Crm\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Awcodes\Curator\Models\Media; // Use the Curator Media model
use Illuminate\Support\Facades\Log;

class ImageSearchService // Renamed for clarity
{
    protected ?string $unsplashApiKey;
    protected string $unsplashApiUrl;

    public function __construct()
    {
        // This service now only depends on the Unsplash API key
        $this->unsplashApiKey = env('UNSPLASH_ACCESS_KEY');
        $this->unsplashApiUrl = "https://api.unsplash.com/search/photos";

        if (empty($this->unsplashApiKey)) {
            // Log a warning instead of throwing an error, allowing the placeholder to function
            Log::warning('UNSPLASH_ACCESS_KEY is not set. The service will fall back to local placeholders.');
        }
    }

    /**
     * Finds a free image using a robust two-step approach: Unsplash Search -> Local Placeholder Fallback.
     *
     * @param string $searchTerm The text prompt to search for.
     * @param string $originalFilename A name to use for the saved file.
     * @return Media The newly created Curator Media model instance.
     */
    public function findAndSaveImage(string $searchTerm, string $originalFilename = 'searched-image'): Media
    {
        $imageData = null;
        $source = 'unknown';

        // --- Attempt 1: Stock Photo Search (Unsplash) ---
        if (!empty($this->unsplashApiKey)) {
            try {
                Log::info('Image Search: Attempting with stock photo search (Unsplash)...');
                $imageData = $this->getImageFromUnsplash($searchTerm);
                if ($imageData) {
                    $source = 'Unsplash Search';
                }
            } catch (\Exception $e) {
                Log::warning('Image Search: Unsplash search failed. Falling back to local placeholder.', ['error' => $e->getMessage()]);
            }
        }

        // --- Attempt 2: Local Placeholder Generation (Guaranteed Fallback) ---
        if (!$imageData) {
            try {
                Log::info('Image Search: Generating local placeholder as a fallback...');
                $imageData = $this->generatePlaceholderImage($originalFilename);
                if ($imageData) {
                    $source = 'Local Placeholder';
                }
            } catch (\Exception $e) {
                Log::error('Image Search: All services, including local placeholder, failed.', ['error' => $e->getMessage()]);
                throw new \Exception('All image search and generation services failed.');
            }
        }

        if (empty($imageData)) {
             throw new \Exception('Failed to retrieve image data from any available service.');
        }

        return $this->saveImageDataToCurator($imageData, $originalFilename, $source);
    }

    /**
     * Fetches an image from Unsplash.
     * @return string|null Raw image binary data on success.
     */
    private function getImageFromUnsplash(string $prompt): ?string
    {
        $response = Http::withHeaders(['Authorization' => "Client-ID {$this->unsplashApiKey}"])
            ->get($this->unsplashApiUrl, ['query' => $prompt, 'per_page' => 1, 'orientation' => 'landscape']);

        $response->throw();
        $imageUrl = data_get($response->json(), 'results.0.urls.regular');

        if ($imageUrl) {
            $imageResponse = Http::get($imageUrl);
            $imageResponse->throw();
            return $imageResponse->body();
        }
        return null;
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
     * Saves raw image data to storage and creates a Curator Media record.
     */
    private function saveImageDataToCurator(string $imageData, string $filename, string $source): Media
    {
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
            'alt' => "{$filename} - Found via {$source}" // Updated alt text
        ]);

        Log::info('Image Search: Successfully saved media.', [
            'media_id' => $media->id,
            'source' => $source
        ]);

        return $media;
    }
}
