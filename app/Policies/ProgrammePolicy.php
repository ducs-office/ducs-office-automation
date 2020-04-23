<?php

namespace App\Policies;

use App\Models\Programme;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProgrammePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any programmes.
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('programmes:view');
    }

    /**
     * Determine whether the user can view the programme.
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->can('programmes:view');
    }

    /**
     * Determine whether the user can create programmes.
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('programmes:create');
    }

    /**
     * Determine whether the user can update the programme.
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->can('programmes:edit');
    }

    /**
     * Determine whether the user can delete the programme.
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->can('programmes:delete');
    }
}
