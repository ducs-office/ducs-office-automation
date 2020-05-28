<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CourseRevisionPolicy
{
    use HandlesAuthorization;

    public function view($user)
    {
        return $user->can('view', Course::class);
    }

    public function update($user)
    {
        return $user->can('update', Course::class);
    }

    public function delete($user)
    {
        return $user->can('delete', Course::class);
    }
}
