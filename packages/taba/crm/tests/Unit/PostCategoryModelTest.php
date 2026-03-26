<?php

namespace Taba\Crm\Tests\Unit;

use Taba\Crm\Tests\TestCase;
use Taba\Crm\Models\PostCategory;
use Taba\Crm\Models\Post;

class PostCategoryModelTest extends TestCase
{
    /** @test */
    public function it_can_create_a_category()
    {
        $category = PostCategory::factory()->create([
            'name' => ['en' => 'Test Category', 'ar' => 'فئة تجريبية'],
        ]);

        $this->assertDatabaseHas('post_categories', [
            'id' => $category->id,
        ]);

        $this->assertEquals('Test Category', $category->name);
    }

    /** @test */
    public function it_has_many_posts()
    {
        $category = PostCategory::factory()->create();
        Post::factory()->count(3)->create(['post_category_id' => $category->id]);

        $this->assertCount(3, $category->posts);
    }

    /** @test */
    public function it_can_get_published_posts_count()
    {
        $category = PostCategory::factory()->create();
        Post::factory()->create(['post_category_id' => $category->id, 'status' => 'published', 'show_in_home' => true]);
        Post::factory()->create(['post_category_id' => $category->id, 'status' => 'draft', 'show_in_home' => true]);
        Post::factory()->create(['post_category_id' => $category->id, 'status' => 'published', 'show_in_home' => true]);

        $category->loadCount(['posts' => function ($query) {
            $query->where('show_in_home', true)->where('status', 'published');
        }]);

        $this->assertEquals(2, $category->posts_count);
    }

    /** @test */
    public function it_handles_translatable_fields()
    {
        $category = PostCategory::factory()->create([
            'name' => ['en' => 'English Name', 'ar' => 'اسم عربي'],
            'description' => ['en' => 'English Description', 'ar' => 'وصف عربي'],
        ]);

        app()->setLocale('en');
        $this->assertEquals('English Name', $category->name);
        $this->assertEquals('English Description', $category->description);

        app()->setLocale('ar');
        $this->assertEquals('اسم عربي', $category->name);
        $this->assertEquals('وصف عربي', $category->description);
    }

    /** @test */
    public function it_can_have_section_component()
    {
        $category = PostCategory::factory()->create([
            'section_component' => 'hero-section',
        ]);

        $this->assertEquals('hero-section', $category->section_component);
    }

    /** @test */
    public function it_can_order_categories()
    {
        PostCategory::factory()->create(['order' => 3]);
        PostCategory::factory()->create(['order' => 1]);
        PostCategory::factory()->create(['order' => 2]);

        $categories = PostCategory::orderBy('order', 'asc')->get();

        $this->assertEquals(1, $categories[0]->order);
        $this->assertEquals(2, $categories[1]->order);
        $this->assertEquals(3, $categories[2]->order);
    }

    /** @test */
    public function it_can_get_first_post()
    {
        $category = PostCategory::factory()->create();
        $firstPost = Post::factory()->create([
            'post_category_id' => $category->id,
            'order' => 1,
            'status' => 'published',
        ]);
        Post::factory()->create([
            'post_category_id' => $category->id,
            'order' => 2,
            'status' => 'published',
        ]);

        $category->load('firstPost');

        $this->assertNotNull($category->firstPost);
        $this->assertEquals($firstPost->id, $category->firstPost->id);
    }
}
