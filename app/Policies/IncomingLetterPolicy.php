<?php

namespace App\Policies;

use App\Models\IncomingLetter;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IncomingLetterPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any incoming letters.
     *
     * @param  \App\Models\User  $user
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
     * @param  \App\Models\User  $user
     * @param  \App\Models\IncomingLetter  $incomingLetter
     *
     * @return mixed
     */
    public function view(User $user, IncomingLetter $letter)
    {
        return $user->can('incoming letters:view');
    }

    /**
     * Determine whether the user can create incoming letters.
     *
     * @param  \App\Models\User  $user
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
     * @param  \App\Models\User  $user
     * @param  \App\Models\IncomingLetter  $incomingLetter
     *
     * @return mixed
     */
    public function update(User $user, IncomingLetter $letter)
    {
        return $user->can('incoming letters:edit')
            && (int) $letter->creator_id === (int) $user->id;
    }

    /**
     * Determine whether the user can delete the incoming letter.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\IncomingLetter  $incomingLetter
     *
     * @return mixed
     */
    public function delete(User $user, IncomingLetter $letter)
    {
        return $user->can('incoming letters:delete')
            && (int) $letter->creator_id === (int) $user->id;
    }
}
