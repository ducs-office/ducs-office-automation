<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\College;
use App\Programme;
use Illuminate\Validation\ValidationException;

class CreateCollegeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /** @test */
    public function admin_can_create_new_college()
    {
        $this->signIn();

        $this->post('/colleges', [
            'code' => 'DU-KMV-21',
            'name' => 'Keshav Mahavidyalaya',
            'programmes' => [create(Programme::class)->id],
        ])->assertRedirect('/colleges')
        ->assertSessionHasFlash('success', 'College created successfully!');

        
        $this->assertEquals(1, College::count());
    }

    /** @test */
    public function request_validates_code_field_is_not_null()
    {
        $this->signIn();

        try {
            $this->withoutExceptionHandling()
            ->post('/colleges', [
            'code' => '',
            'name' => 'Keshav Mahavidyalaya',
            'programmes' => [create(Programme::class)->id],
            ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('code', $e->errors());
        }

        $this->assertEquals(0, College::count());
    }

    /** @test */
    public function request_validates_code_field_is_unique_value()
    {
        $this->signIn();

        $college_code = create(College::class)->code;

        try {
            $this->withoutExceptionHandling()
            ->post('/colleges', [
            'code' => $college_code,
            'name' => 'Keshav Mahavidyalaya',
            'programmes' => [create(Programme::class)->id],
            ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('code', $e->errors());
        }

        $this->assertEquals(1, College::count());
    }

    /** @test */
    public function request_validates_name_field_is_not_null()
    {
        $this->signIn();

        try {
            $this->withoutExceptionHandling()
            ->post('/colleges', [
            'code' => 'DU-KMV-21',
            'name' => '',
            'programmes' => [create(Programme::class)->id],
            ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('name', $e->errors());
        }

        $this->assertEquals(0, College::count());
    }

    /** @test */
    public function request_validates_name_field_is_unique_value()
    {
        $this->signIn();

        $college_name = create(College::class)->name;

        try {
            $this->withoutExceptionHandling()
            ->post('/colleges', [
            'code' => 'DU-KMV-21',
            'name' =>  $college_name,
            'programmes' => [create(Programme::class)->id],
            ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('name', $e->errors());
        }

        $this->assertEquals(1, College::count());
    }

    /** @test */
    public function request_validates_programmes_field_is_not_null()
    {
        $this->signIn();

        try {
            $this->withoutExceptionHandling()
            ->post('/colleges', [
            'code' => 'DU-KMV-21',
            'name' => 'Keshav Mahavidyalya',
            'programmes' => '',
            ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('programmes', $e->errors());
        }

        $this->assertEquals(0, College::count());
    }

    /** @test */
    public function request_validates_programmes_field_is_existing_programme()
    {
        $this->signIn();

        try {
            $this->withoutExceptionHandling()
            ->post('/colleges', [
            'code' => 'DU-KMV-21',
            'name' => 'Keshav Mahavidyalya',
            'programmes' => '[25]',
            ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('programmes', $e->errors());
        }

        $this->assertEquals(0, College::count());
    }
}
