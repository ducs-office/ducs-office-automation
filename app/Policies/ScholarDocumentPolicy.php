<?php

namespace App\Policies;

use App\Models\Scholar;
use App\Models\ScholarDocument;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScholarDocumentPolicy
{
    use HandlesAuthorization;

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
        return ((
            get_class($user) === Scholar::class
                && (int) $document->scholar_id === $user->id
        )
            || (
                get_class($user) === User::class
                && $user->can('scholar documents:view')
            )
            || (
                method_exists($user, 'isSupervisor')
                && $user->isSupervisor()
                && $user->supervisorProfile->scholars->contains((int) $document->scholar_id)
            )
        );
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
        return ((
            get_class($user) === Scholar::class
                && (int) $document->scholar_id === $user->id
        )
            || (
                get_class($user) === User::class
                && $user->can('scholar documents:delete')
            )
        );
    }
}
