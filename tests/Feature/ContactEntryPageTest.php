<?php

namespace Taba\Crm\Tests\Feature;

use Taba\Crm\Tests\TestCase;

class ContactEntryPageTest extends TestCase
{
    /** @test */
    public function contact_entry_store_saves_page_field(): void
    {
        $response = $this->postJson('/api/v1/contact', [
            'name'    => 'Test User',
            'message' => 'Hello world',
            'page'    => '/services',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('contact_entries', ['page' => '/services']);
    }

    /** @test */
    public function page_field_is_optional(): void
    {
        $response = $this->postJson('/api/v1/contact', [
            'name'    => 'Test User',
            'message' => 'Hello world',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('contact_entries', ['page' => null]);
    }
}
