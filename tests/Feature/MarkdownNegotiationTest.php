<?php
// tests/Feature/MarkdownNegotiationTest.php

namespace Taba\Crm\Tests\Feature;

use Taba\Crm\Tests\TestCase;
use Taba\Crm\Models\Post;
use Taba\Crm\Models\PostCategory;

class MarkdownNegotiationTest extends TestCase
{
    /** @test */
    public function it_returns_markdown_when_accept_header_is_text_markdown(): void
    {
        $cat  = PostCategory::factory()->create();
        $post = Post::factory()->create([
            'post_category_id' => $cat->id,
            'is_published'     => true,
            'content'          => '# Hello World',
        ]);

        $response = $this->get(
            '/api/v1/posts/' . $post->slug,
            ['Accept' => 'text/markdown']
        );

        $response->assertStatus(200);
        $this->assertStringContainsString('text/markdown', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('# Hello World', $response->getContent());
    }

    /** @test */
    public function it_returns_json_by_default(): void
    {
        $cat  = PostCategory::factory()->create();
        $post = Post::factory()->create([
            'post_category_id' => $cat->id,
            'is_published'     => true,
        ]);

        $response = $this->getJson('/api/v1/posts/' . $post->slug);
        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['id', 'title']]);
    }
}
