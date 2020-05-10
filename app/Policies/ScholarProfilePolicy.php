<?php

namespace App\Policies;

use App\Models\Scholar;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScholarProfilePolicy
{
    use HandlesAuthorization;

    public function addCoursework($user, Scholar $scholar)
    {
        return method_exists($user, 'isSupervisor') &&
            $user->isSupervisor() &&
            $user->supervisorProfile->scholars->contains($scholar);
    }

    public function applyLeaves($user, Scholar $scholar)
    {
        return get_class($user) === Scholar::class
            && (int) $user->id === (int) $scholar->id;
    }

    public function markCourseworkCompleted($user, Scholar $scholar)
    {
        return $user instanceof User && $user->can('phd course work:mark completed');
    }

    public function addAdvisoryMeeting($user, Scholar $scholar)
    {
        return method_exists($user, 'isSupervisor') &&
            $user->isSupervisor() &&
            $user->supervisorProfile->scholars->contains($scholar);
    }

    public function manageAdvisoryCommittee($user, Scholar $scholar)
    {
        return method_exists($user, 'isSupervisor') &&
            $user->isSupervisor() &&
            $user->supervisorProfile->scholars->contains($scholar);
    }

    public function addOtherDocuments($user, Scholar $scholar)
    {
        return ($user instanceof User && $user->can('scholar other documents:add'));
    }
}
