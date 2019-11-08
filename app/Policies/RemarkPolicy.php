<?php

namespace App\Policies;

use App\Remark;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RemarkPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any remarks.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('view remarks');
    }

    /**
     * Determine whether the user can view the remark.
     *
     * @param  \App\User  $user
     * @param  \App\Remark  $remark
     * @return mixed
     */
    public function view(User $user, Remark $remark)
    {
        return $user->can('view remarks');
    }

    /**
     * Determine whether the user can create remarks.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create remarks');
    }

    /**
     * Determine whether the user can update the remark.
     *
     * @param  \App\User  $user
     * @param  \App\Remark  $remark
     * @return mixed
     */
    public function update(User $user, Remark $remark)
    {
        return $user->can('edit remarks')
            && $remark->user_id == $user->id;
    }

    /**
     * Determine whether the user can delete the remark.
     *
     * @param  \App\User  $user
     * @param  \App\Remark  $remark
     * @return mixed
     */
    public function delete(User $user, Remark $remark)
    {
        return $user->can('delete remarks')
            && $remark->user_id == $user->id;
    }
}
