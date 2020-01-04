<?php

namespace App\Policies;

use App\College;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CollegePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any colleges.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('colleges:view');
    }

    /**
     * Determine whether the user can view the college.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->can('colleges:view');
    }

    /**
     * Determine whether the user can create colleges.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('colleges:create');
    }

    /**
     * Determine whether the user can update the college.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->can('colleges:edit');
    }

    /**
     * Determine whether the user can delete the college.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->can('colleges:delete');
    }
}
