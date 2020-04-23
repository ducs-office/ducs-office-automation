<?php

namespace Tests\Feature;

use App\Models\College;
use App\Models\Programme;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateCollegeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_not_update_a_college()
    {
        $college = create(College::class);

        $this->withExceptionHandling()
            ->patch(route('staff.colleges.update', $college), ['code' => 'code1'])
            ->assertRedirect();

        $this->assertEquals($college->code, $college->fresh()->code);
    }

    /** @test */
    public function admin_can_update_a_college_code()
    {
        $this->signIn();

        $college = create(College::class);

        $this->withoutExceptionHandling()
            ->patch(route('staff.colleges.update', $college), ['code' => $new_code = 'code1'])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'College updated successfully');

        $this->assertEquals(1, College::count());
        $this->assertEquals($new_code, $college->fresh()->code);
    }

    /** @test */
    public function admin_can_update_a_college_name()
    {
        $this->signIn();

        $college = create(College::class);

        $this->withoutExceptionHandling()
            ->patch(route('staff.colleges.update', $college), ['name' => $new_name = 'new name'])
            ->assertRedirect()
            ->assertSessionHasNoErrors()
            ->assertSessionHasFlash('success', 'College updated successfully');

        $this->assertEquals(1, College::count());
        $this->assertEquals($new_name, $college->fresh()->name);
    }

    /** @test */
    public function colleges_principal_name_can_be_updated()
    {
        $this->signIn();

        $college = create(College::class);

        $this->withoutExceptionHandling()
            ->patch(route('staff.colleges.update', $college), [
                'principal_name' => $new_principal = 'New Principal',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors()
            ->assertSessionHasFlash('success', 'College updated successfully');

        $this->assertEquals($new_principal, $college->fresh()->principal_name);
    }

    /** @test */
    public function colleges_principal_phones_can_be_updated()
    {
        $this->signIn();

        $college = create(College::class);

        $this->withoutExceptionHandling()
            ->patch(route('staff.colleges.update', $college->id), [
                'principal_phones' => $new_phones = ['9876543210'],
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors()
            ->assertSessionHasFlash('success', 'College updated successfully');

        $this->assertSame($new_phones, $college->fresh()->principal_phones);
    }

    /** @test */
    public function colleges_principal_emails_can_be_updated()
    {
        $this->signIn();

        $college = create(College::class);

        $this->withoutExceptionHandling()
            ->patch(route('staff.colleges.update', $college), [
                'principal_emails' => $new_emails = ['princy@somecollege.com'],
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors()
            ->assertSessionHasFlash('success', 'College updated successfully');

        $this->assertSame($new_emails, $college->fresh()->principal_emails);
    }

    /** @test */
    public function colleges_address_can_be_updated()
    {
        $this->signIn();

        $college = create(College::class);

        $this->withoutExceptionHandling()
            ->patch(route('staff.colleges.update', $college), [
                'address' => $new_address = 'Arts Faculty, Delhi University, Delhi - 110007',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors()
            ->assertSessionHasFlash('success', 'College updated successfully');

        $this->assertEquals($new_address, $college->fresh()->address);
    }

    /** @test */
    public function colleges_website_can_be_updated()
    {
        $this->signIn();

        $college = create(College::class);

        $this->withoutExceptionHandling()
            ->patch(route('staff.colleges.update', $college), [
                'website' => $new_website = 'https://new-website.com',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors()
            ->assertSessionHasFlash('success', 'College updated successfully');

        $this->assertEquals($new_website, $college->fresh()->website);
    }

    /** @test */
    public function admin_can_update_a_college_programmes()
    {
        $this->signIn();

        $college = create(College::class);
        $related_programmes = create(Programme::class, 3);
        $college->programmes()->attach($related_programmes->pluck('id')->toArray());

        $other_programmes = create(Programme::class, 2);
        $new_programme_ids = [
            $related_programmes[0]->id,
            $other_programmes[0]->id,
            $other_programmes[1]->id,
        ];

        $this->withoutExceptionHandling()
            ->patch(route('staff.colleges.update', $college), [
                'programmes' => $new_programme_ids,
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors()
            ->assertSessionHasFlash('success', 'College updated successfully');

        $this->assertEquals(1, College::count());

        $college_programme_ids = $college->fresh()->programmes->pluck('id')->toArray();

        $this->assertSame(
            $new_programme_ids,
            $college_programme_ids,
            'all programmes were not updated'
        );
    }

    /** @test */
    public function college_is_not_validated_for_uniqueness_if_code_is_not_changed()
    {
        $this->withoutExceptionHandling()
            ->signIn();

        $college = create(College::class);

        $response = $this->patch(route('staff.colleges.update', $college), [
            'code' => $college->code,
            'name' => $newName = 'New college',
        ])->assertRedirect()
        ->assertSessionHasNoErrors()
        ->assertSessionHasFlash('success', 'College updated successfully!');

        $this->assertEquals(1, College::count());
        $this->assertEquals($newName, $college->fresh()->name);
    }

    /** @test */
    public function college_is_not_validated_for_uniqueness_if_name_is_not_changed()
    {
        $this->withoutExceptionHandling()
            ->signIn();

        $college = create(College::class);

        $response = $this->patch(route('staff.colleges.update', $college), [
            'code' => $new_code = 'new_code123',
            'name' => $college->name,
        ])->assertRedirect()
        ->assertSessionHasNoErrors()
        ->assertSessionHasFlash('success', 'College updated successfully!');

        $this->assertEquals(1, College::count());
        $this->assertEquals($new_code, $college->fresh()->code);
    }

    /** @test */
    public function colleges_principal_name_cannot_be_updated_to_null()
    {
        $this->signIn();

        $college = create(College::class);

        $this->withExceptionHandling()
            ->patch(route('staff.colleges.update', $college), [
                'principal_name' => '',
            ])
            ->assertSessionHasErrorsIn('principal_name');

        $this->assertEquals($college->principal_name, $college->fresh()->principal_name);
    }
}
