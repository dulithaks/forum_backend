<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    private $headers;

    protected function setUp(): void
    {
        parent::setUp();
        $this->headers['Accept'] = 'application/json';
    }

    /**
     * @test
     */
    public function user_registration_success()
    {
        $user = User::factory()->make();
        $response = $this->post('api/register', [
            'first_name' => $user->first_name,
            'last_name' => $user->first_name,
            'email' => $user->email,
            'password' => '12345678!',
        ], $this->headers);

        $response->assertStatus(201);
    }

    /**
     * @test
     */
    public function user_registration_fail()
    {
        $user = User::factory()->make();
        $response = $this->post('api/register', [
            'first_name' => '',
            'last_name' => $user->first_name,
            'email' => $user->email,
            'password' => '12345678!',
        ], $this->headers);

        $response->assertStatus(422);
    }
}
