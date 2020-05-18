<?php

namespace App\Models\Pivot;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ScholarCosupervisor extends Pivot
{
    protected $table = 'cosupervisor_scholar';

    protected $fillable = [
        'scholar_id', 'user_id',
        'started_on', 'ended_on',
    ];

    protected $casts = [
        'started_on' => 'datetime',
        'ended_on' => 'datetime',
    ];

    public $timestamps = false;
}
