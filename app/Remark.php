<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\OutgoingLetter;

class Remark extends Model
{
    protected $guarded = [];

    public function letter()
    {
        return $this->belongsTo(OutgoingLetter::class, 'letter_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
