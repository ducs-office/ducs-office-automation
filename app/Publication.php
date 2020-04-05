<?php

namespace App;

use App\Presentation;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    protected $guarded = [];

    public function presentations()
    {
        return $this->hasMany(Presentation::class);
    }
}
