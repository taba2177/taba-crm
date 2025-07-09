<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Jobs\ProcessReview;
use App\Models\review;
use App\Models\Studio;

class FetchGoogleMapsReviews extends Command
{
    protected $signature = 'fetch:google-maps-reviews';
    protected $description = 'Fetch Google Maps reviews and save them to the database as comments if new';

    public function handle()
    {
        $this->info('Fetching Google Maps reviews...');

        try {
            // Execute Node.js script
            $output = shell_exec('node public/rev/scrape_reviews.js');

            if ($output === null) {
                $this->error('Failed to execute Node.js script.');
                Log::error('Node.js script execution failed.');
                return;
            }

            // Sanitize and preprocess the output
            $cleanOutput = trim($output);

            // Remove "Fetched Reviews:" prefix if present
            if (str_starts_with($cleanOutput, 'Fetched Reviews:')) {
                $cleanOutput = substr($cleanOutput, strlen('Fetched Reviews:'));
            }

            // Decode JSON output
            $reviews = json_decode($cleanOutput, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->error('Invalid JSON output from Node.js script.');
                Log::error('JSON Decode Error', [
                    'error' => json_last_error_msg(),
                    'output' => $cleanOutput,
                ]);
                return;
            }

            foreach ($reviews as $review) {
                // Basic validation
                if (!isset($review['review'], $review['name'], $review['rating'], $review['img'], $review['images'])) {
                    Log::warning('Invalid review structure', ['review' => $review]);
                    continue;
                }

                // Check if the review already exists
                $existingReview = review::where('name', $review['name'])
                    ->where('content', $review['review'])
                    ->first();


                Studio::create([
                    'image' => $review['images'],
                ]);

                if ($existingReview) {
                    $this->info("Skipping duplicate review from {$review['name']}");
                    continue;
                }
                // $this->info($review['review']. $review['name']. $review['rating']);
                // Dispatch job to queue for processing
                review::create([
                    'content' => $review['review'],
                    'name' => $review['name'],
                    'rating' => $review['rating'],
                    'img' => $review['img'],
                ]);


                // Studio::create([
                  //  'image' => $review['images'],
               // ]);
            }

            $this->info('Reviews processed successfully!');
        } catch (\Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());
            Log::error('Error fetching reviews', [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}