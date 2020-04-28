<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScholarDocument extends Model
{
    protected $guarded = [];

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }
}
