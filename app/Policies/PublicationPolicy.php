<?php

namespace App\Policies;

use App\Publication;
use App\Scholar;
use App\User;
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
        return (Auth::guard('scholars')->check() || (
            method_exists($user, 'isSupervisor') &&
            $user->isSupervisor()
        ));
    }

    /**
     * Determine whether the user can create publications.
     *
     * @param  \App\User  $user
     *
     * @return mixed
     */
    public function create($user)
    {
        return (Auth::guard('scholars')->check() || (
            method_exists($user, 'isSupervisor') &&
            $user->isSupervisor()
        ));
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
        if (Auth::guard('scholars')->check()) {
            return (int) $publication->mainAuthor->id === (int) $user->id;
        } elseif (method_exists($user, 'isSupervisor') && $user->isSupervisor()) {
            return (int) $publication->mainAuthor->id === (int) $user->supervisorProfile->id;
        } else {
            return false;
        }
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
        if (Auth::guard('scholars')->check()) {
            return (int) $publication->mainAuthor->id === (int) $user->id;
        } elseif (method_exists($user, 'isSupervisor') && $user->isSupervisor()) {
            return (int) $publication->mainAuthor->id === (int) $user->supervisorProfile->id;
        } else {
            return false;
        }
    }
}
