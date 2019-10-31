<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\OutgoingLetter;

class Remark extends Model
{
    protected $guarded = [];

    public function remarkable() 
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
