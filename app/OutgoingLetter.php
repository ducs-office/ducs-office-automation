<?php

namespace App;

use App\Model;

class OutgoingLetter extends Model
{   
    protected $guarded = [];

    protected $dates = ['date'];

    protected $allowedFilters = [
        'date' => 'less_than', 
        'type' => 'equals', 
        'recipient' => 'equals',
        'creator_id' => 'equals',
        'sender_id' => 'equals'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class,'creator_id');
    }
}
