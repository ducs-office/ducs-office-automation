<?php

namespace App\Models;

use App\Types\UserCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Str;

class ScholarCosupervisor extends Pivot
{
    public $timestamps = false;

    protected $casts = [
        'started_on' => 'datetime',
        'ended_on' => 'datetime',
    ];
}
