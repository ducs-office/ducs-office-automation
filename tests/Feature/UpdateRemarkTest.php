<?php

namespace Tests\Feature;

use App\Remark;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

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
        ->patch("remarks/$remark->id",['description'=>'Not received by University'])
        ->assertRedirect('/login');
    
        $this->assertEquals($remark->description,$remark->fresh()->description);
    }

     /** @test */
    public function user_can_update_remark()
    {
        $this->be(create(User::class));
        $remark = create(Remark::class, 1, ['description'=>'Received by University']);
        $new_remark = ['description'=>'Not received by University'];

        $this->withoutExceptionHandling()
            ->patch("remarks/$remark->id",$new_remark);
        
        $this->assertEquals($new_remark['description'],$remark->fresh()->description);
    }

     /** @test */
    public function request_validates_description_field_cannot_be_null()
    {
        $this->be(create(User::class));
        $remark = create(Remark::class, 1, ['description'=>'Received by University']);
        $new_remark = ['description'=> ''];

        try{
        $this->withoutExceptionHandling()
            ->patch("/remarks/{$remark->id}",$new_remark)
            ->assertSuccessful();
        }catch(ValidationException $e){
            $this->assertArrayHasKey('description',$e->errors());
        }

        $this->assertEquals($remark->description,$remark->fresh()->description);
        
    }

     /** @test */
    public function request_validates_description_field_minlimit_10()
    {
        $this->be(create(User::class));
        $remark = create(Remark::class, 1, ['description'=>'Received by University']);
        $new_remark = ['description'=>Str::random(9)];

        try{
        $this->withoutExceptionHandling()
            ->patch("remarks/$remark->id",$new_remark);
        }catch(ValidationException $e){
            $this->assertArrayHasKey('description',$e->errors());
        }

        $this->assertEquals($remark->description,$remark->fresh()->description);
    }

     /** @test */
    public function request_validates_description_field_maxlimit_255()
    {
    $this->be(create(User::class));
        $remark = create(Remark::class, 1, ['description'=>'Received by University']);
        $new_remark = ['description'=>Str::random(256)];

        try{
        $this->withoutExceptionHandling()
            ->patch("remarks/$remark->id",$new_remark);
        }catch(ValidationException $e){
            $this->assertArrayHasKey('description',$e->errors());
        }

        $this->assertEquals($remark->description,$remark->fresh()->description);
    }
}
