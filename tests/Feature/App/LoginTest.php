<?php

namespace Tests\Feature\App;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_login_to_system()
    {
        $response = $this->post('/login', [
            'email' => 'admin@gmail.com',
            'password' => '123456789'
        ]);

        $response->assertRedirect('/panel');
    }
}
