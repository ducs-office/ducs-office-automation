<?php

namespace App\Policies;

use App\CollegeTeacher;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CollegeTeacherPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any college teachers.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('college teachers:view');
    }

    /**
     * Determine whether the user can view the college teacher.
     *
     * @param  \App\User  $user
     * @param  \App\CollegeTeacher  $collegeTeacher
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->can('college teachers:view');
    }

    /**
     * Determine whether the user can create college teachers.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('college teachers:create');
    }

    /**
     * Determine whether the user can update the college teacher.
     *
     * @param  \App\User  $user
     * @param  \App\CollegeTeacher  $collegeTeacher
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->can('college teachers:edit');
    }

    /**
     * Determine whether the user can delete the college teacher.
     *
     * @param  \App\User  $user
     * @param  \App\CollegeTeacher  $collegeTeacher
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->can('college teachers:delete');
    }
}
