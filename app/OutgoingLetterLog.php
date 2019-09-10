<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OutgoingLetterLog extends Model
{
    protected $guarded = [];

    protected $dates = ['date'];

    protected $dateFormat = 'Y-m-d';

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
