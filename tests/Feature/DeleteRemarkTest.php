<?php

namespace Tests\Feature;

use App\Remark;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteRemarkTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_delete_remark()
    {
        $remark = factory(Remark::class)->create();

        $this->withExceptionHandling()
            ->delete("/remarks/$remark->id")
            ->assertRedirect('/login');
        
        $this->assertEquals(1,Remark::count());
    }

    /** @test */
    public function user_can_delete_remark()
    {
        $this->be(factory(User::class)->create());
        $remark = factory(Remark::class)->create();

        $this->withoutExceptionHandling()
            ->delete("/remarks/$remark->id");
        
        $this->assertEquals(0,Remark::count());
    }

}
