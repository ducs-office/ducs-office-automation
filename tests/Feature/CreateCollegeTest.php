<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\College;
use App\Programme;

class CreateCollegeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * creates array of form fields, this is a replacement for factory's make function
     * `make()` tends to go through the model's accessors and mutators
     *
     * @param array $overrides
     * @return array
     */
    protected function fillCollegeFormFields($overrides = [])
    {
        return $this->mergeFormFields([
            'code' => 'DU/ANDC/01',
            'name' => 'Acharaya Narendra Dev College',
            'programmes' => function () {
                return create(Programme::class, 3)->pluck('id')->toArray();
            }
        ], $overrides);
    }

    /** @test */
    public function admin_can_create_new_college()
    {
        $this->signIn();

        $this->withoutExceptionHandling()
            ->from('/colleges')
            ->post('/colleges', $this->fillCollegeFormFields())
            ->assertRedirect('/colleges')
            ->assertSessionHasNoErrors()
            ->assertSessionHasFlash('success', 'College created successfully!');

        $this->assertEquals(1, College::count());
    }

    /** @test */
    public function request_validates_code_field_is_not_null()
    {
        $this->signIn();

        $params = $this->fillCollegeFormFields(['code' => '']);

        $this->withExceptionHandling()
            ->post('/colleges', $params)
            ->assertSessionHasErrorsIn('code');

        $this->assertEquals(0, College::count());
    }

    /** @test */
    public function request_validates_code_field_is_unique_value()
    {
        $this->signIn();

        $existing_college_code = create(College::class)->code;
        $params = $this->fillCollegeFormFields(['code' => $existing_college_code]);

        $this->withExceptionHandling()
            ->post('/colleges', $params)
            ->assertSessionHasErrorsIn('code');

        $this->assertEquals(1, College::count());
    }

    /** @test */
    public function request_validates_name_field_is_not_null()
    {
        $this->signIn();

        $params = $this->fillCollegeFormFields(['name' => '']);

        $this->withExceptionHandling()
            ->post('/colleges', $params)
            ->assertSessionHasErrorsIn('name');

        $this->assertEquals(0, College::count());
    }

    /** @test */
    public function request_validates_name_field_is_unique_value()
    {
        $this->signIn();

        $existing_college_name = create(College::class)->name;
        $params = $this->fillCollegeFormFields(['name' => $existing_college_name]);

        $this->withExceptionHandling()
            ->post('/colleges', $params)
            ->assertSessionHasErrorsIn('name');

        $this->assertEquals(1, College::count());
    }

    /** @test */
    public function request_validates_programmes_field_is_not_null()
    {
        $this->signIn();

        $params = $this->fillCollegeFormFields([
            'programmes' => []
        ]);

        $this->withExceptionHandling()
            ->post('/colleges', $params)
            ->assertSessionHasErrorsIn('programmes');

        $this->assertEquals(0, College::count());
    }

    /** @test */
    public function request_validates_programmes_field_is_existing_programme()
    {
        $this->signIn();

        $params = $this->fillCollegeFormFields([
            'programmes' => [123432, 321323]
        ]);

        $this->withExceptionHandling()
            ->post('/colleges', $params)
            ->assertSessionHasErrorsIn('programmes');

        $this->assertEquals(0, College::count());
    }
}
