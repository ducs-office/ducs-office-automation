<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot as Pivot;

class ScholarCourseworkPivot extends Pivot
{
    protected $casts = [
        'completed_at' => 'datetime',
    ];
}
