<?php

namespace Tests\Feature;

use App\Models\RepairRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RaceConditionTest extends TestCase
{
    use RefreshDatabase;

    public function test_start_succeeds_when_status_is_assigned(): void
    {
        $master = User::factory()->create(['role' => 'master']);
        $request = RepairRequest::create([
            'client_name' => 'Клиент',
            'phone' => '+7 999 111-11-11',
            'address' => 'Адрес',
            'problem_text' => 'Проблема',
            'status' => 'assigned',
            'assigned_to' => $master->id,
        ]);

        $response = $this->actingAs($master)->post(route('master.start', $request));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $request->refresh();
        $this->assertEquals('in_progress', $request->status->value);
    }

    public function test_start_returns_409_on_duplicate_request(): void
    {
        $master = User::factory()->create(['role' => 'master']);
        $request = RepairRequest::create([
            'client_name' => 'Клиент',
            'phone' => '+7 999 111-11-11',
            'address' => 'Адрес',
            'problem_text' => 'Проблема',
            'status' => 'assigned',
            'assigned_to' => $master->id,
        ]);

        $first = $this->actingAs($master)->post(route('master.start', $request));
        $first->assertRedirect()->assertSessionHas('success');

        $second = $this->actingAs($master)->post(route('master.start', $request));
        $second->assertStatus(409);
    }
}
