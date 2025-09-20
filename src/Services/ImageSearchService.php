<?php

namespace Taba\Crm\Services;

use Awcodes\Curator\Models\Media;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Taba\Crm\Jobs\SaveUnsplashImageJob; // We will create this job next

class ImageSearchService
{
    protected ?string $unsplashApiKey;
    protected string $unsplashApiUrl;
    protected GeminiTranslationService $geminiService; // Dependency injection

    /**
     * Inject the Gemini service for AI capabilities.
     */
    public function __construct(GeminiTranslationService $geminiService)
    {
        $this->unsplashApiKey = env('UNSPLASH_ACCESS_KEY');
        $this->unsplashApiUrl = "https://api.unsplash.com/search/photos";
        $this->geminiService = $geminiService;

        if (empty($this->unsplashApiKey)) {
            Log::warning('UNSPLASH_ACCESS_KEY is not set. Unsplash search will be disabled.');
        }
    }

    /**
     * Finds a collection of images from Unsplash based on an initial prompt.
     *
     * @param string $basePrompt The initial text prompt (e.g., post title).
     * @param int $count The number of images to find.
     * @return Collection A collection of structured image data.
     */
    public function findImages(string $basePrompt, int $count = 15): Collection
    {
        if (empty($this->unsplashApiKey)) {
            Log::warning('Image Search: Cannot search Unsplash, API key is missing.');
            return collect();
        }

        try {
            $searchQuery = $this->getSearchQuerySuggestion($basePrompt) ?: $basePrompt;
            Log::info('Image Search: Searching Unsplash with query.', ['query' => $searchQuery]);
            return $this->getImagesFromUnsplash($searchQuery, $count);
        } catch (\Exception $e) {
            Log::error('Image Search: Failed to find images.', ['error' => $e->getMessage()]);
            return collect();
        }
    }

    /**
     * Downloads an image from a URL and saves it as a Curator Media record.
     *
     * @return Media The created Media record.
     */
    public function saveImageFromUrl(string $imageUrl, string $filename, ?string $altText = null, string $source = 'AI Search'): Media
    {
        try {
            $imageResponse = Http::timeout(30)->get($imageUrl);
            $imageResponse->throw();
            $imageData = $imageResponse->body();
        } catch (\Exception $e) {
            Log::error('Image Download Failed', ['url' => $imageUrl, 'error' => $e->getMessage()]);
            throw new \Exception("Failed to download image from URL: {$imageUrl}");
        }

        return $this->saveImageDataToCurator($imageData, $filename, $source, $altText);
    }

    /**
     * Dispatches jobs to save the remaining images in the background.
     *
     * @param Collection $images The collection of image data to save.
     */
    public function saveRemainingImagesInBackground(Collection $images, string $baseFilename): void
    {
        foreach ($images as $image) {
            SaveUnsplashImageJob::dispatch($image, $baseFilename);
        }
        Log::info('Image Search: Dispatched jobs to save remaining images.', ['count' => $images->count()]);
    }

    /**
     * Uses Gemini AI to generate a single, effective search query.
     */
    private function getSearchQuerySuggestion(string $prompt): ?string
    {
        Log::info('Image Search: Getting search query suggestion from Gemini.');
        $geminiPrompt = "Based on the blog post title '{$prompt}', generate one single, highly descriptive, and visually evocative search term for a stock photo website like Unsplash. The term should be concise, in English, and contain 2-4 keywords. Provide only the search term itself, with no extra text or quotes.";

        $suggestion = $this->geminiService->translate($geminiPrompt, 'en', 'en');
        return $suggestion ? trim($suggestion) : null;
    }

    /**
     * Fetches multiple images from Unsplash and formats the response.
     */
    private function getImagesFromUnsplash(string $prompt, int $count): Collection
    {
        $response = Http::withHeaders(['Authorization' => "Client-ID {$this->unsplashApiKey}"])
            ->get($this->unsplashApiUrl, [
                'query' => $prompt,
                'per_page' => $count,
                'orientation' => 'landscape'
            ]);

        $response->throw();
        $results = data_get($response->json(), 'results', []);

        if (empty($results)) {
            Log::warning('Image Search: Unsplash returned no results for query.', ['query' => $prompt]);
            return collect();
        }

        return collect($results)->map(fn ($item) => [
            'id' => data_get($item, 'id'),
            'url' => data_get($item, 'urls.regular'),
            'alt' => data_get($item, 'alt_description') ?: data_get($item, 'description'),
            'author_name' => data_get($item, 'user.name'),
            'author_url' => data_get($item, 'user.links.html'),
        ]);
    }

    /**
     * Saves raw image data to storage and creates a Curator Media record.
     */
    private function saveImageDataToCurator(string $imageData, string $filename, string $source, ?string $altText = null): Media
    {
        $sanitizedFilename = Str::slug($filename);
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($imageData);
        $ext = str_replace('jpeg', 'jpg', explode('/', $mimeType)[1] ?? 'jpg');
        $newFilename = uniqid($sanitizedFilename . '_') . '.' . $ext;

        $disk = config('curator.disk');
        $directory = config('curator.directory');
        $path = "{$directory}/{$newFilename}";

        Storage::disk($disk)->put($path, $imageData);

        $size = Storage::disk($disk)->size($path);
        list($width, $height) = getimagesizefromstring($imageData);

        $media = Media::create([
            'name' => $sanitizedFilename,
            'path' => $path,
            'disk' => $disk,
            'ext' => $ext,
            'type' => 'image',
            'size' => $size,
            'width' => $width,
            'height' => $height,
            'alt' => $altText ?: "{$filename} - Found via {$source}"
        ]);

        Log::info('Image Search: Successfully saved media.', ['media_id' => $media->id, 'source' => $source]);
        return $media;
    }
}
