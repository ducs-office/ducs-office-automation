<?php

namespace App\Models\Pivot;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ScholarAdvisor extends Pivot
{
    protected $table = 'advisor_scholar';

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
