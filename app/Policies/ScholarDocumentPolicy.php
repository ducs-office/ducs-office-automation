<?php

namespace App\Policies;

use App\Models\Scholar;
use App\Models\ScholarDocument;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScholarDocumentPolicy
{
    use HandlesAuthorization;

    public function viewAny()
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param   $user
     * @param  \App\Models\ScholarDocument  $document
     *
     * @return mixed
     */
    public function view($user, ScholarDocument $document)
    {
        if (get_class($user) === Scholar::class && (int) $document->scholar_id === $user->id) {
            return true;
        }

        if (get_class($user) === User::class && $user->can('scholar documents:view')) {
            return true;
        }

        if (get_class($user) === User::class && $user->isSupervisor() && $user->scholars->contains((int) $document->scholar_id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param $user
     *
     * @return mixed
     */
    public function create($user)
    {
        return ((
            get_class($user) === Scholar::class
        )
            || (
                get_class($user) === User::class
                && $user->can('scholar documents:add')
            )
        );
    }

    /**
     * Determine whether the user can delete models.
     *
     * @param $user
     *
     * @return mixed
     */
    public function delete($user, ScholarDocument $document)
    {
        if ($document->scholar->prePhdSeminar) {
            return false;
        }

        if (get_class($user) === Scholar::class && (int) $document->scholar_id === $user->id) {
            return true;
        }

        if (get_class($user) === User::class && $user->can('scholar documents:delete')) {
            return true;
        }

        return false;
    }
}
