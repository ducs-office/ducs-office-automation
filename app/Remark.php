<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\OutgoingLetter;

class Remark extends Model
{
    //
    protected $guarded = [];

    public function letter()
    {
        return $this->belongsTo(OutgoingLetter::class,'id');
    }
}
