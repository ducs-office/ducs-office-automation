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
        return $user instanceof Scholar ||
               (method_exists($user, 'isSupervisor') && $user->isSupervisor()) ||
               ($user instanceof User && $user->can('scholar progress reports:view'));
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
        if ($user instanceof Scholar && $progressReport->scholar_id == $user->id) {
            return true;
        }

        if ($user instanceof User && $user->isSupervisor() && $user->scholars->contains($progressReport->scholar->id)) {
            return true;
        }

        if ($user instanceof User && $user->can('scholar progress reports:view')) {
            return true;
        }
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
        return $user instanceof User &&
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
        return $user instanceof User &&
               $user->can('scholar progress reports:delete');
    }
}
