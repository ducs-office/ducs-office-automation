<?php

namespace App\Models\Pivot;

use App\Models\PhdCourse;
use Illuminate\Database\Eloquent\Relations\Pivot as Pivot;

class ScholarCoursework extends Pivot
{
    protected $table = 'phd_course_scholar';

    protected $dates = [
        'completed_on',
    ];
}
