<?php

namespace App\Policies;

use App\Models\AdvisoryMeeting;
use App\Models\Scholar;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdvisoryMeetingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @return mixed
     */
    public function viewAny()
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\AdvisoryMeeting  $meeting
     *
     * @return mixed
     */
    public function view($user, AdvisoryMeeting $meeting, Scholar $scholar)
    {
        return ((int) $scholar->id === (int) $user->id || (int) $scholar->currentSupervisor->id === (int) $user->id)
            && (int) $meeting->scholar->id === (int) $scholar->id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @return mixed
     */
    public function create($user, Scholar $scholar)
    {
        return get_class($user) === User::class
            && (int) $scholar->currentSupervisor->id === (int) $user->id;
    }
}
