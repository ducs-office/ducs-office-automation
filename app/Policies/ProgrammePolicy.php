<?php

namespace App\Policies;

use App\Programme;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProgrammePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any programmes.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('view programmes');
    }

    /**
     * Determine whether the user can view the programme.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->can('view programmes');
    }

    /**
     * Determine whether the user can create programmes.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create programmes');
    }

    /**
     * Determine whether the user can update the programme.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->can('edit programmes');
    }

    /**
     * Determine whether the user can delete the programme.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->can('delete programmes');
    }
}
