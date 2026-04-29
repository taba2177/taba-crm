<?php
// tests/Feature/AddDiscoveryHeadersTest.php

namespace Taba\Crm\Tests\Feature;

use Taba\Crm\Tests\TestCase;

class AddDiscoveryHeadersTest extends TestCase
{
    private function putPublicFiles(): void
    {
        file_put_contents(public_path('index.html'), '<html><head></head><body></body></html>');
        file_put_contents(public_path('sitemap.xml'), '<?xml version="1.0"?><urlset/>');
        file_put_contents(public_path('llms.txt'), '# test');
    }

    protected function tearDown(): void
    {
        @unlink(public_path('index.html'));
        @unlink(public_path('sitemap.xml'));
        @unlink(public_path('llms.txt'));
        parent::tearDown();
    }

    /** @test */
    public function home_page_includes_link_sitemap_header(): void
    {
        $this->putPublicFiles();
        $response = $this->get('/');
        $this->assertNotEmpty($response->headers->get('Link'));
        $this->assertStringContainsString('rel="sitemap"', $response->headers->get('Link'));
    }

    /** @test */
    public function home_page_includes_link_describedby_header(): void
    {
        $this->putPublicFiles();
        $response = $this->get('/');
        $this->assertStringContainsString('rel="describedby"', $response->headers->get('Link'));
    }

    /** @test */
    public function link_header_omits_sitemap_when_file_absent(): void
    {
        // No sitemap.xml created — only index.html
        file_put_contents(public_path('index.html'), '<html><head></head><body></body></html>');
        $response = $this->get('/');
        $this->assertStringNotContainsString('rel="sitemap"', (string) $response->headers->get('Link'));
    }
}
