<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_farmer_routes(): void
    {
        $this->get('/parcels')->assertRedirect('/login');
    }

    public function test_farmer_cannot_access_admin_dashboard(): void
    {
        $farmer = User::factory()->create(['role' => 'farmer']);

        $this->actingAs($farmer)->get('/admin/dashboard')->assertForbidden();
    }
}
