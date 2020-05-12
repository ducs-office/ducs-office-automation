<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ScholarSupervisor extends Pivot
{
    public $timestamps = false;
    protected $casts = [
        'started_on' => 'datetime',
        'ended_on' => 'datetime',
    ];
}
