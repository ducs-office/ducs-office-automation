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
         $remark=['description'=>'Not received by University'];
        
         $this->withExceptionHandling()
            ->post("/outgoing-letters/{$outgoingletter->id}/remarks", $remark)
            ->assertRedirect('/login');
        
        $this->assertEquals(0,Remark::count());
     }
    
     /** @test */
     public function user_can_store_remark()
     {
        $this->be(factory(User::class)->create());
        $letter = factory(OutgoingLetter::class)->create();
        $remark=['description'=>'Not received by University'];
        
        $this->withoutExceptionHandling()
            ->post("/outgoing-letters/{$letter->id}/remarks", $remark);
        
        $this->assertEquals(1,Remark::count());
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
         $remark = ['description'=>Str::random(256)];

         try{
             $this->withoutExceptionHandling()
                ->post("outgoing-letters/$letter->id/remarks",$remark);
         }catch(ValidationException $e){
            $this->assertArrayHasKey('description',$e->errors());
         }

         $this->assertEquals(0,Remark::count());
     }
}
