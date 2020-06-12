<?php

namespace App\Policies;

use App\Models\Scholar;
use App\Models\User;
use App\Types\RequestStatus;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScholarPolicy
{
    use HandlesAuthorization;

    public function viewAny($user)
    {
        if (method_exists($user, 'isSupervisor') && $user->isSupervisor()) {
            return true;
        }

        return $user->can('scholars:view');
    }

    public function view($user)
    {
        if (method_exists($user, 'isSupervisor') && $user->isSupervisor()) {
            return true;
        }
        return ($user->can('scholars:view'));
    }

    public function create($user)
    {
        return $user->can('scholars:create');
    }

    public function update($user)
    {
        return $user->can('scholars:edit');
    }

    public function delete($user)
    {
        return $user->can('scholars:delete');
    }

    /**
     * Determine whether the user can update scholar profile.
     *
     * @param $user
     * @param Scholar $scholar
     *
     * @return mixed
     */
    public function updateProfile($user, Scholar $scholar)
    {
        return get_class($user) === Scholar::class
            && $user->id === $scholar->id;
    }

    /**
     * Determine whether the user can manage(update/replace) a scholar's advisory committee
     *
     * @param $user
     * @param Scholar $scholar
     *
     * @return mixed
     */
    public function manageAdvisoryCommittee($user, Scholar $scholar)
    {
        return get_class($user) === User::class &&
            $user->isSupervisor() &&
            (int) $user->id === (int) $scholar->currentSupervisor->id;
    }
}
