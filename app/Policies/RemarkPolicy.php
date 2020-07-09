<?php

namespace App\Policies;

use App\Models\Remark;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RemarkPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any letter remarks.
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('letter remarks:view');
    }

    /**
     * Determine whether the user can view the remark.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Remark  $remark
     *
     * @return mixed
     */
    public function view(User $user, Remark $remark)
    {
        return $user->can('letter remarks:view');
    }

    /**
     * Determine whether the user can create letter remarks.
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('letter remarks:create');
    }

    /**
     * Determine whether the user can update the remark.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Remark  $remark
     *
     * @return mixed
     */
    public function update(User $user, Remark $remark)
    {
        return $user->can('letter remarks:edit')
            && (int) $remark->user_id === (int) $user->id;
    }

    /**
     * Determine whether the user can delete the remark.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Remark  $remark
     *
     * @return mixed
     */
    public function delete(User $user, Remark $remark)
    {
        return $user->can('letter remarks:delete')
            && (int) $remark->user_id === (int) $user->id;
    }
}
