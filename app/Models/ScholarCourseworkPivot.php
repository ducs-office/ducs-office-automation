<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot as Pivot;

class ScholarCourseworkPivot extends Pivot
{
    protected $table = 'phd_course_scholar';

    protected $dates = [
        'completed_on',
    ];
}
