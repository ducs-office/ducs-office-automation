<?php

namespace App\Policies;

use App\Models\Scholar;
use App\Models\ScholarDocument;
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
        return ((get_class($user) === Scholar::class
            && (int) $user->id === (int) auth()->id()
            && (int) $document->scholar_id == (int) auth()->id())
            || $user->can('scholars:view'));
    }
}
