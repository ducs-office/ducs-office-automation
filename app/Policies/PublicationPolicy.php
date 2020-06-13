<?php

namespace App\Policies;

use App\Models\Publication;
use App\Models\Scholar;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class PublicationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any publications.
     *
     * @param  \App\User  $user
     *
     * @return mixed
     */
    public function viewAny($user)
    {
        return get_class($user) === Scholar::class ||
            (get_class($user) === User::class && $user->isSupervisor());
    }

    /**
     * Determine whether the user can view a publications.
     *
     * @param  \App\User  $user
     *
     * @return mixed
     */
    public function view($user, Publication $publication)
    {
        if (get_class($user) === Scholar::class && $user->id === (int) $publication->author_id) {
            return true;
        }

        if (get_class($user) === User::class && $user->isSupervisor()) {
            if ($user->scholars->contains($publication->author_id) && $publication->author_type === Scholar::class) {
                return true;
            }
        }
    }

    /**
     * Determine whether the user can create publications.
     *
     * @param  \App\User  $user
     *
     * @return mixed
     */
    public function create($AuthUser, $user)
    {
        return get_class($AuthUser) === get_class($user)
            && (int) $AuthUser->id === (int) $user->id;
    }

    /**
     * Determine whether the user can update the publication.
     *
     * @param  \App\User  $user
     * @param  \App\Publication  $publication
     *
     * @return mixed
     */
    public function update($user, Publication $publication)
    {
        return  $publication->author_type === get_class($user)
            && (int) $publication->author_id === (int) $user->id;
    }

    /**
     * Determine whether the user can delete the publication.
     *
     * @param  \App\User  $user
     * @param  \App\Publication  $publication
     *
     * @return mixed
     */
    public function delete($user, Publication $publication)
    {
        return  $publication->author_type === get_class($user)
            && (int) $publication->author_id === (int) $user->id;
    }
}
