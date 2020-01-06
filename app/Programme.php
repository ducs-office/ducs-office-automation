<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    protected $guarded = [];

    public function courses()
    {
        return $this->hasMany('Course');
    }
}
