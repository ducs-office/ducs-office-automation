<?php

namespace App\Policies;

use App\IncomingLetter;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IncomingLetterPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any incoming letters.
     *
     * @param  \App\User  $user
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('incoming letters:view');
    }

    /**
     * Determine whether the user can view the incoming letter.
     *
     * @param  \App\User  $user
     * @param  \App\IncomingLetter  $incomingLetter
     *
     * @return mixed
     */
    public function view(User $user, IncomingLetter $incomingLetter)
    {
        return $user->can('incoming letters:view');
    }

    /**
     * Determine whether the user can create incoming letters.
     *
     * @param  \App\User  $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('incoming letters:create');
    }

    /**
     * Determine whether the user can update the incoming letter.
     *
     * @param  \App\User  $user
     * @param  \App\IncomingLetter  $incomingLetter
     *
     * @return mixed
     */
    public function update(User $user, IncomingLetter $incomingLetter)
    {
        return $user->can('incoming letters:edit')
            && (int) $incomingLetter->creator_id === (int) $user->id;
    }

    /**
     * Determine whether the user can delete the incoming letter.
     *
     * @param  \App\User  $user
     * @param  \App\IncomingLetter  $incomingLetter
     *
     * @return mixed
     */
    public function delete(User $user, IncomingLetter $incomingLetter)
    {
        return $user->can('incoming letters:delete')
            && (int) $incomingLetter->creator_id === (int) $user->id;
    }
}
