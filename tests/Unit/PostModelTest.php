<?php

namespace Taba\Crm\Tests\Unit;

use Taba\Crm\Tests\TestCase;
use Taba\Crm\Models\Post;
use Taba\Crm\Models\PostCategory;

class PostModelTest extends TestCase
{
    /** @test */
    public function it_can_create_a_post()
    {
        $category = PostCategory::factory()->create();

        $post = Post::factory()->create([
            'title' => ['en' => 'Test Post', 'ar' => 'منشور تجريبي'],
            'post_category_id' => $category->id,
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
        ]);

        $this->assertEquals('Test Post', $post->title);
    }

    /** @test */
    public function it_belongs_to_a_category()
    {
        $category = PostCategory::factory()->create(['name' => 'Test Category']);
        $post = Post::factory()->create(['post_category_id' => $category->id]);

        $this->assertInstanceOf(PostCategory::class, $post->postCategory);
        $this->assertEquals('Test Category', $post->postCategory->name);
    }

    /** @test */
    public function it_can_scope_published_posts()
    {
        Post::factory()->create(['is_published' => true]);
        Post::factory()->create(['is_published' => false]);
        Post::factory()->create(['is_published' => true]);

        $publishedPosts = Post::published()->get();

        $this->assertCount(2, $publishedPosts);
    }

    /** @test */
    public function it_can_filter_posts_for_home()
    {
        Post::factory()->create(['show_in_home' => true, 'is_published' => true]);
        Post::factory()->create(['show_in_home' => false, 'is_published' => true]);
        Post::factory()->create(['show_in_home' => true, 'is_published' => true]);

        $homePosts = Post::where('show_in_home', true)->published()->get();

        $this->assertCount(2, $homePosts);
    }

    /** @test */
    public function it_handles_translatable_fields()
    {
        $post = Post::factory()->create([
            'title' => ['en' => 'English Title', 'ar' => 'عنوان عربي'],
            'meta_description' => ['en' => 'English Excerpt', 'ar' => 'مقتطف عربي'],
        ]);

        app()->setLocale('en');
        $this->assertEquals('English Title', $post->title);
        $this->assertEquals('English Excerpt', $post->meta_description);

        app()->setLocale('ar');
        $this->assertEquals('عنوان عربي', $post->title);
        $this->assertEquals('مقتطف عربي', $post->meta_description);
    }

    /** @test */
    public function it_has_meta_fields_for_seo()
    {
        $post = Post::factory()->create([
            'meta_title' => 'SEO Title',
            'meta_description' => 'SEO Description',
        ]);

        $this->assertEquals('SEO Title', $post->meta_title);
        $this->assertEquals('SEO Description', $post->meta_description);
    }

    /** @test */
    public function it_can_order_posts()
    {
        Post::factory()->create(['order' => 3]);
        Post::factory()->create(['order' => 1]);
        Post::factory()->create(['order' => 2]);

        $posts = Post::orderBy('order', 'asc')->get();

        $this->assertEquals(1, $posts[0]->order);
        $this->assertEquals(2, $posts[1]->order);
        $this->assertEquals(3, $posts[2]->order);
    }
}
