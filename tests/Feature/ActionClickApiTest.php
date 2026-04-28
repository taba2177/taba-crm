<?php

namespace Taba\Crm\Tests\Feature;

use Taba\Crm\Tests\TestCase;
use Taba\Crm\Models\ActionClick;

class ActionClickApiTest extends TestCase
{
    /** @test */
    public function it_stores_a_whatsapp_click(): void
    {
        $response = $this->postJson('/api/v1/actions', [
            'action' => 'whatsapp',
            'source' => 'organic',
            'page'   => '/home',
        ]);

        $response->assertStatus(204);
        $this->assertDatabaseHas('action_clicks', [
            'action' => 'whatsapp',
            'source' => 'organic',
            'page'   => '/home',
        ]);
    }

    /** @test */
    public function it_rejects_invalid_action_type(): void
    {
        $this->postJson('/api/v1/actions', ['action' => 'invalid'])
             ->assertStatus(422);
    }

    /** @test */
    public function it_hashes_the_ip_address(): void
    {
        $this->postJson('/api/v1/actions', ['action' => 'call']);

        $click = ActionClick::first();
        $this->assertNotNull($click->ip_hash);
        $this->assertEquals(64, strlen($click->ip_hash));
    }

    /** @test */
    public function summary_requires_authentication(): void
    {
        $this->getJson('/api/v1/actions/summary')->assertStatus(401);
    }
}
