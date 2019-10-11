<?php

namespace Tests\Feature;

use App\OutgoingLetter;
use App\Remark;
use App\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\str;


class StoreRemarkTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    /** @test */
    public function guest_cannot_store_remark()
    {
        $outgoingletter = factory(OutgoingLetter::class)->create();
    
        $this->withExceptionHandling()
            ->post('/remarks')
            ->assertRedirect('/login');
    
        $this->assertEquals(0, Remark::count());
    }
    
    /** @test */
    public function user_can_store_remark()
    {
        $this->be(factory(User::class)->create());
        $letter = factory(OutgoingLetter::class)->create();
        
        $this->withoutExceptionHandling()
            ->post('/remarks', [
                'description'=>'Not received by University',
                'letter_id' => $letter->id
            ]);
        
        $this->assertEquals(1, Remark::count());
    }

     /** @test */
    public function request_validates_description_field_cannot_be_null()
    {
        $this->be(factory(User::class)->create());
        $letter = factory(OutgoingLetter::class)->create();
        $remark = ['description'=>''];

        try{
        $this->withoutExceptionHandling()
            ->post("/outgoing-letters/$letter->id/remarks",$remark);
        }catch(ValidationException $e){
            $this->assertArrayHasKey('description',$e->errors());
        }

        $this->assertEquals(0,Remark::count());
            
    }

     /** @test */
    public function request_validates_description_field_minlimit_10()
    {
        $this->be(factory(User::class)->create());
        $letter = factory(OutgoingLetter::class)->create();
        $remark = ['description'=>Str::random(9)];

        try{
            $this->withoutExceptionHandling()
                ->post("outgoing-letters/$letter->id/remarks",$remark);
        }catch(ValidationException $e){
            $this->assertArrayHasKey('description',$e->errors());
        }

        $this->assertEquals(0,Remark::count());
    }

     /** @test */
    public function request_validates_description_field_maxlimit_255()
    {
        $this->be(factory(User::class)->create());
        $letter = factory(OutgoingLetter::class)->create();
        $remark = ['description' => Str::random(256)];

        try{
            $this->withoutExceptionHandling()
                ->post("outgoing-letters/$letter->id/remarks",$remark);
        }catch(ValidationException $e){
            $this->assertArrayHasKey('description',$e->errors());
        }

        $this->assertEquals(0,Remark::count());
    }
}
