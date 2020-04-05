<?php

namespace App;

use App\Publication;
use Illuminate\Database\Eloquent\Model;

class Presentation extends Model
{
    protected $guarded = [];

    public function publication()
    {
        return $this->belongsTo(Publication::class);
    }
}
