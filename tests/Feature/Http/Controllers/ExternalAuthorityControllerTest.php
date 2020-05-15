<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\ExternalAuthority;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ExternalAuthorityController
 */
class ExternalAuthorityControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view()
    {
        $externalAuthorities = create(ExternalAuthority::class, 3);

        $this->get(route('external-authority.index'))
            ->assertOk()
            ->assertViewIs('external-authority.index')
            ->assertViewHas('externalAuthorities');
    }

    /**
     * @test
     */
    public function store_saves_and_redirects()
    {
        $name = $this->faker->name;
        $email = $this->faker->safeEmail;
        $designation = $this->faker->word;
        $affiliation = $this->faker->word;
        $phone = $this->faker->regexify('[6-9][0-9]{9}');

        $this->withoutExceptionHandling();
        $response = $this->post(route('external-authority.store'), [
            'name' => $name,
            'email' => $email,
            'designation' => $designation,
            'affiliation' => $affiliation,
            'phone' => $phone,
        ]);

        $response->assertRedirect(route('external-authority.index'));

        $externalAuthorities = ExternalAuthority::query()
            ->where('email', $email)
            ->get();

        $this->assertCount(1, $externalAuthorities);
        $externalAuthority = $externalAuthorities->first();

        $this->assertEquals($name, $externalAuthority->name);
        $this->assertEquals($designation, $externalAuthority->designation);
        $this->assertEquals($affiliation, $externalAuthority->affiliation);
        $this->assertEquals($phone, $externalAuthority->phone);
    }

    /**
     * @test
     */
    public function updates_and_redirects()
    {
        $externalAuthority = create(ExternalAuthority::class);

        $name = $this->faker->name;
        $email = $this->faker->safeEmail;
        $designation = $this->faker->word;
        $affiliation = $this->faker->word;
        $phone = $this->faker->regexify('[6-9][0-9]{9}');

        $this->withoutExceptionHandling();
        $response = $this->patch(route('external-authority.update', $externalAuthority), [
            'name' => $name,
            'email' => $email,
            'designation' => $designation,
            'affiliation' => $affiliation,
            'phone' => $phone,
        ]);

        $response->assertRedirect(route('external-authority.index'));

        $externalAuthority->refresh();

        $this->assertEquals($name, $externalAuthority->name);
        $this->assertEquals($designation, $externalAuthority->designation);
        $this->assertEquals($affiliation, $externalAuthority->affiliation);
        $this->assertEquals($phone, $externalAuthority->phone);
    }
}
