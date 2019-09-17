<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OutgoingLetter extends Model
{
    protected $guarded = [];

    protected $dates = ['date'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class,'creator_id');
    }
}
