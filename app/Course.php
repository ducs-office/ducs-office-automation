<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $guarded = [];

    public function programme()
    {
        return $this->belongsTo(Programme::class, 'programme_id');
    }
}
