<?php

namespace Tests\Feature;

use App\Enums\RequestStatus;
use App\Models\RepairRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequestCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_request_can_be_created(): void
    {
        $response = $this->post(route('requests.store'), [
            'client_name' => 'Тестовый Клиент',
            'phone' => '+7 999 123-45-67',
            'address' => 'ул. Тестовая, д. 1',
            'problem_text' => 'Сломалась техника',
        ]);

        $response->assertRedirect(route('requests.create'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('requests', [
            'client_name' => 'Тестовый Клиент',
            'status' => RequestStatus::New->value,
        ]);
        $request = RepairRequest::first();
        $this->assertDatabaseHas('request_logs', [
            'request_id' => $request->id,
            'new_status' => RequestStatus::New->value,
        ]);
    }
}
