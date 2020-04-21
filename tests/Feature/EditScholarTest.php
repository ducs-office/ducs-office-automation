<?php

namespace Tests\Feature;

use App\Models\Scholar;
use Dotenv\Regex\Success;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EditScholarTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function email_of_scholar_can_be_edited()
    {
        $this->signIn();

        $email = 'scholar@gmail.com';
        $scholar = create(Scholar::class, 1, ['email' => $email]);

        $this->withoutExceptionHandling()
           ->patch(route('staff.scholars.update', $scholar), [
               'email' => $newEmail = 'scholar.du.ac.in',
           ])
           ->assertRedirect()
           ->assertSessionHasFlash('success', 'Scholar updated successfully');

        $this->assertEquals($newEmail, $scholar->fresh()->email);
    }

    /** @test */
    public function first_name_of_scholar_can_be_edited()
    {
        $this->signIn();

        $firstName = 'Pushcar';
        $scholar = create(Scholar::class, 1, ['first_name' => $firstName]);

        $this->withoutExceptionHandling()
           ->patch(route('staff.scholars.update', $scholar), [
               'first_name' => $newFirstName = 'Pushkar',
           ])
           ->assertRedirect()
           ->assertSessionHasFlash('success', 'Scholar updated successfully');

        $this->assertEquals($newFirstName, $scholar->fresh()->first_name);
    }

    /** @test */
    public function last_name_of_scholar_can_be_edited()
    {
        $this->signIn();

        $lastName = 'Solanki';
        $scholar = create(Scholar::class, 1, ['last_name' => $lastName]);

        $this->withoutExceptionHandling()
           ->patch(route('staff.scholars.update', $scholar), [
               'last_name' => $newLastName = 'Sonkar',
           ])
           ->assertRedirect()
           ->assertSessionHasFlash('success', 'Scholar updated successfully');

        $this->assertEquals($newLastName, $scholar->fresh()->last_name);
    }

    /** @test */
    public function scholar_is_not_validated_for_uniqueness_if_email_is_not_changed()
    {
        $this->signIn();

        $lastName = 'Solanki';
        $scholar = create(Scholar::class, 1, ['last_name' => $lastName]);

        $this->withoutExceptionHandling()
            ->patch(route('staff.scholars.update', $scholar), [
                'first_name' => $scholar->first_name,
                'last_name' => $newLastName = 'Sonkar',
                'email' => $scholar->email,
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Scholar updated successfully');

        $this->assertEquals($newLastName, $scholar->fresh()->last_name);
        $this->assertEquals(1, Scholar::count());
    }
}
