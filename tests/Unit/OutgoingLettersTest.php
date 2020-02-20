<?php

namespace Tests\Unit;

use App\OutgoingLetter;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OutgoingLettersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function date_attribute_is_casted_to_carbon_instance()
    {
        $letter = make(OutgoingLetter::class, 1, ['date' => '2016-08-08']);

        $this->assertInstanceOf(Carbon::class, $letter->date);
        $this->assertEquals('2016-08-08', $letter->date->format('Y-m-d'));
    }

    /** @test */
    public function letter_has_an_associated_sender_who_is_a_user()
    {
        $jack = create(User::class);
        $letter = create(OutgoingLetter::class, 1, ['sender_id' => $jack->id]);

        $this->assertInstanceOf(BelongsTo::class, $letter->sender(), 'sender method was expected to return a belongsTo relation');
        $this->assertInstanceOf(User::class, $letter->sender);
        $this->assertTrue($letter->sender->is($jack));
    }
}
