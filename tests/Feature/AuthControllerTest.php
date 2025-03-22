<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Admin;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_register_a_user()
    {
        // Data untuk registrasi
        $data = [
            'role_id' => 1,
            'username' => 'owner',
            'name' => 'owner',
            'password' => 'owner123',
            'password_confirmation' => 'owner123',
            'pin' => '123456',
            'pin_confirmation' => '123456',
            'is_active' => 1,
        ];

        // Kirim request ke API register
        $response = $this->postJson('/api/register', $data);

        // Pastikan response HTTP 201 (Created)
        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'User Successfully Registered',
                 ]);

        // Pastikan user tersimpan di database
        $this->assertDatabaseHas('admins', [
            'username' => 'owner',
            'name' => 'owner',
            'role_id' => 1,
            'is_active' => 1,
        ]);
    }
}
