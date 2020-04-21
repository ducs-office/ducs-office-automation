<?php

namespace App\Policies;

use App\Models\PhdCourse;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PhdCoursePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any phd courses.
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('courses:view');
    }

    /**
     * Determine whether the user can view the phd course.
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function view(User $user, PhdCourse $phdCourse)
    {
        return $user->can('courses:view');
    }

    /**
     * Determine whether the user can create phd courses.
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('courses:create');
    }

    /**
     * Determine whether the user can update the phd course.
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->can('courses:edit');
    }

    /**
     * Determine whether the user can delete the phd course.
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->can('courses:edit');
    }
}
