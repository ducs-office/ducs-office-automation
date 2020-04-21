<?php

namespace App\Policies;

use App\Models\Teacher;
use App\Models\TeachingRecord;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeachingRecordPolicy
{
    use HandlesAuthorization;

    public function create(Teacher $teacher)
    {
        return TeachingRecord::canSubmit($teacher);
    }
}
