<?php

namespace Tests\Feature;

use App\Models\College;
use App\Models\Programme;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CreateCollegeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * creates array of form fields, this is a replacement for factory's make function
     * `make()` tends to go through the model's accessors and mutators
     *
     * @param array $overrides
     *
     * @return array
     */
    protected function fillCollegeFormFields($overrides = [])
    {
        return $this->mergeFormFields([
            'code' => 'DU/ANDC/01',
            'name' => 'Acharaya Narendra Dev College',
            'principal_name' => 'Dr. Ravi Toteja',
            'principal_phones' => ['9876543210', '7654321098'],
            'principal_emails' => ['principal@andcollege.du.ac.in', 'ravi_toteja@gmail.com'],
            'address' => 'Govindpuri, Kalkaji, New Delhi - 110019',
            'website' => 'http://andcollege.du.ac.in',
            'programmes' => static function () {
                return create(Programme::class, 3)->pluck('id')->toArray();
            },
        ], $overrides);
    }

    /** @test */
    public function admin_can_create_new_college()
    {
        $this->signIn(create(User::class, 1, ['college_id' => null]));

        $params = $this->fillCollegeFormFields();

        $this->withoutExceptionHandling()
            ->post(route('staff.colleges.store'), $params)
            ->assertRedirect()
            ->assertSessionHasNoErrors()
            ->assertSessionHasFlash('success', 'College created successfully!');

        $this->assertNotNull($college = College::first());
        $this->assertEquals($params['code'], $college->code);
        $this->assertEquals($params['name'], $college->name);
        $this->assertEquals($params['principal_name'], $college->principal_name);
        $this->assertEquals($params['principal_phones'], $college->principal_phones);
        $this->assertEquals($params['principal_emails'], $college->principal_emails);
        $this->assertEquals($params['address'], $college->address);
        $this->assertEquals($params['website'], $college->website);
        $this->assertSame($params['programmes'], $college->programmes->pluck('id')->toArray());
    }

    /** @test */
    public function request_validates_code_field_is_not_null()
    {
        $this->signIn(create(User::class, 1, ['college_id' => null]));

        $params = $this->fillCollegeFormFields(['code' => '']);

        $this->withExceptionHandling()
            ->post(route('staff.colleges.store'), $params)
            ->assertSessionHasErrorsIn('code');

        $this->assertEquals(0, College::count());
    }

    /** @test */
    public function request_validates_code_field_is_unique_value()
    {
        $this->signIn(create(User::class, 1, ['college_id' => null]));

        $existing_college_code = create(College::class)->code;
        $params = $this->fillCollegeFormFields(['code' => $existing_college_code]);

        $this->withExceptionHandling()
            ->post(route('staff.colleges.store'), $params)
            ->assertSessionHasErrorsIn('code');

        $this->assertEquals(1, College::count());
    }

    /** @test */
    public function request_validates_name_field_is_not_null()
    {
        $this->signIn(create(User::class, 1, ['college_id' => null]));

        $params = $this->fillCollegeFormFields(['name' => '']);

        $this->withExceptionHandling()
            ->post(route('staff.colleges.store'), $params)
            ->assertSessionHasErrorsIn('name');

        $this->assertEquals(0, College::count());
    }

    /** @test */
    public function request_validates_name_field_is_unique_value()
    {
        $this->signIn(create(User::class, 1, ['college_id' => null]));

        $existing_college_name = create(College::class)->name;
        $params = $this->fillCollegeFormFields(['name' => $existing_college_name]);

        $this->withExceptionHandling()
            ->post(route('staff.colleges.store'), $params)
            ->assertSessionHasErrorsIn('name');

        $this->assertEquals(1, College::count());
    }

    /** @test */
    public function request_validates_programmes_field_is_not_null()
    {
        $this->signIn(create(User::class, 1, ['college_id' => null]));

        $params = $this->fillCollegeFormFields([
            'programmes' => [],
        ]);

        $this->withExceptionHandling()
            ->post(route('staff.colleges.store'), $params)
            ->assertSessionHasErrorsIn('programmes');

        $this->assertEquals(0, College::count());
    }

    /** @test */
    public function request_validates_programmes_field_is_existing_programme()
    {
        $this->signIn(create(User::class, 1, ['college_id' => null]));

        $params = $this->fillCollegeFormFields([
            'programmes' => [123432, 321323],
        ]);

        $this->withExceptionHandling()
            ->post(route('staff.colleges.store'), $params)
            ->assertSessionHasErrorsIn('programmes');

        $this->assertEquals(0, College::count());
    }

    /** @test */
    public function request_validates_principal_name_field_is_required()
    {
        $this->signIn(create(User::class, 1, ['college_id' => null]));

        $params = $this->fillCollegeFormFields([
            'principal_name' => '',
        ]);

        $this->withExceptionHandling()
            ->post(route('staff.colleges.store'), $params)
            ->assertSessionHasErrorsIn('principal_name');

        $this->assertEquals(0, College::count());
    }

    /** @test */
    public function request_validates_principal_phones_field_is_required()
    {
        $this->signIn(create(User::class, 1, ['college_id' => null]));

        $params = $this->fillCollegeFormFields([
            'principal_phones' => '',
        ]);

        $this->withExceptionHandling()
            ->post(route('staff.colleges.store'), $params)
            ->assertSessionHasErrorsIn('principal_phones');

        $this->assertEquals(0, College::count());
    }

    /** @test */
    public function request_validates_principal_phones_field_should_not_be_empty_array()
    {
        $this->signIn(create(User::class, 1, ['college_id' => null]));

        $params = $this->fillCollegeFormFields([
            'principal_phones' => [],
        ]);

        $this->withExceptionHandling()
            ->post(route('staff.colleges.store'), $params)
            ->assertSessionHasErrorsIn('principal_phones');

        $this->assertEquals(0, College::count());
    }

    /** @test */
    public function request_validates_principal_phones_every_item_is_10_digit_number()
    {
        $this->signIn(create(User::class, 1, ['college_id' => null]));

        $params = $this->fillCollegeFormFields([
            'principal_phones' => ['9876'],
        ]);

        $this->withExceptionHandling()
            ->post(route('staff.colleges.store'), $params)
            ->assertSessionHasErrorsIn('principal_phones');

        $this->assertEquals(0, College::count());
    }

    /** @test */
    public function request_validates_principal_emails_field_is_required()
    {
        $this->signIn(create(User::class, 1, ['college_id' => null]));

        $params = $this->fillCollegeFormFields([
            'principal_emails' => '',
        ]);

        $this->withExceptionHandling()
            ->post(route('staff.colleges.store'), $params)
            ->assertSessionHasErrorsIn('principal_emails');

        $this->assertEquals(0, College::count());
    }

    /** @test */
    public function request_validates_principal_emails_field_should_not_be_empty_array()
    {
        $this->signIn(create(User::class, 1, ['college_id' => null]));

        $params = $this->fillCollegeFormFields([
            'principal_emails' => [],
        ]);

        $this->withExceptionHandling()
            ->post(route('staff.colleges.store'), $params)
            ->assertSessionHasErrorsIn('principal_emails');

        $this->assertEquals(0, College::count());
    }

    /** @test */
    public function request_validates_principal_emails_every_item_is_10_digit_number()
    {
        $this->signIn(create(User::class, 1, ['college_id' => null]));

        $params = $this->fillCollegeFormFields([
            'principal_emails' => ['9876'],
        ]);

        $this->withExceptionHandling()
            ->post(route('staff.colleges.store'), $params)
            ->assertSessionHasErrorsIn('principal_emails');

        $this->assertEquals(0, College::count());
    }

    /** @test */
    public function request_validates_address_field_is_required()
    {
        $this->signIn(create(User::class, 1, ['college_id' => null]));

        $params = $this->fillCollegeFormFields([
            'address' => '',
        ]);

        $this->withExceptionHandling()
            ->post(route('staff.colleges.store'), $params)
            ->assertSessionHasErrorsIn('address');

        $this->assertEquals(0, College::count());
    }

    /** @test */
    public function request_validates_address_is_minmum_10_characters()
    {
        $this->signIn(create(User::class, 1, ['college_id' => null]));

        $params = $this->fillCollegeFormFields([
            'address' => Str::random(9),
        ]);

        $this->withExceptionHandling()
            ->post(route('staff.colleges.store'), $params)
            ->assertSessionHasErrorsIn('address');

        $this->assertEquals(0, College::count());
    }

    /** @test */
    public function request_validates_website_field_is_required()
    {
        $this->signIn(create(User::class, 1, ['college_id' => null]));

        $params = $this->fillCollegeFormFields([
            'website' => '',
        ]);

        $this->withExceptionHandling()
            ->post(route('staff.colleges.store'), $params)
            ->assertSessionHasErrorsIn('website');

        $this->assertEquals(0, College::count());
    }

    /** @test */
    public function request_validates_website_field_is_well_formed_url()
    {
        $this->signIn(create(User::class, 1, ['college_id' => null]));

        $params = $this->fillCollegeFormFields([
            'website' => 'without-protocol.com',
        ]);

        $this->withExceptionHandling()
            ->post(route('staff.colleges.store'), $params)
            ->assertSessionHasErrorsIn('website');

        $this->assertEquals(0, College::count());
    }
}
