<?php

namespace Tests\Unit;

use App\OutgoingLetterLog;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OutgoingLetterLogsTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function date_attribute_is_casted_to_carbon_instance()
    {
        $letter_log = factory(OutgoingLetterLog::class)->make(['date' => '2016-08-08']);

        $this->assertInstanceOf(Carbon::class, $letter_log->date);
        $this->assertEquals('2016-08-08', $letter_log->date->format('Y-m-d'));
    }

    /** @test */
    public function letter_has_an_associated_sender_who_is_a_user()
    {
        $jack = factory(User::class)->create();
        $letter_log = factory(OutgoingLetterLog::class)->create(['sender_id' => $jack->id]);

        $this->assertInstanceOf(BelongsTo::class, $letter_log->sender(), 'sender method was expected to return a belongsTo relation');
        $this->assertInstanceOf(User::class, $letter_log->sender);
        $this->assertTrue($letter_log->sender->is($jack));
    }
}
