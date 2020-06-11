<?php

namespace App\Policies;

use App\Models\Leave;
use App\Models\Scholar;
use App\Models\User;
use App\Types\LeaveStatus;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeavePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the leave.
     *
     * @param mixed $user
     * @param  \App\Models\Leave  $leave
     *
     * @return mixed
     */
    public function view($user, Leave $leave, Scholar $scholar)
    {
        return (int) $leave->scholar_id === (int) $scholar->id
            && ((get_class($user) === Scholar::class
            && (int) $user->id === (int) $scholar->id)
            || (get_class($user) === User::class
            && ((int) $user->id === (int) $scholar->currentSupervisor->id)
            || $user->can('leaves:respond')));
    }

    /**
     * Determine whether the user can create a leave. (apply for leave)
     *
     * @param mixed $user
     * @param  \App\Models\Leave  $leave
     *
     * @return mixed
     */
    public function create($user, Scholar $scholar)
    {
        return get_class($user) === Scholar::class
            && (int) $user->id === (int) $scholar->id;
    }

    /**
     * Determine whether the user can recommend a leave.
     *
     * @param mixed $user
     * @param  \App\Models\Leave  $leave
     *
     * @return mixed
     */
    public function recommend($user, Leave $leave, Scholar $scholar)
    {
        return $leave->status == LeaveStatus::APPLIED
            && (int) $leave->scholar->id === (int) $scholar->id
            && get_class($user) === User::class
            && (int) $user->id === (int) $scholar->currentSupervisor->id;
    }

    /**
     * Determine whether the user can respond to a leave.
     *
     * @param mixed $user
     * @param  \App\Models\Leave  $leave
     *
     * @return mixed
     */
    public function respond($user, Leave $leave)
    {
        return get_class($user) === User::class
            && $user->can('leaves:respond')
            && in_array($leave->status, [LeaveStatus::APPLIED, LeaveStatus::RECOMMENDED]);
    }

    /**
     * Determine whether the user can extend a leave.
     *
     * @param \App\Models\Scholar $scholar
     * @param  \App\Models\Leave  $leave
     *
      * @return mixed
     */
    public function extend($user, Leave $leave, Scholar $scholar)
    {
        return get_class($user) === Scholar::class
            && (int) $scholar->id === (int) $leave->scholar_id
            && $leave->isApproved()
            && $leave->extensions->every->isApproved();
    }
}
