<?php

namespace App\Policies;

use App\Teacher;
use App\PastTeachersProfile;
use Illuminate\Auth\Access\HandlesAuthorization;

class PastTeachersProfilePolicy
{
    use HandlesAuthorization;

    public function create(Teacher $teacher)
    {
        return PastTeachersProfile::canSubmit($teacher);
    }
}
