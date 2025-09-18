<?php

namespace Taba\Crm\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Awcodes\Curator\Models\Media;
use Illuminate\Support\Facades\Log;

class ImageSearchService
{
    protected ?string $unsplashApiKey;
    protected string $unsplashApiUrl;

    public function __construct()
    {
        $this->unsplashApiKey = env('UNSPLASH_ACCESS_KEY');
        $this->unsplashApiUrl = "https://api.unsplash.com/search/photos";

        if (empty($this->unsplashApiKey)) {
            Log::warning('UNSPLASH_ACCESS_KEY is not set. Image search will be disabled.');
        }
    }
    /**
     * Searches Unsplash for a collection of images.
     *
     * @param string $searchTerm The text to search for.
     * @param int    $count      The number of images to return.
     * @return array An array of image data ([id, thumb, regular, alt]).
     */
    public function searchUnsplash(string $searchTerm, int $count = 9): array
    {
        if (empty($this->unsplashApiKey)) {
            return [];
        }

        try {
            $response = Http::withHeaders(['Authorization' => "Client-ID {$this->unsplashApiKey}"])
                ->get($this->unsplashApiUrl, [
                    'query' => $searchTerm,
                    'per_page' => $count,
                    'orientation' => 'landscape'
                ]);

            $response->throw();

            $results = data_get($response->json(), 'results', []);

            return collect($results)->map(fn ($result) => [
                'id' => $result['id'],
                'thumb' => $result['urls']['thumb'],
                'regular' => $result['urls']['regular'],
                'alt' => $result['alt_description'],
            ])->all();

        } catch (\Exception $e) {
            Log::error('Unsplash API search failed.', ['error' => $e->getMessage()]);
            return []; // Return empty array on failure
        }
    }

    /**
     * Downloads an image from a URL and saves it to the Curator media library.
     *
     * @param string $imageUrl The URL of the image to download.
     * @param string $filename The base filename for the saved media.
     * @return Media The newly created Curator Media model instance.
     */
    public function saveImageFromUrl(string $imageUrl, string $filename): Media
    {
        try {
            $imageData = Http::get($imageUrl)->body();
            if (empty($imageData)) {
                throw new \Exception("Failed to download image from URL: {$imageUrl}");
            }
            return $this->saveImageDataToCurator($imageData, $filename, 'Unsplash Search');
        } catch (\Exception $e) {
            Log::error('Failed to download or save image from URL.', ['error' => $e->getMessage()]);
            // As a final fallback, generate a placeholder
            $imageData = $this->generatePlaceholderImage($filename);
            return $this->saveImageDataToCurator($imageData, $filename, 'Local Placeholder');
        }
    }

    /**
     * Generates a local placeholder image with text. This is the ultimate fallback.
     * Requires the GD PHP extension.
     */
    private function generatePlaceholderImage(string $text): ?string
    {
        if (!extension_loaded('gd')) {
            Log::error('Image Generation: GD extension is not loaded, cannot generate placeholder.');
            return null;
        }

        $width = 1200;
        $height = 675;
        $image = imagecreatetruecolor($width, $height);
        $bgColor = imagecolorallocate($image, 30, 41, 59);
        $textColor = imagecolorallocate($image, 226, 232, 240);
        imagefill($image, 0, 0, $bgColor);
        $fontSize = 5;
        $wrappedText = wordwrap($text, 30, "\n");
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
            'alt' => "{$filename} - Found via {$source}"
        ]);

        Log::info('Image Search: Successfully saved media.', [
            'media_id' => $media->id,
            'source' => $source
        ]);

        return $media;
    }
}
