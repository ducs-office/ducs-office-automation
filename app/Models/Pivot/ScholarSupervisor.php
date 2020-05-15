<?php

namespace App\Models\Pivot;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ScholarSupervisor extends Pivot
{
    public $timestamps = false;
    protected $casts = [
        'started_on' => 'datetime',
        'ended_on' => 'datetime',
    ];
}
