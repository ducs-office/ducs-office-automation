<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ChangePasswordTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_change_their_password_if_correct_password_is_given()
    {
        $curr_pass = 'current_password';
        $new_pass = 'new_password';

        $this->signIn($user = create(User::class, 1, [
            'password' => Hash::make($curr_pass)
        ]));

        $this->withoutExceptionHandling()->post('/account/change_password', [
            'password' => $curr_pass,
            'new_password' => $new_pass,
            'confirmed_new_password' => $new_pass,
        ])->assertRedirect()
        ->assertSessionHasNoErrors();

        $this->assertTrue(Hash::check($new_pass, $user->fresh()->password), 'password was not changed');
    }

    /** @test */
    public function authenticated_user_cannot_change_their_password_if_incorrect_password_is_given()
    {
        $this->signIn($user = create(User::class, 1, [
            'password' => Hash::make('old_password')
        ]));

        $this->post('/account/change_password', [
                'password' => 'incorrect_password',
                'new_password' => 'new_pass',
                'confirmed_new_password' => 'new_pass',
            ])->assertRedirect()
            ->assertSessionHasErrors('password');

        $this->assertEquals($user->password, $user->fresh()->password, 'password was changed, with incorrect password');
    }
}
