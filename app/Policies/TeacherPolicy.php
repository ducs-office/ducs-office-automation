<?php

namespace App\Policies;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeacherPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any teachers.
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('teachers:view');
    }

    /**
     * Determine whether the user can view the teacher.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Teacher  $teacher
     *
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->can('teachers:view');
    }

    /**
     * Determine whether the user can create teachers.
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('teachers:create');
    }

    /**
     * Determine whether the user can update the teacher.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Teacher  $teacher
     *
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->can('teachers:edit');
    }

    /**
     * Determine whether the user can delete the teacher.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Teacher  $teacher
     *
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->can('teachers:delete');
    }
}
