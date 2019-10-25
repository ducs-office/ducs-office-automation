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
        $outgoingletter = create(OutgoingLetter::class);
    
        $this->withExceptionHandling()
            ->post('/remarks')
            ->assertRedirect('/login');
    
        $this->assertEquals(0, Remark::count());
    }
    
    /** @test */
    public function user_can_store_remark()
    {
        $this->signIn();
        
        $letter = create(OutgoingLetter::class);
        
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
        $this->signIn();
        
        $letter = create(OutgoingLetter::class);
        $remark = ['description'=>''];

        try{
        $this->withoutExceptionHandling()
            ->post('/remarks',$remark);
        }catch(ValidationException $e){
            $this->assertArrayHasKey('description',$e->errors());
        }

        $this->assertEquals(0,Remark::count());
            
    }

     /** @test */
    public function request_validates_description_field_minlimit_10()
    {
        $this->signIn();
        
        $letter = create(OutgoingLetter::class);
        $remark = ['description'=>Str::random(9)];

        try{
            $this->withoutExceptionHandling()
                ->post('/remarks',$remark);
        }catch(ValidationException $e){
            $this->assertArrayHasKey('description',$e->errors());
        }

        $this->assertEquals(0,Remark::count());
    }

     /** @test */
    public function request_validates_description_field_maxlimit_255()
    {
        $this->signIn();
        
        $letter = create(OutgoingLetter::class);
        $remark = ['description' => Str::random(256)];

        try{
            $this->withoutExceptionHandling()
                ->post('/remarks',$remark);
        }catch(ValidationException $e){
            $this->assertArrayHasKey('description',$e->errors());
        }

        $this->assertEquals(0,Remark::count());
    }

    /** @test */
    public function request_validates_letter_id_field_cannot_be_null()
    {
        $this->signIn();
        
        $remark = make(Remark::class, 1, ['letter_id'=>'']);

        try{
            $this->withoutExceptionHandling()
                ->post('/remarks', $remark->toArray());
        }catch(ValidationException $e){
            $this->assertArrayHasKey('letter_id', $e->errors());
        }

        $this->assertEquals(0,Remark::count());
    }

    /** @test */
    public function request_validates_letter_id_field_cannot_be_string()
    {
        $this->signIn();
        
        $remark = make(Remark::class, 1, ['letter_id' => 'string']);

        try{
            $this->withoutExceptionHandling()
                ->post('/remarks', $remark->toArray());
        }catch(ValidationException $e){
            $this->assertArrayHasKey('letter_id', $e->errors());
        }
        
        $this->assertEquals(0, Remark::count());
    }

    /** @test */
    public function request_validates_letter_id_field_must_be_existing_outgoing_letter()
    {
        $this->signIn();
        
        $remark = make(Remark::class, 1, ['letter_id' => 123]);

        try{
            $this->withoutExceptionHandling()
                ->post('/remarks', $remark->toArray());
        }catch(ValidationException $e){
            $this->assertArrayHasKey('letter_id', $e->errors());
        }

        $this->assertEquals(0, Remark::count());
    }
}
