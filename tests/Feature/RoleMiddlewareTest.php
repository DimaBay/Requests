<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/dispatcher')->assertRedirect('/login');
        $this->get('/master')->assertRedirect('/login');
    }

    public function test_dispatcher_can_access_dispatcher_panel_only(): void
    {
        $dispatcher = User::factory()->dispatcher()->create();

        $this->actingAs($dispatcher)->get('/dispatcher')->assertOk();
        $this->actingAs($dispatcher)->get('/master')->assertStatus(403);
    }

    public function test_master_can_access_master_panel_only(): void
    {
        $master = User::factory()->master()->create();

        $this->actingAs($master)->get('/master')->assertOk();
        $this->actingAs($master)->get('/dispatcher')->assertStatus(403);
    }
}

