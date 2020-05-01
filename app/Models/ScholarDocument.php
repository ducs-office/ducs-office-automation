<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScholarDocument extends Model
{
    protected $guarded = [];

    protected $dates = ['date'];

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }
}
