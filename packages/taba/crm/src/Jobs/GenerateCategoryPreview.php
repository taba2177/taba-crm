<?php

namespace Taba\Crm\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Taba\Crm\Filament\Resources\PostCategoryResource;
use Taba\Crm\Models\PostCategory;
use Illuminate\Support\Str;


class GenerateCategoryPreview implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     *
     * @param PostCategory $postCategory The category model instance.
     * @param array $formData The current state of the form data.
     */
    public function __construct(public PostCategory $postCategory,public array $formData)
    {
        //
    }


    public static function getHomepageComponentOptions(): array
    {
        $componentPath = resource_path('views/components/homepage');
        $files = File::files($componentPath);
        $options = [];

        foreach ($files as $file) {
            $name = Str::before($file->getFilename(), '.blade.php');
            $options[$name] = Str::title(str_replace('-', ' ', $name));
        }

        return $options;
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {

        try {
            $outputDir = public_path('images/homepage');

            // Ensure the output directory exists, creating it if it doesn't.
            File::ensureDirectoryExists($outputDir);

            // Get the component option keys ('hero-section', 'testimonials', etc.)
            // This must be the same static method your RadioDeck uses.
            $componentKeys = array_keys(self::getHomepageComponentOptions());

            foreach ($componentKeys as $componentKey) {
                // The output path will now match the component key, e.g., '.../homepage/hero-section.png'
                $outputPath = "{$outputDir}/{$componentKey}.png";

                // This URL points to a new route we will create to render the component's preview.
                $this->formData['section_component'] = $componentKey;

                $cacheKey = 'preview_data_for_category_' . $this->postCategory->id . '_' . $componentKey;
                Cache::put($cacheKey, $this->formData, now()->addMinutes(1));

                // The URL points to our new route that accepts the category and a cache key.
                $url = route('preview.category', [
                    'category' => $this->postCategory,
                    'data' => $cacheKey,
                ]);

                // Generate and save the screenshot for the component.
                Browsershot::url($url)
                    ->windowSize(1200, 630)
                    ->timeout(1200000) // Increase timeout to 120 seconds (2 minutes)
                    ->deviceScaleFactor(2)
                    ->quality(90)
                    ->waitUntilNetworkIdle()
                    ->save($outputPath);

                Log::info("Generated preview for category {$this->postCategory->id} at component {$componentKey}");
            }
        } catch (\Exception $e) {
            Log::error("Failed to generate preview for category {$this->postCategory->id}: " . $e->getMessage());
        }
}}