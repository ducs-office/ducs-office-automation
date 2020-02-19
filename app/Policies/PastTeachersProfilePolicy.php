<?php

namespace App\Policies;

use App\PastTeachersProfile;
use App\Teacher;
use Illuminate\Auth\Access\HandlesAuthorization;

class PastTeachersProfilePolicy
{
    use HandlesAuthorization;

    public function create(Teacher $teacher)
    {
        return PastTeachersProfile::canSubmit($teacher);
    }
}
