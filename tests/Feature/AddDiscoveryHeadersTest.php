<?php
// tests/Feature/AddDiscoveryHeadersTest.php

namespace Taba\Crm\Tests\Feature;

use Taba\Crm\Tests\TestCase;

class AddDiscoveryHeadersTest extends TestCase
{
    private function putIndexHtml(): void
    {
        file_put_contents(public_path('index.html'), '<html><head></head><body></body></html>');
    }

    protected function tearDown(): void
    {
        @unlink(public_path('index.html'));
        parent::tearDown();
    }

    /** @test */
    public function home_page_includes_link_sitemap_header(): void
    {
        $this->putIndexHtml();
        $response = $this->get('/');
        $this->assertNotEmpty($response->headers->get('Link'));
        $this->assertStringContainsString('rel="sitemap"', $response->headers->get('Link'));
    }

    /** @test */
    public function home_page_includes_link_describedby_header(): void
    {
        $this->putIndexHtml();
        $response = $this->get('/');
        $this->assertStringContainsString('rel="describedby"', $response->headers->get('Link'));
    }
}
