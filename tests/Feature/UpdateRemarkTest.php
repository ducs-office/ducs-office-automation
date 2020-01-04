<?php

namespace Tests\Feature;

use App\Remark;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UpdateRemarkTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_update_remark()
    {
        $remark = create(Remark::class, 1, ['description'=>'Received by University']);

        $this->withExceptionHandling()
        ->patch("remarks/$remark->id", ['description'=>'Not received by University'])
        ->assertRedirect('/login');

        $this->assertEquals($remark->description, $remark->fresh()->description);
    }

    /** @test */
    public function user_can_update_remark()
    {
        $this->signIn(create(User::class), 'admin');

        $remark = create(Remark::class, 1, [
            'description'=>'Received by University',
            'user_id' => auth()->id()
        ]);

        $this->withoutExceptionHandling()
            ->patch("remarks/$remark->id", [
                'description'=> $newDesc = 'Not received by University'
            ]);

        $this->assertEquals($newDesc, $remark->fresh()->description);
    }

    /** @test */
    public function request_validates_description_field_cannot_be_null()
    {
        $this->signIn(create(User::class), 'admin');

        $remark = create(Remark::class, 1, [
            'description' => 'Received by University',
            'user_id' => auth()->id()
        ]);

        $new_remark = ['description'=> ''];

        try {
            $this->withoutExceptionHandling()
            ->patch("/remarks/{$remark->id}", $new_remark)
            ->assertSuccessful();
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('description', $e->errors());
        }

        $this->assertEquals($remark->description, $remark->fresh()->description);
    }

    /** @test */
    public function request_validates_description_field_minlimit_2()
    {
        $this->signIn(create(User::class), 'admin');

        $remark = create(Remark::class, 1, [
            'description'=>'Received by University',
            'user_id' => auth()->id()
        ]);

        $new_remark = ['description'=>Str::random(1)];

        try {
            $this->withoutExceptionHandling()
            ->patch("remarks/$remark->id", $new_remark);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('description', $e->errors());
        }

        $this->assertEquals($remark->description, $remark->fresh()->description);
    }

    /** @test */
    public function request_validates_description_field_maxlimit_255()
    {
        $this->signIn(create(User::class), 'admin');

        $remark = create(Remark::class, 1, [
            'description'=>'Received by University',
            'user_id' => auth()->id()
        ]);

        $new_remark = ['description'=>Str::random(256)];

        try {
            $this->withoutExceptionHandling()
            ->patch("remarks/$remark->id", $new_remark);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('description', $e->errors());
        }

        $this->assertEquals($remark->description, $remark->fresh()->description);
    }
}
