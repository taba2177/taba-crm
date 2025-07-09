<?php

namespace Database\Seeders;

use App\Models\HomepageSection;
use App\Models\PostCategory;
use Illuminate\Database\Seeder;

class HomepageSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            ['component_view' => 'hero', 'post_category_slug' => 'hero', 'order' => 1],
            ['component_view' => 'brand-marque', 'post_category_slug' => null, 'order' => 2],
            ['component_view' => 'services-list', 'post_category_slug' => 'services', 'order' => 3],
            ['component_view' => 'portfolio', 'post_category_slug' => 'portfolio', 'order' => 4],
            ['component_view' => 'services-features', 'post_category_slug' => 'features', 'order' => 5],
            ['component_view' => 'services-carousel', 'post_category_slug' => 'testimonials', 'order' => 6],
            ['component_view' => 'faq', 'post_category_slug' => 'faq', 'order' => 7],
            ['component_view' => 'services-grid', 'post_category_slug' => 'services', 'order' => 8],
            ['component_view' => 'latest-news', 'post_category_slug' => 'blog', 'order' => 9],
            ['component_view' => 'call-action', 'post_category_slug' => 'call-action', 'order' => 9],

        ];

        foreach ($sections as $section) {
            $postCategory = null;
            if ($section['post_category_slug']) {
                $postCategory = PostCategory::where('slug', $section['post_category_slug'])->first();
            }

            HomepageSection::create([
                'component_view' => $section['component_view'],
                'post_category_id' => $postCategory?->id,
                'order' => $section['order'],
            ]);
        }
    }
}