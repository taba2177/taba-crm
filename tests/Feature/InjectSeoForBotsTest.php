<?php
// tests/Feature/InjectSeoForBotsTest.php

namespace Taba\Crm\Tests\Feature;

use Taba\Crm\Tests\TestCase;

class InjectSeoForBotsTest extends TestCase
{
    private function putIndexHtml(string $content = '<html><head></head><body></body></html>'): void
    {
        file_put_contents(public_path('index.html'), $content);
    }

    protected function tearDown(): void
    {
        @unlink(public_path('index.html'));
        parent::tearDown();
    }

    /** @test */
    public function real_browser_gets_unmodified_html(): void
    {
        $this->putIndexHtml();
        $response = $this->get('/', ['User-Agent' => 'Mozilla/5.0 Chrome/124']);
        // Not a bot — file served as-is (no og:title injected)
        $this->assertStringNotContainsString('og:title', $response->getContent());
    }

    /** @test */
    public function googlebot_gets_og_title_injected(): void
    {
        $this->putIndexHtml();
        $response = $this->get('/', ['User-Agent' => 'Googlebot/2.1']);
        $this->assertStringContainsString('og:title', $response->getContent());
    }

    /** @test */
    public function html_lang_attribute_is_injected(): void
    {
        $this->putIndexHtml('<html><head></head><body></body></html>');
        $response = $this->get('/', ['User-Agent' => 'Googlebot/2.1']);
        $this->assertStringContainsString('lang="', $response->getContent());
    }

    /** @test */
    public function canonical_link_is_injected(): void
    {
        $this->putIndexHtml();
        $response = $this->get('/', ['User-Agent' => 'facebookexternalhit/1.1']);
        $this->assertStringContainsString('rel="canonical"', $response->getContent());
    }

    /** @test */
    public function json_ld_website_schema_injected_on_home(): void
    {
        $this->putIndexHtml();
        $response = $this->get('/', ['User-Agent' => 'Googlebot/2.1']);
        $this->assertStringContainsString('application/ld+json', $response->getContent());
        $this->assertStringContainsString('"@type":"WebSite"', $response->getContent());
    }

    /** @test */
    public function missing_index_html_falls_through_gracefully(): void
    {
        // No index.html — middleware must not crash; route returns 503 when file absent
        $response = $this->get('/', ['User-Agent' => 'Googlebot/2.1']);
        $response->assertStatus(503);
    }
}
