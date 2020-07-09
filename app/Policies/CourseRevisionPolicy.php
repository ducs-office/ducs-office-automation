<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\CourseRevision;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CourseRevisionPolicy
{
    use HandlesAuthorization;

    public function view($user)
    {
        return $user->can('view', Course::class);
    }

    public function create($user)
    {
        return $user->can('update', Course::class);
    }

    public function update($user)
    {
        return $user->can('update', Course::class);
    }

    public function delete($user, CourseRevision $courseRevision)
    {
        return $user->can('update', Course::class)
            && $courseRevision->course->revisions->count() > 1;
    }
}
