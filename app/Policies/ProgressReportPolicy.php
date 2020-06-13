<?php

namespace App\Policies;

use App\Models\ProgressReport;
use App\Models\Scholar;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProgressReportPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function viewAny($user)
    {
        if (get_class($user) === Scholar::class
            || $user->isSupervisor()
            || $user->can('scholar progress reports:view')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProgressReport  $progressReport
     *
     * @return mixed
     */
    public function view($user, ProgressReport $progressReport)
    {
        if (get_class($user) === Scholar::class && $progressReport->scholar_id == $user->id) {
            return true;
        }

        if (get_class($user) === User::class && $user->isSupervisor() && $user->scholars->contains($progressReport->scholar->id)) {
            return true;
        }

        if (get_class($user) === User::class && $user->can('scholar progress reports:view')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function create($user)
    {
        return get_class($user) === User::class &&
               $user->can('scholar progress reports:add');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProgressReport  $progressReport
     *
     * @return mixed
     */
    public function delete($user, ProgressReport $progressReport)
    {
        return get_class($user) === User::class &&
               $user->can('scholar progress reports:delete');
    }
}
