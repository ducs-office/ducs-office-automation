<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminCreatesNewUser extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_staff_can_create_new_teacher_with_credentials()
    {
        $adminRole = Role::create(['name' => 'admin-staff']);

        $this->signIn(create(User::class), $adminRole);

        $teacherRole = Role::create(['name' => 'teacher']);

        Mail::fake();

        $response = $this->post('/admin-staff/users', [
            'name' => 'External Teacher',
            'email' => 'external@gmail.com',
            'role_id' => $teacherRole->id
        ])->assertSucessful();

        Mail::assertSent();
    }
}
