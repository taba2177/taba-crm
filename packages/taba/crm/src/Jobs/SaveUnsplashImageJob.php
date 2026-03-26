<?php

namespace Taba\Crm\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Taba\Crm\Services\ImageSearchService;
use Illuminate\Support\Facades\Log;

class SaveUnsplashImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $imageData;
    public string $baseFilename;

    /**
     * Create a new job instance.
     */
    public function __construct(array $imageData, string $baseFilename)
    {
        $this->imageData = $imageData;
        $this->baseFilename = $baseFilename;
    }

    /**
     * Execute the job.
     */
    public function handle(ImageSearchService $imageService): void
    {
        try {
            Log::info('SaveUnsplashImageJob: Starting to save background image.', ['id' => $this->imageData['id']]);

            $imageService->saveImageFromUrl(
                imageUrl: $this->imageData['url'],
                filename: $this->baseFilename,
                altText: $this->imageData['alt'],
                source: 'Unsplash AI Search'
            );

            Log::info('SaveUnsplashImageJob: Successfully saved background image.', ['id' => $this->imageData['id']]);
        } catch (\Exception $e) {
            Log::error('SaveUnsplashImageJob: Failed to save image.', [
                'id' => $this->imageData['id'],
                'error' => $e->getMessage(),
            ]);
        }
    }
}
