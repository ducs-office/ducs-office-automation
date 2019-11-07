<?php

namespace App\Policies;

use App\OutgoingLetter;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OutgoingLetterPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any outgoing letters.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('view outgoing letters');
    }

    /**
     * Determine whether the user can view the outgoing letter.
     *
     * @param  \App\User  $user
     * @param  \App\OutgoingLetter  $outgoingLetter
     * @return mixed
     */
    public function view(User $user, OutgoingLetter $outgoingLetter)
    {
        return $user->can('view outgoing letters');
    }

    /**
     * Determine whether the user can create outgoing letters.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create outgoing letters');
    }

    /**
     * Determine whether the user can update the outgoing letter.
     *
     * @param  \App\User  $user
     * @param  \App\OutgoingLetter  $outgoingLetter
     * @return mixed
     */
    public function update(User $user, OutgoingLetter $outgoingLetter)
    {
        return $user->can('edit outgoing letters')
            && $outgoingLetter->creator_id == $user->id;
    }

    /**
     * Determine whether the user can delete the outgoing letter.
     *
     * @param  \App\User  $user
     * @param  \App\OutgoingLetter  $outgoingLetter
     * @return mixed
     */
    public function delete(User $user, OutgoingLetter $outgoingLetter)
    {
        return $user->can('delete outgoing letters')
            && $outgoingLetter->creator_id == $user->id;
    }
}
