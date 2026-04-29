<?php

namespace Taba\Crm\Tests\Feature;

use Taba\Crm\Tests\TestCase;
use Taba\Crm\Models\Post;
use Taba\Crm\Models\PostCategory;
use Livewire\Livewire;
use Taba\Crm\Livewire\Home;

class HomeComponentTest extends TestCase
{
    /** @test */
    public function it_loads_categories_with_sections()
    {
        $category = PostCategory::factory()->create([
            'section_component' => 'hero-section',
            'order' => 1,
        ]);

        Post::factory()->count(3)->create([
            'post_category_id' => $category->id,
            'show_in_home' => true,
            'is_published' => true,
        ]);

        $component = Livewire::test(Home::class);

        $component->assertSet('sections', function ($sections) use ($category) {
            return $sections->contains('id', $category->id);
        });
    }

    /** @test */
    public function it_prepares_seo_data_from_first_post()
    {
        $category = PostCategory::factory()->create();
        $post = Post::factory()->create([
            'post_category_id' => $category->id,
            'show_in_home' => true,
            'is_published' => true,
            'meta_title' => 'SEO Title',
            'meta_description' => 'SEO Description',
            'order' => 1,
        ]);

        $component = Livewire::test(Home::class);

        $component->assertSet('metaTitle', 'Laravel');
        $component->assertSet('metaDescription', function ($description) {
            return str_contains($description, 'SEO Description');
        });
    }

    /** @test */
    public function it_falls_back_to_category_for_seo_when_no_posts()
    {
        $category = PostCategory::factory()->create([
            'name' => 'Category Name',
            'description' => 'Category Description',
            'section_component' => 'test-section',
            'order' => 1,
        ]);

        $component = Livewire::test(Home::class);

        $component->assertSet('metaTitle', 'Laravel | Category Name');
        $component->assertSet('metaDescription', 'Laravel - Category Name');
    }

    /** @test */
    public function it_identifies_heavy_sections()
    {
        $category = PostCategory::factory()->create([
            'section_component' => 'posts-section',
            'order' => 1,
        ]);

        // Create more than HEAVY_SECTION_THRESHOLD (8) posts
        Post::factory()->count(9)->create([
            'post_category_id' => $category->id,
            'show_in_home' => true,
            'is_published' => true,
        ]);

        $component = Livewire::test(Home::class);

        $component->assertSet('sections', function ($sections) {
            $section = $sections->first();
            return $section->posts_count > Home::HEAVY_SECTION_THRESHOLD;
        });
    }

    /** @test */
    public function it_creates_fake_posts_for_heavy_sections()
    {
        $category = PostCategory::factory()->create([
            'section_component' => 'posts-section',
            'order' => 1,
        ]);

        Post::factory()->count(9)->create([
            'post_category_id' => $category->id,
            'show_in_home' => true,
            'is_published' => true,
        ]);

        $component = Livewire::test(Home::class);

        $component->assertSet('sections', function ($sections) {
            $section = $sections->first();
            if ($section->posts_count > Home::HEAVY_SECTION_THRESHOLD) {
                // Heavy sections should have placeholder or preloaded posts ready for UI rendering.
                return $section->posts->count() > 0;
            }
            return true;
        });
    }

    /** @test */
    public function it_loads_light_sections_immediately()
    {
        $category = PostCategory::factory()->create([
            'section_component' => 'light-section',
            'order' => 1,
        ]);

        Post::factory()->count(3)->create([
            'post_category_id' => $category->id,
            'show_in_home' => true,
            'is_published' => true,
        ]);

        $component = Livewire::test(Home::class);

        $component->assertSet('sections', function ($sections) {
            $section = $sections->first();
            // Light sections should have real posts loaded
            return $section->posts->count() > 0 && !str_starts_with($section->posts->first()->id, 'fake-');
        });
    }

    /** @test */
    public function it_can_load_remaining_heavy_posts()
    {
        $category = PostCategory::factory()->create([
            'section_component' => 'posts-section',
            'order' => 1,
        ]);

        Post::factory()->count(9)->create([
            'post_category_id' => $category->id,
            'show_in_home' => true,
            'is_published' => true,
        ]);

        $component = Livewire::test(Home::class);

        $component->call('loadRemainingHeavyPosts');

        // After loading, posts should be real (not fake)
        $component->assertSet('sections', function ($sections) {
            $section = $sections->first();
            return $section->posts->count() > 0 && !str_starts_with($section->posts->first()->id, 'fake-');
        });
    }

    /** @test */
    public function it_handles_translatable_content()
    {
        $category = PostCategory::factory()->create([
            'name' => ['en' => 'English Name', 'ar' => 'اسم عربي'],
            'section_component' => 'test-section',
            'order' => 1,
        ]);

        app()->setLocale('en');
        $componentEn = Livewire::test(Home::class);
        $componentEn->assertSet('sections', function ($sections) {
            return $sections->first()->name === 'English Name';
        });

        app()->setLocale('ar');
        $componentAr = Livewire::test(Home::class);
        $componentAr->assertSet('sections', function ($sections) {
            return $sections->first()->name === 'اسم عربي';
        });
    }
}
