<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertJson([
                'test' => "Welcome to Transave Nigeria System Api Live APP..."
            ]);


    }

    public function test_users_registration_on_the_platform()
    {
        $response = $this->postJson('api/register',[
            'name' => 'Ayodeji Johnson',
            'email' => 'ayanwoye74@gmail1.com',
            'phone' => '08063138322',
            'password' => 'password',
        ]);

        $response->assertStatus(201)
        ->assertJson([
            'name' => 'Ayodeji Johnson'
        ]);
    }

    public function test_users_login_on_the_platform()
    {
        $user= ['phone' => '08142306051','password' => 'password'];
        $response = $this->json('POST','api/login',$user,['Accept' => 'application/json']);

        $response->assertStatus(200)
        ->assertJsonStructure([
            0 => [
                'data' => [
                    'original' => [
                        'access_token'
                    ]
                ]
            ]
        ]);
    }
}
