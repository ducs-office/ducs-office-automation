<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LetterReminder extends Model
{
    protected $guarded = [];

    public function letters() 
    {
        return $this->belongsTo(OutgoingLetter::class, 'letter_id');
    }
}
