<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScholarAppeal extends Model
{
    protected $guarded = [];

    protected $dates = [
        'applied_on',
        'response_date',
    ];

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }
}
