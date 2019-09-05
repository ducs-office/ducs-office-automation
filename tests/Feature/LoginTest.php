<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class LoginTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function login_form_test()
    {
        $response = $this->withoutExceptionHandling()->get('/login');
        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_login_with_correct_credentials() {

        $user = factory(\App\User::class)->create([
            // 'email' => 'test.ducs@gmail.com'
            'password' => bcrypt('secret')
        ]);

        $this->withoutExceptionHandling();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret'
        ])->assertRedirect('/');

        $this->assertTrue(Auth::check(), 'User was expected to login but was not.');

    }

    /** @test */
    public function if_user_is_logged_in_redirect_to_home() {

        $user = factory(\App\User::class)->create();
        $this->be($user);

        $this->get('/login')->assertRedirect('/');

    }
}
