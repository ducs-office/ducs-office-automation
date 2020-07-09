<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('users:view');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->can('users:view');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('users:create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->can('users:edit');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function updateProfile(User $user, User $userModel)
    {
        return (int) $user->id === (int) $userModel->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function delete(User $user, User $userModel)
    {
        return $user->can('users:delete')
            && (int) $userModel->id !== (int) $user->id;
    }

    /**
     * Determine whether the user can replace scholars' supervisor & cosupervisor
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function replaceScholarSupervisorAndCosupervisor(User $user)
    {
        return $user->can('scholar supervisor and cosupervisor:replace');
    }
}
